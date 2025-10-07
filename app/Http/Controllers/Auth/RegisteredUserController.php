<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\Admin;
use App\Models\College;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $colleges = College::all();
        return view('auth.register', compact('colleges'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birthdate' => ['nullable', 'date'],
            'sex' => ['nullable', 'string', 'in:male,female,other'],
            'birthplace' => ['nullable', 'string', 'max:255'],
            'religion' => ['nullable', 'string', 'max:100'],
            'affiliation' => ['nullable', 'string', 'max:100'],
            'civil_status' => ['nullable', 'string', 'in:single,married,divorced,widowed'],
            'citizenship' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:100',
                'unique:'.User::class,
                'regex:/^[a-zA-Z0-9._%+-]+@g\.msuiit\.edu\.ph$/i'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:student,counselor,admin'],
        ];

        // Add role-specific rules conditionally
        if ($request->role === 'student') {
            $rules['student_id'] = ['required', 'string', 'max:50', 'unique:students'];
            $rules['year_level'] = ['required', 'string', 'max:50'];
            $rules['course'] = ['required', 'string', 'max:100'];
            $rules['college_id'] = ['required', 'exists:colleges,id'];
        } elseif ($request->role === 'counselor') {
            $rules['position'] = ['required', 'string', 'max:100'];
            $rules['credentials'] = ['required', 'string', 'max:255'];
            $rules['counselor_college_id'] = ['required', 'exists:colleges,id'];
            $rules['is_head'] = ['nullable', 'boolean'];
        } elseif ($request->role === 'admin') {
            $rules['admin_credentials'] = ['required', 'string', 'max:255'];
        }

        // Validate with custom error message
        $request->validate($rules, [
            'email.regex' => 'You must use your MSU-IIT email (@g.msuiit.edu.ph).',
        ]);

        // Use transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Calculate age from birthdate
            $age = null;
            if ($request->birthdate) {
                $age = Carbon::parse($request->birthdate)->age;
            }

            // Step 1: Create User
            $user = User::create([
                'first_name' => strip_tags($request->first_name),
                'middle_name' => $request->middle_name ? strip_tags($request->middle_name) : null,
                'last_name' => strip_tags($request->last_name),
                'birthdate' => $request->birthdate,
                'age' => $age,
                'sex' => $request->sex,
                'birthplace' => $request->birthplace ? strip_tags($request->birthplace) : null,
                'religion' => $request->religion ? strip_tags($request->religion) : null,
                'affiliation' => $request->affiliation ? strip_tags($request->affiliation) : null,
                'civil_status' => $request->civil_status,
                'citizenship' => $request->citizenship ? strip_tags($request->citizenship) : null,
                'address' => $request->address ? strip_tags($request->address) : null,
                'phone_number' => $request->phone_number,
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // Step 2: Create role-specific record
            switch ($request->role) {
                case 'student':
                    Student::create([
                        'user_id' => $user->id,
                        'student_id' => $request->student_id,
                        'year_level' => $request->year_level,
                        'course' => strip_tags($request->course),
                        'college_id' => $request->college_id,
                    ]);
                    break;

                case 'counselor':
                    Counselor::create([
                        'user_id' => $user->id,
                        'position' => strip_tags($request->position),
                        'credentials' => strip_tags($request->credentials),
                        'college_id' => $request->counselor_college_id,
                        'is_head' => $request->is_head ?? false,
                    ]);
                    break;

                case 'admin':
                    Admin::create([
                        'user_id' => $user->id,
                        'credentials' => strip_tags($request->admin_credentials),
                    ]);
                    break;
            }

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            // Redirect based on role
            return redirect()->intended(
                $user->role === 'admin' ? route('admin.dashboard') :
                ($user->role === 'counselor' ? route('counselor.dashboard') : route('dashboard'))
            );

        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error with more details
            Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
