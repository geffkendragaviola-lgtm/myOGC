<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mental Health Corner - Office of Guidance and Counseling</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
    :root {
        --primary-red: #9f1f24;
        --primary-red-dark: #7f171b;
        --primary-red-deep: #651114;
        --primary-red-rich: #b32028;

        --accent-gold: #d4af37;
        --accent-gold-soft: #e7c766;

        --bg-light: #f6efe8;
        --bg-soft: #fbf6f1;
        --bg-white: #fffdfa;

        --text-dark: #2f2522;
        --text-secondary: #766864;
        --text-muted: #a09490;

        --border-soft: #eadfd4;
        --danger-red: #dc3545;

        --shadow-soft: 0 12px 32px rgba(101, 17, 20, 0.08);
        --shadow-medium: 0 18px 42px rgba(101, 17, 20, 0.13);
        --shadow-strong: 0 24px 56px rgba(101, 17, 20, 0.18);

        --radius-lg: 28px;
        --radius-md: 20px;
        --radius-sm: 16px;
    }

    * {
        box-sizing: border-box;
    }

    body.mhc-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background:
            radial-gradient(circle at top left, rgba(159, 31, 36, 0.08), transparent 22%),
            radial-gradient(circle at top right, rgba(212, 175, 55, 0.08), transparent 18%),
            linear-gradient(180deg, #fbf6f1 0%, #f6efe8 100%);
        color: var(--text-dark);
    }

    .gold-text {
        color: #f0cd63;
    }

    .mhc-navbar {
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: linear-gradient(90deg, #5b0f0f, #8f1d1d, #a11f2f);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 24px rgba(91, 15, 15, 0.18);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .mhc-navbar.scrolled {
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

    .dropdown-panel {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        background: #fffdfb;
        box-shadow: 0 16px 40px rgba(91, 15, 15, 0.12);
        border-radius: 16px;
        padding: 0.5rem;
        width: 220px;
        z-index: 1001;
        border: 1px solid #e8ddd2;
    }

    .dropdown-link {
        display: block;
        padding: 0.75rem 0.9rem;
        border-radius: 12px;
        color: #2f2522;
        transition: all 0.2s ease;
    }

    .dropdown-link:hover {
        color: #8f1d1d;
        background: #f8f1e8;
    }

    .profile-dropdown-content {
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        background: #fffdfb;
        box-shadow: 0 16px 40px rgba(91, 15, 15, 0.12);
        border-radius: 16px;
        padding: 1rem;
        min-width: 220px;
        z-index: 1001;
        border: 1px solid #e8ddd2;
    }

    .mhc-page-header {
        position: relative;
        overflow: hidden;
        padding: 6.25rem 0 7.5rem;
        background:
            linear-gradient(135deg, rgba(95, 15, 18, 0.84), rgba(143, 29, 29, 0.74), rgba(179, 32, 40, 0.65)),
            url('https://images.unsplash.com/photo-1499209974431-2761385a0a28?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: inset 0 -60px 120px rgba(0, 0, 0, 0.14);
    }

    .mhc-page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 18% 20%, rgba(231, 199, 102, 0.16), transparent 18%),
            radial-gradient(circle at 82% 12%, rgba(255,255,255,0.08), transparent 18%),
            linear-gradient(180deg, rgba(255,255,255,0.03) 0%, rgba(0,0,0,0.08) 100%);
        pointer-events: none;
    }

    .mhc-page-header::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 130px;
        background: linear-gradient(180deg, rgba(246,239,232,0) 0%, rgba(246,239,232,1) 88%);
        pointer-events: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.72rem 1.1rem;
        border-radius: 999px;
        background: rgba(255, 248, 240, 0.14);
        border: 1px solid rgba(255, 240, 220, 0.22);
        color: white;
        backdrop-filter: blur(10px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.14);
        font-size: 0.88rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .hero-title {
        color: white;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
        text-shadow: 0 10px 28px rgba(0,0,0,0.2);
    }

    .hero-title-accent {
        color: #f3d991;
        font-family: Georgia, 'Times New Roman', serif;
        font-style: italic;
        font-weight: 700;
    }

    .hero-description {
        color: rgba(255,255,255,0.93);
        line-height: 1.85;
        max-width: 46rem;
        margin-left: auto;
        margin-right: auto;
        text-shadow: 0 4px 16px rgba(0,0,0,0.14);
    }

    .section-shell {
        position: relative;
    }

    .section-head {
        display: flex;
        align-items: center;
        gap: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .section-bar {
        width: 6px;
        height: 36px;
        border-radius: 999px;
        background: linear-gradient(to bottom, var(--accent-gold), var(--primary-red));
        flex-shrink: 0;
        box-shadow: 0 6px 14px rgba(212, 175, 55, 0.18);
    }

    .section-title {
        color: var(--text-dark);
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .section-subtitle {
        color: var(--text-secondary);
    }

    .section-link {
        color: var(--primary-red);
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .section-link:hover {
        color: var(--primary-red-deep);
    }

    .mhc-card {
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-soft);
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .mhc-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.22), transparent 20%);
        pointer-events: none;
    }

    .mhc-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
        border-color: rgba(159, 31, 36, 0.18);
    }

    .soft-panel {
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
    }

    .category-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0.08), rgba(47, 37, 34, 0.62));
    }

    .badge-soft {
        padding: 0.4rem 0.9rem;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        backdrop-filter: blur(6px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    }

    .badge-maroon {
        background: linear-gradient(135deg, var(--primary-red), var(--primary-red-dark));
        color: white;
    }

    .badge-gold {
        background: #fbf4ea;
        color: var(--primary-red);
        border: 1px solid #ead8bf;
    }

    .badge-green {
        background: #eefaf2;
        color: #166534;
        border: 1px solid #bfe5c8;
    }

    .badge-red {
        background: linear-gradient(135deg, #dc3545 0%, #b91c1c 100%);
        color: white;
    }

    .badge-gray {
        background: rgba(255, 253, 250, 0.95);
        color: var(--text-secondary);
        border: 1px solid var(--border-soft);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-red), var(--primary-red-dark));
        color: white;
        border-radius: 16px;
        padding: 0.9rem 1.35rem;
        font-weight: 700;
        box-shadow: 0 10px 22px rgba(159, 31, 36, 0.2);
        transition: all 0.25s ease;
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-red-rich), var(--primary-red-deep));
        transform: translateY(-1px);
        box-shadow: 0 14px 28px rgba(159, 31, 36, 0.25);
    }

    .btn-secondary {
        background: #fffdfa;
        color: var(--text-dark);
        border: 1px solid #d9cec3;
        border-radius: 16px;
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .btn-secondary:hover {
        background: #fbf4ea;
        color: var(--primary-red);
        border-color: #ead8bf;
        transform: translateY(-1px);
    }

    .faq-item {
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-bottom: 1rem;
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        border: 1px solid var(--border-soft);
        box-shadow: var(--shadow-soft);
        transition: all 0.25s ease;
    }

    .faq-item:hover {
        border-color: rgba(159, 31, 36, 0.15);
        box-shadow: var(--shadow-medium);
    }

    .faq-question {
        padding: 1.25rem 1.4rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        color: var(--text-dark);
        background: rgba(255, 253, 251, 0.78);
        transition: background 0.2s ease;
    }

    .faq-question:hover {
        background: rgba(159, 31, 36, 0.04);
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.4s ease;
        background: transparent;
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 0.96rem;
    }

    .faq-active .faq-answer {
        max-height: 500px;
        padding: 1.35rem 1.4rem 1.45rem;
        border-top: 1px solid var(--border-soft);
    }

    .profile-dropdown-content {
        position: absolute;
        right: 0;
        top: 100%;
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        box-shadow: var(--shadow-medium);
        border-radius: 18px;
        padding: 1rem;
        min-width: 240px;
        z-index: 1000;
        margin-top: 0.7rem;
        border: 1px solid var(--border-soft);
    }

    .footer-shell {
        background: linear-gradient(180deg, #4d1212 0%, #3f0e0e 100%);
        color: white;
        border-top: 1px solid rgba(255,255,255,0.06);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f3eee8;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-red);
        border-radius: 999px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary-red-dark);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.45s ease-out forwards;
    }

    @media (max-width: 768px) {
        .mhc-page-header {
            padding: 5rem 0 6rem;
        }

        .hero-title {
            font-size: 2.6rem;
            line-height: 1.05;
        }

        .section-head {
            align-items: flex-start;
        }

        .container { padding-left: 1rem !important; padding-right: 1rem !important; }
        .grid.lg\:grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
        .grid.md\:grid-cols-2 { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .mhc-page-header { padding: 4rem 0 5rem; }
        .hero-title { font-size: 2rem; }
        .container { padding-left: 0.85rem !important; padding-right: 0.85rem !important; }
        .grid.lg\:grid-cols-3,
        .grid.md\:grid-cols-2,
        .grid.grid-cols-2 { grid-template-columns: 1fr; }
        /* Navbar: hide center links on mobile */
        .hidden.md\:flex { display: none !important; }
        /* Card padding */
        .mhc-card { border-radius: 1rem; }
        .p-6 { padding: 1rem; }
        .p-8 { padding: 1.25rem; }
    }
</style>
</head>
<body class="mhc-container min-h-screen flex flex-col">

    <!-- Navbar -->@php $unreadNotifications = Auth::user()->unreadNotifications->take(5); $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
    <nav class="mhc-navbar py-4" id="mainNavbar">
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
                <a href="{{ route('dashboard') }}" class="nav-link">Home</a>

                @if(Auth::check() && Auth::user()->role === 'student')
                    <a href="{{ route('student.show', Auth::user()->student->id) }}" class="nav-link">Profile</a>
                    <div class="relative" id="services-dropdown">
                        <button class="nav-link flex items-center" id="services-dropdown-btn">
                            Services <i class="fas fa-chevron-down ml-1 text-sm"></i>
                        </button>
                        <div class="dropdown-panel hidden" id="services-dropdown-menu">
                            <a href="{{ route('bap') }}" class="dropdown-link">Book an Appointment</a>
                            <a href="{{ route('mhc') }}" class="dropdown-link" style="color:#8f1d1d;background:rgba(143,29,29,0.05);font-weight:600;">Mental Health Corner</a>
                        </div>
                    </div>
                    <a href="{{ route('feedback') }}" class="nav-link">Feedback</a>
                @endif
            </div>

            <!-- Right: Icons -->
            <div class="flex items-center space-x-4 justify-end">
                <div class="relative" id="notif-dropdown-wrapper">
                    <button id="notif-bell-btn" class="text-white p-2 rounded-full hover:bg-white/10 transition relative" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        @if($unreadCount > 0)
                            <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 leading-none">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <div id="notif-panel" class="hidden absolute right-0 top-[calc(100%+10px)] w-80 bg-white rounded-2xl shadow-xl border border-[#e8ddd2] z-[1002] overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-[#e8ddd2]">
                            <span class="font-semibold text-sm text-[#2f2522]">Notifications</span>
                            @if($unreadCount > 0)
                                <button id="mark-all-read-btn" class="text-xs text-[#8f1d1d] hover:underline font-medium">Mark all as read</button>
                            @endif
                        </div>
                        <div class="overflow-y-auto divide-y divide-[#e8ddd2]" id="notif-list">
                            @forelse($unreadNotifications as $notif)
                                <div class="notif-item flex items-start gap-3 px-4 py-3 hover:bg-[#f6f1ea] cursor-pointer bg-blue-50/40" data-id="{{ $notif->id }}">
                                    @php
                                        $nType = $notif->data['type'] ?? '';
                                        [$nIcon, $nBg] = match($nType) {
                                            'appointment_booked', 'appointment_booked_by_counselor' => ['fa-calendar-plus', '#2d7a4f'],
                                            'appointment_cancelled' => ['fa-calendar-xmark', '#b91c1c'],
                                            'appointment_rescheduled', 'reschedule_response' => ['fa-calendar-days', '#c2410c'],
                                            'appointment_referred', 'appointment_referred_to_counselor', 'referral_response' => ['fa-arrow-right-arrow-left', '#7a2a2a'],
                                            'appointment_status_changed' => ['fa-circle-check', '#2a5a7a'],
                                            'event_counselor_assigned', 'event_schedule_conflict', 'student_event_schedule_conflict' => ['fa-calendar-exclamation', '#92400e'],
                                            default => ['fa-bell', '#7a2a2a'],
                                        };
                                    @endphp
                                    <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center" style="background:{{ $nBg }}">
                                        <i class="fas {{ $nIcon }} text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-[#2f2522] truncate">{{ $notif->data['title'] ?? 'Notification' }}</p>
                                        <p class="text-xs text-[#766864] mt-0.5 line-clamp-2">{{ $notif->data['message'] ?? '' }}</p>
                                        <p class="text-[10px] text-[#a09490] mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-sm text-[#a09490]">
                                    <i class="fas fa-bell-slash text-2xl mb-2 block opacity-40"></i>
                                    No new notifications
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <button class="text-white p-2 rounded-full hover:bg-white/10 transition focus:outline-none" id="profileBtn">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="profile-dropdown-content hidden" id="profileMenu">
                        <div class="mb-3 border-b pb-2 border-[#e8ddd2]">
                            <div class="font-semibold text-[#2f2522]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                            <div class="text-sm text-[#766864]">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-[#8f1d1d] capitalize font-semibold mt-1">Role: {{ Auth::user()->role }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block py-2 text-[#2f2522] hover:text-[#8f1d1d] transition">
                            <i class="fas fa-circle-user mr-2"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t pt-2 mt-2 border-[#e8ddd2]">
                            @csrf
                            <button type="submit" class="w-full text-left block py-2 text-[#2f2522] hover:text-[#8f1d1d] transition">
                                <i class="fas fa-arrow-right-from-bracket mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Page Header -->
        <div class="mhc-page-header">
            <div class="container mx-auto px-6 text-center relative z-10">
                <div class="hero-badge mb-6">
                    <i class="fas fa-calendar-check text-[var(--accent-gold)]"></i>
                    <span>Connect, grow, and take care of you</span>
                </div>
                <h1 class="hero-title text-4xl md:text-7xl mb-6">
                    Student <span class="hero-title-accent">Events</span>
                </h1>
                <p class="hero-description text-lg md:text-xl">
                    Browse upcoming mental health events, workshops, and seminars designed for you. Register for sessions to learn new skills and find community support.
                </p>
            </div>
        </div>
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
        --student-accent: #fef3c7;
        --student-success: #d1fae5;
        --student-warning: #fef3c7;
        --student-error: #fee2e2;
    }

    .ogc-shell-inner {
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

    .hero-card, .panel-card, .glass-card, .event-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .event-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(92,26,26,0.08); }
    .hero-card::before, .panel-card::before, .glass-card::before, .event-card::before {
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
    .hero-card { min-height: 100px; }
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
    .action-btn.register {
        background: linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%);
        color: white; border: none;
    }
    .action-btn.register:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(122,42,42,0.2); }
    .action-btn.cancel {
        background: rgba(254,242,242,0.9); color: #b91c1c; border: 1px solid #fecaca;
    }
    .action-btn.cancel:hover { background: rgba(254,226,226,0.95); }
    .action-btn.details {
        background: rgba(254,249,231,0.9); color: var(--maroon-700); border: 1px solid rgba(212,175,55,0.3);
    }
    .action-btn.details:hover { background: rgba(254,243,199,0.95); }
    .action-btn:disabled {
        opacity: 0.6; cursor: not-allowed; transform: none !important;
    }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.95); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .select-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    /* Event Card Specific Styles */
    .event-image {
        position: relative; height: 18rem; overflow: hidden;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
    }
    .event-image img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.3s ease;
    }
    .event-card:hover .event-image img { transform: scale(1.08); }
    .event-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(44,36,32,0.7) 0%, transparent 60%);
    }
    .event-content {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 0.75rem 1rem; color: white;
    }
    .event-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.15rem 0.5rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 700; text-transform: capitalize;
    }
    .event-badge.type { background: rgba(212,175,55,0.9); color: var(--maroon-900); }
    .event-badge.required { background: rgba(220,38,38,0.9); color: white; }
    .event-badge.status { background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(4px); }
    .event-badge.college {
        position: absolute; top: 0.5rem; right: 0.5rem;
        background: rgba(16,185,129,0.9); color: white;
        padding: 0.2rem 0.6rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600; backdrop-filter: blur(4px);
    }
    .event-badge.date {
        position: absolute; top: 0.5rem; left: 0.5rem;
        background: rgba(212,175,55,0.95); color: var(--maroon-900);
        padding: 0.2rem 0.6rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 700; backdrop-filter: blur(4px);
    }

    .event-detail {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.75rem; color: var(--text-secondary);
    }
    .event-detail i { color: var(--maroon-700); width: 1rem; text-align: center; }

    .college-chip {
        display: inline-flex; align-items: center;
        padding: 0.15rem 0.5rem; border-radius: 999px;
        background: rgba(236,253,245,0.8); color: #065f46;
        font-size: 0.65rem; font-weight: 600; border: 1px solid rgba(16,185,129,0.2);
    }

    .empty-state, .profile-incomplete {
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
    .profile-incomplete {
        background: rgba(254,243,199,0.9); border-color: rgba(245,158,11,0.3);
    }
    .profile-incomplete .empty-state-icon {
        background: rgba(251,191,36,0.15); color: #b45309;
        border-color: #f59e0b;
    }

    .expand-details {
        border-top: 1px dashed var(--border-soft);
        padding-top: 1rem; margin-top: 1rem;
    }
    .expand-details .info-box {
        background: rgba(254,242,242,0.8); border: 1px solid rgba(239,68,68,0.2);
        border-radius: 0.6rem; padding: 0.75rem;
        font-size: 0.75rem; color: #991b1b;
    }
    .expand-details .info-box i { color: #ef4444; margin-right: 0.3rem; }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--maroon-700); font-size: 0.75rem; font-weight: 600;
        transition: all 0.18s ease;
    }
    .back-link:hover { color: var(--maroon-900); transform: translateX(-2px); }

    /* Responsive Utilities */
    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn, .action-btn { width: 100%; justify-content: center; padding: 0.7rem; font-size: 0.75rem; }
        .btn-row-mobile { flex-direction: column; gap: 0.5rem !important; }
        .hero-card, .summary-card { padding: 1rem !important; }
        .hero-icon { width: 2.25rem; height: 2.25rem; }
        .event-image { height: 8rem; }
        .event-content { padding: 0.6rem 0.8rem; }
        .event-badge { font-size: 0.6rem; padding: 0.12rem 0.4rem; }
        .event-badge.college, .event-badge.date { font-size: 0.6rem; padding: 0.15rem 0.5rem; }
        .event-detail { font-size: 0.7rem; }
        .college-chip { font-size: 0.6rem; padding: 0.12rem 0.4rem; }
        .filters-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 0.5rem; }
        .filters-scroll > div { display: flex; gap: 0.5rem; min-width: max-content; }
    }
</style><div class="container mx-auto px-6 py-10 -mt-14 relative z-20">@php
            $student = Auth::user()->student;

            if ($student) {
                // Base query for getting event types
                $baseQuery = \App\Models\Event::upcoming()
                    ->active()
                    ->forCollege($student->college_id)
                    ->forYearLevel($student->year_level);
                
                $eventTypes = (clone $baseQuery)->pluck('type')->unique()->sort();

                // Main query with relations
                $query = \App\Models\Event::with(['user', 'colleges', 'registrations' => function($q) use ($student) {
                        $q->where('student_id', $student->id);
                    }])
                    ->upcoming()
                    ->active()
                    ->forCollege($student->college_id)
                    ->forYearLevel($student->year_level);

                // Search
                if (request('search')) {
                    $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower(request('search')) . '%']);
                }

                // Type
                if (request('type')) {
                    $query->where('type', request('type'));
                }

                // Status
                if (request('status')) {
                    switch (request('status')) {
                        case 'required':
                            $query->where('is_required', true);
                            break;
                        case 'optional':
                            $query->where('is_required', false);
                            break;
                        case 'registered':
                            $query->whereHas('registrations', function($q) use ($student) {
                                $q->where('student_id', $student->id)->whereIn('status', ['registered', 'attended']);
                            });
                            break;
                        case 'available':
                            $query->where('is_required', false)
                                  ->whereDoesntHave('registrations', function($q) use ($student) {
                                      $q->where('student_id', $student->id)->whereIn('status', ['registered', 'attended']);
                                  });
                            break;
                    }
                }

                $events = $query->orderBy('is_pinned', 'desc')
                    ->orderBy('event_start_date')
                    ->orderBy('start_time')
                    ->paginate(12);

            } else {
                $events = collect();
                $eventTypes = collect();
            }
        @endphp
<!-- Filters Section -->
<form method="GET" action="{{ route('student.events.available') }}" id="filterForm" class="panel-card mb-5 sm:mb-6">
    <div class="panel-topline"></div>

    <div class="p-4 sm:p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="filters-scroll w-full">
                <div class="flex flex-wrap gap-3">
                    
                    <div class="min-w-[200px] flex-grow md:flex-grow-0">
                        <label class="field-label">Search</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="select-field w-full" style="padding-left: 2.75rem !important;">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="min-w-[140px]">
                        <label class="field-label">Event Type</label>
                        <select name="type" id="typeFilter" class="select-field" onchange="this.form.submit()">
                            <option value="">All Event Types</option>
                            @foreach($eventTypes as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-[160px]">
                        <label class="field-label">Status</label>
                        <select name="status" id="statusFilter" class="select-field" onchange="this.form.submit()">
                            <option value="">All Events</option>
                            <option value="required" {{ request('status') == 'required' ? 'selected' : '' }}>Required Events</option>
                            <option value="optional" {{ request('status') == 'optional' ? 'selected' : '' }}>Optional Events</option>
                            <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>My Registrations</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available to Register</option>
                        </select>
                    </div>
                    
                    @if(request()->anyFilled(['search', 'type', 'status']))
                        <div class="flex items-end">
                            <a href="{{ route('student.events.available') }}" 
                               class="secondary-btn h-[41px] px-4 flex items-center gap-2 text-red-600 hover:text-red-700 hover:bg-red-50 border-red-100 hover:border-red-200 transition-all duration-200 shadow-sm"
                               title="Clear all filters">
                                <i class="fas fa-rotate-left text-[10px]"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-[0.75rem] text-[#6b5e57] flex items-center gap-1.5 whitespace-nowrap mt-2 md:mt-0">
                <i class="fas fa-layer-group text-[#8b7e76]"></i>
                <span><strong style="color:var(--text-primary)">{{ $student ? $events->total() : 0 }}</strong> events found</span>
            </div>
        </div>
    </div>
</form>

        @if(!$student)
            <!-- Student Profile Not Complete -->
            <div class="glass-card profile-incomplete mb-6">
                <div class="empty-state-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#92400e] mb-2">Complete Your Student Profile</h3>
                <p class="text-[#b45309] text-sm mb-4 max-w-md mx-auto">
                    Please complete your student profile to view and register for mental health events.
                </p>
                <a href="{{ route('student.profile') }}"
                   class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                    <i class="fas fa-user-edit mr-1.5 text-[9px] sm:text-xs"></i>
                    <span>Complete Profile</span>
                </a>
            </div>

        @elseif($events->isEmpty())
            <!-- No Events Available -->
            <div class="glass-card empty-state mb-6">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Events Right Now</h3>
                <p class="text-[#6b5e57] text-sm mb-4 max-w-md mx-auto">
                    There are currently no upcoming events available for your college. Check back soon!
                </p>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#fef3c7] border border-[#f59e0b]/30">
                    <i class="fas fa-circle-info text-[#b45309] text-[9px]"></i>
                    <span class="text-[#92400e] text-[0.75rem]">New events will appear here when scheduled by your counselors.</span>
                </div>
            </div>

        @else
            <!-- Events Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6 mb-8" id="eventsGrid">
                @foreach($events as $event)
                    @php
                        $isRequiredEvent = $event->is_required && $event->isRequiredForStudent($student);
                        $isRegistered = $event->isRegisteredByStudent($student);
                        $hasAvailableSlots = $event->hasAvailableSlots();
                        $isUpcoming = $event->is_upcoming;
                        $registration = $event->getStudentRegistration($student);
                        $status = $registration ? $registration->status : 'not_registered';
                        $registrationDate = $registration ? $registration->registered_at : null;
                        $isAutoRegistered = $isRequiredEvent && $isRegistered;
                        $canCancel = $isRegistered && $isUpcoming && !$isRequiredEvent;
                        $canRegister = !$isRegistered && $hasAvailableSlots && !$isRequiredEvent;
                        $isEventFull = !$hasAvailableSlots && !$isRegistered;
                        $isRequiredAutoRegister = $isRequiredEvent && !$isRegistered;
                    @endphp

                    <div class="event-card group cursor-pointer flex flex-col h-full"
                         data-type="{{ $event->type }}"
                         data-required="{{ $isRequiredEvent ? 'true' : 'false' }}"
                         data-registered="{{ $isRegistered ? 'true' : 'false' }}"
                         data-available="{{ $canRegister ? 'true' : 'false' }}"
                         onclick="openEventModal({
                            title: {{ json_encode($event->title) }},
                            type: {{ json_encode(ucfirst($event->type)) }},
                            dateRange: {{ json_encode(\Carbon\Carbon::parse($event->event_start_date)->format('M d, Y') . ' - ' . \Carbon\Carbon::parse($event->event_end_date)->format('M d, Y')) }},
                            timeRange: {{ json_encode($event->time_range) }},
                            location: {{ json_encode($event->location) }},
                            description: {{ json_encode($event->description) }},
                            imageUrl: {{ json_encode($event->image_url) }},
                            maxAttendees: {{ json_encode($event->max_attendees) }},
                            registeredCount: {{ $event->registered_count ?? 0 }},
                            isRequired: {{ json_encode($isRequiredEvent) }},
                            isRegistered: {{ json_encode($isRegistered) }}
                        })">

                        <!-- Event Image Header -->
                        <div class="event-image">
                            <img src="{{ $event->image_url }}"
                                 alt="{{ $event->title }}"
                                 onerror="this.parentElement.style.background='linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%)'">

                            <div class="event-overlay"></div>

                            <!-- Content Overlay -->
                            <div class="event-content">
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    <span class="event-badge type">{{ $event->type }}</span>
                                    @if($isRequiredEvent)
                                        <span class="event-badge required">
                                            <i class="fas fa-circle-exclamation text-[8px]"></i> Required
                                        </span>
                                    @endif
                                    @if($status === 'registered')
                                        <span class="event-badge status">✓ Registered</span>
                                    @elseif($status === 'attended')
                                        <span class="event-badge status" style="background: rgba(16,185,129,0.9);">✓ Attended</span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold text-white line-clamp-2">{{ $event->title }}</h3>
                            </div>

                            <!-- College Badge -->
                            <span class="event-badge college">
                                @if($event->for_all_colleges)
                                    <i class="fas fa-globe text-[8px]"></i> All Colleges
                                @else
                                    <i class="fas fa-building-columns text-[8px]"></i> {{ $event->colleges->count() }} Colleges
                                @endif
                            </span>

                            <!-- Date Badge -->
                            <span class="event-badge date">
                                {{ \Carbon\Carbon::parse($event->event_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('M d') }}
                            </span>
                        </div>

                        <!-- Event Details -->
                        <div class="p-4 flex flex-col flex-grow">
                            <!-- Date and Time -->
                            <div class="space-y-1.5 mb-3">
                                <div class="event-detail">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $event->time_range }}</span>
                                </div>
                                <div class="event-detail">
                                    <i class="far fa-location-dot"></i>
                                    <span class="line-clamp-1">{{ $event->location }}</span>
                                </div>
                                @if($event->max_attendees)
                                    <div class="event-detail">
                                        <i class="far fa-users"></i>
                                        <span>{{ $event->registered_count }}/{{ $event->max_attendees }} registered</span>
                                    </div>
                                @endif
                                @if($registrationDate)
                                    <div class="event-detail">
                                        <i class="fas fa-calendar-days-check" style="color:#c9a227"></i>
                                        <span>Registered: {{ $registrationDate->format('M j, Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-[#6b5e57] text-[0.8rem] mb-3 line-clamp-2 leading-relaxed">
                                {{ Str::limit($event->description, 120) }}
                            </p>

                            <!-- Specific Colleges -->
                            @if(!$event->for_all_colleges && $event->colleges->isNotEmpty())
                                <div class="mb-3">
                                    <p class="text-[0.65rem] font-semibold text-[#6b5e57] mb-1.5">Available for:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($event->colleges->take(2) as $college)
                                            <span class="college-chip">{{ $college->name }}</span>
                                        @endforeach
                                        @if($event->colleges->count() > 2)
                                            <span class="college-chip">+{{ $event->colleges->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="mt-auto">
                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2 btn-row-mobile">
                                @if($status === 'attended')
                                    <button class="action-btn" disabled style="background:rgba(16,185,129,0.9);color:white;border:none">
                                        <i class="fas fa-circle-check text-[9px]"></i>
                                        <span class="hidden sm:inline">Attended</span>
                                    </button>
                                @elseif($isRegistered)
                                    @if($isRequiredEvent)
                                        <button class="action-btn" disabled title="Required events cannot be cancelled" style="background:rgba(209,213,219,0.8);color:#6b7280;border:1px solid #d1d5db">
                                            <i class="fas fa-lock text-[9px]"></i>
                                            <span class="hidden sm:inline">Auto Registered</span>
                                        </button>
                                    @else
                                        <form action="{{ route('student.events.cancel', $event) }}" method="POST" class="flex-1 min-w-[100px]" onclick="event.stopPropagation()">
                                            @csrf
                                            <button type="submit"
                                                    class="action-btn cancel w-full"
                                                    onclick="return confirm('Are you sure you want to cancel your registration for this event?')">
                                                <i class="fas fa-circle-xmark text-[9px]"></i>
                                                <span class="hidden sm:inline">Cancel</span>
                                            </button>
                                        </form>
                                    @endif
                                @elseif($isRequiredEvent)
                                    <span class="action-btn" style="background:linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%);color:white;border:none">
                                        <i class="fas fa-user-check text-[9px]"></i>
                                        <span class="hidden sm:inline">Required</span>
                                    </span>
                                @elseif($hasAvailableSlots)
                                    <form action="{{ route('student.events.register', $event) }}" method="POST" class="flex-1 min-w-[100px]" onclick="event.stopPropagation()">
                                        @csrf
                                        <button type="submit"
                                                class="action-btn register w-full"
                                                onclick="return confirm('Are you sure you want to register for this event?')">
                                            <i class="fas fa-calendar-plus text-[9px]"></i>
                                            <span class="hidden sm:inline">Register</span>
                                        </button>
                                    </form>
                                @else
                                    <button class="action-btn" disabled style="background:rgba(209,213,219,0.8);color:#6b7280;border:1px solid #d1d5db">
                                        <i class="fas fa-calendar-xmark text-[9px]"></i>
                                        <span class="hidden sm:inline">Full</span>
                                    </button>
                                @endif

                                
                            </div>

                            <!-- Event Status and Created Info -->
                            <div class="mt-3 pt-3 border-t border-[#e5e0db]/60">
                                <div class="flex justify-between items-center">
                                    <div class="text-[0.7rem] text-[#8b7e76]">
                                        <i class="far fa-user mr-1"></i>
                                        {{ $event->user->first_name }} {{ $event->user->last_name }}
                                    </div>
                                    <div class="text-[0.7rem]">
                                        @if($isUpcoming)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#fef3c7] text-[#92400e] border border-[#f59e0b]/30">
                                                <i class="fas fa-clock text-[8px]"></i> Upcoming
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#f3f4f6] text-[#6b7280] border border-[#d1d5db]">
                                                <i class="fas fa-clock-rotate-left text-[8px]"></i> Past
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            </div> <!-- /mt-auto -->

                            </div>
                    </div>
                @endforeach
            </div>
            
            @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator && $events->hasPages())
                <div class="mt-8 flex justify-center">
                    <div class="glass-card px-6 py-3 rounded-full inline-flex">
                        {{ $events->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

</div></main>﻿<!-- Event Details Modal -->
    <div id="eventModal"
         class="fixed inset-0 z-[2000] hidden items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300"
         style="background:rgba(47,37,34,0.65);backdrop-filter:blur(6px);">
        <div id="eventModalContent" class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-[24px] shadow-2xl scale-95 translate-y-4 transition-all duration-300"
             style="background:linear-gradient(180deg,#fffdfa,#faf4ed);border:1px solid var(--border-soft);">

            <!-- Modal image header -->
            <div class="relative overflow-hidden rounded-t-[24px] bg-black group">
                <img id="modalImage" src="" alt="" class="w-full h-auto max-h-[60vh] object-cover block opacity-90 transition-opacity duration-500 group-hover:opacity-100">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>

                <!-- Close button -->
                <button onclick="closeEventModal()"
                        class="absolute top-4 right-4 w-9 h-9 rounded-full flex items-center justify-center text-white transition hover:bg-black/50 hover:scale-105"
                        style="background:rgba(0,0,0,0.35);backdrop-filter:blur(6px);">
                    <i class="fas fa-xmark text-sm"></i>
                </button>

                <!-- Type badge -->
                <div class="absolute top-4 left-4">
                    <span id="modalType" class="badge-soft badge-maroon capitalize text-xs shadow-lg"></span>
                </div>

                <!-- Required badge -->
                <div id="modalRequiredBadge" class="absolute top-4 left-4 mt-8 hidden">
                    <span class="badge-soft badge-red text-xs shadow-lg"><i class="fas fa-star mr-1"></i> Required</span>
                </div>

                <div class="absolute bottom-0 left-0 p-6 sm:p-8 w-full">
                    <h2 id="modalTitle" class="text-3xl sm:text-4xl font-extrabold text-white mb-2 leading-tight drop-shadow-md"></h2>
                </div>
            </div>

            <!-- Modal body -->
            <div class="p-6 sm:p-8">
                <!-- Meta row -->
                <div class="flex flex-wrap gap-5 mb-6 text-sm" style="color:var(--text-secondary);">
                    <div class="flex items-center gap-2 bg-white/60 px-3 py-1.5 rounded-lg border border-[#e8ddd2]">
                        <i class="fas fa-calendar-days text-[var(--accent-gold)]"></i>
                        <span id="modalDate" class="font-medium text-[var(--text-dark)]"></span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/60 px-3 py-1.5 rounded-lg border border-[#e8ddd2]">
                        <i class="fas fa-clock text-[var(--accent-gold)]"></i>
                        <span id="modalTime" class="font-medium text-[var(--text-dark)]"></span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/60 px-3 py-1.5 rounded-lg border border-[#e8ddd2]">
                        <i class="fas fa-location-dot text-[var(--primary-red)]"></i>
                        <span id="modalLocation" class="font-medium text-[var(--text-dark)]"></span>
                    </div>
                    <div id="modalSlotsWrap" class="flex items-center gap-2 bg-white/60 px-3 py-1.5 rounded-lg border border-[#e8ddd2]">
                        <i class="fas fa-users" style="color:var(--text-muted)"></i>
                        <span id="modalSlots" class="font-medium text-[var(--text-dark)]"></span>
                    </div>
                </div>

                <!-- Divider -->
                <hr style="border-color:var(--border-soft);margin-bottom:1.5rem;">

                <!-- Description -->
                <div class="prose prose-sm max-w-none text-[#5c504a] leading-relaxed">
                    <p id="modalDescription" class="whitespace-pre-wrap"></p>
                </div>

                <!-- Required note -->
                <div id="modalRequiredNote" class="hidden mt-6 p-4 rounded-2xl text-sm border flex items-start gap-3" style="background:#fff5f5;border-color:#fecdd3;color:#9f1239;">
                    <i class="fas fa-circle-exclamation mt-0.5 text-lg"></i>
                    <div>
                        <strong>Mandatory Event</strong>
                        <p class="mt-0.5 opacity-90">This event is required for your college. Your attendance is expected.</p>
                    </div>
                </div>

                <!-- Registered note -->
                <div id="modalRegisteredNote" class="hidden mt-6 p-4 rounded-2xl text-sm border flex items-start gap-3"
                     style="background:#eefaf2;border-color:#bfe5c8;color:#166534;">
                    <i class="fas fa-circle-check mt-0.5 text-lg"></i>
                    <div>
                        <strong>You're Registered!</strong>
                        <p class="mt-0.5 opacity-90">Your spot is secured. Check your email or notifications for updates.</p>
                    </div>
                </div>

                <!-- Close action -->
                <div class="mt-8 flex justify-end pt-5 border-t border-[#e8ddd2]">
                    <button onclick="closeEventModal()"
                            class="btn-secondary px-8 py-2.5 rounded-[14px] text-sm font-bold shadow-sm hover:shadow-md transition-all">
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer style="background:linear-gradient(to right,#5b0f0f,#7b1717,#8f1d1d);color:white;" class="py-4 mt-auto">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[#f3e8df]">&copy; {{ date('Y') }} Office of Guidance and Counseling. All rights reserved.</p>
            <p class="text-sm text-[#e5caa9] mt-2">Committed to student support, wellness, and accessible guidance services</p>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });

        // Profile dropdown
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');
        const notifBellBtn = document.getElementById('notif-bell-btn');
        const notifPanel = document.getElementById('notif-panel');

        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
                notifPanel?.classList.add('hidden');
            });
            document.addEventListener('click', () => profileMenu.classList.add('hidden'));
            profileMenu.addEventListener('click', (e) => e.stopPropagation());
        }

        if (notifBellBtn && notifPanel) {
            notifBellBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifPanel.classList.toggle('hidden');
                profileMenu?.classList.add('hidden');
            });
            document.addEventListener('click', () => notifPanel.classList.add('hidden'));
            notifPanel.addEventListener('click', (e) => e.stopPropagation());
        }

        // Mark all read
        const markAllBtn = document.getElementById('mark-all-read-btn');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', () => {
                fetch('/notifications/mark-all-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                    .then(() => { document.getElementById('notif-badge')?.remove(); document.getElementById('notif-list').innerHTML = '<div class="px-4 py-8 text-center text-sm text-[#a09490]"><i class="fas fa-bell-slash text-2xl mb-2 block opacity-40"></i>No new notifications</div>'; markAllBtn.remove(); });
            });
        }

        document.querySelectorAll('.notif-item').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch(`/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                    .then(() => { this.remove(); });
            });
        });

        // Services dropdown
        const servicesBtn = document.getElementById('services-dropdown-btn');
        const servicesMenu = document.getElementById('services-dropdown-menu');
        if (servicesBtn && servicesMenu) {
            servicesBtn.addEventListener('click', (e) => { e.stopPropagation(); servicesMenu.classList.toggle('hidden'); });
            document.addEventListener('click', () => servicesMenu.classList.add('hidden'));
            servicesMenu.addEventListener('click', (e) => e.stopPropagation());
        }

        
    



        // FAQ Toggle
        function toggleFaq(id) {
            const faqItem = document.getElementById('faq-' + id);
            const answer = faqItem.querySelector('.faq-answer');
            const icon = faqItem.querySelector('.faq-question i');

            // Close others (optional, remove if you want multiple open)
            document.querySelectorAll('.faq-item').forEach(item => {
                if(item.id !== 'faq-' + id) {
                    item.classList.remove('faq-active');
                    item.querySelector('.faq-answer').style.maxHeight = null;
                    item.querySelector('i').style.transform = 'rotate(0deg)';
                }
            });

            faqItem.classList.toggle('faq-active');

            if (faqItem.classList.contains('faq-active')) {
                answer.style.maxHeight = answer.scrollHeight + "px";
                icon.style.transform = 'rotate(180deg)';
            } else {
                answer.style.maxHeight = null;
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Initialize first FAQ
        document.addEventListener('DOMContentLoaded', () => {
            const firstFaq = document.querySelector('.faq-item');
            if(firstFaq) toggleFaq(firstFaq.id.split('-')[1]);
        });

        // Event Details Modal
                function openEventModal(data) {
            document.getElementById('modalImage').src       = data.imageUrl || '';
            document.getElementById('modalImage').style.display = data.imageUrl ? 'block' : 'none';
            document.getElementById('modalImage').alt       = data.title;
            document.getElementById('modalTitle').textContent      = data.title;
            document.getElementById('modalType').textContent       = data.type;
            document.getElementById('modalDate').textContent       = data.dateRange;
            document.getElementById('modalTime').textContent       = data.timeRange;
            document.getElementById('modalLocation').textContent   = data.location;
            document.getElementById('modalDescription').textContent = data.description;

            // Slots
            const slotsWrap = document.getElementById('modalSlotsWrap');
            if (data.maxAttendees) {
                document.getElementById('modalSlots').textContent = data.registeredCount + '/' + data.maxAttendees + ' spots filled';
                slotsWrap.classList.remove('hidden');
                slotsWrap.classList.add('flex');
            } else {
                slotsWrap.classList.add('hidden');
                slotsWrap.classList.remove('flex');
            }

            // Required badge & note
            const reqBadge = document.getElementById('modalRequiredBadge');
            const reqNote  = document.getElementById('modalRequiredNote');
            if (data.isRequired) {
                reqBadge.classList.remove('hidden');
                reqNote.classList.remove('hidden');
                reqNote.classList.add('flex');
            } else {
                reqBadge.classList.add('hidden');
                reqNote.classList.add('hidden');
                reqNote.classList.remove('flex');
            }

            // Registered note
            const regNote = document.getElementById('modalRegisteredNote');
            if (data.isRegistered) {
                regNote.classList.remove('hidden');
                regNote.classList.add('flex');
            } else {
                regNote.classList.add('hidden');
                regNote.classList.remove('flex');
            }

            // Show modal with animation
            const modal = document.getElementById('eventModal');
            const modalContent = document.getElementById('eventModalContent');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Small delay to allow display:flex to apply before animating opacity
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                if (modalContent) {
                    modalContent.classList.remove('scale-95', 'translate-y-4');
                }
            }, 10);
            
            document.body.style.overflow = 'hidden';
        }

        function closeEventModal() {
            const modal = document.getElementById('eventModal');
            const modalContent = document.getElementById('eventModalContent');
            
            modal.classList.add('opacity-0');
            if (modalContent) {
                modalContent.classList.add('scale-95', 'translate-y-4');
            }
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }

        // Close on backdrop click
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) closeEventModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeEventModal();
        });
    </script>
</body>
</html>
