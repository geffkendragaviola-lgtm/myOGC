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

    .hero-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before {
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

    .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, #5c1a1a 0%, #3a0c0c 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, #d4af37, transparent 40%);
        pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.5rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .filter-label { 
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); 
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; 
    }
    .filter-input {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .filter-input:focus { 
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); 
    }

    .table-header-bar {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.6rem;
        padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-soft); background: rgba(250,248,245,0.4);
    }
    .table-header-icon {
        width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; background: rgba(254,249,231,0.6);
    }

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
        border-left: 4px solid var(--maroon-700);
        background: white; border-radius: 0.6rem;
        border-top: 1px solid var(--border-soft);
        border-right: 1px solid var(--border-soft);
        border-bottom: 1px solid var(--border-soft);
        transition: all 0.2s ease;
    }
    .appt-item:hover { box-shadow: 0 4px 12px rgba(44,36,32,0.05); transform: translateY(-1px); border-right-color: var(--gold-400); }
    .appt-title { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); }
    .appt-meta { font-size: 0.7rem; color: var(--text-secondary); display: inline-flex; align-items: center; gap: 0.35rem; margin-right: 1rem; }
    .appt-meta i { font-size: 0.65rem; color: var(--text-muted); }
    .appt-concern-wrap {
        background: #faf8f5; border: 1px solid #e5e0db; border-radius: 0.5rem;
        padding: 0.75rem; margin-top: 0.75rem;
    }
    .appt-concern { font-size: 0.75rem; color: var(--text-secondary); line-height: 1.4; }
    
    .action-link {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.35rem;
        border-radius: 0.5rem; font-size: 0.7rem; font-weight: 600; transition: all 0.2s ease;
        padding: 0.5rem;
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

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-muted); }
    .empty-state-icon {
        width: 4.5rem; height: 4.5rem; border-radius: 1.2rem;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
        margin-bottom: 1.25rem; font-size: 1.5rem;
    }

    @media (max-width: 639px) {
        .filter-input { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .appt-item { border-left-width: 3px; padding: 1rem !important; }
        .appt-title { font-size: 0.85rem; }
        .appt-meta { font-size: 0.65rem; margin-right: 0; display: block; margin-bottom: 0.35rem; }
        .appt-concern-wrap { padding: 0.65rem; }
        .action-link { font-size: 0.65rem; padding: 0.45rem; }
    }
</style>

<div class="min-h-screen session-shell">
    <div class="session-glow one"></div>
    <div class="session-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header Section -->
        <div class="mb-6 sm:mb-8">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <!-- Hero Card -->
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex flex-col md:flex-row md:items-start md:justify-between gap-4 h-full">
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
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="summary-card">
                    <div class="relative h-full flex flex-row items-center justify-between gap-4 p-4 sm:px-6">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-notes-medical text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Total Results</p>
                                <p class="summary-value">{{ $appointments->total() }}</p>
                                <p class="summary-subtext">Appointments found</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <a href="{{ route('counselor.appointments') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs sm:text-sm rounded-lg font-medium border border-white/20 bg-white/10 hover:bg-white/20 transition-all text-white shadow-sm whitespace-nowrap">
                                <i class="fas fa-calendar-check text-[10px]"></i> View Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm mb-6 sm:mb-8">
            <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-[#5c1a1a] via-[#d4af37] to-[#5c1a1a]"></div>
            <div class="p-3 sm:p-4">
                <form method="GET" action="{{ route('counselor.appointment-sessions.dashboard') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="filter-label">Search</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 sm:left-3.5 top-1/2 -translate-y-1/2 text-[#a89f97] text-[10px] sm:text-xs"></i>
                            <input type="text" id="search" name="search"
                                value="{{ request('search') }}"
                                placeholder="Student name, ID, or concern..."
                                class="filter-input"
                                style="padding-left: 2.25rem !important;">
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="filter-label">Status</label>
                        <select id="status" name="status" class="filter-input bg-white">
                            <option value="all"      {{ request('status', 'all') === 'all'      ? 'selected' : '' }}>All Statuses</option>
                            <option value="pending"  {{ request('status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved'  ? 'selected' : '' }}>Approved</option>
                            <option value="completed"{{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="referred" {{ request('status') === 'referred'  ? 'selected' : '' }}>Referred</option>
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label for="date_range" class="filter-label">Date Range</label>
                        <select id="date_range" name="date_range" class="filter-input bg-white">
                            <option value=""         {{ request('date_range') === null      ? 'selected' : '' }}>All Time</option>
                            <option value="today"    {{ request('date_range') === 'today'   ? 'selected' : '' }}>Today</option>
                            <option value="week"     {{ request('date_range') === 'week'    ? 'selected' : '' }}>This Week</option>
                            <option value="month"    {{ request('date_range') === 'month'   ? 'selected' : '' }}>This Month</option>
                            <option value="upcoming" {{ request('date_range') === 'upcoming'? 'selected' : '' }}>Upcoming</option>
                            <option value="past"     {{ request('date_range') === 'past'    ? 'selected' : '' }}>Past</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-end gap-2 sm:gap-3">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] text-white font-medium shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 text-xs sm:text-sm">
                            <i class="fas fa-search text-[10px] sm:text-xs"></i>
                            <span>Apply</span>
                        </button>
                        <a href="{{ route('counselor.appointment-sessions.dashboard') }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-[#f5f0eb] text-[#6b5e57] hover:bg-[#e5e0db] transition font-medium text-xs sm:text-sm" title="Reset Filters">
                            <i class="fas fa-rotate-left"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Appointments List Section -->
        <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm">
            <div class="table-header-bar">
                <div class="flex items-center gap-3">
                    <div class="table-header-icon">
                        <i class="fas fa-notes-medical text-[#7a2a2a] text-[10px] sm:text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-medium text-[#2c2420]">Session Notes List</h2>
                        @if($appointments->total() > 0)
                        <p class="text-[10px] sm:text-xs text-[#8b7e76]">Showing <span class="font-bold text-[#2c2420]">{{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }}</span> of <span class="font-bold text-[#2c2420]">{{ $appointments->total() }}</span></p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-5 md:p-6 bg-[#faf8f5]/30">
                @if($appointments->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-notes-medical"></i>
                        </div>
                        <p class="text-sm sm:text-base font-medium text-[#2c2420]">No appointments found.</p>
                        <p class="text-xs sm:text-sm text-[#8b7e76] mt-1">Appointments will appear here once they are created.</p>
                        <a href="{{ route('counselor.appointments') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 mt-5 rounded-lg bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] text-white font-medium shadow-sm hover:-translate-y-0.5 transition-all text-sm">
                            <i class="fas fa-list text-xs"></i> Go to Appointments
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4 sm:gap-5">
                        @foreach($appointments as $appointment)
                            <div class="appt-item p-4 sm:p-5">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <h2 class="appt-title truncate">
                                            {{ $appointment->session_sequence_label ?? ($appointment->booking_type === 'Initial Interview' ? 'Initial Interview' : 'Session') }} -
                                            {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                        </h2>
                                        <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1">
                                            <span class="appt-meta">
                                                <i class="fas fa-calendar-days"></i>
                                                {{ $appointment->appointment_date->format('M j, Y') }}
                                            </span>
                                            <span class="appt-meta">
                                                <i class="fas fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                            </span>
                                            <span class="text-[10px] sm:text-xs text-[#8b7e76] font-mono mt-0.5 sm:mt-0">
                                                <i class="fas fa-id-card mr-1 text-[9px] text-[#a89f97]"></i> ID: {{ $appointment->student->student_id }}
                                            </span>
                                        </div>
                                        
                                        <div class="appt-concern-wrap">
                                            <div class="text-[10px] uppercase font-bold tracking-wider text-[#a89f97] mb-1">Stated Concern</div>
                                            <p class="appt-concern truncate" title="{{ $appointment->concern }}">
                                                {{ $appointment->concern ? \Illuminate\Support\Str::limit($appointment->concern, 250) : 'No concern specified.' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-start md:items-end gap-3 md:w-56 flex-shrink-0">
                                        <span class="status-chip {{ $appointment->status === 'pending' ? 'pending' : ($appointment->status === 'approved' ? 'approved' : ($appointment->status === 'completed' ? 'completed' : ($appointment->status === 'referred' ? 'referred' : 'default'))) }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                        
                                        <div class="text-xs font-semibold text-[#8b7e76] mt-1 mb-2">
                                            <i class="fas fa-file-medical text-[10px] mr-1 text-[#d4af37]"></i> {{ $appointment->sessionNotes->count() }} Session Note(s)
                                        </div>

                                        <!-- Unified action buttons with exact even widths -->
                                        <div class="grid grid-cols-2 gap-2 w-full mt-auto">
                                            <a href="{{ route('counselor.appointments.session.view', $appointment) }}" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] text-white font-medium shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 text-xs">
                                                <i class="fas fa-eye text-[10px] mr-1.5"></i> View
                                            </a>
                                            <a href="{{ route('counselor.appointments.session', $appointment) }}" class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] text-white font-medium shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 text-xs">
                                                <i class="fas fa-pen text-[10px] mr-1.5"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/80 bg-[#faf8f5]/40">
                @if(method_exists($appointments, 'links'))
                    {{ $appointments->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection