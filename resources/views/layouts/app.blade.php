<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Office of Guidance and Counseling')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">

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
        font-size: 1.1rem;
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

    /* ── Profile dropdown ── */
    .ogc-profile-dropdown {
        position: relative;
    }

    .ogc-profile-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 0.7rem);
        background: rgba(255,255,255,0.98);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid var(--border-soft);
        box-shadow: 0 10px 25px rgba(44, 36, 32, 0.15);
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

@if(Auth::check() && Auth::user()->role === 'counselor')

    {{-- ── TOP NAVBAR (Counselor) ── --}}
    <nav class="ogc-navbar fixed top-0 left-0 right-0 flex items-center justify-between px-6 z-40">
        <div class="flex items-center space-x-3">
            <button type="button" class="ogc-nav-icon transition" id="sidebar-toggle-btn" title="Toggle Sidebar">
                <i class="fas fa-bars"></i>
            </button>

            <div class="ogc-brand-badge">
                <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
            </div>
            <span class="ogc-brand-text text-white font-bold text-sm hidden md:block">
                my.OGC<br>
                <span class="sub font-medium text-xs">MSU-IIT Office of Guidance & Counseling</span>
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
                        <i class="fas fa-user-circle mr-3 text-[var(--maroon-soft)]"></i> My Profile
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

    {{-- ── SIDEBAR (Counselor) ── --}}
    <nav id="ogcSidebar" class="ogc-sidebar fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 flex flex-col z-30">
        <div class="overflow-y-auto">
            <div class="sidebar-user-section">
                <div class="sidebar-user-card">
                    <div class="flex items-center gap-3">
                        <div class="sidebar-user-avatar" style="{{ Auth::user()->profile_picture ? 'background:none;padding:0;overflow:hidden;' : '' }}">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                     alt="Profile"
                                     style="width:100%;height:100%;object-fit:cover;border-radius:1rem;">
                            @else
                                <i class="fas fa-user-tie text-white text-sm"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="sidebar-user-name truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                            <div class="sidebar-user-email truncate">{{ Auth::user()->email }}</div>
                        </div>
                    </div>
                    <span class="sidebar-role-pill">
                        <i class="fas fa-stethoscope text-[10px]"></i> Counselor
                    </span>
                </div>
            </div>

            <div class="px-3 pb-4 pt-2">
                <a href="{{ route('counselor.dashboard') }}" class="sidebar-link {{ request()->routeIs('counselor.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('counselor.calendar') }}" class="sidebar-link {{ request()->routeIs('counselor.calendar') ? 'active' : '' }}">
                    <i class="fas fa-calendar"></i>
                    <span>Calendar</span>
                </a>

                <a href="{{ route('counselor.appointments') }}" class="sidebar-link {{ request()->routeIs('counselor.appointments') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Appointments</span>
                </a>

                <a href="{{ route('counselor.appointment-sessions.dashboard') }}" class="sidebar-link {{ request()->routeIs('counselor.appointment-sessions.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Appointment Sessions</span>
                </a>

                <a href="{{ route('counselor.events.index') }}" class="sidebar-link {{ request()->routeIs('counselor.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Events</span>
                </a>

                <a href="{{ route('counselor.announcements.index') }}" class="sidebar-link {{ request()->routeIs('counselor.announcements.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>

                <a href="{{ route('counselor.resources.index') }}" class="sidebar-link {{ request()->routeIs('counselor.resources.*') ? 'active' : '' }}">
                    <i class="fas fa-box-open"></i>
                    <span>Resources</span>
                </a>

                <a href="{{ route('counselor.feedback.index') }}" class="sidebar-link {{ request()->routeIs('counselor.feedback.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i>
                    <span>Feedback</span>
                </a>

                <a href="{{ route('counselor.availability.edit') }}" class="sidebar-link {{ request()->routeIs('counselor.availability.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Availability</span>
                </a>

                <a href="{{ route('counselor.analytics') }}" class="sidebar-link {{ request()->routeIs('counselor.analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
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
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');

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

    if (sidebarToggleBtn) {
        const sidebarState = localStorage.getItem('ogcSidebarCollapsed');

        if (sidebarState === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }

        sidebarToggleBtn.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');

            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('ogcSidebarCollapsed', isCollapsed ? 'true' : 'false');
        });
    }
});
</script>

{{-- Global System Alert Toast --}}
<div id="layoutAlertStack" style="
    position:fixed; top:1rem; right:1rem; z-index:9999;
    display:flex; flex-direction:column; gap:0.75rem;
    width:min(24rem, calc(100vw - 2rem)); pointer-events:none;
"></div>

<style>
    .system-alert {
        position:relative; overflow:hidden;
        display:flex; align-items:flex-start; gap:0.8rem;
        padding:0.95rem 1rem 0.95rem 0.95rem;
        border-radius:0.9rem; border:1px solid #e5e0db;
        background:rgba(255,255,255,0.97);
        box-shadow:0 12px 30px rgba(44,36,32,0.14);
        backdrop-filter:blur(10px); pointer-events:auto;
        animation:alertSlideIn 0.24s ease;
    }
    .system-alert::before {
        content:""; position:absolute; left:0; top:0; bottom:0;
        width:4px; border-radius:999px;
    }
    .system-alert.success::before { background:linear-gradient(180deg,#15803d,#22c55e); }
    .system-alert.error::before   { background:linear-gradient(180deg,#991b1b,#dc2626); }
    .system-alert.warning::before { background:linear-gradient(180deg,#c9a227,#d4af37); }
    .system-alert.info::before    { background:linear-gradient(180deg,#5c1a1a,#7a2a2a); }
    .system-alert-icon {
        width:2.2rem; height:2.2rem; min-width:2.2rem;
        border-radius:0.75rem; display:flex; align-items:center;
        justify-content:center; margin-top:0.05rem; font-size:0.9rem;
    }
    .system-alert.success .system-alert-icon { background:rgba(34,197,94,0.12); color:#15803d; }
    .system-alert.error   .system-alert-icon { background:rgba(220,38,38,0.12); color:#b91c1c; }
    .system-alert.warning .system-alert-icon { background:rgba(212,175,55,0.16); color:#9a3412; }
    .system-alert.info    .system-alert-icon { background:rgba(92,26,26,0.10);  color:#7a2a2a; }
    .system-alert-content { flex:1; min-width:0; }
    .system-alert-title   { font-size:0.78rem; font-weight:700; color:#2c2420; margin-bottom:0.15rem; letter-spacing:0.02em; }
    .system-alert-message { font-size:0.76rem; line-height:1.5; color:#6b5e57; }
    .system-alert-close {
        width:1.85rem; height:1.85rem; min-width:1.85rem;
        border:none; background:transparent; color:#8b7e76;
        border-radius:999px; display:flex; align-items:center;
        justify-content:center; cursor:pointer; transition:all 0.15s ease;
    }
    .system-alert-close:hover { background:rgba(254,249,231,0.9); color:#7a2a2a; }
    .system-alert-progress { position:absolute; left:0; right:0; bottom:0; height:3px; background:rgba(44,36,32,0.06); overflow:hidden; }
    .system-alert-progress-bar { width:100%; height:100%; transform-origin:left center; }
    .system-alert.success .system-alert-progress-bar { background:linear-gradient(90deg,#15803d,#22c55e); }
    .system-alert.error   .system-alert-progress-bar { background:linear-gradient(90deg,#991b1b,#dc2626); }
    .system-alert.warning .system-alert-progress-bar { background:linear-gradient(90deg,#c9a227,#d4af37); }
    .system-alert.info    .system-alert-progress-bar { background:linear-gradient(90deg,#5c1a1a,#7a2a2a); }
    @keyframes alertSlideIn {
        from { opacity:0; transform:translateY(-10px) translateX(8px); }
        to   { opacity:1; transform:translateY(0) translateX(0); }
    }
    @keyframes alertProgress {
        from { transform:scaleX(1); }
        to   { transform:scaleX(0); }
    }
    @media (max-width:639px) {
        #layoutAlertStack { top:0.75rem; left:0.75rem; right:0.75rem; width:auto; }
        .system-alert { padding:0.85rem 0.9rem; }
    }
</style>

<script>
    function showLayoutAlert(message, type = 'info', title = '') {
        const stack = document.getElementById('layoutAlertStack');
        if (!stack) return;
        const config = {
            success: { icon: 'fa-circle-check',        title: title || 'Success' },
            error:   { icon: 'fa-circle-xmark',        title: title || 'Something went wrong' },
            warning: { icon: 'fa-triangle-exclamation', title: title || 'Warning' },
            info:    { icon: 'fa-circle-info',          title: title || 'Notice' }
        };
        const cfg = config[type] || config.info;
        const duration = type === 'error' ? 5000 : 4200;
        const el = document.createElement('div');
        el.className = `system-alert ${type}`;
        el.innerHTML = `
            <div class="system-alert-icon"><i class="fas ${cfg.icon}"></i></div>
            <div class="system-alert-content">
                <div class="system-alert-title">${cfg.title}</div>
                <div class="system-alert-message">${message}</div>
            </div>
            <button type="button" class="system-alert-close" aria-label="Dismiss">
                <i class="fas fa-times"></i>
            </button>
            <div class="system-alert-progress">
                <div class="system-alert-progress-bar"></div>
            </div>`;
        const bar = el.querySelector('.system-alert-progress-bar');
        if (bar) bar.style.animation = `alertProgress ${duration}ms linear forwards`;
        const remove = () => {
            if (!el.parentNode) return;
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            el.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            setTimeout(() => el.remove(), 200);
        };
        el.querySelector('.system-alert-close').addEventListener('click', remove);
        stack.appendChild(el);
        setTimeout(remove, duration);
    }
</script>

@php
    $layoutToastType = 'info'; $layoutToastTitle = ''; $layoutToastMsg = '';
    if (session('success'))    { $layoutToastType = 'success'; $layoutToastTitle = 'Success'; $layoutToastMsg = session('success'); }
    elseif (session('error'))  { $layoutToastType = 'error';   $layoutToastTitle = 'Error';   $layoutToastMsg = session('error'); }
    elseif (session('status')) {
        $layoutToastType = 'success'; $layoutToastTitle = 'Success';
        $layoutToastMsg = ['profile-updated'=>'Profile updated successfully.','password-updated'=>'Password updated successfully.','student-profile-updated'=>'Student profile updated successfully.','counselor-profile-updated'=>'Counselor profile updated successfully.','counselor-availability-updated'=>'Availability updated successfully.','verification-link-sent'=>'Verification link sent to your email.'][session('status')] ?? session('status');
    }
@endphp
@if($layoutToastMsg)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showLayoutAlert(@json($layoutToastMsg), @json($layoutToastType), @json($layoutToastTitle));
    });
</script>
@endif

</body>
</html>