<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\College;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleCalendarService;
use App\Models\Counselor;
use App\Models\Appointment;
use App\Mail\EventScheduleConflict;
use App\Notifications\EventScheduleConflictNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */

    /**
 * Counselor manual re-registration for cancelled students
 */
public function reRegisterStudent(Request $request, Event $event, EventRegistration $registration)
{
    if ($event->user_id !== Auth::id() || $registration->event_id !== $event->id) {
        abort(403, 'Unauthorized action.');
    }

    // Check if re-registration is possible
    if (!$event->canReRegister($registration->student)) {
        return redirect()->back()->with('error', 'Cannot re-register this student. Event may be full or unavailable.');
    }

    $registration->update([
        'status' => 'registered',
        'registered_at' => now(),
        'cancelled_at' => null,
        'counsellor_override' => true,
        'override_reason' => $request->override_reason ?? 'Counselor manual re-registration',
        'override_by' => Auth::id(),
        'override_at' => now()
    ]);

    Log::info('Student re-registered by counselor', [
        'counselor_id' => Auth::id(),
        'registration_id' => $registration->id,
        'student_id' => $registration->student_id,
        'event_id' => $event->id
    ]);

    return redirect()->back()->with('success', 'Student re-registered successfully!');
}

/**
 * Counselor manual registration status override
 */
public function updateRegistrationStatus(Request $request, Event $event, EventRegistration $registration)
{
    // Ensure the counselor can only update their own event registrations
    if ($event->user_id !== Auth::id() || $registration->event_id !== $event->id) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'status' => 'required|in:registered,attended,cancelled'
    ]);

    $previousStatus = $registration->status;

    $registration->update([
        'status' => $request->status,
        'counsellor_override' => true,
        'override_by' => Auth::id(),
        'override_at' => now()
    ]);

    // If changing from cancelled to registered, update timestamps
    if ($previousStatus === 'cancelled' && $request->status === 'registered') {
        $registration->update([
            'registered_at' => now(),
            'cancelled_at' => null
        ]);
    }

    // If marking as attended and it was registered, keep the original registration time
    if ($request->status === 'attended' && $previousStatus === 'registered') {
        // Keep the original registered_at time
    }

    // Log the override action
    Log::info('Registration status overridden by counselor', [
        'counselor_id' => Auth::id(),
        'registration_id' => $registration->id,
        'previous_status' => $previousStatus,
        'new_status' => $request->status,
        'event_id' => $event->id
    ]);

    return redirect()->back()->with('success', 'Registration status updated successfully!');
}
 public function index(Request $request)
    {
        $query = Event::with(['user', 'colleges'])
            ->where('user_id', Auth::id())
            ->orderBy('event_start_date', 'desc')
            ->orderBy('start_time', 'desc');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            match ($request->status) {
                'active'   => $query->where('is_active', true),
                'inactive' => $query->where('is_active', false),
                'upcoming' => $query->where('event_start_date', '>=', now()->toDateString()),
                default    => null,
            };
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $events = $query->get();

        return view('counselor.events.index', compact('events'));
    }
    /**
     * Show the form for creating a new event.
     */


    /**
     * Store a newly created event in storage.
     */
  public function create()
    {
        $colleges = College::all();
        // Group counselors by user_id, keep only the first (primary) record per person
        $counselors = Counselor::with(['user', 'college'])->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                $first = $group->first();
                $first->college_names = $group->pluck('college.name')->filter()->implode(', ');
                return $first;
            })->values();
        return view('counselor.events.create', compact('colleges', 'counselors'));
    }

    public function store(EventRequest $request)
    {
        $data = array_merge(
            $request->validated(),
            ['user_id' => Auth::id()]
        );

        // Normalize year_levels: null means all year levels
        if (empty($data['year_levels'])) {
            $data['year_levels'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $data['image'] = basename($imagePath);
        }

        // Sync to selected counselors' Google Calendars
        // Extract counselor_ids before creating the event (not a model attribute)
        $rawCounselorIds = $request->input('counselor_ids', []);
        unset($data['counselor_ids']);

        Log::info('Event create - raw counselor_ids received', [
            'rawCounselorIds' => $rawCounselorIds,
            'request_user_id' => Auth::id(),
        ]);

        $event = Event::create($data);

        // Attach colleges if not for all colleges
        if (!$request->for_all_colleges && $request->has('colleges')) {
            $event->colleges()->sync($request->colleges);
        }

        // Automatically register students if event is required
        if ($event->is_required) {
            $event->registerRequiredStudents();
        }

        $counselorIds = collect($rawCounselorIds)
            ->map(fn($id) => (int) $id)->unique()->filter()->values()->toArray();

        Log::info('Event create - normalized counselor_ids', [
            'counselorIds' => $counselorIds,
            'event_id' => $event->id,
        ]);
        if (!empty($counselorIds)) {
            $event->assignedCounselors()->sync($counselorIds);
            app(GoogleCalendarService::class)->syncEventToCounselors($event, $counselorIds);
            $this->notifyCounselorsOfEventConflicts($event, $counselorIds);
            $this->notifyAssignedCounselors($event, $counselorIds);
        }

        return redirect()->route('counselor.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function update(EventRequest $request, Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $wasRequired = $event->is_required;
        $previousColleges = $event->colleges->pluck('id')->toArray();

        $data = $request->validated();

        // Remove counselor_ids — not a model attribute, handled separately
        $rawCounselorIds = $data['counselor_ids'] ?? [];
        unset($data['counselor_ids']);

        // Normalize year_levels: null means all year levels
        if (empty($data['year_levels'])) {
            $data['year_levels'] = null;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image) {
                Storage::disk('public')->delete('events/' . $event->image);
            }

            $imagePath = $request->file('image')->store('events', 'public');
            $data['image'] = basename($imagePath);
        }

        $event->update($data);

        // Update colleges
        if ($request->for_all_colleges) {
            $event->colleges()->detach();
        } else {
            $event->colleges()->sync($request->colleges ?? []);
        }

        // Handle automatic registration for required events
        if ($event->is_required && $event->shouldAutoRegister()) {
            // If event became required or colleges changed, update registrations
            if (!$wasRequired || $previousColleges != ($request->colleges ?? [])) {
                $event->registerRequiredStudents();
            }
        }

        // Sync counselors' Google Calendars
        $newCounselorIds = collect($rawCounselorIds)
            ->map(fn($id) => (int) $id)->unique()->filter()->values()->toArray();

        // Capture previous counselors BEFORE sync so we know who to remove
        $previousCounselorIds = $event->assignedCounselors()->pluck('counselors.id')->toArray();

        // Split into newly added vs already assigned
        $addedCounselorIds   = array_values(array_diff($newCounselorIds, $previousCounselorIds));
        $keptCounselorIds    = array_values(array_intersect($newCounselorIds, $previousCounselorIds));

        // Remove calendar entries for deselected counselors first
        $removedIds = array_diff($previousCounselorIds, $newCounselorIds);
        if (!empty($removedIds)) {
            app(GoogleCalendarService::class)->removeEventFromCounselors($event, $removedIds);
        }

        // Sync pivot table
        $event->assignedCounselors()->sync($newCounselorIds);

        // Refresh event so updated fields (title, dates, times) are used when syncing
        $event->refresh();

        // Re-sync all assigned counselors' calendar entries with updated event data
        if (!empty($newCounselorIds)) {
            app(GoogleCalendarService::class)->syncEventToCounselors($event, $newCounselorIds);
            $this->notifyCounselorsOfEventConflicts($event, $newCounselorIds);
        }

        // Notify newly added counselors as a fresh assignment
        if (!empty($addedCounselorIds)) {
            $this->notifyAssignedCounselors($event, $addedCounselorIds, isUpdate: false);
        }

        // Notify already-assigned counselors that the event was updated
        if (!empty($keptCounselorIds)) {
            $this->notifyAssignedCounselors($event, $keptCounselorIds, isUpdate: true);
        }

        // Sync the event creator's own Google Calendar entry
        $this->syncEventToCalendar($event, update: true);

        return redirect()->route('counselor.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Remove from all assigned counselors' Google Calendars
        $assignedCounselorIds = $event->assignedCounselors()->pluck('counselors.id')->toArray();
        if (!empty($assignedCounselorIds)) {
            try {
                app(GoogleCalendarService::class)->removeEventFromCounselors($event, $assignedCounselorIds);
            } catch (\Throwable $e) {
                Log::warning('Failed to remove event from assigned counselors\' calendars', [
                    'event_id' => $event->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        // Remove from event creator's Google Calendar
        if ($event->google_calendar_event_id) {
            $counselor = Counselor::where('user_id', Auth::id())->first();
            if ($counselor && $counselor->google_calendar_id) {
                try {
                    $ids = json_decode($event->google_calendar_event_id, true) ?? [$event->google_calendar_event_id];
                    foreach ((array) $ids as $calId) {
                        app(GoogleCalendarService::class)->deleteEvent($calId, $counselor->google_calendar_id);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete event from creator\'s Google Calendar', [
                        'event_id' => $event->id,
                        'error'    => $e->getMessage(),
                    ]);
                }
            }
        }

        // Delete event image if exists
        if ($event->image) {
            Storage::disk('public')->delete('events/' . $event->image);
        }

        $event->delete();

        return redirect()->route('counselor.events.index')
            ->with('success', 'Event deleted successfully!');
    }


    public function toggleStatus(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $event->update([
            'is_active' => !$event->is_active
        ]);

        // Automatically register students when activating a required event
        if ($event->is_active && $event->is_required && $event->shouldAutoRegister()) {
            $event->registerRequiredStudents();
        }

        $status = $event->is_active ? 'activated' : 'deactivated';

        return redirect()->route('counselor.events.index')
            ->with('success', "Event {$status} successfully!");
    }
    public function togglePin(Event $event)
    {
        $event->update(['is_pinned' => !$event->is_pinned]);

        return response()->json([
            'success' => true,
            'is_pinned' => $event->is_pinned,
            'message' => $event->is_pinned ? 'Event pinned.' : 'Event unpinned.',
        ]);
    }

    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $colleges = College::all();
        $selectedColleges = $event->colleges->pluck('id')->toArray();

        // Group counselors by user_id, keep only the first (primary) record per person
        $counselors = Counselor::with(['user', 'college'])->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                $first = $group->first();
                $first->college_names = $group->pluck('college.name')->filter()->implode(', ');
                return $first;
            })->values();

        // Normalize selectedCounselors to primary IDs only
        $assignedUserIds = $event->assignedCounselors->pluck('user_id')->unique()->toArray();
        $selectedCounselors = $counselors
            ->filter(fn($c) => in_array($c->user_id, $assignedUserIds))
            ->pluck('id')->toArray();

        return view('counselor.events.edit', compact('event', 'colleges', 'selectedColleges', 'counselors', 'selectedCounselors'));
    }



    /**
     * Remove the specified event from storage.
     */


    /**
     * Toggle event status (active/inactive)
     */
   public function availableEvents()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Please complete your student profile first.');
        }

        $events = Event::with(['user', 'colleges', 'registrations' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->upcoming()
            ->active()
            ->forCollege($student->college_id)
            ->forYearLevel($student->year_level)
            ->orderBy('event_start_date')
            ->orderBy('start_time')
            ->get();

        return view('student.events.available', compact('events', 'student'));
    }

    /**
     * Register for an event
     */
    public function register(Event $event)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Please complete your student profile first.');
        }

        // Check if event is available for student's college
        if (!$event->isAvailableForStudent($student)) {
            return redirect()->back()->with('error', 'This event is not available for your college.');
        }

        // Check if already registered with active status
        $existingRegistration = $event->getStudentRegistration($student);
        if ($existingRegistration && $existingRegistration->status === 'registered') {
            return redirect()->back()->with('info', 'You are already registered for this event.');
        }

        // Check for re-registration scenario
        if ($existingRegistration && $existingRegistration->status === 'cancelled') {
            if (!$event->canReRegister($student)) {
                return redirect()->back()->with('error', 'Cannot re-register for this event. Event may be full or unavailable.');
            }

            // Update existing cancelled registration to registered
            $existingRegistration->update([
                'status' => 'registered',
                'registered_at' => now(),
                'cancelled_at' => null
            ]);

            return redirect()->back()->with('success', 'Successfully re-registered for the event!');
        }

        // Check if event has available slots
        if (!$event->hasAvailableSlots()) {
            return redirect()->back()->with('error', 'This event is already full.');
        }

        // For required events, students are automatically registered
        if ($event->isRequiredForStudent($student)) {
            return redirect()->back()->with('info', 'You are automatically registered for this required event.');
        }

        // Create new registration
        EventRegistration::create([
            'event_id' => $event->id,
            'student_id' => $student->id,
            'registered_at' => now(),
            'status' => 'registered'
        ]);

        return redirect()->back()->with('success', 'Successfully registered for the event!');
    }

    /**
     * Cancel registration with 24-hour cutoff
     */
    public function cancel(Event $event)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Please complete your student profile first.');
        }

        $registration = $event->getStudentRegistration($student);

        if (!$registration) {
            return redirect()->back()->with('error', 'You are not registered for this event.');
        }

        // Prevent cancellation of required events
        if ($event->isRequiredForStudent($student)) {
            return redirect()->back()->with('error', 'This is a required event and cannot be cancelled. Please contact your counselor if you have concerns.');
        }

        // Check 24-hour cancellation cutoff
        if (!$event->isCancellationAllowed()) {
            $cutoffTime = $event->getCancellationCutoffTime();
            return redirect()->back()->with('error', "Cancellation is no longer allowed. The cutoff time was {$cutoffTime}. Please contact your counselor for assistance.");
        }

        // Update registration status to cancelled
        $registration->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return redirect()->back()->with('success', 'Registration cancelled successfully. You can re-register if slots are available.');
    }

    /**
     * Counselor manual registration status override
     */
  

    public function showRegistrations(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $registrations = EventRegistration::with(['student.user', 'student.college'])
            ->where('event_id', $event->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        $registrationStats = [
            'total' => $registrations->count(),
            'registered' => $registrations->where('status', 'registered')->count(),
            'attended' => $registrations->where('status', 'attended')->count(),
            'cancelled' => $registrations->where('status', 'cancelled')->count(),
        ];

        return view('counselor.events.registrations', compact('event', 'registrations', 'registrationStats'));
    }

    /**
     * Update registration status (mark as attended, etc.)
     */


    /**
     * Export registrations to CSV
     */
    public function exportRegistrations(Event $event)
    {
        // Ensure the counselor can only export their own event registrations
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $registrations = EventRegistration::with(['student.user', 'student.college'])
            ->where('event_id', $event->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        $fileName = 'event-registrations-' . $event->id . '-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($registrations, $event) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'Event: ' . $event->title,
                'Date: ' . $event->date_range,
                'Location: ' . $event->location,
                ''
            ]);

            // Column headers
            fputcsv($file, [
                'Student ID',
                'First Name',
                'Middle Name',
                'Last Name',
                'Age',
                'Sex',
                'Phone Number',
                'Email',
                'College',
                'Year Level',
                'Registration Date',
                'Status'
            ]);

            // Data rows
            foreach ($registrations as $registration) {
                $student = $registration->student;
                $user = $student->user;

                fputcsv($file, [
                    $student->student_id ?? 'N/A',
                    $user->first_name,
                    $user->middle_name ?? '',
                    $user->last_name,
                    $user->age ?? 'N/A',
                    $user->sex ?? 'N/A',
                    $user->phone_number ?? 'N/A',
                    $user->email,
                    $student->college->name ?? 'N/A',
                    $student->year_level ?? 'N/A',
                    $registration->registered_at->format('M j, Y g:i A'),
                    ucfirst($registration->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function syncEventToCalendar(\App\Models\Event $event, bool $update = false): void
    {
        $counselor = Counselor::where('user_id', Auth::id())->first();
        if (!$counselor || !$counselor->google_calendar_id) {
            return;
        }

        $timezone = config('app.timezone', 'UTC');
        $service  = app(GoogleCalendarService::class);

        // Build one entry per day (same logic as syncEventToCounselors)
        $days = [];
        $current = $event->event_start_date->copy();
        while ($current->lte($event->event_end_date)) {
            $days[] = $current->format('Y-m-d');
            $current->addDay();
        }

        try {
            // On update: delete all previously stored per-day IDs then recreate
            if ($update && $event->google_calendar_event_id) {
                $oldIds = json_decode($event->google_calendar_event_id, true) ?? [$event->google_calendar_event_id];
                foreach ($oldIds as $oldId) {
                    try { $service->deleteEvent($oldId, $counselor->google_calendar_id); } catch (\Throwable) {}
                }
            }

            $newIds = [];
            foreach ($days as $day) {
                $calendarData = [
                    'name'          => $event->title,
                    'description'   => $event->description,
                    'startDateTime' => Carbon::parse($day . ' ' . $event->start_time, $timezone),
                    'endDateTime'   => Carbon::parse($day . ' ' . $event->end_time, $timezone),
                    'location'      => $event->location,
                ];
                $calendarEvent = $service->createCounselorEvent($calendarData, $counselor->google_calendar_id);
                $newIds[] = $calendarEvent->id;
            }

            $event->updateQuietly(['google_calendar_event_id' => json_encode($newIds)]);
        } catch (\Throwable $e) {
            Log::warning('Failed to sync event to Google Calendar', [
                'event_id' => $event->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check for schedule conflicts and notify counselors and affected students
     */
    private function notifyCounselorsOfEventConflicts(Event $event, array $counselorIds): void
    {
        $timezone = config('app.timezone', 'UTC');

        // Build all event days
        $eventDays = [];
        $current = $event->event_start_date->copy();
        while ($current->lte($event->event_end_date)) {
            $eventDays[] = $current->format('Y-m-d');
            $current->addDay();
        }

        $eventStartTime = Carbon::parse($event->start_time, $timezone);
        $eventEndTime = Carbon::parse($event->end_time, $timezone);

        // Group by user_id to avoid duplicate notifications for multi-college counselors
        $counselors = Counselor::with('user')->whereIn('id', $counselorIds)->get()
            ->groupBy('user_id')
            ->map(fn($group) => $group->first())
            ->values();

        $googleCalendarService = app(GoogleCalendarService::class);

        foreach ($counselors as $counselor) {
            try {
                $counselorAssignmentIds = Counselor::where('user_id', $counselor->user_id)->pluck('id')->toArray();

                // Check appointment conflicts in DB
                $conflictingAppointments = Appointment::with(['student.user'])
                    ->whereIn('counselor_id', $counselorAssignmentIds)
                    ->whereIn('appointment_date', $eventDays)
                    ->whereIn('status', ['pending', 'approved', 'completed', 'rescheduled', 'reschedule_rejected'])
                    ->get()
                    ->filter(function ($appointment) use ($eventStartTime, $eventEndTime, $timezone) {
                        $apptStart = Carbon::parse($appointment->start_time, $timezone);
                        $apptEnd   = Carbon::parse($appointment->end_time, $timezone);
                        return $apptStart->lt($eventEndTime) && $apptEnd->gt($eventStartTime);
                    });

                // Check Google Calendar conflicts
                $calendarConflicts = collect();
                if ($counselor->google_calendar_id) {
                    foreach ($eventDays as $day) {
                        try {
                            $dayDate = Carbon::parse($day, $timezone);
                            $busyIntervals = $googleCalendarService->getBusyIntervalsForDate(
                                $counselor->google_calendar_id,
                                $dayDate
                            );

                            foreach ($busyIntervals as $interval) {
                                // Build event start/end for this specific day
                                $eventDayStart = Carbon::parse($day . ' ' . $event->start_time, $timezone);
                                $eventDayEnd = Carbon::parse($day . ' ' . $event->end_time, $timezone);

                                // Check if this interval overlaps with the event
                                if ($interval['start']->lt($eventDayEnd) && $interval['end']->gt($eventDayStart)) {
                                    $calendarConflicts->push([
                                        'title' => $interval['title'],
                                        'date' => $day,
                                        'start' => $interval['start'],
                                        'end' => $interval['end'],
                                        'description' => $interval['description'] ?? null,
                                        'location' => $interval['location'] ?? null,
                                    ]);
                                }
                            }
                        } catch (\Throwable $e) {
                            Log::warning('Failed to check Google Calendar conflicts for day', [
                                'counselor_id' => $counselor->id,
                                'day' => $day,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }

                // Only notify if there are conflicts
                if ($conflictingAppointments->isEmpty() && $calendarConflicts->isEmpty()) {
                    continue;
                }

                // Notify counselor
                Mail::to($counselor->user->email)->send(
                    new EventScheduleConflict($event, $counselor, $conflictingAppointments, $calendarConflicts)
                );
                $totalConflicts = $conflictingAppointments->count() + $calendarConflicts->count();
                $counselor->user->notify(
                    new EventScheduleConflictNotification($event, $totalConflicts)
                );
            } catch (\Throwable $e) {
                Log::warning('Failed to send event conflict notification', [
                    'event_id'    => $event->id,
                    'counselor_id'=> $counselor->id,
                    'error'       => $e->getMessage(),
                ]);
            }
        }
    }

    private function notifyAssignedCounselors(Event $event, array $counselorIds, bool $isUpdate = false): void
    {
        // Group by user_id to avoid duplicate emails for multi-college counselors
        $counselors = Counselor::with('user')->whereIn('id', $counselorIds)->get()
            ->groupBy('user_id')
            ->map(fn($group) => $group->first())
            ->values();

        foreach ($counselors as $counselor) {
            try {
                Mail::to($counselor->user->email)->send(new \App\Mail\EventCounselorAssigned($event, $counselor, $isUpdate));
                $counselor->user->notify(new \App\Notifications\EventCounselorAssignedNotification($event, $isUpdate));
            } catch (\Throwable $e) {
                Log::warning('Failed to send event assignment notification to counselor', [
                    'event_id'     => $event->id,
                    'counselor_id' => $counselor->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }
    }
}
