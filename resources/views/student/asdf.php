<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Event Registrations - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            --status-ended: #6b7280;
        }

        .registrations-shell {
            position: relative;
            overflow: hidden;
            background: var(--bg-warm);
            min-height: 100vh;
        }
        .registrations-glow {
            position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2;
        }
        .registrations-glow.one { top: -40px; left: -50px; width: 240px; height: 240px; background: var(--gold-400); }
        .registrations-glow.two { bottom: -50px; right: -70px; width: 280px; height: 280px; background: var(--maroon-800); }

        .hero-card, .panel-card, .glass-card, .registration-card {
            position: relative; overflow: hidden; border-radius: 0.75rem;
            border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
            transition: box-shadow 0.2s ease;
        }
        .hero-card:hover, .panel-card:hover, .glass-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
        .hero-card::before, .panel-card::before, .glass-card::before, .registration-card::before {
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 35%);
        }

        .hero-icon, .panel-icon {
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .hero-icon {
            width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
            background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
            box-shadow: 0 4px 12px rgba(92,26,26,0.15);
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

        .primary-btn, .secondary-btn, .action-btn {
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
        .action-btn.cancel {
            background: rgba(254,242,242,0.95); color: #b91c1c;
            border: 1px solid rgba(239,68,68,0.3);
        }
        .action-btn.cancel:hover { background: rgba(254,226,226,0.98); }

        .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
        .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
        .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
        .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
        .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

        .registration-meta {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.75rem; color: var(--text-secondary);
        }
        .registration-meta i { color: var(--maroon-700); width: 1rem; text-align: center; }

        .status-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            padding: 0.15rem 0.5rem; border-radius: 999px;
            font-size: 0.7rem; font-weight: 600;
        }
        .status-badge.registered {
            background: rgba(236,253,245,0.9); color: #065f46;
            border: 1px solid rgba(16,185,129,0.2);
        }
        .status-badge.cancelled {
            background: rgba(254,242,242,0.9); color: #b91c1c;
            border: 1px solid rgba(239,68,68,0.2);
        }
        .status-badge.ended {
            background: rgba(243,244,246,0.9); color: #6b7280;
            border: 1px solid rgba(156,163,175,0.2);
        }

        .type-badge {
            display: inline-flex; align-items: center;
            padding: 0.15rem 0.5rem; border-radius: 999px;
            background: rgba(212,175,55,0.15); color: var(--maroon-700);
            font-size: 0.7rem; font-weight: 600; text-transform: capitalize;
            border: 1px solid rgba(212,175,55,0.3);
        }

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

        .back-link {
            display: inline-flex; align-items: center; gap: 0.4rem;
            color: var(--maroon-700); font-size: 0.75rem; font-weight: 600;
            transition: all 0.18s ease;
        }
        .back-link:hover { color: var(--maroon-900); transform: translateX(-2px); }

        @media (max-width: 639px) {
            .panel-header { padding: 0.75rem 1rem; }
            .primary-btn, .secondary-btn, .action-btn { width: 100%; justify-content: center; padding: 0.7rem; font-size: 0.75rem; }
            .btn-row-mobile { flex-direction: column; gap: 0.5rem !important; }
            .hero-card { padding: 1rem !important; }
            .hero-icon { width: 2.25rem; height: 2.25rem; }
            .registration-meta { font-size: 0.7rem; flex-wrap: wrap; }
            .registration-card .p-6 { padding: 1rem !important; }
            .type-badge, .status-badge { font-size: 0.65rem; padding: 0.12rem 0.4rem; }
        }
    </style>
</head>
<body class="bg-[var(--bg-warm)]">
    @include('partials.navbar')

    <div class="min-h-screen registrations-shell">
        <div class="registrations-glow one"></div>
        <div class="registrations-glow two"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
            <!-- Page Header -->
            <div class="mb-5 sm:mb-6">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-check text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0 flex-1">
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

            @elseif($registrations->isEmpty())
                <!-- No Registrations -->
                <div class="glass-card empty-state mb-6">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Event Registrations Yet</h3>
                    <p class="text-[#6b5e57] text-sm mb-4 max-w-md mx-auto">
                        You haven't registered for any events yet. Start your wellness journey today!
                    </p>
                    <a href="{{ route('student.events.available') }}" class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                        <i class="fas fa-search mr-1.5 text-[9px] sm:text-xs"></i>
                        <span>Browse Available Events</span>
                    </a>
                </div>

            @else
                <!-- Registrations List -->
                <div class="grid grid-cols-1 gap-5 sm:gap-6">
                    @foreach($registrations as $registration)
                        @php $event = $registration->event; @endphp
                        <div class="registration-card">
                            <div class="p-4 sm:p-6">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2 mb-3">
                                            <span class="type-badge">{{ $event->type }}</span>
                                            <span class="status-badge {{ $registration->status === 'registered' ? 'registered' : ($registration->status === 'cancelled' ? 'cancelled' : 'ended') }}">
                                                <i class="fas fa-{{ $registration->status === 'registered' ? 'check-circle' : ($registration->status === 'cancelled' ? 'times-circle' : 'clock') }} text-[8px]"></i>
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </div>

                                        <h3 class="text-lg font-bold text-[#2c2420] mb-3 leading-tight">{{ $event->title }}</h3>

                                        <div class="space-y-1.5 mb-4">
                                            <div class="registration-meta">
                                                <i class="far fa-calendar"></i>
                                                <span>{{ $event->date_range }}</span>
                                            </div>
                                            <div class="registration-meta">
                                                <i class="far fa-clock"></i>
                                                <span>{{ $event->time_range }}</span>
                                            </div>
                                            <div class="registration-meta">
                                                <i class="far fa-map-marker-alt"></i>
                                                <span class="line-clamp-1">{{ $event->location }}</span>
                                            </div>
                                            <div class="registration-meta">
                                                <i class="far fa-calendar-check" style="color:var(--gold-500)"></i>
                                                <span>Registered: {{ $registration->registered_at->format('M j, Y g:i A') }}</span>
                                            </div>
                                        </div>

                                        <p class="text-[#6b5e57] text-[0.8rem] leading-relaxed">{{ $event->description }}</p>
                                    </div>

                                    <div class="md:ml-6 flex-shrink-0">
                                        @if($registration->status === 'registered' && $event->is_upcoming)
                                            <form action="{{ route('student.events.cancel', $event) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="action-btn cancel px-4 py-2.5 text-xs sm:text-sm"
                                                        onclick="return confirm('Are you sure you want to cancel your registration for this event?')">
                                                    <i class="fas fa-times-circle mr-1.5 text-[9px] sm:text-xs"></i>
                                                    <span>Cancel Registration</span>
                                                </button>
                                            </form>
                                        @elseif(!$event->is_upcoming)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-[#f3f4f6] text-[#6b7280] text-[0.75rem] font-medium border border-[#d1d5db]">
                                                <i class="fas fa-history text-[9px]"></i>
                                                Event ended
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @include('partials.footer')
</body>
</html>