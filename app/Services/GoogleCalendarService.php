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
            throw new \RuntimeException("Google Calendar token not found for counselor user_id {$counselor->user_id}");
        }

        config([
            'google-calendar.default_auth_profile' => 'oauth',
            'google-calendar.auth_profiles.oauth.token_json' => $tokenPath,
        ]);
    }

    public function getBusyIntervalsForDate(string $calendarId, Carbon $date): array
    {
        $this->setAuthProfileForCalendar($calendarId);

        $timezone = config('app.timezone', 'UTC');
        $startOfDay = $date->copy()->timezone($timezone)->startOfDay();
        $endOfDay = $date->copy()->timezone($timezone)->endOfDay();

        $events = Event::get(
            $startOfDay,
            $endOfDay,
            [
                'singleEvents' => true,
                'timeZone' => $timezone,
            ],
            $calendarId
        );

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

        return Event::create($eventData, $calendarId);
    }

    public function deleteEvent(?string $eventId, string $calendarId): void
    {
        if (!$eventId) {
            return;
        }

        try {
            $this->setAuthProfileForCalendar($calendarId);
            $event = Event::find($eventId, $calendarId);
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
