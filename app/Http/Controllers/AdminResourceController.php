<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminResourceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        $search = $request->string('search')->trim()->toString();
        $category = $request->string('category')->trim()->toString();
        $status = $request->string('status')->trim()->toString();
        $pinned = $request->string('pinned')->trim()->toString();

        $query = Resource::query()->with('user');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('button_text', 'like', "%{$search}%");
            });
        }

        if ($category !== '' && $category !== 'all') {
            $query->where('category', $category);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($pinned === 'pinned') {
            $query->where('is_pinned', true);
        } elseif ($pinned === 'unpinned') {
            $query->where('is_pinned', false);
        }

        $resources = $query->ordered()->paginate(10)->appends($request->query());

        $categories = Resource::getCategories();

        return view('admin.resources.index', compact(
            'resources',
            'search',
            'category',
            'status',
            'pinned',
            'categories'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

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

        return view('admin.resources.create', compact('categories', 'icons'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

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
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');
        $validated['use_yt_thumbnail'] = $request->has('use_yt_thumbnail');
        $validated['show_disclaimer'] = $request->has('show_disclaimer');
        $validated['is_pinned'] = $request->has('is_pinned');

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('resources', 'public');
        }

        Resource::create($validated);

        return redirect()->route('admin.resources.index')->with('success', 'Resource created successfully!');
    }

    public function edit(Resource $resource)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

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

        return view('admin.resources.edit', compact('resource', 'categories', 'icons'));
    }

    public function update(Request $request, Resource $resource)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

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
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['use_yt_thumbnail'] = $request->has('use_yt_thumbnail');
        $validated['show_disclaimer'] = $request->has('show_disclaimer');
        $validated['is_pinned'] = $request->has('is_pinned');

        if ($request->hasFile('image')) {
            if ($resource->image_path) {
                Storage::disk('public')->delete($resource->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('resources', 'public');
        }

        if ($validated['use_yt_thumbnail'] && $resource->image_path) {
            Storage::disk('public')->delete($resource->image_path);
            $validated['image_path'] = null;
        }

        $resource->update($validated);

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated successfully!');
    }

    public function destroy(Resource $resource)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        if ($resource->image_path) {
            Storage::disk('public')->delete($resource->image_path);
        }

        $resource->delete();

        return redirect()->route('admin.resources.index')->with('success', 'Resource deleted successfully!');
    }

    public function updateStatus(Request $request, Resource $resource)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

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
    public function togglePin(Resource $resource)
    {
        $resource->update(['is_pinned' => !$resource->is_pinned]);

        $state = $resource->is_pinned ? 'pinned' : 'unpinned';

        return response()->json([
            'success' => true,
            'is_pinned' => $resource->is_pinned,
            'message' => "Resource {$state} successfully!",
        ]);
    }
}
