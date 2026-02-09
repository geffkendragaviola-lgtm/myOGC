<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Office of Guidance and Counseling')</title>
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

        /* Sidebar styles */
        .sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #2563eb 100%);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.15);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: white;
            transition: background 0.3s;
        }

        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .dashboard-hero {
            background: linear-gradient(rgba(30, 64, 175, 0.8), rgba(30, 64, 175, 0.8)),
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3');
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

        body {
            overflow-x: hidden;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 dashboard-container">

    {{-- ✅ Sidebar for counselors --}}
    @if(Auth::check() && Auth::user()->role === 'counselor')
        <!-- Top Navbar for Counselors -->
        <nav class="dashboard-navbar fixed top-0 left-0 right-0 h-16 flex items-center justify-between px-6 z-40">
            <!-- Centered Navigation Items -->
            <div class="flex items-center space-x-8 absolute left-1/2 transform -translate-x-1/2">
                <a href="{{ route('dashboard') }}" class="text-white font-semibold hover:text-yellow-300 transition">Home</a>

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

            <div class="flex items-center space-x-4 ml-auto">


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
        </nav>

        <!-- Sidebar for Counselors -->
        <nav class="sidebar fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 text-white flex flex-col justify-between z-30">
            <div>
                <!-- User Info in Sidebar Header -->
                <div class="p-6 border-b border-blue-700">
                    <div class="font-semibold text-lg">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                    <div class="text-sm text-gray-300">{{ Auth::user()->email }}</div>
                    <!-- Add college information here if available -->
                    <div class="text-xs text-gray-400 mt-1">
                        @if(Auth::user()->college)
                            {{ Auth::user()->college }}
                        @else

                        @endif
                    </div>
                </div>

                <div class="mt-6 space-y-1 px-3">
                    <a href="{{ route('counselor.dashboard') }}"><i class="fas fa-tachometer-alt mr-3"></i> Dashboard</a>
                    <a href="{{ route('counselor.session-notes.dashboard') }}"><i class="fas fa-sticky-note mr-3"></i> Session Notes</a>
                    <a href="{{ route('counselor.resources.index') }}"><i class="fas fa-box-open mr-3"></i> Resources</a>
                    <a href="{{ route('counselor.announcements.index') }}"><i class="fas fa-bullhorn mr-3"></i> Announcements</a>
                    <a href="{{ route('counselor.events.index') }}"><i class="fas fa-calendar-alt mr-3"></i> Events</a>
                    <a href="{{ route('counselor.calendar') }}"><i class="fas fa-calendar mr-3"></i> Calendar</a>
                    <a href="{{ route('counselor.appointments') }}"><i class="fas fa-list mr-3"></i> Appointments</a>
                    <!-- ADDED FEEDBACK LINK -->
                    <a href="{{ route('counselor.feedback.index') }}"><i class="fas fa-comments mr-3"></i> Feedback</a>
                    <a href="{{ route('counselor.availability.edit') }}"><i class="fas fa-calendar-check mr-3"></i> Availability &amp; Booking Limits</a>
                </div>
            </div>

            <!-- Removed Profile Section from Sidebar -->
            <div class="border-t border-blue-700 p-4">
                <div class="space-y-2">
                    <!-- Only showing logout in sidebar now -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full text-left text-white hover:bg-blue-700 p-2 rounded"><i class="fas fa-sign-out-alt mr-3"></i> Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        {{-- Shift main content to the right when sidebar is visible --}}
        <div class="ml-64 pt-16 min-h-screen">
            @yield('content')
        </div>
    @else
        {{-- ✅ Default Top Navbar for Students / Guests --}}
        @include('partials.navbar')

        <main class="min-h-screen">
            @yield('content')
        </main>
    @endif

    {{-- ✅ Reusable Footer --}}
   {{--   @include('partials.footer')--}}
    @stack('scripts')

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
        });
    </script>
</body>
</html>
