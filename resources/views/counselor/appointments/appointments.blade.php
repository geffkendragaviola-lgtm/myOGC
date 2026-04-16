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

    .appt-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .appt-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .appt-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .appt-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before {
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

    /* Updated Stat Card Styles - Replace existing .stat-card rules */
.stat-card {
    display: block; text-decoration: none;
    border-radius: 0.6rem;
    border: 1px solid var(--border-soft);
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px);
    box-shadow: 0 2px 8px rgba(44,36,32,0.04);
    transition: all 0.2s ease;
}
.stat-card:hover {
    box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    border-color: var(--gold-400);
    transform: translateY(-1px);
}
.stat-card::before {
    content: ""; position: absolute; inset: 0; pointer-events: none;
    background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    border-radius: 0.6rem;
}

.stat-icon {
    width: 2rem; height: 2rem; border-radius: 0.6rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    background: rgba(254,249,231,0.7);
    color: var(--maroon-700);
    font-size: 0.7rem;
}

.stat-label {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.16em;
    color: var(--text-secondary);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1.1;
}

    .alert-success, .alert-error {
        display: flex; align-items: flex-start; gap: 0.5rem;
        border-radius: 0.6rem; padding: 0.75rem 1rem; font-size: 0.8rem; font-weight: 500;
    }
    .alert-success {
        border: 1px solid rgba(16,185,129,0.3); background: rgba(240,253,244,0.9); color: #065f46;
    }
    .alert-error {
        border: 1px solid rgba(185,28,28,0.3); background: rgba(253,242,242,0.9); color: #7a2a2a;
    }

    .status-chip {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.2rem 0.45rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .status-chip.pending { background: rgba(254,249,231,0.9); color: #7a2a2a; border: 1px solid rgba(212,175,55,0.3); }
    .status-chip.approved { background: rgba(240,253,244,0.9); color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .status-chip.completed { background: rgba(245,240,235,0.9); color: var(--text-secondary); border: 1px solid var(--border-soft); }
    .status-chip.rejected { background: rgba(253,242,242,0.9); color: #7a2a2a; border: 1px solid rgba(185,28,28,0.25); }
    .status-chip.cancelled { background: rgba(245,240,235,0.9); color: var(--text-secondary); border: 1px solid var(--border-soft); }
    .status-chip.no_show { background: rgba(255,237,213,0.9); color: #9a3412; border: 1px solid rgba(234,88,12,0.25); }
    .status-chip.reschedule_requested { background: rgba(255,244,229,0.9); color: #92400e; border: 1px solid rgba(234,88,12,0.25); }
    .status-chip.rescheduled { background: rgba(254,249,231,0.9); color: #7a2a2a; border: 1px solid rgba(212,175,55,0.3); }
    .status-chip.reschedule_rejected { background: rgba(255,241,242,0.9); color: #9f1239; border: 1px solid rgba(225,29,72,0.25); }
    .status-chip.referred { background: rgba(254,249,231,0.9); color: #7a2a2a; border: 1px solid rgba(212,175,55,0.3); }

    .college-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.45rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600;
        background: rgba(245,240,235,0.9); color: var(--text-secondary); border: 1px solid var(--border-soft);
    }

    .filter-chip {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        padding: 0.5rem 1rem; font-size: 0.75rem;
    }
    .filter-chip.active {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .filter-chip.inactive {
        color: var(--text-secondary); background: rgba(255,255,255,0.9);
        border: 1px solid var(--border-soft);
    }
    .filter-chip.inactive:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }

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

    .action-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 1.75rem; height: 1.75rem; border-radius: 0.5rem;
        color: var(--text-secondary); transition: all 0.18s ease;
        font-size: 0.75rem;
    }
    .action-icon:hover { transform: translateY(-1px); color: var(--maroon-700); background: rgba(254,249,231,0.6); }
    .action-icon.danger:hover { color: #b91c1c; background: rgba(253,242,242,0.8); }
    .action-icon.success:hover { color: #059669; background: rgba(240,253,244,0.8); }
    .action-icon.warning:hover { color: #d97706; background: rgba(254,249,231,0.9); }

    .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table-row { transition: background-color 0.15s ease; cursor: pointer; }
    .table-row:hover { background: rgba(254,249,231,0.35); }
    .table-row.referred-out { background: rgba(255,249,230,0.6); }
    .table-row.referred-out:hover { background: rgba(255,249,230,0.9); }
    .table-row.referred-in { background: rgba(255,249,230,0.6); }
    .table-row.referred-in:hover { background: rgba(255,249,230,0.9); }

    /* Modal styles - FIXED: display flex only when NOT hidden to avoid Tailwind conflict */
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
        max-width: 56rem; width: 100%; max-height: 90vh; overflow-y: auto;
    }
    .modal-card-sm { max-width: 28rem; }
    .modal-card-md { max-width: 42rem; }
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

    .calendar-nav {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.5rem 0; margin-bottom: 0.5rem;
    }
    .calendar-nav-btn {
        width: 2.25rem; height: 2.25rem; border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border-soft); color: var(--text-secondary);
        transition: all 0.18s ease; font-size: 1rem;
    }
    .calendar-nav-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); color: var(--maroon-700); }
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.5rem; }
    .calendar-day-header {
        text-align: center; font-size: 0.65rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);
    }
    .calendar-day {
        width: 2.5rem; height: 2.5rem; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; font-weight: 500; border: 1px solid transparent;
        transition: all 0.18s ease;
    }
    .calendar-day.available {
        border-color: rgba(122,42,42,0.3); color: var(--maroon-700);
        background: rgba(254,249,231,0.5);
    }
    .calendar-day.available:hover {
        background: rgba(212,175,55,0.2); border-color: var(--gold-400);
    }
    .calendar-day.selected {
        background: var(--maroon-700); color: #fef9e7; border-color: var(--maroon-700);
    }
    .calendar-day:disabled {
        color: var(--text-muted); cursor: not-allowed; opacity: 0.5;
    }

    .time-slot {
        padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-soft);
        text-align: center; font-size: 0.75rem; font-weight: 500;
        transition: all 0.18s ease; cursor: pointer;
        background: rgba(255,255,255,0.9);
    }
    .time-slot:hover { border-color: var(--maroon-700); background: rgba(254,249,231,0.6); }
    .time-slot.selected {
        border-color: var(--maroon-700); background: rgba(254,249,231,0.9);
        color: var(--maroon-700); font-weight: 600;
    }

    .empty-state {
        text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);
    }
    .empty-state-icon {
        width: 4rem; height: 4rem; border-radius: 1rem;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
        margin-bottom: 1rem; font-size: 1.25rem;
    }

    .avatar-badge {
        flex-shrink: 0; height: 2.5rem; width: 2.5rem; border-radius: 0.65rem;
        display: flex; align-items: center; justify-content: center; color: var(--maroon-700);
        font-weight: 700; font-size: 0.75rem; background: rgba(254,249,231,0.6);
        border: 1px solid rgba(212,175,55,0.3);
    }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn, .filter-chip { width: 100%; justify-content: center; }
        .stat-card { text-align: center; }
        .stat-card .flex { flex-direction: column; align-items: center !important; gap: 0.35rem !important; }
        .stat-icon { margin: 0 auto; }
        .stat-label { margin-bottom: 0.15rem; }
        .stat-value { font-size: 1.1rem; }
        .table-scroll { overflow-x: auto; }
        .action-icon { width: 2rem; height: 2rem; }
        .modal-card { max-height: 95vh; margin: 0.5rem; }
        .modal-header { padding: 0.85rem 1rem; }
        .modal-body { padding: 1rem; }
        .calendar-day { width: 2rem; height: 2rem; font-size: 0.7rem; }
        .time-slot { padding: 0.5rem; font-size: 0.7rem; }
        .college-badge { font-size: 0.6rem; padding: 0.15rem 0.35rem; }
    }
</style>

<div class="min-h-screen appt-shell">
    <div class="appt-glow one"></div>
    <div class="appt-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-check text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Counselor Portal
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Appointment Management</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Manage student appointments and session notes across all assigned colleges
                            </p>
                            @if(isset($allColleges) && $allColleges->count() > 1)
                            <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                <span class="text-[10px] sm:text-xs text-[#8b7e76]">Assigned to:</span>
                                @foreach($allColleges as $college)
                                    <span class="college-badge">
                                        {{ $college->name }}
                                    </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('counselor.dashboard') }}"
                        class="secondary-btn px-4 py-2 text-xs sm:text-sm">
                            <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>Dashboard
                        </a>
                        <a href="{{ route('counselor.appointments.create') }}"
                        class="primary-btn px-4 py-2 text-xs sm:text-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: rgba(16,185,129,0.3);">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i>Book New
                        </a>
                        <a href="{{ route('counselor.calendar') }}"
                        class="primary-btn px-4 py-2 text-xs sm:text-sm">
                            <i class="fas fa-calendar-alt mr-1.5 text-[9px] sm:text-xs"></i>View Calendar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Filter Chips -->
<div class="flex flex-wrap gap-2 mb-6">
    @php
        $currentStatus = request('status', 'all');
        $currentDir    = request('referral_direction', '');
        $baseParams    = request()->except('page', 'status', 'referral_direction');
        $chips = [
            ['label' => 'All',        'status' => 'all',       'dir' => '',    'count' => $stats['total'] ?? 0,        'color' => 'var(--maroon-soft)'],
            ['label' => 'Pending',    'status' => 'pending',   'dir' => '',    'count' => $stats['pending'] ?? 0,      'color' => '#c9a227'],
            ['label' => 'Approved',   'status' => 'approved',  'dir' => '',    'count' => $stats['approved'] ?? 0,     'color' => '#2d7a4f'],
            ['label' => 'Completed',  'status' => 'completed', 'dir' => '',    'count' => $stats['completed'] ?? 0,    'color' => '#2a5a7a'],
            ['label' => 'Cancelled',  'status' => 'cancelled', 'dir' => '',    'count' => $stats['cancelled'] ?? 0,    'color' => '#b91c1c'],
            ['label' => 'Rejected',   'status' => 'rejected',  'dir' => '',    'count' => $stats['rejected'] ?? 0,     'color' => '#7c3aed'],
            ['label' => 'Referred In','status' => 'all',       'dir' => 'in',  'count' => $stats['referred_in'] ?? 0,  'color' => '#c9a227'],
            ['label' => 'Referred Out','status'=> 'all',       'dir' => 'out', 'count' => $stats['referred_out'] ?? 0, 'color' => '#7c3aed'],
        ];
    @endphp
    @foreach($chips as $chip)
        @php
            $isActive = ($currentStatus === $chip['status'] && $currentDir === $chip['dir']);
            $url = route('counselor.appointments') . '?' . http_build_query(array_merge($baseParams, ['status' => $chip['status'], 'referral_direction' => $chip['dir']]));
        @endphp
        <a href="{{ $url }}"
           style="{{ $isActive ? 'background:'.($chip['color']).';color:#fff;border-color:'.($chip['color']).';' : 'background:#fff;color:var(--text-secondary);border-color:var(--border-soft);' }}"
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border text-xs font-semibold transition-all hover:shadow-sm"
           style="{{ $isActive ? '' : '' }}">
            {{ $chip['label'] }}
            <span class="inline-flex items-center justify-center min-w-[1.2rem] h-5 px-1 rounded-full text-[10px] font-bold"
                  style="{{ $isActive ? 'background:rgba(255,255,255,0.25);color:#fff;' : 'background:var(--bg-warm);color:var(--text-primary);' }}">
                {{ $chip['count'] }}
            </span>
        </a>
    @endforeach
</div>

        <!-- Search and Filters Section -->
        <div class="panel-card mb-6">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-sliders-h text-[9px] sm:text-xs"></i></div>
                <div>
                    <h2 class="panel-title">Search and Filter</h2>
                    <p class="panel-subtitle hidden sm:block">Find appointments by student, date, or college</p>
                </div>
            </div>

            <form method="GET" action="{{ route('counselor.appointments') }}" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="field-label">Search</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-[#a89f97] text-xs"></i>
                            <input type="text"
                                id="search"
                                name="search"
                                placeholder="Search by student name, ID, college, or concern..."
                                value="{{ request('search') }}"
                                class="input-field pl-9 sm:pl-11 text-xs sm:text-sm">
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label for="date_range" class="field-label">Date Range</label>
                        <select id="date_range" name="date_range" class="select-field text-xs sm:text-sm">
                            <option value="">All Dates</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="upcoming" {{ request('date_range') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ request('date_range') == 'past' ? 'selected' : '' }}>Past Appointments</option>
                        </select>
                    </div>

                    <!-- College Filter -->
                    <div>
                        <label for="college" class="field-label">College</label>
                        <select id="college" name="college" class="select-field text-xs sm:text-sm">
                            <option value="">All Colleges</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}" {{ request('college') == $college->id ? 'selected' : '' }}>
                                    {{ $college->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                    <div class="text-[10px] sm:text-xs text-[#8b7e76]">
                        Showing {{ $appointments->firstItem() ?? 0 }}-{{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} appointments
                        @if(isset($allColleges) && $allColleges->count() > 1)
                            <span class="text-[#7a2a2a] ml-1">(Across {{ $allColleges->count() }} colleges)</span>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('counselor.appointments') }}"
                        class="secondary-btn px-4 py-2 text-xs sm:text-sm">
                            <i class="fas fa-rotate mr-1.5 text-[9px] sm:text-xs"></i>Reset
                        </a>
                        <button type="submit" class="primary-btn px-4 py-2 text-xs sm:text-sm">
                            <i class="fas fa-filter mr-1.5 text-[9px] sm:text-xs"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Appointments Table -->
        <div class="panel-card overflow-hidden">
            @if($appointments->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <p class="text-sm sm:text-base font-medium text-[#2c2420]">No appointments found.</p>
                    <p class="text-xs sm:text-sm text-[#8b7e76] mt-1">When students book appointments, they will appear here.</p>
                </div>
            @else
                <div class="table-scroll">
                    <table class="w-full min-w-[900px]" id="appointmentsTable">
                        <thead class="bg-[#faf8f5]/80">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Student</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Date & Time</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">College</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Booking Type</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                            @foreach($appointments as $appointment)
                                @php
                                    // Define status colors with ALL possible statuses
                                    $statusColors = [
                                        'pending' => 'pending',
                                        'approved' => 'approved',
                                        'rejected' => 'rejected',
                                        'cancelled' => 'cancelled',
                                        'no_show' => 'no_show',
                                        'completed' => 'completed',
                                        'referred' => 'referred',
                                        'rescheduled' => 'rescheduled',
                                        'reschedule_requested' => 'reschedule_requested',
                                        'reschedule_rejected' => 'reschedule_rejected'
                                    ];

                                    // Safe status color lookup with fallback
                                    $statusColor = $statusColors[$appointment->status] ?? 'cancelled';

                                    $statusText = $appointment->display_status;
                                    $referralBadgeText = $appointment->referral_badge;

                                    // Add special styling for referred appointments
                                    $rowClass = 'table-row';
                                    if ($appointment->is_referred_out) {
                                        $rowClass = 'table-row referred-out';
                                    } elseif ($appointment->is_referred_in) {
                                        $rowClass = 'table-row referred-in';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }} fade-in" onclick="showAppointmentDetails({{ $appointment->id }})">
                                    <td class="px-4 sm:px-6 py-3.5">
                                        <div class="flex items-center gap-2.5 sm:gap-3">
                                            <div class="avatar-badge">
                                                <i class="fas fa-user text-[9px] sm:text-xs"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[140px] sm:max-w-[180px]">
                                                    {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                                    @if($appointment->is_referred_out)
                                                        <span class="ml-2 text-[10px] sm:text-xs bg-[#fff9e6] text-[#7a2a2a] px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full">
                                                            <i class="fas fa-share text-[8px] sm:text-[9px]"></i> Out
                                                        </span>
                                                    @elseif($appointment->is_referred_in)
                                                        <span class="ml-2 text-[10px] sm:text-xs bg-[#f5f0eb] text-[#7a2a2a] px-1.5 sm:px-2 py-0.5 sm:py-1 rounded-full">
                                                            <i class="fas fa-reply text-[8px] sm:text-[9px]"></i> In
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-[10px] sm:text-xs text-[#8b7e76] font-mono truncate max-w-[140px] sm:max-w-[180px]">
                                                    {{ $appointment->student->student_id }}
                                                </div>
                                                <div class="text-[10px] sm:text-xs text-[#8b7e76]">
                                                    {{ $appointment->student->user->sex ?? 'Not provided' }} • Yr {{ $appointment->student->year_level }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Date & Time Column -->
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-xs sm:text-sm text-[#6b5e57]">
                                        @if($appointment->status === 'reschedule_requested' && $appointment->proposed_date)
                                            <div class="text-[10px] font-semibold text-[#92400e] uppercase tracking-wide">New</div>
                                            <div class="text-xs text-[#92400e] font-semibold">
                                                {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j, Y') }}
                                            </div>
                                            <div class="text-[10px] text-[#92400e]">
                                                {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($appointment->proposed_end_time)->format('g:i A') }}
                                            </div>
                                            <div class="text-[9px] font-semibold text-[#a89f97] uppercase tracking-wide mt-1">Old</div>
                                            <div class="text-[10px] text-[#a89f97]">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j') }}
                                            </div>
                                        @elseif($appointment->status === 'referred' && $appointment->proposed_date)
                                            <div class="text-[10px] font-semibold text-[#7a2a2a] uppercase tracking-wide">New</div>
                                            <div class="text-xs text-[#7a2a2a] font-semibold">
                                                {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j, Y') }}
                                            </div>
                                            <div class="text-[10px] text-[#7a2a2a]">
                                                {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($appointment->proposed_end_time)->format('g:i A') }}
                                            </div>
                                            <div class="text-[9px] font-semibold text-[#a89f97] uppercase tracking-wide mt-1">Old</div>
                                            <div class="text-[10px] text-[#a89f97]">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j') }}
                                            </div>
                                        @else
                                            <div class="text-xs sm:text-sm text-[#2c2420]">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                            </div>
                                            <div class="text-[10px] sm:text-xs text-[#8b7e76]">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- College Column -->
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-xs sm:text-sm text-[#6b5e57]">
                                        {{ $appointment->student->college->name ?? 'N/A' }}
                                    </td>

                                    <!-- Booking Type Column -->
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-xs sm:text-sm text-[#6b5e57]">
                                        <div class="flex flex-col">
                                            <span class="text-xs sm:text-sm text-[#2c2420]">
                                                {{ $appointment->booking_type ? ucwords(str_replace('_', ' ', $appointment->booking_type)) : '—' }}{{ $appointment->notes && str_contains(strtolower($appointment->notes), 'follow-up appointment') ? ' • Follow up' : '' }}
                                            </span>
                                            <span class="text-[10px] text-[#8b7e76]">
                                                {{ $appointment->session_sequence_label ?? '' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Status Column -->
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <span class="status-chip {{ $statusColor }}">
                                                {{ $statusText }}
                                            </span>
                                            @if($referralBadgeText)
                                                <span class="status-chip referred">
                                                    {{ $referralBadgeText }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap" onclick="event.stopPropagation();">
                                        @if(!in_array($appointment->status, ['cancelled', 'rejected'], true))
                                            <div class="flex items-center gap-1.5 sm:gap-2">
                                                <!-- Status Management Actions - Available for current counselor AND referred-to counselor -->
                                                @if(in_array($appointment->getEffectiveCounselorId(), $counselorIdList, true))
                                                    @if($appointment->status === 'pending')
                                                        <!-- Approve button -->
                                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit"
                                                                    class="action-icon success"
                                                                    onclick="return confirm('Approve this appointment?')"
                                                                    title="Approve Appointment">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <!-- Transfer buttons
                                                        <button onclick="showRejectionOptions({{ $appointment->id }})"
                                                                class="action-icon danger"
                                                                title="Reject or Transfer Appointment">
                                                            <i class="fas fa-times"></i>
                                                        </button> -->
                                                    @elseif(in_array($appointment->status, ['approved', 'rescheduled'], true))
                                                        <!-- Complete and Cancel buttons -->
                                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit"
                                                                    class="action-icon" style="color: var(--maroon-700);"
                                                                    onclick="return confirm('Mark this appointment as completed?')"
                                                                    title="Mark as Completed">
                                                                <i class="fas fa-flag-checkered"></i>
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="no_show">
                                                            <button type="submit"
                                                                    class="action-icon warning"
                                                                    onclick="return confirm('Mark this appointment as No Show?')"
                                                                    title="No Show / Did Not Show Up">
                                                                <i class="fas fa-user-slash"></i>
                                                            </button>
                                                        </form>
                                                    @elseif($appointment->status === 'referred' && in_array($appointment->referred_to_counselor_id, $counselorIdList, true))
                                                        <!-- Special actions for referred appointments where this counselor is the receiver -->
                                                        <form action="{{ route('counselor.appointments.referral.accept', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="action-icon success"
                                                                    onclick="return confirm('Accept this referred appointment and schedule it?')"
                                                                    title="Accept Referred Appointment">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                <!-- Reschedule option for effective counselor -->
                                                @if(in_array($appointment->getEffectiveCounselorId(), $counselorIdList, true) && in_array($appointment->status, ['pending', 'approved', 'referred', 'rescheduled', 'reschedule_rejected'], true))
                                                    <button onclick="showRescheduleModal({{ $appointment->id }}, {{ $appointment->getEffectiveCounselorId() }}, '{{ $appointment->appointment_date->format('Y-m-d') }}')"
                                                            class="action-icon warning"
                                                            title="Reschedule Appointment">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </button>
                                                @endif
                                                <!-- Referral option for current counselor -->
                                                @if(in_array($appointment->counselor_id, $counselorIdList, true) && in_array($appointment->status, ['pending', 'approved', 'rescheduled', 'reschedule_rejected'], true) && !(isset($referralBadgeText) && $referralBadgeText && \Illuminate\Support\Str::startsWith($referralBadgeText, 'Referred from')))
                                                    <button onclick="showReferralModal({{ $appointment->id }}, '{{ $appointment->appointment_date->format('Y-m-d') }}', {{ $appointment->student_id }}, {{ $appointment->counselor_id }})"
                                                            class="action-icon" style="color: var(--maroon-700);"
                                                            title="Refer to Another Counselor">
                                                        <i class="fas fa-share"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-shell" style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.4);">
                    {{ $appointments->appends(request()->query())->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>

        <!-- Appointment Details Modal -->
        <div id="appointmentModal" class="modal-backdrop hidden">
            <div class="modal-card">
                <div class="modal-header">
                    <h3 class="text-sm font-semibold text-[#2c2420]">Appointment Details</h3>
                    <button onclick="closeAppointmentModal()" class="modal-close" title="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="appointmentDetails" class="modal-body">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>

        <!-- Rejection Options Modal -->
        <div id="rejectionModal" class="modal-backdrop hidden">
            <div class="modal-card modal-card-sm">
                <div class="modal-header">
                    <h3 class="text-sm font-semibold text-[#2c2420]">Reject Appointment</h3>
                    <button onclick="closeRejectionModal()" class="modal-close" title="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-xs sm:text-sm text-[#6b5e57] mb-4">Confirm rejection for this appointment.</p>

                    <form id="directRejectForm" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <input type="hidden" name="notes" id="rejectionNotes" value="I am unavailable at this time. Please book with another counselor.">
                        <button type="submit"
                                class="w-full text-left p-4 border border-[#b91c1c]/30 rounded-lg hover:bg-[#fdf2f2] transition"
                                style="border-color: rgba(185,28,28,0.3);">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg" style="background: rgba(253,242,242,0.9);">
                                    <i class="fas fa-times" style="color: #7a2a2a;"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-[#7a2a2a]">Reject Appointment</h4>
                                    <p class="text-xs text-[#8b7e76]">Appointment will be cancelled</p>
                                </div>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Referral Modal -->
        <div id="referralModal" class="modal-backdrop hidden">
            <div class="modal-card modal-card-md">
                <div class="modal-header">
                    <h3 class="text-sm font-semibold text-[#2c2420]">Refer Appointment</h3>
                    <button onclick="closeReferralModal()" class="modal-close" title="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="referralForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label for="referralCounselorSelect" class="field-label">Select Counselor</label>
                                <select id="referralCounselorSelect" name="referred_to_counselor_id"
                                        class="select-field text-xs sm:text-sm"
                                        required>
                                    <option value="">Loading counselors...</option>
                                </select>
                                <p class="text-[10px] text-[#8b7e76] mt-1">Choose a counselor from any college.</p>
                            </div>

                            <div>
                                <label class="field-label">Select Date</label>
                                <div class="border border-[#e5e0db] rounded-xl bg-white p-4 shadow-sm">
                                    <div class="calendar-nav">
                                        <button type="button" id="referralCalendarPrev" class="calendar-nav-btn">‹</button>
                                        <h3 id="referralCalendarMonthLabel" class="text-sm font-semibold text-[#2c2420]"></h3>
                                        <button type="button" id="referralCalendarNext" class="calendar-nav-btn">›</button>
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
                                    <div id="referralCalendarGrid" class="calendar-grid"></div>
                                    <p id="referralCalendarStatus" class="mt-3 text-[10px] sm:text-xs text-[#8b7e76]">
                                        Select a counselor to load available dates.
                                    </p>
                                </div>
                                <input type="hidden" name="appointment_date" id="referralDateSelect" required>
                            </div>

                            <div>
                                <label class="field-label">Available Time Slots</label>
                                <div id="referralTimeSlots" class="grid grid-cols-2 md:grid-cols-3 gap-2 sm:gap-3">
                                    <div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">
                                        Select a date to see available time slots
                                    </div>
                                </div>
                                <input type="hidden" name="start_time" id="referralSelectedTime" required>
                            </div>

                            <div>
                                <label for="referral_reason" class="field-label">Reason (optional)</label>
                                <textarea id="referral_reason" name="referral_reason" rows="3"
                                          class="textarea-field"
                                          placeholder="Explain the reason for referring the student..."></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end gap-2 pt-2">
                                <button type="button" onclick="closeReferralModal()" class="secondary-btn px-4 py-2 text-xs sm:text-sm">
                                    Cancel
                                </button>
                                <button type="submit" class="primary-btn px-4 py-2 text-xs sm:text-sm">
                                    Send Referral Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reschedule Modal -->
        <div id="rescheduleModal" class="modal-backdrop hidden">
            <div class="modal-card modal-card-sm">
                <div class="modal-header">
                    <h3 class="text-sm font-semibold text-[#2c2420]">Reschedule Appointment</h3>
                    <button onclick="closeRescheduleModal()" class="modal-close" title="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="rescheduleForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label class="field-label">Select Date</label>
                                <div class="border border-[#e5e0db] rounded-xl bg-white p-4 shadow-sm">
                                    <div class="calendar-nav">
                                        <button type="button" id="rescheduleCalendarPrev" class="calendar-nav-btn">‹</button>
                                        <h3 id="rescheduleCalendarMonthLabel" class="text-sm font-semibold text-[#2c2420]"></h3>
                                        <button type="button" id="rescheduleCalendarNext" class="calendar-nav-btn">›</button>
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
                                    <div id="rescheduleCalendarGrid" class="calendar-grid"></div>
                                    <p id="rescheduleCalendarStatus" class="mt-3 text-[10px] sm:text-xs text-[#8b7e76]">
                                        Select a counselor to load available dates.
                                    </p>
                                </div>
                                <input type="hidden" name="appointment_date" id="rescheduleDateSelect" required>
                            </div>
                            <div>
                                <label class="field-label">Available Time Slots</label>
                                <div id="rescheduleTimeSlots" class="grid grid-cols-2 md:grid-cols-3 gap-2 sm:gap-3">
                                    <div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">
                                        Select a date to see available time slots
                                    </div>
                                </div>
                                <input type="hidden" name="start_time" id="rescheduleSelectedTime" required>
                            </div>
                            <div>
                                <label for="reschedule_reason" class="field-label">Reason (optional)</label>
                                <textarea id="reschedule_reason" name="reason" rows="3" class="textarea-field" placeholder="Explain the reason for rescheduling..."></textarea>
                            </div>
                            <div class="flex flex-col sm:flex-row justify-end gap-2 pt-2">
                                <button type="button" onclick="closeRescheduleModal()" class="secondary-btn px-4 py-2 text-xs sm:text-sm">
                                    Cancel
                                </button>
                                <button type="submit" class="primary-btn px-4 py-2 text-xs sm:text-sm">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Export to Excel functionality
            function exportAppointmentsToExcel() {
                // Show loading indicator
                const exportBtn = event.target;
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
                exportBtn.disabled = true;

                try {
                    const table = document.getElementById('appointmentsTable');

                    // Create a copy of the table for export (without action buttons)
                    const exportTable = table.cloneNode(true);

                    // Remove action column from export
                    const headers = exportTable.getElementsByTagName('thead')[0].rows[0].cells;
                    const actionHeaderIndex = Array.from(headers).findIndex(th =>
                        th.textContent.trim() === 'Actions'
                    );

                    if (actionHeaderIndex > -1) {
                        // Remove action header
                        headers[actionHeaderIndex].remove();

                        // Remove action cells from all rows
                        const rows = exportTable.getElementsByTagName('tbody')[0].rows;
                        for (let row of rows) {
                            if (row.cells.length > actionHeaderIndex) {
                                row.deleteCell(actionHeaderIndex);
                            }
                        }
                    }

                    const ws = XLSX.utils.table_to_sheet(exportTable);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Appointments");

                    // Get current date for filename
                    const today = new Date().toISOString().split('T')[0];
                    const fileName = `appointments_${today}.xlsx`;

                    XLSX.writeFile(wb, fileName);

                } catch (error) {
                    console.error('Error exporting to Excel:', error);
                    alert('Error exporting appointments. Please try again.');
                } finally {
                    // Restore button state
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                }
            }

            // Enhanced export with all data (including filtered results)
            function exportAllAppointmentsToExcel() {
                // Get current filters
                const search = document.getElementById('search').value;
                const dateRange = document.getElementById('date_range').value;
                const college = document.getElementById('college').value;
                const status = '{{ $status }}';

                // Show loading
                const exportBtn = event.target;
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
                exportBtn.disabled = true;

                // Create export URL with current filters
                let exportUrl = '{{ route("counselor.appointments.export") }}?';
                const params = new URLSearchParams();

                if (search) params.append('search', search);
                if (dateRange) params.append('date_range', dateRange);
                if (college) params.append('college', college);
                if (status && status !== 'all') params.append('status', status);

                exportUrl += params.toString();

                // Trigger download
                window.location.href = exportUrl;

                // Restore button after a delay
                setTimeout(() => {
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                }, 3000);
            }

            // Rejection Options Modal
            let currentAppointmentId = null;

            function showRejectionOptions(appointmentId) {
                currentAppointmentId = appointmentId;

                // Update form actions with the correct appointment ID
                const directRejectForm = document.getElementById('directRejectForm');
                directRejectForm.action = `/counselor/appointments/${appointmentId}/update-status`;

                document.getElementById('rejectionModal').classList.remove('hidden');
            }

            function closeRejectionModal() {
                document.getElementById('rejectionModal').classList.add('hidden');
                currentAppointmentId = null;
            }

            // Transfer Options Modal
            // Referral Modal
            let referralCounselorId = null;
            let referralCurrentMonth = null;
            let referralSelectedDate = null;
            let referralAvailabilityByDate = new Map();
            let referralAvailabilityRequestId = 0;

            const referralMinDate = new Date();
            referralMinDate.setHours(0, 0, 0, 0);

            function referralFormatDateValue(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function referralFormatMonthLabel(date) {
                return date.toLocaleString('en-US', { month: 'long', year: 'numeric' });
            }

            function referralIsSameDay(a, b) {
                return a && b &&
                    a.getFullYear() === b.getFullYear() &&
                    a.getMonth() === b.getMonth() &&
                    a.getDate() === b.getDate();
            }

            function setReferralCalendarStatus(message, tone = 'muted') {
                const calendarStatus = document.getElementById('referralCalendarStatus');
                if (!calendarStatus) {
                    return;
                }
                calendarStatus.textContent = message;
                calendarStatus.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');
                if (tone === 'success') {
                    calendarStatus.classList.add('text-green-600');
                } else if (tone === 'error') {
                    calendarStatus.classList.add('text-red-600');
                } else {
                    calendarStatus.classList.add('text-gray-500');
                }
            }

            function renderReferralCalendar() {
                const calendarGrid = document.getElementById('referralCalendarGrid');
                const calendarMonthLabel = document.getElementById('referralCalendarMonthLabel');

                if (!calendarGrid || !calendarMonthLabel || !referralCurrentMonth) {
                    return;
                }

                calendarMonthLabel.textContent = referralFormatMonthLabel(referralCurrentMonth);
                calendarGrid.innerHTML = '';

                const firstDayOfMonth = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth(), 1);
                const startDay = firstDayOfMonth.getDay();
                const daysInMonth = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth() + 1, 0).getDate();

                for (let i = 0; i < startDay; i++) {
                    const spacer = document.createElement('div');
                    calendarGrid.appendChild(spacer);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth(), day);
                    const dateValue = referralFormatDateValue(date);
                    const isPast = date < referralMinDate;
                    const availabilityKnown = referralAvailabilityByDate.has(dateValue);
                    const isAvailable = referralAvailabilityByDate.get(dateValue) === true;
                    const isDisabled = !referralCounselorId || isPast || !availabilityKnown || !isAvailable;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = day;
                    button.disabled = isDisabled;
                    button.className = 'calendar-day';

                    if (isDisabled) {
                        button.classList.add('disabled');
                    } else {
                        button.classList.add('available');
                    }

                    if (referralSelectedDate && referralIsSameDay(referralSelectedDate, date)) {
                        button.classList.add('selected');
                    }

                    button.addEventListener('click', () => {
                        if (button.disabled) {
                            return;
                        }
                        referralSelectedDate = date;
                        document.getElementById('referralDateSelect').value = referralFormatDateValue(date);
                        document.getElementById('referralSelectedTime').value = '';
                        setReferralCalendarStatus(
                            `Selected date: ${date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`,
                            'success'
                        );
                        renderReferralCalendar();
                        loadReferralAvailableSlots();
                    });

                    calendarGrid.appendChild(button);
                }
            }

            async function loadReferralMonthAvailability() {
                referralAvailabilityByDate = new Map();
                renderReferralCalendar();

                if (!referralCounselorId) {
                    setReferralCalendarStatus('Select a counselor to load available dates.');
                    return;
                }

                const requestId = ++referralAvailabilityRequestId;
                setReferralCalendarStatus('Checking available dates...');
                const monthValue = `${referralCurrentMonth.getFullYear()}-${String(referralCurrentMonth.getMonth() + 1).padStart(2, '0')}`;

                try {
                    const response = await fetch(`/appointments/available-dates?counselor_id=${referralCounselorId}&month=${monthValue}&allow_today=1`);
                    const data = await response.json();
                    if (requestId !== referralAvailabilityRequestId) {
                        return;
                    }
                    const availability = data.availability || {};
                    Object.keys(availability).forEach(dateValue => {
                        referralAvailabilityByDate.set(dateValue, availability[dateValue] === true);
                    });
                } catch (error) {
                    if (requestId !== referralAvailabilityRequestId) {
                        return;
                    }
                }

                if (requestId !== referralAvailabilityRequestId) {
                    return;
                }

                const hasAnyAvailability = Array.from(referralAvailabilityByDate.values()).some(value => value);
                if (!hasAnyAvailability) {
                    setReferralCalendarStatus('No available dates for this counselor in the selected month.', 'error');
                } else {
                    setReferralCalendarStatus('Available dates are highlighted. Select a date to continue.');
                }

                if (referralSelectedDate && (!referralAvailabilityByDate.get(referralFormatDateValue(referralSelectedDate)))) {
                    referralSelectedDate = null;
                    document.getElementById('referralDateSelect').value = '';
                    document.getElementById('referralSelectedTime').value = '';
                }

                renderReferralCalendar();
                if (referralSelectedDate) {
                    loadReferralAvailableSlots();
                }
            }

            function loadReferralAvailableSlots() {
                const date = document.getElementById('referralDateSelect').value;
                const timeSlots = document.getElementById('referralTimeSlots');
                const selectedTime = document.getElementById('referralSelectedTime');

                if (!referralCounselorId || !date) {
                    timeSlots.innerHTML = '<div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">Select a date to see available time slots</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '<div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">Loading available slots...</div>';

                fetch(`{{ route('appointments.available-slots') }}?counselor_id=${referralCounselorId}&date=${date}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        timeSlots.innerHTML = `<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg text-xs">${data.message}</div>`;
                        selectedTime.value = '';
                        return;
                    }

                    if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg text-xs">No working hours for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    const availableSlots = [...data.available_slots].sort((a, b) => a.start.localeCompare(b.start));

                    if (availableSlots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-yellow-700 text-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-xs">No available time slots for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    timeSlots.innerHTML = '';

                    availableSlots.forEach(slot => {
                        const slotElement = document.createElement('button');
                        slotElement.type = 'button';
                        slotElement.className = 'time-slot';
                        slotElement.textContent = slot.display;

                        slotElement.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot').forEach(s => {
                                s.classList.remove('selected');
                            });

                            this.classList.add('selected');

                            selectedTime.value = slot.start;
                        });

                        slotElement.dataset.start = slot.start;
                        slotElement.dataset.end = slot.end;
                        slotElement.dataset.status = slot.status;
                        timeSlots.appendChild(slotElement);
                    });
                })
                .catch(() => {
                    timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg text-xs">Error loading time slots. Please try again.</div>';
                });
            }

            function showReferralModal(appointmentId, currentDate, studentId, currentCounselorId) {
                referralCounselorId = null;
                const modal = document.getElementById('referralModal');
                const form = document.getElementById('referralForm');
                const counselorSelect = document.getElementById('referralCounselorSelect');
                const timeSlots = document.getElementById('referralTimeSlots');
                const dateSelect = document.getElementById('referralDateSelect');
                const selectedTime = document.getElementById('referralSelectedTime');

                form.action = `/counselor/appointments/${appointmentId}/refer`;

                const parsedDate = currentDate ? new Date(`${currentDate}T00:00:00`) : null;
                referralSelectedDate = parsedDate;
                referralCurrentMonth = new Date(
                    (parsedDate || referralMinDate).getFullYear(),
                    (parsedDate || referralMinDate).getMonth(),
                    1
                );

                dateSelect.value = parsedDate ? referralFormatDateValue(parsedDate) : '';
                selectedTime.value = '';
                timeSlots.innerHTML = '<div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">Select a date to see available time slots</div>';

                counselorSelect.innerHTML = '<option value="">Loading counselors...</option>';

                fetch(`{{ route('counselor.appointments.available-counselors') }}?student_id=${studentId}&current_counselor_id=${currentCounselorId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(counselors => {
                    counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';

                    if (counselors.error || !Array.isArray(counselors) || counselors.length === 0) {
                        counselorSelect.innerHTML = '<option value="">No counselors available</option>';
                        return;
                    }

                    counselors.sort((a, b) => a.display_text.localeCompare(b.display_text));
                    counselors.forEach(counselor => {
                        const option = document.createElement('option');
                        option.value = counselor.id;
                        option.textContent = counselor.display_text || counselor.name;
                        counselorSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    counselorSelect.innerHTML = '<option value="">Error loading counselors</option>';
                });

                counselorSelect.onchange = () => {
                    referralCounselorId = counselorSelect.value || null;
                    referralSelectedDate = parsedDate;
                    dateSelect.value = parsedDate ? referralFormatDateValue(parsedDate) : '';
                    selectedTime.value = '';
                    loadReferralMonthAvailability();
                };

                loadReferralMonthAvailability();
                modal.classList.remove('hidden');
            }

            function closeReferralModal() {
                document.getElementById('referralModal').classList.add('hidden');
                referralCounselorId = null;
                referralSelectedDate = null;
            }

            // Reschedule Modal
            let rescheduleCounselorId = null;
            let rescheduleCurrentMonth = null;
            let rescheduleSelectedDate = null;
            let rescheduleAvailabilityByDate = new Map();
            let rescheduleAvailabilityRequestId = 0;

            const rescheduleMinDate = new Date();
            rescheduleMinDate.setHours(0, 0, 0, 0);

            function rescheduleFormatDateValue(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function rescheduleFormatMonthLabel(date) {
                return date.toLocaleString('en-US', { month: 'long', year: 'numeric' });
            }

            function rescheduleIsSameDay(a, b) {
                return a && b &&
                    a.getFullYear() === b.getFullYear() &&
                    a.getMonth() === b.getMonth() &&
                    a.getDate() === b.getDate();
            }

            function setRescheduleCalendarStatus(message, tone = 'muted') {
                const calendarStatus = document.getElementById('rescheduleCalendarStatus');
                if (!calendarStatus) {
                    return;
                }
                calendarStatus.textContent = message;
                calendarStatus.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');
                if (tone === 'success') {
                    calendarStatus.classList.add('text-green-600');
                } else if (tone === 'error') {
                    calendarStatus.classList.add('text-red-600');
                } else {
                    calendarStatus.classList.add('text-gray-500');
                }
            }

            function renderRescheduleCalendar() {
                const calendarGrid = document.getElementById('rescheduleCalendarGrid');
                const calendarMonthLabel = document.getElementById('rescheduleCalendarMonthLabel');

                if (!calendarGrid || !calendarMonthLabel || !rescheduleCurrentMonth) {
                    return;
                }

                calendarMonthLabel.textContent = rescheduleFormatMonthLabel(rescheduleCurrentMonth);
                calendarGrid.innerHTML = '';

                const firstDayOfMonth = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth(), 1);
                const startDay = firstDayOfMonth.getDay();
                const daysInMonth = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth() + 1, 0).getDate();

                for (let i = 0; i < startDay; i++) {
                    const spacer = document.createElement('div');
                    calendarGrid.appendChild(spacer);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth(), day);
                    const dateValue = rescheduleFormatDateValue(date);
                    const isPast = date < rescheduleMinDate;
                    const availabilityKnown = rescheduleAvailabilityByDate.has(dateValue);
                    const isAvailable = rescheduleAvailabilityByDate.get(dateValue) === true;
                    const isDisabled = !rescheduleCounselorId || isPast || !availabilityKnown || !isAvailable;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = day;
                    button.disabled = isDisabled;
                    button.className = 'calendar-day';

                    if (isDisabled) {
                        button.classList.add('disabled');
                    } else {
                        button.classList.add('available');
                    }

                    if (rescheduleSelectedDate && rescheduleIsSameDay(rescheduleSelectedDate, date)) {
                        button.classList.add('selected');
                    }

                    button.addEventListener('click', () => {
                        if (button.disabled) {
                            return;
                        }
                        rescheduleSelectedDate = date;
                        document.getElementById('rescheduleDateSelect').value = rescheduleFormatDateValue(date);
                        document.getElementById('rescheduleSelectedTime').value = '';
                        setRescheduleCalendarStatus(
                            `Selected date: ${date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`,
                            'success'
                        );
                        renderRescheduleCalendar();
                        loadRescheduleAvailableSlots();
                    });

                    calendarGrid.appendChild(button);
                }
            }

            async function loadRescheduleMonthAvailability() {
                rescheduleAvailabilityByDate = new Map();
                renderRescheduleCalendar();

                if (!rescheduleCounselorId) {
                    setRescheduleCalendarStatus('Select a counselor to load available dates.');
                    return;
                }

                const requestId = ++rescheduleAvailabilityRequestId;
                setRescheduleCalendarStatus('Checking available dates...');
                const monthValue = `${rescheduleCurrentMonth.getFullYear()}-${String(rescheduleCurrentMonth.getMonth() + 1).padStart(2, '0')}`;

                try {
                    const response = await fetch(`/appointments/available-dates?counselor_id=${rescheduleCounselorId}&month=${monthValue}&allow_today=1`);
                    const data = await response.json();
                    if (requestId !== rescheduleAvailabilityRequestId) {
                        return;
                    }
                    const availability = data.availability || {};
                    Object.keys(availability).forEach(dateValue => {
                        rescheduleAvailabilityByDate.set(dateValue, availability[dateValue] === true);
                    });
                } catch (error) {
                    if (requestId !== rescheduleAvailabilityRequestId) {
                        return;
                    }
                }

                if (requestId !== rescheduleAvailabilityRequestId) {
                    return;
                }

                const hasAnyAvailability = Array.from(rescheduleAvailabilityByDate.values()).some(value => value);
                if (!hasAnyAvailability) {
                    setRescheduleCalendarStatus('No available dates for this counselor in the selected month.', 'error');
                } else {
                    setRescheduleCalendarStatus('Available dates are highlighted. Select a date to continue.');
                }

                if (rescheduleSelectedDate && (!rescheduleAvailabilityByDate.get(rescheduleFormatDateValue(rescheduleSelectedDate)))) {
                    rescheduleSelectedDate = null;
                    document.getElementById('rescheduleDateSelect').value = '';
                    document.getElementById('rescheduleSelectedTime').value = '';
                }

                renderRescheduleCalendar();
                if (rescheduleSelectedDate) {
                    loadRescheduleAvailableSlots();
                }
            }

            function loadRescheduleAvailableSlots() {
                const date = document.getElementById('rescheduleDateSelect').value;
                const timeSlots = document.getElementById('rescheduleTimeSlots');
                const selectedTime = document.getElementById('rescheduleSelectedTime');

                if (!rescheduleCounselorId || !date) {
                    timeSlots.innerHTML = '<div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">Select a date to see available time slots</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '<div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">Loading available slots...</div>';

                fetch(`{{ route('appointments.available-slots') }}?counselor_id=${rescheduleCounselorId}&date=${date}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        timeSlots.innerHTML = `<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg text-xs">${data.message}</div>`;
                        selectedTime.value = '';
                        return;
                    }

                    if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg text-xs">No working hours for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    const availableSlots = [...data.available_slots].sort((a, b) => a.start.localeCompare(b.start));

                    if (availableSlots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-yellow-700 text-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-xs">No available time slots for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    timeSlots.innerHTML = '';

                    availableSlots.forEach(slot => {
                        const slotElement = document.createElement('button');
                        slotElement.type = 'button';
                        slotElement.className = 'time-slot';
                        slotElement.textContent = slot.display;

                        slotElement.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot').forEach(s => {
                                s.classList.remove('selected');
                            });

                            this.classList.add('selected');

                            selectedTime.value = slot.start;
                        });

                        slotElement.dataset.start = slot.start;
                        slotElement.dataset.end = slot.end;
                        slotElement.dataset.status = slot.status;
                        timeSlots.appendChild(slotElement);
                    });
                })
                .catch(() => {
                    timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg text-xs">Error loading time slots. Please try again.</div>';
                });
            }

            function showRescheduleModal(appointmentId, counselorId, currentDate) {
                rescheduleCounselorId = counselorId;
                const modal = document.getElementById('rescheduleModal');
                const form = document.getElementById('rescheduleForm');
                const timeSlots = document.getElementById('rescheduleTimeSlots');
                const dateSelect = document.getElementById('rescheduleDateSelect');
                const selectedTime = document.getElementById('rescheduleSelectedTime');

                form.action = `/counselor/appointments/${appointmentId}/reschedule`;

                const parsedDate = currentDate ? new Date(`${currentDate}T00:00:00`) : null;
                rescheduleSelectedDate = parsedDate;
                rescheduleCurrentMonth = new Date(
                    (parsedDate || rescheduleMinDate).getFullYear(),
                    (parsedDate || rescheduleMinDate).getMonth(),
                    1
                );

                dateSelect.value = parsedDate ? rescheduleFormatDateValue(parsedDate) : '';
                selectedTime.value = '';
                timeSlots.innerHTML = '<div class="text-[#8b7e76] text-center p-4 border-2 border-dashed border-[#e5e0db] rounded-lg text-xs">Select a date to see available time slots</div>';

                loadRescheduleMonthAvailability();
                modal.classList.remove('hidden');
            }

            function closeRescheduleModal() {
                document.getElementById('rescheduleModal').classList.add('hidden');
                rescheduleCounselorId = null;
                rescheduleSelectedDate = null;
            }

            document.getElementById('referralCalendarPrev')?.addEventListener('click', function() {
                const prevMonth = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth() - 1, 1);
                const minMonth = new Date(referralMinDate.getFullYear(), referralMinDate.getMonth(), 1);
                if (prevMonth < minMonth) {
                    return;
                }
                referralCurrentMonth = prevMonth;
                loadReferralMonthAvailability();
            });

            document.getElementById('referralCalendarNext')?.addEventListener('click', function() {
                referralCurrentMonth = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth() + 1, 1);
                loadReferralMonthAvailability();
            });

            document.getElementById('rescheduleCalendarPrev')?.addEventListener('click', function() {
                const prevMonth = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth() - 1, 1);
                const minMonth = new Date(rescheduleMinDate.getFullYear(), rescheduleMinDate.getMonth(), 1);
                if (prevMonth < minMonth) {
                    return;
                }
                rescheduleCurrentMonth = prevMonth;
                loadRescheduleMonthAvailability();
            });

            document.getElementById('rescheduleCalendarNext')?.addEventListener('click', function() {
                rescheduleCurrentMonth = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth() + 1, 1);
                loadRescheduleMonthAvailability();
            });

            // Appointment Details Modal
            function showAppointmentDetails(appointmentId) {
                fetch(`/counselor/appointments/${appointmentId}/details`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const modal = document.getElementById('appointmentModal');
                        const details = document.getElementById('appointmentDetails');

                        details.innerHTML = `
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label">Student Name</label>
                                        <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.student.user.first_name} ${data.student.user.last_name}</p>
                                    </div>
                                    <div>
                                        <label class="field-label">Student ID</label>
                                        <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.student.student_id}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label">College</label>
                                        <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.student.college?.name || 'N/A'}</p>
                                    </div>
                                    <div>
                                        <label class="field-label">Year Level</label>
                                        <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.student.year_level}</p>
                                    </div>
                                </div>
                            

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label">Date</label>
                                        <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.formatted_date}</p>
                                    </div>
                                    <div>
                                        <label class="field-label">Time</label>
                                        <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.formatted_time}</p>
                                    </div>
                                </div>

                                ${(data.appointment.status === 'referred' && data.formatted_proposed_date && data.formatted_proposed_time) ? `
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="field-label" style="color: var(--maroon-700);">Proposed Date</label>
                                        <p class="mt-1 text-xs sm:text-sm" style="color: var(--maroon-700);">${data.formatted_proposed_date}</p>
                                    </div>
                                    <div>
                                        <label class="field-label" style="color: var(--maroon-700);">Proposed Time</label>
                                        <p class="mt-1 text-xs sm:text-sm" style="color: var(--maroon-700);">${data.formatted_proposed_time}</p>
                                    </div>
                                </div>
                                ` : ''}
                            <div>
                                <label class="field-label">Type of Booking</label>
                                <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.appointment.booking_type || 'N/A'}</p>
                            </div>

                                <div>
                                    <label class="field-label">Concern</label>
                                    <p class="mt-1 text-xs sm:text-sm text-[#6b5e57] whitespace-pre-line">${data.appointment.concern}</p>
                                </div>

                                ${data.appointment.notes ? `
                                <div>
                                    <label class="field-label">Counselor Notes</label>
                                    <p class="mt-1 text-xs sm:text-sm text-[#6b5e57] whitespace-pre-line">${data.appointment.notes}</p>
                                </div>
                                ` : ''}

                                ${(data.appointment.is_referred || data.appointment.referral_reason || data.referral?.referred_to_name || data.referral?.referred_from_name) ? `
                                <div>
                                    <label class="field-label">Referral Details</label>
                                    <div class="mt-1 p-3 rounded-lg border border-[#d4af37]/40 bg-[#fff9e6] space-y-1">
                                        ${data.referral?.referred_from_name ? `
                                        <p class="text-xs text-[#7a2a2a]"><span class="font-medium">Referred from:</span> ${data.referral.referred_from_name}</p>
                                        ` : ''}
                                        ${data.referral?.referred_to_name ? `
                                        <p class="text-xs text-[#7a2a2a]"><span class="font-medium">Referred to:</span> ${data.referral.referred_to_name}</p>
                                        ` : ''}
                                        ${data.formatted_referral_date ? `
                                        <p class="text-xs text-[#7a2a2a]"><span class="font-medium">Referral date:</span> ${data.formatted_referral_date}</p>
                                        ` : ''}
                                        ${data.appointment.referral_reason ? `
                                        <div class="pt-2">
                                            <p class="text-[10px] font-medium text-[#7a2a2a]">Reason:</p>
                                            <p class="text-xs text-[#7a2a2a] whitespace-pre-line">${data.appointment.referral_reason}</p>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                ` : ''}

                                <div>
                                    <label class="field-label">Status</label>
                                    <span class="mt-1 inline-flex px-2 py-1 text-[10px] font-semibold rounded-full status-chip ${data.appointment.status === 'pending' ? 'pending' : data.appointment.status === 'approved' ? 'approved' : data.appointment.status === 'rejected' ? 'rejected' : data.appointment.status === 'referred' ? 'referred' : 'completed'}">
                                        ${data.appointment.status_display || (data.appointment.status.charAt(0).toUpperCase() + data.appointment.status.slice(1))}
                                    </span>
                                </div>

                                ${data.appointment.has_session_notes ? `
                                <div class="border-t pt-4 mt-4" style="border-color: var(--border-soft)/60;">
                                    <h4 class="text-xs font-semibold text-[#2c2420] mb-2">Session Notes</h4>
                                    <div class="space-y-3">
                                        ${data.session_notes.map(note => `
                                            <div class="bg-[#faf8f5] rounded-lg p-3">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="text-[10px] font-medium text-[#6b5e57]">
                                                        ${note.session_date} • ${note.session_type_label}
                                                    </span>
                                                    ${note.mood_level ? `
                                                    <span class="text-[10px] px-2 py-1 rounded-full
                                                        ${note.mood_level === 'very_good' ? 'bg-green-100 text-green-800' :
                                                        note.mood_level === 'good' ? 'bg-[#f5f0eb] text-[#7a2a2a]' :
                                                        note.mood_level === 'neutral' ? 'bg-yellow-100 text-yellow-800' :
                                                        note.mood_level === 'low' ? 'bg-orange-100 text-orange-800' :
                                                        'bg-red-100 text-red-800'}">
                                                        ${note.mood_level_label}
                                                    </span>
                                                    ` : ''}
                                                </div>
                                                <p class="text-xs text-[#6b5e57] whitespace-pre-line">${note.notes}</p>
                                                ${note.follow_up_actions ? `
                                                <div class="mt-2">
                                                    <p class="text-[10px] font-medium text-[#6b5e57]">Follow-up:</p>
                                                    <p class="text-xs text-[#6b5e57] whitespace-pre-line">${note.follow_up_actions}</p>
                                                </div>
                                                ` : ''}
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                                ` : ''}

                                ${data.appointment.status === 'completed' && !data.appointment.has_session_notes ? `
                                <div class="border-t pt-4 mt-4" style="border-color: var(--border-soft)/60;">
                                    <a href="/counselor/appointments/${data.appointment.id}/session"
                                    class="inline-flex items-center text-[#7a2a2a] hover:text-[#5c1a1a] text-xs">
                                        <i class="fas fa-plus mr-1"></i> Add Session Notes
                                    </a>
                                </div>
                                ` : ''}

                                <div class="border-t pt-4 mt-4 flex flex-col sm:flex-row justify-end gap-2" style="border-color: var(--border-soft)/60;">
                                    ${data.appointment.session_url ? `
                                    <a href="${data.appointment.session_url}"
                                       class="primary-btn px-4 py-2 text-xs" style="background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);">
                                        <i class="fas fa-clipboard mr-1.5 text-[9px]"></i> Open Session
                                    </a>
                                    ` : ''}
                                    <a href="${data.student.profile_url}"
                                       class="primary-btn px-4 py-2 text-xs">
                                        <i class="fas fa-user mr-1.5 text-[9px]"></i> View Profile
                                    </a>
                                </div>
                            </div>
                        `;

                        modal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching appointment details:', error);
                        const modal = document.getElementById('appointmentModal');
                        const details = document.getElementById('appointmentDetails');
                        details.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-4xl text-red-300 mb-4"></i>
                                <p class="text-red-500">Error loading appointment details. Please try again.</p>
                            </div>
                        `;
                        modal.classList.remove('hidden');
                    });
            }

            function closeAppointmentModal() {
                document.getElementById('appointmentModal').classList.add('hidden');
            }

            // Helper functions for styling
            function getSessionNoteBorderColor(sessionType) {
                const colors = {
                    'initial': 'border-[#F00000]',
                    'follow_up': 'border-green-500',
                    'crisis': 'border-red-500',
                    'regular': 'border-[#820000]'
                };
                return colors[sessionType] || 'border-gray-500';
            }

            function getMoodLevelColor(moodLevel) {
                const colors = {
                    'very_good': 'bg-green-100 text-green-800',
                    'good': 'bg-gray-100 text-[#820000]',
                    'neutral': 'bg-yellow-100 text-yellow-800',
                    'low': 'bg-orange-100 text-orange-800',
                    'very_low': 'bg-red-100 text-red-800'
                };
                return colors[moodLevel] || 'bg-gray-100 text-gray-800';
            }

            // Close modals when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.id === 'appointmentModal') {
                    closeAppointmentModal();
                }
                if (e.target.id === 'rejectionModal') {
                    closeRejectionModal();
                }
                if (e.target.id === 'referralModal') {
                    closeReferralModal();
                }
                if (e.target.id === 'rescheduleModal') {
                    closeRescheduleModal();
                }
            });
        </script>
    </div>
@endsection