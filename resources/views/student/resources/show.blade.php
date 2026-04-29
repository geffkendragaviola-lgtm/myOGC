<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $resource->title }} — Mental Health Corner</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --maroon-900: #3a0c0c;
        --maroon-800: #5c1a1a;
        --maroon-700: #7a2a2a;
        --gold-500: #c9a227;
        --gold-400: #d4af37;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--bg-warm);
        color: var(--text-primary);
        min-height: 100vh;
    }

    /* ── MHC Navbar ── */
    .mhc-navbar {
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: linear-gradient(90deg, #5b0f0f, #8f1d1d, #a11f2f);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 24px rgba(91, 15, 15, 0.18);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .mhc-navbar.scrolled { box-shadow: 0 12px 28px rgba(91, 15, 15, 0.24); }
    .nav-link { color: white; font-weight: 600; transition: 0.25s ease; }
    .nav-link:hover { color: rgba(255, 245, 235, 0.88); }
    .dropdown-panel {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        background: #fffdfb;
        box-shadow: 0 16px 40px rgba(91, 15, 15, 0.12);
        border-radius: 16px;
        padding: 0.5rem;
        width: 220px;
        z-index: 1001;
        border: 1px solid #e8ddd2;
    }
    .dropdown-link {
        display: block;
        padding: 0.75rem 0.9rem;
        border-radius: 12px;
        color: #2f2522;
        transition: all 0.2s ease;
    }
    .dropdown-link:hover { color: #8f1d1d; background: #f8f1e8; }
    .profile-dropdown-content {
        position: absolute;
        right: 0;
        top: 100%;
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        box-shadow: 0 16px 40px rgba(91, 15, 15, 0.12);
        border-radius: 16px;
        padding: 1rem;
        min-width: 240px;
        z-index: 1001;
        margin-top: 0.7rem;
        border: 1px solid #e8ddd2;
    }

    /* ── Page shell ── */
    .page-shell {
        position: relative;
        background:
            radial-gradient(circle at top left, rgba(212,175,55,0.06), transparent 28%),
            radial-gradient(circle at bottom right, rgba(92,26,26,0.06), transparent 28%),
            var(--bg-warm);
    }

    /* ── Hero banner ── */
    .hero-banner {
        position: relative; width: 100%; height: 26rem; overflow: hidden;
        background: linear-gradient(135deg, var(--maroon-900) 0%, var(--maroon-700) 100%);
    }
    .hero-banner img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 8s ease;
    }
    .hero-banner:hover img { transform: scale(1.04); }
    .hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(30,8,8,0.82) 0%, rgba(30,8,8,0.3) 50%, transparent 100%);
    }
    .hero-content {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 2.5rem 2rem 2rem;
        max-width: 56rem; margin: 0 auto;
    }
    .hero-category {
        display: inline-flex; align-items: center; gap: 0.45rem;
        background: rgba(212,175,55,0.92); color: var(--maroon-900);
        padding: 0.3rem 0.85rem; border-radius: 999px;
        font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.12em; margin-bottom: 0.75rem;
    }
    .hero-title {
        font-size: clamp(1.5rem, 4vw, 2.4rem);
        font-weight: 800; color: #fff; line-height: 1.15;
        letter-spacing: -0.025em;
        text-shadow: 0 2px 16px rgba(0,0,0,0.35);
    }

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-size: 0.78rem; color: var(--text-muted);
        padding: 1.25rem 0 0;
    }
    .breadcrumb a { color: var(--text-secondary); font-weight: 500; transition: color 0.18s; }
    .breadcrumb a:hover { color: var(--maroon-700); }
    .breadcrumb-sep { color: var(--border-soft); }

    /* ── Layout grid ── */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 21rem;
        gap: 2.25rem;
        align-items: start;
        padding: 2rem 0 4rem;
    }
    @media (max-width: 1023px) {
        .content-grid { grid-template-columns: 1fr; }
        .hero-banner { height: 18rem; }
    }
    @media (max-width: 639px) {
        .hero-banner { height: 13rem; }
        .hero-content { padding: 1.5rem 1.25rem 1.25rem; }
        .hero-title { font-size: 1.25rem; }
        .topbar { padding: 0.65rem 1rem; }
        .topbar-brand { font-size: 0.875rem; }
        .topbar-back { padding: 0.35rem 0.75rem; font-size: 0.75rem; }
        .content-grid { padding: 1.25rem 0 2.5rem; }
        .max-w-screen-xl { padding-left: 1rem; padding-right: 1rem; }
    }

    /* ── Article card ── */
    .article-card {
        position: relative; overflow: hidden;
        background: rgba(255,255,255,0.98);
        border: 1px solid var(--border-soft);
        border-radius: 1.25rem;
        box-shadow: 0 4px 28px rgba(44,36,32,0.07);
    }
    .article-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 35%);
    }
    .article-topline {
        position: absolute; inset-inline: 0; top: 0; height: 3px;
        background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%);
    }

    /* ── Meta row ── */
    .meta-row {
        display: flex; flex-wrap: wrap; align-items: center; gap: 1.25rem;
        padding-bottom: 1.35rem;
        border-bottom: 1px solid var(--border-soft);
        margin-bottom: 1.75rem;
    }
    .meta-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.8rem; color: var(--text-secondary);
    }
    .meta-chip i { color: var(--gold-500); font-size: 0.72rem; }

    /* ── Article body ── */
    .article-body {
        font-size: 1.05rem;
        line-height: 1.9;
        color: var(--text-primary);
        white-space: pre-line;
        word-break: break-word;
    }

    /* ── CTA ── */
    .cta-btn {
        display: inline-flex; align-items: center; gap: 0.65rem;
        padding: 0.9rem 2rem; border-radius: 0.75rem;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; font-weight: 700; font-size: 1rem;
        box-shadow: 0 6px 20px rgba(92,26,26,0.22);
        transition: all 0.22s; text-decoration: none;
    }
    .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(92,26,26,0.3); color: #fef9e7; }

    /* ── Disclaimer ── */
    .disclaimer-box {
        background: #fff7ed; border: 1px solid #fdba74;
        border-radius: 0.75rem; padding: 1rem; cursor: pointer;
        transition: background 0.18s;
    }
    .disclaimer-box:hover { background: #fff3e0; }
    .disclaimer-header {
        display: flex; align-items: center; justify-content: space-between;
        font-size: 0.75rem; font-weight: 700; color: #9a3412;
        text-transform: uppercase; letter-spacing: 0.06em;
    }
    .disclaimer-body {
        margin-top: 0.75rem; font-size: 0.82rem; color: #7c2d12; line-height: 1.6;
    }

    /* ── Sidebar cards ── */
    .side-card {
        position: relative; overflow: hidden;
        background: rgba(255,255,255,0.98);
        border: 1px solid var(--border-soft);
        border-radius: 1rem;
        box-shadow: 0 2px 14px rgba(44,36,32,0.05);
    }
    .side-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 30%);
    }
    .side-topline {
        position: absolute; inset-inline: 0; top: 0; height: 2.5px;
        background: linear-gradient(90deg, var(--maroon-800), var(--gold-400));
    }
    .side-label {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--text-muted);
        padding: 1rem 1.25rem 0.5rem;
    }

    /* ── Related items ── */
    .related-item {
        display: flex; align-items: center; gap: 0.9rem;
        padding: 0.85rem 1.25rem;
        border-top: 1px solid var(--border-soft);
        transition: background 0.15s; text-decoration: none;
    }
    .related-item:hover { background: rgba(254,249,231,0.55); }
    .related-thumb {
        width: 3.75rem; height: 3.75rem; border-radius: 0.65rem; flex-shrink: 0;
        background: linear-gradient(135deg, var(--maroon-800), var(--maroon-700));
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .related-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .related-thumb i { color: rgba(255,255,255,0.65); font-size: 1.15rem; }
    .related-name {
        font-size: 0.84rem; font-weight: 600; color: var(--text-primary); line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .related-sub { font-size: 0.72rem; color: var(--text-muted); margin-top: 0.2rem; }

    .footer-shell {
        background: linear-gradient(180deg, #4d1212 0%, #3f0e0e 100%);
        color: white;
        border-top: 1px solid rgba(255,255,255,0.06);
    }
    </style>
</head>
<body class="page-shell">

    <nav class="mhc-navbar py-4" id="mainNavbar">
        <div class="container mx-auto px-6 flex items-center" style="display:grid;grid-template-columns:1fr auto 1fr;align-items:center;">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 no-underline" style="text-decoration:none;">
                    <div style="width:2.6rem;height:2.6rem;border-radius:0.9rem;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.10);display:flex;align-items:center;justify-content:center;box-shadow:inset 0 1px 0 rgba(255,255,255,0.12);flex-shrink:0;">
                        <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-8 w-8 object-contain" onerror="this.style.display='none'">
                    </div>
                    <span class="text-white font-bold text-sm hidden md:block" style="line-height:1.1;letter-spacing:0.01em;">
                        my.OGC<br>
                        <span class="font-medium text-xs" style="color:#d4af37;">MSU-IIT Office of Guidance & Counseling</span>
                    </span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('dashboard') }}" class="nav-link">Home</a>

                @if(Auth::check() && Auth::user()->role === 'student')
                    <a href="{{ route('student.show', Auth::user()->student->id) }}" class="nav-link">Profile</a>
                    <div class="relative" id="services-dropdown">
                        <button class="nav-link flex items-center" id="services-dropdown-btn">
                            Services <i class="fas fa-chevron-down ml-1 text-sm"></i>
                        </button>
                        <div class="dropdown-panel hidden" id="services-dropdown-menu">
                            <a href="{{ route('bap') }}" class="dropdown-link">Book an Appointment</a>
                            <a href="{{ route('mhc') }}" class="dropdown-link" style="color:#8f1d1d;background:rgba(143,29,29,0.05);font-weight:600;">Mental Health Corner</a>
                        </div>
                    </div>
                    <a href="{{ route('feedback') }}" class="nav-link">Feedback</a>
                @endif
            </div>

            <div class="flex items-center space-x-4 justify-end">
                <div class="relative" id="notif-dropdown-wrapper">
                    <button id="notif-bell-btn" class="text-white p-2 rounded-full hover:bg-white/10 transition relative" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        @if(($unreadCount ?? 0) > 0)
                            <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 leading-none">
                                {{ ($unreadCount ?? 0) > 99 ? '99+' : ($unreadCount ?? 0) }}
                            </span>
                        @endif
                    </button>
                    <div id="notif-panel" class="hidden absolute right-0 top-[calc(100%+10px)] w-80 bg-white rounded-2xl shadow-xl border border-[#e8ddd2] z-[1002] overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-[#e8ddd2]">
                            <span class="font-semibold text-sm text-[#2f2522]">Notifications</span>
                            @if(($unreadCount ?? 0) > 0)
                                <button id="mark-all-read-btn" class="text-xs text-[#8f1d1d] hover:underline font-medium">Mark all as read</button>
                            @endif
                        </div>
                        <div class="overflow-y-auto divide-y divide-[#e8ddd2]" id="notif-list">
                            @forelse(($unreadNotifications ?? collect()) as $notif)
                                <div class="notif-item px-4 py-3 hover:bg-[#f6f1ea] cursor-pointer bg-blue-50/40" data-id="{{ $notif->id }}">
                                    <div class="text-xs font-semibold text-[#2f2522] truncate">{{ $notif->data['title'] ?? 'Notification' }}</div>
                                    <div class="text-xs text-[#766864] mt-0.5 line-clamp-2">{{ $notif->data['message'] ?? '' }}</div>
                                    <p class="text-[10px] text-[#a09490] mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center text-sm text-[#a09490]">
                                    <i class="fas fa-bell-slash text-2xl mb-2 block opacity-40"></i>
                                    No new notifications
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <button class="text-white p-2 rounded-full hover:bg-white/10 transition focus:outline-none" id="profileBtn">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="profile-dropdown-content hidden" id="profileMenu">
                        <div class="mb-3 border-b pb-2 border-[#e8ddd2]">
                            <div class="font-semibold text-[#2f2522]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                            <div class="text-sm text-[#766864]">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-[#8f1d1d] capitalize font-semibold mt-1">Role: {{ Auth::user()->role }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block py-2 text-[#2f2522] hover:text-[#8f1d1d] transition">
                            <i class="fas fa-circle-user mr-2"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t pt-2 mt-2 border-[#e8ddd2]">
                            @csrf
                            <button type="submit" class="w-full text-left block py-2 text-[#2f2522] hover:text-[#8f1d1d] transition">
                                <i class="fas fa-arrow-right-from-bracket mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero banner --}}
    <div class="hero-banner">
        <img src="{{ $resource->image_url }}"
             alt="{{ $resource->title }}"
             onerror="this.style.display='none'">
        <div class="hero-overlay"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 w-full">
            <div class="hero-content">
                <div class="hero-category">
                    <i class="{{ $resource->icon }} text-[10px]"></i>
                    {{ $categories[$category] }}
                </div>
                <h1 class="hero-title">{{ $resource->title }}</h1>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb">
            <a href="{{ route('mhc') }}"><i class="fas fa-heart text-[10px]"></i> Mental Health Corner</a>
            <span class="breadcrumb-sep">/</span>
            <a href="{{ route('student.resources.category', $category) }}">{{ $categories[$category] }}</a>
            <span class="breadcrumb-sep">/</span>
            <span class="font-medium text-[var(--text-primary)] truncate max-w-[220px]">{{ $resource->title }}</span>
        </nav>

        <div class="content-grid">

            {{-- Article --}}
            <div class="space-y-5">
                <div class="article-card">
                    <div class="article-topline"></div>
                    <div class="p-7 sm:p-9">

                        {{-- Meta --}}
                        <div class="meta-row">
                            @if($resource->user)
                            <span class="meta-chip">
                                <i class="fas fa-user-doctor"></i>
                                {{ $resource->user->first_name }} {{ $resource->user->last_name }}
                            </span>
                            @endif
                            <span class="meta-chip">
                                <i class="fas fa-tag"></i>
                                {{ $categories[$category] }}
                            </span>
                            <span class="meta-chip">
                                <i class="fas fa-calendar-days"></i>
                                {{ $resource->created_at->format('F j, Y') }}
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="article-body">{{ $resource->description }}</div>

                        {{-- Disclaimer --}}
                        @if($resource->show_disclaimer)
                        <div class="mt-7">
                            <div class="disclaimer-box" onclick="toggleDisclaimer()">
                                <div class="disclaimer-header">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-circle-exclamation text-amber-500"></i>
                                        Content Disclaimer
                                    </div>
                                    <i class="fas fa-chevron-down text-orange-600 transition-transform duration-200" id="disc-icon"></i>
                                </div>
                                <div id="disc-body" class="hidden disclaimer-body">
                                    {{ $resource->display_disclaimer }}
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- CTA --}}
                        @if($resource->link)
                        <div class="mt-9 pt-7 border-t border-[var(--border-soft)]">
                            <p class="text-sm text-[var(--text-muted)] mb-3">Ready to explore this resource?</p>
                            <a href="{{ $resource->link }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="cta-btn">
                                <i class="fas fa-external-link-alt text-sm"></i>
                                {{ $resource->button_text }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-5">

                {{-- About card --}}
                <div class="side-card">
                    <div class="side-topline"></div>
                    <p class="side-label">About this resource</p>
                    <div class="px-5 pb-5 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(254,249,231,0.8);color:var(--maroon-700);">
                                <i class="{{ $resource->icon }} text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[0.68rem] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Category</p>
                                <p class="text-sm font-semibold text-[var(--text-primary)] mt-0.5">{{ $categories[$category] }}</p>
                            </div>
                        </div>
                        @if($resource->user)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(254,249,231,0.8);color:var(--maroon-700);">
                                <i class="fas fa-user-doctor text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[0.68rem] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Added by</p>
                                <p class="text-sm font-semibold text-[var(--text-primary)] mt-0.5">
                                    {{ $resource->user->first_name }} {{ $resource->user->last_name }}
                                </p>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(254,249,231,0.8);color:var(--maroon-700);">
                                <i class="fas fa-calendar text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[0.68rem] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Published</p>
                                <p class="text-sm font-semibold text-[var(--text-primary)] mt-0.5">
                                    {{ $resource->created_at->format('M j, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Related --}}
                @if($related->count())
                <div class="side-card">
                    <div class="side-topline"></div>
                    <p class="side-label">More in {{ $categories[$category] }}</p>
                    @foreach($related as $rel)
                    <a href="{{ route('student.resources.show', [$category, $rel]) }}" class="related-item">
                        <div class="related-thumb">
                            @if($rel->image_url && !str_contains($rel->image_url, 'default-resource'))
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}"
                                     onerror="this.parentElement.innerHTML='<i class=\'{{ $rel->icon }}\'></i>'">
                            @else
                                <i class="{{ $rel->icon }}"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="related-name">{{ $rel->title }}</p>
                            <p class="related-sub">{{ Str::limit($rel->description, 60) }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                {{-- Back to MHC --}}
                <div class="side-card p-5" style="padding-top:1.25rem;">
                    <div class="side-topline"></div>
                    <p class="text-sm font-semibold text-[var(--text-primary)] mb-1">Looking for more?</p>
                    <p class="text-xs text-[var(--text-muted)] mb-4 leading-relaxed">Browse all resource categories in the Mental Health Corner.</p>
                    <a href="{{ route('mhc') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--maroon-700)] hover:text-[var(--maroon-900)] transition">
                        <i class="fas fa-heart text-[10px]"></i> Mental Health Corner
                    </a>
                </div>

            </aside>
        </div>
    </main>

    <footer style="background:linear-gradient(to right,#5b0f0f,#7b1717,#8f1d1d);color:white;" class="py-4 mt-auto footer-shell">
        <div class="container mx-auto px-6 text-center">
            <p class="text-[#f3e8df]">&copy; {{ date('Y') }} Office of Guidance and Counseling. All rights reserved.</p>
            <p class="text-sm text-[#e5caa9] mt-2">Committed to student support, wellness, and accessible guidance services</p>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) navbar?.classList.add('scrolled');
            else navbar?.classList.remove('scrolled');
        });

        // Profile dropdown
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');
        const notifBellBtn = document.getElementById('notif-bell-btn');
        const notifPanel = document.getElementById('notif-panel');

        if (profileBtn && profileMenu) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
                notifPanel?.classList.add('hidden');
            });
            document.addEventListener('click', () => profileMenu.classList.add('hidden'));
            profileMenu.addEventListener('click', (e) => e.stopPropagation());
        }

        if (notifBellBtn && notifPanel) {
            notifBellBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifPanel.classList.toggle('hidden');
                profileMenu?.classList.add('hidden');
            });
            document.addEventListener('click', () => notifPanel.classList.add('hidden'));
            notifPanel.addEventListener('click', (e) => e.stopPropagation());
        }

        // Mark all read
        const markAllBtn = document.getElementById('mark-all-read-btn');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', () => {
                fetch('/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                    .then(() => {
                        document.getElementById('notif-badge')?.remove();
                        document.getElementById('notif-list').innerHTML = '<div class="px-4 py-8 text-center text-sm text-[#a09490]"><i class="fas fa-bell-slash text-2xl mb-2 block opacity-40"></i>No new notifications</div>';
                        markAllBtn.remove();
                    });
            });
        }

        document.querySelectorAll('.notif-item').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.dataset.id;
                fetch(`/notifications/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
                    .then(() => { this.remove(); });
            });
        });

        // Services dropdown
        const servicesBtn = document.getElementById('services-dropdown-btn');
        const servicesMenu = document.getElementById('services-dropdown-menu');
        if (servicesBtn && servicesMenu) {
            servicesBtn.addEventListener('click', (e) => { e.stopPropagation(); servicesMenu.classList.toggle('hidden'); });
            document.addEventListener('click', () => servicesMenu.classList.add('hidden'));
            servicesMenu.addEventListener('click', (e) => e.stopPropagation());
        }

        function toggleDisclaimer() {
            const body = document.getElementById('disc-body');
            const icon = document.getElementById('disc-icon');
            body.classList.toggle('hidden');
            icon.style.transform = body.classList.contains('hidden') ? '' : 'rotate(180deg)';
        }
    </script>
</body>
</html>
