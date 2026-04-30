<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resources.
     */
    public function index(Request $request)
    {
        $query = Resource::with('user')->ordered();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        $resources = $query->paginate(10)->appends($request->query());
        $categories = Resource::getCategories();

        return view('counselor.resources.index', compact('resources', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Resource::getCategories();
        $icons = [
            'fab fa-youtube',
            'fas fa-book-open',
            'fas fa-lock',
            'fas fa-archive',
            'fas fa-video',
            'fas fa-file-pdf',
            'fas fa-headphones',
            'fas fa-hands-helping',
            'fas fa-brain',
            'fas fa-heart',
            'fas fa-users',
            'fas fa-graduation-cap',
        ];

        return view('counselor.resources.create', compact('categories', 'icons'));
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'button_text' => 'required|string|max:50',
            'link' => 'required|url|max:500',
            'category' => 'required|in:youtube,ebooks,private,ogc',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'use_yt_thumbnail' => 'boolean',
            'is_active' => 'boolean',
            'show_disclaimer' => 'boolean',
            'disclaimer_text' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');
        $validated['use_yt_thumbnail'] = $request->has('use_yt_thumbnail');
        $validated['show_disclaimer'] = $request->has('show_disclaimer');

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('resources', 'public');
        }

        Resource::create($validated);

        return redirect()->route('counselor.resources.index')
            ->with('success', 'Resource created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        $categories = Resource::getCategories();
        $icons = [
            'fab fa-youtube',
            'fas fa-book-open',
            'fas fa-lock',
            'fas fa-archive',
            'fas fa-video',
            'fas fa-file-pdf',
            'fas fa-headphones',
            'fas fa-hands-helping',
            'fas fa-brain',
            'fas fa-heart',
            'fas fa-users',
            'fas fa-graduation-cap',
        ];

        return view('counselor.resources.edit', compact('resource', 'categories', 'icons'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
            'button_text' => 'required|string|max:50',
            'link' => 'required|url|max:500',
            'category' => 'required|in:youtube,ebooks,private,ogc',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'use_yt_thumbnail' => 'boolean',
            'is_active' => 'boolean',
            'show_disclaimer' => 'boolean',
            'disclaimer_text' => 'nullable|string|max:1000',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['use_yt_thumbnail'] = $request->has('use_yt_thumbnail');
        $validated['show_disclaimer'] = $request->has('show_disclaimer');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($resource->image_path) {
                Storage::disk('public')->delete($resource->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('resources', 'public');
        }

        // If use_yt_thumbnail is checked, remove custom image
        if ($validated['use_yt_thumbnail'] && $resource->image_path) {
            Storage::disk('public')->delete($resource->image_path);
            $validated['image_path'] = null;
        }

        $resource->update($validated);

        return redirect()->route('counselor.resources.index')
            ->with('success', 'Resource updated successfully!');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Resource $resource)
    {
        // Delete associated image
        if ($resource->image_path) {
            Storage::disk('public')->delete($resource->image_path);
        }

        $resource->delete();

        return redirect()->route('counselor.resources.index')
            ->with('success', 'Resource deleted successfully!');
    }

    /**
     * Display resources by category for students
     */
    public function showCategory($category)
    {
        $categories = Resource::getCategories();

        if (!array_key_exists($category, $categories)) {
            abort(404);
        }

        $resources = Resource::byCategory($category)
            ->active()
            ->ordered()
            ->get();

        $unreadNotifications = auth()->user()->unreadNotifications->take(5);
        $unreadCount = auth()->user()->unreadNotifications->count();

        return view('student.resources.category', compact('resources', 'category', 'categories', 'unreadNotifications', 'unreadCount'));
    }

    /**
     * Display a single resource detail page for students
     */
    public function showResource($category, Resource $resource)
    {
        $categories = Resource::getCategories();

        if (!array_key_exists($category, $categories) || $resource->category !== $category) {
            abort(404);
        }

        if (!$resource->is_active) {
            abort(404);
        }

        // Related resources: same category, excluding current
        $related = Resource::byCategory($category)
            ->active()
            ->ordered()
            ->where('id', '!=', $resource->id)
            ->limit(3)
            ->get();

        $unreadNotifications = auth()->user()->unreadNotifications->take(5);
        $unreadCount = auth()->user()->unreadNotifications->count();

        return view('student.resources.show', compact('resource', 'category', 'categories', 'related', 'unreadNotifications', 'unreadCount'));
    }

    /**
     * Update resource status
     */
    public function updateStatus(Request $request, Resource $resource)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $resource->update([
            'is_active' => $request->is_active
        ]);

        $status = $request->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "Resource {$status} successfully!",
            'resource' => $resource
        ]);
    }
}
