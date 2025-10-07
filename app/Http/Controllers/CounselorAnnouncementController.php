<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CounselorAnnouncementController extends Controller
{
    public function index()
    {
        $counselor = Auth::user()->counselor;
        $announcements = Announcement::with('user')
            ->byCounselor(Auth::id())
            ->latest()
            ->paginate(10);

        return view('counselor.announcements.index', compact('announcements', 'counselor'));
    }


    public function create()
    {
        $counselor = Auth::user()->counselor;
        return view('counselor.announcements.create', compact('counselor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        Announcement::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? true
        ]);

        return redirect()->route('counselor.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        // Verify the counselor owns this announcement
        if ($announcement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $counselor = Auth::user()->counselor;
        return view('counselor.announcements.edit', compact('announcement', 'counselor'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        // Verify the counselor owns this announcement
        if ($announcement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? $announcement->is_active
        ]);

        // Check if this was a "mark as done" action
        if ($request->has('mark_done')) {
            return redirect()->route('counselor.announcements.index')
                ->with('success', 'Announcement marked as completed successfully.');
        }

        return redirect()->route('counselor.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        // Verify the counselor owns this announcement
        if ($announcement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $announcement->delete();

        return redirect()->route('counselor.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    public function toggleStatus(Announcement $announcement)
    {
        // Verify the counselor owns this announcement
        if ($announcement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);

        $status = $announcement->is_active ? 'activated' : 'deactivated';

        return redirect()->route('counselor.announcements.index')
            ->with('success', "Announcement {$status} successfully.");
    }

    public function complete(Announcement $announcement)
    {
        // Verify the counselor owns this announcement
        if ($announcement->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $announcement->update([
            'is_active' => false,
            'end_date' => now() // Set end date to now to mark as completed
        ]);

        return redirect()->route('counselor.announcements.index')
            ->with('success', 'Announcement marked as completed successfully.');
    }
}
