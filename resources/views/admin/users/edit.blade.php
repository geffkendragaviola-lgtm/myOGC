@extends('layouts.admin')

@section('title', 'Edit User - Admin Panel')

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

    .users-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .users-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .users-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .users-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .tabs-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .tabs-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before, .glass-card::before, .tabs-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon {
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
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .role-chip {
        display: inline-flex; align-items: center; padding: 0.2rem 0.5rem;
        border-radius: 999px; background: rgba(254,249,231,0.8);
        border: 1px solid rgba(212,175,55,0.3); color: var(--maroon-700);
        font-size: 0.7rem; font-weight: 700; text-transform: capitalize;
    }

    .primary-btn, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        color: var(--text-primary); background: rgba(255,255,255,0.9);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.6); border-color: var(--maroon-700); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .textarea-field { padding: 0.75rem; resize: vertical; min-height: 3.5rem; }
    .input-field:focus, .select-field:focus, .textarea-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    .error-text {
        font-size: 0.7rem; color: #b91c1c; margin-top: 0.3rem; font-weight: 500;
    }

    .success-alert, .error-alert {
        background: rgba(236,253,245,0.95); border: 1px solid #10b981/30;
        color: #065f46; padding: 0.85rem 1.1rem; border-radius: 0.6rem;
        font-size: 0.8rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .error-alert {
        background: rgba(254,242,242,0.96); border: 1px solid #ef4444/30;
        color: #b91c1c;
    }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--maroon-700); font-size: 0.75rem; font-weight: 600;
        transition: all 0.18s ease;
    }
    .back-link:hover { color: var(--maroon-900); transform: translateX(-2px); }

    /* Tabs - adapted to design system */
    .tabs-nav {
        display: flex; border-bottom: 1px solid var(--border-soft);
        overflow-x: auto; -webkit-overflow-scrolling: touch;
    }
    .tab-btn {
        padding: 0.75rem 1.25rem; font-size: 0.75rem; font-weight: 600;
        color: var(--text-muted); border-bottom: 2px solid transparent;
        white-space: nowrap; transition: all 0.2s ease;
        background: transparent; border: none; cursor: pointer;
    }
    .tab-btn:hover { color: var(--maroon-700); background: rgba(254,249,231,0.4); }
    .tab-active {
        color: var(--maroon-700) !important;
        border-bottom-color: var(--gold-400) !important;
        background: rgba(254,249,231,0.6);
    }
    .tab-content { display: block; }
    .tab-content.hidden { display: none; }

    .checkbox-card {
        display: flex; align-items: center; padding: 0.75rem 1rem;
        border-radius: 0.6rem; background: rgba(250,248,245,0.6);
        border: 1px solid var(--border-soft); transition: all 0.2s ease;
    }
    .checkbox-card:hover { background: rgba(254,249,231,0.4); border-color: var(--maroon-700); }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field, .textarea-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.75rem; }
        .flex-end-mobile { flex-direction: column; gap: 0.75rem !important; }
        .hero-card, .summary-card { padding: 1rem !important; }
        .hero-icon { width: 2.25rem; height: 2.25rem; }
        .summary-icon { width: 2.25rem; height: 2.25rem; }
        .tabs-nav { padding: 0 0.5rem; }
        .tab-btn { padding: 0.65rem 1rem; font-size: 0.7rem; }
    }
</style>

<div class="min-h-screen users-shell">
    <div class="users-glow one"></div>
    <div class="users-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-user-gear text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('admin.users') }}" class="back-link mb-2">
                                <i class="fas fa-arrow-left text-[9px]"></i> Back to Users
                            </a>
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                User Editor
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Edit User</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Manage user account information and role-specific details.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-id-badge text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Current User</p>
                                <p class="summary-value">{{ $user->first_name }} {{ $user->last_name }}</p>
                                <p class="summary-subtext hidden sm:block">
                                    <span class="role-chip">{{ $user->role }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="role-chip sm:hidden">{{ $user->role }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Messages -->
        @if(session('success'))
            <div class="glass-card success-alert mb-5">
                <i class="fas fa-check-circle text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="glass-card error-alert mb-5">
                <i class="fas fa-exclamation-circle text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="glass-card error-alert mb-5">
                <i class="fas fa-exclamation-circle text-sm"></i>
                <div>
                    <span class="font-semibold">Please fix the errors below.</span>
                </div>
            </div>
        @endif

        <!-- Tabs -->
        <div class="tabs-card mb-5">
            <div class="tabs-nav">
                <button id="personal-tab" class="tab-btn tab-active">
                    Personal Information
                </button>
                <button id="role-tab" class="tab-btn">
                    {{ ucfirst($user->role) }} Profile
                </button>
            </div>
        </div>

        <!-- Personal Information Tab -->
        <div id="personal-content" class="tab-content">
            <div class="panel-card">
                <div class="panel-topline"></div>
                <div class="panel-header">
                    <div class="panel-icon"><i class="fas fa-user text-[9px] sm:text-xs"></i></div>
                    <div>
                        <h2 class="panel-title">Personal Information</h2>
                        <p class="panel-subtitle hidden sm:block">Update core identity, contact, and demographic details.</p>
                    </div>
                </div>

                <div class="p-4 sm:p-5">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                            <div>
                                <label for="first_name" class="field-label">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                       class="input-field">
                                @error('first_name')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="middle_name" class="field-label">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                                       class="input-field">
                                @error('middle_name')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="field-label">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                       class="input-field">
                                @error('last_name')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="field-label">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="input-field">
                                @error('email')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="field-label">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                       class="input-field">
                                @error('phone_number')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="birthdate" class="field-label">Birthdate</label>
                                <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}"
                                       class="input-field">
                                @error('birthdate')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sex" class="field-label">Sex</label>
                                <select id="sex" name="sex" class="select-field">
                                    <option value="">Select</option>
                                    <option value="male" {{ old('sex', $user->sex) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('sex', $user->sex) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('sex', $user->sex) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sex')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="civil_status" class="field-label">Civil Status</label>
                                <select id="civil_status" name="civil_status" class="select-field">
                                    <option value="">Select</option>
                                    @foreach(['single','married','divorced','widowed'] as $cs)
                                        <option value="{{ $cs }}" {{ old('civil_status', $user->civil_status) == $cs ? 'selected' : '' }}>{{ ucfirst($cs) }}</option>
                                    @endforeach
                                </select>
                                @error('civil_status')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="birthplace" class="field-label">Birthplace</label>
                                <input type="text" id="birthplace" name="birthplace" value="{{ old('birthplace', $user->birthplace) }}"
                                       class="input-field">
                                @error('birthplace')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="religion" class="field-label">Religion</label>
                                <input type="text" id="religion" name="religion" value="{{ old('religion', $user->religion) }}"
                                       class="input-field">
                                @error('religion')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="citizenship" class="field-label">Citizenship</label>
                                <input type="text" id="citizenship" name="citizenship" value="{{ old('citizenship', $user->citizenship) }}"
                                       class="input-field">
                                @error('citizenship')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="field-label">Address</label>
                                <textarea id="address" name="address" rows="3"
                                          class="textarea-field">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 sm:mt-8 flex justify-between flex-end-mobile">
                            <a href="{{ route('admin.users') }}"
                               class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                                <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>
                                <span>Back to Users</span>
                            </a>
                            <button type="submit"
                                    class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                                <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>
                                <span>Update User</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Role-specific Profile Tab -->
        <div id="role-content" class="tab-content hidden">
            <div class="panel-card">
                <div class="panel-topline"></div>
                <div class="panel-header">
                    <div class="panel-icon"><i class="fas fa-id-badge text-[9px] sm:text-xs"></i></div>
                    <div>
                        <h2 class="panel-title">{{ ucfirst($user->role) }} Profile Information</h2>
                        <p class="panel-subtitle hidden sm:block">Manage role-specific fields and professional or academic details.</p>
                    </div>
                </div>

                <div class="p-4 sm:p-5">
                    @if($user->role === 'student')
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div>
                                    <label for="student_id" class="field-label">Student ID</label>
                                    <input type="text" id="student_id" name="student_id"
                                           value="{{ old('student_id', $user->student->student_id ?? '') }}"
                                           class="input-field">
                                    @error('student_id')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="year_level" class="field-label">Year Level</label>
                                    <select id="year_level" name="year_level" class="select-field">
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" {{ old('year_level', $user->student->year_level ?? '') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level', $user->student->year_level ?? '') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level', $user->student->year_level ?? '') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level', $user->student->year_level ?? '') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                        <option value="5th Year" {{ old('year_level', $user->student->year_level ?? '') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                                        <option value="Graduate" {{ old('year_level', $user->student->year_level ?? '') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    </select>
                                    @error('year_level')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="course" class="field-label">Course</label>
                                    <input type="text" id="course" name="course"
                                           value="{{ old('course', $user->student->course ?? '') }}"
                                           class="input-field"
                                           placeholder="e.g., Bachelor of Science in Computer Science">
                                    @error('course')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="college_id" class="field-label">College</label>
                                    <select id="college_id" name="college_id" class="select-field">
                                        <option value="">Select College</option>
                                        @foreach($colleges as $college)
                                            <option value="{{ $college->id }}"
                                                    {{ old('college_id', $user->student->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('college_id')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 sm:mt-8 flex justify-between flex-end-mobile">
                                <a href="{{ route('admin.users') }}"
                                   class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                                    <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>
                                    <span>Back to Users</span>
                                </a>
                                <button type="submit"
                                        class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                                    <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>
                                    <span>Update Student Profile</span>
                                </button>
                            </div>
                        </form>

                    @elseif($user->role === 'counselor')
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                                <div>
                                    <label for="position" class="field-label">Position</label>
                                    <input type="text" id="position" name="position"
                                           value="{{ old('position', $user->counselor->position ?? '') }}"
                                           class="input-field">
                                    @error('position')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="credentials" class="field-label">Credentials</label>
                                    <input type="text" id="credentials" name="credentials"
                                           value="{{ old('credentials', $user->counselor->credentials ?? '') }}"
                                           class="input-field">
                                    @error('credentials')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="counselor_college_id" class="field-label">Assigned College</label>
                                    <select id="counselor_college_id" name="counselor_college_id" class="select-field">
                                        <option value="">Select College</option>
                                        @foreach($colleges as $college)
                                            <option value="{{ $college->id }}"
                                                    {{ old('counselor_college_id', $user->counselor->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('counselor_college_id')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="specialization" class="field-label">Specialization</label>
                                    <textarea id="specialization" name="specialization" rows="3"
                                              class="textarea-field">{{ old('specialization', $user->counselor->specialization ?? '') }}</textarea>
                                    @error('specialization')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="checkbox-card">
                                        <input type="checkbox" name="is_head" value="1"
                                               {{ old('is_head', $user->counselor->is_head ?? false) ? 'checked' : '' }}
                                               class="rounded border-[#e5e0db] text-[#7a2a2a] focus:ring-[#7a2a2a]">
                                        <span class="ml-3 text-[0.8rem] text-[#6b5e57] font-medium">Head Counselor</span>
                                    </label>
                                    @error('is_head')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 sm:mt-8 flex justify-between flex-end-mobile">
                                <a href="{{ route('admin.users') }}"
                                   class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                                    <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>
                                    <span>Back to Users</span>
                                </a>
                                <button type="submit"
                                        class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                                    <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>
                                    <span>Update Counselor Profile</span>
                                </button>
                            </div>
                        </form>

                    @elseif($user->role === 'admin')
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 gap-4 sm:gap-5">
                                <div>
                                    <label for="admin_credentials" class="field-label">Admin Credentials</label>
                                    <input type="text" id="admin_credentials" name="admin_credentials"
                                           value="{{ old('admin_credentials', $user->admin->credentials ?? '') }}"
                                           class="input-field"
                                           placeholder="e.g., System Administrator, Head Admin">
                                    @error('admin_credentials')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 sm:mt-8 flex justify-between flex-end-mobile">
                                <a href="{{ route('admin.users') }}"
                                   class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                                    <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>
                                    <span>Back to Users</span>
                                </a>
                                <button type="submit"
                                        class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                                    <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>
                                    <span>Update Admin Profile</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const personalTab = document.getElementById('personal-tab');
        const roleTab = document.getElementById('role-tab');
        const personalContent = document.getElementById('personal-content');
        const roleContent = document.getElementById('role-content');

        function switchToPersonal() {
            personalTab.classList.add('tab-active');
            
            roleTab.classList.remove('tab-active');
            
            personalContent.classList.remove('hidden');
            roleContent.classList.add('hidden');
        }

        function switchToRole() {
            roleTab.classList.add('tab-active');
            
            personalTab.classList.remove('tab-active');
            
            roleContent.classList.remove('hidden');
            personalContent.classList.add('hidden');
        }

        personalTab.addEventListener('click', switchToPersonal);
        roleTab.addEventListener('click', switchToRole);
    });
</script>
@endsection