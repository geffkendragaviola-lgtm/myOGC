@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

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
    .notes-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .notes-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .notes-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .notes-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

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
    .student-meta { color: var(--text-muted); font-size: 0.85rem; margin-top: 0.25rem; }
    .count-badge { 
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--maroon-700); font-weight: 600; font-size: 0.8rem;
        background: rgba(122, 42, 42, 0.05); padding: 0.25rem 0.6rem;
        border-radius: 999px; margin-top: 0.5rem;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; font-weight: 600; border-radius: 0.6rem;
        padding: 0.6rem 1rem; display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(92,26,26,0.15); transition: all 0.2s ease;
        border: none; text-decoration: none; white-space: nowrap;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    
    .btn-secondary {
        background: white; color: var(--text-secondary); border: 1px solid var(--border-soft);
        font-weight: 600; border-radius: 0.6rem; padding: 0.6rem 1rem;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s ease; text-decoration: none; white-space: nowrap;
    }
    .btn-secondary:hover { background: var(--bg-warm); color: var(--text-primary); border-color: var(--maroon-700); }

    /* Alert */
    .alert-success {
        background: rgba(209, 250, 229, 0.6); border: 1px solid rgba(16, 185, 129, 0.2);
        color: #047857; border-radius: 0.75rem; padding: 0.75rem 1rem;
        display: flex; align-items: center; gap: 0.75rem;
    }

    /* Note Card Specifics */
    .note-card { margin-bottom: 1.5rem; }
    .note-header { border-bottom: 1px solid var(--border-soft); padding-bottom: 0.75rem; margin-bottom: 1rem; }
    .note-title { color: var(--text-primary); font-weight: 700; font-size: 1.1rem; }
    .note-date { color: var(--text-muted); font-size: 0.8rem; margin-top: 0.25rem; }
    
    .note-label {
        font-size: 0.75rem; font-weight: 600; color: var(--text-secondary);
        text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; display: block;
    }
    .note-text {
        color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6; white-space: pre-line;
    }

    /* Mood Badges */
    .mood-badge {
        display: inline-flex; align-items: center; padding: 0.35rem 0.75rem;
        border-radius: 999px; font-size: 0.8rem; font-weight: 600;
    }
    .mood-very_good { background: rgba(209, 250, 229, 0.8); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); }
    .mood-good { background: rgba(229, 231, 235, 0.6); color: var(--maroon-800); border: 1px solid var(--border-soft); }
    .mood-neutral { background: rgba(254, 243, 199, 0.8); color: #92400e; border: 1px solid rgba(245, 158, 11, 0.3); }
    .mood-low { background: rgba(255, 237, 213, 0.8); color: #c2410c; border: 1px solid rgba(249, 115, 22, 0.3); }
    .mood-very_low { background: rgba(254, 226, 226, 0.8); color: #b91c1c; border: 1px solid rgba(185, 28, 28, 0.3); }

    /* Follow-up Box */
    .followup-box {
        background: rgba(254, 243, 199, 0.3); border: 1px solid rgba(245, 158, 11, 0.2);
        border-radius: 0.6rem; padding: 0.75rem 1rem;
        display: flex; align-items: center; gap: 0.75rem;
    }
    .followup-icon { color: #d97706; font-size: 1.1rem; }
    .followup-text { color: #92400e; font-size: 0.85rem; font-weight: 600; }

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
    .empty-text { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem; }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .header-actions { flex-direction: column; width: 100%; }
        .header-actions .btn-primary, .header-actions .btn-secondary { width: 100%; justify-content: center; }
        .note-header { flex-direction: column; align-items: flex-start !important; gap: 0.5rem; }
        .note-actions { width: 100%; justify-content: flex-end; }
    }
</style>

<div class="min-h-screen notes-shell">
    <div class="notes-glow one"></div>
    <div class="notes-glow two"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 panel-card p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="page-header">
                    <h1 class="text-xl sm:text-2xl font-bold">Session Notes</h1>
                    <p class="font-medium mt-1">
                        for {{ $student->user->first_name }} {{ $student->user->last_name }}
                        <span class="text-[var(--text-muted)] font-normal">({{ $student->student_id }})</span>
                    </p>
                    <div class="student-meta">
                        {{ $student->college->name ?? 'N/A' }} • {{ $student->year_level }}
                    </div>
                    <div class="count-badge">
                        <i class="fas fa-notes-medical"></i>
                        {{ $sessionNotes->count() }} session note(s) found
                    </div>
                </div>
                <div class="header-actions flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('counselor.session-notes.create', $student) }}"
                       class="btn-primary">
                        <i class="fas fa-plus mr-2 text-xs"></i> New Session Note
                    </a>
                    <a href="{{ route('counselor.appointments') }}"
                       class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Appointments
                    </a>
                </div>
            </div>
        </div>

        <!-- Session Notes List -->
        @if($sessionNotes->isEmpty())
            <div class="panel-card empty-state">
                <i class="fas fa-notes-medical empty-icon"></i>
                <h3 class="empty-title">No Session Notes Yet</h3>
                <p class="empty-text">Start documenting your counseling sessions with this student.</p>
                <a href="{{ route('counselor.session-notes.create', $student) }}"
                   class="btn-primary">
                    Create First Session Note
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($sessionNotes as $note)
                    <div class="panel-card note-card p-5 sm:p-6">
                        <div class="note-header flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                            <div>
                                <h3 class="note-title">
                                    {{ $note->session_type_label }}
                                    @if($note->appointment)
                                        <span class="text-sm font-normal text-[var(--text-muted)] ml-2">
                                            (Appointment: {{ $note->appointment->appointment_date->format('M j, Y') }})
                                        </span>
                                    @endif
                                </h3>
                                <p class="note-date">
                                    {{ $note->session_date->format('F j, Y') }} •
                                    Created: {{ $note->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                            <div class="note-actions flex items-center">
                                <a href="{{ route('counselor.session-notes.edit', $note) }}"
                                   class="action-btn" title="Edit Note">
                                    <i class="fas fa-pen-to-square"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Mood Level -->
                        @if($note->mood_level)
                            @php
                                // Mapping logic preserved, classes updated to match CSS
                                $moodClass = match($note->mood_level) {
                                    'very_good' => 'mood-very_good',
                                    'good' => 'mood-good',
                                    'neutral' => 'mood-neutral',
                                    'low' => 'mood-low',
                                    'very_low' => 'mood-very_low',
                                    default => 'mood-good'
                                };
                            @endphp
                            <div class="mb-4">
                                <span class="mood-badge {{ $moodClass }}">
                                    <i class="fas fa-smile mr-1.5"></i>
                                    Mood: {{ $note->mood_level_label }}
                                </span>
                            </div>
                        @endif

                        <!-- Session Notes -->
                        <div class="mb-4">
                            <span class="note-label">Session Notes:</span>
                            <div class="note-text">{{ $note->notes }}</div>
                        </div>

                        <!-- Follow-up Actions -->
                        @if($note->follow_up_actions)
                            <div class="mb-4">
                                <span class="note-label">Follow-up Actions:</span>
                                <div class="note-text">{{ $note->follow_up_actions }}</div>
                            </div>
                        @endif

                        <!-- Follow-up Required -->
                        @if($note->requires_follow_up && $note->next_session_date)
                            <div class="followup-box">
                                <i class="fas fa-calendar-check followup-icon"></i>
                                <span class="followup-text">
                                    Follow-up scheduled for {{ $note->next_session_date->format('F j, Y') }}
                                </span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection