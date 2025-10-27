<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CounselorAnnouncementController extends Controller
{
    public function index()
    {
        $counselor = Auth::user()->counselor;
        $announcements = Announcement::with(['user', 'colleges'])
            ->byCounselor(Auth::id())
            ->latest()
            ->paginate(10);

        return view('counselor.announcements.index', compact('announcements', 'counselor'));
    }

    public function create()
    {
        $counselor = Auth::user()->counselor;
        $colleges = College::all();
        return view('counselor.announcements.create', compact('counselor', 'colleges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'for_all_colleges' => 'boolean',
            'colleges' => 'required_if:for_all_colleges,false|array',
            'colleges.*' => 'exists:colleges,id'
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? true,
            'for_all_colleges' => $request->for_all_colleges ?? false
        ];

        // Handle image upload
if ($request->hasFile('image')) {
    $imagePath = $request->file('image')->store('announcements', 'public');
    $data['image'] = $imagePath; // Store full path, not just basename
}

        $announcement = Announcement::create($data);

        // Attach colleges if not for all colleges
        if (!$request->for_all_colleges && $request->has('colleges')) {
            $announcement->colleges()->sync($request->colleges);
        }

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
        $colleges = College::all();
        $selectedColleges = $announcement->colleges->pluck('id')->toArray();

        return view('counselor.announcements.edit', compact('announcement', 'counselor', 'colleges', 'selectedColleges'));
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'for_all_colleges' => 'boolean',
            'colleges' => 'required_if:for_all_colleges,false|array',
            'colleges.*' => 'exists:colleges,id'
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? $announcement->is_active,
            'for_all_colleges' => $request->for_all_colleges ?? false
        ];

        // Handle image upload
      if ($request->hasFile('image')) {
    // Delete old image if exists
    if ($announcement->image) {
        Storage::disk('public')->delete($announcement->image); // Remove 'announcements/' prefix
    }
    $imagePath = $request->file('image')->store('announcements', 'public');
    $data['image'] = $imagePath;
}

        $announcement->update($data);

        // Update colleges
        if ($request->for_all_colleges) {
            $announcement->colleges()->detach();
        } else {
            $announcement->colleges()->sync($request->colleges ?? []);
        }

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

        // Delete image if exists
        if ($announcement->image) {
            Storage::disk('public')->delete('announcements/' . $announcement->image);
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

public function removeImage(Announcement $announcement)
{
    Log::info('Remove image called for announcement:', [
        'announcement_id' => $announcement->id,
        'current_image' => $announcement->image,
        'user_id' => Auth::id(),
        'owner_id' => $announcement->user_id
    ]);

    // Verify the counselor owns this announcement
    if ($announcement->user_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    if ($announcement->image) {
        Log::info('Attempting to delete image:', ['path' => $announcement->image]);

        // Check if file exists before deleting
        if (Storage::disk('public')->exists($announcement->image)) {
            Storage::disk('public')->delete($announcement->image);
            Log::info('Image deleted successfully');
        } else {
            Log::warning('Image file not found:', ['path' => $announcement->image]);
        }

        $announcement->update(['image' => null]);
    }

    return redirect()->back()->with('success', 'Image removed successfully.');
}
}
