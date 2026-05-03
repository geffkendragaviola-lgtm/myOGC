<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AdminAnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with(['user', 'colleges']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $stats = [
            'total' => Announcement::count(),
            'active' => Announcement::where('is_active', true)->count(),
            'scheduled' => Announcement::where('is_active', true)
                ->where('start_date', '>', now())->count(),
            'completed' => Announcement::where('is_active', false)->count()
        ];

        $announcements = $query->orderBy('is_pinned', 'desc')->latest()->paginate(10);

        return view('admin.announcements.index', compact('announcements', 'stats'));
    }

    public function create()
    {
        $colleges = College::all();
        return view('admin.announcements.create', compact('colleges'));
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
            'colleges.*' => 'exists:colleges,id',
            'year_levels' => 'nullable|array',
            'year_levels.*' => 'string|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? true,
            'for_all_colleges' => $request->for_all_colleges ?? false,
            'year_levels' => !empty($request->year_levels) ? $request->year_levels : null,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
            $data['image'] = $imagePath;
        }

        $announcement = Announcement::create($data);

        if (!$request->for_all_colleges && $request->has('colleges')) {
            $announcement->colleges()->sync($request->colleges);
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function edit(Announcement $announcement)
    {
        $colleges = College::all();
        $selectedColleges = $announcement->colleges->pluck('id')->toArray();

        return view('admin.announcements.edit', compact('announcement', 'colleges', 'selectedColleges'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'for_all_colleges' => 'boolean',
            'colleges' => 'required_if:for_all_colleges,false|array',
            'colleges.*' => 'exists:colleges,id',
            'year_levels' => 'nullable|array',
            'year_levels.*' => 'string|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active ?? $announcement->is_active,
            'for_all_colleges' => $request->for_all_colleges ?? false,
            'year_levels' => !empty($request->year_levels) ? $request->year_levels : null,
        ];

        if ($request->hasFile('image')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $imagePath = $request->file('image')->store('announcements', 'public');
            $data['image'] = $imagePath;
        }

        $announcement->update($data);

        if ($request->for_all_colleges) {
            $announcement->colleges()->detach();
        } else {
            $announcement->colleges()->sync($request->colleges ?? []);
        }

        if ($request->has('mark_done')) {
            return redirect()->route('admin.announcements.index')
                ->with('success', 'Announcement marked as completed successfully.');
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    public function toggleStatus(Announcement $announcement)
    {
        $announcement->update([
            'is_active' => !$announcement->is_active
        ]);

        $status = $announcement->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.announcements.index')
            ->with('success', "Announcement {$status} successfully.");
    }
    public function togglePin(Announcement $announcement)
    {
        $announcement->update(['is_pinned' => !$announcement->is_pinned]);

        return response()->json([
            'success' => true,
            'is_pinned' => $announcement->is_pinned,
            'message' => $announcement->is_pinned ? 'Announcement pinned.' : 'Announcement unpinned.',
        ]);
    }

    public function complete(Announcement $announcement)
    {
        $announcement->update([
            'is_active' => false,
            'end_date' => now()
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement marked as completed successfully.');
    }

    public function removeImage(Announcement $announcement)
    {
        if ($announcement->image) {
            if (Storage::disk('public')->exists($announcement->image)) {
                Storage::disk('public')->delete($announcement->image);
            }
            $announcement->update(['image' => null]);
        }

        return redirect()->back()->with('success', 'Image removed successfully.');
    }
}
