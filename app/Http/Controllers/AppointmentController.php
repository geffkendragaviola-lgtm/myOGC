<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Counselor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
public function index(Request $request)
{
    $user = Auth::user();

    if ($user->role === 'student') {
        $student = Student::where('user_id', $user->id)->first();
        $query = Appointment::with('counselor.user', 'counselor.college', 'sessionNotes')
            ->where('student_id', $student->id);

        // Date filter
        if ($request->has('search_date') && $request->search_date) {
            $query->where('appointment_date', $request->search_date);
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Assignment filter
        if ($request->has('has_assignment') && $request->has_assignment) {
            if ($request->has_assignment === 'yes') {
                $query->whereHas('sessionNotes', function($q) {
                    $q->whereNotNull('follow_up_actions')->where('follow_up_actions', '!=', '');
                })->where('status', 'completed');
            } elseif ($request->has_assignment === 'no') {
                $query->where(function($q) {
                    $q->where('status', '!=', 'completed')
                      ->orWhereDoesntHave('sessionNotes')
                      ->orWhereHas('sessionNotes', function($q) {
                          $q->whereNull('follow_up_actions')->orWhere('follow_up_actions', '');
                      });
                });
            }
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
    } else {
        // Counselor/admin view logic here
        $appointments = Appointment::with('student.user', 'counselor.user', 'sessionNotes')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
    }

    return view('appointments.index', compact('appointments'));
}
public function create()
{
    $student = Student::with('college')->where('user_id', Auth::id())->first();

    if (!$student) {
        return redirect()->back()->with('error', 'Student profile not found.');
    }

    // Get counselors from the same college OR counselors who have this college as secondary assignment
    $counselors = Counselor::with('user', 'college')
        ->where(function($query) use ($student) {
            // Primary college assignment
            $query->where('college_id', $student->college_id);
        })
        ->get();

    return view('appointments.create', compact('counselors', 'student'));
}
/**
 * Get available slots for follow-up appointments (counselor only)
 */
public function getFollowupAvailableSlots(Request $request)
{
    $request->validate([
        'counselor_id' => 'required|exists:counselors,id',
        'date' => 'required|date|after:yesterday'
    ]);

    $counselor = Counselor::findOrFail($request->counselor_id);
    $date = Carbon::parse($request->date);
    $dayName = strtolower($date->englishDayOfWeek);

    // Get counselor's availability for that day
    $availability = $counselor->getAvailability();
    $dayAvailability = $availability[$dayName] ?? [];

    if (empty($dayAvailability)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'No working hours for this day'
        ]);
    }

    // Get booked appointments for that date (EXCLUDE cancelled appointments)
    $bookedAppointments = Appointment::where('counselor_id', $counselor->id)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', ['pending', 'approved'])
        ->get(['start_time', 'end_time', 'status']);

    // Generate all possible time slots
    $allSlots = [];
    $slotDuration = 60; // 1 hour in minutes

    foreach ($dayAvailability as $timeRange) {
        list($start, $end) = explode('-', $timeRange);

        $currentTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        while ($currentTime->addMinutes($slotDuration)->lte($endTime)) {
            $slotStart = $currentTime->copy()->subMinutes($slotDuration);
            $slotEnd = $currentTime->copy();

            $slotStartTime = $slotStart->format('H:i');
            $slotEndTime = $slotEnd->format('H:i');

            // Check if this slot is booked
            $isBooked = $bookedAppointments->contains(function ($appointment) use ($slotStartTime, $slotEndTime) {
                $appointmentStart = Carbon::parse($appointment->start_time)->format('H:i');
                $appointmentEnd = Carbon::parse($appointment->end_time)->format('H:i');

                return $slotStartTime === $appointmentStart &&
                       $slotEndTime === $slotEndTime;
            });

            $slotData = [
                'start' => $slotStartTime,
                'end' => $slotEndTime,
                'display' => $slotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                'status' => $isBooked ? 'booked' : 'available'
            ];

            $allSlots[] = $slotData;
        }
    }

    // Separate available and booked slots
    $availableSlots = array_values(array_filter($allSlots, function($slot) {
        return $slot['status'] === 'available';
    }));

    $bookedSlots = array_values(array_filter($allSlots, function($slot) {
        return $slot['status'] === 'booked';
    }));

    return response()->json([
        'available_slots' => $availableSlots,
        'booked_slots' => $bookedSlots
    ]);
}
public function getAvailableSlots(Request $request)
{
    $request->validate([
        'counselor_id' => 'required|exists:counselors,id',
        'date' => 'required|date|after:yesterday'
    ]);

    $counselor = Counselor::findOrFail($request->counselor_id);
    $date = Carbon::parse($request->date);
    $dayName = strtolower($date->englishDayOfWeek);

    // Get counselor's availability for that day
    $availability = $counselor->getAvailability();
    $dayAvailability = $availability[$dayName] ?? [];

    if (empty($dayAvailability)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'No working hours for this day'
        ]);
    }

    // Get booked appointments for that date (EXCLUDE cancelled appointments)
    $bookedAppointments = Appointment::where('counselor_id', $counselor->id)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', ['pending', 'approved']) // Only pending and approved count as booked
        ->get(['start_time', 'end_time', 'status']);

    // Generate all possible time slots
    $allSlots = [];
    $slotDuration = 60; // 1 hour in minutes

    foreach ($dayAvailability as $timeRange) {
        list($start, $end) = explode('-', $timeRange);

        $currentTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        while ($currentTime->addMinutes($slotDuration)->lte($endTime)) {
            $slotStart = $currentTime->copy()->subMinutes($slotDuration);
            $slotEnd = $currentTime->copy();

            $slotStartTime = $slotStart->format('H:i');
            $slotEndTime = $slotEnd->format('H:i');

            // Check if this slot is booked (excluding cancelled appointments)
            $isBooked = $bookedAppointments->contains(function ($appointment) use ($slotStartTime, $slotEndTime) {
                $appointmentStart = Carbon::parse($appointment->start_time)->format('H:i');
                $appointmentEnd = Carbon::parse($appointment->end_time)->format('H:i');

                return $slotStartTime === $appointmentStart &&
                       $slotEndTime === $appointmentEnd;
            });

            $slotData = [
                'start' => $slotStartTime,
                'end' => $slotEndTime,
                'display' => $slotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                'status' => $isBooked ? 'booked' : 'available'
            ];

            $allSlots[] = $slotData;
        }
    }

    // Separate available and booked slots
    $availableSlots = array_values(array_filter($allSlots, function($slot) {
        return $slot['status'] === 'available';
    }));

    $bookedSlots = array_values(array_filter($allSlots, function($slot) {
        return $slot['status'] === 'booked';
    }));

    return response()->json([
        'available_slots' => $availableSlots,
        'booked_slots' => $bookedSlots
    ]);
}
    public function store(Request $request)
    {
        $request->validate([
            'counselor_id' => 'required|exists:counselors,id',
            'appointment_date' => 'required|date|after:yesterday',
            'start_time' => 'required|date_format:H:i',
            'concern' => 'required|string|max:500'
        ]);

        $student = Student::where('user_id', Auth::id())->first();

        // Calculate end time (1 hour duration)
        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addHour();

        // Check if slot is still available
        $existingAppointment = Appointment::where('counselor_id', $request->counselor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('start_time', $request->start_time)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existingAppointment) {
            return redirect()->back()->with('error', 'This time slot has been booked by another student. Please choose another time.');
        }

        $appointment = Appointment::create([
            'student_id' => $student->id,
            'counselor_id' => $request->counselor_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTime->format('H:i'),
            'concern' => $request->concern,
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment booked successfully! It is now pending approval.');
    }


  public function cancel(Appointment $appointment)
    {
        // Check if the student owns this appointment
        $student = Student::where('user_id', Auth::id())->first();

        if ($appointment->student_id !== $student->id) {
            return redirect()->back()->with('error', 'You can only cancel your own appointments.');
        }

        // Only allow cancellation of pending or approved appointments
        if (!in_array($appointment->status, ['pending', 'approved'])) {
            return redirect()->back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update([
            'status' => 'cancelled',
            'notes' => $appointment->notes . "\nCancelled by student on " . now()->toDateTimeString()
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully. The time slot is now available for booking.');
    }

// In your AppointmentController updateStatus method
public function updateStatus(Request $request, Appointment $appointment)
{
    $request->validate([
        'status' => 'required|in:approved,rejected,cancelled,completed',
        'notes' => 'nullable|string|max:500',
        'transfer_to_counselor' => 'sometimes|boolean'
    ]);

    $oldStatus = $appointment->status;
    $appointment->update([
        'status' => $request->status,
        'notes' => $request->notes ?: $appointment->notes
    ]);

    // Handle transfer logic
    if ($request->has('transfer_to_counselor')) {
        // You can add notification logic here to inform the student
        // to book with another counselor
        // Example: Send email notification or create a notification record
    }

    $statusMessages = [
        'approved' => 'Appointment approved successfully.',
        'rejected' => $request->has('transfer_to_counselor')
            ? 'Appointment transferred. Student can book with another counselor.'
            : 'Appointment rejected.',
        'cancelled' => 'Appointment cancelled.',
        'completed' => 'Appointment marked as completed.'
    ];

    return redirect()->back()->with('success', $statusMessages[$request->status]);
}
    // Add this method to your AppointmentController
public function transfer(Request $request, Appointment $appointment)
{
    $request->validate([
        'new_counselor_id' => 'required|exists:counselors,id',
        'transfer_reason' => 'required|string|max:500'
    ]);

    // Check if the counselor owns this appointment
    $counselorIds = Counselor::where('user_id', Auth::id())->pluck('id');
    if (!$counselorIds->contains($appointment->counselor_id)) {
        return redirect()->back()->with('error', 'You can only transfer your own appointments.');
    }

    // Get the new counselor
    $newCounselor = Counselor::findOrFail($request->new_counselor_id);

    // Store old counselor info
    $oldCounselorName = $appointment->counselor->user->first_name . ' ' . $appointment->counselor->user->last_name;
    $newCounselorName = $newCounselor->user->first_name . ' ' . $newCounselor->user->last_name;

    // Update the appointment
    $appointment->update([
        'counselor_id' => $request->new_counselor_id,
        'status' => 'pending', // Reset status to pending for new counselor
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') .
                  "TRANSFERRED from {$oldCounselorName} to {$newCounselorName} on " . now()->toDateTimeString() .
                  "\nReason: " . $request->transfer_reason
    ]);

    return redirect()->back()->with('success', "Appointment transferred successfully to {$newCounselorName}. The student has been notified.");
}

}
