@extends('layouts.admin')

@section('title', 'Edit Event - Admin Dashboard')

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

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-pen-to-square text-base sm:text-lg"></i>
                        </div>

                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Event Editing
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Edit Event</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Update the event details below.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="text-center sm:text-left min-w-0">
                            <p class="summary-label">Navigation</p>
                            <p class="summary-value">Back to Events</p>
                            <p class="summary-subtext hidden sm:block">Return to the events directory whenever needed.</p>
                        </div>
                        <a href="{{ route('admin.events') }}"
                           class="back-btn px-3 py-2 whitespace-nowrap text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert-success bg-[#ecfdf5] border-[#10b981]/30 text-[#059669]">
                <div class="flex items-center text-xs sm:text-sm">
                    <i class="fas fa-check-circle mr-2 text-emerald-500 text-sm"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error bg-[#fdf2f2] border-[#b91c1c]/30 text-[#b91c1c]">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mr-2 text-rose-500 mt-0.5 text-sm"></i>
                    <ul class="list-disc list-inside text-[10px] sm:text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Event Form -->
        <div class="panel-card">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon">
                    <i class="fas fa-file-pen text-[9px] sm:text-xs"></i>
                </div>
                <div>
                    <h2 class="panel-title">Edit Event Form</h2>
                    <p class="panel-subtitle hidden sm:block">Update core event information, schedule, counselor assignment, and status.</p>
                </div>
            </div>

            <div class="p-3 sm:p-4">
                <form method="POST" action="{{ route('admin.events.update', $event) }}">
                    @csrf
                    @method('PUT')

                    <div class="section-card mb-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-circle-info text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Event Details</h3>
                                <p class="section-subtitle hidden sm:block">Update the event title, type, counselor assignment, and attendance settings.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <!-- Event Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="field-label">Event Title <span class="text-[#b91c1c]">*</span></label>
                                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}"
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
                                    <option value="workshop" {{ old('type', $event->type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                    <option value="seminar" {{ old('type', $event->type) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                    <option value="webinar" {{ old('type', $event->type) == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                    <option value="conference" {{ old('type', $event->type) == 'conference' ? 'selected' : '' }}>Conference</option>
                                    <option value="other" {{ old('type', $event->type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('type')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assign to Counselor -->
                            <div>
                                <label for="user_id" class="field-label">Assign to Counselor <span class="text-[#b91c1c]">*</span></label>
                                <select id="user_id" name="user_id"
                                        class="select-field" required>
                                    <option value="">Select Counselor</option>
                                    @foreach($counselors as $counselor)
                                        <option value="{{ $counselor->user_id }}" {{ old('user_id', $event->user_id) == $counselor->user_id ? 'selected' : '' }}>
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
                                <label for="max_attendees" class="field-label">Max Attendees <span class="text-[#8b7e76] text-[10px]">(Optional)</span></label>
                                <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}"
                                       class="input-field form-input"
                                       placeholder="Leave empty for unlimited" min="1">
                                @error('max_attendees')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Date -->
                            <div>
                                <label for="event_start_date" class="field-label">Start Date <span class="text-[#b91c1c]">*</span></label>
                                <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date', $event->event_start_date->format('Y-m-d')) }}"
                                       class="input-field form-input" required>
                                @error('event_start_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="event_end_date" class="field-label">End Date <span class="text-[#b91c1c]">*</span></label>
                                <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date', $event->event_end_date->format('Y-m-d')) }}"
                                       class="input-field form-input" required>
                                @error('event_end_date')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Start Time -->
                            <div>
                                <label for="start_time" class="field-label">Start Time <span class="text-[#b91c1c]">*</span></label>
                                <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}"
                                       class="input-field form-input" required>
                                @error('start_time')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- End Time -->
                            <div>
                                <label for="end_time" class="field-label">End Time <span class="text-[#b91c1c]">*</span></label>
                                <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}"
                                       class="input-field form-input" required>
                                @error('end_time')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="md:col-span-2">
                                <label for="location" class="field-label">Location <span class="text-[#b91c1c]">*</span></label>
                                <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
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
                                          placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description', $event->description) }}</textarea>
                                @error('description')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="md:col-span-2">
                                <div class="option-card">
                                    <input type="checkbox" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', $event->is_active) ? 'checked' : '' }}
                                           class="w-4 h-4 text-[#7a2a2a] border-[#e5e0db] rounded focus:ring-[#7a2a2a] mt-0.5 flex-shrink-0">
                                    <label for="is_active" class="ml-3 text-xs font-medium text-[#4a3f3a]">
                                        Activate this event
                                    </label>
                                </div>
                                @error('is_active')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Current Event Information Card -->
                    <div class="info-card mt-4">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-info-circle text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Current Event Information</h3>
                                <p class="section-subtitle hidden sm:block">A quick snapshot of the event's current state and registration details.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-[10px] sm:text-xs">
                            <div>
                                <span class="info-grid-label">Counselor</span>
                                <span class="info-grid-value truncate block">{{ $event->user->first_name }} {{ $event->user->last_name }}</span>
                            </div>
                            <div>
                                <span class="info-grid-label">Status</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $event->is_active ? 'bg-[#ecfdf5] text-[#059669]' : 'bg-[#f5f0eb] text-[#6b5e57]' }}">
                                    {{ $event->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div>
                                <span class="info-grid-label">Registrations</span>
                                <span class="info-grid-value">{{ $event->registrations->where('status', 'registered')->count() }} active</span>
                            </div>
                            <div>
                                <span class="info-grid-label">Created</span>
                                <span class="info-grid-value">{{ $event->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-4 flex flex-col sm:flex-row gap-2 sm:gap-3 justify-end">
                        <a href="{{ route('admin.events') }}"
                           class="form-action-secondary text-center rounded-lg">
                            Cancel
                        </a>
                        <button type="submit"
                                class="form-action-primary rounded-lg">
                            <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i>
                            Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
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

        // Show confirmation for significant changes
        const form = document.querySelector('form');
        let originalData = new FormData(form);

        form.addEventListener('submit', function(e) {
            let hasSignificantChanges = false;
            const currentData = new FormData(form);

            // Check for changes in critical fields
            const criticalFields = ['user_id', 'event_start_date', 'event_end_date', 'start_time', 'end_time'];

            for (let [key, value] of currentData.entries()) {
                if (criticalFields.includes(key)) {
                    const originalValue = originalData.get(key);
                    if (originalValue !== value) {
                        hasSignificantChanges = true;
                        break;
                    }
                }
            }

            if (hasSignificantChanges) {
                if (!confirm('You are making significant changes to this event. This may affect student registrations. Continue?')) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
@endsection