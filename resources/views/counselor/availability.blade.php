@extends('layouts.app')

@section('title', 'Availability & Booking Limits - OGC')

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
    .avail-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .avail-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .avail-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .avail-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

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

    /* Header Specifics */
    .page-header h1 { color: var(--text-primary); font-weight: 700; letter-spacing: -0.02em; }
    .page-header p { color: var(--text-secondary); }

    /* Alerts */
    .alert-success {
        background: rgba(209, 250, 229, 0.6); border: 1px solid rgba(16, 185, 129, 0.2);
        color: #047857; border-radius: 0.75rem; padding: 0.75rem 1rem;
        display: flex; align-items: center; gap: 0.75rem;
    }
    .alert-error {
        background: rgba(254, 226, 226, 0.6); border: 1px solid rgba(185, 28, 28, 0.2);
        color: #b91c1c; border-radius: 0.75rem; padding: 0.75rem 1rem;
    }
    .alert-title { font-weight: 600; margin-bottom: 0.25rem; display: block; }
    .alert-list { list-style-type: disc; padding-left: 1.25rem; font-size: 0.85rem; }

    /* Form Elements */
    .section-title {
        font-size: 0.9rem; font-weight: 700; color: var(--maroon-800);
        margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .section-title::before {
        content: ""; display: block; width: 4px; height: 18px;
        background: var(--gold-500); border-radius: 2px;
    }
    
    .input-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.85rem; padding: 0.6rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }

    .helper-text { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.35rem; }
    .error-msg { color: #b91c1c; font-size: 0.75rem; margin-top: 0.35rem; font-weight: 500; }

    /* Custom Checkbox */
    .custom-checkbox {
        appearance: none; -webkit-appearance: none;
        width: 1.1rem; height: 1.1rem; border: 1px solid var(--border-soft);
        border-radius: 0.25rem; background: white; cursor: pointer;
        position: relative; transition: all 0.2s; flex-shrink: 0;
    }
    .custom-checkbox:checked {
        background: var(--maroon-700); border-color: var(--maroon-700);
    }
    .custom-checkbox:checked::after {
        content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(45deg);
        width: 0.3rem; height: 0.6rem; border: solid white; border-width: 0 2px 2px 0;
    }
    .custom-control-label {
        font-size: 0.85rem; color: var(--text-secondary); cursor: pointer; user-select: none; font-weight: 600;
    }

    /* Day Card */
    .day-card {
        border: 1px solid var(--border-soft); border-radius: 0.6rem; padding: 1rem;
        background: rgba(255,255,255,0.6); transition: all 0.2s;
    }
    .day-card:hover { background: rgba(255,255,255,0.9); border-color: var(--gold-400); }

    /* Override Row */
    .override-row {
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 0.75rem; margin-bottom: 0.75rem;
        align-items: end;
    }
    .remove-btn {
        color: #b91c1c; font-weight: 600; font-size: 0.8rem;
        background: rgba(254, 226, 226, 0.5); padding: 0.4rem 0.8rem;
        border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 0.3rem;
    }
    .remove-btn:hover { background: rgba(254, 226, 226, 0.9); color: #991b1b; }

    /* Buttons */
    .btn-add {
        background: rgba(255, 249, 230, 0.8); color: var(--maroon-700);
        border: 1px solid rgba(212, 175, 55, 0.3); font-weight: 600;
        border-radius: 0.6rem; padding: 0.6rem 1rem;
        display: inline-flex; align-items: center; gap: 0.5rem;
        transition: all 0.2s; font-size: 0.8rem;
    }
    .btn-add:hover { background: rgba(255, 249, 230, 1); border-color: var(--gold-500); transform: translateY(-1px); }

    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; font-weight: 600; border-radius: 0.6rem;
        padding: 0.75rem 1.5rem; display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(92,26,26,0.15); transition: all 0.2s ease;
        border: none;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .btn-back {
        background: white; color: var(--text-secondary); border: 1px solid var(--border-soft);
        font-weight: 600; border-radius: 0.6rem; padding: 0.6rem 1rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s ease; text-decoration: none;
    }
    .btn-back:hover { background: var(--bg-warm); color: var(--text-primary); border-color: var(--maroon-700); }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .panel-card { padding: 1rem; }
        .btn-primary, .btn-back { width: 100%; justify-content: center; }
        .action-group { flex-direction: column; gap: 0.75rem; }
        .action-group > * { width: 100%; }
        .override-row { grid-template-columns: 1fr !important; gap: 0.75rem !important; }
        .override-row > div { width: 100%; }
    }
</style>

<div class="min-h-screen avail-shell">
    <div class="avail-glow one"></div>
    <div class="avail-glow two"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 panel-card p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="page-header">
                    <h1 class="text-xl sm:text-2xl font-bold">Availability & Booking Limits</h1>
                    <p class="text-sm mt-1">Set your weekly availability, daily limits, and date overrides.</p>
                </div>
                <div>
                    <a href="{{ route('counselor.dashboard') }}"
                       class="btn-back">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 alert-error">
                <div>
                    <span class="alert-title">Please fix the following errors:</span>
                    <ul class="alert-list space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="panel-card p-5 sm:p-6 md:p-8">
            <form method="POST" action="{{ route('counselor.availability.update') }}">
                @csrf
                @method('patch')

                <!-- Daily Limit -->
                <div class="mb-8">
                    <label for="daily_booking_limit" class="section-title">Daily Booking Limit</label>
                    <input type="number" id="daily_booking_limit" name="daily_booking_limit" min="0" max="50"
                           value="{{ old('daily_booking_limit', $counselorProfile->daily_booking_limit ?? 3) }}"
                           class="input-field md:w-1/3">
                    <p class="helper-text">Set how many students you can see per day.</p>
                    @error('daily_booking_limit')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weekly Availability -->
                <div class="border-t border-[var(--border-soft)] pt-6">
                    <h3 class="section-title">Weekly Availability</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($weekdays as $dayKey => $dayLabel)
                            @php
                                $slots = $availability[$dayKey] ?? [];
                                $isAvailable = !empty($slots);
                                $slotDisplay = !empty($slots) ? implode(', ', $slots) : '08:00-12:00, 13:00-17:00';
                            @endphp
                            <div class="day-card">
                                <label class="flex items-center mb-3 cursor-pointer">
                                    <input type="checkbox" name="availability_days[]" value="{{ $dayKey }}"
                                           class="custom-checkbox"
                                           {{ $isAvailable ? 'checked' : '' }}>
                                    <span class="ms-2 custom-control-label">{{ $dayLabel }}</span>
                                </label>
                                <input type="text" name="availability_slots[{{ $dayKey }}]"
                                       value="{{ $slotDisplay }}"
                                       placeholder="08:00-12:00, 13:00-17:00"
                                       class="input-field">
                                <p class="helper-text">Use 24-hour format, comma-separated ranges.</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Schedule Overrides -->
                <div class="border-t border-[var(--border-soft)] pt-6 mt-6">
                    <h3 class="section-title">Schedule Overrides</h3>
                    <p class="helper-text mb-4">Close or open specific dates (e.g., whole-day interviews).</p>

                    <div id="scheduleOverrides" data-next-index="{{ $existingOverrides->count() }}">
                        @foreach($existingOverrides as $index => $override)
                            <div class="override-row grid grid-cols-1 md:grid-cols-4 gap-3">
                                <input type="hidden" name="schedule_overrides[{{ $index }}][id]" value="{{ $override->id }}">
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">Date</label>
                                    <input type="date" name="schedule_overrides[{{ $index }}][date]"
                                           value="{{ $override->date->format('Y-m-d') }}"
                                           class="input-field">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">Status</label>
                                    <select name="schedule_overrides[{{ $index }}][status]"
                                            class="select-field">
                                        <option value="open" {{ $override->is_closed ? '' : 'selected' }}>Open</option>
                                        <option value="closed" {{ $override->is_closed ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">Time Slots</label>
                                    <input type="text" name="schedule_overrides[{{ $index }}][time_slots]"
                                           value="{{ implode(', ', $override->time_slots ?? []) }}"
                                           placeholder="08:00-12:00, 13:00-17:00"
                                           class="input-field">
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="flex items-center text-sm text-[var(--text-secondary)] cursor-pointer">
                                        <input type="checkbox" name="schedule_overrides[{{ $index }}][remove]" value="1"
                                               class="custom-checkbox">
                                        <span class="ms-2">Remove</span>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="addOverride"
                            class="btn-add mt-2">
                        <i class="fas fa-plus"></i> Add Date Override
                    </button>
                    <p class="helper-text mt-2">If opening a date outside your usual days, add time slots.</p>
                </div>

                <!-- Submit -->
                <div class="mt-8 action-group flex justify-end">
                    <button type="submit"
                            class="btn-primary">
                        <i class="fas fa-save mr-2 text-xs"></i> Save Availability
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const overridesContainer = document.getElementById('scheduleOverrides');
        const addOverrideButton = document.getElementById('addOverride');

        if (overridesContainer && addOverrideButton) {
            addOverrideButton.addEventListener('click', () => {
                const nextIndex = parseInt(overridesContainer.dataset.nextIndex || '0', 10);
                const row = document.createElement('div');
                row.className = 'override-row grid grid-cols-1 md:grid-cols-4 gap-3';
                row.innerHTML = `
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">Date</label>
                        <input type="date" name="schedule_overrides[${nextIndex}][date]"
                               class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">Status</label>
                        <select name="schedule_overrides[${nextIndex}][status]"
                                class="select-field">
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--text-secondary)] uppercase mb-1">Time Slots</label>
                        <input type="text" name="schedule_overrides[${nextIndex}][time_slots]"
                               placeholder="08:00-12:00, 13:00-17:00"
                               class="input-field">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="remove-override">
                            <i class="fas fa-trash-can-alt mr-1"></i> Remove
                        </button>
                    </div>
                `;

                overridesContainer.appendChild(row);
                overridesContainer.dataset.nextIndex = (nextIndex + 1).toString();
            });

            overridesContainer.addEventListener('click', (event) => {
                // Handle click on button or icon inside button
                const target = event.target.closest('.remove-override');
                if (target) {
                    const row = target.closest('.override-row');
                    if (row) {
                        row.remove();
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection