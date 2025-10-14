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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect()->route('login');
        }

        // Force create admin profile if missing
        $admin = Admin::with('user')->where('user_id', $user->id)->first();
        if (!$admin) {
            $admin = Admin::create([
                'user_id' => $user->id,
                'credentials' => 'System Administrator'
            ]);
        }

        // Simple stats without relationships
        $stats = [
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_counselors' => Counselor::count(),
            'total_admins' => Admin::count(),
            'pending_users' => 0, // Temporary
        ];

        // Simple recent users without relationships
        $recentUsers = User::latest()->limit(10)->get();

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
        // Base validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,counselor,admin',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date',
            'sex' => 'nullable|in:male,female,other',
            'birthplace' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:100',
            'affiliation' => 'nullable|string|max:100',
            'civil_status' => 'nullable|in:single,married,divorced,widowed',
            'citizenship' => 'nullable|string|max:50',
        ];

        // Add role-specific rules
        if ($request->role === 'student') {
            $rules['student_id'] = 'required|string|max:50|unique:students';
            $rules['year_level'] = 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate';
            $rules['course'] = 'required|string|max:255';
            $rules['college_id'] = 'required|exists:colleges,id';
        } elseif ($request->role === 'counselor') {
            $rules['position'] = 'required|string|max:255';
            $rules['credentials'] = 'required|string|max:255';
            $rules['counselor_college_id'] = 'required|exists:colleges,id';
            $rules['specialization'] = 'nullable|string|max:500';
        } elseif ($request->role === 'admin') {
            $rules['admin_credentials'] = 'required|string|max:255';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // Calculate age from birthdate
            $age = null;
            if ($request->birthdate) {
                $age = Carbon::parse($request->birthdate)->age;
            }

            // Create user
            $user = User::create([
                'first_name' => strip_tags($request->first_name),
                'last_name' => strip_tags($request->last_name),
                'middle_name' => $request->middle_name ? strip_tags($request->middle_name) : null,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'phone_number' => $request->phone_number,
                'address' => $request->address ? strip_tags($request->address) : null,
                'birthdate' => $request->birthdate,
                'age' => $age,
                'sex' => $request->sex,
                'birthplace' => $request->birthplace ? strip_tags($request->birthplace) : null,
                'religion' => $request->religion ? strip_tags($request->religion) : null,
                'affiliation' => $request->affiliation ? strip_tags($request->affiliation) : null,
                'civil_status' => $request->civil_status,
                'citizenship' => $request->citizenship ? strip_tags($request->citizenship) : null,
                'email_verified_at' => now(), // Auto-verify for admin-created users
            ]);

            // Create role-specific profile
            if ($request->role === 'student') {
                Student::create([
                    'user_id' => $user->id,
                    'student_id' => $request->student_id,
                    'year_level' => $request->year_level,
                    'course' => strip_tags($request->course),
                    'college_id' => $request->college_id,
                ]);

            } elseif ($request->role === 'counselor') {
                Counselor::create([
                    'user_id' => $user->id,
                    'position' => strip_tags($request->position),
                    'credentials' => strip_tags($request->credentials),
                    'college_id' => $request->counselor_college_id,
                    'specialization' => $request->specialization ? strip_tags($request->specialization) : null,
                    'is_head' => $request->has('is_head'),
                ]);

            } elseif ($request->role === 'admin') {
                Admin::create([
                    'user_id' => $user->id,
                    'credentials' => strip_tags($request->admin_credentials),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.users')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create user: ' . $e->getMessage()]);
        }
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
        // Base validation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birthdate' => 'nullable|date',
            'sex' => 'nullable|in:male,female,other',
            'birthplace' => 'nullable|string|max:255',
            'religion' => 'nullable|string|max:100',
            'affiliation' => 'nullable|string|max:100',
            'civil_status' => 'nullable|in:single,married,divorced,widowed',
            'citizenship' => 'nullable|string|max:50',
        ];

        // Add role-specific rules
        if ($user->role === 'student') {
            $rules['student_id'] = 'required|string|max:50|unique:students,student_id,' . ($user->student ? $user->student->id : 'NULL');
            $rules['year_level'] = 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate';
            $rules['course'] = 'required|string|max:255';
            $rules['college_id'] = 'required|exists:colleges,id';
        } elseif ($user->role === 'counselor') {
            $rules['position'] = 'required|string|max:255';
            $rules['credentials'] = 'required|string|max:255';
            $rules['counselor_college_id'] = 'required|exists:colleges,id';
            $rules['specialization'] = 'nullable|string|max:500';
        } elseif ($user->role === 'admin') {
            $rules['admin_credentials'] = 'required|string|max:255';
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // Calculate age from birthdate
            $age = null;
            if ($request->birthdate) {
                $age = Carbon::parse($request->birthdate)->age;
            }

            // Update user
            $user->update([
                'first_name' => strip_tags($request->first_name),
                'last_name' => strip_tags($request->last_name),
                'middle_name' => $request->middle_name ? strip_tags($request->middle_name) : null,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address ? strip_tags($request->address) : null,
                'birthdate' => $request->birthdate,
                'age' => $age,
                'sex' => $request->sex,
                'birthplace' => $request->birthplace ? strip_tags($request->birthplace) : null,
                'religion' => $request->religion ? strip_tags($request->religion) : null,
                'affiliation' => $request->affiliation ? strip_tags($request->affiliation) : null,
                'civil_status' => $request->civil_status,
                'citizenship' => $request->citizenship ? strip_tags($request->citizenship) : null,
            ]);

            // Update role-specific profile
            if ($user->role === 'student') {
                if ($user->student) {
                    $user->student->update([
                        'student_id' => $request->student_id,
                        'year_level' => $request->year_level,
                        'course' => strip_tags($request->course),
                        'college_id' => $request->college_id,
                    ]);
                } else {
                    Student::create([
                        'user_id' => $user->id,
                        'student_id' => $request->student_id,
                        'year_level' => $request->year_level,
                        'course' => strip_tags($request->course),
                        'college_id' => $request->college_id,
                    ]);
                }

            } elseif ($user->role === 'counselor') {
                if ($user->counselor) {
                    $user->counselor->update([
                        'position' => strip_tags($request->position),
                        'credentials' => strip_tags($request->credentials),
                        'college_id' => $request->counselor_college_id,
                        'specialization' => $request->specialization ? strip_tags($request->specialization) : null,
                        'is_head' => $request->has('is_head'),
                    ]);
                } else {
                    Counselor::create([
                        'user_id' => $user->id,
                        'position' => strip_tags($request->position),
                        'credentials' => strip_tags($request->credentials),
                        'college_id' => $request->counselor_college_id,
                        'specialization' => $request->specialization ? strip_tags($request->specialization) : null,
                        'is_head' => $request->has('is_head'),
                    ]);
                }

            } elseif ($user->role === 'admin') {
                if ($user->admin) {
                    $user->admin->update([
                        'credentials' => strip_tags($request->admin_credentials),
                    ]);
                } else {
                    Admin::create([
                        'user_id' => $user->id,
                        'credentials' => strip_tags($request->admin_credentials),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.users')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update user: ' . $e->getMessage()]);
        }
    }

    public function deleteUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        DB::beginTransaction();

        try {
            // Delete role-specific profile first
            switch ($user->role) {
                case 'student':
                    if ($user->student) {
                        $user->student->delete();
                    }
                    break;
                case 'counselor':
                    if ($user->counselor) {
                        $user->counselor->delete();
                    }
                    break;
                case 'admin':
                    if ($user->admin) {
                        $user->admin->delete();
                    }
                    break;
            }

            // Delete user
            $user->delete();

            DB::commit();

            return redirect()->route('admin.users')->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
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
