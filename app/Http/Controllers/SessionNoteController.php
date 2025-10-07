<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\SessionNote;
use App\Models\Student;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SessionNoteController extends Controller
{
    /**
     * Get counselor IDs for the authenticated user
     */
    private function getCounselorIds()
    {
        return \App\Models\Counselor::where('user_id', Auth::id())->pluck('id');
    }

    /**
     * Get all colleges assigned to the counselor
     */
    private function getAssignedColleges()
    {
        return \App\Models\Counselor::with('college')
            ->where('user_id', Auth::id())
            ->get()
            ->pluck('college')
            ->filter();
    }

    /**
     * Show comprehensive session notes dashboard with session numbers
     */
    public function dashboard(Request $request)
    {
        $counselorIds = $this->getCounselorIds();
        $allColleges = $this->getAssignedColleges();

        // Get student session counts first
        $studentSessionCounts = SessionNote::whereIn('counselor_id', $counselorIds)
            ->select('student_id', DB::raw('COUNT(*) as session_count'))
            ->groupBy('student_id')
            ->get()
            ->keyBy('student_id');

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

        // College filter
        if ($request->has('college') && $request->college) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('college_id', $request->college);
            });
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
                    $lastMonth = $now->copy()->subMonth();
                    $query->whereBetween('session_date', [
                        $lastMonth->startOfMonth()->toDateString(),
                        $lastMonth->endOfMonth()->toDateString()
                    ]);
                    break;
            }
        }

        $sessionNotes = $query->paginate(20);

        // Calculate session numbers and total sessions per student
        $sessionNotes->getCollection()->transform(function ($note) use ($studentSessionCounts, $counselorIds) {
            // Get all session dates for this student with this counselor to calculate session number
            $studentSessions = SessionNote::where('student_id', $note->student_id)
                ->whereIn('counselor_id', $counselorIds)
                ->orderBy('session_date', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            // Find the position of this session in the ordered list
            $sessionNumber = 1;
            foreach ($studentSessions as $index => $session) {
                if ($session->id === $note->id) {
                    $sessionNumber = $index + 1;
                    break;
                }
            }

            // Add computed properties
            $note->session_number = $sessionNumber;
            $note->student_total_sessions = $studentSessionCounts[$note->student_id]->session_count ?? 0;

            return $note;
        });

        // Statistics - updated to use multiple counselor IDs
        $totalNotes = SessionNote::whereIn('counselor_id', $counselorIds)->count();
        $totalStudents = SessionNote::whereIn('counselor_id', $counselorIds)
            ->distinct('student_id')
            ->count('student_id');
        $notesThisMonth = SessionNote::whereIn('counselor_id', $counselorIds)
            ->whereYear('session_date', now()->year)
            ->whereMonth('session_date', now()->month)
            ->count();
        $crisisSessions = SessionNote::whereIn('counselor_id', $counselorIds)
            ->where('session_type', 'crisis')
            ->count();

        // Calculate average sessions per student
        $averageSessionsPerStudent = $totalStudents > 0 ? $totalNotes / $totalStudents : 0;

        $sessionTypes = SessionNote::getSessionTypes();
        $colleges = College::orderBy('name')->get();

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
     * Show session notes for a student
     */
    public function index(Student $student)
    {
        $counselorIds = $this->getCounselorIds();

        $sessionNotes = SessionNote::with(['appointment', 'counselor.user'])
            ->where('student_id', $student->id)
            ->whereIn('counselor_id', $counselorIds)
            ->orderBy('session_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Verify the student belongs to one of the counselor's assigned colleges
        $assignedCollegeIds = $this->getAssignedColleges()->pluck('id');
        if (!$assignedCollegeIds->contains($student->college_id)) {
            abort(403, 'You are not assigned to this student\'s college.');
        }

        return view('counselor.session-notes.index', compact('student', 'sessionNotes'));
    }

    /**
     * Show form to create session notes
     */
    public function create(Student $student, Request $request)
    {
        $counselorIds = $this->getCounselorIds();
        $allColleges = $this->getAssignedColleges();

        // Verify the student belongs to one of the counselor's assigned colleges
        $assignedCollegeIds = $allColleges->pluck('id');
        if (!$assignedCollegeIds->contains($student->college_id)) {
            abort(403, 'You are not assigned to this student\'s college.');
        }

        // Get appointment if provided
        $appointment = null;
        if ($request->has('appointment_id')) {
            $appointment = Appointment::where('id', $request->appointment_id)
                ->whereIn('counselor_id', $counselorIds)
                ->where('student_id', $student->id)
                ->first();
        }

        // Get recent appointments with this student for reference
        $recentAppointments = Appointment::with('sessionNotes')
            ->where('student_id', $student->id)
            ->whereIn('counselor_id', $counselorIds)
            ->whereIn('status', ['completed', 'approved'])
            ->orderBy('appointment_date', 'desc')
            ->limit(10)
            ->get();

        $sessionTypes = SessionNote::getSessionTypes();
        $moodLevels = SessionNote::getMoodLevels();

        return view('counselor.session-notes.create', compact(
            'student',
            'appointment',
            'recentAppointments',
            'sessionTypes',
            'moodLevels',
            'allColleges'
        ));
    }

    /**
     * Store session notes
     */
    public function store(Request $request, Student $student)
    {
        $counselorIds = $this->getCounselorIds();
        $allColleges = $this->getAssignedColleges();

        // Verify the student belongs to one of the counselor's assigned colleges
        $assignedCollegeIds = $allColleges->pluck('id');
        if (!$assignedCollegeIds->contains($student->college_id)) {
            abort(403, 'You are not assigned to this student\'s college.');
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
                ->whereIn('counselor_id', $counselorIds)
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
     * Create follow-up appointment (without creating session notes)
     */
    private function createFollowupAppointment($request, $student, $counselorId, $sessionNote)
    {
        $startTime = Carbon::parse($request->followup_start_time);
        $endTime = $startTime->copy()->addHour();

        // Check if slot is still available
        $existingAppointment = Appointment::where('counselor_id', $counselorId)
            ->where('appointment_date', $request->followup_appointment_date)
            ->where('start_time', $request->followup_start_time)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existingAppointment) {
            // Don't fail, just notify that follow-up couldn't be scheduled
            session()->flash('warning', 'Session notes saved, but follow-up time slot was no longer available. Please schedule manually.');
            return null;
        }

        $appointment = Appointment::create([
            'student_id' => $student->id,
            'counselor_id' => $counselorId,
            'appointment_date' => $request->followup_appointment_date,
            'start_time' => $request->followup_start_time,
            'end_time' => $endTime->format('H:i'),
            'concern' => $request->followup_concern,
            'status' => $request->has('auto_approve_followup') ? 'approved' : 'pending',
            'notes' => "Follow-up appointment created from session notes #{$sessionNote->id}",
            // Important: Do NOT link this back to the session note to avoid circular reference
            'session_note_id' => null
        ]);

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

        $startTime = Carbon::parse($request->followup_start_time);
        $endTime = $startTime->copy()->addHour();

        // Check if slot is still available (excluding the current follow-up appointment if it exists)
        $existingAppointmentQuery = Appointment::where('counselor_id', $counselorId)
            ->where('appointment_date', $request->followup_appointment_date)
            ->where('start_time', $request->followup_start_time)
            ->whereIn('status', ['pending', 'approved']);

        // Exclude the current follow-up appointment if it exists
        if ($sessionNote->appointment) {
            $existingAppointmentQuery->where('id', '!=', $sessionNote->appointment->id);
        }

        $existingAppointment = $existingAppointmentQuery->exists();

        if ($existingAppointment) {
            session()->flash('warning', 'Session notes updated, but follow-up time slot was no longer available. Please schedule manually.');
            return;
        }

        // Update existing appointment or create new one
        if ($sessionNote->appointment) {
            // Update existing follow-up appointment
            $sessionNote->appointment->update([
                'appointment_date' => $request->followup_appointment_date,
                'start_time' => $request->followup_start_time,
                'end_time' => $endTime->format('H:i'),
                'concern' => $request->followup_concern,
                'status' => $request->has('auto_approve_followup') ? 'approved' : 'pending',
                'notes' => "Follow-up appointment updated from session notes #{$sessionNote->id}"
            ]);
        } else {
            // Create new follow-up appointment
            $appointment = Appointment::create([
                'student_id' => $student->id,
                'counselor_id' => $counselorId,
                'appointment_date' => $request->followup_appointment_date,
                'start_time' => $request->followup_start_time,
                'end_time' => $endTime->format('H:i'),
                'concern' => $request->followup_concern,
                'status' => $request->has('auto_approve_followup') ? 'approved' : 'pending',
                'notes' => "Follow-up appointment created from session notes #{$sessionNote->id}"
            ]);

            // Link the appointment to the session note
            $sessionNote->update([
                'appointment_id' => $appointment->id
            ]);
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
}
