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
                return view('admin.profile.edit', compact('user', 'adminProfile'));
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
            $user = $request->user();

            if ($user->role === 'student') {
                return back()->withErrors(['error' => 'You can only change your password on this page.']);
            }

            $request->validate([
                'first_name'   => ['nullable', 'string', 'max:255'],
                'middle_name'  => ['nullable', 'string', 'max:255'],
                'last_name'    => ['nullable', 'string', 'max:255'],
                'email'        => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'phone_number' => ['nullable', 'string', 'max:20'],
                'address'      => ['nullable', 'string', 'max:500'],
                'birthdate'    => ['nullable', 'date'],
                'sex'          => ['nullable', 'string', 'max:20'],
                'birthplace'   => ['nullable', 'string', 'max:255'],
                'religion'     => ['nullable', 'string', 'max:255'],
                'civil_status' => ['nullable', 'string', 'max:50'],
                'citizenship'  => ['nullable', 'string', 'max:255'],
            ]);

            // For counselors: only update fields that are currently empty (lock-once)
            if ($user->role === 'counselor') {
                $fillable = ['first_name','middle_name','last_name','birthdate','sex','birthplace','religion','civil_status','citizenship','phone_number','address'];
                foreach ($fillable as $field) {
                    if (empty($user->$field) && $request->filled($field)) {
                        $user->$field = $request->input($field);
                    }
                }
            } else {
                $user->fill($request->only(['first_name','middle_name','last_name','email','phone_number','address','birthdate','sex','birthplace','religion','civil_status','citizenship']));
                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }
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
                'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            ]);

            $user = $request->user();

            if ($user->role !== 'student') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $studentProfile = Student::where('user_id', $user->id)->first();

            if (!$studentProfile) {
                return back()->withErrors(['error' => 'Student profile not found.']);
            }

            if ($request->hasFile('profile_picture')) {
                if ($studentProfile->profile_picture) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($studentProfile->profile_picture);
                }
                $path = $request->file('profile_picture')->store('profile-pictures', 'public');
                $studentProfile->profile_picture = $path;
                $studentProfile->save();
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
                'google_calendar_id' => ['nullable', 'string', 'max:255'],
                'profile_picture'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:20480'],
                'facebook_link'      => ['nullable', 'url', 'max:255'],
                'position'           => ['nullable', 'string', 'max:255'],
                'credentials'        => ['nullable', 'string', 'max:255'],
            ]);

            $user = $request->user();

            if ($user->role !== 'counselor') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $counselorProfiles = Counselor::where('user_id', $user->id)->get();
            if ($counselorProfiles->isEmpty()) {
                return back()->withErrors(['error' => 'Counselor profile not found.']);
            }

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                // Delete old picture if exists
                if ($user->profile_picture) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_picture);
                }
                $path = $request->file('profile_picture')->store('profile-pictures', 'public');
                $user->profile_picture = $path;
                $user->save();
            }

            $updateData = [
                'google_calendar_id' => $request->input('google_calendar_id'),
                'facebook_link'      => $request->input('facebook_link') ?: null,
            ];

            // Only save position/credentials if currently empty (lock-once)
            $firstProfile = $counselorProfiles->first();
            if (empty($firstProfile->position) && $request->filled('position')) {
                $updateData['position'] = $request->input('position');
            }
            if (empty($firstProfile->credentials) && $request->filled('credentials')) {
                $updateData['credentials'] = $request->input('credentials');
            }

            Counselor::where('user_id', $user->id)->update($updateData);

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

            $counselorProfiles = Counselor::where('user_id', $user->id)->get();

            if ($counselorProfiles->isEmpty()) {
                return Redirect::route('profile.edit')->withErrors(['error' => 'Counselor profile not found.']);
            }

            $availability = $this->buildAvailabilityFromRequest($request);
            $dailyBookingLimit = $request->input('daily_booking_limit');
            $overrides = $request->input('schedule_overrides', []);

            Log::info('Availability update', [
                'user_id' => $user->id,
                'selected_days' => $request->input('availability_days', []),
                'slots_input' => $request->input('availability_slots', []),
                'built_availability' => $availability,
                'counselor_count' => $counselorProfiles->count(),
            ]);

            foreach ($counselorProfiles as $counselorProfile) {
                $rows = \Illuminate\Support\Facades\DB::table('counselors')
                    ->where('id', $counselorProfile->id)
                    ->update([
                        'daily_booking_limit' => $dailyBookingLimit ?? $counselorProfile->daily_booking_limit,
                        'availability' => json_encode($availability),
                        'updated_at' => now(),
                    ]);

                Log::debug('DB update result', ['counselor_id' => $counselorProfile->id, 'rows_affected' => $rows]);

                $counselorProfile->refresh();
                $this->syncScheduleOverrides($counselorProfile, $overrides);
            }

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

        $defaultSlots = ['08:00-12:00', '13:00-17:00'];
        $selectedDays = $request->input('availability_days', []);
        $slotsInput = $request->input('availability_slots', []);
        $availability = [];

        foreach ($days as $day) {
            if (in_array($day, $selectedDays, true)) {
                $parsed = $this->parseTimeSlots($slotsInput[$day] ?? '');
                // If checked but no valid slots typed, use the default
                $availability[$day] = !empty($parsed) ? $parsed : $defaultSlots;
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
     * Update admin email.
     */
    public function updateAdmin(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role !== 'admin') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ]);

            $user->email = $request->email;
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $user->save();

            return Redirect::route('profile.edit')->with('status', 'admin-profile-updated');
        } catch (\Exception $e) {
            Log::error('Admin profile update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update email.']);
        }
    }

    /**
     * Update admin profile picture.
     */
    public function updateAdminPicture(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role !== 'admin') {
                return back()->withErrors(['error' => 'Unauthorized action.']);
            }

            $request->validate([
                'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:4096'],
            ]);

            if ($user->profile_picture) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = $path;
            $user->save();

            return Redirect::route('profile.edit')->with('status', 'admin-picture-updated');
        } catch (\Exception $e) {
            Log::error('Admin picture update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update profile picture.']);
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
