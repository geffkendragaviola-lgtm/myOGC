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

    .session-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .session-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .session-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .session-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .appt-item {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .appt-item:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before, .appt-item::before {
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

    .field-label { 
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); 
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; 
    }
    .input-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus { 
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); 
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

    .status-chip {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.2rem 0.45rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .status-chip.pending { background: rgba(254,249,231,0.9); color: #7a2a2a; border: 1px solid rgba(212,175,55,0.3); }
    .status-chip.approved { background: rgba(240,253,244,0.9); color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .status-chip.completed { background: rgba(245,240,235,0.9); color: var(--text-secondary); border: 1px solid var(--border-soft); }
    .status-chip.referred { background: rgba(254,249,231,0.9); color: #7a2a2a; border: 1px solid rgba(212,175,55,0.3); }
    .status-chip.default { background: rgba(245,240,235,0.9); color: var(--text-secondary); border: 1px solid var(--border-soft); }

    .appt-item {
        border-left: 3px solid var(--maroon-700);
    }
    .appt-title { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); }
    .appt-meta { font-size: 0.65rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 0.3rem; margin-right: 0.75rem; }
    .appt-meta i { font-size: 0.6rem; color: var(--text-muted); }
    .appt-concern { font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.5rem; }
    .appt-footer { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.5rem; margin-top: 0.75rem; }
    .appt-notes { font-size: 0.65rem; color: var(--text-muted); }
    .appt-actions { display: flex; flex-wrap: wrap; gap: 0.35rem; }
    .action-link {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.35rem 0.6rem; border-radius: 0.5rem;
        font-size: 0.65rem; font-weight: 500; transition: all 0.18s ease;
    }
    .action-link.secondary {
        color: var(--text-secondary); border: 1px solid var(--border-soft);
        background: rgba(255,255,255,0.9);
    }
    .action-link.secondary:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); color: var(--maroon-700); }
    .action-link.primary {
        color: var(--maroon-700); border: 1px solid rgba(122,42,42,0.4);
        background: rgba(254,249,231,0.6);
    }
    .action-link.primary:hover { background: rgba(212,175,55,0.2); border-color: var(--gold-400); }

    .empty-state {
        text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);
    }
    .empty-state-icon {
        width: 4rem; height: 4rem; border-radius: 1rem;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
        margin-bottom: 1rem; font-size: 1.25rem;
    }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; }
        .appt-item { border-left-width: 2px; padding: 0.75rem !important; }
        .appt-title { font-size: 0.8rem; }
        .appt-meta { font-size: 0.6rem; margin-right: 0; display: block; margin-bottom: 0.25rem; }
        .appt-concern { font-size: 0.7rem; }
        .appt-footer { flex-direction: column; align-items: flex-start; }
        .action-link { width: 100%; justify-content: center; }
        .empty-state { padding: 2rem 0.5rem; }
        .empty-state-icon { width: 3rem; height: 3rem; font-size: 1rem; }
    }
</style>

<div class="min-h-screen session-shell">
    <div class="session-glow one"></div>
    <div class="session-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
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
                                Case Overview
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Appointment Sessions</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Select an appointment to open and update its session notes.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('counselor.appointments') }}"
                       class="secondary-btn px-4 py-2 text-xs sm:text-sm w-full md:w-auto">
                        <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>Back to Appointments
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="panel-card mb-6">
            <div class="panel-topline"></div>
            <form method="GET" action="{{ route('counselor.appointment-sessions.dashboard') }}" class="p-4 sm:p-5">
                <div class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[180px]">
                        <label for="search" class="field-label">Search</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#a89f97] text-xs"></i>
                            <input type="text" id="search" name="search"
                                   value="{{ request('search') }}"
                                   placeholder="‎ ‎ ‎ Student name, ID, or concern..."
                                   class="input-field pl-9 text-xs sm:text-sm">
                        </div>
                    </div>
                    <div class="w-36 sm:w-40">
                        <label for="status" class="field-label">Status</label>
                        <select id="status" name="status" class="select-field text-xs sm:text-sm">
                            <option value="all"     {{ request('status', 'all') === 'all'     ? 'selected' : '' }}>All</option>
                            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed"{{ request('status') === 'completed'? 'selected' : '' }}>Completed</option>
                            <option value="referred" {{ request('status') === 'referred' ? 'selected' : '' }}>Referred</option>
                        </select>
                    </div>
                    <div class="w-36 sm:w-40">
                        <label for="date_range" class="field-label">Date Range</label>
                        <select id="date_range" name="date_range" class="select-field text-xs sm:text-sm">
                            <option value=""        {{ request('date_range') === null     ? 'selected' : '' }}>All Time</option>
                            <option value="today"   {{ request('date_range') === 'today'   ? 'selected' : '' }}>Today</option>
                            <option value="week"    {{ request('date_range') === 'week'    ? 'selected' : '' }}>This Week</option>
                            <option value="month"   {{ request('date_range') === 'month'   ? 'selected' : '' }}>This Month</option>
                            <option value="upcoming"{{ request('date_range') === 'upcoming'? 'selected' : '' }}>Upcoming</option>
                            <option value="past"    {{ request('date_range') === 'past'    ? 'selected' : '' }}>Past</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2 pb-0.5">
                        <a href="{{ route('counselor.appointment-sessions.dashboard') }}" class="secondary-btn px-3 py-2 text-xs sm:text-sm">
                            <i class="fas fa-rotate-left mr-1 text-[9px]"></i>Reset
                        </a>
                        <button type="submit" class="primary-btn px-3 py-2 text-xs sm:text-sm">
                            <i class="fas fa-magnifying-glass mr-1 text-[9px]"></i>Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Appointments List -->
        <div class="panel-card overflow-hidden">
            @if($appointments->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-notes-medical"></i>
                    </div>
                    <p class="text-sm sm:text-base font-medium text-[#2c2420]">No appointments found.</p>
                    <p class="text-xs sm:text-sm text-[#8b7e76] mt-1">Appointments will appear here once they are created.</p>
                    <a href="{{ route('counselor.appointments') }}"
                       class="primary-btn px-4 py-2 text-xs sm:text-sm mt-4">
                        <i class="fas fa-list mr-1.5 text-[9px] sm:text-xs"></i>Go to Appointments
                    </a>
                </div>
            @else
                <div class="p-4 sm:p-5 md:p-6 space-y-4 sm:space-y-5">
                    @foreach($appointments as $appointment)
                        <div class="appt-item p-4 sm:p-5">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex items-start gap-3">
                                        <div class="min-w-0">
                                            <h2 class="appt-title truncate">
                                                {{ $appointment->session_sequence_label ?? ($appointment->booking_type === 'Initial Interview' ? 'Initial Interview' : 'Session') }} -
                                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                            </h2>
                                            <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                                <span class="appt-meta">
                                                    <i class="fas fa-calendar-days"></i>
                                                    {{ $appointment->appointment_date->format('M j, Y') }}
                                                </span>
                                                <span class="appt-meta">
                                                    <i class="fas fa-clock"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-start md:items-end gap-2">
                                    <span class="status-chip {{ $appointment->status === 'pending' ? 'pending' : ($appointment->status === 'approved' ? 'approved' : ($appointment->status === 'completed' ? 'completed' : ($appointment->status === 'referred' ? 'referred' : 'default'))) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                    <div class="text-[10px] sm:text-xs text-[#8b7e76] inline-flex items-center">
                                        <i class="fas fa-id-card mr-1.5 text-[#a89f97] text-[9px]"></i>
                                        ID: {{ $appointment->student->student_id }}
                                    </div>
                                </div>
                            </div>

                            <p class="appt-concern truncate">
                                {{ \Illuminate\Support\Str::limit($appointment->concern, 180) }}
                            </p>

                            <div class="appt-footer">
                                <div class="appt-notes">
                                    {{ $appointment->sessionNotes->count() }} note(s)
                                </div>

                                <div class="appt-actions">
                                    <a href="{{ route('counselor.appointments.session.view', $appointment) }}"
                                       class="action-link secondary">
                                        <i class="fas fa-eye text-[9px] sm:text-xs"></i> View
                                    </a>
                                    <a href="{{ route('counselor.appointments.session', $appointment) }}"
                                       class="action-link primary">
                                        <i class="fas fa-pen text-[9px] sm:text-xs"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-shell" style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.4);">
                    {{ $appointments->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection