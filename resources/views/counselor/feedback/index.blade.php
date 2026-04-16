@extends('layouts.app')

@section('title', 'Feedback Management - OGC')

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

    /* Base Layout & Glow */
    .feedback-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .feedback-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .feedback-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .feedback-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

    /* Glass Cards */
    .panel-card {
        position: relative; z-index: 1; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .panel-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    /* Header Specifics */
    .page-header h1 { color: var(--text-primary); font-weight: 700; letter-spacing: -0.02em; }
    .page-header p { color: var(--text-secondary); }

    /* Alert/Success Box */
    .alert-success {
        background: rgba(209, 250, 229, 0.6); border: 1px solid rgba(16, 185, 129, 0.2);
        color: #047857; border-radius: 0.75rem; padding: 0.75rem 1rem;
        display: flex; align-items: center; gap: 0.75rem;
    }

    /* Buttons */
    .btn-action {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0.6rem 1rem; border-radius: 0.6rem; font-weight: 600; font-size: 0.8rem;
        transition: all 0.2s ease; white-space: nowrap; gap: 0.5rem;
    }
    .btn-export {
        background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white;
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.15);
    }
    .btn-export:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(5, 150, 105, 0.2); }
    
    .btn-filter {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .btn-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .btn-reset {
        background: white; color: var(--text-secondary); border: 1px solid var(--border-soft);
    }
    .btn-reset:hover { background: var(--bg-warm); color: var(--text-primary); border-color: var(--maroon-700); }

    /* Stats Cards */
    .stat-card {
        display: flex; align-items: center; gap: 1rem; padding: 1rem;
        border-radius: 0.75rem; border: 1px solid var(--border-soft);
        background: rgba(255,255,255,0.8);
    }
    .stat-icon-box {
        width: 3rem; height: 3rem; border-radius: 0.6rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; flex-shrink: 0;
    }
    .stat-icon-gray { background: rgba(229, 231, 235, 0.6); color: var(--maroon-700); }
    .stat-icon-green { background: rgba(209, 250, 229, 0.6); color: #047857; }
    .stat-icon-gold { background: rgba(255, 249, 230, 0.6); color: var(--maroon-800); }
    .stat-icon-orange { background: rgba(255, 237, 213, 0.6); color: #c2410c; }
    
    .stat-label { font-size: 0.75rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--text-primary); line-height: 1.2; }
    .stat-sub { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }

    /* Form Inputs */
    .input-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.85rem; padding: 0.6rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }
    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }

    /* Table Styling */
    .table-container { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 900px; }
    .custom-table thead th {
        background: rgba(250,248,245,0.8); color: var(--text-muted);
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
        padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-soft);
        text-align: left;
    }
    .custom-table tbody td {
        padding: 0.85rem 1rem; border-bottom: 1px solid rgba(229, 224, 219, 0.5);
        color: var(--text-secondary); font-size: 0.8rem; vertical-align: middle;
    }
    .custom-table tbody tr:last-child td { border-bottom: none; }
    .custom-table tbody tr:hover { background: rgba(254,249,231,0.3); }

    /* Avatar & Info */
    .avatar-circle {
        width: 2.5rem; height: 2.5rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.75rem; flex-shrink: 0;
    }
    .avatar-anon { background: rgba(255, 249, 230, 0.8); color: var(--maroon-800); border: 1px solid rgba(212, 175, 55, 0.3); }
    .avatar-user { background: rgba(250,248,245,0.8); color: var(--maroon-700); border: 1px solid var(--border-soft); }
    
    .student-name { font-weight: 600; color: var(--text-primary); font-size: 0.85rem; }
    .student-meta { font-size: 0.65rem; color: var(--text-muted); margin-top: 0.1rem; line-height: 1.4; }
    .meta-sep { color: var(--border-soft); margin: 0 0.25rem; }

    /* Comments Preview */
    .comment-preview {
        max-width: 280px; color: var(--text-primary); font-size: 0.8rem;
        line-height: 1.4;
    }
    .comment-empty { color: var(--text-muted); font-style: italic; font-size: 0.75rem; }

    /* Action Icons */
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        transition: all 0.2s ease; background: transparent; border: none; cursor: pointer;
        color: var(--maroon-700);
    }
    .action-btn:hover { 
        background: rgba(122, 42, 42, 0.1); 
        color: var(--maroon-900);
        transform: translateY(-1px);
    }

    /* Empty State */
    .empty-state { padding: 3rem 1rem; text-align: center; }
    .empty-icon { color: var(--border-soft); font-size: 3.5rem; margin-bottom: 1rem; }
    .empty-title { color: var(--text-secondary); font-weight: 600; font-size: 1.1rem; }
    .empty-text { color: var(--text-muted); font-size: 0.85rem; }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .header-actions { flex-direction: column; width: 100%; }
        .header-actions .btn-action { width: 100%; }
        .stat-card { padding: 0.75rem; }
        .stat-icon-box { width: 2.5rem; height: 2.5rem; font-size: 1rem; }
        .stat-value { font-size: 1.25rem; }
        .filter-grid { grid-template-columns: 1fr !important; }
        .filter-actions { flex-direction: column; width: 100%; }
        .filter-actions .btn-action { width: 100%; }
        .custom-table { font-size: 0.75rem; }
        .custom-table thead th, .custom-table tbody td { padding: 0.6rem 0.5rem; }
        .avatar-circle { width: 2rem; height: 2rem; font-size: 0.65rem; }
        .comment-preview { max-width: 150px; }
    }
</style>

<div class="min-h-screen feedback-shell">
    <div class="feedback-glow one"></div>
    <div class="feedback-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 panel-card p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="page-header">
                    <h1 class="text-xl sm:text-2xl font-bold">Student Feedback</h1>
                    <p class="text-sm mt-1">View and manage all student feedback submissions.</p>
                </div>
                <div class="header-actions flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('counselor.feedback.export', request()->query()) }}"
                       class="btn-action btn-export">
                        <i class="fas fa-file-export"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-gray">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <p class="stat-label">Total Feedback</p>
                    <p class="stat-value">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-green">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <p class="stat-label">Average Rating</p>
                    <p class="stat-value">{{ $stats['average_rating'] }}<span class="text-base text-[var(--text-muted)]">/5</span></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-gold">
                    <i class="fas fa-user-secret"></i>
                </div>
                <div>
                    <p class="stat-label">Anonymous</p>
                    <p class="stat-value">{{ $stats['anonymous_count'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-box stat-icon-orange">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div>
                    <p class="stat-label">Distribution</p>
                    <p class="stat-sub truncate max-w-[140px]">
                        @foreach($stats['rating_distribution'] as $rating => $count)
                            {{ $rating }}★:{{ $count }}@if(!$loop->last) • @endif
                        @endforeach
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="panel-card mb-6 p-4 sm:p-5">
            <form method="GET" action="{{ route('counselor.feedback.index') }}" class="filter-grid grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="field-label">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Student, service, comments..."
                           class="input-field">
                </div>

                <!-- Rating Filter -->
                <div>
                    <label class="field-label">Rating</label>
                    <select name="rating" class="select-field">
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
                    <select name="service" class="select-field">
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
                    <select name="date_range" class="select-field">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>

                <!-- Anonymous Filter -->
                <div>
                    <label class="field-label">Anonymous</label>
                    <select name="anonymous" class="select-field">
                        <option value="">All</option>
                        <option value="1" {{ request('anonymous') == '1' ? 'selected' : '' }}>Anonymous Only</option>
                        <option value="0" {{ request('anonymous') == '0' ? 'selected' : '' }}>Non-anonymous Only</option>
                    </select>
                </div>

                <!-- Filter Buttons -->
                <div class="filter-actions md:col-span-5 flex flex-col sm:flex-row justify-end gap-3 pt-2">
                    <button type="submit"
                            class="btn-action btn-filter">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                    <a href="{{ route('counselor.feedback.index') }}"
                       class="btn-action btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Feedback Table -->
        <div class="panel-card overflow-hidden">
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th class="w-1/5">Student Info</th>
                            <th class="w-1/5">Service & Personnel</th>
                            <th class="w-1/4">Comments</th>
                            <th class="w-1/6">Submitted</th>
                            <th class="w-1/6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border-soft)]/50">
                        @forelse($feedbacks as $feedback)
                            <tr class="hover:bg-[rgba(254,249,231,0.3)] transition">
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($feedback->is_anonymous)
                                            <div class="avatar-circle avatar-anon">
                                                <i class="fas fa-user-secret"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="student-name">Anonymous</div>
                                                <div class="student-meta">{{ $feedback->user->student->college->name ?? 'N/A' }}</div>
                                            </div>
                                        @else
                                            <div class="avatar-circle avatar-user">
                                                {{ strtoupper(substr($feedback->user->first_name, 0, 1)) }}{{ strtoupper(substr($feedback->user->last_name, 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="student-name truncate">
                                                    {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                                </div>
                                                <div class="student-meta">
                                                    {{ $feedback->user->student->college->name ?? 'N/A' }}
                                                    <span class="meta-sep">|</span>
                                                    {{ $feedback->user->sex ?? 'N/A' }}
                                                    <span class="meta-sep">|</span>
                                                    Age: {{ $feedback->user->age ?? 'N/A' }}
                                                </div>
                                                <div class="student-meta hidden sm:block">
                                                    Region: {{ $feedback->user->region_of_residence ?? 'N/A' }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="font-medium text-[var(--text-primary)] text-xs">
                                        {{ $feedback->service_availed }}
                                    </div>
                                    <div class="text-[var(--text-muted)] text-xs mt-0.5">
                                        Personnel: {{ $feedback->personnel_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="comment-preview">
                                        @if($feedback->comments)
                                            {{ Str::limit($feedback->comments, 100) }}
                                        @else
                                            <span class="comment-empty">No comments provided</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-xs font-medium text-[var(--text-primary)]">
                                        {{ $feedback->created_at->format('M j, Y') }}
                                    </div>
                                    <div class="text-[10px] text-[var(--text-muted)]">
                                        {{ $feedback->created_at->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('counselor.feedback.show', $feedback) }}"
                                       class="action-btn"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <i class="fas fa-comments empty-icon"></i>
                                    <p class="empty-title">No feedback found.</p>
                                    <p class="empty-text">No feedback submissions match your current filters.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($feedbacks->hasPages())
                <div class="p-4 border-t border-[var(--border-soft)] bg-[rgba(250,248,245,0.4)]">
                    {{ $feedbacks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection