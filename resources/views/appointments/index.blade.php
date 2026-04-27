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

    /* Cards */
    .hero-card, .panel-card, .glass-card, .stat-card {
        position: relative; overflow: hidden; border-radius: 1rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.96);
        box-shadow: 0 1px 3px rgba(44,36,32,0.04), 0 1px 2px rgba(44,36,32,0.03);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .stat-card:hover { box-shadow: 0 4px 12px rgba(44,36,32,0.06); }

    /* Hero Section */
    .hero-icon {
        width: 3rem; height: 3rem; border-radius: 1rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        background: rgba(212,175,55,0.12); padding: 0.2rem 0.65rem;
        font-size: 0.7rem; font-weight: 600; letter-spacing: 0.08em;
        color: var(--maroon-700); text-transform: uppercase;
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    /* Summary Card */
    .summary-card {
        position: relative; overflow: hidden; border-radius: 1rem;
        border: 1px solid rgba(92,26,26,0.2);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%);
        box-shadow: 0 4px 14px rgba(58,12,12,0.2);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.12;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 50%);
        pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem;
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
        display: flex; align-items: center; justify-content: center; color: var(--gold-400);
    }
    .summary-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.1rem; font-weight: 700; color: white; margin-top: 0.2rem; }

    /* Buttons */
    .btn-primary, .btn-secondary, .btn-filter {
        border-radius: 0.5rem; font-weight: 500; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem; padding: 0.5rem 1rem; gap: 0.5rem;
    }
    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: white; box-shadow: 0 2px 6px rgba(92,26,26,0.2);
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(92,26,26,0.25); }
    .btn-secondary {
        background: rgba(255,255,255,0.95); color: var(--text-primary);
        border: 1px solid var(--border-soft);
    }
    .btn-secondary:hover { background: rgba(254,249,231,0.8); border-color: var(--maroon-700); }
    .btn-filter {
        background: white; color: var(--text-secondary);
        border: 1px solid var(--border-soft);
    }
    .btn-filter.active, .btn-filter:hover {
        background: var(--maroon-700); border-color: var(--maroon-700); color: white;
    }

    /* Panel */
    .panel-header {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-soft);
    }
    .panel-icon {
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        background: rgba(122,42,42,0.08); color: var(--maroon-700);
        display: flex; align-items: center; justify-content: center;
    }
    .panel-title { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.1rem; }

    /* Form Elements */
    .form-label {
        display: block; font-size: 0.7rem; font-weight: 600;
        color: var(--text-secondary); margin-bottom: 0.35rem;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .form-input, .form-select {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.5rem;
        background: white; color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.5rem 0.75rem;
    }
    .form-input:focus, .form-select:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    /* Filter Chips */
    .filter-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.25rem 0.6rem; border-radius: 999px;
        background: rgba(254,249,231,0.9); color: var(--maroon-700);
        font-size: 0.7rem; font-weight: 500; border: 1px solid rgba(212,175,55,0.3);
    }
    .filter-chip a { color: var(--gold-500); transition: color 0.15s; }
    .filter-chip a:hover { color: var(--maroon-700); }

    /* Quick Filter Bar */
    .quick-filter-bar {
        display: flex; flex-wrap: wrap; gap: 0.5rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.4);
    }

    /* Alerts */
    .alert-success, .alert-error {
        display: flex; align-items: flex-start; gap: 0.75rem;
        padding: 0.85rem 1.1rem; border-radius: 0.75rem; font-size: 0.8rem;
        border-left: 3px solid;
    }
    .alert-success { background: rgba(236,253,245,0.96); border-color: #10b981; color: #065f46; }
    .alert-error { background: rgba(254,242,242,0.96); border-color: #ef4444; color: #b91c1c; }

    /* Status Badges */
    .status-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.2rem 0.6rem; border-radius: 999px;
        font-size: 0.7rem; font-weight: 600;
    }
    .status-badge.pending { background: rgba(245,158,11,0.12); color: #92400e; border: 1px solid rgba(245,158,11,0.25); }
    .status-badge.approved, .status-badge.completed, .status-badge.referred, .status-badge.rescheduled {
        background: rgba(212,175,55,0.12); color: var(--maroon-800); border: 1px solid rgba(212,175,55,0.25);
    }
    .status-badge.rejected, .status-badge.reschedule_rejected {
        background: rgba(239,68,68,0.1); color: #7f1d1d; border: 1px solid rgba(239,68,68,0.25);
    }
    .status-badge.cancelled {
        background: rgba(156,163,175,0.1); color: #374151; border: 1px solid rgba(156,163,175,0.25);
    }
    .status-badge.reschedule_requested {
        background: rgba(249,115,22,0.12); color: #9a3412; border: 1px solid rgba(249,115,22,0.25);
    }

    /* Action Buttons */
    .action-btn {
        padding: 0.3rem 0.7rem; font-size: 0.7rem; border-radius: 0.4rem;
        font-weight: 500; transition: all 0.15s ease;
        display: inline-flex; align-items: center; gap: 0.35rem;
    }
    .action-btn.accept {
        background: rgba(16,185,129,0.1); color: #065f46; border: 1px solid rgba(16,185,129,0.3);
    }
    .action-btn.accept:hover { background: rgba(16,185,129,0.15); }
    .action-btn.reject, .action-btn.cancel {
        background: rgba(239,68,68,0.08); color: #b91c1c; border: 1px solid rgba(239,68,68,0.25);
    }
    .action-btn.reject:hover, .action-btn.cancel:hover { background: rgba(239,68,68,0.12); }
    .action-btn.info {
        background: rgba(59,130,246,0.08); color: #1d4ed8; border: 1px solid rgba(59,130,246,0.22);
    }
    .action-btn.info:hover { background: rgba(59,130,246,0.14); }

    /* Table */
    .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .appointments-table { width: 100%; min-width: 800px; border-collapse: separate; border-spacing: 0; }
    .appointments-table th {
        padding: 0.875rem 1rem; text-align: left; font-size: 0.7rem; font-weight: 600;
        color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;
        background: rgba(250,248,245,0.6); border-bottom: 1px solid var(--border-soft);
    }
    .appointments-table td {
        padding: 1rem; border-bottom: 1px solid rgba(229,224,219,0.5);
        font-size: 0.8rem; color: var(--text-primary);
    }
    .appointments-table tr:hover td { background: rgba(254,249,231,0.3); }

    /* Empty State */
    .empty-state {
        text-align: center; padding: 3rem 1.5rem;
        background: rgba(255,255,255,0.96); border-radius: 1rem;
    }
    .empty-icon {
        width: 4rem; height: 4rem; border-radius: 999px;
        background: rgba(254,249,231,0.8); border: 2px dashed var(--gold-400);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 1.5rem; color: var(--maroon-700);
    }

    /* Modal */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(44,36,32,0.5);
        display: flex; align-items: center; justify-content: center;
        z-index: 50; padding: 1rem;
    }
    .modal-overlay.hidden { display: none; }
    .modal-container {
        background: white; border-radius: 1rem; border: 1px solid var(--border-soft);
        box-shadow: 0 20px 35px rgba(44,36,32,0.15); max-width: 32rem; width: 100%;
        overflow: hidden;
    }
    .modal-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-soft);
        background: rgba(250,248,245,0.5);
    }
    .modal-title { font-size: 0.9rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem; }
    .modal-close { background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1rem; }
    .modal-close:hover { color: var(--maroon-700); }
    .modal-body { padding: 1.25rem; }
    .modal-info {
        background: rgba(254,249,231,0.8); border: 1px solid rgba(212,175,55,0.25);
        border-radius: 0.75rem; padding: 0.75rem; margin-bottom: 1rem;
        font-size: 0.8rem; color: var(--maroon-800);
    }
    .modal-content {
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft);
        border-radius: 0.75rem; padding: 1rem; font-size: 0.8rem;
        line-height: 1.5; max-height: 16rem; overflow-y: auto;
    }
    .modal-footer { padding: 1rem 1.25rem; border-top: 1px solid var(--border-soft); display: flex; justify-content: flex-end; }

    /* Referral Info */
    .referral-info { font-size: 0.7rem; color: var(--maroon-700); }
    .referral-info i { color: var(--gold-500); margin-right: 0.25rem; }

    /* Counselor Contact Modal */
    .contact-card {
        background: rgba(250,248,245,0.7);
        border: 1px solid var(--border-soft);
        border-radius: 0.75rem;
        padding: 1rem;
    }
    .contact-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(229,224,219,0.7);
    }
    .contact-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .contact-row:first-child {
        padding-top: 0;
    }
    .contact-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(122,42,42,0.08);
        color: var(--maroon-700);
    }
    .contact-label {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
        margin-bottom: 0.2rem;
    }
    .contact-value {
        font-size: 0.82rem;
        color: var(--text-primary);
        word-break: break-word;
    }
    .contact-link {
        color: #1d4ed8;
        text-decoration: none;
        font-weight: 500;
    }
    .contact-link:hover {
        text-decoration: underline;
    }

    /* Responsive */
    @media (max-width: 639px) {
        .hero-icon { width: 2.5rem; height: 2.5rem; }
        .btn-primary, .btn-secondary, .btn-filter { width: 100%; justify-content: center; }
        .quick-filter-bar { overflow-x: auto; flex-wrap: nowrap; padding-bottom: 0.75rem; }
        .quick-filter-bar .btn-filter { white-space: nowrap; }
        .action-buttons-mobile { flex-direction: column; gap: 0.4rem; }
    }
</style>

<div class="min-h-screen appointments-shell">
    <div class="appointments-glow one"></div>
    <div class="appointments-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        {{-- Header Section --}}
        <div class="mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4">
                <div class="hero-card">
                    <div class="p-5 flex items-start gap-4">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-check text-lg"></i>
                        </div>
                        <div>
                            <div class="hero-badge mb-2">
                                <span class="hero-badge-dot"></span>
                                My Appointments
                            </div>
                            <h1 class="text-xl font-semibold tracking-tight text-[#2c2420]">Counseling Sessions</h1>
                            <p class="text-sm text-[#6b5e57] mt-1">
                                Manage and track your counseling appointments
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="h-full flex items-center justify-between gap-3 p-5">
                        <div class="flex items-center gap-3">
                            <div class="summary-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div>
                                <p class="summary-label">Quick Action</p>
                                <p class="summary-value">Book Session</p>
                            </div>
                        </div>
                        <a href="{{ route('appointments.create') }}" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>New Appointment</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}

        {{-- Quick Filter Buttons (Moved to Top After Header) --}}
        <div class="panel-card mb-5">
            <div class="quick-filter-bar">
                <a href="{{ route('appointments.index') }}"
                   class="btn-filter {{ !request('status') && !request('search_date') ? 'active' : '' }}">
                    <i class="fas fa-bars-staggered"></i> All
                </a>
                <a href="{{ route('appointments.index', ['status' => 'pending']) }}"
                   class="btn-filter {{ request('status') == 'pending' ? 'active' : '' }}">
                    <i class="fas fa-hourglass-half"></i> Pending
                </a>
                <a href="{{ route('appointments.index', ['status' => 'approved']) }}"
                   class="btn-filter {{ request('status') == 'approved' ? 'active' : '' }}">
                    <i class="fas fa-circle-check"></i> Approved
                </a>
                <a href="{{ route('appointments.index', ['status' => 'reschedule_requested']) }}"
                   class="btn-filter {{ request('status') == 'reschedule_requested' ? 'active' : '' }}">
                    <i class="fas fa-calendar-pen"></i> Reschedule Req.
                </a>
                <a href="{{ route('appointments.index', ['status' => 'rescheduled']) }}"
                   class="btn-filter {{ request('status') == 'rescheduled' ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Rescheduled
                </a>
                <a href="{{ route('appointments.index', ['status' => 'reschedule_rejected']) }}"
                   class="btn-filter {{ request('status') == 'reschedule_rejected' ? 'active' : '' }}">
                    <i class="fas fa-circle-xmark"></i> Rejected by Me
                </a>
                <a href="{{ route('appointments.index', ['status' => 'completed']) }}"
                   class="btn-filter {{ request('status') == 'completed' ? 'active' : '' }}">
                    <i class="fas fa-circle-dot"></i> Completed
                </a>
                <a href="{{ route('appointments.index', ['status' => 'referred']) }}"
                   class="btn-filter {{ request('status') == 'referred' ? 'active' : '' }}">
                    <i class="fas fa-arrow-right-arrow-left"></i> Referred
                </a>
                <a href="{{ route('appointments.index', ['status' => 'cancelled']) }}"
                   class="btn-filter {{ request('status') == 'cancelled' ? 'active' : '' }}">
                    <i class="fas fa-ban"></i> Cancelled
                </a>
            </div>
        </div>

        {{-- Search and Filters Section --}}
        <div class="panel-card mb-6">
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-sliders"></i></div>
                <div>
                    <h3 class="panel-title">Search & Filter</h3>
                    <p class="panel-subtitle">Find appointments by date or status</p>
                </div>
            </div>

            <div class="p-5">
                <form method="GET" action="{{ route('appointments.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex flex-wrap gap-4 flex-1">
                        <div class="min-w-[160px]">
                            <label class="form-label">Date</label>
                            <input type="date" name="search_date" id="search_date"
                                   value="{{ request('search_date') }}"
                                   class="form-input">
                        </div>
                        <div class="min-w-[180px]">
                            <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="reschedule_requested" {{ request('status') == 'reschedule_requested' ? 'selected' : '' }}>Reschedule Requested</option>
                                <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                <option value="reschedule_rejected" {{ request('status') == 'reschedule_rejected' ? 'selected' : '' }}>Rejected by Student</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="referred" {{ request('status') == 'referred' ? 'selected' : '' }}>Referred</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-search"></i>
                            <span>Search</span>
                        </button>
                        <a href="{{ route('appointments.index') }}" class="btn-secondary">
                            <i class="fas fa-rotate-left"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </form>

                {{-- Active Filters Display --}}
                @if(request()->anyFilled(['search_date', 'status']))
                <div class="mt-4 pt-4 border-t border-[#e5e0db]">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-[#6b5e57]">Active filters:</span>
                            @if(request('search_date'))
                                <span class="filter-chip">
                                    Date: {{ \Carbon\Carbon::parse(request('search_date'))->format('M j, Y') }}
                                    <a href="{{ request()->fullUrlWithQuery(['search_date' => null]) }}"><i class="fas fa-xmark"></i></a>
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
                                    <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}"><i class="fas fa-xmark"></i></a>
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-[#6b5e57]">
                            {{ $appointments->count() }} appointment(s) found
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Appointments Table --}}
        <div class="panel-card overflow-hidden">
            @if($appointments->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-xmark"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Appointments Found</h3>
                    <p class="text-sm text-[#6b5e57] mb-4 max-w-md mx-auto">
                        @if(request()->anyFilled(['search_date', 'status']))
                            Try adjusting your filters to see more results.
                        @else
                            You haven't booked any counseling sessions yet.
                        @endif
                    </p>
                    @if(request()->anyFilled(['search_date', 'status']))
                        <a href="{{ route('appointments.index') }}" class="btn-secondary mb-3">
                            <i class="fas fa-magnifying-glass"></i>
                            <span>Clear Filters</span>
                        </a>
                    @endif
                    <a href="{{ route('appointments.create') }}" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        <span>Book Your First Appointment</span>
                    </a>
                </div>
            @else
                <div class="table-wrapper">
                    <table class="appointments-table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Counselor</th>
                                <th>Concern</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                                @php
                                    $counselorPhone =
                                        data_get($appointment, 'counselor.phone_number')
                                        ?? data_get($appointment, 'counselor.contact_number')
                                        ?? data_get($appointment, 'counselor.mobile_number')
                                        ?? data_get($appointment, 'counselor.user.phone_number')
                                        ?? data_get($appointment, 'counselor.user.contact_number')
                                        ?? data_get($appointment, 'counselor.user.mobile_number')
                                        ?? 'Not available';

                                    $counselorFbLink =
                                        data_get($appointment, 'counselor.facebook_page_link')
                                        ?? data_get($appointment, 'counselor.facebook_link')
                                        ?? data_get($appointment, 'counselor.fb_page_link')
                                        ?? data_get($appointment, 'counselor.fb_link')
                                        ?? data_get($appointment, 'counselor.user.facebook_page_link')
                                        ?? data_get($appointment, 'counselor.user.facebook_link')
                                        ?? data_get($appointment, 'counselor.user.fb_page_link')
                                        ?? data_get($appointment, 'counselor.user.fb_link')
                                        ?? '';
                                @endphp
                                <tr class="cursor-pointer hover:bg-[#faf8f5] transition-colors" onclick="showAppointmentDetails({{ $appointment->id }})">
                                    <td>
                                        <div class="font-semibold text-[#2c2420]">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                        </div>
                                        <div class="text-xs text-[#6b5e57]">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                        </div>
                                        @if($appointment->status === 'reschedule_requested' && $appointment->proposed_date)
                                            <div class="text-xs text-[#9a3412] mt-1">
                                                Proposed: {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j') }}
                                                {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }}
                                            </div>
                                            @if($appointment->reschedule_reason)
                                                <div class="text-xs text-[#9a3412]">
                                                    Reason: {{ Str::limit($appointment->reschedule_reason, 30) }}
                                                </div>
                                            @endif
                                        @elseif($appointment->status === 'referred' && $appointment->proposed_date)
                                            <div class="text-xs text-[#7a2a2a] mt-1">
                                                Proposed: {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j') }}
                                                {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }}
                                            </div>
                                            @if($appointment->referral_reason)
                                                <div class="text-xs text-[#7a2a2a]">
                                                    Reason: {{ Str::limit($appointment->referral_reason, 30) }}
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-semibold text-[#2c2420]">
                                            {{ $appointment->counselor->user->first_name }} {{ $appointment->counselor->user->last_name }}
                                        </div>
                                        <div class="text-xs text-[#6b5e57]">
                                            {{ $appointment->counselor->position }}
                                        </div>
                                        @if($appointment->is_referred && $appointment->original_counselor_id)
                                            <div class="text-xs text-[#7a2a2a] mt-1 referral-info">
                                                <i class="fas fa-arrow-right-arrow-left"></i>
                                                Originally: {{ $appointment->originalCounselor->user->first_name }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-sm text-[#6b5e57] max-w-[180px] truncate" title="{{ $appointment->concern }}">
                                            {{ $appointment->concern }}
                                        </div>
                                    </td>
                                    <td>
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
                                    <td>
                                        <div class="flex flex-wrap gap-1.5 action-buttons-mobile" onclick="event.stopPropagation()">
                                            @if(Auth::user()->role === 'student')
                                                <button type="button"
                                                        class="action-btn info"
                                                        onclick="showCounselorInfo(
                                                            '{{ $appointment->counselor->user->first_name }} {{ $appointment->counselor->user->last_name }}',
                                                            '{{ addslashes($appointment->counselor->position ?? 'Counselor') }}',
                                                            '{{ addslashes($counselorPhone) }}',
                                                            '{{ addslashes($counselorFbLink) }}'
                                                        )">
                                                    <i class="fas fa-id-card"></i> Counselor Info
                                                </button>
                                            @endif

                                            @if($appointment->status === 'reschedule_requested' && Auth::user()->role === 'student')
                                                <form action="{{ route('appointments.reschedule.accept', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="action-btn accept"
                                                            onclick="return confirm('Accept the new appointment time?')">
                                                        <i class="fas fa-circle-check"></i> Accept
                                                    </button>
                                                </form>
                                                <button type="button" class="action-btn reject"
                                                        onclick="openReasonModal('{{ route('appointments.reschedule.reject', $appointment) }}', 'PATCH', 'Reject Reschedule', 'Why are you rejecting this reschedule request?')">
                                                    <i class="fas fa-circle-xmark"></i> Reject
                                                </button>
                                            @elseif(in_array($appointment->status, ['pending', 'approved', 'rescheduled'], true) && Auth::user()->role === 'student')
                                                <button type="button" class="action-btn cancel"
                                                        onclick="openReasonModal('{{ route('appointments.cancel', $appointment) }}', 'POST', 'Cancel Appointment', 'Please tell us why you are cancelling this appointment.')">
                                                    <i class="fas fa-xmark"></i> Cancel
                                                </button>
                                            @elseif($appointment->status === 'referred' && Auth::user()->role === 'student')
                                                @if($appointment->proposed_date && $appointment->proposed_start_time && $appointment->proposed_end_time)
                                                    <form action="{{ route('appointments.referral.accept', $appointment) }}" method="POST" class="inline">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="action-btn accept"
                                                                onclick="return confirm('Accept the referral schedule with the new counselor?')">
                                                            <i class="fas fa-circle-check"></i> Accept
                                                        </button>
                                                    </form>
                                                    <button type="button" class="action-btn reject"
                                                            onclick="openReasonModal('{{ route('appointments.referral.reject', $appointment) }}', 'PATCH', 'Reject Referral', 'Why are you rejecting this referral?')">
                                                        <i class="fas fa-circle-xmark"></i> Reject
                                                    </button>
                                                @else
                                                    <span class="text-[#7a2a2a] text-xs italic">Awaiting details</span>
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

{{-- Appointment Details Modal --}}
<div id="appointmentDetailsModal" class="modal-overlay hidden">
    <div class="modal-container" style="max-width:540px;max-height:90vh;overflow-y:auto;">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-calendar-check mr-1"></i>Appointment Details</h3>
            <button onclick="closeAppointmentDetailsModal()" class="modal-close" aria-label="Close">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body" id="appointmentDetailsBody">
            <div class="text-center py-8 text-[#8b7e76] text-sm">
                <i class="fas fa-spinner fa-spin text-2xl mb-3"></i><br>Loading...
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeAppointmentDetailsModal()" class="btn-secondary px-4 py-2 text-xs">
                <i class="fas fa-xmark"></i> Close
            </button>
        </div>
    </div>
</div>

{{-- Appointment Details JS --}}
<script>
function showAppointmentDetails(id) {
    const body = document.getElementById('appointmentDetailsBody');
    body.innerHTML = '<div class="text-center py-8 text-[#8b7e76] text-sm"><i class="fas fa-spinner fa-spin text-2xl mb-3"></i><br>Loading...</div>';
    document.getElementById('appointmentDetailsModal').classList.remove('hidden');

    fetch(`/appointments/${id}/details`)
        .then(r => { if (!r.ok) throw new Error(); return r.json(); })
        .then(data => {
            const a = data.appointment;
            const c = data.counselor;

            const field = (label, value) => value
                ? `<div><label class="field-label">${label}</label><p class="mt-1 text-xs sm:text-sm text-[#2c2420] whitespace-pre-line">${value}</p></div>`
                : '';

            const statusColors = { pending:'pending', approved:'approved', completed:'approved', referred:'referred', cancelled:'cancelled', rejected:'rejected' };
            const statusChip = `<span class="mt-1 inline-flex px-2 py-1 text-[10px] font-semibold rounded-full status-chip ${statusColors[a.status] || 'pending'}">${a.status_display}</span>`;

            body.innerHTML = `<div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    ${field('Date', data.formatted_date)}
                    ${field('Time', data.formatted_time)}
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    ${field('Type of Booking', a.booking_type)}
                    ${field('Booking Category', a.booking_category ? a.booking_category.charAt(0).toUpperCase() + a.booking_category.slice(1).replace('-',' ') : '')}
                </div>
                ${field('Counselor', c.name + (c.position ? ' — ' + c.position : ''))}
                ${a.referred_by ? field('Source of Referral (Referred)', a.referred_by) : ''}
                ${field('Reason / Concern', a.concern)}
                ${a.mood_rating ? field('Mood at Booking', a.mood_rating) : ''}
                <div><label class="field-label">Status</label><div class="mt-1">${statusChip}</div></div>
                ${a.cancellation_reason ? `
                <div>
                    <label class="field-label">Cancellation Reason</label>
                    <p class="mt-1 text-xs italic" style="color:#b91c1c;background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);border-radius:0.4rem;padding:0.4rem 0.6rem;">${a.cancellation_reason}</p>
                </div>` : ''}
                ${a.reschedule_reason ? `
                <div>
                    <label class="field-label">Reschedule Reason</label>
                    <p class="mt-1 text-xs italic" style="color:#92400e;background:rgba(251,191,36,0.06);border:1px solid rgba(251,191,36,0.3);border-radius:0.4rem;padding:0.4rem 0.6rem;">${a.reschedule_reason}</p>
                </div>` : ''}
            </div>`;
        })
        .catch(() => {
            body.innerHTML = '<div class="text-center py-8 text-red-500 text-sm"><i class="fas fa-exclamation-triangle text-2xl mb-3"></i><br>Failed to load details. Please try again.</div>';
        });
}

function closeAppointmentDetailsModal() {
    document.getElementById('appointmentDetailsModal').classList.add('hidden');
}

document.addEventListener('click', function(e) {
    if (e.target.id === 'appointmentDetailsModal') closeAppointmentDetailsModal();
});
</script>
<div id="referralReasonModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-circle-info"></i>Referral Reason
            </h3>
            <button onclick="closeReferralReasonModal()" class="modal-close" aria-label="Close">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="referralCounselorInfo" class="modal-info">
                <p class="text-sm" id="counselorInfoText"></p>
            </div>
            <div id="referralReasonContent" class="modal-content whitespace-pre-line"></div>
        </div>
        <div class="modal-footer">
            <button onclick="closeReferralReasonModal()" class="btn-secondary px-4 py-2 text-xs">
                <i class="fas fa-xmark"></i>Close
            </button>
        </div>
    </div>
</div>

{{-- Modal for Counselor Contact Info --}}
<div id="counselorInfoModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-id-card"></i>Counselor Information
            </h3>
            <button onclick="closeCounselorInfoModal()" class="modal-close" aria-label="Close">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-info">
                <p class="text-sm">You may use the contact details below for direct communication with your counselor.</p>
            </div>

            <div class="contact-card">
                <div class="contact-row">
                    <div class="contact-icon">
                        <i class="fas fa-circle-user"></i>
                    </div>
                    <div>
                        <div class="contact-label">Counselor</div>
                        <div class="contact-value" id="modalCounselorName">—</div>
                    </div>
                </div>

                <div class="contact-row">
                    <div class="contact-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <div class="contact-label">Position</div>
                        <div class="contact-value" id="modalCounselorPosition">—</div>
                    </div>
                </div>

                <div class="contact-row">
                    <div class="contact-icon">
                        <i class="fas fa-phone-flip"></i>
                    </div>
                    <div>
                        <div class="contact-label">Phone Number</div>
                        <div class="contact-value" id="modalCounselorPhone">Not available</div>
                    </div>
                </div>

                <div class="contact-row">
                    <div class="contact-icon">
                        <i class="fab fa-facebook"></i>
                    </div>
                    <div>
                        <div class="contact-label">Facebook Page</div>
                        <div class="contact-value" id="modalCounselorFbWrapper">
                            <span id="modalCounselorFbText">Not available</span>
                            <a id="modalCounselorFbLink" href="#" target="_blank" rel="noopener noreferrer" class="contact-link hidden">
                                Open Facebook Page
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button onclick="closeCounselorInfoModal()" class="btn-secondary px-4 py-2 text-xs">
                <i class="fas fa-xmark"></i>Close
            </button>
        </div>
    </div>
</div>

<script>
// Function to show referral reason modal
function showReferralReason(reason, originalCounselorName = '', referredCounselorName = '', isDifferentCollege = false) {
    document.getElementById('referralReasonContent').textContent = reason || 'No referral reason provided.';

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

// Function to show counselor info modal
function showCounselorInfo(name = '', position = '', phone = '', fbLink = '') {
    document.getElementById('modalCounselorName').textContent = name || 'Not available';
    document.getElementById('modalCounselorPosition').textContent = position || 'Not available';
    document.getElementById('modalCounselorPhone').textContent = phone || 'Not available';

    const fbText = document.getElementById('modalCounselorFbText');
    const fbAnchor = document.getElementById('modalCounselorFbLink');

    if (fbLink && fbLink.trim() !== '') {
        fbText.classList.add('hidden');
        fbAnchor.classList.remove('hidden');
        fbAnchor.href = fbLink;
    } else {
        fbText.classList.remove('hidden');
        fbText.textContent = 'Not available';
        fbAnchor.classList.add('hidden');
        fbAnchor.href = '#';
    }

    document.getElementById('counselorInfoModal').classList.remove('hidden');
}

// Function to close counselor info modal
function closeCounselorInfoModal() {
    document.getElementById('counselorInfoModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'referralReasonModal') {
        closeReferralReasonModal();
    }
    if (e.target.id === 'counselorInfoModal') {
        closeCounselorInfoModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReferralReasonModal();
        closeCounselorInfoModal();
        closeAppointmentDetailsModal();
    }
});</script>

<!-- Cancellation/Rejection Reason Modal -->
<div id="reasonModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:0.75rem;padding:1.5rem;width:100%;max-width:440px;margin:1rem;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <h3 id="reasonModalTitle" style="margin:0 0 0.25rem;font-size:1rem;font-weight:700;color:#2c2420;"></h3>
        <p id="reasonModalSubtitle" style="margin:0 0 1rem;font-size:0.8rem;color:#6b7280;"></p>
        <form id="reasonModalForm" method="POST">
            @csrf
            <input type="hidden" name="_method" id="reasonModalMethod" value="POST">
            <textarea name="cancellation_reason" id="reasonModalInput" rows="4" required
                      placeholder="Enter your reason here..."
                      style="width:100%;padding:0.65rem 0.75rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.85rem;resize:vertical;outline:none;box-sizing:border-box;"></textarea>
            <p id="reasonModalError" style="display:none;color:#b91c1c;font-size:0.75rem;margin:0.25rem 0 0;"></p>
            <div style="display:flex;gap:0.5rem;justify-content:flex-end;margin-top:1rem;">
                <button type="button" onclick="closeReasonModal()"
                        style="padding:0.5rem 1rem;border:1px solid #d1d5db;border-radius:0.5rem;background:#fff;font-size:0.82rem;cursor:pointer;color:#374151;">
                    Cancel
                </button>
                <button type="submit"
                        style="padding:0.5rem 1rem;border:none;border-radius:0.5rem;background:#7a2a2a;color:#fff;font-size:0.82rem;font-weight:600;cursor:pointer;">
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openReasonModal(action, method, title, subtitle) {
    document.getElementById('reasonModalTitle').textContent = title;
    document.getElementById('reasonModalSubtitle').textContent = subtitle;
    document.getElementById('reasonModalForm').action = action;
    document.getElementById('reasonModalMethod').value = method;
    document.getElementById('reasonModalInput').value = '';
    document.getElementById('reasonModalError').style.display = 'none';
    document.getElementById('reasonModal').style.display = 'flex';
}
function closeReasonModal() {
    document.getElementById('reasonModal').style.display = 'none';
}
document.getElementById('reasonModalForm').addEventListener('submit', function(e) {
    const val = document.getElementById('reasonModalInput').value.trim();
    if (!val) {
        e.preventDefault();
        const err = document.getElementById('reasonModalError');
        err.textContent = 'Please provide a reason before submitting.';
        err.style.display = 'block';
    }
});
document.getElementById('reasonModal').addEventListener('click', function(e) {
    if (e.target === this) closeReasonModal();
});
</script>
@endsection
