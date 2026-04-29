<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $categories[$category] }} - Mental Health Corner</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-red: #8f1d1d;
            --primary-red-dark: #6f1414;
            --primary-red-deep: #5b0f0f;
            --accent-gold: #d4af37;
            --bg-light: #f6f1ea;
            --bg-white: #fffdfb;
            --text-dark: #2f2522;
            --text-secondary: #766864;
            --text-muted: #a09490;
            --border-soft: #e8ddd2;
            --shadow-soft: 0 10px 30px rgba(91,15,15,0.08);
            --shadow-medium: 0 16px 40px rgba(91,15,15,0.12);
            --maroon-900: #3a0c0c;
            --maroon-800: #5c1a1a;
            --maroon-700: #7a2a2a;
            --gold-400: #d4af37;
            --bg-warm: #faf8f5;
            --student-warning: #fff7ed;
            --student-warning-border: #fdba74;
            --student-warning-text: #9a3412;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg-light);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .dashboard-navbar {
            position: sticky; top: 0; left: 0; right: 0; z-index: 1000;
            background: linear-gradient(90deg, var(--primary-red-deep), var(--primary-red), #a11f2f);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 24px rgba(91,15,15,0.18);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s ease;
        }
        .dashboard-navbar.scrolled { box-shadow: 0 12px 28px rgba(91,15,15,0.24); }
        .nav-link {
            color: white; font-weight: 600; transition: 0.25s ease; text-decoration: none;
        }
        .nav-link:hover { color: rgba(255,245,235,0.88); }
        .dropdown-panel {
            position: absolute; top: calc(100% + 10px); left: 0;
            background: var(--bg-white); box-shadow: var(--shadow-medium);
            border-radius: 16px; padding: 0.5rem; width: 220px;
            z-index: 1001; border: 1px solid var(--border-soft);
        }
        .dropdown-link {
            display: block; padding: 0.75rem 0.9rem; border-radius: 12px;
            color: var(--text-dark); transition: all 0.2s ease; text-decoration: none;
        }
        .dropdown-link:hover { color: var(--primary-red); background: #f8f1e8; }
        .profile-dropdown-content {
            position: absolute; right: 0; top: calc(100% + 10px);
            background: var(--bg-white); box-shadow: var(--shadow-medium);
            border-radius: 16px; padding: 1rem; min-width: 220px;
            z-index: 1001; border: 1px solid var(--border-soft);
        }
        /* Resource page specific */
        .resources-shell { position: relative; overflow: hidden; background: var(--bg-warm); min-height: 100vh; }
        .resources-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; }
        .resources-glow.one { top: -40px; left: -50px; width: 240px; height: 240px; background: var(--gold-400); }
        .resources-glow.two { bottom: -50px; right: -70px; width: 280px; height: 280px; background: var(--maroon-800); }
        .hero-card, .panel-card, .glass-card, .resource-card {
            position: relative; overflow: hidden; border-radius: 0.75rem;
            border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .hero-card:hover, .panel-card:hover, .glass-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
        .resource-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(92,26,26,0.08); }
        .hero-card::before, .panel-card::before, .glass-card::before, .resource-card::before {
            content: ""; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 35%);
        }
        .hero-icon { width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%); box-shadow: 0 4px 12px rgba(92,26,26,0.15); }
        .hero-badge { display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px; border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.9); padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.16em; color: var(--maroon-700); }
        .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }
        .back-link { display: inline-flex; align-items: center; gap: 0.4rem; color: var(--maroon-700); font-size: 0.75rem; font-weight: 600; transition: all 0.18s ease; text-decoration: none; }
        .back-link:hover { color: var(--maroon-900); transform: translateX(-2px); }
        .resource-image { position: relative; height: 12rem; overflow: hidden; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%); display: flex; align-items: center; justify-content: center; }
        .resource-image img { width: 100%; height: 100%; object-fit: contain; padding: 1.5rem; background: white; transition: transform 0.3s ease; }
        .resource-card:hover .resource-image img { transform: scale(1.03); }
        .resource-icon-overlay { position: absolute; top: 0.75rem; right: 0.75rem; width: 2.5rem; height: 2.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.95); color: var(--maroon-700); font-size: 1rem; box-shadow: 0 4px 12px rgba(44,36,32,0.1); }
        .resource-title { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); line-height: 1.3; margin-bottom: 0.5rem; }
        .resource-desc { font-size: 0.8rem; color: var(--text-secondary); line-height: 1.5; white-space: pre-line; word-wrap: break-word; }
        .resource-btn { border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; font-size: 0.8rem; padding: 0.55rem 1rem; background: linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%); color: white; border: none; width: 100%; }
        .resource-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(122,42,42,0.2); }
        .resource-btn.external::after { content: "\f35d"; font-family: "Font Awesome 6 Free"; font-weight: 900; font-size: 0.7rem; margin-left: 0.4rem; opacity: 0.9; }
        .resource-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none !important; }
        .disclaimer-box { background: var(--student-warning); border: 1px solid var(--student-warning-border); border-radius: 0.6rem; padding: 0.6rem; cursor: pointer; transition: all 0.2s ease; }
        .disclaimer-box:hover { background: rgba(255,247,237,0.95); }
        .disclaimer-header { display: flex; align-items: center; justify-content: space-between; font-size: 0.7rem; font-weight: 700; color: var(--student-warning-text); text-transform: uppercase; letter-spacing: 0.05em; }
        .disclaimer-header i { color: #f59e0b; margin-right: 0.3rem; }
        .disclaimer-content { margin-top: 0.5rem; font-size: 0.7rem; color: var(--student-warning-text); line-height: 1.4; white-space: pre-line; }
        .disclaimer-icon { transition: transform 0.2s ease; color: #ea580c; }
        .empty-state { text-align: center; padding: 2.5rem 1.5rem; background: rgba(255,255,255,0.95); border: 1px solid var(--border-soft); border-radius: 0.75rem; }
        .empty-state-icon { width: 4rem; height: 4rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem; background: rgba(254,249,231,0.8); color: var(--maroon-700); border: 2px dashed var(--gold-400); }
        @media (max-width: 639px) {
            .resource-image { height: 10rem; }
            .resource-title { font-size: 1rem; }
            .resource-desc { font-size: 0.75rem; }
            .resources-grid-mobile { grid-template-columns: 1fr !important; gap: 1rem !important; }
        }
    </style>
</head>
<body>
<div class="resources-shell">
    <div class="resources-glow one"></div>
    <div class="resources-glow two"></div>

    <nav class="dashboard-navbar py-4" id="mainNavbar">
        <div class="container mx-auto px-6" style="display:grid;grid-template-columns:1fr auto 1fr;align-items:center;">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3" style="text-decoration:none;">
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
                            <a href="{{ route('mhc') }}" class="dropdown-link">Mental Health Corner</a>
                        </div>
                    </div>
                    <a href="{{ route('feedback') }}" class="nav-link">Feedback</a>
                @endif
            </div>

            <div class="flex items-center space-x-4 justify-end">
                <div class="relative" id="notif-dropdown-wrapper">
                    <button id="notif-bell-btn" class="text-white p-2 rounded-full hover:bg-white/10 transition relative" aria-label="Notifications">
                        <i class="fas fa-bell"></i>
                        @if($unreadCount > 0)
                            <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-1 leading-none">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @endif
                    </button>
                    <div id="notif-panel" class="hidden absolute right-0 top-[calc(100%+10px)] w-80 bg-white rounded-2xl shadow-xl border border-[#e8ddd2] z-[1002] overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-[#e8ddd2]">
                            <span class="font-semibold text-sm text-[#2f2522]">Notifications</span>
                            @if($unreadCount > 0)
                                <button id="mark-all-read-btn" class="text-xs text-[#8f1d1d] hover:underline font-medium">Mark all as read</button>
                            @endif
                        </div>
                        <div class="overflow-y-auto divide-y divide-[#e8ddd2]" id="notif-list">
                            @forelse($unreadNotifications as $notif)
                                <div class="notif-item flex items-start gap-3 px-4 py-3 hover:bg-[#f6f1ea] cursor-pointer bg-blue-50/40" data-id="{{ $notif->id }}">
                                    @php
                                        $nType = $notif->data['type'] ?? '';
                                        [$nIcon, $nBg] = match($nType) {
                                            'appointment_booked','appointment_booked_by_counselor' => ['fa-calendar-plus','#2d7a4f'],
                                            'appointment_cancelled' => ['fa-calendar-xmark','#b91c1c'],
                                            'appointment_rescheduled','reschedule_response' => ['fa-calendar-days','#c2410c'],
                                            'appointment_referred','appointment_referred_to_counselor','referral_response' => ['fa-arrow-right-arrow-left','#7a2a2a'],
                                            'appointment_status_changed' => ['fa-circle-check','#2a5a7a'],
                                            default => ['fa-bell','#7a2a2a'],
                                        };
                                    @endphp
                                    <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center" style="background:{{ $nBg }}">
                                        <i class="fas {{ $nIcon }} text-white text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-[#2f2522] truncate">{{ $notif->data['title'] ?? 'Notification' }}</p>
                                        <p class="text-xs text-[#766864] mt-0.5 line-clamp-2">{{ $notif->data['message'] ?? '' }}</p>
                                        <p class="text-[10px] text-[#a09490] mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
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
                        <a href="{{ route('profile.edit') }}" class="block py-2 text-[#2f2522] hover:text-[#8f1d1d] transition" style="text-decoration:none;">
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

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex items-start gap-3">
                    <div class="hero-icon">
                        <i class="fas fa-book-open text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('mhc') }}" class="back-link mb-2">
                            <i class="fas fa-arrow-left text-[9px]"></i> Back to Mental Health Corner
                        </a>
                        <div class="hero-badge">
                            <span class="hero-badge-dot"></span>
                            Resources
                        </div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight mt-2" style="color:var(--text-dark)">{{ $categories[$category] }}</h1>
                        <p class="text-xs sm:text-sm mt-1.5 max-w-2xl" style="color:var(--text-secondary)">
                            Browse helpful mental health resources curated for your wellbeing.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($resources->isEmpty())
            <div class="glass-card empty-state mb-6">
                <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                <h3 class="text-lg font-semibold mb-2" style="color:var(--text-dark)">No Resources Yet</h3>
                <p class="text-sm mb-1 max-w-md mx-auto" style="color:var(--text-secondary)">No resources available in this category yet.</p>
                <p class="text-[0.75rem]" style="color:var(--text-muted)">Check back later — we're always adding more support for you.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6 resources-grid-mobile">
                @foreach($resources as $resource)
                    <div class="resource-card flex flex-col cursor-pointer"
                         onclick="window.location='{{ route('student.resources.show', [$category, $resource]) }}'">
                        <div class="resource-image">
                            <img src="{{ $resource->image_url }}" alt="{{ $resource->title }}"
                                 onerror="this.parentElement.style.background='linear-gradient(135deg,#5c1a1a,#7a2a2a)'">
                            <div class="resource-icon-overlay"><i class="{{ $resource->icon }}"></i></div>
                        </div>
                        <div class="p-4 sm:p-5 flex flex-col flex-grow">
                            <h3 class="resource-title">{{ $resource->title }}</h3>
                            <div class="mb-3 flex-grow">
                                <p class="resource-desc">{{ $resource->description }}</p>
                            </div>
                            @if($resource->show_disclaimer)
                                <div class="mb-3">
                                    <div class="disclaimer-box" onclick="event.stopPropagation(); toggleDisclaimer({{ $resource->id }})">
                                        <div class="disclaimer-header">
                                            <div class="flex items-center">
                                                <i class="fas fa-circle-exclamation"></i>
                                                <span>Disclaimer</span>
                                            </div>
                                            <i class="fas fa-chevron-down disclaimer-icon" id="disclaimer-icon-{{ $resource->id }}"></i>
                                        </div>
                                        <div id="disclaimer-content-{{ $resource->id }}" class="hidden disclaimer-content">
                                            {{ $resource->display_disclaimer }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($resource->link)
                                <a href="{{ $resource->link }}" target="_blank" rel="noopener noreferrer"
                                   class="resource-btn external" onclick="event.stopPropagation();">
                                    {{ $resource->button_text }}
                                </a>
                            @else
                                <button class="resource-btn" disabled onclick="event.stopPropagation();">
                                    {{ $resource->button_text }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <footer style="background:linear-gradient(to right,#5b0f0f,#7b1717,#8f1d1d);color:white;" class="py-4 mt-4">
        <div class="container mx-auto px-6 text-center">
            <p style="color:#f3e8df;">&copy; {{ date('Y') }} Office of Guidance and Counseling. All rights reserved.</p>
            <p class="text-sm mt-2" style="color:#e5caa9;">Committed to student support, wellness, and accessible guidance services</p>
        </div>
    </footer>
</div>

<script>
    const navbar = document.getElementById('mainNavbar');
    window.addEventListener('scroll', () => {
        navbar?.classList.toggle('scrolled', window.scrollY > 10);
    });
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');
    const notifBellBtn = document.getElementById('notif-bell-btn');
    const notifPanel = document.getElementById('notif-panel');
    profileBtn?.addEventListener('click', e => { e.stopPropagation(); profileMenu.classList.toggle('hidden'); notifPanel?.classList.add('hidden'); });
    notifBellBtn?.addEventListener('click', e => { e.stopPropagation(); notifPanel.classList.toggle('hidden'); profileMenu?.classList.add('hidden'); });
    document.addEventListener('click', () => { profileMenu?.classList.add('hidden'); notifPanel?.classList.add('hidden'); });
    profileMenu?.addEventListener('click', e => e.stopPropagation());
    notifPanel?.addEventListener('click', e => e.stopPropagation());
    document.getElementById('mark-all-read-btn')?.addEventListener('click', () => {
        fetch('/notifications/read-all', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(() => {
            document.getElementById('notif-badge')?.remove();
            document.getElementById('notif-list').innerHTML = '<div class="px-4 py-8 text-center text-sm text-[#a09490]"><i class="fas fa-bell-slash text-2xl mb-2 block opacity-40"></i>No new notifications</div>';
            document.getElementById('mark-all-read-btn')?.remove();
        });
    });
    document.querySelectorAll('.notif-item').forEach(item => {
        item.addEventListener('click', function() {
            fetch(`/notifications/${this.dataset.id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
            .then(() => this.remove());
        });
    });
    const servicesBtn = document.getElementById('services-dropdown-btn');
    const servicesMenu = document.getElementById('services-dropdown-menu');
    servicesBtn?.addEventListener('click', e => { e.stopPropagation(); servicesMenu.classList.toggle('hidden'); });
    document.addEventListener('click', () => servicesMenu?.classList.add('hidden'));
    servicesMenu?.addEventListener('click', e => e.stopPropagation());
    function toggleDisclaimer(id) {
        document.getElementById(`disclaimer-content-${id}`)?.classList.toggle('hidden');
        const icon = document.getElementById(`disclaimer-icon-${id}`);
        icon?.classList.toggle('fa-chevron-down');
        icon?.classList.toggle('fa-chevron-up');
    }
</script>
</body>
</html>
