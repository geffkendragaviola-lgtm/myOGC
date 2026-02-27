<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Counselor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FeedbackController extends Controller
{
    /**
     * Display a listing of all feedback for counselors and admins
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Check if user is counselor OR admin
        $isCounselor = \App\Models\Counselor::where('user_id', $userId)->exists();
        $isAdmin = \App\Models\Admin::where('user_id', $userId)->exists();

        if (!$isCounselor && !$isAdmin) {
            abort(403, 'Access denied. Counselor or Admin access required.');
        }

        // For counselors and admins
        $query = Feedback::with(['user.student.college', 'targetCounselor.user'])
            ->orderBy('created_at', 'desc');

        if ($isCounselor && !$isAdmin) {
            $counselorAssignments = Counselor::where('user_id', $userId)->get(['id', 'is_head']);
            $counselorIds = $counselorAssignments->pluck('id');
            $isHead = (bool) $counselorAssignments->firstWhere('is_head', true);

            if (!$isHead) {
                $query->where(function ($q) use ($counselorIds) {
                    $q->whereNull('target_counselor_id')
                        ->orWhereIn('target_counselor_id', $counselorIds);
                });
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->whereRaw('LOWER(first_name) LIKE ?', ["%{$search}%"])
                      ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$search}%"]);
                })
                ->orWhere('service_availed', 'like', "%{$search}%")
                ->orWhere('comments', 'like', "%{$search}%");
            });
        }

        // Rating filter (legacy)
        if ($request->has('rating') && $request->rating) {
            $query->where('satisfaction_rating', $request->rating);
        }

        // Service type filter
        if ($request->has('service') && $request->service) {
            $query->where('service_availed', $request->service);
        }

        // Date range filter
        if ($request->has('date_range') && $request->date_range) {
            $now = Carbon::now();
            switch ($request->date_range) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        $now->startOfWeek()->toDateTimeString(),
                        $now->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'month':
                    $query->whereBetween('created_at', [
                        $now->startOfMonth()->toDateTimeString(),
                        $now->endOfMonth()->toDateTimeString()
                    ]);
                    break;
            }
        }

        // Anonymous filter
        if ($request->has('anonymous') && $request->anonymous !== '') {
            $query->where('is_anonymous', $request->anonymous);
        }

        $feedbacks = $query->paginate(15);

        // Get unique service types for filter dropdown
        $serviceTypes = Feedback::distinct()->pluck('service_availed')->sort();

        // Calculate statistics based on the same visibility scope
        $statsQuery = (clone $query);
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'average_rating' => round((clone $statsQuery)->avg('satisfaction_rating') ?? 0, 1),
            'anonymous_count' => (clone $statsQuery)->where('is_anonymous', true)->count(),
            'rating_distribution' => (clone $statsQuery)
                ->reorder()
                ->selectRaw('satisfaction_rating, COUNT(*) as count')
                ->groupBy('satisfaction_rating')
                ->orderBy('satisfaction_rating', 'desc')
                ->get()
                ->pluck('count', 'satisfaction_rating')
        ];

        // Determine which view to use based on user role
        if ($isAdmin) {
            return view('admin.feedback.index', compact('feedbacks', 'stats', 'serviceTypes'));
        } else {
            return view('counselor.feedback.index', compact('feedbacks', 'stats', 'serviceTypes'));
        }
    }

    /**
     * Show the feedback form for students
     */
    public function create()
    {
        $counselors = Counselor::with('user')
            ->whereHas('user', function ($q) {
                $q->where('role', 'counselor');
            })
            ->get()
            ->sortBy(function ($c) {
                return strtolower(trim(($c->user->last_name ?? '') . ' ' . ($c->user->first_name ?? '')));
            })
            ->values();

        return view('feedback', compact('counselors'));
    }

    /**
     * Display the feedback form (alternative method for student feedback page)
     * This is the main method students will use
     */
    public function showForm()
    {
        return $this->create();
    }

    /**
     * Store a newly created feedback
     */
    public function store(Request $request)
    {
        $sqdKeys = [
            'sqd0', 'sqd1', 'sqd2', 'sqd3_1', 'sqd3_2', 'sqd4', 'sqd5', 'sqd6',
            'sqd7_1', 'sqd7_2', 'sqd7_3', 'sqd8', 'sqd9',
        ];

        $validated = $request->validate(array_merge([
            'service_availed' => 'required|string|in:COUNSELING SERVICES,TESTING-TEST ADMINISTRATION,TESTING - TEST INTERPRETATION,REQUEST FOR ISSUANCE OF CERTIFICATION AND REQUEST FOR ISSUANCE OF RECOMMENDATION LETTER,INITIAL INTERVIEW FOR FRESHMEN,REFERRAL SERVICE',
            'target_counselor_id' => 'required|string',
            'share_mobile' => 'sometimes|boolean',
            'cc1' => 'required|string|max:50',
            'cc2' => 'nullable|string|max:50',
            'cc3' => 'nullable|string|max:50',
            'comments' => 'nullable|string|max:1000',
            'is_anonymous' => 'sometimes|boolean',
        ], collect($sqdKeys)->mapWithKeys(fn ($k) => [$k => 'required|integer|between:1,5'])->all()));

        $targetCounselorId = $validated['target_counselor_id'] === 'unidentified'
            ? null
            : (int) $validated['target_counselor_id'];

        if (!is_null($targetCounselorId)) {
            $request->validate([
                'target_counselor_id' => 'exists:counselors,id',
            ]);
        }

        $personnelName = "I can't identify";
        if (!is_null($targetCounselorId)) {
            $counselor = Counselor::with('user')->find($targetCounselorId);
            if ($counselor && $counselor->user) {
                $personnelName = trim($counselor->user->first_name . ' ' . $counselor->user->last_name);
            }
        }

        Feedback::create(array_merge(
            [
                'user_id' => Auth::id(),
                'target_counselor_id' => $targetCounselorId,
                'service_availed' => $validated['service_availed'],
                'personnel_name' => $personnelName,
                'comments' => $validated['comments'] ?? null,
                'is_anonymous' => (bool) ($validated['is_anonymous'] ?? false),
                'share_mobile' => (bool) ($validated['share_mobile'] ?? false),
                'cc1' => $validated['cc1'],
                'cc2' => $validated['cc2'] ?? null,
                'cc3' => $validated['cc3'] ?? null,
                // Keep legacy satisfaction_rating for existing screens/statistics
                'satisfaction_rating' => (int) ($validated['sqd0'] ?? 0),
            ],
            collect($sqdKeys)->mapWithKeys(fn ($k) => [$k => (int) $validated[$k]])->all(),
        ));

        return redirect()->route('feedback')
            ->with('success', 'Thank you for your feedback! Your input helps us improve our services.');
    }

    /**
     * Display individual feedback details
     */
    public function show(Feedback $feedback)
    {
        $userId = Auth::id();

        // Check if user is counselor, admin, or the feedback owner
        $isCounselor = \App\Models\Counselor::where('user_id', $userId)->exists();
        $isAdmin = \App\Models\Admin::where('user_id', $userId)->exists();
        $isOwner = $feedback->user_id == $userId;

        if (!$isCounselor && !$isAdmin && !$isOwner) {
            abort(403, 'Access denied.');
        }

        if ($isCounselor && !$isAdmin) {
            $counselorAssignments = Counselor::where('user_id', $userId)->get(['id', 'is_head']);
            $counselorIds = $counselorAssignments->pluck('id');
            $isHead = (bool) $counselorAssignments->firstWhere('is_head', true);
            $isAllowed = $isHead
                || is_null($feedback->target_counselor_id)
                || $counselorIds->contains((int) $feedback->target_counselor_id);

            if (!$isAllowed) {
                abort(403, 'Access denied.');
            }
        }

        $feedback->load(['user.student.college', 'targetCounselor.user']);

        // Use appropriate view based on user role
        if ($isCounselor) {
            return view('counselor.feedback.show', compact('feedback'));
        } elseif ($isAdmin) {
            return view('admin.feedback.show', compact('feedback'));
        } else {
            return view('student.feedback.show', compact('feedback'));
        }
    }

    /**
     * Export feedback to Excel/CSV
     */
    public function export(Request $request)
    {
        $userId = Auth::id();

        // Check if user is counselor or admin
        $isCounselor = \App\Models\Counselor::where('user_id', $userId)->exists();
        $isAdmin = \App\Models\Admin::where('user_id', $userId)->exists();

        if (!$isCounselor && !$isAdmin) {
            abort(403, 'Access denied. Counselor or Admin access required.');
        }

        $query = Feedback::with('user');

        // Apply filters same as index method
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                })
                ->orWhere('service_availed', 'like', "%{$search}%")
                ->orWhere('comments', 'like', "%{$search}%");
            });
        }

        if ($request->has('rating') && $request->rating) {
            $query->where('satisfaction_rating', $request->rating);
        }

        if ($request->has('service') && $request->service) {
            $query->where('service_availed', $request->service);
        }

        $feedbacks = $query->orderBy('created_at', 'desc')->get();

        $fileName = 'feedback_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($feedbacks) {
            $file = fopen('php://output', 'w');

            // Add BOM for UTF-8
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Headers
            fputcsv($file, [
                'Student Name',
                'Service Availed',
                'Satisfaction Rating',
                'Rating Label',
                'Comments',
                'Is Anonymous',
                'Submitted Date'
            ]);

            // Data
            foreach ($feedbacks as $feedback) {
                $studentName = $feedback->is_anonymous ?
                    'Anonymous' :
                    $feedback->user->first_name . ' ' . $feedback->user->last_name;

                fputcsv($file, [
                    $studentName,
                    $feedback->service_availed,
                    $feedback->satisfaction_rating,
                    \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating),
                    $feedback->comments,
                    $feedback->is_anonymous ? 'Yes' : 'No',
                    $feedback->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Simple admin feedback index (legacy method for admin-only access)
     * This is kept for backward compatibility
     */
    public function adminIndex()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $feedbacks = Feedback::with('user')->latest()->paginate(20);

        return view('admin.feedback.index', compact('feedbacks'));
    }
}
