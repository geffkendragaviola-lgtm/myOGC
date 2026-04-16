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

    .session-view-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .session-view-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .session-view-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .session-view-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

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

    .cause-chip {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.45rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600;
        background: rgba(245,240,235,0.9); color: var(--text-secondary); border: 1px solid var(--border-soft);
    }

    .note-box {
        border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(250,248,245,0.9); padding: 0.75rem;
        font-size: 0.75rem; color: var(--text-primary); line-height: 1.5;
        white-space: pre-line;
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

    .edit-link {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.35rem 0.6rem; border-radius: 0.5rem;
        font-size: 0.65rem; font-weight: 500; transition: all 0.18s ease;
        color: var(--maroon-700); border: 1px solid rgba(122,42,42,0.4);
        background: rgba(254,249,231,0.6);
    }
    .edit-link:hover { background: rgba(212,175,55,0.2); border-color: var(--gold-400); }

    .profile-link {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.7rem; font-weight: 500; color: var(--maroon-700);
        transition: all 0.18s ease;
    }
    .profile-link:hover { color: var(--maroon-800); transform: translateX(2px); }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .primary-btn, .secondary-btn, .edit-link { width: 100%; justify-content: center; }
        .detail-card .grid { grid-template-columns: 1fr !important; }
        .note-box { font-size: 0.7rem; padding: 0.6rem; }
        .cause-chip { font-size: 0.6rem; padding: 0.15rem 0.35rem; }
        .hero-card .flex { flex-direction: column; align-items: flex-start !important; }
    }
</style>

<div class="min-h-screen session-view-shell">
    <div class="session-view-glow one"></div>
    <div class="session-view-glow two"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-notes-medical text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Counselor Portal
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">View Appointment Session</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">
                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                <span class="text-[#8b7e76]">({{ $appointment->student->student_id }})</span>
                            </p>
                            <p class="text-[10px] sm:text-xs text-[#8b7e76] mt-0.5">
                                {{ $appointment->student->college->name ?? 'N/A' }} • {{ $appointment->student->course }} • {{ $appointment->student->year_level }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('counselor.appointment-sessions.dashboard') }}"
                       class="secondary-btn px-4 py-2 text-xs sm:text-sm w-full md:w-auto">
                        <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>Back to Sessions
                    </a>
                </div>
            </div>
        </div>

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
                                <span class="detail-label">Status</span>
                                <div class="detail-value">{{ ucfirst($appointment->status) }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Booking Type</span>
                                <div class="detail-value muted">{{ $appointment->booking_type ?: '—' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Case Number</span>
                                <div class="detail-value muted">{{ $appointment->case_number ?: '—' }}</div>
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
                                <span class="detail-label">Email</span>
                                <div class="detail-value muted">{{ $appointment->student->user->email ?: '—' }}</div>
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

            <!-- Right: Session Notes -->
            <div class="lg:col-span-2">
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-sticky-note text-[9px] sm:text-xs"></i></div>
                        <div class="flex-1">
                            <h2 class="panel-title">Session Notes</h2>
                            <p class="panel-subtitle hidden sm:block">Read-only view of the latest session note</p>
                        </div>
                        <a href="{{ route('counselor.appointments.session', $appointment) }}"
                           class="edit-link">
                            <i class="fas fa-pen text-[9px] sm:text-xs"></i> Edit Session
                        </a>
                    </div>

                    <div class="p-4 sm:p-5 md:p-6">
                        @if(!$latestSessionNote)
                            <div class="text-[10px] sm:text-xs text-[#6b5e57]">
                                No session notes found for this appointment.
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <span class="detail-label">Type of Appointment</span>
                                    <div class="detail-value">
                                        {{ $latestSessionNote->appointment_type ? ucwords(str_replace('_', ' ', $latestSessionNote->appointment_type)) : '—' }}
                                    </div>
                                </div>
                                <div>
                                    <span class="detail-label">Session Date</span>
                                    <div class="detail-value">
                                        {{ $latestSessionNote->session_date ? $latestSessionNote->session_date->format('F j, Y') : '—' }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 sm:mt-6">
                                <span class="detail-label">Root Causes</span>
                                <div class="mt-2 flex flex-wrap gap-1.5 sm:gap-2">
                                    @php
                                        $rootCauses = is_array($latestSessionNote->root_causes ?? null) ? $latestSessionNote->root_causes : [];
                                    @endphp
                                    @if(empty($rootCauses))
                                        <span class="detail-value muted">—</span>
                                    @else
                                        @foreach($rootCauses as $cause)
                                            <span class="cause-chip">
                                                {{ ucwords(str_replace('_', ' ', $cause)) }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 sm:mt-6">
                                <span class="detail-label">Session Notes</span>
                                <div class="note-box mt-2">
                                    {{ $latestSessionNote->notes }}
                                </div>
                            </div>

                            <div class="mt-5 sm:mt-6">
                                <span class="detail-label">Assignment / Follow-up Actions</span>
                                <div class="note-box mt-2">
                                    {{ $latestSessionNote->follow_up_actions ?: '—' }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection