<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\Admin;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
   public function dashboard()
{
    $user = Auth::user();

    // Debug: Check if user is authenticated and has admin role
    if (!$user) {
        return redirect()->route('login');
    }

    // Check if user has admin role
    if ($user->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'Access denied. Your account does not have admin privileges.');
    }

    // Get or create admin profile
    $admin = Admin::with('user')->where('user_id', $user->id)->first();

    if (!$admin) {
        try {
            $admin = Admin::create([
                'user_id' => $user->id,
                'credentials' => 'System Administrator'
            ]);
            $admin->load('user');
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Failed to create admin profile: ' . $e->getMessage());
        }
    }

    // Get statistics with error handling
    try {
        $stats = [
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_counselors' => Counselor::count(),
            'total_admins' => Admin::count(),
            'pending_users' => User::where('status', 'pending')->count() ?? 0,
        ];

        // Recent users
        $recentUsers = User::with(['student', 'counselor', 'admin'])
            ->latest()
            ->limit(10)
            ->get();
    } catch (\Exception $e) {
        // If there's an error with stats, set defaults
        $stats = [
            'total_users' => 0,
            'total_students' => 0,
            'total_counselors' => 0,
            'total_admins' => 0,
            'pending_users' => 0,
        ];
        $recentUsers = collect();
    }

    return view('admin.dashboard', compact('admin', 'stats', 'recentUsers'));
}

    public function users(Request $request)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $role = $request->get('role', 'all');
        $search = $request->get('search');

        $query = User::with(['student', 'counselor', 'admin']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('student', function($q) use ($search) {
                      $q->where('student_id', 'like', "%{$search}%");
                  })
                  ->orWhereHas('counselor', function($q) use ($search) {
                      $q->where('position', 'like', "%{$search}%");
                  });
            });
        }

        // Role filter
        if ($role !== 'all') {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('admin', 'users', 'role', 'search'));
    }

    public function createUser()
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        $colleges = College::orderBy('name')->get();

        return view('admin.users.create', compact('admin', 'colleges'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,counselor,admin',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'email_verified_at' => now(), // Auto-verify for admin-created users
        ]);

        // Create role-specific profile
        if ($request->role === 'student') {
            $request->validate([
                'student_id' => 'required|string|max:50|unique:students',
                'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate',
                'course' => 'required|string|max:255',
                'college_id' => 'required|exists:colleges,id',
            ]);

            Student::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'year_level' => $request->year_level,
                'course' => $request->course,
                'college_id' => $request->college_id,
            ]);

        } elseif ($request->role === 'counselor') {
            $request->validate([
                'position' => 'required|string|max:255',
                'credentials' => 'required|string|max:255',
                'college_id' => 'required|exists:colleges,id',
                'specialization' => 'nullable|string|max:500',
            ]);

            Counselor::create([
                'user_id' => $user->id,
                'position' => $request->position,
                'credentials' => $request->credentials,
                'college_id' => $request->college_id,
                'specialization' => $request->specialization,
                'is_head' => $request->has('is_head'),
            ]);

        } elseif ($request->role === 'admin') {
            $request->validate([
                'credentials' => 'required|string|max:100',
            ]);

            Admin::create([
                'user_id' => $user->id,
                'credentials' => $request->credentials,
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        $userId = Auth::id();
        $adminUser = Admin::with('user')->where('user_id', $userId)->first();
        $colleges = College::orderBy('name')->get();

        return view('admin.users.edit', compact('adminUser', 'user', 'colleges'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Update user
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        // Update role-specific profile
        if ($user->role === 'student' && $user->student) {
            $request->validate([
                'student_id' => 'required|string|max:50|unique:students,student_id,' . $user->student->id,
                'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate',
                'course' => 'required|string|max:255',
                'college_id' => 'required|exists:colleges,id',
            ]);

            $user->student->update([
                'student_id' => $request->student_id,
                'year_level' => $request->year_level,
                'course' => $request->course,
                'college_id' => $request->college_id,
            ]);

        } elseif ($user->role === 'counselor' && $user->counselor) {
            $request->validate([
                'position' => 'required|string|max:255',
                'credentials' => 'required|string|max:255',
                'college_id' => 'required|exists:colleges,id',
                'specialization' => 'nullable|string|max:500',
            ]);

            $user->counselor->update([
                'position' => $request->position,
                'credentials' => $request->credentials,
                'college_id' => $request->college_id,
                'specialization' => $request->specialization,
                'is_head' => $request->has('is_head'),
            ]);

        } elseif ($user->role === 'admin' && $user->admin) {
            $request->validate([
                'credentials' => 'required|string|max:100',
            ]);

            $user->admin->update([
                'credentials' => $request->credentials,
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function students(Request $request)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $search = $request->get('search');
        $college = $request->get('college');

        $query = Student::with(['user', 'college']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                  ->orWhere('course', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // College filter
        if ($college) {
            $query->where('college_id', $college);
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(15);
        $colleges = College::orderBy('name')->get();

        return view('admin.students.index', compact('admin', 'students', 'colleges', 'search'));
    }

    public function counselors(Request $request)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $search = $request->get('search');
        $college = $request->get('college');

        $query = Counselor::with(['user', 'college']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('position', 'like', "%{$search}%")
                  ->orWhere('credentials', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('college', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // College filter
        if ($college) {
            $query->where('college_id', $college);
        }

        $counselors = $query->orderBy('created_at', 'desc')->paginate(15);
        $colleges = College::orderBy('name')->get();

        return view('admin.counselors.index', compact('admin', 'counselors', 'colleges', 'search'));
    }
}
