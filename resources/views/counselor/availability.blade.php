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

    .avail-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .avail-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .avail-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .avail-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

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

    .day-card {
        border: 1px solid var(--border-soft); border-radius: 0.6rem; padding: 1rem;
        background: rgba(255,255,255,0.6); transition: all 0.2s;
    }
    .day-card:hover { background: rgba(255,255,255,0.9); border-color: rgba(212,175,55,0.4); box-shadow: 0 2px 8px rgba(44,36,32,0.02); }

    .override-row {
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 0.75rem; margin-bottom: 0.75rem;
        align-items: end; transition: border-color 0.2s;
    }
    .override-row:hover { border-color: rgba(212,175,55,0.4); }
    
    .remove-btn {
        color: #b91c1c; font-weight: 600; font-size: 0.75rem;
        background: rgba(254, 226, 226, 0.5); padding: 0.5rem 0.8rem;
        border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;
        display: inline-flex; align-items: center; justify-content: center; gap: 0.3rem; border: 1px solid rgba(185,28,28,0.2);
    }
    .remove-btn:hover { background: rgba(254, 226, 226, 0.9); color: #991b1b; }

    .btn-add {
        background: rgba(255, 249, 230, 0.8); color: var(--maroon-700);
        border: 1px solid rgba(212, 175, 55, 0.3); font-weight: 600;
        border-radius: 0.6rem; padding: 0.6rem 1rem;
        display: inline-flex; align-items: center; gap: 0.5rem;
        transition: all 0.2s; font-size: 0.8rem;
    }
    .btn-add:hover { background: rgba(255, 249, 230, 1); border-color: var(--gold-500); transform: translateY(-1px); }

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

    @media (max-width: 639px) {
        .panel-header, .section-header { padding: 0.75rem 1rem; }
        .input-field, .select-field { padding: 0.5rem 0.7rem; font-size: 0.85rem; }
        .form-action-primary, .form-action-secondary, .back-btn { width: 100%; justify-content: center; }
        .override-row { grid-template-columns: 1fr !important; gap: 0.75rem !important; }
        .override-row > div { width: 100%; }
        .remove-btn { width: 100%; justify-content: center; }
    }
</style>

<div class="min-h-screen avail-shell">
    <div class="avail-glow one"></div>
    <div class="avail-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-clock text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                My Schedule
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Availability & Booking Limits</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Set your weekly availability, daily limits, and date overrides.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-week text-sm"></i>
                            </div>
                            <div>
                                <p class="summary-label">Current Limit</p>
                                <p class="summary-value">{{ $counselorProfile->daily_booking_limit ?? 3 }} <span class="text-sm font-normal text-white/70">/ day</span></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('counselor.appointments') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs sm:text-sm rounded-lg font-medium border border-white/20 bg-white/10 hover:bg-white/20 transition-all text-white shadow-sm whitespace-nowrap">
                                <i class="fas fa-calendar-check text-[10px]"></i> View Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="alert-error bg-[#fdf2f2] border-[#b91c1c]/30 text-[#b91c1c]">
                <div class="flex items-start">
                    <i class="fas fa-circle-exclamation mr-2 text-rose-500 mt-0.5 text-sm"></i>
                    <ul class="list-disc list-inside text-[10px] sm:text-xs space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('counselor.availability.update') }}">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 xl:grid-cols-[1fr_380px] gap-5 sm:gap-6 items-stretch">
                
                <!-- Left Column (Weekly Availability) -->
                <div class="space-y-5 sm:space-y-6">

                    <!-- Weekly Availability -->
                    <div class="section-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-calendar-day text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Weekly Availability</h3>
                                <p class="section-subtitle hidden sm:block">Check the days you are available and enter time slots.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                @foreach($weekdays as $dayKey => $dayLabel)
                                    @php
                                        $slots = $availability[$dayKey] ?? [];
                                        $isAvailable = !empty($slots);
                                        $slotDisplay = !empty($slots) ? implode(', ', $slots) : '08:00-12:00, 13:00-17:00';
                                    @endphp
                                    <div class="day-card">
                                        <label class="flex items-center mb-2.5 cursor-pointer">
                                            <input type="checkbox" name="availability_days[]" value="{{ $dayKey }}"
                                                   class="custom-checkbox"
                                                   {{ $isAvailable ? 'checked' : '' }}>
                                            <span class="ms-2 custom-control-label">{{ $dayLabel }}</span>
                                        </label>
                                        <input type="text" name="availability_slots[{{ $dayKey }}]"
                                               value="{{ $slotDisplay }}"
                                               placeholder="08:00-12:00, 13:00-17:00"
                                               class="input-field text-sm">
                                        <p class="helper-text mt-1.5 text-[10px]">Use 24-hour format, comma-separated ranges.</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Settings & Actions) -->
                <div class="space-y-5 sm:space-y-6 flex flex-col h-full">

                    <!-- Settings -->
                    <div class="section-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-sliders text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Booking Settings</h3>
                                <p class="section-subtitle hidden sm:block">Global limits for your schedule.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4">
                            <!-- Daily Limit -->
                            <div>
                                <label for="daily_booking_limit" class="field-label text-sm font-semibold">Daily Booking Limit <span class="text-[#b91c1c]">*</span></label>
                                <input type="number" id="daily_booking_limit" name="daily_booking_limit" min="0" max="50"
                                       value="{{ old('daily_booking_limit', $counselorProfile->daily_booking_limit ?? 3) }}"
                                       class="input-field form-input text-lg font-bold">
                                <p class="helper-text mt-1.5">Maximum number of students you can see per day. Set to 0 to disable daily limits.</p>
                                @error('daily_booking_limit')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Overrides -->
                    <div class="section-card flex-1 flex flex-col">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-calendar-xmark text-[9px] sm:text-xs"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Schedule Overrides</h3>
                                <p class="section-subtitle hidden sm:block">Close specific dates or open them with different hours.</p>
                            </div>
                        </div>

                        <div class="p-3 sm:p-4 flex-1">
                            <div id="scheduleOverrides" data-next-index="{{ $existingOverrides->count() }}">
                                @foreach($existingOverrides as $index => $override)
                                    <div class="override-row grid grid-cols-1 md:grid-cols-[1fr_1fr_1.5fr_auto] gap-3 relative">
                                        <input type="hidden" name="schedule_overrides[{{ $index }}][id]" value="{{ $override->id }}">
                                        <div>
                                            <label class="field-label">Date</label>
                                            <input type="date" name="schedule_overrides[{{ $index }}][date]"
                                                   value="{{ $override->date->format('Y-m-d') }}"
                                                   class="input-field">
                                        </div>
                                        <div>
                                            <label class="field-label">Status</label>
                                            <select name="schedule_overrides[{{ $index }}][status]"
                                                    class="select-field">
                                                <option value="open" {{ $override->is_closed ? '' : 'selected' }}>Open</option>
                                                <option value="closed" {{ $override->is_closed ? 'selected' : '' }}>Closed</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="field-label">Time Slots</label>
                                            <input type="text" name="schedule_overrides[{{ $index }}][time_slots]"
                                                   value="{{ implode(', ', $override->time_slots ?? []) }}"
                                                   placeholder="08:00-12:00, 13:00-17:00"
                                                   class="input-field">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" class="remove-override remove-btn h-[2.35rem]">
                                                <i class="fas fa-trash-can-alt"></i> <span class="md:hidden">Remove</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" id="addOverride" class="btn-add mt-2 w-full sm:w-auto justify-center">
                                <i class="fas fa-plus text-[10px]"></i> Add Date Override
                            </button>
                            <p class="helper-text mt-2 text-center sm:text-left">If opening a date outside your usual days, add time slots.</p>
                        </div>
                    </div>

                    <!-- Submit Actions -->
                    <div class="section-card bg-transparent border-none shadow-none mt-2">
                        <div class="flex flex-col gap-3">
                            <button type="submit" class="form-action-primary w-full shadow-lg">
                                <i class="fas fa-save mr-1.5 text-[10px]"></i> Save Availability
                            </button>
                            <a href="{{ route('counselor.dashboard') }}" class="form-action-secondary w-full text-center">
                                Cancel
                            </a>
                        </div>
                    </div>

                </div>
            </div> <!-- End Grid -->
        </form>
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
                row.className = 'override-row grid grid-cols-1 md:grid-cols-[1fr_1fr_1.5fr_auto] gap-3 relative';
                row.innerHTML = `
                    <div>
                        <label class="field-label">Date</label>
                        <input type="date" name="schedule_overrides[${nextIndex}][date]"
                               class="input-field">
                    </div>
                    <div>
                        <label class="field-label">Status</label>
                        <select name="schedule_overrides[${nextIndex}][status]"
                                class="select-field">
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Time Slots</label>
                        <input type="text" name="schedule_overrides[${nextIndex}][time_slots]"
                               placeholder="08:00-12:00, 13:00-17:00"
                               class="input-field">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-override remove-btn h-[2.35rem]">
                            <i class="fas fa-trash-can-alt"></i> <span class="md:hidden">Remove</span>
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