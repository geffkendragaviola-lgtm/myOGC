<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <title>Profile - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-red: #8f1d1d;
            --primary-red-dark: #6f1414;
            --primary-red-deep: #5b0f0f;
            --accent-gold: #d4af37;
            --accent-gold-soft: #e7c766;
            --bg-light: #f6f1ea;
            --bg-soft: #fbf7f2;
            --bg-white: #fffdfb;
            --text-dark: #2f2522;
            --text-secondary: #766864;
            --text-muted: #a09490;
            --border-soft: #e8ddd2;
            --danger-red: #dc3545;
            --shadow-soft: 0 10px 30px rgba(91, 15, 15, 0.08);
            --shadow-medium: 0 16px 40px rgba(91, 15, 15, 0.12);
            --shadow-strong: 0 20px 46px rgba(91, 15, 15, 0.18);
            --radius-lg: 24px;
            --radius-md: 18px;
            --radius-sm: 14px;
        }

        * {
            scroll-behavior: smooth;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(143, 29, 29, 0.06), transparent 24%),
                radial-gradient(circle at top right, rgba(212, 175, 55, 0.08), transparent 20%),
                linear-gradient(180deg, #faf6f0 0%, #f6f1ea 100%);
            color: var(--text-dark);
        }

        .profile-container {
            min-height: 100vh;
        }

        .profile-navbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: linear-gradient(90deg, var(--primary-red-deep), var(--primary-red), #a11f2f);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 24px rgba(91, 15, 15, 0.18);
            backdrop-filter: blur(12px);
        }

        .brand-badge {
            min-width: 112px;
            height: 58px;
            padding: 0 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            background: rgba(255, 248, 240, 0.12);
            border: 1px solid rgba(255, 240, 220, 0.18);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.14);
        }

        .brand-text {
            color: #FFFFFF;
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            display: block;
        }

        .glass-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.72rem 1rem;
            border-radius: 999px;
            color: #fff;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .glass-link:hover {
            background: rgba(255, 248, 240, 0.12);
            transform: translateY(-1px);
        }

        .section-shell {
            background: rgba(255, 253, 251, 0.82);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 250, 246, 0.85);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-soft);
        }

        .hero-card {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(91, 15, 15, 0.96) 0%, rgba(143, 29, 29, 0.94) 55%, rgba(167, 37, 47, 0.92) 100%);
            color: white;
            box-shadow: var(--shadow-strong);
        }

        .hero-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(255,255,255,0.16), transparent 26%),
                radial-gradient(circle at bottom left, rgba(212,175,55,0.18), transparent 24%);
            pointer-events: none;
        }

        .hero-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(255,255,255,0.02), rgba(0,0,0,0.08));
            pointer-events: none;
        }

        .hero-badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            background: rgba(255, 248, 240, 0.12);
            border: 1px solid rgba(255, 240, 220, 0.18);
            color: rgba(255,255,255,0.9);
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .hero-side-summary {
            background: rgba(255, 248, 240, 0.12);
            border: 1px solid rgba(255, 240, 220, 0.18);
            border-radius: 18px;
            padding: 1rem 1.15rem;
            min-width: 220px;
            backdrop-filter: blur(8px);
        }

        .profile-card {
            background: linear-gradient(180deg, #fffdfb, #faf6f0);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-soft);
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        }

        .profile-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-medium);
            border-color: rgba(143, 29, 29, 0.18);
        }

        .tabs-wrap {
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--border-soft);
            box-shadow: var(--shadow-soft);
            background: linear-gradient(180deg, #fffdfb, #faf6f0);
        }

        .tab-button {
            position: relative;
            padding: 1rem 1.4rem;
            color: var(--text-secondary);
            font-weight: 700;
            transition: all 0.25s ease;
            background: transparent;
        }

        .tab-button:hover {
            color: var(--primary-red);
            background: rgba(143, 29, 29, 0.04);
        }

        .tab-active {
            color: var(--primary-red) !important;
            background: linear-gradient(180deg, rgba(143, 29, 29, 0.08), rgba(212, 175, 55, 0.03));
        }

        .tab-active::after {
            content: '';
            position: absolute;
            left: 1rem;
            right: 1rem;
            bottom: 0;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--accent-gold), var(--primary-red));
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .section-title i {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-red);
            background: rgba(212, 175, 55, 0.12);
            box-shadow: inset 0 0 0 1px rgba(212, 175, 55, 0.18);
        }

        .label-text {
            color: #4b3f3b;
            font-size: 0.875rem;
            font-weight: 700;
            margin-bottom: 0.55rem;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            border: 1px solid #d9cec3;
            background: #fffdfa;
            color: #2f2522;
            border-radius: 14px;
            padding: 0.82rem 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease, background 0.2s ease;
            box-shadow: inset 0 1px 2px rgba(91, 15, 15, 0.03);
        }

        .form-input:hover,
        .form-select:hover,
        .form-textarea:hover {
            border-color: #cdb8a6;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 4px rgba(143, 29, 29, 0.10);
            background: #fffefd;
        }

        .form-input[disabled],
        .form-select[disabled],
        .form-textarea[disabled],
        .form-input[readonly] {
            background: #f3eee8;
            color: #8a7d77;
            cursor: not-allowed;
        }

        .helper-text {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.45rem;
        }

        .error-text {
            color: #b91c1c;
            font-size: 0.8rem;
            margin-top: 0.45rem;
            font-weight: 600;
        }

        .btn-primary,
        .btn-danger,
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            border-radius: 14px;
            padding: 0.88rem 1.35rem;
            font-weight: 700;
            transition: all 0.25s ease;
            box-shadow: 0 10px 20px rgba(91, 15, 15, 0.08);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(91, 15, 15, 0.24);
        }

        .btn-secondary {
            background: #fffdfa;
            color: var(--text-dark);
            border: 1px solid #d9cec3;
        }

        .btn-secondary:hover {
            background: #f8f1e8;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #b91c1c 100%);
            color: white;
        }

        .status-card {
            border-radius: 16px;
            border: 1px solid transparent;
            box-shadow: var(--shadow-soft);
        }

        .status-success {
            background: #eefaf2;
            color: #166534;
            border-color: #bfe5c8;
        }

        .status-warning {
            background: #fff5e8;
            color: #9a3412;
            border-color: #f3d2aa;
        }

        .checkbox-accent {
            accent-color: var(--primary-red);
        }

        .feature-callout {
            border-radius: 16px;
            background: linear-gradient(180deg, #fffdfb, #f9f3eb);
            border: 1px solid var(--border-soft);
        }

        .footer-shell {
            background: linear-gradient(180deg, #4d1212 0%, #3f0e0e 100%);
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        @media (max-width: 768px) {
            .tab-button {
                width: 100%;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="profile-container">
       <nav class="profile-navbar py-4">
            <div class="container mx-auto px-4 md:px-6 flex justify-between items-center gap-4">
                <div class="flex items-center gap-4 md:gap-6">
                    <div class="brand-badge">
                        <span class="brand-text">my.OGC</span>
                    </div>

                    <div class="hidden md:flex items-center gap-2">
                        <a href="{{ route('dashboard') }}" class="glass-link">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to Dashboard</span>
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="hidden sm:block text-white/90 font-medium">
                        Welcome, {{ Auth::user()->first_name }}
                    </div>
                    <a href="{{ route('dashboard') }}" class="w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 border border-white/15 text-white inline-flex items-center justify-center transition">
                        <i class="fas fa-home"></i>
                    </a>
                </div>
            </div>
        </nav>

        <div class="container mx-auto px-4 md:px-6 py-8 max-w-6xl">
            <div class="hero-card section-shell rounded-[24px] p-6 md:p-8 mb-6">
                <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div>
                        <div class="hero-badge-soft mb-3">Account Center</div>
                        <h1 class="text-3xl md:text-4xl font-extrabold leading-tight">Profile Settings</h1>
                        <p class="text-white/80 mt-3 max-w-2xl">
                            Manage your account information, role details, and password with the same warm maroon,
                            cream, and gold experience used across the updated OGC pages.
                        </p>
                    </div>
                    <div class="hero-side-summary">
                        <div class="text-white text-lg font-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="text-white/75 text-sm mt-1 capitalize">{{ Auth::user()->role }}</div>
                    </div>
                </div>
            </div>

            <div class="tabs-wrap mb-6">
                <div class="flex flex-col md:flex-row">
                    <button id="personal-tab" class="tab-button tab-active">Personal Information</button>
                    <button id="role-tab" class="tab-button">{{ ucfirst(Auth::user()->role) }} Profile</button>
                    <button id="password-tab" class="tab-button">Change Password</button>
                </div>
            </div>

            @if(session('status'))
                <div class="mb-6 p-4 status-card {{ in_array(session('status'), ['profile-updated', 'password-updated', 'student-profile-updated', 'counselor-profile-updated']) ? 'status-success' : 'status-warning' }}">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        @switch(session('status'))
                            @case('profile-updated')
                                Profile information updated successfully.
                                @break
                            @case('password-updated')
                                Password updated successfully.
                                @break
                            @case('student-profile-updated')
                                Student profile updated successfully.
                                @break
                            @case('counselor-profile-updated')
                                Counselor profile updated successfully.
                                @break
                            @default
                                Profile updated successfully.
                        @endswitch
                    </div>
                </div>
            @endif

            <div id="personal-content" class="tab-content">
                <div class="p-6 md:p-7 profile-card">
                    <h2 class="section-title"><i class="fas fa-user"></i> Personal Information</h2>

                    @php
                        $lockPersonalInfo = in_array(Auth::user()->role, ['student', 'counselor'], true);
                    @endphp

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block label-text">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('first_name') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="middle_name" class="block label-text">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('middle_name') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block label-text">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('last_name') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="birthdate" class="block label-text">Birthdate</label>
                                <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('birthdate') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="age" class="block label-text">Age</label>
                                <input type="number" id="age" name="age" value="{{ old('age', $user->age) }}" readonly class="form-input">
                                @error('age') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="sex" class="block label-text">Sex</label>
                                <select id="sex" name="sex" @if($lockPersonalInfo) disabled @endif class="form-select">
                                    <option value="">Select Sex</option>
                                    <option value="male" {{ old('sex', $user->sex) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('sex', $user->sex) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('sex', $user->sex) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sex') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="birthplace" class="block label-text">Birthplace</label>
                                <input type="text" id="birthplace" name="birthplace" value="{{ old('birthplace', $user->birthplace) }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('birthplace') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="religion" class="block label-text">Religion</label>
                                <input type="text" id="religion" name="religion" value="{{ old('religion', $user->religion) }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('religion') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="civil_status" class="block label-text">Civil Status</label>
                                <select id="civil_status" name="civil_status" @if($lockPersonalInfo) disabled @endif class="form-select">
                                    <option value="">Select Civil Status</option>
                                    <option value="single" {{ old('civil_status', $user->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('civil_status', $user->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('civil_status', $user->civil_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('civil_status', $user->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                @error('civil_status') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="citizenship" class="block label-text">Citizenship</label>
                                <input type="text" id="citizenship" name="citizenship" value="{{ old('citizenship', $user->citizenship) }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('citizenship') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block label-text">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('email') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone_number" class="block label-text">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" @if($lockPersonalInfo) disabled @endif class="form-input">
                                @error('phone_number') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block label-text">Address</label>
                                <textarea id="address" name="address" rows="3" @if($lockPersonalInfo) disabled @endif class="form-textarea">{{ old('address', $user->address) }}</textarea>
                                @error('address') <p class="error-text">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        @if(!$lockPersonalInfo)
                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div id="role-content" class="tab-content hidden">
                <div class="p-6 md:p-7 profile-card">
                    <h2 class="section-title"><i class="fas fa-id-badge"></i> {{ ucfirst(Auth::user()->role) }} Profile Information</h2>

                    @if(Auth::user()->role === 'student')
                        <form method="POST" action="{{ route('profile.student.update') }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="student_id" class="block label-text">Student ID</label>
                                    <input type="text" id="student_id" name="student_id" value="{{ old('student_id', $studentProfile->student_id ?? '') }}" disabled class="form-input">
                                    @error('student_id') <p class="error-text">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="year_level" class="block label-text">Year Level</label>
                                    <select id="year_level" name="year_level" disabled class="form-select">
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" {{ old('year_level', $studentProfile->year_level ?? '') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level', $studentProfile->year_level ?? '') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level', $studentProfile->year_level ?? '') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level', $studentProfile->year_level ?? '') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                        <option value="5th Year" {{ old('year_level', $studentProfile->year_level ?? '') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                                        <option value="Graduate" {{ old('year_level', $studentProfile->year_level ?? '') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    </select>
                                    @error('year_level') <p class="error-text">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="course" class="block label-text">Course</label>
                                    <input type="text" id="course" name="course" value="{{ old('course', $studentProfile->course ?? '') }}" disabled class="form-input" placeholder="e.g., Bachelor of Science in Computer Science">
                                    @error('course') <p class="error-text">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="college_id" class="block label-text">College</label>
                                    <select id="college_id" name="college_id" disabled class="form-select">
                                        <option value="">Select College</option>
                                        @foreach(\App\Models\College::all() as $college)
                                            <option value="{{ $college->id }}" {{ old('college_id', $studentProfile->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('college_id') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </form>

                    @elseif(Auth::user()->role === 'counselor')
                        <form method="POST" action="{{ route('profile.counselor.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            {{-- Profile Picture --}}
                            <div class="mb-6 flex flex-col sm:flex-row items-center gap-5">
                                <div class="relative flex-shrink-0">
                                    @if(Auth::user()->profile_picture)
                                        <img id="pic-preview"
                                             src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                             alt="Profile Picture"
                                             class="w-24 h-24 rounded-full object-cover border-4"
                                             style="border-color: var(--accent-gold);">
                                    @else
                                        <div id="pic-placeholder"
                                             class="w-24 h-24 rounded-full flex items-center justify-center text-3xl font-bold text-white"
                                             style="background: linear-gradient(135deg, var(--primary-red), var(--primary-red-dark));">
                                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                        </div>
                                        <img id="pic-preview" src="" alt="Preview" class="w-24 h-24 rounded-full object-cover border-4 hidden"
                                             style="border-color: var(--accent-gold);">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <label class="block label-text mb-1">Profile Picture</label>
                                    <label for="profile_picture"
                                           class="inline-flex items-center gap-2 cursor-pointer px-4 py-2 rounded-xl text-sm font-semibold transition"
                                           style="background: var(--bg-light); border: 1px solid var(--border-soft); color: var(--text-dark);">
                                        <i class="fas fa-camera"></i> Choose Photo
                                    </label>
                                    <input type="file" id="profile_picture" name="profile_picture"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           class="hidden" onchange="previewPic(this)">
                                    <p class="helper-text mt-1">JPG, PNG, GIF or WebP. Max 4MB.</p>
                                    @error('profile_picture') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="position" class="block label-text">Position</label>
                                    <input type="text" id="position" name="position" value="{{ old('position', $counselorProfile->position ?? '') }}" disabled class="form-input">
                                    @error('position') <p class="error-text">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="credentials" class="block label-text">Credentials</label>
                                    <input type="text" id="credentials" name="credentials" value="{{ old('credentials', $counselorProfile->credentials ?? '') }}" disabled class="form-input">
                                    @error('credentials') <p class="error-text">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="google_calendar_id" class="block label-text">Google Calendar ID</label>
                                    <input type="text" id="google_calendar_id" name="google_calendar_id" value="{{ old('google_calendar_id', $counselorProfile->google_calendar_id ?? '') }}" placeholder="e.g. counselor@yourdomain.com" class="form-input">
                                    <p class="helper-text">Used to sync booked appointments to your calendar.</p>
                                    @error('google_calendar_id') <p class="error-text">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="college_id" class="block label-text">Assigned College</label>
                                    <select id="college_id" name="college_id" disabled class="form-select">
                                        <option value="">Select College</option>
                                        @foreach(\App\Models\College::all() as $college)
                                            <option value="{{ $college->id }}" {{ old('college_id', $counselorProfile->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('college_id') <p class="error-text">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="flex items-center gap-3 rounded-2xl border border-[var(--border-soft)] bg-[#faf4ec] px-4 py-4">
                                        <input type="checkbox" name="is_head" value="1" {{ old('is_head', $counselorProfile->is_head ?? '') ? 'checked' : '' }} disabled class="checkbox-accent w-4 h-4">
                                        <span class="text-sm font-medium text-[var(--text-dark)]">Head Counselor</span>
                                    </label>
                                    @error('is_head') <p class="error-text">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> Save Counselor Profile
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <div id="password-content" class="tab-content hidden">
                <div class="p-6 md:p-7 profile-card">
                    <h2 class="section-title"><i class="fas fa-shield-alt"></i> Change Password</h2>

                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('patch')

                        <div class="space-y-6 max-w-md">
                            <div>
                                <label for="current_password" class="block label-text">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-input">
                                @error('current_password') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password" class="block label-text">New Password</label>
                                <input type="password" id="password" name="password" class="form-input">
                                @error('password') <p class="error-text">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block label-text">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input">
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="footer-shell text-white py-8 mt-12">
            <div class="container mx-auto px-6 text-center text-white/85">
                <p>&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        function previewPic(input) {
            const preview = document.getElementById('pic-preview');
            const placeholder = document.getElementById('pic-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tabs = {
                'personal-tab': 'personal-content',
                'role-tab': 'role-content',
                'password-tab': 'password-content'
            };

            function switchTab(activeTab) {
                Object.keys(tabs).forEach(tabId => {
                    document.getElementById(tabId).classList.remove('tab-active');
                    document.getElementById(tabs[tabId]).classList.add('hidden');
                });

                document.getElementById(activeTab).classList.add('tab-active');
                document.getElementById(tabs[activeTab]).classList.remove('hidden');
            }

            Object.keys(tabs).forEach(tabId => {
                document.getElementById(tabId).addEventListener('click', () => switchTab(tabId));
            });

            const birthdateInput = document.getElementById('birthdate');
            const ageInput = document.getElementById('age');

            if (birthdateInput && ageInput) {
                birthdateInput.addEventListener('change', function() {
                    if (this.value) {
                        const birthDate = new Date(this.value);
                        const today = new Date();
                        let age = today.getFullYear() - birthDate.getFullYear();
                        const monthDiff = today.getMonth() - birthDate.getMonth();

                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                            age--;
                        }

                        ageInput.value = age;
                    } else {
                        ageInput.value = '';
                    }
                });
            }

            const statusMessages = document.querySelectorAll('.status-card');
            statusMessages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-4px)';
                    setTimeout(() => {
                        if (message.parentNode) {
                            message.remove();
                        }
                    }, 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>