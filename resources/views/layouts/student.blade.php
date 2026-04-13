<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Office of Guidance and Counseling')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { overflow-x: hidden; background-color: #f9fafb; }

        /* ── Navbar ── */
        .ogc-navbar {
            background: linear-gradient(135deg, #820000 0%, #F8650C 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .ogc-navbar a, .ogc-navbar button { color: #fff; }
        .ogc-navbar a:hover { color: #FFE100; }
        .ogc-nav-icon:hover { background-color: rgba(255,255,255,0.15); }

        /* ── Sidebar (Original Admin Colors) ── */
        .ogc-sidebar {
            background: linear-gradient(180deg, #820000 0%, #5a0000 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        }
        
        /* Sidebar scrollbar - thin and clean */
        .ogc-sidebar .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.25) transparent;
        }
        .ogc-sidebar .overflow-y-auto::-webkit-scrollbar {
            width: 4px;
        }
        .ogc-sidebar .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }
        .ogc-sidebar .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.25);
            border-radius: 4px;
        }
        
        .ogc-sidebar .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.875rem;
            margin: 0.25rem 0;
            border-radius: 0.75rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.25s ease;
            font-size: 0.875rem;
            font-weight: 500;
            gap: 0.75rem;
        }
        .ogc-sidebar .sidebar-link i {
            width: 1.25rem;
            font-size: 1rem;
            text-align: center;
            transition: all 0.25s ease;
        }
        .ogc-sidebar .sidebar-link:hover {
            background-color: rgba(255,225,0,0.15);
            color: #FFE100;
            transform: translateX(4px);
        }
        .ogc-sidebar .sidebar-link:hover i {
            transform: scale(1.05);
        }
        .ogc-sidebar .sidebar-link.active {
            background-color: rgba(248,101,12,0.35);
            color: #FFE100;
            font-weight: 600;
            border-left: 3px solid #FFE100;
        }
        .ogc-sidebar .sidebar-link.active i {
            color: #FFE100;
        }
        .ogc-sidebar .sidebar-divider { 
            border-color: rgba(255,255,255,0.12);
            margin: 0.5rem 0;
        }
        
        /* User Profile Section in Sidebar - Compact */
        .sidebar-user-section {
            padding: 0.875rem 1rem 0.75rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 0.5rem;
        }
        .sidebar-user-avatar {
            width: 2.25rem;
            height: 2.25rem;
            background: linear-gradient(135deg, #F8650C, #820000);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .sidebar-user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: white;
            line-height: 1.3;
        }
        .sidebar-user-email {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.6);
            word-break: break-all;
        }
        
        /* Logout button styling */
        .logout-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.875rem;
            margin: 0.25rem 0;
            border-radius: 0.75rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.25s ease;
            font-size: 0.875rem;
            font-weight: 500;
            width: 100%;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        .logout-link i {
            width: 1.25rem;
            font-size: 1rem;
            text-align: center;
        }
        .logout-link:hover {
            background-color: rgba(255,225,0,0.15);
            color: #FFE100;
            transform: translateX(4px);
        }

        /* Section header styling */
        .sidebar-section-header {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 0.875rem 0.25rem;
        }

        /* ── Navbar dropdown ── */
        .ogc-nav-dropdown { position: relative; }
        .ogc-nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            min-width: 180px;
            z-index: 50;
            padding: 0.5rem 0;
        }
        .ogc-nav-dropdown-menu a {
            display: block;
            padding: 0.5rem 1rem;
            color: #374151;
            font-size: 0.875rem;
            transition: background 0.15s;
        }
        .ogc-nav-dropdown-menu a:hover { background: #FFF9E6; color: #820000; }

        /* ── Profile dropdown ── */
        .ogc-profile-dropdown { position: relative; }
        .ogc-profile-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 0.5rem);
            background: #fff;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            border-radius: 10px;
            padding: 1rem;
            min-width: 210px;
            z-index: 1000;
        }
        .ogc-profile-menu a, .ogc-profile-menu button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.5rem 0;
            color: #374151;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: color 0.2s;
        }
        .ogc-profile-menu a:hover, .ogc-profile-menu button:hover { color: #820000; }

        /* ── Bootstrap tab override ── */
        .nav-tabs .nav-link { color: #6c757d; font-weight: 500; border: none; border-bottom: 3px solid transparent; padding: 0.75rem 1.5rem; transition: all 0.3s; }
        .nav-tabs .nav-link.active { color: #820000; font-weight: 600; background: transparent; border-bottom: 3px solid #820000; }
        .nav-tabs .nav-link:hover { border-color: transparent; border-bottom: 3px solid #FFC917; color: #333; }
    </style>

    @stack('styles')
</head>
<body>

@if(Auth::check() && Auth::user()->role === 'student')

    {{-- ── TOP NAVBAR (Student) ── --}}
    <nav class="ogc-navbar fixed top-0 left-0 right-0 h-14 flex items-center justify-between px-6 z-40">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
            <span class="text-white font-bold text-sm leading-tight hidden md:block">MSU-IIT<br><span class="font-normal text-xs opacity-80">Student Portal</span></span>
        </div>

        <div class="hidden md:flex items-center space-x-6 absolute left-1/2 -translate-x-1/2">
            <a href="{{ route('dashboard') }}" class="text-white font-medium text-sm hover:text-[#FFE100] transition">Home</a>

            <div class="ogc-nav-dropdown" id="dd-services">
                <button class="text-white font-medium text-sm hover:text-[#FFE100] transition flex items-center" id="dd-services-btn">
                    Services <i class="fas fa-chevron-down ml-1 text-xs"></i>
                </button>
                <div class="ogc-nav-dropdown-menu hidden" id="dd-services-menu">
                    <a href="{{ route('bap') }}"><i class="fas fa-calendar-plus mr-2 text-[#F8650C]"></i> Book Appointment</a>
                    <a href="{{ route('mhc') }}"><i class="fas fa-heart mr-2 text-[#F8650C]"></i> Mental Health Corner</a>
                </div>
            </div>

            <a href="{{ route('feedback') }}" class="text-white font-medium text-sm hover:text-[#FFE100] transition">Feedback</a>
        </div>

        <div class="flex items-center space-x-3 ml-auto">
            <button class="ogc-nav-icon text-white p-2 rounded-full transition">
                <i class="fas fa-bell"></i>
            </button>
            <div class="ogc-profile-dropdown">
                <button class="ogc-nav-icon text-white p-2 rounded-full transition" id="profile-dropdown-btn">
                    <i class="fas fa-user-circle text-lg"></i>
                </button>
                <div class="ogc-profile-menu hidden" id="profile-dropdown-menu">
                    <div class="mb-3 pb-3 border-b border-gray-100">
                        <div class="font-semibold text-gray-800 text-sm">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</div>
                        <span class="inline-block mt-1 text-xs bg-[#820000] text-white px-2 py-0.5 rounded-full capitalize">{{ Auth::user()->role }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}"><i class="fas fa-user-circle mr-2 text-[#F8650C]"></i> Profile Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt mr-2 text-[#F8650C]"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- ── SIDEBAR (Student) - With Student Profile as a Tab ── --}}
    <nav class="ogc-sidebar fixed top-14 left-0 h-[calc(100vh-3.5rem)] w-64 flex flex-col z-30">
        <!-- User Profile Section - Compact -->
        <div class="sidebar-user-section">
            <div class="flex items-center gap-3">
                <div class="sidebar-user-avatar">
                    <i class="fas fa-user-graduate text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="sidebar-user-name truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                    <div class="sidebar-user-email truncate">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="mt-2">
                <span class="inline-flex items-center gap-1 text-xs bg-[#F8650C]/30 text-[#FFE100] px-2 py-0.5 rounded-full">
                    <i class="fas fa-graduation-cap text-[10px]"></i> Student
                </span>
            </div>
        </div>

        <!-- Navigation Links - Compact -->
        <div class="flex-1 overflow-y-auto px-3 pb-2">
            <div class="space-y-0.5">
                <!-- Student Profile Tab (moved from navbar to sidebar) -->
                <a href="{{ route('student.show', Auth::user()->student->id) }}" class="sidebar-link {{ request()->routeIs('student.show') ? 'active' : '' }}">
                    <i class="fas fa-id-card"></i>
                    <span>Student Profile</span>
                </a>
                
                <a href="{{ route('appointments.index') }}" class="sidebar-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>My Appointments</span>
                </a>
                <a href="{{ route('student.events.my-registrations') }}" class="sidebar-link {{ request()->routeIs('student.events.my-registrations') ? 'active' : '' }}">
                    <i class="fas fa-list-check"></i>
                    <span>My Registrations</span>
                </a>
                <a href="{{ route('student.events.available') }}" class="sidebar-link {{ request()->routeIs('student.events.available') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Available Events</span>
                </a>
                
                <a href="{{ route('bap') }}" class="sidebar-link {{ request()->routeIs('bap') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    <span>Book Appointment</span>
                </a>
            </div>
        </div>

        <!-- Logout Section - Compact -->
        <div class="border-t border-white/15 p-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <div class="ml-64 pt-14 min-h-screen">
        @yield('content')
    </div>

@else
    <main class="min-h-screen">
        @yield('content')
    </main>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    function bindDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        if (!btn || !menu) return;
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            document.querySelectorAll('.ogc-nav-dropdown-menu, .ogc-profile-menu').forEach(m => {
                if (m !== menu) m.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        });
        menu.addEventListener('click', e => e.stopPropagation());
    }

    bindDropdown('dd-services-btn', 'dd-services-menu');

    // Profile dropdown functionality
    const profileBtn = document.getElementById('profile-dropdown-btn');
    const profileMenu = document.getElementById('profile-dropdown-menu');

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            document.querySelectorAll('.ogc-nav-dropdown-menu, .ogc-profile-menu').forEach(m => {
                if (m !== profileMenu) m.classList.add('hidden');
            });
            profileMenu.classList.toggle('hidden');
        });
        profileMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function () {
        document.querySelectorAll('.ogc-nav-dropdown-menu, .ogc-profile-menu').forEach(m => m.classList.add('hidden'));
    });

    // Bootstrap tabs
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(el => {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            new bootstrap.Tab(this).show();
        });
    });
});
</script>
</body>
</html>