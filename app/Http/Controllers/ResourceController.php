<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resources.
     */
    public function index()
    {
        $resources = Resource::with('user')
            ->ordered()
            ->get();

        return view('counselor.resources.index', compact('resources'));
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
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

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
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $resource->update($validated);

        return redirect()->route('counselor.resources.index')
            ->with('success', 'Resource updated successfully!');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();

        return redirect()->route('counselor.resources.index')
            ->with('success', 'Resource deleted successfully!');
    }

    /**
     * Update resource status (following the same pattern as appointments)
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
