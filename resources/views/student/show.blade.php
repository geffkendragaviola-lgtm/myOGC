@php
    $layout = 'layouts.student';
    if (Auth::check()) {
        if (Auth::user()->role === 'counselor') {
            $layout = 'layouts.app';
        } elseif (Auth::user()->role === 'admin') {
            $layout = 'layouts.admin';
        }
    }
@endphp
@extends($layout)
@section('title', 'Student Profile')
@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c; --maroon-800: #5c1a1a; --maroon-700: #7a2a2a;
        --gold-500: #c9a227; --gold-400: #d4af37;
        --bg-warm: #faf8f5; --border-soft: #e5e0db;
        --text-primary: #2c2420; --text-secondary: #6b5e57; --text-muted: #8b7e76;
    }
    .ogc-shell { position:relative; overflow:hidden; background:var(--bg-warm); min-height:100vh; }
    .ogc-glow { position:absolute; border-radius:50%; filter:blur(80px); pointer-events:none; opacity:0.2; }
    .ogc-glow.one { top:-40px; left:-50px; width:240px; height:240px; background:var(--gold-400); }
    .ogc-glow.two { bottom:-50px; right:-70px; width:280px; height:280px; background:var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .info-card {
        position:relative; overflow:hidden; border-radius:0.75rem;
        border:1px solid var(--border-soft); background:rgba(255,255,255,0.95);
        backdrop-filter:blur(8px); box-shadow:0 2px 8px rgba(44,36,32,0.04);
        transition:box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover { box-shadow:0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before, .glass-card::before, .info-card::before {
        content:""; position:absolute; inset:0; pointer-events:none;
        background:radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 35%);
    }
    .hero-icon {
        width:2.75rem; height:2.75rem; border-radius:0.75rem; color:#fef9e7;
        background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow:0 4px 12px rgba(92,26,26,0.15);
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
    }
    .hero-badge {
        display:inline-flex; align-items:center; gap:0.4rem; border-radius:999px;
        border:1px solid rgba(212,175,55,0.3); background:rgba(254,249,231,0.9);
        padding:0.2rem 0.55rem; font-size:9px; font-weight:700; text-transform:uppercase;
        letter-spacing:0.16em; color:var(--maroon-700);
    }
    .hero-badge-dot { width:0.3rem; height:0.3rem; border-radius:999px; background:var(--gold-400); }

    .summary-card {
        position:relative; overflow:hidden; border-radius:0.75rem;
        border:1px solid rgba(92,26,26,0.15);
        background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color:white;
        box-shadow:0 4px 12px rgba(58,12,12,0.15);
        min-width: 280px;
    }
    @media (min-width: 1024px) {
        .summary-card {
     width: 500px;
            min-width: 500px;
        }
    }
    .summary-card::before {
        content:""; position:absolute; inset:0; opacity:0.15;
        background:radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events:none;
    }
    .summary-icon {
        width:2.5rem; height:2.5rem; border-radius:0.75rem;
        background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.1);
        display:flex; align-items:center; justify-content:center; color:#fef9e7; flex-shrink:0;
    }
    .summary-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.2em; color:rgba(255,255,255,0.7); }
    .summary-value { font-size:1.2rem; line-height:1.2; font-weight:800; margin-top:0.35rem; }
    .btn-primary {
        border-radius:0.6rem; font-weight:600; transition:all 0.2s ease;
        display:inline-flex; align-items:center; justify-content:center; white-space:nowrap;
        font-size:0.8rem; padding:0.55rem 1rem; gap:0.4rem;
        color:#fef9e7; background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow:0 4px 10px rgba(92,26,26,0.15);
    }
    .btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 14px rgba(92,26,26,0.2); }

    /* Profile Card */
    .profile-avatar-wrap {
        position:relative; width:5.5rem; height:5.5rem; margin:0 auto 1rem;
    }
    .profile-avatar-wrap::before {
        content:""; position:absolute; inset:-3px; border-radius:50%;
        background:linear-gradient(135deg, var(--gold-400), var(--maroon-700));
        z-index:0;
    }
    .profile-avatar {
        position:relative; z-index:1; width:100%; height:100%; border-radius:50%;
        object-fit:cover; border:3px solid white; background:var(--bg-warm);
        display:flex; align-items:center; justify-content:center;
    }
    .profile-name { font-size:1.05rem; font-weight:700; color:var(--text-primary); margin:0 0 0.2rem; }
    .profile-id { font-size:0.75rem; color:var(--text-muted); margin:0 0 0.6rem; }
    .chip {
        padding:0.2rem 0.55rem; border-radius:999px; font-size:0.68rem; font-weight:700;
        background:rgba(254,249,231,0.9); color:var(--maroon-800); border:1px solid rgba(212,175,55,0.5);
    }
    .stat-pill {
        flex:1; text-align:center; padding:0.6rem 0.5rem;
        background:var(--bg-warm); border-radius:0.5rem; border:1px solid var(--border-soft);
    }
    .stat-pill-value { font-size:1.2rem; font-weight:800; color:var(--maroon-700); line-height:1; }
    .stat-pill-label { font-size:0.65rem; color:var(--text-muted); margin-top:0.2rem; text-transform:uppercase; letter-spacing:0.05em; }

    /* Info Card */
    .info-card { margin-bottom:0; }
    .info-card-header {
        display:flex; align-items:center; gap:0.6rem;
        padding:0.75rem 1.1rem; border-bottom:1px solid var(--border-soft);
        font-size:0.8rem; font-weight:700; color:var(--text-primary);
    }
    .info-card-header-icon {
        width:1.75rem; height:1.75rem; border-radius:0.5rem;
        background:rgba(254,249,231,0.8); color:var(--maroon-700);
        display:flex; align-items:center; justify-content:center; font-size:0.75rem; flex-shrink:0;
    }
    .info-card-body { padding:1rem 1.1rem; }

    /* Info rows */
    .info-row {
        display:flex; align-items:flex-start; gap:0.75rem;
        padding:0.55rem 0; border-bottom:1px solid rgba(229,224,219,0.5);
        font-size:0.8rem;
    }
    .info-row:last-child { border-bottom:none; padding-bottom:0; }
    .info-row-icon {
        width:1.5rem; height:1.5rem; border-radius:0.4rem; flex-shrink:0; margin-top:0.05rem;
        background:rgba(122,42,42,0.07); color:var(--maroon-700);
        display:flex; align-items:center; justify-content:center; font-size:0.65rem;
    }
    .info-row-label { font-size:0.68rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.1rem; }
    .info-row-value { color:var(--text-primary); font-weight:500; line-height:1.4; }
    .info-row-value a { color:var(--maroon-700); text-decoration:none; font-weight:600; }
    .info-row-value a:hover { color:var(--maroon-900); text-decoration:underline; }

    /* Tabs */
    .tabs-nav {
        display:flex; gap:0.15rem; overflow-x:auto; padding:0.5rem 0.75rem 0;
        border-bottom:1px solid var(--border-soft); background:rgba(250,248,245,0.5);
        scrollbar-width:none;
    }
    .tabs-nav::-webkit-scrollbar { display:none; }
    .tab-btn {
        display:inline-flex; align-items:center; gap:0.4rem;
        padding:0.5rem 0.85rem; font-size:0.75rem; font-weight:600;
        color:var(--text-secondary); background:transparent; border:none;
        border-bottom:2px solid transparent; cursor:pointer; white-space:nowrap;
        transition:all 0.15s ease; border-radius:0.4rem 0.4rem 0 0; margin-bottom:-1px;
    }
    .tab-btn:hover { color:var(--maroon-700); background:rgba(212,175,55,0.08); }
    .tab-btn.active { color:var(--maroon-800); border-bottom-color:var(--gold-500); background:rgba(212,175,55,0.12); font-weight:700; }
    .tab-btn i { font-size:0.7rem; }
    .tab-pane { display:none; }
    .tab-pane.active { display:block; }

    /* Section title */
    .section-title {
        font-size:0.78rem; font-weight:700; color:var(--maroon-700);
        text-transform:uppercase; letter-spacing:0.08em;
        margin:0 0 0.75rem; padding-bottom:0.4rem;
        border-bottom:2px solid rgba(212,175,55,0.3);
        display:flex; align-items:center; gap:0.4rem;
    }

    /* Data grid */
    .data-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; }
    .data-cell {
        padding:0.55rem 0.75rem; border-bottom:1px solid rgba(229,224,219,0.5);
        border-right:1px solid rgba(229,224,219,0.5);
    }
    .data-cell:nth-child(even) { border-right:none; }
    .data-cell-label { font-size:0.65rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.15rem; }
    .data-cell-value { font-size:0.8rem; color:var(--text-primary); font-weight:500; }
    @media (max-width:640px) {
        .data-grid { grid-template-columns:1fr; }
        .data-cell { border-right:none; }
    }

    /* Badges */
    .badge-cloud { display:flex; flex-wrap:wrap; gap:0.35rem; }
    .tag {
        padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600;
        display:inline-flex; align-items:center; gap:0.25rem;
    }
    .tag-blue { background:rgba(59,130,246,0.1); color:#1d4ed8; border:1px solid rgba(59,130,246,0.2); }
    .tag-green { background:rgba(16,185,129,0.1); color:#065f46; border:1px solid rgba(16,185,129,0.2); }
    .tag-gold { background:rgba(212,175,55,0.12); color:#92400e; border:1px solid rgba(212,175,55,0.3); }
    .tag-red { background:rgba(239,68,68,0.1); color:#b91c1c; border:1px solid rgba(239,68,68,0.2); }
    .tag-purple { background:rgba(139,92,246,0.1); color:#5b21b6; border:1px solid rgba(139,92,246,0.2); }
    .tag-gray { background:rgba(107,114,128,0.1); color:#374151; border:1px solid rgba(107,114,128,0.2); }
    .tag-maroon { background:rgba(122,42,42,0.1); color:var(--maroon-700); border:1px solid rgba(122,42,42,0.2); }

    /* Status badges */
    .status-yes { background:rgba(16,185,129,0.1); color:#065f46; border:1px solid rgba(16,185,129,0.25); padding:0.15rem 0.5rem; border-radius:999px; font-size:0.7rem; font-weight:700; }
    .status-no { background:rgba(107,114,128,0.1); color:#374151; border:1px solid rgba(107,114,128,0.2); padding:0.15rem 0.5rem; border-radius:999px; font-size:0.7rem; font-weight:700; }
    .status-danger { background:rgba(239,68,68,0.1); color:#b91c1c; border:1px solid rgba(239,68,68,0.2); padding:0.15rem 0.5rem; border-radius:999px; font-size:0.7rem; font-weight:700; }

    /* Alerts */
    .alert { padding:0.75rem 1rem; border-radius:0.6rem; font-size:0.8rem; display:flex; align-items:flex-start; gap:0.6rem; font-weight:500; }
    .alert-warning { background:rgba(254,243,199,0.95); border:1px solid rgba(217,119,6,0.35); color:#7c3d0a; border-left:3px solid #d97706; }
    .alert-danger { background:rgba(254,242,242,0.95); border:1px solid rgba(185,28,28,0.35); color:#7f1d1d; border-left:3px solid #dc2626; }
    .alert i { margin-top:0.1rem; flex-shrink:0; }

    /* Text block */
    .text-block {
        background:rgba(250,248,245,0.8); border:1px solid var(--border-soft); border-radius:0.5rem;
        padding:0.75rem 1rem; font-size:0.8rem; color:var(--text-primary); line-height:1.6;
    }

    /* Buttons */
    .primary-btn, .secondary-btn, .btn {
        border-radius:0.6rem; font-weight:600; transition:all 0.2s ease;
        display:inline-flex; align-items:center; justify-content:center; white-space:nowrap;
        font-size:0.8rem; padding:0.5rem 1rem; gap:0.4rem; cursor:pointer;
    }
    .primary-btn { color:#fef9e7; background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%); box-shadow:0 4px 10px rgba(92,26,26,0.15); border:none; }
    .primary-btn:hover { transform:translateY(-1px); box-shadow:0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn, .btn { color:var(--text-primary); background:rgba(255,255,255,0.95); border:1px solid var(--border-soft); }
    .secondary-btn:hover, .btn:hover { background:rgba(254,249,231,0.7); border-color:var(--maroon-700); }

    /* Empty state */
    .empty-state { text-align:center; padding:2rem 1rem; color:var(--text-muted); font-size:0.8rem; }
    .empty-state i { font-size:1.5rem; margin-bottom:0.5rem; display:block; opacity:0.4; }

    /* Risk card */
    .risk-card-safe { border-left:3px solid #10b981; }
    .risk-card-danger { border-left:3px solid #dc2626; }

    @media (max-width:768px) {
        .hero-icon { width:2.25rem; height:2.25rem; }
        .tab-btn { padding:0.45rem 0.65rem; font-size:0.7rem; }
    }

    @media print {
        @page { margin:1.5cm; size:A4; }
        .ogc-navbar, #ogcSidebar, .sidebar-footer, .no-print, .tabs-nav, .ogc-glow { display:none !important; }
        #ogcMainContent { margin-left:0 !important; padding-top:0 !important; width:100% !important; }
        body { background:white !important; color:#000 !important; font-size:11pt; }
        .tab-pane { display:block !important; }
        .info-card, .hero-card, .panel-card { border:1px solid #000 !important; box-shadow:none !important; background:white !important; break-inside:avoid; }
        .print-header { display:block !important; border-bottom:2px solid #000; padding-bottom:0.5rem; margin-bottom:1rem; }
        .print-footer { display:block !important; position:fixed; bottom:0; left:0; right:0; text-align:center; font-size:8pt; border-top:1px solid #000; padding:0.5rem 0; }
    }
    .print-header, .print-footer { display:none; }
    .btn:focus, .tab-btn:focus { outline:2px solid var(--gold-500); outline-offset:2px; }
</style>

<div class="min-h-screen ogc-shell">
    <div class="ogc-glow one"></div>
    <div class="ogc-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        <!-- Print Header -->
        <div class="print-header">
            <h1>Student Profile Report</h1>
            <div class="meta"><strong>{{ $student->full_name }}</strong> | ID: {{ $student->student_id }} | Generated: {{ date('F j, Y g:i A') }}</div>
        </div>

        <!-- Page Header -->
        <div class="mb-6 sm:mb-8">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card h-full">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon"><i class="fas fa-user-graduate text-base sm:text-lg"></i></div>
                        <div class="min-w-0 flex-1">
                            <div class="hero-badge"><span class="hero-badge-dot"></span>Student Profile</div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">{{ $student->full_name }}</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1">{{ $student->student_id }} &middot; {{ $student->course }} &middot; {{ $student->year_level }}</p>
                        </div>
                    </div>
                </div>

                <div class="summary-card no-print h-full">
                    <div class="relative h-full flex items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3">
                            <div class="summary-icon">
                                <i class="fas fa-calendar-check text-sm"></i>
                            </div>
                            <div>
                                <p class="summary-label">Total Sessions</p>
                                <p class="summary-value">{{ $student->appointments->count() }}</p>
                            </div>
                        </div>
                        @if(Auth::check() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.students') }}" class="btn-primary no-print">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back to Student List</span>
                        </a>
                        @elseif(Auth::check() && Auth::user()->role === 'counselor')
                        <a href="{{ route('counselor.appointments.create', ['student_id' => $student->id]) }}" 
                           class="primary-btn px-4 py-2 text-xs sm:text-sm whitespace-nowrap no-print" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); box-shadow: none;">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Book New</span>
                        </a>
                        @else
                        <a href="{{ route('appointments.create') }}" class="btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Book Session</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Completion Alert -->
        @if($student->profile_completion['percentage'] < 100)
        <div class="alert alert-warning mb-6 sm:mb-8">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Profile {{ $student->profile_completion['percentage'] }}% complete.</strong>
                Missing sections:
                @foreach($student->profile_completion['sections'] as $section => $completed)
                    @if(!$completed)<span class="chip ml-1">{{ ucfirst($section) }}</span>@endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- High-Risk Alert (Counselor/Admin Only) -->
        @if(Auth::check() && in_array(Auth::user()->role, ['counselor', 'admin']))
        @php
            $stressResponses = $student->needsAssessment?->stress_responses ?? [];
            $stressResponses = is_array($stressResponses) ? $stressResponses : [];
            $riskResponses = ['Hurt myself', 'Attempted to end my life', 'Thought it would be better dead'];
            $hasSelfHarmRisk = !$student->high_risk_overridden && count(array_intersect($riskResponses, $stressResponses)) > 0;
            $isHighRisk = $student->is_high_risk || $hasSelfHarmRisk;
        @endphp
        <div class="panel-card mb-5 sm:mb-6" style="{{ $isHighRisk ? 'border:1px solid rgba(220,38,38,0.3); background:rgba(254,242,242,0.95);' : 'border:1px solid rgba(16,185,129,0.3); background:rgba(236,253,245,0.95);' }}">
            <div class="panel-topline" style="{{ $isHighRisk ? 'background:linear-gradient(90deg, #dc2626 0%, #ef4444 100%);' : 'background:linear-gradient(90deg, #10b981 0%, #34d399 100%);' }}"></div>
            <div class="p-3 sm:p-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="panel-icon w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="{{ $isHighRisk ? 'background:rgba(220,38,38,0.1);color:#dc2626;' : 'background:rgba(16,185,129,0.1);color:#059669;' }}">
                            <i class="fas fa-{{ $isHighRisk ? 'exclamation-triangle' : 'shield-halved' }} text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold" style="{{ $isHighRisk ? 'color:#991b1b;' : 'color:#065f46;' }}">High-Risk Status</h3>
                            <p class="text-[11px] mt-0.5" style="{{ $isHighRisk ? 'color:#b91c1c;' : 'color:#047857;' }}">
                                {{ $isHighRisk ? 'This student requires immediate attention.' : 'Not currently flagged as high-risk.' }}
                            </p>
                        </div>
                    </div>
                    @if(in_array(Auth::user()->role, ['counselor', 'admin']))
                    <button type="button" onclick="toggleHighRiskModal()" class="secondary-btn text-[11px] px-3 py-1.5 flex-shrink-0" style="{{ $isHighRisk ? 'border-color:rgba(220,38,38,0.2);color:#dc2626;hover:background:rgba(220,38,38,0.05);' : '' }}">
                        <i class="fas fa-flag text-[10px]"></i>
                        <span>{{ $student->is_high_risk ? 'Update Flag' : 'Flag Student' }}</span>
                    </button>
                    @endif
                </div>

                @if($isHighRisk)
                <div class="mt-3 flex flex-col gap-2">
                    @if($hasSelfHarmRisk)
                    <div class="p-2.5 sm:p-3 rounded-md bg-white border border-red-100 flex items-start gap-2.5">
                        <i class="fas fa-notes-medical mt-0.5 text-red-500 text-xs"></i>
                        <div>
                            <h4 class="text-xs font-semibold text-red-800">Assessment-Based Risk</h4>
                            <p class="text-[11px] text-red-600 mt-0.5 leading-snug">Student indicated self-harm or suicidal thoughts in their needs assessment.</p>
                        </div>
                    </div>
                    @endif
                    @if($student->is_high_risk)
                    <div class="p-2.5 sm:p-3 rounded-md bg-white border border-orange-100 flex items-start gap-2.5">
                        <i class="fas fa-user-shield mt-0.5 text-orange-500 text-xs"></i>
                        <div>
                            <h4 class="text-xs font-semibold text-orange-800">Counselor Flagged</h4>
                            @if($student->high_risk_notes)
                                <p class="text-[11px] text-orange-700 mt-0.5 leading-snug"><span class="font-medium">Notes:</span> {{ $student->high_risk_notes }}</p>
                            @endif
                            <p class="text-[9px] text-orange-600 mt-1.5 opacity-80">
                                By {{ $student->flaggedBy->first_name ?? 'Unknown' }} {{ $student->flaggedBy->last_name ?? '' }} &middot; {{ $student->high_risk_flagged_at?->format('M j, Y g:i A') }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Top Info Grid: Profile + Contact + Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6 sm:mb-8">

            <!-- Profile Card -->
            <div class="info-card text-center">
                <div class="info-card-body">
                    <div class="profile-avatar-wrap">
                        @if($student->profile_picture)
                            <img src="{{ $student->profile_picture_url }}" alt="Profile" class="profile-avatar">
                        @else
                            <div class="profile-avatar text-3xl text-[var(--text-muted)]"><i class="fas fa-user"></i></div>
                        @endif
                    </div>
                    <h3 class="profile-name">{{ $student->full_name }}</h3>
                    <p class="profile-id">{{ $student->student_id }}</p>
                    <div class="flex flex-wrap gap-1.5 justify-center mb-4">
                        <span class="chip">{{ $student->year_level }}</span>
                        <span class="chip">{{ $student->course }}</span>
                        <span class="chip">{{ $student->student_status }}</span>
                    </div>
                    <div class="flex gap-2">
                        <div class="stat-pill">
                            <div class="stat-pill-value">{{ $student->registration_count }}</div>
                            <div class="stat-pill-label">Events</div>
                        </div>
                        <div class="stat-pill">
                            <div class="stat-pill-value">{{ $student->appointments->count() }}</div>
                            <div class="stat-pill-label">Sessions</div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Contact & Academic Info -->
            <div class="info-card lg:col-span-2">
                <div class="info-card-header">
                    <div class="info-card-header-icon"><i class="fas fa-id-card"></i></div>
                    Contact & Academic Information
                </div>
                <div class="info-card-body">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-0">
                        <div class="info-row">
                            <div class="info-row-icon"><i class="fas fa-envelope"></i></div>
                            <div><div class="info-row-label">Email</div><div class="info-row-value"><a href="mailto:{{ $student->email }}">{{ $student->email ?: 'Not provided' }}</a></div></div>
                        </div>
                        <div class="info-row">
                            <div class="info-row-icon"><i class="fas fa-phone"></i></div>
                            <div><div class="info-row-label">Phone</div><div class="info-row-value">{{ $student->phone_number ?: 'Not provided' }}</div></div>
                        </div>
                        <div class="info-row">
                            <div class="info-row-icon"><i class="fas fa-location-dot"></i></div>
                            <div><div class="info-row-label">Address (Iligan City)</div><div class="info-row-value">{{ $student->user->address ?? 'Not provided' }}</div></div>
                        </div>
                        <div class="info-row">
                            <div class="info-row-icon"><i class="fas fa-building-columns"></i></div>
                            <div><div class="info-row-label">College</div><div class="info-row-value">{{ $student->college->name ?? 'Not assigned' }}</div></div>
                        </div>
                        <div class="info-row">
                            <div class="info-row-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div><div class="info-row-label">Course & Year</div><div class="info-row-value">{{ $student->course ?: 'N/A' }} — {{ $student->year_level ?: 'N/A' }}</div></div>
                        </div>
                        <div class="info-row">
                            <div class="info-row-icon"><i class="fas fa-calendar"></i></div>
                            <div><div class="info-row-label">Academic Year</div><div class="info-row-value">{{ $student->academic_year ?: 'Not provided' }}</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Tabs Card -->
        <div class="info-card">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="personal"><i class="fas fa-user"></i> Personal</button>
                <button class="tab-btn" data-tab="family"><i class="fas fa-house-user"></i> Family</button>
                <button class="tab-btn" data-tab="academic"><i class="fas fa-graduation-cap"></i> Academic</button>
                <button class="tab-btn" data-tab="learning"><i class="fas fa-laptop"></i> Learning</button>
                <button class="tab-btn" data-tab="psychosocial"><i class="fas fa-brain"></i> Psychosocial</button>
                <button class="tab-btn" data-tab="needs"><i class="fas fa-notes-medical"></i> Needs</button>
            </div>

            <div class="info-card-body">

                <!-- ── PERSONAL TAB ── -->
                <div class="tab-pane active" id="personal">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
                        <!-- Basic Info -->
                        <div>
                            <p class="section-title"><i class="fas fa-circle-info"></i> Basic Information</p>
                            <div class="data-grid" style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                                <div class="data-cell"><div class="data-cell-label">First Name</div><div class="data-cell-value">{{ $student->user->first_name ?? '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Last Name</div><div class="data-cell-value">{{ $student->user->last_name ?? '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Middle Name</div><div class="data-cell-value">{{ $student->user->middle_name ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Sex</div><div class="data-cell-value">{{ $student->user->sex ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Birthdate</div><div class="data-cell-value">{{ $student->user->birthdate ? \Carbon\Carbon::parse($student->user->birthdate)->format('M d, Y') : '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Age</div><div class="data-cell-value">{{ $student->user->age ?? '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Birthplace</div><div class="data-cell-value">{{ $student->user->birthplace ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Religion</div><div class="data-cell-value">{{ $student->user->religion ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Civil Status</div><div class="data-cell-value">{{ $student->user->civil_status ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Citizenship</div><div class="data-cell-value">{{ $student->user->citizenship ?: '—' }}</div></div>
                            </div>
                        </div>
                        <!-- Registration Details -->
                        <div>
                            <p class="section-title"><i class="fas fa-id-badge"></i> Registration Details</p>
                            <div class="data-grid" style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                                <div class="data-cell"><div class="data-cell-label">Student ID</div><div class="data-cell-value">{{ $student->student_id ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Status</div><div class="data-cell-value">{{ $student->student_status ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Year Level</div><div class="data-cell-value">{{ $student->year_level ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Course</div><div class="data-cell-value">{{ $student->course ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">College</div><div class="data-cell-value">{{ $student->college->name ?? '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Academic Year</div><div class="data-cell-value">{{ $student->academic_year ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">MSU SASE Score</div><div class="data-cell-value">{{ $student->msu_sase_score ?? '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Children</div><div class="data-cell-value">{{ $student->user->number_of_children ?? '—' }}</div></div>
                            </div>
                        </div>
                    </div>

                    @if($student->personalData)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="section-title"><i class="fas fa-house"></i> Personal Details</p>
                            <div class="data-grid" style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                                <div class="data-cell"><div class="data-cell-label">Nickname</div><div class="data-cell-value">{{ $student->personalData->nickname ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Stays With</div><div class="data-cell-value">{{ $student->personalData->stays_with ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Home Address</div><div class="data-cell-value">{{ $student->personalData->home_address ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Working Student</div><div class="data-cell-value">{{ $student->personalData->working_student ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Sex Identity</div><div class="data-cell-value">{{ $student->personalData->sex_identity ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Romantic Attraction</div><div class="data-cell-value">{{ $student->personalData->romantic_attraction ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Medical Condition</div><div class="data-cell-value">{{ $student->personalData->serious_medical_condition ?: 'None' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Physical Disability</div><div class="data-cell-value">{{ $student->personalData->physical_disability ?: 'None' }}</div></div>
                            </div>
                        </div>
                        <div>
                            @php
                                $talentsSkills = $student->personalData->talents_skills;
                                if (is_string($talentsSkills)) { $d = json_decode($talentsSkills, true); $talentsSkills = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $talentsSkills))); }
                                $leisureActivities = $student->personalData->leisure_activities;
                                if (is_string($leisureActivities)) { $d = json_decode($leisureActivities, true); $leisureActivities = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $leisureActivities))); }
                            @endphp
                            <p class="section-title"><i class="fas fa-star"></i> Talents & Skills</p>
                            @if($talentsSkills && count($talentsSkills))
                                <div class="badge-cloud mb-4">@foreach($talentsSkills as $s)<span class="tag tag-blue">{{ $s }}</span>@endforeach</div>
                            @else<div class="empty-state"><i class="fas fa-star"></i>Not provided</div>@endif
                            <p class="section-title mt-4"><i class="fas fa-gamepad"></i> Leisure Activities</p>
                            @if($leisureActivities && count($leisureActivities))
                                <div class="badge-cloud">@foreach($leisureActivities as $a)<span class="tag tag-green">{{ $a }}</span>@endforeach</div>
                            @else<div class="empty-state"><i class="fas fa-gamepad"></i>Not provided</div>@endif
                        </div>
                    </div>
                    @else
                        <div class="empty-state"><i class="fas fa-user-slash"></i>No personal data available</div>
                    @endif
                </div>

                <!-- ── FAMILY TAB ── -->
                <div class="tab-pane" id="family">
                    @if($student->familyData)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="section-title"><i class="fas fa-person"></i> Father's Information</p>
                            <div class="data-grid" style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                                <div class="data-cell"><div class="data-cell-label">Name</div><div class="data-cell-value">{{ $student->familyData->father_name ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Status</div><div class="data-cell-value">@if($student->familyData->father_deceased)<span class="status-danger">Deceased</span>@else<span class="status-yes">Living</span>@endif</div></div>
                                <div class="data-cell"><div class="data-cell-label">Occupation</div><div class="data-cell-value">{{ $student->familyData->father_occupation ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Phone</div><div class="data-cell-value">{{ $student->familyData->father_phone_number ?: '—' }}</div></div>
                            </div>
                        </div>
                        <div>
                            <p class="section-title"><i class="fas fa-person-dress"></i> Mother's Information</p>
                            <div class="data-grid" style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                                <div class="data-cell"><div class="data-cell-label">Name</div><div class="data-cell-value">{{ $student->familyData->mother_name ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Status</div><div class="data-cell-value">@if($student->familyData->mother_deceased)<span class="status-danger">Deceased</span>@else<span class="status-yes">Living</span>@endif</div></div>
                                <div class="data-cell"><div class="data-cell-label">Occupation</div><div class="data-cell-value">{{ $student->familyData->mother_occupation ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Phone</div><div class="data-cell-value">{{ $student->familyData->mother_phone_number ?: '—' }}</div></div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="section-title"><i class="fas fa-people-roof"></i> Family Overview</p>
                            <div class="data-grid" style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                                <div class="data-cell"><div class="data-cell-label">Marital Status</div><div class="data-cell-value">{{ $student->familyData->parents_marital_status ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Monthly Income</div><div class="data-cell-value">{{ $student->familyData->family_monthly_income ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Ordinal Position</div><div class="data-cell-value">{{ $student->familyData->ordinal_position ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">No. of Siblings</div><div class="data-cell-value">{{ $student->familyData->number_of_siblings ?: '0' }}</div></div>
                            </div>
                        </div>
                        @if($student->familyData->guardian_name)
                        <div>
                            <p class="section-title"><i class="fas fa-user-shield"></i> Guardian Information</p>
                            <div class="data-grid" style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                                <div class="data-cell"><div class="data-cell-label">Name</div><div class="data-cell-value">{{ $student->familyData->guardian_name }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Relationship</div><div class="data-cell-value">{{ $student->familyData->guardian_relationship ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Occupation</div><div class="data-cell-value">{{ $student->familyData->guardian_occupation ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Phone</div><div class="data-cell-value">{{ $student->familyData->guardian_phone_number ?: '—' }}</div></div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if($student->familyData->home_environment_description)
                        <p class="section-title"><i class="fas fa-house-chimney"></i> Home Environment</p>
                        <div class="text-block">{{ $student->familyData->home_environment_description }}</div>
                    @endif
                    @else
                        <div class="empty-state"><i class="fas fa-house-crack"></i>No family data available</div>
                    @endif
                </div>

                <!-- ── ACADEMIC TAB ── -->
                <div class="tab-pane" id="academic">
                    @if($student->academicData)
                    @php
                        $awards = $student->academicData->awards_honors;
                        if (is_string($awards)) { $d = json_decode($awards, true); $awards = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $awards))); }
                        $orgs = $student->academicData->student_organizations;
                        if (is_string($orgs)) { $d = json_decode($orgs, true); $orgs = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $orgs))); }
                        $activities = $student->academicData->co_curricular_activities;
                        if (is_string($activities)) { $d = json_decode($activities, true); $activities = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $activities))); }
                        $reasons = $student->academicData->msu_choice_reasons;
                        if (is_string($reasons)) { $d = json_decode($reasons, true); $reasons = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $reasons))); }
                    @endphp
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
                        <div>
                            <p class="section-title"><i class="fas fa-school"></i> Academic Background</p>
                            <div class="data-grid" style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                                <div class="data-cell"><div class="data-cell-label">SHS GPA</div><div class="data-cell-value">{{ $student->academicData->shs_gpa ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Scholar</div><div class="data-cell-value">@if($student->academicData->is_scholar)<span class="status-yes">Yes</span>@if($student->academicData->scholarship_type) <span class="text-xs text-[var(--text-muted)]">({{ $student->academicData->scholarship_type }})</span>@endif@else<span class="status-no">No</span>@endif</div></div>
                                <div class="data-cell"><div class="data-cell-label">SHS Track</div><div class="data-cell-value">{{ $student->academicData->shs_track ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">SHS Strand</div><div class="data-cell-value">{{ $student->academicData->shs_strand ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Last School</div><div class="data-cell-value">{{ $student->academicData->school_last_attended ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">School Address</div><div class="data-cell-value">{{ $student->academicData->school_address ?: '—' }}</div></div>
                                <div class="data-cell"><div class="data-cell-label">Course Choice By</div><div class="data-cell-value">{{ $student->academicData->course_choice_by ?: '—' }}</div></div>
                            </div>
                        </div>
                        <div>
                            @if($student->academicData->career_option_1 || $student->academicData->career_option_2 || $student->academicData->career_option_3)
                            <p class="section-title"><i class="fas fa-briefcase"></i> Career Options</p>
                            <div class="flex flex-col gap-2 mb-4">
                                @if($student->academicData->career_option_1)
                                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.6rem 0.75rem;background:rgba(122,42,42,0.05);border:1px solid rgba(122,42,42,0.12);border-radius:0.5rem;">
                                    <span style="width:1.4rem;height:1.4rem;border-radius:50%;background:var(--maroon-700);color:white;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;">1</span>
                                    <span style="font-size:0.82rem;font-weight:600;color:var(--text-primary);">{{ $student->academicData->career_option_1 }}</span>
                                </div>
                                @endif
                                @if($student->academicData->career_option_2)
                                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.6rem 0.75rem;background:rgba(212,175,55,0.06);border:1px solid rgba(212,175,55,0.2);border-radius:0.5rem;">
                                    <span style="width:1.4rem;height:1.4rem;border-radius:50%;background:var(--gold-500);color:white;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;">2</span>
                                    <span style="font-size:0.82rem;font-weight:600;color:var(--text-primary);">{{ $student->academicData->career_option_2 }}</span>
                                </div>
                                @endif
                                @if($student->academicData->career_option_3)
                                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.6rem 0.75rem;background:var(--bg-warm);border:1px solid var(--border-soft);border-radius:0.5rem;">
                                    <span style="width:1.4rem;height:1.4rem;border-radius:50%;background:var(--text-muted);color:white;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;">3</span>
                                    <span style="font-size:0.82rem;font-weight:600;color:var(--text-primary);">{{ $student->academicData->career_option_3 }}</span>
                                </div>
                                @endif
                            </div>
                            @endif
                            <p class="section-title"><i class="fas fa-trophy"></i> Awards & Honors</p>
                            @if($awards && count($awards))<div class="badge-cloud mb-3">@foreach($awards as $a)<span class="tag tag-gold">{{ $a }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">None provided</span></div>@endif
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="section-title"><i class="fas fa-users"></i> Organizations</p>
                            @if($orgs && count($orgs))<div class="badge-cloud">@foreach($orgs as $o)<span class="tag tag-purple">{{ $o }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">None provided</span></div>@endif
                        </div>
                        <div>
                            <p class="section-title"><i class="fas fa-running"></i> Co-Curricular Activities</p>
                            @if($activities && count($activities))<div class="badge-cloud">@foreach($activities as $a)<span class="tag tag-green">{{ $a }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">None provided</span></div>@endif
                        </div>
                    </div>
                    @if($reasons && count($reasons))
                        <p class="section-title"><i class="fas fa-lightbulb"></i> Why MSU-IIT?</p>
                        <div class="badge-cloud mb-4">@foreach($reasons as $r)<span class="tag tag-maroon">{{ $r }}</span>@endforeach</div>
                    @endif
                    @if($student->academicData->future_career_plans)
                        <p class="section-title"><i class="fas fa-road"></i> Future Career Plans</p>
                        <div class="text-block mb-4">{{ $student->academicData->future_career_plans }}</div>
                    @endif
                    @if($student->academicData->course_choice_reason)
                        <p class="section-title"><i class="fas fa-comment-dots"></i> Reason for Course Choice</p>
                        <div class="text-block">{{ $student->academicData->course_choice_reason }}</div>
                    @endif
                    @else
                        <div class="empty-state"><i class="fas fa-graduation-cap"></i>No academic data available</div>
                    @endif
                </div>

                <!-- ── LEARNING TAB ── -->
                <div class="tab-pane" id="learning">
                    @if($student->learningResources)
                    @php
                        $gadgets = $student->learningResources->technology_gadgets;
                        if (is_string($gadgets)) { $d = json_decode($gadgets, true); $gadgets = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $gadgets))); }
                        $connectivity = $student->learningResources->internet_connectivity;
                        if (is_string($connectivity)) { $d = json_decode($connectivity, true); $connectivity = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $connectivity))); }
                        $internetAccess = $student->learningResources->internet_access;
                        $readiness = $student->learningResources->distance_learning_readiness;
                        $internetColor = match(true) { str_contains(strtolower($internetAccess ?? ''), 'no') => 'tag-red', str_contains(strtolower($internetAccess ?? ''), 'limited') => 'tag-gold', default => 'tag-green' };
                        $readinessColor = match(true) { str_contains(strtolower($readiness ?? ''), 'fully') => 'tag-green', str_contains(strtolower($readiness ?? ''), 'not') => 'tag-red', str_contains(strtolower($readiness ?? ''), 'little') => 'tag-gold', default => 'tag-blue' };
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                        <div style="padding:1rem;background:var(--bg-warm);border:1px solid var(--border-soft);border-radius:0.6rem;">
                            <div class="data-cell-label mb-1">Internet Access</div>
                            @if($internetAccess)<span class="tag {{ $internetColor }}">{{ $internetAccess }}</span>@else<span class="text-xs text-[var(--text-muted)]">Not provided</span>@endif
                        </div>
                        <div style="padding:1rem;background:var(--bg-warm);border:1px solid var(--border-soft);border-radius:0.6rem;">
                            <div class="data-cell-label mb-1">Distance Learning Readiness</div>
                            @if($readiness)<span class="tag {{ $readinessColor }}">{{ $readiness }}</span>@else<span class="text-xs text-[var(--text-muted)]">Not provided</span>@endif
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="section-title"><i class="fas fa-mobile-screen"></i> Technology Gadgets</p>
                            @if($gadgets && count($gadgets))<div class="badge-cloud">@foreach($gadgets as $g)<span class="tag tag-blue"><i class="fas fa-mobile-alt" style="font-size:0.6rem;"></i>{{ $g }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">None provided</span></div>@endif
                        </div>
                        <div>
                            <p class="section-title"><i class="fas fa-wifi"></i> Internet Connectivity</p>
                            @if($connectivity && count($connectivity))<div class="badge-cloud">@foreach($connectivity as $c)<span class="tag tag-green">{{ $c }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">None provided</span></div>@endif
                        </div>
                    </div>
                    @if($student->learningResources->learning_space_description)
                        <p class="section-title"><i class="fas fa-chalkboard"></i> Learning Space</p>
                        <div class="text-block">{{ $student->learningResources->learning_space_description }}</div>
                    @endif
                    @else
                        <div class="empty-state"><i class="fas fa-laptop"></i>No learning resources data available</div>
                    @endif
                </div>

                <!-- ── PSYCHOSOCIAL TAB ── -->
                <div class="tab-pane" id="psychosocial">
                    @if($student->psychosocialData)
                    @php
                        $personality = $student->psychosocialData->personality_characteristics;
                        if (is_string($personality)) { $d = json_decode($personality, true); $personality = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $personality))); }
                        $coping = $student->psychosocialData->coping_mechanisms;
                        if (is_string($coping)) { $d = json_decode($coping, true); $coping = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $coping))); }
                        $sharing = $student->psychosocialData->problem_sharing_targets;
                        if (is_string($sharing)) { $d = json_decode($sharing, true); $sharing = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $sharing))); }
                    @endphp
                    @if($student->psychosocialData->needs_immediate_counseling)
                    <div class="alert alert-danger mb-4"><i class="fas fa-exclamation-triangle"></i><strong>Needs Immediate Counseling</strong></div>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                        <div style="padding:1rem;background:var(--bg-warm);border:1px solid var(--border-soft);border-radius:0.6rem;text-align:center;">
                            <div class="data-cell-label mb-1">Had Counseling Before</div>
                            @if($student->psychosocialData->had_counseling_before)<span class="status-yes">Yes</span>@else<span class="status-no">No</span>@endif
                        </div>
                        <div style="padding:1rem;background:var(--bg-warm);border:1px solid var(--border-soft);border-radius:0.6rem;text-align:center;">
                            <div class="data-cell-label mb-1">Sought Psychologist Help</div>
                            @if($student->psychosocialData->sought_psychologist_help)<span class="status-yes">Yes</span>@else<span class="status-no">No</span>@endif
                        </div>
                        <div style="padding:1rem;background:var(--bg-warm);border:1px solid var(--border-soft);border-radius:0.6rem;text-align:center;">
                            <div class="data-cell-label mb-1">Needs Immediate Counseling</div>
                            @if($student->psychosocialData->needs_immediate_counseling)<span class="status-danger">Yes</span>@else<span class="status-no">No</span>@endif
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="section-title"><i class="fas fa-person-rays"></i> Personality Characteristics</p>
                            @if($personality && count($personality))<div class="badge-cloud">@foreach($personality as $p)<span class="tag tag-blue">{{ $p }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">Not provided</span></div>@endif
                        </div>
                        <div>
                            <p class="section-title"><i class="fas fa-heart-pulse"></i> Coping Mechanisms</p>
                            @if($coping && count($coping))<div class="badge-cloud">@foreach($coping as $c)<span class="tag tag-green">{{ $c }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">Not provided</span></div>@endif
                        </div>
                    </div>
                    @if($sharing && count($sharing))
                        <p class="section-title"><i class="fas fa-comments"></i> Problem Sharing Targets</p>
                        <div class="badge-cloud mb-4">@foreach($sharing as $s)<span class="tag tag-gold">{{ $s }}</span>@endforeach</div>
                    @endif
                    @if($student->psychosocialData->mental_health_perception)
                        <p class="section-title"><i class="fas fa-brain"></i> Mental Health Perception</p>
                        <div class="text-block mb-4">{{ $student->psychosocialData->mental_health_perception }}</div>
                    @endif
                    @if($student->psychosocialData->future_counseling_concerns)
                        <p class="section-title"><i class="fas fa-comment-medical"></i> Counseling Concerns</p>
                        <div class="text-block">{{ $student->psychosocialData->future_counseling_concerns }}</div>
                    @endif
                    @else
                        <div class="empty-state"><i class="fas fa-brain"></i>No psychosocial data available</div>
                    @endif
                </div>

                <!-- ── NEEDS ASSESSMENT TAB ── -->
                <div class="tab-pane" id="needs">
                    @if($student->needsAssessment)
                    @php
                        $improvement = $student->needsAssessment->improvement_needs;
                        if (is_string($improvement)) { $d = json_decode($improvement, true); $improvement = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $improvement))); }
                        $financial = $student->needsAssessment->financial_assistance_needs;
                        if (is_string($financial)) { $d = json_decode($financial, true); $financial = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $financial))); }
                        $social = $student->needsAssessment->personal_social_needs;
                        if (is_string($social)) { $d = json_decode($social, true); $social = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $social))); }
                        $stress = $student->needsAssessment->stress_responses;
                        if (is_string($stress)) { $d = json_decode($stress, true); $stress = is_array($d) ? $d : array_filter(array_map('trim', explode(',', $stress))); }
                        $perceptions = $student->needsAssessment->counseling_perceptions;
                        if (is_string($perceptions)) { $d = json_decode($perceptions, true); $perceptions = is_array($d) ? $d : []; }
                        $statements = [
                            'I willfully came for counseling when I had a problem.',
                            'I experienced counseling upon referral by teachers, friends, parents, etc.',
                            'I know that help is available at the Guidance and Counseling Center of MSU-IIT.',
                            'I am afraid to go to the Guidance and Counseling Center of MSU-IIT.',
                            'I am shy to ask assistance/seek counseling from my guidance counselor.'
                        ];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
                        <div>
                            <p class="section-title"><i class="fas fa-arrow-up-right-dots"></i> Needs to Improve</p>
                            @if($improvement && count($improvement))<div class="badge-cloud">@foreach($improvement as $n)<span class="tag tag-blue">{{ $n }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">Not provided</span></div>@endif
                        </div>
                        <div>
                            <p class="section-title"><i class="fas fa-hand-holding-dollar"></i> Financial Assistance</p>
                            @if($financial && count($financial))<div class="badge-cloud">@foreach($financial as $n)<span class="tag tag-gold">{{ $n }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">Not provided</span></div>@endif
                        </div>
                        <div>
                            <p class="section-title"><i class="fas fa-people-group"></i> Personal-Social Needs</p>
                            @if($social && count($social))<div class="badge-cloud">@foreach($social as $n)<span class="tag tag-green">{{ $n }}</span>@endforeach</div>
                            @else<div class="empty-state" style="padding:0.5rem 0;text-align:left;"><span class="text-xs text-[var(--text-muted)]">Not provided</span></div>@endif
                        </div>
                    </div>
                    @if($stress && count($stress))
                        <p class="section-title"><i class="fas fa-triangle-exclamation"></i> Stress Responses</p>
                        <div class="badge-cloud mb-5">@foreach($stress as $s)<span class="tag tag-red">{{ $s }}</span>@endforeach</div>
                    @endif
                    <p class="section-title"><i class="fas fa-comment-dots"></i> Counseling Perceptions</p>
                    <div style="border:1px solid var(--border-soft);border-radius:0.5rem;overflow:hidden;">
                        @foreach($statements as $index => $statement)
                        @php $value = $perceptions[$index] ?? $perceptions[$statement] ?? null; @endphp
                        <div style="display:flex;align-items:flex-start;gap:0.75rem;padding:0.65rem 0.85rem;border-bottom:1px solid rgba(229,224,219,0.5);{{ $loop->last ? 'border-bottom:none;' : '' }}">
                            <div style="width:1.5rem;height:1.5rem;border-radius:50%;background:rgba(122,42,42,0.08);color:var(--maroon-700);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;flex-shrink:0;margin-top:0.1rem;">{{ $index + 1 }}</div>
                            <div style="flex:1;min-width:0;">
                                <p style="margin:0 0 0.2rem;font-size:0.78rem;color:var(--text-primary);line-height:1.4;">{{ $statement }}</p>
                                @if($value)<span class="tag tag-maroon" style="font-size:0.68rem;">{{ $value }}</span>@else<span style="font-size:0.7rem;color:var(--text-muted);">Not provided</span>@endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($student->needsAssessment->easy_discussion_target)
                        <div class="info-row mt-4">
                            <div class="info-row-icon"><i class="fas fa-comments"></i></div>
                            <div><div class="info-row-label">Easy Discussion Target</div><div class="info-row-value">{{ $student->needsAssessment->easy_discussion_target }}</div></div>
                        </div>
                    @endif
                    @else
                        <div class="empty-state"><i class="fas fa-notes-medical"></i>No needs assessment data available</div>
                    @endif
                </div>

            </div><!-- /.info-card-body -->
        </div><!-- /.info-card tabs -->

        <!-- Print Footer -->
        <div class="print-footer">Student Profile Report • {{ $student->full_name }} • Generated: {{ date('F j, Y g:i A') }} • Office of Guidance and Counseling</div>

    </div><!-- /.max-w-7xl -->
</div><!-- /.ogc-shell -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });
});

function toggleHighRiskModal() {
    const modal = document.getElementById('highRiskModal');
    modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
}

function submitHighRiskForm() {
    const isHighRisk = document.getElementById('is_high_risk').checked;
    const notes = document.getElementById('high_risk_notes').value;
    const btn = document.getElementById('submitHighRiskBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    const submitUrl = '{{ Auth::user()->role === "admin" ? route("admin.students.toggle-high-risk", $student) : route("counselor.students.toggle-high-risk", $student) }}';
    
    fetch(submitUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ is_high_risk: isHighRisk, high_risk_notes: notes })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) location.reload();
        else { alert('Error: ' + (data.message || 'Failed')); btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Save Changes'; }
    })
    .catch(() => { alert('An error occurred.'); btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Save Changes'; });
}
</script>

@if(Auth::check() && in_array(Auth::user()->role, ['counselor', 'admin']))
<div id="highRiskModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:white;border-radius:0.75rem;max-width:500px;width:100%;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);overflow:hidden;">
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border-soft);display:flex;align-items:center;justify-content:space-between;background:rgba(250,248,245,0.6);">
            <h3 style="margin:0;font-size:0.95rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:0.5rem;">
                <i class="fas fa-exclamation-triangle" style="color:#dc2626;"></i> High-Risk Status
            </h3>
            <button onclick="toggleHighRiskModal()" style="background:none;border:none;font-size:1.2rem;color:var(--text-muted);cursor:pointer;width:2rem;height:2rem;display:flex;align-items:center;justify-content:center;border-radius:999px;" onmouseover="this.style.background='rgba(254,249,231,0.8)'" onmouseout="this.style.background='none'">&times;</button>
        </div>
        <div style="padding:1.25rem;">
            <div style="margin-bottom:1rem;">
                <label style="display:flex;align-items:center;gap:0.6rem;cursor:pointer;font-size:0.85rem;font-weight:600;color:var(--text-primary);">
                    <input type="checkbox" id="is_high_risk" {{ $student->is_high_risk ? 'checked' : '' }} style="width:1rem;height:1rem;accent-color:var(--maroon-700);">
                    Flag this student as high-risk
                </label>
            </div>
            <div>
                <label style="display:block;font-size:0.7rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.4rem;">Notes (optional)</label>
                <textarea id="high_risk_notes" rows="3" style="width:100%;border:1px solid var(--border-soft);border-radius:0.5rem;padding:0.6rem 0.75rem;font-size:0.8rem;color:var(--text-primary);outline:none;resize:vertical;" placeholder="Add context or observations...">{{ $student->high_risk_notes }}</textarea>
            </div>
        </div>
        <div style="padding:0.85rem 1.25rem;border-top:1px solid var(--border-soft);display:flex;justify-content:flex-end;gap:0.5rem;background:rgba(250,248,245,0.4);">
            <button onclick="toggleHighRiskModal()" class="secondary-btn" style="padding:0.45rem 0.9rem;font-size:0.78rem;">Cancel</button>
            <button id="submitHighRiskBtn" onclick="submitHighRiskForm()" class="primary-btn" style="padding:0.45rem 0.9rem;font-size:0.78rem;">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>
</div>
@endif

@endsection
