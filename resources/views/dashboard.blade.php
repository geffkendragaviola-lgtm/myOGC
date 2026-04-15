<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-red: #C41E3A;
            --primary-red-dark: #A01830;
            --sidebar-gray: #4A4A4A;
            --bg-light: #F5F5F5;
            --bg-white: #FFFFFF;
            --text-dark: #2f2f2f;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --border-soft: #e5e7eb;
            --danger-red: #DC3545;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--bg-light);
            color: var(--text-dark);
        }

        .dashboard-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-navbar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(196, 30, 58, 0.96);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.3s ease;
        }

        .dashboard-navbar.scrolled {
            background: rgba(196, 30, 58, 0.98);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
        }

        .nav-link {
            color: white;
            font-weight: 600;
            transition: 0.25s ease;
        }

        .nav-link:hover {
            color: rgba(255, 255, 255, 0.82);
        }

        .dashboard-hero {
            position: relative;
            overflow: hidden;
            padding: 5.5rem 0 4.5rem;
            padding-top: calc(5.5rem + 4.5rem);
            background:
                linear-gradient(135deg, rgba(196, 30, 58, 0.78), rgba(74, 74, 74, 0.72)),
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
                linear-gradient(to bottom, rgba(255,255,255,0.04), rgba(0,0,0,0.18));
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
            background: rgba(255,255,255,0.12);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 999px;
            padding: 0.55rem 1rem;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 1rem;
            backdrop-filter: blur(8px);
        }

        .hero-title {
            font-size: clamp(2.2rem, 5vw, 4.1rem);
            line-height: 1.02;
            font-weight: 800;
            color: white;
            letter-spacing: -0.03em;
            margin-bottom: 1rem;
            text-shadow: 0 8px 24px rgba(0,0,0,0.18);
        }

        .hero-description {
            font-size: 1.02rem;
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
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.16);
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .hero-side-card {
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 24px;
            padding: 1.4rem;
            box-shadow: 0 20px 45px rgba(0,0,0,0.16);
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
            color: rgba(255,255,255,0.86);
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
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 18px;
            padding: 1rem;
        }

        .hero-stat-label {
            color: rgba(255,255,255,0.72);
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
            background: var(--bg-white);
            border: 1px solid var(--border-soft);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
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
            background: linear-gradient(to bottom, var(--primary-red), var(--sidebar-gray));
            margin-right: 0.85rem;
        }

        .section-icon {
            color: var(--primary-red);
            margin-right: 0.75rem;
        }

        .dashboard-profile-dropdown {
            position: relative;
        }

        .dashboard-profile-dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
            border-radius: 14px;
            padding: 1rem;
            min-width: 220px;
            z-index: 1001;
            margin-top: 0.5rem;
            border: 1px solid var(--border-soft);
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

        .dashboard-staff-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--border-soft);
        }

        .dashboard-staff-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
            border-color: rgba(196, 30, 58, 0.22);
        }

        .dashboard-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(196,30,58,0.35), rgba(74,74,74,0.25), transparent);
            margin: 3rem 0;
        }

        .announcement-image-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid var(--border-soft);
        }

        .announcement-image {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            display: block;
        }

        .btn-brand {
            background: var(--primary-red);
            color: white;
            transition: all 0.25s ease;
        }

        .btn-brand:hover {
            background: var(--primary-red-dark);
            transform: translateY(-1px);
        }

        .dropdown-link:hover {
            color: var(--primary-red);
            background: var(--bg-light);
        }

        .brand-soft-bg {
            background: var(--bg-light);
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
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
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
            <div class="container mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="text-white font-bold text-2xl mr-10 tracking-wide">
                        <span>OGC</span>
                    </div>

                    <div class="hidden md:flex space-x-8">
                        @if(Auth::user()->role === 'counselor')
                        <div class="relative" id="counselor-dropdown">
                            <button
                                class="nav-link flex items-center"
                                id="counselor-dropdown-btn"
                            >
                                Counselor
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>

                            <div
                                class="absolute hidden bg-white rounded-xl shadow-lg mt-1 w-52 z-50 border border-[var(--border-soft)]"
                                id="counselor-dropdown-menu"
                            >
                                <a href="{{ route('counselor.dashboard') }}" class="dropdown-link block px-4 py-2 text-gray-800 rounded-t-xl">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                                <a href="{{ route('counselor.resources.index') }}" class="dropdown-link block px-4 py-2 text-gray-800">
                                    <i class="fas fa-box-open mr-2"></i> Resources
                                </a>
                                <a href="{{ route('counselor.announcements.index') }}" class="dropdown-link block px-4 py-2 text-gray-800">
                                    <i class="fas fa-bullhorn mr-2"></i> Manage Announcements
                                </a>
                                <a href="{{ route('counselor.events.index') }}" class="dropdown-link block px-4 py-2 text-gray-800">
                                    <i class="fas fa-calendar-alt mr-2"></i> Manage Events
                                </a>
                                <a href="{{ route('counselor.calendar') }}" class="dropdown-link block px-4 py-2 text-gray-800">
                                    <i class="fas fa-calendar mr-2"></i> Calendar
                                </a>
                                <a href="{{ route('counselor.appointments') }}" class="dropdown-link block px-4 py-2 text-gray-800 rounded-b-xl">
                                    <i class="fas fa-list mr-2"></i> Appointments
                                </a>
                            </div>
                        </div>
                        @endif

                        @if(Auth::user()->role === 'admin')
                        <div class="relative" id="admin-dropdown">
                            <button
                                class="nav-link flex items-center"
                                id="admin-dropdown-btn"
                            >
                                Admin
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>

                            <div
                                class="absolute hidden bg-white rounded-xl shadow-lg mt-1 w-52 z-50 border border-[var(--border-soft)]"
                                id="admin-dropdown-menu"
                            >
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-link block px-4 py-2 text-gray-800 rounded-t-xl">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                                <a href="{{ route('admin.users') }}" class="dropdown-link block px-4 py-2 text-gray-800">
                                    <i class="fas fa-users mr-2"></i> Manage Users
                                </a>
                                <a href="{{ route('admin.events') }}" class="dropdown-link block px-4 py-2 text-gray-800">
                                    <i class="fas fa-calendar-alt mr-2"></i> Manage Events
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="dropdown-link block px-4 py-2 text-gray-800">
                                    <i class="fas fa-user-plus mr-2"></i> Create User
                                </a>
                                <a href="{{ route('admin.students') }}" class="dropdown-link block px-4 py-2 text-gray-800">
                                    <i class="fas fa-user-graduate mr-2"></i> Students
                                </a>
                                <a href="{{ route('admin.counselors') }}" class="dropdown-link block px-4 py-2 text-gray-800 rounded-b-xl">
                                    <i class="fas fa-user-md mr-2"></i> Counselors
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
                                <div class="absolute hidden bg-white rounded-xl shadow-lg py-2 mt-1 w-52 z-50 border border-[var(--border-soft)]" id="services-dropdown-menu">
                                    <a href="{{ route('bap') }}" class="dropdown-link block px-4 py-2 text-gray-800">Book an Appointment</a>
                                    <a href="{{ route('mhc') }}" class="dropdown-link block px-4 py-2 text-gray-800">Mental Health Corner</a>
                                </div>
                            </div>

                            <a href="{{ route('feedback') }}" class="nav-link">Feedback</a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @if(Auth::user()->role === 'counselor')
                        <a href="{{ route('counselor.appointments') }}"
                           class="btn-brand font-semibold py-2 px-4 rounded-lg flex items-center hover:shadow-lg">
                            <i class="fas fa-calendar-check mr-2"></i> My Appointment
                        </a>
                    @endif

                    <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
                        <i class="fas fa-bell"></i>
                    </button>

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
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full text-left block py-2 text-[var(--text-dark)] hover:text-[var(--primary-red)]">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
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
                        $announcements = \App\Models\Announcement::with(['user', 'colleges'])
                        ->active()
                        ->when($userCollegeId, function($query) use ($userCollegeId) {
                            return $query->forCollege($userCollegeId);
                        })
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
                                                        <span class="bg-[#C41E3A] text-white text-xs px-3 py-1 rounded-full font-medium">
                                                            <i class="fas fa-globe mr-1"></i> All Colleges
                                                        </span>
                                                    @else
                                                        @foreach($announcement->colleges->take(3) as $college)
                                                            <span class="bg-[#F5F5F5] text-[#C41E3A] text-xs px-3 py-1 rounded-full font-medium border border-gray-200">
                                                                <i class="fas fa-university mr-1"></i> {{ $college->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($announcement->colleges->count() > 3)
                                                            <span class="bg-[#F5F5F5] text-[#C41E3A] text-xs px-3 py-1 rounded-full font-medium border border-gray-200">
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

                                                <div class="flex flex-wrap gap-4 text-sm text-[var(--text-muted)] mt-6 pt-4 border-t border-[var(--border-soft)]">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user mr-2 text-[var(--primary-red)]"></i>
                                                        Posted by: {{ $announcement->user->first_name }} {{ $announcement->user->last_name }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-calendar mr-2 text-[var(--primary-red)]"></i>
                                                        Posted: {{ $announcement->created_at->format('M j, Y') }}
                                                    </div>
                                                    @if($announcement->start_date || $announcement->end_date)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-clock mr-2 text-[var(--primary-red)]"></i>
                                                            @if($announcement->start_date && $announcement->end_date)
                                                                Valid: {{ $announcement->start_date->format('M j') }} - {{ $announcement->end_date->format('M j, Y') }}
                                                            @elseif($announcement->start_date)
                                                                Starts: {{ $announcement->start_date->format('M j, Y') }}
                                                            @elseif($announcement->end_date)
                                                                Until: {{ $announcement->end_date->format('M j, Y') }}
                                                            @endif
                                                        </div>
                                                    @endif
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

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($services as $service)
                                    <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-[var(--border-soft)] group">
                                        <img src="{{ $service->image_url }}"
                                             alt="{{ $service->title }}"
                                             class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">

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
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8 flex flex-col md:flex-row items-center border-l-4 border-[#C41E3A] hover:shadow-xl transition">
                        <div class="w-32 h-32 bg-gradient-to-br from-[#F5F5F5] to-[#e5e7eb] rounded-full overflow-hidden mb-4 md:mb-0 md:mr-6 shadow-lg ring-4 ring-[#C41E3A]/20">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Head Counselor" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-[var(--text-dark)]">
                                {{ $headCounselor->user->first_name }}
                                {{ $headCounselor->user->middle_name ? $headCounselor->user->middle_name . ' ' : '' }}
                                {{ $headCounselor->user->last_name }}
                            </h3>
                            <p class="text-[#C41E3A] font-semibold text-lg">Head of the Office of Guidance and Counseling</p>
                            <p class="text-[var(--text-secondary)]">{{ $headCounselor->position }} • {{ $headCounselor->credentials }}</p>
                            <p class="text-[var(--text-secondary)]">
                                <i class="fas fa-university mr-2 text-[#C41E3A]"></i>{{ $headCounselor->college->name ?? 'N/A' }}
                            </p>
                            <p class="text-[var(--text-secondary)] mt-2">
                                <i class="fas fa-envelope mr-2 text-[#C41E3A]"></i>{{ $headCounselor->user->email }}
                            </p>
                            <p class="text-[var(--text-secondary)] mt-2">
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
                            <div class="dashboard-staff-card bg-white rounded-xl shadow-md p-6 text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-[#F5F5F5] to-[#e5e7eb] rounded-full overflow-hidden mx-auto mb-4 ring-2 ring-[#C41E3A]/20">
                                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Counselor" class="w-full h-full object-cover">
                                </div>
                                <h4 class="text-lg font-semibold text-[var(--text-dark)]">
                                    {{ $counselor->user->first_name }}
                                    {{ $counselor->user->middle_name ? substr($counselor->user->middle_name, 0, 1) . '. ' : '' }}
                                    {{ $counselor->user->last_name }}
                                </h4>
                                <p class="text-[#C41E3A] font-medium">{{ $counselor->position }}</p>
                                <p class="text-[var(--text-secondary)] text-sm mt-1">{{ $colleges }}</p>
                                <p class="text-[var(--text-muted)] text-sm mt-1">{{ $counselor->credentials }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        <footer class="bg-[#4A4A4A] text-white py-8 mt-8">
            <div class="container mx-auto px-6 text-center">
                <p class="text-gray-200">&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
                <p class="text-sm text-gray-300 mt-2">Committed to student support, wellness, and accessible guidance services</p>
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

            function setupDropdown(dropdownId, btnId, menuId) {
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);
                if (!btn || !menu) return;

                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    menu.classList.toggle('hidden');
                });
            }

            setupDropdown('counselor-dropdown', 'counselor-dropdown-btn', 'counselor-dropdown-menu');
            setupDropdown('student-dropdown', 'student-dropdown-btn', 'student-dropdown-menu');
            setupDropdown('services-dropdown', 'services-dropdown-btn', 'services-dropdown-menu');

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

            const adminDropdownBtn = document.getElementById('admin-dropdown-btn');
            const adminDropdownMenu = document.getElementById('admin-dropdown-menu');
            if (adminDropdownBtn && adminDropdownMenu) {
                adminDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    adminDropdownMenu.classList.toggle('hidden');
                });
                document.addEventListener('click', () => adminDropdownMenu.classList.add('hidden'));
                adminDropdownMenu.addEventListener('click', (e) => e.stopPropagation());
            }

            document.querySelectorAll('.announcement-image').forEach(img => {
                img.addEventListener('error', function() { this.style.display = 'none'; });
            });
        });
    </script>
</body>
</html>