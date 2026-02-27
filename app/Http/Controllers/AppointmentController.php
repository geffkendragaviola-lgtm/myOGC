<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Counselor;
use App\Models\CounselorScheduleOverride;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\GoogleCalendarService;

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

    $allowAllCounselors = Appointment::where('student_id', $student->id)
        ->where('status', 'completed')
        ->whereNotNull('original_counselor_id')
        ->exists();

    if ($allowAllCounselors) {
        $counselors = Counselor::with('user', 'college')
            ->whereHas('user', function($query) {
                $query->where('role', 'counselor');
            })
            ->get();
    } else {
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
    }

    return view('appointments.create', compact('counselors', 'student', 'allowAllCounselors'));
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
    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    $date = Carbon::parse($request->date);
    if ($this->isDateClosed($counselor, $date)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Schedule is closed for this date'
        ]);
    }

    if ($this->getCounselorBookingsForDate($counselorIds, $date) >= $this->getDailyBookingLimit($counselor)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Daily booking limit reached for this counselor'
        ]);
    }

    // Get counselor's availability for that day
    $dayAvailability = $this->getAvailabilityForDate($counselor, $date);

    if (empty($dayAvailability)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'No working hours for this day'
        ]);
    }

    // Get booked appointments for that date - INCLUDE completed, pending, approved, and referred statuses
    $bookedAppointments = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', $this->getBookingStatuses())
        ->get(['start_time', 'end_time', 'status']);

    $calendarIds = $this->getCounselorCalendarIds($counselorIds);
    if (empty($calendarIds)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Counselor calendar is not configured'
        ]);
    }

    $calendarBusyIntervals = [];
    try {
        $calendarBusyIntervals = $this->getCalendarBusyIntervalsForDate($calendarIds, $date);
    } catch (\Throwable $exception) {
        Log::error('Failed to load Google Calendar availability', [
            'counselor_id' => $counselor->id,
            'calendar_ids' => $calendarIds,
            'error' => $exception->getMessage(),
        ]);

        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Unable to load counselor calendar availability'
        ]);
    }

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
                       $slotEndTime === $appointmentEnd;
            });

            $timezone = $this->getCalendarTimezone();
            $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $slotStartTime, $timezone);
            $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $slotEndTime, $timezone);

            $isCalendarBusy = collect($calendarBusyIntervals)->contains(function ($interval) use ($slotStartDateTime, $slotEndDateTime) {
                return $slotStartDateTime < $interval['end'] && $slotEndDateTime > $interval['start'];
            });

            $slotData = [
                'start' => $slotStartTime,
                'end' => $slotEndTime,
                'display' => $slotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                'status' => ($isBooked || $isCalendarBusy) ? 'booked' : 'available'
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
    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    $date = Carbon::parse($request->date);
    if ($this->isDateClosed($counselor, $date)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Schedule is closed for this date'
        ]);
    }

    if ($this->getCounselorBookingsForDate($counselorIds, $date) >= $this->getDailyBookingLimit($counselor)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Daily booking limit reached for this counselor'
        ]);
    }

    // Get counselor's availability for that day
    $dayAvailability = $this->getAvailabilityForDate($counselor, $date);

    if (empty($dayAvailability)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'No working hours for this day'
        ]);
    }

    // Get booked appointments for that date - INCLUDE completed, pending, approved, and referred statuses
    $bookedAppointments = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', $this->getBookingStatuses())
        ->get(['start_time', 'end_time', 'status']);

    $calendarIds = $this->getCounselorCalendarIds($counselorIds);
    if (empty($calendarIds)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Counselor calendar is not configured'
        ]);
    }

    $calendarBusyIntervals = [];
    try {
        $calendarBusyIntervals = $this->getCalendarBusyIntervalsForDate($calendarIds, $date);
    } catch (\Throwable $exception) {
        Log::error('Failed to load Google Calendar availability', [
            'counselor_id' => $counselor->id,
            'calendar_ids' => $calendarIds,
            'error' => $exception->getMessage(),
        ]);

        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Unable to load counselor calendar availability'
        ]);
    }

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
                       $slotEndTime === $appointmentEnd;
            });

            $timezone = $this->getCalendarTimezone();
            $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $slotStartTime, $timezone);
            $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $slotEndTime, $timezone);

            $isCalendarBusy = collect($calendarBusyIntervals)->contains(function ($interval) use ($slotStartDateTime, $slotEndDateTime) {
                return $slotStartDateTime < $interval['end'] && $slotEndDateTime > $interval['start'];
            });

            $slotData = [
                'start' => $slotStartTime,
                'end' => $slotEndTime,
                'display' => $slotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                'status' => ($isBooked || $isCalendarBusy) ? 'booked' : 'available'
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

public function getAvailableDates(Request $request)
{
    $request->validate([
        'counselor_id' => 'required|exists:counselors,id',
        'month' => 'required|date_format:Y-m'
    ]);

    $counselor = Counselor::findOrFail($request->counselor_id);
    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    $monthStart = Carbon::createFromFormat('Y-m', $request->month)->startOfMonth();
    $monthEnd = $monthStart->copy()->endOfMonth();
    $today = Carbon::today();
    $allowToday = $request->boolean('allow_today', false);
    $minDate = $allowToday ? $today->copy() : $today->copy()->addDay();

    $availability = $counselor->getAvailability();
    $overrides = CounselorScheduleOverride::where('counselor_id', $counselor->id)
        ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
        ->get()
        ->keyBy(function ($override) {
            return Carbon::parse($override->date)->toDateString();
        });

    $bookedAppointments = Appointment::whereIn('counselor_id', $counselorIds)
        ->whereBetween('appointment_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
        ->whereIn('status', $this->getBookingStatuses())
        ->get(['appointment_date', 'start_time', 'end_time']);

    $appointmentsByDate = $bookedAppointments->groupBy('appointment_date');
    $calendarIds = $this->getCounselorCalendarIds($counselorIds);

    $results = [];
    $currentDate = $monthStart->copy();
    while ($currentDate->lte($monthEnd)) {
        $dateKey = $currentDate->toDateString();

        if ($currentDate->lt($minDate)) {
            $results[$dateKey] = false;
            $currentDate->addDay();
            continue;
        }

        $override = $overrides->get($dateKey);
        if ($override && $override->is_closed) {
            $results[$dateKey] = false;
            $currentDate->addDay();
            continue;
        }

        if ($this->getCounselorBookingsForDate($counselorIds, $currentDate) >= $this->getDailyBookingLimit($counselor)) {
            $results[$dateKey] = false;
            $currentDate->addDay();
            continue;
        }

        if ($override && !empty($override->time_slots)) {
            $dayAvailability = $override->time_slots;
        } else {
            $dayName = strtolower($currentDate->englishDayOfWeek);
            $dayAvailability = $availability[$dayName] ?? [];
        }

        if (empty($dayAvailability) || empty($calendarIds)) {
            $results[$dateKey] = false;
            $currentDate->addDay();
            continue;
        }

        $calendarBusyIntervals = [];
        try {
            $calendarBusyIntervals = $this->getCalendarBusyIntervalsForDate($calendarIds, $currentDate);
        } catch (\Throwable $exception) {
            Log::error('Failed to load Google Calendar availability', [
                'counselor_id' => $counselor->id,
                'calendar_ids' => $calendarIds,
                'error' => $exception->getMessage(),
            ]);
            $results[$dateKey] = false;
            $currentDate->addDay();
            continue;
        }

        $slotDuration = 60;
        $hasAvailableSlot = false;
        foreach ($dayAvailability as $timeRange) {
            [$start, $end] = explode('-', $timeRange);
            $currentTime = Carbon::parse($start);
            $endTime = Carbon::parse($end);

            while ($currentTime->addMinutes($slotDuration)->lte($endTime)) {
                $slotStart = $currentTime->copy()->subMinutes($slotDuration);
                $slotEnd = $currentTime->copy();
                $slotStartTime = $slotStart->format('H:i');
                $slotEndTime = $slotEnd->format('H:i');

                $isBooked = ($appointmentsByDate->get($dateKey) ?? collect())->contains(function ($appointment) use ($slotStartTime, $slotEndTime) {
                    $appointmentStart = Carbon::parse($appointment->start_time)->format('H:i');
                    $appointmentEnd = Carbon::parse($appointment->end_time)->format('H:i');
                    return $slotStartTime === $appointmentStart && $slotEndTime === $appointmentEnd;
                });

                $timezone = $this->getCalendarTimezone();
                $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $dateKey . ' ' . $slotStartTime, $timezone);
                $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $dateKey . ' ' . $slotEndTime, $timezone);

                $isCalendarBusy = collect($calendarBusyIntervals)->contains(function ($interval) use ($slotStartDateTime, $slotEndDateTime) {
                    return $slotStartDateTime < $interval['end'] && $slotEndDateTime > $interval['start'];
                });

                if (!$isBooked && !$isCalendarBusy) {
                    $hasAvailableSlot = true;
                    break 2;
                }
            }
        }

        $results[$dateKey] = $hasAvailableSlot;
        $currentDate->addDay();
    }

    return response()->json([
        'month' => $monthStart->format('Y-m'),
        'availability' => $results
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'counselor_id' => 'required|exists:counselors,id',
        'appointment_date' => 'required|date|after:yesterday',
        'start_time' => 'required|date_format:H:i',
        'booking_type' => 'required|in:Initial Interview,Counseling,Consultation',
        'concern' => 'required|string|max:500'
    ]);

    $student = Student::where('user_id', Auth::id())->first();
    $counselor = Counselor::findOrFail($request->counselor_id);
    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    $date = Carbon::parse($request->appointment_date);

    if (!$student) {
        return redirect()->back()->with('error', 'Student profile not found.');
    }

    if ($this->isDateClosed($counselor, $date)) {
        return redirect()->back()->with('error', 'This counselor is not available on the selected date.');
    }

    if ($this->getCounselorBookingsForDate($counselorIds, $date) >= $this->getDailyBookingLimit($counselor)) {
        return redirect()->back()->with('error', 'Daily booking limit reached for the selected counselor.');
    }

    // Calculate end time (1 hour duration)
    $startTime = Carbon::parse($request->start_time);
    $endTime = $startTime->copy()->addHour();
    $endTimeFormatted = $endTime->format('H:i');

    if (!$this->isSlotWithinAvailability($counselor, $date, $request->start_time, $endTimeFormatted)) {
        return redirect()->back()->with('error', 'Selected time is outside the counselor availability.');
    }

    // Check if slot is still available - INCLUDE completed and referred statuses
    $existingAppointment = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $request->appointment_date)
        ->where('start_time', $request->start_time)
        ->whereIn('status', $this->getBookingStatuses())
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'This time slot has been booked by another student. Please choose another time.');
    }

    $calendarIds = $this->getCounselorCalendarIds($counselorIds);
    if (empty($calendarIds)) {
        return redirect()->back()->with('error', 'Counselor calendar is not configured. Please choose another counselor.');
    }

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $request->start_time, $timezone);
    $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $endTimeFormatted, $timezone);

    try {
        foreach ($calendarIds as $calendarId) {
            if (!$calendarService->isSlotAvailable($calendarId, $slotStartDateTime, $slotEndDateTime)) {
                return redirect()->back()->with('error', 'Selected time is no longer available. Please choose another slot.');
            }
        }
    } catch (\Throwable $exception) {
        Log::error('Failed to check Google Calendar availability', [
            'counselor_id' => $counselor->id,
            'calendar_ids' => $calendarIds,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Unable to verify counselor availability. Please try again later.');
    }

    DB::beginTransaction();

    try {
        $appointment = Appointment::create([
            'student_id' => $student->id,
            'counselor_id' => $request->counselor_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTimeFormatted,
            'booking_type' => $request->booking_type,
            'concern' => $request->concern,
            'status' => 'pending'
        ]);

        $eventData = [
            'name' => 'Counseling Appointment - ' . $student->user->first_name . ' ' . $student->user->last_name,
            'description' => "Student ID: {$student->student_id}\nConcern: {$request->concern}",
            'startDateTime' => $slotStartDateTime,
            'endDateTime' => $slotEndDateTime,
        ];

        $event = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);

        $appointment->update([
            'google_calendar_event_id' => $event->id
        ]);

        DB::commit();
    } catch (\Throwable $exception) {
        DB::rollBack();
        Log::error('Failed to create appointment with Google Calendar event', [
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Failed to book appointment. Please try again.');
    }

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
        if (!in_array($appointment->status, ['pending', 'approved', 'rescheduled', 'reschedule_requested', 'reschedule_rejected'], true)) {
            return redirect()->back()->with('error', 'This appointment cannot be cancelled.');
        }

        $calendarService = new GoogleCalendarService();
        if ($appointment->google_calendar_event_id && $appointment->counselor && $appointment->counselor->google_calendar_id) {
            $calendarService->deleteEvent($appointment->google_calendar_event_id, $appointment->counselor->google_calendar_id);
        }

        $appointment->update([
            'status' => 'cancelled',
            'notes' => $appointment->notes . "\nCancelled by student on " . now()->toDateTimeString(),
            'google_calendar_event_id' => null,
            'proposed_date' => null,
            'proposed_start_time' => null,
            'proposed_end_time' => null,
            'reschedule_reason' => null,
            'reschedule_requested_at' => null,
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully. The time slot is now available for booking.');
    }

public function updateStatus(Request $request, Appointment $appointment)
{
    $request->validate([
        'status' => 'required|in:approved,rejected,cancelled,completed,referred,rescheduled,reschedule_requested,reschedule_rejected', // removed 'transferred'
        'notes' => 'nullable|string|max:500',
        'referred_to_counselor_id' => 'nullable|exists:counselors,id',
        'referral_reason' => 'nullable|string|max:500'
    ]);

    $oldStatus = $appointment->status;

    $updateData = [
        'status' => $request->status,
        'notes' => $request->notes ?: $appointment->notes
    ];

    // Handle referral (uses 'referred' status)
    if ($request->status === 'referred' && $request->referred_to_counselor_id) {
        $updateData['referred_to_counselor_id'] = $request->referred_to_counselor_id;
        $updateData['referral_reason'] = $request->referral_reason;
        $updateData['original_counselor_id'] = $appointment->counselor_id;
    }

    $appointment->update($updateData);

    if ($request->status === 'completed' && $appointment->booking_type === 'Initial Interview') {
        $appointment->loadMissing('student');
        if ($appointment->student && $appointment->student->initial_interview_completed !== true) {
            $appointment->student->update(['initial_interview_completed' => true]);
        }
    }

    if (in_array($request->status, ['rejected', 'cancelled'], true)) {
        $calendarService = new GoogleCalendarService();
        if ($appointment->google_calendar_event_id && $appointment->counselor && $appointment->counselor->google_calendar_id) {
            $calendarService->deleteEvent($appointment->google_calendar_event_id, $appointment->counselor->google_calendar_id);
        }

        $appointment->update([
            'google_calendar_event_id' => null,
            'proposed_date' => null,
            'proposed_start_time' => null,
            'proposed_end_time' => null,
            'reschedule_reason' => null,
            'reschedule_requested_at' => null,
        ]);
    }

    // Status messages - only 'referred' no 'transferred'
    $statusMessages = [
        'approved' => 'Appointment approved successfully.',
        'rejected' => 'Appointment rejected.',
        'cancelled' => 'Appointment cancelled.',
        'completed' => 'Appointment marked as completed.',
        'referred' => 'Appointment referred to another counselor successfully.',
        'rescheduled' => 'Appointment rescheduled successfully.'
    ];

    // Safe lookup with fallback
    $message = $statusMessages[$request->status] ?? 'Status updated successfully.';

    return redirect()->back()->with('success', $message);
}

public function reschedule(Request $request, Appointment $appointment)
{
    $request->validate([
        'appointment_date' => 'required|date|after:yesterday',
        'start_time' => 'required|date_format:H:i',
        'reason' => 'nullable|string|max:500',
    ]);

    $counselor = Counselor::where('user_id', Auth::id())->first();
    if (!$counselor) {
        return redirect()->back()->with('error', 'Counselor profile not found.');
    }

    $counselorIds = Counselor::where('user_id', $counselor->user_id)->pluck('id')->all();
    if (!in_array($appointment->getEffectiveCounselorId(), $counselorIds, true)) {
        return redirect()->back()->with('error', 'You can only reschedule your own appointments.');
    }

    if (!in_array($appointment->status, ['pending', 'approved', 'referred', 'rescheduled', 'reschedule_rejected'], true)) {
        return redirect()->back()->with('error', 'This appointment cannot be rescheduled.');
    }

    $date = Carbon::parse($request->appointment_date);
    $startTime = Carbon::parse($request->start_time);
    $endTime = $startTime->copy()->addHour()->format('H:i');

    if ($this->isDateClosed($counselor, $date)) {
        return redirect()->back()->with('error', 'This counselor is not available on the selected date.');
    }

    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    $dailyBookings = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', $this->getBookingStatuses())
        ->where('id', '!=', $appointment->id)
        ->count();

    if ($dailyBookings >= $this->getDailyBookingLimit($counselor)) {
        return redirect()->back()->with('error', 'Daily booking limit reached for the selected date.');
    }

    if (!$this->isSlotWithinAvailability($counselor, $date, $request->start_time, $endTime)) {
        return redirect()->back()->with('error', 'Selected time is outside the counselor availability.');
    }

    $existingAppointment = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->where('start_time', $request->start_time)
        ->whereIn('status', $this->getBookingStatuses())
        ->where('id', '!=', $appointment->id)
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'This time slot is already booked. Please choose another time.');
    }

    if (!$counselor->google_calendar_id) {
        return redirect()->back()->with('error', 'Counselor calendar is not configured.');
    }

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $request->start_time, $timezone);
    $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $endTime, $timezone);

    try {
        if (!$calendarService->isSlotAvailable(
            $counselor->google_calendar_id,
            $slotStartDateTime,
            $slotEndDateTime,
            $appointment->google_calendar_event_id
        )) {
            return redirect()->back()->with('error', 'Selected time is no longer available.');
        }
    } catch (\Throwable $exception) {
        Log::error('Failed to check Google Calendar availability for reschedule', [
            'counselor_id' => $counselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Unable to verify counselor availability. Please try again later.');
    }

    $rescheduleNote = "RESCHEDULE REQUESTED by {$counselor->user->first_name} {$counselor->user->last_name} on " .
        now()->toDateTimeString() . "\nProposed schedule: " . $date->format('Y-m-d') . " {$request->start_time} - {$endTime}";

    if ($request->filled('reason')) {
        $rescheduleNote .= "\nReason: " . $request->input('reason');
    }

    $appointment->update([
        'status' => 'reschedule_requested',
        'proposed_date' => $date->toDateString(),
        'proposed_start_time' => $request->start_time,
        'proposed_end_time' => $endTime,
        'reschedule_reason' => $request->input('reason'),
        'reschedule_requested_at' => now(),
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $rescheduleNote,
    ]);

    return redirect()->back()->with('success', 'Reschedule request sent to the student.');
}

public function refer(Request $request, Appointment $appointment)
{
    $request->validate([
        'referred_to_counselor_id' => 'required|exists:counselors,id',
        'appointment_date' => 'required|date|after:yesterday',
        'start_time' => 'required|date_format:H:i',
        'referral_reason' => 'nullable|string|max:500',
    ]);

    $counselorAssignments = Counselor::where('user_id', Auth::id())->get();
    $counselor = $counselorAssignments->first();
    if (!$counselor || !$counselorAssignments->pluck('id')->contains($appointment->counselor_id)) {
        return redirect()->back()->with('error', 'You can only refer your own appointments.');
    }

    if (!in_array($appointment->status, ['pending', 'approved', 'rescheduled', 'reschedule_rejected'], true)) {
        return redirect()->back()->with('error', 'This appointment cannot be referred.');
    }

    if ((int) $request->referred_to_counselor_id === (int) $appointment->counselor_id) {
        return redirect()->back()->with('error', 'Please choose a different counselor for the referral.');
    }

    $referredCounselor = Counselor::findOrFail($request->referred_to_counselor_id);
    $date = Carbon::parse($request->appointment_date);
    $startTime = Carbon::parse($request->start_time);
    $endTime = $startTime->copy()->addHour()->format('H:i');

    if ($this->isDateClosed($referredCounselor, $date)) {
        return redirect()->back()->with('error', 'The referred counselor is not available on the selected date.');
    }

    $referredCounselorIds = $this->getCounselorAssignmentIds($referredCounselor);
    if ($this->getCounselorBookingsForDate($referredCounselorIds, $date) >= $this->getDailyBookingLimit($referredCounselor)) {
        return redirect()->back()->with('error', 'Daily booking limit reached for the referred counselor on the selected date.');
    }

    if (!$this->isSlotWithinAvailability($referredCounselor, $date, $request->start_time, $endTime)) {
        return redirect()->back()->with('error', 'Selected time is outside the referred counselor availability.');
    }

    $existingAppointment = Appointment::whereIn('counselor_id', $referredCounselorIds)
        ->where('appointment_date', $date->toDateString())
        ->where('start_time', $request->start_time)
        ->whereIn('status', $this->getBookingStatuses())
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'This time slot is already booked for the referred counselor.');
    }

    $calendarIds = $this->getCounselorCalendarIds($referredCounselorIds);
    if (empty($calendarIds)) {
        return redirect()->back()->with('error', 'Referred counselor calendar is not configured.');
    }

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $request->start_time, $timezone);
    $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $endTime, $timezone);

    try {
        foreach ($calendarIds as $calendarId) {
            if (!$calendarService->isSlotAvailable($calendarId, $slotStartDateTime, $slotEndDateTime)) {
                return redirect()->back()->with('error', 'Selected time is no longer available for the referred counselor.');
            }
        }
    } catch (\Throwable $exception) {
        Log::error('Failed to check Google Calendar availability for referral', [
            'counselor_id' => $referredCounselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Unable to verify counselor availability. Please try again later.');
    }

    $referralNote = "REFERRAL REQUESTED by {$counselor->user->first_name} {$counselor->user->last_name} on " .
        now()->toDateTimeString() . "\nProposed schedule: " . $date->format('Y-m-d') . " {$request->start_time} - {$endTime}";

    if ($request->filled('referral_reason')) {
        $referralNote .= "\nReason: " . $request->input('referral_reason');
    }

    $appointment->update([
        'status' => 'referred',
        'referred_to_counselor_id' => $referredCounselor->id,
        'referral_reason' => $request->input('referral_reason'),
        'referral_previous_status' => $appointment->status,
        'referral_requested_at' => now(),
        'referral_outcome' => null,
        'referral_resolved_at' => null,
        'referral_resolved_by_counselor_id' => null,
        'original_counselor_id' => $appointment->counselor_id,
        'proposed_date' => $date->toDateString(),
        'proposed_start_time' => $request->start_time,
        'proposed_end_time' => $endTime,
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $referralNote,
    ]);

    return redirect()->back()->with('success', 'Referral request sent to the student.');
}

public function acceptReschedule(Request $request, Appointment $appointment)
{
    $student = Student::where('user_id', Auth::id())->first();
    if (!$student || $appointment->student_id !== $student->id) {
        return redirect()->back()->with('error', 'You can only update your own appointments.');
    }

    if ($appointment->status !== 'reschedule_requested') {
        return redirect()->back()->with('error', 'This appointment does not have a pending reschedule request.');
    }

    if (!$appointment->proposed_date || !$appointment->proposed_start_time || !$appointment->proposed_end_time) {
        return redirect()->back()->with('error', 'Reschedule details are incomplete.');
    }

    $counselor = $appointment->counselor;
    if (!$counselor || !$counselor->google_calendar_id) {
        return redirect()->back()->with('error', 'Counselor calendar is not configured.');
    }

    $date = Carbon::parse($appointment->proposed_date);
    $startTime = $appointment->proposed_start_time;
    $endTime = $appointment->proposed_end_time;

    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    $existingAppointment = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->where('start_time', $startTime)
        ->whereIn('status', $this->getBookingStatuses())
        ->where('id', '!=', $appointment->id)
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'The proposed time is no longer available.');
    }

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::parse($date->toDateString() . ' ' . $startTime, $timezone);
    $slotEndDateTime = Carbon::parse($date->toDateString() . ' ' . $endTime, $timezone);

    try {
        if (!$calendarService->isSlotAvailable(
            $counselor->google_calendar_id,
            $slotStartDateTime,
            $slotEndDateTime,
            $appointment->google_calendar_event_id
        )) {
            return redirect()->back()->with('error', 'The proposed time is no longer available.');
        }
    } catch (\Throwable $exception) {
        Log::error('Failed to check Google Calendar availability for reschedule accept', [
            'counselor_id' => $counselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Unable to verify counselor availability. Please try again later.');
    }

    DB::beginTransaction();

    $newEvent = null;

    try {
        $oldEventId = $appointment->google_calendar_event_id;
        $eventData = [
            'name' => 'Counseling Appointment - ' . $appointment->student->user->first_name . ' ' . $appointment->student->user->last_name,
            'description' => "Student ID: {$appointment->student->student_id}\nConcern: {$appointment->concern}",
            'startDateTime' => $slotStartDateTime,
            'endDateTime' => $slotEndDateTime,
        ];

        $newEvent = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);

        $acceptNote = "RESCHEDULE ACCEPTED by student on " . now()->toDateTimeString() .
            "\nNew schedule: " . $date->format('Y-m-d') . " {$startTime} - {$endTime}";

        $appointment->update([
            'appointment_date' => $date->toDateString(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'rescheduled',
            'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $acceptNote,
            'google_calendar_event_id' => $newEvent->id,
            'proposed_date' => null,
            'proposed_start_time' => null,
            'proposed_end_time' => null,
            'reschedule_reason' => null,
            'reschedule_requested_at' => null,
        ]);

        if ($oldEventId) {
            $calendarService->deleteEvent($oldEventId, $counselor->google_calendar_id);
        }

        DB::commit();
    } catch (\Throwable $exception) {
        DB::rollBack();
        if ($newEvent && $counselor->google_calendar_id) {
            $calendarService->deleteEvent($newEvent->id, $counselor->google_calendar_id);
        }
        Log::error('Failed to accept reschedule', [
            'counselor_id' => $counselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Failed to accept reschedule. Please try again.');
    }

    return redirect()->back()->with('success', 'Reschedule accepted. Your appointment has been updated.');
}

public function rejectReschedule(Request $request, Appointment $appointment)
{
    $student = Student::where('user_id', Auth::id())->first();
    if (!$student || $appointment->student_id !== $student->id) {
        return redirect()->back()->with('error', 'You can only update your own appointments.');
    }

    if ($appointment->status !== 'reschedule_requested') {
        return redirect()->back()->with('error', 'This appointment does not have a pending reschedule request.');
    }

    $rejectNote = "RESCHEDULE REJECTED by student on " . now()->toDateTimeString();

    $appointment->update([
        'status' => 'rejected',
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $rejectNote,
        'proposed_date' => null,
        'proposed_start_time' => null,
        'proposed_end_time' => null,
        'reschedule_reason' => null,
        'reschedule_requested_at' => null,
    ]);

    return redirect()->back()->with('success', 'Reschedule request rejected. Appointment marked as rejected.');
}

public function acceptReferralByCounselor(Request $request, Appointment $appointment)
{
    $counselor = Counselor::where('user_id', Auth::id())->first();
    if (!$counselor) {
        return redirect()->back()->with('error', 'Counselor profile not found.');
    }

    if ($appointment->status !== 'referred' || (int) $appointment->referred_to_counselor_id !== (int) $counselor->id) {
        return redirect()->back()->with('error', 'You can only accept referrals assigned to you.');
    }

    $date = $appointment->proposed_date
        ? Carbon::parse($appointment->proposed_date)
        : Carbon::parse($appointment->appointment_date);
    $startTime = $appointment->proposed_start_time ?: $appointment->start_time;
    $endTime = $appointment->proposed_end_time
        ?: Carbon::parse($startTime)->addHour()->format('H:i');

    if ($this->isDateClosed($counselor, $date)) {
        return redirect()->back()->with('error', 'This counselor is not available on the selected date.');
    }

    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    if ($this->getCounselorBookingsForDate($counselorIds, $date) >= $this->getDailyBookingLimit($counselor)) {
        return redirect()->back()->with('error', 'Daily booking limit reached for the selected date.');
    }

    if (!$this->isSlotWithinAvailability($counselor, $date, $startTime, $endTime)) {
        return redirect()->back()->with('error', 'Selected time is outside the counselor availability.');
    }

    $existingAppointment = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->where('start_time', $startTime)
        ->whereIn('status', $this->getBookingStatuses())
        ->where('id', '!=', $appointment->id)
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'This time slot is already booked. Please choose another time.');
    }

    if (!$counselor->google_calendar_id) {
        return redirect()->back()->with('error', 'Counselor calendar is not configured.');
    }

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::parse($date->toDateString() . ' ' . $startTime, $timezone);
    $slotEndDateTime = Carbon::parse($date->toDateString() . ' ' . $endTime, $timezone);

    try {
        if (!$calendarService->isSlotAvailable(
            $counselor->google_calendar_id,
            $slotStartDateTime,
            $slotEndDateTime
        )) {
            return redirect()->back()->with('error', 'Selected time is no longer available.');
        }
    } catch (\Throwable $exception) {
        Log::error('Failed to check Google Calendar availability for referral accept', [
            'counselor_id' => $counselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Unable to verify counselor availability. Please try again later.');
    }

    DB::beginTransaction();

    $newEvent = null;

    try {
        $oldEventId = $appointment->google_calendar_event_id;
        $originalCounselor = $appointment->counselor;

        $eventData = [
            'name' => 'Counseling Appointment - ' . $appointment->student->user->first_name . ' ' . $appointment->student->user->last_name,
            'description' => "Student ID: {$appointment->student->student_id}\nConcern: {$appointment->concern}",
            'startDateTime' => $slotStartDateTime,
            'endDateTime' => $slotEndDateTime,
        ];

        $newEvent = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);

        $acceptNote = "REFERRAL ACCEPTED by {$counselor->user->first_name} {$counselor->user->last_name} on " .
            now()->toDateTimeString() . "\nNew schedule: " . $date->format('Y-m-d') . " {$startTime} - {$endTime}";

        $appointment->update([
            'counselor_id' => $counselor->id,
            'appointment_date' => $date->toDateString(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'approved',
            'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $acceptNote,
            'google_calendar_event_id' => $newEvent->id,
            'proposed_date' => null,
            'proposed_start_time' => null,
            'proposed_end_time' => null,
            'referral_outcome' => 'accepted',
            'referral_resolved_at' => now(),
            'referral_resolved_by_counselor_id' => $counselor->id,
        ]);

        if ($oldEventId && $originalCounselor && $originalCounselor->google_calendar_id) {
            $calendarService->deleteEvent($oldEventId, $originalCounselor->google_calendar_id);
        }

        DB::commit();
    } catch (\Throwable $exception) {
        DB::rollBack();
        if ($newEvent && $counselor->google_calendar_id) {
            $calendarService->deleteEvent($newEvent->id, $counselor->google_calendar_id);
        }
        Log::error('Failed to accept referral by counselor', [
            'counselor_id' => $counselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Failed to accept referral. Please try again.');
    }

    return redirect()->back()->with('success', 'Referral accepted. Appointment scheduled successfully.');
}

public function rejectReferralByCounselor(Request $request, Appointment $appointment)
{
    $counselor = Counselor::where('user_id', Auth::id())->first();
    if (!$counselor) {
        return redirect()->back()->with('error', 'Counselor profile not found.');
    }

    if ($appointment->status !== 'referred' || (int) $appointment->referred_to_counselor_id !== (int) $counselor->id) {
        return redirect()->back()->with('error', 'You can only reject referrals assigned to you.');
    }

    $rejectNote = "REFERRAL REJECTED by {$counselor->user->first_name} {$counselor->user->last_name} on " . now()->toDateTimeString();
    $previousStatus = $appointment->referral_previous_status ?: 'pending';

    $appointment->update([
        'status' => $previousStatus,
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $rejectNote,
        'referral_outcome' => 'rejected',
        'referral_resolved_at' => now(),
        'referral_resolved_by_counselor_id' => $counselor->id,
        'proposed_date' => null,
        'proposed_start_time' => null,
        'proposed_end_time' => null,
    ]);

    return redirect()->back()->with('success', 'Referral request rejected. Appointment returned to the original counselor.');
}

public function acceptReferral(Request $request, Appointment $appointment)
{
    $student = Student::where('user_id', Auth::id())->first();
    if (!$student || $appointment->student_id !== $student->id) {
        return redirect()->back()->with('error', 'You can only update your own appointments.');
    }

    if ($appointment->status !== 'referred') {
        return redirect()->back()->with('error', 'This appointment does not have a pending referral request.');
    }

    if (!$appointment->referred_to_counselor_id || !$appointment->proposed_date || !$appointment->proposed_start_time || !$appointment->proposed_end_time) {
        return redirect()->back()->with('error', 'Referral details are incomplete.');
    }

    $referredCounselor = Counselor::find($appointment->referred_to_counselor_id);
    if (!$referredCounselor || !$referredCounselor->google_calendar_id) {
        return redirect()->back()->with('error', 'Referred counselor calendar is not configured.');
    }

    $date = Carbon::parse($appointment->proposed_date);
    $startTime = $appointment->proposed_start_time;
    $endTime = $appointment->proposed_end_time;

    $referredCounselorIds = $this->getCounselorAssignmentIds($referredCounselor);
    if ($this->isDateClosed($referredCounselor, $date)) {
        return redirect()->back()->with('error', 'The referred counselor is not available on the selected date.');
    }

    if ($this->getCounselorBookingsForDate($referredCounselorIds, $date) >= $this->getDailyBookingLimit($referredCounselor)) {
        return redirect()->back()->with('error', 'Daily booking limit reached for the referred counselor on the selected date.');
    }

    if (!$this->isSlotWithinAvailability($referredCounselor, $date, $startTime, $endTime)) {
        return redirect()->back()->with('error', 'Selected time is outside the referred counselor availability.');
    }

    $existingAppointment = Appointment::whereIn('counselor_id', $referredCounselorIds)
        ->where('appointment_date', $date->toDateString())
        ->where('start_time', $startTime)
        ->whereIn('status', $this->getBookingStatuses())
        ->where('id', '!=', $appointment->id)
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'The proposed time is no longer available.');
    }

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::parse($date->toDateString() . ' ' . $startTime, $timezone);
    $slotEndDateTime = Carbon::parse($date->toDateString() . ' ' . $endTime, $timezone);

    try {
        if (!$calendarService->isSlotAvailable(
            $referredCounselor->google_calendar_id,
            $slotStartDateTime,
            $slotEndDateTime
        )) {
            return redirect()->back()->with('error', 'The proposed time is no longer available.');
        }
    } catch (\Throwable $exception) {
        Log::error('Failed to check Google Calendar availability for referral accept', [
            'counselor_id' => $referredCounselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Unable to verify counselor availability. Please try again later.');
    }

    DB::beginTransaction();

    $newEvent = null;

    try {
        $oldEventId = $appointment->google_calendar_event_id;
        $originalCounselor = $appointment->counselor;
        $eventData = [
            'name' => 'Counseling Appointment - ' . $appointment->student->user->first_name . ' ' . $appointment->student->user->last_name,
            'description' => "Student ID: {$appointment->student->student_id}\nConcern: {$appointment->concern}",
            'startDateTime' => $slotStartDateTime,
            'endDateTime' => $slotEndDateTime,
        ];

        $newEvent = $calendarService->createAppointmentEvent($eventData, $referredCounselor->google_calendar_id);

        $acceptNote = "REFERRAL ACCEPTED by student on " . now()->toDateTimeString() .
            "\nNew schedule: " . $date->format('Y-m-d') . " {$startTime} - {$endTime}";

        $appointment->update([
            'counselor_id' => $referredCounselor->id,
            'appointment_date' => $date->toDateString(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => 'pending',
            'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $acceptNote,
            'google_calendar_event_id' => $newEvent->id,
            'proposed_date' => null,
            'proposed_start_time' => null,
            'proposed_end_time' => null,
            'referral_previous_status' => null,
            'referral_requested_at' => null,
        ]);

        if ($oldEventId && $originalCounselor && $originalCounselor->google_calendar_id) {
            $calendarService->deleteEvent($oldEventId, $originalCounselor->google_calendar_id);
        }

        DB::commit();
    } catch (\Throwable $exception) {
        DB::rollBack();
        if ($newEvent && $referredCounselor->google_calendar_id) {
            $calendarService->deleteEvent($newEvent->id, $referredCounselor->google_calendar_id);
        }
        Log::error('Failed to accept referral', [
            'counselor_id' => $referredCounselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Failed to accept referral. Please try again.');
    }

    return redirect()->back()->with('success', 'Referral accepted. Your appointment has been updated.');
}

public function rejectReferral(Request $request, Appointment $appointment)
{
    $student = Student::where('user_id', Auth::id())->first();
    if (!$student || $appointment->student_id !== $student->id) {
        return redirect()->back()->with('error', 'You can only update your own appointments.');
    }

    if ($appointment->status !== 'referred') {
        return redirect()->back()->with('error', 'This appointment does not have a pending referral request.');
    }

    $rejectNote = "REFERRAL CANCELLED by student on " . now()->toDateTimeString();

    $appointment->update([
        'status' => 'cancelled',
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $rejectNote,
        'referred_to_counselor_id' => null,
        'referral_reason' => null,
        'referral_previous_status' => null,
        'referral_requested_at' => null,
        'original_counselor_id' => null,
        'proposed_date' => null,
        'proposed_start_time' => null,
        'proposed_end_time' => null,
    ]);

    return redirect()->back()->with('success', 'Referral cancelled. Appointment has been cancelled.');
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
        $currentCounselor = Counselor::with('user')->findOrFail($request->current_counselor_id);

        // Get counselors from any college, excluding current counselor's user assignments
        $counselors = Counselor::with('user', 'college')
            ->whereHas('user', function($query) {
                $query->where('role', 'counselor');
            })
            ->where('user_id', '!=', $currentCounselor->user_id)
            ->get()
            ->map(function($counselor) use ($student) {
                $isSameCollege = $counselor->college_id == $student->college_id;
                $collegeName = $counselor->college->name ?? 'N/A';
                return [
                    'id' => $counselor->id,
                    'name' => $counselor->user->first_name . ' ' . $counselor->user->last_name,
                    'position' => $counselor->position,
                    'college' => $collegeName,
                    'same_college' => $isSameCollege,
                    'display_text' => $counselor->user->first_name . ' ' . $counselor->user->last_name .
                        ' - ' . $counselor->position . ' (' . $collegeName . ')' .
                        ($isSameCollege ? ' - Same College' : ' - Different College')
                ];
            });

        return response()->json($counselors);
    }


    private function getBookingStatuses(): array
    {
        return [
            'pending',
            'approved',
            'completed',
            'referred',
            'rescheduled',
            'reschedule_requested',
            'reschedule_rejected'
        ];
    }

    private function getCounselorBookingsForDate(array $counselorIds, Carbon $date): int
    {
        return Appointment::whereIn('counselor_id', $counselorIds)
            ->where('appointment_date', $date->toDateString())
            ->whereIn('status', $this->getBookingStatuses())
            ->count();
    }

    private function getCounselorAssignmentIds(Counselor $counselor): array
    {
        return Counselor::where('user_id', $counselor->user_id)
            ->pluck('id')
            ->all();
    }

    private function getCounselorCalendarIds(array $counselorIds): array
    {
        return Counselor::whereIn('id', $counselorIds)
            ->whereNotNull('google_calendar_id')
            ->pluck('google_calendar_id')
            ->unique()
            ->values()
            ->all();
    }

    private function getCalendarBusyIntervalsForDate(array $calendarIds, Carbon $date): array
    {
        $calendarService = new GoogleCalendarService();
        $busyIntervals = [];

        foreach ($calendarIds as $calendarId) {
            $busyIntervals = array_merge(
                $busyIntervals,
                $calendarService->getBusyIntervalsForDate($calendarId, $date)
            );
        }

        return $busyIntervals;
    }

    private function getCalendarTimezone(): string
    {
        return config('app.timezone') ?: 'Asia/Manila';
    }

    private function getDailyBookingLimit(Counselor $counselor): int
    {
        return $counselor->getDailyBookingLimit();
    }

    private function getAvailabilityForDate(Counselor $counselor, Carbon $date): array
    {
        $override = CounselorScheduleOverride::where('counselor_id', $counselor->id)
            ->whereDate('date', $date->toDateString())
            ->first();

        if ($override && $override->is_closed) {
            return [];
        }

        if ($override && !empty($override->time_slots)) {
            return $override->time_slots;
        }

        $availability = $counselor->getAvailability();
        $dayName = strtolower($date->englishDayOfWeek);

        return $availability[$dayName] ?? [];
    }

    private function isDateClosed(Counselor $counselor, Carbon $date): bool
    {
        return CounselorScheduleOverride::where('counselor_id', $counselor->id)
            ->whereDate('date', $date->toDateString())
            ->where('is_closed', true)
            ->exists();
    }

    private function isSlotWithinAvailability(Counselor $counselor, Carbon $date, string $startTime, string $endTime): bool
    {
        $dayAvailability = $this->getAvailabilityForDate($counselor, $date);

        $timezone = $this->getCalendarTimezone();
        $slotStart = Carbon::parse($date->toDateString() . ' ' . $startTime, $timezone);
        $slotEnd = Carbon::parse($date->toDateString() . ' ' . $endTime, $timezone);

        foreach ($dayAvailability as $timeRange) {
            [$start, $end] = explode('-', $timeRange);

            $rangeStart = Carbon::parse($date->toDateString() . ' ' . trim($start), $timezone);
            $rangeEnd = Carbon::parse($date->toDateString() . ' ' . trim($end), $timezone);

            if ($slotStart->greaterThanOrEqualTo($rangeStart) && $slotEnd->lessThanOrEqualTo($rangeEnd)) {
                return true;
            }
        }

        return false;
    }
}
