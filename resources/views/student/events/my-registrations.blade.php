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
        --student-success: #d1fae5;
        --student-warning: #fef3c7;
        --student-error: #fee2e2;
        --status-active: #7a2a2a;
        --status-cancelled: #b45309;
        --status-attended: #065f46;
    }

    .ogc-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .ogc-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2;
    }
    .ogc-glow.one { top: -40px; left: -50px; width: 240px; height: 240px; background: var(--gold-400); }
    .ogc-glow.two { bottom: -50px; right: -70px; width: 280px; height: 280px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .stat-card, .event-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .stat-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .event-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(92,26,26,0.08); }
    .hero-card::before, .panel-card::before, .glass-card::before, .stat-card::before, .event-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 35%);
    }

    .hero-icon, .panel-icon, .stat-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-card { min-height: 100px; }
    .stat-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; flex-shrink: 0;
        font-size: 1rem;
    }
    .stat-icon.total { background: rgba(122,42,42,0.1); color: var(--maroon-700); }
    .stat-icon.active { background: rgba(212,175,55,0.15); color: var(--gold-500); }
    .stat-icon.attended { background: rgba(16,185,129,0.1); color: #10b981; }
    .stat-icon.cancelled { background: rgba(249,115,22,0.1); color: #f97316; }

    .stat-card {
        width: 100%;
        text-align: left;
        cursor: pointer;
        border: 1px solid var(--border-soft);
        appearance: none;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(44,36,32,0.08);
    }

    .stat-card:focus-visible {
        outline: 2px solid var(--gold-400);
        outline-offset: 2px;
    }

    .stat-card-active {
        border-color: rgba(122,42,42,0.25);
        background: linear-gradient(135deg, rgba(254,249,231,0.95) 0%, rgba(255,255,255,1) 100%);
        box-shadow: 0 8px 22px rgba(92,26,26,0.10);
    }

    .stat-card-active::after {
        content: "";
        position: absolute;
        inset-inline: 0;
        bottom: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 100%);
    }

    .stat-card-active .stat-icon.total {
        background: rgba(122,42,42,0.18);
        color: var(--maroon-800);
    }

    .stat-card-active .stat-icon.active {
        background: rgba(212,175,55,0.22);
        color: var(--gold-500);
    }

    .stat-card-active .stat-icon.attended {
        background: rgba(16,185,129,0.18);
        color: #059669;
    }

    .stat-card-active .stat-icon.cancelled {
        background: rgba(249,115,22,0.18);
        color: #ea580c;
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
        min-width: 280px;
    }

    @media (min-width: 1024px) {
        .summary-card {
    width: 500px;
            min-width: 500px;
        }
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem;
        background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);
        display: flex; align-items: center; justify-content: center; color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .btn-primary {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        font-size: 0.8rem; padding: 0.55rem 1rem; gap: 0.4rem;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .primary-btn, .btn-primary, .secondary-btn, .action-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        font-size: 0.8rem; padding: 0.55rem 1rem; gap: 0.4rem;
    }
    .primary-btn, .btn-primary {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover, .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        color: var(--text-primary); background: rgba(255,255,255,0.95);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }
    .action-btn.reregister {
        background: linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%);
        color: white; border: none;
    }
    .action-btn.reregister:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(122,42,42,0.2); }
    .action-btn.details {
        background: rgba(254,249,231,0.9); color: var(--maroon-700); border: 1px solid rgba(212,175,55,0.3);
    }
    .action-btn.details:hover { background: rgba(254,243,199,0.95); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    /* Event Card Specific Styles */
    .event-card-new { transition: all 0.22s ease; background: rgba(255, 255, 255, 0.95); border: 1px solid var(--border-soft); border-radius: 0.75rem; overflow: hidden; position: relative; }
    .event-card-new:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(44,36,32,0.06); }
    .event-card-new::before { content: ""; position: absolute; inset: 0; pointer-events: none; background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%); }

    .event-banner {
        position: relative; overflow: hidden; color: white;
        height: 11rem;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%);
    }
    .event-banner img {
        position: absolute; inset: 0; width: 100%; height: 100%; object-fit: contain;
        background-color: rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
    }
    .event-card-new:hover .event-banner img { transform: scale(1.04); }
    .event-banner-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(44,20,20,0.82) 0%, rgba(44,20,20,0.25) 60%, transparent 100%);
    }
    .event-banner-content {
        position: absolute; bottom: 0; left: 0; right: 0; padding: 0.65rem 0.85rem;
    }
    .event-banner-top {
        position: absolute; top: 0.5rem; right: 0.5rem;
    }
    .event-type-pill {
        display: inline-flex; align-items: center; border-radius: 999px; background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(6px);
        font-size: 9px; padding: 0.2rem 0.5rem; font-weight: 600; text-transform: capitalize; color: white;
    }
    .status-badge { font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 9999px; font-weight: 700; backdrop-filter: blur(4px); }
    .status-active { background-color: rgba(209, 250, 229, 0.95); color: #059669; }
    .status-inactive { background-color: rgba(254, 226, 226, 0.95); color: #b91c1c; }
    .status-attended { background-color: rgba(16, 185, 129, 0.95); color: white; }
    .status-cancelled { background-color: rgba(249, 115, 22, 0.95); color: white; }
    .status-registered { background-color: rgba(209, 250, 229, 0.95); color: #059669; }

    .event-meta-row { display: flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); font-size: 0.8rem; }
    .event-meta-icon { width: 1.5rem; height: 1.5rem; border-radius: 0.45rem; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }

    .action-btn-soft {
        display: inline-flex; align-items: center; justify-content: center; border-radius: 0.6rem;
        padding: 0.45rem 0.55rem; font-size: 0.7rem; font-weight: 600; transition: all 0.15s ease;
    }
    .action-btn-soft:hover { transform: translateY(-1px); }

    .event-badge.completed {
        position: absolute; top: 0.5rem; left: 0.5rem;
        background: rgba(16,185,129,0.95); color: white;
        padding: 0.2rem 0.6rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 700; backdrop-filter: blur(4px);
    }
    .event-badge.cancelled-overlay {
        position: absolute; inset: 0;
        background: rgba(249,115,22,0.15);
        display: flex; align-items: center; justify-content: center;
    }
    .event-badge.cancelled-overlay span {
        background: rgba(234,88,12,0.95); color: white;
        padding: 0.4rem 1rem; border-radius: 0.5rem;
        font-size: 0.75rem; font-weight: 700; transform: rotate(-8deg);
        display: flex; align-items: center; gap: 0.3rem;
    }

    .event-detail {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.75rem; color: var(--text-secondary);
    }
    .event-detail i { color: var(--maroon-700); width: 1rem; text-align: center; }

    .empty-state, .profile-required {
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
    .profile-required {
        background: rgba(254,243,199,0.9); border-color: rgba(245,158,11,0.3);
    }
    .profile-required .empty-state-icon {
        background: rgba(251,191,36,0.15); color: #b45309;
        border-color: #f59e0b;
    }

    .re-register-box {
        background: rgba(254,243,199,0.9); border: 1px solid rgba(245,158,11,0.3);
        border-radius: 0.6rem; padding: 0.75rem; margin-bottom: 1rem;
    }
    .re-register-box.unavailable {
        background: rgba(248,250,252,0.9); border-color: var(--border-soft);
    }
    .re-register-box .info-title {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.75rem; font-weight: 600; color: var(--maroon-700);
        margin-bottom: 0.4rem;
    }
    .re-register-box .info-title.unavailable { color: var(--text-secondary); }
    .re-register-box .info-desc {
        font-size: 0.7rem; color: var(--text-secondary);
    }

    .expand-details {
        border-top: 1px dashed var(--border-soft);
        padding-top: 1rem; margin-top: 1rem;
    }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--maroon-700); font-size: 0.75rem; font-weight: 600;
        transition: all 0.18s ease;
    }
    .back-link:hover { color: var(--maroon-900); transform: translateX(-2px); }

    /* Total Count in Header */
    .header-total {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, rgba(122,42,42,0.08) 0%, rgba(212,175,55,0.08) 100%);
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(212,175,55,0.2);
    }
    .header-total-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    .header-total-info {
        text-align: right;
    }
    .header-total-label {
        font-size: 0.7rem;
        color: var(--text-secondary);
        letter-spacing: 0.05em;
    }
    .header-total-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--maroon-700);
        line-height: 1.2;
    }

    /* Section Headers */
    .section-header {
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--gold-400);
        display: inline-block;
    }
    .section-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-header h2 i {
        color: var(--maroon-700);
        font-size: 1rem;
    }

    /* Responsive Utilities */
    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .primary-btn, .secondary-btn, .action-btn { width: 100%; justify-content: center; padding: 0.7rem; font-size: 0.75rem; }
        .btn-row-mobile { flex-direction: column; gap: 0.5rem !important; }
        .hero-card, .summary-card, .stat-card { padding: 1rem !important; }
        .hero-icon { width: 2.25rem; height: 2.25rem; }
        .stat-icon { width: 2.25rem; height: 2.25rem; font-size: 0.9rem; }
        .event-image { height: 8rem; }
        .event-content { padding: 0.6rem 0.8rem; }
        .event-badge { font-size: 0.6rem; padding: 0.12rem 0.4rem; }
        .event-badge.completed, .event-badge.cancelled-overlay span { font-size: 0.65rem; padding: 0.15rem 0.5rem; }
        .event-detail { font-size: 0.7rem; }
        .stat-grid-mobile { grid-template-columns: 1fr 1fr 1fr !important; gap: 0.5rem !important; }
        .header-total {
            padding: 0.35rem 0.75rem;
        }
        .header-total-icon {
            width: 1.75rem;
            height: 1.75rem;
        }
        .header-total-number {
            font-size: 1.25rem;
        }
    }
</style>

<div class="min-h-screen ogc-shell">
    <div class="ogc-glow one"></div>
    <div class="ogc-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Page Header with Total on the Right -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card h-full">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-check text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                My Registrations
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">My Event Registrations</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                View and manage your mental health event registrations.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card h-full">
                    <div class="relative h-full flex items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3">
                            <div class="summary-icon">
                                <i class="fas fa-chart-line text-sm"></i>
                            </div>
                            <div>
                                <p class="summary-label">Total Registrations</p>
                                <p class="summary-value">{{ $student ? $registrations->count() : '—' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('student.events.available') }}" class="btn-primary">
                            <i class="fas fa-calendar-days"></i>
                            <span>Browse Events</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(!$student)
            <!-- Student Profile Required -->
            <div class="glass-card profile-required mb-6">
                <div class="empty-state-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#92400e] mb-2">Complete Your Student Profile</h3>
                <p class="text-[#b45309] text-sm mb-4 max-w-md mx-auto">
                    You need to complete your student profile before you can register for mental health events.
                </p>
                <a href="{{ route('profile.edit') }}" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                    <i class="fas fa-user-edit mr-1.5 text-[9px] sm:text-xs"></i>
                    <span>Complete Profile</span>
                </a>
            </div>
        @else
            <!-- Statistics Tabs -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 stat-grid-mobile">
                <!-- Total -->
                <button id="total-card" class="stat-card stat-card-active p-3 sm:p-4 focus:outline-none">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon total">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider mb-0.5">All</p>
                            <h3 class="text-base sm:text-lg font-bold text-[#2c2420]">{{ $registrations->count() }}</h3>
                        </div>
                    </div>
                </button>

                <!-- Active -->
                <button id="active-card" class="stat-card p-3 sm:p-4 focus:outline-none">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon active">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider mb-0.5">Active</p>
                            <h3 class="text-base sm:text-lg font-bold text-[#2c2420]">{{ $registrations->where('status', 'registered')->count() }}</h3>
                        </div>
                    </div>
                </button>

                <!-- Attended -->
                <button id="attended-card" class="stat-card p-3 sm:p-4 focus:outline-none">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon attended">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider mb-0.5">Attended</p>
                            <h3 class="text-base sm:text-lg font-bold text-[#2c2420]">{{ $registrations->where('status', 'attended')->count() }}</h3>
                        </div>
                    </div>
                </button>

                <!-- Cancelled -->
                <button id="cancelled-card" class="stat-card p-3 sm:p-4 focus:outline-none">
                    <div class="flex items-center gap-3">
                        <div class="stat-icon cancelled">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider mb-0.5">Cancelled</p>
                            <h3 class="text-base sm:text-lg font-bold text-[#2c2420]">{{ $registrations->where('status', 'cancelled')->count() }}</h3>
                        </div>
                    </div>
                </button>
            </div>

            <!-- Active Registrations Section -->
            <div id="active-section" class="tab-content">
                <div class="section-header">
                    <h2><i class="fas fa-calendar-check"></i> Active Registrations</h2>
                </div>
                
                @if($registrations->where('status', 'registered')->isEmpty())
                    <div class="glass-card empty-state mb-6">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Active Registrations</h3>
                        <p class="text-[#6b5e57] text-sm mb-4 max-w-md mx-auto">
                            You don't have any upcoming event registrations yet.
                        </p>
                        <a href="{{ route('student.events.available') }}" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                            <i class="fas fa-search mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Browse Available Events</span>
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                        @foreach($registrations->where('status', 'registered') as $registration)
                            @include('partials.event-registration-card', ['registration' => $registration])
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Cancelled Registrations Section -->
            <div id="cancelled-section" class="tab-content hidden mt-8">
                <div class="section-header">
                    <h2><i class="fas fa-ban"></i> Cancelled Registrations</h2>
                </div>
                
                @if($registrations->where('status', 'cancelled')->isEmpty())
                    <div class="glass-card empty-state mb-6">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Cancellation History</h3>
                        <p class="text-[#6b5e57] text-sm mb-4 max-w-md mx-auto">
                            You haven't cancelled any event registrations. Great job staying committed!
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                        @foreach($registrations->where('status', 'cancelled') as $registration)
                            <div class="event-card-new flex flex-col h-full" style="border-top: 3px solid #f97316; opacity: 0.95;">
                                <!-- Event Header -->
                                <div class="event-banner flex-shrink-0">
                                    @if($registration->event->image_url)
                                        <img src="{{ $registration->event->image_url }}" alt="{{ $registration->event->title }}"
                                             onerror="this.style.display='none'">
                                    @endif
                                    <div class="event-banner-overlay"></div>

                                    <!-- Status badge top-right -->
                                    <div class="event-banner-top">
                                        <span class="status-badge status-cancelled">
                                            Cancelled
                                        </span>
                                    </div>

                                    <!-- Title + type bottom -->
                                    <div class="event-banner-content">
                                        <div class="flex flex-wrap gap-1 mb-1.5">
                                            <span class="event-type-pill">{{ $registration->event->type }}</span>
                                        </div>
                                        <h3 class="text-sm sm:text-base font-semibold leading-tight line-clamp-2">{{ $registration->event->title }}</h3>
                                        <p class="text-[10px] sm:text-xs text-white/80 mt-0.5 truncate">
                                            @if($registration->event->for_all_colleges)
                                                <i class="fas fa-globe mr-1"></i> All Colleges
                                            @else
                                                <i class="fas fa-building-columns mr-1"></i> {{ $registration->event->colleges->count() }} college(s)
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Cancelled Overlay -->
                                    <div class="event-badge cancelled-overlay">
                                        <span><i class="fas fa-circle-xmark text-[9px]"></i> Cancelled</span>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-3.5 flex-1 flex flex-col">
                                    <div class="space-y-2 mb-3">
                                        <div class="event-meta-row">
                                            <div class="event-meta-icon bg-[#fef9e7] text-[10px] sm:text-xs text-[#c9a227]"><i class="fas fa-calendar-days"></i></div>
                                            <span class="text-xs sm:text-sm font-medium">{{ $registration->event->date_range }}</span>
                                        </div>
                                        <div class="event-meta-row">
                                            <div class="event-meta-icon bg-[#eff6ff] text-[10px] sm:text-xs text-sky-600"><i class="fas fa-clock"></i></div>
                                            <span class="text-[11px] sm:text-xs">{{ $registration->event->time_range }}</span>
                                        </div>
                                        <div class="event-meta-row">
                                            <div class="event-meta-icon bg-[#fdf2f2] text-[10px] sm:text-xs text-[#b91c1c]"><i class="fas fa-location-dot"></i></div>
                                            <span class="text-[11px] sm:text-xs truncate" title="{{ $registration->event->location }}">{{ $registration->event->location }}</span>
                                        </div>
                                        <div class="event-meta-row">
                                            <div class="event-meta-icon bg-[#fff7ed] text-[10px] sm:text-xs text-[#ea580c]"><i class="fas fa-calendar-xmark"></i></div>
                                            <span class="text-[11px] sm:text-xs">Cancelled: {{ $registration->cancelled_at ? $registration->cancelled_at->format('M j, Y') : 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <p class="text-[11px] sm:text-xs text-[#6b5e57] line-clamp-2 leading-relaxed mb-3 flex-1">
                                        {{ Str::limit($registration->event->description, 120) }}
                                    </p>

                                    <!-- Re-registration Option -->
                                    @if($registration->event->is_registration_open && $registration->event->hasAvailableSlots())
                                        <div class="re-register-box mb-3">
                                            <div class="info-title">
                                                <i class="fas fa-redo-alt text-[9px]"></i>
                                                <span>Re-registration Available</span>
                                            </div>
                                            <p class="info-desc mb-2 text-[10px]">
                                                This event still has available slots. You can register again if you'd like to attend.
                                            </p>
                                            <form action="{{ route('student.events.re-register', $registration->event) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="action-btn-soft bg-[#7a2a2a] text-white hover:bg-[#5c1a1a] border-none w-full">
                                                    <i class="fas fa-redo-alt mr-1.5"></i> Re-register
                                                </button>
                                            </form>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="mt-auto pt-3 border-t border-[#e5e0db] flex justify-between gap-2">
                                        <button onclick="toggleDetails('cancelled-details-{{ $registration->id }}')" class="action-btn-soft bg-[#faf8f5] text-[#6b5e57] border border-[#e5e0db] w-full">
                                            <i class="fas fa-circle-info mr-1.5"></i> Details
                                        </button>
                                    </div>

                                    <!-- Expandable Details -->
                                    <div id="cancelled-details-{{ $registration->id }}" class="hidden mt-3 pt-3 border-t border-dashed border-[#e5e0db]">
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-[11px] font-semibold text-[#6b5e57] uppercase tracking-wider mb-1">Description</p>
                                                <p class="text-[11px] sm:text-xs text-[#2c2420] leading-relaxed">{{ $registration->event->description }}</p>
                                            </div>
                                            <div class="space-y-1 text-[11px] sm:text-xs">
                                                <div class="flex justify-between">
                                                    <span class="text-[#8b7e76]">Originally Registered:</span>
                                                    <span class="text-[#2c2420] font-medium">{{ $registration->registered_at->format('M j, Y g:i A') }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-[#8b7e76]">Cancelled On:</span>
                                                    <span class="text-[#b91c1c] font-medium">{{ $registration->cancelled_at ? $registration->cancelled_at->format('M j, Y g:i A') : 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Attended Events Section -->
            <div id="attended-section" class="tab-content hidden mt-8">
                <div class="section-header">
                    <h2><i class="fas fa-clipboard-check"></i> Attended Events</h2>
                </div>
                
                @if($registrations->where('status', 'attended')->isEmpty())
                    <div class="glass-card empty-state mb-6">
                        <div class="empty-state-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Attended Events Yet</h3>
                        <p class="text-[#6b5e57] text-sm mb-4 max-w-md mx-auto">
                            You haven't attended any events yet. Your wellness journey starts with a single step!
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                        @foreach($registrations->where('status', 'attended') as $registration)
                            <div class="event-card-new flex flex-col h-full" style="border-top: 3px solid #10b981;">
                                <!-- Event Header -->
                                <div class="event-banner flex-shrink-0">
                                    @if($registration->event->image_url)
                                        <img src="{{ $registration->event->image_url }}" alt="{{ $registration->event->title }}"
                                             onerror="this.style.display='none'">
                                    @endif
                                    <div class="event-banner-overlay"></div>

                                    <!-- Status badge top-right -->
                                    <div class="event-banner-top">
                                        <span class="status-badge status-attended">
                                            Attended
                                        </span>
                                    </div>

                                    <!-- Title + type bottom -->
                                    <div class="event-banner-content">
                                        <div class="flex flex-wrap gap-1 mb-1.5">
                                            <span class="event-type-pill">{{ $registration->event->type }}</span>
                                        </div>
                                        <h3 class="text-sm sm:text-base font-semibold leading-tight line-clamp-2">{{ $registration->event->title }}</h3>
                                        <p class="text-[10px] sm:text-xs text-white/80 mt-0.5 truncate">
                                            @if($registration->event->for_all_colleges)
                                                <i class="fas fa-globe mr-1"></i> All Colleges
                                            @else
                                                <i class="fas fa-building-columns mr-1"></i> {{ $registration->event->colleges->count() }} college(s)
                                            @endif
                                        </p>
                                    </div>

                                    <!-- Completed Badge -->
                                    <span class="event-badge completed">
                                        <i class="fas fa-circle-check text-[8px]"></i> Completed
                                    </span>
                                </div>

                                <!-- Card Body -->
                                <div class="p-3.5 flex-1 flex flex-col">
                                    <div class="space-y-2 mb-3">
                                        <div class="event-meta-row">
                                            <div class="event-meta-icon bg-[#fef9e7] text-[10px] sm:text-xs text-[#c9a227]"><i class="fas fa-calendar-days"></i></div>
                                            <span class="text-xs sm:text-sm font-medium">{{ $registration->event->date_range }}</span>
                                        </div>
                                        <div class="event-meta-row">
                                            <div class="event-meta-icon bg-[#eff6ff] text-[10px] sm:text-xs text-sky-600"><i class="fas fa-clock"></i></div>
                                            <span class="text-[11px] sm:text-xs">{{ $registration->event->time_range }}</span>
                                        </div>
                                        <div class="event-meta-row">
                                            <div class="event-meta-icon bg-[#fdf2f2] text-[10px] sm:text-xs text-[#b91c1c]"><i class="fas fa-location-dot"></i></div>
                                            <span class="text-[11px] sm:text-xs truncate" title="{{ $registration->event->location }}">{{ $registration->event->location }}</span>
                                        </div>
                                        <div class="event-meta-row">
                                            <div class="event-meta-icon bg-[#f0fdf4] text-[10px] sm:text-xs text-[#16a34a]"><i class="fas fa-circle-check"></i></div>
                                            <span class="text-[11px] sm:text-xs">Attended: {{ $registration->updated_at->format('M j, Y') }}</span>
                                        </div>
                                    </div>

                                    <p class="text-[11px] sm:text-xs text-[#6b5e57] line-clamp-2 leading-relaxed mb-3 flex-1">
                                        {{ Str::limit($registration->event->description, 120) }}
                                    </p>

                                    <!-- Action Buttons -->
                                    <div class="mt-auto pt-3 border-t border-[#e5e0db] flex justify-between gap-2">
                                        <button onclick="toggleDetails('attended-details-{{ $registration->id }}')" class="action-btn-soft bg-[#faf8f5] text-[#6b5e57] border border-[#e5e0db] w-full">
                                            <i class="fas fa-circle-info mr-1.5"></i> Details
                                        </button>
                                    </div>

                                    <!-- Expandable Details -->
                                    <div id="attended-details-{{ $registration->id }}" class="hidden mt-3 pt-3 border-t border-dashed border-[#e5e0db]">
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-[11px] font-semibold text-[#6b5e57] uppercase tracking-wider mb-1">Description</p>
                                                <p class="text-[11px] sm:text-xs text-[#2c2420] leading-relaxed">{{ $registration->event->description }}</p>
                                            </div>
                                            <div class="flex justify-between text-[11px] sm:text-xs">
                                                <span class="text-[#8b7e76]">Attended On:</span>
                                                <span class="text-[#10b981] font-medium">{{ $registration->updated_at->format('M j, Y g:i A') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
    function toggleDetails(id) {
        const element = document.getElementById(id);
        element.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const statCards = document.querySelectorAll('.stat-card');
        const sections = {
            'total-card': ['active-section', 'cancelled-section', 'attended-section'],
            'active-card': ['active-section'],
            'cancelled-card': ['cancelled-section'],
            'attended-card': ['attended-section']
        };

        function clearStatCards() {
            statCards.forEach(card => card.classList.remove('stat-card-active'));
        }

        function showSections(sectionIds) {
            // Hide all sections first
            document.querySelectorAll('.tab-content').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Show selected sections
            sectionIds.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) section.classList.remove('hidden');
            });
        }

        function activateCard(cardId) {
            clearStatCards();
            const card = document.getElementById(cardId);
            if (card) {
                card.classList.add('stat-card-active');
                
                // Show corresponding sections
                if (sections[cardId]) {
                    showSections(sections[cardId]);
                }
            }
        }

        // Add click handlers to stat cards
        statCards.forEach(card => {
            card.addEventListener('click', function() {
                activateCard(this.id);
            });
        });
    });
</script>
@endsection