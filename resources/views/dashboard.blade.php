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
            /* Softer Maroon Palette */
            --maroon-soft: #7a2a2a;      /* Primary - warm, readable */
            --maroon-medium: #5c1a1a;    /* Secondary - for depth */
            --maroon-dark: #3a0c0c;      /* Accent - sparing use */
            --gold-primary: #d4af37;
            --gold-secondary: #c9a227;
            --bg-warm: #faf8f5;
            --border-soft: #e5e0db;
            --text-primary: #2c2420;
            --text-secondary: #6b5e57;
            --text-muted: #8b7e76;
        }

        .dashboard-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Fixed/Sticky Navbar */
        .dashboard-navbar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(135deg, var(--maroon-soft) 0%, var(--maroon-medium) 100%);
            box-shadow: 0 4px 20px rgba(122, 42, 42, 0.25);
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            backdrop-filter: blur(8px);
        }

        /* Enhanced shadow when scrolled */
        .dashboard-navbar.scrolled {
            box-shadow: 0 6px 24px rgba(122, 42, 42, 0.35);
        }

        .dashboard-hero {
            background: linear-gradient(135deg, rgba(122, 42, 42, 0.9), rgba(92, 26, 26, 0.9)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            padding: 4rem 0;
            position: relative;
            /* Add top padding to account for fixed navbar height */
            padding-top: calc(4rem + 4.5rem);
        }

        .dashboard-hero-content {
            position: relative;
            z-index: 10;
        }

        .dashboard-quote-text {
            font-size: 1rem;
            font-style: italic;
            color: #fef3c7;
            max-width: 800px;
            margin: 1rem auto 0;
            line-height: 1.6;
            font-weight: 400;
        }

        .dashboard-quote-author {
            color: var(--gold-primary);
            font-size: 0.80rem;
            margin-top: 0.75rem;
            font-weight: 500;
        }

        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .dashboard-profile-dropdown {
            position: relative;
        }

        .dashboard-profile-dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            border-radius: 8px;
            padding: 1rem;
            min-width: 200px;
            z-index: 1001; /* Higher than navbar */
            margin-top: 0.5rem;
            border: 1px solid var(--border-soft);
        }

        .dashboard-announcements-container {
            position: relative;
            min-height: 280px;
        }

        .dashboard-announcement-item {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
        }

        .dashboard-announcement-active {
            opacity: 1;
            pointer-events: all;
            position: relative;
        }

        .dashboard-staff-card {
            transition: transform 0.3s ease;
            border: 1px solid var(--border-soft);
        }

        .dashboard-staff-card:hover {
            transform: translateY(-3px);
            border-color: var(--gold-primary);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .dashboard-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold-primary), var(--maroon-soft), var(--gold-primary), transparent);
            margin: 3rem 0;
        }

        /* New styles for responsive image container */
        .announcement-image-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            background: #f8fafc;
        }

        .announcement-image {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
            display: block;
        }

        /* Gold accent classes */
        .gold-text {
            color: var(--gold-primary);
        }
        
        .gold-border {
            border-color: var(--gold-primary);
        }
        
        .gold-bg-light {
            background-color: rgba(212, 175, 55, 0.1);
        }
        
        .gold-hover:hover {
            color: var(--gold-primary);
        }

        /* Softer Maroon variations */
        .bg-maroon {
            background: linear-gradient(135deg, var(--maroon-soft) 0%, var(--maroon-medium) 100%);
        }
        
        .border-maroon {
            border-color: var(--maroon-soft);
        }
        
        .text-maroon {
            color: var(--maroon-soft);
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .announcement-image {
                max-height: 400px;
            }
        }

        @media (max-width: 768px) {
            .announcement-image {
                max-height: 300px;
            }
            .dashboard-hero {
                padding-top: calc(4rem + 4rem);
            }
        }

        @media (max-width: 640px) {
            .announcement-image {
                max-height: 250px;
            }
            .dashboard-navbar {
                padding: 0.75rem 0;
            }
            .dashboard-hero {
                padding-top: calc(4rem + 3.5rem);
            }
        }
        
        /* Custom scrollbar - softer */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--maroon-soft);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--maroon-medium);
        }
    </style>
</head>
<body class="bg-[var(--bg-warm)]">
    <div class="dashboard-container">
        <nav class="dashboard-navbar py-4" id="mainNavbar">
            <div class="container mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="text-white font-bold text-2xl mr-10 tracking-wide">
                        <span class="gold-text">OGC</span>
                    </div>
                    <div class="hidden md:flex space-x-8">

                        <!-- Counselor Dropdown -->
                        @if(Auth::user()->role === 'counselor')
                        <div class="relative" id="counselor-dropdown">
                            <button
                                class="text-white font-semibold hover:text-[var(--gold-primary)] transition flex items-center"
                                id="counselor-dropdown-btn"
                            >
                                Counselor
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>

                            <div
                                class="absolute hidden bg-white rounded-md shadow-lg mt-1 w-48 z-50 border border-[var(--border-soft)]"
                                id="counselor-dropdown-menu"
                            >
                                <a href="{{ route('counselor.dashboard') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                                <a href="{{ route('counselor.resources.index') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-box-open mr-2"></i> Resources
                                </a>
                                <a href="{{ route('counselor.announcements.index') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-bullhorn mr-2"></i> Manage Announcements
                                </a>
                                <a href="{{ route('counselor.events.index') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-calendar-alt mr-2"></i> Manage Events
                                </a>
                                <a href="{{ route('counselor.calendar') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-calendar mr-2"></i> Calendar
                                </a>
                                <a href="{{ route('counselor.appointments') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-list mr-2"></i> Appointments
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Admin Dropdown -->
                        @if(Auth::user()->role === 'admin')
                        <div class="relative" id="admin-dropdown">
                            <button
                                class="text-white font-semibold hover:text-[var(--gold-primary)] transition flex items-center"
                                id="admin-dropdown-btn"
                            >
                                Admin
                                <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>

                            <div
                                class="absolute hidden bg-white rounded-md shadow-lg mt-1 w-48 z-50 border border-[var(--border-soft)]"
                                id="admin-dropdown-menu"
                            >
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                                <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-users mr-2"></i> Manage Users
                                </a>
                                <a href="{{ route('admin.events') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-calendar-alt mr-2"></i> Manage Events
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-user-plus mr-2"></i> Create User
                                </a>
                                <a href="{{ route('admin.students') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-user-graduate mr-2"></i> Students
                                </a>
                                <a href="{{ route('admin.counselors') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">
                                    <i class="fas fa-user-md mr-2"></i> Counselors
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Student Dropdown -->
                        @if(Auth::user()->role === 'student')
                        <div class="relative" id="student-dropdown">
                            <a href="{{ route('student.show', Auth::user()->student->id) }}" class="text-white font-semibold hover:text-[var(--gold-primary)] transition flex items-center">
                                Profile
                            </a>
                        </div>
                        @endif

                        <a href="#" class="text-white font-semibold hover:text-[var(--gold-primary)] transition">Home</a>

                        @if(Auth::user()->role === 'student')
                            <!-- Services Dropdown -->
                            <div class="relative" id="services-dropdown">
                                <button class="text-white font-semibold hover:text-[var(--gold-primary)] transition flex items-center" id="services-dropdown-btn">
                                    Services <i class="fas fa-chevron-down ml-1 text-sm"></i>
                                </button>
                                <div class="absolute hidden bg-white rounded-md shadow-lg py-2 mt-1 w-48 z-50 border border-[var(--border-soft)]" id="services-dropdown-menu">
                                    <a href="{{ route('bap') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">Book an Appointment</a>
                                    <a href="{{ route('mhc') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">Mental Health Corner</a>
                                </div>
                            </div>

                            <a href="{{ route('feedback') }}" class="text-white font-semibold hover:text-[var(--gold-primary)] transition">Feedback</a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @if(Auth::user()->role === 'counselor')
                        <a href="{{ route('counselor.appointments') }}"
                           class="bg-gradient-to-r from-[var(--gold-primary)] to-[var(--gold-secondary)] text-[var(--maroon-medium)] font-semibold py-2 px-4 rounded-lg flex items-center hover:shadow-lg transition">
                            <i class="fas fa-calendar-check mr-2"></i> My Appointment
                        </a>
                    @endif

                    <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
                        <i class="fas fa-bell"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="dashboard-profile-dropdown">
                        <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition" id="profile-dropdown-btn">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="dashboard-profile-dropdown-content hidden" id="profile-dropdown-menu">
                            <div class="mb-3 border-b pb-2 border-[var(--border-soft)]">
                                <div class="font-semibold text-[var(--text-primary)]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-[var(--text-secondary)]">{{ Auth::user()->email }}</div>
                                <div class="text-xs text-[var(--maroon-soft)] capitalize font-semibold">Role: {{ Auth::user()->role }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block py-2 text-[var(--text-primary)] hover:text-[var(--maroon-soft)]">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full text-left block py-2 text-[var(--text-primary)] hover:text-[var(--maroon-soft)]">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Banner -->
        <header class="dashboard-hero">
            <div class="container mx-auto px-6 text-center dashboard-hero-content">
                <p class="text-white text-sm md:text-base mb-2 font-medium tracking-wide">
                    Welcome back, {{ Auth::user()->first_name }}!
                </p>
                
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 drop-shadow-lg tracking-tight">
                    Office of Guidance and Counseling
                </h1>
                
                <div class="max-w-3xl mx-auto">
                    <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-[var(--gold-primary)] to-transparent mx-auto mb-4"></div>
                    <p class="dashboard-quote-text">
                        <i class="fas fa-quote-left gold-text mr-2 text-sm"></i>
                        Make it a daily practice to purposefully look for joy — and when you find it, take a moment, inhale it, treasure it, and take it with you.
                        <i class="fas fa-quote-right gold-text ml-2 text-sm"></i>
                    </p>
                    <p class="dashboard-quote-author">— Office of Guidance and Counseling</p>
                    <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-[var(--gold-primary)] to-transparent mx-auto mt-4"></div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="min-h-screen bg-[var(--bg-warm)]">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                
                <!-- Announcements Section -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-6 flex items-center">
                        <div class="h-8 w-1 bg-gradient-to-b from-[var(--maroon-soft)] to-[var(--gold-primary)] rounded-full mr-3"></div>
                        <i class="fas fa-bullhorn text-[var(--maroon-soft)] mr-3"></i>Announcements
                    </h2>

                    @php
                        use Carbon\Carbon;
                        $userCollegeId = Auth::user()->student->college_id ?? null;
                        $announcements = \App\Models\Announcement::with(['user', 'colleges'])
                            ->active()
                            ->when($userCollegeId, function($query) use ($userCollegeId) {
                                return $query->forCollege($userCollegeId);
                            })
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp

                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-[var(--gold-primary)]">
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
                                                        <span class="bg-gradient-to-r from-[var(--maroon-soft)] to-[var(--maroon-medium)] text-white text-xs px-3 py-1 rounded-full font-medium">
                                                            <i class="fas fa-globe mr-1"></i> All Colleges
                                                        </span>
                                                    @else
                                                        @foreach($announcement->colleges->take(3) as $college)
                                                            <span class="bg-[var(--bg-warm)] text-[var(--maroon-soft)] text-xs px-3 py-1 rounded-full font-medium border border-[var(--gold-primary)]/30">
                                                                <i class="fas fa-university mr-1"></i> {{ $college->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($announcement->colleges->count() > 3)
                                                            <span class="bg-[var(--bg-warm)] text-[var(--maroon-soft)] text-xs px-3 py-1 rounded-full font-medium">
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
                                                <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-4">{{ $announcement->title }}</h3>

                                                <div class="text-[var(--text-secondary)] whitespace-pre-line leading-relaxed mb-6 text-lg">
                                                    {{ $announcement->content }}
                                                </div>

                                                <div class="flex flex-wrap gap-4 text-sm text-[var(--text-muted)] mt-6 pt-4 border-t border-[var(--border-soft)]">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-user mr-2 text-[var(--maroon-soft)]"></i>
                                                        Posted by: {{ $announcement->user->first_name }} {{ $announcement->user->last_name }}
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-calendar mr-2 text-[var(--maroon-soft)]"></i>
                                                        Posted: {{ $announcement->created_at->format('M j, Y') }}
                                                    </div>
                                                    @if($announcement->start_date || $announcement->end_date)
                                                        <div class="flex items-center">
                                                            <i class="fas fa-clock mr-2 text-[var(--maroon-soft)]"></i>
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
                                        <i class="fas fa-list mr-2 text-[var(--maroon-soft)]"></i>Announcement <span id="current-announcement" class="font-bold text-[var(--maroon-soft)]">1</span> of {{ $announcements->count() }}
                                    </div>

                                    <div class="flex space-x-2">
                                        <button class="dashboard-prev bg-gradient-to-r from-[var(--maroon-soft)] to-[var(--maroon-medium)] text-white p-3 rounded-full hover:shadow-lg transition transform hover:scale-110">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button class="dashboard-next bg-gradient-to-r from-[var(--maroon-soft)] to-[var(--maroon-medium)] text-white p-3 rounded-full hover:shadow-lg transition transform hover:scale-110">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </section>

                <!-- Services Cards -->
                @php
                    $services = \App\Models\Service::active()->ordered()->get();
                @endphp
                
                @if(in_array(Auth::user()->role, ['student', 'counselor', 'admin']))
                    @if($services->isNotEmpty())
                        <div class="mb-12">
                            <h3 class="text-2xl font-bold text-[var(--text-primary)] mb-6 flex items-center">
                                <div class="h-8 w-1 bg-gradient-to-b from-[var(--maroon-soft)] to-[var(--gold-primary)] rounded-full mr-3"></div>
                                <i class="fas fa-hands-helping text-[var(--maroon-soft)] mr-3"></i>Our Services
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($services as $service)
                                    <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border-t-4 border-[var(--gold-primary)] group">
                                        <img src="{{ $service->image_url }}" 
                                             alt="{{ $service->title }}" 
                                             class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">

                                        <div class="p-6">
                                            <h3 class="text-xl font-bold text-[var(--text-primary)] mb-3">
                                                {{ $service->title }}
                                            </h3>

                                            <p class="text-[var(--text-secondary)] mb-4 line-clamp-3">
                                                {{ $service->description }}
                                            </p>

                                            @if(Auth::user()->role === 'student')
                                                @if($service->route_name)
                                                    <a href="{{ route($service->route_name) }}" 
                                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[var(--maroon-soft)] to-[var(--maroon-medium)] text-white font-medium rounded-lg hover:shadow-md transition">
                                                        Learn More <i class="fas fa-arrow-right ml-2"></i>
                                                    </a>
                                                @else
                                                    <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[var(--maroon-soft)] to-[var(--maroon-medium)] text-white font-medium rounded-lg hover:shadow-md transition">
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

                <!-- Divider -->
                <div class="dashboard-divider"></div>

                <!-- Staff Directory Section -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-[var(--text-primary)] mb-2 flex items-center">
                        <div class="h-8 w-1 bg-gradient-to-b from-[var(--maroon-soft)] to-[var(--gold-primary)] rounded-full mr-3"></div>
                        <i class="fas fa-users text-[var(--maroon-soft)] mr-3"></i>Office of Guidance and Counseling
                    </h2>
                    <p class="text-[var(--text-secondary)] mb-6 ml-4">Our dedicated team is here to support you</p>

                    <!-- Head of Office -->
                    @php
                        $headCounselor = \App\Models\Counselor::with(['user', 'college'])
                            ->where('is_head', true)
                            ->first();
                    @endphp

                    @if($headCounselor)
                    <div class="bg-white rounded-xl shadow-lg p-8 mb-8 flex flex-col md:flex-row items-center border-l-4 border-[var(--gold-primary)] hover:shadow-xl transition">
                        <div class="w-32 h-32 bg-gradient-to-br from-[var(--maroon-soft)] to-[var(--maroon-medium)] rounded-full overflow-hidden mb-4 md:mb-0 md:mr-6 shadow-lg ring-4 ring-[var(--gold-primary)]/30">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Head Counselor" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-[var(--text-primary)]">
                                {{ $headCounselor->user->first_name }}
                                {{ $headCounselor->user->middle_name ? $headCounselor->user->middle_name . ' ' : '' }}
                                {{ $headCounselor->user->last_name }}
                            </h3>
                            <p class="text-[var(--gold-primary)] font-semibold text-lg">Head of the Office of Guidance and Counseling</p>
                            <p class="text-[var(--text-secondary)]">{{ $headCounselor->position }} • {{ $headCounselor->credentials }}</p>
                            <p class="text-[var(--text-secondary)]">
                                <i class="fas fa-university mr-2 text-[var(--maroon-soft)]"></i>{{ $headCounselor->college->name ?? 'N/A' }}
                            </p>
                            <p class="text-[var(--text-secondary)] mt-2">
                                <i class="fas fa-envelope mr-2 text-[var(--maroon-soft)]"></i>{{ $headCounselor->user->email }}
                            </p>
                            <p class="text-[var(--text-secondary)] mt-2">
                                As the Head Counselor, {{ $headCounselor->user->first_name }} provides leadership and direction
                                for all guidance and counseling services. With credentials in {{ $headCounselor->credentials }},
                                {{ $headCounselor->user->first_name }} ensures the office delivers comprehensive support to students.
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Staff Grid -->
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
                                <div class="w-24 h-24 bg-gradient-to-br from-[var(--bg-warm)] to-[var(--border-soft)] rounded-full overflow-hidden mx-auto mb-4 ring-2 ring-[var(--gold-primary)]/50">
                                    <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Counselor" class="w-full h-full object-cover">
                                </div>
                                <h4 class="text-lg font-semibold text-[var(--text-primary)]">
                                    {{ $counselor->user->first_name }}
                                    {{ $counselor->user->middle_name ? substr($counselor->user->middle_name, 0, 1) . '. ' : '' }}
                                    {{ $counselor->user->last_name }}
                                </h4>
                                <p class="text-[var(--maroon-soft)] font-medium">{{ $counselor->position }}</p>
                                <p class="text-[var(--text-secondary)] text-sm mt-1">{{ $colleges }}</p>
                                <p class="text-[var(--text-muted)] text-sm mt-1">{{ $counselor->credentials }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gradient-to-r from-[var(--maroon-medium)] to-[var(--maroon-dark)] text-white py-8 mt-8">
            <div class="container mx-auto px-6 text-center">
                <p class="text-gray-300">&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
                <p class="text-xs text-gray-400 mt-2">Committed to your mental health and well-being</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sticky navbar scroll effect
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

            // Dropdown setup function
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

            // Profile dropdown
            const profileBtn = document.getElementById('profile-dropdown-btn');
            const profileMenu = document.getElementById('profile-dropdown-menu');
            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(m => m.classList.add('hidden'));
                    profileMenu.classList.toggle('hidden');
                });
            }

            // Close dropdowns on outside click
            document.addEventListener('click', function(event) {
                if (!event.target.closest('[id$="-dropdown"]') && !event.target.closest('.dashboard-profile-dropdown')) {
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(menu => menu.classList.add('hidden'));
                    if (profileMenu) profileMenu.classList.add('hidden');
                }
            });

            // Prevent closing when clicking inside menus
            document.querySelectorAll('[id$="-dropdown-menu"], .dashboard-profile-dropdown-content').forEach(menu => {
                menu.addEventListener('click', function(e) { e.stopPropagation(); });
            });

            // Announcement slider
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

            // Admin dropdown (legacy support)
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

            // Image error handling
            document.querySelectorAll('.announcement-image').forEach(img => {
                img.addEventListener('error', function() { this.style.display = 'none'; });
            });
        });
    </script>
</body>
</html>