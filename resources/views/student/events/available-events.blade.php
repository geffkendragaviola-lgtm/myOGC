@extends('layouts.student')

@section('title', 'All Events - Mental Health Corner')

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
        --student-accent: #fef3c7;
        --student-success: #d1fae5;
        --student-warning: #fef3c7;
        --student-error: #fee2e2;
    }

    .events-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .events-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2;
    }
    .events-glow.one { top: -40px; left: -50px; width: 240px; height: 240px; background: var(--gold-400); }
    .events-glow.two { bottom: -50px; right: -70px; width: 280px; height: 280px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .event-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .event-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(92,26,26,0.08); }
    .hero-card::before, .panel-card::before, .glass-card::before, .event-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 35%);
    }

    .hero-icon, .panel-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.9);
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
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }

    .primary-btn, .secondary-btn, .action-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        font-size: 0.8rem; padding: 0.55rem 1rem;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        color: var(--text-primary); background: rgba(255,255,255,0.95);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }
    .action-btn.register {
        background: linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%);
        color: white; border: none;
    }
    .action-btn.register:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(122,42,42,0.2); }
    .action-btn.cancel {
        background: rgba(254,242,242,0.9); color: #b91c1c; border: 1px solid #fecaca;
    }
    .action-btn.cancel:hover { background: rgba(254,226,226,0.95); }
    .action-btn.details {
        background: rgba(254,249,231,0.9); color: var(--maroon-700); border: 1px solid rgba(212,175,55,0.3);
    }
    .action-btn.details:hover { background: rgba(254,243,199,0.95); }
    .action-btn:disabled {
        opacity: 0.6; cursor: not-allowed; transform: none !important;
    }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.95); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .select-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    /* Event Card Specific Styles */
    .event-image {
        position: relative; height: 10rem; overflow: hidden;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
    }
    .event-image img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.3s ease;
    }
    .event-card:hover .event-image img { transform: scale(1.03); }
    .event-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(44,36,32,0.7) 0%, transparent 60%);
    }
    .event-content {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 0.75rem 1rem; color: white;
    }
    .event-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.15rem 0.5rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 700; text-transform: capitalize;
    }
    .event-badge.type { background: rgba(212,175,55,0.9); color: var(--maroon-900); }
    .event-badge.required { background: rgba(220,38,38,0.9); color: white; }
    .event-badge.status { background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(4px); }
    .event-badge.college {
        position: absolute; top: 0.5rem; right: 0.5rem;
        background: rgba(16,185,129,0.9); color: white;
        padding: 0.2rem 0.6rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600; backdrop-filter: blur(4px);
    }
    .event-badge.date {
        position: absolute; top: 0.5rem; left: 0.5rem;
        background: rgba(212,175,55,0.95); color: var(--maroon-900);
        padding: 0.2rem 0.6rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 700; backdrop-filter: blur(4px);
    }

    .event-detail {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.75rem; color: var(--text-secondary);
    }
    .event-detail i { color: var(--maroon-700); width: 1rem; text-align: center; }

    .college-chip {
        display: inline-flex; align-items: center;
        padding: 0.15rem 0.5rem; border-radius: 999px;
        background: rgba(236,253,245,0.8); color: #065f46;
        font-size: 0.65rem; font-weight: 600; border: 1px solid rgba(16,185,129,0.2);
    }

    .empty-state, .profile-incomplete {
        text-align: center; padding: 2.5rem 1.5rem;
        background: rgba(255,255,255,0.95); border: 1px solid var(--border-soft);
        border-radius: 0.75rem;
    }
    .empty-state-icon {
        width: 4rem; height: 4rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 1.5rem;
        background: rgba(254,249,231,0.8); color: var(--maroon-700);
        border: 2px dashed var(--gold-400);
    }
    .profile-incomplete {
        background: rgba(254,243,199,0.9); border-color: rgba(245,158,11,0.3);
    }
    .profile-incomplete .empty-state-icon {
        background: rgba(251,191,36,0.15); color: #b45309;
        border-color: #f59e0b;
    }

    .expand-details {
        border-top: 1px dashed var(--border-soft);
        padding-top: 1rem; margin-top: 1rem;
    }
    .expand-details .info-box {
        background: rgba(254,242,242,0.8); border: 1px solid rgba(239,68,68,0.2);
        border-radius: 0.6rem; padding: 0.75rem;
        font-size: 0.75rem; color: #991b1b;
    }
    .expand-details .info-box i { color: #ef4444; margin-right: 0.3rem; }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--maroon-700); font-size: 0.75rem; font-weight: 600;
        transition: all 0.18s ease;
    }
    .back-link:hover { color: var(--maroon-900); transform: translateX(-2px); }

    /* Responsive Utilities */
    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn, .action-btn { width: 100%; justify-content: center; padding: 0.7rem; font-size: 0.75rem; }
        .btn-row-mobile { flex-direction: column; gap: 0.5rem !important; }
        .hero-card, .summary-card { padding: 1rem !important; }
        .hero-icon { width: 2.25rem; height: 2.25rem; }
        .event-image { height: 8rem; }
        .event-content { padding: 0.6rem 0.8rem; }
        .event-badge { font-size: 0.6rem; padding: 0.12rem 0.4rem; }
        .event-badge.college, .event-badge.date { font-size: 0.6rem; padding: 0.15rem 0.5rem; }
        .event-detail { font-size: 0.7rem; }
        .college-chip { font-size: 0.6rem; padding: 0.12rem 0.4rem; }
        .filters-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; padding-bottom: 0.5rem; }
        .filters-scroll > div { display: flex; gap: 0.5rem; min-width: max-content; }
    }
</style>

<div class="min-h-screen events-shell">
    <div class="events-glow one"></div>
    <div class="events-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Page Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-calendar-alt text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('mhc') }}" class="back-link mb-2">
                                <i class="fas fa-arrow-left text-[9px]"></i> Back to Mental Health Corner
                            </a>
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Student Events
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">All Events</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Browse upcoming mental health events and workshops designed for you.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0" style="width:2.5rem;height:2.5rem;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.15);color:#fef9e7">
                                <i class="fas fa-heart text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label" style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:0.2em;color:rgba(255,255,255,0.7)">Your Wellness</p>
                                <p class="summary-value" style="font-size:1.1rem;line-height:1.2;font-weight:700;margin-top:0.2rem">Take Care of You</p>
                                <p class="summary-subtext hidden sm:block" style="font-size:0.7rem;color:rgba(255,255,255,0.85);margin-top:0.15rem">Events to support your mental health journey.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $student = Auth::user()->student;

            if ($student) {
                $events = \App\Models\Event::with(['user', 'colleges', 'registrations' => function($query) use ($student) {
                        $query->where('student_id', $student->id);
                    }])
                    ->upcoming()
                    ->active()
                    ->forCollege($student->college_id)
                    ->orderBy('event_start_date')
                    ->orderBy('start_time')
                    ->get();

                // Get unique event types for filter
                $eventTypes = $events->pluck('type')->unique()->sort();
            } else {
                $events = collect();
                $eventTypes = collect();
            }
        @endphp

        <!-- Filters Section -->
        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-sliders-h text-[9px] sm:text-xs"></i></div>
                <div>
                    <h3 class="panel-title">Filter Events</h3>
                    <p class="panel-subtitle hidden sm:block">Find events by type or registration status.</p>
                </div>
            </div>

            <div class="p-4 sm:p-5">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="filters-scroll w-full">
                        <div class="flex flex-wrap gap-3">
                            <div class="min-w-[140px]">
                                <label class="field-label">Event Type</label>
                                <select id="typeFilter" class="select-field">
                                    <option value="">All Event Types</option>
                                    @foreach($eventTypes as $type)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="min-w-[160px]">
                                <label class="field-label">Status</label>
                                <select id="statusFilter" class="select-field">
                                    <option value="">All Events</option>
                                    <option value="required">Required Events</option>
                                    <option value="optional">Optional Events</option>
                                    <option value="registered">My Registrations</option>
                                    <option value="available">Available to Register</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="text-[0.75rem] text-[#6b5e57] flex items-center gap-1.5">
                        <i class="fas fa-search text-[#8b7e76]"></i>
                        <span><strong style="color:var(--text-primary)">{{ $events->count() }}</strong> events found</span>
                    </div>
                </div>
            </div>
        </div>

        @if(!$student)
            <!-- Student Profile Not Complete -->
            <div class="glass-card profile-incomplete mb-6">
                <div class="empty-state-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#92400e] mb-2">Complete Your Student Profile</h3>
                <p class="text-[#b45309] text-sm mb-4 max-w-md mx-auto">
                    Please complete your student profile to view and register for mental health events.
                </p>
                <a href="{{ route('student.profile') }}"
                   class="primary-btn px-5 py-2.5 text-xs sm:text-sm">
                    <i class="fas fa-user-edit mr-1.5 text-[9px] sm:text-xs"></i>
                    <span>Complete Profile</span>
                </a>
            </div>

        @elseif($events->isEmpty())
            <!-- No Events Available -->
            <div class="glass-card empty-state mb-6">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Events Right Now</h3>
                <p class="text-[#6b5e57] text-sm mb-4 max-w-md mx-auto">
                    There are currently no upcoming events available for your college. Check back soon!
                </p>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#fef3c7] border border-[#f59e0b]/30">
                    <i class="fas fa-info-circle text-[#b45309] text-[9px]"></i>
                    <span class="text-[#92400e] text-[0.75rem]">New events will appear here when scheduled by your counselors.</span>
                </div>
            </div>

        @else
            <!-- Events Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6 mb-8" id="eventsGrid">
                @foreach($events as $event)
                    @php
                        $isRequiredEvent = $event->is_required && $event->isRequiredForStudent($student);
                        $isRegistered = $event->isRegisteredByStudent($student);
                        $hasAvailableSlots = $event->hasAvailableSlots();
                        $isUpcoming = $event->is_upcoming;
                        $registration = $event->getStudentRegistration($student);
                        $status = $registration ? $registration->status : 'not_registered';
                        $registrationDate = $registration ? $registration->registered_at : null;
                        $isAutoRegistered = $isRequiredEvent && $isRegistered;
                        $canCancel = $isRegistered && $isUpcoming && !$isRequiredEvent;
                        $canRegister = !$isRegistered && $hasAvailableSlots && !$isRequiredEvent;
                        $isEventFull = !$hasAvailableSlots && !$isRegistered;
                        $isRequiredAutoRegister = $isRequiredEvent && !$isRegistered;
                    @endphp

                    <div class="event-card"
                         data-type="{{ $event->type }}"
                         data-required="{{ $isRequiredEvent ? 'true' : 'false' }}"
                         data-registered="{{ $isRegistered ? 'true' : 'false' }}"
                         data-available="{{ $canRegister ? 'true' : 'false' }}">

                        <!-- Event Image Header -->
                        <div class="event-image">
                            <img src="{{ $event->image_url }}"
                                 alt="{{ $event->title }}"
                                 onerror="this.parentElement.style.background='linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%)'">

                            <div class="event-overlay"></div>

                            <!-- Content Overlay -->
                            <div class="event-content">
                                <div class="flex flex-wrap gap-1.5 mb-2">
                                    <span class="event-badge type">{{ $event->type }}</span>
                                    @if($isRequiredEvent)
                                        <span class="event-badge required">
                                            <i class="fas fa-exclamation-circle text-[8px]"></i> Required
                                        </span>
                                    @endif
                                    @if($isRegistered)
                                        <span class="event-badge status">✓ Registered</span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold text-white line-clamp-2">{{ $event->title }}</h3>
                            </div>

                            <!-- College Badge -->
                            <span class="event-badge college">
                                @if($event->for_all_colleges)
                                    <i class="fas fa-globe text-[8px]"></i> All Colleges
                                @else
                                    <i class="fas fa-university text-[8px]"></i> {{ $event->colleges->count() }} Colleges
                                @endif
                            </span>

                            <!-- Date Badge -->
                            <span class="event-badge date">
                                {{ \Carbon\Carbon::parse($event->event_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('M d') }}
                            </span>
                        </div>

                        <!-- Event Details -->
                        <div class="p-4">
                            <!-- Date and Time -->
                            <div class="space-y-1.5 mb-3">
                                <div class="event-detail">
                                    <i class="far fa-clock"></i>
                                    <span>{{ $event->time_range }}</span>
                                </div>
                                <div class="event-detail">
                                    <i class="far fa-map-marker-alt"></i>
                                    <span class="line-clamp-1">{{ $event->location }}</span>
                                </div>
                                @if($event->max_attendees)
                                    <div class="event-detail">
                                        <i class="far fa-users"></i>
                                        <span>{{ $event->registered_count }}/{{ $event->max_attendees }} registered</span>
                                    </div>
                                @endif
                                @if($registrationDate)
                                    <div class="event-detail">
                                        <i class="far fa-calendar-check" style="color:#c9a227"></i>
                                        <span>Registered: {{ $registrationDate->format('M j, Y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-[#6b5e57] text-[0.8rem] mb-3 line-clamp-2 leading-relaxed">
                                {{ Str::limit($event->description, 120) }}
                            </p>

                            <!-- Specific Colleges -->
                            @if(!$event->for_all_colleges && $event->colleges->isNotEmpty())
                                <div class="mb-3">
                                    <p class="text-[0.65rem] font-semibold text-[#6b5e57] mb-1.5">Available for:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($event->colleges->take(2) as $college)
                                            <span class="college-chip">{{ $college->name }}</span>
                                        @endforeach
                                        @if($event->colleges->count() > 2)
                                            <span class="college-chip">+{{ $event->colleges->count() - 2 }} more</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2 btn-row-mobile">
                                @if($isRegistered)
                                    @if($isRequiredEvent)
                                        <button class="action-btn" disabled title="Required events cannot be cancelled" style="background:rgba(209,213,219,0.8);color:#6b7280;border:1px solid #d1d5db">
                                            <i class="fas fa-lock text-[9px]"></i>
                                            <span class="hidden sm:inline">Auto Registered</span>
                                        </button>
                                    @else
                                        <form action="{{ route('student.events.cancel', $event) }}" method="POST" class="flex-1 min-w-[100px]">
                                            @csrf
                                            <button type="submit"
                                                    class="action-btn cancel w-full"
                                                    onclick="return confirm('Are you sure you want to cancel your registration for this event?')">
                                                <i class="fas fa-times-circle text-[9px]"></i>
                                                <span class="hidden sm:inline">Cancel</span>
                                            </button>
                                        </form>
                                    @endif
                                @elseif($isRequiredEvent)
                                    <span class="action-btn" style="background:linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%);color:white;border:none">
                                        <i class="fas fa-user-check text-[9px]"></i>
                                        <span class="hidden sm:inline">Required</span>
                                    </span>
                                @elseif($hasAvailableSlots)
                                    <form action="{{ route('student.events.register', $event) }}" method="POST" class="flex-1 min-w-[100px]">
                                        @csrf
                                        <button type="submit"
                                                class="action-btn register w-full">
                                            <i class="fas fa-calendar-plus text-[9px]"></i>
                                            <span class="hidden sm:inline">Register</span>
                                        </button>
                                    </form>
                                @else
                                    <button class="action-btn" disabled style="background:rgba(209,213,219,0.8);color:#6b7280;border:1px solid #d1d5db">
                                        <i class="fas fa-calendar-times text-[9px]"></i>
                                        <span class="hidden sm:inline">Full</span>
                                    </button>
                                @endif

                                <!-- View Details Button -->
                                <button onclick="toggleDetails('details-{{ $event->id }}')"
                                        class="action-btn details flex-1 min-w-[100px]">
                                    <i class="fas fa-info-circle text-[9px]"></i>
                                    <span class="hidden sm:inline">Details</span>
                                </button>
                            </div>

                            <!-- Event Status and Created Info -->
                            <div class="mt-3 pt-3 border-t border-[#e5e0db]/60">
                                <div class="flex justify-between items-center">
                                    <div class="text-[0.7rem] text-[#8b7e76]">
                                        <i class="far fa-user mr-1"></i>
                                        {{ $event->user->first_name }} {{ $event->user->last_name }}
                                    </div>
                                    <div class="text-[0.7rem]">
                                        @if($isUpcoming)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#fef3c7] text-[#92400e] border border-[#f59e0b]/30">
                                                <i class="fas fa-clock text-[8px]"></i> Upcoming
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-[#f3f4f6] text-[#6b7280] border border-[#d1d5db]">
                                                <i class="fas fa-history text-[8px]"></i> Past
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Expandable Details -->
                            <div id="details-{{ $event->id }}" class="hidden expand-details">
                                <div class="space-y-2.5">
                                    <!-- Full Description -->
                                    <div>
                                        <p class="text-[0.75rem] font-semibold text-[#6b5e57] mb-1">Description:</p>
                                        <p class="text-[#6b5e57] text-[0.8rem] leading-relaxed">{{ $event->description }}</p>
                                    </div>

                                    <!-- Capacity Info -->
                                    <div class="flex items-center justify-between text-[0.75rem]">
                                        <span class="text-[#6b5e57]">Capacity:</span>
                                        <span class="text-[#6b5e57]">
                                            @if($event->max_attendees)
                                                {{ $event->registered_count }}/{{ $event->max_attendees }} registered
                                                ({{ $event->available_slots }} available)
                                            @else
                                                Unlimited capacity
                                            @endif
                                        </span>
                                    </div>

                                    <!-- Event Requirements Information -->
                                    @if($isRequiredEvent)
                                        <div class="info-box">
                                            <div class="flex items-center mb-1">
                                                <i class="fas fa-info-circle"></i>
                                                <span class="font-semibold">Required Event</span>
                                            </div>
                                            <p class="text-[0.7rem]">
                                                This event is required for your college. Attendance is mandatory.
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    function toggleDetails(id) {
        const element = document.getElementById(id);
        element.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const typeFilter = document.getElementById('typeFilter');
        const statusFilter = document.getElementById('statusFilter');
        const eventsGrid = document.getElementById('eventsGrid');
        const eventCards = eventsGrid ? Array.from(eventsGrid.getElementsByClassName('event-card')) : [];

        function filterEvents() {
            const selectedType = typeFilter.value;
            const selectedStatus = statusFilter.value;

            eventCards.forEach(card => {
                let show = true;

                // Filter by type
                if (selectedType && card.dataset.type !== selectedType) {
                    show = false;
                }

                // Filter by status
                if (selectedStatus) {
                    switch (selectedStatus) {
                        case 'required':
                            if (card.dataset.required !== 'true') show = false;
                            break;
                        case 'optional':
                            if (card.dataset.required === 'true') show = false;
                            break;
                        case 'registered':
                            if (card.dataset.registered !== 'true') show = false;
                            break;
                        case 'available':
                            if (card.dataset.available !== 'true') show = false;
                            break;
                    }
                }

                card.style.display = show ? 'block' : 'none';
            });

            // Update event count
            const visibleCount = eventCards.filter(card => card.style.display !== 'none').length;
            const countElement = document.querySelector('.text-sm.text-gray-600 span.font-semibold');
            if (countElement) {
                countElement.textContent = visibleCount;
            }
        }

        if (typeFilter) typeFilter.addEventListener('change', filterEvents);
        if (statusFilter) statusFilter.addEventListener('change', filterEvents);
    });
</script>
@endsection