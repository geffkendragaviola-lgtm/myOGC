@extends('layouts.app')

@section('title', 'Events - OGC')

@section('content')
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

    .announce-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }

    .announce-glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        opacity: 0.25;
    }
    .announce-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .announce-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .glass-card, .hero-card, .panel-card, .stats-card, .event-card-new, .summary-card {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        border: 1px solid var(--border-soft);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 8px rgba(44, 36, 32, 0.04);
        transition: box-shadow 0.2s ease;
    }
    .glass-card:hover, .panel-card:hover, .event-card-new:hover, .summary-card:hover {
        box-shadow: 0 4px 14px rgba(44, 36, 32, 0.06);
    }

    .hero-card::before, .panel-card::before, .event-card-new::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.16em; color: var(--maroon-700);
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
        pointer-events: none;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }

    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .filter-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .filter-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft); }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .table-header-bar {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.6rem;
        padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.4);
    }
    .table-live-pill {
        display: inline-flex; align-items: center; font-size: 0.65rem; color: var(--text-secondary);
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft); padding: 0.25rem 0.5rem;
        border-radius: 999px; font-weight: 500;
    }

    .input-field {
        border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary);
        outline: none; transition: all 0.2s ease; font-size: 0.8rem;
    }
    .input-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    .stats-card { transition: all 0.2s ease; }
    .stats-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(44,36,32,0.06); }
    .stats-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; }
    .mini-progress { width: 100%; background: #f5f0eb; border-radius: 999px; height: 0.3rem; overflow: hidden; }
    .mini-progress > div { height: 100%; border-radius: 999px; }

    .event-card-new { transition: all 0.22s ease; }
    .event-card-new:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(44,36,32,0.06); }

    .event-banner {
        position: relative; overflow: hidden; padding: 0.85rem; color: white;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
    }
    .event-banner::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 35%); pointer-events: none;
    }
    .event-type-pill {
        display: inline-flex; align-items: center; border-radius: 999px; background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(6px);
        font-size: 9px; padding: 0.2rem 0.5rem; font-weight: 600; text-transform: capitalize;
    }
    .status-badge { font-size: 0.65rem; padding: 0.2rem 0.5rem; border-radius: 9999px; font-weight: 700; }
    .status-active { background-color: rgba(209,250,229,0.95); color: #059669; }
    .status-inactive { background-color: rgba(254,226,226,0.95); color: #b91c1c; }

    .event-meta-row { display: flex; align-items: center; gap: 0.5rem; color: var(--text-secondary); font-size: 0.8rem; }
    .event-meta-icon { width: 1.5rem; height: 1.5rem; border-radius: 0.45rem; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }

    .action-btn-soft {
        display: inline-flex; align-items: center; justify-content: center; border-radius: 0.6rem;
        padding: 0.45rem 0.55rem; font-size: 0.7rem; font-weight: 600; transition: all 0.15s ease;
    }
    .action-btn-soft:hover { transform: translateY(-1px); }

    .empty-state-icon {
        width: 3rem; height: 3rem; border-radius: 999px; display: flex;
        align-items: center; justify-content: center; background: rgba(245,240,235,0.6);
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.04); margin-inline: auto;
    }

    .pagination-btn { border-radius: 0.6rem; transition: all 0.2s ease; }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .stats-icon { width: 1.75rem; height: 1.75rem; }
    }
</style>

<div class="min-h-screen announce-shell">
    <div class="announce-glow one"></div>
    <div class="announce-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        {{-- Header --}}
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-days text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span> Events Management
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Manage Events</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">Create and manage mental health events, workshops, and seminars.</p>
                        </div>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-plus text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Quick Action</p>
                                <p class="summary-value">Create Event</p>
                                <p class="summary-subtext hidden sm:block">Add workshops, webinars, seminars, and conferences.</p>
                            </div>
                        </div>
                        <a href="{{ route('counselor.events.create') }}" class="primary-btn px-5 py-2.5 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Create Event</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-5 sm:mb-6">
            <div class="summary-card" style="background:#faf8f5; border-color:rgba(122,42,42,0.12);">
                <div class="relative p-4 flex items-center gap-3">
                    <div class="summary-icon flex-shrink-0" style="background:rgba(56,189,248,0.12); border-color:rgba(56,189,248,0.18); color:#0284c7;">
                        <i class="fas fa-calendar text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="summary-label" style="color:var(--text-muted);">Total</p>
                        <p class="summary-value" style="color:var(--text-primary);">{{ $events->count() }}</p>
                        <p class="summary-subtext" style="color:var(--text-secondary);">Events</p>
                    </div>
                </div>
            </div>

            <div class="summary-card" style="background:#f0fdf6; border-color:rgba(16,185,129,0.15);">
                <div class="relative p-4 flex items-center gap-3">
                    <div class="summary-icon flex-shrink-0" style="background:rgba(16,185,129,0.1); border-color:rgba(16,185,129,0.15); color:#059669;">
                        <i class="fas fa-circle-play text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="summary-label" style="color:var(--text-muted);">Active</p>
                        <p class="summary-value" style="color:var(--text-primary);">{{ $events->where('is_active', true)->count() }}</p>
                        <p class="summary-subtext" style="color:var(--text-secondary);">Currently live</p>
                    </div>
                </div>
            </div>

            <div class="summary-card" style="background:#fffbeb; border-color:rgba(245,158,11,0.15);">
                <div class="relative p-4 flex items-center gap-3">
                    <div class="summary-icon flex-shrink-0" style="background:rgba(245,158,11,0.1); border-color:rgba(245,158,11,0.15); color:#b45309;">
                        <i class="fas fa-clock text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="summary-label" style="color:var(--text-muted);">Upcoming</p>
                        <p class="summary-value" style="color:var(--text-primary);">{{ $events->where('event_start_date', '>=', now()->toDateString())->count() }}</p>
                        <p class="summary-subtext" style="color:var(--text-secondary);">Scheduled ahead</p>
                    </div>
                </div>
            </div>

            <div class="summary-card" style="background:#fef2f2; border-color:rgba(185,28,28,0.15);">
                <div class="relative p-4 flex items-center gap-3">
                    <div class="summary-icon flex-shrink-0" style="background:rgba(185,28,28,0.1); border-color:rgba(185,28,28,0.15); color:#b91c1c;">
                        <i class="fas fa-circle-exclamation text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="summary-label" style="color:var(--text-muted);">Required</p>
                        <p class="summary-value" style="color:var(--text-primary);">{{ $events->where('is_required', true)->count() }}</p>
                        <p class="summary-subtext" style="color:var(--text-secondary);">Mandatory events</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <div class="mb-4 bg-[#ecfdf5] border border-emerald-200 text-emerald-800 rounded-lg p-3 flex items-center gap-2 text-xs sm:text-sm">
                <i class="fas fa-circle-check text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif

        <div class="panel-card overflow-hidden">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-list text-[9px] sm:text-xs"></i></div>
                <div>
                    <h2 class="panel-title">Events List</h2>
                    <p class="panel-subtitle hidden sm:block">Manage all created events and registrations</p>
                </div>
            </div>

            <div class="table-header-bar">
                <div class="flex items-center gap-3">
                    <div class="table-header-icon" style="background: rgba(254,249,231,0.6); color: var(--gold-500); width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-days text-[9px] sm:text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-medium text-[#2c2420]">All Events</h2>
                        <p class="text-[10px] sm:text-xs text-[#8b7e76]">Total: {{ $events->count() }} items</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="table-live-pill">
                        <i class="fas fa-clock mr-1 text-[9px]"></i> Live updates
                    </span>
                </div>
            </div>

            <div class="p-3 sm:p-4 border-b border-[#e5e0db]/60">
                <form method="GET" action="{{ route('counselor.events.index') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="sm:col-span-2 lg:col-span-1">
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#a89f97] text-[9px] sm:text-xs"></i>
                                <input type="text" name="search"
                                       class="input-field w-full pl-8 sm:pl-9 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm"
                                       placeholder="Search events..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div>
                            <select name="status" class="input-field w-full px-3 py-2 sm:py-2.5 bg-white text-[#4a3f3a] text-xs sm:text-sm">
                                <option value="all" {{ request('status','all') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            </select>
                        </div>
                        <div>
                            <select name="type" class="input-field w-full px-3 py-2 sm:py-2.5 bg-white text-[#4a3f3a] text-xs sm:text-sm">
                                <option value="all" {{ request('type','all') == 'all' ? 'selected' : '' }}>All Types</option>
                                <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="seminar" {{ request('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="webinar" {{ request('type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="filter-btn w-full px-4 py-2 sm:py-2.5 font-medium flex items-center justify-center gap-2 text-xs sm:text-sm rounded-lg">
                                <i class="fas fa-filter text-[9px] sm:text-xs"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-4 sm:p-5">
                @if($events->isEmpty())
                    <div class="glass-card p-6 sm:p-8 text-center">
                        <div class="empty-state-icon mb-3">
                            <i class="fas fa-calendar-plus text-[#a89f97] text-xl sm:text-2xl"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-semibold text-[#4a3f3a] mb-1.5">No Events Found</h3>
                        <p class="text-[#8b7e76] text-xs sm:text-sm mb-4">No events match your current filters or none have been created yet.</p>
                        <a href="{{ route('counselor.events.create') }}"
                           class="inline-flex items-center px-4 py-2.5 primary-btn font-medium text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i> Create Your First Event
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($events as $event)
                            <div class="event-card-new flex flex-col h-full">
                        {{-- Banner --}}
                        <div class="event-banner flex-shrink-0">
                            <div class="relative flex justify-between items-start gap-2">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap gap-1 mb-1.5">
                                        <span class="event-type-pill">{{ $event->type }}</span>
                                        @if($event->is_required)
                                            <span class="event-type-pill" style="background:rgba(185,28,28,0.5);">
                                                <i class="fas fa-circle-exclamation text-[8px] mr-0.5"></i> Required
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-sm sm:text-base font-semibold leading-tight truncate">{{ $event->title }}</h3>
                                    <p class="text-[10px] sm:text-xs text-white/80 mt-1">
                                        @if($event->for_all_colleges)
                                            <i class="fas fa-globe mr-1"></i> All Colleges
                                        @else
                                            <i class="fas fa-building-columns mr-1"></i> {{ $event->colleges->count() }} college(s)
                                        @endif
                                    </p>
                                </div>
                                <span class="status-badge flex-shrink-0 {{ $event->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $event->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="p-4 flex flex-col flex-1">
                            <div class="space-y-2 mb-3">
                                <div class="event-meta-row">
                                    <span class="event-meta-icon bg-[#eff6ff] text-sky-500"><i class="fas fa-calendar-days text-[9px] sm:text-xs"></i></span>
                                    <span class="text-xs sm:text-sm truncate">{{ $event->date_range }}</span>
                                </div>
                                <div class="event-meta-row">
                                    <span class="event-meta-icon bg-[#fffbeb] text-amber-500"><i class="fas fa-clock text-[9px] sm:text-xs"></i></span>
                                    <span class="text-xs sm:text-sm truncate">{{ $event->time_range }}</span>
                                </div>
                                <div class="event-meta-row">
                                    <span class="event-meta-icon bg-[#fdf2f2] text-[#7a2a2a]/60"><i class="fas fa-location-dot text-[9px] sm:text-xs"></i></span>
                                    <span class="text-xs sm:text-sm truncate">{{ $event->location }}</span>
                                </div>
                                <div class="event-meta-row">
                                    <span class="event-meta-icon bg-[#ecfdf5] text-emerald-500"><i class="fas fa-users text-[9px] sm:text-xs"></i></span>
                                    <span class="text-xs sm:text-sm truncate">
                                        {{ $event->registered_count }} registered
                                        @if($event->max_attendees) / {{ $event->max_attendees }} max @endif
                                    </span>
                                </div>
                            </div>

                            <p class="text-[#8b7e76] text-[10px] sm:text-xs mb-3 line-clamp-2 leading-relaxed flex-1">
                                {{ Str::limit($event->description, 100) }}
                            </p>

                            {{-- Actions --}}
                            <div class="grid grid-cols-2 gap-2 mb-3">
                                <a href="{{ route('counselor.events.registrations', $event) }}"
                                   class="action-btn-soft bg-[#fffbeb] text-[#9a7b0a] hover:bg-[#fef3d1] text-center">
                                    <i class="fas fa-users mr-1.5 text-[9px]"></i> Registrations
                                </a>
                                <a href="{{ route('counselor.events.edit', $event) }}"
                                   class="action-btn-soft bg-[#f5f0eb] text-[#6b5e57] hover:bg-[#e5e0db] text-center">
                                    <i class="fas fa-pen-to-square mr-1.5 text-[9px]"></i> Edit
                                </a>

                                <button onclick="togglePin({{ $event->id }}, this)"
                                        class="action-btn-soft {{ $event->is_pinned ? 'bg-[#fef9e7] text-[#c9a227]' : 'bg-[#f5f0eb] text-[#8b7e76]' }} hover:bg-[#fef3c7] text-center"
                                        title="{{ $event->is_pinned ? 'Unpin' : 'Pin to top' }}">
                                    <i class="fas fa-thumbtack mr-1.5 text-[9px] {{ $event->is_pinned ? '' : 'opacity-50' }}"></i>
                                    {{ $event->is_pinned ? 'Pinned' : 'Pin' }}
                                </button>
                                <form action="{{ route('counselor.events.toggle-status', $event) }}" method="POST" class="contents">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="action-btn-soft {{ $event->is_active ? 'bg-[#fffbeb] text-[#9a7b0a] hover:bg-[#fef3d1]' : 'bg-[#ecfdf5] text-[#059669] hover:bg-[#d1fae5]' }} text-center w-full">
                                        <i class="fas {{ $event->is_active ? 'fa-pause' : 'fa-play' }} mr-1.5 text-[9px]"></i>
                                        {{ $event->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('counselor.events.destroy', $event) }}" method="POST" class="contents"
                                      onsubmit="return confirm('Delete this event? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="action-btn-soft bg-[#fdf2f2] text-[#b91c1c] hover:bg-[#fce4e4] text-center w-full">
                                        <i class="fas fa-trash-can mr-1.5 text-[9px]"></i> Delete
                                    </button>
                                </form>
                            </div>

                            <div class="pt-2.5 border-t border-[#e5e0db]/60 mt-auto">
                                <p class="text-[10px] text-[#8b7e76]">
                                    <i class="fas fa-clock mr-1"></i>
                                    Created: {{ $event->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="p-4 sm:p-5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                @if($events->hasPages())
                    {{ $events->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
                @else
                <div class="flex items-center justify-center gap-2 text-[10px] sm:text-xs text-[#8b7e76]">
                    <i class="fas fa-circle-check text-[#059669]"></i>
                    <span>Showing all <span class="font-semibold text-[#2c2420]">{{ $events->count() }}</span> events</span>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
function togglePin(id, btn) {
    fetch(`/counselor/events/${id}/toggle-pin`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.is_pinned) {
            btn.classList.remove('bg-[#f5f0eb]', 'text-[#8b7e76]');
            btn.classList.add('bg-[#fef9e7]', 'text-[#c9a227]');
            btn.title = 'Unpin';
            btn.innerHTML = '<i class="fas fa-thumbtack mr-1.5 text-[9px]"></i> Pinned';
        } else {
            btn.classList.remove('bg-[#fef9e7]', 'text-[#c9a227]');
            btn.classList.add('bg-[#f5f0eb]', 'text-[#8b7e76]');
            btn.title = 'Pin to top';
            btn.innerHTML = '<i class="fas fa-thumbtack mr-1.5 text-[9px] opacity-50"></i> Pin';
        }
    });
}
</script>
@endsection
