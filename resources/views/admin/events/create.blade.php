@extends('layouts.admin')

@section('title', 'Create Event - Admin Panel')

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

    .create-event-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .create-event-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .create-event-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .create-event-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

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
    .helper-text { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.3rem; line-height: 1.5; }
    .error-text { font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem; }

    .alert-success, .alert-error { border-radius: 0.6rem; padding: 0.65rem 0.85rem; border-width: 1px; margin-bottom: 1rem; }

    .option-card {
        display: flex; align-items: flex-start; padding: 0.7rem 0.85rem; border-radius: 0.65rem;
        background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft);
        transition: all 0.2s ease;
    }
    .option-card:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }

    .form-action-primary, .form-action-secondary {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;
        padding: 0.55rem 0.85rem;
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
        .input-field, .select-field { padding: 0.5rem 0.7rem; font-size: 0.85rem; }
        .textarea-field { padding: 0.6rem 0.7rem; }
        .form-action-primary, .form-action-secondary { width: 100%; justify-content: center; }
        .space-x-3 > * + * { margin-left: 0; margin-top: 0.5rem; }
    }
</style>

<div class="min-h-screen create-event-shell">
    <div class="create-event-glow one"></div>
    <div class="create-event-glow two"></div>

    <!-- Main Content -->
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-plus text-base sm:text-lg"></i>
                        </div>

                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Event Creation
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Create New Event</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Fill in the details below to create a new mental health event.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center gap-3 p-4">
                        <div class="summary-icon flex-shrink-0">
                            <i class="fas fa-wand-magic-sparkles text-sm"></i>
                        </div>
                        <div class="text-center sm:text-left min-w-0">
                            <p class="summary-label">Publishing Flow</p>
                            <p class="summary-value">Draft your event</p>
                            <p class="summary-subtext">Configure schedule, audience, and event options before publishing.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->

        @if($errors->any())
            <div class="alert-error bg-[#fdf2f2] border-[#b91c1c]/30 text-[#b91c1c]">
                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Event Form -->
        <div class="panel-card">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon">
                    <i class="fas fa-pen-ruler text-[9px] sm:text-xs"></i>
                </div>
                <div>
                    <h2 class="panel-title">Event Form</h2>
                    <p class="panel-subtitle hidden sm:block">Complete each section carefully to create a polished event listing.</p>
                </div>
            </div>

            <div class="p-3 sm:p-4">
                <form method="POST" action="{{ route('admin.events.store') }}">
                    @csrf

                    <!-- Event Details Section -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-circle-info text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Event Details</h3>
                                <p class="section-subtitle hidden sm:block">Set the main event identity, counselor assignment, and attendance settings.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Event Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="field-label">Event Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}"
                                       class="input-field"
                                       placeholder="Enter event title" required>
                                @error('title')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Event Type -->
                            <div>
                                <label for="type" class="field-label">Event Type</label>
                                <select id="type" name="type"
                                        class="select-field" required>
                                    <option value="">Select Event Type</option>
                                    <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="seminar" {{ old('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                    <option value="webinar" {{ old('type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                    <option value="conference" {{ old('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assign to Counselor -->
                            <div>
                                <label for="user_id" class="field-label">Assign to Counselor</label>
                                <select id="user_id" name="user_id"
                                        class="select-field" required>
                                    <option value="">Select Counselor</option>
                                    @foreach($counselors as $counselor)
                                        <option value="{{ $counselor->user_id }}" {{ old('user_id') == $counselor->user_id ? 'selected' : '' }}>
                                            {{ $counselor->user->first_name }} {{ $counselor->user->last_name }} - {{ $counselor->position }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Max Attendees -->
                            <div>
                                <label for="max_attendees" class="field-label">Max Attendees (Optional)</label>
                                <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees') }}"
                                       class="input-field"
                                       placeholder="Leave empty for unlimited" min="1">
                                @error('max_attendees')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Date and Time Section -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-calendar-days text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Date and Time</h3>
                                <p class="section-subtitle hidden sm:block">Set the event date range and define the start and end times.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Start Date -->
                            <div>
                                <label for="event_start_date" class="field-label">Start Date</label>
                                <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date') }}"
                                       class="input-field" required>
                                @error('event_start_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="event_end_date" class="field-label">End Date</label>
                                <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date') }}"
                                       class="input-field" required>
                                @error('event_end_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label for="start_time" class="field-label">Start Time</label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                                       class="input-field" required>
                                @error('start_time')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div>
                                <label for="end_time" class="field-label">End Time</label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                                       class="input-field" required>
                                @error('end_time')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location and Description Section -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-location-dot text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Location and Description</h3>
                                <p class="section-subtitle hidden sm:block">Describe where the event takes place and what attendees should expect.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 gap-3 sm:gap-4">
                            <!-- Location -->
                            <div>
                                <label for="location" class="field-label">Location</label>
                                <input type="text" id="location" name="location" value="{{ old('location') }}"
                                       class="input-field"
                                       placeholder="Enter event location (e.g., Room 101, Online, etc.)" required>
                                @error('location')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="field-label">Description</label>
                                <textarea id="description" name="description" rows="4"
                                          class="textarea-field"
                                          placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Target Audience Section -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-users-viewfinder text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Target Audience</h3>
                                <p class="section-subtitle hidden sm:block">Choose the colleges that should be able to access this event.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 gap-3 sm:gap-4">
                            <!-- Colleges (Multi-select) -->
                            <div>
                                <label for="colleges" class="field-label">
                                    Target Colleges (Optional - Leave empty for all colleges)
                                </label>
                                <select id="colleges" name="colleges[]" multiple
                                        class="select-field"
                                        size="5">
                                    @foreach($colleges ?? [] as $college)
                                        <option value="{{ $college->id }}" {{ in_array($college->id, old('colleges', [])) ? 'selected' : '' }}>
                                            {{ $college->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="helper-text">Hold Ctrl (Windows) or Cmd (Mac) to select multiple colleges. Leave empty to make this event available to all colleges.</p>
                                @error('colleges')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Year Level Restriction -->
                            <div>
                                <label class="field-label">Year Level Restriction</label>
                                <p class="helper-text mb-2">Leave all unchecked to allow all year levels.</p>
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
                                @error('year_levels')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Event Options Section -->
                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-toggle-on text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Event Options</h3>
                                <p class="section-subtitle hidden sm:block">Choose whether the event is required and whether it should be active immediately.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 space-y-3">
                            <!-- Is Required Event -->
                            <div class="option-card">
                                <input type="checkbox" id="is_required" name="is_required" value="1"
                                       {{ old('is_required') ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#7a2a2a] border-[#e5e0db] rounded focus:ring-[#7a2a2a] mt-0.5 flex-shrink-0">
                                <label for="is_required" class="ml-3 text-xs font-medium text-[#4a3f3a]">
                                    <span class="font-semibold text-[#7a2a2a]">Required Event</span> - This event is mandatory for selected colleges
                                </label>
                            </div>

                            <!-- Active Status -->
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

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-2">
                        <button type="reset"
                                class="form-action-secondary">
                            <i class="fas fa-redo mr-2 text-[9px] sm:text-xs"></i> Reset Form
                        </button>
                        <button type="submit"
                                class="form-action-primary">
                            <i class="fas fa-save mr-2 text-[9px] sm:text-xs"></i> Create Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add some client-side validation and UX enhancements
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
            });

            // Validate time range
            function validateTimeRange() {
                if (startDate.value === endDate.value && startTime.value && endTime.value) {
                    if (startTime.value >= endTime.value) {
                        endTime.setCustomValidity('End time must be after start time');
                    } else {
                        endTime.setCustomValidity('');
                    }
                }
            }

            startTime.addEventListener('change', validateTimeRange);
            endTime.addEventListener('change', validateTimeRange);
            startDate.addEventListener('change', validateTimeRange);
            endDate.addEventListener('change', validateTimeRange);
        });
    </script>
@endsection