@extends('layouts.app')
@section('title', 'Feedback - OGC')
@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c; --maroon-800: #5c1a1a; --maroon-700: #7a2a2a;
        --gold-500: #c9a227; --gold-400: #d4af37;
        --bg-warm: #faf8f5; --border-soft: #e5e0db;
        --text-primary: #2c2420; --text-secondary: #6b5e57; --text-muted: #8b7e76;
    }
    .feedback-shell { position:relative; overflow:hidden; background:var(--bg-warm); min-height:100vh; padding-bottom:2rem; }
    .feedback-glow { position:absolute; border-radius:50%; filter:blur(80px); pointer-events:none; opacity:0.25; }
    .feedback-glow.one { top:-30px; left:-40px; width:200px; height:200px; background:var(--gold-400); }
    .feedback-glow.two { bottom:-30px; right:-60px; width:220px; height:220px; background:var(--maroon-800); }

    .hero-card, .panel-card {
        position:relative; overflow:hidden; border-radius:0.75rem;
        border:1px solid var(--border-soft); background:rgba(255,255,255,0.95);
        backdrop-filter:blur(8px); box-shadow:0 2px 8px rgba(44,36,32,0.04);
        transition:box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover { box-shadow:0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before {
        content:""; position:absolute; inset:0; pointer-events:none;
        background:radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .hero-icon {
        display:flex; align-items:center; justify-content:center; flex-shrink:0;
        width:2.75rem; height:2.75rem; border-radius:0.75rem; color:#fef9e7;
        background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow:0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display:inline-flex; align-items:center; gap:0.4rem; border-radius:999px;
        border:1px solid rgba(212,175,55,0.3); background:rgba(254,249,231,0.8);
        padding:0.2rem 0.55rem; font-size:9px; font-weight:700; text-transform:uppercase;
        letter-spacing:0.16em; color:var(--maroon-700);
    }
    .hero-badge-dot { width:0.3rem; height:0.3rem; border-radius:999px; background:var(--gold-400); }
    .panel-topline { position:absolute; inset-inline:0; top:0; height:3px; background:linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }

    .field-label { display:block; font-size:0.65rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.08em; }
    .input-field, .select-field {
        width:100%; border:1px solid var(--border-soft); border-radius:0.6rem;
        background:rgba(255,255,255,0.9); color:var(--text-primary); outline:none;
        transition:all 0.2s ease; font-size:0.8rem; padding:0.55rem 0.75rem;
    }
    .input-field:focus, .select-field:focus { border-color:var(--maroon-700); box-shadow:0 0 0 3px rgba(92,26,26,0.08); }

    .primary-btn {
        border-radius:0.6rem; font-weight:600; transition:all 0.2s ease;
        display:inline-flex; align-items:center; justify-content:center; white-space:nowrap;
        color:#fef9e7; background:linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow:0 4px 10px rgba(92,26,26,0.15); font-size:0.8rem;
    }
    .primary-btn:hover { transform:translateY(-1px); box-shadow:0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        border-radius:0.6rem; font-weight:600; transition:all 0.2s ease;
        display:inline-flex; align-items:center; justify-content:center; white-space:nowrap;
        color:var(--text-secondary); background:rgba(255,255,255,0.9); border:1px solid var(--border-soft); font-size:0.8rem;
    }
    .secondary-btn:hover { background:rgba(254,249,231,0.7); border-color:var(--maroon-700); }
    .export-btn {
        border-radius:0.6rem; font-weight:600; transition:all 0.2s ease;
        display:inline-flex; align-items:center; justify-content:center; white-space:nowrap;
        color:#fff; background:linear-gradient(135deg,#059669,#047857);
        box-shadow:0 4px 10px rgba(5,150,105,0.15); font-size:0.8rem;
    }
    .export-btn:hover { transform:translateY(-1px); box-shadow:0 6px 14px rgba(5,150,105,0.2); }

    .stat-card {
        position:relative; overflow:hidden; border-radius:0.75rem;
        border:1px solid var(--border-soft); background:rgba(255,255,255,0.95);
        backdrop-filter:blur(8px); box-shadow:0 2px 8px rgba(44,36,32,0.04);
        padding:1rem 1.1rem; display:flex; align-items:center; gap:0.85rem;
    }
    .stat-card::before { content:""; position:absolute; inset:0; pointer-events:none; background:radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%); }
    .stat-icon-box {
        width:2.5rem; height:2.5rem; border-radius:0.65rem;
        display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;
    }
    .si-gray { background:rgba(122,42,42,0.08); color:var(--maroon-700); }
    .si-green { background:rgba(16,185,129,0.1); color:#047857; }
    .si-gold { background:rgba(212,175,55,0.12); color:var(--maroon-800); }
    .si-orange { background:rgba(234,88,12,0.1); color:#c2410c; }
    .stat-label { font-size:0.68rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:0.06em; }
    .stat-value { font-size:1.4rem; font-weight:800; color:var(--text-primary); line-height:1.1; }
    .stat-sub { font-size:0.72rem; color:var(--text-muted); }

    .custom-table { width:100%; border-collapse:separate; border-spacing:0; min-width:860px; }
    .custom-table thead th {
        background:rgba(250,248,245,0.8); color:var(--text-muted);
        font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em;
        padding:0.75rem 1rem; border-bottom:1px solid var(--border-soft); text-align:left;
    }
    .custom-table tbody td { padding:0.85rem 1rem; border-bottom:1px solid rgba(229,224,219,0.5); color:var(--text-secondary); font-size:0.8rem; vertical-align:middle; }
    .custom-table tbody tr:last-child td { border-bottom:none; }
    .custom-table tbody tr { transition:background 0.15s ease; cursor:pointer; }
    .custom-table tbody tr:hover { background:rgba(254,249,231,0.35); }

    .avatar-badge {
        flex-shrink:0; width:2.25rem; height:2.25rem; border-radius:0.6rem;
        display:flex; align-items:center; justify-content:center;
        font-size:0.7rem; font-weight:700; color:var(--maroon-700);
        background:rgba(254,249,231,0.6); border:1px solid rgba(212,175,55,0.3);
    }
    .avatar-anon { background:rgba(254,249,231,0.8); color:var(--maroon-700); }

    .action-icon {
        display:inline-flex; align-items:center; justify-content:center;
        width:1.75rem; height:1.75rem; border-radius:0.5rem;
        color:var(--text-secondary); transition:all 0.18s ease; font-size:0.75rem;
    }
    .action-icon:hover { transform:translateY(-1px); color:var(--maroon-700); background:rgba(254,249,231,0.6); }

    .empty-state { text-align:center; padding:2.5rem 1rem; color:var(--text-muted); }
    .empty-state-icon {
        width:3.5rem; height:3.5rem; border-radius:1rem;
        display:inline-flex; align-items:center; justify-content:center;
        background:rgba(254,249,231,0.7); color:var(--maroon-700);
        margin-bottom:0.75rem; font-size:1.1rem;
    }

    @media (max-width:639px) {
        .stat-value { font-size:1.2rem; }
        .custom-table thead th, .custom-table tbody td { padding:0.6rem 0.5rem; font-size:0.72rem; }
    }
</style>

<div class="min-h-screen feedback-shell">
    <div class="feedback-glow one"></div>
    <div class="feedback-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-message text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge"><span class="hero-badge-dot"></span>Student Insights</div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Student Feedback</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">View and manage all student feedback submissions.</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('counselor.feedback.export', request()->query()) }}" class="export-btn px-4 py-2 text-xs sm:text-sm">
                            <i class="fas fa-file-export mr-1.5 text-[9px] sm:text-xs"></i>Export CSV
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5 sm:mb-6">
            <div class="stat-card">
                <div class="stat-icon-box si-gray"><i class="fas fa-message"></i></div>
                <div><div class="stat-label">Total</div><div class="stat-value">{{ $stats['total'] }}</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box si-green"><i class="fas fa-star"></i></div>
                <div><div class="stat-label">Avg Rating</div><div class="stat-value">{{ $stats['average_rating'] }}<span class="text-sm font-normal text-[var(--text-muted)]">/5</span></div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box si-gold"><i class="fas fa-user-secret"></i></div>
                <div><div class="stat-label">Anonymous</div><div class="stat-value">{{ $stats['anonymous_count'] }}</div></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box si-orange"><i class="fas fa-chart-column"></i></div>
                <div>
                    <div class="stat-label">Distribution</div>
                    <div class="stat-sub truncate max-w-[130px]">
                        @foreach($stats['rating_distribution'] as $rating => $count)
                            {{ $rating }}★:{{ $count }}@if(!$loop->last) · @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <form method="GET" action="{{ route('counselor.feedback.index') }}" class="p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div>
                        <label class="field-label">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Student, service, comments..." class="input-field">
                    </div>
                    <div>
                        <label class="field-label">Rating</label>
                        <select name="rating" class="select-field">
                            <option value="">All Ratings</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} ★ — {{ \App\Models\Feedback::getRatingLabel($i) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Service</label>
                        <select name="service" class="select-field">
                            <option value="">All Services</option>
                            @foreach($serviceTypes as $service)
                                <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>{{ $service }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Date Range</label>
                        <select name="date_range" class="select-field">
                            <option value="">All Time</option>
                            <option value="today"  {{ request('date_range') == 'today'  ? 'selected' : '' }}>Today</option>
                            <option value="week"   {{ request('date_range') == 'week'   ? 'selected' : '' }}>This Week</option>
                            <option value="month"  {{ request('date_range') == 'month'  ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">Anonymous</label>
                        <select name="anonymous" class="select-field">
                            <option value="">All</option>
                            <option value="1" {{ request('anonymous') == '1' ? 'selected' : '' }}>Anonymous Only</option>
                            <option value="0" {{ request('anonymous') == '0' ? 'selected' : '' }}>Non-anonymous Only</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-3 gap-3">
                    <div class="text-[10px] sm:text-xs text-[#8b7e76]">
                        {{ $feedbacks->total() }} result{{ $feedbacks->total() !== 1 ? 's' : '' }}
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('counselor.feedback.index') }}" class="secondary-btn px-3 py-2 text-xs sm:text-sm">
                            <i class="fas fa-rotate-left mr-1 text-[9px]"></i>Reset
                        </a>
                        <button type="submit" class="primary-btn px-3 py-2 text-xs sm:text-sm">
                            <i class="fas fa-magnifying-glass mr-1 text-[9px]"></i>Apply
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="panel-card overflow-hidden">
            <div class="panel-topline"></div>
            <div class="overflow-x-auto">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="w-1/5">Student</th>
                            <th class="w-1/5">Service & Personnel</th>
                            <th class="w-1/4">Comments</th>
                            <th class="w-1/6">Submitted</th>
                            <th class="w-[5%] text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($feedbacks as $feedback)
                        <tr onclick="window.location='{{ route('counselor.feedback.show', $feedback) }}'">
                            <td>
                                <div class="flex items-center gap-2.5">
                                    <div class="avatar-badge {{ $feedback->is_anonymous ? 'avatar-anon' : '' }}">
                                        @if($feedback->is_anonymous)
                                            <i class="fas fa-user-secret text-xs"></i>
                                        @else
                                            {{ strtoupper(substr($feedback->user->first_name, 0, 1)) }}{{ strtoupper(substr($feedback->user->last_name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        @if($feedback->is_anonymous)
                                            <div class="text-xs font-semibold text-[#2c2420]">Anonymous</div>
                                            <div class="text-[10px] text-[#8b7e76]">{{ $feedback->user->student->college->name ?? 'N/A' }}</div>
                                        @else
                                            <div class="text-xs font-semibold text-[#2c2420] truncate max-w-[150px]">
                                                {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                            </div>
                                            <div class="text-[10px] text-[#8b7e76] truncate max-w-[150px]">
                                                {{ $feedback->user->student->college->name ?? 'N/A' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-xs font-medium text-[#2c2420]">{{ $feedback->service_availed }}</div>
                                <div class="text-[10px] text-[#8b7e76] mt-0.5">{{ $feedback->personnel_name ?? 'N/A' }}</div>
                            </td>
                            <td>
                                @if($feedback->comments)
                                    <span class="text-xs text-[#2c2420] line-clamp-2" style="max-width:260px;">{{ Str::limit($feedback->comments, 100) }}</span>
                                @else
                                    <span class="text-xs text-[#8b7e76] italic">No comments</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap">
                                <div class="text-xs font-medium text-[#2c2420]">{{ $feedback->created_at->format('M j, Y') }}</div>
                                <div class="text-[10px] text-[#8b7e76]">{{ $feedback->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="text-right" onclick="event.stopPropagation()">
                                <a href="{{ route('counselor.feedback.show', $feedback) }}" class="action-icon" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-message"></i></div>
                                    <p class="text-sm font-medium text-[#2c2420]">No feedback found.</p>
                                    <p class="text-xs text-[#8b7e76] mt-1">No submissions match your current filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($feedbacks->hasPages())
            <div class="px-4 sm:px-5 py-3 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                {{ $feedbacks->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
