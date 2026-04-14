@extends('layouts.app')

@section('title', 'Feedback Details - OGC')

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
    .detail-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .detail-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .detail-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .detail-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

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

    /* Header Section (Gradient) */
    .feedback-header {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: white; padding: 1.5rem; position: relative; overflow: hidden;
    }
    .feedback-header::after {
        content: ""; position: absolute; top: 0; right: 0; width: 100px; height: 100%;
        background: linear-gradient(to left, rgba(255,255,255,0.1), transparent);
    }
    .header-icon-box {
        width: 3.5rem; height: 3.5rem; border-radius: 50%;
        background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(255,255,255,0.2); margin-right: 1rem; flex-shrink: 0;
    }
    .header-title { font-size: 1.25rem; font-weight: 700; line-height: 1.3; }
    .header-subtitle { font-size: 0.85rem; opacity: 0.9; margin-top: 0.25rem; }
    .header-meta { text-align: right; font-size: 0.8rem; opacity: 0.9; }
    .header-meta-label { font-weight: 600; display: block; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.65rem; opacity: 0.8; }

    /* Content Sections */
    .section-title {
        font-size: 0.9rem; font-weight: 700; color: var(--maroon-800);
        margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .section-title::before {
        content: ""; display: block; width: 4px; height: 18px;
        background: var(--gold-500); border-radius: 2px;
    }

    .info-grid {
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 1rem;
    }
    .info-item label {
        font-size: 0.7rem; font-weight: 600; color: var(--text-secondary);
        text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.25rem;
    }
    .info-item p {
        font-size: 0.9rem; font-weight: 600; color: var(--text-primary);
        word-break: break-word;
    }

    /* CC & SQD Lists */
    .list-group {
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 1rem;
    }
    .list-item {
        display: flex; justify-content: space-between; align-items: flex-start;
        padding-bottom: 0.75rem; margin-bottom: 0.75rem;
        border-bottom: 1px dashed var(--border-soft);
    }
    .list-item:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
    .list-label { font-size: 0.8rem; color: var(--text-secondary); padding-right: 1rem; line-height: 1.4; }
    .list-value { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); text-align: right; min-width: 60px; }

    /* Comments Box */
    .comments-box {
        background: rgba(255,255,255,0.5); border: 1px solid var(--border-soft);
        border-radius: 0.6rem; padding: 1rem;
    }
    .comment-text {
        font-size: 0.9rem; line-height: 1.6; color: var(--text-primary); white-space: pre-wrap;
    }
    .comment-empty { font-style: italic; color: var(--text-muted); }

    /* Badges */
    .badge {
        display: inline-flex; align-items: center; padding: 0.25rem 0.6rem;
        border-radius: 999px; font-size: 0.7rem; font-weight: 600;
    }
    .badge-yes { background: rgba(255, 249, 230, 0.8); color: var(--maroon-800); border: 1px solid rgba(212, 175, 55, 0.3); }
    .badge-no { background: rgba(209, 250, 229, 0.8); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); }

    /* Buttons */
    .btn-back {
        background: white; color: var(--text-secondary); border: 1px solid var(--border-soft);
        font-weight: 600; border-radius: 0.6rem; padding: 0.6rem 1.25rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-back:hover { background: var(--bg-warm); color: var(--text-primary); border-color: var(--maroon-700); transform: translateY(-1px); }
    
    .btn-back-large {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; border: none;
    }
    .btn-back-large:hover { box-shadow: 0 4px 12px rgba(92,26,26,0.2); }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .feedback-header { padding: 1rem; text-align: center; }
        .feedback-header .flex { flex-direction: column; align-items: center; gap: 1rem; }
        .header-icon-box { margin-right: 0; margin-bottom: 0.5rem; }
        .header-meta { text-align: center; width: 100%; }
        .list-item { flex-direction: column; gap: 0.25rem; }
        .list-label { padding-right: 0; }
        .list-value { text-align: left; }
        .action-footer { flex-direction: column; gap: 0.75rem; }
        .action-footer .btn-back { width: 100%; }
    }
</style>

<div class="min-h-screen detail-shell">
    <div class="detail-glow one"></div>
    <div class="detail-glow two"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)] tracking-tight">Feedback Details</h1>
                <p class="text-[var(--text-secondary)] text-sm mt-1">Detailed view of student feedback submission.</p>
            </div>
            <a href="{{ route('counselor.feedback.index') }}"
               class="btn-back">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>

        <!-- Feedback Card -->
        <div class="panel-card overflow-hidden">
            
            <!-- Header Section (Gradient) -->
            <div class="feedback-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="header-icon-box">
                            @if($feedback->is_anonymous)
                                <i class="fas fa-user-secret text-white text-xl"></i>
                            @else
                                <i class="fas fa-user text-white text-xl"></i>
                            @endif
                        </div>
                        <div>
                            @if($feedback->is_anonymous)
                                <h2 class="header-title">Anonymous Feedback</h2>
                                <p class="header-subtitle">Identity protected</p>
                            @else
                                <h2 class="header-title">
                                    {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                </h2>
                                <p class="header-subtitle">{{ $feedback->user->email }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="header-meta">
                        <span class="header-meta-label">Submitted</span>
                        {{ $feedback->created_at->format('F j, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-5 sm:p-6 md:p-8 space-y-8">
                
                <!-- Student Information -->
                <div>
                    <h3 class="section-title">Student Information (Limited View)</h3>
                    <div class="info-grid grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="info-item">
                            <label>College</label>
                            <p>{{ $feedback->user->student->college->name ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Sex at Birth</label>
                            <p>{{ $feedback->user->sex ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Age (in years)</label>
                            <p>{{ $feedback->user->age ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Region of Residence</label>
                            <p>{{ $feedback->user->region_of_residence ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Email Address</label>
                            <p class="break-all">{{ $feedback->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Mobile Number</label>
                            <p>
                                @if($feedback->share_mobile)
                                    {{ $feedback->user->phone_number ?? 'N/A' }}
                                @else
                                    <span class="text-[var(--text-muted)] italic">Not shared</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Service Information -->
                <div>
                    <h3 class="section-title">Service Information</h3>
                    <div class="info-grid grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="info-item">
                            <label>Service Availed</label>
                            <p class="text-lg">{{ $feedback->service_availed }}</p>
                        </div>
                        <div class="info-item">
                            <label>Personnel Transacted With</label>
                            <p class="text-lg">{{ $feedback->personnel_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Citizen's Charter -->
                <div>
                    <h3 class="section-title">Citizen's Charter (CC)</h3>
                    <div class="list-group">
                        @php
                            $cc1Map = [
                                'A' => 'I know what a CC is and I saw this office\'s CC.',
                                'B' => 'I know what a CC is but I did NOT see this office\'s CC.',
                                'C' => 'I learned of the CC only when I saw this office\'s CC.',
                                'D' => 'I do not know what a CC is and I did not see one in this office.',
                            ];
                            $cc1Value = $feedback->cc1;
                            $cc1Display = $cc1Map[$cc1Value] ?? ($cc1Value ?? 'N/A');
                        @endphp
                        <div class="list-item">
                            <div class="list-label"><strong>CC1:</strong></div>
                            <div class="list-value">{{ $cc1Display }}</div>
                        </div>
                        <div class="list-item">
                            <div class="list-label"><strong>CC2:</strong></div>
                            <div class="list-value">{{ $feedback->cc2 ?? 'N/A' }}</div>
                        </div>
                        <div class="list-item">
                            <div class="list-label"><strong>CC3:</strong></div>
                            <div class="list-value">{{ $feedback->cc3 ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Service Quality Dimensions -->
                <div>
                    <h3 class="section-title">Service Quality Dimensions (SQD)</h3>
                    <div class="list-group">
                        @php
                            $sqdQuestions = [
                                'sqd0' => 'SQD0. I am satisfied with the service that I availed.',
                                'sqd1' => 'SQD1. I spent a reasonable amount of time for my transaction.',
                                'sqd2' => "SQD2. The office followed the transaction's requirements and steps based on the information provided.",
                                'sqd3_1' => 'SQD3-1. The steps (including payment) I needed to do for my transaction were easy and simple.',
                                'sqd3_2' => 'SQD3-2. The receiving/ waiting/ processing/ working area, office facilities, etc. has visual appeal and comfiness.',
                                'sqd4' => 'SQD4. I easily found information about my transaction from the office or its website.',
                                'sqd5' => 'SQD5. I paid a reasonable amount of fees for my transaction.',
                                'sqd6' => 'SQD6. I feel the office was fair to everyone, or "walang palakasan", during my transaction.',
                                'sqd7_1' => 'SQD7-1. I was treated courteously by the staff, and (if asked for help) the staff was helpful.',
                                'sqd7_2' => 'SQD7-2. The staff is knowledgeable of the functions and/or operations of the office.',
                                'sqd7_3' => 'SQD7-3. The staff has the ability to complete the transaction.',
                                'sqd8' => 'SQD8. I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me.',
                                'sqd9' => 'SQD9. The staff shows professionalism, politeness, and willingness to help.',
                            ];
                        @endphp

                        @foreach($sqdQuestions as $key => $label)
                            <div class="list-item">
                                <div class="list-label">{{ $label }}</div>
                                <div class="list-value">{{ $feedback->{$key} ?? 'N/A' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Comments -->
                <div>
                    <h3 class="section-title">Comments/Suggestions</h3>
                    <div class="comments-box">
                        @if($feedback->comments)
                            <p class="comment-text">{{ $feedback->comments }}</p>
                        @else
                            <p class="comment-empty">No comments provided by the student.</p>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="pt-4 border-t border-[var(--border-soft)]">
                    <h3 class="section-title">Additional Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="info-item">
                            <label>Feedback ID</label>
                            <p>#{{ $feedback->id }}</p>
                        </div>
                        <div class="info-item">
                            <label>Anonymous Submission</label>
                            <p>
                                @if($feedback->is_anonymous)
                                    <span class="badge badge-yes">Yes</span>
                                @else
                                    <span class="badge badge-no">No</span>
                                @endif
                            </p>
                        </div>
                        <div class="info-item">
                            <label>Last Updated</label>
                            <p>{{ $feedback->updated_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="info-item">
                            <label>Visible To</label>
                            <p>
                                @if(is_null($feedback->target_counselor_id))
                                    All Counselors
                                @else
                                    Selected Counselor
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 action-footer flex justify-end gap-4">
            <a href="{{ route('counselor.feedback.index') }}"
               class="btn-back btn-back-large">
                <i class="fas fa-list mr-2"></i> Back to List
            </a>
        </div>
    </div>
</div>
@endsection