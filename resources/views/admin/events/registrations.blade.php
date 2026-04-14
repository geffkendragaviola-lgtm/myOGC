@extends('layouts.admin')

@section('title', 'Admin Dashboard - OGC')

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

    .registrations-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .registrations-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .registrations-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .registrations-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .stats-card, .summary-card, .glass-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .stats-card:hover, .glass-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .panel-card::before, .stats-card::before, .glass-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon, .stats-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
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
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
        pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.25rem; }

    .primary-btn, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        background: #ffffff; color: var(--text-secondary); border: 1px solid var(--border-soft);
        box-shadow: 0 2px 6px rgba(44,36,32,0.03);
    }
    .secondary-btn:hover { background: #f5f0eb; }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .stats-card { transition: all 0.2s ease; }
    .stats-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(44,36,32,0.06); }
    .stats-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; }
    .mini-progress { width: 100%; background: #f5f0eb; border-radius: 999px; height: 0.3rem; overflow: hidden; }
    .mini-progress > div { height: 100%; border-radius: 999px; }

    .status-badge { font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 9999px; font-weight: 700; display: inline-flex; align-items: center; }
    .status-registered { background-color: #fffbeb; color: #9a7b0a; border: 1px solid rgba(212,175,55,0.3); }
    .status-attended { background-color: #ecfdf5; color: #059669; border: 1px solid rgba(16,185,129,0.3); }
    .status-cancelled { background-color: #fdf2f2; color: #b91c1c; border: 1px solid rgba(185,28,28,0.3); }

    .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .table-head { background: rgba(250,248,245,0.6); }
    .table-row { transition: background-color 0.15s ease; }
    .table-row:hover { background: rgba(254,249,231,0.35); }

    .avatar-badge {
        width: 2.25rem; height: 2.25rem; border-radius: 999px; display: flex;
        align-items: center; justify-content: center; color: var(--maroon-700); font-weight: 700;
        font-size: 0.7rem; background: linear-gradient(135deg, #fef9e7 0%, #f5e6b8 100%);
        border: 1px solid rgba(212,175,55,0.3); flex-shrink: 0;
    }

    .empty-state-icon {
        width: 3rem; height: 3rem; border-radius: 999px; display: flex;
        align-items: center; justify-content: center; background: rgba(245,240,235,0.6);
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.04); margin-inline: auto;
    }

    .summary-box {
        border-radius: 0.75rem; border: 1px solid rgba(212,175,55,0.3);
        background: linear-gradient(135deg, rgba(254,249,231,0.95), rgba(255,255,255,0.92));
        box-shadow: 0 4px 12px rgba(92,26,26,0.04);
    }

    .action-icon-btn { transition: all 0.18s ease; display: inline-flex; align-items: center; justify-content: center; }
    .action-icon-btn:hover { transform: translateY(-1px); }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .stats-icon { width: 1.75rem; height: 1.75rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; padding: 0.55rem 0.85rem; }
        .flex-wrap.gap-2 { gap: 0.5rem !important; }
        .avatar-badge { width: 2rem; height: 2rem; font-size: 0.65rem; }
    }
</style>

<div class="min-h-screen registrations-shell">
    <div class="registrations-glow one"></div>
    <div class="registrations-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-users text-base sm:text-lg"></i>
                        </div>

                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Event Registrations
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Event Registrations</h1>
                            <p class="text-[#4a3f3a] mt-1.5 font-medium text-sm truncate">{{ $event->title }}</p>
                            <p class="text-[#8b7e76] text-[10px] sm:text-xs mt-1.5 flex flex-wrap gap-x-2">
                                <span><i class="far fa-calendar mr-1"></i> {{ $event->date_range }}</span>
                                <span><i class="far fa-clock mr-1"></i> {{ $event->time_range }}</span>
                                <span class="hidden sm:inline"><i class="far fa-map-marker-alt mr-1"></i> {{ $event->location }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row xl:flex-col justify-center gap-2.5 p-4">
                        <div class="flex items-center gap-3">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-file-export text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Quick Actions</p>
                                <p class="summary-value">Export & Navigate</p>
                                <p class="summary-subtext hidden sm:block">Export registrations or go back to the events list.</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-2 sm:mt-3 justify-center sm:justify-start xl:justify-center">
                            <a href="{{ route('counselor.events.export-registrations', $event) }}"
                               class="primary-btn text-xs sm:text-sm rounded-lg">
                                <i class="fas fa-file-export mr-1.5 text-[9px] sm:text-xs"></i> Export CSV
                            </a>
                            <a href="{{ route('counselor.events.index') }}"
                               class="secondary-btn text-xs sm:text-sm rounded-lg">
                                <i class="fas fa-calendar mr-1.5 text-[9px] sm:text-xs"></i> Back to Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-6">
            <div class="stats-card p-3.5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-semibold text-[#8b7e76] uppercase tracking-[0.16em]">Total Registrations</p>
                        <p class="text-xl sm:text-2xl font-semibold text-[#2c2420] mt-1.5">{{ $registrationStats['total'] }}</p>
                    </div>
                    <div class="stats-icon bg-[#f5f0eb]">
                        <i class="fas fa-users text-[#7a2a2a] text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="mt-3 mini-progress">
                    <div class="bg-gradient-to-r from-[#7a2a2a] to-[#9a2a3a]" style="width: 100%"></div>
                </div>
            </div>

            <div class="stats-card p-3.5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-semibold text-[#8b7e76] uppercase tracking-[0.16em]">Registered</p>
                        <p class="text-xl sm:text-2xl font-semibold text-[#2c2420] mt-1.5">{{ $registrationStats['registered'] }}</p>
                    </div>
                    <div class="stats-icon bg-[#fffbeb]">
                        <i class="fas fa-user-check text-[#9a7b0a] text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="mt-3 mini-progress">
                    <div class="bg-gradient-to-r from-[#9a7b0a] to-[#c9a227]" style="width: {{ $registrationStats['total'] > 0 ? ($registrationStats['registered'] / $registrationStats['total']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="stats-card p-3.5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-semibold text-[#8b7e76] uppercase tracking-[0.16em]">Attended</p>
                        <p class="text-xl sm:text-2xl font-semibold text-[#2c2420] mt-1.5">{{ $registrationStats['attended'] }}</p>
                    </div>
                    <div class="stats-icon bg-[#ecfdf5]">
                        <i class="fas fa-calendar-check text-[#059669] text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="mt-3 mini-progress">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600" style="width: {{ $registrationStats['total'] > 0 ? ($registrationStats['attended'] / $registrationStats['total']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="stats-card p-3.5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-semibold text-[#8b7e76] uppercase tracking-[0.16em]">Cancelled</p>
                        <p class="text-xl sm:text-2xl font-semibold text-[#2c2420] mt-1.5">{{ $registrationStats['cancelled'] }}</p>
                    </div>
                    <div class="stats-icon bg-[#fdf2f2]">
                        <i class="fas fa-user-times text-[#b91c1c] text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="mt-3 mini-progress">
                    <div class="bg-gradient-to-r from-rose-500 to-rose-600" style="width: {{ $registrationStats['total'] > 0 ? ($registrationStats['cancelled'] / $registrationStats['total']) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="panel-card overflow-hidden">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon">
                    <i class="fas fa-list-check text-[9px] sm:text-xs"></i>
                </div>
                <div>
                    <h2 class="panel-title">Student Registrations</h2>
                    <p class="panel-subtitle hidden sm:block">Review participant details, registration status, and attendance actions.</p>
                </div>
            </div>

            @if($registrations->isEmpty())
                <div class="p-6 sm:p-8 text-center">
                    <div class="empty-state-icon mb-3">
                        <i class="fas fa-users-slash text-[#a89f97] text-xl sm:text-2xl"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-[#4a3f3a] mb-1.5">No Registrations Yet</h3>
                    <p class="text-[#8b7e76] text-xs sm:text-sm">No students have registered for this event yet.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="w-full min-w-[800px] sm:min-w-[1000px]">
                        <thead class="table-head">
                            <tr>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Student Info</th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Contact</th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Academic Info</th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Registration</th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                            @foreach($registrations as $registration)
                                @php
                                    $student = $registration->student;
                                    $user = $student->user;
                                @endphp
                                <tr class="table-row">
                                    <!-- Student Information -->
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="avatar-badge flex-shrink-0">
                                                {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate">
                                                    {{ $user->first_name }}
                                                    {{ $user->middle_name ? $user->middle_name . ' ' : '' }}
                                                    {{ $user->last_name }}
                                                </div>
                                                <div class="text-[10px] text-[#8b7e76]">
                                                    ID: {{ $student->student_id ?? 'N/A' }}
                                                </div>
                                                <div class="text-[9px] sm:text-[10px] text-[#a89f97] hidden sm:block">
                                                    Age: {{ $user->age ?? 'N/A' }} • {{ $user->sex ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Contact Information -->
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5">
                                        <div class="text-xs sm:text-sm text-[#2c2420] truncate max-w-[140px]">{{ $user->email }}</div>
                                        <div class="text-[10px] text-[#8b7e76] truncate max-w-[140px]">{{ $user->phone_number ?? 'No phone' }}</div>
                                    </td>

                                    <!-- Academic Information -->
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5">
                                        <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[140px]">
                                            {{ $student->college->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-[10px] text-[#8b7e76]">
                                            {{ $student->year_level ?? 'N/A' }}
                                        </div>
                                        <div class="text-[10px] text-[#8b7e76] truncate max-w-[140px]">
                                            {{ $student->course ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <!-- Registration Information -->
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5 whitespace-nowrap">
                                        <span class="status-badge status-{{ $registration->status }}">
                                            {{ ucfirst($registration->status) }}
                                        </span>
                                        <div class="text-[9px] sm:text-[10px] text-[#8b7e76] mt-1.5">
                                            {{ $registration->registered_at->format('M j, Y g:i A') }}
                                        </div>
                                    </td>

                                    <!-- Actions -->
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 sm:gap-2">
                                            @if($registration->status === 'registered' && $event->is_upcoming)
                                                <form action="{{ route('counselor.events.update-registration-status', [$event, $registration]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="attended">
                                                    <button type="submit"
                                                            class="action-icon-btn text-[#059669] hover:text-[#047857]"
                                                            title="Mark as Attended">
                                                        <i class="fas fa-check-circle text-[10px] sm:text-sm"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if($registration->status === 'attended')
                                                <span class="text-[#059669]" title="Attended">
                                                    <i class="fas fa-check-double text-[10px] sm:text-sm"></i>
                                                </span>
                                            @endif

                                            @if(in_array($registration->status, ['registered', 'attended']))
                                                <form action="{{ route('counselor.events.update-registration-status', [$event, $registration]) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit"
                                                            class="action-icon-btn text-[#b91c1c] hover:text-[#991b1b]"
                                                            onclick="return confirm('Are you sure you want to cancel this registration?')"
                                                            title="Cancel Registration">
                                                        <i class="fas fa-times-circle text-[10px] sm:text-sm"></i>
                                                    </button>
                                                </form>
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

        <!-- Registration Summary -->
        @if(!$registrations->isEmpty())
            <div class="mt-4 sm:mt-5 summary-box p-3.5 sm:p-4">
                <h3 class="text-sm sm:text-base font-semibold text-[#7a2a2a] mb-2.5 sm:mb-3">Registration Summary</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3 text-[10px] sm:text-xs text-[#7a4a2a]">
                    <div>
                        <strong>Total Capacity:</strong>
                        {{ $event->max_attendees ? $event->max_attendees . ' students' : 'Unlimited' }}
                    </div>
                    <div>
                        <strong>Available Slots:</strong>
                        {{ $event->available_slots }}
                        @if($event->max_attendees)
                            ({{ number_format(($event->registered_count / $event->max_attendees) * 100, 1) }}% filled)
                        @endif
                    </div>
                    <div>
                        <strong>Registration Rate:</strong>
                        {{ number_format(($registrationStats['registered'] / max(1, $registrationStats['total'])) * 100, 1) }}% active registrations
                    </div>
                    <div>
                        <strong>Attendance Rate:</strong>
                        {{ number_format(($registrationStats['attended'] / max(1, $registrationStats['total'])) * 100, 1) }}% attended
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection