@extends('layouts.app')

@section('title', 'View Appointment Session - OGC')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c; --maroon-800: #5c1a1a; --maroon-700: #7a2a2a;
        --gold-400: #d4af37; --bg-warm: #faf8f5; --border-soft: #e5e0db;
        --text-primary: #2c2420; --text-secondary: #6b5e57; --text-muted: #8b7e76;
    }
    .session-view-shell { position:relative; overflow:hidden; background:var(--bg-warm); min-height:100vh; padding-bottom:2rem; }
    .session-view-glow { position:absolute; border-radius:50%; filter:blur(80px); pointer-events:none; opacity:0.25; }
    .session-view-glow.one { top:-30px; left:-40px; width:200px; height:200px; background:var(--gold-400); }
    .session-view-glow.two { bottom:-30px; right:-60px; width:220px; height:220px; background:var(--maroon-800); }
    .hero-card,.panel-card,.glass-card,.detail-card {
        position:relative; overflow:hidden; border-radius:0.75rem;
        border:1px solid var(--border-soft); background:rgba(255,255,255,0.95);
        backdrop-filter:blur(8px); box-shadow:0 2px 8px rgba(44,36,32,0.04); transition:box-shadow 0.2s ease;
    }
    .hero-card:hover,.panel-card:hover,.glass-card:hover,.detail-card:hover { box-shadow:0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before,.panel-card::before,.glass-card::before,.detail-card::before {
        content:""; position:absolute; inset:0; pointer-events:none;
        background:radial-gradient(circle at top right,rgba(212,175,55,0.06),transparent 30%);
    }
    .hero-icon { display:flex; align-items:center; justify-content:center; flex-shrink:0; width:2.75rem; height:2.75rem; border-radius:0.75rem; color:#fef9e7; background:linear-gradient(135deg,var(--maroon-800) 0%,var(--maroon-700) 100%); box-shadow:0 4px 12px rgba(92,26,26,0.15); }
    .hero-badge { display:inline-flex; align-items:center; gap:0.4rem; border-radius:999px; border:1px solid rgba(212,175,55,0.3); background:rgba(254,249,231,0.8); padding:0.2rem 0.55rem; font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.16em; color:var(--maroon-700); }
    .hero-badge-dot { width:0.3rem; height:0.3rem; border-radius:999px; background:var(--gold-400); }
    .panel-topline { position:absolute; inset-inline:0; top:0; height:3px; background:linear-gradient(90deg,var(--maroon-800) 0%,var(--gold-400) 50%,var(--maroon-800) 100%); }
    .panel-header { display:flex; align-items:center; gap:0.7rem; padding:0.85rem 1.25rem; border-bottom:1px solid var(--border-soft); }
    .panel-icon { width:2rem; height:2rem; border-radius:0.6rem; display:flex; align-items:center; justify-content:center; background:rgba(254,249,231,0.7); color:var(--maroon-700); }
    .panel-title { font-size:0.8rem; font-weight:600; color:var(--text-primary); }
    .panel-subtitle { font-size:0.68rem; color:var(--text-muted); margin-top:0.1rem; }
    .detail-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.16em; color:var(--text-muted); margin-bottom:0.25rem; display:block; }
    .detail-value { font-size:0.75rem; color:var(--text-primary); font-weight:500; }
    .detail-value.muted { color:var(--text-secondary); }
    .cause-chip { display:inline-flex; align-items:center; gap:0.3rem; padding:0.2rem 0.45rem; border-radius:999px; font-size:0.65rem; font-weight:600; background:rgba(245,240,235,0.9); color:var(--text-secondary); border:1px solid var(--border-soft); }
    .note-box { border:1px solid var(--border-soft); border-radius:0.6rem; background:rgba(250,248,245,0.9); padding:0.75rem; font-size:0.75rem; color:var(--text-primary); line-height:1.5; white-space:pre-line; }
    .primary-btn { border-radius:0.6rem; font-weight:600; transition:all 0.2s ease; display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; color:#fef9e7; background:linear-gradient(135deg,var(--maroon-800) 0%,var(--maroon-700) 100%); box-shadow:0 4px 10px rgba(92,26,26,0.15); }
    .primary-btn:hover { transform:translateY(-1px); box-shadow:0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn { border-radius:0.6rem; font-weight:600; transition:all 0.2s ease; display:inline-flex; align-items:center; justify-content:center; white-space:nowrap; color:var(--text-secondary); background:rgba(255,255,255,0.9); border:1px solid var(--border-soft); }
    .secondary-btn:hover { background:rgba(254,249,231,0.7); border-color:var(--maroon-700); }
    .edit-link { display:inline-flex; align-items:center; gap:0.3rem; padding:0.35rem 0.6rem; border-radius:0.5rem; font-size:0.65rem; font-weight:500; transition:all 0.18s ease; color:var(--maroon-700); border:1px solid rgba(122,42,42,0.4); background:rgba(254,249,231,0.6); }
    .edit-link:hover { background:rgba(212,175,55,0.2); border-color:var(--gold-400); }
    .profile-link { display:inline-flex; align-items:center; gap:0.3rem; font-size:0.7rem; font-weight:500; color:var(--maroon-700); transition:all 0.18s ease; }
    .profile-link:hover { color:var(--maroon-800); transform:translateX(2px); }
    @media(max-width:639px) {
        .panel-header { padding:0.75rem 1rem; }
        .primary-btn,.secondary-btn,.edit-link { width:100%; justify-content:center; }
        .detail-card .grid { grid-template-columns:1fr !important; }
        .note-box { font-size:0.7rem; padding:0.6rem; }
        .cause-chip { font-size:0.6rem; padding:0.15rem 0.35rem; }
        .hero-card .flex { flex-direction:column; align-items:flex-start !important; }
    }
</style>

@php
    $svStress   = $appointment->student->needsAssessment?->stress_responses ?? [];
    $svStress   = is_array($svStress) ? $svStress : [];
    $svRiskKeys = ['Hurt myself', 'Attempted to end my life', 'Thought it would be better dead'];
    $svSelfHarm = !$appointment->student->high_risk_overridden
        && count(array_intersect($svRiskKeys, $svStress)) > 0;
    $svHighRisk = $appointment->student->is_high_risk || $svSelfHarm;

    // Referred by/to from session notes (takes priority over appointment relation)
    $svReferredBy  = $latestSessionNote?->referred_by_source
        ?: $appointment->referred_by
        ?: ($appointment->originalCounselor?->user
            ? $appointment->originalCounselor->user->first_name . ' ' . $appointment->originalCounselor->user->last_name
            : null);
    $svReferredTo  = $latestSessionNote?->referred_to_destination
        ?: ($appointment->referredCounselor?->user
            ? $appointment->referredCounselor->user->first_name . ' ' . $appointment->referredCounselor->user->last_name
            : null);
@endphp

<div class="min-h-screen session-view-shell">
    <div class="session-view-glow one"></div>
    <div class="session-view-glow two"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        {{-- Header --}}
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="hero-icon"><i class="fas fa-notes-medical text-base sm:text-lg"></i></div>
                        <div class="min-w-0">
                            <div class="hero-badge"><span class="hero-badge-dot"></span>Counselor Portal</div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">View Appointment Session</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">
                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                <span class="text-[#8b7e76]">({{ $appointment->student->student_id }})</span>
                                @if($svHighRisk)
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
                    <a href="{{ route('counselor.appointment-sessions.dashboard') }}"
                       class="secondary-btn px-4 py-2 text-xs sm:text-sm w-full md:w-auto">
                        <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>Back to Sessions
                    </a>
                </div>
            </div>
        </div>

        {{-- High-Risk Banner --}}
        @if($svHighRisk)
        <div style="border-radius:0.75rem;border:1px solid #fecaca;background:#fff5f5;padding:1rem 1.25rem;margin-bottom:1.25rem;display:flex;flex-wrap:wrap;align-items:center;gap:0.75rem;border-left:4px solid #dc2626;">
            <div style="display:flex;align-items:center;gap:0.6rem;flex:1;min-width:0;">
                <span style="display:flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:0.5rem;background:#fee2e2;color:#dc2626;flex-shrink:0;">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                </span>
                <div style="min-width:0;">
                    <p style="margin:0;font-size:0.82rem;font-weight:700;color:#991b1b;">High-Risk Individual</p>
                    <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:0.35rem;">
                        @if($svSelfHarm)
                            <span style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.7rem;font-weight:600;background:#fff7ed;color:#9a3412;border:1px solid #fed7aa;">
                                <i class="fas fa-notes-medical text-[9px]"></i> Assessment-Based Risk
                            </span>
                        @endif
                        @if($appointment->student->is_high_risk)
                            <span style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.7rem;font-weight:600;background:#fee2e2;color:#991b1b;border:1px solid #fecaca;">
                                <i class="fas fa-flag text-[9px]"></i> Counselor Flagged
                                @if($appointment->student->high_risk_notes)
                                    <span style="font-weight:400;"> — {{ Str::limit($appointment->student->high_risk_notes, 60) }}</span>
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" onclick="toggleHighRiskModal()"
                    style="flex-shrink:0;padding:0.4rem 0.85rem;border-radius:0.5rem;border:1px solid #fca5a5;background:white;color:#991b1b;font-size:0.75rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.4rem;">
                <i class="fas fa-edit text-[10px]"></i> Update Flag
            </button>
        </div>
        @else
        <div style="border-radius:0.75rem;border:1px solid var(--border-soft);background:white;padding:0.75rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:0.75rem;">
            <p style="margin:0;font-size:0.8rem;color:var(--text-secondary);display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-shield-halved" style="color:#059669;"></i>
                This student is not currently flagged as high-risk.
            </p>
            <button type="button" onclick="toggleHighRiskModal()"
                    style="flex-shrink:0;padding:0.35rem 0.75rem;border-radius:0.5rem;border:1px solid var(--border-soft);background:white;color:var(--text-secondary);font-size:0.75rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.4rem;">
                <i class="fas fa-flag text-[10px]"></i> Flag as High-Risk
            </button>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

            {{-- Left Sidebar --}}
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">

                {{-- Appointment Details --}}
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
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} –
                                    {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                </div>
                            </div>
                            <div>
                                <span class="detail-label">Booking Type</span>
                                <div class="detail-value muted">{{ $appointment->booking_type ?: '—' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Booking Category</span>
                                <div class="detail-value muted">{{ $appointment->booking_category ? ucwords(str_replace('-',' ',$appointment->booking_category)) : '—' }}</div>
                            </div>
                            <div>
                                <span class="detail-label">Status</span>
                                <div class="detail-value">{{ ucwords(str_replace('_',' ',$appointment->status)) }}</div>
                            </div>
                            @if($appointment->cancellation_reason)
                            <div>
                                <span class="detail-label">Student's Reason</span>
                                <div class="detail-value" style="color:#b91c1c;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);border-radius:0.4rem;padding:0.5rem 0.65rem;font-size:0.82rem;">
                                    {{ $appointment->cancellation_reason }}
                                </div>
                            </div>
                            @endif
                            <div>
                                <span class="detail-label">Case Number</span>
                                <div class="detail-value muted">{{ $appointment->case_number ?: '—' }}</div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Student Details --}}
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
                                <a href="{{ route('counselor.students.profile', $appointment->student) }}" class="profile-link">
                                    <i class="fas fa-user text-[9px]"></i> View Student Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- High-Risk Panel removed from sidebar — shown as banner above --}}
            </div>{{-- end sidebar --}}

            {{-- Right: Session Notes (read-only) --}}
            <div class="lg:col-span-2">
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-sticky-note text-[9px] sm:text-xs"></i></div>
                        <div class="flex-1">
                            <h2 class="panel-title">Session Notes</h2>
                            <p class="panel-subtitle hidden sm:block">Read-only view of the latest session note</p>
                        </div>
                        <a href="{{ route('counselor.appointments.session', $appointment) }}" class="edit-link">
                            <i class="fas fa-pen text-[9px] sm:text-xs"></i> Edit Session
                        </a>
                    </div>

                    <div class="p-4 sm:p-5 md:p-6">
                        @if(!$latestSessionNote)
                            <p class="text-xs text-[#6b5e57]">No session notes found for this appointment.</p>
                        @else
                            {{-- Type of Appointment --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <span class="detail-label">Type of Appointment</span>
                                    <div class="detail-value">
                                        {{ $latestSessionNote->appointment_type ? ucwords(str_replace('_',' ',$latestSessionNote->appointment_type)) : '—' }}
                                    </div>
                                </div>
                                <div>
                                    <span class="detail-label">Session Date</span>
                                    <div class="detail-value">
                                        {{ $latestSessionNote->session_date ? $latestSessionNote->session_date->format('F j, Y') : '—' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Referred By / Referred To --}}
                            @php
                                $viewReferredBy = $latestSessionNote->referred_by_source ?: $appointment->referred_by;
                                $viewReferredTo = $latestSessionNote->referred_to_destination;
                            @endphp
                            @if($viewReferredBy || $viewReferredTo)
                            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @if($viewReferredBy)
                                <div style="padding:0.6rem 0.75rem;background:rgba(250,248,245,0.8);border:1px solid var(--border-soft);border-radius:0.5rem;">
                                    <span class="detail-label">Referred By</span>
                                    <div class="detail-value">{{ $viewReferredBy }}</div>
                                </div>
                                @endif
                                @if($viewReferredTo)
                                <div style="padding:0.6rem 0.75rem;background:rgba(250,248,245,0.8);border:1px solid var(--border-soft);border-radius:0.5rem;">
                                    <span class="detail-label">Referred To</span>
                                    <div class="detail-value">{{ $viewReferredTo }}</div>
                                </div>
                                @endif
                            </div>
                            @endif

                            {{-- Root Causes --}}
                            <div class="mt-5 sm:mt-6">
                                <span class="detail-label">Root Causes</span>
                                <div class="mt-2 flex flex-wrap gap-1.5 sm:gap-2">
                                    @php $rootCauses = is_array($latestSessionNote->root_causes ?? null) ? $latestSessionNote->root_causes : []; @endphp
                                    @forelse($rootCauses as $cause)
                                        <span class="cause-chip">{{ ucwords(str_replace('_',' ',$cause)) }}</span>
                                    @empty
                                        <span class="detail-value muted">—</span>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Session Notes --}}
                            <div class="mt-5 sm:mt-6">
                                <span class="detail-label">Session Notes</span>
                                <div class="note-box mt-2">{{ $latestSessionNote->notes }}</div>
                            </div>

                            {{-- Follow-up Actions --}}
                            <div class="mt-5 sm:mt-6">
                                <span class="detail-label">Assignment / Follow-up Actions</span>
                                <div class="note-box mt-2">{{ $latestSessionNote->follow_up_actions ?: '—' }}</div>
                            </div>

                            {{-- Mood Level --}}
                            @if($latestSessionNote->mood_level !== null)
                            <div class="mt-5 sm:mt-6">
                                <span class="detail-label">Mood Level</span>
                                <div class="detail-value mt-1">{{ $latestSessionNote->mood_level }} / 10</div>
                            </div>
                            @endif

                            {{-- Requires Follow-up / Next Session --}}
                            <div class="mt-5 sm:mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <span class="detail-label">Requires Follow-up</span>
                                    <div class="detail-value mt-1">
                                        @if($latestSessionNote->requires_follow_up)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                <i class="fas fa-rotate-right text-[9px]"></i> Yes
                                            </span>
                                        @else
                                            <span class="text-[#8b7e76] text-xs">No</span>
                                        @endif
                                    </div>
                                </div>
                                @if($latestSessionNote->next_session_date)
                                <div>
                                    <span class="detail-label">Next Session Date</span>
                                    <div class="detail-value mt-1">{{ $latestSessionNote->next_session_date->format('F j, Y') }}</div>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>{{-- end grid --}}
    </div>
</div>

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
                <input type="checkbox" id="hr_is_high_risk" {{ ($appointment->student->is_high_risk || $svSelfHarm) ? 'checked' : '' }}
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
