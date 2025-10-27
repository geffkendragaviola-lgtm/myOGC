<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .dashboard-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-navbar {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-hero {
            background: linear-gradient(rgba(30, 64, 175, 0.8), rgba(30, 64, 175, 0.8)), url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .dashboard-hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
        }

        .dashboard-hero-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
        }

        .dashboard-quote-section {
            background-color: #fbbf24;
            padding: 2rem 0;
            text-align: center;
        }

        .dashboard-quote-text {
            font-size: 1.5rem;
            font-style: italic;
            color: #78350f;
            max-width: 800px;
            margin: 0 auto;
        }

        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .dashboard-profile-dropdown {
            position: relative;
        }

        .dashboard-profile-dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            padding: 1rem;
            min-width: 200px;
            z-index: 1000;
            margin-top: 0.5rem;
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
        }

        .dashboard-staff-card:hover {
            transform: translateY(-3px);
        }

        .dashboard-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #d1d5db, transparent);
            margin: 3rem 0;
        }

        /* New styles for responsive image container */
        .announcement-image-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background: #f8fafc;
        }

        .announcement-image {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: contain;
            display: block;
        }

        .image-placeholder {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
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

            .image-placeholder {
                height: 200px;
                font-size: 1.2rem;
            }
        }

        @media (max-width: 640px) {
            .announcement-image {
                max-height: 250px;
            }

            .image-placeholder {
                height: 150px;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="dashboard-container">
        <nav class="dashboard-navbar py-4">
            <div class="container mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="text-white font-bold text-2xl mr-10">OGC</div>
                    <div class="hidden md:flex space-x-8">

                        <!-- Counselor Dropdown (if user role is counselor) -->
                        @if(Auth::user()->role === 'counselor')
<div class="relative" id="counselor-dropdown">
    <button
        class="text-white font-semibold hover:text-yellow-300 transition flex items-center"
        id="counselor-dropdown-btn"
    >
        Counselor
        <i class="fas fa-chevron-down ml-1 text-sm"></i>
    </button>

    <div
        class="absolute hidden bg-white rounded-md shadow-lg mt-1 w-48 z-50"
        id="counselor-dropdown-menu"
    >
        <a href="{{ route('counselor.dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>

        <a href="{{ route('counselor.session-notes.dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-sticky-note mr-2"></i> Session Notes
        </a>

        <a href="{{ route('counselor.resources.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-box-open mr-2"></i> Resources
        </a>

        <a href="{{ route('counselor.announcements.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-bullhorn mr-2"></i> Manage Announcements
        </a>

        <a href="{{ route('counselor.events.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-calendar-alt mr-2"></i> Manage Events
        </a>

        <a href="{{ route('counselor.calendar') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-calendar mr-2"></i> Calendar
        </a>

        <a href="{{ route('counselor.appointments') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-list mr-2"></i> Appointments
        </a>
    </div>
</div>

                        @endif
<!-- Admin Dropdown (if user role is admin) -->
@if(Auth::user()->role === 'admin')
<div class="relative" id="admin-dropdown">
    <button
        class="text-white font-semibold hover:text-yellow-300 transition flex items-center"
        id="admin-dropdown-btn"
    >
        Admin
        <i class="fas fa-chevron-down ml-1 text-sm"></i>
    </button>

    <div
        class="absolute hidden bg-white rounded-md shadow-lg mt-1 w-48 z-50"
        id="admin-dropdown-menu"
    >
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>

        <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-users mr-2"></i> Manage Users
        </a>

<a href="{{ route('admin.events') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-calendar-alt mr-2"></i> Manage Events
        </a>
        <a href="{{ route('admin.users.create') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-user-plus mr-2"></i> Create User
        </a>

        <a href="{{ route('admin.students') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-user-graduate mr-2"></i> Students
        </a>

        <a href="{{ route('admin.counselors') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-user-md mr-2"></i> Counselors
        </a>

        <!-- Remove or update the feedback route since it wasn't defined -->
        <!-- <a href="{{ route('admin.feedback.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-comments mr-2"></i> Feedback
        </a> -->
    </div>
</div>
        @endif
<!-- Student Dropdown (if user role is student) -->
@if(Auth::user()->role === 'student')
<div class="relative" id="student-dropdown">
    <button class="text-white font-semibold hover:text-yellow-300 transition flex items-center" id="student-dropdown-btn">
        Student <i class="fas fa-chevron-down ml-1 text-sm"></i>
    </button>
    <div class="absolute hidden bg-white rounded-md shadow-lg py-2 mt-1 w-48 z-50" id="student-dropdown-menu">
        <a href="{{ route('student.show', Auth::user()->student->id) }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-user mr-2"></i>My Profile
        </a>
        <a href="{{ route('appointments.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-calendar-plus mr-2"></i>My Appointments
        </a>
        <a href="{{ route('student.events.my-registrations') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">
            <i class="fas fa-list-check mr-2"></i>My Registrations
        </a>
    </div>
</div>
@endif
                        <a href="#" class="text-white font-semibold hover:text-yellow-300 transition">Home</a>

                        <!-- Services Dropdown -->
                        <div class="relative" id="services-dropdown">
                            <button class="text-white font-semibold hover:text-yellow-300 transition flex items-center" id="services-dropdown-btn">
                                Services <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>
                            <div class="absolute hidden bg-white rounded-md shadow-lg py-2 mt-1 w-48 z-50" id="services-dropdown-menu">
                                <a href="{{ route('bap') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">Book an Appointment</a>
                                <a href="{{ route('mhc') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">Mental Health Corner</a>
                            </div>
                        </div>

                        <a href="{{ route('feedback') }}" class="text-white font-semibold hover:text-yellow-300 transition">Feedback</a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
<div class="flex items-center space-x-4">
    @if(Auth::user()->role === 'student')
        <a href="{{ route('appointments.index') }}"
           class="bg-white text-blue-700 font-semibold py-2 px-4 rounded-lg flex items-center hover:bg-blue-50 transition">
            <i class="fas fa-calendar-check mr-2"></i> My Appointment
        </a>
    @elseif(Auth::user()->role === 'counselor')
        <a href="{{ route('counselor.appointments') }}"
           class="bg-white text-blue-700 font-semibold py-2 px-4 rounded-lg flex items-center hover:bg-blue-50 transition">
            <i class="fas fa-calendar-check mr-2"></i> My Appointment
        </a>
    @endif
</div>



                    <button class="text-white p-2 rounded-full hover:bg-blue-700 transition">
                        <i class="fas fa-bell"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="dashboard-profile-dropdown">
                        <button class="text-white p-2 rounded-full hover:bg-blue-700 transition" id="profile-dropdown-btn">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="dashboard-profile-dropdown-content hidden" id="profile-dropdown-menu">
                            <div class="mb-3 border-b pb-2">
                                <div class="font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                                <div class="text-xs text-blue-600 capitalize">Role: {{ Auth::user()->role }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="w-full text-left block py-2 text-gray-700 hover:text-blue-600">
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
            <div class="dashboard-hero-overlay"></div>
            <div class="container mx-auto text-center px-6 z-10">
                <h1 class="dashboard-hero-title">Office of Guidance and Counseling</h1>
            </div>
        </header>

        <!-- Quote Section -->
        <section class="dashboard-quote-section">
            <div class="container mx-auto px-6">
                <blockquote class="dashboard-quote-text">
                    "Make it a daily practice to purposefully look for joy — and when you find it, take a moment, inhale it, treasure it, and take it with you."
                </blockquote>
            </div>
        </section>

        <!-- Dashboard Content -->
        <div class="container mx-auto px-6 py-8">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome, {{ Auth::user()->first_name }}!</h2>
                <p class="text-gray-600">You're logged in as: <span class="font-semibold capitalize text-blue-600">{{ Auth::user()->role }}</span></p>

                <!-- Role-specific content -->
                @if(Auth::user()->role === 'student')
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800">Student Dashboard</h3>
                    <p class="mt-2 text-blue-600">Welcome to your student portal! Here you can book appointments, access resources, and more.</p>
                </div>
                @elseif(Auth::user()->role === 'counselor')
                <div class="mt-6 p-4 bg-green-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-green-800">Counselor Dashboard</h3>
                    <p class="mt-2 text-green-600">Welcome to your counseling portal! Manage appointments and access counselor resources.</p>
                </div>
                @elseif(Auth::user()->role === 'admin')
                <div class="mt-6 p-4 bg-purple-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-purple-800">Admin Dashboard</h3>
                    <p class="mt-2 text-purple-600">Welcome to the administration panel! Manage users, appointments, and system settings.</p>
                </div>
                @endif
            </div>

            <!-- Services Cards -->
            @php
                $services = \App\Models\Service::active()->ordered()->get();
            @endphp

            @if($services->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    @foreach($services as $service)
                        <div class="dashboard-card bg-white rounded-xl shadow-md overflow-hidden">
                            <img src="{{ $service->image_url }}" alt="{{ $service->title }}" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $service->title }}</h3>
                                <p class="text-gray-600 mb-4">{{ $service->description }}</p>

                                @if($service->route_name)
                                    <a href="{{ route($service->route_name) }}" class="dashboard-btn bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition inline-block">
                                        See more
                                    </a>
                                @else
                                    <button class="dashboard-btn bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                        See more
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

     <!-- Announcements Section -->
<section class="mb-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Announcements</h2>

    @php
        use Carbon\Carbon;
        // Get announcements that are active and available for the user's college
        $userCollegeId = Auth::user()->student->college_id ?? null;
        $announcements = \App\Models\Announcement::with(['user', 'colleges'])
            ->active()
            ->when($userCollegeId, function($query) use ($userCollegeId) {
                return $query->forCollege($userCollegeId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp

    <div class="bg-white rounded-xl shadow-md p-6">
        @if($announcements->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-bullhorn text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No current announcements at this time.</p>
            </div>
        @else
            <div class="dashboard-announcements-container">
                @foreach($announcements as $index => $announcement)
                    <div class="dashboard-announcement-item {{ $index === 0 ? 'dashboard-announcement-active' : '' }}">
                        <!-- Announcement Header -->
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4 gap-3">
                            <div class="flex items-center space-x-3">
                                <!-- College Badge -->
                                <div class="flex flex-wrap gap-2">
                                    @if($announcement->for_all_colleges)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">
                                            <i class="fas fa-globe mr-1"></i> All Colleges
                                        </span>
                                    @else
                                        @foreach($announcement->colleges->take(3) as $college)
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                                                <i class="fas fa-university mr-1"></i> {{ $college->name }}
                                            </span>
                                        @endforeach
                                        @if($announcement->colleges->count() > 3)
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                                                +{{ $announcement->colleges->count() - 3 }} more
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Announcement Content with Image -->
                        <div class="flex flex-col lg:flex-row gap-8">
                            <!-- Image Section - Only show if image exists -->
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

                            <!-- Text Content Section - Full width if no image -->
                            <div class="{{ $announcement->image_url ? 'lg:w-3/5' : 'w-full' }}">
                                <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $announcement->title }}</h3>

                                <div class="text-gray-700 whitespace-pre-line leading-relaxed mb-6 text-lg">
                                    {{ $announcement->content }}
                                </div>

                                <!-- Announcement Meta Information -->
                                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mt-6 pt-4 border-t border-gray-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2"></i>
                                        Posted by: {{ $announcement->user->first_name }} {{ $announcement->user->last_name }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Posted: {{ $announcement->created_at->format('M j, Y') }}
                                    </div>
                                    @if($announcement->start_date || $announcement->end_date)
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-2"></i>
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
                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
                    <!-- Announcement Counter -->
                    <div class="text-sm text-gray-500">
                        Announcement <span id="current-announcement">1</span> of {{ $announcements->count() }}
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex space-x-2">
                        <button class="dashboard-prev bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition shadow-md">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="dashboard-next bg-blue-600 text-white p-3 rounded-full hover:bg-blue-700 transition shadow-md">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>

            <!-- Divider -->
            <div class="dashboard-divider"></div>

            <!-- Staff Directory Section -->
            <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Office of Guidance and Counseling</h2>
                <p class="text-gray-600 mb-6">Directory</p>

                <!-- Head of Office -->
                @php
                    $headCounselor = \App\Models\Counselor::with(['user', 'college'])
                        ->where('is_head', true)
                        ->first();
                @endphp

                @if($headCounselor)
                <div class="bg-white rounded-xl shadow-md p-6 mb-8 flex flex-col md:flex-row items-center">
                    <div class="w-32 h-32 bg-gray-200 rounded-full overflow-hidden mb-4 md:mb-0 md:mr-6">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Head Counselor" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            {{ $headCounselor->user->first_name }}
                            {{ $headCounselor->user->middle_name ? $headCounselor->user->middle_name . ' ' : '' }}
                            {{ $headCounselor->user->last_name }}
                        </h3>
                        <p class="text-gray-600">Head of the Office of Guidance and Counseling</p>
                        <p class="text-gray-600">{{ $headCounselor->position }} • {{ $headCounselor->credentials }}</p>
                        <p class="text-gray-600">{{ $headCounselor->college->name ?? 'N/A' }}</p>
                        <p class="text-gray-600 mt-2">
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

                    // Group counselors by user_id to handle multiple college assignments
                    $groupedCounselors = $counselors->groupBy('user_id');
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($groupedCounselors as $userCounselors)
                        @php
                            $counselor = $userCounselors->first();
                            $colleges = $userCounselors->pluck('college.name')->filter()->implode(', ');
                        @endphp
                        <div class="dashboard-staff-card bg-white rounded-xl shadow-md p-6 text-center">
                            <div class="w-24 h-24 bg-gray-200 rounded-full overflow-hidden mx-auto mb-4">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" alt="Counselor" class="w-full h-full object-cover">
                            </div>
                            <h4 class="text-lg font-semibold text-gray-800">
                                {{ $counselor->user->first_name }}
                                {{ $counselor->user->middle_name ? substr($counselor->user->middle_name, 0, 1) . '. ' : '' }}
                                {{ $counselor->user->last_name }}
                            </h4>
                            <p class="text-gray-600">{{ $counselor->position }}</p>
                            <p class="text-gray-600">{{ $colleges }}</p>
                            <p class="text-gray-600">{{ $counselor->credentials }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-6 text-center">
                <p>&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to setup dropdown with click
            function setupDropdown(dropdownId, btnId, menuId) {
                const dropdown = document.getElementById(dropdownId);
                const btn = document.getElementById(btnId);
                const menu = document.getElementById(menuId);

                if (!btn || !menu) return;

                btn.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Close all other dropdowns
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(m => {
                        if (m !== menu) {
                            m.classList.add('hidden');
                        }
                    });

                    // Toggle current dropdown
                    menu.classList.toggle('hidden');
                });
            }

            // Setup all dropdowns
            setupDropdown('counselor-dropdown', 'counselor-dropdown-btn', 'counselor-dropdown-menu');
            setupDropdown('student-dropdown', 'student-dropdown-btn', 'student-dropdown-menu');
            setupDropdown('services-dropdown', 'services-dropdown-btn', 'services-dropdown-menu');

            // Profile dropdown
            const profileBtn = document.getElementById('profile-dropdown-btn');
            const profileMenu = document.getElementById('profile-dropdown-menu');

            if (profileBtn && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Close other dropdowns
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(m => {
                        m.classList.add('hidden');
                    });

                    profileMenu.classList.toggle('hidden');
                });
            }

            // Close all dropdowns when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('[id$="-dropdown"]') && !event.target.closest('.dashboard-profile-dropdown')) {
                    document.querySelectorAll('[id$="-dropdown-menu"]').forEach(menu => {
                        menu.classList.add('hidden');
                    });
                    if (profileMenu) {
                        profileMenu.classList.add('hidden');
                    }
                }
            });

            // Prevent dropdown from closing when clicking inside menu
            document.querySelectorAll('[id$="-dropdown-menu"]').forEach(menu => {
                menu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
            if (profileMenu) {
                profileMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

// Announcement slider functionality
const announcements = document.querySelectorAll('.dashboard-announcement-item');
const prevBtn = document.querySelector('.dashboard-prev');
const nextBtn = document.querySelector('.dashboard-next');
const currentCounter = document.getElementById('current-announcement');
let currentIndex = 0;

if (announcements.length > 0 && prevBtn && nextBtn) {
    function showAnnouncement(index) {
        announcements.forEach(ann => ann.classList.remove('dashboard-announcement-active'));
        announcements[index].classList.add('dashboard-announcement-active');

        // Update counter
        if (currentCounter) {
            currentCounter.textContent = index + 1;
        }
    }

    prevBtn.addEventListener('click', function() {
        currentIndex = (currentIndex - 1 + announcements.length) % announcements.length;
        showAnnouncement(currentIndex);
    });

    nextBtn.addEventListener('click', function() {
        currentIndex = (currentIndex + 1) % announcements.length;
        showAnnouncement(currentIndex);
    });

    // Auto-advance announcements every 8 seconds
    setInterval(() => {
        if (announcements.length > 1) {
            currentIndex = (currentIndex + 1) % announcements.length;
            showAnnouncement(currentIndex);
        }
    }, 8000);
}
        });

        // Admin Dropdown functionality
const adminDropdownBtn = document.getElementById('admin-dropdown-btn');
const adminDropdownMenu = document.getElementById('admin-dropdown-menu');

if (adminDropdownBtn && adminDropdownMenu) {
    adminDropdownBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        adminDropdownMenu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
        adminDropdownMenu.classList.add('hidden');
    });

    // Prevent dropdown from closing when clicking inside
    adminDropdownMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
}

// Image error handling - simplified to just hide broken images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.announcement-image');
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.style.display = 'none';
        });
    });
});
    </script>
</body>
</html>
