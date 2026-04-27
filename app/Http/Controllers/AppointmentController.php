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
use App\Mail\AppointmentBooked;
use App\Mail\AppointmentBookedByCounselor;
use App\Mail\AppointmentCancelled;
use App\Mail\AppointmentStatusChanged;
use App\Mail\AppointmentRescheduled;
use App\Mail\AppointmentReferred;
use App\Mail\AppointmentReferredToCounselor;
use App\Mail\RescheduleResponse;
use App\Mail\ReferralResponse;
use Illuminate\Support\Facades\Mail;
use App\Notifications\AppointmentBookedNotification;
use App\Notifications\AppointmentBookedByCounselorNotification;
use App\Notifications\AppointmentCancelledNotification;
use App\Notifications\AppointmentStatusChangedNotification;
use App\Notifications\AppointmentRescheduledNotification;
use App\Notifications\AppointmentReferredNotification;
use App\Notifications\AppointmentReferredToCounselorNotification;
use App\Notifications\RescheduleResponseNotification;
use App\Notifications\ReferralResponseNotification;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            $query = Appointment::with([
                    'counselor.user',
                    'counselor.college',
                    'referredCounselor.user',
                    'originalCounselor.user',
                    'sessionNotes'
                ])
                ->where('student_id', $student->id);

            // Date filter
            if ($request->has('search_date') && $request->search_date) {
                $query->where('appointment_date', $request->search_date);
            }

            // Status filter
            if ($request->has('status') && $request->status) {
                if ($request->status === 'referred') {
                    $query->where(function ($q) {
                        $q->whereNotNull('original_counselor_id')
                          ->orWhereNotNull('referred_to_counselor_id');
                    });
                } else {
                    $query->where('status', $request->status);
                }
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

    public function storeFollowupByCounselor(Request $request, Appointment $appointment)
    {
        $request->validate([
            'counselor_id' => 'nullable|integer|exists:counselors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'booking_type' => 'required|in:Counseling,Consultation',
            'booking_category' => 'nullable|in:online,walk-in,referred,called-in',
            'concern' => 'required|string|max:500',
            'auto_approve' => 'nullable|boolean',
        ]);

        if (!in_array($appointment->status, ['approved', 'rescheduled', 'completed'], true)) {
            return redirect()->back()->with('error', 'Follow-up appointments can only be booked for approved appointments.');
        }

        $user = Auth::user();
        if ($user->role !== 'counselor') {
            abort(403);
        }

        $counselorId = $request->input('counselor_id', $appointment->getEffectiveCounselorId());
        $counselor = Counselor::where('user_id', $user->id)->where('id', $counselorId)->first();
        if (!$counselor) {
            abort(403);
        }

        $counselorIds = $this->getCounselorAssignmentIds($counselor);
        $date = Carbon::parse($request->appointment_date);

        if ($this->isDateClosed($counselor, $date)) {
            return redirect()->back()->with('error', 'This counselor is not available on the selected date.');
        }

        $overrideAvailability = $request->boolean('override_availability', false);

        if (!$overrideAvailability && $this->getCounselorBookingsForDate($counselorIds, $date) >= $this->getDailyBookingLimit($counselor)) {
            return redirect()->back()->with('error', 'Daily booking limit reached. Enable "Override Availability" to book beyond the limit.');
        }

        $startTime = Carbon::parse($request->start_time);
        $endTime = $startTime->copy()->addHour();
        $endTimeFormatted = $endTime->format('H:i');

        if (!$overrideAvailability) {
            $dayAvailability = $this->getAvailabilityForDate($counselor, $date);
            if (!empty($dayAvailability) && !$this->isSlotWithinAvailability($counselor, $date, $request->start_time, $endTimeFormatted)) {
                return redirect()->back()->with('error', 'Selected time is outside the counselor availability. Enable "Override Availability" to book outside set hours.');
            }
        }

        $existingAppointment = Appointment::whereIn('counselor_id', $counselorIds)
            ->where('appointment_date', $request->appointment_date)
            ->where('start_time', $request->start_time)
            ->whereIn('status', $this->getDbSlotBlockingStatuses())
            ->exists();

        if ($existingAppointment) {
            return redirect()->back()->with('error', 'This time slot has already been booked. Please choose another time.');
        }

        $calendarIds = $this->getCounselorCalendarIds($counselorIds);
        if (empty($calendarIds)) {
            return redirect()->back()->with('error', 'Counselor calendar is not configured.');
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
            Log::warning('Google Calendar check skipped for follow-up booking  falling back to DB-only', [
                'appointment_id' => $appointment->id,
                'counselor_id' => $counselor->id,
                'calendar_ids' => $calendarIds,
                'error' => $exception->getMessage(),
            ]);
        }

        $student = $appointment->student()->with('user')->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student profile not found.');
        }

        DB::beginTransaction();
        try {
            $originalAppointmentDateLabel = $appointment->appointment_date
                ? $appointment->appointment_date->format('F j, Y')
                : null;

            $followupNote = 'Follow-up appointment booked by counselor for appointment #' . $appointment->id;
            if ($originalAppointmentDateLabel) {
                $followupNote .= ' ' . $originalAppointmentDateLabel;
            }

            $followupAppointment = Appointment::create([
                'student_id' => $appointment->student_id,
                'counselor_id' => $counselor->id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $request->start_time,
                'end_time' => $endTimeFormatted,
                'booking_type' => $request->booking_type,
                'booking_category' => $request->input('booking_category', 'online'),
                'concern' => $request->concern,
                'status' => $request->boolean('auto_approve', true) ? 'approved' : 'pending',
                'notes' => $followupNote,
            ]);

            $eventData = [
                'name' => 'Counseling Follow-up - ' . $student->user->first_name . ' ' . $student->user->last_name,
                'description' => "Student ID: {$student->student_id}\nConcern: {$request->concern}\nFollow-up for Appointment: {$appointment->id}",
                'startDateTime' => $slotStartDateTime,
                'endDateTime' => $slotEndDateTime,
            ];

            if ($counselor->google_calendar_id) {
                try {
                    $event = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);
                    $followupAppointment->update(['google_calendar_event_id' => $event->id]);
                } catch (\Throwable $calendarException) {
                    Log::warning('Google Calendar event skipped for follow-up appointment', [
                        'counselor_id' => $counselor->id,
                        'error' => $calendarException->getMessage(),
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('Failed to create counselor follow-up appointment', [
                'appointment_id' => $appointment->id,
                'counselor_id' => $counselor->id,
                'student_id' => $appointment->student_id,
                'error' => $exception->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to book follow-up appointment. Please try again.');
        }

        return redirect()->back()->with('success', 'Follow-up appointment booked successfully!');
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

    $hasInitialInterviewAppointment = Appointment::where('student_id', $student->id)
        ->where('booking_type', 'Initial Interview')
        ->whereNotIn('status', ['cancelled', 'rejected', 'no_show'])
        ->exists();

    return view('appointments.create', compact('counselors', 'student', 'allowAllCounselors', 'hasInitialInterviewAppointment'));
}

public function createByCounselor(Request $request)
{
    $user = Auth::user();
    if (!$user || $user->role !== 'counselor') {
        abort(403);
    }

    $counselorAssignments = Counselor::with('college')
        ->where('user_id', $user->id)
        ->get();

    if ($counselorAssignments->isEmpty()) {
        abort(404, 'Counselor profile not found.');
    }

    $collegeIds = $counselorAssignments->pluck('college_id')->filter()->unique()->values();
    if ($collegeIds->isEmpty()) {
        abort(403, 'Counselor college assignment is missing.');
    }

    $selectedCounselorId = (int) $request->input('counselor_id', $counselorAssignments->first()->id);
    $selectedCounselor = $counselorAssignments->firstWhere('id', $selectedCounselorId) ?? $counselorAssignments->first();

    $students = Student::with('user', 'college')
        ->whereIn('college_id', $collegeIds->all())
        ->orderBy('student_id')
        ->get();

    $initialInterviewBookedStudentIds = Appointment::whereIn('student_id', $students->pluck('id')->all())
        ->where('booking_type', 'Initial Interview')
        ->whereNotIn('status', ['cancelled', 'rejected'])
        ->pluck('student_id')
        ->unique()
        ->values()
        ->all();

    $initialInterviewInProgressStudentIds = Appointment::whereIn('student_id', $students->pluck('id')->all())
        ->where('booking_type', 'Initial Interview')
        ->whereIn('status', ['pending', 'approved', 'rescheduled', 'reschedule_requested', 'reschedule_rejected'])
        ->pluck('student_id')
        ->unique()
        ->values()
        ->all();

    return view('counselor.appointments.create', [
        'counselorAssignments' => $counselorAssignments,
        'selectedCounselor' => $selectedCounselor,
        'students' => $students,
        'initialInterviewBookedStudentIds' => $initialInterviewBookedStudentIds,
        'initialInterviewInProgressStudentIds' => $initialInterviewInProgressStudentIds,
    ]);
}

public function storeByCounselor(Request $request)
{
    $user = Auth::user();
    if (!$user || $user->role !== 'counselor') {
        abort(403);
    }

    $request->validate([
        'counselor_id' => 'required|exists:counselors,id',
        'student_id' => 'required|exists:students,id',
        'appointment_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required|date_format:H:i',
        'booking_type' => 'required|in:Initial Interview,Counseling,Consultation',
        'booking_category' => 'required|in:online,walk-in,referred,called-in',
        'concern' => 'required|string|max:500',
    ]);

    $counselor = Counselor::where('user_id', $user->id)
        ->where('id', $request->counselor_id)
        ->first();
    if (!$counselor) {
        abort(403);
    }

    $counselorAssignments = Counselor::where('user_id', $user->id)->get();
    $allowedCollegeIds = $counselorAssignments->pluck('college_id')->filter()->unique()->values();

    $student = Student::with('user', 'college')->findOrFail($request->student_id);
    if (!$allowedCollegeIds->contains((int) $student->college_id)) {
        abort(403);
    }

    $studentNeedsInitialInterview = false; // Initial Interview is optional — students can book Counseling/Consultation freely
    if (false) {
    }

    if ($request->booking_type === 'Initial Interview') {
        $hasInitialInterviewAppointment = Appointment::where('student_id', $student->id)
            ->where('booking_type', 'Initial Interview')
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->exists();

        if ($hasInitialInterviewAppointment) {
            return redirect()->back()->with('error', 'This student already has an Initial Interview appointment booked.');
        }
    }

    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    $date = Carbon::parse($request->appointment_date);

    if ($this->isDateClosed($counselor, $date)) {
        return redirect()->back()->with('error', 'This counselor is not available on the selected date.');
    }

    // Daily booking limit is intentionally not enforced here —
    // counselors can book beyond the limit for urgent/emergency student needs.

    $overrideAvailability = $request->boolean('override_availability', false);

    $startTime = Carbon::parse($request->start_time);
    $endTime = $startTime->copy()->addHour();
    $endTimeFormatted = $endTime->format('H:i');

    // When override_availability is set, skip availability window check to allow urgent bookings
    if (!$overrideAvailability) {
        $dayAvailability = $this->getAvailabilityForDate($counselor, $date);
        if (!empty($dayAvailability) && !$this->isSlotWithinAvailability($counselor, $date, $request->start_time, $endTimeFormatted)) {
            return redirect()->back()->with('error', 'Selected time is outside the counselor availability. Enable "Override Availability" to book outside set hours.');
        }
    }

    $existingAppointment = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $request->appointment_date)
        ->where('start_time', $request->start_time)
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'This time slot has already been booked. Please choose another time.');
    }

    $calendarIds = $this->getCounselorCalendarIds($counselorIds);
    if (empty($calendarIds)) {
        return redirect()->back()->with('error', 'Counselor calendar is not configured.');
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
        Log::warning('Google Calendar check skipped for counselor booking � falling back to DB-only', [
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'calendar_ids' => $calendarIds,
            'error' => $exception->getMessage(),
        ]);
    }

    DB::beginTransaction();
    try {
        $appointment = Appointment::create([
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTimeFormatted,
            'booking_type' => $request->booking_type,
            'booking_category' => $request->booking_category,
            'concern' => $request->concern,
            'status' => 'approved',
            'notes' => 'Booked by counselor on ' . now()->toDateTimeString(),
        ]);

        $eventData = [
            'name' => 'Counseling Appointment - ' . $student->user->first_name . ' ' . $student->user->last_name,
            'description' => "Student ID: {$student->student_id}\nConcern: {$request->concern}",
            'startDateTime' => $slotStartDateTime,
            'endDateTime' => $slotEndDateTime,
        ];

        if ($counselor->google_calendar_id) {
            try {
                $event = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);
                $appointment->update(['google_calendar_event_id' => $event->id]);
            } catch (\Throwable $calendarException) {
                Log::warning('Google Calendar event skipped for counselor-booked appointment', [
                    'counselor_id' => $counselor->id,
                    'error' => $calendarException->getMessage(),
                ]);
            }
        }

        DB::commit();
    } catch (\Throwable $exception) {
        DB::rollBack();
        Log::error('Failed to create counselor-booked appointment', [
            'counselor_id' => $counselor->id,
            'student_id' => $student->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Failed to book appointment. Please try again.');
    }

    // Notify student of the counselor-booked appointment
    try {
        $appointment->load(['student.user', 'counselor.user']);
        Mail::to($appointment->student->user->email)->send(new AppointmentBookedByCounselor($appointment));
        $appointment->student->user->notify(new AppointmentBookedByCounselorNotification($appointment));
    } catch (\Throwable $e) {
        Log::warning('Failed to send counselor-booked appointment email', ['error' => $e->getMessage()]);
    }

    return redirect()->route('counselor.appointments')
        ->with('success', 'Appointment booked successfully! It is approved immediately.');
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
    $requestUser = $request->user();
    $isCounselorRequest = $requestUser && $requestUser->role === 'counselor';
    $overrideAvailability = $request->boolean('override_availability', false);

    $request->validate([
        'counselor_id' => 'required|exists:counselors,id',
        'date' => 'required|date|after_or_equal:today',
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

    if (!$overrideAvailability && $this->getCounselorBookingsForDate($counselorIds, $date) >= $this->getDailyBookingLimit($counselor)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Daily booking limit reached for this counselor'
        ]);
    }

    // Get counselor's availability for that day
    $dayAvailability = $this->getAvailabilityForDate($counselor, $date);

    if ($overrideAvailability && $isCounselorRequest) {
        // Override mode: allow booking outside set hours; still respect closed dates.
        $dayAvailability = ['08:00-17:00'];
    }

    // Only fall back if the counselor has no availability configured at all.
    // If they have a schedule set, an empty day means they don't work that day.
    if (empty($dayAvailability) && $isCounselorRequest && $counselor->availability === null) {
        $dayAvailability = ['08:00-12:00', '13:00-17:00'];
    }

    if (empty($dayAvailability)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'No working hours for this day',
        ]);
    }

    // Get booked appointments for that date - DB slot blocking statuses only
    $bookedAppointments = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
        ->get(['start_time', 'end_time', 'status']);

    $calendarIds = $this->getCounselorCalendarIds($counselorIds);

    $calendarBusyIntervals = [];
    if (!empty($calendarIds)) {
        try {
            $calendarBusyIntervals = $this->getCalendarBusyIntervalsForDate($calendarIds, $date);
        } catch (\Throwable $exception) {
            Log::warning('Google Calendar unavailable  falling back to DB-only availability', [
                'counselor_id' => $counselor->id,
                'calendar_ids' => $calendarIds,
                'error' => $exception->getMessage(),
            ]);
            // Continue with empty calendar intervals  DB availability still works
        }
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
    $requestUser = $request->user();
    $isCounselorRequest = $requestUser && $requestUser->role === 'counselor';
    $overrideAvailability = $request->boolean('override_availability', false);

    $request->validate([
        'counselor_id' => 'required|exists:counselors,id',
        'date' => $isCounselorRequest ? 'required|date|after_or_equal:today' : 'required|date|after:yesterday',
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

    if (!$overrideAvailability && $this->getCounselorBookingsForDate($counselorIds, $date) >= $this->getDailyBookingLimit($counselor)) {
        return response()->json([
            'available_slots' => [],
            'booked_slots' => [],
            'message' => 'Daily booking limit reached for this counselor'
        ]);
    }

    // Get counselor's availability for that day
    $dayAvailability = $this->getAvailabilityForDate($counselor, $date);

    if ($overrideAvailability && $isCounselorRequest) {
        // Override mode: allow booking outside set hours; still respect closed dates.
        $dayAvailability = ['08:00-17:00'];
    }

    // Counselors can book outside their set availability (emergency/urgent cases)
    if (empty($dayAvailability)) {
        if ($isCounselorRequest && $counselor->availability === null) {
            $dayAvailability = ['08:00-17:00'];
        } else {
            return response()->json([
                'available_slots' => [],
                'booked_slots' => [],
                'message' => 'No working hours for this day',
            ]);
        }
    }

    // Get booked appointments for that date - DB slot blocking statuses only
    $bookedAppointments = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
        ->get(['start_time', 'end_time', 'status']);

    $calendarIds = $this->getCounselorCalendarIds($counselorIds);

    $calendarBusyIntervals = [];
    if (!empty($calendarIds)) {
        try {
            $calendarBusyIntervals = $this->getCalendarBusyIntervalsForDate($calendarIds, $date);
        } catch (\Throwable $exception) {
            Log::warning('Google Calendar unavailable  falling back to DB-only availability', [
                'counselor_id' => $counselor->id,
                'calendar_ids' => $calendarIds,
                'error' => $exception->getMessage(),
            ]);
            // Continue with empty calendar intervals  DB availability still works
        }
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
    $requestUser = $request->user();
    $isCounselorRequest = $requestUser && $requestUser->role === 'counselor';
    $overrideAvailability = $request->boolean('override_availability', false);
    $overrides = CounselorScheduleOverride::where('counselor_id', $counselor->id)
        ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
        ->get()
        ->keyBy(function ($override) {
            return Carbon::parse($override->date)->toDateString();
        });

    $bookedAppointments = Appointment::whereIn('counselor_id', $counselorIds)
        ->whereBetween('appointment_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
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

        // Counselor override mode: allow selecting any non-past, non-closed date in the month.
        if ($overrideAvailability && $isCounselorRequest) {
            $results[$dateKey] = true;
            $currentDate->addDay();
            continue;
        }

        // Counselors can book beyond daily limit for urgent/emergency cases
        if (!$isCounselorRequest && $this->getCounselorBookingsForDate($counselorIds, $currentDate) >= $this->getDailyBookingLimit($counselor)) {
            $results[$dateKey] = false;
            $currentDate->addDay();
            continue;
        }

        if ($override && !empty($override->time_slots)) {
            $dayAvailability = $override->time_slots;
        } else {
            $dayName = strtolower($currentDate->englishDayOfWeek);
            $dayAvailability = $availability[$dayName] ?? [];

            // Only fall back to default hours if the counselor has NO availability
            // configured at all (raw column is null). If they have a schedule set,
            // respect it — an empty day means they simply don't work that day.
            if ($isCounselorRequest && empty($dayAvailability) && $counselor->availability === null) {
                $dayAvailability = ['08:00-17:00'];
            }
        }

        if (empty($dayAvailability)) {
            $results[$dateKey] = false;
            $currentDate->addDay();
            continue;
        }

        $calendarBusyIntervals = [];
        if (!empty($calendarIds)) {
            try {
                $calendarBusyIntervals = $this->getCalendarBusyIntervalsForDate($calendarIds, $currentDate);
            } catch (\Throwable $exception) {
                Log::error('Failed to load Google Calendar availability', [
                    'counselor_id' => $counselor->id,
                    'calendar_ids' => $calendarIds,
                    'error' => $exception->getMessage(),
                ]);
                // Calendar failed — still show dates based on schedule alone
            }
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

public function getDetails(Appointment $appointment)
{
    $student = Student::where('user_id', Auth::id())->first();
    if (!$student || $appointment->student_id !== $student->id) {
        abort(403);
    }

    $appointment->load(['counselor.user', 'counselor.college', 'referredCounselor.user', 'originalCounselor.user', 'sessionNotes']);

    $latestNote = $appointment->sessionNotes->sortByDesc('created_at')->first();

    return response()->json([
        'appointment' => [
            'id'                => $appointment->id,
            'case_number'       => $appointment->case_number,
            'booking_type'      => $appointment->booking_type,
            'booking_category'  => $appointment->booking_category,
            'concern'           => $appointment->concern,
            'mood_rating'       => $appointment->mood_rating,
            'referred_by'       => $appointment->referred_by,
            'referred_to_destination' => $latestNote?->referred_to_destination,
            'status'            => $appointment->status,
            'status_display'    => ucfirst(str_replace('_', ' ', $appointment->status)),
            'is_referred'       => (bool) $appointment->is_referred,
            'referral_reason'   => $appointment->referral_reason,
            'cancellation_reason' => $appointment->cancellation_reason,
            'reschedule_reason'   => $appointment->reschedule_reason,
            'is_appointment_high_risk' => (bool) $appointment->is_appointment_high_risk,
            'appointment_high_risk_notes' => $appointment->appointment_high_risk_notes,
        ],
        'referral' => [
            'referred_to_name'   => $appointment->referredCounselor?->user
                ? $appointment->referredCounselor->user->first_name . ' ' . $appointment->referredCounselor->user->last_name
                : null,
            'referred_from_name' => $appointment->originalCounselor?->user
                ? $appointment->originalCounselor->user->first_name . ' ' . $appointment->originalCounselor->user->last_name
                : null,
        ],
        'counselor' => [
            'name'     => $appointment->counselor->user->first_name . ' ' . $appointment->counselor->user->last_name,
            'position' => $appointment->counselor->position,
            'college'  => $appointment->counselor->college->name ?? 'N/A',
        ],
        'formatted_date'          => $appointment->appointment_date->format('F j, Y'),
        'formatted_time'          => \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') . ' – ' . \Carbon\Carbon::parse($appointment->end_time)->format('g:i A'),
        'formatted_proposed_date' => $appointment->proposed_date?->format('F j, Y'),
        'formatted_proposed_time' => ($appointment->proposed_start_time && $appointment->proposed_end_time)
            ? \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') . ' – ' . \Carbon\Carbon::parse($appointment->proposed_end_time)->format('g:i A')
            : null,
        'formatted_referral_date' => $appointment->referral_requested_at?->format('F j, Y g:i A'),
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'counselor_id' => 'required|exists:counselors,id',
        'appointment_date' => 'required|date|after:yesterday',
        'start_time' => 'required|date_format:H:i',
        'booking_type' => 'required|in:Initial Interview,Counseling,Consultation',
        'booking_category' => 'required|in:online,walk-in,referred,called-in',
        'concern' => 'required|string|max:2000',
        'mood_rating' => 'nullable|string|max:50',
        'referred_by' => 'nullable|string|max:255',
    ]);

    $student = Student::where('user_id', Auth::id())->first();
    $counselor = Counselor::findOrFail($request->counselor_id);
    $counselorIds = $this->getCounselorAssignmentIds($counselor);
    $date = Carbon::parse($request->appointment_date);

    if (!$student) {
        return redirect()->back()->with('error', 'Student profile not found.');
    }

    $studentNeedsInitialInterview = false; // Initial Interview is optional — students can book Counseling/Consultation freely
    if (false) {
    }

    if ($request->booking_type === 'Initial Interview') {
        $hasInitialInterviewAppointment = Appointment::where('student_id', $student->id)
            ->where('booking_type', 'Initial Interview')
            ->whereNotIn('status', ['cancelled', 'rejected', 'no_show'])
            ->exists();

        if ($hasInitialInterviewAppointment) {
            return redirect()->back()->with('error', 'You already have an Initial Interview appointment booked. Only one Initial Interview is allowed.');
        }
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

    // Check if slot is still available
    $existingAppointment = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $request->appointment_date)
        ->where('start_time', $request->start_time)
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
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

        // Calendar unavailable  proceeding with DB-only slot validation
    }

    DB::beginTransaction();

    try {
        $appointmentHighRiskKeywords = [
            'Depression / depressive thoughts',
            'Hurts self',
            'Self-destructive acting out',
            'Aggression resulting from conflict/s',
        ];
        $concernText   = $request->concern ?? '';
        $moodRating    = $request->mood_rating ?? '';
        $highRiskMoods = ['1 - Very Overwhelmed', '2 - Struggling'];

        $triggeredKeywords = collect($appointmentHighRiskKeywords)
            ->filter(fn($kw) => str_contains($concernText, $kw))
            ->values()
            ->toArray();
        $triggeredMood = in_array($moodRating, $highRiskMoods) ? $moodRating : null;

        $isAppointmentHighRisk = count($triggeredKeywords) > 0 || $triggeredMood !== null;

        $highRiskReasons = [];
        if (count($triggeredKeywords) > 0) {
            $highRiskReasons[] = 'Concern: ' . implode(', ', $triggeredKeywords);
        }
        if ($triggeredMood) {
            $highRiskReasons[] = 'Mood at booking: ' . $triggeredMood;
        }
        $highRiskNotes = $isAppointmentHighRisk ? implode(' | ', $highRiskReasons) : null;

        $appointment = Appointment::create([
            'student_id' => $student->id,
            'counselor_id' => $request->counselor_id,
            'appointment_date' => $request->appointment_date,
            'start_time' => $request->start_time,
            'end_time' => $endTimeFormatted,
            'booking_type' => $request->booking_type,
            'booking_category' => $request->booking_category,
            'concern' => $request->concern,
            'mood_rating' => $request->filled('mood_rating') ? $request->mood_rating : null,
            'referred_by' => $request->filled('referred_by') ? $request->referred_by : null,
            'is_appointment_high_risk' => $isAppointmentHighRisk,
            'appointment_high_risk_notes' => $highRiskNotes,
            'status' => 'pending'
        ]);

        $eventData = [
            'name' => 'Counseling Appointment - ' . $student->user->first_name . ' ' . $student->user->last_name,
            'description' => "Student ID: {$student->student_id}\nConcern: {$request->concern}",
            'startDateTime' => $slotStartDateTime,
            'endDateTime' => $slotEndDateTime,
        ];

        if ($counselor->google_calendar_id) {
            try {
                $event = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);
                $appointment->update(['google_calendar_event_id' => $event->id]);
            } catch (\Throwable $calendarException) {
                Log::warning('Google Calendar event skipped for student-booked appointment', [
                    'counselor_id' => $counselor->id,
                    'error' => $calendarException->getMessage(),
                ]);
            }
        }

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

    // Notify counselor of new booking
    try {
        $appointment->load(['student.user', 'counselor.user']);
        Mail::to($appointment->counselor->user->email)->send(new AppointmentBooked($appointment));
        $appointment->counselor->user->notify(new AppointmentBookedNotification($appointment));
    } catch (\Throwable $e) {
        Log::warning('Failed to send booking notification email', ['error' => $e->getMessage()]);
    }

    return redirect()->route('appointments.index')
        ->with('success', 'Appointment booked successfully! It is now pending approval.');
}

    public function cancel(Request $request, Appointment $appointment)
    {
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student || $appointment->student_id !== $student->id) {
            return redirect()->back()->with('error', 'You can only cancel your own appointments.');
        }

        if (!in_array($appointment->status, ['pending', 'approved', 'rescheduled', 'reschedule_requested', 'reschedule_rejected'], true)) {
            return redirect()->back()->with('error', 'This appointment cannot be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $calendarService = new GoogleCalendarService();
        if ($appointment->google_calendar_event_id && $appointment->counselor && $appointment->counselor->google_calendar_id) {
            try {
                $calendarService->deleteEvent($appointment->google_calendar_event_id, $appointment->counselor->google_calendar_id);
            } catch (\Throwable $e) {
                Log::warning('Failed to delete calendar event on cancel', ['error' => $e->getMessage()]);
            }
        }

        $existingNotes = $appointment->notes ?? '';
        $appointment->update([
            'status'                  => 'cancelled',
            'notes'                   => trim($existingNotes . "\nCancelled by student on " . now()->toDateTimeString()),
            'cancellation_reason'     => $request->cancellation_reason,
            'google_calendar_event_id' => null,
            'proposed_date'           => null,
            'proposed_start_time'     => null,
            'proposed_end_time'       => null,
            'reschedule_reason'       => null,
            'reschedule_requested_at' => null,
        ]);

        try {
            $appointment->load(['student.user', 'counselor.user']);
            Mail::to($appointment->counselor->user->email)->send(new AppointmentCancelled($appointment));
            $appointment->counselor->user->notify(new AppointmentCancelledNotification($appointment));
        } catch (\Throwable $e) {
            Log::warning('Failed to send cancellation notification email', ['error' => $e->getMessage()]);
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment cancelled successfully.')
            ->withErrors([]);
    }

public function updateStatus(Request $request, Appointment $appointment)
{
    $request->validate([
        'status' => 'required|in:approved,cancelled,no_show,completed,referred,rescheduled,reschedule_requested,reschedule_rejected',
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

    if ($request->status === 'cancelled' || $request->status === 'no_show') {
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

    $statusMessages = [
        'approved'   => 'Appointment approved successfully.',
        'no_show'    => 'Appointment marked as no show.',
        'cancelled'  => 'Appointment cancelled.',
        'completed'  => 'Appointment marked as completed.',
        'referred'   => 'Appointment referred to another counselor successfully.',
        'rescheduled'=> 'Appointment rescheduled successfully.'
    ];

    // Safe lookup with fallback
    $message = $statusMessages[$request->status] ?? 'Status updated successfully.';

    // Notify student of status change (approved, cancelled, no_show, completed)
    if (in_array($request->status, ['approved', 'cancelled', 'no_show', 'completed'], true)) {
        try {
            $appointment->load(['student.user', 'counselor.user']);
            Mail::to($appointment->student->user->email)
                ->send(new AppointmentStatusChanged($appointment, $request->status));
            $appointment->student->user->notify(new AppointmentStatusChangedNotification($appointment, $request->status));
        } catch (\Throwable $e) {
            Log::warning('Failed to send status-changed notification email', ['error' => $e->getMessage()]);
        }
    }

    return redirect()->back()->with('success', $message);
}

public function reschedule(Request $request, Appointment $appointment)
{
    $request->validate([
        'appointment_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required|date_format:H:i',
        'reason' => 'nullable|string|max:500',
    ]);

    $counselor = Counselor::where('user_id', Auth::id())->first();
    if (!$counselor) {
        return redirect()->back()->with('error', 'Counselor profile not found.');
    }

    $counselorIds = Counselor::where('user_id', $counselor->user_id)->pluck('id')->all();

    // For referred appointments, allow the referred-to counselor to reschedule directly
    $isReferredCounselor = $appointment->status === 'referred'
        && in_array((int) $appointment->referred_to_counselor_id, array_map('intval', $counselorIds), true);

    if (!$isReferredCounselor && !in_array($appointment->getEffectiveCounselorId(), $counselorIds, true)) {
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
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
        ->where('id', '!=', $appointment->id)
        ->count();

    $overrideAvailability = $request->boolean('override_availability', false);

    if (!$overrideAvailability && $dailyBookings >= $this->getDailyBookingLimit($counselor)) {
        return redirect()->back()->with('error', 'Daily booking limit reached. Enable "Override Availability" to reschedule beyond the limit.');
    }

    if (!$overrideAvailability) {
        $dayAvailability = $this->getAvailabilityForDate($counselor, $date);
        if (!empty($dayAvailability) && !$this->isSlotWithinAvailability($counselor, $date, $request->start_time, $endTime)) {
            return redirect()->back()->with('error', 'Selected time is outside the counselor availability. Enable "Override Availability" to reschedule outside set hours.');
        }
    }

    $existingAppointment = Appointment::whereIn('counselor_id', $counselorIds)
        ->where('appointment_date', $date->toDateString())
        ->where('start_time', $request->start_time)
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
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

        // Calendar unavailable — proceeding with DB-only slot validation
    }

    $rescheduleNote = "RESCHEDULE REQUESTED by {$counselor->user->first_name} {$counselor->user->last_name} on " .
        now()->toDateTimeString() . "\nProposed schedule: " . $date->format('Y-m-d') . " {$request->start_time} - {$endTime}";

    if ($request->filled('reason')) {
        $rescheduleNote .= "\nReason: " . $request->input('reason');
    }

    DB::beginTransaction();

    $newEvent = null;

    try {
        $oldEventId = $appointment->google_calendar_event_id;
        $oldCounselorForCalendar = $appointment->counselor;

        $eventData = [
            'name' => 'Counseling Appointment - ' . $appointment->student->user->first_name . ' ' . $appointment->student->user->last_name,
            'description' => "Student ID: {$appointment->student->student_id}\nConcern: {$appointment->concern}",
            'startDateTime' => $slotStartDateTime,
            'endDateTime' => $slotEndDateTime,
        ];

        if ($counselor->google_calendar_id) {
            try {
                $newEvent = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);
                // Delete old event from old counselor's calendar if switching counselors
                if ($oldEventId && $oldCounselorForCalendar && $oldCounselorForCalendar->google_calendar_id) {
                    $calendarService->deleteEvent($oldEventId, $oldCounselorForCalendar->google_calendar_id);
                }
            } catch (\Throwable $calendarException) {
                Log::warning('Google Calendar event skipped for reschedule request', [
                    'counselor_id' => $counselor->id,
                    'appointment_id' => $appointment->id,
                    'error' => $calendarException->getMessage(),
                ]);
            }
        }

        $updateData = [
            'status' => 'reschedule_requested',
            'proposed_date' => $date->toDateString(),
            'proposed_start_time' => $request->start_time,
            'proposed_end_time' => $endTime,
            'reschedule_reason' => $request->input('reason'),
            'reschedule_requested_at' => now(),
            'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $rescheduleNote,
            'google_calendar_event_id' => $newEvent?->id ?? $appointment->google_calendar_event_id,
        ];

        // If the referred counselor is rescheduling, transfer ownership now so the
        // student sees the correct counselor details immediately.
        if ($isReferredCounselor) {
            $updateData['counselor_id'] = $counselor->id;
            $updateData['referral_outcome'] = 'accepted';
            $updateData['referral_resolved_at'] = now();
            $updateData['referral_resolved_by_counselor_id'] = $counselor->id;
        }

        $appointment->update($updateData);

        DB::commit();
    } catch (\Throwable $exception) {
        DB::rollBack();

        Log::error('Failed to process reschedule request', [
            'counselor_id' => $counselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Failed to process reschedule request. Please try again.');
    }

    // Notify student of reschedule request
    try {
        $appointment->load(['student.user', 'counselor.user']);
        Mail::to($appointment->student->user->email)->send(new AppointmentRescheduled($appointment));
        $appointment->student->user->notify(new AppointmentRescheduledNotification($appointment));
    } catch (\Throwable $e) {
        Log::warning('Failed to send reschedule notification email', ['error' => $e->getMessage()]);
    }

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
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'This time slot is already booked for the referred counselor.');
    }

    $calendarIds = $this->getCounselorCalendarIds($referredCounselorIds);

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $request->start_time, $timezone);
    $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $endTime, $timezone);

    if (!empty($calendarIds)) {
        try {
            foreach ($calendarIds as $calendarId) {
                if (!$calendarService->isSlotAvailable($calendarId, $slotStartDateTime, $slotEndDateTime)) {
                    return redirect()->back()->with('error', 'Selected time is no longer available for the referred counselor.');
                }
            }
        } catch (\Throwable $exception) {
            Log::warning('Google Calendar check skipped for referral � falling back to DB-only', [
                'counselor_id' => $referredCounselor->id,
                'appointment_id' => $appointment->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    $referralNote = "REFERRAL REQUESTED by {$counselor->user->first_name} {$counselor->user->last_name} on " .
        now()->toDateTimeString() . "\nProposed schedule: " . $date->format('Y-m-d') . " {$request->start_time} - {$endTime}";

    if ($request->filled('referral_reason')) {
        $referralNote .= "\nReason: " . $request->input('referral_reason');
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

        // Try to create calendar event — non-fatal if calendar not configured
        if ($referredCounselor->google_calendar_id) {
            try {
                $newEvent = $calendarService->createAppointmentEvent($eventData, $referredCounselor->google_calendar_id);
            } catch (\Throwable $calendarException) {
                Log::warning('Google Calendar event creation skipped for referral', [
                    'counselor_id' => $referredCounselor->id,
                    'appointment_id' => $appointment->id,
                    'error' => $calendarException->getMessage(),
                ]);
            }
        }

        if ($oldEventId && $appointment->counselor && $appointment->counselor->google_calendar_id) {
            try {
                $calendarService->deleteEvent($oldEventId, $appointment->counselor->google_calendar_id);
            } catch (\Throwable $e) {
                Log::warning('Failed to delete old calendar event during referral', ['error' => $e->getMessage()]);
            }
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
            'google_calendar_event_id' => $newEvent?->id,
        ]);

        DB::commit();
    } catch (\Throwable $exception) {
        DB::rollBack();

        Log::error('Failed to process referral request', [
            'counselor_id' => $counselor->id,
            'appointment_id' => $appointment->id,
            'error' => $exception->getMessage(),
        ]);

        return redirect()->back()->with('error', 'Failed to process referral. Please try again.');
    }

    // Notify student of referral
    try {
        $appointment->load(['student.user', 'counselor.user', 'referredCounselor.user', 'originalCounselor.user']);
        Mail::to($appointment->student->user->email)->send(new AppointmentReferred($appointment));
        $appointment->student->user->notify(new AppointmentReferredNotification($appointment));
    } catch (\Throwable $e) {
        Log::warning('Failed to send referral notification email', ['error' => $e->getMessage()]);
    }

    // Notify referred-to counselor of the new assignment
    try {
        $appointment->loadMissing(['student.user', 'referredCounselor.user', 'originalCounselor.user']);
        Mail::to($appointment->referredCounselor->user->email)->send(new AppointmentReferredToCounselor($appointment));
        $appointment->referredCounselor->user->notify(new AppointmentReferredToCounselorNotification($appointment));
    } catch (\Throwable $e) {
        Log::warning('Failed to send referral-to-counselor notification email', ['error' => $e->getMessage()]);
    }

    return redirect()->back()->with('success', 'Referral request sent successfully.')->withErrors([]);
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
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
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

        // Calendar unavailable  proceeding with DB-only slot validation
    }

    $acceptNote = "RESCHEDULE ACCEPTED by student on " . now()->toDateTimeString() .
        "\nNew schedule: " . $date->format('Y-m-d') . " {$startTime} - {$endTime}";

    $appointment->update([
        'appointment_date' => $date->toDateString(),
        'start_time' => $startTime,
        'end_time' => $endTime,
        'status' => 'rescheduled',
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $acceptNote,
        'proposed_date' => null,
        'proposed_start_time' => null,
        'proposed_end_time' => null,
        'reschedule_reason' => null,
        'reschedule_requested_at' => null,
    ]);

    // Notify counselor that student accepted reschedule
    try {
        $appointment->load(['student.user', 'counselor.user']);
        Mail::to($appointment->counselor->user->email)->send(new RescheduleResponse($appointment, 'accepted'));
        $appointment->counselor->user->notify(new RescheduleResponseNotification($appointment, 'accepted'));
    } catch (\Throwable $e) {
        Log::warning('Failed to send reschedule-accepted email', ['error' => $e->getMessage()]);
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

    $request->validate([
        'cancellation_reason' => 'required|string|max:500',
    ]);

    if ($appointment->google_calendar_event_id && $appointment->counselor && $appointment->counselor->google_calendar_id) {
        try {
            $calendarService = new GoogleCalendarService();
            $calendarService->deleteEvent($appointment->google_calendar_event_id, $appointment->counselor->google_calendar_id);
        } catch (\Throwable $exception) {
            Log::warning('Failed to delete Google Calendar event on reschedule rejection', [
                'appointment_id' => $appointment->id,
                'counselor_id' => $appointment->counselor_id,
                'event_id' => $appointment->google_calendar_event_id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    $rejectNote = "RESCHEDULE REJECTED by student on " . now()->toDateTimeString();

    $appointment->update([
        'status' => 'rejected',
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $rejectNote,
        'cancellation_reason' => $request->cancellation_reason,
        'google_calendar_event_id' => null,
        'proposed_date' => null,
        'proposed_start_time' => null,
        'proposed_end_time' => null,
        'reschedule_reason' => null,
        'reschedule_requested_at' => null,
    ]);

    // Notify counselor that student rejected reschedule
    try {
        $appointment->load(['student.user', 'counselor.user']);
        Mail::to($appointment->counselor->user->email)->send(new RescheduleResponse($appointment, 'rejected'));
        $appointment->counselor->user->notify(new RescheduleResponseNotification($appointment, 'rejected'));
    } catch (\Throwable $e) {
        Log::warning('Failed to send reschedule-rejected email', ['error' => $e->getMessage()]);
    }

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

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::parse($date->toDateString() . ' ' . $startTime, $timezone);
    $slotEndDateTime = Carbon::parse($date->toDateString() . ' ' . $endTime, $timezone);

    if ($counselor->google_calendar_id) {
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
            Log::warning('Google Calendar check skipped for referral accept  falling back to DB-only', [
                'counselor_id' => $counselor->id,
                'appointment_id' => $appointment->id,
                'error' => $exception->getMessage(),
            ]);
        }
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

    // Notify student that referred counselor accepted
    try {
        $appointment->load(['student.user', 'counselor.user', 'originalCounselor.user']);
        Mail::to($appointment->student->user->email)->send(new ReferralResponse($appointment, 'accepted', 'counselor'));
        $appointment->student->user->notify(new ReferralResponseNotification($appointment, 'accepted', 'counselor'));
    } catch (\Throwable $e) {
        Log::warning('Failed to send referral-accepted-by-counselor email', ['error' => $e->getMessage()]);
    }

    return redirect()->back()->with('success', 'Referral accepted. Appointment scheduled successfully.');
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
        ->whereIn('status', $this->getDbSlotBlockingStatuses())
        ->where('id', '!=', $appointment->id)
        ->exists();

    if ($existingAppointment) {
        return redirect()->back()->with('error', 'The proposed time is no longer available.');
    }

    $calendarService = new GoogleCalendarService();
    $timezone = $this->getCalendarTimezone();
    $slotStartDateTime = Carbon::parse($date->toDateString() . ' ' . $startTime, $timezone);
    $slotEndDateTime = Carbon::parse($date->toDateString() . ' ' . $endTime, $timezone);

    if ($referredCounselor->google_calendar_id) {
        try {
            if (!$calendarService->isSlotAvailable(
                $referredCounselor->google_calendar_id,
                $slotStartDateTime,
                $slotEndDateTime,
                $appointment->google_calendar_event_id
            )) {
                return redirect()->back()->with('error', 'The proposed time is no longer available.');
            }
        } catch (\Throwable $exception) {
            Log::warning('Google Calendar check skipped for referral accept � falling back to DB-only', [
                'counselor_id' => $referredCounselor->id,
                'appointment_id' => $appointment->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    $acceptNote = "REFERRAL ACCEPTED by student on " . now()->toDateTimeString() .
        "\nNew schedule: " . $date->format('Y-m-d') . " {$startTime} - {$endTime}";

    $appointment->update([
        'counselor_id' => $referredCounselor->id,
        'appointment_date' => $date->toDateString(),
        'start_time' => $startTime,
        'end_time' => $endTime,
        'status' => 'pending',
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $acceptNote,
        'proposed_date' => null,
        'proposed_start_time' => null,
        'proposed_end_time' => null,
        'referral_previous_status' => null,
        'referral_requested_at' => null,
    ]);

    // Notify original counselor that student accepted referral
    try {
        $appointment->load(['student.user', 'counselor.user', 'originalCounselor.user']);
        $notifyEmail = $appointment->originalCounselor
            ? $appointment->originalCounselor->user->email
            : $appointment->counselor->user->email;
        $notifyUser = $appointment->originalCounselor
            ? $appointment->originalCounselor->user
            : $appointment->counselor->user;
        Mail::to($notifyEmail)->send(new ReferralResponse($appointment, 'accepted', 'student'));
        $notifyUser->notify(new ReferralResponseNotification($appointment, 'accepted', 'student'));
    } catch (\Throwable $e) {
        Log::warning('Failed to send referral-accepted email', ['error' => $e->getMessage()]);
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

    $request->validate([
        'cancellation_reason' => 'required|string|max:500',
    ]);

    $calendarId = null;
    if ($appointment->referredCounselor && $appointment->referredCounselor->google_calendar_id) {
        $calendarId = $appointment->referredCounselor->google_calendar_id;
    } elseif ($appointment->counselor && $appointment->counselor->google_calendar_id) {
        $calendarId = $appointment->counselor->google_calendar_id;
    }

    if ($appointment->google_calendar_event_id && $calendarId) {
        try {
            $calendarService = new GoogleCalendarService();
            $calendarService->deleteEvent($appointment->google_calendar_event_id, $calendarId);
        } catch (\Throwable $exception) {
            Log::warning('Failed to delete Google Calendar event on referral rejection', [
                'appointment_id' => $appointment->id,
                'counselor_id' => $appointment->counselor_id,
                'referred_to_counselor_id' => $appointment->referred_to_counselor_id,
                'event_id' => $appointment->google_calendar_event_id,
                'calendar_id' => $calendarId,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    $rejectNote = "REFERRAL CANCELLED by student on " . now()->toDateTimeString();

    $appointment->update([
        'status' => 'rejected',
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') . $rejectNote,
        'cancellation_reason' => $request->cancellation_reason,
        'google_calendar_event_id' => null,
        'referred_to_counselor_id' => null,
        'referral_reason' => null,
        'referral_previous_status' => null,
        'referral_requested_at' => null,
        'original_counselor_id' => null,
        'proposed_date' => null,
        'proposed_start_time' => null,
        'proposed_end_time' => null,
    ]);

    // Notify original counselor that student rejected referral
    try {
        $appointment->load(['student.user', 'counselor.user', 'originalCounselor.user']);
        $notifyEmail = $appointment->originalCounselor
            ? $appointment->originalCounselor->user->email
            : $appointment->counselor->user->email;
        $notifyUser = $appointment->originalCounselor
            ? $appointment->originalCounselor->user
            : $appointment->counselor->user;
        Mail::to($notifyEmail)->send(new ReferralResponse($appointment, 'rejected', 'student'));
        $notifyUser->notify(new ReferralResponseNotification($appointment, 'rejected', 'student'));
    } catch (\Throwable $e) {
        Log::warning('Failed to send referral-rejected email', ['error' => $e->getMessage()]);
    }

    return redirect()->back()->with('success', 'Referral rejected. Appointment has been closed.');
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
            ->groupBy('user_id')
            ->map(function($assignments) use ($student) {
                $first = $assignments->first();
                $collegeNames = $assignments
                    ->map(fn ($c) => $c->college->name ?? null)
                    ->filter()
                    ->unique()
                    ->values();

                $collegeLabel = $collegeNames->isEmpty() ? 'N/A' : $collegeNames->implode('/');
                $isSameCollege = $assignments->pluck('college_id')->contains($student->college_id);

                return [
                    'id' => $first->id,
                    'name' => $first->user->first_name . ' ' . $first->user->last_name,
                    'position' => $first->position,
                    'college' => $collegeLabel,
                    'same_college' => $isSameCollege,
                    'display_text' => $first->user->first_name . ' ' . $first->user->last_name .
                        ' - ' . $first->position . ' (' . $collegeLabel . ')'
                ];
            })
            ->values();

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

    private function getDbSlotBlockingStatuses(): array
    {
        return [
            'pending',
            'approved',
            'completed',
            'rescheduled',
            'reschedule_rejected'
        ];
    }

    private function getCounselorBookingsForDate(array $counselorIds, Carbon $date): int
    {
        return Appointment::whereIn('counselor_id', $counselorIds)
            ->where('appointment_date', $date->toDateString())
            ->whereIn('status', $this->getDbSlotBlockingStatuses())
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
