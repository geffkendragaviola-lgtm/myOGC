@extends('layouts.admin')

@section('title', 'Feedback Management - Admin Panel')

@section('content')
<style>
/* ΓöÇΓöÇ Feedback page styles (match Analytics) ΓöÇΓöÇ */
.analytics-card {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: 1rem;
    box-shadow: 0 4px 18px rgba(44,36,32,0.07);
    padding: 1.5rem;
}
.stat-card {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: 1rem;
    box-shadow: 0 4px 18px rgba(44,36,32,0.07);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.stat-icon {
    width: 3rem; height: 3rem;
    border-radius: 0.75rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; flex-shrink: 0;
}
.stat-icon.maroon { background: #7a2a2a; color:#fff; }
.stat-icon.gold   { background: #c9a227; color:#fff; }
.stat-icon.green  { background: #2d7a4f; color:#fff; }
.stat-icon.blue   { background: #2a5a7a; color:#fff; }
.stat-value { font-size: 1.75rem; font-weight: 600; color: var(--text-primary); line-height:1; }
.stat-label { font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.2rem; font-weight: 500; }

.filter-bar {
    background: #fff;
    border: 1px solid var(--border-soft);
    border-radius: 1rem;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 10px rgba(44,36,32,0.05);
}
.filter-bar select, .filter-bar input[type=text] {
    border: 1px solid var(--border-soft);
    border-radius: 0.5rem;
    padding: 0.45rem 0.75rem;
    font-size: 0.875rem;
    color: var(--text-primary);
    background: var(--bg-warm);
    outline: none;
    transition: border-color 0.2s;
    width: 100%;
}
.filter-bar select:focus, .filter-bar input[type=text]:focus { border-color: var(--maroon-soft); }

.btn-maroon {
    background: linear-gradient(135deg,var(--maroon-soft),var(--maroon-medium));
    color: #fff; border: none; border-radius: 0.5rem;
    padding: 0.5rem 1.1rem; font-size: 0.875rem; font-weight: 600;
    cursor: pointer; transition: opacity 0.2s;
    display: inline-flex; align-items: center; gap: 0.4rem;
    text-decoration: none;
}
.btn-maroon:hover { opacity: 0.88; }
.btn-outline {
    background: transparent;
    color: var(--maroon-soft);
    border: 1.5px solid var(--maroon-soft);
    border-radius: 0.5rem;
    padding: 0.45rem 1rem; font-size: 0.875rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s; text-decoration: none;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-outline:hover { background: var(--maroon-soft); color: #fff; }

.table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
.table-row { transition: background-color 0.15s ease; }
.table-row:hover { background: rgba(254,249,231,0.35); }
.admin-shell { position: relative; overflow: hidden; background: #faf8f5; min-height: 100vh; }
.admin-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25; }
.admin-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: #d4af37; }
.admin-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: #5c1a1a; }
.hero-card {
    position: relative; overflow: hidden; border-radius: 0.75rem;
    border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
    backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
}
.hero-card::before {
    content: ""; position: absolute; inset: 0; pointer-events: none;
    background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
}
.hero-icon {
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
    background: linear-gradient(135deg, #5c1a1a 0%, #7a2a2a 100%);
    box-shadow: 0 4px 12px rgba(92,26,26,0.15);
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
    border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
    padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.16em; color: #7a2a2a;
}
.hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: #d4af37; }

.summary-card {
    position: relative; overflow: hidden; border-radius: 0.75rem;
    border: 1px solid rgba(92,26,26,0.15);
    background: linear-gradient(135deg, #5c1a1a 0%, #3a0c0c 100%); color: white;
    box-shadow: 0 4px 12px rgba(58,12,12,0.15);
}
.summary-card::before {
    content: ""; position: absolute; inset: 0; opacity: 0.15;
    background: radial-gradient(circle at top right, #d4af37, transparent 40%);
    pointer-events: none;
}
.summary-icon {
    width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
    align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
}
.summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
.summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
.summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

.primary-btn {
    border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
    display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
    color: #fef9e7; background: linear-gradient(135deg, #5c1a1a 0%, #7a2a2a 100%);
    box-shadow: 0 4px 10px rgba(92,26,26,0.15);
}
.primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
</style>

<div class="min-h-screen admin-shell">
    <div class="admin-glow one"></div>
    <div class="admin-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8 space-y-6">

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon"><i class="fas fa-message text-base sm:text-lg"></i></div>
                        <div class="min-w-0">
                            <div class="hero-badge"><span class="hero-badge-dot"></span>Admin Panel</div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Feedback</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">Feedback submissions and satisfaction overview</p>
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
                                <p class="summary-value">Export Data</p>
                                <p class="summary-subtext hidden sm:block">Download the current feedback list.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.feedback.export', request()->query()) }}" class="primary-btn px-5 py-2.5 whitespace-nowrap text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-file-export mr-1.5 text-[9px] sm:text-xs"></i> Export
                        </a>
                    </div>
                </div>
            </div>
        </div>

    {{-- Summary cards --}}
    @php
        $totalFeedback = (int) ($stats['total'] ?? 0);
        $anonymousFeedback = (int) ($stats['anonymous_count'] ?? 0);
        $identifiedFeedback = max(0, $totalFeedback - $anonymousFeedback);
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="stat-icon maroon"><i class="fas fa-message"></i></div>
            <div>
                <div class="stat-value">{{ number_format($totalFeedback) }}</div>
                <div class="stat-label">Total Feedback</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gold"><i class="fas fa-star"></i></div>
            <div>
                <div class="stat-value">{{ $stats['average_rating'] ?? 0 }}/5</div>
                <div class="stat-label">Average Rating</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-user-secret"></i></div>
            <div>
                <div class="stat-value">{{ number_format($anonymousFeedback) }}</div>
                <div class="stat-label">Anonymous</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-circle-check"></i></div>
            <div>
                <div class="stat-value">{{ number_format($identifiedFeedback) }}</div>
                <div class="stat-label">Identified</div>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="filter-bar relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-[#5c1a1a] via-[#d4af37] to-[#5c1a1a]"></div>
        <form method="GET" action="{{ route('admin.feedback.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search feedbacks..." class="w-full">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Rating</label>
                <select name="rating" class="w-full">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == (string) $i ? 'selected' : '' }}>{{ $i }} Γÿà</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Service</label>
                <select name="service" class="w-full">
                    <option value="">All Services</option>
                    @foreach($serviceTypes as $service)
                        <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>{{ $service }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Date Range</label>
                <select name="date_range" class="w-full">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1" style="color:var(--text-secondary)">Anonymous</label>
                <select name="anonymous" class="w-full">
                    <option value="">All</option>
                    <option value="1" {{ request('anonymous') == '1' ? 'selected' : '' }}>Anonymous Only</option>
                    <option value="0" {{ request('anonymous') == '0' ? 'selected' : '' }}>Identified Only</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn-maroon flex-1 justify-center"><i class="fas fa-filter"></i> Apply</button>
                <a href="{{ route('admin.feedback.index') }}" class="btn-outline flex-1 justify-center"><i class="fas fa-rotate-left"></i> Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="analytics-card overflow-hidden" style="padding:0;">
        <div class="px-5 py-4" style="border-bottom:1px solid var(--border-soft);">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <div class="text-sm font-semibold" style="color:var(--text-primary)">All Feedback Submissions</div>
                    <div class="text-xs" style="color:var(--text-muted)">
                        Showing {{ $feedbacks->firstItem() }} - {{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }}
                    </div>
                </div>
                <span class="text-xs" style="color:var(--text-muted)"><i class="fas fa-clock mr-1"></i>Live data</span>
            </div>
        </div>

        <div class="table-wrap">
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
                                        <div class="w-9 h-9 rounded-full bg-[#fffbeb] flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-user-secret text-[#7a2a2a] text-xs sm:text-sm"></i>
                                        </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-semibold text-[#2c2420]">Anonymous User</div>
                                                <div class="text-[10px] text-[#8b7e76]">Identity Protected</div>
                                            </div>
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-[#f5f0eb] flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-user text-[#7a2a2a] text-xs sm:text-sm"></i>
                                        </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[160px]">
                                                    {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                                </div>
                                                <div class="text-[10px] text-[#8b7e76] truncate max-w-[160px]">{{ $feedback->user->email }}</div>
                                                @if($feedback->user->student)
                                                    <div class="text-[10px] text-[#a89f97] truncate max-w-[160px]">
                                                        {{ $feedback->user->student->student_id }} ΓÇó {{ $feedback->user->student->college->name ?? 'N/A' }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 sm:px-6 py-4">
                                    <div class="mb-1.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#f5f0eb] text-[#6b5e57] text-[10px] font-semibold border border-[#e5e0db]">{{ $feedback->service_availed }}</span>
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
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#fffbeb] text-[#7a2a2a] border border-[#d4af37]/30">
                                                <i class="fas fa-user-secret mr-1 text-[8px]"></i> Anonymous
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30">
                                                <i class="fas fa-user mr-1 text-[8px]"></i> Identified
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.feedback.show', $feedback) }}" class="text-[#7a2a2a] hover:text-[#5c1a1a]" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$feedback->is_anonymous && $feedback->user->student)
                                        <a href="{{ route('admin.students') }}?search={{ $feedback->user->student->student_id }}"
                                           class="text-[#059669] hover:text-[#047857]" title="View Student Profile">
                                            <i class="fas fa-user-graduate"></i>
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
                                            <i class="fas fa-message text-[#a89f97] text-xl sm:text-3xl"></i>
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

        @if($feedbacks->hasPages())
            <!-- Pagination -->
            <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                {{ $feedbacks->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
            </div>
        @endif
    </div>

    </div>
</div>

@endsection
