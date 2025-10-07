<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EventRegistration;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
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
    public function create()
    {
        return view('counselor.events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(EventRequest $request)
    {
        $event = Event::create(array_merge(
            $request->validated(),
            ['user_id' => Auth::id()]
        ));

        return redirect()->route('counselor.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        // The middleware ensures only counselors can access, but we still check ownership
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('counselor.events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        // Check event ownership
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $event->update($request->validated());

        return redirect()->route('counselor.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Check event ownership
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $event->delete();

        return redirect()->route('counselor.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Toggle event status (active/inactive)
     */
    public function toggleStatus(Event $event)
    {
        // Check event ownership
        if ($event->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $event->update([
            'is_active' => !$event->is_active
        ]);

        $status = $event->is_active ? 'activated' : 'deactivated';

        return redirect()->route('counselor.events.index')
            ->with('success', "Event {$status} successfully!");
    }
  public function showRegistrations(Event $event)
    {
        // Ensure the counselor can only view their own event registrations
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
    public function updateRegistrationStatus(Request $request, Event $event, EventRegistration $registration)
    {
        // Ensure the counselor can only update their own event registrations
        if ($event->user_id !== Auth::id() || $registration->event_id !== $event->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:registered,attended,cancelled'
        ]);

        $registration->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Registration status updated successfully!');
    }

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
                'Gender',
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
                    $user->gender ?? 'N/A',
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
