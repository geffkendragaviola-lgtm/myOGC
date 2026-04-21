@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

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

    .dash-shell { position:relative; overflow:hidden; background:var(--bg-warm); min-height:100vh; }
    .dash-glow { position:absolute; border-radius:50%; filter:blur(80px); pointer-events:none; opacity:0.22; }
    .dash-glow.one { top:-40px; left:-50px; width:240px; height:240px; background:var(--gold-400); }
    .dash-glow.two { bottom:-40px; right:-60px; width:260px; height:260px; background:var(--maroon-800); }

    /* Cards */
    .hero-card, .panel-card, .stat-card, .quick-card {
        position:relative; overflow:hidden; border-radius:0.75rem;
        border:1px solid var(--border-soft); background:rgba(255,255,255,0.95);
        backdrop-filter:blur(8px); box-shadow:0 2px 8px rgba(44,36,32,0.04);
        transition:box-shadow 0.2s ease, transform 0.2s ease;
    }
    .hero-card::before, .panel-card::before, .stat-card::before, .quick-card::before {
        content:""; position:absolute; inset:0; pointer-events:none;
        background:radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .panel-card:hover, .stat-card:hover { box-shadow:0 4px 14px rgba(44,36,32,0.07); }
    .quick-card:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(44,36,32,0.08); border-color:rgba(212,175,55,0.35); }

    /* Hero */
    .hero-icon {
        width:2.75rem; height:2.75rem; border-radius:0.75rem; color:#fef9e7; flex-shrink:0;
        background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow:0 4px 12px rgba(92,26,26,0.15); display:flex; align-items:center; justify-content:center;
    }
    .hero-badge {
        display:inline-flex; align-items:center; gap:0.4rem; border-radius:999px;
        border:1px solid rgba(212,175,55,0.3); background:rgba(254,249,231,0.8);
        padding:0.2rem 0.55rem; font-size:9px; font-weight:700; text-transform:uppercase;
        letter-spacing:0.16em; color:var(--maroon-700);
    }
    .hero-badge-dot { width:0.3rem; height:0.3rem; border-radius:999px; background:var(--gold-400); }

    /* Summary card (dark maroon) */
    .summary-card {
        position:relative; overflow:hidden; border-radius:0.75rem;
        border:1px solid rgba(92,26,26,0.15);
        background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%);
        color:white; box-shadow:0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content:""; position:absolute; inset:0; opacity:0.15;
        background:radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events:none;
    }
    .summary-icon {
        width:2.5rem; height:2.5rem; border-radius:0.75rem; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.1); color:#fef9e7;
    }
    .summary-label { font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:0.2em; color:rgba(255,255,255,0.7); }
    .summary-value { font-size:1.5rem; line-height:1; font-weight:800; margin-top:0.3rem; }
    .summary-sub { font-size:0.7rem; color:rgba(255,255,255,0.75); margin-top:0.2rem; }

    /* Stat cards */
    .stat-icon {
        width:2.5rem; height:2.5rem; border-radius:0.75rem; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
    }
    .stat-value { font-size:1.6rem; font-weight:600; color:var(--text-primary); line-height:1; }
    .stat-label { font-size:0.7rem; font-weight:500; text-transform:uppercase; letter-spacing:0.1em; color:var(--text-muted); margin-top:0.15rem; }
    .stat-bar { height:3px; border-radius:999px; margin-top:0.85rem; background:var(--border-soft); overflow:hidden; }
    .stat-bar-fill { height:100%; border-radius:999px; }

    /* Panel */
    .panel-topline { position:absolute; inset-inline:0; top:0; height:3px; background:linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display:flex; align-items:center; gap:0.7rem; padding:0.85rem 1.25rem; border-bottom:1px solid rgba(229,224,219,0.6); }
    .panel-icon { width:2rem; height:2rem; border-radius:0.6rem; display:flex; align-items:center; justify-content:center; background:rgba(254,249,231,0.7); color:var(--maroon-700); flex-shrink:0; }
    .panel-title { font-size:0.8rem; font-weight:600; color:var(--text-primary); }
    .panel-subtitle { font-size:0.68rem; color:var(--text-muted); margin-top:0.1rem; }

    /* Quick access */
    .quick-icon {
        width:2.5rem; height:2.5rem; border-radius:0.65rem; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        transition:background 0.2s, color 0.2s;
    }

    /* Table */
    .table-row { transition:background 0.15s; }
    .table-row:hover { background:rgba(254,249,231,0.35); }
    .avatar-badge {
        width:2.25rem; height:2.25rem; border-radius:0.65rem; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
        font-weight:700; font-size:0.7rem; color:var(--maroon-700);
        background:linear-gradient(135deg,#fef9e7,#f5e6b8); border:1px solid rgba(212,175,55,0.3);
    }
    .table-footer { padding:0.75rem 1.25rem; border-top:1px solid rgba(229,224,219,0.6); background:rgba(250,248,245,0.4); }

    @media(max-width:639px){
        .stat-value { font-size:1.35rem; }
        .panel-header { padding:0.75rem 1rem; }
    }
</style>

<div class="dash-shell">
    <div class="dash-glow one"></div>
    <div class="dash-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8 space-y-5 sm:space-y-6">

        {{-- Page header --}}
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex items-start gap-3">
                    <div class="hero-icon"><i class="fas fa-gauge-high text-base sm:text-lg"></i></div>
                    <div class="min-w-0">
                        <div class="hero-badge"><span class="hero-badge-dot"></span>Admin Panel</div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Dashboard</h1>
                        <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                System operational &mdash; {{ now()->format('l, F j, Y') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="summary-card">
                <div class="relative h-full flex items-center gap-3 px-5 py-4">
                    <div class="summary-icon"><i class="fas fa-calendar-days text-sm"></i></div>
                    <div>
                        <p class="summary-label">This Month</p>
                        <p class="summary-value">{{ $stats['total_events'] ?? 0 }}</p>
                        <p class="summary-sub">Total events in system</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stat cards --}}
        @php
            $cards = [
                ['label'=>'Total Users',  'value'=>$stats['total_users'],      'icon'=>'fa-users',          'bg'=>'background:linear-gradient(135deg,#7a2a2a,#5c1a1a);color:#fef9e7;', 'bar'=>'background:linear-gradient(90deg,#7a2a2a,#d4af37);', 'w'=>'100%'],
                ['label'=>'Students',     'value'=>$stats['total_students'],   'icon'=>'fa-user-graduate',  'bg'=>'background:rgba(254,249,231,0.8);color:#7a5a1a;',                  'bar'=>'background:linear-gradient(90deg,#c9a227,#f0cd63);',  'w'=>'75%'],
                ['label'=>'Counselors',   'value'=>$stats['total_counselors'], 'icon'=>'fa-user-doctor',    'bg'=>'background:rgba(240,253,244,0.8);color:#065f46;',                  'bar'=>'background:linear-gradient(90deg,#2d7a4f,#4ade80);',  'w'=>'60%'],
                ['label'=>'Admins',       'value'=>$stats['total_admins'],     'icon'=>'fa-shield-halved',  'bg'=>'background:rgba(245,240,235,0.8);color:#6b5e57;',                  'bar'=>'background:linear-gradient(90deg,#8b7e76,#c4b8b1);',  'w'=>'40%'],
                ['label'=>'Active Events','value'=>$stats['active_events'],    'icon'=>'fa-calendar-days',  'bg'=>'background:rgba(254,249,231,0.8);color:#7a2a2a;',                  'bar'=>'background:linear-gradient(90deg,#d4af37,#f0cd63);',  'w'=>'55%'],
            ];
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            @foreach($cards as $c)
            <div class="stat-card p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="stat-label">{{ $c['label'] }}</p>
                        <p class="stat-value mt-1">{{ number_format($c['value']) }}</p>
                    </div>
                    <div class="stat-icon" style="{{ $c['bg'] }}">
                        <i class="fas {{ $c['icon'] }} text-sm"></i>
                    </div>
                </div>
                <div class="stat-bar">
                    <div class="stat-bar-fill" style="width:{{ $c['w'] }};{{ $c['bar'] }}"></div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Counseling analytics cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            {{-- Total Appointments --}}
            <div class="stat-card p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="stat-label">Appointments</p>
                        <p class="stat-value mt-1" style="font-weight:600;">{{ number_format($stats['total_appointments']) }}</p>
                    </div>
                    <div class="stat-icon" style="background:#7a2a2a;color:#fff;">
                        <i class="fas fa-calendar-check text-sm"></i>
                    </div>
                </div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:100%;background:#7a2a2a;"></div></div>
            </div>

            {{-- Completion Rate --}}
            <div class="stat-card p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="stat-label">Completion Rate</p>
                        <p class="stat-value mt-1" style="font-weight:600;color:#2d7a4f;">{{ $stats['completion_rate'] }}%</p>
                    </div>
                    <div class="stat-icon" style="background:#ecfdf5;color:#2d7a4f;border:1px solid #d1fae5;">
                        <i class="fas fa-circle-check text-sm"></i>
                    </div>
                </div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ $stats['completion_rate'] }}%;background:#2d7a4f;"></div></div>
            </div>

            {{-- Pending / Awaiting --}}
            <div class="stat-card p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="stat-label">Pending</p>
                        <p class="stat-value mt-1" style="font-weight:600;color:#b45309;">{{ number_format($stats['pending_count']) }}</p>
                    </div>
                    <div class="stat-icon" style="background:#fef3c7;color:#b45309;border:1px solid #fde68a;">
                        <i class="fas fa-hourglass-half text-sm"></i>
                    </div>
                </div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ $stats['total_appointments'] > 0 ? round(($stats['pending_count']/$stats['total_appointments'])*100) : 0 }}%;background:#d97706;"></div></div>
            </div>

            {{-- No-Show Rate --}}
            <div class="stat-card p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="stat-label">No-Show Rate</p>
                        <p class="stat-value mt-1" style="font-weight:600;color:#b91c1c;">{{ $stats['no_show_rate'] }}%</p>
                    </div>
                    <div class="stat-icon" style="background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;">
                        <i class="fas fa-user-xmark text-sm"></i>
                    </div>
                </div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ $stats['no_show_rate'] }}%;background:#dc2626;"></div></div>
            </div>

            {{-- Avg Satisfaction --}}
            <div class="stat-card p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="stat-label">Avg Satisfaction</p>
                        <p class="stat-value mt-1" style="font-weight:600;color:#c9a227;">
                            {{ $stats['avg_satisfaction'] !== null ? $stats['avg_satisfaction'].'/5' : 'N/A' }}
                        </p>
                    </div>
                    <div class="stat-icon" style="background:#fef9e7;color:#c9a227;border:1px solid #f5e6b8;">
                        <i class="fas fa-star text-sm"></i>
                    </div>
                </div>
                @php $satPct = $stats['avg_satisfaction'] !== null ? ($stats['avg_satisfaction']/5)*100 : 0; @endphp
                <div class="stat-bar"><div class="stat-bar-fill" style="width:{{ $satPct }}%;background:#c9a227;"></div></div>
            </div>

            {{-- Follow-up Required --}}
            <div class="stat-card p-4">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="stat-label">Follow-ups Due</p>
                        <p class="stat-value mt-1" style="font-weight:600;color:#5c1a1a;">{{ number_format($stats['follow_up_count']) }}</p>
                    </div>
                    <div class="stat-icon" style="background:#f5e6e8;color:#5c1a1a;border:1px solid #e5d0d3;">
                        <i class="fas fa-rotate-right text-sm"></i>
                    </div>
                </div>
                <div class="stat-bar"><div class="stat-bar-fill" style="width:60%;background:#5c1a1a;"></div></div>
            </div>
        </div>

        {{-- Appointments by College mini-panel --}}
        @if($appointmentsByCollege->count())
        <div class="panel-card overflow-hidden">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-building-columns text-xs"></i></div>
                <div>
                    <p class="panel-title">Appointments by College</p>
                    <p class="panel-subtitle hidden sm:block">Top colleges by counseling load</p>
                </div>
                <a href="{{ route('admin.analytics') }}" class="ml-auto text-[10px] font-semibold text-[#7a2a2a] hover:text-[#5c1a1a] transition flex items-center gap-1">
                    Full analytics <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>
            <div class="p-4 space-y-2.5">
                @php $maxAppts = $appointmentsByCollege->max('total') ?: 1; @endphp
                @foreach($appointmentsByCollege as $row)
                <div class="flex items-center gap-3">
                    <span class="text-[11px] text-[#6b5e57] truncate" style="min-width:0;flex:1;">{{ $row->college_name }}</span>
                    <div class="flex-shrink-0" style="width:45%;">
                        <div style="height:6px;border-radius:999px;background:#e5e0db;overflow:hidden;">
                            <div style="height:100%;border-radius:999px;width:{{ round(($row->total/$maxAppts)*100) }}%;background:#7a2a2a;"></div>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-[#2c2420] flex-shrink-0" style="min-width:2rem;text-align:right;">{{ $row->total }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Two-column layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:gap-6">

            {{-- Quick access --}}
            <div class="lg:col-span-1 space-y-3">
                <div class="panel-card">
                    <div class="panel-topline"></div>
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-bolt text-xs"></i></div>
                        <div>
                            <p class="panel-title">Quick Access</p>
                            <p class="panel-subtitle hidden sm:block">Common admin actions</p>
                        </div>
                    </div>
                    <div class="p-3 space-y-2">
                        @php
                            $quickLinks = [
                                ['href'=>route('admin.users.create'),  'icon'=>'fa-user-plus',     'label'=>'Add New User',      'sub'=>'Create a new account',          'ic_bg'=>'background:rgba(253,242,242,0.8);color:#7a2a2a;', 'ic_hover'=>'background:#7a2a2a;color:#fef9e7;'],
                                ['href'=>route('admin.students'),      'icon'=>'fa-user-graduate', 'label'=>'Student Records',   'sub'=>'Browse all students',           'ic_bg'=>'background:rgba(254,249,231,0.8);color:#7a5a1a;', 'ic_hover'=>'background:#c9a227;color:#3a0c0c;'],
                                ['href'=>route('admin.counselors'),    'icon'=>'fa-user-doctor',   'label'=>'Counselors',        'sub'=>'View counselor directory',      'ic_bg'=>'background:rgba(240,253,244,0.8);color:#065f46;', 'ic_hover'=>'background:#2d7a4f;color:#fff;'],
                                ['href'=>route('admin.appointments'),  'icon'=>'fa-calendar-check','label'=>'Appointments',      'sub'=>'Manage all appointments',       'ic_bg'=>'background:rgba(245,240,235,0.8);color:#6b5e57;', 'ic_hover'=>'background:#5c1a1a;color:#fef9e7;'],
                                ['href'=>route('admin.analytics'),     'icon'=>'fa-chart-column',  'label'=>'Analytics',         'sub'=>'View system analytics',         'ic_bg'=>'background:rgba(254,249,231,0.8);color:#7a2a2a;', 'ic_hover'=>'background:#d4af37;color:#3a0c0c;'],
                            ];
                        @endphp
                        @foreach($quickLinks as $ql)
                        <a href="{{ $ql['href'] }}"
                           class="quick-card flex items-center gap-3 p-3 group"
                           style="text-decoration:none;">
                            <div class="quick-icon" style="{{ $ql['ic_bg'] }}" data-hover="{{ $ql['ic_hover'] }}">
                                <i class="fas {{ $ql['icon'] }} text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-[#2c2420] group-hover:text-[#7a2a2a] transition truncate">{{ $ql['label'] }}</p>
                                <p class="text-[10px] text-[#8b7e76] truncate">{{ $ql['sub'] }}</p>
                            </div>
                            <i class="fas fa-chevron-right text-[#c4b8b1] text-[10px] group-hover:text-[#7a2a2a] group-hover:translate-x-0.5 transition-all flex-shrink-0"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Recent registrations --}}
            <div class="lg:col-span-2">
                <div class="panel-card overflow-hidden h-full flex flex-col">
                    <div class="panel-topline"></div>
                    <div class="panel-header">
                        <div class="panel-icon"><i class="fas fa-users text-xs"></i></div>
                        <div>
                            <p class="panel-title">Recent Registrations</p>
                            <p class="panel-subtitle hidden sm:block">Latest accounts created in the system</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto flex-1">
                        <table class="w-full min-w-[520px]">
                            <thead>
                                <tr style="background:rgba(250,248,245,0.8);">
                                    <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-[#8b7e76] uppercase tracking-wider">User</th>
                                    <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-[#8b7e76] uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-2.5 text-left text-[10px] font-semibold text-[#8b7e76] uppercase tracking-wider">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e5e0db]/50">
                                @forelse($recentUsers as $user)
                                <tr class="table-row">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2.5">
                                            <div class="avatar-badge">
                                                {{ strtoupper(substr($user->first_name,0,1)) }}{{ strtoupper(substr($user->last_name,0,1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-semibold text-[#2c2420] truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                                                <p class="text-[10px] text-[#8b7e76] truncate max-w-[160px]">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @php
                                            $roleStyle = match($user->role) {
                                                'admin'     => 'background:#fdf2f2;color:#b91c1c;border:1px solid rgba(185,28,28,0.2);',
                                                'counselor' => 'background:#fffbeb;color:#7a5a1a;border:1px solid rgba(212,175,55,0.3);',
                                                default     => 'background:#ecfdf5;color:#059669;border:1px solid rgba(16,185,129,0.25);',
                                            };
                                            $roleIcon = match($user->role) {
                                                'admin'     => 'fa-shield-halved',
                                                'counselor' => 'fa-user-doctor',
                                                default     => 'fa-user-graduate',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold" style="{{ $roleStyle }}">
                                            <i class="fas {{ $roleIcon }} text-[9px]"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-[10px] text-[#8b7e76]">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-10 text-center">
                                        <div class="flex flex-col items-center gap-2 text-[#8b7e76]">
                                            <i class="fas fa-inbox text-2xl opacity-30"></i>
                                            <p class="text-xs">No recent users found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer flex items-center justify-between">
                        <span class="text-[10px] text-[#8b7e76]">
                            Showing <span class="font-semibold text-[#2c2420]">{{ $recentUsers->count() }}</span> recent users
                        </span>
                        <a href="{{ route('admin.users') }}"
                           class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-[#7a2a2a] hover:text-[#5c1a1a] transition">
                            View all <i class="fas fa-arrow-right text-[9px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent events row --}}
        @if(isset($recentEvents) && $recentEvents->count())
        <div class="panel-card overflow-hidden">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-calendar-days text-xs"></i></div>
                <div>
                    <p class="panel-title">Recent Events</p>
                    <p class="panel-subtitle hidden sm:block">Latest events added to the system</p>
                </div>
                <a href="{{ route('admin.events') }}" class="ml-auto text-[10px] font-semibold text-[#7a2a2a] hover:text-[#5c1a1a] transition flex items-center gap-1">
                    View all <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3">
                @foreach($recentEvents as $event)
                <div class="rounded-lg border border-[#e5e0db] bg-[#faf8f5]/60 p-3 hover:border-[rgba(212,175,55,0.4)] hover:bg-[rgba(254,249,231,0.4)] transition">
                    <p class="text-xs font-semibold text-[#2c2420] truncate">{{ $event->title }}</p>
                    <p class="text-[10px] text-[#8b7e76] mt-1 flex items-center gap-1">
                        <i class="fas fa-calendar-days text-[9px] text-[#c4b8b1]"></i>
                        {{ \Carbon\Carbon::parse($event->event_start_date)->format('M j, Y') }}
                    </p>
                    <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded-full text-[9px] font-semibold
                        {{ $event->is_active ? 'bg-[#ecfdf5] text-[#059669] border border-[#10b981]/25' : 'bg-[#f5f0eb] text-[#8b7e76] border border-[#e5e0db]' }}">
                        {{ $event->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
