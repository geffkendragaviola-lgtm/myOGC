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
    :root {
        /* Dashboard Design System Colors */
        --maroon-soft: #7a2a2a;      /* Primary */
        --maroon-medium: #5c1a1a;    /* Secondary/Darker */
        --maroon-dark: #3a0c0c;      /* Accent/Darkest */
        --gold-primary: #d4af37;
        --gold-secondary: #c9a227;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
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
        color: var(--text-primary);
        background-color: var(--bg-warm);
        /* Subtle warm background gradient matching dashboard */
        background-image: 
            radial-gradient(circle at top left, rgba(212, 175, 55, 0.05), transparent 25%),
            radial-gradient(circle at bottom right, rgba(122, 42, 42, 0.05), transparent 25%);
    }

    /* ── Navbar ── */
    .ogc-navbar {
        height: 4rem;
        /* Dashboard Gradient: Soft Maroon to Medium Maroon */
        background: linear-gradient(135deg, var(--maroon-soft) 0%, var(--maroon-medium) 100%);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(212, 175, 55, 0.3); /* Gold tint border */
        box-shadow: 0 4px 20px rgba(122, 42, 42, 0.25);
        position: relative;
        z-index: 50;
    }

    .ogc-navbar::after {
        content: "";
        position: absolute;
        inset: auto 0 0 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
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
        background: rgba(255,255,255,0.20);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: var(--gold-primary); /* Gold hover effect */
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
        transition: opacity 0.2s ease, transform 0.3s ease;
    }

    .ogc-brand-text .sub {
        color: var(--gold-primary); /* Gold subtext */
        font-weight: 500;
    }

    .student-top-link,
    .student-top-button {
        position: relative;
        font-size: 0.9rem;
        font-weight: 600;
        color: rgba(255,255,255,0.9);
        transition: all 0.2s ease;
    }

    .student-top-link:hover,
    .student-top-button:hover {
        color: var(--gold-primary); /* Gold hover */
    }

    #sidebar-toggle-btn i {
        transition: transform 0.28s ease;
    }

    body.sidebar-collapsed #sidebar-toggle-btn i {
        transform: rotate(180deg);
    }

    /* ── Sidebar ── */
    .ogc-sidebar,
    #ogcMainContent,
    .sidebar-user-card,
    .sidebar-user-section,
    .sidebar-link,
    .logout-link,
    .sidebar-link span,
    .logout-link span,
    .sidebar-user-name,
    .sidebar-user-email,
    .sidebar-role-pill,
    .ogc-brand-text {
        transition:
            width 0.3s ease,
            margin 0.3s ease,
            padding 0.3s ease,
            gap 0.3s ease,
            opacity 0.2s ease,
            transform 0.3s ease,
            background 0.25s ease,
            box-shadow 0.25s ease;
    }

    .ogc-sidebar {
        /* Deep Maroon Gradient for Sidebar */
        background: linear-gradient(180deg, var(--maroon-medium) 0%, var(--maroon-dark) 100%);
        border-right: 1px solid rgba(212, 175, 55, 0.2); /* Subtle gold border */
        box-shadow: 8px 0 28px rgba(58, 12, 12, 0.15);
        overflow: hidden;
        overflow-x: hidden;
        transition: width 0.3s ease;
        position: relative;
    }

    /* Ambient glow in sidebar */
    .ogc-sidebar::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            radial-gradient(circle at top left, rgba(212,175,55,0.08), transparent 25%),
            radial-gradient(circle at bottom left, rgba(122,42,42,0.2), transparent 30%);
    }

    .ogc-sidebar .overflow-y-auto {
        scrollbar-width: thin;
        scrollbar-color: rgba(212, 175, 55, 0.3) transparent;
    }

    .ogc-sidebar .overflow-y-auto::-webkit-scrollbar {
        width: 5px;
    }

    .ogc-sidebar .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .ogc-sidebar .overflow-y-auto::-webkit-scrollbar-thumb {
        background: rgba(212, 175, 55, 0.3);
        border-radius: 999px;
    }

    .sidebar-user-section {
        position: relative;
        padding: 1.15rem 1rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .sidebar-user-card {
        border: 1px solid rgba(255,255,255,0.08);
        background: rgba(255,255,255,0.03);
        border-radius: 1rem;
        padding: 0.9rem;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
    }

    .sidebar-user-avatar {
        width: 2.7rem;
        height: 2.7rem;
        /* Avatar Gradient: Maroon to Gold */
        background: linear-gradient(135deg, var(--maroon-soft), var(--gold-primary));
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.2);
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
        color: rgba(255,255,255,0.6);
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
        color: var(--maroon-dark);
        padding: 0.42rem 0.7rem;
        border-radius: 999px;
        /* Gold Badge */
        background: linear-gradient(135deg, var(--gold-primary), var(--gold-secondary));
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
        border-radius: 0.75rem; /* Slightly softer radius */
        color: rgba(255,255,255,0.75);
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
        color: rgba(255,255,255,0.6);
    }

    .sidebar-link:hover,
    .logout-link:hover {
        color: #fff;
        background: rgba(255,255,255,0.08);
        border-color: rgba(255,255,255,0.1);
        transform: translateX(4px);
    }

    .sidebar-link:hover i,
    .logout-link:hover i {
        color: var(--gold-primary);
        transform: scale(1.1);
    }

    .sidebar-link.active {
        color: #fff;
        font-weight: 600;
        /* Active State: Warm Maroon with Gold hint */
        background: linear-gradient(90deg, rgba(122, 42, 42, 0.4), rgba(212, 175, 55, 0.1));
        border: 1px solid rgba(212, 175, 55, 0.3);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15), inset 0 1px 0 rgba(255,255,255,0.1);
    }

    .sidebar-link.active::before {
        content: "";
        position: absolute;
        left: 0.45rem;
        top: 50%;
        transform: translateY(-50%);
        width: 0.25rem;
        height: 1.5rem;
        border-radius: 999px;
        /* Gold Indicator */
        background: linear-gradient(180deg, var(--gold-primary), var(--gold-secondary));
        box-shadow: 0 0 8px rgba(212, 175, 55, 0.4);
    }

    .sidebar-link.active i {
        color: var(--gold-primary);
    }

    .sidebar-footer {
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 0.9rem 0.75rem;
        background: linear-gradient(180deg, rgba(0,0,0,0.1), transparent);
    }

    body.sidebar-collapsed .ogc-sidebar {
        width: 5.5rem;
    }

    body.sidebar-collapsed #ogcMainContent {
        margin-left: 5.5rem;
    }

    body.sidebar-collapsed .sidebar-user-section {
        padding: 1rem 0.65rem 0.85rem;
    }

    body.sidebar-collapsed .sidebar-user-card {
        padding: 0.8rem 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    body.sidebar-collapsed .sidebar-user-card .flex {
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
    }

    body.sidebar-collapsed .sidebar-user-name,
    body.sidebar-collapsed .sidebar-user-email,
    body.sidebar-collapsed .sidebar-link span,
    body.sidebar-collapsed .logout-link span {
        opacity: 0;
        width: 0;
        max-width: 0;
        overflow: hidden;
        white-space: nowrap;
        transform: translateX(-6px);
        pointer-events: none;
    }

    body.sidebar-collapsed .sidebar-role-pill {
        display: none;
    }

    body.sidebar-collapsed .sidebar-link,
    body.sidebar-collapsed .logout-link {
        justify-content: center;
        gap: 0;
        padding: 0.85rem 0.5rem;
        transform: none;
    }

    body.sidebar-collapsed .sidebar-link i,
    body.sidebar-collapsed .logout-link i {
        width: auto;
        margin: 0;
        font-size: 1rem;
    }

    body.sidebar-collapsed .sidebar-link.active::before {
        left: 0.35rem;
    }

    body.sidebar-collapsed .sidebar-footer {
        padding: 0.9rem 0.55rem;
    }

    body.sidebar-collapsed .ogc-brand-text {
        opacity: 0.92;
    }

    /* ── Navbar dropdown ── */
    .ogc-nav-dropdown,
    .ogc-profile-dropdown {
        position: relative;
    }

    .ogc-nav-dropdown-menu,
    .ogc-profile-menu {
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid var(--border-soft);
        box-shadow: 0 10px 25px rgba(44, 36, 32, 0.15);
    }

    .ogc-nav-dropdown-menu {
        position: absolute;
        top: calc(100% + 0.6rem);
        left: 0;
        border-radius: 0.75rem;
        min-width: 14rem;
        z-index: 50;
        padding: 0.5rem;
    }

    .ogc-nav-dropdown-menu a {
        display: block;
        padding: 0.75rem 0.9rem;
        border-radius: 0.5rem;
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .ogc-nav-dropdown-menu a:hover {
        background: var(--bg-warm);
        color: var(--maroon-soft);
    }

    .ogc-profile-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 0.7rem);
        border-radius: 0.75rem;
        padding: 0.5rem;
        min-width: 16rem;
        z-index: 1000;
    }

    .ogc-profile-menu a,
    .ogc-profile-menu button {
        display: flex;
        align-items: center;
        width: 100%;
        text-align: left;
        padding: 0.6rem 0.8rem;
        color: var(--text-primary);
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 0.89rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .ogc-profile-menu a:hover,
    .ogc-profile-menu button:hover {
        background: var(--bg-warm);
        color: var(--maroon-soft);
    }

    .ogc-profile-summary {
        margin-bottom: 0.5rem;
        padding: 0.5rem 0.8rem 0.8rem;
        border-bottom: 1px solid var(--border-soft);
    }

    .ogc-profile-summary .name {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 0.92rem;
    }

    .ogc-profile-summary .email {
        font-size: 0.77rem;
        color: var(--text-secondary);
        margin-top: 0.2rem;
        word-break: break-word;
    }

    .ogc-profile-role {
        display: inline-flex;
        align-items: center;
        margin-top: 0.55rem;
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--maroon-dark);
        padding: 0.34rem 0.62rem;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--gold-primary), var(--gold-secondary));
        box-shadow: 0 2px 4px rgba(212, 175, 55, 0.2);
    }

    /* ── Bootstrap tab override ── */
    .nav-tabs .nav-link {
        color: var(--text-secondary);
        font-weight: 500;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link.active {
        color: var(--maroon-soft);
        font-weight: 600;
        background: transparent;
        border-bottom: 3px solid var(--maroon-soft);
    }

    .nav-tabs .nav-link:hover {
        border-color: transparent;
        border-bottom: 3px solid var(--gold-primary);
        color: var(--text-primary);
    }

    .ogc-main-shell {
        min-height: 100vh;
        position: relative;
        background-color: var(--bg-warm);
    }

    @media (max-width: 1024px) {
        .ogc-sidebar {
            width: 16rem;
        }

        .ml-64,
        #ogcMainContent {
            margin-left: 16rem;
        }

        body.sidebar-collapsed .ogc-sidebar {
            width: 5.5rem;
        }

        body.sidebar-collapsed #ogcMainContent {
            margin-left: 5.5rem;
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

        .ml-64,
        #ogcMainContent {
            margin-left: 15rem;
        }

        body.sidebar-collapsed .ogc-sidebar {
            width: 5rem;
        }

        body.sidebar-collapsed #ogcMainContent {
            margin-left: 5rem;
        }
    }
</style>

    @stack('styles')
</head>
<body>

@if(Auth::check() && Auth::user()->role === 'student')

    {{-- ── TOP NAVBAR (Student) ── --}}
    <nav class="ogc-navbar fixed top-0 left-0 right-0 flex items-center justify-between px-6 z-40">
        <div class="flex items-center space-x-3">
            <button type="button" class="ogc-nav-icon transition" id="sidebar-toggle-btn" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <div class="ogc-brand-badge">
                <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
            </div>
            <span class="ogc-brand-text text-white font-bold text-sm hidden md:block">
                MSU-IIT<br>
                <span class="sub font-medium text-xs">Student Portal</span>
            </span>
        </div>

        <div class="hidden md:flex items-center space-x-6 absolute left-1/2 -translate-x-1/2">
            <a href="{{ route('dashboard') }}" class="student-top-link">Home</a>

            <div class="ogc-nav-dropdown" id="dd-services">
                <button class="student-top-button flex items-center" id="dd-services-btn">
                    Services <i class="fas fa-chevron-down ml-2 text-[10px]"></i>
                </button>
                <div class="ogc-nav-dropdown-menu hidden" id="dd-services-menu">
                    <a href="{{ route('bap') }}"><i class="fas fa-calendar-plus mr-2 text-[var(--maroon-soft)]"></i> Book Appointment</a>
                    <a href="{{ route('mhc') }}"><i class="fas fa-heart mr-2 text-[var(--maroon-soft)]"></i> Mental Health Corner</a>
                </div>
            </div>

            <a href="{{ route('feedback') }}" class="student-top-link">Feedback</a>
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
                        <i class="fas fa-user-circle mr-3 text-[var(--maroon-soft)]"></i> Profile Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt mr-3 text-[var(--maroon-soft)]"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- ── SIDEBAR (Student) ── --}}
    <nav id="ogcSidebar" class="ogc-sidebar fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 flex flex-col z-30">
        <div class="sidebar-user-section">
            <div class="sidebar-user-card">
                <div class="flex items-center gap-3">
                    <div class="sidebar-user-avatar">
                        <i class="fas fa-user-graduate text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="sidebar-user-name truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="sidebar-user-email truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <span class="sidebar-role-pill">
                    <i class="fas fa-graduation-cap text-[10px]"></i> Student
                </span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-3 pb-3">
            <div class="space-y-0.5 pt-2">
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

    <div id="ogcMainContent" class="ml-64 pt-16 min-h-screen ogc-main-shell">
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
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');

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

    // Student sidebar toggle
    if (sidebarToggleBtn) {
        const sidebarState = localStorage.getItem('ogcStudentSidebarCollapsed');

        if (sidebarState === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }

        sidebarToggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');

            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('ogcStudentSidebarCollapsed', isCollapsed ? 'true' : 'false');
        });
    }

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