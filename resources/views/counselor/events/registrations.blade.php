@extends('layouts.app')

@section('title', 'Event Registrations - OGC')

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

    /* Base Layout & Glow */
    .reg-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .reg-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .reg-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .reg-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

    /* Glass Cards */
    .panel-card {
        position: relative; z-index: 1; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .panel-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    /* Header Specifics */
    .event-meta { color: var(--text-muted); font-size: 0.8rem; display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 0.5rem; }
    .event-meta i { color: var(--gold-500); margin-right: 0.25rem; }
    
    .badge-required {
        display: inline-flex; align-items: center; gap: 0.3rem;
        background: rgba(185, 28, 28, 0.1); color: #b91c1c;
        border: 1px solid rgba(185, 28, 28, 0.2);
        padding: 0.25rem 0.6rem; border-radius: 999px;
        font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
        margin-top: 0.5rem;
    }

    /* Buttons */
    .btn-action {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0.6rem 1rem; border-radius: 0.6rem; font-weight: 600; font-size: 0.8rem;
        transition: all 0.2s ease; white-space: nowrap; gap: 0.5rem;
    }
    .btn-export {
        background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white;
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.15);
    }
    .btn-export:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(5, 150, 105, 0.2); }
    
    .btn-back {
        background: white; color: var(--text-secondary); border: 1px solid var(--border-soft);
    }
    .btn-back:hover { background: var(--bg-warm); color: var(--text-primary); border-color: var(--maroon-700); }

    /* Stats Cards */
    .stat-card {
        display: flex; align-items: center; gap: 1rem; padding: 1rem;
        border-radius: 0.75rem; border: 1px solid var(--border-soft);
        background: rgba(255,255,255,0.8);
    }
    .stat-icon-box {
        width: 3rem; height: 3rem; border-radius: 0.6rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; flex-shrink: 0;
    }
    .stat-icon-gray { background: rgba(229, 231, 235, 0.6); color: var(--maroon-700); }
    .stat-icon-gold { background: rgba(255, 249, 230, 0.6); color: var(--gold-500); }
    .stat-icon-maroon { background: rgba(254, 242, 242, 0.6); color: var(--maroon-800); }
    
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); line-height: 1.2; }

    /* Table Filters */
    .filter-group { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .filter-btn {
        font-size: 0.7rem; font-weight: 600; padding: 0.35rem 0.75rem;
        border-radius: 999px; border: 1px solid transparent;
        cursor: pointer; transition: all 0.2s ease;
        background: rgba(250,248,245,0.6); color: var(--text-secondary);
    }
    .filter-btn:hover { background: rgba(254,249,231,0.6); color: var(--maroon-700); }
    .filter-btn.active {
        background: var(--maroon-700); color: white;
        box-shadow: 0 2px 6px rgba(122, 42, 42, 0.2);
        transform: scale(1.05);
    }

    /* Table Styling */
    .table-container { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
    .custom-table thead th {
        background: rgba(250,248,245,0.8); color: var(--text-muted);
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
        padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-soft);
        text-align: left;
    }
    .custom-table tbody td {
        padding: 0.85rem 1rem; border-bottom: 1px solid rgba(229, 224, 219, 0.5);
        color: var(--text-secondary); font-size: 0.8rem; vertical-align: middle;
    }
    .custom-table tbody tr:last-child td { border-bottom: none; }
    .custom-table tbody tr:hover { background: rgba(254,249,231,0.3); }

    /* Avatar & Info */
    .avatar-circle {
        width: 2.5rem; height: 2.5rem; border-radius: 50%;
        background: rgba(250,248,245,0.8); border: 1px solid var(--border-soft);
        display: flex; align-items: center; justify-content: center;
        color: var(--maroon-700); font-weight: 700; font-size: 0.75rem; flex-shrink: 0;
    }
    .student-name { font-weight: 600; color: var(--text-primary); font-size: 0.85rem; }
    .student-id { font-size: 0.7rem; color: var(--text-muted); font-family: monospace; }
    .student-meta { font-size: 0.65rem; color: var(--text-muted); margin-top: 0.1rem; }

    /* Status Badges */
    .status-badge {
        display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .status-registered { background: rgba(255, 249, 230, 0.8); color: var(--gold-500); border: 1px solid rgba(212, 175, 55, 0.3); }
    .status-attended { background: rgba(209, 250, 229, 0.8); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); }
    .status-cancelled { background: rgba(254, 226, 226, 0.8); color: #b91c1c; border: 1px solid rgba(185, 28, 28, 0.3); }
    
    .override-badge {
        background: rgba(254, 243, 199, 0.8); color: #92400e;
        border: 1px solid rgba(245, 158, 11, 0.3);
        padding: 0.15rem 0.4rem; border-radius: 999px; font-size: 0.65rem;
    }

    /* Action Icons */
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        transition: all 0.2s ease; background: transparent; border: none; cursor: pointer;
    }
    .action-btn:hover { transform: translateY(-1px); }
    .action-attend { color: #059669; } .action-attend:hover { background: rgba(5, 150, 105, 0.1); }
    .action-cancel { color: #b91c1c; } .action-cancel:hover { background: rgba(185, 28, 28, 0.1); }
    .action-rereg { color: var(--maroon-700); } .action-rereg:hover { background: rgba(122, 42, 42, 0.1); }
    .action-done { color: #059669; opacity: 0.6; }

    /* Summary Box */
    .summary-box {
        background: rgba(255, 249, 230, 0.6); border: 1px solid rgba(212, 175, 55, 0.3);
        border-radius: 0.75rem; padding: 1rem;
    }
    .summary-title { color: var(--maroon-800); font-weight: 700; font-size: 0.95rem; margin-bottom: 0.75rem; }
    .summary-item { font-size: 0.8rem; color: var(--text-secondary); }
    .summary-item strong { color: var(--text-primary); }

    /* Empty State */
    .empty-state { padding: 3rem 1rem; text-align: center; }
    .empty-icon { color: var(--border-soft); font-size: 3.5rem; margin-bottom: 1rem; }
    .empty-title { color: var(--text-secondary); font-weight: 600; font-size: 1.1rem; }
    .empty-text { color: var(--text-muted); font-size: 0.85rem; }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .header-actions { flex-direction: column; width: 100%; }
        .header-actions .btn-action { width: 100%; }
        .stat-card { padding: 0.75rem; }
        .stat-icon-box { width: 2.5rem; height: 2.5rem; font-size: 1rem; }
        .stat-value { font-size: 1.25rem; }
        .filter-group { justify-content: center; }
        .custom-table { font-size: 0.75rem; }
        .custom-table thead th, .custom-table tbody td { padding: 0.6rem 0.5rem; }
        .avatar-circle { width: 2rem; height: 2rem; font-size: 0.65rem; }
    }
</style>

<div class="min-h-screen reg-shell">
    <div class="reg-glow one"></div>
    <div class="reg-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 panel-card p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)] tracking-tight">Event Registrations</h1>
                    <p class="text-[var(--text-secondary)] mt-1 font-medium">{{ $event->title }}</p>
                    
                    <div class="event-meta">
                        <span><i class="fas fa-calendar-days"></i> {{ $event->date_range }}</span>
                        <span><i class="fas fa-clock"></i> {{ $event->time_range }}</span>
                        <span><i class="far fa-location-dot"></i> {{ $event->location }}</span>
                    </div>

                    @if($event->is_required)
                        <span class="badge-required">
                            <i class="fas fa-circle-exclamation"></i> Required Event
                        </span>
                    @endif
                </div>

                <div class="header-actions flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('counselor.events.export-registrations', $event) }}"
                       class="btn-action btn-export">
                        <i class="fas fa-file-export"></i> Export CSV
                    </a>
                    <a href="{{ route('counselor.events.index') }}"
                       class="btn-action btn-back">
                        <i class="fas fa-calendar"></i> Back to Events
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-gray">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="stat-label">Total Registrations</p>
                    <p class="stat-value">{{ $registrationStats['total'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-gold">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <p class="stat-label">Registered</p>
                    <p class="stat-value">{{ $registrationStats['registered'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-maroon" style="background: rgba(209, 250, 229, 0.4); color: #047857;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <p class="stat-label">Attended</p>
                    <p class="stat-value">{{ $registrationStats['attended'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-maroon">
                    <i class="fas fa-user-times"></i>
                </div>
                <div>
                    <p class="stat-label">Cancelled</p>
                    <p class="stat-value">{{ $registrationStats['cancelled'] }}</p>
                </div>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="panel-card overflow-hidden">
            <div class="p-4 border-b border-[var(--border-soft)] flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[rgba(250,248,245,0.4)]">
                <h2 class="text-sm font-bold text-[var(--text-primary)] uppercase tracking-wide">Student Registrations</h2>
                <div class="filter-group">
                    <button onclick="filterRegistrations('all')" class="filter-btn active">
                        All ({{ $registrationStats['total'] }})
                    </button>
                    <button onclick="filterRegistrations('registered')" class="filter-btn">
                        Registered ({{ $registrationStats['registered'] }})
                    </button>
                    <button onclick="filterRegistrations('attended')" class="filter-btn">
                        Attended ({{ $registrationStats['attended'] }})
                    </button>
                    <button onclick="filterRegistrations('cancelled')" class="filter-btn">
                        Cancelled ({{ $registrationStats['cancelled'] }})
                    </button>
                </div>
            </div>

            @if($registrations->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-users-slash empty-icon"></i>
                    <h3 class="empty-title">No Registrations Yet</h3>
                    <p class="empty-text">No students have registered for this event yet.</p>
                </div>
            @else
                <div class="table-container">
                    <table class="custom-table" id="registrationsTable">
                        <thead>
                            <tr>
                                <th class="w-1/4">Student Info</th>
                                <th class="w-1/5">Contact</th>
                                <th class="w-1/5">Academic Info</th>
                                <th class="w-1/6">Registration</th>
                                <th class="w-1/6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-soft)]/50">
                            @foreach($registrations as $registration)
                                @php
                                    $student = $registration->student;
                                    $user = $student->user;
                                @endphp
                                <tr class="registration-row" data-status="{{ $registration->status }}">
                                    <!-- Student Information -->
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="avatar-badge avatar-circle">
                                                {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="student-name truncate">
                                                    {{ $user->first_name }}
                                                    {{ $user->middle_name ? substr($user->middle_name, 0, 1) . '.' : '' }}
                                                    {{ $user->last_name }}
                                                </div>
                                                <div class="student-id">ID: {{ $student->student_id ?? 'N/A' }}</div>
                                                <div class="student-meta">
                                                    Age: {{ $user->age ?? 'N/A' }} • {{ $user->sex ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Contact Information -->
                                    <td>
                                        <div class="font-medium text-[var(--text-primary)] text-xs">{{ $user->email }}</div>
                                        <div class="text-[var(--text-muted)] text-xs mt-0.5">{{ $user->phone_number ?? 'No phone' }}</div>
                                    </td>

                                    <!-- Academic Information -->
                                    <td>
                                        <div class="font-medium text-[var(--text-primary)] text-xs">
                                            {{ $student->college->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-[var(--text-muted)] text-xs mt-0.5">
                                            {{ $student->year_level ?? 'N/A' }}
                                        </div>
                                        <div class="text-[var(--text-muted)] text-xs mt-0.5">
                                            {{ $student->course ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <!-- Registration Information -->
                                    <td>
                                        <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                            <span class="status-badge status-{{ $registration->status }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                            @if($registration->wasOverriddenByCounselor())
                                                <span class="override-badge" title="Overridden by counselor">
                                                    <i class="fas fa-shield-halved"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-[var(--text-muted)]">
                                            Reg: {{ $registration->registered_at->format('M j, g:i A') }}
                                        </div>
                                        @if($registration->cancelled_at)
                                            <div class="text-[10px] text-red-500">
                                                Cnl: {{ $registration->cancelled_at->format('M j, g:i A') }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Actions -->
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <!-- Mark as Attended -->
                                            @if($registration->status !== 'attended')
                                                <form action="{{ route('counselor.events.update-registration-status', [$event, $registration]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="attended">
                                                    <button type="submit"
                                                            class="action-btn action-attend"
                                                            title="Mark as Attended"
                                                            onclick="return confirm('Mark this student as attended?')">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="action-btn action-done" title="Already Attended">
                                                    <i class="fas fa-circle-dot"></i>
                                                </span>
                                            @endif

                                            <!-- Cancel Registration -->
                                            @if($registration->status !== 'cancelled')
                                                <form action="{{ route('counselor.events.update-registration-status', [$event, $registration]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit"
                                                            class="action-btn action-cancel"
                                                            title="Cancel Registration"
                                                            onclick="return confirm('Cancel this registration?')">
                                                        <i class="fas fa-circle-xmark"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Re-register Button -->
                                            @if($registration->status === 'cancelled' && $event->is_registration_open && $event->hasAvailableSlots())
                                                <form action="{{ route('counselor.events.update-registration-status', [$event, $registration]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="registered">
                                                    <button type="submit"
                                                            class="action-btn action-rereg"
                                                            title="Re-register Student"
                                                            onclick="return confirm('Re-register this student?')">
                                                        <i class="fas fa-redo-alt"></i>
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
            <div class="mt-6 summary-box">
                <h3 class="summary-title">Registration Summary</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="summary-item">
                        <strong>Total Capacity:</strong>
                        {{ $event->max_attendees ? $event->max_attendees . ' students' : 'Unlimited' }}
                    </div>
                    <div class="summary-item">
                        <strong>Available Slots:</strong>
                        {{ $event->available_slots }}
                        @if($event->max_attendees)
                            ({{ number_format(($event->registered_count / $event->max_attendees) * 100, 1) }}% filled)
                        @endif
                    </div>
                    <div class="summary-item">
                        <strong>Active Registrations:</strong>
                        {{ $registrationStats['registered'] }} students
                    </div>
                    <div class="summary-item">
                        <strong>Attendance Rate:</strong>
                        {{ number_format(($registrationStats['attended'] / max(1, $registrationStats['total'])) * 100, 1) }}%
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function filterRegistrations(status) {
        // Update active filter button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.classList.add('active');

        // Filter rows
        const rows = document.querySelectorAll('.registration-row');
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection