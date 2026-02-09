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
 public function index()
    {
        $events = Event::with('user')
            ->where('user_id', Auth::id())
            ->orderBy('event_start_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

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
        return view('counselor.events.create', compact('colleges'));
    }

    public function store(EventRequest $request)
    {
        $data = array_merge(
            $request->validated(),
            ['user_id' => Auth::id()]
        );

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

        return redirect()->route('counselor.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
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

        return view('counselor.events.edit', compact('event', 'colleges', 'selectedColleges'));
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
}
