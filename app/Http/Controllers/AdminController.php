<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\Admin;
use App\Models\College;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
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

        // Stats with relationships
        $stats = [
            'total_users' => User::count(),
            'total_students' => Student::count(),
            'total_counselors' => Counselor::count(),
            'total_admins' => Admin::count(),
            'total_events' => Event::count(),
            'active_events' => Event::where('is_active', true)->count(),
            'upcoming_events' => Event::where('event_start_date', '>=', now()->toDateString())->count(),
          
        ];

        // Recent events for admin dashboard
        $recentEvents = Event::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Recent users
        $recentUsers = User::with(['student', 'counselor', 'admin'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('admin', 'stats', 'recentUsers', 'recentEvents'));
    }

    /**
     * Display all events in the system
     */
    public function events(Request $request)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');
        $counselor = $request->get('counselor', 'all');

        $query = Event::with(['user', 'registrations']);

        // Search functionality
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($status !== 'all') {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'upcoming') {
                $query->where('event_start_date', '>=', now()->toDateString());
            } elseif ($status === 'past') {
                $query->where('event_end_date', '<', now()->toDateString());
            }
        }

        // Type filter
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        // Counselor filter
        if ($counselor !== 'all') {
            $query->where('user_id', $counselor);
        }

        $events = $query->orderBy('event_start_date', 'desc')
                       ->orderBy('start_time', 'desc')
                       ->paginate(15);

        $counselors = Counselor::with('user')->get();

        return view('admin.events.index', compact('admin', 'events', 'search', 'status', 'type', 'counselor', 'counselors'));
    }

    /**
     * Show the form for creating a new event as admin
     */
    public function createEvent()
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        $counselors = Counselor::with('user')->get();

        return view('admin.events.create', compact('admin', 'counselors'));
    }

    /**
     * Store a newly created event as admin
     */
    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:workshop,seminar,webinar,conference,other',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'required|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'user_id' => 'required|exists:users,id',
            'is_active' => 'boolean'
        ]);

        DB::beginTransaction();

        try {
            $event = Event::create([
                'user_id' => $request->user_id,
                'title' => strip_tags($request->title),
                'description' => strip_tags($request->description),
                'type' => $request->type,
                'event_start_date' => $request->event_start_date,
                'event_end_date' => $request->event_end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => strip_tags($request->location),
                'max_attendees' => $request->max_attendees,
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();

            return redirect()->route('admin.events')->with('success', 'Event created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create event: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing an event as admin
     */
    public function editEvent(Event $event)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        $counselors = Counselor::with('user')->get();

        return view('admin.events.edit', compact('admin', 'event', 'counselors'));
    }

    /**
     * Update the specified event as admin
     */
    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:workshop,seminar,webinar,conference,other',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'required|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'user_id' => 'required|exists:users,id',
            'is_active' => 'boolean'
        ]);

        DB::beginTransaction();

        try {
            $event->update([
                'user_id' => $request->user_id,
                'title' => strip_tags($request->title),
                'description' => strip_tags($request->description),
                'type' => $request->type,
                'event_start_date' => $request->event_start_date,
                'event_end_date' => $request->event_end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => strip_tags($request->location),
                'max_attendees' => $request->max_attendees,
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();

            return redirect()->route('admin.events')->with('success', 'Event updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update event: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete an event as admin
     */
    public function deleteEvent(Event $event)
    {
        DB::beginTransaction();

        try {
            // Delete related registrations first
            EventRegistration::where('event_id', $event->id)->delete();

            // Delete the event
            $event->delete();

            DB::commit();

            return redirect()->route('admin.events')->with('success', 'Event deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }

    /**
     * Toggle event status (active/inactive) as admin
     */
    public function toggleEventStatus(Event $event)
    {
        try {
            $event->update([
                'is_active' => !$event->is_active
            ]);

            $status = $event->is_active ? 'activated' : 'deactivated';

            return redirect()->route('admin.events')
                ->with('success', "Event {$status} successfully!");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update event status: ' . $e->getMessage());
        }
    }

    /**
     * Show event registrations as admin
     */
    public function showEventRegistrations(Event $event)
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();

        $registrations = EventRegistration::with(['student.user', 'student.college'])
            ->where('event_id', $event->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        $registrationStats = [
            'total' => $registrations->count(),
            'registered' => $registrations->where('status', 'registered')->count(),
            'attended' => $registrations->where('status', 'attended')->count(),
            'cancelled' => $registrations->where('status', 'cancelled')->count(),
        ];

        return view('admin.events.registrations', compact('admin', 'event', 'registrations', 'registrationStats'));
    }

    /**
     * Update registration status as admin
     */
    public function updateEventRegistrationStatus(Request $request, Event $event, EventRegistration $registration)
    {
        $request->validate([
            'status' => 'required|in:registered,attended,cancelled'
        ]);

        // Verify the registration belongs to the event
        if ($registration->event_id !== $event->id) {
            return redirect()->back()->with('error', 'Invalid registration for this event.');
        }

        try {
            $registration->update([
                'status' => $request->status
            ]);

            return redirect()->back()->with('success', 'Registration status updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update registration status: ' . $e->getMessage());
        }
    }

    /**
     * Export event registrations to CSV as admin
     */
    public function exportEventRegistrations(Event $event)
    {
        $registrations = EventRegistration::with(['student.user', 'student.college'])
            ->where('event_id', $event->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        $fileName = 'event-registrations-' . $event->id . '-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($registrations, $event) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'Event: ' . $event->title,
                'Date: ' . $event->date_range,
                'Location: ' . $event->location,
                'Counselor: ' . $event->user->first_name . ' ' . $event->user->last_name,
                ''
            ]);

            // Column headers
            fputcsv($file, [
                'Student ID',
                'First Name',
                'Middle Name',
                'Last Name',
                'Age',
                'Gender',
                'Phone Number',
                'Email',
                'College',
                'Year Level',
                'Registration Date',
                'Status'
            ]);

            // Data rows
            foreach ($registrations as $registration) {
                $student = $registration->student;
                $user = $student->user;

                fputcsv($file, [
                    $student->student_id ?? 'N/A',
                    $user->first_name,
                    $user->middle_name ?? '',
                    $user->last_name,
                    $user->age ?? 'N/A',
                    $user->gender ?? 'N/A',
                    $user->phone_number ?? 'N/A',
                    $user->email,
                    $student->college->name ?? 'N/A',
                    $student->year_level ?? 'N/A',
                    $registration->registered_at->format('M j, Y g:i A'),
                    ucfirst($registration->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display all users
     */
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

    /**
     * Show form to create new user
     */
    public function createUser()
    {
        $userId = Auth::id();
        $admin = Admin::with('user')->where('user_id', $userId)->first();
        $colleges = College::orderBy('name')->get();

        return view('admin.users.create', compact('admin', 'colleges'));
    }

    /**
     * Store a newly created user
     */
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

    /**
     * Show form to edit user
     */
    public function editUser(User $user)
    {
        $userId = Auth::id();
        $adminUser = Admin::with('user')->where('user_id', $userId)->first();
        $colleges = College::orderBy('name')->get();

        return view('admin.users.edit', compact('adminUser', 'user', 'colleges'));
    }

    /**
     * Update the specified user
     */
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

    /**
     * Delete a user
     */
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

    /**
     * Display all students
     */
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

    /**
     * Display all counselors
     */
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
