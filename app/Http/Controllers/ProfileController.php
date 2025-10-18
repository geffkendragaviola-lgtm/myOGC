<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FeedbackController extends Controller
{
    /**
     * Display a listing of all feedback for counselors
     */
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Get all counselor assignments for this user
        $counselorAssignments = \App\Models\Counselor::where('user_id', $userId)->get();

        if ($counselorAssignments->isEmpty()) {
            abort(403, 'Counselor access required.');
        }

        $query = Feedback::with('user')
            ->orderBy('created_at', 'desc');

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

        // Rating filter
        if ($request->has('rating') && $request->rating) {
            $query->where('satisfaction_rating', $request->rating);
        }

        // Service type filter
        if ($request->has('service') && $request->service) {
            $query->where('service_availed', $request->service);
        }

        // Date range filter
        if ($request->has('date_range') && $request->date_range) {
            $now = \Carbon\Carbon::now();
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

        // Calculate statistics
        $stats = [
            'total' => Feedback::count(),
            'average_rating' => round(Feedback::avg('satisfaction_rating') ?? 0, 1),
            'anonymous_count' => Feedback::where('is_anonymous', true)->count(),
            'rating_distribution' => Feedback::selectRaw('satisfaction_rating, COUNT(*) as count')
                ->groupBy('satisfaction_rating')
                ->orderBy('satisfaction_rating', 'desc')
                ->get()
                ->pluck('count', 'satisfaction_rating')
        ];

        return view('counselor.feedback.index', compact(
            'feedbacks',
            'stats',
            'serviceTypes'
        ));
    }

    /**
     * Show the form for creating new feedback (for students)
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_availed' => 'required|string|max:255',
            'satisfaction_rating' => 'required|integer|between:1,5',
            'comments' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean'
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'service_availed' => $request->service_availed,
            'satisfaction_rating' => $request->satisfaction_rating,
            'comments' => $request->comments,
            'is_anonymous' => $request->is_anonymous ?? false
        ]);

        return redirect()->route('feedback.create')
            ->with('success', 'Thank you for your feedback! Your input helps us improve our services.');
    }

    /**
     * Display individual feedback details
     */
    public function show(Feedback $feedback)
    {
        $userId = Auth::id();
        $counselorIds = \App\Models\Counselor::where('user_id', $userId)->pluck('id');

        if ($counselorIds->isEmpty()) {
            abort(403, 'Counselor access required.');
        }

        $feedback->load('user');

        return view('counselor.feedback.show', compact('feedback'));
    }

    /**
     * Export feedback to Excel/CSV
     */
    public function export(Request $request)
    {
        $userId = Auth::id();
        $counselorIds = \App\Models\Counselor::where('user_id', $userId)->pluck('id');

        if ($counselorIds->isEmpty()) {
            abort(403, 'Counselor access required.');
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
                    Feedback::getRatingLabel($feedback->satisfaction_rating),
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
     * Get feedback statistics for dashboard
     */
    public function getStats()
    {
        $userId = Auth::id();
        $counselorIds = \App\Models\Counselor::where('user_id', $userId)->pluck('id');

        if ($counselorIds->isEmpty()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $totalFeedback = Feedback::count();
        $averageRating = round(Feedback::avg('satisfaction_rating') ?? 0, 1);

        $recentFeedback = Feedback::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($feedback) {
                return [
                    'id' => $feedback->id,
                    'student_name' => $feedback->is_anonymous ?
                        'Anonymous' :
                        $feedback->user->first_name . ' ' . $feedback->user->last_name,
                    'service' => $feedback->service_availed,
                    'rating' => $feedback->satisfaction_rating,
                    'rating_label' => Feedback::getRatingLabel($feedback->satisfaction_rating),
                    'comments' => Str::limit($feedback->comments, 50),
                    'date' => $feedback->created_at->format('M j, Y'),
                    'is_anonymous' => $feedback->is_anonymous
                ];
            });

        $ratingDistribution = Feedback::selectRaw('satisfaction_rating, COUNT(*) as count')
            ->groupBy('satisfaction_rating')
            ->orderBy('satisfaction_rating', 'desc')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->satisfaction_rating => $item->count];
            });

        return response()->json([
            'total_feedback' => $totalFeedback,
            'average_rating' => $averageRating,
            'recent_feedback' => $recentFeedback,
            'rating_distribution' => $ratingDistribution
        ]);
    }
}
