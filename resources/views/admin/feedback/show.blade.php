@extends('layouts.admin')

@section('title', 'Feedback Details - Admin Panel')

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

    .feedback-detail-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .feedback-detail-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .feedback-detail-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .feedback-detail-glow.two { bottom: -40px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .info-card, .rating-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .info-card:hover, .rating-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .panel-card::before, .info-card::before, .rating-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .section-icon {
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

    .primary-btn, .secondary-btn, .success-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        padding: 0.55rem 0.85rem;
    }
    .primary-btn, .success-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover, .success-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        background: #ffffff; color: var(--text-secondary); border: 1px solid var(--border-soft);
        box-shadow: 0 2px 6px rgba(44,36,32,0.03);
    }
    .secondary-btn:hover { background: #f5f0eb; }

    .section-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .section-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .section-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .section-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .section-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .metric-label { display: block; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.12em; color: var(--text-muted); margin-bottom: 0.3rem; }
    .metric-value { color: var(--text-primary); font-size: 0.85rem; font-weight: 600; }

    .identity-pill, .service-pill {
        display: inline-flex; align-items: center; padding: 0.25rem 0.55rem; border-radius: 999px;
        font-size: 0.7rem; font-weight: 600;
    }
    .service-pill { background: rgba(245,240,235,0.7); color: var(--text-secondary); border: 1px solid var(--border-soft); }

    .mini-progress { width: 100%; background: #f5f0eb; border-radius: 999px; height: 0.4rem; overflow: hidden; }
    .mini-progress > div { height: 100%; border-radius: 999px; }

    .user-avatar { width: 3.5rem; height: 3.5rem; border-radius: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .system-box { border-radius: 0.6rem; background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft)/60; padding: 0.75rem; }
    .footer-note { color: var(--text-muted); font-size: 0.75rem; }

    @media (max-width: 639px) {
        .section-header { padding: 0.75rem 1rem; }
        .user-avatar { width: 3rem; height: 3rem; }
        .primary-btn, .secondary-btn, .success-btn { width: 100%; justify-content: center; }
    }
</style>

<div class="min-h-screen feedback-detail-shell">
    <div class="feedback-detail-glow one"></div>
    <div class="feedback-detail-glow two"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-comment-dots text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Feedback Details
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Feedback Details</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Complete view of student feedback submission.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row xl:flex-col justify-center gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-bolt text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Quick Actions</p>
                                <p class="summary-value">Navigate & Export</p>
                                <p class="summary-subtext hidden sm:block">Return to the list or export this feedback instantly.</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 sm:gap-3 mt-2 sm:mt-3 justify-center sm:justify-start xl:justify-center">
                            <a href="{{ route('admin.feedback.index') }}"
                               class="secondary-btn px-4 py-2.5 text-xs sm:text-sm rounded-lg">
                                <i class="fas fa-arrow-left mr-1.5 text-[9px] sm:text-xs"></i> Back
                            </a>
                            <a href="{{ route('admin.feedback.export', ['search' => $feedback->id]) }}"
                               class="success-btn px-4 py-2.5 text-xs sm:text-sm rounded-lg">
                                <i class="fas fa-download mr-1.5 text-[9px] sm:text-xs"></i> Export
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Feedback Card -->
        <div class="panel-card overflow-hidden">
            <div class="section-topline"></div>

            <!-- Header Section -->
            <div class="px-4 sm:px-6 py-5 sm:py-6 border-b border-[#e5e0db]/60 bg-gradient-to-r from-[#5c1a1a] via-[#7a2a2a] to-[#d4af37]">
                <div class="flex flex-col xl:flex-row justify-between items-start gap-4 sm:gap-6">
                    <div class="flex items-center gap-3 sm:gap-4">
                        @if($feedback->is_anonymous)
                            <div class="user-avatar bg-white/10 backdrop-blur-sm flex-shrink-0">
                                <i class="fas fa-user-secret text-white text-xl sm:text-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-white">Anonymous Feedback Submission</h2>
                                <p class="text-white/80 mt-1 text-xs sm:text-sm">Student identity protected for privacy</p>
                            </div>
                        @else
                            <div class="user-avatar bg-white/10 backdrop-blur-sm flex-shrink-0">
                                <i class="fas fa-user text-white text-xl sm:text-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-white">
                                    {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                </h2>
                                <p class="text-white/80 mt-1 text-xs sm:text-sm">{{ $feedback->user->email }}</p>
                                @if($feedback->user->student)
                                    <p class="text-[#fef9e7] text-xs sm:text-sm mt-1 truncate max-w-[220px]">
                                        {{ $feedback->user->student->student_id }} •
                                        {{ $feedback->user->student->college->name ?? 'No College' }} •
                                        {{ $feedback->user->student->year_level ?? 'N/A' }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 sm:px-5 py-3 sm:py-4 text-right border border-white/10 w-full xl:w-auto">
                        <div class="text-white text-2xl sm:text-3xl md:text-4xl font-semibold mb-1">
                            {{ $feedback->satisfaction_rating }}/5
                        </div>
                        <div class="text-white/90 text-sm sm:text-lg">
                            {{ \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating) }}
                        </div>
                        <div class="text-[#fef9e7] mt-1.5 sm:mt-2 flex justify-end gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $feedback->satisfaction_rating ? '' : '-o' }} text-xs sm:text-sm"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-4 sm:p-6 md:p-8">
                <!-- Service & Timeline Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 md:gap-8 mb-6 sm:mb-8">
                    <!-- Service Information -->
                    <div class="info-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-cog text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Service Information</h3>
                                <p class="section-subtitle hidden sm:block">Service type and submission privacy details.</p>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6 space-y-4">
                            <div>
                                <label class="metric-label">Service Availed</label>
                                <div class="service-pill">{{ $feedback->service_availed }}</div>
                            </div>
                            <div>
                                <label class="metric-label">Submission Type</label>
                                <p class="mt-1">
                                    @if($feedback->is_anonymous)
                                        <span class="identity-pill bg-[#fffbeb] text-[#7a2a2a] border border-[#d4af37]/30">
                                            <i class="fas fa-user-secret mr-1 text-[8px]"></i> Anonymous Submission
                                        </span>
                                    @else
                                        <span class="identity-pill bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30">
                                            <i class="fas fa-user mr-1 text-[8px]"></i> Identified Submission
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Information -->
                    <div class="info-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-clock text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Timeline Information</h3>
                                <p class="section-subtitle hidden sm:block">Submission date, update history, and record details.</p>
                            </div>
                        </div>
                        <div class="p-4 sm:p-6 space-y-4">
                            <div>
                                <label class="metric-label">Submitted On</label>
                                <p class="metric-value">{{ $feedback->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="metric-label">Last Updated</label>
                                <p class="metric-value">{{ $feedback->updated_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="metric-label">Feedback ID</label>
                                <p class="metric-value font-mono text-xs">#{{ $feedback->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Visualization -->
                <div class="mb-6 sm:mb-8">
                    <div class="rating-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-chart-bar text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Satisfaction Rating</h3>
                                <p class="section-subtitle hidden sm:block">Visual representation of the submitted satisfaction score.</p>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6 md:p-8">
                            <div class="bg-[#fffbeb] rounded-xl p-4 sm:p-6 border border-[#d4af37]/30 text-center">
                                <div class="text-[#c9a227] text-2xl sm:text-3xl md:text-5xl mb-3 sm:mb-4 flex justify-center flex-wrap gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $feedback->satisfaction_rating ? '' : '-o' }} mx-0.5 sm:mx-1"></i>
                                    @endfor
                                </div>
                                <div class="text-xl sm:text-2xl md:text-3xl font-semibold text-[#2c2420] mb-1 sm:mb-2">
                                    {{ $feedback->satisfaction_rating }} out of 5 Stars
                                </div>
                                <div class="text-base sm:text-lg md:text-xl text-[#6b5e57] mb-3 sm:mb-4">
                                    {{ \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating) }}
                                </div>
                                <div class="mini-progress max-w-xs mx-auto">
                                    <div class="bg-[#c9a227]" style="width: {{ ($feedback->satisfaction_rating / 5) * 100 }}%"></div>
                                </div>
                                <div class="text-xs sm:text-sm text-[#8b7e76] mt-2">
                                    {{ number_format(($feedback->satisfaction_rating / 5) * 100, 1) }}% Satisfaction Rate
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Comments -->
                <div class="mb-6 sm:mb-8">
                    <div class="info-card">
                        <div class="section-topline"></div>
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-comment-dots text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <h3 class="section-title">Student Comments & Feedback</h3>
                                <p class="section-subtitle hidden sm:block">Full written feedback provided by the student.</p>
                            </div>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="bg-[#faf8f5] rounded-xl p-4 sm:p-6 border border-[#e5e0db]/60">
                                @if($feedback->comments)
                                    <div class="prose max-w-none">
                                        <p class="text-[#4a3f3a] leading-relaxed whitespace-pre-wrap text-sm sm:text-base">{{ $feedback->comments }}</p>
                                    </div>
                                    <div class="mt-3 sm:mt-4 text-[10px] sm:text-xs text-[#8b7e76] flex items-center gap-1.5">
                                        <i class="fas fa-info-circle"></i>
                                        {{ str_word_count($feedback->comments) }} words, {{ strlen($feedback->comments) }} characters
                                    </div>
                                @else
                                    <div class="text-center py-6 sm:py-8">
                                        <i class="fas fa-comment-slash text-3xl sm:text-4xl text-[#a89f97] mb-2 sm:mb-3"></i>
                                        <p class="text-[#6b5e57] text-base sm:text-lg">No comments provided by the student.</p>
                                        <p class="text-[#8b7e76] text-xs sm:text-sm mt-1">The student only submitted a rating without additional comments.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="info-card">
                    <div class="section-topline"></div>
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-database text-xs sm:text-sm"></i>
                        </div>
                        <div>
                            <h3 class="section-title">System Information</h3>
                            <p class="section-subtitle hidden sm:block">Stored metadata related to this feedback submission.</p>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 text-xs sm:text-sm">
                        <div class="system-box">
                            <label class="metric-label">Database Record</label>
                            <div class="metric-value">ID: #{{ $feedback->id }}</div>
                        </div>
                        <div class="system-box">
                            <label class="metric-label">User Account</label>
                            <div class="metric-value">
                                @if($feedback->is_anonymous)
                                    <span class="text-[#a89f97]">Hidden (Anonymous)</span>
                                @else
                                    User ID: {{ $feedback->user_id }}
                                @endif
                            </div>
                        </div>
                        <div class="system-box">
                            <label class="metric-label">Submission IP</label>
                            <div class="metric-value">Recorded in system logs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-5 sm:mt-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3 sm:gap-4">
            <div class="footer-note flex items-center gap-1">
                <i class="fas fa-info-circle"></i>
                This feedback is part of the system's quality assurance process.
            </div>
            <div class="flex flex-wrap gap-2 sm:gap-3 w-full lg:w-auto">
                <a href="{{ route('admin.feedback.index') }}"
                   class="secondary-btn px-5 py-2.5 text-xs sm:text-sm rounded-lg">
                    <i class="fas fa-list mr-1.5 text-[9px] sm:text-xs"></i> Back to All Feedback
                </a>
                @if(!$feedback->is_anonymous && $feedback->user->student)
                <a href="{{ route('admin.students') }}?search={{ $feedback->user->student->student_id }}"
                   class="primary-btn px-5 py-2.5 text-xs sm:text-sm rounded-lg">
                    <i class="fas fa-user-graduate mr-1.5 text-[9px] sm:text-xs"></i> View Student Profile
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection