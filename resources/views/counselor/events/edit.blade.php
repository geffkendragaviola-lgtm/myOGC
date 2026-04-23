@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

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

    /* Base Layout & Glow */
    .event-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .event-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .event-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .event-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

    /* Glass Cards */
    .panel-card {
        position: relative; z-index: 1; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .panel-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    /* Typography & Headers */
    .page-header h1 { color: var(--text-primary); font-weight: 700; letter-spacing: -0.02em; }
    .page-header p { color: var(--text-secondary); }
    
    .section-title {
        font-size: 0.8rem; font-weight: 600; color: var(--text-primary);
        text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.35rem; display: block;
    }

    /* Form Elements */
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.85rem; padding: 0.6rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus, .textarea-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .textarea-field { resize: vertical; line-height: 1.5; }

    /* Custom File Upload */
    .file-upload-zone {
        border: 2px dashed var(--border-soft); border-radius: 0.75rem;
        background: rgba(250,248,245,0.5); transition: all 0.2s ease;
        cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 1.5rem; text-align: center; min-height: 160px;
    }
    .file-upload-zone:hover { border-color: var(--gold-400); background: rgba(254,249,231,0.4); }
    .file-icon { color: var(--text-muted); margin-bottom: 0.5rem; }
    .file-text-main { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); }
    .file-text-sub { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.25rem; }
    .file-warning { font-size: 0.7rem; color: var(--maroon-700); font-weight: 600; margin-top: 0.5rem; }

    /* Image Preview Box */
    .current-image-box {
        position: relative; display: inline-block; border-radius: 0.75rem; overflow: hidden;
        border: 1px solid var(--border-soft); box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .remove-img-btn {
        position: absolute; top: 0.5rem; right: 0.5rem;
        background: rgba(185, 28, 28, 0.9); color: white;
        width: 1.75rem; height: 1.75rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s; backdrop-filter: blur(2px);
    }
    .remove-img-btn:hover { background: #b91c1c; transform: scale(1.1); }

    /* Radio/Checkbox Styling */
    .custom-radio, .custom-checkbox {
        appearance: none; -webkit-appearance: none;
        width: 1.1rem; height: 1.1rem; border: 1px solid var(--border-soft);
        border-radius: 0.25rem; background: white; cursor: pointer;
        position: relative; transition: all 0.2s; flex-shrink: 0;
    }
    .custom-radio { border-radius: 50%; }
    .custom-radio:checked, .custom-checkbox:checked {
        background: var(--maroon-700); border-color: var(--maroon-700);
    }
    .custom-radio:checked::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
        width: 0.4rem; height: 0.4rem; background: white; border-radius: 50%;
    }
    .custom-checkbox:checked::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(45deg);
        width: 0.3rem; height: 0.6rem; border: solid white; border-width: 0 2px 2px 0;
    }
    .custom-control-label {
        font-size: 0.85rem; color: var(--text-secondary); cursor: pointer; user-select: none;
    }

    /* College Grid */
    .college-grid {
        max-height: 300px; overflow-y: auto; border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 0.75rem; background: rgba(255,255,255,0.5);
    }
    .college-item { display: flex; align-items: center; gap: 0.6rem; padding: 0.4rem 0; }
    .college-item label { margin: 0; font-size: 0.8rem; color: var(--text-primary); }

    /* Info Box (Yellowish) */
    .info-box {
        background: rgba(255, 249, 230, 0.6); border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 0.75rem; padding: 1rem;
    }
    .info-title { color: var(--maroon-800); font-weight: 700; font-size: 0.95rem; margin-bottom: 0.75rem; }
    .info-label { color: var(--text-secondary); font-weight: 600; font-size: 0.8rem; }
    .info-value { color: var(--text-primary); font-size: 0.85rem; }
    
    /* Badges */
    .badge {
        display: inline-flex; align-items: center; padding: 0.25rem 0.6rem;
        border-radius: 999px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .badge-green { background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-red { background: rgba(185, 28, 28, 0.1); color: #b91c1c; border: 1px solid rgba(185, 28, 28, 0.2); }
    .badge-gray { background: rgba(107, 94, 87, 0.1); color: var(--text-secondary); border: 1px solid var(--border-soft); }
    .badge-maroon { background: rgba(122, 42, 42, 0.1); color: var(--maroon-700); border: 1px solid rgba(122, 42, 42, 0.2); }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; font-weight: 600; border-radius: 0.6rem;
        padding: 0.75rem 1.5rem; display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(92,26,26,0.15); transition: all 0.2s ease;
        border: none; width: 100%;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    
    .btn-secondary {
        background: white; color: var(--text-secondary); border: 1px solid var(--border-soft);
        font-weight: 600; border-radius: 0.6rem; padding: 0.75rem 1.5rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s ease; width: 100%;
    }
    .btn-secondary:hover { background: var(--bg-warm); color: var(--text-primary); border-color: var(--maroon-700); }

    /* Error Message */
    .error-msg { color: #b91c1c; font-size: 0.75rem; margin-top: 0.35rem; font-weight: 500; }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .panel-card { padding: 1rem; }
        .file-upload-zone { padding: 1rem; min-height: 140px; }
        .btn-primary, .btn-secondary { width: 100%; }
        .action-group { flex-direction: column-reverse; }
        .action-group > * { width: 100%; }
        .current-image-box img { width: 100%; height: auto; max-width: 100%; }
    }
</style>

<div class="min-h-screen event-shell">
    <div class="event-glow one"></div>
    <div class="event-glow two"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-6 md:py-10">
        
        <!-- Header -->
        <div class="mb-6 panel-card p-5 sm:p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-maroon-800 to-maroon-700 flex items-center justify-center text-white shadow-sm flex-shrink-0" style="background: linear-gradient(135deg, var(--maroon-800), var(--maroon-700));">
                    <i class="fas fa-pen-to-square text-lg"></i>
                </div>
                <div class="page-header">
                    <h1 class="text-xl sm:text-2xl font-bold">Edit Event</h1>
                    <p class="text-sm mt-1">Update the event details below.</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="panel-card p-5 sm:p-6 md:p-8">
            <form method="POST" action="{{ route('counselor.events.update', $event) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    
                    <!-- Event Image -->
                    <div class="md:col-span-2">
                        <label class="section-title">Event Image (Optional)</label>

                        <!-- Current Image Preview -->
                        @if($event->image)
                            <div class="mb-4">
                                <p class="text-xs font-semibold text-[var(--text-secondary)] mb-2 uppercase tracking-wide">Current Image:</p>
                                <div class="current-image-box">
                                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-64 h-48 object-cover">
                                    <button type="button" onclick="confirmImageRemoval()" class="remove-img-btn" title="Remove Image">
                                        <i class="fas fa-xmark text-xs"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_image" id="remove_image" value="0">
                            </div>
                        @endif

                        <!-- Image Upload -->
                        <div class="mt-2">
                            <label for="image" class="file-upload-zone group">
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                                <i class="fas fa-cloud-upload-alt text-3xl file-icon group-hover:text-[var(--gold-500)] transition"></i>
                                <p class="file-text-main"><span class="text-[var(--maroon-700)]">Click to upload</span> or drag and drop</p>
                                <p class="file-text-sub">PNG, JPG, GIF (MAX. 2MB)</p>
                                @if($event->image)
                                    <p class="file-warning">Uploading a new image will replace the current one</p>
                                @endif
                            </label>
                        </div>

                        <!-- New Image Preview -->
                        <div id="image-preview" class="mt-4 hidden">
                            <p class="text-xs font-semibold text-[var(--text-secondary)] mb-2 uppercase tracking-wide">New Image Preview:</p>
                            <img id="preview" class="w-full max-w-xs h-48 object-cover rounded-lg shadow-md border border-[var(--border-soft)]">
                        </div>

                        @error('image')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="section-title">Event Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}"
                               class="input-field"
                               placeholder="Enter event title" required>
                        @error('title')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Type -->
                    <div>
                        <label for="type" class="section-title">Event Type *</label>
                        <select id="type" name="type" class="select-field" required>
                            <option value="">Select Event Type</option>
                            <option value="webinar" {{ old('type', $event->type) == 'webinar' ? 'selected' : '' }}>Webinar</option>
                            <option value="workshop" {{ old('type', $event->type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="seminar" {{ old('type', $event->type) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="activity" {{ old('type', $event->type) == 'activity' ? 'selected' : '' }}>Activity</option>
                            <option value="conference" {{ old('type', $event->type) == 'conference' ? 'selected' : '' }}>Conference</option>
                        </select>
                        @error('type')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Attendees -->
                    <div>
                        <label for="max_attendees" class="section-title">Max Attendees (Optional)</label>
                        <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}"
                               class="input-field"
                               placeholder="Leave empty for unlimited" min="1">
                        @error('max_attendees')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- College Selection -->
                    <div class="md:col-span-2">
                        <label class="section-title">College Availability *</label>

                        <!-- All Colleges Option -->
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center gap-3">
                                <input type="radio" id="for_all_colleges_true" name="for_all_colleges" value="1"
                                       {{ old('for_all_colleges', $event->for_all_colleges) ? 'checked' : '' }}
                                       class="custom-radio">
                                <label for="for_all_colleges_true" class="custom-control-label">
                                    All Colleges - Event available to students from all colleges
                                </label>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="radio" id="for_all_colleges_false" name="for_all_colleges" value="0"
                                       {{ old('for_all_colleges', $event->for_all_colleges) === '0' || !$event->for_all_colleges ? 'checked' : '' }}
                                       class="custom-radio">
                                <label for="for_all_colleges_false" class="custom-control-label">
                                    Specific Colleges - Choose which colleges can see this event
                                </label>
                            </div>
                        </div>

                        <!-- College Selection (shown only when specific colleges is selected) -->
                        <div id="colleges_selection" class="{{ old('for_all_colleges', $event->for_all_colleges) === '0' || !$event->for_all_colleges ? 'mt-4' : 'hidden' }}">
                            <label class="section-title">Select Colleges</label>
                            <div class="college-grid">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                    @foreach($colleges as $college)
                                        <div class="college-item">
                                            <input type="checkbox" id="college_{{ $college->id }}" name="colleges[]"
                                                   value="{{ $college->id }}"
                                                   {{ in_array($college->id, old('colleges', $selectedColleges)) ? 'checked' : '' }}
                                                   class="custom-checkbox">
                                            <label for="college_{{ $college->id }}" class="custom-control-label">
                                                {{ $college->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <p class="text-xs text-[var(--text-muted)] mt-2 flex items-center">
                                <i class="fas fa-circle-info mr-1.5 text-[var(--gold-500)]"></i>
                                Selected colleges will automatically be saved when you update.
                            </p>
                            @error('colleges')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Required Event -->
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-[rgba(250,248,245,0.6)] border border-[var(--border-soft)]">
                            <input type="checkbox" id="is_required" name="is_required" value="1"
                                   {{ old('is_required', $event->is_required) ? 'checked' : '' }}
                                   class="custom-checkbox">
                            <div>
                                <label for="is_required" class="custom-control-label font-medium text-[var(--text-primary)] block">
                                    Required Event
                                </label>
                                <p class="text-xs text-[var(--text-muted)] mt-0.5">Students from selected colleges must attend this event.</p>
                            </div>
                        </div>
                        @error('is_required')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year Level Availability -->
                    <div class="md:col-span-2">
                        <label class="section-title">Year Level Availability</label>

                        @php $savedYearLevels = old('year_levels', $event->year_levels ?? []); @endphp
                        <div class="mt-2 space-y-3">
                            <div class="flex items-center gap-3">
                                <input type="radio" id="for_all_year_levels_true" name="for_all_year_levels" value="1"
                                       {{ empty($savedYearLevels) ? 'checked' : '' }}
                                       class="custom-radio">
                                <label for="for_all_year_levels_true" class="custom-control-label">
                                    All Year Levels - Available to students from all year levels
                                </label>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="radio" id="for_all_year_levels_false" name="for_all_year_levels" value="0"
                                       {{ !empty($savedYearLevels) ? 'checked' : '' }}
                                       class="custom-radio">
                                <label for="for_all_year_levels_false" class="custom-control-label">
                                    Specific Year Levels - Choose which year levels can see this event
                                </label>
                            </div>
                        </div>

                        <div id="year_levels_selection" class="{{ !empty($savedYearLevels) ? 'mt-4' : 'hidden' }}">
                            <label class="section-title">Select Year Levels *</label>
                            <div class="college-grid">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                    @foreach(['1st Year','2nd Year','3rd Year','4th Year','5th Year'] as $yl)
                                    <div class="college-item">
                                        <input type="checkbox" id="yl_{{ Str::slug($yl) }}" name="year_levels[]"
                                               value="{{ $yl }}"
                                               {{ in_array($yl, $savedYearLevels) ? 'checked' : '' }}
                                               class="custom-checkbox">
                                        <label for="yl_{{ Str::slug($yl) }}" class="custom-control-label">{{ $yl }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('year_levels')
                                <p class="error-msg">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="event_start_date" class="section-title">Start Date *</label>
                        <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date', $event->event_start_date->format('Y-m-d')) }}"
                               class="input-field" required>
                        @error('event_start_date')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="event_end_date" class="section-title">End Date *</label>
                        <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date', $event->event_end_date->format('Y-m-d')) }}"
                               class="input-field" required>
                        @error('event_end_date')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="section-title">Start Time *</label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}"
                               class="input-field" required>
                        @error('start_time')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="section-title">End Time *</label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}"
                               class="input-field" required>
                        @error('end_time')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="md:col-span-2">
                        <label for="location" class="section-title">Location *</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
                               class="input-field"
                               placeholder="e.g., Room 101, Online, Main Hall" required>
                        @error('location')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Attending Counselors -->
                    <div class="md:col-span-2">
                        <label class="section-title">Attending Counselors (Google Calendar)</label>
                        <p class="text-xs text-[var(--text-muted)] mb-3 flex items-center gap-1.5">
                            <i class="fab fa-google text-[var(--gold-500)]"></i>
                            Selected counselors will have this event added to their Google Calendar.
                        </p>
                        <div class="college-grid">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                                @foreach($counselors as $counselor)
                                    <div class="college-item">
                                        <input type="checkbox" id="counselor_{{ $counselor->id }}" name="counselor_ids[]"
                                               value="{{ implode(',', $counselor->all_ids) }}"
                                               {{ count(array_intersect($counselor->all_ids, old('counselor_ids', $selectedCounselors))) > 0 ? 'checked' : '' }}
                                               class="custom-checkbox counselor-multi-check"
                                               data-ids="{{ implode(',', $counselor->all_ids) }}"
                                               {{ !$counselor->google_calendar_id ? 'disabled' : '' }}>
                                        <label for="counselor_{{ $counselor->id }}" class="custom-control-label {{ !$counselor->google_calendar_id ? 'opacity-50' : '' }}">
                                            {{ trim($counselor->user->first_name . ' ' . $counselor->user->last_name) }}
                                            @if($counselor->college_names)
                                                <span class="text-xs text-[var(--text-muted)]">({{ $counselor->college_names }})</span>
                                            @endif
                                            @if(!$counselor->google_calendar_id)
                                                <span class="text-xs text-[var(--text-muted)]">(no calendar)</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('counselor_ids')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="section-title">Description *</label>
                        <textarea id="description" name="description" rows="5"
                                  class="textarea-field"
                                  placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-[rgba(250,248,245,0.6)] border border-[var(--border-soft)]">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $event->is_active) ? 'checked' : '' }}
                                   class="custom-checkbox">
                            <div>
                                <label for="is_active" class="custom-control-label font-medium text-[var(--text-primary)] block">
                                    Activate this event
                                </label>
                                <p class="text-xs text-[var(--text-muted)] mt-0.5">Active events are visible to students. Uncheck to hide.</p>
                            </div>
                        </div>
                        @error('is_active')
                            <p class="error-msg">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Event Information -->
                <div class="mt-8 info-box">
                    <h3 class="info-title">Current Event Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                            <span class="info-label">College Availability:</span>
                            <span>
                                @if($event->for_all_colleges)
                                    <span class="badge badge-green">All Colleges</span>
                                @else
                                    <span class="badge badge-maroon">
                                        {{ $event->colleges->count() }} Specific {{ Str::plural('College', $event->colleges->count()) }}
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                            <span class="info-label">Event Status:</span>
                            <span>
                                @if($event->is_required)
                                    <span class="badge badge-red">Required Event</span>
                                @else
                                    <span class="badge badge-gray">Optional Event</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                            <span class="info-label">Current Registrations:</span>
                            <span class="info-value">{{ $event->registered_count }} registered</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                            <span class="info-label">Available Slots:</span>
                            <span class="info-value">
                                @if($event->max_attendees)
                                    {{ $event->available_slots }} of {{ $event->max_attendees }}
                                @else
                                    Unlimited
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Show current colleges if specific colleges are selected -->
                    @if(!$event->for_all_colleges && $event->colleges->isNotEmpty())
                        <div class="mt-4 pt-3 border-t border-[rgba(212, 175, 55, 0.2)]">
                            <span class="info-label block mb-2">Currently Available For:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($event->colleges as $college)
                                    <span class="badge badge-green">{{ $college->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="mt-8 action-group flex flex-col md:flex-row gap-4 justify-end">
                    <a href="{{ route('counselor.events.index') }}"
                       class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit"
                            class="btn-primary">
                        <i class="fas fa-save mr-2 text-xs"></i> Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const allCollegesRadio = document.getElementById('for_all_colleges_true');
        const specificCollegesRadio = document.getElementById('for_all_colleges_false');
        const collegesSelection = document.getElementById('colleges_selection');

        function toggleCollegesSelection() {
            if (specificCollegesRadio.checked) {
                collegesSelection.classList.remove('hidden');
                collegesSelection.classList.add('mt-4');
            } else {
                collegesSelection.classList.add('hidden');
                collegesSelection.classList.remove('mt-4');
            }
        }

        allCollegesRadio.addEventListener('change', toggleCollegesSelection);
        specificCollegesRadio.addEventListener('change', toggleCollegesSelection);

        // Year level selection toggle
        const allYearLevelsRadio = document.getElementById('for_all_year_levels_true');
        const specificYearLevelsRadio = document.getElementById('for_all_year_levels_false');
        const yearLevelsSelection = document.getElementById('year_levels_selection');

        function toggleYearLevelsSelection() {
            if (specificYearLevelsRadio.checked) {
                yearLevelsSelection.classList.remove('hidden');
                yearLevelsSelection.classList.add('mt-4');
            } else {
                yearLevelsSelection.classList.add('hidden');
                yearLevelsSelection.classList.remove('mt-4');
                document.querySelectorAll('input[name="year_levels[]"]').forEach(cb => cb.checked = false);
            }
        }

        allYearLevelsRadio.addEventListener('change', toggleYearLevelsSelection);
        specificYearLevelsRadio.addEventListener('change', toggleYearLevelsSelection);

        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const preview = document.getElementById('preview');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    preview.setAttribute('src', this.result);
                    imagePreview.classList.remove('hidden');
                });
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
            }
        });

        // Initial toggle
        toggleCollegesSelection();
    });

    function confirmImageRemoval() {
        if (confirm('Are you sure you want to remove the current event image?')) {
            document.getElementById('remove_image').value = '1';
            // Hide the current image preview visually
            const currentImgBox = event.target.closest('.mb-4');
            if(currentImgBox) {
                currentImgBox.style.display = 'none';
            }
        }
    }
</script>
@endsection