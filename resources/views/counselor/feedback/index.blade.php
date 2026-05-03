@extends('layouts.app')

@section('title', 'Feedback Management - OGC')

@section('content')
    <div class="feedback-shell relative overflow-hidden min-h-screen bg-[#faf8f5]">
        <div class="feedback-glow feedback-glow-1"></div>
        <div class="feedback-glow feedback-glow-2"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
            <!-- Header Section -->
            <div class="mb-6 sm:mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                    <div class="hero-card group">
                        <div class="hero-card-pattern"></div>
                        <div class="relative flex items-start gap-3 p-4 sm:p-5">
                            <div class="hero-icon">
                                <i class="fas fa-message text-base sm:text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="hero-badge">
                                    <span class="hero-badge-dot"></span>
                                    Student Insights
                                </div>
                                <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Student Feedback</h1>
                                <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                    View and manage all student feedback submissions.
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
                                    <p class="summary-value">Export Data</p>
                                    <p class="summary-subtext hidden sm:block">Download the current feedback list.</p>
                                </div>
                            </div>
                            <a href="{{ route('counselor.feedback.export', request()->query()) }}" class="inline-flex items-center justify-center px-4 py-2 sm:py-2.5 rounded-lg bg-white/10 hover:bg-white/20 text-white font-medium transition-all text-xs sm:text-sm border border-white/20 backdrop-blur-sm shadow-sm">
                                <i class="fas fa-file-export mr-1.5 text-[9px] sm:text-xs"></i> Export
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $totalFeedback = (int) ($stats['total'] ?? 0);
                $anonymousFeedback = (int) ($stats['anonymous_count'] ?? 0);
                $identifiedFeedback = max(0, $totalFeedback - $anonymousFeedback);
            @endphp

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#fdf2f2] text-[#7a2a2a] group-hover:bg-[#fce4e4]">
                            <i class="fas fa-message text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Total Feedback</p>
                            <p class="stat-value">{{ number_format($totalFeedback) }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#fef9e7] text-[#9a7b0a] group-hover:bg-[#fef3d1]">
                            <i class="fas fa-star text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Average Rating</p>
                            <p class="stat-value">{{ number_format($stats['average_rating'] ?? 0, 1) }}/5</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#f8fafc] text-[#475569] group-hover:bg-[#f1f5f9]">
                            <i class="fas fa-user-secret text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Anonymous</p>
                            <p class="stat-value">{{ number_format($anonymousFeedback) }}</p>
                        </div>
                    </div>
                </div>

                <div class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#ecfdf5] text-[#059669] group-hover:bg-[#d1fae5]">
                            <i class="fas fa-circle-check text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Identified</p>
                            <p class="stat-value">{{ number_format($identifiedFeedback) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm mb-6 sm:mb-8">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-[#5c1a1a] via-[#d4af37] to-[#5c1a1a]"></div>

                <div class="p-3 sm:p-4">
                    <form method="GET" action="{{ route('counselor.feedback.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
                        <div>
                            <label class="filter-label">Search</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 sm:left-3.5 top-1/2 -translate-y-1/2 text-[#a89f97] text-[10px] sm:text-xs"></i>
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search feedbacks..."
                                       class="filter-input"
                                       style="padding-left: 2.25rem !important;" />
                            </div>
                        </div>

                        <div>
                            <label class="filter-label">Rating</label>
                            <select name="rating" class="filter-input bg-white">
                                <option value="">All Ratings</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('rating') == (string) $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="filter-label">Service</label>
                            <select name="service" class="filter-input bg-white">
                                <option value="">All Services</option>
                                @foreach($serviceTypes as $service)
                                    <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>{{ $service }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="filter-label">Date Range</label>
                            <select name="date_range" class="filter-input bg-white">
                                <option value="">All Time</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                            </select>
                        </div>

                        <div>
                            <label class="filter-label">Anonymous</label>
                            <select name="anonymous" class="filter-input bg-white">
                                <option value="">All</option>
                                <option value="1" {{ request('anonymous') == '1' ? 'selected' : '' }}>Anonymous Only</option>
                                <option value="0" {{ request('anonymous') == '0' ? 'selected' : '' }}>Identified Only</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2 sm:gap-3">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] text-white font-medium shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 text-xs sm:text-sm">
                                <i class="fas fa-search text-[10px] sm:text-xs"></i>
                                <span>Apply</span>
                            </button>
                            <a href="{{ route('counselor.feedback.index') }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-[#f5f0eb] text-[#6b5e57] hover:bg-[#e5e0db] transition font-medium text-xs sm:text-sm">
                                <i class="fas fa-rotate-left"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm">
                <div class="table-header-bar">
                    <div class="flex items-center gap-3">
                        <div class="table-header-icon">
                            <i class="fas fa-message text-[#7a2a2a] text-[10px] sm:text-xs"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-[#2c2420]">All Feedback Submissions</h2>
                            <p class="text-[10px] sm:text-xs text-[#8b7e76]">Showing <span class="font-bold text-[#2c2420]">{{ $feedbacks->firstItem() ?? 0 }} - {{ $feedbacks->lastItem() ?? 0 }}</span> of <span class="font-bold text-[#2c2420]">{{ $feedbacks->total() }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[950px]">
                        <thead>
                            <tr class="bg-[#faf8f5] border-b border-[#e5e0db]/80">
                                <th class="px-4 sm:px-6 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Student Information</span>
                                </th>
                                <th class="px-4 sm:px-6 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Service & Rating</span>
                                </th>
                                <th class="px-4 sm:px-6 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Comments</span>
                                </th>
                                <th class="px-4 sm:px-6 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Submission Details</span>
                                </th>
                                <th class="px-4 sm:px-6 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Actions</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#e5e0db]/50">
                            @forelse($feedbacks as $feedback)
                                <tr class="group hover:bg-[#fdf9f6] transition-colors duration-150 cursor-pointer" onclick="window.location='{{ route('counselor.feedback.show', $feedback) }}'">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($feedback->is_anonymous)
                                                <div class="w-9 h-9 rounded-md bg-[#fffbeb] flex items-center justify-center flex-shrink-0 shadow-inner">
                                                    <i class="fas fa-user-secret text-[#7a2a2a] text-xs sm:text-sm"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs sm:text-sm font-semibold text-[#2c2420]">Anonymous User</div>
                                                    <div class="text-[10px] sm:text-[11px] text-[#8b7e76]">Identity Protected</div>
                                                </div>
                                            @else
                                                <div class="w-9 h-9 rounded-md bg-[#f5f0eb] flex items-center justify-center flex-shrink-0 shadow-inner">
                                                    <i class="fas fa-user text-[#7a2a2a] text-xs sm:text-sm"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[160px]">
                                                        {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                                    </div>
                                                    <div class="text-[10px] sm:text-[11px] text-[#8b7e76] truncate max-w-[160px]">{{ $feedback->user->email }}</div>
                                                    @if($feedback->user->student)
                                                        <div class="text-[10px] sm:text-[11px] text-[#a89f97] truncate max-w-[160px] mt-0.5">
                                                            {{ $feedback->user->student->student_id }} &bull; {{ $feedback->user->student->college->name ?? 'N/A' }}
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
                                        <div class="flex items-center gap-1.5">
                                            <div class="text-[#d4af37] text-xs">
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
                                        <div class="text-[10px] text-[#8b7e76] mt-0.5">{{ $feedback->created_at->format('g:i A') }}</div>
                                        <div class="mt-2">
                                            @if($feedback->is_anonymous)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#fffbeb] text-[#b45309] border border-[#f59e0b]/30">
                                                    <i class="fas fa-user-secret mr-1.5 text-[9px]"></i> Anonymous
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30">
                                                    <i class="fas fa-user-check mr-1.5 text-[9px]"></i> Identified
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                        <div class="flex items-center gap-2.5">
                                            <a href="{{ route('counselor.feedback.show', $feedback) }}" class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-[#fdf2f2] text-[#7a2a2a] hover:bg-[#7a2a2a] hover:text-white transition-colors" title="View Details">
                                                <i class="fas fa-eye text-[10px] sm:text-xs"></i>
                                            </a>
                                            @if(!$feedback->is_anonymous && $feedback->user->student)
                                                <a href="{{ url('/counselor/students') }}?search={{ $feedback->user->student->student_id }}"
                                                   class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-[#ecfdf5] text-[#059669] hover:bg-[#059669] hover:text-white transition-colors" title="View Student Profile">
                                                    <i class="fas fa-user-graduate text-[10px] sm:text-xs"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#f5f0eb] flex items-center justify-center shadow-inner">
                                                <i class="fas fa-message text-[#a89f97] text-xl sm:text-2xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-[#6b5e57] font-medium text-sm">No feedback submissions found</p>
                                                <p class="text-xs text-[#8b7e76] mt-1">Try adjusting your search or filters</p>
                                            </div>
                                            @if(request()->hasAny(['search', 'rating', 'service', 'date_range', 'anonymous']))
                                                <a href="{{ route('counselor.feedback.index') }}"
                                                   class="inline-flex items-center mt-3 px-3 py-1.5 rounded-lg bg-white border border-[#e5e0db] text-xs font-medium text-[#6b5e57] hover:bg-[#f5f0eb] transition">
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
                <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                    {{ $feedbacks->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon-900: #3a0c0c;
            --maroon-800: #5c1a1a;
            --maroon-700: #7a2a2a;
            --gold-500: #c9a227;
            --gold-400: #d4af37;
        }

        .feedback-shell { min-height: 100%; }

        .feedback-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            opacity: 0.3;
        }

        .feedback-glow-1 {
            top: -20px;
            left: -40px;
            width: 180px;
            height: 180px;
            background: var(--gold-400);
        }

        .feedback-glow-2 {
            bottom: -30px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: var(--maroon-800);
        }

        .hero-card {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid rgba(229, 224, 219, 0.8);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(44, 36, 32, 0.04);
            transition: box-shadow 0.2s ease;
        }

        .hero-card:hover {
            box-shadow: 0 4px 12px rgba(44, 36, 32, 0.06);
        }

        .hero-card-pattern {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(212,175,55,0.08), transparent 35%),
                radial-gradient(circle at bottom right, rgba(92,26,26,0.06), transparent 40%);
            pointer-events: none;
        }

        .hero-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fef9e7;
            background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
            box-shadow: 0 4px 12px rgba(92,26,26,0.15);
            flex-shrink: 0;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            border: 1px solid rgba(212,175,55,0.3);
            background: rgba(254,249,231,0.8);
            padding: 0.2rem 0.55rem;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.16em;
            color: var(--maroon-700);
        }

        .hero-badge-dot {
            width: 0.3rem;
            height: 0.3rem;
            border-radius: 999px;
            background: var(--gold-400);
        }

        .summary-card {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid rgba(92,26,26,0.15);
            background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(58,12,12,0.15);
            min-width: 200px;
        }
        .summary-card::before {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.15;
            background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
            pointer-events: none;
        }

        .summary-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fef9e7;
            flex-shrink: 0;
        }

        .summary-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: rgba(255,255,255,0.7);
        }

        .summary-value {
            font-size: 1.5rem;
            line-height: 1;
            font-weight: 800;
            margin-top: 0.35rem;
        }

        .summary-subtext {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.8);
            margin-top: 0.25rem;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid rgba(229, 224, 219, 0.8);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 0.85rem;
            box-shadow: 0 2px 8px rgba(44, 36, 32, 0.04);
            transition: all 0.2s ease;
            display: block;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(44, 36, 32, 0.06);
        }

        .stat-card-pattern {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(212, 175, 55, 0.06), transparent 30%);
            pointer-events: none;
        }

        .stat-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .stat-label {
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #8b7e76;
            margin-bottom: 0.15rem;
        }

        .stat-value {
            font-size: 1.1rem;
            line-height: 1;
            font-weight: 700;
            color: #2c2420;
        }

        .filter-label {
            display: block;
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #8b7e76;
            margin-bottom: 0.4rem;
        }

        .filter-input {
            width: 100%;
            border: 1px solid #e5e0db;
            border-radius: 0.5rem;
            padding: 0.55rem 0.8rem;
            font-size: 0.8rem;
            color: #2c2420;
            background: rgba(255, 255, 255, 0.9);
            outline: none;
            transition: all 0.2s ease;
        }

        .filter-input:focus {
            border-color: var(--maroon-700);
            box-shadow: 0 0 0 3px rgba(122, 42, 42, 0.08);
        }

        .table-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.6rem;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #e5e0db;
            background: rgba(250,248,245,0.4);
        }

        .table-header-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: rgba(254,249,231,0.6);
        }

        tbody tr { transition: background-color 0.15s ease; }

        @media (max-width: 639px) {
            .stat-card { padding: 0.7rem; }
            .stat-icon { width: 1.75rem; height: 1.75rem; }
            .stat-value { font-size: 1rem; }
        }
    </style>
@endsection
