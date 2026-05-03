<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Office of Guidance and Counseling</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-red: #8f1d1d;
            --primary-red-dark: #6f1414;
            --primary-red-deep: #5b0f0f;
            --accent-gold: #d4af37;
            --accent-gold-soft: #e7c766;
            --bg-light: #f6f1ea;
            --bg-soft: #fbf7f2;
            --bg-white: #fffdfb;
            --text-dark: #2f2522;
            --text-secondary: #766864;
            --text-muted: #a09490;
            --border-soft: #e8ddd2;
            --shadow-soft: 0 10px 30px rgba(91, 15, 15, 0.08);
            --shadow-medium: 0 16px 40px rgba(91, 15, 15, 0.12);
            --shadow-strong: 0 18px 46px rgba(91, 15, 15, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            min-height: 100vh;
        }

        .dashboard-navbar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(90deg, var(--primary-red-deep), var(--primary-red), #a11f2f);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(91, 15, 15, 0.18);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .dashboard-navbar.scrolled {
            box-shadow: 0 12px 28px rgba(91, 15, 15, 0.24);
        }

        .nav-link {
            color: white;
            font-weight: 600;
            transition: 0.25s ease;
        }

        .nav-link:hover {
            color: rgba(255, 245, 235, 0.88);
        }

        .dashboard-profile-dropdown {
            position: relative;
        }

        .dashboard-profile-dropdown-content {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            background: var(--bg-white);
            box-shadow: var(--shadow-medium);
            border-radius: 16px;
            padding: 1rem;
            min-width: 220px;
            z-index: 1001;
            border: 1px solid var(--border-soft);
        }

        .dropdown-panel {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            background: var(--bg-white);
            box-shadow: var(--shadow-medium);
            border-radius: 16px;
            padding: 0.5rem;
            width: 220px;
            z-index: 1001;
            border: 1px solid var(--border-soft);
        }

        .dropdown-link {
            display: block;
            padding: 0.75rem 0.9rem;
            border-radius: 12px;
            color: var(--text-dark);
            transition: all 0.2s ease;
        }

        .dropdown-link:hover {
            color: var(--primary-red);
            background: #f8f1e8;
        }

        .dashboard-hero {
            position: relative;
            overflow: hidden;
            padding: 5.5rem 0 4.5rem;
            padding-top: calc(5.5rem + 4.5rem);
            background:
                linear-gradient(135deg, rgba(91, 15, 15, 0.72), rgba(143, 29, 29, 0.58)),
                url('{{ asset('images/dashboard-header.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .dashboard-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(255,255,255,0.12), transparent 24%),
                linear-gradient(to bottom, rgba(255,255,255,0.03), rgba(0,0,0,0.18));
            pointer-events: none;
        }

        .dashboard-hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.85fr;
            gap: 2rem;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 248, 240, 0.12);
            color: white;
            border: 1px solid rgba(255, 240, 220, 0.18);
            border-radius: 999px;
            padding: 0.55rem 1rem;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 1rem;
            backdrop-filter: blur(8px);
        }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 4.15rem);
            line-height: 1.03;
            font-weight: 800;
            color: white;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
            text-shadow: 0 8px 24px rgba(0,0,0,0.18);
        }

        .hero-description {
            font-size: 1.03rem;
            line-height: 1.8;
            color: rgba(255,255,255,0.92);
            max-width: 700px;
        }

        .hero-chip-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1rem;
            border-radius: 999px;
            background: rgba(255, 248, 240, 0.12);
            border: 1px solid rgba(255, 240, 220, 0.18);
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .hero-side-card {
            background: rgba(255, 248, 240, 0.12);
            border: 1px solid rgba(255, 240, 220, 0.18);
            border-radius: 24px;
            padding: 1.4rem;
            box-shadow: 0 20px 45px rgba(91, 15, 15, 0.18);
            backdrop-filter: blur(12px);
        }

        .hero-quote {
            color: white;
            font-size: 1rem;
            line-height: 1.85;
            font-style: italic;
        }

        .hero-quote-author {
            margin-top: 1rem;
            color: #f3d991;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.85rem;
            margin-top: 1.25rem;
        }

        .hero-stat {
            background: rgba(255, 248, 240, 0.12);
            border: 1px solid rgba(255, 240, 220, 0.18);
            border-radius: 18px;
            padding: 1rem;
        }

        .hero-stat-label {
            color: rgba(255, 245, 235, 0.72);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.35rem;
        }

        .hero-stat-value {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            line-height: 1.3;
        }

        .section-card {
            background: linear-gradient(180deg, #fffdfb, #faf6f0);
            border: 1px solid var(--border-soft);
            border-radius: 20px;
            box-shadow: var(--shadow-soft);
        }

        .section-title-row {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-bar {
            width: 6px;
            height: 34px;
            border-radius: 999px;
            background: linear-gradient(to bottom, var(--accent-gold), var(--primary-red));
            margin-right: 0.85rem;
        }

        .section-icon {
            color: var(--primary-red);
            margin-right: 0.75rem;
        }

        .btn-brand {
            background: linear-gradient(135deg, var(--primary-red), var(--primary-red-dark));
            color: white;
            transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(143, 29, 29, 0.18);
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, #a32121, var(--primary-red-deep));
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(143, 29, 29, 0.24);
        }

        .outline-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            border: 1px solid #ead8bf;
            background: #fbf4ea;
            color: var(--primary-red);
        }

        .announcement-image-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 16px;
            background: #f8f4ee;
            border: 1px solid var(--border-soft);
        }

        .announcement-image {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            display: block;
        }

        .dashboard-announcements-container {
            position: relative;
            min-height: 300px;
        }

        .dashboard-announcement-item {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            transition: opacity 0.45s ease;
            pointer-events: none;
        }

        .dashboard-announcement-active {
            opacity: 1;
            pointer-events: all;
            position: relative;
        }

        .service-card-custom,
        .dashboard-staff-card {
            background: linear-gradient(180deg, #fffdfb, #faf6f0);
            border: 1px solid var(--border-soft);
            border-radius: 18px;
            box-shadow: var(--shadow-soft);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card-custom:hover,
        .dashboard-staff-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
            border-color: rgba(143, 29, 29, 0.16);
        }

        .dashboard-divider {
            height: 1px;
            background: linear-gradient(
                to right,
                transparent,
                rgba(212, 175, 55, 0.55),
                rgba(143, 29, 29, 0.22),
                transparent
            );
            margin: 3rem 0;
        }

        .soft-ring {
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.12);
        }

        footer.dashboard-footer {
            background: linear-gradient(to right, #5b0f0f, #7b1717, #8f1d1d);
            color: white;
        }

        @media (max-width: 1024px) {
            .hero-grid {
                grid-template-columns: 1fr;
            }

            .announcement-image {
                max-height: 380px;
            }
        }

        @media (max-width: 768px) {
            .announcement-image {
                max-height: 300px;
            }

            .dashboard-hero {
                padding-top: calc(4.5rem + 4rem);
                padding-bottom: 4rem;
            }
        }

        @media (max-width: 640px) {
            .announcement-image {
                max-height: 240px;
            }

            .dashboard-navbar {
                padding: 0.75rem 0;
            }

            .hero-stats {
                grid-template-columns: 1fr;
            }

            .container { padding-left: 0.85rem !important; padding-right: 0.85rem !important; }
            .grid.grid-cols-2 { grid-template-columns: 1fr; }
            .grid.grid-cols-3 { grid-template-columns: 1fr; }
            .grid.sm\:grid-cols-2 { grid-template-columns: 1fr; }
            .grid.md\:grid-cols-2 { grid-template-columns: 1fr; }
            .grid.lg\:grid-cols-3 { grid-template-columns: 1fr; }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1ece5;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-red);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-red-dark);
        }
    </style>
</head>
<body class="bg-[var(--bg-light)]">
    <div class="dashboard-container">
        <nav class="dashboard-navbar py-4" id="mainNavbar">
            <div class="container mx-auto px-6 flex items-center" style="display:grid;grid-template-columns:1fr auto 1fr;align-items:center;">
                <!-- Left: Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline" style="text-decoration:none;">
                        <div style="width:2.6rem;height:2.6rem;border-radius:0.9rem;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.10);display:flex;align-items:center;justify-content:center;box-shadow:inset 0 1px 0 rgba(255,255,255,0.12);flex-shrink:0;">
                            <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
                        </div>
                        <span class="text-white font-bold text-sm hidden md:block" style="line-height:1.1;letter-spacing:0.01em;">
                            my.OGC<br>
                            <span class="font-medium text-xs" style="color:#d4af37;">MSU-IIT Office of Guidance & Counseling</span>
                        </span>
                    </a>
                </div>

                <!-- Center: Nav Links -->
                <div class="hidden md:flex items-center space-x-8">
                        @if(Auth::user()->role === 'counselor')
                        <div class="relative" id="counselor-dropdown">
                            <button class="nav-link flex items-center" id="counselor-dropdown-btn">
                                Counselor
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>

                            <div class="dropdown-panel hidden" id="counselor-dropdown-menu">
                                <a href="{{ route('counselor.dashboard') }}" class="dropdown-link">
                                    <i class="fas fa-gauge-high mr-2"></i> Dashboard
                                </a>
                                <a href="{{ route('counselor.resources.index') }}" class="dropdown-link">
                                    <i class="fas fa-folder-open mr-2"></i> Resources
                                </a>
                                <a href="{{ route('counselor.announcements.index') }}" class="dropdown-link">
                                    <i class="fas fa-bullhorn mr-2"></i> Manage Announcements
                                </a>
                                <a href="{{ route('counselor.events.index') }}" class="dropdown-link">
                                    <i class="fas fa-calendar-days mr-2"></i> Manage Events
                                </a>
                                <a href="{{ route('counselor.calendar') }}" class="dropdown-link">
                                    <i class="fas fa-calendar mr-2"></i> Calendar
                                </a>
                                <a href="{{ route('counselor.appointments') }}" class="dropdown-link">
                                    <i class="fas fa-list mr-2"></i> Appointments
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(Auth::user()->role === 'admin')
                        <div class="relative" id="admin-dropdown">
                            <button class="nav-link flex items-center" id="admin-dropdown-btn">
                                Admin
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>

                            <div class="dropdown-panel hidden" id="admin-dropdown-menu">
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-link">
                                    <i class="fas fa-gauge-high mr-2"></i> Dashboard
                                </a>
                                <a href="{{ route('admin.events') }}" class="dropdown-link">
                                    <i class="fas fa-calendar-days mr-2"></i> Manage Events
                                </a>
                                <a href="{{ route('admin.students') }}" class="dropdown-link">
                                    <i class="fas fa-user-graduate mr-2"></i> Students
                                </a>
                                <a href="{{ route('admin.counselors') }}" class="dropdown-link">
                                    <i class="fas fa-user-doctor mr-2"></i> Counselors
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(Auth::user()->role === 'student')
                        <div class="relative" id="student-dropdown">
                            <a href="{{ route('student.show', Auth::user()->student->id) }}" class="nav-link flex items-center">
                                Profile
                            </a>
                        </div>
                        @endif

                        <a href="#" class="nav-link">Home</a>

                        @if(Auth::user()->role === 'student')
                            <div class="relative" id="services-dropdown">
                                <button class="nav-link flex items-center" id="services-dropdown-btn">
                                    Services <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div class="dropdown-panel hidden" id="services-dropdown-menu">
                                    <a href="{{ route('bap') }}" class="dropdown-link">Book an Appointment</a>
                                    <a href="{{ route('mhc') }}" class="dropdown-link">Mental Health Corner</a>
                                </div>
                            </div>

                            <a href="{{ route('feedback') }}" class="nav-link">Feedback</a>
                        @endif
                    </div>

                <!-- Right: Icons -->
                <div class="flex items-center space-x-4 justify-end">
                    @if(Auth::user()->role === 'counselor')
                        <a href="{{ route('counselor.appointments') }}"
                           class="btn-brand font-semibold py-2 px-4 rounded-lg flex items-center hover:shadow-lg">
                            <i class="fas fa-calendar-check mr-2"></i> My Appointment
                        </a>
                    @endif

                    {{-- Notification Bell --}}
                    @php
                        $unreadNotifications = Auth::user()->unreadNotifications->take(5);
                        $unreadCount = Auth::user()->unreadNotifications->count();
                    @endphp
                    <div class="relative" id="notif-dropdown-wrapper">
                        <button id="notif-bell-btn" class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition relative" aria-label="Notifications">
                            <i class="fas fa-bell"></i>
                            @if($unreadCount > 0)
                                <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 leading-none">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </button>
                        <div id="notif-panel" class="hidden absolute right-0 top-[calc(100%+10px)] w-80 bg-white rounded-2xl shadow-xl border border-[var(--border-soft)] z-[1002] overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-[var(--border-soft)]">
                                <span class="font-semibold text-sm text-[var(--text-dark)]">Notifications</span>
                                @if($unreadCount > 0)
                                    <button id="mark-all-read-btn" class="text-xs text-[var(--primary-red)] hover:underline font-medium">Mark all as read</button>
                                @endif
                            </div>
                            <div class="overflow-y-auto divide-y divide-[var(--border-soft)]" id="notif-list">
                                @forelse($unreadNotifications as $notif)
                                    <div class="notif-item flex items-start gap-3 px-4 py-3 hover:bg-[var(--bg-light)] cursor-pointer bg-blue-50/40" data-id="{{ $notif->id }}">
                                        @php
                                            $nType = $notif->data['type'] ?? '';
                                            [$nIcon, $nBg] = match($nType) {
                                                'appointment_booked', 'appointment_booked_by_counselor' => ['fa-calendar-plus', '#2d7a4f'],
                                                'appointment_cancelled'                                  => ['fa-calendar-xmark', '#b91c1c'],
                                                'appointment_rescheduled', 'reschedule_response'         => ['fa-calendar-days', '#c2410c'],
                                                'appointment_referred', 'appointment_referred_to_counselor', 'referral_response' => ['fa-arrow-right-arrow-left', '#7a2a2a'],
                                                'appointment_status_changed'                             => ['fa-circle-check', '#2a5a7a'],
                                                'event_counselor_assigned', 'event_schedule_conflict', 'student_event_schedule_conflict' => ['fa-calendar-exclamation', '#92400e'],
                                                default                                                  => ['fa-bell', '#7a2a2a'],
                                            };
                                        @endphp
                                        <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center" style="background:{{ $nBg }}">
                                            <i class="fas {{ $nIcon }} text-white text-xs"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-[var(--text-dark)] truncate">{{ $notif->data['title'] ?? 'Notification' }}</p>
                                            <p class="text-xs text-[var(--text-secondary)] mt-0.5 line-clamp-2">{{ $notif->data['message'] ?? '' }}</p>
                                            <p class="text-[10px] text-[var(--text-muted)] mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-8 text-center text-sm text-[var(--text-muted)]">
                                        <i class="fas fa-bell-slash text-2xl mb-2 block opacity-40"></i>
                                        No new notifications
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-profile-dropdown">
                        <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition" id="profile-dropdown-btn">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="dashboard-profile-dropdown-content hidden" id="profile-dropdown-menu">
                            <div class="mb-3 border-b pb-2 border-[var(--border-soft)]">
                                <div class="font-semibold text-[var(--text-dark)]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-[var(--text-secondary)]">{{ Auth::user()->email }}</div>
                                <div class="text-xs text-[var(--primary-red)] capitalize font-semibold">Role: {{ Auth::user()->role }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block py-2 text-[var(--text-dark)] hover:text-[var(--primary-red)]">
                                <i class="fas fa-circle-user mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full text-left block py-2 text-[var(--text-dark)] hover:text-[var(--primary-red)]">
                                    <i class="fas fa-arrow-right-from-bracket mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <header class="dashboard-hero">
            <div class="container mx-auto px-6 dashboard-hero-content">
                <div class="hero-grid">
                    <div>
                        <div class="hero-badge">
                            <i class="fas fa-hand-sparkles"></i>
                            Welcome back, {{ Auth::user()->first_name }}!
                        </div>

                        <h1 class="hero-title">
                            Office of Guidance<br>
                            and Counseling
                        </h1>

                        <p class="hero-description">
                            A trusted digital space for student support, guidance services, and campus well-being.
                            Built to keep announcements, appointments, and essential services clear, accessible,
                            and organized.
                        </p>

                        <div class="hero-chip-group">
                            <span class="hero-chip">
                                <i class="fas fa-bullhorn"></i> Announcements
                            </span>
                            <span class="hero-chip">
                                <i class="fas fa-calendar-check"></i> Appointments
                            </span>
                            <span class="hero-chip">
                                <i class="fas fa-hands-helping"></i> Student Services
                            </span>
                        </div>
                    </div>

                    <div class="hero-side-card">
                        <p class="hero-quote">
                            <i class="fas fa-quote-left mr-2"></i>
                            Make it a daily practice to purposefully look for joy — and when you find it,
                            take a moment, inhale it, treasure it, and take it with you.
                            <i class="fas fa-quote-right ml-2"></i>
                        </p>

                        <p class="hero-quote-author">Office of Guidance and Counseling</p>

                        <div class="hero-stats">
                            <div class="hero-stat">
                                <div class="hero-stat-label">Environment</div>
                                <div class="hero-stat-value">Supportive</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-label">Experience</div>
                                <div class="hero-stat-value">Student-Centered</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-label">Access</div>
                                <div class="hero-stat-value">Clear & Organized</div>
                            </div>
                            <div class="hero-stat">
                                <div class="hero-stat-label">Platform</div>
                                <div class="hero-stat-value">Modern Dashboard</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="min-h-screen bg-[var(--bg-light)]">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <section class="mb-12">
                    <div class="section-title-row">
                        <div class="section-bar"></div>
                        <h2 class="text-2xl font-bold text-[var(--text-dark)] flex items-center">
                            <i class="fas fa-bullhorn section-icon"></i>Announcements
                        </h2>
                    </div>

                    @php
                        use Carbon\Carbon;
                        $userCollegeId = Auth::user()->student->college_id ?? null;
                        $userYearLevel = Auth::user()->student->year_level ?? null;
                        $announcements = \App\Models\Announcement::with(['user', 'colleges'])
                            ->active()
                            ->when($userCollegeId, function($query) use ($userCollegeId) {
                                return $query->forCollege($userCollegeId);
                            })
                            ->when($userYearLevel, function($query) use ($userYearLevel) {
                                return $query->forYearLevel($userYearLevel);
                            })
                            ->orderBy('is_pinned', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get()
                            ->unique(function ($announcement) {
                                return strtolower(trim($announcement->title . '|' . $announcement->content));
                            })
                            ->values();
                    @endphp

                    <div class="section-card p-6">
                        @if($announcements->isEmpty())
                            <div class="text-center py-12">
                                <i class="fas fa-bullhorn text-5xl text-[var(--text-muted)] mb-4"></i>
                                <p class="text-[var(--text-secondary)] text-lg">No current announcements at this time.</p>
                            </div>
                        @else
                            <div class="dashboard-announcements-container">
                                @foreach($announcements as $index => $announcement)
                                    <div class="dashboard-announcement-item {{ $index === 0 ? 'dashboard-announcement-active' : '' }}">
                                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4 gap-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex flex-wrap gap-2">
                                                    @if($announcement->for_all_colleges)
                                                        <span class="bg-gradient-to-r from-[#8f1d1d] to-[#6f1414] text-white text-xs px-3 py-1 rounded-full font-medium">
                                                            <i class="fas fa-globe mr-1"></i> All Colleges
                                                        </span>
                                                    @else
                                                        @foreach($announcement->colleges->take(3) as $college)
                                                            <span class="bg-[#fbf4ea] text-[#8f1d1d] text-xs px-3 py-1 rounded-full font-medium border border-[#ead8bf]">
                                                                <i class="fas fa-building-columns mr-1"></i> {{ $college->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($announcement->colleges->count() > 3)
                                                            <span class="bg-[#fbf4ea] text-[#8f1d1d] text-xs px-3 py-1 rounded-full font-medium border border-[#ead8bf]">
                                                                +{{ $announcement->colleges->count() - 3 }} more
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col lg:flex-row gap-8">
                                            @if($announcement->image_url)
                                                <div class="lg:w-2/5">
                                                    <div class="announcement-image-container">
                                                        <img src="{{ $announcement->image_url }}"
                                                             alt="{{ $announcement->title }}"
                                                             class="announcement-image"
                                                             onerror="this.style.display='none';">
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="{{ $announcement->image_url ? 'lg:w-3/5' : 'w-full' }}">
                                                <h3 class="text-2xl font-bold text-[var(--text-dark)] mb-4">{{ $announcement->title }}</h3>

                                                <div class="text-[var(--text-secondary)] whitespace-pre-line leading-relaxed mb-6 text-lg">
                                                    {{ $announcement->content }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($announcements->count() > 1)
                                <div class="flex justify-between items-center mt-6 pt-4 border-t border-[var(--border-soft)]">
                                    <div class="text-sm text-[var(--text-secondary)] font-medium">
                                        <i class="fas fa-list mr-2 text-[var(--primary-red)]"></i>
                                        Announcement <span id="current-announcement" class="font-bold text-[var(--primary-red)]">1</span> of {{ $announcements->count() }}
                                    </div>

                                    <div class="flex space-x-2">
                                        <button class="dashboard-prev btn-brand p-3 rounded-full hover:shadow-lg transition">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button class="dashboard-next btn-brand p-3 rounded-full hover:shadow-lg transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </section>

                @php
                    $services = \App\Models\Service::active()
                        ->ordered()
                        ->get()
                        ->unique(function ($service) {
                            return strtolower(trim($service->title));
                        })
                        ->values();
                @endphp

                @if(in_array(Auth::user()->role, ['student', 'counselor', 'admin']))
                    @if($services->isNotEmpty())
                        <div class="mb-12">
                            <div class="section-title-row">
                                <div class="section-bar"></div>
                                <h3 class="text-2xl font-bold text-[var(--text-dark)] flex items-center">
                                    <i class="fas fa-hands-helping section-icon"></i>Our Services
                                </h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($services as $service)
                                    <div class="service-card-custom overflow-hidden group">
                                        @if($service->image_url)
                                            @php
                                                $img = $service->image_url;
                                                $imgSrc = $img && preg_match('/^https?:\/\//i', $img) ? $img : ($img ? asset('storage/' . $img) : null);
                                            @endphp
                                            <img src="{{ $imgSrc }}"
                                                 alt="{{ $service->title }}"
                                                 class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                                        @endif

                                        <div class="p-6">
                                            <h3 class="text-xl font-bold text-[var(--text-dark)] mb-3">
                                                {{ $service->title }}
                                            </h3>

                                            <p class="text-[var(--text-secondary)] mb-4 line-clamp-3">
                                                {{ $service->description }}
                                            </p>

                                            @if(Auth::user()->role === 'student')
                                                @if($service->route_name)
                                                    <a href="{{ route($service->route_name) }}"
                                                       class="inline-flex items-center px-4 py-2 btn-brand font-medium rounded-lg hover:shadow-md transition">
                                                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                                                    </a>
                                                @else
                                                    <button class="inline-flex items-center px-4 py-2 btn-brand font-medium rounded-lg hover:shadow-md transition">
                                                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif

                <div class="dashboard-divider"></div>

                <section class="mb-12">
                    <div class="section-title-row">
                        <div class="section-bar"></div>
                        <h2 class="text-2xl font-bold text-[var(--text-dark)] flex items-center">
                            <i class="fas fa-users section-icon"></i>Office of Guidance and Counseling
                        </h2>
                    </div>
                    <p class="text-[var(--text-secondary)] mb-6 ml-4">Our dedicated team is here to support you</p>

                    @php
                        $headCounselor = \App\Models\Counselor::with(['user', 'college'])
                            ->where('is_head', true)
                            ->first();
                    @endphp

                    @if($headCounselor)
                    <div class="section-card p-8 mb-8 flex flex-col md:flex-row items-center border-l-4 border-[#d4af37] hover:shadow-xl transition">
                        <div class="w-32 h-32 bg-gradient-to-br from-[#f8f1e8] to-[#efe3d4] rounded-full overflow-hidden mb-4 md:mb-0 md:mr-6 shadow-lg soft-ring">
                            @if($headCounselor->user->profile_picture)
                                <img src="{{ asset('storage/' . $headCounselor->user->profile_picture) }}" alt="Head Counselor" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-[#7a2a2a]">
                                    {{ strtoupper(substr($headCounselor->user->first_name, 0, 1)) }}{{ strtoupper(substr($headCounselor->user->last_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-[var(--text-dark)]">
                                {{ $headCounselor->user->first_name }}
                                {{ $headCounselor->user->middle_name ? $headCounselor->user->middle_name . ' ' : '' }}
                                {{ $headCounselor->user->last_name }}
                            </h3>
                            <p class="text-[var(--primary-red)] font-semibold text-lg">Head of the Office of Guidance and Counseling</p>
                            <p class="text-[var(--text-secondary)]">{{ $headCounselor->position }} • {{ $headCounselor->credentials }}</p>
                            @if($headCounselor->specialization)
                            <p class="text-[var(--text-secondary)] text-sm mt-1">{{ $headCounselor->specialization }}</p>
                            @endif
                            <p class="text-[var(--text-secondary)]">
                                <i class="fas fa-building-columns mr-2 text-[var(--accent-gold)]"></i>{{ $headCounselor->college->name ?? 'N/A' }}
                            </p>
                            <p class="text-[var(--text-secondary)] mt-2">
                                <i class="fas fa-envelope mr-2 text-[var(--accent-gold)]"></i>{{ $headCounselor->user->email }}
                            </p>
                            @if($headCounselor->user->phone_number)
                            <p class="text-[var(--text-secondary)] mt-1">
                                <i class="fas fa-phone mr-2 text-[var(--accent-gold)]"></i>{{ $headCounselor->user->phone_number }}
                            </p>
                            @endif
                            @if($headCounselor->facebook_link)
                            <p class="mt-1">
                                <a href="{{ $headCounselor->facebook_link }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 text-[#1877f2] hover:underline text-sm font-medium">
                                    <i class="fab fa-facebook text-base"></i> Facebook Profile
                                </a>
                            </p>
                            @endif
                            <p class="text-[var(--text-secondary)] mt-2 leading-relaxed">
                                As the Head Counselor, {{ $headCounselor->user->first_name }} provides leadership and direction
                                for all guidance and counseling services. With credentials in {{ $headCounselor->credentials }},
                                {{ $headCounselor->user->first_name }} ensures the office delivers comprehensive support to students.
                            </p>
                        </div>
                    </div>
                    @endif

                    @php
                        $counselors = \App\Models\Counselor::with(['user', 'college'])
                            ->where('is_head', false)
                            ->get();
                        $groupedCounselors = $counselors->groupBy('user_id');
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($groupedCounselors as $userCounselors)
                            @php
                                $counselor = $userCounselors->first();
                                $colleges = $userCounselors->pluck('college.name')->filter()->implode(', ');
                            @endphp
                            <div class="dashboard-staff-card p-6 text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-[#f8f1e8] to-[#efe3d4] rounded-full overflow-hidden mx-auto mb-4 soft-ring">
                                    @if($counselor->user->profile_picture)
                                        <img src="{{ asset('storage/' . $counselor->user->profile_picture) }}" alt="Counselor" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-2xl font-bold text-[#7a2a2a]">
                                            {{ strtoupper(substr($counselor->user->first_name, 0, 1)) }}{{ strtoupper(substr($counselor->user->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <h4 class="text-lg font-semibold text-[var(--text-dark)]">
                                    {{ $counselor->user->first_name }}
                                    {{ $counselor->user->middle_name ? substr($counselor->user->middle_name, 0, 1) . '. ' : '' }}
                                    {{ $counselor->user->last_name }}
                                </h4>
                                <p class="text-[var(--primary-red)] font-medium">{{ $counselor->position }}</p>
                                <p class="text-[var(--text-secondary)] text-sm mt-1">{{ $colleges }}</p>
                                <p class="text-[var(--text-muted)] text-sm mt-1">{{ $counselor->credentials }}</p>
                                @if($counselor->specialization)
                                <p class="text-[var(--text-secondary)] text-sm mt-1">{{ $counselor->specialization }}</p>
                                @endif
                                <div class="mt-3 space-y-1 text-sm text-center">
                                    <p class="text-[var(--text-secondary)]">
                                        <i class="fas fa-envelope mr-2 text-[var(--accent-gold)]"></i>{{ $counselor->user->email }}
                                    </p>
                                    @if($counselor->user->phone_number)
                                    <p class="text-[var(--text-secondary)]">
                                        <i class="fas fa-phone mr-2 text-[var(--accent-gold)]"></i>{{ $counselor->user->phone_number }}
                                    </p>
                                    @endif
                                    @if($counselor->facebook_link)
                                    <p>
                                        <a href="{{ $counselor->facebook_link }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center justify-center gap-1.5 text-[#1877f2] hover:underline font-medium">
                                            <i class="fab fa-facebook text-base"></i> Facebook Profile
                                        </a>
                                    </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        <footer class="dashboard-footer py-4 mt-4">
            <div class="container mx-auto px-6 text-center">
                <p class="text-[#f3e8df]">&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
                <p class="text-sm text-[#e5caa9] mt-2">Committed to student support, wellness, and accessible guidance services</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('mainNavbar');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 10) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }

            function setupDropdown(btnId, menuId) {
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);
                if (!btn || !menu) return;

                btn.addEventListener('click', function(e) {
                    e.stopPropagation();

                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });

                    const profileMenu = document.getElementById('profile-dropdown-menu');
                    if (profileMenu) profileMenu.classList.add('hidden');

                    menu.classList.toggle('hidden');
                });
            }

            setupDropdown('counselor-dropdown-btn', 'counselor-dropdown-menu');
            setupDropdown('admin-dropdown-btn', 'admin-dropdown-menu');
            setupDropdown('services-dropdown-btn', 'services-dropdown-menu');

            const profileBtn = document.getElementById('profile-dropdown-btn');
            const profileMenu = document.getElementById('profile-dropdown-menu');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(m => m.classList.add('hidden'));
                    profileMenu.classList.toggle('hidden');
                });
            }

            document.addEventListener('click', function(event) {
                if (!event.target.closest('[id$="-dropdown"]') && !event.target.closest('.dashboard-profile-dropdown')) {
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(menu => menu.classList.add('hidden'));
                    if (profileMenu) profileMenu.classList.add('hidden');
                }
            });

            document.querySelectorAll('[id$="-dropdown-menu"], .dashboard-profile-dropdown-content').forEach(menu => {
                menu.addEventListener('click', function(e) { e.stopPropagation(); });
            });

            const announcements = document.querySelectorAll('.dashboard-announcement-item');
            const prevBtn = document.querySelector('.dashboard-prev');
            const nextBtn = document.querySelector('.dashboard-next');
            const currentCounter = document.getElementById('current-announcement');
            let currentIndex = 0;

            if (announcements.length > 0 && prevBtn && nextBtn) {
                function showAnnouncement(index) {
                    announcements.forEach(ann => ann.classList.remove('dashboard-announcement-active'));
                    announcements[index].classList.add('dashboard-announcement-active');
                    if (currentCounter) currentCounter.textContent = index + 1;
                }

                prevBtn.addEventListener('click', function() {
                    currentIndex = (currentIndex - 1 + announcements.length) % announcements.length;
                    showAnnouncement(currentIndex);
                });

                nextBtn.addEventListener('click', function() {
                    currentIndex = (currentIndex + 1) % announcements.length;
                    showAnnouncement(currentIndex);
                });

                setInterval(() => {
                    if (announcements.length > 1) {
                        currentIndex = (currentIndex + 1) % announcements.length;
                        showAnnouncement(currentIndex);
                    }
                }, 8000);
            }

            document.querySelectorAll('.announcement-image').forEach(img => {
                img.addEventListener('error', function() { this.style.display = 'none'; });
            });

            // Notification bell
            const notifBtn   = document.getElementById('notif-bell-btn');
            const notifPanel = document.getElementById('notif-panel');
            const markAllBtn = document.getElementById('mark-all-read-btn');

            if (notifBtn && notifPanel) {
                notifBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(m => m.classList.add('hidden'));
                    if (profileMenu) profileMenu.classList.add('hidden');
                    notifPanel.classList.toggle('hidden');
                });

                notifPanel.addEventListener('click', e => e.stopPropagation());

                notifPanel.querySelectorAll('.notif-item').forEach(function (item) {
                    item.addEventListener('click', function () {
                        if (item.dataset.read === 'true') return;
                        const id = item.dataset.id;
                        fetch('/notifications/' + id + '/read', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                        }).then(function () {
                            item.dataset.read = 'true';
                            item.classList.remove('bg-blue-50/40');
                            item.style.opacity = '0.55';
                            updateNotifBadge(-1);
                        });
                    });
                });

                if (markAllBtn) {
                    markAllBtn.addEventListener('click', function () {
                        fetch('/notifications/read-all', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                        }).then(function () {
                            notifPanel.querySelectorAll('.notif-item').forEach(function (el) {
                                el.dataset.read = 'true';
                                el.classList.remove('bg-blue-50/40');
                                el.style.opacity = '0.55';
                            });
                            const badge = document.getElementById('notif-badge');
                            if (badge) badge.remove();
                            markAllBtn.style.display = 'none';
                        });
                    });
                }
            }

            function updateNotifBadge(delta) {
                const badge = document.getElementById('notif-badge');
                if (!badge) return;
                const next = (parseInt(badge.textContent) || 0) + delta;
                if (next <= 0) { badge.remove(); }
                else { badge.textContent = next > 99 ? '99+' : next; }
            }

            // Close notif panel on outside click
            document.addEventListener('click', function (event) {
                if (notifPanel && !event.target.closest('#notif-dropdown-wrapper')) {
                    notifPanel.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>