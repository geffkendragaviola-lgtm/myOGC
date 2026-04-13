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
    </style>

    @stack('styles')
</head>
<body>

@if(Auth::check() && Auth::user()->role === 'counselor')

    {{-- ── TOP NAVBAR (Counselor) ── --}}
    <nav class="ogc-navbar fixed top-0 left-0 right-0 h-16 flex items-center justify-between px-6 z-40">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-9 w-9 object-contain" onerror="this.style.display='none'">
            <span class="text-white font-bold text-sm leading-tight hidden md:block">MSU-IIT<br><span class="font-normal text-xs opacity-80">Guidance & Counseling</span></span>
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
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"><i class="fas fa-sign-out-alt mr-2 text-[#F8650C]"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- ── SIDEBAR (Counselor) ── --}}
    <nav class="ogc-sidebar fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 text-white flex flex-col justify-between z-30">
        <div class="overflow-y-auto">
            <div class="p-5 border-b sidebar-divider">
                <div class="font-semibold text-base">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                <div class="text-xs text-red-200 mt-0.5">{{ Auth::user()->email }}</div>
                <span class="inline-block mt-2 text-xs bg-[#F8650C] text-white px-2 py-0.5 rounded-full">Counselor</span>
            </div>
            <div class="mt-4 space-y-0.5 px-3 pb-4">
                <a href="{{ route('counselor.dashboard') }}" class="sidebar-link {{ request()->routeIs('counselor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 w-4 text-center"></i> Dashboard
                </a>
                <a href="{{ route('counselor.calendar') }}" class="sidebar-link {{ request()->routeIs('counselor.calendar') ? 'active' : '' }}">
                    <i class="fas fa-calendar mr-3 w-4 text-center"></i> Calendar
                </a>
                <a href="{{ route('counselor.appointments') }}" class="sidebar-link {{ request()->routeIs('counselor.appointments') ? 'active' : '' }}">
                    <i class="fas fa-list mr-3 w-4 text-center"></i> Appointments
                </a>
                <a href="{{ route('counselor.appointment-sessions.dashboard') }}" class="sidebar-link {{ request()->routeIs('counselor.appointment-sessions.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list mr-3 w-4 text-center"></i> Appointment Sessions
                </a>
                <a href="{{ route('counselor.events.index') }}" class="sidebar-link {{ request()->routeIs('counselor.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt mr-3 w-4 text-center"></i> Events
                </a>
                <a href="{{ route('counselor.announcements.index') }}" class="sidebar-link {{ request()->routeIs('counselor.announcements.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn mr-3 w-4 text-center"></i> Announcements
                </a>
                <a href="{{ route('counselor.resources.index') }}" class="sidebar-link {{ request()->routeIs('counselor.resources.*') ? 'active' : '' }}">
                    <i class="fas fa-box-open mr-3 w-4 text-center"></i> Resources
                </a>
                <a href="{{ route('counselor.feedback.index') }}" class="sidebar-link {{ request()->routeIs('counselor.feedback.*') ? 'active' : '' }}">
                    <i class="fas fa-comments mr-3 w-4 text-center"></i> Feedback
                </a>
                <a href="{{ route('counselor.availability.edit') }}" class="sidebar-link {{ request()->routeIs('counselor.availability.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check mr-3 w-4 text-center"></i> Availability
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
    @include('partials.navbar')
    <main class="min-h-screen">
        @yield('content')
    </main>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.getElementById('profile-dropdown-btn');
    const profileMenu = document.getElementById('profile-dropdown-menu');

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
        document.addEventListener('click', function () {
            profileMenu.classList.add('hidden');
        });
        profileMenu.addEventListener('click', e => e.stopPropagation());
    }
});
</script>
</body>
</html>
