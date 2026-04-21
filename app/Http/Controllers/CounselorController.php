<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Counselor;
use App\Models\SessionNote;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\College;
use App\Services\GoogleCalendarService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

public function students(Request $request)
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

    $counselorIds = $counselorAssignments->pluck('id')->filter()->unique()->values();

    $referredStudentIds = Appointment::query()
        ->whereIn('referred_to_counselor_id', $counselorIds->all())
        ->pluck('student_id')
        ->filter()
        ->unique()
        ->values();

    $search = $request->get('search');
    $college = $request->get('college');

    $visibleCollegeIds = $collegeIds
        ->merge(
            Student::query()
                ->whereIn('id', $referredStudentIds->all())
                ->pluck('college_id')
                ->filter()
        )
        ->unique()
        ->values();

    if ($college && !$visibleCollegeIds->contains((int) $college)) {
        abort(403);
    }

    $query = Student::with(['user', 'college', 'lastSessionNote', 'needsAssessment'])
        ->where(function ($q) use ($collegeIds, $referredStudentIds) {
            $q->whereIn('college_id', $collegeIds->all());

            if ($referredStudentIds->isNotEmpty()) {
                $q->orWhereIn('id', $referredStudentIds->all());
            }
        });

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('student_id', 'like', "%{$search}%")
                ->orWhere('course', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
        });
    }

    if ($college) {
        $query->where('college_id', (int) $college);
    }

    $students = $query
        ->orderBy('student_id')
        ->paginate(15)
        ->appends($request->query());

    $assignedColleges = College::query()
        ->whereIn('id', $visibleCollegeIds->all())
        ->orderBy('name')
        ->get();

    return view('counselor.students.index', [
        'students' => $students,
        'colleges' => $assignedColleges,
        'search' => $search,
        'college' => $college,
    ]);
}

public function appointmentSessionsDashboard(Request $request)
{
    $userId = Auth::id();
    $counselorAssignments = Counselor::where('user_id', $userId)->get();
    $counselorIds = $counselorAssignments->pluck('id');

    if ($counselorIds->isEmpty()) {
        abort(404, 'Counselor profile not found.');
    }

    $query = Appointment::with(['student.user', 'student.college', 'sessionNotes'])
        ->select('appointments.*')
        ->selectSub(function ($sub) {
            $sub->from('appointments as a2')
                ->selectRaw('COUNT(*)')
                ->whereColumn('a2.student_id', 'appointments.student_id')
                ->where(function ($q) {
                    $q->whereNull('a2.booking_type')
                        ->orWhere('a2.booking_type', '!=', 'Initial Interview');
                })
                ->where(function ($q) {
                    $q->where('a2.appointment_date', '<', DB::raw('appointments.appointment_date'))
                        ->orWhere(function ($q) {
                            $q->where('a2.appointment_date', '=', DB::raw('appointments.appointment_date'))
                                ->where('a2.start_time', '<', DB::raw('appointments.start_time'));
                        });
                });
        }, 'non_initial_session_index')
        ->whereHas('sessionNotes')
        ->where(function ($q) use ($counselorIds) {
            $q->whereIn('counselor_id', $counselorIds)
                ->orWhereIn('referred_to_counselor_id', $counselorIds)
                ->orWhereIn('original_counselor_id', $counselorIds);
        });

    if ($request->filled('status') && $request->input('status') !== 'all') {
        $query->where('status', $request->input('status'));
    }

    if ($request->filled('date_range')) {
        $now = Carbon::now();
        switch ($request->input('date_range')) {
            case 'today':
                $query->whereDate('appointment_date', $now->toDateString());
                break;
            case 'week':
                $query->whereBetween('appointment_date', [
                    $now->copy()->startOfWeek()->toDateString(),
                    $now->copy()->endOfWeek()->toDateString(),
                ]);
                break;
            case 'month':
                $query->whereBetween('appointment_date', [
                    $now->copy()->startOfMonth()->toDateString(),
                    $now->copy()->endOfMonth()->toDateString(),
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

    if ($request->filled('search')) {
        $search = strtolower($request->input('search'));
        $query->where(function ($q) use ($search) {
            $q->whereHas('student.user', function ($q) use ($search) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$search}%"]);
            })
            ->orWhereHas('student', function ($q) use ($search) {
                $q->whereRaw('LOWER(student_id) LIKE ?', ["%{$search}%"]);
            })
            ->orWhereRaw('LOWER(concern) LIKE ?', ["%{$search}%"]);
        });
    }

    $appointments = $query
        ->orderBy('appointment_date', 'desc')
        ->orderBy('start_time', 'desc')
        ->paginate(15)
        ->appends($request->query());

    $appointments->getCollection()->transform(function ($appointment) {
        $ordinal = function (int $number): string {
            $suffixes = ['th', 'st', 'nd', 'rd'];
            $value = $number % 100;
            if ($value >= 11 && $value <= 13) {
                return $number . 'th';
            }
            return $number . ($suffixes[$number % 10] ?? 'th');
        };

        if ($appointment->booking_type === 'Initial Interview') {
            $appointment->session_sequence_label = 'Initial Interview';
        } else {
            $index = ((int) ($appointment->non_initial_session_index ?? 0)) + 1;
            $appointment->session_sequence_label = $ordinal($index) . ' Session';
        }

        return $appointment;
    });

    return view('counselor.appointments.session-dashboard', compact('appointments'));
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
    $referralDirection = $request->get('referral_direction');

    // Base query: appointments that are relevant to this counselor (current, referred-to, or referred-by)
    $baseQuery = Appointment::with([
            'student.user',
            'student.college',
            'sessionNotes',
            'referredCounselor.user',
            'originalCounselor.user',
            'counselor.user'
        ])
        ->select('appointments.*')
        ->selectSub(function ($sub) {
            $sub->from('appointments as a2')
                ->selectRaw('COUNT(*)')
                ->whereColumn('a2.student_id', 'appointments.student_id')
                ->where(function ($q) {
                    $q->whereNull('a2.booking_type')
                        ->orWhere('a2.booking_type', '!=', 'Initial Interview');
                })
                ->where(function ($q) {
                    $q->where('a2.appointment_date', '<', DB::raw('appointments.appointment_date'))
                        ->orWhere(function ($q) {
                            $q->where('a2.appointment_date', '=', DB::raw('appointments.appointment_date'))
                                ->where('a2.start_time', '<', DB::raw('appointments.start_time'));
                        });
                });
        }, 'non_initial_session_index')
        ->where(function($q) use ($counselorIds) {
            // Appointments where this counselor is the CURRENT counselor
            $q->whereIn('counselor_id', $counselorIds)
              // OR appointments where this counselor is the REFERRED-TO counselor
              ->orWhereIn('referred_to_counselor_id', $counselorIds)
              // OR appointments that were REFERRED BY this counselor (history view even after acceptance)
              ->orWhereIn('original_counselor_id', $counselorIds);
        });

    // Search functionality (case-insensitive)
    if ($request->has('search') && $request->search) {
        $search = strtolower($request->search);
        $baseQuery->where(function($q) use ($search) {
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
                $baseQuery->whereDate('appointment_date', $now->toDateString());
                break;
            case 'week':
                $baseQuery->whereBetween('appointment_date', [
                    $now->startOfWeek()->toDateString(),
                    $now->endOfWeek()->toDateString()
                ]);
                break;
            case 'month':
                $baseQuery->whereBetween('appointment_date', [
                    $now->startOfMonth()->toDateString(),
                    $now->endOfMonth()->toDateString()
                ]);
                break;
            case 'upcoming':
                $baseQuery->where('appointment_date', '>=', $now->toDateString());
                break;
            case 'past':
                $baseQuery->where('appointment_date', '<', $now->toDateString());
                break;
        }
    }

    // College filter
    if ($request->has('college') && $request->college) {
        $baseQuery->whereHas('student', function($q) use ($request) {
            $q->where('college_id', $request->college);
        });
    }

    $statsQuery = (clone $baseQuery);

    // Referral direction filter (history view)
    if ($referralDirection === 'in') {
        $baseQuery->whereNotNull('referred_to_counselor_id')
            ->whereIn('referred_to_counselor_id', $counselorIds);
    } elseif ($referralDirection === 'out') {
        $baseQuery->whereNotNull('original_counselor_id')
            ->whereIn('original_counselor_id', $counselorIds);
    }

    // Status filter - handle 'referred' status specially
    if ($status !== 'all') {
        if ($status === 'referred') {
            // For referred status, we want appointments that are referred
            // AND where this counselor is either the original OR current counselor
            $baseQuery->where('status', 'referred');
        } else {
            $baseQuery->where('status', $status);
        }
    }

    $counselorIds = Counselor::where('user_id', $counselor->user_id)->pluck('id');
    $counselorIdList = $counselorIds->map(fn ($id) => (int) $id)->all();

    $appointments = $baseQuery->orderBy('appointment_date', 'desc')
        ->orderBy('start_time', 'desc')
        ->paginate(15);

    $stats = [
        'total' => (clone $statsQuery)->count(),
        'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
        'approved' => (clone $statsQuery)->where('status', 'approved')->count(),
        'cancelled' => (clone $statsQuery)->where('status', 'cancelled')->count(),
        'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
        'referred' => (clone $statsQuery)->where('status', 'referred')->count(),
        'referred_in' => (clone $statsQuery)
            ->whereNotNull('referred_to_counselor_id')
            ->whereIn('referred_to_counselor_id', $counselorIds)
            ->count(),
        'referred_out' => (clone $statsQuery)
            ->whereNotNull('original_counselor_id')
            ->whereIn('original_counselor_id', $counselorIds)
            ->count(),
    ];

    // Add referral context to each appointment
    $appointments->getCollection()->transform(function ($appointment) use ($counselorIds) {
        $contextCounselorId = $counselorIds->first(function ($counselorId) use ($appointment) {
            return (int) $appointment->counselor_id === (int) $counselorId
                || (int) $appointment->referred_to_counselor_id === (int) $counselorId
                || (int) $appointment->original_counselor_id === (int) $counselorId;
        });

        $contextCounselorId = $contextCounselorId ?? $counselorIds->first();

        $appointment->has_session_notes = $appointment->sessionNotes->isNotEmpty();
        $appointment->session_notes_count = $appointment->sessionNotes->count();

        $ordinal = function (int $number): string {
            $suffixes = ['th', 'st', 'nd', 'rd'];
            $value = $number % 100;
            if ($value >= 11 && $value <= 13) {
                return $number . 'th';
            }
            return $number . ($suffixes[$number % 10] ?? 'th');
        };

        if ($appointment->booking_type === 'Initial Interview') {
            $appointment->session_sequence_label = 'Initial Interview';
        } else {
            $index = ((int) ($appointment->non_initial_session_index ?? 0)) + 1;
            $appointment->session_sequence_label = $ordinal($index) . ' Session';
        }

        $statusLabels = [
            'reschedule_requested' => 'Reschedule Requested (Pending Student Approval)',
            'reschedule_rejected' => 'Rejected by Student',
            'rescheduled' => 'Scheduled (Rescheduled)',
        ];

        if ($appointment->status === 'referred') {
            $appointment->display_status = $contextCounselorId
                ? ($appointment->getReferralBadgeForCounselor((int) $contextCounselorId) ?? 'Referred')
                : 'Referred';
            $appointment->referral_badge = null;
        } else {
            $appointment->display_status = $statusLabels[$appointment->status] ?? ucfirst($appointment->status);
            $appointment->referral_badge = $contextCounselorId
                ? $appointment->getReferralBadgeForCounselor((int) $contextCounselorId)
                : null;
        }

        // Add a flag to identify if this is a referred appointment where current counselor is the original
        $appointment->is_referred_out = (bool) $appointment->is_referred &&
                                      in_array((int) $appointment->original_counselor_id, $counselorIds->map(fn ($id) => (int) $id)->all(), true);

        // Add a flag to identify if this is a referred appointment where current counselor is the receiver
        $appointment->is_referred_in = (bool) $appointment->is_referred &&
                                     in_array((int) $appointment->referred_to_counselor_id, $counselorIds->map(fn ($id) => (int) $id)->all(), true);

        return $appointment;
    });

    $colleges = College::orderBy('name')->get();

    return view('counselor.appointments.appointments', compact(
        'counselor',
        'counselorIdList',
        'appointments',
        'status',
        'colleges',
        'stats',
        'referralDirection'
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

    $appointments = Appointment::with([
            'student.user',
            'student.college',
            'referredCounselor.user',
            'originalCounselor.user',
            'counselor.user'
        ])
        ->where(function ($query) use ($counselorIds) {
            $query->whereIn('counselor_id', $counselorIds)
                ->orWhereIn('referred_to_counselor_id', $counselorIds)
                ->orWhereIn('original_counselor_id', $counselorIds);
        })
        ->whereDate('appointment_date', $date->toDateString())
        ->whereIn('status', ['pending', 'approved', 'completed', 'referred', 'rescheduled', 'reschedule_requested', 'reschedule_rejected'])
        ->orderBy('start_time')
        ->get()
        ->map(function ($appointment) use ($counselorIds) {
            $appointment->formatted_start_time = Carbon::parse($appointment->start_time)->format('H:i');

            $contextCounselorId = $counselorIds->first(function ($counselorId) use ($appointment) {
                return (int) $appointment->counselor_id === (int) $counselorId
                    || (int) $appointment->referred_to_counselor_id === (int) $counselorId
                    || (int) $appointment->original_counselor_id === (int) $counselorId;
            });

            $contextCounselorId = $contextCounselorId ?? $counselorIds->first();

            $statusLabels = [
                'reschedule_requested' => 'Reschedule Requested (Pending Student Approval)',
                'reschedule_rejected' => 'Rejected by Student',
                'rescheduled' => 'Scheduled (Rescheduled)',
            ];

            if ($appointment->status === 'referred') {
                $appointment->display_status = $contextCounselorId
                    ? ($appointment->getReferralBadgeForCounselor((int) $contextCounselorId) ?? 'Referred')
                    : 'Referred';
                $appointment->referral_badge = null;
            } else {
                $appointment->display_status = $statusLabels[$appointment->status] ?? ucfirst($appointment->status);
                $appointment->referral_badge = $contextCounselorId
                    ? $appointment->getReferralBadgeForCounselor((int) $contextCounselorId)
                    : null;
            }

            $counselorIdList = $counselorIds->map(fn ($id) => (int) $id)->all();
            $appointment->is_referred_out = (bool) $appointment->is_referred &&
                                          in_array((int) $appointment->original_counselor_id, $counselorIdList, true);
            $appointment->is_referred_in = (bool) $appointment->is_referred &&
                                         in_array((int) $appointment->referred_to_counselor_id, $counselorIdList, true);

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
        'status' => 'required|in:approved,completed,cancelled,no_show',
        'notes' => 'nullable|string|max:500'
    ]);

    if (in_array($appointment->status, ['cancelled'], true)) {
        return redirect()->back()->with('error', 'This appointment is closed and can no longer be updated.');
    }

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
        'completed' => 'Appointment marked as completed.',
        'cancelled' => 'Appointment cancelled.',
        'no_show' => 'Appointment marked as no show.',
    ];

    return redirect()->back()->with('success', $statusMessages[$request->status]);
}
public function getAppointmentDetails(Appointment $appointment)
{
    // Verify the counselor owns this appointment (from any assigned college)
    $userId = Auth::id();
    $counselorIds = Counselor::where('user_id', $userId)->pluck('id');

    $canView = $counselorIds->contains(function ($counselorId) use ($appointment) {
        return $appointment->canBeViewedBy($counselorId);
    });

    if (!$canView) {
        abort(403);
    }

    $appointment->load(['student.user', 'student.college', 'sessionNotes', 'referredCounselor.user', 'originalCounselor.user']);

    $sessionUrl = null;
    if (in_array($appointment->status, ['approved', 'rescheduled', 'completed'], true) && empty($appointment->is_referred_out)) {
        $sessionUrl = $appointment->sessionNotes->isNotEmpty()
            ? route('counselor.appointments.session.view', $appointment)
            : route('counselor.appointments.session', $appointment);
    }

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
    $currentCounselorId = $counselorIds->first(function ($counselorId) use ($appointment) {
        return $appointment->canBeViewedBy($counselorId);
    });

    return response()->json([
        'appointment' => [
            'id' => $appointment->id,
            'case_number' => $appointment->case_number,
            'concern' => $appointment->concern,
            'notes' => $appointment->notes,
            'status' => $appointment->status,
            'status_display' => $currentCounselorId
                ? $appointment->getStatusWithReferralContext($currentCounselorId)
                : ucfirst($appointment->status),
            'booking_type' => $appointment->booking_type,
            'booking_category' => $appointment->booking_category,
            'is_referred' => (bool) $appointment->is_referred,
            'referred_to_counselor_id' => $appointment->referred_to_counselor_id,
            'original_counselor_id' => $appointment->original_counselor_id,
            'referral_reason' => $appointment->referral_reason,
            'referral_requested_at' => $appointment->referral_requested_at?->toIso8601String(),
            'referral_outcome' => $appointment->referral_outcome,
            'referral_resolved_at' => $appointment->referral_resolved_at?->toIso8601String(),
            'referral_resolved_by_counselor_id' => $appointment->referral_resolved_by_counselor_id,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d'),
            'start_time' => $appointment->start_time,
            'end_time' => $appointment->end_time,
            'proposed_date' => $appointment->proposed_date?->format('Y-m-d'),
            'proposed_start_time' => $appointment->proposed_start_time,
            'proposed_end_time' => $appointment->proposed_end_time,
            'session_url' => $sessionUrl,
            'has_session_notes' => $appointment->sessionNotes->isNotEmpty(),
            'session_notes_count' => $appointment->sessionNotes->count(),
        ],
        'referral' => [
            'referred_to_name' => $appointment->referredCounselor && $appointment->referredCounselor->user
                ? $appointment->referredCounselor->user->first_name . ' ' . $appointment->referredCounselor->user->last_name
                : null,
            'referred_from_name' => $appointment->originalCounselor && $appointment->originalCounselor->user
                ? $appointment->originalCounselor->user->first_name . ' ' . $appointment->originalCounselor->user->last_name
                : null,
        ],
        'student' => [
            'id' => $appointment->student->id,
            'student_id' => $appointment->student->student_id,
            'year_level' => $appointment->student->year_level,
            'course' => $appointment->student->course,
            'initial_interview_completed' => $appointment->student->initial_interview_completed,
            'initial_interview_completed_label' => $appointment->student->initial_interview_completed === null
                ? 'Not provided'
                : ($appointment->student->initial_interview_completed ? 'Yes' : 'No'),
            'profile_url' => route('counselor.students.profile', $appointment->student->id),
            'user' => [
                'first_name' => $appointment->student->user->first_name,
                'last_name' => $appointment->student->user->last_name,
                'email' => $appointment->student->user->email,
                'age' => $appointment->student->user->age,
                'birthdate' => $appointment->student->user->birthdate?->format('Y-m-d'),
                'sex' => $appointment->student->user->sex,
                'address' => $appointment->student->user->address,
                'phone_number' => $appointment->student->user->phone_number,
            ],
            'college' => [
                'name' => $appointment->student->college->name ?? null,
            ],
        ],
        'formatted_date' => $appointment->appointment_date->format('F j, Y'),
        'formatted_time' => \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($appointment->end_time)->format('g:i A'),
        'formatted_referral_date' => $appointment->referral_requested_at?->format('F j, Y g:i A'),
        'formatted_proposed_date' => $appointment->proposed_date?->format('F j, Y'),
        'formatted_proposed_time' => ($appointment->proposed_start_time && $appointment->proposed_end_time)
            ? (\Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($appointment->proposed_end_time)->format('g:i A'))
            : null,
        'session_notes' => $sessionNotes,
    ]);
}

public function showAppointmentSession(Appointment $appointment)
{
    $userId = Auth::id();
    $counselorIds = Counselor::where('user_id', $userId)->pluck('id');

    $effectiveCounselorId = $counselorIds->first(function ($counselorId) use ($appointment) {
        return $appointment->canBeManagedBy($counselorId);
    });

    if (!$effectiveCounselorId) {
        abort(403);
    }

    $appointment->load(['student.user', 'student.college', 'referredCounselor.user', 'originalCounselor.user']);

    $followupAppointment = Appointment::where('student_id', $appointment->student_id)
        ->where('notes', 'like', '%Follow-up appointment booked by counselor for appointment #' . $appointment->id . '%')
        ->orderByDesc('appointment_date')
        ->orderByDesc('start_time')
        ->first();

    $latestSessionNote = SessionNote::where('appointment_id', $appointment->id)
        ->where('counselor_id', $effectiveCounselorId)
        ->orderByDesc('session_date')
        ->orderByDesc('created_at')
        ->first();

    $rootCauseOptions = [
        'personal_social' => 'Personal Social',
        'career_occupational_vocational' => 'Career Occupational/Vocational',
        'academic_educational' => 'Academic Educational',
    ];

    $appointmentTypeOptions = [
        'intake_session' => 'Intake Session',
        'follow_up_session' => 'Follow-up Session',
        'case_closed' => 'Case Closure / Case Closed',
    ];

    return view('counselor.appointments.session', compact(
        'appointment',
        'effectiveCounselorId',
        'followupAppointment',
        'latestSessionNote',
        'rootCauseOptions',
        'appointmentTypeOptions'
    ));
}

public function viewAppointmentSession(Appointment $appointment)
{
    $userId = Auth::id();
    $counselorIds = Counselor::where('user_id', $userId)->pluck('id');

    $canView = $counselorIds->contains(function ($counselorId) use ($appointment) {
        return $appointment->canBeViewedBy($counselorId);
    });

    if (!$canView) {
        abort(403);
    }

    $appointment->load(['student.user', 'student.college', 'referredCounselor.user', 'originalCounselor.user', 'sessionNotes']);

    $latestSessionNote = $appointment->sessionNotes
        ->sortByDesc('session_date')
        ->sortByDesc('created_at')
        ->first();

    return view('counselor.appointments.session-view', compact('appointment', 'latestSessionNote'));
}

public function storeAppointmentSession(Request $request, Appointment $appointment)
{
    $userId = Auth::id();
    $counselorIds = Counselor::where('user_id', $userId)->pluck('id');

    $effectiveCounselorId = $counselorIds->first(function ($counselorId) use ($appointment) {
        return $appointment->canBeManagedBy($counselorId);
    });

    if (!$effectiveCounselorId) {
        abort(403);
    }

    $rootCauseKeys = ['personal_social', 'career_occupational_vocational', 'academic_educational'];

    $validated = $request->validate([
        'appointment_type' => 'required|in:intake_session,follow_up_session,case_closed',
        'notes' => 'required|string|min:10',
        'follow_up_actions' => 'nullable|string',
        'root_causes' => 'nullable|array',
        'root_causes.*' => 'in:' . implode(',', $rootCauseKeys),
        'referred_by_source'      => 'nullable|string|max:255',
        'referred_to_destination' => 'nullable|string|max:255',
    ]);

    SessionNote::create([
        'appointment_id' => $appointment->id,
        'counselor_id' => $effectiveCounselorId,
        'student_id' => $appointment->student_id,
        'notes' => $validated['notes'],
        'follow_up_actions' => $validated['follow_up_actions'] ?? null,
        'root_causes' => $validated['root_causes'] ?? [],
        'session_date' => $appointment->appointment_date,
        'session_type' => 'regular',
        'appointment_type' => $validated['appointment_type'],
        'referred_by_source'      => $validated['referred_by_source'] ?? null,
        'referred_to_destination' => $validated['referred_to_destination'] ?? null,
    ]);

    if ($appointment->status !== 'cancelled') {
        $appointment->update(['status' => 'completed']);

        if ($appointment->booking_type === 'Initial Interview') {
            $appointment->loadMissing('student');
            if ($appointment->student && $appointment->student->initial_interview_completed !== true) {
                $appointment->student->update(['initial_interview_completed' => true]);
            }
        }
    }

    return redirect()
        ->route('counselor.appointments.session', $appointment)
        ->with('success', 'Session notes saved successfully!');
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
        $counselorAssignments = Counselor::where('user_id', Auth::id())->get();
        $counselor = $counselorAssignments->first();
        if (!$counselor || !$counselorAssignments->pluck('id')->contains($appointment->counselor_id)) {
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
    $counselorAssignments = Counselor::where('user_id', Auth::id())->get();
    $counselor = $counselorAssignments->first();
    if (!$counselor || !$counselorAssignments->pluck('id')->contains($appointment->counselor_id)) {
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

