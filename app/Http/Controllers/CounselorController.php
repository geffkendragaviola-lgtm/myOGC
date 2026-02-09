<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Counselor;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\College;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CounselorController extends Controller
{
public function dashboard()
{
    $userId = Auth::id();

    // Get all counselor assignments for this user
    $counselorAssignments = Counselor::with('user', 'college')
        ->where('user_id', $userId)
        ->get();

    if ($counselorAssignments->isEmpty()) {
        abort(404, 'Counselor profile not found.');
    }

    // Use the first counselor assignment as primary
    $counselor = $counselorAssignments->first();

    // Get all colleges this counselor handles
    $allColleges = $counselorAssignments->pluck('college')->filter();

    // Get appointments from ALL assigned colleges AND referred appointments
    $counselorIds = $counselorAssignments->pluck('id');

    // Today's appointments - include referred appointments
    $todayAppointments = Appointment::with('student.user')
        ->where(function($q) use ($counselorIds) {
            $q->whereIn('counselor_id', $counselorIds)
              ->orWhereIn('original_counselor_id', $counselorIds);
        })
        ->where('appointment_date', today())
        ->whereIn('status', ['pending', 'approved'])
        ->orderBy('start_time')
        ->get();

    // Upcoming appointments - include referred appointments
    $upcomingAppointments = Appointment::with('student.user')
        ->where(function($q) use ($counselorIds) {
            $q->whereIn('counselor_id', $counselorIds)
              ->orWhereIn('original_counselor_id', $counselorIds);
        })
        ->where('appointment_date', '>', today())
        ->whereIn('status', ['pending', 'approved'])
        ->orderBy('appointment_date')
        ->orderBy('start_time')
        ->limit(10)
        ->get();

    // Appointment statistics - include referred appointments
    $appointmentStats = [
        'pending' => Appointment::where(function($q) use ($counselorIds) {
                $q->whereIn('counselor_id', $counselorIds)
                  ->orWhereIn('original_counselor_id', $counselorIds);
            })
            ->where('status', 'pending')
            ->count(),
        'approved' => Appointment::where(function($q) use ($counselorIds) {
                $q->whereIn('counselor_id', $counselorIds)
                  ->orWhereIn('original_counselor_id', $counselorIds);
            })
            ->where('status', 'approved')
            ->count(),
        'total' => Appointment::where(function($q) use ($counselorIds) {
                $q->whereIn('counselor_id', $counselorIds)
                  ->orWhereIn('original_counselor_id', $counselorIds);
            })
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->count(),
    ];

    return view('counselor.appointments.dashboard', compact(
        'counselor',
        'allColleges',
        'todayAppointments',
        'upcomingAppointments',
        'appointmentStats'
    ));
}
public function appointments(Request $request)
{
    $userId = Auth::id();

    // Get all counselor assignments for this user
    $counselorAssignments = Counselor::with('user')
        ->where('user_id', $userId)
        ->get();

    $counselorIds = $counselorAssignments->pluck('id');
    $counselor = $counselorAssignments->first();

    $status = $request->get('status', 'all');

    // MODIFIED QUERY: Include appointments where counselor is current OR referred-to counselor
    $query = Appointment::with([
            'student.user',
            'student.college',
            'sessionNotes',
            'referredCounselor.user',
            'originalCounselor.user',
            'counselor.user'
        ])
        ->where(function($q) use ($counselorIds, $counselor) {
            // Appointments where this counselor is the CURRENT counselor
            $q->whereIn('counselor_id', $counselorIds)
              // OR appointments where this counselor is the REFERRED-TO counselor
              ->orWhere('referred_to_counselor_id', $counselor->id);
        });

    // Search functionality (case-insensitive)
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
            ->orWhereRaw('LOWER(concern) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(notes) LIKE ?', ["%{$search}%"]);
        });
    }

    // Date range filter
    if ($request->has('date_range') && $request->date_range) {
        $now = Carbon::now();
        switch ($request->date_range) {
            case 'today':
                $query->whereDate('appointment_date', $now->toDateString());
                break;
            case 'week':
                $query->whereBetween('appointment_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'month':
                $query->whereBetween('appointment_date', [
                    $now->startOfMonth()->toDateString(),
                    $now->endOfMonth()->toDateString()
                ]);
                break;
            case 'upcoming':
                $query->where('appointment_date', '>=', $now->toDateString());
                break;
            case 'past':
                $query->where('appointment_date', '<', $now->toDateString());
                break;
        }
    }

    // College filter
    if ($request->has('college') && $request->college) {
        $query->whereHas('student', function($q) use ($request) {
            $q->where('college_id', $request->college);
        });
    }

    // Status filter - handle 'referred' status specially
    if ($status !== 'all') {
        if ($status === 'referred') {
            // For referred status, we want appointments that are referred
            // AND where this counselor is either the original OR current counselor
            $query->where('status', 'referred');
        } else {
            $query->where('status', $status);
        }
    }

    $counselorIds = Counselor::where('user_id', $counselor->user_id)->pluck('id')->all();

    $appointments = $query->orderBy('appointment_date', 'desc')
        ->orderBy('start_time', 'desc')
        ->paginate(15);

    // Add referral context to each appointment
    $appointments->getCollection()->transform(function ($appointment) use ($counselor) {
        $appointment->has_session_notes = $appointment->sessionNotes->isNotEmpty();
        $appointment->session_notes_count = $appointment->sessionNotes->count();
        $appointment->display_status = $appointment->getStatusWithReferralContext($counselor->id);

        // Add a flag to identify if this is a referred appointment where current counselor is the original
        $appointment->is_referred_out = $appointment->status === 'referred' &&
                                      $appointment->original_counselor_id == $counselor->id;

        // Add a flag to identify if this is a referred appointment where current counselor is the receiver
        $appointment->is_referred_in = $appointment->status === 'referred' &&
                                     $appointment->counselor_id == $counselor->id;

        return $appointment;
    });

    $colleges = College::orderBy('name')->get();

    return view('counselor.appointments.appointments', compact(
        'counselor',
        'counselorIds',
        'appointments',
        'status',
        'colleges'
    ));
}

public function calendar(Request $request)
{
    $userId = Auth::id();

    $counselorAssignments = Counselor::with('user', 'college')
        ->where('user_id', $userId)
        ->get();

    if ($counselorAssignments->isEmpty()) {
        abort(404, 'Counselor profile not found.');
    }

    $counselor = $counselorAssignments->first();
    $counselorIds = $counselorAssignments->pluck('id');

    $date = $request->filled('date')
        ? Carbon::parse($request->input('date'))
        : Carbon::today();

    $appointments = Appointment::with(['student.user'])
        ->where(function ($query) use ($counselorIds, $counselor) {
            $query->whereIn('counselor_id', $counselorIds)
                ->orWhere('referred_to_counselor_id', $counselor->id);
        })
        ->whereDate('appointment_date', $date->toDateString())
        ->whereIn('status', ['pending', 'approved', 'completed', 'referred', 'rescheduled', 'reschedule_requested', 'reschedule_rejected'])
        ->orderBy('start_time')
        ->get()
        ->map(function ($appointment) {
            $appointment->formatted_start_time = Carbon::parse($appointment->start_time)->format('H:i');
            return $appointment;
        });

    $selectedDate = $date->format('Y-m-d');
    $googleCalendarId = $counselor->google_calendar_id;
    $busyIntervals = [];
    $googleCalendarEvents = [];

    if ($googleCalendarId) {
        try {
            $calendarService = app(GoogleCalendarService::class);
            $googleCalendarEvents = $calendarService->getBusyIntervalsForDate($googleCalendarId, $date);
            $busyIntervals = $googleCalendarEvents;
        } catch (\Throwable $exception) {
            Log::warning('Failed to load Google Calendar schedule', [
                'counselor_id' => $counselor->id,
                'calendar_id' => $googleCalendarId,
                'date' => $selectedDate,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    return view('counselor.appointments.calendar', compact(
        'counselor',
        'appointments',
        'date',
        'selectedDate',
        'googleCalendarId',
        'busyIntervals',
        'googleCalendarEvents'
    ));
}

    /**
     * Export appointments to Excel
     */
    public function exportAppointments(Request $request)
    {
        $userId = Auth::id();

        // Get all counselor assignments for this user
        $counselorAssignments = Counselor::where('user_id', $userId)->get();
        $counselorIds = $counselorAssignments->pluck('id');

        $query = Appointment::with(['student.user', 'student.college', 'sessionNotes'])
            ->whereIn('counselor_id', $counselorIds);

        // Apply the same filters as the main appointments method
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student.user', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhereHas('student', function($q) use ($search) {
                    $q->where('student_id', 'like', "%{$search}%");
                })
                ->orWhereHas('student.college', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('concern', 'like', "%{$search}%");
            });
        }

        if ($request->has('date_range') && $request->date_range) {
            $now = Carbon::now();
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('appointment_date', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('appointment_date', [
                        $now->startOfWeek()->toDateString(),
                        $now->endOfWeek()->toDateString()
                    ]);
                    break;
                case 'month':
                    $query->whereBetween('appointment_date', [
                        $now->startOfMonth()->toDateString(),
                        $now->endOfMonth()->toDateString()
                    ]);
                    break;
                case 'upcoming':
                    $query->where('appointment_date', '>=', $now->toDateString());
                    break;
                case 'past':
                    $query->where('appointment_date', '<', $now->toDateString());
                    break;
            }
        }

        if ($request->has('college') && $request->college) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('college_id', $request->college);
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        // Export logic using a package like Laravel Excel or simple CSV
        return $this->generateExcelExport($appointments);
    }

    /**
     * Generate Excel export
     */
    private function generateExcelExport($appointments)
    {
        $fileName = 'appointments_' . date('Y-m-d') . '.xlsx';

        // For now, we'll use a simple CSV approach
        // In production, consider using Laravel Excel package
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fputs($file, $bom = (chr(0xEF) . chr(0xBB) . chr(0xBF)));

            // Headers
            fputcsv($file, [
                'Student Name',
                'Student ID',
                'College',
                'Appointment Date',
                'Start Time',
                'End Time',
                'Concern',
                'Status',
                'Session Notes Count',
                'Created At'
            ]);

            // Data
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->student->user->first_name . ' ' . $appointment->student->user->last_name,
                    $appointment->student->student_id,
                    $appointment->student->college->name ?? 'N/A',
                    $appointment->appointment_date->format('Y-m-d'),
                    $appointment->start_time,
                    $appointment->end_time,
                    $appointment->concern,
                    ucfirst($appointment->status),
                    $appointment->sessionNotes->count(),
                    $appointment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

public function updateAppointmentStatus(Request $request, Appointment $appointment)
{
    $request->validate([
        'status' => 'required|in:approved,rejected,completed,cancelled',
        'notes' => 'nullable|string|max:500'
    ]);

    // Check if the counselor can manage this appointment
    $userId = Auth::id();
    $counselor = Counselor::where('user_id', $userId)->first();

    if (!$appointment->canBeManagedBy($counselor->id)) {
        return redirect()->back()->with('error', 'You can only update appointments assigned to you.');
    }

    $oldStatus = $appointment->status;
    $appointment->update([
        'status' => $request->status,
        'notes' => $request->notes ?: $appointment->notes
    ]);

    $statusMessages = [
        'approved' => 'Appointment approved successfully.',
        'rejected' => 'Appointment rejected.',
        'completed' => 'Appointment marked as completed.',
        'cancelled' => 'Appointment cancelled.'
    ];

    return redirect()->back()->with('success', $statusMessages[$request->status]);
}
public function getAppointmentDetails(Appointment $appointment)
{
    // Verify the counselor owns this appointment (from any assigned college)
    $userId = Auth::id();
    $counselorIds = Counselor::where('user_id', $userId)->pluck('id');

    if (!$counselorIds->contains($appointment->counselor_id)) {
        abort(403);
    }

    $appointment->load(['student.user', 'student.college', 'sessionNotes', 'referredCounselor.user', 'originalCounselor.user']);

    $sessionNotes = $appointment->sessionNotes->map(function ($note) {
        return [
            'id' => $note->id,
            'notes' => $note->notes,
            'follow_up_actions' => $note->follow_up_actions,
            'session_date' => $note->session_date->format('M j, Y'),
            'session_type' => $note->session_type,
            'session_type_label' => $note->session_type_label,
            'mood_level' => $note->mood_level,
            'mood_level_label' => $note->mood_level_label,
            'requires_follow_up' => $note->requires_follow_up,
            'next_session_date' => $note->next_session_date?->format('M j, Y'),
            'created_at' => $note->created_at->format('M j, Y g:i A'),
        ];
    });

    // Get the current counselor for context
    $currentCounselor = Counselor::where('user_id', Auth::id())->first();

    return response()->json([
        'appointment' => [
            'id' => $appointment->id,
            'concern' => $appointment->concern,
            'notes' => $appointment->notes,
            'status' => $appointment->status,
            'status_display' => $appointment->getStatusWithReferralContext($currentCounselor->id), // ADD THIS
            'booking_type' => $appointment->booking_type,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d'),
            'start_time' => $appointment->start_time,
            'end_time' => $appointment->end_time,
            'has_session_notes' => $appointment->sessionNotes->isNotEmpty(),
            'session_notes_count' => $appointment->sessionNotes->count(),
        ],
        'student' => [
            'id' => $appointment->student->id,
            'student_id' => $appointment->student->student_id,
            'year_level' => $appointment->student->year_level,
            'initial_interview_completed' => $appointment->student->initial_interview_completed,
            'initial_interview_completed_label' => $appointment->student->initial_interview_completed === null
                ? 'Not provided'
                : ($appointment->student->initial_interview_completed ? 'Yes' : 'No'),
            'profile_url' => route('counselor.students.profile', $appointment->student->id),
            'user' => [
                'first_name' => $appointment->student->user->first_name,
                'last_name' => $appointment->student->user->last_name,
                'email' => $appointment->student->user->email,
            ],
            'college' => [
                'name' => $appointment->student->college->name ?? null,
            ],
        ],
        'formatted_date' => $appointment->appointment_date->format('F j, Y'),
        'formatted_time' => \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->format('g:i A'),
        'session_notes' => $sessionNotes,
    ]);
}

    private function getStatusColor($status)
    {
        $colors = [
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'completed' => 'blue',
            'cancelled' => 'gray',
            'referred' => 'purple',
            'rescheduled' => 'indigo',
            'reschedule_requested' => 'orange',
            'reschedule_rejected' => 'rose'
        ];

        return $colors[$status] ?? 'gray';
    }

     public function showReferralForm(Appointment $appointment)
    {
        // Check if counselor owns this appointment
        $counselor = Counselor::where('user_id', Auth::id())->first();
        if ($appointment->counselor_id !== $counselor->id) {
            return redirect()->back()->with('error', 'You can only refer your own appointments.');
        }

        // Get available counselors from the same college
        $availableCounselors = Counselor::with('user', 'college')
            ->where('college_id', $appointment->student->college_id)
            ->where('id', '!=', $counselor->id)
            ->whereHas('user', function($query) {
                $query->where('role', 'counselor');
            })
            ->get();

        return view('counselor.referral-form', compact('appointment', 'availableCounselors'));
    }

    /**
     * Process referral request
     */

/**
 * Process referral request
 */
public function processReferral(Request $request, Appointment $appointment)
{
    $request->validate([
        'referred_to_counselor_id' => 'required|exists:counselors,id',
        'referral_reason' => 'required|string|max:500'
    ]);

    // Check if counselor owns this appointment
    $counselor = Counselor::where('user_id', Auth::id())->first();
    if ($appointment->counselor_id !== $counselor->id) {
        return redirect()->back()->with('error', 'You can only refer your own appointments.');
    }

    // Get referred counselor info
    $referredCounselor = Counselor::with('user')->find($request->referred_to_counselor_id);
    $referredCounselorName = $referredCounselor->user->first_name . ' ' . $referredCounselor->user->last_name;
    $currentCounselorName = $counselor->user->first_name . ' ' . $counselor->user->last_name;

    // Update appointment status to referred
    $appointment->update([
        'status' => 'referred',
        'referred_to_counselor_id' => $request->referred_to_counselor_id,
        'referral_reason' => $request->referral_reason,
        'original_counselor_id' => $counselor->id,
        'notes' => ($appointment->notes ? $appointment->notes . "\n\n" : '') .
                  "REFERRED by {$currentCounselorName} to {$referredCounselorName} on " . now()->toDateTimeString() .
                  "\nReason: " . $request->referral_reason
    ]);

    return redirect()->route('counselor.appointments')
        ->with('success', "Appointment referred to {$referredCounselorName} successfully. The student has been notified.");
}

public function showStudentProfile(Student $student)
{
    $student->load([
        'user',
        'college',
        'personalData',
        'familyData',
        'academicData',
        'learningResources',
        'psychosocialData',
        'needsAssessment',
        'appointments',
        'events'
    ]);

    return view('student.show', compact('student'));
}

}

