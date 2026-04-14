@extends('layouts.admin')

@section('title', 'Feedback Management - Admin Panel')

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

    .feedback-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .feedback-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .feedback-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .feedback-glow.two { bottom: -40px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .stats-card, .glass-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .stats-card:hover, .glass-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .panel-card::before, .stats-card::before, .glass-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon, .stats-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
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
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.15rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .primary-btn, .secondary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        padding: 0.55rem 0.85rem;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .secondary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon, .table-header-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .panel-icon { background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .input-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    .flash-success { border-radius: 0.6rem; padding: 0.65rem 0.85rem; border-width: 1px; }

    .stats-card { transition: all 0.2s ease; padding: 0.85rem 1rem; }
    .stats-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(44,36,32,0.06); }
    .stats-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; }
    .mini-progress { width: 100%; background: #f5f0eb; border-radius: 999px; height: 0.3rem; overflow: hidden; margin-top: 0.6rem; }
    .mini-progress > div { height: 100%; border-radius: 999px; }

    .table-header-bar {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.6rem;
        padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.4);
    }
    .table-header-icon { background: rgba(254,249,231,0.6); }
    .table-live-pill {
        display: inline-flex; align-items: center; font-size: 0.65rem; color: var(--text-secondary);
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft); padding: 0.25rem 0.5rem;
        border-radius: 999px; font-weight: 500;
    }
    .table-row { transition: background-color 0.15s ease; }
    .table-row:hover { background: rgba(254,249,231,0.35); }

    .avatar-badge { width: 2.25rem; height: 2.25rem; border-radius: 999px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .service-pill { display: inline-flex; align-items: center; padding: 0.2rem 0.5rem; border-radius: 999px; background: rgba(245,240,235,0.7); color: var(--text-secondary); font-size: 0.7rem; font-weight: 600; border: 1px solid var(--border-soft); }
    .identity-pill { display: inline-flex; align-items: center; padding: 0.2rem 0.45rem; border-radius: 0.5rem; font-size: 0.65rem; font-weight: 600; }
    .empty-state-icon { width: 3rem; height: 3rem; border-radius: 999px; display: flex; align-items: center; justify-content: center; background: rgba(245,240,235,0.6); box-shadow: inset 0 1px 2px rgba(44,36,32,0.04); margin-inline: auto; }
    .action-link { transition: all 0.18s ease; display: inline-flex; align-items: center; justify-content: center; }
    .action-link:hover { transform: translateY(-1px); }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .stats-card { padding: 0.75rem; }
        .table-header-bar { padding: 0.65rem 1rem; }
        .space-x-3 > * + * { margin-left: 0; margin-top: 0.5rem; }
        .flex.justify-end { flex-direction: column; }
        .input-field, .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn, .secondary-btn { width: 100%; justify-content: center; }
    }
</style>

<div class="min-h-screen feedback-shell">
    <div class="feedback-glow one"></div>
    <div class="feedback-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-comments text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Feedback Management
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Feedback Management</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                View and manage all student feedback submissions across the system.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-file-export text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Quick Action</p>
                                <p class="summary-value">Export CSV</p>
                                <p class="summary-subtext hidden sm:block">Download the filtered feedback dataset instantly.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.feedback.export', request()->query()) }}"
                           class="primary-btn px-4 py-2.5 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-file-export mr-1.5 text-[9px] sm:text-xs"></i> Export
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="flash-success bg-[#ecfdf5] border-[#10b981]/30 text-[#059669] mb-4 sm:mb-6 flex items-center text-xs sm:text-sm">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Admin Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-6">
            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em]">Total Feedback</p>
                        <p class="text-xl sm:text-2xl font-semibold text-[#2c2420] mt-1.5">{{ $stats['total'] }}</p>
                    </div>
                    <div class="stats-icon bg-[#f5f0eb]">
                        <i class="fas fa-comments text-[#7a2a2a] text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="mini-progress">
                    <div class="bg-gradient-to-r from-[#7a2a2a] to-[#9a2a3a]" style="width: 100%"></div>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em]">Average Rating</p>
                        <p class="text-xl sm:text-2xl font-semibold text-[#2c2420] mt-1.5">{{ $stats['average_rating'] }}/5</p>
                    </div>
                    <div class="stats-icon bg-[#ecfdf5]">
                        <i class="fas fa-star text-[#059669] text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="mini-progress">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600" style="width: {{ min(100, ($stats['average_rating'] / 5) * 100) }}%"></div>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em]">Anonymous Feedback</p>
                        <p class="text-xl sm:text-2xl font-semibold text-[#2c2420] mt-1.5">{{ $stats['anonymous_count'] }}</p>
                    </div>
                    <div class="stats-icon bg-[#fffbeb]">
                        <i class="fas fa-user-secret text-[#7a2a2a] text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="mini-progress">
                    <div class="bg-gradient-to-r from-[#9a7b0a] to-[#c9a227]" style="width: {{ $stats['total'] > 0 ? ($stats['anonymous_count'] / $stats['total']) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] sm:text-[10px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em]">Rating Distribution</p>
                        <p class="text-xs sm:text-sm font-semibold text-[#2c2420] mt-1.5">
                            @foreach($stats['rating_distribution'] as $rating => $count)
                                {{ $rating }}★:{{ $count }}
                            @endforeach
                        </p>
                    </div>
                    <div class="stats-icon bg-[#fff7ed]">
                        <i class="fas fa-chart-pie text-[#c9a227] text-sm sm:text-base"></i>
                    </div>
                </div>
                <div class="mini-progress">
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600" style="width: 100%"></div>
                </div>
            </div>
        </div>

        <!-- Advanced Filters -->
        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon">
                    <i class="fas fa-sliders-h text-[9px] sm:text-xs"></i>
                </div>
                <div>
                    <h2 class="panel-title">Advanced Filters</h2>
                    <p class="panel-subtitle hidden sm:block">Refine feedback by search term, rating, service, date range, and anonymity.</p>
                </div>
            </div>

            <div class="p-3 sm:p-5">
                <form method="GET" action="{{ route('admin.feedback.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                    <!-- Search -->
                    <div>
                        <label class="field-label">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search by student, service, or comments..."
                               class="input-field text-xs sm:text-sm">
                    </div>

                    <!-- Rating Filter -->
                    <div>
                        <label class="field-label">Rating</label>
                        <select name="rating" class="select-field text-xs sm:text-sm">
                            <option value="">All Ratings</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} ★ - {{ \App\Models\Feedback::getRatingLabel($i) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Service Filter -->
                    <div>
                        <label class="field-label">Service</label>
                        <select name="service" class="select-field text-xs sm:text-sm">
                            <option value="">All Services</option>
                            @foreach($serviceTypes as $service)
                                <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>
                                    {{ $service }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date Range -->
                    <div>
                        <label class="field-label">Date Range</label>
                        <select name="date_range" class="select-field text-xs sm:text-sm">
                            <option value="">All Time</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>

                    <!-- Anonymous Filter -->
                    <div>
                        <label class="field-label">Anonymous</label>
                        <select name="anonymous" class="select-field text-xs sm:text-sm">
                            <option value="">All</option>
                            <option value="1" {{ request('anonymous') == '1' ? 'selected' : '' }}>Anonymous Only</option>
                            <option value="0" {{ request('anonymous') == '0' ? 'selected' : '' }}>Non-anonymous Only</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="sm:col-span-2 lg:col-span-5 flex justify-end gap-2 sm:gap-3 pt-1">
                        <button type="submit" class="secondary-btn px-4 py-2 sm:px-6 sm:py-2.5 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-filter mr-1.5 text-[9px] sm:text-xs"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.feedback.index') }}"
                           class="glass-card px-4 py-2 sm:px-6 sm:py-2.5 text-[#6b5e57] hover:bg-[#f5f0eb] transition rounded-lg inline-flex items-center text-xs sm:text-sm">
                            <i class="fas fa-redo mr-1.5 text-[9px] sm:text-xs"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Feedback Table -->
        <div class="panel-card overflow-hidden">
            <div class="table-header-bar">
                <div class="flex items-center gap-3">
                    <div class="table-header-icon">
                        <i class="fas fa-comments text-[#7a2a2a] text-[10px] sm:text-xs"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-[#2c2420]">All Feedback Submissions</h3>
                        <p class="text-[10px] sm:text-xs text-[#8b7e76]">
                            Showing {{ $feedbacks->firstItem() }} - {{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }} feedback entries
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="table-live-pill">
                        <i class="far fa-clock mr-1 text-[9px]"></i> Live data
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto -webkit-overflow-scrolling: touch;">
                <table class="w-full min-w-[950px]">
                    <thead class="bg-[#faf8f5]/85">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Student Information</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Service & Rating</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Comments</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Submission Details</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                        @forelse($feedbacks as $feedback)
                            <tr class="table-row">
                                <td class="px-4 sm:px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($feedback->is_anonymous)
                                            <div class="avatar-badge bg-[#fffbeb] flex-shrink-0">
                                                <i class="fas fa-user-secret text-[#7a2a2a] text-xs sm:text-sm"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-semibold text-[#2c2420]">Anonymous User</div>
                                                <div class="text-[10px] text-[#8b7e76]">Identity Protected</div>
                                            </div>
                                        @else
                                            <div class="avatar-badge bg-[#f5f0eb] flex-shrink-0">
                                                <i class="fas fa-user text-[#7a2a2a] text-xs sm:text-sm"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[160px]">
                                                    {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                                </div>
                                                <div class="text-[10px] text-[#8b7e76] truncate max-w-[160px]">{{ $feedback->user->email }}</div>
                                                @if($feedback->user->student)
                                                    <div class="text-[10px] text-[#a89f97] truncate max-w-[160px]">
                                                        {{ $feedback->user->student->student_id }} • {{ $feedback->user->student->college->name ?? 'N/A' }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 sm:px-6 py-4">
                                    <div class="mb-1.5">
                                        <span class="service-pill">{{ $feedback->service_availed }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="text-[#c9a227] text-xs">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $feedback->satisfaction_rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="text-xs sm:text-sm font-semibold text-[#2c2420]">{{ $feedback->satisfaction_rating }}/5</span>
                                    </div>
                                    <div class="text-[10px] text-[#8b7e76] mt-0.5">
                                        {{ \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating) }}
                                    </div>
                                </td>

                                <td class="px-4 sm:px-6 py-4">
                                    <div class="text-xs sm:text-sm text-[#4a3f3a] max-w-[150px] sm:max-w-xs leading-relaxed line-clamp-2">
                                        @if($feedback->comments)
                                            {{ Str::limit($feedback->comments, 120) }}
                                        @else
                                            <span class="text-[#a89f97] italic">No comments provided</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm font-medium text-[#2c2420]">{{ $feedback->created_at->format('M j, Y') }}</div>
                                    <div class="text-[10px] text-[#8b7e76]">{{ $feedback->created_at->format('g:i A') }}</div>
                                    <div class="mt-1.5">
                                        @if($feedback->is_anonymous)
                                            <span class="identity-pill bg-[#fffbeb] text-[#7a2a2a] border border-[#d4af37]/30">
                                                <i class="fas fa-user-secret mr-1 text-[8px]"></i> Anonymous
                                            </span>
                                        @else
                                            <span class="identity-pill bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30">
                                                <i class="fas fa-user mr-1 text-[8px]"></i> Identified
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2 sm:gap-3">
                                        <a href="{{ route('admin.feedback.show', $feedback) }}"
                                           class="action-link text-[#7a2a2a] hover:text-[#5c1a1a]"
                                           title="View Details">
                                            <i class="fas fa-eye text-xs sm:text-sm"></i>
                                        </a>
                                        @if(!$feedback->is_anonymous && $feedback->user->student)
                                        <a href="{{ route('admin.students') }}?search={{ $feedback->user->student->student_id }}"
                                           class="action-link text-[#059669] hover:text-[#047857]"
                                           title="View Student Profile">
                                            <i class="fas fa-user-graduate text-xs sm:text-sm"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 sm:py-14 text-center">
                                    <div class="text-slate-500">
                                        <div class="empty-state-icon mb-3 sm:mb-4">
                                            <i class="fas fa-comments text-[#a89f97] text-xl sm:text-3xl"></i>
                                        </div>
                                        <p class="text-sm sm:text-base font-semibold text-[#4a3f3a]">No feedback submissions found</p>
                                        <p class="text-[10px] sm:text-xs text-[#8b7e76] mt-1 sm:mt-2">No feedback matches your current search criteria.</p>
                                        @if(request()->hasAny(['search', 'rating', 'service', 'date_range', 'anonymous']))
                                            <a href="{{ route('admin.feedback.index') }}"
                                               class="inline-block mt-3 sm:mt-4 secondary-btn px-4 py-2 text-xs sm:text-sm rounded-lg">
                                                Clear Filters
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($feedbacks->hasPages())
                <div class="bg-[#faf8f5]/40 px-4 py-3 sm:px-6 border-t border-[#e5e0db]/60">
                    <div class="pagination-wrap flex justify-center">
                        {{ $feedbacks->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .pagination-wrap nav { display: inline-flex; }
    .pagination-wrap .relative { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
    .pagination-wrap span, .pagination-wrap a {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 28px; height: 28px; padding: 0 8px; border-radius: 8px;
        font-size: 11px; font-weight: 600; transition: all 0.2s ease;
    }
    .pagination-wrap span[aria-current="page"] span {
        background: linear-gradient(135deg, #5c1a1a 0%, #7a2a2a 55%, #d4af37 100%);
        color: white; box-shadow: 0 4px 10px rgba(92, 26, 26, 0.2);
    }
    .pagination-wrap a {
        background: white; color: #6b5e57; border: 1px solid #e5e0db;
        box-shadow: 0 1px 3px rgba(44, 36, 32, 0.04);
    }
    .pagination-wrap a:hover {
        background: #fdf2f2; color: #5c1a1a; border-color: rgba(212, 175, 55, 0.4); 
        transform: translateY(-1px); box-shadow: 0 4px 8px rgba(92, 26, 26, 0.08);
    }
    @media (max-width: 639px) {
        .pagination-wrap span, .pagination-wrap a { min-width: 26px; height: 26px; font-size: 10px; }
    }
</style>
@endsection