<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminServiceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $admin = Admin::with('user')->where('user_id', $user->id)->first();

        $services = Service::query()
            ->orderBy('order')
            ->orderBy('title')
            ->get();

        return view('admin.services.index', compact('admin', 'services'));
    }

    public function edit(Service $service)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $admin = Admin::with('user')->where('user_id', $user->id)->first();

        return view('admin.services.edit', compact('admin', 'service'));
    }

    public function update(Request $request, Service $service)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'file', 'image', 'max:5120'],
            'route_name' => ['nullable', 'string', 'max:255'],
            'order' => ['required', 'integer'],
            'is_active' => ['nullable'],
        ]);

        $updateData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'route_name' => $validated['route_name'] ?? null,
            'order' => $validated['order'],
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $previousImagePath = $service->image_url;
            $storedPath = $request->file('image')->storePublicly('services', 'public');
            $updateData['image_url'] = $storedPath;

            if ($previousImagePath
                && !preg_match('/^https?:\/\//i', $previousImagePath)
                && Storage::disk('public')->exists($previousImagePath)
            ) {
                Storage::disk('public')->delete($previousImagePath);
            }
        }

        $service->update($updateData);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }
}
