@extends('layouts.app')

@section('title', 'Create Event - OGC')

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

    .edit-event-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .edit-event-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .edit-event-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .edit-event-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .section-card, .info-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .section-card:hover, .info-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .panel-card::before, .section-card::before, .info-card::before {
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

    .panel-topline, .section-topline { position: absolute; inset-inline: 0; top: 0; }
    .panel-topline { height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .section-topline { height: 2.5px; background: linear-gradient(90deg, var(--maroon-700), var(--gold-400)); }

    .panel-header, .section-header {
        display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-soft)/60;
    }
    .panel-icon, .section-icon {
        width: 2rem; height: 2rem; border-radius: 0.6rem;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
    }
    .panel-title, .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle, .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .input-field, .textarea-field, .select-field, .form-input {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field, .select-field { padding: 0.55rem 0.75rem; }
    .textarea-field { padding: 0.65rem 0.75rem; resize: vertical; }
    .input-field:focus, .textarea-field:focus, .select-field:focus, .form-input:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .helper-text { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.3rem; line-height: 1.5; }
    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }

    .alert-success, .alert-error { border-radius: 0.6rem; padding: 0.65rem 0.85rem; border-width: 1px; margin-bottom: 1rem; }

    .radio-group { display: flex; flex-direction: column; gap: 0.75rem; }
    .radio-option, .checkbox-option, .option-card {
        display: flex; align-items: flex-start; gap: 0.5rem;
        padding: 0.5rem; border-radius: 0.5rem;
        transition: background-color 0.15s ease;
    }
    .radio-option:hover, .checkbox-option:hover { background: rgba(254,249,231,0.4); }

    .radio-option input, .checkbox-option input, .option-card input[type="checkbox"] {
        margin-top: 0.15rem; width: 1rem; height: 1rem;
        accent-color: var(--maroon-700); cursor: pointer; flex-shrink: 0;
    }
    .radio-option label, .checkbox-option label, .option-card label {
        font-size: 0.8rem; color: var(--text-secondary); cursor: pointer; line-height: 1.4;
    }

    .college-grid {
        display: grid; grid-template-columns: repeat(1, 1fr); gap: 0.5rem;
        max-height: 12rem; overflow-y: auto; padding: 0.75rem;
        border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9);
    }
    @media (min-width: 768px) { .college-grid { grid-template-columns: repeat(2, 1fr); } }

    .option-card {
        display: flex; align-items: flex-start; padding: 0.7rem 0.85rem; border-radius: 0.65rem;
        background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft);
        transition: all 0.2s ease;
    }
    .option-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }

    .form-action-primary, .form-action-secondary, .back-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;
        padding: 0.55rem 0.85rem;
    }
    .form-action-primary {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .form-action-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .form-action-secondary, .back-btn {
        background: #ffffff; color: var(--text-secondary); border: 1px solid var(--border-soft);
        box-shadow: 0 2px 6px rgba(44,36,32,0.03);
    }
    .form-action-secondary:hover, .back-btn:hover { background: #f5f0eb; }

    .info-grid-label {
        display: block; font-size: 0.6rem; font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--text-muted); margin-bottom: 0.25rem;
    }
    .info-grid-value { color: var(--text-primary); font-size: 0.8rem; font-weight: 600; }

    @media (max-width: 639px) {
        .panel-header, .section-header { padding: 0.75rem 1rem; }
        .input-field, .select-field { padding: 0.5rem 0.7rem; font-size: 0.85rem; }
        .textarea-field { padding: 0.6rem 0.7rem; }
        .form-action-primary, .form-action-secondary, .back-btn { width: 100%; justify-content: center; }
        .flex-col.sm\:flex-row > * + * { margin-left: 0; margin-top: 0.5rem; }
        .info-grid-value { font-size: 0.85rem; }
    }
</style>

<div class="min-h-screen edit-event-shell">
    <div class="edit-event-glow one"></div>
    <div class="edit-event-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-pen-to-square text-base sm:text-lg"></i>
                        </div>

                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Event Creation
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Create Event</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Fill in the details below to create a new mental health event.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-days text-sm"></i>
                            </div>
                            <div>
                                <p class="summary-label">Event Management</p>
                                <p class="summary-value">Draft Event</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('counselor.events.index') }}"
                               class="back-btn px-3 py-2 whitespace-nowrap text-xs sm:text-sm rounded-lg">
                                <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert-error bg-[#fdf2f2] border-[#b91c1c]/30 text-[#b91c1c]">
                <div class="flex items-start">
                    <i class="fas fa-circle-exclamation mr-2 text-rose-500 mt-0.5 text-sm"></i>
                    <ul class="list-disc list-inside text-[10px] sm:text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Event Form -->
        <form method="POST" action="{{ route('counselor.events.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-5 sm:gap-6 items-start">
                <!-- Left Column -->
                <div class="space-y-5 sm:space-y-6">

                    {{-- Event Image --}}
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-image text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Event Image</h3>
                                <p class="section-subtitle hidden sm:block">Upload or replace the event cover photo.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row gap-5 items-start">
                                {{-- Current image preview --}}
                                <div class="flex-shrink-0">
                                    <div class="w-full sm:w-48 h-32 rounded-lg overflow-hidden border border-[#e5e0db] bg-[#f5f0eb] relative">
                                        <div class="w-full h-full flex flex-col items-center justify-center text-[#8b7e76]" id="event-img-placeholder">
                                            <i class="fas fa-image text-2xl mb-1 opacity-40"></i>
                                            <span class="text-xs">No image</span>
                                        </div>
                                        <img src="" alt="" class="w-full h-full object-contain hidden bg-black/5" id="event-img-preview">
                                    </div>
                                </div>

                                {{-- Upload controls --}}
                                <div class="flex-1 min-w-0">
                                    <label class="field-label">
                                        Add Image
                                    </label>
                                    <input type="file"
                                           name="image"
                                           id="event-image-input"
                                           accept="image/jpeg,image/png,image/jpg,image/gif"
                                           class="input-field mt-1"
                                           onchange="previewEventImage(this)">
                                    <p class="helper-text mt-1">JPG, PNG or GIF · Max 10MB.</p>
                                    @error('image')
                                        <p class="error-text mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-circle-info text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Event Details</h3>
                                <p class="section-subtitle hidden sm:block">Update the event title, type, and attendance settings.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Event Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="field-label">Event Title <span class="text-[#b91c1c]">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}"
                                       class="input-field form-input"
                                       placeholder="Enter event title" required>
                                @error('title')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Event Type -->
                            <div>
                                <label for="type" class="field-label">Event Type <span class="text-[#b91c1c]">*</span></label>
                                <select id="type" name="type"
                                        class="select-field" required>
                                    <option value="">Select Event Type</option>
                                    <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="seminar" {{ old('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                    <option value="webinar" {{ old('type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                    <option value="conference" {{ old('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="activity" {{ old('type') == 'activity' ? 'selected' : '' }}>Activity</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Attendees -->
                            <div>
                                <label for="max_attendees" class="field-label">Max Attendees <span class="text-[#8b7e76] text-[10px]">(Optional)</span></label>
                                <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees') }}"
                                       class="input-field form-input"
                                       placeholder="Leave empty for unlimited" min="1">
                                @error('max_attendees')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="event_start_date" class="field-label">Start Date <span class="text-[#b91c1c]">*</span></label>
                                <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date') }}"
                                       class="input-field form-input" required>
                                @error('event_start_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="event_end_date" class="field-label">End Date <span class="text-[#b91c1c]">*</span></label>
                                <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date') }}"
                                       class="input-field form-input" required>
                                @error('event_end_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label for="start_time" class="field-label">Start Time <span class="text-[#b91c1c]">*</span></label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                                       class="input-field form-input" required>
                                @error('start_time')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div>
                                <label for="end_time" class="field-label">End Time <span class="text-[#b91c1c]">*</span></label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                                       class="input-field form-input" required>
                                @error('end_time')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <label for="location" class="field-label">Location <span class="text-[#b91c1c]">*</span></label>
                                <input type="text" id="location" name="location" value="{{ old('location') }}"
                                       class="input-field form-input"
                                       placeholder="Enter event location (e.g., Room 101, Online, etc.)" required>
                                @error('location')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="field-label">Description <span class="text-[#b91c1c]">*</span></label>
                                <textarea id="description" name="description" rows="4"
                                          class="textarea-field form-input"
                                          placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="md:col-span-2">
                                <div class="option-card">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#7a2a2a] border-[#e5e0db] rounded focus:ring-[#7a2a2a] mt-0.5 flex-shrink-0">
                                    <label for="is_active" class="ml-3 text-xs font-medium text-[#4a3f3a]">
                                        Activate this event immediately
                                    </label>
                                </div>
                                @error('is_active')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="space-y-5 sm:space-y-6">
                    <!-- Attending Counselors -->
                    <div class="section-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fab fa-google text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Attending Counselors (Google Calendar)</h3>
                                <p class="section-subtitle hidden sm:block">Selected counselors will have this event synced to their Google Calendar.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            @php $oldCounselorIds = old('counselor_ids') !== null
                                ? collect(old('counselor_ids'))->map('intval')->filter()->all()
                                : []; @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                @foreach($counselors as $counselor)
                                    @php $isChecked = in_array($counselor->id, $oldCounselorIds); @endphp
                                    <label class="option-card cursor-pointer gap-2" style="padding:0.5rem 0.75rem;">
                                        <input type="checkbox"
                                               name="counselor_ids[]"
                                               value="{{ $counselor->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               class="w-4 h-4 text-[#7a2a2a] border-[#e5e0db] rounded focus:ring-[#7a2a2a] flex-shrink-0">
                                        <span class="text-xs font-medium text-[#4a3f3a]">
                                            {{ trim($counselor->user->first_name . ' ' . $counselor->user->last_name) }}
                                            @if($counselor->college_names)
                                                <span class="text-[10px] text-[#8b7e76]">({{ $counselor->college_names }})</span>
                                            @endif
                                            @if(!$counselor->google_calendar_id)
                                                <span class="text-[10px] text-[#b45309]">(no calendar)</span>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('counselor_ids')
                                <p class="error-text mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- College & Year Level -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-users-viewfinder text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Target Audience</h3>
                                <p class="section-subtitle hidden sm:block">Colleges and year levels that can access this event.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 grid grid-cols-1 gap-3 sm:gap-4">
                            <!-- College Targeting -->
                            <div>
                                <label class="field-label">College Availability <span class="text-[#b91c1c]">*</span></label>
                                <div class="mb-4 radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="for_all_colleges_true" name="for_all_colleges" value="1"
                                               {{ old('for_all_colleges', true) ? 'checked' : '' }}>
                                        <label for="for_all_colleges_true">
                                            <strong>All Colleges</strong> — Event visible to students from all colleges
                                        </label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="for_all_colleges_false" name="for_all_colleges" value="0"
                                               {{ old('for_all_colleges') === '0' ? 'checked' : '' }}>
                                        <label for="for_all_colleges_false">
                                            <strong>Specific Colleges</strong> — Choose which colleges can see this event
                                        </label>
                                    </div>
                                </div>

                                <!-- College Selection -->
                                <div id="colleges_selection" class="{{ old('for_all_colleges', true) ? 'hidden' : '' }}">
                                    <label class="field-label">Select Colleges <span class="text-[#b91c1c]">*</span></label>
                                    <div class="college-grid">
                                        @foreach($colleges as $college)
                                            <div class="checkbox-option">
                                                <input type="checkbox" id="college_{{ $college->id }}" name="colleges[]"
                                                       value="{{ $college->id }}"
                                                       {{ in_array($college->id, old('colleges', [])) ? 'checked' : '' }}>
                                                <label for="college_{{ $college->id }}">{{ $college->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('colleges')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Year Level Targeting -->
                            <div>
                                <label class="field-label">Year Level Availability <span class="text-[#b91c1c]">*</span></label>
                                <div class="mb-4 radio-group">
                                    <div class="radio-option">
                                        <input type="radio" id="for_all_year_levels_true" name="for_all_year_levels" value="1"
                                               {{ empty(old('year_levels', [])) ? 'checked' : '' }}>
                                        <label for="for_all_year_levels_true">
                                            <strong>All Year Levels</strong> — Visible to students from all year levels
                                        </label>
                                    </div>
                                    <div class="radio-option">
                                        <input type="radio" id="for_all_year_levels_false" name="for_all_year_levels" value="0"
                                               {{ !empty(old('year_levels', [])) ? 'checked' : '' }}>
                                        <label for="for_all_year_levels_false">
                                            <strong>Specific Year Levels</strong> — Choose which year levels can see this event
                                        </label>
                                    </div>
                                </div>

                                <div id="year_levels_selection" class="{{ !empty(old('year_levels', [])) ? '' : 'hidden' }}">
                                    <label class="field-label">Select Year Levels <span class="text-[#b91c1c]">*</span></label>
                                    <div class="college-grid" style="max-height:none;">
                                        @php $oldYearLevels = old('year_levels', []); @endphp
                                        @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $yl)
                                        <div class="checkbox-option">
                                            <input type="checkbox" id="yl_ann_{{ Str::slug($yl) }}" name="year_levels[]"
                                                   value="{{ $yl }}"
                                                   {{ in_array($yl, $oldYearLevels) ? 'checked' : '' }}>
                                            <label for="yl_ann_{{ Str::slug($yl) }}">{{ $yl }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('year_levels')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="option-card mt-2">
                                <input type="checkbox" id="is_required" name="is_required" value="1"
                                       {{ old('is_required') ? 'checked' : '' }}>
                                <label for="is_required">
                                    <span class="font-semibold text-[#7a2a2a]">Required Event</span> — mandatory for selected colleges
                                </label>
                            </div>
                        </div>
                    </div>

                    </div>
            </div> <!-- End Grid -->

            <!-- Form Actions -->
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ route('counselor.events.index') }}"
                   class="form-action-secondary w-full sm:w-auto rounded-lg text-center" style="padding: 0.6rem 1.25rem;">
                    Cancel
                </a>
                <button type="submit"
                        class="form-action-primary w-full sm:w-auto rounded-lg" style="padding: 0.6rem 1.25rem;">
                    <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>
                    Create Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Add client-side validation and UX enhancements
    document.addEventListener('DOMContentLoaded', function() {
        const startDate = document.getElementById('event_start_date');
        const endDate = document.getElementById('event_end_date');
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        startDate.min = today;
        endDate.min = today;

        // Update end date min when start date changes
        startDate.addEventListener('change', function() {
            endDate.min = this.value;
            if (endDate.value && endDate.value < this.value) {
                endDate.value = this.value;
            }
        });

        // Validate time range
        function validateTimeRange() {
            if (startDate.value === endDate.value && startTime.value && endTime.value) {
                if (startTime.value >= endTime.value) {
                    endTime.setCustomValidity('End time must be after start time');
                } else {
                    endTime.setCustomValidity('');
                }
            } else {
                endTime.setCustomValidity('');
            }
        }

        startTime.addEventListener('change', validateTimeRange);
        endTime.addEventListener('change', validateTimeRange);
        startDate.addEventListener('change', validateTimeRange);
        endDate.addEventListener('change', validateTimeRange);

        // College selection toggle
        const allCollegesRadio = document.getElementById('for_all_colleges_true');
        const specificCollegesRadio = document.getElementById('for_all_colleges_false');
        const collegesSelection = document.getElementById('colleges_selection');

        function toggleCollegesSelection() {
            if (specificCollegesRadio.checked) {
                collegesSelection.classList.remove('hidden');
            } else {
                collegesSelection.classList.add('hidden');
                // Uncheck all college checkboxes when "all colleges" is selected
                document.querySelectorAll('input[name="colleges[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
        }

        if (allCollegesRadio && specificCollegesRadio) {
            allCollegesRadio.addEventListener('change', toggleCollegesSelection);
            specificCollegesRadio.addEventListener('change', toggleCollegesSelection);
            toggleCollegesSelection();
        }

        // Year level selection toggle
        const allYearLevelsRadio = document.getElementById('for_all_year_levels_true');
        const specificYearLevelsRadio = document.getElementById('for_all_year_levels_false');
        const yearLevelsSelection = document.getElementById('year_levels_selection');

        function toggleYearLevelsSelection() {
            if (specificYearLevelsRadio.checked) {
                yearLevelsSelection.classList.remove('hidden');
            } else {
                yearLevelsSelection.classList.add('hidden');
                document.querySelectorAll('input[name="year_levels[]"]').forEach(cb => cb.checked = false);
            }
        }

        if (allYearLevelsRadio && specificYearLevelsRadio) {
            allYearLevelsRadio.addEventListener('change', toggleYearLevelsSelection);
            specificYearLevelsRadio.addEventListener('change', toggleYearLevelsSelection);
            toggleYearLevelsSelection();
        }
    });

    function previewEventImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('event-img-preview');
                const placeholder = document.getElementById('event-img-placeholder');
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection
