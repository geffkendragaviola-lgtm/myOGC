<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use App\Models\Counselor;
use Spatie\GoogleCalendar\Event;

class GoogleCalendarService
{
    private function setAuthProfileForCalendar(string $calendarId): void
    {
        $counselor = Counselor::where('google_calendar_id', $calendarId)->first();
        if (!$counselor) {
            return;
        }

        $tokenPath = storage_path('app/google-calendar/tokens/' . $counselor->user_id . '.json');
        if (!File::exists($tokenPath)) {
            Log::warning("Google Calendar token not found for counselor user_id {$counselor->user_id} — skipping calendar check.");
            throw new \RuntimeException("Google Calendar token not found for counselor user_id {$counselor->user_id}");
        }

        config([
            'google-calendar.default_auth_profile' => 'oauth',
            'google-calendar.auth_profiles.oauth.token_json' => $tokenPath,
        ]);

        // Disable SSL verification for development
        if (env('APP_ENV') === 'local') {
            putenv('GUZZLE_VERIFY=false');
        }
    }

    public function getBusyIntervalsForDate(string $calendarId, Carbon $date): array
    {
        $this->setAuthProfileForCalendar($calendarId);

        // When the calendar ID is a Gmail address, the authenticated user's primary
        // calendar must be accessed as 'primary' — using the email directly returns 404
        $apiCalendarId = filter_var($calendarId, FILTER_VALIDATE_EMAIL) ? 'primary' : $calendarId;

        $timezone = config('app.timezone', 'UTC');
        $startOfDay = $date->copy()->timezone($timezone)->startOfDay();
        $endOfDay = $date->copy()->timezone($timezone)->endOfDay();

        try {
            $events = Event::get(
                $startOfDay,
                $endOfDay,
                [
                    'singleEvents' => true,
                    'timeZone' => $timezone,
                ],
                $apiCalendarId
            );
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // If SSL error in development, log and return empty array
            if (env('APP_ENV') === 'local' && strpos($e->getMessage(), 'cURL error 77') !== false) {
                Log::warning('SSL certificate error in development - returning empty busy intervals', [
                    'calendar_id' => $calendarId,
                    'error' => $e->getMessage(),
                ]);
                return [];
            }
            throw $e;
        }

        return $events->map(function (Event $event) use ($date, $timezone) {
            $start = $event->startDateTime ?? $event->startDate;
            $end = $event->endDateTime ?? $event->endDate;

            if (!$start || !$end) {
                return null;
            }

            if ($event->isAllDayEvent()) {
                $start = $date->copy()->timezone($timezone)->startOfDay();
                $end = $date->copy()->timezone($timezone)->endOfDay();
            } else {
                $start = $this->normalizeEventDateTime($start, $timezone);
                $end = $this->normalizeEventDateTime($end, $timezone);
            }

            return [
                'id' => $event->id,
                'start' => $start->copy(),
                'end' => $end->copy(),
                'title' => $event->summary ?: 'Busy',
                'description' => $event->description,
                'location' => $event->location,
            ];
        })->filter()->values()->all();
    }

    private function normalizeEventDateTime($value, string $timezone): ?Carbon
    {
        if (!$value) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value->copy()->setTimezone($timezone);
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->setTimezone($timezone);
        }

        return Carbon::parse($value, $timezone)->setTimezone($timezone);
    }

    public function isSlotAvailable(
        string $calendarId,
        Carbon $slotStart,
        Carbon $slotEnd,
        ?string $excludeEventId = null
    ): bool
    {
        $this->setAuthProfileForCalendar($calendarId);

        $date = $slotStart->copy()->startOfDay();
        $busyIntervals = $this->getBusyIntervalsForDate($calendarId, $date);

        foreach ($busyIntervals as $interval) {
            if ($excludeEventId && ($interval['id'] ?? null) === $excludeEventId) {
                continue;
            }
            if ($slotStart < $interval['end'] && $slotEnd > $interval['start']) {
                return false;
            }
        }

        return true;
    }

    public function createAppointmentEvent(array $eventData, string $calendarId): Event
    {
        $this->setAuthProfileForCalendar($calendarId);
        $apiCalendarId = filter_var($calendarId, FILTER_VALIDATE_EMAIL) ? 'primary' : $calendarId;

        try {
            return Event::create($eventData, $apiCalendarId);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // If SSL error in development, log and create mock event
            if (env('APP_ENV') === 'local' && strpos($e->getMessage(), 'cURL error 77') !== false) {
                Log::warning('SSL certificate error in development - creating mock event', [
                    'calendar_id' => $calendarId,
                    'error' => $e->getMessage(),
                ]);
                // Return a mock event object to allow the appointment to be created
                $mockEvent = new Event();
                $mockEvent->id = 'mock-' . uniqid();
                return $mockEvent;
            }
            throw $e;
        }
    }

    public function syncEventToCounselors(\App\Models\Event $event, array $counselorIds): void
    {
        $timezone = config('app.timezone', 'UTC');

        Log::info('GoogleCalendar syncEventToCounselors - requested counselor IDs', [
            'event_id' => $event->id,
            'counselorIds' => array_values($counselorIds),
        ]);

        // Build one calendar entry per day so only the specific time window is blocked
        // each day — matching appointment booking logic and allowing other slots to remain open.
        $days = [];
        $current = $event->event_start_date->copy();
        $endDate  = $event->event_end_date->copy();
        while ($current->lte($endDate)) {
            $days[] = $current->format('Y-m-d');
            $current->addDay();
        }

        $selectedCounselors = \App\Models\Counselor::whereIn('id', $counselorIds)->get();
        $counselors = $selectedCounselors
            ->groupBy('user_id')
            ->map(function ($group) {
                return $group->firstWhere(fn ($c) => !empty($c->google_calendar_id)) ?? $group->first();
            })
            ->filter(fn ($c) => !empty($c?->google_calendar_id))
            ->values();

        Log::info('GoogleCalendar syncEventToCounselors - counselors loaded', [
            'event_id' => $event->id,
            'loaded_counselor_ids' => $counselors->pluck('id')->values()->all(),
            'loaded_calendar_ids' => $counselors->pluck('google_calendar_id')->values()->all(),
        ]);

        foreach ($counselors as $counselor) {
            try {
                // Remove any previously synced calendar events for this counselor/event pair
                $pivot = $event->assignedCounselors()->where('counselor_id', $counselor->id)->first();
                $existingIds = $pivot?->pivot?->google_calendar_event_id
                    ? json_decode($pivot->pivot->google_calendar_event_id, true) ?? [$pivot->pivot->google_calendar_event_id]
                    : [];

                foreach ($existingIds as $oldId) {
                    try {
                        $this->deleteEvent($oldId, $counselor->google_calendar_id);
                    } catch (\Throwable) {}
                }

                // Create one timed event per day
                $newIds = [];
                foreach ($days as $day) {
                    $startDateTime = \Carbon\Carbon::parse($day . ' ' . $event->start_time, $timezone);
                    $endDateTime   = \Carbon\Carbon::parse($day . ' ' . $event->end_time, $timezone);

                    $calendarData = [
                        'name'          => $event->title,
                        'description'   => $event->description,
                        'startDateTime' => $startDateTime,
                        'endDateTime'   => $endDateTime,
                        'location'      => $event->location,
                    ];

                    $calendarEvent = $this->createCounselorEvent($calendarData, $counselor->google_calendar_id);
                    $newIds[] = $calendarEvent->id;
                }

                // Store all day-event IDs as JSON in the pivot
                $event->assignedCounselors()->updateExistingPivot($counselor->id, [
                    'google_calendar_event_id' => json_encode($newIds),
                ]);
            } catch (\Throwable $e) {
                Log::warning('Failed to sync event to counselor calendar', [
                    'event_id'     => $event->id,
                    'counselor_id' => $counselor->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }
    }

    public function removeEventFromCounselors(\App\Models\Event $event, array $counselorIds): void
    {
        $selectedCounselors = \App\Models\Counselor::whereIn('id', $counselorIds)->get();
        $counselors = $selectedCounselors
            ->groupBy('user_id')
            ->map(function ($group) {
                return $group->firstWhere(fn ($c) => !empty($c->google_calendar_id)) ?? $group->first();
            })
            ->filter(fn ($c) => !empty($c?->google_calendar_id))
            ->values();

        foreach ($counselors as $counselor) {
            try {
                $pivot = $event->assignedCounselors()->where('counselor_id', $counselor->id)->first();
                $raw = $pivot?->pivot?->google_calendar_event_id;
                if (!$raw) continue;

                $ids = json_decode($raw, true) ?? [$raw];
                foreach ($ids as $calendarEventId) {
                    $this->deleteEvent($calendarEventId, $counselor->google_calendar_id);
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to remove event from counselor calendar', [
                    'event_id'     => $event->id,
                    'counselor_id' => $counselor->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }
    }

    public function createCounselorEvent(array $eventData, string $calendarId): Event
    {
        $this->setAuthProfileForCalendar($calendarId);
        $apiCalendarId = filter_var($calendarId, FILTER_VALIDATE_EMAIL) ? 'primary' : $calendarId;

        try {
            return Event::create($eventData, $apiCalendarId);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if (env('APP_ENV') === 'local' && strpos($e->getMessage(), 'cURL error 77') !== false) {
                Log::warning('SSL certificate error in development - creating mock calendar event', [
                    'calendar_id' => $calendarId,
                    'error' => $e->getMessage(),
                ]);
                $mockEvent = new Event();
                $mockEvent->id = 'mock-' . uniqid();
                return $mockEvent;
            }
            throw $e;
        }
    }

    public function updateCounselorEvent(string $eventId, array $eventData, string $calendarId): void
    {
        try {
            $this->setAuthProfileForCalendar($calendarId);
            $apiCalendarId = filter_var($calendarId, FILTER_VALIDATE_EMAIL) ? 'primary' : $calendarId;
            $calendarEvent = Event::find($eventId, $apiCalendarId);
            if (!$calendarEvent) {
                return;
            }
            foreach ($eventData as $key => $value) {
                $calendarEvent->$key = $value;
            }
            $calendarEvent->save($apiCalendarId);
        } catch (\Throwable $exception) {
            Log::warning('Failed to update Google Calendar event', [
                'event_id' => $eventId,
                'calendar_id' => $calendarId,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    public function deleteEvent(?string $eventId, string $calendarId): void
    {
        if (!$eventId) {
            return;
        }

        try {
            $this->setAuthProfileForCalendar($calendarId);
            $apiCalendarId = filter_var($calendarId, FILTER_VALIDATE_EMAIL) ? 'primary' : $calendarId;
            $event = Event::find($eventId, $apiCalendarId);
            $event->delete();
        } catch (\Throwable $exception) {
            Log::warning('Failed to delete Google Calendar event', [
                'event_id' => $eventId,
                'calendar_id' => $calendarId,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
