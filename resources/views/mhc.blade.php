<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health Corner - Office of Guidance and Counseling</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
    :root {
        --primary-red: #9f1f24;
        --primary-red-dark: #7f171b;
        --primary-red-deep: #651114;
        --primary-red-rich: #b32028;

        --accent-gold: #d4af37;
        --accent-gold-soft: #e7c766;

        --bg-light: #f6efe8;
        --bg-soft: #fbf6f1;
        --bg-white: #fffdfa;

        --text-dark: #2f2522;
        --text-secondary: #766864;
        --text-muted: #a09490;

        --border-soft: #eadfd4;
        --danger-red: #dc3545;

        --shadow-soft: 0 12px 32px rgba(101, 17, 20, 0.08);
        --shadow-medium: 0 18px 42px rgba(101, 17, 20, 0.13);
        --shadow-strong: 0 24px 56px rgba(101, 17, 20, 0.18);

        --radius-lg: 28px;
        --radius-md: 20px;
        --radius-sm: 16px;
    }

    * {
        box-sizing: border-box;
    }

    body.mhc-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background:
            radial-gradient(circle at top left, rgba(159, 31, 36, 0.08), transparent 22%),
            radial-gradient(circle at top right, rgba(212, 175, 55, 0.08), transparent 18%),
            linear-gradient(180deg, #fbf6f1 0%, #f6efe8 100%);
        color: var(--text-dark);
    }

    .gold-text {
        color: #f0cd63;
    }

    .mhc-navbar {
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: linear-gradient(90deg, #5f0f12 0%, #8f1d1d 45%, #b32028 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 10px 28px rgba(91, 15, 15, 0.22);
        backdrop-filter: blur(12px);
        transition: box-shadow 0.25s ease, background 0.25s ease;
    }

    .mhc-navbar.scrolled {
        box-shadow: 0 16px 34px rgba(91, 15, 15, 0.28);
    }

    .nav-link {
        color: white;
        font-weight: 600;
        transition: all 0.25s ease;
        position: relative;
    }

    .nav-link:hover {
        color: rgba(255, 245, 235, 0.92);
    }

    .nav-link::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -0.35rem;
        width: 0;
        height: 2px;
        border-radius: 999px;
        background: linear-gradient(90deg, #f0cd63, #fff2c6);
        transition: width 0.25s ease;
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .dropdown-panel {
        background: rgba(255, 253, 250, 0.98);
        border: 1px solid var(--border-soft);
        border-radius: 18px;
        box-shadow: var(--shadow-medium);
        overflow: hidden;
    }

    .mhc-page-header {
        position: relative;
        overflow: hidden;
        padding: 6.25rem 0 7.5rem;
        background:
            linear-gradient(135deg, rgba(95, 15, 18, 0.84), rgba(143, 29, 29, 0.74), rgba(179, 32, 40, 0.65)),
            url('https://images.unsplash.com/photo-1499209974431-2761385a0a28?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        box-shadow: inset 0 -60px 120px rgba(0, 0, 0, 0.14);
    }

    .mhc-page-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 18% 20%, rgba(231, 199, 102, 0.16), transparent 18%),
            radial-gradient(circle at 82% 12%, rgba(255,255,255,0.08), transparent 18%),
            linear-gradient(180deg, rgba(255,255,255,0.03) 0%, rgba(0,0,0,0.08) 100%);
        pointer-events: none;
    }

    .mhc-page-header::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: -1px;
        height: 130px;
        background: linear-gradient(180deg, rgba(246,239,232,0) 0%, rgba(246,239,232,1) 88%);
        pointer-events: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        padding: 0.72rem 1.1rem;
        border-radius: 999px;
        background: rgba(255, 248, 240, 0.14);
        border: 1px solid rgba(255, 240, 220, 0.22);
        color: white;
        backdrop-filter: blur(10px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.14);
        font-size: 0.88rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .hero-title {
        color: white;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
        text-shadow: 0 10px 28px rgba(0,0,0,0.2);
    }

    .hero-title-accent {
        color: #f3d991;
        font-family: Georgia, 'Times New Roman', serif;
        font-style: italic;
        font-weight: 700;
    }

    .hero-description {
        color: rgba(255,255,255,0.93);
        line-height: 1.85;
        max-width: 46rem;
        margin-left: auto;
        margin-right: auto;
        text-shadow: 0 4px 16px rgba(0,0,0,0.14);
    }

    .section-shell {
        position: relative;
    }

    .section-head {
        display: flex;
        align-items: center;
        gap: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .section-bar {
        width: 6px;
        height: 36px;
        border-radius: 999px;
        background: linear-gradient(to bottom, var(--accent-gold), var(--primary-red));
        flex-shrink: 0;
        box-shadow: 0 6px 14px rgba(212, 175, 55, 0.18);
    }

    .section-title {
        color: var(--text-dark);
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .section-subtitle {
        color: var(--text-secondary);
    }

    .section-link {
        color: var(--primary-red);
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .section-link:hover {
        color: var(--primary-red-deep);
    }

    .mhc-card {
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-soft);
        transition: all 0.3s ease;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .mhc-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.22), transparent 20%);
        pointer-events: none;
    }

    .mhc-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
        border-color: rgba(159, 31, 36, 0.18);
    }

    .soft-panel {
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-soft);
    }

    .category-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0.08), rgba(47, 37, 34, 0.62));
    }

    .badge-soft {
        padding: 0.4rem 0.9rem;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        backdrop-filter: blur(6px);
        box-shadow: 0 8px 18px rgba(0,0,0,0.12);
    }

    .badge-maroon {
        background: linear-gradient(135deg, var(--primary-red), var(--primary-red-dark));
        color: white;
    }

    .badge-gold {
        background: #fbf4ea;
        color: var(--primary-red);
        border: 1px solid #ead8bf;
    }

    .badge-green {
        background: #eefaf2;
        color: #166534;
        border: 1px solid #bfe5c8;
    }

    .badge-red {
        background: linear-gradient(135deg, #dc3545 0%, #b91c1c 100%);
        color: white;
    }

    .badge-gray {
        background: rgba(255, 253, 250, 0.95);
        color: var(--text-secondary);
        border: 1px solid var(--border-soft);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-red), var(--primary-red-dark));
        color: white;
        border-radius: 16px;
        padding: 0.9rem 1.35rem;
        font-weight: 700;
        box-shadow: 0 10px 22px rgba(159, 31, 36, 0.2);
        transition: all 0.25s ease;
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-red-rich), var(--primary-red-deep));
        transform: translateY(-1px);
        box-shadow: 0 14px 28px rgba(159, 31, 36, 0.25);
    }

    .btn-secondary {
        background: #fffdfa;
        color: var(--text-dark);
        border: 1px solid #d9cec3;
        border-radius: 16px;
        font-weight: 700;
        transition: all 0.25s ease;
    }

    .btn-secondary:hover {
        background: #fbf4ea;
        color: var(--primary-red);
        border-color: #ead8bf;
        transform: translateY(-1px);
    }

    .faq-item {
        border-radius: var(--radius-sm);
        overflow: hidden;
        margin-bottom: 1rem;
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        border: 1px solid var(--border-soft);
        box-shadow: var(--shadow-soft);
        transition: all 0.25s ease;
    }

    .faq-item:hover {
        border-color: rgba(159, 31, 36, 0.15);
        box-shadow: var(--shadow-medium);
    }

    .faq-question {
        padding: 1.25rem 1.4rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 700;
        color: var(--text-dark);
        background: rgba(255, 253, 251, 0.78);
        transition: background 0.2s ease;
    }

    .faq-question:hover {
        background: rgba(159, 31, 36, 0.04);
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.4s ease;
        background: transparent;
        color: var(--text-secondary);
        line-height: 1.8;
        font-size: 0.96rem;
    }

    .faq-active .faq-answer {
        max-height: 500px;
        padding: 1.35rem 1.4rem 1.45rem;
        border-top: 1px solid var(--border-soft);
    }

    .profile-dropdown-content {
        position: absolute;
        right: 0;
        top: 100%;
        background: linear-gradient(180deg, #fffdfa, #faf4ed);
        box-shadow: var(--shadow-medium);
        border-radius: 18px;
        padding: 1rem;
        min-width: 240px;
        z-index: 1000;
        margin-top: 0.7rem;
        border: 1px solid var(--border-soft);
    }

    .footer-shell {
        background: linear-gradient(180deg, #4d1212 0%, #3f0e0e 100%);
        color: white;
        border-top: 1px solid rgba(255,255,255,0.06);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f3eee8;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-red);
        border-radius: 999px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary-red-dark);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fadeIn 0.45s ease-out forwards;
    }

    @media (max-width: 768px) {
        .mhc-page-header {
            padding: 5rem 0 6rem;
        }

        .hero-title {
            font-size: 2.6rem;
            line-height: 1.05;
        }

        .section-head {
            align-items: flex-start;
        }
    }
</style>
</head>
<body class="mhc-container min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="mhc-navbar py-4" id="mainNavbar">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center">
                <div class="text-white font-bold text-2xl mr-10 tracking-wide">
                    <span class="gold-text">my.OGC</span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('dashboard') }}" class="nav-link">Home</a>

                    <div class="relative group">
                        <button class="nav-link flex items-center">
                            Services <i class="fas fa-chevron-down ml-1 text-sm"></i>
                        </button>

                        <div class="absolute hidden group-hover:block rounded-2xl shadow-lg py-2 mt-3 w-52 z-10 dropdown-panel">
                            <a href="{{ route('bap') }}" class="block px-4 py-2.5 text-[var(--text-dark)] hover:text-[var(--primary-red)] hover:bg-[rgba(143,29,29,0.04)]">
                                Book an Appointment
                            </a>
                            <a href="{{ route('mhc') }}" class="block px-4 py-2.5 text-[var(--primary-red)] bg-[rgba(143,29,29,0.05)] font-semibold">
                                Mental Health Corner
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <button class="text-white p-2 rounded-full hover:bg-white/10 transition">
                    <i class="fas fa-bell"></i>
                </button>

                <div class="relative">
                    <button class="text-white p-2 rounded-full hover:bg-white/10 transition focus:outline-none" id="profileBtn">
                        <i class="fas fa-user"></i>
                    </button>

                    <div class="profile-dropdown-content hidden" id="profileMenu">
                        <div class="mb-3 border-b pb-2 border-[var(--border-soft)]">
                            <div class="font-semibold text-[var(--text-dark)]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                            <div class="text-sm text-[var(--text-secondary)]">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-[var(--primary-red)] capitalize font-semibold mt-1">Role: {{ Auth::user()->role }}</div>
                        </div>

                        <a href="" class="block py-2 text-[var(--text-dark)] hover:text-[var(--primary-red)] transition">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="border-t pt-2 mt-2 border-[var(--border-soft)]">
                            @csrf
                            <button type="submit" class="w-full text-left block py-2 text-[var(--text-dark)] hover:text-[var(--primary-red)] transition">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Page Header -->
        <div class="mhc-page-header">
    <div class="container mx-auto px-6 text-center relative z-10">
        <div class="hero-badge mb-6">
            <i class="fas fa-heart text-[var(--accent-gold)]"></i>
            <span>You're not alone — we're here with you</span>
        </div>

        <h1 class="hero-title text-4xl md:text-7xl mb-6">
            Mental Health <span class="hero-title-accent">Corner</span>
        </h1>

        <p class="hero-description text-lg md:text-xl">
            Some days are harder than others, and that's okay. This is a quiet corner where you can find resources, join events, and know that the guidance office is always in your corner.
        </p>
    </div>
</div>

        <div class="container mx-auto px-6 py-10 -mt-14 relative z-20">

            <!-- Events Section -->
            <section class="mb-16 section-shell">
                <div class="flex flex-col sm:flex-row justify-between items-end mb-10 gap-4">
                    <div>
                        <div class="section-head">
                            <span class="section-bar"></span>
                            <h2 class="section-title text-3xl">Upcoming Events</h2>
                        </div>
                        <p class="section-subtitle mt-2 ml-4 sm:ml-[1.55rem] text-sm">Come as you are. Everyone's welcome.</p>
                    </div>

                    <a href="{{ route('student.events.available') }}" class="section-link flex items-center transition group text-sm uppercase tracking-wide">
                        View All Events
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

                @php
                    $student = Auth::user()->student;
                    if ($student) {
                        $events = \App\Models\Event::with(['user', 'colleges'])
                            ->upcoming()
                            ->active()
                            ->forCollege($student->college_id)
                            ->limit(3)
                            ->get();
                    } else {
                        $events = collect();
                    }
                @endphp

                @if(!$student)
                    <div class="soft-panel p-10 text-center justify-center items-center">
                        <div class="bg-[#fff8ef] border border-[#ead8bf] rounded-[22px] p-8 max-w-lg mx-auto shadow-sm">
                            <i class="fas fa-exclamation-triangle text-[var(--accent-gold)] text-4xl mb-4"></i>
                            <h3 class="text-xl font-bold text-[var(--text-dark)] mb-2">Finish setting up your profile</h3>
                            <p class="text-[var(--text-secondary)] text-sm leading-relaxed">
                                Once your student profile is complete, we can show you events that are relevant to your college. It only takes a minute!
                            </p>
                        </div>
                    </div>
                @elseif($events->isEmpty())
                    <div class="mhc-card p-16 text-center justify-center items-center">
                        <div class="w-24 h-24 bg-[var(--bg-soft)] rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="far fa-calendar-alt text-4xl text-[var(--text-muted)]"></i>
                        </div>
                        <p class="text-[var(--text-secondary)] text-lg font-medium">Nothing scheduled just yet for your college.</p>
                        <p class="text-[var(--text-muted)] text-sm mt-3">We'll post new events soon — check back in a bit.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        @foreach($events as $event)
                            @php
                                $isRequiredEvent = $event->is_required && $event->isRequiredForStudent($student);
                                $isRegistered = $event->isRegisteredByStudent($student);
                                $hasAvailableSlots = $event->hasAvailableSlots();
                                $isUpcoming = $event->is_upcoming;
                                $registration = $event->getStudentRegistration($student);
                                $status = $registration ? $registration->status : 'not_registered';
                                $registrationDate = $registration ? $registration->registered_at : null;
                                $canCancel = $isRegistered && $isUpcoming && !$isRequiredEvent;
                                $canRegister = !$isRegistered && $hasAvailableSlots && !$isRequiredEvent;
                                $isEventFull = !$hasAvailableSlots && !$isRegistered;
                            @endphp

                            <div class="mhc-card h-full group">
                                <div class="relative h-72 bg-gray-200 overflow-hidden">
                                    <img src="{{ $event->image_url }}"
                                         alt="{{ $event->title }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                    <div class="absolute top-5 left-5 right-5 flex justify-between items-start">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="badge-soft badge-maroon capitalize">
                                                {{ $event->type }}
                                            </span>

                                            @if($isRequiredEvent)
                                                <span class="badge-soft badge-red animate-pulse">
                                                    <i class="fas fa-star mr-1"></i> Required
                                                </span>
                                            @endif
                                        </div>

                                        @if($isRegistered)
                                            <span class="badge-soft badge-green">
                                                <i class="fas fa-check mr-1"></i> Joined
                                            </span>
                                        @elseif($isRequiredEvent)
                                            <span class="badge-soft badge-maroon">
                                                Required
                                            </span>
                                        @else
                                            <span class="badge-soft badge-gray">
                                                Open
                                            </span>
                                        @endif
                                    </div>

                                    <div class="absolute bottom-5 left-5 text-white">
                                        <div class="text-xs font-medium opacity-90 uppercase tracking-widest mb-1 text-[#f3d991]">When</div>
                                        <div class="font-bold text-xl drop-shadow-md">
                                            {{ \Carbon\Carbon::parse($event->event_start_date)->format('M d') }}
                                            @if(\Carbon\Carbon::parse($event->event_start_date)->format('M d') !== \Carbon\Carbon::parse($event->event_end_date)->format('M d'))
                                                <span class="text-base font-normal opacity-80">-</span> {{ \Carbon\Carbon::parse($event->event_end_date)->format('M d') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 flex-grow flex flex-col">
                                    <h3 class="text-xl font-bold text-[var(--text-dark)] mb-3 line-clamp-2 leading-tight">
                                        {{ $event->title }}
                                    </h3>

                                    <div class="space-y-3 mb-5 text-sm text-[var(--text-secondary)]">
                                        <div class="flex items-center">
                                            <i class="far fa-clock mr-3 text-[var(--accent-gold)] w-5 text-center"></i>
                                            <span>{{ $event->time_range }}</span>
                                        </div>

                                        <div class="flex items-center">
                                            <i class="far fa-map-marker-alt mr-3 text-[var(--primary-red)] w-5 text-center"></i>
                                            <span class="truncate">{{ $event->location }}</span>
                                        </div>

                                        @if($event->max_attendees)
                                            <div class="flex items-center">
                                                <i class="far fa-users mr-3 text-[var(--text-muted)] w-5 text-center"></i>
                                                <span>{{ $event->registered_count }}/{{ $event->max_attendees }} spots filled</span>
                                            </div>
                                        @endif
                                    </div>

                                    <p class="text-[var(--text-secondary)] text-sm mb-6 line-clamp-3 leading-relaxed flex-grow">
                                        {{ Str::limit($event->description, 100) }}
                                    </p>

                                    <div class="grid grid-cols-2 gap-3 mt-auto">
                                        @if($isRegistered)
                                            @if($isRequiredEvent)
                                                <button class="col-span-2 bg-[#f3eee8] text-[#8a7d77] text-sm px-3 py-3 rounded-[14px] cursor-not-allowed flex items-center justify-center font-medium" disabled>
                                                    <i class="fas fa-lock mr-2"></i> Auto Registered
                                                </button>
                                            @else
                                                <form action="{{ route('student.events.cancel', $event) }}" method="POST" class="col-span-2">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full bg-red-50 text-red-700 border border-red-100 text-sm px-3 py-3 rounded-[14px] hover:bg-red-100 transition flex items-center justify-center font-medium"
                                                            onclick="return confirm('Cancel your registration?')">
                                                        <i class="fas fa-times-circle mr-2"></i> Cancel Registration
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif($isRequiredEvent)
                                            <button class="col-span-2 btn-primary flex items-center justify-center font-medium rounded-[14px] py-3">
                                                <i class="fas fa-user-check mr-2"></i> Required Attendance
                                            </button>
                                        @elseif($hasAvailableSlots)
                                            <form action="{{ route('student.events.register', $event) }}" method="POST" class="col-span-2">
                                                @csrf
                                                <button type="submit" class="w-full btn-primary flex items-center justify-center font-medium rounded-[14px] py-3">
                                                    <i class="fas fa-calendar-plus mr-2"></i> Register Now
                                                </button>
                                            </form>
                                        @else
                                            <button class="col-span-2 bg-[#f3eee8] text-[#8a7d77] text-sm px-3 py-3 rounded-[14px] cursor-not-allowed flex items-center justify-center font-medium" disabled>
                                                <i class="fas fa-calendar-times mr-2"></i> Event Full
                                            </button>
                                        @endif
                                    </div>

                                    <button
                                        onclick="openEventModal({
                                            title: {{ json_encode($event->title) }},
                                            type: {{ json_encode(ucfirst($event->type)) }},
                                            description: {{ json_encode($event->description) }},
                                            location: {{ json_encode($event->location) }},
                                            timeRange: {{ json_encode($event->time_range) }},
                                            dateRange: {{ json_encode($event->date_range) }},
                                            maxAttendees: {{ json_encode($event->max_attendees) }},
                                            registeredCount: {{ $event->registered_count }},
                                            imageUrl: {{ json_encode($event->image_url) }},
                                            isRequired: {{ json_encode($isRequiredEvent) }},
                                            isRegistered: {{ json_encode($isRegistered) }}
                                        })"
                                        class="mt-5 text-xs font-bold text-[var(--accent-gold)] hover:text-[var(--primary-red)] transition flex items-center justify-center w-full uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-2"></i> View Full Details
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Resources Section -->
            <section class="mb-16 section-shell">
                <div class="mb-10 text-center md:text-left">
                    <div class="section-head justify-center md:justify-start">
                        <span class="section-bar"></span>
                        <h2 class="section-title text-3xl">Resources for You</h2>
                    </div>
                    <p class="section-subtitle mt-3 text-sm ml-1">Handpicked reads, videos, and tools — whenever you need them.</p>
                </div>

                @php
                    $categories = \App\Models\Resource::getCategories();
                    $categoryResources = [];
                    foreach ($categories as $key => $name) {
                        $categoryResources[$key] = \App\Models\Resource::byCategory($key)->active()->ordered()->get();
                    }
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($categories as $key => $name)
                        @php
                            $resources = $categoryResources[$key];
                            $firstResource = $resources->first();
                        @endphp

                        <div class="mhc-card group h-full">
                            <div class="relative h-52 overflow-hidden">
                                @if($firstResource && $firstResource->image_url)
                                    <img src="{{ $firstResource->image_url }}"
                                         alt="{{ $name }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[var(--primary-red)] to-[var(--primary-red-dark)]"></div>
                                @endif

                                <div class="absolute inset-0 category-overlay flex items-center justify-center">
                                    <i class="{{ $firstResource->icon ?? 'fas fa-folder' }} text-white text-4xl drop-shadow-lg"></i>
                                </div>
                            </div>

                            <div class="p-6 text-center flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--text-dark)] mb-2">{{ $name }}</h3>
                                    <p class="text-[var(--text-muted)] mb-6 text-sm">
                                        {{ $resources->count() }} resource{{ $resources->count() !== 1 ? 's' : '' }} available
                                    </p>
                                </div>

                                <a href="{{ route('student.resources.category', $key) }}"
                                   class="btn-secondary inline-block px-6 py-3 rounded-[14px] font-semibold text-sm w-full">
                                    @if($key === 'youtube') Watch Videos
                                    @elseif($key === 'ebooks') Read eBooks
                                    @elseif($key === 'private') Watch Curated Videos
                                    @else Browse Resources
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- FAQs Section -->
            <section class="mb-12 max-w-4xl mx-auto section-shell">
                <div class="text-center mb-12">
                    <div class="section-head justify-center">
                        <span class="section-bar"></span>
                        <h2 class="section-title text-3xl">Got Questions?</h2>
                    </div>
                    <p class="section-subtitle mt-4">Here are some things students often ask us.</p>
                </div>

                @php
                    $faqs = \App\Models\FAQ::active()->ordered()->get();
                @endphp

                @if($faqs->isEmpty())
                    <div class="mhc-card p-10 text-center">
                        <p class="text-[var(--text-secondary)]">We're still putting together our FAQ list. In the meantime, feel free to reach out to the guidance office directly.</p>
                    </div>
                @else
                    <div>
                        @foreach($faqs as $index => $faq)
                            <div class="faq-item" id="faq-{{ $faq->id }}">
                                <div class="faq-question" onclick="toggleFaq({{ $faq->id }})">
                                    <span class="text-base">{{ $faq->question }}</span>
                                    <i class="fas fa-chevron-down text-[var(--accent-gold)] transition-transform duration-300"></i>
                                </div>
                                <div class="faq-answer">
                                    <p>{{ $faq->answer }}</p>
                                    @if($faq->category)
                                        <p class="text-xs text-[var(--text-muted)] mt-4 font-bold uppercase tracking-wider">
                                            Category: <span class="capitalize normal-case font-normal">{{ $faq->category }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </main>

    <!-- Event Details Modal -->
    <div id="eventModal"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4"
         style="background:rgba(47,37,34,0.55);backdrop-filter:blur(4px);">
        <div class="relative w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-[24px] shadow-2xl"
             style="background:linear-gradient(180deg,#fffdfa,#faf4ed);border:1px solid var(--border-soft);">

            <!-- Modal image header -->
            <div class="relative h-52 overflow-hidden rounded-t-[24px]">
                <img id="modalImage" src="" alt="" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                <!-- Close button -->
                <button onclick="closeEventModal()"
                        class="absolute top-4 right-4 w-9 h-9 rounded-full flex items-center justify-center text-white transition"
                        style="background:rgba(0,0,0,0.35);backdrop-filter:blur(6px);">
                    <i class="fas fa-times text-sm"></i>
                </button>

                <!-- Type badge -->
                <div class="absolute top-4 left-4">
                    <span id="modalType" class="badge-soft badge-maroon capitalize text-xs"></span>
                </div>

                <!-- Required badge -->
                <div id="modalRequiredBadge" class="absolute top-4 left-4 mt-8 hidden">
                    <span class="badge-soft badge-red text-xs"><i class="fas fa-star mr-1"></i> Required</span>
                </div>
            </div>

            <!-- Modal body -->
            <div class="p-6 sm:p-8">
                <h2 id="modalTitle" class="text-2xl font-bold mb-1" style="color:var(--text-dark);letter-spacing:-0.02em;"></h2>

                <!-- Meta row -->
                <div class="flex flex-wrap gap-4 mt-4 mb-6 text-sm" style="color:var(--text-secondary);">
                    <div class="flex items-center gap-2">
                        <i class="far fa-calendar text-[var(--accent-gold)]"></i>
                        <span id="modalDate"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="far fa-clock text-[var(--accent-gold)]"></i>
                        <span id="modalTime"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-[var(--primary-red)]"></i>
                        <span id="modalLocation"></span>
                    </div>
                    <div id="modalSlotsWrap" class="flex items-center gap-2">
                        <i class="fas fa-users" style="color:var(--text-muted)"></i>
                        <span id="modalSlots"></span>
                    </div>
                </div>

                <!-- Divider -->
                <hr style="border-color:var(--border-soft);margin-bottom:1.25rem;">

                <!-- Description -->
                <p id="modalDescription" class="leading-relaxed text-sm" style="color:var(--text-secondary);"></p>

                <!-- Required note -->
                <div id="modalRequiredNote" class="hidden mt-5 p-4 rounded-[14px] text-xs border border-red-100 bg-red-50 text-red-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Heads up:</strong> This event is mandatory for your college.
                </div>

                <!-- Registered note -->
                <div id="modalRegisteredNote" class="hidden mt-5 p-4 rounded-[14px] text-xs border flex items-center gap-2"
                     style="background:#eefaf2;border-color:#bfe5c8;color:#166534;">
                    <i class="fas fa-check-circle"></i>
                    <span>You're already registered for this event.</span>
                </div>

                <!-- Close action -->
                <div class="mt-7 flex justify-end">
                    <button onclick="closeEventModal()"
                            class="btn-secondary px-6 py-2.5 rounded-[14px] text-sm font-semibold">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-shell py-12 mt-auto">
        <div class="container mx-auto px-6 text-center">
            <div class="mb-6">
                <span class="text-3xl font-bold tracking-wide"><span class="text-[var(--accent-gold)]">OGC</span></span>
            </div>
            <p class="text-gray-300 text-sm">&copy; {{ date('Y') }} Office of Guidance and Counseling — MSU-IIT</p>
            <p class="text-xs text-gray-400 mt-3 italic font-light">We're here whenever you need us.</p>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });

        // Profile dropdown
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');
        if(profileBtn) {
            profileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', () => profileMenu.classList.add('hidden'));
            profileMenu.addEventListener('click', (e) => e.stopPropagation());
        }

        // FAQ Toggle
        function toggleFaq(id) {
            const faqItem = document.getElementById('faq-' + id);
            const answer = faqItem.querySelector('.faq-answer');
            const icon = faqItem.querySelector('.faq-question i');

            // Close others (optional, remove if you want multiple open)
            document.querySelectorAll('.faq-item').forEach(item => {
                if(item.id !== 'faq-' + id) {
                    item.classList.remove('faq-active');
                    item.querySelector('.faq-answer').style.maxHeight = null;
                    item.querySelector('i').style.transform = 'rotate(0deg)';
                }
            });

            faqItem.classList.toggle('faq-active');

            if (faqItem.classList.contains('faq-active')) {
                answer.style.maxHeight = answer.scrollHeight + "px";
                icon.style.transform = 'rotate(180deg)';
            } else {
                answer.style.maxHeight = null;
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Initialize first FAQ
        document.addEventListener('DOMContentLoaded', () => {
            const firstFaq = document.querySelector('.faq-item');
            if(firstFaq) toggleFaq(firstFaq.id.split('-')[1]);
        });

        // Event Details Modal
        function openEventModal(data) {
            document.getElementById('modalImage').src       = data.imageUrl || '';
            document.getElementById('modalImage').alt       = data.title;
            document.getElementById('modalTitle').textContent      = data.title;
            document.getElementById('modalType').textContent       = data.type;
            document.getElementById('modalDate').textContent       = data.dateRange;
            document.getElementById('modalTime').textContent       = data.timeRange;
            document.getElementById('modalLocation').textContent   = data.location;
            document.getElementById('modalDescription').textContent = data.description;

            // Slots
            const slotsWrap = document.getElementById('modalSlotsWrap');
            if (data.maxAttendees) {
                document.getElementById('modalSlots').textContent = data.registeredCount + '/' + data.maxAttendees + ' spots filled';
                slotsWrap.classList.remove('hidden');
            } else {
                slotsWrap.classList.add('hidden');
            }

            // Required badge & note
            const reqBadge = document.getElementById('modalRequiredBadge');
            const reqNote  = document.getElementById('modalRequiredNote');
            if (data.isRequired) {
                reqBadge.classList.remove('hidden');
                reqNote.classList.remove('hidden');
            } else {
                reqBadge.classList.add('hidden');
                reqNote.classList.add('hidden');
            }

            // Registered note
            const regNote = document.getElementById('modalRegisteredNote');
            data.isRegistered ? regNote.classList.remove('hidden') : regNote.classList.add('hidden');

            // Show modal
            const modal = document.getElementById('eventModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeEventModal() {
            const modal = document.getElementById('eventModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Close on backdrop click
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) closeEventModal();
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeEventModal();
        });
    </script>
</body>
</html>