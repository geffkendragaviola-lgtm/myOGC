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

        // Check if student is already registered
        if ($event->isRegisteredByStudent($student)) {
            return redirect()->back()->with('error', 'You are already registered for this event.');
        }

        // Check if there are available slots
        if (!$event->hasAvailableSlots()) {
            return redirect()->back()->with('error', 'This event is full. No more slots available.');
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
     * Cancel event registration
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

        // Cancel the registration
        $registration->cancel();

        return redirect()->back()->with('success', 'Registration cancelled successfully.');
    }

    /**
     * Show student's registered events
     */
    public function myRegistrations()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return view('student.events.my-registrations', [
                'registrations' => collect(),
                'student' => null
            ]);
        }

        $registrations = EventRegistration::with(['event', 'event.user'])
            ->where('student_id', $student->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        return view('student.events.my-registrations', compact('registrations', 'student'));
    }

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

        return view('student.events.event-details', compact('event', 'student', 'isRegistered', 'registration'));
    }
}
