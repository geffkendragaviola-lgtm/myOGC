<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFAQController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        $query = FAQ::query()->with('user')->ordered();

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(question) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(answer) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(category) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $faqs = $query->paginate(10)->appends($request->query());

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'question' => 'required|string|max:2000',
            'answer' => 'required|string|max:5000',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');
        $validated['is_pinned'] = $request->has('is_pinned');

        FAQ::create($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully!');
    }

    public function edit(FAQ $faq)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, FAQ $faq)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'question' => 'required|string|max:2000',
            'answer' => 'required|string|max:5000',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_pinned'] = $request->has('is_pinned');

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully!');
    }

    public function destroy(FAQ $faq)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully!');
    }
    public function togglePin(FAQ $faq)
    {
        $faq->update(['is_pinned' => !$faq->is_pinned]);

        $state = $faq->is_pinned ? 'pinned' : 'unpinned';

        return response()->json([
            'success' => true,
            'is_pinned' => $faq->is_pinned,
            'message' => "FAQ {$state} successfully!",
        ]);
    }
}
