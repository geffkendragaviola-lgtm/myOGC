<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Student;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    /**
     * Register student for an event
     */
    public function register(Event $event)
    {
        // Get the student profile of the logged-in user
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found. Please complete your student profile.');
        }

        // Check if event is active and registration is open
        if (!$event->is_registration_open) {
            return redirect()->back()->with('error', 'Registration for this event is not available.');
        }

        // Check if student is already registered with active status
        $existingRegistration = $event->getStudentRegistration($student);
        if ($existingRegistration && $existingRegistration->status === 'registered') {
            return redirect()->back()->with('error', 'You are already registered for this event.');
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
                'cancelled_at' => null,
                'counsellor_override' => false,
                'override_reason' => null
            ]);

            return redirect()->back()->with('success', 'Successfully re-registered for the event!');
        }

        // Check if there are available slots
        if (!$event->hasAvailableSlots()) {
            return redirect()->back()->with('error', 'This event is full. No more slots available.');
        }

        // For required events, students are automatically registered
        if ($event->isRequiredForStudent($student)) {
            return redirect()->back()->with('info', 'You are automatically registered for this required event.');
        }

        // Create registration using EventRegistration model
        EventRegistration::create([
            'event_id' => $event->id,
            'student_id' => $student->id,
            'registered_at' => now(),
            'status' => 'registered'
        ]);

        return redirect()->back()->with('success', 'Successfully registered for the event!');
    }

    /**
     * Cancel event registration with 24-hour cutoff
     */
    public function cancelRegistration(Event $event)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        // Get the registration
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

        // Cancel the registration
        $registration->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        return redirect()->back()->with('success', 'Registration cancelled successfully. You can re-register if slots are available.');
    }

    /**
     * Show student's registered events
     */


    /**
     * Show all available events for registration
     */
    public function availableEvents()
    {
        $student = Auth::user()->student;
        $events = Event::with(['user', 'registrations'])
            ->upcoming()
            ->active()
            ->orderBy('event_start_date')
            ->orderBy('start_time')
            ->get();

        return view('student.events.available-events', compact('events', 'student'));
    }

    /**
     * Show event details and registration status
     */
    public function eventDetails(Event $event)
    {
        $student = Auth::user()->student;
        $isRegistered = $student ? $event->isRegisteredByStudent($student) : false;
        $registration = $student ? $event->getStudentRegistration($student) : null;

        // Check if cancellation is allowed
        $isCancellationAllowed = $student ? $event->isCancellationAllowed() : false;
        $cancellationCutoffTime = $event->getCancellationCutoffTime();

        // Check if re-registration is possible
        $canReRegister = $student && $registration && $registration->status === 'cancelled'
            ? $event->canReRegister($student)
            : false;

        return view('student.events.event-details', compact(
            'event',
            'student',
            'isRegistered',
            'registration',
            'isCancellationAllowed',
            'cancellationCutoffTime',
            'canReRegister'
        ));
    }

    /**
     * Re-register for a previously cancelled event
     */
    public function reRegister(Event $event)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        // Get the previous cancelled registration
        $previousRegistration = EventRegistration::where('event_id', $event->id)
            ->where('student_id', $student->id)
            ->where('status', 'cancelled')
            ->first();

        if (!$previousRegistration) {
            return redirect()->back()->with('error', 'No previous cancelled registration found for this event.');
        }

        // Check if re-registration is allowed
        if (!$event->canReRegister($student)) {
            return redirect()->back()->with('error', 'Cannot re-register for this event. Event may be full or unavailable.');
        }

        // Re-activate the cancelled registration
        $previousRegistration->update([
            'status' => 'registered',
            'registered_at' => now(),
            'cancelled_at' => null,
            'counsellor_override' => false,
            'override_reason' => null
        ]);

        return redirect()->back()->with('success', 'Successfully re-registered for the event!');
    }
    public function myRegistrations()
{
    $student = Auth::user()->student;

    if (!$student) {
        return view('student.events.my-registrations', [
            'registrations' => collect(),
            'student' => null
        ]);
    }

    $registrations = EventRegistration::with(['event', 'event.user', 'event.colleges'])
        ->where('student_id', $student->id)
        ->orderBy('registered_at', 'desc')
        ->get();

    return view('student.events.my-registrations', compact('registrations', 'student'));
}
}
