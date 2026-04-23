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

    .cal-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .cal-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .cal-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .cal-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .slot-card, .legend-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .slot-card:hover, .legend-card:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before, .slot-card::before, .legend-card::before {
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

    .date-nav {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 0.75rem;
    }
    .date-nav-btn {
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        display: flex; align-items: center; justify-content: center;
        color: var(--text-secondary); transition: all 0.18s ease;
        font-size: 0.75rem;
    }
    .date-nav-btn:hover { background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .date-title { font-size: 0.9rem; font-weight: 700; color: var(--text-primary); }
    .date-weekend { font-size: 0.65rem; color: #b91c1c; font-weight: 500; }

    .date-picker {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
    }
    .date-input {
        border: 1px solid var(--border-soft); border-radius: 0.5rem;
        padding: 0.4rem 0.6rem; font-size: 0.75rem; color: var(--text-primary);
        background: rgba(255,255,255,0.9);
    }
    .date-input:focus { outline: none; border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    .alert-weekend {
        display: flex; align-items: center; justify-content: center; gap: 0.75rem;
        border: 1px solid rgba(212,175,55,0.4); background: rgba(255,249,230,0.95);
        border-radius: 0.75rem; padding: 1rem; color: #7a2a2a;
    }
    .alert-weekend-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.6rem;
        display: flex; align-items: center; justify-content: center;
        background: rgba(254,249,231,0.7); color: var(--gold-500);
        font-size: 1rem;
    }

    .slot-card {
        padding: 0.75rem; border-radius: 0.6rem;
        border: 1px solid var(--border-soft);
        transition: all 0.18s ease;
    }
    .slot-card.pending { background: rgba(254,249,231,0.9); border-color: rgba(212,175,55,0.4); }
    .slot-card.approved { background: rgba(240,253,244,0.9); border-color: rgba(16,185,129,0.3); }
    .slot-card.completed { background: rgba(245,240,235,0.9); border-color: rgba(212,175,55,0.3); }
    .slot-card.rejected { background: rgba(253,242,242,0.9); border-color: rgba(185,28,28,0.3); }
    .slot-card.cancelled { background: rgba(245,240,235,0.9); border-color: var(--border-soft); }
    .slot-card.no_show { background: rgba(255,237,213,0.9); border-color: rgba(234,88,12,0.3); }
    .slot-card.busy { background: rgba(254,249,231,0.9); border-color: rgba(212,175,55,0.4); }
    .slot-card.available { background: rgba(250,248,245,0.6); border-color: var(--border-soft); }
    .slot-card.overlap {
        background: linear-gradient(135deg, rgba(253,242,242,0.95) 0%, rgba(254,249,231,0.95) 100%);
        border-color: rgba(185,28,28,0.5);
        box-shadow: 0 0 0 2px rgba(185,28,28,0.15);
    }

    .slot-time { font-size: 0.75rem; font-weight: 600; color: var(--text-primary); }
    .slot-status {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.15rem 0.4rem; border-radius: 999px;
        font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .slot-status.pending { background: rgba(254,249,231,0.9); color: #7a2a2a; }
    .slot-status.approved { background: rgba(240,253,244,0.9); color: #065f46; }
    .slot-status.completed { background: rgba(245,240,235,0.9); color: var(--text-secondary); }
    .slot-status.rejected { background: rgba(253,242,242,0.9); color: #7a2a2a; }
    .slot-status.cancelled { background: rgba(245,240,235,0.9); color: var(--text-secondary); }
    .slot-status.no_show { background: rgba(255,237,213,0.9); color: #9a3412; }
    .slot-status.busy { background: rgba(254,249,231,0.9); color: #7a2a2a; }
    .slot-status.available { background: rgba(245,240,235,0.9); color: var(--text-muted); }
    .slot-status.overlap { background: rgba(253,242,242,0.9); color: #b91c1c; font-weight: 700; }

    .slot-student { font-size: 0.75rem; font-weight: 600; color: var(--text-primary); }
    .slot-id { font-size: 0.65rem; color: var(--text-secondary); font-family: ui-monospace, monospace; }
    .slot-concern { font-size: 0.65rem; color: var(--text-muted); margin-top: 0.25rem; }

    .slot-actions { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.5rem; }
    .slot-btn {
        padding: 0.25rem 0.5rem; border-radius: 0.4rem;
        font-size: 0.65rem; font-weight: 500; transition: all 0.18s ease;
    }
    .slot-btn.primary { color: #fef9e7; background: var(--maroon-700); }
    .slot-btn.primary:hover { background: var(--maroon-800); }
    .slot-btn.success { color: #fef9e7; background: #059669; }
    .slot-btn.success:hover { background: #047857; }
    .slot-btn.danger { color: #fef9e7; background: #b91c1c; }
    .slot-btn.danger:hover { background: #991b1b; }
    .slot-btn.warning { color: #fef9e7; background: #d97706; }
    .slot-btn.warning:hover { background: #b45309; }

    .legend-item { display: flex; align-items: center; gap: 0.5rem; }
    .legend-dot {
        width: 1rem; height: 1rem; border-radius: 0.25rem;
        border: 1px solid var(--border-soft);
    }
    .legend-dot.pending { background: rgba(254,249,231,0.9); border-color: rgba(212,175,55,0.4); }
    .legend-dot.approved { background: rgba(240,253,244,0.9); border-color: rgba(16,185,129,0.3); }
    .legend-dot.completed { background: rgba(245,240,235,0.9); border-color: rgba(212,175,55,0.3); }
    .legend-dot.rejected { background: rgba(253,242,242,0.9); border-color: rgba(185,28,28,0.3); }
    .legend-dot.available { background: rgba(245,240,235,0.9); border-color: var(--border-soft); }
    .legend-dot.busy { background: rgba(254,249,231,0.9); border-color: rgba(212,175,55,0.4); }
    .legend-dot.overlap { background: linear-gradient(135deg, rgba(253,242,242,0.95) 0%, rgba(254,249,231,0.95) 100%); border-color: rgba(185,28,28,0.5); }
    .legend-label { font-size: 0.7rem; color: var(--text-secondary); }

    .summary-card {
        background: rgba(250,248,245,0.9); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 0.75rem;
    }
    .summary-row { display: flex; justify-content: space-between; font-size: 0.7rem; color: var(--text-secondary); }
    .summary-value { font-weight: 600; color: var(--text-primary); }
    .summary-value.pending { color: #7a2a2a; }
    .summary-value.approved { color: #065f46; }
    .summary-value.completed { color: var(--maroon-700); }

    .quick-stats {
        background: rgba(255,255,255,0.95); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 0.75rem;
    }
    .quick-stats p { font-size: 0.7rem; color: var(--text-secondary); margin: 0.25rem 0; }
    .quick-stats p::before { content: "•"; margin-right: 0.25rem; color: var(--gold-500); }

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
        max-width: 42rem; width: 100%; max-height: 90vh; overflow-y: auto;
    }
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

    @media (max-width: 639px) {
        .hero-card .flex { flex-direction: column; align-items: flex-start !important; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; }
        .date-nav { flex-direction: column; align-items: flex-start; }
        .date-picker { width: 100%; }
        .date-input { width: 100%; }
        .slot-card { padding: 0.6rem; }
        .slot-time { font-size: 0.7rem; }
        .slot-student { font-size: 0.7rem; }
        .slot-btn { padding: 0.2rem 0.4rem; font-size: 0.6rem; }
        .legend-item { font-size: 0.65rem; }
        .summary-row { font-size: 0.65rem; }
        .modal-card { max-height: 95vh; margin: 0.5rem; }
        .modal-header { padding: 0.85rem 1rem; }
        .modal-body { padding: 1rem; }
    }
</style>

<div class="min-h-screen cal-shell">
    <div class="cal-glow one"></div>
    <div class="cal-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        @php
            $googleCalendarId = $googleCalendarId
                ?? ($counselor->google_calendar_id ?? optional(Auth::user()->counselor)->google_calendar_id);
            $googleCalendarUrl = $googleCalendarId
                ? 'https://calendar.google.com/calendar/u/0/r?cid=' . urlencode($googleCalendarId)
                : null;
        @endphp

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-days text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Counselor Portal
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Appointment Calendar</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                View your schedule and manage booked time slots
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full md:w-auto">
                        <a href="{{ route('counselor.dashboard') }}"
                           class="secondary-btn px-4 py-2 text-xs sm:text-sm">
                            <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i>Dashboard
                        </a>
                        <a href="{{ route('counselor.appointments') }}"
                           class="primary-btn px-4 py-2 text-xs sm:text-sm">
                            <i class="fas fa-list mr-1.5 text-[9px] sm:text-xs"></i>All Appointments
                        </a>
                        @if($googleCalendarUrl)
                            <a href="{{ $googleCalendarUrl }}"
                               target="_blank" rel="noopener"
                               class="primary-btn px-4 py-2 text-xs sm:text-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-color: rgba(16,185,129,0.3);">
                                <i class="fab fa-google mr-1.5 text-[9px] sm:text-xs"></i>Google Calendar
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}"
                               class="secondary-btn px-4 py-2 text-xs sm:text-sm" style="border-color: rgba(212,175,55,0.4); color: #7a2a2a;">
                                <i class="fas fa-link mr-1.5 text-[9px] sm:text-xs"></i>Add Calendar ID
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Navigation -->
        <div class="panel-card mb-6">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-calendar-day text-[9px] sm:text-xs"></i></div>
                <div>
                    <h2 class="panel-title">Date Navigation</h2>
                    <p class="panel-subtitle hidden sm:block">Browse your daily schedule</p>
                </div>
            </div>

            <div class="p-4 sm:p-5">
                <div class="date-nav">
                    <div class="flex items-center gap-3 sm:gap-4">
                        @php
                            $prevDate = $date->copy()->subDay();
                            $nextDate = $date->copy()->addDay();
                            while ($prevDate->isWeekend()) { $prevDate->subDay(); }
                            while ($nextDate->isWeekend()) { $nextDate->addDay(); }
                        @endphp

                        <a href="?date={{ $prevDate->format('Y-m-d') }}" class="date-nav-btn" title="Previous day">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        <div>
                            <h2 class="date-title">
                                {{ $date->format('l, F j, Y') }}
                                @if($date->isWeekend())
                                    <span class="date-weekend">(Weekend)</span>
                                @endif
                            </h2>
                        </div>

                        <a href="?date={{ $nextDate->format('Y-m-d') }}" class="date-nav-btn" title="Next day">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <form method="GET" class="date-picker">
                        <input type="date" name="date" value="{{ $selectedDate }}"
                               class="date-input"
                               min="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}">
                        <button type="submit" class="primary-btn px-4 py-2 text-xs">Go</button>
                        @php
                            $today = \Carbon\Carbon::today();
                            $nextAvailableDay = $today->copy();
                            while ($nextAvailableDay->isWeekend()) { $nextAvailableDay->addDay(); }
                        @endphp
                        <a href="?date={{ $nextAvailableDay->format('Y-m-d') }}"
                           class="secondary-btn px-4 py-2 text-xs">
                            Next Available
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Weekend Message -->
        @if($date->isWeekend())
        <div class="alert-weekend mb-6">
            <div class="alert-weekend-icon">
                <i class="fas fa-calendar-xmark"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold">Weekend Schedule</h3>
                <p class="text-xs mt-0.5">No appointments are scheduled on weekends. Please select a weekday to view appointments.</p>
            </div>
        </div>
        @endif

        <!-- Time Slots Grid - Only show on weekdays -->
        @if(!$date->isWeekend())
        @php
            $busyIntervals = $busyIntervals ?? [];
            $googleCalendarEvents = $googleCalendarEvents ?? $busyIntervals;
            $morningSlots = ['08:00', '09:00', '10:00', '11:00'];
            $afternoonSlots = ['13:00', '14:00', '15:00', '16:00'];
            $allSlots = array_merge($morningSlots, $afternoonSlots);

            $isSlotBusy = function (\Carbon\Carbon $slotStart, \Carbon\Carbon $slotEnd) use ($busyIntervals) {
                foreach ($busyIntervals as $interval) {
                    if (!isset($interval['start'], $interval['end'])) { continue; }
                    if ($slotStart < $interval['end'] && $slotEnd > $interval['start']) { return true; }
                }
                return false;
            };

            $findEventForSlot = function (\Carbon\Carbon $slotStart, \Carbon\Carbon $slotEnd) use ($googleCalendarEvents) {
                foreach ($googleCalendarEvents as $event) {
                    if (!isset($event['start'], $event['end'])) { continue; }
                    if ($slotStart < $event['end'] && $slotEnd > $event['start']) { return $event; }
                }
                return null;
            };
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Morning Session (8AM - 12PM) -->
            <div class="lg:col-span-1">
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-[#2c2420] border-b border-[#e5e0db]/60 pb-2 mb-3">Morning (8AM - 12PM)</h3>
                        <div class="space-y-3">
                            @foreach($morningSlots as $slot)
                                @php
                                    $appointment = $appointments->first(function($appt) use ($slot) {
                                        return isset($appt->formatted_start_time) && $appt->formatted_start_time === $slot;
                                    });
                                    $slotStart = $date->copy()->setTimeFromTimeString($slot);
                                    $slotEnd = $slotStart->copy()->addHour();
                                    $event = $findEventForSlot($slotStart, $slotEnd);
                                    $isBusy = !$appointment && $event;
                                    $isOverlap = $appointment && $event;
                                    $slotClass = $isOverlap ? 'overlap' : ($appointment ? $appointment->status : ($isBusy ? 'busy' : 'available'));
                                @endphp
                                <div class="slot-card {{ $slotClass }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="slot-time">{{ \Carbon\Carbon::parse($slot)->format('g:i A') }}</span>
                                        @if($isOverlap)
                                            <span class="slot-status overlap">
                                                <i class="fas fa-exclamation-triangle mr-1 text-[9px]"></i>Overlap
                                            </span>
                                        @elseif($appointment)
                                            <span class="slot-status {{ $appointment->status }}">
                                                {{ $appointment->display_status ?? ucfirst($appointment->status) }}
                                            </span>
                                        @elseif($isBusy)
                                            <span class="slot-status busy">Google Calendar</span>
                                        @else
                                            <span class="slot-status available">Available</span>
                                        @endif
                                    </div>

                                    @if($appointment)
                                        <div class="text-xs">
                                            <p class="slot-student truncate">
                                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                            </p>
                                            <p class="slot-id">{{ $appointment->student->student_id }}</p>
                                            <p class="slot-concern truncate">{{ Str::limit($appointment->concern, 40) }}</p>
                                        </div>
                                        @if($isOverlap)
                                            <div class="mt-1.5 pt-1.5 border-t border-red-200 text-xs text-[#b91c1c]">
                                                <p class="font-semibold flex items-center gap-1">
                                                    <i class="fab fa-google text-[9px]"></i>
                                                    {{ $event['title'] ?? 'Google Calendar Event' }}
                                                </p>
                                                @if(!empty($event['location']))<p class="text-[10px] opacity-80">{{ $event['location'] }}</p>@endif
                                            </div>
                                        @endif
                                        <div class="slot-actions">
                                            <button onclick="showAppointmentDetails({{ $appointment->id }})"
                                                    class="slot-btn primary">View</button>
                                            <a href="{{ route('counselor.appointments') }}?highlight={{ $appointment->id }}"
                                               class="slot-btn primary" style="background: var(--gold-500);">
                                                <i class="fas fa-arrow-up-right-from-square" style="font-size:0.55rem;margin-right:2px;"></i>Open
                                            </a>
                                            @if($appointment->status === 'pending')
                                                <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="slot-btn success">Approve</button>
                                                </form>
                                            @elseif(in_array($appointment->status, ['approved', 'rescheduled'], true))
                                                <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="slot-btn primary">Complete</button>
                                                </form>
                                                <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="no_show">
                                                    <button type="submit" class="slot-btn warning">No Show</button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        @if($isBusy)
                                            <div class="text-xs text-[#7a2a2a]">
                                                <p class="font-semibold truncate">{{ $event['title'] ?? 'Busy' }}</p>
                                                @if(!empty($event['location']))<p class="text-[10px]">{{ $event['location'] }}</p>@endif
                                                @if(!empty($event['description']))<p class="text-[10px] truncate">{{ Str::limit($event['description'], 40) }}</p>@endif
                                            </div>
                                        @else
                                            <p class="text-xs text-[#8b7e76]">No appointment</p>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Afternoon Session (1PM - 5PM) -->
            <div class="lg:col-span-1">
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-[#2c2420] border-b border-[#e5e0db]/60 pb-2 mb-3">Afternoon (1PM - 5PM)</h3>
                        <div class="space-y-3">
                            @foreach($afternoonSlots as $slot)
                                @php
                                    $appointment = $appointments->first(function($appt) use ($slot) {
                                        return isset($appt->formatted_start_time) && $appt->formatted_start_time === $slot;
                                    });
                                    $slotStart = $date->copy()->setTimeFromTimeString($slot);
                                    $slotEnd = $slotStart->copy()->addHour();
                                    $event = $findEventForSlot($slotStart, $slotEnd);
                                    $isBusy = !$appointment && $event;
                                    $isOverlap = $appointment && $event;
                                    $slotClass = $isOverlap ? 'overlap' : ($appointment ? $appointment->status : ($isBusy ? 'busy' : 'available'));
                                @endphp
                                <div class="slot-card {{ $slotClass }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="slot-time">{{ \Carbon\Carbon::parse($slot)->format('g:i A') }}</span>
                                        @if($isOverlap)
                                            <span class="slot-status overlap">
                                                <i class="fas fa-exclamation-triangle mr-1 text-[9px]"></i>Overlap
                                            </span>
                                        @elseif($appointment)
                                            <span class="slot-status {{ $appointment->status }}">
                                                {{ $appointment->display_status ?? ucfirst($appointment->status) }}
                                            </span>
                                        @elseif($isBusy)
                                            <span class="slot-status busy">Google Calendar</span>
                                        @else
                                            <span class="slot-status available">Available</span>
                                        @endif
                                    </div>

                                    @if($appointment)
                                        <div class="text-xs">
                                            <p class="slot-student truncate">
                                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                            </p>
                                            <p class="slot-id">{{ $appointment->student->student_id }}</p>
                                            <p class="slot-concern truncate">{{ Str::limit($appointment->concern, 40) }}</p>
                                        </div>
                                        @if($isOverlap)
                                            <div class="mt-1.5 pt-1.5 border-t border-red-200 text-xs text-[#b91c1c]">
                                                <p class="font-semibold flex items-center gap-1">
                                                    <i class="fab fa-google text-[9px]"></i>
                                                    {{ $event['title'] ?? 'Google Calendar Event' }}
                                                </p>
                                                @if(!empty($event['location']))<p class="text-[10px] opacity-80">{{ $event['location'] }}</p>@endif
                                            </div>
                                        @endif
                                        <div class="slot-actions">
                                            <button onclick="showAppointmentDetails({{ $appointment->id }})"
                                                    class="slot-btn primary">View</button>
                                            <a href="{{ route('counselor.appointments') }}?highlight={{ $appointment->id }}"
                                               class="slot-btn primary" style="background: var(--gold-500);">
                                                <i class="fas fa-arrow-up-right-from-square" style="font-size:0.55rem;margin-right:2px;"></i>Open
                                            </a>
                                            @if($appointment->status === 'pending')
                                                <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="slot-btn success">Approve</button>
                                                </form>
                                            @elseif(in_array($appointment->status, ['approved', 'rescheduled'], true))
                                                <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="slot-btn primary">Complete</button>
                                                </form>
                                                <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="no_show">
                                                    <button type="submit" class="slot-btn warning">No Show</button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        @if($isBusy)
                                            <div class="text-xs text-[#7a2a2a]">
                                                <p class="font-semibold truncate">{{ $event['title'] ?? 'Busy' }}</p>
                                                @if(!empty($event['location']))<p class="text-[10px]">{{ $event['location'] }}</p>@endif
                                                @if(!empty($event['description']))<p class="text-[10px] truncate">{{ Str::limit($event['description'], 40) }}</p>@endif
                                            </div>
                                        @else
                                            <p class="text-xs text-[#8b7e76]">No appointment</p>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Legend & Summary -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Legend -->
                <div class="legend-card">
                    <div class="panel-topline"></div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-[#2c2420] border-b border-[#e5e0db]/60 pb-2 mb-3">Legend</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <div class="legend-item"><div class="legend-dot pending"></div><span class="legend-label">Pending</span></div>
                            <div class="legend-item"><div class="legend-dot approved"></div><span class="legend-label">Approved</span></div>
                            <div class="legend-item"><div class="legend-dot completed"></div><span class="legend-label">Completed</span></div>
                            <div class="legend-item"><div class="legend-dot available"></div><span class="legend-label">Available</span></div>
                            <div class="legend-item"><div class="legend-dot busy"></div><span class="legend-label">Google Calendar</span></div>
                            <div class="legend-item"><div class="legend-dot overlap"></div><span class="legend-label">⚠ Overlap</span></div>
                        </div>
                    </div>
                </div>

                <!-- Daily Summary -->
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-[#2c2420] border-b border-[#e5e0db]/60 pb-2 mb-3">Daily Summary</h3>
                        <div class="space-y-2">
                            <div class="summary-row"><span>Total Bookings:</span><span class="summary-value">{{ $appointments->count() }}</span></div>
                            <div class="summary-row"><span>Pending:</span><span class="summary-value pending">{{ $appointments->where('status', 'pending')->count() }}</span></div>
                            <div class="summary-row"><span>Approved:</span><span class="summary-value approved">{{ $appointments->where('status', 'approved')->count() }}</span></div>
                            <div class="summary-row"><span>Completed:</span><span class="summary-value completed">{{ $appointments->where('status', 'completed')->count() }}</span></div>
                            @php
                                $bookedSlots = $appointments->whereIn('status', ['pending', 'approved', 'completed'])->count();
                                $busySlots = 0;
                                foreach ($allSlots as $slot) {
                                    $appointmentForSlot = $appointments->first(function($appt) use ($slot) {
                                        return isset($appt->formatted_start_time) && $appt->formatted_start_time === $slot;
                                    });
                                    if ($appointmentForSlot) { continue; }
                                    $slotStart = $date->copy()->setTimeFromTimeString($slot);
                                    $slotEnd = $slotStart->copy()->addHour();
                                    if ($isSlotBusy($slotStart, $slotEnd)) { $busySlots++; }
                                }
                                $availableSlots = count($allSlots) - $bookedSlots - $busySlots;
                            @endphp
                            <div class="summary-row"><span>Available Slots:</span><span class="summary-value">{{ $availableSlots }}</span></div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="quick-stats">
                    <h4 class="text-xs font-semibold text-[#2c2420] mb-2">Quick Stats</h4>
                    <p class="text-[10px]">{{ $appointments->whereIn('status', ['pending', 'approved'])->count() }} active appointments today</p>
                    <p class="text-[10px]">{{ $appointments->where('status', 'pending')->count() }} need your attention</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Appointment Details Modal -->
    <div id="appointmentModal" class="modal-backdrop hidden">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="text-sm font-semibold text-[#2c2420]">Appointment Details</h3>
                <button onclick="closeAppointmentModal()" class="modal-close" title="Close">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <div id="appointmentDetails" class="modal-body">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <script>
        function showAppointmentDetails(appointmentId) {
            fetch(`/counselor/appointments/${appointmentId}/details`)
                .then(response => response.json())
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
                                    <label class="field-label">Initial Interview</label>
                                    <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.student.initial_interview_completed_label}</p>
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
                            <div>
                                <label class="field-label">Booking Type</label>
                                <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.appointment.booking_type || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="field-label">Booking Category</label>
                                <p class="mt-1 text-xs sm:text-sm text-[#2c2420]">${data.appointment.booking_category ? data.appointment.booking_category.charAt(0).toUpperCase() + data.appointment.booking_category.slice(1).replace('-', ' ') : 'N/A'}</p>
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

                            <div>
                                <label class="field-label">Status</label>
                                <span class="mt-1 inline-flex px-2 py-1 text-[10px] font-semibold rounded-full slot-status ${data.appointment.status}">
                                    ${data.appointment.status.charAt(0).toUpperCase() + data.appointment.status.slice(1)}
                                </span>
                            </div>
                        </div>
                    `;

                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching appointment details:', error);
                    alert('Error loading appointment details. Please try again.');
                });
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('appointmentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAppointmentModal();
            }
        });
    </script>
@endsection