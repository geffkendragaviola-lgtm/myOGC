@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c;
        --maroon-800: #5c1a1a;
        --maroon-700: #7a2a2a;
        --gold-500: #c9a227;
        --gold-400: #d4af37;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
    }

    .profile-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .profile-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .profile-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .profile-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .section-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .section-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .panel-card::before, .section-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon, .section-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.16em; color: var(--maroon-700);
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
        pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.25rem; }

    .primary-btn, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; font-size: 0.8rem;
        padding: 0.55rem 0.85rem;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        background: #ffffff; color: var(--text-secondary); border: 1px solid var(--border-soft);
        box-shadow: 0 2px 6px rgba(44,36,32,0.03);
    }
    .secondary-btn:hover { background: #f5f0eb; }

    .panel-topline, .section-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header, .section-header {
        display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-soft)/60;
    }
    .panel-icon, .section-icon {
        width: 2rem; height: 2rem; border-radius: 0.6rem; background: rgba(254,249,231,0.7); color: var(--maroon-700);
    }
    .panel-title, .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle, .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .input-field, .textarea-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field, .select-field { padding: 0.55rem 0.75rem; }
    .textarea-field { padding: 0.65rem 0.75rem; resize: vertical; }
    .input-field:focus, .textarea-field:focus, .select-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }
    .helper-text { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.25rem; }

    @media (max-width: 639px) {
        .panel-header, .section-header { padding: 0.75rem 1rem; }
        .input-field, .textarea-field, .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.6rem 1rem; }
    }
</style>

@php
    $cp = $counselorProfile;
    $lockPosition = $cp ? !empty($cp->position) : false;
    $lockCredentials = $cp ? !empty($cp->credentials) : false;

    $lockFirstName = !empty($user->first_name);
    $lockMiddleName = !empty($user->middle_name);
    $lockLastName = !empty($user->last_name);
    $lockBirthdate = !empty($user->birthdate);
    $lockSex = !empty($user->sex);
    $lockBirthplace = !empty($user->birthplace);
    $lockReligion = !empty($user->religion);
    $lockCivilStatus = !empty($user->civil_status);
    $lockCitizenship = !empty($user->citizenship);
    $lockPhoneNumber = !empty($user->phone_number);
    $lockAddress = !empty($user->address);
@endphp

<div class="min-h-screen profile-shell">
    <div class="profile-glow one"></div>
    <div class="profile-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-user-tie text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Account Center
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Profile Settings</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Manage your profile photo, counselor details, and account password.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-arrow-left text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Navigation</p>
                                <p class="summary-value">Back to Dashboard</p>
                                <p class="summary-subtext hidden sm:block">Return to your counselor dashboard.</p>
                            </div>
                        </div>
                        <a href="{{ route('counselor.dashboard') }}" class="secondary-btn px-3 py-2 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('status') === 'counselor-profile-updated')
            <div class="mb-4 bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30 p-3 rounded-lg flex items-center gap-2 text-sm font-semibold shadow-sm">
                <i class="fas fa-check-circle"></i> Counselor profile updated successfully.
            </div>
        @endif
        @if(session('status') === 'profile-updated')
            <div class="mb-4 bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30 p-3 rounded-lg flex items-center gap-2 text-sm font-semibold shadow-sm">
                <i class="fas fa-check-circle"></i> Profile updated successfully.
            </div>
        @endif
        @if(session('status') === 'password-updated')
            <div class="mb-4 bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30 p-3 rounded-lg flex items-center gap-2 text-sm font-semibold shadow-sm">
                <i class="fas fa-check-circle"></i> Password updated successfully.
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">

            <div class="panel-card flex flex-col">
                <div class="panel-topline"></div>
                <div class="panel-header">
                    <div class="panel-icon"><i class="fas fa-id-card text-[9px] sm:text-xs"></i></div>
                    <div>
                        <h2 class="panel-title">Profile Information</h2>
                        <p class="panel-subtitle hidden sm:block">Update your photo and counselor details.</p>
                    </div>
                </div>

                <div class="p-3 sm:p-4 flex-1 flex flex-col">
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-camera text-[9px] sm:text-xs"></i></div>
                            <div>
                                <h3 class="section-title">Profile Photo</h3>
                                <p class="section-subtitle hidden sm:block">JPG, PNG or GIF. Max 20MB.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            <form method="POST" action="{{ route('profile.counselor.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <div class="flex items-center gap-4">
                                    <div class="relative flex-shrink-0">
                                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-[rgba(212,175,55,0.4)] shadow-sm bg-[#faf8f5]">
                                            @if(Auth::user()->profile_picture)
                                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover" id="pfp-preview">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#7a2a2a] to-[#3a0c0c]" id="pfp-placeholder">
                                                    <i class="fas fa-user-tie text-[#fef9e7] text-2xl sm:text-3xl"></i>
                                                </div>
                                                <img src="" alt="" class="w-full h-full object-cover hidden" id="pfp-preview">
                                            @endif
                                        </div>
                                        <label for="profile_picture" class="absolute bottom-0 right-0 w-7 h-7 bg-[#d4af37] text-white rounded-full flex items-center justify-center cursor-pointer shadow border-2 border-white hover:bg-[#c9a227] transition">
                                            <i class="fas fa-camera text-xs"></i>
                                        </label>
                                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden" onchange="previewPfp(this)">
                                    </div>
                                    <div class="flex-1">
                                        <button type="submit" class="secondary-btn rounded-lg">
                                            <i class="fas fa-upload mr-1.5 text-[9px] sm:text-xs"></i> Upload Photo
                                        </button>
                                        @error('profile_picture') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-user text-[9px] sm:text-xs"></i></div>
                            <div>
                                <h3 class="section-title">Personal Information</h3>
                                <p class="section-subtitle hidden sm:block">Fill in your personal details (editable only if empty).</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('patch')

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                    <div>
                                        <label for="first_name" class="field-label">First Name</label>
                                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" @if($lockFirstName) disabled @endif class="input-field">
                                        @if($lockFirstName)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('first_name') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="middle_name" class="field-label">Middle Name</label>
                                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" @if($lockMiddleName) disabled @endif class="input-field">
                                        @if($lockMiddleName)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('middle_name') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="last_name" class="field-label">Last Name</label>
                                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" @if($lockLastName) disabled @endif class="input-field">
                                        @if($lockLastName)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('last_name') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="birthdate" class="field-label">Birthdate</label>
                                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate) }}" @if($lockBirthdate) disabled @endif class="input-field">
                                        @if($lockBirthdate)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('birthdate') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="sex" class="field-label">Sex</label>
                                        <input type="text" id="sex" name="sex" value="{{ old('sex', $user->sex) }}" @if($lockSex) disabled @endif class="input-field">
                                        @if($lockSex)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('sex') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="civil_status" class="field-label">Civil Status</label>
                                        <input type="text" id="civil_status" name="civil_status" value="{{ old('civil_status', $user->civil_status) }}" @if($lockCivilStatus) disabled @endif class="input-field">
                                        @if($lockCivilStatus)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('civil_status') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="citizenship" class="field-label">Citizenship</label>
                                        <input type="text" id="citizenship" name="citizenship" value="{{ old('citizenship', $user->citizenship) }}" @if($lockCitizenship) disabled @endif class="input-field">
                                        @if($lockCitizenship)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('citizenship') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="religion" class="field-label">Religion</label>
                                        <input type="text" id="religion" name="religion" value="{{ old('religion', $user->religion) }}" @if($lockReligion) disabled @endif class="input-field">
                                        @if($lockReligion)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('religion') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="birthplace" class="field-label">Birthplace</label>
                                        <input type="text" id="birthplace" name="birthplace" value="{{ old('birthplace', $user->birthplace) }}" @if($lockBirthplace) disabled @endif class="input-field">
                                        @if($lockBirthplace)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('birthplace') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="phone_number" class="field-label">Phone Number</label>
                                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" @if($lockPhoneNumber) disabled @endif class="input-field">
                                        @if($lockPhoneNumber)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('phone_number') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="address" class="field-label">Address</label>
                                        <textarea id="address" name="address" rows="3" @if($lockAddress) disabled @endif class="textarea-field">{{ old('address', $user->address) }}</textarea>
                                        @if($lockAddress)<p class="helper-text">Contact admin to change this field.</p>@endif
                                        @error('address') <p class="error-text">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end">
                                    <button type="submit" class="primary-btn rounded-lg">
                                        <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i> Save Personal Info
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-5 sm:space-y-6">
                <div class="panel-card flex flex-col">
                    <div class="panel-topline"></div>
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-user-gear text-[9px] sm:text-xs"></i></div>
                        <div>
                            <h2 class="panel-title">Counselor Details</h2>
                            <p class="panel-subtitle hidden sm:block">Update your counselor-facing information.</p>
                        </div>
                    </div>

                    <div class="p-3 sm:p-4 flex-1 flex flex-col">
                        <div class="section-card flex-1 flex flex-col">
                            <div class="section-topline"></div>
                            <div class="section-header">
                                <div class="section-icon"><i class="fas fa-user-tie text-[9px] sm:text-xs"></i></div>
                                <div>
                                    <h3 class="section-title">Professional Information</h3>
                                    <p class="section-subtitle hidden sm:block">Position, credentials, and contact links.</p>
                                </div>
                            </div>

                            <div class="p-3 sm:p-4 flex-1 flex flex-col">
                                <form method="POST" action="{{ route('profile.counselor.update') }}" class="flex-1 flex flex-col" enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')

                                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                            <div>
                                                <label for="position" class="field-label">Position</label>
                                                <input type="text" id="position" name="position" value="{{ old('position', $cp->position ?? '') }}" @if($lockPosition) disabled @endif class="input-field">
                                                @if($lockPosition)
                                                    <p class="helper-text">Contact admin to change your position.</p>
                                                @endif
                                                @error('position') <p class="error-text">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <label for="credentials" class="field-label">Credentials</label>
                                                <input type="text" id="credentials" name="credentials" value="{{ old('credentials', $cp->credentials ?? '') }}" @if($lockCredentials) disabled @endif class="input-field">
                                                @if($lockCredentials)
                                                    <p class="helper-text">Contact admin to change your credentials.</p>
                                                @endif
                                                @error('credentials') <p class="error-text">{{ $message }}</p> @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label for="google_calendar_id" class="field-label">Google Calendar ID</label>
                                            <input type="text" id="google_calendar_id" name="google_calendar_id" value="{{ old('google_calendar_id', $cp->google_calendar_id ?? '') }}" placeholder="e.g. counselor@yourdomain.com" class="input-field">
                                            @error('google_calendar_id') <p class="error-text">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label for="facebook_link" class="field-label">Facebook Page Link</label>
                                            <input type="url" id="facebook_link" name="facebook_link" value="{{ old('facebook_link', $cp->facebook_link ?? '') }}" placeholder="e.g. https://www.facebook.com/yourpage" class="input-field">
                                            @error('facebook_link') <p class="error-text">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label for="college_id" class="field-label">Assigned College</label>
                                            <select id="college_id" name="college_id" disabled class="select-field">
                                                <option value="">Select College</option>
                                                @foreach($colleges as $college)
                                                    <option value="{{ $college->id }}" {{ old('college_id', $cp->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                        {{ $college->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('college_id') <p class="error-text">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4 sm:mt-auto pt-4 flex justify-end">
                                        <button type="submit" class="primary-btn rounded-lg">
                                            <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i> Save Counselor Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-card flex flex-col">
                    <div class="panel-topline"></div>
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-shield-alt text-[9px] sm:text-xs"></i></div>
                        <div>
                            <h2 class="panel-title">Change Password</h2>
                            <p class="panel-subtitle hidden sm:block">Ensure your account is using a long, random password.</p>
                        </div>
                    </div>

                    <div class="p-3 sm:p-4 flex-1 flex flex-col">
                        <div class="section-card flex-1 flex flex-col">
                            <div class="section-topline"></div>
                            <div class="section-header">
                                <div class="section-icon"><i class="fas fa-lock text-[9px] sm:text-xs"></i></div>
                                <div>
                                    <h3 class="section-title">Security Credentials</h3>
                                    <p class="section-subtitle hidden sm:block">Provide your current and new password.</p>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4 flex-1 flex flex-col">
                                <form method="POST" action="{{ route('profile.password.update') }}" class="flex-1 flex flex-col">
                                    @csrf
                                    @method('patch')

                                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                                        <div>
                                            <label for="counselor_current_password" class="field-label">Current Password *</label>
                                            <input type="password" id="counselor_current_password" name="current_password" required class="input-field">
                                            @error('current_password') <p class="error-text">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label for="counselor_new_password" class="field-label">New Password *</label>
                                            <input type="password" id="counselor_new_password" name="password" required class="input-field">
                                            @error('password') <p class="error-text">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <label for="counselor_password_confirmation" class="field-label">Confirm New Password *</label>
                                            <input type="password" id="counselor_password_confirmation" name="password_confirmation" required class="input-field">
                                        </div>
                                    </div>

                                    <div class="mt-4 sm:mt-auto pt-4 flex justify-end">
                                        <button type="submit" class="primary-btn rounded-lg">
                                            <i class="fas fa-lock mr-1.5 text-[9px] sm:text-xs"></i> Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function previewPfp(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('pfp-preview');
                var placeholder = document.getElementById('pfp-placeholder');
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
