@extends('layouts.app')

@section('title', 'Create Event - OGC')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c; --maroon-800: #5c1a1a; --maroon-700: #7a2a2a;
        --gold-500: #c9a227; --gold-400: #d4af37; --bg-warm: #faf8f5;
        --border-soft: #e5e0db; --text-primary: #2c2420; --text-secondary: #6b5e57; --text-muted: #8b7e76;
    }
    .create-event-shell { position: relative; overflow: hidden; background: var(--bg-warm); min-height: 100vh; }
    .create-event-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25; }
    .create-event-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .create-event-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .section-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04); transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .section-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before, .section-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .hero-icon, .panel-icon, .section-icon { display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
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
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.25rem; }
    .panel-topline, .section-topline { position: absolute; inset-inline: 0; top: 0; }
    .panel-topline { height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .section-topline { height: 2.5px; background: linear-gradient(90deg, var(--maroon-700), var(--gold-400)); }
    .panel-header, .section-header {
        display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-soft);
    }
    .panel-icon, .section-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title, .section-title-text { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle, .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }
    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .input-field, .textarea-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field, .select-field { padding: 0.55rem 0.75rem; }
    .textarea-field { padding: 0.65rem 0.75rem; resize: vertical; }
    .input-field:focus, .textarea-field:focus, .select-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .helper-text { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.3rem; line-height: 1.5; }
    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }
    .alert-error { border-radius: 0.6rem; padding: 0.65rem 0.85rem; border-width: 1px; margin-bottom: 1rem; }
    .option-card {
        display: flex; align-items: flex-start; padding: 0.7rem 0.85rem; border-radius: 0.65rem;
        background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft); transition: all 0.2s ease;
    }
    .option-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }
    .file-upload-zone {
        border: 2px dashed var(--border-soft); border-radius: 0.75rem;
        background: rgba(250,248,245,0.5); transition: all 0.2s ease; cursor: pointer;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 1.5rem; text-align: center;
    }
    .file-upload-zone:hover { border-color: var(--gold-400); background: rgba(254,249,231,0.4); }
    .college-grid {
        max-height: 260px; overflow-y: auto; border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 0.75rem; background: rgba(255,255,255,0.5);
    }
    .college-item { display: flex; align-items: center; gap: 0.6rem; padding: 0.35rem 0; }
    .custom-radio, .custom-checkbox {
        appearance: none; -webkit-appearance: none; width: 1rem; height: 1rem;
        border: 1px solid var(--border-soft); border-radius: 0.25rem; background: white;
        cursor: pointer; position: relative; transition: all 0.2s; flex-shrink: 0;
    }
    .custom-radio { border-radius: 50%; }
    .custom-radio:checked, .custom-checkbox:checked { background: var(--maroon-700); border-color: var(--maroon-700); }
    .custom-radio:checked::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
        width: 0.35rem; height: 0.35rem; background: white; border-radius: 50%;
    }
    .custom-checkbox:checked::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%) rotate(45deg);
        width: 0.28rem; height: 0.55rem; border: solid white; border-width: 0 2px 2px 0;
    }
    .custom-control-label { font-size: 0.8rem; color: var(--text-secondary); cursor: pointer; user-select: none; }
    .form-action-primary, .form-action-secondary {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; padding: 0.55rem 0.85rem;
    }
    .form-action-primary {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .form-action-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .form-action-secondary { background: #f5f0eb; color: var(--text-secondary); border: 1px solid var(--border-soft); }
    .form-action-secondary:hover { background: #e5e0db; }
    @media (max-width: 639px) {
        .panel-header, .section-header { padding: 0.75rem 1rem; }
        .form-action-primary, .form-action-secondary { width: 100%; justify-content: center; }
    }
</style>

<div class="min-h-screen create-event-shell">
    <div class="create-event-glow one"></div>
    <div class="create-event-glow two"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon"><i class="fas fa-calendar-plus text-base sm:text-lg"></i></div>
                        <div class="min-w-0">
                            <div class="hero-badge"><span class="hero-badge-dot"></span> Event Creation</div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Create New Event</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">Fill in the details below to create a new mental health event.</p>
                        </div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center gap-3 p-4">
                        <div class="summary-icon flex-shrink-0"><i class="fas fa-wand-magic-sparkles text-sm"></i></div>
                        <div class="text-center sm:text-left min-w-0">
                            <p class="summary-label">Publishing Flow</p>
                            <p class="summary-value">Draft your event</p>
                            <p class="summary-subtext">Configure schedule, audience, and options before publishing.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-error bg-[#fdf2f2] border-[#b91c1c]/30 text-[#b91c1c]">
                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <div class="panel-card">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-pen-ruler text-[9px] sm:text-xs"></i></div>
                <div>
                    <h2 class="panel-title">Event Form</h2>
                    <p class="panel-subtitle hidden sm:block">Complete each section carefully to create a polished event listing.</p>
                </div>
            </div>

            <div class="p-3 sm:p-4">
                <form method="POST" action="{{ route('counselor.events.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- ── Event Details ── --}}
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-circle-info text-[9px] sm:text-xs"></i></div>
                            <div>
                                <h3 class="panel-title">Event Details</h3>
                                <p class="section-subtitle hidden sm:block">Set the main event identity and attendance settings.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">

                            {{-- Image --}}
                            <div class="md:col-span-2">
                                <label class="field-label">Event Image (Optional)</label>
                                <label for="image" class="file-upload-zone group">
                                    <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                                    <i class="fas fa-cloud-upload-alt text-2xl text-[var(--text-muted)] mb-1 group-hover:text-[var(--gold-500)] transition"></i>
                                    <p class="text-xs font-semibold text-[var(--text-primary)]"><span class="text-[var(--maroon-700)]">Click to upload</span> or drag and drop</p>
                                    <p class="text-[10px] text-[var(--text-muted)] mt-0.5">PNG, JPG, GIF (MAX. 2MB)</p>
                                </label>
                                <div id="image-preview" class="mt-3 hidden">
                                    <img id="preview" class="w-full h-48 object-cover rounded-lg shadow border border-[var(--border-soft)]">
                                </div>
                                @error('image')<p class="error-text">{{ $message }}</p>@enderror
                            </div>

                            {{-- Title --}}
                            <div class="md:col-span-2">
                                <label for="title" class="field-label">Event Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}"
                                       class="input-field" placeholder="Enter event title" required>
                                @error('title')<p class="error-text">{{ $message }}</p>@enderror
                            </div>

                            {{-- Type --}}
                            <div>
                                <label for="type" class="field-label">Event Type *</label>
                                <select id="type" name="type" class="select-field" required>
                                    <option value="">Select Event Type</option>
                                    @foreach(['webinar'=>'Webinar','workshop'=>'Workshop','seminar'=>'Seminar','activity'=>'Activity','conference'=>'Conference'] as $val=>$label)
                                        <option value="{{ $val }}" {{ old('type')==$val?'selected':'' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')<p class="error-text">{{ $message }}</p>@enderror
                            </div>

                            {{-- Max Attendees --}}
                            <div>
                                <label for="max_attendees" class="field-label">Max Attendees <span class="text-[var(--text-muted)] normal-case">(Optional)</span></label>
                                <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees') }}"
                                       class="input-field" placeholder="Leave empty for unlimited" min="1">
                                @error('max_attendees')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── Date & Time ── --}}
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-calendar-days text-[9px] sm:text-xs"></i></div>
                            <div>
                                <h3 class="panel-title">Date and Time</h3>
                                <p class="section-subtitle hidden sm:block">Set the event date range and start/end times.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label for="event_start_date" class="field-label">Start Date *</label>
                                <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date') }}" class="input-field" required>
                                @error('event_start_date')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="event_end_date" class="field-label">End Date *</label>
                                <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date') }}" class="input-field" required>
                                @error('event_end_date')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="start_time" class="field-label">Start Time *</label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" class="input-field" required>
                                @error('start_time')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="end_time" class="field-label">End Time *</label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" class="input-field" required>
                                @error('end_time')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── Location & Description ── --}}
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-location-dot text-[9px] sm:text-xs"></i></div>
                            <div>
                                <h3 class="panel-title">Location and Description</h3>
                                <p class="section-subtitle hidden sm:block">Where the event takes place and what attendees should expect.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 grid grid-cols-1 gap-3 sm:gap-4">
                            <div>
                                <label for="location" class="field-label">Location *</label>
                                <input type="text" id="location" name="location" value="{{ old('location') }}"
                                       class="input-field" placeholder="e.g., Room 101, Online, Main Hall" required>
                                @error('location')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="description" class="field-label">Description *</label>
                                <textarea id="description" name="description" rows="4" class="textarea-field"
                                          placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description') }}</textarea>
                                @error('description')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── Attending Counselors ── --}}
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fab fa-google text-[9px] sm:text-xs"></i></div>
                            <div>
                                <h3 class="panel-title">Attending Counselors (Google Calendar)</h3>
                                <p class="section-subtitle hidden sm:block">Selected counselors will have this event added to their Google Calendar.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            @php $oldCounselorIds = collect(old('counselor_ids', []))->map('intval')->filter()->all(); @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                @foreach($counselors as $counselor)
                                    @php $isChecked = in_array($counselor->id, $oldCounselorIds); @endphp
                                    <label class="option-card cursor-pointer gap-2" style="padding:0.5rem 0.75rem;">
                                        <input type="checkbox" name="counselor_ids[]" value="{{ $counselor->id }}"
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
                            @error('counselor_ids')<p class="error-text mt-2">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- ── Target Audience ── --}}
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-users-viewfinder text-[9px] sm:text-xs"></i></div>
                            <div>
                                <h3 class="panel-title">Target Audience</h3>
                                <p class="section-subtitle hidden sm:block">Choose which colleges and year levels can access this event.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 space-y-4">

                            {{-- College availability --}}
                            <div>
                                <label class="field-label">College Availability *</label>
                                <div class="space-y-2 mt-1">
                                    <label class="option-card cursor-pointer gap-2" style="padding:0.5rem 0.75rem;">
                                        <input type="radio" id="for_all_colleges_true" name="for_all_colleges" value="1"
                                               {{ old('for_all_colleges', true) ? 'checked' : '' }}
                                               class="custom-radio flex-shrink-0">
                                        <span class="text-xs font-medium text-[#4a3f3a]">All Colleges — available to students from all colleges</span>
                                    </label>
                                    <label class="option-card cursor-pointer gap-2" style="padding:0.5rem 0.75rem;">
                                        <input type="radio" id="for_all_colleges_false" name="for_all_colleges" value="0"
                                               {{ old('for_all_colleges') === '0' ? 'checked' : '' }}
                                               class="custom-radio flex-shrink-0">
                                        <span class="text-xs font-medium text-[#4a3f3a]">Specific Colleges — choose which colleges can see this event</span>
                                    </label>
                                </div>
                                <div id="colleges_selection" class="{{ old('for_all_colleges') === '0' ? 'mt-3' : 'hidden mt-3' }}">
                                    <div class="college-grid">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                                            @foreach($colleges as $college)
                                                <div class="college-item">
                                                    <input type="checkbox" id="college_{{ $college->id }}" name="colleges[]"
                                                           value="{{ $college->id }}"
                                                           {{ in_array($college->id, old('colleges', [])) ? 'checked' : '' }}
                                                           class="custom-checkbox">
                                                    <label for="college_{{ $college->id }}" class="custom-control-label">{{ $college->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @error('colleges')<p class="error-text">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            {{-- Year level --}}
                            <div>
                                <label class="field-label">Year Level Availability</label>
                                <div class="space-y-2 mt-1">
                                    <label class="option-card cursor-pointer gap-2" style="padding:0.5rem 0.75rem;">
                                        <input type="radio" id="for_all_year_levels_true" name="for_all_year_levels" value="1"
                                               {{ empty(old('year_levels', [])) ? 'checked' : '' }}
                                               class="custom-radio flex-shrink-0">
                                        <span class="text-xs font-medium text-[#4a3f3a]">All Year Levels</span>
                                    </label>
                                    <label class="option-card cursor-pointer gap-2" style="padding:0.5rem 0.75rem;">
                                        <input type="radio" id="for_all_year_levels_false" name="for_all_year_levels" value="0"
                                               {{ !empty(old('year_levels', [])) ? 'checked' : '' }}
                                               class="custom-radio flex-shrink-0">
                                        <span class="text-xs font-medium text-[#4a3f3a]">Specific Year Levels</span>
                                    </label>
                                </div>
                                <div id="year_levels_selection" class="{{ !empty(old('year_levels', [])) ? 'mt-3' : 'hidden mt-3' }}">
                                    @php $oldYearLevels = old('year_levels', []); @endphp
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
                                        @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $yl)
                                        <label class="option-card cursor-pointer" style="gap:0.5rem;padding:0.5rem 0.75rem;">
                                            <input type="checkbox" name="year_levels[]" value="{{ $yl }}"
                                                   {{ in_array($yl, $oldYearLevels) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-[#7a2a2a] border-[#e5e0db] rounded focus:ring-[#7a2a2a] flex-shrink-0">
                                            <span class="text-xs font-medium text-[#4a3f3a]">{{ $yl }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @error('year_levels')<p class="error-text">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Event Options ── --}}
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon"><i class="fas fa-toggle-on text-[9px] sm:text-xs"></i></div>
                            <div>
                                <h3 class="panel-title">Event Options</h3>
                                <p class="section-subtitle hidden sm:block">Required attendance and activation settings.</p>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4 space-y-3">
                            <div class="option-card">
                                <input type="checkbox" id="is_required" name="is_required" value="1"
                                       {{ old('is_required') ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#7a2a2a] border-[#e5e0db] rounded focus:ring-[#7a2a2a] mt-0.5 flex-shrink-0">
                                <label for="is_required" class="ml-3 text-xs font-medium text-[#4a3f3a]">
                                    <span class="font-semibold text-[#7a2a2a]">Required Event</span> — students from selected colleges must attend
                                </label>
                            </div>
                            <div class="option-card">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#7a2a2a] border-[#e5e0db] rounded focus:ring-[#7a2a2a] mt-0.5 flex-shrink-0">
                                <label for="is_active" class="ml-3 text-xs font-medium text-[#4a3f3a]">Activate this event immediately</label>
                            </div>
                            @error('is_active')<p class="error-text">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-2">
                        <a href="{{ route('counselor.events.index') }}" class="form-action-secondary text-center">Cancel</a>
                        <button type="submit" class="form-action-primary">
                            <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i> Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // College toggle
    const allCollegesRadio = document.getElementById('for_all_colleges_true');
    const specificCollegesRadio = document.getElementById('for_all_colleges_false');
    const collegesSelection = document.getElementById('colleges_selection');
    function toggleColleges() {
        collegesSelection.classList.toggle('hidden', !specificCollegesRadio.checked);
    }
    allCollegesRadio.addEventListener('change', toggleColleges);
    specificCollegesRadio.addEventListener('change', toggleColleges);
    toggleColleges();

    // Year level toggle
    const allYLRadio = document.getElementById('for_all_year_levels_true');
    const specificYLRadio = document.getElementById('for_all_year_levels_false');
    const ylSelection = document.getElementById('year_levels_selection');
    function toggleYearLevels() {
        ylSelection.classList.toggle('hidden', !specificYLRadio.checked);
        if (!specificYLRadio.checked) {
            ylSelection.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        }
    }
    allYLRadio.addEventListener('change', toggleYearLevels);
    specificYLRadio.addEventListener('change', toggleYearLevels);

    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const preview = document.getElementById('preview');
    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.addEventListener('load', function () {
                preview.setAttribute('src', this.result);
                imagePreview.classList.remove('hidden');
            });
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    });
});
</script>
@endsection
