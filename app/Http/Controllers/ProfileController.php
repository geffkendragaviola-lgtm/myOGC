<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Student;
use App\Models\Counselor;
use Illuminate\Support\Facades\Log;
use App\Models\College;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        try {
            $user = $request->user();
            $studentProfile = null;
            $counselorProfile = null;

            if ($user->role === 'student') {
                $studentProfile = Student::where('user_id', $user->id)->first();
            } elseif ($user->role === 'counselor') {
                $counselorProfile = Counselor::where('user_id', $user->id)->first();
            }

            // Get colleges for dropdown
            $colleges = College::all();

            return view('profile.edit', compact('user', 'studentProfile', 'counselorProfile', 'colleges'));
        } catch (\Exception $e) {
            // Log the error and return a simple view
            Log::error('Profile edit error: ' . $e->getMessage());
            abort(500, 'Unable to load profile page.');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        try {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'middle_name' => ['nullable', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
                'phone' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:500'],
            ]);

            $user = $request->user();

            $user->fill($request->all());

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update profile.']);
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => ['required', 'current_password'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user = $request->user();

            $user->update([
                'password' => Hash::make($request->password),
            ]);

            return Redirect::route('profile.edit')->with('status', 'password-updated');
        } catch (\Exception $e) {
            Log::error('Password update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update password.']);
        }
    }

    /**
     * Update student-specific profile information.
     */
    public function updateStudent(Request $request)
    {
        try {
            $request->validate([
                'student_id' => ['required', 'string', 'max:50'],
                'year_level' => ['required', 'string', 'in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate'],
                'college_id' => ['required', 'exists:colleges,id'],
            ]);

            $user = $request->user();

            if ($user->role !== 'student') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $studentProfile = Student::where('user_id', $user->id)->first();

            if ($studentProfile) {
                $studentProfile->update($request->all());
            } else {
                Student::create(array_merge(
                    ['user_id' => $user->id],
                    $request->all()
                ));
            }

            return Redirect::route('profile.edit')->with('status', 'student-profile-updated');
        } catch (\Exception $e) {
            Log::error('Student profile update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update student profile.']);
        }
    }

    /**
     * Update counselor-specific profile information.
     */
    public function updateCounselor(Request $request)
    {
        try {
            $request->validate([
                'position' => ['required', 'string', 'max:255'],
                'credentials' => ['required', 'string', 'max:255'],
                'specialization' => ['nullable', 'string', 'max:500'],
                'college_id' => ['required', 'exists:colleges,id'],
            ]);

            $user = $request->user();

            if ($user->role !== 'counselor') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $counselorProfile = Counselor::where('user_id', $user->id)->first();

            if ($counselorProfile) {
                $counselorProfile->update($request->all());
            } else {
                Counselor::create(array_merge(
                    ['user_id' => $user->id],
                    $request->all()
                ));
            }

            return Redirect::route('profile.edit')->with('status', 'counselor-profile-updated');
        } catch (\Exception $e) {
            Log::error('Counselor profile update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update counselor profile.']);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'password' => ['required', 'current_password'],
            ]);

            $user = $request->user();

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/');
        } catch (\Exception $e) {
            Log::error('Account deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete account.']);
        }
    }
}
