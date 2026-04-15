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
            /* Softer Maroon Palette */
            --maroon-soft: #7a2a2a;      /* Primary - warm, readable */
            --maroon-medium: #5c1a1a;    /* Secondary - for depth */
            --maroon-dark: #3a0c0c;      /* Accent - sparing use */
            --gold-primary: #d4af37;
            --gold-secondary: #c9a227;
            --bg-warm: #faf8f5;
            --border-soft: #e5e0db;
            --text-primary: #2c2420;
            --text-secondary: #6b5e57;
            --text-muted: #8b7e76;
        }

        .mhc-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-warm);
            color: var(--text-primary);
        }

        /* Fixed/Sticky Navbar matching Dashboard */
        .mhc-navbar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(135deg, var(--maroon-soft) 0%, var(--maroon-medium) 100%);
            box-shadow: 0 4px 20px rgba(122, 42, 42, 0.25);
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            backdrop-filter: blur(8px);
        }

        .mhc-navbar.scrolled {
            box-shadow: 0 6px 24px rgba(122, 42, 42, 0.35);
        }

        .gold-text { color: var(--gold-primary); }

        /* Relaxed Page Header */
        .mhc-page-header {
            background: linear-gradient(135deg, rgba(122, 42, 42, 0.85) 0%, rgba(92, 26, 26, 0.9) 100%), 
                        url('https://images.unsplash.com/photo-1499209974431-2761385a0a28?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
            padding: 6rem 0 8rem 0; /* Extra bottom padding for overlap effect */
            color: white;
            position: relative;
            text-align: center;
        }
        
        /* Soft curve at bottom of header */
        .mhc-page-header::after {
            content: "";
            position: absolute;
            bottom: -2rem;
            left: 0;
            right: 0;
            height: 4rem;
            background: var(--bg-warm);
            border-radius: 50% 50% 0 0 / 100% 100% 0 0;
            transform: scaleX(1.5);
        }

        /* Card Styles - Soft & Floating */
        .mhc-card {
            background: rgba(255,255,255,0.98);
            border-radius: 1.5rem;
            box-shadow: 0 4px 20px rgba(44, 36, 32, 0.06);
            border: 1px solid var(--border-soft);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .mhc-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(122, 42, 42, 0.12);
            border-color: rgba(212, 175, 55, 0.4);
        }

        /* Category Card Specifics */
        .category-overlay {
            background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.6));
        }

        /* FAQ Styles - Accordion */
        .faq-item {
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 1rem;
            background: white;
            border: 1px solid var(--border-soft);
            transition: all 0.3s ease;
        }
        .faq-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-color: rgba(212, 175, 55, 0.3);
        }
        .faq-question {
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--text-primary);
            background: rgba(250,248,245,0.5);
            transition: background 0.2s;
        }
        .faq-question:hover {
            background: rgba(250,248,245,0.8);
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.4s ease;
            background: white;
            color: var(--text-secondary);
            line-height: 1.7;
            font-size: 0.95rem;
        }
        .faq-active .faq-answer {
            max-height: 500px;
            padding: 1.5rem;
            border-top: 1px solid var(--border-soft);
        }

        /* Profile Dropdown */
        .profile-dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            padding: 1rem;
            min-width: 220px;
            z-index: 1000;
            margin-top: 0.5rem;
            border: 1px solid var(--border-soft);
        }

        /* Badges - Softer & Modern */
        .badge-soft {
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            backdrop-filter: blur(4px);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .badge-maroon { background: rgba(122, 42, 42, 0.95); color: white; box-shadow: 0 2px 4px rgba(122,42,42,0.2); }
        .badge-gold { background: rgba(212, 175, 55, 0.95); color: var(--maroon-dark); box-shadow: 0 2px 4px rgba(212,175,55,0.2); }
        .badge-green { background: rgba(16, 185, 129, 0.95); color: white; box-shadow: 0 2px 4px rgba(16,185,129,0.2); }
        .badge-red { background: rgba(220, 38, 38, 0.95); color: white; box-shadow: 0 2px 4px rgba(220,38,38,0.2); }
        .badge-gray { background: rgba(240, 238, 235, 0.8); color: var(--text-secondary); border: 1px solid var(--border-soft); }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--maroon-soft) 0%, var(--maroon-medium) 100%);
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(122, 42, 42, 0.2);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(122, 42, 42, 0.3);
            filter: brightness(1.05);
        }
        
        .btn-secondary {
            background: white;
            color: var(--text-secondary);
            border: 1px solid var(--border-soft);
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: var(--bg-warm);
            color: var(--maroon-soft);
            border-color: var(--maroon-soft);
            transform: translateY(-1px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: var(--maroon-soft); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--maroon-medium); }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="bg-[var(--bg-warm)] mhc-container min-h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="mhc-navbar py-4" id="mainNavbar">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center">
                <div class="text-white font-bold text-2xl mr-10 tracking-wide">
                    <span class="gold-text">OGC</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('dashboard') }}" class="text-white font-semibold hover:text-[var(--gold-primary)] transition">Home</a>

                    <div class="relative group">
                        <button class="text-white font-semibold hover:text-[var(--gold-primary)] transition flex items-center">
                            Services <i class="fas fa-chevron-down ml-1 text-sm"></i>
                        </button>
                        <div class="absolute hidden group-hover:block bg-white rounded-md shadow-lg py-2 mt-1 w-48 z-10 border border-[var(--border-soft)]">
                            <a href="{{ route('bap') }}" class="block px-4 py-2 text-gray-800 hover:text-[var(--maroon-soft)] hover:bg-[rgba(212,175,55,0.1)]">Book an Appointment</a>
                            <a href="{{ route('mhc') }}" class="block px-4 py-2 text-[var(--maroon-soft)] bg-[rgba(212,175,55,0.1)] font-semibold">Mental Health Corner</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
                    <i class="fas fa-bell"></i>
                </button>

                <div class="relative">
                    <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition focus:outline-none" id="profileBtn">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="profile-dropdown-content hidden" id="profileMenu">
                        <div class="mb-3 border-b pb-2 border-[var(--border-soft)]">
                            <div class="font-semibold text-[var(--text-primary)]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                            <div class="text-sm text-[var(--text-secondary)]">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-[var(--maroon-soft)] capitalize font-semibold mt-1">Role: {{ Auth::user()->role }}</div>
                        </div>
                        <a href="" class="block py-2 text-[var(--text-primary)] hover:text-[var(--maroon-soft)] transition">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t pt-2 mt-2 border-[var(--border-soft)]">
                            @csrf
                            <button type="submit" class="w-full text-left block py-2 text-[var(--text-primary)] hover:text-[var(--maroon-soft)] transition">
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
                <div class="inline-block mb-6 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sm font-medium tracking-wide shadow-lg">
                    <i class="fas fa-heart mr-2 text-[var(--gold-primary)] animate-pulse"></i> A Space for the Mind
                </div>
                <h1 class="text-4xl md:text-6xl font-bold mb-6 drop-shadow-md font-light">
                    Mental Health <span class="font-serif italic text-[var(--gold-primary)]">Corner</span>
                </h1>
                <p class="text-lg md:text-xl max-w-2xl mx-auto text-white/90 leading-relaxed font-light drop-shadow-sm">
                    Explore resources to support your mental well-being, including upcoming events, educational materials, and self-help guides. Take a deep breath—you are in the right place.
                </p>
            </div>
        </div>

        <div class="container mx-auto px-6 py-8 -mt-10 relative z-20">
            
            <!-- Events Section -->
            <section class="mb-16">
                <div class="flex flex-col sm:flex-row justify-between items-end mb-10 gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-[var(--text-primary)] flex items-center font-serif">
                            <span class="w-1.5 h-10 bg-[var(--gold-primary)] rounded-full mr-4 shadow-sm"></span>
                            Upcoming Events
                        </h2>
                        <p class="text-[var(--text-secondary)] mt-2 text-sm ml-5.5">Join us in fostering a supportive community.</p>
                    </div>
                    <a href="{{ route('student.events.available') }}" class="text-[var(--maroon-soft)] hover:text-[var(--maroon-medium)] font-semibold flex items-center transition group text-sm uppercase tracking-wide">
                        View All Events <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
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
                    <div class="mhc-card p-10 text-center justify-center items-center bg-white/80 backdrop-blur-sm">
                        <div class="bg-[rgba(255,249,230,0.5)] border border-[rgba(212,175,55,0.3)] rounded-2xl p-8 max-w-lg mx-auto shadow-sm">
                            <i class="fas fa-exclamation-triangle text-[var(--gold-primary)] text-4xl mb-4"></i>
                            <h3 class="text-xl font-bold text-[var(--text-primary)] mb-2">Complete Your Profile</h3>
                            <p class="text-[var(--text-secondary)] text-sm leading-relaxed">Please complete your student profile to view and register for events tailored to your college.</p>
                        </div>
                    </div>
                @elseif($events->isEmpty())
                    <div class="mhc-card p-16 text-center justify-center items-center">
                        <div class="w-24 h-24 bg-[var(--bg-warm)] rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <i class="far fa-calendar-alt text-4xl text-[var(--text-muted)]"></i>
                        </div>
                        <p class="text-[var(--text-secondary)] text-lg font-medium">No upcoming events available for your college at this time.</p>
                        <p class="text-[var(--text-muted)] text-sm mt-3">Check back later for new opportunities to connect!</p>
                    </div>
                @else
                    <!-- Events Grid -->
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
                                <!-- Event Image Header -->
                                <div class="relative h-64 bg-gray-200 overflow-hidden">
                                    <img src="{{ $event->image_url }}"
                                         alt="{{ $event->title }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                                    <!-- Top Badges -->
                                    <div class="absolute top-5 left-5 right-5 flex justify-between items-start">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="badge-soft badge-maroon capitalize shadow-sm">
                                                {{ $event->type }}
                                            </span>
                                            @if($isRequiredEvent)
                                                <span class="badge-soft badge-red shadow-sm animate-pulse">
                                                    <i class="fas fa-star mr-1"></i> Required
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if($isRegistered)
                                            <span class="badge-soft badge-green shadow-sm">
                                                <i class="fas fa-check mr-1"></i> Joined
                                            </span>
                                        @elseif($isRequiredEvent)
                                            <span class="badge-soft badge-maroon shadow-sm">
                                                Required
                                            </span>
                                        @else
                                            <span class="badge-soft bg-white/90 text-[var(--text-primary)] shadow-sm">
                                                Open
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Date Badge -->
                                    <div class="absolute bottom-5 left-5 text-white">
                                        <div class="text-xs font-medium opacity-90 uppercase tracking-widest mb-1 text-[var(--gold-primary)]">When</div>
                                        <div class="font-bold text-xl drop-shadow-md font-sans">
                                            {{ \Carbon\Carbon::parse($event->event_start_date)->format('M d') }} 
                                            @if(\Carbon\Carbon::parse($event->event_start_date)->format('M d') !== \Carbon\Carbon::parse($event->event_end_date)->format('M d'))
                                                <span class="text-base font-normal opacity-80">-</span> {{ \Carbon\Carbon::parse($event->event_end_date)->format('M d') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Details -->
                                <div class="p-6 flex-grow flex flex-col">
                                    <h3 class="text-xl font-bold text-[var(--text-primary)] mb-3 line-clamp-2 leading-tight font-serif">
                                        {{ $event->title }}
                                    </h3>

                                    <!-- Meta Info -->
                                    <div class="space-y-3 mb-5 text-sm text-[var(--text-secondary)]">
                                        <div class="flex items-center">
                                            <i class="far fa-clock mr-3 text-[var(--gold-primary)] w-5 text-center"></i>
                                            <span>{{ $event->time_range }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="far fa-map-marker-alt mr-3 text-[var(--maroon-soft)] w-5 text-center"></i>
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

                                    <!-- Action Buttons -->
                                    <div class="grid grid-cols-2 gap-3 mt-auto">
                                        @if($isRegistered)
                                            @if($isRequiredEvent)
                                                <button class="col-span-2 bg-gray-100 text-gray-500 text-sm px-3 py-3 rounded-xl cursor-not-allowed flex items-center justify-center font-medium" disabled>
                                                    <i class="fas fa-lock mr-2"></i> Auto Registered
                                                </button>
                                            @else
                                                <form action="{{ route('student.events.cancel', $event) }}" method="POST" class="col-span-2">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full bg-red-50 text-red-700 border border-red-100 text-sm px-3 py-3 rounded-xl hover:bg-red-100 transition flex items-center justify-center font-medium"
                                                            onclick="return confirm('Cancel your registration?')">
                                                        <i class="fas fa-times-circle mr-2"></i> Cancel Registration
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif($isRequiredEvent)
                                            <button class="col-span-2 btn-primary flex items-center justify-center font-medium rounded-xl py-3">
                                                <i class="fas fa-user-check mr-2"></i> Required Attendance
                                            </button>
                                        @elseif($hasAvailableSlots)
                                            <form action="{{ route('student.events.register', $event) }}" method="POST" class="col-span-2">
                                                @csrf
                                                <button type="submit" class="w-full btn-primary flex items-center justify-center font-medium rounded-xl py-3">
                                                    <i class="fas fa-calendar-plus mr-2"></i> Register Now
                                                </button>
                                            </form>
                                        @else
                                            <button class="col-span-2 bg-gray-100 text-gray-500 text-sm px-3 py-3 rounded-xl cursor-not-allowed flex items-center justify-center font-medium" disabled>
                                                <i class="fas fa-calendar-times mr-2"></i> Event Full
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <!-- Expandable Details Toggle -->
                                    <button onclick="toggleDetails('mhc-details-{{ $event->id }}')"
                                            class="mt-5 text-xs font-bold text-[var(--gold-primary)] hover:text-[var(--maroon-soft)] transition flex items-center justify-center w-full uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-2"></i> View Full Details
                                    </button>

                                    <!-- Hidden Details -->
                                    <div id="mhc-details-{{ $event->id }}" class="hidden mt-4 pt-5 border-t border-[var(--border-soft)] text-sm text-[var(--text-secondary)] space-y-3 animate-fade-in">
                                        <p class="leading-relaxed">{{ $event->description }}</p>
                                        @if($isRequiredEvent)
                                            <div class="bg-red-50 text-red-800 p-4 rounded-xl mt-2 text-xs border border-red-100">
                                                <i class="fas fa-info-circle mr-1"></i> <strong>Note:</strong> This event is mandatory for your college.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <!-- Resources Section -->
            <section class="mb-16">
                <div class="mb-10 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-[var(--text-primary)] inline-block border-b-2 border-[var(--gold-primary)] pb-2 font-serif">
                        Access Resources
                    </h2>
                    <p class="text-[var(--text-secondary)] mt-3 text-sm ml-1">Curated tools and content for your journey.</p>
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
                            <!-- Category Header Image -->
                            <div class="relative h-48 overflow-hidden">
                                @if($firstResource && $firstResource->image_url)
                                    <img src="{{ $firstResource->image_url }}"
                                         alt="{{ $name }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-[var(--maroon-soft)] to-[var(--maroon-medium)]"></div>
                                @endif
                                <div class="absolute inset-0 category-overlay flex items-center justify-center">
                                    <i class="{{ $firstResource->icon ?? 'fas fa-folder' }} text-white text-4xl drop-shadow-lg"></i>
                                </div>
                            </div>

                            <!-- Category Content -->
                            <div class="p-6 text-center flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-[var(--text-primary)] mb-2 font-serif">{{ $name }}</h3>
                                    <p class="text-[var(--text-muted)] mb-6 text-sm">
                                        {{ $resources->count() }} resource{{ $resources->count() !== 1 ? 's' : '' }} available
                                    </p>
                                </div>

                                <a href="{{ route('student.resources.category', $key) }}"
                                   class="btn-secondary inline-block px-6 py-3 rounded-xl font-semibold text-sm w-full">
                                    @if($key === 'youtube') Explore Videos
                                    @elseif($key === 'ebooks') Browse eBooks
                                    @elseif($key === 'private') Access Content
                                    @else View Resources
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- FAQs Section -->
            <section class="mb-12 max-w-4xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-[var(--text-primary)] inline-block border-b-2 border-[var(--gold-primary)] pb-2 font-serif">Frequently Asked Questions</h2>
                    <p class="text-[var(--text-secondary)] mt-4">Find answers to common questions about our services.</p>
                </div>

                @php
                    $faqs = \App\Models\FAQ::active()->ordered()->get();
                @endphp

                @if($faqs->isEmpty())
                    <div class="mhc-card p-10 text-center">
                        <p class="text-[var(--text-secondary)]">No FAQs available at this time.</p>
                    </div>
                @else
                    <div>
                        @foreach($faqs as $index => $faq)
                            <div class="faq-item" id="faq-{{ $faq->id }}">
                                <div class="faq-question" onclick="toggleFaq({{ $faq->id }})">
                                    <span class="text-base">{{ $faq->question }}</span>
                                    <i class="fas fa-chevron-down text-[var(--gold-primary)] transition-transform duration-300"></i>
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

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-[var(--maroon-medium)] to-[var(--maroon-dark)] text-white py-12 mt-auto shadow-inner">
        <div class="container mx-auto px-6 text-center">
            <div class="mb-6">
                <span class="text-3xl font-bold tracking-wide"><span class="text-[var(--gold-primary)]">OGC</span></span>
            </div>
            <p class="text-gray-300 text-sm">&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
            <p class="text-xs text-gray-400 mt-3 italic font-light">Committed to your mental health and well-being</p>
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

        // Toggle Event Details
        function toggleDetails(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
</body>
</html>