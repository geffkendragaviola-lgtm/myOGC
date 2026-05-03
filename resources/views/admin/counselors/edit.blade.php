@extends('layouts.admin')

@section('title', 'Edit Counselor - Admin Panel')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c; --maroon-800: #5c1a1a; --maroon-700: #7a2a2a;
        --gold-500: #c9a227; --gold-400: #d4af37;
        --bg-warm: #faf8f5; --border-soft: #e5e0db;
        --text-primary: #2c2420; --text-secondary: #6b5e57; --text-muted: #8b7e76;
    }
    .edit-shell { position: relative; overflow: hidden; background: var(--bg-warm); min-height: 100vh; }
    .edit-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25; }
    .edit-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .edit-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .summary-card, .glass-alert {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .section-card {
        position: relative; overflow: hidden; border-radius: 0.85rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.98);
        box-shadow: 0 4px 16px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .section-card::before, .glass-alert::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .hero-icon { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%); box-shadow: 0 4px 12px rgba(92,26,26,0.15); }
    .hero-badge { display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px; border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8); padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; color: var(--maroon-700); }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    .summary-card { border: 1px solid rgba(92,26,26,0.15); background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white; box-shadow: 0 4px 12px rgba(58,12,12,0.15); }
    .summary-card::before { content: ""; position: absolute; inset: 0; opacity: 0.15; background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none; }
    .summary-avatar { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0; font-weight: 700; }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.15rem; line-height: 1.25; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .section-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .section-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft); background: rgba(250,248,245,0.5); }
    .section-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; background: rgba(254,249,231,0.7); color: var(--maroon-700); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .field-input, .field-select, .field-textarea {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .field-textarea { padding: 0.65rem 0.75rem; resize: vertical; }
    .field-input:focus, .field-select:focus, .field-textarea:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    .checkbox-card { display: flex; align-items: flex-start; padding: 0.7rem 0.85rem; border-radius: 0.65rem; background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft); transition: all 0.2s ease; }
    .checkbox-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }

    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }
    .success-alert { background: rgba(236,253,245,0.8); border: 1px solid rgba(16,185,129,0.3); color: #059669; border-radius: 0.6rem; padding: 0.65rem 0.85rem; }
    .error-alert { background: rgba(254,242,242,0.8); border: 1px solid rgba(185,28,28,0.3); color: #b91c1c; border-radius: 0.6rem; padding: 0.65rem 0.85rem; }

    .primary-btn, .secondary-btn { border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; padding: 0.55rem 0.85rem; }
    .primary-btn { color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%); box-shadow: 0 4px 10px rgba(92,26,26,0.15); }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn { background: #ffffff; color: var(--text-secondary); border: 1px solid var(--border-soft); }
    .secondary-btn:hover { background: #f5f0eb; }

    @media (max-width: 639px) {
        .section-header { padding: 0.75rem 1rem; }
        .field-input, .field-select { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.6rem 1rem; }
    }
</style>

<div class="min-h-screen edit-shell">
    <div class="edit-glow one"></div>
    <div class="edit-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        {{-- Header --}}
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                        <div class="hero-icon flex-shrink-0">
                            <i class="fas fa-user-pen text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('admin.counselors') }}" class="inline-flex items-center text-[#7a2a2a] hover:text-[#5c1a1a] mb-3 font-medium text-xs sm:text-sm">
                                <i class="fas fa-arrow-left mr-1.5"></i> Back to Counselors
                            </a>
                            <div class="hero-badge"><span class="hero-badge-dot"></span>Counselor Profile Editor</div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Edit Counselor</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">Update counselor profile details, college assignment, and system settings.</p>
                        </div>
                    </div>
                </div>
                <div class="summary-card min-w-[240px] sm:min-w-[280px]">
                    <div class="relative h-full flex flex-col justify-center p-4 sm:p-5">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="summary-avatar flex-shrink-0">
                                {{ strtoupper(substr($counselor->user->first_name, 0, 1)) }}{{ strtoupper(substr($counselor->user->last_name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="summary-label">Counselor Profile</div>
                                <div class="summary-value truncate" title="{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}">{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}</div>
                                <div class="summary-subtext">{{ $counselor->position }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="success-alert mb-4 flex items-center gap-2 text-xs sm:text-sm">
                <i class="fas fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="error-alert mb-4 flex items-center gap-2 text-xs sm:text-sm">
                <i class="fas fa-circle-exclamation"></i>
                <div><div class="font-semibold">Please fix the errors below.</div></div>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.counselors.update', $counselor) }}">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 sm:gap-6 items-start">
                
                <!-- Left Column -->
                <div class="space-y-5 sm:space-y-6">

            {{-- User Information --}}
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-user text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">User Information</h2>
                        <p class="section-subtitle hidden sm:block">Basic identity and contact details.</p>
                    </div>
                </div>
                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div>
                        <label class="field-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $counselor->user->first_name) }}" class="field-input" required>
                        @error('first_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name', $counselor->user->middle_name) }}" class="field-input">
                        @error('middle_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $counselor->user->last_name) }}" class="field-input" required>
                        @error('last_name')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $counselor->user->email) }}" class="field-input" required>
                        @error('email')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $counselor->user->phone_number) }}" class="field-input">
                        @error('phone_number')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Birthdate</label>
                        <input type="date" name="birthdate" value="{{ old('birthdate', optional($counselor->user->birthdate)->format('Y-m-d')) }}" class="field-input">
                        @error('birthdate')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Sex</label>
                        <select name="sex" class="field-select">
                            <option value="">Select</option>
                            @foreach(['male','female','other'] as $s)
                                <option value="{{ $s }}" {{ old('sex', $counselor->user->sex) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        @error('sex')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Religion</label>
                        <input type="text" name="religion" value="{{ old('religion', $counselor->user->religion) }}" class="field-input">
                        @error('religion')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Address</label>
                        <textarea name="address" rows="2" class="field-textarea">{{ old('address', $counselor->user->address) }}</textarea>
                        @error('address')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

                </div> <!-- End Left Column -->

                <!-- Right Column -->
                <div class="space-y-5 sm:space-y-6">

            {{-- Counselor Record --}}
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-id-card text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">Counselor Record</h2>
                        <p class="section-subtitle hidden sm:block">Position, credentials, college assignment, and role settings.</p>
                    </div>
                </div>
                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div>
                        <label class="field-label">College</label>
                        <select name="college_id" class="field-select" required>
                            @foreach($colleges as $c)
                                <option value="{{ $c->id }}" {{ (string)old('college_id', $counselor->college_id) === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('college_id')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Position</label>
                        <input type="text" name="position" value="{{ old('position', $counselor->position) }}" class="field-input" required>
                        @error('position')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Credentials</label>
                        <input type="text" name="credentials" value="{{ old('credentials', $counselor->credentials) }}" class="field-input" required>
                        @error('credentials')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Specialization</label>
                        <input type="text" name="specialization" value="{{ old('specialization', $counselor->specialization) }}" class="field-input">
                        @error('specialization')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="field-label">Daily Booking Limit</label>
                        <input type="number" min="0" max="50" name="daily_booking_limit" value="{{ old('daily_booking_limit', $counselor->daily_booking_limit ?? 3) }}" class="field-input">
                        @error('daily_booking_limit')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div class="checkbox-card mt-0 md:mt-7">
                        <input type="hidden" name="is_head" value="0">
                        <input type="checkbox" name="is_head" value="1" id="is_head"
                               class="mt-0.5 mr-2 h-4 w-4 rounded border-gray-300 text-[#7a2a2a] focus:ring-[#7a2a2a] flex-shrink-0"
                               {{ old('is_head', $counselor->is_head) ? 'checked' : '' }}>
                        <label for="is_head" class="text-xs sm:text-sm font-medium text-[#4a3f3a]">Head Counselor</label>
                    </div>
                </div>
            </div>

            {{-- System Settings --}}
            <div class="section-card">
                <div class="section-topline"></div>
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-gear text-xs sm:text-sm"></i></div>
                    <div>
                        <h2 class="section-title">System Settings</h2>
                        <p class="section-subtitle hidden sm:block">Google Calendar integration and social links.</p>
                    </div>
                </div>
                <div class="p-3 sm:p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 md:gap-6">
                    <div class="md:col-span-2">
                        <label class="field-label">Google Calendar ID</label>
                        <input type="text" name="google_calendar_id" value="{{ old('google_calendar_id', $counselor->google_calendar_id) }}" class="field-input" placeholder="e.g. example@group.calendar.google.com">
                        @error('google_calendar_id')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="field-label">Facebook Link</label>
                        <input type="url" name="facebook_link" value="{{ old('facebook_link', $counselor->facebook_link) }}" class="field-input" placeholder="https://facebook.com/...">
                        @error('facebook_link')<p class="error-text">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

                </div> <!-- End Right Column -->
            </div> <!-- End Grid -->

            <div class="mt-6 flex justify-end">
                <button type="submit" class="primary-btn w-full sm:w-auto rounded-lg">
                    <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
