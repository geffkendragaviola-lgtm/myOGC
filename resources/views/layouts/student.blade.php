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

        /* ── Sidebar ── */
        .ogc-sidebar {
            background: linear-gradient(180deg, #820000 0%, #5a0000 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        }
        .ogc-sidebar .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1rem;
            border-radius: 0.5rem;
            color: #fff;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            font-size: 0.9rem;
        }
        .ogc-sidebar .sidebar-link:hover {
            background-color: rgba(255,225,0,0.15);
            color: #FFE100;
        }
        .ogc-sidebar .sidebar-link.active {
            background-color: rgba(248,101,12,0.35);
            color: #FFE100;
            font-weight: 600;
        }
        .ogc-sidebar .sidebar-divider { border-color: rgba(255,255,255,0.15); }

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
    <nav class="ogc-navbar fixed top-0 left-0 right-0 h-16 flex items-center justify-between px-6 z-40">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-9 w-9 object-contain" onerror="this.style.display='none'">
            <span class="text-white font-bold text-sm leading-tight hidden md:block">MSU-IIT<br><span class="font-normal text-xs opacity-80">Guidance & Counseling</span></span>
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
                    <a href="{{ route('profile.edit') }}"><i class="fas fa-user-circle mr-2 text-[#F8650C]"></i> My Profile</a>
                    <a href="{{ route('student.profile') }}"><i class="fas fa-id-card mr-2 text-[#F8650C]"></i> Student Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt mr-2 text-[#F8650C]"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- ── SIDEBAR (Student) ── --}}
    <nav class="ogc-sidebar fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 text-white flex flex-col justify-between z-30">
        <div class="overflow-y-auto">
            <div class="p-5 border-b sidebar-divider">
                <div class="font-semibold text-base">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                <div class="text-xs text-red-200 mt-0.5">{{ Auth::user()->email }}</div>
                <span class="inline-block mt-2 text-xs bg-[#F8650C] text-white px-2 py-0.5 rounded-full">Student</span>
            </div>
            <div class="mt-4 space-y-0.5 px-3 pb-4">
                <a href="{{ route('appointments.index') }}" class="sidebar-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check mr-3 w-4 text-center"></i> My Appointments
                </a>
                <a href="{{ route('student.events.my-registrations') }}" class="sidebar-link {{ request()->routeIs('student.events.my-registrations') ? 'active' : '' }}">
                    <i class="fas fa-list-check mr-3 w-4 text-center"></i> My Registrations
                </a>
                <a href="{{ route('student.events.available') }}" class="sidebar-link {{ request()->routeIs('student.events.available') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt mr-3 w-4 text-center"></i> Available Events
                </a>
                <a href="{{ route('bap') }}" class="sidebar-link {{ request()->routeIs('bap') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle mr-3 w-4 text-center"></i> Book Appointment
                </a>
                <a href="{{ route('mhc') }}" class="sidebar-link {{ request()->routeIs('mhc') ? 'active' : '' }}">
                    <i class="fas fa-heart mr-3 w-4 text-center"></i> Mental Health Corner
                </a>
                <a href="{{ route('feedback') }}" class="sidebar-link {{ request()->routeIs('feedback') ? 'active' : '' }}">
                    <i class="fas fa-comments mr-3 w-4 text-center"></i> Feedback
                </a>
            </div>
        </div>
        <div class="border-t sidebar-divider p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full">
                    <i class="fas fa-sign-out-alt mr-3 w-4 text-center"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="ml-64 pt-16 min-h-screen">
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
    bindDropdown('profile-dropdown-btn', 'profile-dropdown-menu');

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
