<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Counselor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            $query = Appointment::with(['counselor.user', 'counselor.college', 'referredCounselor.user', 'sessionNotes'])
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
            $appointments = Appointment::with(['student.user', 'counselor.user', 'referredCounselor.user', 'sessionNotes'])
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

    // Get counselors from the same college OR counselors who have received referrals from this student
    $counselors = Counselor::with('user', 'college')
        ->where(function($query) use ($student) {
            // Primary college assignment
            $query->where('college_id', $student->college_id);
        })
        ->orWhereHas('receivedReferrals', function($query) use ($student) {
            // Counselors who have received referrals for this student
            $query->where('student_id', $student->id)
                  ->where('status', 'referred');
        })
        ->get()
        ->unique('id'); // Remove duplicates

    return view('appointments.create', compact('counselors', 'student'));
}
/**
 * Get referred counselors for a student (cross-college allowed)
 */
/**
 * Get referred counselors for a student (cross-college allowed)
 */
public function getReferredCounselors(Request $request)
{
    $student = Student::where('user_id', Auth::id())->first();

    if (!$student) {
        return response()->json([]);
    }

    // Get counselors who have been referred to in past appointments for this student
    $referredCounselors = Counselor::with('user', 'college')
        ->whereHas('receivedReferrals', function($query) use ($student) {
            $query->where('student_id', $student->id)
                  ->where('status', 'referred');
        })
        ->get()
        ->map(function($counselor) {
            return [
                'id' => $counselor->id,
                'name' => $counselor->user->first_name . ' ' . $counselor->user->last_name,
                'position' => $counselor->position,
                'college' => $counselor->college->name ?? 'N/A',
                'college_id' => $counselor->college_id,
                'is_referred' => true,
                'display_text' => $counselor->user->first_name . ' ' . $counselor->user->last_name .
                                 ' - ' . $counselor->position .
                                 ' (' . ($counselor->college->name ?? 'N/A') . ')' .
                                 ' - Previously Referred'
            ];
        });

    return response()->json($referredCounselors);
}

    /**
     * Get available slots for follow-up appointments (counselor only)
     */
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

    // Get booked appointments for that date - INCLUDE completed, pending, approved, and referred statuses
    $bookedAppointments = Appointment::where('counselor_id', $counselor->id)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', ['pending', 'approved', 'completed', 'referred']) // Added completed and referred
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

            // Check if this slot is booked (including completed and referred appointments)
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

    // Get booked appointments for that date - INCLUDE completed, pending, approved, and referred statuses
    $bookedAppointments = Appointment::where('counselor_id', $counselor->id)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', ['pending', 'approved', 'completed', 'referred']) // Added completed and referred
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

            // Check if this slot is booked (including completed and referred appointments)
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

    // Check if slot is still available - INCLUDE completed and referred statuses
    $existingAppointment = Appointment::where('counselor_id', $request->counselor_id)
        ->where('appointment_date', $request->appointment_date)
        ->where('start_time', $request->start_time)
        ->whereIn('status', ['pending', 'approved', 'completed', 'referred']) // Added completed and referred
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

public function updateStatus(Request $request, Appointment $appointment)
{
    $request->validate([
        'status' => 'required|in:approved,rejected,cancelled,completed,referred', // removed 'transferred'
        'notes' => 'nullable|string|max:500',
        'transfer_to_counselor' => 'sometimes|boolean',
        'referred_to_counselor_id' => 'nullable|exists:counselors,id',
        'referral_reason' => 'nullable|string|max:500'
    ]);

    $oldStatus = $appointment->status;

    $updateData = [
        'status' => $request->status,
        'notes' => $request->notes ?: $appointment->notes
    ];

    // Handle referral/transfer (both use 'referred' status)
    if ($request->status === 'referred' && $request->referred_to_counselor_id) {
        $updateData['referred_to_counselor_id'] = $request->referred_to_counselor_id;
        $updateData['referral_reason'] = $request->referral_reason;
        $updateData['original_counselor_id'] = $appointment->counselor_id;
    }

    $appointment->update($updateData);

    // Status messages - only 'referred' no 'transferred'
    $statusMessages = [
        'approved' => 'Appointment approved successfully.',
        'rejected' => $request->has('transfer_to_counselor')
            ? 'Appointment referred. Student can book with another counselor.'
            : 'Appointment rejected.',
        'cancelled' => 'Appointment cancelled.',
        'completed' => 'Appointment marked as completed.',
        'referred' => 'Appointment referred to another counselor successfully.'
    ];

    // Safe lookup with fallback
    $message = $statusMessages[$request->status] ?? 'Status updated successfully.';

    return redirect()->back()->with('success', $message);
}
    /**
     * Get available counselors for referral
     */
    public function getAvailableCounselors(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'current_counselor_id' => 'required|exists:counselors,id'
        ]);

        $student = Student::findOrFail($request->student_id);

        // Get counselors from the same college, excluding the current counselor
        $counselors = Counselor::with('user', 'college')
            ->where('college_id', $student->college_id)
            ->where('id', '!=', $request->current_counselor_id)
            ->whereHas('user', function($query) {
                $query->where('role', 'counselor');
            })
            ->get()
            ->map(function($counselor) {
                return [
                    'id' => $counselor->id,
                    'name' => $counselor->user->first_name . ' ' . $counselor->user->last_name,
                    'position' => $counselor->position,
                    'college' => $counselor->college->name ?? 'N/A'
                ];
            });

        return response()->json($counselors);
    }

    /**
     * Get available counselors for transfer - ALLOW DIFFERENT COLLEGES
     */
    public function getAvailableCounselorsForTransfer(Appointment $appointment)
    {
        Log::info('=== getAvailableCounselorsForTransfer START ===', [
            'appointment_id' => $appointment->id,
            'auth_user_id' => Auth::id(),
            'auth_user_role' => Auth::user()->role ?? 'unknown'
        ]);

        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                Log::warning('User not authenticated');
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            // Check if counselor owns this appointment
            $counselor = Counselor::where('user_id', Auth::id())->first();

            Log::info('Current counselor check', [
                'counselor_found' => !is_null($counselor),
                'counselor_id' => $counselor->id ?? null,
                'appointment_counselor_id' => $appointment->counselor_id,
                'match' => $counselor && $appointment->counselor_id === $counselor->id
            ]);

            if (!$counselor || $appointment->counselor_id !== $counselor->id) {
                Log::warning('Counselor authorization failed');
                return response()->json(['error' => 'You can only transfer your own appointments.'], 403);
            }

            // Get student info for context (but don't restrict by college)
            $student = $appointment->student;
            $studentCollegeId = $student->college_id ?? null;

            Log::info('Student info (for context)', [
                'student_id' => $student->id ?? null,
                'student_college_id' => $studentCollegeId,
                'student_college_name' => $student->college->name ?? 'No college'
            ]);

            // Get ALL available counselors (excluding current counselor) - NO COLLEGE RESTRICTION
            $availableCounselors = Counselor::with(['user', 'college'])
                ->where('id', '!=', $counselor->id) // Exclude current counselor
                ->whereHas('user', function($query) {
                    $query->where('role', 'counselor')
                          ->where('id', '!=', Auth::id()); // Also exclude current user
                })
                ->get();

            Log::info('All available counselors (across all colleges)', [
                'total_count' => $availableCounselors->count(),
                'current_counselor_excluded' => $counselor->id,
                'available_counselors' => $availableCounselors->map(function($c) use ($studentCollegeId) {
                    return [
                        'id' => $c->id,
                        'name' => $c->user->first_name . ' ' . $c->user->last_name,
                        'position' => $c->position,
                        'college' => $c->college->name ?? 'N/A',
                        'college_id' => $c->college_id,
                        'same_college_as_student' => $c->college_id == $studentCollegeId
                    ];
                })->toArray()
            ]);

            $counselors = $availableCounselors->map(function($counselor) use ($studentCollegeId) {
                $isSameCollege = $counselor->college_id == $studentCollegeId;
                $collegeInfo = $counselor->college->name ?? 'N/A';

                return [
                    'id' => $counselor->id,
                    'name' => $counselor->user->first_name . ' ' . $counselor->user->last_name,
                    'position' => $counselor->position,
                    'college' => $collegeInfo,
                    'college_id' => $counselor->college_id,
                    'same_college' => $isSameCollege,
                    'display_text' => $counselor->user->first_name . ' ' . $counselor->user->last_name .
                                     ' - ' . $counselor->position .
                                     ' (' . $collegeInfo . ')' .
                                     ($isSameCollege ? ' - Same College' : ' - Different College')
                ];
            });

            Log::info('Successfully loaded counselors for transfer', [
                'counselors_count' => $counselors->count(),
                'same_college_count' => $counselors->where('same_college', true)->count(),
                'different_college_count' => $counselors->where('same_college', false)->count()
            ]);

            Log::info('=== getAvailableCounselorsForTransfer END ===');

            return response()->json($counselors);

        } catch (\Exception $e) {
            Log::error('Error in getAvailableCounselorsForTransfer', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'appointment_id' => $appointment->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Failed to load available counselors: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Transfer appointment to another counselor - SET STATUS TO 'transferred'
     */
/**
 * Transfer appointment to another counselor - SET STATUS TO 'referred'
 */
/**
 * Transfer appointment to another counselor - SET STATUS TO 'referred'
 */
// In App\Http\Controllers\AppointmentController.php

/**
 * Transfer appointment to another counselor - SET STATUS TO 'referred'
 */
public function transfer(Request $request, Appointment $appointment)
{
    Log::info('=== TRANSFER APPOINTMENT START ===', [
        'appointment_id' => $appointment->id,
        'new_counselor_id' => $request->new_counselor_id,
        'auth_user_id' => Auth::id(),
        'all_request_data' => $request->all()
    ]);

    $request->validate([
        'new_counselor_id' => 'required|exists:counselors,id',
        'transfer_reason' => 'required|string|max:500'
    ]);

    try {
        // Check if the counselor owns this appointment
        $counselor = Counselor::where('user_id', Auth::id())->first();

        if (!$counselor) {
            Log::error('Counselor not found for user', ['user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Counselor profile not found.');
        }

        if ($appointment->counselor_id !== $counselor->id) {
            Log::warning('Counselor authorization failed', [
                'counselor_id' => $counselor->id,
                'appointment_counselor_id' => $appointment->counselor_id
            ]);
            return redirect()->back()->with('error', 'You can only transfer your own appointments.');
        }

        // Get the new counselor
        $newCounselor = Counselor::with(['user', 'college'])->findOrFail($request->new_counselor_id);

        // Store counselor names for display
        $oldCounselorName = $counselor->user->first_name . ' ' . $counselor->user->last_name;
        $newCounselorName = $newCounselor->user->first_name . ' ' . $newCounselor->user->last_name;

        // Prepare update data - Use 'referred' status consistently
        $updateData = [
            'counselor_id' => $request->new_counselor_id,
            'status' => 'referred',
            'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') .
                      "REFERRED by {$oldCounselorName} to {$newCounselorName} on " . now()->toDateTimeString() .
                      "\nReason: " . $request->transfer_reason,
            'original_counselor_id' => $counselor->id,
            'referred_to_counselor_id' => $request->new_counselor_id,
            'referral_reason' => $request->transfer_reason
        ];

        // Update the appointment
        $appointment->update($updateData);

        Log::info('Appointment referred successfully', [
            'appointment_id' => $appointment->id,
            'new_counselor_id' => $request->new_counselor_id,
            'from_counselor' => $oldCounselorName,
            'to_counselor' => $newCounselorName
        ]);

        $successMessage = "Appointment referred successfully to {$newCounselorName}. The appointment is now marked as referred.";

        return redirect()->route('counselor.appointments')
            ->with('success', $successMessage);

    } catch (\Exception $e) {
        Log::error('Error referring appointment', [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'appointment_id' => $appointment->id,
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'Failed to refer appointment: ' . $e->getMessage());
    }
}

// In App\Models\Appointment.php

}
