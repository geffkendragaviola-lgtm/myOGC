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

    .dash-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .dash-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .dash-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .dash-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .stat-card, .appt-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .stat-card:hover, .appt-card:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before, .stat-card::before, .appt-card::before {
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

    .welcome-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.2);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%);
        color: white; box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .welcome-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }
    .welcome-title { font-size: 1.25rem; font-weight: 700; line-height: 1.2; }
    .welcome-college {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.5rem; border-radius: 999px;
        font-size: 0.7rem; font-weight: 600;
        background: rgba(255,255,255,0.15); color: white;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .welcome-meta { font-size: 0.7rem; color: rgba(255,255,255,0.85); }
    .welcome-date { font-size: 0.65rem; color: rgba(255,255,255,0.7); }
    .welcome-date-value { font-size: 0.9rem; font-weight: 600; color: white; }

    .stat-card {
        display: block; text-decoration: none;
    }
    .stat-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; flex-shrink: 0;
        background: var(--maroon-700); color: #fef9e7;
    }
    .stat-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; color: var(--text-secondary); }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); margin-top: 0.15rem; }

    .appt-card {
        border-left: 3px solid var(--maroon-700);
        padding: 0.75rem; border-radius: 0 0.5rem 0.5rem 0;
    }
    .appt-card.pending { border-left-color: #d97706; background: rgba(255,244,229,0.6); }
    .appt-card.approved { border-left-color: #059669; background: rgba(240,253,244,0.6); }
    .appt-student { font-size: 0.75rem; font-weight: 600; color: var(--text-primary); }
    .appt-id { font-size: 0.65rem; color: var(--text-secondary); font-family: ui-monospace, monospace; }
    .appt-time { font-size: 0.7rem; color: var(--text-primary); font-weight: 500; }
    .appt-concern { font-size: 0.65rem; color: var(--text-muted); margin-top: 0.25rem; }
    .appt-status {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.15rem 0.4rem; border-radius: 999px;
        font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .appt-status.pending { background: rgba(254,249,231,0.9); color: #7a2a2a; }
    .appt-status.approved { background: rgba(240,253,244,0.9); color: #065f46; }

    .quick-action {
        display: block; border-radius: 0.75rem; padding: 1rem;
        text-align: center; text-decoration: none;
        transition: all 0.2s ease;
    }
    .quick-action.maroon {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .quick-action.maroon:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .quick-action.gold {
        color: #fef9e7; background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        box-shadow: 0 4px 10px rgba(180,83,9,0.15);
    }
    .quick-action.gold:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(180,83,9,0.2); }
    .quick-action i { font-size: 1.5rem; margin-bottom: 0.5rem; display: block; }
    .quick-action p { font-size: 0.75rem; font-weight: 600; }

    .upcoming-card {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.75rem; border-radius: 0.5rem;
        border-left: 3px solid var(--maroon-700);
        background: rgba(250,248,245,0.9);
    }
    .upcoming-date {
        text-align: center; padding: 0.5rem; border-radius: 0.4rem;
        background: white; border: 1px solid var(--border-soft);
        min-width: 3.5rem;
    }
    .upcoming-date-day { font-size: 0.7rem; font-weight: 700; color: var(--maroon-700); }
    .upcoming-date-time { font-size: 0.6rem; color: var(--text-secondary); }
    .upcoming-student { font-size: 0.75rem; font-weight: 600; color: var(--text-primary); }
    .upcoming-college { font-size: 0.6rem; color: var(--text-muted); }

    .empty-state {
        text-align: center; padding: 2rem 1rem; color: var(--text-muted);
    }
    .empty-state-icon {
        width: 3rem; height: 3rem; border-radius: 0.75rem;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
        margin-bottom: 0.75rem; font-size: 1rem;
    }

    @media (max-width: 639px) {
        .welcome-card .flex { flex-direction: column; align-items: flex-start !important; }
        .welcome-date { text-align: left !important; margin-top: 0.5rem; }
        .stat-card { text-align: center; }
        .stat-card .flex { flex-direction: column; align-items: center !important; gap: 0.35rem !important; }
        .stat-icon { margin: 0 auto; }
        .stat-value { font-size: 1.75rem; }
        .quick-action { padding: 0.75rem; }
        .quick-action i { font-size: 1.25rem; }
        .quick-action p { font-size: 0.7rem; }
        .upcoming-card { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        .upcoming-date { width: 100%; display: flex; justify-content: space-between; padding: 0.4rem 0.75rem; }
        .appt-card { padding: 0.6rem; }
        .appt-student { font-size: 0.7rem; }
        .panel-header { padding: 0.75rem 1rem; }
    }
</style>

<div class="min-h-screen dash-shell">
    <div class="dash-glow one"></div>
    <div class="dash-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Welcome Section -->
        <div class="mb-6 sm:mb-8">
            <div class="welcome-card p-5 sm:p-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                    <div>
                        <h1 class="welcome-title mb-2">
                            Welcome, {{ $counselor->user->first_name }}!
                        </h1>

                        <!-- Colleges -->
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            @foreach($allColleges as $college)
                                <span class="welcome-college">
                                    <i class="fas fa-university text-[10px]"></i>{{ $college->name }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Position & Credentials -->
                        <p class="welcome-meta">
                            <i class="fas fa-user-tie mr-1.5 text-[10px]"></i>{{ $counselor->position }} • {{ $counselor->credentials }}
                        </p>
                    </div>

                    <!-- Date -->
                    <div class="welcome-date text-left md:text-right">
                        <p class="welcome-date">Today is</p>
                        <p class="welcome-date-value">
                            {{ now()->format('l, F j, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <div class="stat-card">
                <div class="relative p-4 flex items-center gap-3">
                    <div class="stat-icon">
                        <i class="fas fa-clock text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="stat-label">Pending</p>
                        <p class="stat-value">{{ $appointmentStats['pending'] }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="relative p-4 flex items-center gap-3">
                    <div class="stat-icon" style="background: #059669;">
                        <i class="fas fa-check-circle text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="stat-label">Approved</p>
                        <p class="stat-value">{{ $appointmentStats['approved'] }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="relative p-4 flex items-center gap-3">
                    <div class="stat-icon" style="background: var(--maroon-800);">
                        <i class="fas fa-calendar-alt text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="stat-label">Total</p>
                        <p class="stat-value">{{ $appointmentStats['total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Appointments and Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            <!-- Left: Today's Appointments -->
            <div class="panel-card">
                <div class="panel-topline"></div>
                <div class="panel-header" style="background: rgba(254,249,231,0.4);">
                    <div class="panel-icon"><i class="fas fa-calendar-day text-[9px] sm:text-xs"></i></div>
                    <div>
                        <h2 class="panel-title">Today's Appointments</h2>
                    </div>
                </div>
                <div class="p-4 sm:p-5">
                    @if($todayAppointments->isEmpty())
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <p class="text-xs sm:text-sm">No appointments scheduled for today.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($todayAppointments as $appointment)
                                <div class="appt-card {{ $appointment->status }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="appt-student truncate">
                                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                            </h3>
                                            <p class="appt-id">
                                                <i class="fas fa-id-card mr-1 text-[9px]"></i>{{ $appointment->student->student_id }}
                                            </p>
                                        </div>
                                        <span class="appt-status {{ $appointment->status }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <p class="appt-time">
                                        <i class="fas fa-clock text-[#7a2a2a] mr-1.5 text-[9px]"></i>
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                    </p>
                                    <p class="appt-concern truncate">
                                        <i class="fas fa-comment text-[#a89f97] mr-1.5 text-[9px]"></i>
                                        {{ $appointment->concern }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Quick Actions + Upcoming -->
            <div class="space-y-6 sm:space-y-8">
                <!-- Quick Actions -->
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="panel-header" style="background: rgba(254,249,231,0.4);">
                        <div class="panel-icon"><i class="fas fa-bolt text-[9px] sm:text-xs"></i></div>
                        <div>
                            <h2 class="panel-title">Quick Actions</h2>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <a href="{{ route('counselor.calendar') }}"
                               class="quick-action maroon">
                                <i class="fas fa-calendar-alt"></i>
                                <p>View Calendar</p>
                            </a>
                            <a href="{{ route('counselor.appointments') }}?status=pending"
                               class="quick-action gold">
                                <i class="fas fa-clock"></i>
                                <p>Pending Requests</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Appointments -->
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="panel-header" style="background: rgba(254,249,231,0.4);">
                        <div class="panel-icon"><i class="fas fa-calendar-plus text-[9px] sm:text-xs"></i></div>
                        <div>
                            <h2 class="panel-title">Upcoming Appointments</h2>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        @if($upcomingAppointments->isEmpty())
                            <div class="empty-state py-3">
                                <p class="text-xs">No upcoming appointments.</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($upcomingAppointments as $appointment)
                                    <div class="upcoming-card">
                                        <div class="flex items-center gap-3 sm:gap-4">
                                            <div class="upcoming-date">
                                                <p class="upcoming-date-day">
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j') }}
                                                </p>
                                                <p class="upcoming-date-time">
                                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="upcoming-student truncate max-w-[120px] sm:max-w-[160px]">
                                                    {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                                </p>
                                                <p class="upcoming-college">
                                                    <i class="fas fa-university mr-1 text-[9px]"></i>{{ $appointment->student->college->name ?? 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="appt-status {{ $appointment->status }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection