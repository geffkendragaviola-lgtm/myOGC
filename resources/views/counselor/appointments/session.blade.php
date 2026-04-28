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
        max-width: 52rem; width: 100%; max-height: 90vh;
        overflow: hidden; display: flex; flex-direction: column;
    }
    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.7); flex-shrink: 0; position: relative;
    }
    .modal-header::before {
        content: ""; position: absolute; inset-inline: 0; top: 0; height: 3px;
        background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%);
    }
    .modal-close {
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); transition: all 0.18s ease; font-size: 0.9rem;
    }
    .modal-close:hover { background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .modal-body { padding: 1.25rem; overflow-y: auto; flex: 1; }
    .modal-footer {
        padding: 1rem 1.25rem; border-top: 1px solid var(--border-soft);
        display: flex; justify-content: flex-end; gap: 0.75rem;
        background: rgba(250,248,245,0.5); flex-shrink: 0;
        position: sticky;
        bottom: 0;
        z-index: 10;
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(8px);
    }

    .time-slot {
        padding: 0.5rem; border-radius: 0.4rem; border: 1px solid var(--border-soft);
        text-align: center; font-size: 0.7rem; font-weight: 500;
        transition: all 0.18s ease; cursor: pointer; background: rgba(255,255,255,0.9);
    }
    .time-slot:hover { border-color: var(--maroon-700); background: rgba(254,249,231,0.6); }
    .time-slot.selected { border-color: var(--maroon-700); background: rgba(254,249,231,0.9); color: var(--maroon-700); font-weight: 600; }

    .calendar-card { border: 1px solid var(--border-soft); border-radius: 0.75rem; background: rgba(255,255,255,0.95); padding: 1rem; }
    .calendar-nav { display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0; margin-bottom: 0.5rem; }
    .calendar-nav-btn { width: 2.25rem; height: 2.25rem; border-radius: 999px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-soft); color: var(--text-secondary); transition: all 0.18s ease; font-size: 1rem; background: white; cursor: pointer; }
    .calendar-nav-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); color: var(--maroon-700); }
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.35rem; }
    .calendar-day-header { text-align: center; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); }
    .calendar-day { width: 100%; aspect-ratio: 1; border-radius: 0.4rem; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 500; border: 1px solid transparent; transition: all 0.18s ease; cursor: default; background: none; }
    .calendar-day.available { border-color: rgba(122,42,42,0.3); color: var(--maroon-700); background: rgba(254,249,231,0.5); cursor: pointer; }
    .calendar-day.available:hover { background: rgba(212,175,55,0.2); border-color: var(--gold-400); }
    .calendar-day.selected { background: var(--maroon-700) !important; color: #fef9e7 !important; border-color: var(--maroon-700) !important; }
    .calendar-day:disabled, .calendar-day.disabled { color: var(--text-muted); cursor: not-allowed; opacity: 0.4; }
    .calendar-status { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.5rem; min-height: 1rem; }
    .calendar-status.success { color: #065f46; }
    .calendar-status.error { color: #b91c1c; }

    .auto-approve {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem; border-radius: 0.4rem;
        background: rgba(254,249,231,0.4);
    }
    .auto-approve input { width: 1rem; height: 1rem; accent-color: var(--maroon-700); }
    .auto-approve label { font-size: 0.75rem; color: var(--text-secondary); }

    .custom-checkbox {
        width: 1rem; height: 1rem; accent-color: var(--maroon-700); cursor: pointer;
    }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field, .textarea-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; }
        .detail-card .grid { grid-template-columns: 1fr !important; }
        .checkbox-option label { font-size: 0.7rem; }
        .time-slot { padding: 0.4rem; font-size: 0.65rem; }
        .modal-card { max-height: 95vh; margin: 0.5rem; }
        .modal-header { padding: 0.75rem 1rem; }
        .modal-body { padding: 1rem; }
        .hero-card .flex { flex-direction: column; align-items: flex-start !important; }
    }
</style>

@php
    $sessionStressResponses = $appointment->student->needsAssessment?->stress_responses ?? [];
    $sessionStressResponses = is_array($sessionStressResponses) ? $sessionStressResponses : [];
    $sessionRiskKeywords    = ['Hurt myself', 'Attempted to end my life', 'Thought it would be better dead'];
    $sessionSelfHarmRisk    = !$appointment->student->high_risk_overridden
        && count(array_intersect($sessionRiskKeywords, $sessionStressResponses)) > 0;
    $sessionIsHighRisk      = $appointment->student->is_high_risk || $sessionSelfHarmRisk;
@endphp

<div class="min-h-screen session-form-shell">
    <div class="session-form-glow one"></div>
    <div class="session-form-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
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
                                @if($sessionIsHighRisk)
                                    <span class="ml-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-100 text-red-700 border border-red-200">
                                        <i class="fas fa-exclamation-triangle text-[9px]"></i> High-risk individual
                                    </span>
                                @endif
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

        {{-- High-Risk Banner — only show if flagged, no "not flagged" noise --}}
        @if($sessionIsHighRisk)
        <div class="panel-card mb-5" style="border-left:3px solid #dc2626;">
            <div class="relative p-3 sm:p-4 flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 flex-1 min-w-0">
                    <span class="flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center bg-red-100 text-red-600 text-xs">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                    <div class="min-w-0">
                        <p class="text-xs font-700 text-red-800 font-semibold">High-Risk Individual</p>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            @if($sessionSelfHarmRisk)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-orange-50 text-orange-800 border border-orange-200">
                                    <i class="fas fa-notes-medical text-[8px]"></i> Assessment-Based
                                </span>
                            @endif
                            @if($appointment->student->is_high_risk)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-800 border border-red-200">
                                    <i class="fas fa-flag text-[8px]"></i> Counselor Flagged{{ $appointment->student->high_risk_notes ? ' — ' . Str::limit($appointment->student->high_risk_notes, 50) : '' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <button type="button" onclick="toggleHighRiskModal()" class="secondary-btn px-3 py-1.5 text-xs flex-shrink-0">
                    <i class="fas fa-edit mr-1 text-[9px]"></i>Update Flag
                </button>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Left Sidebar -->
            <div class="lg:col-span-1 space-y-4">

                <!-- Appointment + Student combined -->
                <div class="detail-card">
                    <div class="panel-topline"></div>
                    <div class="p-4">
                        {{-- Student mini-profile --}}
                        <div class="flex items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid var(--border-soft);">
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm text-[var(--maroon-700)]" style="background:rgba(254,249,231,0.8);border:1px solid rgba(212,175,55,0.3);">
                                {{ strtoupper(substr($appointment->student->user->first_name,0,1)) }}{{ strtoupper(substr($appointment->student->user->last_name,0,1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-[#2c2420] truncate">{{ $appointment->student->user->full_name }}</div>
                                <div class="text-[10px] text-[#8b7e76] font-mono">{{ $appointment->student->student_id }}</div>
                                <div class="text-[10px] text-[#8b7e76]">{{ $appointment->student->college->name ?? 'N/A' }} · {{ $appointment->student->year_level }}</div>
                            </div>
                        </div>

                        {{-- Appointment details as compact rows --}}
                        <div class="space-y-2.5">
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Date</span>
                                <span class="detail-value text-right">{{ $appointment->appointment_date->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Time</span>
                                <span class="detail-value text-right">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</span>
                            </div>
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Type</span>
                                <span class="detail-value text-right">{{ $appointment->booking_type ?: '—' }}</span>
                            </div>
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Category</span>
                                <span class="detail-value text-right">{{ $appointment->booking_category ? ucwords(str_replace('-',' ',$appointment->booking_category)) : '—' }}</span>
                            </div>
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Status</span>
                                <span class="detail-value text-right">{{ ucwords(str_replace('_',' ',$appointment->status)) }}</span>
                            </div>
                            @if($appointment->case_number)
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Case #</span>
                                <span class="detail-value muted text-right font-mono">{{ $appointment->case_number }}</span>
                            </div>
                            @endif
                            @if($appointment->cancellation_reason)
                            <div class="pt-1">
                                <span class="detail-label">Cancellation Reason</span>
                                <div class="text-xs mt-1 p-2 rounded-lg" style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);color:#b91c1c;">{{ $appointment->cancellation_reason }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="mt-3 pt-3 flex items-center justify-between" style="border-top:1px solid var(--border-soft);">
                            <a href="{{ route('counselor.students.profile', $appointment->student) }}" class="profile-link">
                                <i class="fas fa-user text-[9px]"></i> View Profile
                            </a>
                            @if(!$sessionIsHighRisk)
                            <button type="button" onclick="toggleHighRiskModal()" class="text-[10px] text-[#8b7e76] hover:text-[var(--maroon-700)] flex items-center gap-1 transition-colors">
                                <i class="fas fa-flag text-[9px]"></i> Flag
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Follow-up (only if exists) -->
                @if($followupAppointment)
                <div class="detail-card" style="border-color:rgba(212,175,55,0.35);">
                    <div class="panel-topline"></div>
                    <div class="p-4">
                        <h2 class="panel-title mb-3">Follow-up Booked</h2>
                        <div class="space-y-2.5">
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Date</span>
                                <span class="detail-value text-right">{{ \Carbon\Carbon::parse($followupAppointment->appointment_date)->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Time</span>
                                <span class="detail-value text-right">{{ \Carbon\Carbon::parse($followupAppointment->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($followupAppointment->end_time)->format('g:i A') }}</span>
                            </div>
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Status</span>
                                <span class="detail-value text-right">{{ ucwords(str_replace('_',' ',$followupAppointment->status)) }}</span>
                            </div>
                            @if($followupAppointment->referred_by)
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Referred by</span>
                                <span class="detail-value text-right">{{ $followupAppointment->referred_by }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Reason / Concern -->
                <div class="detail-card">
                    <div class="panel-topline"></div>
                    <div class="p-4">
                        <h2 class="panel-title mb-3">Reason / Concern</h2>
                        @php
                            $rawConcern = $appointment->concern ?? '';
                            $catMatch = preg_match('/^\[([^\]]+)\]\s*/', $rawConcern, $m);
                            $category  = $catMatch ? $m[1] : '';
                            $rest      = $catMatch ? ltrim(substr($rawConcern, strlen($m[0]))) : '';
                            $parts     = $category ? explode("\n", $rest, 2) : [];
                            $itemsPart = $category ? trim($parts[0] ?? '') : '';
                            $narrative = $category ? trim($parts[1] ?? '') : '';
                            $items     = $category && $itemsPart ? array_filter(array_map('trim', explode(';', $itemsPart))) : [];
                        @endphp
                        @if(!$rawConcern)
                            <p class="text-xs text-[var(--text-muted)] italic">No concern recorded.</p>
                        @else
                        <div class="space-y-3">
                            @if($appointment->referred_by)
                            <div class="text-xs p-2 rounded-lg" style="background:rgba(212,175,55,0.07);border:1px solid rgba(212,175,55,0.2);">
                                <span class="detail-label" style="margin:0 0 0.2rem;">Referred by</span>
                                <div class="detail-value">{{ $appointment->referred_by }}</div>
                            </div>
                            @endif
                            @if($category)
                            <div>
                                <span class="detail-label">Category</span>
                                <div class="text-xs font-semibold" style="color:var(--maroon-700);">{{ $category }}</div>
                            </div>
                            @endif
                            @if(count($items))
                            <div>
                                <span class="detail-label">Items</span>
                                <ul class="mt-1 space-y-1">
                                    @foreach($items as $item)
                                    <li class="flex items-start gap-1.5 text-xs text-[#4a3a2a]">
                                        <i class="fas fa-check-square text-[9px] mt-0.5 flex-shrink-0" style="color:var(--maroon-700);"></i>{{ $item }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @if(!$category)
                            <div class="text-xs text-[#4a3a2a]">{{ $rawConcern }}</div>
                            @endif
                            @if($narrative)
                            <div class="pt-2" style="border-top:1px solid var(--border-soft);">
                                <span class="detail-label">Narrative</span>
                                <p class="text-xs text-[#4a3a2a] mt-1 leading-relaxed italic">"{{ $narrative }}"</p>
                            </div>
                            @endif
                            @if($appointment->mood_rating)
                            <div class="flex justify-between items-baseline gap-2">
                                <span class="detail-label" style="margin:0;flex-shrink:0;">Mood</span>
                                <span class="detail-value muted text-right">{{ $appointment->mood_rating }}</span>
                            </div>
                            @endif
                        </div>
                        @endif
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

                        {{-- Source of Referral / Referred Out --}}
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- Source of Referral (Referred) --}}
                            <div class="rounded-lg border p-3" style="border-color:var(--border-soft);background:rgba(250,248,245,0.6);">
                                @php $hasReferredBy = old('referred_by_source', $latestSessionNote->referred_by_source ?? $appointment->referred_by ?? ''); @endphp
                                <label class="flex items-center gap-2 cursor-pointer select-none mb-2">
                                    <input type="checkbox" id="chk_referred_by"
                                           class="custom-checkbox"
                                           {{ $hasReferredBy ? 'checked' : '' }}
                                           onchange="toggleReferral('referred_by_source', this.checked)">
                                    <span class="text-xs font-semibold" style="color:var(--maroon-800)">Source of Referral (Referred)</span>
                                </label>
                                <div id="referred_by_box" class="{{ $hasReferredBy ? '' : 'hidden' }}">
                                    <input type="text"
                                           id="referred_by_source"
                                           name="referred_by_source"
                                           value="{{ old('referred_by_source', $latestSessionNote->referred_by_source ?? $appointment->referred_by ?? '') }}"
                                           placeholder="e.g. Teacher, Professor, Parent, Friend"
                                           class="input-field text-xs"
                                           maxlength="255">
                                    @error('referred_by_source')
                                        <p class="error-text">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Referred Out --}}
                            <div class="rounded-lg border p-3" style="border-color:var(--border-soft);background:rgba(250,248,245,0.6);">
                                <label class="flex items-center gap-2 cursor-pointer select-none mb-2">
                                    <input type="checkbox" id="chk_referred_to"
                                           class="custom-checkbox"
                                           {{ old('referred_to_destination', $latestSessionNote->referred_to_destination ?? '') ? 'checked' : '' }}
                                           onchange="toggleReferral('referred_to_destination', this.checked)">
                                    <span class="text-xs font-semibold" style="color:var(--maroon-800)">Referred Out</span>
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
        <div class="modal-card" onclick="event.stopPropagation();" style="max-width:52rem;">

            {{-- Modal Header --}}
            <div class="modal-header" style="background:linear-gradient(135deg,var(--maroon-800) 0%,var(--maroon-700) 100%);border-radius:0.75rem 0.75rem 0 0;padding:1.1rem 1.5rem;">
                <div class="flex items-center gap-3">
                    <div style="width:2.25rem;height:2.25rem;border-radius:0.6rem;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-calendar-plus text-sm" style="color:#fef9e7;"></i>
                    </div>
                    <div>
                        <h3 style="margin:0;font-size:0.95rem;font-weight:600;color:#fef9e7;">Book Follow-up Appointment</h3>
                        <p style="margin:0;font-size:0.7rem;color:rgba(254,249,231,0.7);">
                            {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                            &mdash; {{ $appointment->student->student_id }}
                        </p>
                    </div>
                </div>
                <button type="button" onclick="closeFollowupModal()" class="modal-close" title="Close"
                        style="color:rgba(254,249,231,0.7);">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('counselor.appointments.followup.store', $appointment) }}" method="POST">
                @csrf
                <input type="hidden" name="counselor_id" value="{{ $effectiveCounselorId }}">

                <div class="modal-body" style="padding:1.5rem;">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                        {{-- Left column: fields --}}
                        <div class="space-y-4">

                            {{-- Type & Category --}}
                            <div style="background:rgba(250,248,245,0.7);border:1px solid var(--border-soft);border-radius:0.65rem;padding:1rem;">
                                <p style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-muted);margin:0 0 0.75rem;">Appointment Details</p>
                                <div class="space-y-3">
                                    <div>
                                        <label for="followup_booking_type" class="field-label">Type of Booking <span class="text-[#b91c1c]">*</span></label>
                                        <select name="booking_type" id="followup_booking_type" class="select-field text-xs sm:text-sm" required>
                                            <option value="">Choose a type</option>
                                            <option value="Counseling" {{ old('booking_type') === 'Counseling' ? 'selected' : '' }}>Counseling</option>
                                            <option value="Consultation" {{ old('booking_type') === 'Consultation' ? 'selected' : '' }}>Consultation</option>
                                        </select>
                                        @error('booking_type')<p class="error-text">{{ $message }}</p>@enderror
                                    </div>
                                    <div>
                                        <label for="followup_booking_category" class="field-label">Booking Category <span class="text-[#b91c1c]">*</span></label>
                                        <select name="booking_category" id="followup_booking_category" class="select-field text-xs sm:text-sm" required>
                                           
                                            <option value="walk-in" {{ old('booking_category') === 'walk-in' ? 'selected' : '' }}>Walk-in</option>
                                            <option value="referred" {{ old('booking_category') === 'referred' ? 'selected' : '' }}>Referred</option>
                                            <option value="called-in" {{ old('booking_category') === 'called-in' ? 'selected' : '' }}>Called-in</option>
                                        </select>
                                        @error('booking_category')<p class="error-text">{{ $message }}</p>@enderror
                                    </div>
                                    <div id="followupReferredByWrap" class="{{ old('booking_category') === 'referred' ? '' : 'hidden' }}">
                                        <label for="followup_referred_by" class="field-label">Referred by</label>
                                        <input type="text" name="referred_by" id="followup_referred_by" value="{{ old('referred_by') }}"
                                               class="input-field text-xs sm:text-sm" maxlength="255"
                                               placeholder="e.g. Teacher, Parent, Friend">
                                        @error('referred_by')<p class="error-text">{{ $message }}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Concern --}}
                            <div style="background:rgba(250,248,245,0.7);border:1px solid var(--border-soft);border-radius:0.65rem;padding:1rem;">
                                <p style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-muted);margin:0 0 0.75rem;">Concern / Agenda</p>
                                <textarea name="concern" id="followup_concern" rows="4"
                                          class="textarea-field" required
                                          style="min-height:90px;">{{ old('concern', 'Follow-up session') }}</textarea>
                            </div>

                            {{-- Time Slots --}}
                            <div id="fuSlotWrap" style="background:rgba(250,248,245,0.7);border:1px solid var(--border-soft);border-radius:0.65rem;padding:1rem;">
                                <p style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-muted);margin:0 0 0.75rem;">Available Time Slots</p>
                                <div id="followup_time_slots" class="grid grid-cols-2 gap-2 max-h-44 overflow-y-auto">
                                    <div class="col-span-full text-center text-[#8b7e76] text-xs py-4 border border-dashed border-[#e5e0db] rounded-lg">
                                        Select a date to see available time slots
                                    </div>
                                </div>
                                <input type="hidden" name="start_time" id="followup_selected_time">
                                @error('start_time')<p class="error-text">{{ $message }}</p>@enderror
                            </div>

                            {{-- Override Availability --}}
                            <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;padding:0.65rem 0.85rem;border:1px solid #fca5a5;border-radius:0.6rem;background:rgba(255,241,242,0.5);">
                                <input type="checkbox" name="override_availability" id="fuOverrideCheck" value="1"
                                       onchange="toggleFollowupOverride(this.checked)"
                                       style="width:1rem;height:1rem;accent-color:#dc2626;cursor:pointer;">
                                <span style="font-size:0.78rem;color:#991b1b;font-weight:600;display:flex;align-items:center;gap:0.4rem;">
                                    <i class="fas fa-bolt text-[10px]"></i>
                                    Override Availability — book outside set hours / daily limit
                                </span>
                            </label>

                            {{-- Auto-approve --}}
                            <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;padding:0.65rem 0.85rem;border:1px solid var(--border-soft);border-radius:0.6rem;background:rgba(254,249,231,0.4);">
                                <input type="checkbox" name="auto_approve" id="followup_auto_approve" value="1" checked
                                       style="width:1rem;height:1rem;accent-color:var(--maroon-700);">
                                <span style="font-size:0.78rem;color:var(--text-secondary);">Auto-approve this follow-up appointment</span>
                            </label>
                        </div>

                        {{-- Right column: calendar --}}
                        <div id="fuCalendarWrap">
                            <div style="background:rgba(250,248,245,0.7);border:1px solid var(--border-soft);border-radius:0.65rem;padding:1rem;">
                                <p style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:var(--text-muted);margin:0 0 0.75rem;">Select Date <span style="color:#b91c1c;">*</span></p>
                                <div class="calendar-card" style="border:none;padding:0;background:transparent;">
                                    <div class="calendar-nav">
                                        <button type="button" id="fuCalPrev" class="calendar-nav-btn">‹</button>
                                        <h3 id="fuCalMonthLabel" class="text-sm font-semibold text-[#2c2420]"></h3>
                                        <button type="button" id="fuCalNext" class="calendar-nav-btn">›</button>
                                    </div>
                                    <div class="calendar-grid mb-2">
                                        <span class="calendar-day-header">Sun</span>
                                        <span class="calendar-day-header">Mon</span>
                                        <span class="calendar-day-header">Tue</span>
                                        <span class="calendar-day-header">Wed</span>
                                        <span class="calendar-day-header">Thu</span>
                                        <span class="calendar-day-header">Fri</span>
                                        <span class="calendar-day-header">Sat</span>
                                    </div>
                                    <div id="fuCalGrid" class="calendar-grid"></div>
                                    <p id="fuCalStatus" class="calendar-status mt-2">Loading available dates...</p>
                                </div>
                                <input type="hidden" name="appointment_date" id="followup_appointment_date" required>
                                @error('appointment_date')<p class="error-text">{{ $message }}</p>@enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer">
                    <button type="button" onclick="closeFollowupModal()" class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                        Cancel
                    </button>
                    <button type="submit" id="followup_submit_btn"
                            class="primary-btn px-5 py-2.5 text-xs sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-calendar-check mr-1.5 text-[9px] sm:text-xs"></i> Book Follow-up
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
            // Reset override state on open
            const overrideCheck = document.getElementById('fuOverrideCheck');
            if (overrideCheck) overrideCheck.checked = false;
            toggleFollowupOverride(false);
            updateFollowupSubmitState();
            updateFollowupReferredByVisibility();
        }

        function closeFollowupModal() {
            const modal = document.getElementById('followupModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function updateFollowupReferredByVisibility() {
            const category = document.getElementById('followup_booking_category');
            const wrap = document.getElementById('followupReferredByWrap');
            const input = document.getElementById('followup_referred_by');
            if (!category || !wrap) return;
            const show = String(category.value) === 'referred';
            wrap.classList.toggle('hidden', !show);
        }

        function toggleFollowupOverride(enabled) {
            const calWrap   = document.getElementById('fuCalendarWrap');
            const slotWrap  = document.getElementById('fuSlotWrap');
            const manualWrap = document.getElementById('fuManualTimeWrap');
            const dateHidden = document.getElementById('followup_appointment_date');
            const timeHidden = document.getElementById('followup_selected_time');

            if (enabled) {
                if (manualWrap) manualWrap.classList.remove('hidden');
                // Sync manual date if already set
                const manualTime = document.getElementById('fuManualTime');
                if (manualTime && manualTime.value) {
                    timeHidden.value = manualTime.value;
                } else {
                    timeHidden.value = '';
                }
            } else {
                if (manualWrap) manualWrap.classList.add('hidden');
                // Clear manual inputs
                const manualTime = document.getElementById('fuManualTime');
                if (manualTime) manualTime.value = '';
                const manualDate = document.getElementById('fuManualDate');
                if (manualDate) manualDate.value = '';
                timeHidden.value = '';
                dateHidden.value = '';
            }
            updateFollowupSubmitState();

            // Refresh calendar availability & slots in case override affects enabled dates/slots
            if (!enabled && dateHidden && dateHidden.value) {
                loadFollowupSlots(dateHidden.value);
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
            const overrideCheck = document.getElementById('fuOverrideCheck');
            const dateHidden = document.getElementById('followup_appointment_date');
            if (!btn || !selectedTime) return;

            const isOverride = overrideCheck && overrideCheck.checked;
            if (isOverride) {
                const manualDate = document.getElementById('fuManualDate');
                const manualTime = document.getElementById('fuManualTime');
                const hasCalendarSelection = Boolean(dateHidden && dateHidden.value && selectedTime.value);
                const hasManualSelection = Boolean(manualDate && manualTime && manualDate.value && manualTime.value);
                btn.disabled = !(hasCalendarSelection || hasManualSelection);
            } else {
                btn.disabled = !selectedTime.value;
            }
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

        async function loadFollowupSlots(dateValue) {
            const slotsContainer = document.getElementById('followup_time_slots');
            const selectedTime = document.getElementById('followup_selected_time');
            const overrideCheck = document.getElementById('fuOverrideCheck');
            if (!slotsContainer || !selectedTime) return;

            selectedTime.value = '';
            updateFollowupSubmitState();

            if (!dateValue) {
                slotsContainer.innerHTML = '<div class="col-span-full text-center text-[#8b7e76] text-xs py-3">Select a date to see available time slots</div>';
                return;
            }

            slotsContainer.innerHTML = '<div class="col-span-full text-center text-[#8b7e76] text-xs py-3">Loading slots...</div>';

            const counselorId = {{ (int) $effectiveCounselorId }};
            const isOverride = Boolean(overrideCheck && overrideCheck.checked);
            const url = `{{ route('counselor.appointments.followup-available-slots') }}?counselor_id=${encodeURIComponent(counselorId)}&date=${encodeURIComponent(dateValue)}&override_availability=${isOverride ? 1 : 0}`;

            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                const available = Array.isArray(data.available_slots) ? data.available_slots : [];
                if (!available.length) {
                    slotsContainer.innerHTML = `<div class="col-span-full text-center text-[#8b7e76] text-xs py-3">${data.message || 'No available slots for this date'}</div>`;
                    return;
                }
                slotsContainer.innerHTML = '';
                available.forEach(slot => {
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

        // ── Follow-up Calendar ────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            updateFollowupSubmitState();

            const followupBookingCategory = document.getElementById('followup_booking_category');
            if (followupBookingCategory) {
                followupBookingCategory.addEventListener('change', updateFollowupReferredByVisibility);
            }

            updateFollowupReferredByVisibility();
        
            // Ensure modal stays scrolled to top on open
            const modal = document.getElementById('followupModal');
            if (modal) {
                modal.addEventListener('shown.bs.modal', () => {
                    modal.scrollTop = 0;
                });
            }

            const counselorId = {{ (int) $effectiveCounselorId }};
            const dateHidden   = document.getElementById('followup_appointment_date');
            const calGrid      = document.getElementById('fuCalGrid');
            const calLabel     = document.getElementById('fuCalMonthLabel');
            const calStatus    = document.getElementById('fuCalStatus');
            const calPrev      = document.getElementById('fuCalPrev');
            const calNext      = document.getElementById('fuCalNext');
            const overrideCheck = document.getElementById('fuOverrideCheck');

            if (!calGrid) return;

            const minDate = new Date();
            minDate.setHours(0,0,0,0);
            // Counselors can book same-day appointments

            let currentMonth = new Date(minDate.getFullYear(), minDate.getMonth(), 1);
            let selectedDate  = null;
            let availMap      = new Map();
            let reqId         = 0;

            function fmt(d) {
                return `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
            }
            function sameDay(a,b) {
                return a && b && a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate();
            }
            function setStatus(msg, tone='') {
                calStatus.textContent = msg;
                calStatus.className = 'calendar-status' + (tone ? ' '+tone : '');
            }

            function renderCal() {
                calLabel.textContent = currentMonth.toLocaleString('en-US',{month:'long',year:'numeric'});
                calGrid.innerHTML = '';
                const startDay = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), 1).getDay();
                const daysInMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth()+1, 0).getDate();
                const isOverride = Boolean(overrideCheck && overrideCheck.checked);

                for (let i=0; i<startDay; i++) calGrid.appendChild(document.createElement('div'));

                for (let day=1; day<=daysInMonth; day++) {
                    const date = new Date(currentMonth.getFullYear(), currentMonth.getMonth(), day);
                    const dv   = fmt(date);
                    const past = date < minDate;
                    const avail = availMap.get(dv) === true;
                    const btn  = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = day;
                    btn.className = 'calendar-day';

                    if (past || (!isOverride && !avail)) {
                        btn.disabled = true;
                        btn.classList.add('disabled');
                    } else {
                        btn.classList.add('available');
                    }
                    if (selectedDate && sameDay(selectedDate, date)) btn.classList.add('selected');

                    btn.addEventListener('click', () => {
                        if (btn.disabled) return;
                        selectedDate = date;
                        dateHidden.value = dv;
                        setStatus(`Selected: ${date.toLocaleDateString('en-US',{month:'long',day:'numeric',year:'numeric'})}`, 'success');
                        renderCal();
                        loadFollowupSlots(dv);
                    });
                    calGrid.appendChild(btn);
                }
            }

            async function loadMonthAvail() {
                availMap = new Map();
                renderCal();
                setStatus('Checking available dates...');
                const month = `${currentMonth.getFullYear()}-${String(currentMonth.getMonth()+1).padStart(2,'0')}`;
                const id = ++reqId;
                try {
                    const isOverride = Boolean(overrideCheck && overrideCheck.checked);
                    const res  = await fetch(`/appointments/available-dates?counselor_id=${counselorId}&month=${month}&allow_today=1&override_availability=${isOverride ? 1 : 0}`);
                    const data = await res.json();
                    if (id !== reqId) return;
                    Object.entries(data.availability || {}).forEach(([k,v]) => availMap.set(k, v===true));
                } catch(e) {
                    if (id !== reqId) return;
                    setStatus('Unable to load available dates.', 'error');
                    renderCal(); return;
                }
                if (id !== reqId) return;
                const hasAny = [...availMap.values()].some(v=>v);
                setStatus(hasAny ? 'Available dates are highlighted. Select a date to continue.' : 'No available dates this month.', hasAny ? '' : 'error');
                if (selectedDate && !availMap.get(fmt(selectedDate))) {
                    selectedDate = null; dateHidden.value = '';
                }
                renderCal();
            }

            calPrev.addEventListener('click', () => {
                currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth()-1, 1);
                loadMonthAvail();
            });
            calNext.addEventListener('click', () => {
                currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth()+1, 1);
                loadMonthAvail();
            });

            overrideCheck?.addEventListener('change', () => {
                // Override affects which dates are selectable + which slots are returned
                loadMonthAvail();
                if (dateHidden && dateHidden.value) {
                    loadFollowupSlots(dateHidden.value);
                }
            });

            loadMonthAvail();
        });
    </script>

    {{-- High-Risk Modal --}}
    <div id="highRiskModal" style="display:none;position:fixed;inset:0;background:rgba(44,36,32,0.6);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
        <div style="background:white;border-radius:0.75rem;max-width:480px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.15);">
            <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border-soft);display:flex;align-items:center;justify-content:space-between;">
                <h3 style="margin:0;font-size:0.95rem;font-weight:600;color:var(--text-primary);display:flex;align-items:center;gap:0.5rem;">
                    <i class="fas fa-exclamation-triangle" style="color:#dc2626;font-size:0.85rem;"></i> High-Risk Status
                </h3>
                <button onclick="toggleHighRiskModal()" style="background:none;border:none;font-size:1.25rem;color:var(--text-muted);cursor:pointer;line-height:1;">×</button>
            </div>
            <div style="padding:1.25rem;">
                <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;padding:0.75rem;border:1px solid var(--border-soft);border-radius:0.5rem;margin-bottom:1rem;">
                    <input type="checkbox" id="hr_is_high_risk" {{ ($appointment->student->is_high_risk || $sessionSelfHarmRisk) ? 'checked' : '' }}
                           style="width:1.1rem;height:1.1rem;cursor:pointer;accent-color:#dc2626;">
                    <span style="font-size:0.85rem;font-weight:600;color:var(--text-primary);">Flag this student as high-risk</span>
                </label>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:var(--text-secondary);margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.06em;">Notes (Optional)</label>
                    <textarea id="hr_notes" rows="4"
                              placeholder="Reason for flagging, observations, concerns..."
                              style="width:100%;padding:0.65rem 0.75rem;border:1px solid var(--border-soft);border-radius:0.5rem;font-size:0.82rem;resize:vertical;outline:none;">{{ $appointment->student->high_risk_notes }}</textarea>
                </div>
            </div>
            <div style="padding:0.85rem 1.25rem;border-top:1px solid var(--border-soft);display:flex;gap:0.6rem;justify-content:flex-end;">
                <button onclick="toggleHighRiskModal()"
                        style="padding:0.5rem 1rem;border:1px solid var(--border-soft);background:white;color:var(--text-secondary);border-radius:0.5rem;font-size:0.82rem;font-weight:600;cursor:pointer;">
                    Cancel
                </button>
                <button id="hrSubmitBtn" onclick="submitHighRisk()"
                        style="padding:0.5rem 1rem;border:none;background:var(--maroon-700);color:white;border-radius:0.5rem;font-size:0.82rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.4rem;">
                    <i class="fas fa-save text-[10px]"></i> Save
                </button>
            </div>
        </div>
    </div>

    <script>
        function toggleHighRiskModal() {
            const modal = document.getElementById('highRiskModal');
            modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
        }

        function submitHighRisk() {
            const btn = document.getElementById('hrSubmitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-[10px]"></i> Saving...';

            fetch('{{ route("counselor.students.toggle-high-risk", $appointment->student) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({
                    is_high_risk: document.getElementById('hr_is_high_risk').checked,
                    high_risk_notes: document.getElementById('hr_notes').value,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) { location.reload(); }
                else {
                    alert(data.message || 'Failed to update.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-save text-[10px]"></i> Save';
                }
            })
            .catch(() => {
                alert('An error occurred. Please try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save text-[10px]"></i> Save';
            });
        }
    </script>
@endsection