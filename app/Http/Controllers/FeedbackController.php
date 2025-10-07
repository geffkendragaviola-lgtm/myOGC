<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display the feedback form.
     */
    public function create()
    {
        return view('feedback');
    }

    /**
     * Store a newly created feedback in storage.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'service_availed' => 'required|string|in:counseling,mental_health_corner,consultation,other',
        'satisfaction_rating' => 'required|integer|between:1,5',
        'feedback' => 'nullable|string|max:1000', // Keep this as 'feedback' for form validation
        'is_anonymous' => 'boolean'
    ]);

    Feedback::create([
        'user_id' => Auth::id(),
        'service_availed' => $validated['service_availed'],
        'satisfaction_rating' => $validated['satisfaction_rating'],
        'comments' => $validated['feedback'] ?? null, // Map 'feedback' from form to 'comments' in database
        'is_anonymous' => $validated['is_anonymous'] ?? false
    ]);

    return redirect()->route('feedback')
        ->with('success', 'Thank you for your feedback! Your responses have been submitted.');
}

    /**
     * Display a listing of the feedback for admin.
     */
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $feedbacks = Feedback::with('user')->latest()->paginate(20);

        return view('admin.feedback.index', compact('feedbacks'));
    }
}
