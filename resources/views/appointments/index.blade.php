@extends('layouts.student')

@section('title', 'Student Dashboard - OGC')

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

    .appointments-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .appointments-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .appointments-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .appointments-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .stat-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .stat-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before, .glass-card::before, .stat-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon, .stat-icon, .table-header-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .stat-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(122,42,42,0.1);
        color: var(--maroon-700); flex-shrink: 0;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.9);
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
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .primary-btn, .secondary-btn, .filter-btn, .action-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        font-size: 0.8rem; padding: 0.55rem 1rem;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        color: var(--text-primary); background: rgba(255,255,255,0.95);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }
    .filter-btn {
        background: rgba(254,249,231,0.9); color: var(--maroon-700);
        border: 1px solid rgba(212,175,55,0.3);
    }
    .filter-btn.active, .filter-btn:hover {
        background: rgba(212,175,55,0.2); border-color: var(--gold-400);
    }
    .action-btn {
        padding: 0.35rem 0.75rem; font-size: 0.7rem; border-radius: 0.5rem;
        border: 1px solid; transition: all 0.15s ease;
    }
    .action-btn.accept {
        background: rgba(236,253,245,0.9); color: #065f46; border-color: rgba(16,185,129,0.3);
    }
    .action-btn.accept:hover { background: rgba(236,253,245,0.95); }
    .action-btn.reject, .action-btn.cancel {
        background: rgba(254,242,242,0.9); color: #b91c1c; border-color: rgba(239,68,68,0.3);
    }
    .action-btn.reject:hover, .action-btn.cancel:hover { background: rgba(254,242,242,0.95); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon, .table-header-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; }
    .panel-icon { background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .table-header-icon { background: rgba(254,249,231,0.6); color: var(--gold-500); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .input-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.95); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    .success-alert, .error-alert {
        display: flex; align-items: flex-start; gap: 0.6rem;
        padding: 0.85rem 1.1rem; border-radius: 0.6rem; font-size: 0.8rem;
        border-left: 3px solid;
    }
    .success-alert {
        background: rgba(236,253,245,0.95); border-color: #10b981; color: #065f46;
    }
    .error-alert {
        background: rgba(254,242,242,0.96); border-color: #ef4444; color: #b91c1c;
    }
    .success-alert i, .error-alert i { margin-top: 0.1rem; }

    .table-header-bar {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.6rem;
        padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.4);
    }
    .table-live-pill {
        display: inline-flex; align-items: center; font-size: 0.65rem; color: var(--text-secondary);
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft); padding: 0.25rem 0.5rem;
        border-radius: 999px; font-weight: 500;
    }
    .table-row { transition: background-color 0.15s ease; }
    .table-row:hover { background: rgba(254,249,231,0.35); }

    .status-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.55rem; border-radius: 999px;
        font-size: 0.7rem; font-weight: 700;
    }
    .status-badge.pending { background: rgba(245,158,11,0.15); color: #92400e; border: 1px solid rgba(245,158,11,0.3); }
    .status-badge.approved, .status-badge.completed, .status-badge.referred, .status-badge.rescheduled {
        background: rgba(212,175,55,0.15); color: var(--maroon-800); border: 1px solid rgba(212,175,55,0.3);
    }
    .status-badge.rejected, .status-badge.reschedule_rejected {
        background: rgba(239,68,68,0.15); color: #7f1d1d; border: 1px solid rgba(239,68,68,0.3);
    }
    .status-badge.cancelled {
        background: rgba(156,163,175,0.15); color: #374151; border: 1px solid rgba(156,163,175,0.3);
    }
    .status-badge.reschedule_requested {
        background: rgba(249,115,22,0.15); color: #9a3412; border: 1px solid rgba(249,115,22,0.3);
    }

    .filter-chip {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.5rem; border-radius: 999px;
        background: rgba(254,249,231,0.9); color: var(--maroon-700);
        font-size: 0.7rem; font-weight: 600; border: 1px solid rgba(212,175,55,0.3);
    }
    .filter-chip a { color: var(--gold-500); transition: color 0.15s ease; }
    .filter-chip a:hover { color: var(--maroon-700); }

    .referral-info {
        font-size: 0.75rem; color: var(--maroon-700);
    }
    .referral-info i { color: var(--gold-500); margin-right: 0.2rem; }

    .empty-state {
        text-align: center; padding: 2.5rem 1.5rem;
        background: rgba(255,255,255,0.95); border: 1px solid var(--border-soft);
        border-radius: 0.75rem;
    }
    .empty-state-icon {
        width: 4rem; height: 4rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 1.5rem;
        background: rgba(254,249,231,0.8); color: var(--maroon-700);
        border: 2px dashed var(--gold-400);
    }

    /* Modal - adapted to design system */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(44,36,32,0.5);
        display: flex; align-items: center; justify-content: center;
        z-index: 50; padding: 1rem;
    }
    .modal-overlay.hidden { display: none; }
    .modal-card {
        background: white; border-radius: 0.75rem; border: 1px solid var(--border-soft);
        box-shadow: 0 8px 32px rgba(44,36,32,0.12); max-width: 32rem; width: 100%;
        overflow: hidden;
    }
    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.6);
    }
    .modal-title { font-size: 0.85rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.4rem; }
    .modal-title i { color: var(--maroon-700); }
    .modal-close {
        background: none; border: none; color: var(--text-muted);
        font-size: 1.1rem; cursor: pointer; transition: color 0.15s ease;
    }
    .modal-close:hover { color: var(--maroon-700); }
    .modal-body { padding: 1rem 1.25rem; }
    .modal-info-box {
        background: rgba(254,249,231,0.9); border: 1px solid rgba(212,175,55,0.3);
        border-radius: 0.6rem; padding: 0.75rem; margin-bottom: 0.75rem;
        font-size: 0.8rem; color: var(--maroon-800);
    }
    .modal-content {
        background: rgba(250,248,245,0.8); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 0.85rem; font-size: 0.8rem;
        color: var(--text-primary); line-height: 1.5; max-height: 16rem; overflow-y: auto;
    }
    .modal-footer {
        padding: 0.85rem 1.25rem; border-top: 1px solid var(--border-soft);
        display: flex; justify-content: flex-end; gap: 0.5rem;
    }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn, .filter-btn, .action-btn { width: 100%; justify-content: center; padding: 0.7rem; font-size: 0.75rem; }
        .btn-row-mobile { flex-direction: column; gap: 0.5rem !important; }
        .hero-card, .summary-card { padding: 1rem !important; }
        .hero-icon { width: 2.25rem; height: 2.25rem; }
        .stat-icon { width: 2.25rem; height: 2.25rem; font-size: 0.9rem; }
        .stat-grid-mobile { grid-template-columns: 1fr 1fr !important; gap: 0.75rem !important; }
        .table-header-bar { flex-direction: column; align-items: flex-start; }
        .filters-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 0.5rem; width: 100%; }
        .filters-scroll > div { display: flex; gap: 0.5rem; min-width: max-content; }
        .action-buttons-mobile { flex-direction: column; gap: 0.4rem !important; }
    }
</style>

<div class="min-h-screen appointments-shell">
    <div class="appointments-glow one"></div>
    <div class="appointments-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        {{-- Header Section --}}
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-check text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                My Appointments
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">My Appointments</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Manage and track your counseling sessions with ease.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-plus text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Quick Action</p>
                                <p class="summary-value">Book Session</p>
                                <p class="summary-subtext hidden sm:block">Schedule a new counseling appointment.</p>
                            </div>
                        </div>
                        <a href="{{ route('appointments.create') }}" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Book New Appointment</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="glass-card success-alert mb-5">
                <i class="fas fa-check-circle text-sm"></i>
                <div>
                    <p class="font-semibold">Success!</p>
                    <p class="text-sm mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="glass-card error-alert mb-5">
                <i class="fas fa-exclamation-circle text-sm"></i>
                <div>
                    <p class="font-semibold">Error!</p>
                    <p class="text-sm mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Statistics Cards --}}
        @php
            $stats = [
                'total' => $appointments->count(),
                'pending' => $appointments->where('status', 'pending')->count(),
                'approved' => $appointments->where('status', 'approved')->count(),
                'rejected' => $appointments->where('status', 'rejected')->count(),
                'cancelled' => $appointments->where('status', 'cancelled')->count(),
                'completed' => $appointments->where('status', 'completed')->count(),
                'referred' => $appointments->filter(function ($appointment) {
                    return !is_null($appointment->original_counselor_id)
                        || !is_null($appointment->referred_to_counselor_id);
                })->count(),
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6 stat-grid-mobile">
            {{-- Total --}}
            <div class="stat-card">
                <div class="p-4 flex items-center gap-3">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[0.7rem] text-[#6b5e57]">Total</p>
                        <p class="text-lg font-bold text-[#2c2420] leading-tight">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            {{-- Pending --}}
            <div class="stat-card">
                <div class="p-4 flex items-center gap-3">
                    <div class="stat-icon" style="background:rgba(245,158,11,0.15);color:#92400e">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[0.7rem] text-[#6b5e57]">Pending</p>
                        <p class="text-lg font-bold text-[#2c2420] leading-tight">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>
            {{-- Approved --}}
            <div class="stat-card">
                <div class="p-4 flex items-center gap-3">
                    <div class="stat-icon" style="background:rgba(212,175,55,0.15);color:var(--maroon-800)">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[0.7rem] text-[#6b5e57]">Approved</p>
                        <p class="text-lg font-bold text-[#2c2420] leading-tight">{{ $stats['approved'] }}</p>
                    </div>
                </div>
            </div>
            {{-- Rejected --}}
            <div class="stat-card">
                <div class="p-4 flex items-center gap-3">
                    <div class="stat-icon" style="background:rgba(239,68,68,0.15);color:#7f1d1d">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[0.7rem] text-[#6b5e57]">Rejected</p>
                        <p class="text-lg font-bold text-[#2c2420] leading-tight">{{ $stats['rejected'] }}</p>
                    </div>
                </div>
            </div>
            {{-- Cancelled --}}
            <div class="stat-card">
                <div class="p-4 flex items-center gap-3">
                    <div class="stat-icon" style="background:rgba(156,163,175,0.15);color:#374151">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[0.7rem] text-[#6b5e57]">Cancelled</p>
                        <p class="text-lg font-bold text-[#2c2420] leading-tight">{{ $stats['cancelled'] }}</p>
                    </div>
                </div>
            </div>
            {{-- Referred --}}
            <div class="stat-card">
                <div class="p-4 flex items-center gap-3">
                    <div class="stat-icon" style="background:rgba(212,175,55,0.15);color:var(--maroon-800)">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[0.7rem] text-[#6b5e57]">Referred</p>
                        <p class="text-lg font-bold text-[#2c2420] leading-tight">{{ $stats['referred'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filters Section --}}
        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-sliders-h text-[9px] sm:text-xs"></i></div>
                <div>
                    <h3 class="panel-title">Search and Filter</h3>
                    <p class="panel-subtitle hidden sm:block">Find appointments by date or status.</p>
                </div>
            </div>

            <div class="p-4 sm:p-5">
                <form method="GET" action="{{ route('appointments.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="filters-scroll w-full">
                        <div class="flex flex-wrap gap-3">
                            <div class="min-w-[160px]">
                                <label class="field-label">Date</label>
                                <input type="date" name="search_date" id="search_date"
                                       value="{{ request('search_date') }}"
                                       class="input-field">
                            </div>
                            <div class="min-w-[180px]">
                                <label class="field-label">Status</label>
                                <select name="status" id="status" class="select-field">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="reschedule_requested" {{ request('status') == 'reschedule_requested' ? 'selected' : '' }}>Reschedule Requested</option>
                                    <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                    <option value="reschedule_rejected" {{ request('status') == 'reschedule_rejected' ? 'selected' : '' }}>Rejected by Student</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="referred" {{ request('status') == 'referred' ? 'selected' : '' }}>Referred</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-end gap-2 btn-row-mobile">
                        <button type="submit" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                            <i class="fas fa-search mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Search</span>
                        </button>
                        <a href="{{ route('appointments.index') }}" class="secondary-btn px-5 py-2.5 text-xs sm:text-sm">
                            <i class="fas fa-rotate mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>

                {{-- Active Filters Display --}}
                @if(request()->anyFilled(['search_date', 'status']))
                <div class="mt-4 pt-4 border-t border-[#e5e0db]/60">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex flex-wrap items-center gap-2 text-[0.75rem]">
                            <span class="text-[#6b5e57]">Active filters:</span>
                            @if(request('search_date'))
                                <span class="filter-chip">
                                    Date: {{ \Carbon\Carbon::parse(request('search_date'))->format('M j, Y') }}
                                    <a href="{{ request()->fullUrlWithQuery(['search_date' => null]) }}"><i class="fas fa-times text-[9px]"></i></a>
                                </span>
                            @endif
                            @if(request('status'))
                                <span class="filter-chip">
                                    @php
                                        $filterStatusLabels = [
                                            'reschedule_rejected' => 'Rejected by Student',
                                            'reschedule_requested' => 'Reschedule Requested',
                                            'rescheduled' => 'Rescheduled',
                                        ];
                                    @endphp
                                    Status: {{ $filterStatusLabels[request('status')] ?? ucwords(str_replace('_', ' ', request('status'))) }}
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"><i class="fas fa-times text-[9px]"></i></a>
                                </span>
                            @endif
                        </div>
                        <span class="text-[0.75rem] text-[#6b5e57]">
                            {{ $appointments->count() }} appointment(s) found
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Quick Filter Buttons --}}
        <div class="panel-card mb-5 sm:mb-6">
            <div class="p-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('appointments.index') }}"
                       class="filter-btn px-4 py-2 text-xs {{ !request('status') && !request('search_date') ? 'active' : '' }}">
                        <i class="fas fa-list mr-1.5 text-[9px]"></i>All
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'pending']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'pending' ? 'active' : '' }}">
                        <i class="fas fa-clock mr-1.5 text-[9px]"></i>Pending
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'approved']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'approved' ? 'active' : '' }}">
                        <i class="fas fa-check mr-1.5 text-[9px]"></i>Approved
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'reschedule_requested']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'reschedule_requested' ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt mr-1.5 text-[9px]"></i>Reschedule Req.
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'rescheduled']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'rescheduled' ? 'active' : '' }}">
                        <i class="fas fa-calendar-check mr-1.5 text-[9px]"></i>Rescheduled
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'reschedule_rejected']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'reschedule_rejected' ? 'active' : '' }}">
                        <i class="fas fa-times mr-1.5 text-[9px]"></i>Rejected by Me
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'completed']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'completed' ? 'active' : '' }}">
                        <i class="fas fa-check-double mr-1.5 text-[9px]"></i>Completed
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'referred']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'referred' ? 'active' : '' }}">
                        <i class="fas fa-exchange-alt mr-1.5 text-[9px]"></i>Referred
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'rejected']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'rejected' ? 'active' : '' }}">
                        <i class="fas fa-times mr-1.5 text-[9px]"></i>Rejected
                    </a>
                    <a href="{{ route('appointments.index', ['status' => 'cancelled']) }}"
                       class="filter-btn px-4 py-2 text-xs {{ request('status') == 'cancelled' ? 'active' : '' }}">
                        <i class="fas fa-ban mr-1.5 text-[9px]"></i>Cancelled
                    </a>
                </div>
            </div>
        </div>

        {{-- Appointments Table --}}
        <div class="panel-card overflow-hidden">
            @if($appointments->isEmpty())
                <div class="glass-card empty-state m-4">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Appointments Found</h3>
                    <p class="text-[#6b5e57] text-sm mb-4 max-w-md mx-auto">
                        @if(request()->anyFilled(['search_date', 'status']))
                            Try adjusting your filters to see more results.
                        @else
                            You haven't booked any counseling sessions yet.
                        @endif
                    </p>
                    @if(request()->anyFilled(['search_date', 'status']))
                        <a href="{{ route('appointments.index') }}" class="secondary-btn px-5 py-2.5 text-xs sm:text-sm mb-3">
                            <i class="fas fa-filter mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Clear Filters</span>
                        </a>
                    @endif
                    <a href="{{ route('appointments.create') }}" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                        <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i>
                        <span>Book Your First Appointment</span>
                    </a>
                </div>
            @else
                <div class="table-header-bar">
                    <div class="flex items-center gap-3">
                        <div class="table-header-icon">
                            <i class="fas fa-calendar-alt text-[9px] sm:text-xs"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-[#2c2420]">Appointments</h2>
                            <p class="text-[10px] sm:text-xs text-[#8b7e76]">Total: {{ $appointments->count() }} sessions</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="table-live-pill">
                            <i class="far fa-clock mr-1 text-[9px]"></i> Updated recently
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto -webkit-overflow-scrolling: touch;">
                    <table class="w-full min-w-[800px] divide-y divide-[#e5e0db]/60">
                        <thead class="bg-[#faf8f5]/80">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Date & Time</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Counselor</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Concern</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Referral</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                            @foreach($appointments as $appointment)
                                <tr class="table-row">
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                        <div class="text-xs sm:text-sm font-semibold text-[#2c2420]">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                        </div>
                                        <div class="text-[10px] sm:text-xs text-[#6b5e57]">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        </div>
                                        @if($appointment->status === 'reschedule_requested' && $appointment->proposed_date)
                                            <div class="text-[10px] text-[#9a3412] mt-1">
                                                Proposed: {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j') }}
                                                {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }}
                                            </div>
                                            @if($appointment->reschedule_reason)
                                                <div class="text-[10px] text-[#9a3412]">
                                                    Reason: {{ Str::limit($appointment->reschedule_reason, 30) }}
                                                </div>
                                            @endif
                                        @elseif($appointment->status === 'referred' && $appointment->proposed_date)
                                            <div class="text-[10px] text-[#7a2a2a] mt-1">
                                                Proposed: {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j') }}
                                                {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }}
                                            </div>
                                            @if($appointment->referral_reason)
                                                <div class="text-[10px] text-[#7a2a2a]">
                                                    Reason: {{ Str::limit($appointment->referral_reason, 30) }}
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                        <div class="text-xs sm:text-sm font-semibold text-[#2c2420]">
                                            {{ $appointment->counselor->user->first_name }} {{ $appointment->counselor->user->last_name }}
                                        </div>
                                        <div class="text-[10px] sm:text-xs text-[#6b5e57]">
                                            {{ $appointment->counselor->position }}
                                        </div>
                                        @if($appointment->is_referred && $appointment->original_counselor_id)
                                            <div class="text-[10px] text-[#7a2a2a] mt-1 referral-info">
                                                <i class="fas fa-exchange-alt"></i>
                                                Originally: {{ $appointment->originalCounselor->user->first_name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5">
                                        <div class="text-xs sm:text-sm text-[#6b5e57] max-w-[140px] sm:max-w-[180px] truncate" title="{{ $appointment->concern }}">
                                            {{ $appointment->concern }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                        @php
                                            $statusClass = match($appointment->status) {
                                                'pending' => 'pending',
                                                'approved', 'completed', 'referred', 'rescheduled' => 'approved',
                                                'rejected', 'reschedule_rejected' => 'rejected',
                                                'cancelled' => 'cancelled',
                                                'reschedule_requested' => 'reschedule_requested',
                                                default => 'pending',
                                            };
                                            $statusLabels = [
                                                'rescheduled' => 'Rescheduled',
                                                'reschedule_requested' => 'Reschedule Req.',
                                                'reschedule_rejected' => 'Rejected by Me',
                                            ];
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ $appointment->status === 'referred'
                                                ? ($appointment->getStatusWithReferralContext((int) $appointment->counselor_id))
                                                : ($statusLabels[$appointment->status] ?? ucfirst($appointment->status))
                                            }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                        @if($appointment->status === 'referred' && $appointment->is_referred)
                                            <div class="text-[0.75rem] referral-info">
                                                <div class="font-medium">
                                                    <i class="fas fa-user-md"></i>
                                                    {{ $appointment->referredCounselor->user->first_name }}
                                                </div>
                                                <div class="text-[#6b5e57] mt-0.5">
                                                    <i class="fas fa-university"></i>
                                                    {{ Str::limit($appointment->referredCounselor->college->name ?? 'N/A', 15) }}
                                                </div>
                                                @if($appointment->referral_reason)
                                                    <button type="button"
                                                            onclick="showReferralReason(
                                                                '{{ addslashes($appointment->referral_reason) }}',
                                                                '{{ $appointment->originalCounselor->user->first_name }} {{ $appointment->originalCounselor->user->last_name }}',
                                                                '{{ $appointment->referredCounselor->user->first_name }} {{ $appointment->referredCounselor->user->last_name }}',
                                                                {{ $appointment->student->college_id != $appointment->referredCounselor->college_id ? 'true' : 'false' }}
                                                            )"
                                                            class="text-[#c9a227] hover:text-[#7a2a2a] mt-1 inline-flex items-center gap-1">
                                                        <i class="fas fa-info-circle text-[9px]"></i>
                                                        View reason
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-[#8b7e76] text-[0.75rem]">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-1.5 action-buttons-mobile">
                                            @if($appointment->status === 'reschedule_requested' && Auth::user()->role === 'student')
                                                <form action="{{ route('appointments.reschedule.accept', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="action-btn accept"
                                                            onclick="return confirm('Accept the new appointment time?')">
                                                        <i class="fas fa-check text-[9px]"></i> Accept
                                                    </button>
                                                </form>
                                                <form action="{{ route('appointments.reschedule.reject', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="action-btn reject"
                                                            onclick="return confirm('Reject the proposed time and keep the original schedule?')">
                                                        <i class="fas fa-times text-[9px]"></i> Reject
                                                    </button>
                                                </form>
                                            @elseif(in_array($appointment->status, ['pending', 'approved', 'rescheduled'], true) && Auth::user()->role === 'student')
                                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="action-btn cancel"
                                                            onclick="return confirm('Are you sure you want to cancel this appointment? The time slot will become available for others.')">
                                                        <i class="fas fa-times text-[9px]"></i> Cancel
                                                    </button>
                                                </form>
                                            @elseif($appointment->status === 'referred' && Auth::user()->role === 'student')
                                                @if($appointment->proposed_date && $appointment->proposed_start_time && $appointment->proposed_end_time)
                                                    <form action="{{ route('appointments.referral.accept', $appointment) }}" method="POST" class="inline">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="action-btn accept"
                                                                onclick="return confirm('Accept the referral schedule with the new counselor?')">
                                                            <i class="fas fa-check text-[9px]"></i> Accept
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('appointments.referral.reject', $appointment) }}" method="POST" class="inline">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="action-btn reject"
                                                                onclick="return confirm('Reject this referral? This appointment will be closed and you will need to create a new request if you still want counseling.')">
                                                            <i class="fas fa-times text-[9px]"></i> Reject
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-[#7a2a2a] text-[0.7rem] italic">Awaiting details</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal for Referral Reason --}}
<div id="referralReasonModal" class="modal-overlay hidden">
    <div class="modal-card">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-info-circle"></i>Referral Reason
            </h3>
            <button onclick="closeReferralReasonModal()" class="modal-close" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="referralCounselorInfo" class="modal-info-box">
                <p class="text-sm" id="counselorInfoText"></p>
            </div>
            <div id="referralReasonContent" class="modal-content whitespace-pre-line"></div>
        </div>
        <div class="modal-footer">
            <button onclick="closeReferralReasonModal()" class="secondary-btn px-4 py-2 text-xs">
                <i class="fas fa-times mr-1.5 text-[9px]"></i>Close
            </button>
        </div>
    </div>
</div>

<script>
// Function to show referral reason modal
function showReferralReason(reason, originalCounselorName = '', referredCounselorName = '', isDifferentCollege = false) {
    // Set the referral reason content
    document.getElementById('referralReasonContent').textContent = reason || 'No referral reason provided.';

    // Build counselor info text
    let counselorInfo = '';
    if (originalCounselorName) {
        counselorInfo += `Originally with: ${originalCounselorName}`;
    }
    if (referredCounselorName) {
        counselorInfo += originalCounselorName ? ` → Referred to: ${referredCounselorName}` : `Referred to: ${referredCounselorName}`;
    }
    if (isDifferentCollege) {
        counselorInfo += ' (Different College)';
    }

    document.getElementById('counselorInfoText').textContent = counselorInfo || 'No counselor information available.';
    document.getElementById('referralReasonModal').classList.remove('hidden');
}

// Function to close referral reason modal
function closeReferralReasonModal() {
    document.getElementById('referralReasonModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'referralReasonModal') {
        closeReferralReasonModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReferralReasonModal();
    }
});
</script>
@endsection