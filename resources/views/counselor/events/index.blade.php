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

    .events-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 2rem;
    }
    .events-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .events-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .events-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .stat-card, .event-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .stat-card:hover, .event-card:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before, .stat-card::before, .event-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
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

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { 
        width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; 
        align-items: center; justify-content: center; 
        background: rgba(254,249,231,0.7); color: var(--maroon-700); 
    }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .stat-card {
        display: block; text-decoration: none;
    }
    .stat-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; flex-shrink: 0;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
    }
    .stat-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; color: var(--text-secondary); }
    .stat-value { font-size: 1.2rem; font-weight: 800; color: var(--text-primary); margin-top: 0.25rem; }

    .alert-success, .alert-error {
        display: flex; align-items: flex-start; gap: 0.5rem;
        border-radius: 0.6rem; padding: 0.75rem 1rem; font-size: 0.8rem; font-weight: 500;
    }
    .alert-success {
        border: 1px solid rgba(16,185,129,0.3); background: rgba(240,253,244,0.9); color: #065f46;
    }
    .alert-error {
        border: 1px solid rgba(185,28,28,0.3); background: rgba(253,242,242,0.9); color: #7a2a2a;
    }

    .event-card {
        border-left: 3px solid var(--maroon-700);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .event-card.required { border-left-color: #b91c1c; }
    .event-card:hover { transform: translateY(-2px); }

    .event-header {
        position: relative; height: 10rem; overflow: hidden;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    .event-header img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.3s ease;
    }
    .event-card:hover .event-header img { transform: scale(1.05); }
    .event-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(44,36,32,0.7), transparent);
    }
    .event-header-content {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 0.75rem; color: white;
    }

    .event-type, .event-badge, .event-status, .college-chip {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.45rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .event-type { background: rgba(122,42,42,0.9); color: #fef9e7; }
    .event-badge.required { background: rgba(185,28,28,0.9); color: #fef9e7; }
    .event-status.active { background: rgba(16,185,129,0.9); color: #fef9e7; }
    .event-status.inactive { background: rgba(185,28,28,0.9); color: #fef9e7; }
    .college-chip.all { background: rgba(16,185,129,0.9); color: white; }
    .college-chip.specific { background: rgba(122,42,42,0.9); color: white; }

    .event-title { font-size: 0.85rem; font-weight: 600; color: white; margin-top: 0.25rem; }

    .event-body { padding: 0.75rem; }
    .event-meta { font-size: 0.65rem; color: var(--text-secondary); display: flex; align-items: center; gap: 0.3rem; margin-bottom: 0.25rem; }
    .event-meta i { font-size: 0.6rem; color: var(--text-muted); width: 0.8rem; text-align: center; }
    .event-desc { font-size: 0.7rem; color: var(--text-secondary); margin: 0.5rem 0; line-height: 1.4; }
    .event-colleges { display: flex; flex-wrap: wrap; gap: 0.25rem; margin: 0.5rem 0; }
    .event-college-tag {
        font-size: 0.6rem; padding: 0.15rem 0.4rem; border-radius: 999px;
        background: rgba(240,253,244,0.9); color: #065f46; border: 1px solid rgba(16,185,129,0.3);
    }

    .event-actions {
        display: flex; flex-wrap: wrap; gap: 0.35rem; margin-top: 0.5rem;
    }
    .action-btn {
        flex: 1; min-width: 2rem;
        padding: 0.35rem 0.5rem; border-radius: 0.4rem;
        font-size: 0.65rem; font-weight: 500; text-align: center;
        transition: all 0.18s ease; display: inline-flex; align-items: center; justify-content: center; gap: 0.25rem;
    }
    .action-btn.registrations {
        color: var(--maroon-700); background: rgba(254,249,231,0.9); border: 1px solid rgba(212,175,55,0.3);
    }
    .action-btn.registrations:hover { background: rgba(212,175,55,0.2); border-color: var(--gold-400); }
    .action-btn.edit {
        color: var(--text-secondary); background: rgba(245,240,235,0.9); border: 1px solid var(--border-soft);
    }
    .action-btn.edit:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); color: var(--maroon-700); }
    .action-btn.toggle {
        color: #7a2a2a; background: rgba(254,249,231,0.9); border: 1px solid rgba(212,175,55,0.3);
    }
    .action-btn.toggle:hover { background: rgba(212,175,55,0.2); border-color: var(--gold-400); }
    .action-btn.delete {
        color: #b91c1c; background: rgba(253,242,242,0.9); border: 1px solid rgba(185,28,28,0.3);
    }
    .action-btn.delete:hover { background: rgba(253,242,242,0.7); border-color: #b91c1c; }

    .event-footer {
        display: flex; justify-content: space-between; align-items: center;
        padding-top: 0.5rem; margin-top: 0.5rem; border-top: 1px solid var(--border-soft)/60;
        font-size: 0.6rem; color: var(--text-muted);
    }
    .event-period {
        display: inline-flex; align-items: center; gap: 0.2rem;
        padding: 0.15rem 0.4rem; border-radius: 999px;
        background: rgba(245,240,235,0.9); color: var(--text-secondary);
    }

    .empty-state {
        text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);
    }
    .empty-state-icon {
        width: 4rem; height: 4rem; border-radius: 1rem;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
        margin-bottom: 1rem; font-size: 1.25rem;
    }

    .overview-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;
        background: rgba(255,255,255,0.95); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 0.75rem;
    }
    .overview-item {
        text-align: center; padding: 0.5rem; border-radius: 0.4rem;
    }
    .overview-item.active { background: rgba(254,249,231,0.9); }
    .overview-item.upcoming { background: rgba(240,253,244,0.9); }
    .overview-item.required { background: rgba(254,249,231,0.9); }
    .overview-item.registrations { background: rgba(255,244,229,0.9); }
    .overview-value { font-size: 0.9rem; font-weight: 700; }
    .overview-value.active { color: var(--maroon-700); }
    .overview-value.upcoming { color: #065f46; }
    .overview-value.required { color: var(--maroon-700); }
    .overview-value.registrations { color: #92400e; }
    .overview-label { font-size: 0.6rem; color: var(--text-secondary); margin-top: 0.1rem; }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    @media (max-width: 639px) {
        .hero-card .flex { flex-direction: column; align-items: flex-start !important; }
        .primary-btn { width: 100%; justify-content: center; }
        .stat-card { text-align: center; }
        .stat-card .flex { flex-direction: column; align-items: center !important; gap: 0.35rem !important; }
        .stat-icon { margin: 0 auto; }
        .event-header { height: 8rem; }
        .event-title { font-size: 0.8rem; }
        .event-meta { font-size: 0.6rem; }
        .event-desc { font-size: 0.65rem; }
        .action-btn { padding: 0.3rem 0.4rem; font-size: 0.6rem; }
        .action-btn span { display: none; }
        .event-footer { flex-direction: column; align-items: flex-start; gap: 0.25rem; }
        .overview-grid { grid-template-columns: repeat(2, 1fr); }
        .overview-value { font-size: 0.85rem; }
        .overview-label { font-size: 0.55rem; }
    }
</style>

@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<div class="min-h-screen events-shell">
    <div class="events-glow one"></div>
    <div class="events-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-alt text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Counselor Portal
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Manage Events</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Create and manage mental health events, workshops, and seminars
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('counselor.events.create') }}"
                       class="primary-btn px-4 py-2 text-xs sm:text-sm w-full md:w-auto">
                        <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i> Create New Event
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <div class="stat-card">
                <div class="relative p-3 sm:p-4 flex items-center gap-2 sm:gap-3">
                    <div class="stat-icon flex-shrink-0">
                        <i class="fas fa-calendar text-[10px] sm:text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="stat-label">Total</p>
                        <p class="stat-value">{{ $events->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="relative p-3 sm:p-4 flex items-center gap-2 sm:gap-3">
                    <div class="stat-icon flex-shrink-0" style="background: rgba(254,249,231,0.9); color: #c9a227;">
                        <i class="fas fa-play-circle text-[10px] sm:text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="stat-label">Active</p>
                        <p class="stat-value">{{ $events->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="relative p-3 sm:p-4 flex items-center gap-2 sm:gap-3">
                    <div class="stat-icon flex-shrink-0" style="background: rgba(254,249,231,0.9); color: var(--maroon-700);">
                        <i class="fas fa-users text-[10px] sm:text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="stat-label">Upcoming</p>
                        <p class="stat-value">{{ $events->where('event_start_date', '>=', now()->toDateString())->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="relative p-3 sm:p-4 flex items-center gap-2 sm:gap-3">
                    <div class="stat-icon flex-shrink-0" style="background: rgba(255,244,229,0.9); color: #92400e;">
                        <i class="fas fa-exclamation-circle text-[10px] sm:text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="stat-label">Required</p>
                        <p class="stat-value">{{ $events->where('is_required', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Messages -->

        <!-- Events Grid -->
        @if($events->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <p class="text-sm sm:text-base font-medium text-[#2c2420]">No Events Created Yet</p>
                <p class="text-xs sm:text-sm text-[#8b7e76] mt-1">Start by creating your first event to help students with mental health awareness.</p>
                <a href="{{ route('counselor.events.create') }}"
                   class="primary-btn px-4 py-2 text-xs sm:text-sm mt-4">
                    <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i> Create Your First Event
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                @foreach($events as $event)
                    <div class="event-card {{ $event->is_required ? 'required' : '' }}">
                        <!-- Event Image Header -->
                        <div class="event-header">
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}">
                            <div class="event-overlay"></div>
                            <div class="event-header-content">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="event-type">{{ $event->type }}</span>
                                        @if($event->is_required)
                                            <span class="event-badge required">
                                                <i class="fas fa-exclamation-circle text-[8px]"></i> Required
                                            </span>
                                        @endif
                                    </div>
                                    <span class="event-status {{ $event->is_active ? 'active' : 'inactive' }}">
                                        {{ $event->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <h3 class="event-title line-clamp-2">{{ $event->title }}</h3>
                            </div>
                            <!-- College Badge -->
                            <div class="absolute top-3 right-3">
                                @if($event->for_all_colleges)
                                    <span class="college-chip all">
                                        <i class="fas fa-globe text-[8px]"></i> All
                                    </span>
                                @else
                                    <span class="college-chip specific">
                                        <i class="fas fa-university text-[8px]"></i> {{ $event->colleges->count() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="event-body">
                            <!-- Date and Time -->
                            <div class="space-y-1 mb-3">
                                <div class="event-meta">
                                    <i class="far fa-calendar"></i>
                                    <span>{{ $event->date_range }}</span>
                                </div>
                                <div class="event-meta">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $event->time_range }}</span>
                                </div>
                                <div class="event-meta">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                                @if($event->max_attendees)
                                    <div class="event-meta">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $event->registered_count }}/{{ $event->max_attendees }} registered</span>
                                    </div>
                                @else
                                    <div class="event-meta">
                                        <i class="fas fa-users"></i>
                                        <span>{{ $event->registered_count }} registered (Unlimited)</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="event-desc line-clamp-2">
                                {{ Str::limit($event->description, 120) }}
                            </p>

                            <!-- Specific Colleges -->
                            @if(!$event->for_all_colleges && $event->colleges->isNotEmpty())
                                <div class="mb-3">
                                    <p class="text-[10px] font-semibold text-[#6b5e57] mb-1">Available for:</p>
                                    <div class="event-colleges">
                                        @foreach($event->colleges->take(2) as $college)
                                            <span class="event-college-tag">{{ $college->name }}</span>
                                        @endforeach
                                        @if($event->colleges->count() > 2)
                                            <span class="event-college-tag">+{{ $event->colleges->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="event-actions">
                                <a href="{{ route('counselor.events.registrations', $event) }}"
                                   class="action-btn registrations">
                                    <i class="fas fa-users text-[9px]"></i>
                                    <span>Registrations</span>
                                </a>

                                <a href="{{ route('counselor.events.edit', $event) }}"
                                   class="action-btn edit">
                                    <i class="fas fa-edit text-[9px]"></i>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('counselor.events.toggle-status', $event) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="action-btn toggle w-full">
                                        <i class="fas {{ $event->is_active ? 'fa-pause' : 'fa-play' }} text-[9px]"></i>
                                        <span>{{ $event->is_active ? 'Deactivate' : 'Activate' }}</span>
                                    </button>
                                </form>

                                <form action="{{ route('counselor.events.destroy', $event) }}" method="POST" class="flex-1"
                                      onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="action-btn delete w-full">
                                        <i class="fas fa-trash text-[9px]"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                            </div>

                            <!-- Event Status and Created Info -->
                            <div class="event-footer">
                                <div>
                                    <i class="far fa-calendar-plus mr-1"></i>
                                    {{ $event->created_at->format('M j, Y') }}
                                </div>
                                <div>
                                    @if($event->is_upcoming)
                                        <span class="event-period">
                                            <i class="fas fa-clock text-[8px]"></i> Upcoming
                                        </span>
                                    @else
                                        <span class="event-period">
                                            <i class="fas fa-history text-[8px]"></i> Past
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Stats Footer -->
            <div class="mt-6 sm:mt-8">
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="p-4 sm:p-5">
                        <h3 class="panel-title mb-3 sm:mb-4">Event Overview</h3>
                        <div class="overview-grid">
                            <div class="overview-item active">
                                <div class="overview-value active">{{ $events->where('is_active', true)->count() }}</div>
                                <div class="overview-label">Active Events</div>
                            </div>
                            <div class="overview-item upcoming">
                                <div class="overview-value upcoming">
                                    {{ $events->where('event_start_date', '>=', now()->toDateString())->count() }}
                                </div>
                                <div class="overview-label">Upcoming</div>
                            </div>
                            <div class="overview-item required">
                                <div class="overview-value required">
                                    {{ $events->where('is_required', true)->count() }}
                                </div>
                                <div class="overview-label">Required</div>
                            </div>
                            <div class="overview-item registrations">
                                <div class="overview-value registrations">
                                    {{ $events->sum('registered_count') }}
                                </div>
                                <div class="overview-label">Total Registrations</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection