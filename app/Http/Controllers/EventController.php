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
        $query = Event::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('event_start_date', 'desc')
            ->orderBy('start_time', 'desc');

        switch ($request->input('filter')) {
            case 'active':
                $query->where('is_active', true);
                break;
            case 'upcoming':
                $query->where('event_start_date', '>=', now()->toDateString());
                break;
            case 'required':
                $query->where('is_required', true);
                break;
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
        // Group counselors by user so multi-college counselors appear once
        $counselors = Counselor::with(['user', 'college'])->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                $first = $group->first();
                $first->all_ids       = $group->pluck('id')->toArray();
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

        $event = Event::create($data);

        // Attach colleges if not for all colleges
        if (!$request->for_all_colleges && $request->has('colleges')) {
            $event->colleges()->sync($request->colleges);
        }

        // Automatically register students if event is required
        if ($event->is_required) {
            $event->registerRequiredStudents();
        }

        // Sync to selected counselors' Google Calendars
        $counselorIds = collect($request->input('counselor_ids', []))
            ->flatMap(fn($v) => explode(',', $v))
            ->map('intval')->unique()->filter()->values()->toArray();
        if (!empty($counselorIds)) {
            $event->assignedCounselors()->sync($counselorIds);
            app(GoogleCalendarService::class)->syncEventToCounselors($event, $counselorIds);
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
        $newCounselorIds = collect($request->input('counselor_ids', []))
            ->flatMap(fn($v) => explode(',', $v))
            ->map('intval')->unique()->filter()->values()->toArray();

        // Capture previous counselors BEFORE sync so we know who to remove
        $previousCounselorIds = $event->assignedCounselors()->pluck('counselors.id')->toArray();

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

        // Remove from counselor's Google Calendar
        if ($event->google_calendar_event_id) {
            $counselor = Counselor::where('user_id', Auth::id())->first();
            if ($counselor && $counselor->google_calendar_id) {
                try {
                    app(GoogleCalendarService::class)->deleteEvent(
                        $event->google_calendar_event_id,
                        $counselor->google_calendar_id
                    );
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete event from Google Calendar', [
                        'event_id' => $event->id,
                        'error' => $e->getMessage(),
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

    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $colleges = College::all();
        $selectedColleges = $event->colleges->pluck('id')->toArray();
        $selectedCounselors = $event->assignedCounselors->pluck('id')->toArray();

        // Group counselors by user so multi-college counselors appear once
        $counselors = Counselor::with(['user', 'college'])->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                $first = $group->first();
                $first->all_ids       = $group->pluck('id')->toArray();
                $first->college_names = $group->pluck('college.name')->filter()->implode(', ');
                return $first;
            })->values();

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
}
