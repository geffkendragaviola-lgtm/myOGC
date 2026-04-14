<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - OGC')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --ogc-maroon: #820000;
            --ogc-maroon-dark: #5a0000;
            --ogc-orange: #F8650C;
            --ogc-gold: #FFE100;
            --ogc-cream: #fff7f2;
            --ogc-surface: #ffffff;
            --ogc-surface-soft: #f8fafc;
            --ogc-border: rgba(15, 23, 42, 0.08);
            --ogc-text: #0f172a;
            --ogc-muted: #64748b;
            --ogc-shadow: 0 18px 45px rgba(130, 0, 0, 0.10);
            --ogc-shadow-soft: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            overflow-x: hidden;
            background:
                radial-gradient(circle at top left, rgba(248, 101, 12, 0.08), transparent 28%),
                radial-gradient(circle at top right, rgba(130, 0, 0, 0.08), transparent 24%),
                linear-gradient(180deg, #fffaf8 0%, #f8fafc 48%, #f5f7fb 100%);
            color: var(--ogc-text);
        }

        /* ── Navbar ── */
        .ogc-navbar {
            height: 4rem;
            background:
                linear-gradient(135deg, rgba(130, 0, 0, 0.96) 0%, rgba(168, 23, 23, 0.94) 45%, rgba(248, 101, 12, 0.94) 100%);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,0.10);
            box-shadow: 0 10px 30px rgba(130, 0, 0, 0.22);
        }

        .ogc-navbar::after {
            content: "";
            position: absolute;
            inset: auto 0 0 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.32), transparent);
        }

        .ogc-navbar a,
        .ogc-navbar button {
            color: #fff;
        }

        .ogc-nav-icon {
            width: 2.6rem;
            height: 2.6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.10);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);
            transition: all 0.25s ease;
        }

        .ogc-nav-icon:hover {
            background: rgba(255,255,255,0.18);
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.16);
        }

        .ogc-brand-badge {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 0.9rem;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.12);
        }

        .ogc-brand-text {
            line-height: 1.1;
            letter-spacing: 0.01em;
        }

        .ogc-brand-text .sub {
            color: rgba(255,255,255,0.74);
        }

        /* ── Sidebar ── */
        .ogc-sidebar {
            background:
                linear-gradient(180deg, rgba(81, 0, 0, 0.98) 0%, rgba(107, 0, 0, 0.97) 26%, rgba(130, 0, 0, 0.96) 65%, rgba(86, 0, 0, 0.98) 100%);
            border-right: 1px solid rgba(255,255,255,0.08);
            box-shadow: 8px 0 28px rgba(46, 1, 1, 0.18);
            overflow: hidden;
        }

        .ogc-sidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at top left, rgba(255,225,0,0.09), transparent 22%),
                radial-gradient(circle at bottom left, rgba(248,101,12,0.10), transparent 24%);
        }

        .ogc-sidebar .overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.20) transparent;
        }

        .ogc-sidebar .overflow-y-auto::-webkit-scrollbar {
            width: 5px;
        }

        .ogc-sidebar .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .ogc-sidebar .overflow-y-auto::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.20);
            border-radius: 999px;
        }

        .sidebar-user-section {
            position: relative;
            padding: 1.15rem 1rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.09);
            background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.015));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .sidebar-user-card {
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.05);
            border-radius: 1rem;
            padding: 0.9rem;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
        }

        .sidebar-user-avatar {
            width: 2.7rem;
            height: 2.7rem;
            background: linear-gradient(135deg, #ff8a3d, #f8650c 42%, #820000 100%);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 10px 22px rgba(0,0,0,0.22),
                inset 0 1px 0 rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.14);
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-weight: 700;
            font-size: 0.92rem;
            color: #fff;
            line-height: 1.3;
        }

        .sidebar-user-email {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.64);
            word-break: break-word;
            margin-top: 0.15rem;
        }

        .sidebar-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.7rem;
            font-size: 0.72rem;
            font-weight: 600;
            color: #fff1a6;
            padding: 0.42rem 0.7rem;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(248,101,12,0.28), rgba(255,225,0,0.10));
            border: 1px solid rgba(255,225,0,0.18);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.06);
        }

        .sidebar-link,
        .logout-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            width: 100%;
            padding: 0.8rem 0.9rem;
            margin: 0.22rem 0;
            border-radius: 1rem;
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.24s ease;
            border: 1px solid transparent;
            background: transparent;
        }

        .sidebar-link i,
        .logout-link i {
            width: 1.2rem;
            text-align: center;
            font-size: 0.98rem;
            transition: all 0.24s ease;
            flex-shrink: 0;
        }

        .sidebar-link:hover,
        .logout-link:hover {
            color: #fff;
            background: linear-gradient(135deg, rgba(255,255,255,0.10), rgba(255,255,255,0.04));
            border-color: rgba(255,255,255,0.08);
            transform: translateX(4px);
            box-shadow: 0 10px 18px rgba(0,0,0,0.12);
        }

        .sidebar-link:hover i,
        .logout-link:hover i {
            color: #FFE100;
            transform: scale(1.06);
        }

        .sidebar-link.active {
            color: #fff;
            font-weight: 700;
            background:
                linear-gradient(135deg, rgba(248,101,12,0.30), rgba(255,225,0,0.08));
            border: 1px solid rgba(255,225,0,0.18);
            box-shadow:
                0 12px 24px rgba(0,0,0,0.14),
                inset 0 1px 0 rgba(255,255,255,0.08);
        }

        .sidebar-link.active::before {
            content: "";
            position: absolute;
            left: 0.45rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0.28rem;
            height: 1.35rem;
            border-radius: 999px;
            background: linear-gradient(180deg, #FFE100, #F8650C);
            box-shadow: 0 0 12px rgba(255,225,0,0.35);
        }

        .sidebar-link.active i {
            color: #FFE100;
        }

        .sidebar-divider {
            border: 0;
            height: 1px;
            margin: 0.8rem 0 0.65rem;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.14), transparent);
        }

        .sidebar-section-header {
            font-size: 0.68rem;
            font-weight: 700;
            color: rgba(255,255,255,0.42);
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 0.4rem 0.9rem 0.35rem;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.10);
            padding: 0.9rem 0.75rem;
            background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
        }

        /* ── Dropdowns ── */
        .ogc-nav-dropdown,
        .ogc-profile-dropdown {
            position: relative;
        }

        .ogc-nav-dropdown-menu,
        .ogc-profile-menu {
            background: rgba(255,255,255,0.94);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(15,23,42,0.08);
            box-shadow: 0 22px 50px rgba(15,23,42,0.16);
        }

        .ogc-nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 0.6rem);
            left: 0;
            border-radius: 1rem;
            min-width: 12rem;
            z-index: 50;
            padding: 0.5rem;
        }

        .ogc-nav-dropdown-menu a {
            display: block;
            padding: 0.7rem 0.85rem;
            border-radius: 0.8rem;
            color: #334155;
            font-size: 0.875rem;
            transition: all 0.18s ease;
        }

        .ogc-nav-dropdown-menu a:hover {
            background: #fff7ed;
            color: var(--ogc-maroon);
        }

        .ogc-profile-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 0.7rem);
            border-radius: 1.1rem;
            padding: 0.9rem;
            min-width: 16rem;
            z-index: 1000;
        }

        .ogc-profile-menu a,
        .ogc-profile-menu button {
            display: flex;
            align-items: center;
            width: 100%;
            text-align: left;
            padding: 0.8rem 0.9rem;
            color: #334155;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 0.89rem;
            border-radius: 0.85rem;
            transition: all 0.18s ease;
        }

        .ogc-profile-menu a:hover,
        .ogc-profile-menu button:hover {
            background: #fff7ed;
            color: var(--ogc-maroon);
        }

        .ogc-profile-summary {
            margin-bottom: 0.75rem;
            padding: 0.1rem 0.2rem 0.8rem;
            border-bottom: 1px solid rgba(15,23,42,0.07);
        }

        .ogc-profile-summary .name {
            font-weight: 700;
            color: #0f172a;
            font-size: 0.92rem;
        }

        .ogc-profile-summary .email {
            font-size: 0.77rem;
            color: #64748b;
            margin-top: 0.2rem;
            word-break: break-word;
        }

        .ogc-profile-role {
            display: inline-flex;
            align-items: center;
            margin-top: 0.55rem;
            font-size: 0.72rem;
            font-weight: 700;
            color: white;
            padding: 0.34rem 0.62rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #820000, #F8650C);
            box-shadow: 0 8px 16px rgba(130,0,0,0.16);
        }

        /* ── Main content wrapper ── */
        .ogc-main-shell {
            min-height: 100vh;
            position: relative;
        }

        .ogc-main-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.18), transparent 16%);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .ogc-sidebar {
                width: 16rem;
            }

            .ml-64 {
                margin-left: 16rem;
            }
        }

        @media (max-width: 768px) {
            .ogc-navbar {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .ogc-sidebar {
                width: 15rem;
            }

            .ml-64 {
                margin-left: 15rem;
            }

            .sidebar-user-email {
                max-width: 100%;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

@if(Auth::check() && Auth::user()->role === 'admin')

    {{-- ── TOP NAVBAR (Admin) ── --}}
    <nav class="ogc-navbar fixed top-0 left-0 right-0 flex items-center justify-between px-6 z-40">
        <div class="flex items-center space-x-3">
            <div class="ogc-brand-badge">
                <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
            </div>
            <span class="ogc-brand-text text-white font-bold text-sm hidden md:block">
                MSU-IIT<br>
                <span class="sub font-medium text-xs">Admin Panel</span>
            </span>
        </div>

        <div class="flex items-center space-x-3 ml-auto">
            <button class="ogc-nav-icon transition">
                <i class="fas fa-bell"></i>
            </button>
            <div class="ogc-profile-dropdown">
                <button class="ogc-nav-icon transition" id="profile-dropdown-btn">
                    <i class="fas fa-user-circle text-lg"></i>
                </button>
                <div class="ogc-profile-menu hidden" id="profile-dropdown-menu">
                    <div class="ogc-profile-summary">
                        <div class="name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="email">{{ Auth::user()->email }}</div>
                        <span class="ogc-profile-role capitalize">{{ Auth::user()->role }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}">
                        <i class="fas fa-user-circle mr-3 text-[#F8650C]"></i> Profile Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt mr-3 text-[#F8650C]"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- ── SIDEBAR (Admin) ── --}}
    <nav class="ogc-sidebar fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 flex flex-col z-30">
        <div class="sidebar-user-section">
            <div class="sidebar-user-card">
                <div class="flex items-center gap-3">
                    <div class="sidebar-user-avatar">
                        <i class="fas fa-user-shield text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="sidebar-user-name truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="sidebar-user-email truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <span class="sidebar-role-pill">
                    <i class="fas fa-shield-alt text-[10px]"></i> Administrator
                </span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-3 pb-3">
            <div class="space-y-0.5 pt-2">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') && !request()->routeIs('admin.users.create') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                <a href="{{ route('admin.students') }}" class="sidebar-link {{ request()->routeIs('admin.students') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i>
                    <span>Students</span>
                </a>
                <a href="{{ route('admin.counselors') }}" class="sidebar-link {{ request()->routeIs('admin.counselors') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Counselors</span>
                </a>
                <a href="{{ route('admin.appointments') }}" class="sidebar-link {{ request()->routeIs('admin.appointments') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
                <a href="{{ route('admin.resources.index') }}" class="sidebar-link {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i>
                    <span>Resources</span>
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="sidebar-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span>FAQs</span>
                </a>
                <a href="{{ route('admin.events') }}" class="sidebar-link {{ request()->routeIs('admin.events') && !request()->routeIs('admin.events.create') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Events</span>
                </a>
                <a href="{{ route('admin.feedback.index') }}" class="sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Feedback</span>
                </a>

                <div class="sidebar-divider"></div>

                <div class="sidebar-section-header">
                    Quick Actions
                </div>

                <a href="{{ route('admin.users.create') }}" class="sidebar-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i>
                    <span>Create User</span>
                </a>
                <a href="{{ route('admin.events.create') }}" class="sidebar-link {{ request()->routeIs('admin.events.create') ? 'active' : '' }}">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Create Event</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link border-0">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <div class="ml-64 pt-16 min-h-screen ogc-main-shell">
        @yield('content')
    </div>

@else
    <main class="min-h-screen">
        @yield('content')
    </main>
@endif

@stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Profile dropdown functionality
    const profileBtn = document.getElementById('profile-dropdown-btn');
    const profileMenu = document.getElementById('profile-dropdown-menu');

    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });
        profileMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function () {
        if (profileMenu) {
            profileMenu.classList.add('hidden');
        }
    });
});
</script>
</body>
</html>