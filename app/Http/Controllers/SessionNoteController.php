<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\SessionNote;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\CounselorScheduleOverride;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleCalendarService;

class SessionNoteController extends Controller
{
    /**
     * Get counselor IDs for the authenticated user
     */
    private function getCounselorIds()
    {
        return Counselor::where('user_id', Auth::id())->pluck('id');
    }

    private function getAssignedColleges()
    {
        return College::whereHas('counselors', function($query) {
            $query->where('user_id', Auth::id());
        })->get();
    }

    private function getBookingStatuses(): array
    {
        return ['pending', 'approved', 'completed', 'referred'];
    }

    private function getCounselorBookingsForDate(int $counselorId, Carbon $date, ?int $excludeAppointmentId = null): int
    {
        $query = Appointment::where('counselor_id', $counselorId)
            ->where('appointment_date', $date->toDateString())
            ->whereIn('status', $this->getBookingStatuses());

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->count();
    }

    private function isSlotWithinAvailability(Counselor $counselor, Carbon $date, string $startTime, string $endTime): bool
    {
        $dayAvailability = $this->getAvailabilityForDate($counselor, $date);

        $timezone = config('app.timezone') ?: 'Asia/Manila';
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

    /**
     * Check if counselor can manage student (including cross-college referrals)
     */
private function canManageStudent($student, $counselorIds, $allColleges)
{
    // If student belongs to one of counselor's assigned colleges
    $assignedCollegeIds = $allColleges->pluck('id');
    if ($assignedCollegeIds->contains($student->college_id)) {
        return true;
    }

    // Check if counselor has ANY appointments with this student (not just referred)
    $hasAnyAppointment = Appointment::where('student_id', $student->id)
        ->where(function($query) use ($counselorIds) {
            $query->whereIn('counselor_id', $counselorIds)
                  ->orWhereIn('referred_to_counselor_id', $counselorIds);
        })
        ->exists();

    return $hasAnyAppointment;
}

public function create(Student $student, Request $request)
{
    $counselor = Counselor::where('user_id', Auth::id())->first();
    $counselorIds = $this->getCounselorIds();
    $allColleges = $this->getAssignedColleges();

    Log::info('SessionNoteController@create', [
        'counselor_id' => $counselor->id,
        'student_id' => $student->id,
        'student_college_id' => $student->college_id,
        'appointment_id' => $request->appointment_id,
        'assigned_colleges' => $allColleges->pluck('id')->toArray(),
        'has_appointment_id' => $request->has('appointment_id')
    ]);

    // Get appointment if provided
    $appointment = null;
    if ($request->has('appointment_id')) {
        $appointment = Appointment::find($request->appointment_id);

        Log::info('Appointment found', [
            'appointment_id' => $appointment ? $appointment->id : null,
            'appointment_counselor_id' => $appointment ? $appointment->counselor_id : null,
            'referred_to_counselor_id' => $appointment ? $appointment->referred_to_counselor_id : null,
            'can_manage' => $appointment ? $appointment->canBeManagedBy($counselor->id) : false
        ]);

        // Verify the counselor can manage this appointment
        if (!$appointment || !$appointment->canBeManagedBy($counselor->id)) {
            abort(403, 'You cannot add session notes for this appointment.');
        }

        Log::info('Passed appointment authorization - should skip college check');

    } else {
        Log::info('No appointment ID - checking college assignment and referrals');

        // Check if counselor can manage this student (either same college or through referrals)
        if (!$this->canManageStudent($student, $counselorIds, $allColleges)) {
            Log::warning('College and referral check failed', [
                'student_college_id' => $student->college_id,
                'assigned_college_ids' => $allColleges->pluck('id')->toArray(),
                'counselor_ids' => $counselorIds->toArray()
            ]);
            abort(403, 'You are not authorized to add session notes for this student.');
        }
    }

    // Get recent appointments for this student with the current counselor
    $recentAppointments = Appointment::with(['counselor.user'])
        ->where('student_id', $student->id)
        ->where(function($query) use ($counselorIds) {
            $query->whereIn('counselor_id', $counselorIds)
                  ->orWhereIn('referred_to_counselor_id', $counselorIds);
        })
        ->whereIn('status', ['completed', 'approved'])
        ->orderBy('appointment_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    $sessionTypes = SessionNote::getSessionTypes();
    $moodLevels = SessionNote::getMoodLevels();

    return view('counselor.session-notes.create', compact(
        'student',
        'appointment',
        'sessionTypes',
        'moodLevels',
        'allColleges',
        'recentAppointments' // Add this line
    ));
}

    /**
     * Store session notes
     */
    public function store(Request $request, Student $student)
    {
        $counselorIds = $this->getCounselorIds();
        $allColleges = $this->getAssignedColleges();

        // Verify the counselor can manage this student
        if (!$this->canManageStudent($student, $counselorIds, $allColleges)) {
            abort(403, 'You are not authorized to add session notes for this student.');
        }

        $validated = $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'notes' => 'required|string|min:10',
            'follow_up_actions' => 'nullable|string',
            'session_date' => 'required|date',
            'session_type' => 'required|in:initial,follow_up,crisis,regular',
            'mood_level' => 'nullable|in:very_low,low,neutral,good,very_good',
            'requires_follow_up' => 'boolean',
            'next_session_date' => 'nullable|date|after:session_date',
            // Follow-up appointment fields
            'followup_appointment_date' => 'nullable|date|after:session_date',
            'followup_start_time' => 'nullable|date_format:H:i',
            'followup_concern' => 'nullable|string|max:500',
            'auto_approve_followup' => 'boolean'
        ]);

        // Verify appointment belongs to this counselor and student if provided
        if ($request->filled('appointment_id')) {
            $appointment = Appointment::where('id', $request->appointment_id)
                ->where(function($query) use ($counselorIds) {
                    $query->whereIn('counselor_id', $counselorIds)
                          ->orWhereIn('referred_to_counselor_id', $counselorIds);
                })
                ->where('student_id', $student->id)
                ->first();

            if (!$appointment) {
                return redirect()->back()->with('error', 'Invalid appointment selected.');
            }
            $validated['appointment_id'] = $request->appointment_id;
        } else {
            $validated['appointment_id'] = null;
        }

        // Use the first counselor ID for the session note
        $validated['counselor_id'] = $counselorIds->first();
        $validated['student_id'] = $student->id;
        $validated['requires_follow_up'] = $request->has('requires_follow_up');

        // Create session note FIRST
        $sessionNote = SessionNote::create($validated);

        // Create follow-up appointment if requested
        $followUpAppointment = null;
        if ($request->has('requires_follow_up') &&
            $request->filled('followup_appointment_date') &&
            $request->filled('followup_start_time') &&
            $request->filled('followup_concern')) {

            $followUpAppointment = $this->createFollowupAppointment($request, $student, $counselorIds->first(), $sessionNote);
        }

        // Update the original appointment status to completed if it exists
        if ($request->filled('appointment_id')) {
            $originalAppointment = Appointment::find($request->appointment_id);
            if ($originalAppointment && $originalAppointment->status !== 'completed') {
                $originalAppointment->update(['status' => 'completed']);
            }
        }

        $successMessage = 'Session notes saved successfully!';
        if ($followUpAppointment) {
            $status = $request->has('auto_approve_followup') ? 'approved' : 'pending';
            $successMessage .= " Follow-up appointment scheduled for " .
                \Carbon\Carbon::parse($request->followup_appointment_date)->format('M j, Y') .
                " (Status: " . ucfirst($status) . ").";
        }

        return redirect()->route('counselor.session-notes.index', $student)
            ->with('success', $successMessage);
    }

    /**
     * Show session notes for a student
     */
    public function index(Student $student)
    {
        $counselorIds = $this->getCounselorIds();
        $allColleges = $this->getAssignedColleges();

        // Verify the counselor can manage this student
        if (!$this->canManageStudent($student, $counselorIds, $allColleges)) {
            abort(403, 'You are not authorized to view session notes for this student.');
        }

        $sessionNotes = SessionNote::with(['appointment', 'counselor.user'])
            ->where('student_id', $student->id)
            ->whereIn('counselor_id', $counselorIds)
            ->orderBy('session_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('counselor.session-notes.index', compact('student', 'sessionNotes'));
    }


    /**
     * Create follow-up appointment (without creating session notes)
     */
    private function createFollowupAppointment($request, $student, $counselorId, $sessionNote)
    {
        $date = Carbon::parse($request->followup_appointment_date);
        $startTime = Carbon::parse($request->followup_start_time);
        $endTime = $startTime->copy()->addHour();
        $endTimeFormatted = $endTime->format('H:i');
        $counselor = Counselor::find($counselorId);

        if (!$counselor) {
            session()->flash('warning', 'Session notes saved, but follow-up could not be scheduled (counselor not found).');
            return null;
        }

        if ($this->isDateClosed($counselor, $date)) {
            session()->flash('warning', 'Session notes saved, but the counselor is not available on that date.');
            return null;
        }

        if ($this->getCounselorBookingsForDate($counselorId, $date) >= $this->getDailyBookingLimit($counselor)) {
            session()->flash('warning', 'Session notes saved, but the counselor daily booking limit has been reached.');
            return null;
        }

        if (!$this->isSlotWithinAvailability($counselor, $date, $request->followup_start_time, $endTimeFormatted)) {
            session()->flash('warning', 'Session notes saved, but the follow-up time is outside counselor availability.');
            return null;
        }

        // Check if slot is still available
        $existingAppointment = Appointment::where('counselor_id', $counselorId)
            ->where('appointment_date', $request->followup_appointment_date)
            ->where('start_time', $request->followup_start_time)
            ->whereIn('status', $this->getBookingStatuses())
            ->exists();

        if ($existingAppointment) {
            // Don't fail, just notify that follow-up couldn't be scheduled
            session()->flash('warning', 'Session notes saved, but follow-up time slot was no longer available. Please schedule manually.');
            return null;
        }

        if (!$counselor->google_calendar_id) {
            session()->flash('warning', 'Session notes saved, but counselor calendar is not configured.');
            return null;
        }

        $calendarService = new GoogleCalendarService();
        $timezone = config('app.timezone') ?: 'Asia/Manila';
        $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $request->followup_start_time, $timezone);
        $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $endTimeFormatted, $timezone);

        try {
            if (!$calendarService->isSlotAvailable($counselor->google_calendar_id, $slotStartDateTime, $slotEndDateTime)) {
                session()->flash('warning', 'Session notes saved, but the follow-up slot is no longer available.');
                return null;
            }
        } catch (\Throwable $exception) {
            Log::error('Failed to check follow-up Google Calendar availability', [
                'counselor_id' => $counselorId,
                'calendar_id' => $counselor->google_calendar_id,
                'error' => $exception->getMessage(),
            ]);
            session()->flash('warning', 'Session notes saved, but calendar availability could not be verified.');
            return null;
        }

        $event = null;
        try {
            DB::beginTransaction();

            $appointment = Appointment::create([
                'student_id' => $student->id,
                'counselor_id' => $counselorId,
                'appointment_date' => $request->followup_appointment_date,
                'start_time' => $request->followup_start_time,
                'end_time' => $endTimeFormatted,
                'concern' => $request->followup_concern,
                'status' => $request->has('auto_approve_followup') ? 'approved' : 'pending',
                'notes' => "Follow-up appointment created from session notes #{$sessionNote->id}",
                // Important: Do NOT link this back to the session note to avoid circular reference
                'session_note_id' => null
            ]);

            $eventData = [
                'name' => 'Counseling Follow-up - ' . $student->user->first_name . ' ' . $student->user->last_name,
                'description' => "Student ID: {$student->student_id}\nConcern: {$request->followup_concern}\nSession Note: {$sessionNote->id}",
                'startDateTime' => $slotStartDateTime,
                'endDateTime' => $slotEndDateTime,
            ];

            $event = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);

            $appointment->update([
                'google_calendar_event_id' => $event->id
            ]);

            DB::commit();
        } catch (\Throwable $exception) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            if ($event) {
                $calendarService->deleteEvent($event->id, $counselor->google_calendar_id);
            }
            Log::error('Failed to create follow-up appointment with Google Calendar event', [
                'counselor_id' => $counselorId,
                'student_id' => $student->id,
                'error' => $exception->getMessage(),
            ]);
            session()->flash('warning', 'Session notes saved, but follow-up appointment could not be scheduled.');
            return null;
        }

        // Update the session note to indicate it has a follow-up
        $sessionNote->update([
            'requires_follow_up' => true,
            'next_session_date' => $request->followup_appointment_date
        ]);

        return $appointment;
    }

    /**
     * Show session note details
     */
    public function show(SessionNote $sessionNote)
    {
        // Verify the counselor owns this session note (from any assigned college)
        $counselorIds = $this->getCounselorIds();
        if (!$counselorIds->contains($sessionNote->counselor_id)) {
            abort(403);
        }

        return view('counselor.session-notes.show', compact('sessionNote'));
    }

    /**
     * Edit session notes
     */
    public function edit(SessionNote $sessionNote)
    {
        // Verify the counselor owns this session note (from any assigned college)
        $counselorIds = $this->getCounselorIds();
        if (!$counselorIds->contains($sessionNote->counselor_id)) {
            abort(403);
        }

        // Load the student relationship
        $sessionNote->load('student');

        $sessionTypes = SessionNote::getSessionTypes();
        $moodLevels = SessionNote::getMoodLevels();
        $allColleges = $this->getAssignedColleges();

        return view('counselor.session-notes.edit', compact(
            'sessionNote',
            'sessionTypes',
            'moodLevels',
            'allColleges'
        ));
    }

    /**
     * Update session notes
     */
public function update(Request $request, SessionNote $sessionNote)
{
    // Verify the counselor owns this session note (from any assigned college)
    $counselorIds = $this->getCounselorIds();
    if (!$counselorIds->contains($sessionNote->counselor_id)) {
        abort(403);
    }

    $validated = $request->validate([
        'notes' => 'required|string|min:10',
        'follow_up_actions' => 'nullable|string',
        'session_date' => 'required|date',
        'session_type' => 'required|in:initial,follow_up,crisis,regular',
        'mood_level' => 'nullable|in:very_low,low,neutral,good,very_good',
        'requires_follow_up' => 'boolean',
        'next_session_date' => 'nullable|date|after:session_date',
        // Follow-up appointment fields
        'followup_appointment_date' => 'nullable|date|after:session_date',
        'followup_start_time' => 'nullable|date_format:H:i',
        'followup_concern' => 'nullable|string|max:500',
        'auto_approve_followup' => 'boolean'
    ]);

    $validated['requires_follow_up'] = $request->has('requires_follow_up');

    // Update session note
    $sessionNote->update($validated);

    // Handle follow-up appointment - FIXED LOGIC
// Handle follow-up appointment - PRESERVE EXISTING APPOINTMENTS
$hasFollowUpData = $request->filled('followup_appointment_date') &&
                  $request->filled('followup_start_time') &&
                  $request->filled('followup_concern');

if ($request->has('requires_follow_up') && $hasFollowUpData) {
    // Create or update follow-up appointment
    $this->updateOrCreateFollowupAppointment($request, $sessionNote);
}
// Never delete appointments automatically - only update or create
    // If requires_follow_up is checked but data is incomplete, do nothing (preserve existing appointment)

    $successMessage = 'Session notes updated successfully!';
    if ($request->has('requires_follow_up') && $hasFollowUpData) {
        $successMessage .= ' Follow-up appointment updated.';
    } elseif (!$request->has('requires_follow_up') && $sessionNote->appointment) {
        $successMessage .= ' Follow-up appointment removed.';
    }

    return redirect()->route('counselor.session-notes.index', $sessionNote->student)
        ->with('success', $successMessage);
}

    /**
     * Update or create follow-up appointment
     */
    private function updateOrCreateFollowupAppointment($request, $sessionNote)
    {
        $counselorId = $sessionNote->counselor_id;
        $student = $sessionNote->student;
        $date = Carbon::parse($request->followup_appointment_date);

        $startTime = Carbon::parse($request->followup_start_time);
        $endTime = $startTime->copy()->addHour();
        $endTimeFormatted = $endTime->format('H:i');
        $appointment = $sessionNote->appointment;
        $excludeAppointmentId = $appointment?->id;
        $counselor = Counselor::find($counselorId);

        if (!$counselor) {
            session()->flash('warning', 'Session notes updated, but follow-up could not be scheduled (counselor not found).');
            return;
        }

        if ($this->isDateClosed($counselor, $date)) {
            session()->flash('warning', 'Session notes updated, but the counselor is not available on that date.');
            return;
        }

        if ($this->getCounselorBookingsForDate($counselorId, $date, $excludeAppointmentId) >= $this->getDailyBookingLimit($counselor)) {
            session()->flash('warning', 'Session notes updated, but the counselor daily booking limit has been reached.');
            return;
        }

        if (!$this->isSlotWithinAvailability($counselor, $date, $request->followup_start_time, $endTimeFormatted)) {
            session()->flash('warning', 'Session notes updated, but the follow-up time is outside counselor availability.');
            return;
        }

        // Check if slot is still available (excluding the current follow-up appointment if it exists)
        $existingAppointmentQuery = Appointment::where('counselor_id', $counselorId)
            ->where('appointment_date', $request->followup_appointment_date)
            ->where('start_time', $request->followup_start_time)
            ->whereIn('status', $this->getBookingStatuses());

        // Exclude the current follow-up appointment if it exists
        if ($excludeAppointmentId) {
            $existingAppointmentQuery->where('id', '!=', $excludeAppointmentId);
        }

        $existingAppointment = $existingAppointmentQuery->exists();

        if ($existingAppointment) {
            session()->flash('warning', 'Session notes updated, but follow-up time slot was no longer available. Please schedule manually.');
            return;
        }

        if (!$counselor->google_calendar_id) {
            session()->flash('warning', 'Session notes updated, but counselor calendar is not configured.');
            return;
        }

        $calendarService = new GoogleCalendarService();
        $timezone = config('app.timezone') ?: 'Asia/Manila';
        $slotStartDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $request->followup_start_time, $timezone);
        $slotEndDateTime = Carbon::createFromFormat('Y-m-d H:i', $date->toDateString() . ' ' . $endTimeFormatted, $timezone);

        try {
            if (!$calendarService->isSlotAvailable(
                $counselor->google_calendar_id,
                $slotStartDateTime,
                $slotEndDateTime,
                $appointment?->google_calendar_event_id
            )) {
                session()->flash('warning', 'Session notes updated, but the follow-up slot is no longer available.');
                return;
            }
        } catch (\Throwable $exception) {
            Log::error('Failed to check follow-up Google Calendar availability', [
                'counselor_id' => $counselorId,
                'calendar_id' => $counselor->google_calendar_id,
                'error' => $exception->getMessage(),
            ]);
            session()->flash('warning', 'Session notes updated, but calendar availability could not be verified.');
            return;
        }

        $event = null;
        $oldEventId = $appointment?->google_calendar_event_id;

        try {
            $eventData = [
                'name' => 'Counseling Follow-up - ' . $student->user->first_name . ' ' . $student->user->last_name,
                'description' => "Student ID: {$student->student_id}\nConcern: {$request->followup_concern}\nSession Note: {$sessionNote->id}",
                'startDateTime' => $slotStartDateTime,
                'endDateTime' => $slotEndDateTime,
            ];

            $event = $calendarService->createAppointmentEvent($eventData, $counselor->google_calendar_id);

            DB::beginTransaction();

            // Update existing appointment or create new one
            if ($appointment) {
                $appointment->update([
                    'appointment_date' => $request->followup_appointment_date,
                    'start_time' => $request->followup_start_time,
                    'end_time' => $endTimeFormatted,
                    'concern' => $request->followup_concern,
                    'status' => $request->has('auto_approve_followup') ? 'approved' : 'pending',
                    'notes' => "Follow-up appointment updated from session notes #{$sessionNote->id}",
                    'google_calendar_event_id' => $event->id,
                ]);
            } else {
                $appointment = Appointment::create([
                    'student_id' => $student->id,
                    'counselor_id' => $counselorId,
                    'appointment_date' => $request->followup_appointment_date,
                    'start_time' => $request->followup_start_time,
                    'end_time' => $endTimeFormatted,
                    'concern' => $request->followup_concern,
                    'status' => $request->has('auto_approve_followup') ? 'approved' : 'pending',
                    'notes' => "Follow-up appointment created from session notes #{$sessionNote->id}",
                    'google_calendar_event_id' => $event->id,
                ]);

                // Link the appointment to the session note
                $sessionNote->update([
                    'appointment_id' => $appointment->id
                ]);
            }

            DB::commit();

            if ($oldEventId && $oldEventId !== $event->id) {
                $calendarService->deleteEvent($oldEventId, $counselor->google_calendar_id);
            }
        } catch (\Throwable $exception) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            if ($event) {
                $calendarService->deleteEvent($event->id, $counselor->google_calendar_id);
            }
            Log::error('Failed to update follow-up appointment with Google Calendar event', [
                'counselor_id' => $counselorId,
                'student_id' => $student->id,
                'error' => $exception->getMessage(),
            ]);
            session()->flash('warning', 'Session notes updated, but follow-up appointment could not be scheduled.');
        }
    }

    /**
     * Get session notes for a student (AJAX)
     */
    public function getStudentNotes(Student $student, Request $request)
    {
        $counselorIds = $this->getCounselorIds();

        $query = SessionNote::with(['appointment'])
            ->where('student_id', $student->id)
            ->whereIn('counselor_id', $counselorIds)
            ->orderBy('session_date', 'desc');

        // Filter by appointment if provided
        if ($request->has('appointment_id')) {
            $query->where('appointment_id', $request->appointment_id);
        }

        $sessionNotes = $query->get();

        // Transform the data to include formatted values
        $formattedNotes = $sessionNotes->map(function ($note) use ($student) {
            return [
                'id' => $note->id,
                'appointment_id' => $note->appointment_id,
                'student_name' => $student->user->first_name . ' ' . $student->user->last_name,
                'student_id' => $student->student_id,
                'notes' => $note->notes,
                'follow_up_actions' => $note->follow_up_actions,
                'session_date' => $note->session_date ? \Carbon\Carbon::parse($note->session_date)->format('M j, Y') : 'N/A',
                'session_type' => $note->session_type,
                'session_type_label' => $this->getSessionTypeLabel($note->session_type),
                'mood_level' => $note->mood_level,
                'mood_level_label' => $this->getMoodLevelLabel($note->mood_level),
                'requires_follow_up' => $note->requires_follow_up,
                'next_session_date' => $note->next_session_date ? \Carbon\Carbon::parse($note->next_session_date)->format('M j, Y') : null,
                'session_duration' => $this->getSessionDuration($note),
                'created_at' => $note->created_at,
                'updated_at' => $note->updated_at,
            ];
        });

        return response()->json($formattedNotes);
    }

    /**
     * Get session duration info
     */
    private function getSessionDuration($note)
    {
        if ($note->appointment && $note->appointment->start_time && $note->appointment->end_time) {
            $start = \Carbon\Carbon::parse($note->appointment->start_time);
            $end = \Carbon\Carbon::parse($note->appointment->end_time);
            $duration = $end->diffInMinutes($start);
            return $duration . ' minutes';
        }
        return 'Duration not specified';
    }

    /**
     * Get human-readable session type label
     */
    private function getSessionTypeLabel($sessionType)
    {
        $labels = [
            'initial' => 'Initial Session',
            'follow_up' => 'Follow-up Session',
            'crisis' => 'Crisis Intervention',
            'regular' => 'Regular Session'
        ];

        return $labels[$sessionType] ?? 'Unknown Session Type';
    }

    /**
     * Get human-readable mood level label
     */
    private function getMoodLevelLabel($moodLevel)
    {
        $labels = [
            'very_low' => 'Very Low',
            'low' => 'Low',
            'neutral' => 'Neutral',
            'good' => 'Good',
            'very_good' => 'Very Good'
        ];

        return $labels[$moodLevel] ?? 'Not Specified';
    }

    // In SessionNoteController - update dashboard method

/**
 * Show comprehensive session notes dashboard with session numbers
 */
public function dashboard(Request $request)
{
    $counselorIds = $this->getCounselorIds();
    $allColleges = $this->getAssignedColleges();

    // Get student session counts - include students from referrals
    $studentSessionCounts = SessionNote::whereIn('counselor_id', $counselorIds)
        ->select('student_id', DB::raw('COUNT(*) as session_count'))
        ->groupBy('student_id')
        ->get()
        ->keyBy('student_id');

    // Calculate statistics
    $totalNotes = SessionNote::whereIn('counselor_id', $counselorIds)->count();
    $totalStudents = $studentSessionCounts->count();
    $notesThisMonth = SessionNote::whereIn('counselor_id', $counselorIds)
        ->whereMonth('session_date', now()->month)
        ->whereYear('session_date', now()->year)
        ->count();
    $crisisSessions = SessionNote::whereIn('counselor_id', $counselorIds)
        ->where('session_type', 'crisis')
        ->count();

    // Calculate average sessions per student
    $averageSessionsPerStudent = $totalStudents > 0 ? $totalNotes / $totalStudents : 0;

    // Base query for session notes with session numbers
    $query = SessionNote::with([
            'student.user',
            'student.college',
            'appointment',
            'counselor.user'
        ])
        ->whereIn('counselor_id', $counselorIds)
        ->orderBy('session_date', 'desc')
        ->orderBy('created_at', 'desc');

    // Search functionality
    if ($request->has('search') && $request->search) {
        $search = strtolower($request->search);
        $query->where(function($q) use ($search) {
            $q->whereHas('student.user', function($q) use ($search) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$search}%"]);
            })
            ->orWhereHas('student', function($q) use ($search) {
                $q->whereRaw('LOWER(student_id) LIKE ?', ["%{$search}%"]);
            })
            ->orWhereHas('student.college', function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
            })
            ->orWhereRaw('LOWER(notes) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(follow_up_actions) LIKE ?', ["%{$search}%"]);
        });
    }

    // Session type filter
    if ($request->has('session_type') && $request->session_type) {
        $query->where('session_type', $request->session_type);
    }

    // Date range filter
    if ($request->has('date_range') && $request->date_range) {
        $now = Carbon::now();
        switch ($request->date_range) {
            case 'today':
                $query->whereDate('session_date', $now->toDateString());
                break;
            case 'week':
                $query->whereBetween('session_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'month':
                $query->whereBetween('session_date', [
                    $now->startOfMonth()->toDateString(),
                    $now->endOfMonth()->toDateString()
                ]);
                break;
            case 'last_month':
                $lastMonth = $now->subMonth();
                $query->whereBetween('session_date', [
                    $lastMonth->startOfMonth()->toDateString(),
                    $lastMonth->endOfMonth()->toDateString()
                ]);
                break;
        }
    }

    // College filter - include all students that counselor has session notes for
    if ($request->has('college') && $request->college) {
        $query->whereHas('student', function($q) use ($request) {
            $q->where('college_id', $request->college);
        });
    }

    // Get session notes with pagination
    $sessionNotes = $query->paginate(15);

    // Add session numbers and total sessions per student to each note
    $sessionNotes->getCollection()->transform(function ($note) use ($studentSessionCounts) {
        // Get total sessions for this student
        $totalSessions = $studentSessionCounts->get($note->student_id)?->session_count ?? 0;

        // Calculate session number for this specific note
        $sessionNumber = SessionNote::where('student_id', $note->student_id)
            ->where('counselor_id', $note->counselor_id)
            ->where('created_at', '<=', $note->created_at)
            ->count();

        // Add computed properties
        $note->session_number = $sessionNumber;
        $note->student_total_sessions = $totalSessions;

        return $note;
    });

    $sessionTypes = SessionNote::getSessionTypes();
    $colleges = College::whereIn('id', $allColleges->pluck('id'))->get();

    return view('counselor.session-notes.dashboard', compact(
        'sessionNotes',
        'totalNotes',
        'totalStudents',
        'notesThisMonth',
        'crisisSessions',
        'averageSessionsPerStudent',
        'sessionTypes',
        'colleges',
        'allColleges'
    ));
}

/**
 * Get session note details for modal (AJAX)
 */
/**
 * Get session note details for modal (AJAX)
 */
public function getSessionNoteDetails(SessionNote $sessionNote)
{
    // Verify the counselor owns this session note
    $counselorIds = $this->getCounselorIds();

    Log::info('Session note details request', [
        'session_note_id' => $sessionNote->id,
        'session_note_counselor_id' => $sessionNote->counselor_id,
        'current_counselor_ids' => $counselorIds->toArray(),
        'authorized' => $counselorIds->contains($sessionNote->counselor_id)
    ]);

    if (!$counselorIds->contains($sessionNote->counselor_id)) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    try {
        // Load relationships
        $sessionNote->load(['student.user', 'student.college', 'appointment']);

        // Calculate session number and total sessions
        $sessionNumber = SessionNote::where('student_id', $sessionNote->student_id)
            ->where('counselor_id', $sessionNote->counselor_id)
            ->where('created_at', '<=', $sessionNote->created_at)
            ->count();

        $totalSessions = SessionNote::where('student_id', $sessionNote->student_id)
            ->where('counselor_id', $sessionNote->counselor_id)
            ->count();

        $responseData = [
            'id' => $sessionNote->id,
            'student' => [
                'id' => $sessionNote->student->id,
                'user' => [
                    'first_name' => $sessionNote->student->user->first_name,
                    'last_name' => $sessionNote->student->user->last_name,
                ],
                'student_id' => $sessionNote->student->student_id,
                'year_level' => $sessionNote->student->year_level,
                'college' => [
                    'name' => $sessionNote->student->college->name ?? null,
                ],
            ],
            'session_date_formatted' => $sessionNote->session_date->format('F j, Y'),
            'session_type' => $sessionNote->session_type,
            'session_type_label' => $sessionNote->session_type_label,
            'mood_level' => $sessionNote->mood_level,
            'mood_level_label' => $sessionNote->mood_level_label,
            'notes' => $sessionNote->notes,
            'follow_up_actions' => $sessionNote->follow_up_actions,
            'requires_follow_up' => $sessionNote->requires_follow_up,
            'next_session_date' => $sessionNote->next_session_date?->format('F j, Y'),
            'session_number' => $sessionNumber,
            'total_sessions' => $totalSessions,
        ];

        // Add appointment time if exists
        if ($sessionNote->appointment) {
            $responseData['appointment_time'] =
                \Carbon\Carbon::parse($sessionNote->appointment->start_time)->format('g:i A') . ' - ' .
                \Carbon\Carbon::parse($sessionNote->appointment->end_time)->format('g:i A');
        } else {
            $responseData['appointment_time'] = null;
        }

        Log::info('Session note details response', [
            'session_note_id' => $sessionNote->id,
            'has_appointment' => !is_null($sessionNote->appointment),
            'session_number' => $sessionNumber,
            'total_sessions' => $totalSessions
        ]);

        return response()->json($responseData);

    } catch (\Exception $e) {
        Log::error('Error fetching session note details', [
            'session_note_id' => $sessionNote->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'error' => 'Failed to load session note details: ' . $e->getMessage()
        ], 500);
    }
}}
