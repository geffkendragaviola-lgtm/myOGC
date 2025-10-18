<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
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
            // If user is student, only show their own feedback
            if (Auth::user()->role === 'student') {
                $feedbacks = Feedback::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                return view('student.feedback.index', compact('feedbacks'));
            }

            abort(403, 'Access denied. Counselor or Admin access required.');
        }

        // For counselors and admins - show all feedback
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
        // Use the existing feedback view for students
        return view('feedback');
    }

    /**
     * Display the feedback form (alternative method for student feedback page)
     * This is the main method students will use
     */
    public function showForm()
    {
        return view('feedback');
    }

    /**
     * Store a newly created feedback
     */
    public function store(Request $request)
    {
        // Handle both form field names - 'feedback' and 'comments'
        $validated = $request->validate([
            'service_availed' => 'required|string|in:counseling,mental_health_corner,consultation,other',
            'satisfaction_rating' => 'required|integer|between:1,5',
            'comments' => 'nullable|string|max:1000',
            'feedback' => 'nullable|string|max:1000', // For form compatibility
            'is_anonymous' => 'boolean'
        ]);

        // Use 'comments' if provided, otherwise use 'feedback' from form
        $comments = $validated['comments'] ?? ($validated['feedback'] ?? null);

        Feedback::create([
            'user_id' => Auth::id(),
            'service_availed' => $validated['service_availed'],
            'satisfaction_rating' => $validated['satisfaction_rating'],
            'comments' => $comments,
            'is_anonymous' => $validated['is_anonymous'] ?? false
        ]);

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

        $feedback->load('user');

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
