<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Feedback - Office of Guidance and Counseling</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --maroon-900: #3a0c0c; --maroon-800: #5c1a1a; --maroon-700: #7a2a2a;
            --gold-500: #c9a227; --gold-400: #d4af37;
            --bg-warm: #faf8f5; --border-soft: #e5e0db;
            --text-primary: #2c2420; --text-secondary: #6b5e57; --text-muted: #8b7e76;
        }
        * { box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: var(--bg-warm); color: var(--text-primary); }

        /* Navbar */
        .fb-nav {
            position: sticky; top: 0; z-index: 1000;
            background: linear-gradient(90deg, #5b0f0f, #8f1d1d, #a11f2f);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 8px 24px rgba(91,15,15,0.18);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .fb-nav.scrolled { box-shadow: 0 12px 28px rgba(91,15,15,0.24); }
        .nav-link {
            color: white; font-weight: 600; transition: 0.25s ease;
        }
        .nav-link:hover { color: rgba(255,245,235,0.88); }
        .dropdown-panel {
            position: absolute; top: calc(100% + 10px); left: 0;
            background: #fffdfb; box-shadow: 0 16px 40px rgba(91,15,15,0.12);
            border-radius: 16px; padding: 0.5rem; width: 220px; z-index: 1001;
            border: 1px solid #e8ddd2;
        }
        .dropdown-link {
            display: block; padding: 0.75rem 0.9rem; border-radius: 12px;
            color: #2f2522; transition: all 0.2s ease;
        }
        .dropdown-link:hover { color: #8f1d1d; background: #f8f1e8; }
        .profile-dropdown-content {
            position: absolute; right: 0; top: calc(100% + 10px);
            background: #fffdfb; box-shadow: 0 16px 40px rgba(91,15,15,0.12);
            border-radius: 16px; padding: 1rem; min-width: 220px; z-index: 1001;
            border: 1px solid #e8ddd2;
        }
        .btn-brand {
            background: linear-gradient(135deg, #8f1d1d, #6f1414);
            color: white; transition: all 0.25s ease;
            box-shadow: 0 8px 20px rgba(143,29,29,0.18);
        }
        .btn-brand:hover {
            background: linear-gradient(135deg, #a32121, #5b0f0f);
            transform: translateY(-1px); box-shadow: 0 12px 26px rgba(143,29,29,0.24);
        }

        /* Card */
        .fb-card {
            background: linear-gradient(180deg, #fffdfb, #faf6f0); border-radius: 0.75rem;
            border: 1px solid var(--border-soft);
            box-shadow: 0 2px 8px rgba(44,36,32,0.05);
            overflow: hidden;
        }
        .fb-card-topline {
            height: 3px;
            background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%);
        }

        /* Section */
        .fb-section { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(229,224,219,0.4); }
        .fb-section:last-child { border-bottom: none; }
        .fb-section-title {
            font-size: 0.78rem; font-weight: 700; color: var(--maroon-700);
            text-transform: uppercase; letter-spacing: 0.08em;
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 0.85rem; padding-bottom: 0.4rem;
            border-bottom: 2px solid rgba(212,175,55,0.2);
        }
        .fb-section-title i { font-size: 0.75rem; }

        /* Fields */
        .fb-label { display: block; font-size: 0.72rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.4rem; }
        .fb-select, .fb-textarea {
            width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
            background: #fffdfb; color: var(--text-primary);
            padding: 0.6rem 0.85rem; font-size: 0.85rem; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .fb-select:focus, .fb-textarea:focus {
            border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(122,42,42,0.06);
        }
        .fb-textarea { resize: vertical; min-height: 5rem; }

        /* Radio option rows */
        .radio-option {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.6rem 0.75rem; border-radius: 0.5rem; cursor: pointer;
            border: 1px solid transparent; transition: all 0.15s ease;
            font-size: 0.82rem; color: var(--text-secondary);
            background: rgba(250,248,245,0.3);
        }
        .radio-option:hover { background: rgba(254,249,231,0.5); border-color: rgba(212,175,55,0.15); }
        .radio-option input[type="radio"] { width: 1rem; height: 1rem; accent-color: var(--maroon-700); flex-shrink: 0; margin-top: 0.15rem; cursor: pointer; }
        .radio-option.selected { background: rgba(254,249,231,0.7); border-color: rgba(212,175,55,0.3); color: var(--text-primary); font-weight: 500; }

        /* Checkbox option */
        .check-option {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.75rem 1rem; border-radius: 0.6rem; cursor: pointer;
            border: 1px solid var(--border-soft); background: rgba(250,248,245,0.5);
            font-size: 0.82rem; color: var(--text-secondary); transition: all 0.15s ease;
        }
        .check-option:hover { border-color: rgba(212,175,55,0.3); background: rgba(254,249,231,0.4); }
        .check-option input[type="checkbox"] { width: 1rem; height: 1rem; accent-color: var(--maroon-700); flex-shrink: 0; margin-top: 0.15rem; cursor: pointer; }

        /* SQD Rating */
        .sqd-row {
            padding: 0.75rem 0; border-bottom: 1px solid rgba(229,224,219,0.25);
        }
        .sqd-row:last-child { border-bottom: none; padding-bottom: 0; }
        .sqd-question { font-size: 0.78rem; color: var(--text-primary); font-weight: 500; margin-bottom: 0.5rem; line-height: 1.35; }
        .sqd-scale { display: flex; gap: 0.35rem; }
        .sqd-scale label { flex: 1; cursor: pointer; }
        .sqd-scale input[type="radio"] { display: none; }
        .sqd-btn {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 0.4rem 0.2rem; border-radius: 0.5rem;
            border: 1.5px solid var(--border-soft); background: #fffdfb;
            transition: all 0.15s ease; text-align: center;
        }
        .sqd-btn:hover { border-color: rgba(212,175,55,0.4); background: rgba(254,249,231,0.5); }
        .sqd-scale input:checked + .sqd-btn {
            background: linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%);
            border-color: var(--maroon-700); color: white;
            box-shadow: 0 2px 6px rgba(92,26,26,0.2);
        }
        .sqd-num { font-size: 0.8rem; font-weight: 700; }
        .sqd-word { font-size: 0.52rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; margin-top: 0.15rem; opacity: 0.75; line-height: 1.1; }
        .sqd-scale input:checked + .sqd-btn .sqd-word { opacity: 0.9; }
        @media (max-width: 480px) {
            .sqd-word { display: none; }
            .sqd-num { font-size: 0.9rem; }
        }

        /* Step Wizard */
        .step-indicator {
            display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
            transition: all 0.3s ease;
        }
        .step-circle {
            width: 2.5rem; height: 2.5rem; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.9rem;
            background: rgba(229,224,219,0.5); color: var(--text-muted);
            border: 2px solid var(--border-soft);
            transition: all 0.3s ease;
        }
        .step-indicator.active .step-circle {
            background: linear-gradient(135deg, var(--maroon-700), var(--maroon-800));
            color: white; border-color: var(--maroon-700);
            box-shadow: 0 4px 12px rgba(122,42,42,0.25);
        }
        .step-indicator.completed .step-circle {
            background: rgba(212,175,55,0.2); color: var(--gold-400);
            border-color: var(--gold-400);
        }
        .step-label {
            font-size: 0.7rem; font-weight: 600; color: var(--text-muted);
            text-align: center;
        }
        .step-indicator.active .step-label { color: var(--maroon-700); }
        .step-indicator.completed .step-label { color: var(--gold-400); }
        .step-line {
            width: 3rem; height: 2px; background: var(--border-soft);
            margin: 0 0.5rem; align-self: center; margin-bottom: 1.5rem;
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .secondary-btn {
            background: rgba(255,255,255,0.9); color: var(--text-secondary);
            border: 1px solid var(--border-soft); transition: all 0.2s ease;
            display: flex; align-items: center; justify-content: center;
        }
        .secondary-btn:hover {
            background: rgba(254,249,231,0.7); border-color: var(--maroon-700);
            color: var(--maroon-700);
        }

        /* Submit */
        .fb-submit {
            width: 100%; padding: 0.85rem; border-radius: 0.6rem; border: none; cursor: pointer;
            background: linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%);
            color: white; font-size: 0.9rem; font-weight: 700; letter-spacing: 0.04em;
            box-shadow: 0 4px 12px rgba(92,26,26,0.2); transition: all 0.2s ease;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .fb-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(92,26,26,0.28); }

        /* Alert */
        .fb-alert-error {
            background: rgba(254,242,242,0.95); border: 1px solid rgba(185,28,28,0.3);
            border-left: 3px solid #dc2626; border-radius: 0.6rem;
            padding: 0.85rem 1rem; font-size: 0.8rem; color: #7f1d1d;
        }

        /* Intro box */
        .fb-intro {
            background: rgba(250,248,245,0.7); border: 1px solid var(--border-soft);
            border-radius: 0.6rem; padding: 1rem 1.1rem;
            font-size: 0.8rem; color: var(--text-secondary); line-height: 1.65;
        }

        /* Scale legend */
        .scale-legend {
            display: flex; flex-wrap: wrap; gap: 0.5rem;
            padding: 0.65rem 0.85rem; background: rgba(250,248,245,0.7);
            border: 1px solid var(--border-soft); border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .scale-legend-item { display: flex; align-items: center; gap: 0.3rem; font-size: 0.7rem; color: var(--text-secondary); }
        .scale-dot {
            width: 1.4rem; height: 1.4rem; border-radius: 0.3rem; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.65rem; font-weight: 700; color: white;
        }

        /* Alert notifications */
        .alert-stack {
            position: fixed;
            top: 5rem;
            right: 1rem;
            z-index: 1100;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            width: min(24rem, calc(100vw - 2rem));
            pointer-events: none;
        }

        .system-alert {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
            padding: 0.95rem 1rem 0.95rem 0.95rem;
            border-radius: 0.9rem;
            border: 1px solid var(--border-soft);
            background: rgba(255,255,255,0.97);
            box-shadow: 0 12px 30px rgba(44,36,32,0.14);
            backdrop-filter: blur(10px);
            pointer-events: auto;
            animation: alertSlideIn 0.24s ease;
        }

        .system-alert::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            border-radius: 999px;
        }

        .system-alert.success::before {
            background: linear-gradient(180deg, #15803d, #22c55e);
        }

        .system-alert.error::before {
            background: linear-gradient(180deg, #991b1b, #dc2626);
        }

        .system-alert.warning::before {
            background: linear-gradient(180deg, var(--gold-500), var(--gold-400));
        }

        .system-alert.info::before {
            background: linear-gradient(180deg, var(--maroon-800), var(--maroon-700));
        }

        .system-alert-icon {
            width: 2.2rem;
            height: 2.2rem;
            min-width: 2.2rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 0.05rem;
            font-size: 0.9rem;
        }

        .system-alert.success .system-alert-icon {
            background: rgba(34,197,94,0.12);
            color: #15803d;
        }

        .system-alert.error .system-alert-icon {
            background: rgba(220,38,38,0.12);
            color: #b91c1c;
        }

        .system-alert.warning .system-alert-icon {
            background: rgba(212,175,55,0.16);
            color: #9a3412;
        }

        .system-alert.info .system-alert-icon {
            background: rgba(92,26,26,0.10);
            color: var(--maroon-700);
        }

        .system-alert-content {
            flex: 1;
            min-width: 0;
        }

        .system-alert-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.15rem;
            letter-spacing: 0.02em;
        }

        .system-alert-message {
            font-size: 0.76rem;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        .system-alert-close {
            width: 1.85rem;
            height: 1.85rem;
            min-width: 1.85rem;
            border: none;
            background: transparent;
            color: var(--text-muted);
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .system-alert-close:hover {
            background: rgba(254,249,231,0.9);
            color: var(--maroon-700);
        }

        .system-alert-progress {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 3px;
            background: rgba(44,36,32,0.06);
            overflow: hidden;
        }

        .system-alert-progress-bar {
            width: 100%;
            height: 100%;
            transform-origin: left center;
        }

        .system-alert.success .system-alert-progress-bar {
            background: linear-gradient(90deg, #15803d, #22c55e);
        }

        .system-alert.error .system-alert-progress-bar {
            background: linear-gradient(90deg, #991b1b, #dc2626);
        }

        .system-alert.warning .system-alert-progress-bar {
            background: linear-gradient(90deg, var(--gold-500), var(--gold-400));
        }

        .system-alert.info .system-alert-progress-bar {
            background: linear-gradient(90deg, var(--maroon-800), var(--maroon-700));
        }

        @keyframes alertSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px) translateX(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0) translateX(0);
            }
        }

        @keyframes alertProgress {
            from {
                transform: scaleX(1);
            }
            to {
                transform: scaleX(0);
            }
        }

        @media (max-width: 639px) {
            .alert-stack {
                top: 0.75rem;
                left: 0.75rem;
                right: 0.75rem;
                width: auto;
            }

            .system-alert {
                padding: 0.85rem 0.9rem 0.85rem 0.9rem;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="fb-nav py-4" id="mainNavbar">
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
                            <a href="{{ route('mhc') }}" class="dropdown-link">Mental Health Corner</a>
                        </div>
                    </div>
                    <a href="{{ route('feedback') }}" class="nav-link" style="color:#d4af37;">Feedback</a>
                @endif
            </div>

            <!-- Right: Icons -->
            <div class="flex items-center space-x-4 justify-end">
                @auth
                    @php
                        $unreadNotifications = Auth::user()->unreadNotifications->take(5);
                        $unreadCount = Auth::user()->unreadNotifications->count();
                    @endphp
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
                    <div class="relative" id="profile-dropdown-wrapper">
                        <button class="text-white p-2 rounded-full hover:bg-white/10 transition" id="profile-dropdown-btn">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="profile-dropdown-content hidden" id="profile-dropdown-menu">
                            <div class="mb-3 border-b pb-2 border-[#e8ddd2]">
                                <div class="font-semibold text-[#2f2522]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-[#766864]">{{ Auth::user()->email }}</div>
                                <div class="text-xs text-[#8f1d1d] capitalize font-semibold">Role: {{ Auth::user()->role }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block py-2 text-[#2f2522] hover:text-[#8f1d1d]">
                                <i class="fas fa-circle-user mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full text-left block py-2 text-[#2f2522] hover:text-[#8f1d1d]">
                                    <i class="fas fa-arrow-right-from-bracket mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-white font-semibold hover:text-[rgba(255,245,235,0.88)] transition">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-6 pb-2">
        <div class="text-center mb-5">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-700 uppercase tracking-widest mb-3"
                 style="background:rgba(254,249,231,0.9);border:1px solid rgba(212,175,55,0.3);color:var(--maroon-700);font-weight:700;">
                <span style="width:0.3rem;height:0.3rem;border-radius:50%;background:var(--gold-400);display:inline-block;"></span>
                Client Satisfaction Survey
            </div>
            <h1 class="text-xl sm:text-2xl font-bold" style="color:var(--text-primary);">Office of Guidance and Counseling</h1>
            <p class="text-sm mt-1" style="color:var(--text-secondary);">Your feedback helps us serve you better. It only takes a few minutes.</p>
        </div>

        <!-- Intro Box -->
        <div class="fb-intro mb-5">
            <p class="mb-2">This <strong>Client Satisfaction Measurement (CSM)</strong> tracks the customer experience of government offices. Your feedback on your recently concluded transaction will help this office provide better service.</p>
            <p class="mb-2">Personal information shared will be kept confidential and you always have the option to not answer this form.</p>
            <p class="font-semibold italic" style="color:var(--maroon-700);">Let us journey together to a greater MSU-IIT!</p>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 pb-10 flex-grow">

        @if($errors->any())
        <div class="fb-alert-error mb-4">
            <div class="font-semibold mb-1 flex items-center gap-2"><i class="fas fa-circle-exclamation"></i> Please fix the following:</div>
            <ul class="list-disc list-inside space-y-0.5 ml-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        <!-- Progress Steps -->
        <div class="mb-6 flex items-center justify-center gap-2">
            <div class="step-indicator active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Transaction</div>
            </div>
            <div class="step-line"></div>
            <div class="step-indicator" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Charter</div>
            </div>
            <div class="step-line"></div>
            <div class="step-indicator" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Quality</div>
            </div>
            <div class="step-line"></div>
            <div class="step-indicator" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Comments</div>
            </div>
        </div>

        <div class="fb-card">
            <div class="fb-card-topline"></div>

            <form method="POST" action="{{ route('feedback.store') }}" id="feedbackForm">
                @csrf

                <!-- Step 1: Transaction Details -->
                <div class="form-step active" data-step="1">
                    <div class="fb-section">
                        <div class="fb-section-title"><i class="fas fa-clipboard-list"></i> Transaction Details</div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="fb-label">Service / Transaction Availed</label>
                                <select name="service_availed" class="fb-select" required>
                                    <option value="" disabled selected>Select a service...</option>
                                    <option value="COUNSELING SERVICES">Counseling Services</option>
                                    <option value="TESTING-TEST ADMINISTRATION">Testing — Test Administration</option>
                                    <option value="TESTING - TEST INTERPRETATION">Testing — Test Interpretation</option>
                                    <option value="REQUEST FOR ISSUANCE OF CERTIFICATION AND REQUEST FOR ISSUANCE OF RECOMMENDATION LETTER">Issuance of Certification / Recommendation Letter</option>
                                    <option value="INITIAL INTERVIEW FOR FRESHMEN">Initial Interview for Freshmen</option>
                                    <option value="REFERRAL SERVICE">Referral Service</option>
                                </select>
                            </div>
                            <div>
                                <label class="fb-label">Personnel you transacted with</label>
                                <select name="target_counselor_id" class="fb-select" required>
                                    <option value="" disabled selected>Select personnel...</option>
                                    @isset($counselors)
                                        @foreach($counselors as $counselor)
                                            <option value="{{ $counselor->id }}">{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}</option>
                                        @endforeach
                                    @endisset
                                    <option value="unidentified">I can't identify / No specific personnel</option>
                                </select>
                            </div>
                        </div>
                        <label class="check-option">
                            <input type="checkbox" name="is_anonymous" value="1">
                            <span><strong style="color:var(--text-primary);">Submit anonymously</strong> — your name will not be attached to this response.</span>
                        </label>
                    </div>
                    <div class="fb-section">
                        <button type="button" class="fb-submit" onclick="nextStep(1)">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Citizen's Charter -->
                <div class="form-step" data-step="2">
                    <div class="fb-section">
                        <div class="fb-section-title"><i class="fas fa-file-lines"></i> Citizen's Charter (CC)</div>
                        <p class="text-xs mb-4" style="color:var(--text-muted);">The Citizen's Charter is an official document that reflects the services of a government agency including its requirements, fees, and processing times.</p>

                        <div class="space-y-6">
                            <div>
                                <p class="text-sm font-semibold mb-2" style="color:var(--text-primary);">CC1. Which best describes your awareness of a Citizen's Charter?</p>
                                <div class="space-y-1" id="cc1-group">
                                    @foreach(['A' => 'I know what a CC is and I saw this office\'s CC.', 'B' => 'I know what a CC is but I did NOT see this office\'s CC.', 'C' => 'I learned of the CC only when I saw this office\'s CC.', 'D' => 'I do not know what a CC is and I did not see one in this office.'] as $code => $opt)
                                    <label class="radio-option" onclick="markSelected(this)">
                                        <input type="radio" name="cc1" value="{{ $code }}" required>
                                        <span>{{ $opt }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="text-sm font-semibold mb-2" style="color:var(--text-primary);">CC2. If aware of the CC, was it easy to see?</p>
                                <div class="space-y-1" id="cc2-group">
                                    @foreach(['Easy to see','Somewhat easy to see','Difficult to see','Not visible at all','N/A'] as $opt)
                                    <label class="radio-option" onclick="markSelected(this)">
                                        <input type="radio" name="cc2" value="{{ $opt }}" required>
                                        <span>{{ $opt }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <p class="text-sm font-semibold mb-2" style="color:var(--text-primary);">CC3. If aware of the CC, how much did it help in your transaction?</p>
                                <div class="space-y-1" id="cc3-group">
                                    @foreach(['Helped very much','Somewhat helped','Did not help','N/A'] as $opt)
                                    <label class="radio-option" onclick="markSelected(this)">
                                        <input type="radio" name="cc3" value="{{ $opt }}" required>
                                        <span>{{ $opt }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fb-section">
                        <div class="flex gap-3">
                            <button type="button" class="secondary-btn flex-1" onclick="prevStep(2)" style="padding:0.85rem;border-radius:0.6rem;font-weight:700;font-size:0.9rem;">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </button>
                            <button type="button" class="fb-submit flex-1" onclick="nextStep(2)">
                                Next <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Service Quality -->
                <div class="form-step" data-step="3">
                    <div class="fb-section">
                        <div class="fb-section-title"><i class="fas fa-star-half-stroke"></i> Service Quality (SQD)</div>
                        <div class="scale-legend mb-4">
                            @foreach([[1,'#dc2626','Very Dissatisfied'],[2,'#f97316','Dissatisfied'],[3,'#d4af37','Neutral'],[4,'#16a34a','Satisfied'],[5,'#059669','Very Satisfied']] as [$n,$c,$w])
                            <div class="scale-legend-item">
                                <div class="scale-dot" style="background:{{ $c }}">{{ $n }}</div>
                                <span>{{ $w }}</span>
                            </div>
                            @endforeach
                        </div>

                        @php
                            $sqdQuestions = [
                                'sqd0' => 'I am satisfied with the service that I availed.',
                                'sqd1' => 'I spent a reasonable amount of time for my transaction.',
                                'sqd2' => 'The office followed the transaction\'s requirements and steps based on the information provided.',
                                'sqd3_1' => 'The steps (including payment) I needed to do for my transaction were easy and simple.',
                                'sqd3_2' => 'The receiving/waiting/processing/working area and office facilities have visual appeal and comfiness.',
                                'sqd4' => 'I easily found information about my transaction from the office or its website.',
                                'sqd5' => 'I paid a reasonable amount of fees for my transaction.',
                                'sqd6' => 'I feel the office was fair to everyone — "walang palakasan" — during my transaction.',
                                'sqd7_1' => 'I was treated courteously by the staff, and (if asked for help) the staff was helpful.',
                                'sqd7_2' => 'The staff is knowledgeable of the functions and/or operations of the office.',
                                'sqd7_3' => 'The staff has the ability to complete the transaction.',
                                'sqd8' => 'I got what I needed from the office, or (if denied) the denial was sufficiently explained to me.',
                                'sqd9' => 'The staff shows professionalism, politeness, and willingness to help.',
                            ];
                            $sqdColors = [1=>'#dc2626',2=>'#f97316',3=>'#d4af37',4=>'#16a34a',5=>'#059669'];
                            $sqdWords = [1=>'Very Dissatisfied',2=>'Dissatisfied',3=>'Neutral',4=>'Satisfied',5=>'Very Satisfied'];
                        @endphp

                        @foreach($sqdQuestions as $key => $label)
                        <div class="sqd-row" style="padding:0.75rem 0;">
                            <div class="sqd-question" style="font-size:0.78rem;margin-bottom:0.5rem;">{{ $loop->iteration }}. {{ $label }}</div>
                            <div class="sqd-scale">
                                @foreach([1,2,3,4,5] as $v)
                                <label>
                                    <input type="radio" name="{{ $key }}" value="{{ $v }}" required>
                                    <div class="sqd-btn" style="padding:0.4rem 0.2rem;">
                                        <span class="sqd-num" style="font-size:0.8rem;">{{ $v }}</span>
                                        <span class="sqd-word">{{ $sqdWords[$v] }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="fb-section">
                        <div class="flex gap-3">
                            <button type="button" class="secondary-btn flex-1" onclick="prevStep(3)" style="padding:0.85rem;border-radius:0.6rem;font-weight:700;font-size:0.9rem;">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </button>
                            <button type="button" class="fb-submit flex-1" onclick="nextStep(3)">
                                Next <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Comments & Submit -->
                <div class="form-step" data-step="4">
                    <div class="fb-section">
                        <div class="fb-section-title"><i class="fas fa-comment-dots"></i> Comments & Suggestions</div>
                        <p class="text-xs mb-3" style="color:var(--text-muted);">Please share any comments, suggestions, or issues you encountered. This helps us improve.</p>
                        <textarea name="comments" class="fb-textarea" rows="4" placeholder="Write your thoughts here..."></textarea>
                    </div>
                    <div class="fb-section">
                        <div class="flex gap-3">
                            <button type="button" class="secondary-btn flex-1" onclick="prevStep(4)" style="padding:0.85rem;border-radius:0.6rem;font-weight:700;font-size:0.9rem;">
                                <i class="fas fa-arrow-left mr-2"></i> Back
                            </button>
                            <button type="submit" class="fb-submit flex-1">
                                <i class="fas fa-paper-plane"></i> Submit Feedback
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background:linear-gradient(to right,#5b0f0f,#7b1717,#8f1d1d);color:white;" class="py-4 mt-auto">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[#f3e8df]">&copy; {{ date('Y') }} Office of Guidance and Counseling. All rights reserved.</p>
            <p class="text-sm text-[#e5caa9] mt-2">Committed to student support, wellness, and accessible guidance services</p>
        </div>
    </footer>

    <!-- Alert Stack -->
    <div id="alertStack" class="alert-stack"></div>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        if (navbar) {
            window.addEventListener('scroll', () => {
                navbar.classList.toggle('scrolled', window.scrollY > 10);
            });
        }

        // Profile dropdown
        document.addEventListener('DOMContentLoaded', function() {
            // Profile dropdown
            const profileBtn = document.getElementById('profile-dropdown-btn');
            const profileMenu = document.getElementById('profile-dropdown-menu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', e => { e.stopPropagation(); profileMenu.classList.toggle('hidden'); notifPanel?.classList.add('hidden'); });
                document.addEventListener('click', () => profileMenu.classList.add('hidden'));
                profileMenu.addEventListener('click', e => e.stopPropagation());
            }

            // Notification bell
            const notifBtn = document.getElementById('notif-bell-btn');
            const notifPanel = document.getElementById('notif-panel');
            if (notifBtn && notifPanel) {
                notifBtn.addEventListener('click', e => { e.stopPropagation(); notifPanel.classList.toggle('hidden'); profileMenu?.classList.add('hidden'); });
                document.addEventListener('click', () => notifPanel.classList.add('hidden'));
                notifPanel.addEventListener('click', e => e.stopPropagation());
            }

            // Mark all read
            const markAllBtn = document.getElementById('mark-all-read-btn');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', () => {
                    fetch('/notifications/mark-all-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                        .then(() => { document.getElementById('notif-badge')?.remove(); document.getElementById('notif-list').innerHTML = '<div class="px-4 py-8 text-center text-sm text-[#a09490]"><i class="fas fa-bell-slash text-2xl mb-2 block opacity-40"></i>No new notifications</div>'; markAllBtn.remove(); });
                });
            }

            // Notification item click
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
                servicesBtn.addEventListener('click', e => { e.stopPropagation(); servicesMenu.classList.toggle('hidden'); });
                document.addEventListener('click', () => servicesMenu.classList.add('hidden'));
                servicesMenu.addEventListener('click', e => e.stopPropagation());
            }
        });
        function markSelected(label) {
            const group = label.parentElement;
            group.querySelectorAll('.radio-option').forEach(l => l.classList.remove('selected'));
            label.classList.add('selected');
        }

        // Step Navigation
        let currentStep = 1;

        function validateStep(step) {
            const stepEl = document.querySelector(`.form-step[data-step="${step}"]`);
            const requiredInputs = stepEl.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalid = null;

            requiredInputs.forEach(input => {
                if (input.type === 'radio') {
                    const name = input.name;
                    const checked = stepEl.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        if (!firstInvalid) firstInvalid = input;
                    }
                } else if (input.tagName === 'SELECT') {
                    if (!input.value || input.value === '') {
                        isValid = false;
                        if (!firstInvalid) firstInvalid = input;
                        input.style.borderColor = '#dc2626';
                    } else {
                        input.style.borderColor = '';
                    }
                } else if (input.tagName === 'INPUT' && input.type !== 'checkbox') {
                    if (!input.value.trim()) {
                        isValid = false;
                        if (!firstInvalid) firstInvalid = input;
                        input.style.borderColor = '#dc2626';
                    } else {
                        input.style.borderColor = '';
                    }
                }
            });

            if (!isValid && firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                showSystemAlert('Please fill in all required fields before continuing.', 'warning', 'Incomplete');
            }

            return isValid;
        }

        function nextStep(step) {
            if (!validateStep(step)) return;

            document.querySelector(`.form-step[data-step="${step}"]`).classList.remove('active');
            document.querySelector(`.step-indicator[data-step="${step}"]`).classList.add('completed');
            document.querySelector(`.step-indicator[data-step="${step}"]`).classList.remove('active');

            currentStep = step + 1;
            document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');
            document.querySelector(`.step-indicator[data-step="${currentStep}"]`).classList.add('active');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function prevStep(step) {
            document.querySelector(`.form-step[data-step="${step}"]`).classList.remove('active');
            document.querySelector(`.step-indicator[data-step="${step}"]`).classList.remove('active');

            currentStep = step - 1;
            document.querySelector(`.form-step[data-step="${currentStep}"]`).classList.add('active');
            document.querySelector(`.step-indicator[data-step="${currentStep}"]`).classList.remove('completed');
            document.querySelector(`.step-indicator[data-step="${currentStep}"]`).classList.add('active');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Auto-mark on page load if old() values exist (validation repopulation)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.radio-option input[type="radio"]').forEach(input => {
                if (input.checked) input.closest('.radio-option')?.classList.add('selected');
                input.addEventListener('change', function() {
                    const group = this.closest('.space-y-1');
                    if (group) group.querySelectorAll('.radio-option').forEach(l => l.classList.remove('selected'));
                    this.closest('.radio-option')?.classList.add('selected');
                });
            });
        });

        // Alert System (similar to appointments/create.blade.php)
        function showSystemAlert(message, type = 'info', title = null) {
            const alertStack = document.getElementById('alertStack');
            if (!alertStack) return;

            const config = {
                success: { icon: 'fa-circle-check', title: title || 'Success' },
                error: { icon: 'fa-circle-xmark', title: title || 'Something went wrong' },
                warning: { icon: 'fa-triangle-exclamation', title: title || 'Required information' },
                info: { icon: 'fa-circle-info', title: title || 'Notice' }
            };

            const selected = config[type] || config.warning;
            const duration = type === 'error' ? 5000 : 4200;

            const alertEl = document.createElement('div');
            alertEl.className = `system-alert ${type}`;
            alertEl.innerHTML = `
                <div class="system-alert-icon">
                    <i class="fas ${selected.icon}"></i>
                </div>
                <div class="system-alert-content">
                    <div class="system-alert-title">${selected.title}</div>
                    <div class="system-alert-message">${message}</div>
                </div>
                <button type="button" class="system-alert-close" aria-label="Dismiss notification">
                    <i class="fas fa-xmark"></i>
                </button>
                <div class="system-alert-progress">
                    <div class="system-alert-progress-bar"></div>
                </div>
            `;

            const closeBtn = alertEl.querySelector('.system-alert-close');
            const progressBar = alertEl.querySelector('.system-alert-progress-bar');
            if (progressBar) {
                progressBar.style.animation = `alertProgress ${duration}ms linear forwards`;
            }

            const removeAlert = () => {
                if (!alertEl.parentNode) return;
                alertEl.style.opacity = '0';
                alertEl.style.transform = 'translateY(-6px)';
                alertEl.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                setTimeout(() => {
                    if (alertEl.parentNode) {
                        alertEl.remove();
                    }
                }, 200);
            };

            closeBtn.addEventListener('click', removeAlert);
            alertStack.appendChild(alertEl);
            setTimeout(removeAlert, duration);
        }

        // Check for success message from session
        @if(session('success'))
            showSystemAlert('{{ session('success') }}', 'success', 'Feedback Submitted');
        @endif

        @if(session('error'))
            showSystemAlert('{{ session('error') }}', 'error', 'Error');
        @endif
    </script>
</body>
</html>
