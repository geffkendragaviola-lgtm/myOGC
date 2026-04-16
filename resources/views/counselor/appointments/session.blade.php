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

    .session-form-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .session-form-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .session-form-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .session-form-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .detail-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .detail-card:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before, .detail-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
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

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { 
        width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; 
        align-items: center; justify-content: center; 
        background: rgba(254,249,231,0.7); color: var(--maroon-700); 
    }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .detail-label { 
        font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; 
        color: var(--text-muted); margin-bottom: 0.25rem; display: block;
    }
    .detail-value { font-size: 0.75rem; color: var(--text-primary); font-weight: 500; }
    .detail-value.muted { color: var(--text-secondary); }

    .field-label { 
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); 
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; 
    }
    .input-field, .select-field, .textarea-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus, .textarea-field:focus { 
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); 
    }
    .textarea-field { min-height: 120px; resize: vertical; line-height: 1.5; }

    .checkbox-option {
        display: flex; align-items: flex-start; gap: 0.5rem;
        padding: 0.4rem; border-radius: 0.4rem;
        transition: background-color 0.15s ease;
    }
    .checkbox-option:hover { background: rgba(254,249,231,0.4); }
    .checkbox-option input {
        margin-top: 0.15rem; width: 1rem; height: 1rem;
        accent-color: var(--maroon-700); cursor: pointer;
    }
    .checkbox-option label {
        font-size: 0.75rem; color: var(--text-secondary); cursor: pointer; line-height: 1.4;
    }

    .error-text {
        font-size: 0.7rem; color: #b91c1c; margin-top: 0.25rem;
        display: flex; align-items: center; gap: 0.25rem;
    }
    .error-text::before { content: "•"; font-weight: bold; }

    .alert-success {
        display: flex; align-items: flex-start; gap: 0.5rem;
        border: 1px solid rgba(16,185,129,0.3); background: rgba(240,253,244,0.9);
        border-radius: 0.6rem; padding: 0.75rem 1rem; color: #065f46;
        font-size: 0.8rem; font-weight: 500;
    }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: var(--text-secondary); background: rgba(255,255,255,0.9);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }

    .profile-link {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.7rem; font-weight: 500; color: var(--maroon-700);
        transition: all 0.18s ease;
    }
    .profile-link:hover { color: var(--maroon-800); transform: translateX(2px); }

    .form-actions {
        display: flex; flex-direction: column-reverse; gap: 0.75rem;
        padding-top: 1rem; border-top: 1px solid var(--border-soft)/60;
    }
    @media (min-width: 768px) { 
        .form-actions { flex-direction: row; justify-content: flex-end; } 
    }

    /* Modal - FIXED: display flex only when NOT hidden to avoid Tailwind conflict */
    .modal-backdrop {
        position: fixed; inset: 0; background: rgba(44,36,32,0.6);
        align-items: center; justify-content: center;
        z-index: 50; padding: 1rem;
    }
    .modal-backdrop:not(.hidden) {
        display: flex;
    }
    .modal-card {
        background: rgba(255,255,255,0.98); border-radius: 0.75rem;
        border: 1px solid var(--border-soft); backdrop-filter: blur(8px);
        box-shadow: 0 8px 32px rgba(44,36,32,0.12);
        max-width: 44rem; width: 100%; max-height: 90vh; overflow-y: auto;
    }
    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60;
    }
    .modal-close {
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); transition: all 0.18s ease;
        font-size: 1rem;
    }
    .modal-close:hover { background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .modal-body { padding: 1.25rem; }
    .modal-footer {
        padding: 1rem 1.25rem; border-top: 1px solid var(--border-soft)/60;
        display: flex; justify-content: flex-end; gap: 0.75rem;
    }

    .time-slot {
        padding: 0.5rem; border-radius: 0.4rem; border: 1px solid var(--border-soft);
        text-align: center; font-size: 0.7rem; font-weight: 500;
        transition: all 0.18s ease; cursor: pointer;
        background: rgba(255,255,255,0.9);
    }
    .time-slot:hover { border-color: var(--maroon-700); background: rgba(254,249,231,0.6); }
    .time-slot.selected {
        border-color: var(--maroon-700); background: rgba(254,249,231,0.9);
        color: var(--maroon-700); font-weight: 600;
    }

    .auto-approve {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem; border-radius: 0.4rem;
        background: rgba(254,249,231,0.4);
    }
    .auto-approve input { width: 1rem; height: 1rem; accent-color: var(--maroon-700); }
    .auto-approve label { font-size: 0.75rem; color: var(--text-secondary); }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field, .textarea-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; }
        .detail-card .grid { grid-template-columns: 1fr !important; }
        .checkbox-option label { font-size: 0.8rem; }
        .time-slot { padding: 0.4rem; font-size: 0.65rem; }
        .modal-card { max-height: 95vh; margin: 0.5rem; }
        .modal-header { padding: 0.85rem 1rem; }
        .modal-body { padding: 1rem; }
        .hero-card .flex { flex-direction: column; align-items: flex-start !important; }
    }
</style>

<div class="min-h-screen session-form-shell">
    <div class="session-form-glow one"></div>
    <div class="session-form-glow two"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-sticky-note text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Counselor Portal
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Appointment Session</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">
                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                <span class="text-[#8b7e76]">({{ $appointment->student->student_id }})</span>
                            </p>
                            <p class="text-[10px] sm:text-xs text-[#8b7e76] mt-0.5">
                                {{ $appointment->student->college->name ?? 'N/A' }} • {{ $appointment->student->course }} • {{ $appointment->student->year_level }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full md:w-auto">
                        <button type="button"
                                onclick="openFollowupModal()"
                                class="primary-btn px-4 py-2 text-xs sm:text-sm w-full sm:w-auto">
                            <i class="fas fa-calendar-plus mr-1.5 text-[9px] sm:text-xs"></i> Book Follow-up
                        </button>
                        <a href="{{ route('counselor.appointments') }}"
                           class="secondary-btn px-4 py-2 text-xs sm:text-sm w-full sm:w-auto">
                            <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success mb-6">
                <i class="fas fa-check-circle mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Left Sidebar: Details -->
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                <!-- Appointment Details -->
                <div class="detail-card">
                    <div class="panel-topline"></div>
                    <div class="p-4 sm:p-5">
                        <h2 class="panel-title mb-4">Appointment Details</h2>
                        <div class="space-y-3">
                            <div>
                                <span class="detail-label">Date</span>
                                <div class="detail-value">{{ $appointment->appointment_date->format('F j, Y') }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Time</span>
                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                </div>
                            </div>
                            <div>
                                <span class="detail-label">Category</span>
                                <div class="detail-value">{{ $appointment->status === 'referred' ? 'Referred' : 'Booked' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Case Number</span>
                                <div class="detail-value muted">{{ $appointment->case_number ?: '—' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Date of Referral</span>
                                <div class="detail-value muted">{{ $appointment->referral_requested_at ? $appointment->referral_requested_at->format('F j, Y g:i A') : '—' }}</div>
                            </div>
                            {{-- Referred By --}}
                            <div>
                                <span class="detail-label">Referred By</span>
                                <div class="detail-value muted">
                                    @if($appointment->originalCounselor && $appointment->originalCounselor->user)
                                        {{ $appointment->originalCounselor->user->first_name }} {{ $appointment->originalCounselor->user->last_name }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            {{-- Referred To --}}
                            <div>
                                <span class="detail-label">Referred To</span>
                                <div class="detail-value muted">
                                    @if($appointment->referredCounselor && $appointment->referredCounselor->user)
                                        {{ $appointment->referredCounselor->user->first_name }} {{ $appointment->referredCounselor->user->last_name }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Follow-up Appointment (if exists) -->
                @if($followupAppointment)
                <div class="detail-card" style="border-color: rgba(212,175,55,0.4);">
                    <div class="panel-topline" style="background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%);"></div>
                    <div class="p-4 sm:p-5">
                        <h2 class="panel-title mb-4">Follow-up Appointment</h2>
                        <div class="space-y-3">
                            <div>
                                <span class="detail-label">Date</span>
                                <div class="detail-value">{{ \Carbon\Carbon::parse($followupAppointment->appointment_date)->format('F j, Y') }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Time</span>
                                <div class="detail-value">
                                    {{ \Carbon\Carbon::parse($followupAppointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($followupAppointment->end_time)->format('g:i A') }}
                                </div>
                            </div>
                            <div>
                                <span class="detail-label">Type</span>
                                <div class="detail-value">{{ $followupAppointment->booking_type ? ucwords(str_replace('_', ' ', $followupAppointment->booking_type)) : '—' }} - Follow up</div>
                            </div>
                            <div>
                                <span class="detail-label">Status</span>
                                <div class="detail-value">{{ ucwords(str_replace('_', ' ', $followupAppointment->status)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Student Details -->
                <div class="detail-card">
                    <div class="panel-topline"></div>
                    <div class="p-4 sm:p-5">
                        <h2 class="panel-title mb-4">Student Details</h2>
                        <div class="space-y-3">
                            <div>
                                <span class="detail-label">Name</span>
                                <div class="detail-value">{{ $appointment->student->user->full_name }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Age</span>
                                <div class="detail-value muted">{{ $appointment->student->user->age ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Date of Birth</span>
                                <div class="detail-value muted">{{ $appointment->student->user->birthdate ? $appointment->student->user->birthdate->format('Y-m-d') : '—' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Sex</span>
                                <div class="detail-value muted">{{ $appointment->student->user->sex ? ucfirst($appointment->student->user->sex) : '—' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Address</span>
                                <div class="detail-value muted whitespace-pre-line">{{ $appointment->student->user->address ?: '—' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Phone</span>
                                <div class="detail-value muted">{{ $appointment->student->user->phone_number ?: '—' }}</div>
                            </div>
                            <div class="pt-2">
                                <a href="{{ route('counselor.students.profile', $appointment->student) }}"
                                   class="profile-link">
                                    <i class="fas fa-user text-[9px]"></i> View Student Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Session Notes Form -->
            <div class="lg:col-span-2">
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-pen text-[9px] sm:text-xs"></i></div>
                        <div>
                            <h2 class="panel-title">Session Notes</h2>
                            <p class="panel-subtitle hidden sm:block">Record observations and follow-up actions</p>
                        </div>
                    </div>

                    <form action="{{ route('counselor.appointments.session.store', $appointment) }}" method="POST" class="p-4 sm:p-5 md:p-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <label for="appointment_type" class="field-label">Type of Appointment <span class="text-[#b91c1c]">*</span></label>
                                <select name="appointment_type"
                                        id="appointment_type"
                                        class="select-field text-xs sm:text-sm"
                                        required>
                                    <option value="">Select type</option>
                                    @foreach($appointmentTypeOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('appointment_type', $latestSessionNote->appointment_type ?? '') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_type')
                                    <p class="error-text">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Referred By / Referred To --}}
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- Referred By --}}
                            <div class="rounded-lg border p-3" style="border-color:var(--border-soft);background:rgba(250,248,245,0.6);">
                                <label class="flex items-center gap-2 cursor-pointer select-none mb-2">
                                    <input type="checkbox" id="chk_referred_by"
                                           class="custom-checkbox"
                                           {{ old('referred_by_source', $latestSessionNote->referred_by_source ?? '') ? 'checked' : '' }}
                                           onchange="toggleReferral('referred_by_source', this.checked)">
                                    <span class="text-xs font-semibold" style="color:var(--maroon-800)">Referred By</span>
                                </label>
                                <div id="referred_by_box" class="{{ old('referred_by_source', $latestSessionNote->referred_by_source ?? '') ? '' : 'hidden' }}">
                                    <input type="text"
                                           id="referred_by_source"
                                           name="referred_by_source"
                                           value="{{ old('referred_by_source', $latestSessionNote->referred_by_source ?? '') }}"
                                           placeholder="e.g. Teacher, Professor, Parent, Friend"
                                           class="input-field text-xs"
                                           maxlength="255">
                                    @error('referred_by_source')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Referred To --}}
                            <div class="rounded-lg border p-3" style="border-color:var(--border-soft);background:rgba(250,248,245,0.6);">
                                <label class="flex items-center gap-2 cursor-pointer select-none mb-2">
                                    <input type="checkbox" id="chk_referred_to"
                                           class="custom-checkbox"
                                           {{ old('referred_to_destination', $latestSessionNote->referred_to_destination ?? '') ? 'checked' : '' }}
                                           onchange="toggleReferral('referred_to_destination', this.checked)">
                                    <span class="text-xs font-semibold" style="color:var(--maroon-800)">Referred To</span>
                                </label>
                                <div id="referred_to_box" class="{{ old('referred_to_destination', $latestSessionNote->referred_to_destination ?? '') ? '' : 'hidden' }}">
                                    <input type="text"
                                           id="referred_to_destination"
                                           name="referred_to_destination"
                                           value="{{ old('referred_to_destination', $latestSessionNote->referred_to_destination ?? '') }}"
                                           placeholder="e.g. Outside mental health professional"
                                           class="input-field text-xs"
                                           maxlength="255">
                                    @error('referred_to_destination')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="field-label">Root Causes</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($rootCauseOptions as $value => $label)
                                    <div class="checkbox-option">
                                        <input type="checkbox"
                                               name="root_causes[]"
                                               value="{{ $value }}"
                                               {{ in_array($value, old('root_causes', $latestSessionNote->root_causes ?? []), true) ? 'checked' : '' }}>
                                        <label>{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('root_causes')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                            @error('root_causes.*')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="notes" class="field-label">Session Notes <span class="text-[#b91c1c]">*</span></label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="10"
                                      class="textarea-field"
                                      required>{{ old('notes', $latestSessionNote->notes ?? '') }}</textarea>
                            @error('notes')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="follow_up_actions" class="field-label">Assignment / Follow-up Actions</label>
                            <textarea name="follow_up_actions"
                                      id="follow_up_actions"
                                      rows="5"
                                      class="textarea-field">{{ old('follow_up_actions', $latestSessionNote->follow_up_actions ?? '') }}</textarea>
                            @error('follow_up_actions')
                                <p class="error-text">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                                <i class="fas fa-save mr-1.5 text-[9px] sm:text-xs"></i> Save Session Notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Follow-up Modal -->
    <div id="followupModal" class="modal-backdrop hidden" onclick="handleFollowupBackdropClick(event)">
        <div class="modal-card" onclick="event.stopPropagation();">
            <div class="modal-header">
                <h3 class="text-sm font-semibold text-[#2c2420]">Book Follow-up Appointment</h3>
                <button type="button" onclick="closeFollowupModal()" class="modal-close" title="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('counselor.appointments.followup.store', $appointment) }}" method="POST" class="modal-body">
                @csrf
                <input type="hidden" name="counselor_id" value="{{ $effectiveCounselorId }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="followup_booking_type" class="field-label">Type of Booking <span class="text-[#b91c1c]">*</span></label>
                        <select name="booking_type"
                                id="followup_booking_type"
                                class="select-field text-xs sm:text-sm"
                                required>
                            <option value="">Choose a booking type</option>
                            <option value="Counseling" {{ old('booking_type') === 'Counseling' ? 'selected' : '' }}>Counseling</option>
                            <option value="Consultation" {{ old('booking_type') === 'Consultation' ? 'selected' : '' }}>Consultation</option>
                        </select>
                        @error('booking_type')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="followup_appointment_date" class="field-label">Date <span class="text-[#b91c1c]">*</span></label>
                        <input type="date"
                               name="appointment_date"
                               id="followup_appointment_date"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="input-field text-xs sm:text-sm"
                               required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="field-label">Time Slot <span class="text-[#b91c1c]">*</span></label>
                        <div id="followup_time_slots" class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-40 overflow-y-auto p-2 border border-[#e5e0db] rounded-lg bg-white">
                            <div class="col-span-full text-center text-[#8b7e76] text-xs py-3">
                                Select a date to see available time slots
                            </div>
                        </div>
                        <input type="hidden" name="start_time" id="followup_selected_time">
                        @error('start_time')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="followup_concern" class="field-label">Concern / Agenda <span class="text-[#b91c1c]">*</span></label>
                    <textarea name="concern"
                              id="followup_concern"
                              rows="3"
                              class="textarea-field"
                              required>{{ old('concern', 'Follow-up session') }}</textarea>
                </div>

                <div class="mt-4">
                    <div class="auto-approve">
                        <input type="checkbox"
                               name="auto_approve"
                               id="followup_auto_approve"
                               value="1"
                               checked>
                        <label for="followup_auto_approve">
                            Auto-approve this follow-up appointment
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            onclick="closeFollowupModal()"
                            class="secondary-btn px-4 py-2 text-xs sm:text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                            id="followup_submit_btn"
                            class="primary-btn px-4 py-2 text-xs sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Book Follow-up
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openFollowupModal() {
            const modal = document.getElementById('followupModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
            updateFollowupSubmitState();
        }

        function closeFollowupModal() {
            const modal = document.getElementById('followupModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function handleFollowupBackdropClick(event) {
            if (event.target && event.target.id === 'followupModal') {
                closeFollowupModal();
            }
        }

        function updateFollowupSubmitState() {
            const btn = document.getElementById('followup_submit_btn');
            const selectedTime = document.getElementById('followup_selected_time');
            if (!btn || !selectedTime) {
                return;
            }
            btn.disabled = !selectedTime.value;
        }

        function setSelectedFollowupSlot(startTime, buttonEl) {
            const selectedTime = document.getElementById('followup_selected_time');
            if (!selectedTime) {
                return;
            }
            selectedTime.value = startTime;

            document.querySelectorAll('#followup_time_slots button[data-start]').forEach((btn) => {
                btn.classList.remove('selected');
            });

            if (buttonEl) {
                buttonEl.classList.add('selected');
            }
            updateFollowupSubmitState();
        }

        function toggleReferral(fieldId, show) {
            const boxId = fieldId === 'referred_by_source' ? 'referred_by_box' : 'referred_to_box';
            const box = document.getElementById(boxId);
            const input = document.getElementById(fieldId);
            if (show) {
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
                if (input) input.value = '';
            }
        }

        async function loadFollowupSlots() {
            const dateInput = document.getElementById('followup_appointment_date');
            const slotsContainer = document.getElementById('followup_time_slots');
            const selectedTime = document.getElementById('followup_selected_time');

            if (!dateInput || !slotsContainer || !selectedTime) {
                return;
            }

            selectedTime.value = '';
            updateFollowupSubmitState();

            if (!dateInput.value) {
                slotsContainer.innerHTML = '<div class="col-span-full text-center text-[#8b7e76] text-xs py-3">Select a date to see available time slots</div>';
                return;
            }

            slotsContainer.innerHTML = '<div class="col-span-full text-center text-[#8b7e76] text-xs py-3">Loading slots...</div>';

            const counselorId = {{ (int) $effectiveCounselorId }};
            const url = `{{ route('counselor.appointments.followup-available-slots') }}?counselor_id=${encodeURIComponent(counselorId)}&date=${encodeURIComponent(dateInput.value)}`;

            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();

                const available = Array.isArray(data.available_slots) ? data.available_slots : [];
                if (!available.length) {
                    slotsContainer.innerHTML = `<div class="col-span-full text-center text-[#8b7e76] text-xs py-3">${data.message ? data.message : 'No available slots for this date'}</div>`;
                    return;
                }

                slotsContainer.innerHTML = '';
                available.forEach((slot) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.dataset.start = slot.start;
                    btn.className = 'time-slot';
                    btn.textContent = slot.display || `${slot.start} - ${slot.end}`;
                    btn.addEventListener('click', () => setSelectedFollowupSlot(slot.start, btn));
                    slotsContainer.appendChild(btn);
                });
            } catch (e) {
                slotsContainer.innerHTML = '<div class="col-span-full text-center text-red-500 text-xs py-3">Failed to load slots. Please try again.</div>';
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('followup_appointment_date');
            if (dateInput) {
                dateInput.addEventListener('change', loadFollowupSlots);
            }
            updateFollowupSubmitState();
        });
    </script>
@endsection