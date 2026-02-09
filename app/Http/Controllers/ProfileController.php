<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\Admin;
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
            $adminProfile = null;

            if ($user->role === 'student') {
                $studentProfile = Student::where('user_id', $user->id)->first();
            } elseif ($user->role === 'counselor') {
                $counselorProfile = Counselor::where('user_id', $user->id)
                    ->with('scheduleOverrides')
                    ->first();
            } elseif ($user->role === 'admin') {
                $adminProfile = Admin::where('user_id', $user->id)->first();
            }

            // Get colleges for dropdown
            $colleges = College::all();

            return view('profile.edit', compact('user', 'studentProfile', 'counselorProfile', 'adminProfile', 'colleges'));
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
                'google_calendar_id' => ['nullable', 'string', 'max:255'],
            ]);

            $user = $request->user();

            if ($user->role !== 'counselor') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $counselorProfile = Counselor::where('user_id', $user->id)->first();

            $updateData = [
                'position' => $request->input('position'),
                'credentials' => $request->input('credentials'),
                'specialization' => $request->input('specialization'),
                'college_id' => $request->input('college_id'),
                'google_calendar_id' => $request->input('google_calendar_id'),
                'is_head' => $request->boolean('is_head'),
            ];

            if ($counselorProfile) {
                $counselorProfile->update($updateData);
            } else {
                Counselor::create(array_merge(
                    ['user_id' => $user->id],
                    $updateData
                ));
            }

            return Redirect::route('profile.edit')->with('status', 'counselor-profile-updated');
        } catch (\Exception $e) {
            Log::error('Counselor profile update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update counselor profile.']);
        }
    }

    public function editAvailability(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'counselor') {
            abort(403, 'Unauthorized action.');
        }

        $counselorProfile = Counselor::where('user_id', $user->id)
            ->with('scheduleOverrides')
            ->first();

        if (!$counselorProfile) {
            return Redirect::route('profile.edit')->withErrors([
                'error' => 'Please complete your counselor profile before setting availability.',
            ]);
        }

        $availability = $counselorProfile->getAvailability() ?? [];
        $weekdays = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];
        $existingOverrides = $counselorProfile->scheduleOverrides ?? collect();

        return view('counselor.availability', compact('counselorProfile', 'availability', 'weekdays', 'existingOverrides'));
    }

    public function updateAvailability(Request $request)
    {
        try {
            $request->validate([
                'daily_booking_limit' => ['nullable', 'integer', 'min:0', 'max:50'],
                'availability_days' => ['nullable', 'array'],
                'availability_slots' => ['nullable', 'array'],
                'schedule_overrides' => ['nullable', 'array'],
            ]);

            $user = $request->user();

            if ($user->role !== 'counselor') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $counselorProfile = Counselor::where('user_id', $user->id)->first();

            if (!$counselorProfile) {
                return Redirect::route('profile.edit')->withErrors(['error' => 'Counselor profile not found.']);
            }

            $availability = $this->buildAvailabilityFromRequest($request);

            $counselorProfile->update([
                'daily_booking_limit' => $request->input('daily_booking_limit', $counselorProfile->daily_booking_limit),
                'availability' => $availability,
            ]);

            $this->syncScheduleOverrides($counselorProfile, $request->input('schedule_overrides', []));

            return Redirect::route('counselor.availability.edit')->with('status', 'counselor-availability-updated');
        } catch (\Exception $e) {
            Log::error('Counselor availability update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update availability settings.']);
        }
    }

    private function buildAvailabilityFromRequest(Request $request): array
    {
        $days = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];

        $selectedDays = $request->input('availability_days', []);
        $slotsInput = $request->input('availability_slots', []);
        $availability = [];

        foreach ($days as $day) {
            if (in_array($day, $selectedDays, true)) {
                $availability[$day] = $this->parseTimeSlots($slotsInput[$day] ?? '');
            } else {
                $availability[$day] = [];
            }
        }

        return $availability;
    }

    private function parseTimeSlots(?string $value): array
    {
        $rawSlots = array_filter(array_map('trim', explode(',', (string) $value)));

        return array_values(array_filter($rawSlots, function ($slot) {
            return preg_match('/^\d{2}:\d{2}-\d{2}:\d{2}$/', $slot);
        }));
    }

    private function syncScheduleOverrides(Counselor $counselor, array $overrides): void
    {
        foreach ($overrides as $override) {
            $overrideId = $override['id'] ?? null;

            if (!empty($override['remove'])) {
                if ($overrideId) {
                    $counselor->scheduleOverrides()->where('id', $overrideId)->delete();
                }
                continue;
            }

            $date = $override['date'] ?? null;
            if (!$date) {
                continue;
            }

            $status = $override['status'] ?? 'open';
            $isClosed = $status === 'closed';
            $timeSlots = $isClosed ? null : $this->parseTimeSlots($override['time_slots'] ?? '');

            $payload = [
                'date' => $date,
                'is_closed' => $isClosed,
                'time_slots' => $timeSlots ?: null,
            ];

            if ($overrideId) {
                $counselor->scheduleOverrides()->where('id', $overrideId)->update($payload);
            } else {
                $counselor->scheduleOverrides()->updateOrCreate(
                    ['date' => $date],
                    $payload
                );
            }
        }
    }

    /**
     * Update admin-specific profile information.
     */
    public function updateAdmin(Request $request)
    {
        try {
            $request->validate([
                'position' => ['required', 'string', 'max:255'],
                'department' => ['required', 'string', 'max:255'],
                'employee_id' => ['required', 'string', 'max:50'],
                'office_location' => ['nullable', 'string', 'max:255'],
                'extension' => ['nullable', 'string', 'max:20'],
            ]);

            $user = $request->user();

            if ($user->role !== 'admin') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $adminProfile = Admin::where('user_id', $user->id)->first();

            if ($adminProfile) {
                $adminProfile->update($request->all());
            } else {
                Admin::create(array_merge(
                    ['user_id' => $user->id],
                    $request->all()
                ));
            }

            return Redirect::route('profile.edit')->with('status', 'admin-profile-updated');
        } catch (\Exception $e) {
            Log::error('Admin profile update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update admin profile.']);
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
