<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - OGC')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
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
        
        --ogc-shadow: 0 18px 45px rgba(58, 12, 12, 0.10);
        --ogc-shadow-soft: 0 10px 30px rgba(44, 36, 32, 0.08);
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
        background-color: var(--bg-warm);
        color: var(--text-primary);
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
    .ogc-brand-text,
    .sidebar-divider,
    .sidebar-section-header {
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
        border-top: 1px solid rgba(255,255,255,0.08);
        padding: 0.9rem 0.75rem;
        background: linear-gradient(180deg, rgba(0,0,0,0.1), transparent);
    }

    body.sidebar-collapsed .ogc-sidebar {
        width: 5.5rem;
    }

    /* Pre-collapsed: same as collapsed but no transition (prevents flash) */
    html.sidebar-pre-collapsed .ogc-sidebar,
    html.sidebar-pre-collapsed #ogcMainContent {
        transition: none !important;
    }
    html.sidebar-pre-collapsed .ogc-sidebar { width: 5.5rem; }
    html.sidebar-pre-collapsed #ogcMainContent { margin-left: 5.5rem; }
    html.sidebar-pre-collapsed .sidebar-user-name,
    html.sidebar-pre-collapsed .sidebar-user-email,
    html.sidebar-pre-collapsed .sidebar-link span,
    html.sidebar-pre-collapsed .logout-link span,
    html.sidebar-pre-collapsed .sidebar-section-header {
        opacity: 0; width: 0; max-width: 0; overflow: hidden;
        white-space: nowrap; pointer-events: none;
    }
    html.sidebar-pre-collapsed .sidebar-role-pill { display: none; }
    html.sidebar-pre-collapsed .sidebar-link,
    html.sidebar-pre-collapsed .logout-link { justify-content: center; gap: 0; padding: 0.85rem 0.5rem; }
    html.sidebar-pre-collapsed .sidebar-link i,
    html.sidebar-pre-collapsed .logout-link i { width: auto; margin: 0; font-size: 1rem; }

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
    body.sidebar-collapsed .logout-link span,
    body.sidebar-collapsed .sidebar-section-header {
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

    body.sidebar-collapsed .sidebar-divider {
        opacity: 0.35;
        margin-left: 0.35rem;
        margin-right: 0.35rem;
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

    /* ── Dropdowns ── */
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
        min-width: 12rem;
        z-index: 50;
        padding: 0.5rem;
    }

    .ogc-nav-dropdown-menu a {
        display: block;
        padding: 0.7rem 0.85rem;
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

    /* ── Main content wrapper ── */
    .ogc-main-shell {
        min-height: 100vh;
        position: relative;
        background-color: var(--bg-warm);
    }

    /* ── Sidebar overlay backdrop (mobile) ── */
    #ogcSidebarOverlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 29;
        background: rgba(30, 8, 8, 0.5);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        transition: opacity 0.3s ease;
    }
    body.sidebar-mobile-open #ogcSidebarOverlay {
        display: block;
    }

    /* ── Responsive breakpoints ── */
    @media (max-width: 1024px) {
        .ogc-sidebar { width: 16rem; }
        .ml-64, #ogcMainContent { margin-left: 16rem; }
        body.sidebar-collapsed .ogc-sidebar { width: 5.5rem; }
        body.sidebar-collapsed #ogcMainContent { margin-left: 5.5rem; }
    }

    /* Tablet: auto-collapse sidebar to icon-only */
    @media (max-width: 900px) {
        .ogc-sidebar { width: 5.5rem; }
        .ml-64, #ogcMainContent { margin-left: 5.5rem; }

        .sidebar-user-name,
        .sidebar-user-email,
        .sidebar-link span,
        .logout-link span,
        .sidebar-section-header {
            opacity: 0; width: 0; max-width: 0;
            overflow: hidden; white-space: nowrap; pointer-events: none;
        }
        .sidebar-role-pill { display: none; }
        .sidebar-link, .logout-link {
            justify-content: center; gap: 0; padding: 0.85rem 0.5rem;
        }
        .sidebar-link i, .logout-link i { width: auto; margin: 0; font-size: 1rem; }
        .sidebar-link.active::before { left: 0.35rem; }
        .sidebar-user-card { padding: 0.8rem 0.5rem; flex-direction: column; align-items: center; text-align: center; }
        .sidebar-user-card .flex { flex-direction: column; align-items: center; justify-content: center; gap: 0.55rem; }
        .sidebar-user-section { padding: 1rem 0.65rem 0.85rem; }
        .sidebar-footer { padding: 0.9rem 0.55rem; }

        /* When toggle is clicked on tablet, expand to full width */
        body.sidebar-expanded .ogc-sidebar { width: 16rem; }
        body.sidebar-expanded #ogcMainContent { margin-left: 16rem; }
        body.sidebar-expanded .sidebar-user-name,
        body.sidebar-expanded .sidebar-user-email,
        body.sidebar-expanded .sidebar-link span,
        body.sidebar-expanded .logout-link span,
        body.sidebar-expanded .sidebar-section-header {
            opacity: 1; width: auto; max-width: none; pointer-events: auto;
        }
        body.sidebar-expanded .sidebar-role-pill { display: inline-flex; }
        body.sidebar-expanded .sidebar-link,
        body.sidebar-expanded .logout-link { justify-content: flex-start; gap: 0.8rem; padding: 0.8rem 0.9rem; }
        body.sidebar-expanded .sidebar-link i,
        body.sidebar-expanded .logout-link i { width: 1.2rem; }
        body.sidebar-expanded .sidebar-user-card { padding: 0.9rem; flex-direction: column; align-items: flex-start; }
        body.sidebar-expanded .sidebar-user-card .flex { flex-direction: row; align-items: center; gap: 0.75rem; }
        body.sidebar-expanded .sidebar-user-section { padding: 1.15rem 1rem 1rem; }
        body.sidebar-expanded .sidebar-footer { padding: 0.9rem 0.75rem; }
    }

    /* Mobile: sidebar becomes overlay drawer */
    @media (max-width: 640px) {
        .ogc-navbar {
            padding-left: 0.85rem !important;
            padding-right: 0.85rem !important;
        }

        /* Sidebar hidden off-screen by default */
        .ogc-sidebar {
            width: 16rem !important;
            transform: translateX(-100%);
            transition: transform 0.3s ease, width 0.3s ease !important;
            z-index: 40;
        }

        /* Main content takes full width */
        .ml-64, #ogcMainContent {
            margin-left: 0 !important;
        }

        /* Sidebar open state */
        body.sidebar-mobile-open .ogc-sidebar {
            transform: translateX(0);
        }

        /* Restore text visibility when open on mobile */
        body.sidebar-mobile-open .sidebar-user-name,
        body.sidebar-mobile-open .sidebar-user-email,
        body.sidebar-mobile-open .sidebar-link span,
        body.sidebar-mobile-open .logout-link span,
        body.sidebar-mobile-open .sidebar-section-header {
            opacity: 1; width: auto; max-width: none; pointer-events: auto;
            white-space: normal; transform: none;
        }
        body.sidebar-mobile-open .sidebar-role-pill { display: inline-flex; }
        body.sidebar-mobile-open .sidebar-link,
        body.sidebar-mobile-open .logout-link { justify-content: flex-start; gap: 0.8rem; padding: 0.8rem 0.9rem; }
        body.sidebar-mobile-open .sidebar-link i,
        body.sidebar-mobile-open .logout-link i { width: 1.2rem; }
        body.sidebar-mobile-open .sidebar-user-card { padding: 0.9rem; }
        body.sidebar-mobile-open .sidebar-user-card .flex { flex-direction: row; align-items: center; gap: 0.75rem; }
        body.sidebar-mobile-open .sidebar-user-section { padding: 1.15rem 1rem 1rem; }
        body.sidebar-mobile-open .sidebar-footer { padding: 0.9rem 0.75rem; }
        body.sidebar-mobile-open .sidebar-link.active::before { left: 0.45rem; }

        /* Prevent body scroll when sidebar open */
        body.sidebar-mobile-open { overflow: hidden; }

        /* Content responsive adjustments */
        #ogcMainContent { padding-left: 0; padding-right: 0; }
    }
</style>

    @stack('styles')

    {{-- ── Admin content scale-up ── --}}
    <style>
    /* Increase base content padding and max-width so pages breathe more */
    #ogcMainContent > * {
        font-size: 1rem;
    }

    /* Wider max-width for all admin page wrappers */
    #ogcMainContent .max-w-7xl { max-width: 90rem; }
    #ogcMainContent .max-w-6xl { max-width: 80rem; }
    #ogcMainContent .max-w-5xl { max-width: 72rem; }
    #ogcMainContent .max-w-4xl { max-width: 64rem; }

    /* More generous page padding */
    #ogcMainContent .py-5    { padding-top: 1.75rem; padding-bottom: 1.75rem; }
    #ogcMainContent .py-6    { padding-top: 2rem;    padding-bottom: 2rem; }
    #ogcMainContent .md\:py-8 { padding-top: 2.5rem; padding-bottom: 2.5rem; }
    #ogcMainContent .px-4    { padding-left: 1.5rem;  padding-right: 1.5rem; }
    #ogcMainContent .sm\:px-6 { padding-left: 2rem;   padding-right: 2rem; }

    /* Bigger hero headings */
    #ogcMainContent h1.text-lg   { font-size: 1.35rem; }
    #ogcMainContent h1.text-xl   { font-size: 1.5rem; }
    #ogcMainContent h1.text-2xl  { font-size: 1.75rem; }
    #ogcMainContent h1.lg\:text-2xl { font-size: 1.75rem; }

    /* Bigger body text in tables and cards */
    #ogcMainContent .text-xs  { font-size: 0.82rem; }
    #ogcMainContent .text-sm  { font-size: 0.925rem; }
    #ogcMainContent .text-base { font-size: 1.05rem; }

    /* Taller table rows */
    #ogcMainContent table td,
    #ogcMainContent table th { padding-top: 0.85rem; padding-bottom: 0.85rem; }

    /* Bigger stat values */
    #ogcMainContent .stat-value  { font-size: 2rem; }
    #ogcMainContent .summary-value { font-size: 1.75rem; }

    /* More padding inside cards */
    #ogcMainContent .panel-card .p-3   { padding: 1.1rem; }
    #ogcMainContent .panel-card .p-4   { padding: 1.35rem; }
    #ogcMainContent .panel-card .sm\:p-4 { padding: 1.35rem; }
    #ogcMainContent .panel-card .sm\:p-5 { padding: 1.5rem; }
    #ogcMainContent .panel-card .p-5   { padding: 1.5rem; }

    /* Bigger hero icon */
    #ogcMainContent .hero-icon {
        width: 3.25rem;
        height: 3.25rem;
        font-size: 1.2rem;
    }

    /* Bigger panel icon */
    #ogcMainContent .panel-icon,
    #ogcMainContent .panel-header-icon {
        width: 2.4rem;
        height: 2.4rem;
    }

    /* Taller input fields */
    #ogcMainContent .input-field,
    #ogcMainContent .select-field,
    #ogcMainContent .filter-input {
        padding: 0.65rem 0.9rem;
        font-size: 0.9rem;
    }

    /* Bigger primary buttons */
    #ogcMainContent .primary-btn,
    #ogcMainContent .search-btn {
        padding: 0.65rem 1.35rem;
        font-size: 0.9rem;
    }

    /* More gap between grid items */
    #ogcMainContent .gap-3 { gap: 1rem; }
    #ogcMainContent .gap-4 { gap: 1.25rem; }
    #ogcMainContent .sm\:gap-4 { gap: 1.25rem; }
    #ogcMainContent .sm\:gap-6 { gap: 1.75rem; }
    #ogcMainContent .gap-6 { gap: 1.75rem; }

    /* Bigger avatar badges */
    #ogcMainContent .avatar-badge {
        width: 2.75rem;
        height: 2.75rem;
        font-size: 0.85rem;
        border-radius: 0.75rem;
    }

    /* Taller panel headers */
    #ogcMainContent .panel-header {
        padding: 1rem 1.5rem;
    }

    /* More breathing room in table header bar */
    #ogcMainContent .table-header-bar {
        padding: 0.9rem 1.5rem;
    }

    /* Bigger pagination footer */
    #ogcMainContent .pagination-shell,
    #ogcMainContent .table-footer {
        padding: 0.9rem 1.5rem;
    }

    /* ── Global design enhancements ── */

    /* Smoother body background */
    body {
        background-image:
            radial-gradient(ellipse at 0% 0%, rgba(212,175,55,0.06) 0%, transparent 40%),
            radial-gradient(ellipse at 100% 100%, rgba(122,42,42,0.06) 0%, transparent 40%);
    }

    /* Refined card base */
    #ogcMainContent .hero-card,
    #ogcMainContent .panel-card,
    #ogcMainContent .glass-card,
    #ogcMainContent .an-card,
    #ogcMainContent .stat-card {
        border-radius: 1rem;
        border-color: rgba(229,224,219,0.7);
        box-shadow: 0 1px 3px rgba(44,36,32,0.04), 0 4px 16px rgba(44,36,32,0.05);
        transition: box-shadow 0.22s ease, transform 0.22s ease;
    }
    #ogcMainContent .panel-card:hover,
    #ogcMainContent .hero-card:hover {
        box-shadow: 0 2px 6px rgba(44,36,32,0.05), 0 8px 24px rgba(44,36,32,0.08);
    }

    /* Refined panel topline */
    #ogcMainContent .panel-topline {
        height: 2px;
        background: linear-gradient(90deg, var(--maroon-medium) 0%, var(--gold-primary) 50%, var(--maroon-medium) 100%);
        opacity: 0.7;
    }

    /* Better table styling */
    #ogcMainContent table {
        border-collapse: separate;
        border-spacing: 0;
    }
    #ogcMainContent table thead tr th {
        background: rgba(250,248,245,0.9);
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid rgba(229,224,219,0.8);
        padding-top: 0.9rem;
        padding-bottom: 0.9rem;
    }
    #ogcMainContent table tbody tr td {
        border-bottom: 1px solid rgba(229,224,219,0.45);
        vertical-align: middle;
    }
    #ogcMainContent table tbody tr:last-child td {
        border-bottom: none;
    }
    #ogcMainContent .table-row:hover td {
        background: rgba(254,249,231,0.3);
    }

    /* Refined input fields */
    #ogcMainContent .input-field,
    #ogcMainContent .select-field,
    #ogcMainContent .filter-input,
    #ogcMainContent .textarea-field {
        border-radius: 0.65rem;
        border-color: rgba(229,224,219,0.9);
        background: rgba(255,255,255,0.95);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    #ogcMainContent .input-field:focus,
    #ogcMainContent .select-field:focus,
    #ogcMainContent .filter-input:focus,
    #ogcMainContent .textarea-field:focus {
        border-color: var(--maroon-soft);
        box-shadow: 0 0 0 3px rgba(122,42,42,0.08);
        outline: none;
    }

    /* Refined primary button */
    #ogcMainContent .primary-btn,
    #ogcMainContent .search-btn {
        border-radius: 0.65rem;
        letter-spacing: 0.01em;
        box-shadow: 0 2px 8px rgba(92,26,26,0.18);
        transition: all 0.2s ease;
    }
    #ogcMainContent .primary-btn:hover,
    #ogcMainContent .search-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(92,26,26,0.25);
    }
    #ogcMainContent .primary-btn:active,
    #ogcMainContent .search-btn:active {
        transform: translateY(0);
        box-shadow: 0 1px 4px rgba(92,26,26,0.15);
    }

    /* Refined hero icon */
    #ogcMainContent .hero-icon {
        border-radius: 0.85rem;
        box-shadow: 0 4px 14px rgba(92,26,26,0.18);
    }

    /* Refined avatar badge */
    #ogcMainContent .avatar-badge {
        background: linear-gradient(135deg, #fef9e7 0%, #f5e6b8 100%);
        border: 1px solid rgba(212,175,55,0.35);
        box-shadow: 0 1px 4px rgba(44,36,32,0.06);
    }

    /* Refined hero badge */
    #ogcMainContent .hero-badge {
        background: rgba(254,249,231,0.9);
        border-color: rgba(212,175,55,0.35);
        letter-spacing: 0.14em;
    }

    /* Refined summary card */
    #ogcMainContent .summary-card {
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(58,12,12,0.18);
    }

    /* Refined action links */
    #ogcMainContent .action-link {
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        transition: all 0.18s ease;
        color: var(--text-muted);
    }
    #ogcMainContent .action-link:hover {
        background: rgba(254,249,231,0.8);
        color: var(--maroon-soft);
        transform: translateY(-1px);
    }

    /* Refined status badges */
    #ogcMainContent .status-badge,
    #ogcMainContent [class*="status-chip"] {
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    /* Refined empty states */
    #ogcMainContent .empty-state-icon {
        background: linear-gradient(135deg, rgba(254,249,231,0.8), rgba(245,240,235,0.6));
        border: 1.5px dashed rgba(212,175,55,0.4);
    }

    /* Refined panel header */
    #ogcMainContent .panel-header {
        background: rgba(250,248,245,0.5);
    }

    /* Refined table header bar */
    #ogcMainContent .table-header-bar {
        background: rgba(250,248,245,0.6);
        border-bottom-color: rgba(229,224,219,0.6);
    }

    /* Smooth page transitions */
    #ogcMainContent {
        animation: contentFadeIn 0.18s ease-out;
    }
    @keyframes contentFadeIn {
        from { opacity: 0.85; transform: translateY(3px); }
        to   { opacity: 1;    transform: translateY(0); }
    }
    </style>

    {{-- ── Responsive content improvements ── --}}
    <style>
    /* Tables: horizontal scroll on small screens */
    @media (max-width: 900px) {
        #ogcMainContent .overflow-x-auto,
        #ogcMainContent table { display: block; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        #ogcMainContent table { display: table; min-width: 600px; }
        #ogcMainContent .overflow-x-auto { width: 100%; }

        /* Reduce padding on mobile */
        #ogcMainContent .px-4    { padding-left: 1rem; padding-right: 1rem; }
        #ogcMainContent .sm\:px-6 { padding-left: 1rem; padding-right: 1rem; }
        #ogcMainContent .py-5    { padding-top: 1.25rem; padding-bottom: 1.25rem; }
        #ogcMainContent .py-6    { padding-top: 1.5rem; padding-bottom: 1.5rem; }

        /* Stat grids: 2 columns on tablet */
        #ogcMainContent .grid-cols-5 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        #ogcMainContent .grid-cols-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        #ogcMainContent .grid-cols-3 { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        /* Hero card: stack on tablet */
        #ogcMainContent .hero-card .flex.items-start { flex-direction: column; gap: 1rem; }

        /* Filter rows: wrap */
        #ogcMainContent .filter-row { flex-wrap: wrap; gap: 0.75rem; }
        #ogcMainContent .filter-row > * { flex: 1 1 calc(50% - 0.375rem); min-width: 0; }

        /* Reduce stat values */
        #ogcMainContent .stat-value { font-size: 1.5rem; }
        #ogcMainContent .summary-value { font-size: 1.35rem; }
    }

    @media (max-width: 640px) {
        /* Full-width padding on mobile */
        #ogcMainContent .px-4    { padding-left: 0.85rem; padding-right: 0.85rem; }
        #ogcMainContent .sm\:px-6 { padding-left: 0.85rem; padding-right: 0.85rem; }
        #ogcMainContent .py-5    { padding-top: 1rem; padding-bottom: 1rem; }
        #ogcMainContent .py-6    { padding-top: 1.25rem; padding-bottom: 1.25rem; }

        /* Single column grids on mobile */
        #ogcMainContent .grid-cols-5,
        #ogcMainContent .grid-cols-4,
        #ogcMainContent .grid-cols-3,
        #ogcMainContent .grid-cols-2,
        #ogcMainContent .sm\:grid-cols-2,
        #ogcMainContent .sm\:grid-cols-3,
        #ogcMainContent .md\:grid-cols-2,
        #ogcMainContent .md\:grid-cols-3,
        #ogcMainContent .lg\:grid-cols-2,
        #ogcMainContent .lg\:grid-cols-3,
        #ogcMainContent .lg\:grid-cols-4 { grid-template-columns: repeat(1, minmax(0, 1fr)); }

        /* Two-column layouts: stack */
        #ogcMainContent .lg\:grid-cols-3.xl\:grid-cols-4 { grid-template-columns: 1fr; }

        /* Filter rows: full width on mobile */
        #ogcMainContent .filter-row > * { flex: 1 1 100%; }

        /* Hero card adjustments */
        #ogcMainContent .hero-card { padding: 1rem; }
        #ogcMainContent .hero-icon { width: 2.5rem; height: 2.5rem; font-size: 1rem; }

        /* Panel header: stack on mobile */
        #ogcMainContent .panel-header { flex-wrap: wrap; gap: 0.5rem; padding: 0.85rem 1rem; }
        #ogcMainContent .table-header-bar { flex-wrap: wrap; gap: 0.5rem; padding: 0.75rem 1rem; }

        /* Buttons: full width on mobile */
        #ogcMainContent .primary-btn,
        #ogcMainContent .search-btn { width: 100%; justify-content: center; }

        /* Stat values */
        #ogcMainContent .stat-value { font-size: 1.35rem; }
        #ogcMainContent .summary-value { font-size: 1.2rem; }

        /* Modal: full screen on mobile */
        .modal-card { width: calc(100vw - 1.5rem) !important; max-width: none !important; margin: 0.75rem !important; }

        /* Reduce font sizes on mobile */
        #ogcMainContent .text-xs  { font-size: 0.75rem; }
        #ogcMainContent .text-sm  { font-size: 0.85rem; }
    }
    </style>

    {{-- Prevent sidebar collapse flash on page load --}}
    <script>
        (function(){
            var k = 'ogcAdminSidebarCollapsed';
            if(localStorage.getItem(k) === 'true') document.documentElement.classList.add('sidebar-pre-collapsed');
        })();
    </script>
</head>
<body>

@if(Auth::check() && Auth::user()->role === 'admin')

    {{-- ── TOP NAVBAR (Admin) ── --}}
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
                <span class="sub font-medium text-xs">Admin Panel</span>
            </span>
        </div>

        <div class="flex items-center space-x-3 ml-auto">
            <button class="ogc-nav-icon transition">
                <i class="fas fa-bell"></i>
            </button>
            <div class="ogc-profile-dropdown">
                <button class="ogc-nav-icon transition" id="profile-dropdown-btn">
                    <i class="fas fa-circle-user text-lg"></i>
                </button>
                <div class="ogc-profile-menu hidden" id="profile-dropdown-menu">
                    <div class="ogc-profile-summary">
                        <div class="name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="email">{{ Auth::user()->email }}</div>
                        <span class="ogc-profile-role capitalize">{{ Auth::user()->role }}</span>
                    </div>
                    <a href="{{ route('profile.edit') }}">
                        <i class="fas fa-circle-user mr-3 text-[var(--maroon-soft)]"></i> Profile Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to log out?')">
                            <i class="fas fa-arrow-right-from-bracket mr-3 text-[var(--maroon-soft)]"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- ── SIDEBAR (Admin) ── --}}
    <nav id="ogcSidebar" class="ogc-sidebar fixed top-16 left-0 h-[calc(100vh-4rem)] w-64 flex flex-col z-30">
        <div class="sidebar-user-section">
            <div class="sidebar-user-card">
                <div class="flex items-center gap-3">
                    <div class="sidebar-user-avatar">
                        <i class="fas fa-shield-halved text-white text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="sidebar-user-name truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="sidebar-user-email truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
                <span class="sidebar-role-pill">
                    <i class="fas fa-shield-halved text-[10px]"></i> Administrator
                </span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto px-3 pb-3">
            <div class="space-y-0.5 pt-2">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
                    <a href="{{ route('admin.analytics') }}" class="sidebar-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <i class="fas fa-chart-column"></i>
                    <span>Analytics</span>
                </a>
                <a href="{{ route('admin.students') }}" class="sidebar-link {{ request()->routeIs('admin.students', 'admin.students.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate"></i>
                    <span>Students</span>
                </a>
                <a href="{{ route('admin.counselors') }}" class="sidebar-link {{ request()->routeIs('admin.counselors', 'admin.counselors.*') ? 'active' : '' }}">
                    <i class="fas fa-user-doctor"></i>
                    <span>Counselors</span>
                </a>
                <a href="{{ route('admin.appointments') }}" class="sidebar-link {{ request()->routeIs('admin.appointments') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
                <a href="{{ route('admin.events') }}" class="sidebar-link {{ request()->routeIs('admin.events') || request()->routeIs('admin.events.create') ? 'active' : '' }}">
                    <i class="fas fa-calendar-days"></i>
                    <span>Events</span>
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="sidebar-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
                <a href="{{ route('admin.resources.index') }}" class="sidebar-link {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i>
                    <span>Resources</span>
                </a>
                <a href="{{ route('admin.feedback.index') }}" class="sidebar-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                    <i class="fas fa-message"></i>
                    <span>Feedback</span>
                </a>
                <a href="{{ route('admin.services.index') }}" class="sidebar-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span>Services</span>
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="sidebar-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <i class="fas fa-circle-question"></i>
                    <span>FAQs</span>
                </a>
            

                <div class="sidebar-divider"></div>

                <div class="sidebar-section-header">
                    Quick Actions
                </div>

                <a href="{{ route('admin.events.create') }}" class="sidebar-link {{ request()->routeIs('admin.events.create') ? 'active' : '' }}">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Create Event</span>
                </a>
                <a href="{{ route('admin.announcements.create') }}" class="sidebar-link {{ request()->routeIs('admin.announcements.create') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Create Announcement</span>
                </a>
                <a href="{{ route('admin.resources.create') }}" class="sidebar-link {{ request()->routeIs('admin.resources.create') ? 'active' : '' }}">
                    <i class="fas fa-folder-plus"></i>
                    <span>Create Resource   </span>
                </a>
                <a href="{{ route('admin.faqs.create') }}" class="sidebar-link {{ request()->routeIs('admin.faqs.create') ? 'active' : '' }}">
                    <i class="fas fa-circle-question"></i>
                    <span>Create FAQ</span>
                </a>
            </div>
        </div>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link border-0" onclick="return confirm('Are you sure you want to log out?')">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <div id="ogcMainContent" class="ml-64 pt-16 min-h-screen ogc-main-shell">
        @yield('content')
    </div>

    {{-- Mobile sidebar overlay backdrop --}}
    <div id="ogcSidebarOverlay"></div>

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
    const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');

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

    // Sidebar toggle
    if (sidebarToggleBtn) {
        const MOBILE_BP  = 640;
        const TABLET_BP  = 900;
        const ADMIN_KEY  = 'ogcAdminSidebarCollapsed';

        function isMobile()  { return window.innerWidth <= MOBILE_BP; }
        function isTablet()  { return window.innerWidth > MOBILE_BP && window.innerWidth <= TABLET_BP; }

        // Restore desktop collapsed state
        if (!isMobile() && !isTablet()) {
            if (localStorage.getItem(ADMIN_KEY) === 'true') {
                document.body.classList.add('sidebar-collapsed');
            }
        }

        // Remove pre-collapsed class after first paint
        requestAnimationFrame(function() {
            requestAnimationFrame(function() {
                document.documentElement.classList.remove('sidebar-pre-collapsed');
            });
        });

        const overlay = document.getElementById('ogcSidebarOverlay');

        function closeMobileSidebar() {
            document.body.classList.remove('sidebar-mobile-open');
        }

        if (overlay) {
            overlay.addEventListener('click', closeMobileSidebar);
        }

        // Close mobile sidebar when a nav link is clicked
        document.querySelectorAll('#ogcSidebar .sidebar-link, #ogcSidebar .logout-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (isMobile()) closeMobileSidebar();
            });
        });

        sidebarToggleBtn.addEventListener('click', function () {
            if (isMobile()) {
                // Mobile: toggle overlay drawer
                document.body.classList.toggle('sidebar-mobile-open');
            } else if (isTablet()) {
                // Tablet: toggle expanded state
                document.body.classList.toggle('sidebar-expanded');
            } else {
                // Desktop: toggle collapsed state
                document.body.classList.toggle('sidebar-collapsed');
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                localStorage.setItem(ADMIN_KEY, isCollapsed ? 'true' : 'false');
            }
        });

        // On resize, clean up state
        window.addEventListener('resize', function() {
            if (isMobile()) {
                document.body.classList.remove('sidebar-collapsed', 'sidebar-expanded');
            } else if (isTablet()) {
                document.body.classList.remove('sidebar-collapsed', 'sidebar-mobile-open');
            } else {
                document.body.classList.remove('sidebar-mobile-open', 'sidebar-expanded');
                document.body.style.overflow = '';
            }
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

{{-- Global Confirm Modal --}}
<div id="ogcConfirmOverlay" style="
    display:none; position:fixed; inset:0; z-index:10000;
    background:rgba(44,36,32,0.45); backdrop-filter:blur(4px);
    align-items:center; justify-content:center; padding:24px;">
    <div style="
        background:#fff; border-radius:16px; width:100%; max-width:400px;
        padding:28px 28px 24px; box-shadow:0 24px 60px rgba(44,36,32,0.18);
        border:1px solid #e5e0db; animation:alertSlideIn 0.22s ease;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:14px;">
            <div style="
                width:36px; height:36px; border-radius:10px; flex-shrink:0;
                background:linear-gradient(135deg,#5c1a1a,#7a2a2a);
                display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-question" style="color:#fef9e7; font-size:14px;"></i>
            </div>
            <span id="ogcConfirmTitle" style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.15em; color:#5c1a1a;"></span>
        </div>
        <p id="ogcConfirmMessage" style="font-size:14px; color:#2c2420; line-height:1.6; margin-bottom:22px;"></p>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button id="ogcConfirmCancel" style="
                padding:9px 22px; border-radius:8px; border:1.5px solid #e5e0db;
                background:#fff; color:#6b5e57; font-size:13px; font-weight:600;
                cursor:pointer; font-family:inherit;">Cancel</button>
            <button id="ogcConfirmOk" style="
                padding:9px 22px; border-radius:8px; border:none;
                background:linear-gradient(135deg,#5c1a1a,#7a2a2a); color:#fff;
                font-size:13px; font-weight:700; cursor:pointer; font-family:inherit;
                box-shadow:0 4px 12px rgba(92,26,26,0.25);">Confirm</button>
        </div>
    </div>
</div>
<script>
    (function() {
        let _resolve = null;
        const overlay   = document.getElementById('ogcConfirmOverlay');
        const msgEl     = document.getElementById('ogcConfirmMessage');
        const titleEl   = document.getElementById('ogcConfirmTitle');
        const okBtn     = document.getElementById('ogcConfirmOk');
        const cancelBtn = document.getElementById('ogcConfirmCancel');

        function showConfirm(message) {
            titleEl.textContent   = 'Confirm Action';
            msgEl.textContent     = message || 'Are you sure?';
            okBtn.textContent     = 'Confirm';
            overlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            return new Promise(resolve => { _resolve = resolve; });
        }
        window.ogcConfirm = showConfirm;

        function close(result) {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
            if (_resolve) { _resolve(result); _resolve = null; }
        }
        okBtn.addEventListener('click',     () => close(true));
        cancelBtn.addEventListener('click', () => close(false));
        overlay.addEventListener('click', e => { if (e.target === overlay) close(false); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') close(false); });

        window.confirm = function(message) {
            showConfirm(message).then(result => {
                if (!result) return;
                const active = document._ogcConfirmTrigger;
                if (!active) return;
                document._ogcConfirmTrigger = null;
                if (active.tagName === 'FORM') {
                    active._ogcSkipConfirm = true;
                    active.submit();
                } else if (active.form) {
                    active.form._ogcSkipConfirm = true;
                    active.form.submit();
                } else if (active.href && active.href !== '#') {
                    window.location.href = active.href;
                }
            });
            return false;
        };

        document.addEventListener('click', function(e) {
            const el = e.target.closest('[onclick], button[type="submit"], a[onclick]');
            if (el) document._ogcConfirmTrigger = el;
        }, true);

        document.addEventListener('submit', function(e) {
            if (e.target._ogcSkipConfirm) { e.target._ogcSkipConfirm = false; return; }
            document._ogcConfirmTrigger = e.target;
        }, true);
    })();
</script>

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
                <i class="fas fa-xmark"></i>
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