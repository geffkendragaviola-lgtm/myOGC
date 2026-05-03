<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\Admin;
use App\Models\College;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Appointment;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Requests\EventRequest;
use App\Services\GoogleCalendarService;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        // Force create admin profile if missing
        $admin = Admin::with('user')->where('user_id', $user->id)->first();
        if (!$admin) {
            $admin = Admin::create([
                'user_id' => $user->id,
                'credentials' => 'System Administrator'
            ]);
        }

        // Stats with relationships
        $totalAppointments = Appointment::count();
        $completedCount    = Appointment::where('status', 'completed')->count();
        $pendingCount      = Appointment::whereIn('status', ['pending', 'approved'])->count();
        $noShowCount       = Appointment::where('status', 'no_show')->count();
        $referralCount     = Appointment::where('status', 'referred')->count();
        $completionRate    = $totalAppointments > 0 ? round(($completedCount / $totalAppointments) * 100, 1) : 0;
        $noShowRate        = $totalAppointments > 0 ? round(($noShowCount / $totalAppointments) * 100, 1) : 0;

        $avgSatisfaction = Feedback::whereNotNull('satisfaction_rating')->avg('satisfaction_rating');
        $avgSatisfaction = $avgSatisfaction ? round($avgSatisfaction, 1) : null;

        $followUpCount = \App\Models\SessionNote::where('requires_follow_up', true)->count();

        // Appointments per college
        $appointmentsByCollege = Appointment::select('colleges.name as college_name', DB::raw('count(*) as total'))
            ->join('students', 'appointments.student_id', '=', 'students.id')
            ->join('colleges', 'students.college_id', '=', 'colleges.id')
            ->groupBy('colleges.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $stats = [
            'total_users'       => User::count(),
            'total_students'    => Student::count(),
            'total_counselors'  => Counselor::count(),
            'total_admins'      => Admin::count(),
            'total_events'      => Event::count(),
            'active_events'     => Event::where('is_active', true)->count(),
            'upcoming_events'   => Event::where('event_start_date', '>=', now()->toDateString())->count(),
            'total_appointments'=> $totalAppointments,
            'completed_count'   => $completedCount,
            'pending_count'     => $pendingCount,
            'no_show_count'     => $noShowCount,
            'referral_count'    => $referralCount,
            'completion_rate'   => $completionRate,
            'no_show_rate'      => $noShowRate,
            'avg_satisfaction'  => $avgSatisfaction,
            'follow_up_count'   => $followUpCount,
        ];

        // Recent events for admin dashboard
        $recentEvents = Event::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Recent users
        $recentUsers = User::with(['student', 'counselor', 'admin'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('admin', 'stats', 'recentUsers', 'recentEvents', 'appointmentsByCollege'));
    }

    /**
     * Display all events in the system
     */
    public function events(Request $request)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');
        $counselor = $request->get('counselor', 'all');

        $query = Event::with(['user', 'registrations']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($status !== 'all') {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'upcoming') {
                $query->where('event_start_date', '>=', now()->toDateString());
            } elseif ($status === 'past') {
                $query->where('event_end_date', '<', now()->toDateString());
            }
        }

        // Type filter
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        // Counselor filter
        if ($counselor !== 'all') {
            $query->where('user_id', $counselor);
        }

        $events = $query->orderBy('is_pinned', 'desc')
                       ->orderBy('event_start_date', 'desc')
                       ->orderBy('start_time', 'desc')
                       ->paginate(12);

        $counselors = Counselor::with('user')->get();

        return view('admin.events.index', compact('admin', 'events', 'search', 'status', 'type', 'counselor', 'counselors'));
    }

    public function appointments(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        if (!$admin) {
            $admin = Admin::create([
                'user_id' => $userId,
                'credentials' => 'System Administrator'
            ]);
        }

        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $collegeId = $request->get('college');
        $counselorId = $request->get('counselor');
        $date = $request->get('date');

        $query = Appointment::with([
            'student.user',
            'student.college',
            'counselor.user',
            'counselor.college',
            'referredCounselor.user',
            'originalCounselor.user',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('case_number', 'like', "%{$search}%")
                    ->orWhere('concern', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('student_id', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($q) use ($search) {
                                $q->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    })
                    ->orWhereHas('counselor', function ($q) use ($search) {
                        $q->whereHas('user', function ($q) use ($search) {
                            $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    });
            });
        }

        if ($collegeId) {
            $query->whereHas('counselor', function($q) use ($collegeId) {
                $q->where('college_id', $collegeId);
            });
        }

        if ($counselorId) {
            $query->where('counselor_id', $counselorId);
        }

        if ($date) {
            $query->whereDate('appointment_date', $date);
        }

        $statsQuery = (clone $query);

        $stats = [
            'total' => (clone $statsQuery)->count(),
        ];

        foreach (Appointment::getStatuses() as $s) {
            $stats[$s] = (clone $statsQuery)->where('status', $s)->count();
        }

        $stats['rejected_by_student'] = (clone $statsQuery)
            ->where('status', 'rejected')
            ->where('notes', 'like', '%by student%')
            ->count();

        $stats['cancelled_by_student'] = (clone $statsQuery)
            ->where('status', 'cancelled')
            ->where('notes', 'like', '%Cancelled by student%')
            ->count();

        $stats['referred_total'] = (clone $statsQuery)
            ->where(function ($q) {
                $q->whereNotNull('referred_to_counselor_id')
                  ->orWhereNotNull('original_counselor_id')
                  ->orWhere('status', 'referred');
            })
            ->count();

        if ($status !== 'all') {
            if ($status === 'rejected_by_student') {
                $query->where('status', 'rejected')
                    ->where('notes', 'like', '%by student%');
            } elseif ($status === 'cancelled_by_student') {
                $query->where('status', 'cancelled')
                    ->where('notes', 'like', '%Cancelled by student%');
            } elseif ($status === 'referred_total') {
                $query->where(function ($q) {
                    $q->whereNotNull('referred_to_counselor_id')
                      ->orWhereNotNull('original_counselor_id')
                      ->orWhere('status', 'referred');
                });
            } else {
                $query->where('status', $status);
            }
        }

        $appointments = $query
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        $monthStart = Carbon::now()->startOfMonth()->toDateString();
        $monthEnd = Carbon::now()->endOfMonth()->toDateString();
        $totalAppointmentsThisMonth = Appointment::whereBetween('appointment_date', [$monthStart, $monthEnd])->count();

        $statuses = Appointment::getStatuses();
        $colleges = College::orderBy('name')->get();
        $counselorsList = Counselor::with('user', 'college')->get()->sortBy(function($c) {
            return $c->user->last_name ?? '';
        });

        return view('admin.appointments.index', compact(
            'admin',
            'appointments',
            'search',
            'status',
            'collegeId',
            'counselorId',
            'date',
            'statuses',
            'colleges',
            'counselorsList',
            'totalAppointmentsThisMonth',
            'stats'
        ));
    }

    public function getAppointmentDetails(Appointment $appointment)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $appointment->load([
            'student.user',
            'student.college',
            'counselor.user',
            'counselor.college',
            'sessionNotes',
            'referredCounselor.user',
            'originalCounselor.user',
        ]);

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

        $statusLabels = [
            'reschedule_requested' => 'Reschedule Requested (Pending Student Approval)',
            'reschedule_rejected' => 'Rejected by Student',
            'rescheduled' => 'Scheduled (Rescheduled)',
        ];

        $statusDisplay = $statusLabels[$appointment->status] ?? ucfirst(str_replace('_', ' ', $appointment->status));

        $referralOutcomeDisplay = null;
        if ($appointment->referral_outcome) {
            $referralOutcomeDisplay = ucfirst(str_replace('_', ' ', $appointment->referral_outcome));
        }

        if ($appointment->is_referred) {
            $originalCounselorName = $appointment->originalCounselor && $appointment->originalCounselor->user
                ? $appointment->originalCounselor->user->first_name . ' ' . $appointment->originalCounselor->user->last_name
                : 'Unknown Counselor';

            $referredCounselorName = $appointment->referredCounselor && $appointment->referredCounselor->user
                ? $appointment->referredCounselor->user->first_name . ' ' . $appointment->referredCounselor->user->last_name
                : 'Unknown Counselor';

            $suffix = '';
            if ($appointment->referral_previous_status === 'rescheduled') {
                $suffix .= ' (Rescheduled)';
            }

            $statusDisplay = "Referred from {$originalCounselorName} to {$referredCounselorName}{$suffix}";
        }

        return response()->json([
            'appointment' => [
                'id' => $appointment->id,
                'case_number' => $appointment->case_number,
                'concern' => $appointment->concern,
                'notes' => $appointment->notes,
                'status' => $appointment->status,
                'status_display' => $statusDisplay,
                'booking_type' => $appointment->booking_type,
                'booking_category' => $appointment->booking_category,
                'is_referred' => (bool) $appointment->is_referred,
                'referral_outcome_display' => $referralOutcomeDisplay,
                'referred_to_counselor_id' => $appointment->referred_to_counselor_id,
                'original_counselor_id' => $appointment->original_counselor_id,
                'referral_reason' => $appointment->referral_reason,
                'referral_requested_at' => $appointment->referral_requested_at?->toIso8601String(),
                'referral_outcome' => $appointment->referral_outcome,
                'referral_resolved_at' => $appointment->referral_resolved_at?->toIso8601String(),
                'appointment_date' => $appointment->appointment_date?->format('Y-m-d'),
                'start_time' => $appointment->start_time,
                'end_time' => $appointment->end_time,
                'proposed_date' => $appointment->proposed_date?->format('Y-m-d'),
                'proposed_start_time' => $appointment->proposed_start_time,
                'proposed_end_time' => $appointment->proposed_end_time,
                'has_session_notes' => $appointment->sessionNotes->isNotEmpty(),
                'session_notes_count' => $appointment->sessionNotes->count(),
                'session_notes_url' => route('admin.appointments.session-notes', $appointment),
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
                'id' => $appointment->student?->id,
                'student_id' => $appointment->student?->student_id,
                'year_level' => $appointment->student?->year_level,
                'course' => $appointment->student?->course,
                'initial_interview_completed' => $appointment->student?->initial_interview_completed,
                'initial_interview_completed_label' => $appointment->student?->initial_interview_completed === null
                    ? 'Not provided'
                    : ($appointment->student?->initial_interview_completed ? 'Yes' : 'No'),
                'profile_url' => $appointment->student
                    ? route('admin.students.edit', $appointment->student)
                    : null,
                'user' => [
                    'first_name' => $appointment->student?->user?->first_name,
                    'last_name' => $appointment->student?->user?->last_name,
                    'email' => $appointment->student?->user?->email,
                    'age' => $appointment->student?->user?->age,
                    'birthdate' => $appointment->student?->user?->birthdate?->format('Y-m-d'),
                    'sex' => $appointment->student?->user?->sex,
                    'address' => $appointment->student?->user?->address,
                    'phone_number' => $appointment->student?->user?->phone_number,
                ],
                'college' => [
                    'name' => $appointment->student?->college?->name,
                ],
            ],
            'counselor' => [
                'id' => $appointment->counselor?->id,
                'name' => $appointment->counselor && $appointment->counselor->user
                    ? $appointment->counselor->user->first_name . ' ' . $appointment->counselor->user->last_name
                    : null,
                'college' => [
                    'name' => $appointment->counselor?->college?->name,
                ],
            ],
            'formatted_date' => $appointment->appointment_date?->format('F j, Y'),
            'formatted_time' => $appointment->start_time && $appointment->end_time
                ? (Carbon::parse($appointment->start_time)->format('g:i A') . ' - ' . Carbon::parse($appointment->end_time)->format('g:i A'))
                : null,
            'formatted_referral_date' => $appointment->referral_requested_at?->format('F j, Y g:i A'),
            'formatted_proposed_date' => $appointment->proposed_date?->format('F j, Y'),
            'formatted_proposed_time' => ($appointment->proposed_start_time && $appointment->proposed_end_time)
                ? (Carbon::parse($appointment->proposed_start_time)->format('g:i A') . ' - ' . Carbon::parse($appointment->proposed_end_time)->format('g:i A'))
                : null,
            'session_notes' => $sessionNotes,
        ]);
    }

    public function viewAppointmentSessionNotes(Appointment $appointment)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        if (!$admin) {
            $admin = Admin::create([
                'user_id' => $userId,
                'credentials' => 'System Administrator'
            ]);
        }

        $appointment->load([
            'student.user',
            'student.college',
            'counselor.user',
            'counselor.college',
            'sessionNotes',
        ]);

        $sessionNotes = $appointment->sessionNotes->sortByDesc('created_at');

        return view('admin.appointments.session-notes', compact('admin', 'appointment', 'sessionNotes'));
    }

    public function appointmentSessionsDashboard(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        if (!$admin) {
            $admin = Admin::create([
                'user_id' => $userId,
                'credentials' => 'System Administrator'
            ]);
        }

        $query = Appointment::with(['student.user', 'student.college', 'counselor.user', 'counselor.college', 'sessionNotes'])
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
            ->whereHas('sessionNotes');

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
                ->orWhereHas('counselor.user', function ($q) use ($search) {
                    $q->whereRaw('LOWER(first_name) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$search}%"]);
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

        return view('admin.appointments.session-dashboard', compact('admin', 'appointments'));
    }

    /**
     * Show the form for creating a new event as admin
     */
    public function createEvent()
    {
        $colleges = College::all();
        $counselors = Counselor::with(['user', 'college'])->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                $first = $group->first();
                $first->college_names = $group->pluck('college.name')->filter()->implode(', ');
                return $first;
            })->values();

        return view('admin.events.create', compact('colleges', 'counselors'));
    }

    /**
     * Store a newly created event as admin
     */
    public function storeEvent(EventRequest $request)
    {
        $data = $request->validated();
        $rawCounselorIds = $data['counselor_ids'] ?? [];
        unset($data['counselor_ids']);

        // Admin must pick which counselor owns the event
        $data['user_id'] = $request->input('user_id');

        if (empty($data['year_levels'])) {
            $data['year_levels'] = null;
        }

        if ($request->hasFile('image')) {
            $data['image'] = basename($request->file('image')->store('events', 'public'));
        }

        $event = Event::create($data);

        if (!$request->for_all_colleges && $request->has('colleges')) {
            $event->colleges()->sync($request->colleges);
        }

        if ($event->is_required) {
            $event->registerRequiredStudents();
        }

        $counselorIds = collect($rawCounselorIds)
            ->map(fn($id) => (int) $id)->unique()->filter()->values()->toArray();

        if (!empty($counselorIds)) {
            $event->assignedCounselors()->sync($counselorIds);
            app(GoogleCalendarService::class)->syncEventToCounselors($event, $counselorIds);
            $this->notifyCounselorsOfEventConflicts($event, $counselorIds);
            $this->notifyAssignedCounselors($event, $counselorIds);
        }

        return redirect()->route('admin.events')->with('success', 'Event created successfully!');
    }

    /**
     * Show the form for editing an event as admin
     */
    public function editEvent(Event $event)
    {
        $colleges = College::all();
        $selectedColleges = $event->colleges->pluck('id')->toArray();

        $counselors = Counselor::with(['user', 'college'])->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                $first = $group->first();
                $first->college_names = $group->pluck('college.name')->filter()->implode(', ');
                return $first;
            })->values();

        // Normalize selected counselors to primary IDs
        $assignedUserIds = $event->assignedCounselors->pluck('user_id')->unique()->toArray();
        $selectedCounselors = $counselors
            ->filter(fn($c) => in_array($c->user_id, $assignedUserIds))
            ->pluck('id')->toArray();

        return view('admin.events.edit', compact('event', 'colleges', 'selectedColleges', 'counselors', 'selectedCounselors'));
    }

    /**
     * Update the specified event as admin
     */
    public function updateEvent(EventRequest $request, Event $event)
    {
        $data = $request->validated();
        $rawCounselorIds = $data['counselor_ids'] ?? [];
        unset($data['counselor_ids']);

        // Admin can reassign the owning counselor
        $data['user_id'] = $request->input('user_id');

        if (empty($data['year_levels'])) {
            $data['year_levels'] = null;
        }

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete('events/' . $event->image);
            }
            $data['image'] = basename($request->file('image')->store('events', 'public'));
        }

        $event->update($data);

        if ($request->for_all_colleges) {
            $event->colleges()->detach();
        } else {
            $event->colleges()->sync($request->colleges ?? []);
        }

        if ($event->is_required && $event->shouldAutoRegister()) {
            $event->registerRequiredStudents();
        }

        $newCounselorIds = collect($rawCounselorIds)
            ->map(fn($id) => (int) $id)->unique()->filter()->values()->toArray();

        $previousCounselorIds = $event->assignedCounselors()->pluck('counselors.id')->toArray();
        $addedCounselorIds    = array_values(array_diff($newCounselorIds, $previousCounselorIds));
        $keptCounselorIds     = array_values(array_intersect($newCounselorIds, $previousCounselorIds));

        $removedIds = array_diff($previousCounselorIds, $newCounselorIds);
        if (!empty($removedIds)) {
            app(GoogleCalendarService::class)->removeEventFromCounselors($event, $removedIds);
        }

        $event->assignedCounselors()->sync($newCounselorIds);
        $event->refresh();

        if (!empty($newCounselorIds)) {
            app(GoogleCalendarService::class)->syncEventToCounselors($event, $newCounselorIds);
            $this->notifyCounselorsOfEventConflicts($event, $newCounselorIds);
        }

        if (!empty($addedCounselorIds)) {
            $this->notifyAssignedCounselors($event, $addedCounselorIds, isUpdate: false);
        }

        if (!empty($keptCounselorIds)) {
            $this->notifyAssignedCounselors($event, $keptCounselorIds, isUpdate: true);
        }

        return redirect()->route('admin.events')->with('success', 'Event updated successfully!');
    }

    /**
     * Delete an event as admin
     */
    public function deleteEvent(Event $event)
    {
        DB::beginTransaction();

        try {
            // Delete related registrations first
            EventRegistration::where('event_id', $event->id)->delete();

            // Delete the event
            $event->delete();

            DB::commit();

            return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }

    /**
     * Toggle event status (active/inactive) as admin
     */
    public function toggleEventStatus(Event $event)
    {
        try {
            $event->update([
                'is_active' => !$event->is_active
            ]);

            $status = $event->is_active ? 'activated' : 'deactivated';

            return redirect()->route('admin.events')
                ->with('success', "Event {$status} successfully!");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update event status: ' . $e->getMessage());
        }
    }
    public function toggleEventPin(Event $event)
    {
        $event->update(['is_pinned' => !$event->is_pinned]);

        return response()->json([
            'success' => true,
            'is_pinned' => $event->is_pinned,
            'message' => $event->is_pinned ? 'Event pinned.' : 'Event unpinned.',
        ]);
    }

    /**
     * Show event registrations as admin
     */
    public function showEventRegistrations(Event $event)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

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

        return view('admin.events.registrations', compact('admin', 'event', 'registrations', 'registrationStats'));
    }

    /**
     * Update registration status as admin
     */
    public function updateEventRegistrationStatus(Request $request, Event $event, EventRegistration $registration)
    {
        $request->validate([
            'status' => 'required|in:registered,attended,cancelled'
        ]);

        // Verify the registration belongs to the event
        if ($registration->event_id !== $event->id) {
            return redirect()->back()->with('error', 'Invalid registration for this event.');
        }

        try {
            $registration->update([
                'status' => $request->status
            ]);

            return redirect()->back()->with('success', 'Registration status updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update registration status: ' . $e->getMessage());
        }
    }

    /**
     * Export event registrations to CSV as admin
     */
    public function exportEventRegistrations(Event $event)
    {
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
                'Counselor: ' . $event->user->first_name . ' ' . $event->user->last_name,
                ''
            ]);

            // Column headers
            fputcsv($file, [
                'Student ID',
                'First Name',
                'Middle Name',
                'Last Name',
                'Age',
                'Sex',
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
                    $user->sex ?? 'N/A',
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

    /**
     * Display all users
     */
    public function users(Request $request)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $role = $request->get('role', 'all');
        $search = $request->get('search');

        $query = User::with(['student', 'counselor', 'admin']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('student_id', 'like', "%{$search}%");
                  })
                  ->orWhereHas('counselor', function($q) use ($search) {
                      $q->where('position', 'like', "%{$search}%");
                  });
            });
        }

        // Role filter
        if ($role !== 'all') {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('admin', 'users', 'role', 'search'));
    }

    /**
     * Show form to create new user
     */
    public function createUser()
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        $colleges = College::orderBy('name')->get();

        return view('admin.users.create', compact('admin', 'colleges'));
    }

    /**
     * Store a newly created user
     */
    public function storeUser(Request $request)
    {
        // Base validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,counselor',
            'phone_number' => 'nullable|string|max:20|unique:users,phone_number',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date',
            'sex' => 'nullable|in:male,female,other',
            'birthplace' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:100',

            'civil_status' => 'nullable|in:single,married,divorced,widowed',
            'citizenship' => 'nullable|string|max:50',
        ];

        // Add role-specific rules
        if ($request->role === 'student') {
            $rules['student_id'] = 'required|string|max:50|unique:students';
            $rules['year_level'] = 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate';
            $rules['course'] = 'required|string|max:255';
            $rules['college_id'] = 'required|exists:colleges,id';
            $rules['msu_sase_score'] = 'nullable|numeric|min:0|max:180';
            $rules['academic_year'] = 'nullable|string|max:20';
            $rules['student_status'] = 'required|in:new,transferee,returnee,shiftee';
            $rules['initial_interview_completed'] = 'nullable|in:yes,no';
        } elseif ($request->role === 'counselor') {
            $rules['position'] = 'required|string|max:255';
            $rules['credentials'] = 'required|string|max:255';
            $rules['counselor_college_id'] = 'required|exists:colleges,id';
            $rules['specialization'] = 'nullable|string|max:500';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // Calculate age from birthdate
            $age = null;
            if ($request->birthdate) {
                $age = Carbon::parse($request->birthdate)->age;
            }

            // Create user
            $user = User::create([
                'first_name' => strip_tags($request->first_name),
                'last_name' => strip_tags($request->last_name),
                'middle_name' => $request->middle_name ? strip_tags($request->middle_name) : null,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'address' => $request->address ? strip_tags($request->address) : null,
                'birthdate' => $request->birthdate,
                'age' => $age,
                'sex' => $request->sex,
                'birthplace' => $request->birthplace ? strip_tags($request->birthplace) : null,
                'religion' => $request->religion ? strip_tags($request->religion) : null,

                'civil_status' => $request->civil_status,
                'citizenship' => $request->citizenship ? strip_tags($request->citizenship) : null,

            ]);

            // Create role-specific profile
            if ($request->role === 'student') {
                $student = Student::create([
                    'user_id' => $user->id,
                    'student_id' => $request->student_id,
                    'year_level' => $request->year_level,
                    'course' => strip_tags($request->course),
                    'college_id' => $request->college_id,
                    'msu_sase_score' => $request->msu_sase_score,
                    'academic_year' => $request->academic_year,
                    'student_status' => $request->student_status,
                    'initial_interview_completed' => $request->initial_interview_completed ?? 'no',
                ]);
                
                \App\Models\StudentPersonalData::create(['student_id' => $student->id]);
                \App\Models\StudentFamilyData::create(['student_id' => $student->id]);
                \App\Models\StudentAcademicData::create(['student_id' => $student->id]);
                \App\Models\StudentLearningResources::create(['student_id' => $student->id]);
                \App\Models\StudentPsychosocialData::create(['student_id' => $student->id]);
                \App\Models\StudentNeedsAssessment::create(['student_id' => $student->id]);

            } elseif ($request->role === 'counselor') {
                Counselor::create([
                    'user_id' => $user->id,
                    'position' => strip_tags($request->position),
                    'credentials' => strip_tags($request->credentials),
                    'college_id' => $request->counselor_college_id,
                    'specialization' => $request->specialization ? strip_tags($request->specialization) : null,
                    'is_head' => $request->has('is_head'),
                ]);

            }

            DB::commit();

            if ($request->role === 'student') {
                return redirect()->route('admin.students.edit', $student)->with('success', 'Student created successfully. Please complete their profile details below.');
            } else {
                return redirect()->route('admin.counselors')->with('success', 'Counselor user created successfully.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create user. Please ensure all details are correct.']);
        }
    }

    /**
     * Show form to edit user
     */
    public function editUser(User $user)
    {
        $userId = Auth::id();
        $adminUser = Admin::with('user')->where('user_id', $userId)->first();
        $colleges = College::orderBy('name')->get();

        return view('admin.users.edit', compact('adminUser', 'user', 'colleges'));
    }

    /**
     * Update the specified user
     */
    public function updateUser(Request $request, User $user)
    {
        // Base validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone_number')->ignore($user->id)],
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date',
            'sex' => 'nullable|in:male,female,other',
            'birthplace' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:100',

            'civil_status' => 'nullable|in:single,married,divorced,widowed',
            'citizenship' => 'nullable|string|max:50',
        ];

        // Add role-specific rules
        if ($user->role === 'student') {
            $rules['student_id'] = 'required|string|max:50|unique:students,student_id,' . ($user->student ? $user->student->id : 'NULL');
            $rules['year_level'] = 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate';
            $rules['course'] = 'required|string|max:255';
            $rules['college_id'] = 'required|exists:colleges,id';
        } elseif ($user->role === 'counselor') {
            $rules['position'] = 'required|string|max:255';
            $rules['credentials'] = 'required|string|max:255';
            $rules['counselor_college_id'] = 'required|exists:colleges,id';
            $rules['specialization'] = 'nullable|string|max:500';
        } elseif ($user->role === 'admin') {
            $rules['admin_credentials'] = 'required|string|max:255';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // Calculate age from birthdate
            $age = null;
            if ($request->birthdate) {
                $age = Carbon::parse($request->birthdate)->age;
            }

            // Update user
            $user->update([
                'first_name' => strip_tags($request->first_name),
                'last_name' => strip_tags($request->last_name),
                'middle_name' => $request->middle_name ? strip_tags($request->middle_name) : null,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address ? strip_tags($request->address) : null,
                'birthdate' => $request->birthdate,
                'age' => $age,
                'sex' => $request->sex,
                'birthplace' => $request->birthplace ? strip_tags($request->birthplace) : null,
                'religion' => $request->religion ? strip_tags($request->religion) : null,
                'affiliation' => $request->affiliation ? strip_tags($request->affiliation) : null,
                'civil_status' => $request->civil_status,
                'citizenship' => $request->citizenship ? strip_tags($request->citizenship) : null,
            ]);

            // Update role-specific profile
            if ($user->role === 'student') {
                if ($user->student) {
                    $user->student->update([
                        'student_id' => $request->student_id,
                        'year_level' => $request->year_level,
                        'course' => strip_tags($request->course),
                        'college_id' => $request->college_id,
                    ]);
                } else {
                    Student::create([
                        'user_id' => $user->id,
                        'student_id' => $request->student_id,
                        'year_level' => $request->year_level,
                        'course' => strip_tags($request->course),
                        'college_id' => $request->college_id,
                    ]);
                }

            } elseif ($user->role === 'counselor') {
                if ($user->counselor) {
                    $user->counselor->update([
                        'position' => strip_tags($request->position),
                        'credentials' => strip_tags($request->credentials),
                        'college_id' => $request->counselor_college_id,
                        'specialization' => $request->specialization ? strip_tags($request->specialization) : null,
                        'is_head' => $request->has('is_head'),
                    ]);
                } else {
                    Counselor::create([
                        'user_id' => $user->id,
                        'position' => strip_tags($request->position),
                        'credentials' => strip_tags($request->credentials),
                        'college_id' => $request->counselor_college_id,
                        'specialization' => $request->specialization ? strip_tags($request->specialization) : null,
                        'is_head' => $request->has('is_head'),
                    ]);
                }

            } elseif ($user->role === 'admin') {
                if ($user->admin) {
                    $user->admin->update([
                        'credentials' => strip_tags($request->admin_credentials),
                    ]);
                } else {
                    Admin::create([
                        'user_id' => $user->id,
                        'credentials' => strip_tags($request->admin_credentials),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.users')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update user. Please ensure all details are correct.']);
        }
    }

    /**
     * Delete a user
     */
    public function deleteUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        DB::beginTransaction();

        try {
            // Delete role-specific profile first
            switch ($user->role) {
                case 'student':
                    if ($user->student) {
                        $user->student->delete();
                    }
                    break;
                case 'counselor':
                    if ($user->counselor) {
                        $user->counselor->delete();
                    }
                    break;
                case 'admin':
                    if ($user->admin) {
                        $user->admin->delete();
                    }
                    break;
            }

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()->route('admin.users')->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Display all students
     */
    public function students(Request $request)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $search = $request->get('search');
        $college = $request->get('college');

        $query = Student::with(['user', 'college', 'lastSessionNote', 'needsAssessment']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // College filter
        if ($college) {
            $query->where('college_id', $college);
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(10);
        $colleges = College::orderBy('name')->get();

        $studentsPerCollege = College::query()
            ->withCount('students')
            ->orderBy('name')
            ->get();

        $totalStudents = Student::count();

        $collegeCounselors = Counselor::with('user')
            ->where('is_head', true)
            ->get()
            ->keyBy('college_id');

        return view('admin.dashboards.students', compact('admin', 'students', 'colleges', 'studentsPerCollege', 'search', 'college', 'totalStudents', 'collegeCounselors'));
    }

    public function showStudentProfile(Student $student)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

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

        return view('student.show', compact('admin', 'student'));
    }

    public function toggleHighRisk(Request $request, Student $student)
    {
        $request->validate([
            'is_high_risk'    => 'required|boolean',
            'high_risk_notes' => 'nullable|string|max:1000',
        ]);

        $student->update([
            'is_high_risk'           => $request->is_high_risk,
            'high_risk_notes'        => $request->high_risk_notes,
            'high_risk_flagged_at'   => $request->is_high_risk ? now() : null,
            'high_risk_flagged_by'   => $request->is_high_risk ? auth()->id() : null,
            // If admin explicitly unflagged, mark override so assessment logic doesn't re-trigger
            'high_risk_overridden'   => ! $request->is_high_risk,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'High risk status updated successfully.'
        ]);
    }

    public function editStudent(Student $student)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $student->load([
            'user',
            'college',
            'personalData',
            'familyData',
            'academicData',
            'learningResources',
            'psychosocialData',
            'needsAssessment',
        ]);

        $colleges = College::orderBy('name')->get();

        return view('admin.students.edit', compact('admin', 'student', 'colleges'));
    }

    public function updateStudent(Request $request, Student $student)
    {
        $student->load([
            'user',
            'personalData',
            'familyData',
            'academicData',
            'learningResources',
            'psychosocialData',
            'needsAssessment',
        ]);

        $rules = [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($student->user_id)],
            'phone_number' => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone_number')->ignore($student->user_id)],
            'address' => 'nullable|string',
            'birthdate' => 'nullable|date',
            'sex' => 'nullable|in:male,female,other',
            'birthplace' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:100',
            'civil_status' => 'nullable|in:single,married,not legally married,divorced,widowed,separated,others',
            'number_of_children' => 'nullable|integer|min:0',
            'citizenship' => 'nullable|string|max:50',

            'student_id' => ['required', 'string', 'max:50', Rule::unique('students', 'student_id')->ignore($student->id)],
            'year_level' => 'required|string|max:50',
            'course' => 'required|string|max:100',
            'college_id' => 'required|exists:colleges,id',
            'msu_sase_score' => 'nullable|numeric',
            'academic_year' => 'nullable|string|max:20',
            'student_status' => 'nullable|in:new,transferee,returnee,shiftee',

            'personal.nickname' => 'nullable|string|max:100',
            'personal.home_address' => 'nullable|string',
            'personal.stays_with' => 'nullable|in:parents/guardian,board/roommates,relatives,friends,employer,living on my own',
            'personal.working_student' => 'nullable|in:yes full time,yes part time,no but planning to work,no and have no plan to work',
            'personal.talents_skills' => 'nullable|string',
            'personal.leisure_activities' => 'nullable|string',
            'personal.serious_medical_condition' => 'nullable|string|max:255',
            'personal.physical_disability' => 'nullable|string|max:255',
            'personal.sex_identity' => 'nullable|in:male/man,female/woman,transsex male/man,transsex female/woman,sex variant/nonconforming,not listed,prefer not to say',
            'personal.romantic_attraction' => 'nullable|in:my same sex,opposite sex,both men and women,all sexes,neither sex,prefer not to answer',

            'family.father_name' => 'nullable|string|max:100',
            'family.father_deceased' => 'nullable|boolean',
            'family.father_occupation' => 'nullable|string|max:100',
            'family.father_phone_number' => 'nullable|string|max:20',
            'family.mother_name' => 'nullable|string|max:100',
            'family.mother_deceased' => 'nullable|boolean',
            'family.mother_occupation' => 'nullable|string|max:100',
            'family.mother_phone_number' => 'nullable|string|max:20',
            'family.parents_marital_status' => 'nullable|in:married,not legally married,separated,both parents remarried,one parent remarried',
            'family.family_monthly_income' => 'nullable|in:below 3k,3001-5000,5001-8000,8001-10000,10001-15000,15001-20000,20001 above',
            'family.guardian_name' => 'nullable|string|max:100',
            'family.guardian_occupation' => 'nullable|string|max:100',
            'family.guardian_phone_number' => 'nullable|string|max:20',
            'family.guardian_relationship' => 'nullable|string|max:50',
            'family.ordinal_position' => 'nullable|in:only child,eldest,middle,youngest',
            'family.number_of_siblings' => 'nullable|integer|min:0',
            'family.home_environment_description' => 'nullable|string',

            'academic.shs_gpa' => 'nullable|numeric',
            'academic.is_scholar' => 'nullable|boolean',
            'academic.scholarship_type' => 'nullable|string|max:100',
            'academic.school_last_attended' => 'nullable|string|max:255',
            'academic.school_address' => 'nullable|string|max:255',
            'academic.shs_track' => 'nullable|in:academic,arts/design,tech-voc,sports',
            'academic.shs_strand' => 'nullable|in:GA,STEM,HUMMS,ABM',
            'academic.awards_honors' => 'nullable|string',
            'academic.student_organizations' => 'nullable|string',
            'academic.co_curricular_activities' => 'nullable|string',
            'academic.career_option_1' => 'nullable|string|max:100',
            'academic.career_option_2' => 'nullable|string|max:100',
            'academic.career_option_3' => 'nullable|string|max:100',
            'academic.course_choice_by' => 'nullable|in:own choice,parents choice,relative choice,sibling choice,according to MSU-SASE score/slot,others',
            'academic.course_choice_reason' => 'nullable|string',
            'academic.msu_choice_reasons' => 'nullable|string',
            'academic.future_career_plans' => 'nullable|string',

            'learning.internet_access' => 'nullable|in:no internet access,limited internet access,full internet access',
            'learning.technology_gadgets' => 'nullable|string',
            'learning.internet_connectivity' => 'nullable|string',
            'learning.distance_learning_readiness' => 'nullable|in:fully ready,ready,a little ready,not ready',
            'learning.learning_space_description' => 'nullable|string',

            'psychosocial.personality_characteristics' => 'nullable|string',
            'psychosocial.coping_mechanisms' => 'nullable|string',
            'psychosocial.mental_health_perception' => 'nullable|string',
            'psychosocial.had_counseling_before' => 'nullable|boolean',
            'psychosocial.sought_psychologist_help' => 'nullable|boolean',
            'psychosocial.problem_sharing_targets' => 'nullable|string',
            'psychosocial.needs_immediate_counseling' => 'nullable|boolean',
            'psychosocial.future_counseling_concerns' => 'nullable|string',

            'needs.improvement_needs' => 'nullable|string',
            'needs.financial_assistance_needs' => 'nullable|string',
            'needs.personal_social_needs' => 'nullable|string',
            'needs.stress_responses' => 'nullable|string',
            'needs.easy_discussion_target' => 'nullable|in:guidance counselor,parents,teachers,brothers/sisters,friends/relatives,nobody,others',
            'needs.counseling_perceptions' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        $toArray = function ($value) {
            if ($value === null || $value === '') {
                return null;
            }

            if (is_array($value)) {
                return array_values(array_filter(array_map('trim', $value), fn ($v) => $v !== ''));
            }

            $value = trim((string) $value);
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_filter(array_map('trim', $decoded), fn ($v) => $v !== ''));
            }

            $parts = preg_split('/[\r\n,]+/', $value);
            return array_values(array_filter(array_map('trim', $parts), fn ($v) => $v !== ''));
        };

        DB::beginTransaction();

        try {
            $age = null;
            if (!empty($validated['birthdate'])) {
                $age = Carbon::parse($validated['birthdate'])->age;
            }

            $student->user->update([
                'first_name' => strip_tags($validated['first_name']),
                'middle_name' => isset($validated['middle_name']) && $validated['middle_name'] !== null ? strip_tags($validated['middle_name']) : null,
                'last_name' => strip_tags($validated['last_name']),
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'address' => isset($validated['address']) && $validated['address'] !== null ? strip_tags($validated['address']) : null,
                'birthdate' => $validated['birthdate'] ?? null,
                'age' => $age,
                'sex' => $validated['sex'] ?? null,
                'birthplace' => isset($validated['birthplace']) && $validated['birthplace'] !== null ? strip_tags($validated['birthplace']) : null,
                'religion' => isset($validated['religion']) && $validated['religion'] !== null ? strip_tags($validated['religion']) : null,
                'civil_status' => $validated['civil_status'] ?? null,
                'number_of_children' => $validated['number_of_children'] ?? ($validated['civil_status'] && $validated['civil_status'] !== 'married' ? 0 : null),
                'citizenship' => isset($validated['citizenship']) && $validated['citizenship'] !== null ? strip_tags($validated['citizenship']) : null,
            ]);

            $student->update([
                'student_id' => $validated['student_id'],
                'year_level' => strip_tags($validated['year_level']),
                'course' => strip_tags($validated['course']),
                'college_id' => $validated['college_id'],
                'msu_sase_score' => $validated['msu_sase_score'] ?? null,
                'academic_year' => $validated['academic_year'] ?? null,
                'student_status' => $validated['student_status'] ?? $student->student_status,
            ]);

            $personal = $validated['personal'] ?? [];
            if (!empty(array_filter($personal, fn ($v) => $v !== null && $v !== ''))) {
                $student->personalData()->updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'nickname' => isset($personal['nickname']) ? strip_tags($personal['nickname']) : null,
                        'home_address' => isset($personal['home_address']) ? strip_tags($personal['home_address']) : null,
                        'stays_with' => $personal['stays_with'] ?? null,
                        'working_student' => $personal['working_student'] ?? null,
                        'talents_skills' => $toArray($personal['talents_skills'] ?? null),
                        'leisure_activities' => $toArray($personal['leisure_activities'] ?? null),
                        'serious_medical_condition' => isset($personal['serious_medical_condition']) ? strip_tags($personal['serious_medical_condition']) : null,
                        'physical_disability' => isset($personal['physical_disability']) ? strip_tags($personal['physical_disability']) : null,
                        'sex_identity' => $personal['sex_identity'] ?? null,
                        'romantic_attraction' => $personal['romantic_attraction'] ?? null,
                    ]
                );
            }

            $family = $validated['family'] ?? [];
            if (!empty(array_filter($family, fn ($v) => $v !== null && $v !== ''))) {
                $student->familyData()->updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'father_name' => isset($family['father_name']) ? strip_tags($family['father_name']) : null,
                        'father_deceased' => (bool)($family['father_deceased'] ?? false),
                        'father_occupation' => isset($family['father_occupation']) ? strip_tags($family['father_occupation']) : null,
                        'father_phone_number' => $family['father_phone_number'] ?? null,
                        'mother_name' => isset($family['mother_name']) ? strip_tags($family['mother_name']) : null,
                        'mother_deceased' => (bool)($family['mother_deceased'] ?? false),
                        'mother_occupation' => isset($family['mother_occupation']) ? strip_tags($family['mother_occupation']) : null,
                        'mother_phone_number' => $family['mother_phone_number'] ?? null,
                        'parents_marital_status' => $family['parents_marital_status'] ?? null,
                        'family_monthly_income' => $family['family_monthly_income'] ?? null,
                        'guardian_name' => isset($family['guardian_name']) ? strip_tags($family['guardian_name']) : null,
                        'guardian_occupation' => isset($family['guardian_occupation']) ? strip_tags($family['guardian_occupation']) : null,
                        'guardian_phone_number' => $family['guardian_phone_number'] ?? null,
                        'guardian_relationship' => isset($family['guardian_relationship']) ? strip_tags($family['guardian_relationship']) : null,
                        'ordinal_position' => $family['ordinal_position'] ?? null,
                        'number_of_siblings' => $family['number_of_siblings'] ?? 0,
                        'home_environment_description' => isset($family['home_environment_description']) ? strip_tags($family['home_environment_description']) : null,
                    ]
                );
            }

            $academic = $validated['academic'] ?? [];
            if (!empty(array_filter($academic, fn ($v) => $v !== null && $v !== ''))) {
                $student->academicData()->updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'shs_gpa' => $academic['shs_gpa'] ?? null,
                        'is_scholar' => (bool)($academic['is_scholar'] ?? false),
                        'scholarship_type' => isset($academic['scholarship_type']) ? strip_tags($academic['scholarship_type']) : null,
                        'school_last_attended' => isset($academic['school_last_attended']) ? strip_tags($academic['school_last_attended']) : null,
                        'school_address' => isset($academic['school_address']) ? strip_tags($academic['school_address']) : null,
                        'shs_track' => $academic['shs_track'] ?? null,
                        'shs_strand' => $academic['shs_strand'] ?? null,
                        'awards_honors' => $toArray($academic['awards_honors'] ?? null),
                        'student_organizations' => $toArray($academic['student_organizations'] ?? null),
                        'co_curricular_activities' => $toArray($academic['co_curricular_activities'] ?? null),
                        'career_option_1' => isset($academic['career_option_1']) ? strip_tags($academic['career_option_1']) : null,
                        'career_option_2' => isset($academic['career_option_2']) ? strip_tags($academic['career_option_2']) : null,
                        'career_option_3' => isset($academic['career_option_3']) ? strip_tags($academic['career_option_3']) : null,
                        'course_choice_by' => $academic['course_choice_by'] ?? null,
                        'course_choice_reason' => isset($academic['course_choice_reason']) ? strip_tags($academic['course_choice_reason']) : null,
                        'msu_choice_reasons' => $toArray($academic['msu_choice_reasons'] ?? null),
                        'future_career_plans' => isset($academic['future_career_plans']) ? strip_tags($academic['future_career_plans']) : null,
                    ]
                );
            }

            $learning = $validated['learning'] ?? [];
            if (!empty(array_filter($learning, fn ($v) => $v !== null && $v !== ''))) {
                $student->learningResources()->updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'internet_access' => $learning['internet_access'] ?? null,
                        'technology_gadgets' => $toArray($learning['technology_gadgets'] ?? null),
                        'internet_connectivity' => $toArray($learning['internet_connectivity'] ?? null),
                        'distance_learning_readiness' => $learning['distance_learning_readiness'] ?? null,
                        'learning_space_description' => isset($learning['learning_space_description']) ? strip_tags($learning['learning_space_description']) : null,
                    ]
                );
            }

            $psychosocial = $validated['psychosocial'] ?? [];
            if (!empty(array_filter($psychosocial, fn ($v) => $v !== null && $v !== ''))) {
                $student->psychosocialData()->updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'personality_characteristics' => $toArray($psychosocial['personality_characteristics'] ?? null),
                        'coping_mechanisms' => $toArray($psychosocial['coping_mechanisms'] ?? null),
                        'mental_health_perception' => isset($psychosocial['mental_health_perception']) ? strip_tags($psychosocial['mental_health_perception']) : null,
                        'had_counseling_before' => (bool)($psychosocial['had_counseling_before'] ?? false),
                        'sought_psychologist_help' => (bool)($psychosocial['sought_psychologist_help'] ?? false),
                        'problem_sharing_targets' => $toArray($psychosocial['problem_sharing_targets'] ?? null),
                        'needs_immediate_counseling' => (bool)($psychosocial['needs_immediate_counseling'] ?? false),
                        'future_counseling_concerns' => isset($psychosocial['future_counseling_concerns']) ? strip_tags($psychosocial['future_counseling_concerns']) : null,
                    ]
                );
            }

            $needs = $validated['needs'] ?? [];
            if (!empty(array_filter($needs, fn ($v) => $v !== null && $v !== ''))) {
                $student->needsAssessment()->updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'improvement_needs' => $toArray($needs['improvement_needs'] ?? null),
                        'financial_assistance_needs' => $toArray($needs['financial_assistance_needs'] ?? null),
                        'personal_social_needs' => $toArray($needs['personal_social_needs'] ?? null),
                        'stress_responses' => $toArray($needs['stress_responses'] ?? null),
                        'easy_discussion_target' => $needs['easy_discussion_target'] ?? null,
                        'counseling_perceptions' => $toArray($needs['counseling_perceptions'] ?? null),
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.students.edit', $student)->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update student. Please ensure all details are correct.']);
        }
    }

    /**
     * Display all counselors
     */
    public function editCounselor(Counselor $counselor)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        $counselor->load('user', 'college');
        $colleges = College::orderBy('name')->get();
        return view('admin.counselors.edit', compact('admin', 'counselor', 'colleges'));
    }

    public function updateCounselor(Request $request, Counselor $counselor)
    {
        $counselor->load('user');

        $validated = $request->validate([
            'first_name'          => 'required|string|max:100',
            'middle_name'         => 'nullable|string|max:100',
            'last_name'           => 'required|string|max:100',
            'email'               => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($counselor->user_id)],
            'phone_number'        => ['nullable', 'string', 'max:20', Rule::unique('users', 'phone_number')->ignore($counselor->user_id)],
            'address'             => 'nullable|string',
            'birthdate'           => 'nullable|date',
            'sex'                 => 'nullable|in:male,female,other',
            'college_id'          => 'required|exists:colleges,id',
            'position'            => 'required|string|max:100',
            'credentials'         => 'required|string|max:100',
            'specialization'      => 'nullable|string|max:100',
            'is_head'             => 'boolean',
            'daily_booking_limit' => 'nullable|integer|min:0|max:50',
            'google_calendar_id'  => 'nullable|string|max:255',
            'facebook_link'       => 'nullable|url|max:255',
        ]);

        DB::beginTransaction();
        try {
            $age = null;
            if (!empty($validated['birthdate'])) {
                $age = Carbon::parse($validated['birthdate'])->age;
            }

            $counselor->user->update([
                'first_name'   => strip_tags($validated['first_name']),
                'middle_name'  => isset($validated['middle_name']) ? strip_tags($validated['middle_name']) : null,
                'last_name'    => strip_tags($validated['last_name']),
                'email'        => $validated['email'],
                'phone_number' => $validated['phone_number'] ?? null,
                'address'      => isset($validated['address']) ? strip_tags($validated['address']) : null,
                'birthdate'    => $validated['birthdate'] ?? null,
                'age'          => $age,
                'sex'          => $validated['sex'] ?? null,
            ]);

            $counselor->update([
                'college_id'          => $validated['college_id'],
                'position'            => strip_tags($validated['position']),
                'credentials'         => strip_tags($validated['credentials']),
                'specialization'      => isset($validated['specialization']) ? strip_tags($validated['specialization']) : null,
                'is_head'             => (bool)($request->input('is_head', false)),
                'daily_booking_limit' => $validated['daily_booking_limit'] ?? null,
                'google_calendar_id'  => $validated['google_calendar_id'] ?? null,
                'facebook_link'       => $validated['facebook_link'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('admin.counselors.edit', $counselor)->with('success', 'Counselor updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Counselor update failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update counselor. Please ensure all details are correct.']);
        }
    }

    public function counselors(Request $request)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $search = $request->get('search');
        $college = $request->get('college');

        $query = Counselor::with(['user', 'college']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('position', 'like', "%{$search}%")
                  ->orWhere('credentials', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('college', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // College filter
        if ($college) {
            $query->where('college_id', $college);
        }

        $counselors = $query->orderBy('created_at', 'desc')->paginate(10);
        $colleges = College::orderBy('name')->get();

        return view('admin.dashboards.counselor', compact('admin', 'counselors', 'colleges', 'search', 'college'));
    }

    // ─── Private helpers (mirrors EventController) ───────────────────────────

    private function notifyAssignedCounselors(Event $event, array $counselorIds, bool $isUpdate = false): void
    {
        $counselors = Counselor::with('user')->whereIn('id', $counselorIds)->get()
            ->groupBy('user_id')->map(fn($g) => $g->first())->values();

        foreach ($counselors as $counselor) {
            try {
                Mail::to($counselor->user->email)
                    ->send(new \App\Mail\EventCounselorAssigned($event, $counselor, $isUpdate));
                $counselor->user->notify(
                    new \App\Notifications\EventCounselorAssignedNotification($event, $isUpdate)
                );
            } catch (\Throwable $e) {
                Log::warning('Admin: failed to notify counselor of event assignment', [
                    'event_id' => $event->id, 'counselor_id' => $counselor->id, 'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function notifyCounselorsOfEventConflicts(Event $event, array $counselorIds): void
    {
        $timezone = config('app.timezone', 'UTC');

        $eventDays = [];
        $current = $event->event_start_date->copy();
        while ($current->lte($event->event_end_date)) {
            $eventDays[] = $current->format('Y-m-d');
            $current->addDay();
        }

        $eventStartTime = Carbon::parse($event->start_time, $timezone);
        $eventEndTime   = Carbon::parse($event->end_time, $timezone);

        $counselors = Counselor::with('user')->whereIn('id', $counselorIds)->get()
            ->groupBy('user_id')->map(fn($g) => $g->first())->values();

        $googleCalendarService = app(GoogleCalendarService::class);

        foreach ($counselors as $counselor) {
            try {
                $allIds = Counselor::where('user_id', $counselor->user_id)->pluck('id')->toArray();

                $conflictingAppointments = Appointment::with(['student.user'])
                    ->whereIn('counselor_id', $allIds)
                    ->whereIn('appointment_date', $eventDays)
                    ->whereIn('status', ['pending', 'approved', 'completed', 'rescheduled', 'reschedule_rejected'])
                    ->get()
                    ->filter(function ($appt) use ($eventStartTime, $eventEndTime, $timezone) {
                        $s = Carbon::parse($appt->start_time, $timezone);
                        $e = Carbon::parse($appt->end_time, $timezone);
                        return $s->lt($eventEndTime) && $e->gt($eventStartTime);
                    });

                $calendarConflicts = collect();
                if ($counselor->google_calendar_id) {
                    foreach ($eventDays as $day) {
                        try {
                            $intervals = $googleCalendarService->getBusyIntervalsForDate(
                                $counselor->google_calendar_id,
                                Carbon::parse($day, $timezone)
                            );
                            $dayStart = Carbon::parse($day . ' ' . $event->start_time, $timezone);
                            $dayEnd   = Carbon::parse($day . ' ' . $event->end_time, $timezone);
                            foreach ($intervals as $interval) {
                                if ($interval['start']->lt($dayEnd) && $interval['end']->gt($dayStart)) {
                                    $calendarConflicts->push([
                                        'title' => $interval['title'],
                                        'date'  => $day,
                                        'start' => $interval['start'],
                                        'end'   => $interval['end'],
                                    ]);
                                }
                            }
                        } catch (\Throwable) {}
                    }
                }

                if ($conflictingAppointments->isEmpty() && $calendarConflicts->isEmpty()) {
                    continue;
                }

                Mail::to($counselor->user->email)->send(
                    new \App\Mail\EventScheduleConflict($event, $counselor, $conflictingAppointments, $calendarConflicts)
                );
                $counselor->user->notify(
                    new \App\Notifications\EventScheduleConflictNotification(
                        $event,
                        $conflictingAppointments->count() + $calendarConflicts->count()
                    )
                );
            } catch (\Throwable $e) {
                Log::warning('Admin: failed to send event conflict notification', [
                    'event_id' => $event->id, 'counselor_id' => $counselor->id, 'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
