@extends('layouts.admin')

@section('title', 'Session Notes - Admin Panel')

@section('content')
    <div class="session-detail-shell relative overflow-hidden min-h-screen bg-[#faf8f5]">
        <div class="session-detail-glow session-detail-glow-1"></div>
        <div class="session-detail-glow session-detail-glow-2"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="mb-6 sm:mb-8">
                <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                    <div class="relative overflow-hidden rounded-xl border border-[#d4af37]/20 bg-white/95 backdrop-blur-sm shadow-sm">
                        <div class="absolute inset-0 bg-gradient-to-br from-[#fdf2f2] via-white to-[#fef9e7]/40"></div>
                        <div class="relative px-4 sm:px-5 py-4 sm:py-5">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg bg-gradient-to-br from-[#5c1a1a] to-[#7a2a2a] text-[#d4af37] shadow-sm flex items-center justify-center shrink-0">
                                    <i class="fas fa-notes-medical text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full border border-[#d4af37]/20 bg-[#fef9e7]/70 px-2 sm:px-2.5 py-0.5 text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.2em] text-[#7a2a2a] mb-1.5 sm:mb-2">
                                        <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-[#d4af37]"></span>
                                        Session Notes
                                    </div>
                                    <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420]">Session Notes</h1>
                                    <p class="mt-1 text-xs sm:text-sm text-[#6b5e57]">
                                        Appointment {{ $appointment->case_number ?? ('#' . $appointment->id) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-xl border border-[#5c1a1a]/10 bg-gradient-to-br from-[#5c1a1a] to-[#3a0c0c] text-white shadow-sm">
                        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(circle_at_top_right,#d4af37,transparent_40%)]"></div>
                        <div class="relative h-full px-4 sm:px-5 py-3.5 sm:py-4 flex flex-col sm:flex-row xl:flex-col justify-center gap-2.5">
                            <a href="{{ route('admin.appointments') }}"
                               class="inline-flex items-center justify-center w-full sm:w-auto px-3 py-2 rounded-lg bg-white/15 border border-white/10 text-white hover:bg-white/25 transition font-medium backdrop-blur-sm text-xs sm:text-sm">
                                <i class="fas fa-arrow-left mr-1.5 text-[10px] sm:text-xs"></i>Back to Appointments
                            </a>
                            @if($appointment->student)
                                <a href="{{ route('admin.students.edit', $appointment->student) }}"
                                   class="inline-flex items-center justify-center w-full sm:w-auto px-3 py-2 rounded-lg bg-[#fef9e7] text-[#7a2a2a] hover:bg-[#f5e6b8] transition font-semibold shadow-sm text-xs sm:text-sm">
                                    <i class="fas fa-user mr-1.5 text-[10px] sm:text-xs"></i>View Student Details
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm mb-5 sm:mb-6">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-[#5c1a1a] via-[#d4af37] to-[#5c1a1a]"></div>

                <div class="px-4 sm:px-5 py-3 border-b border-[#e5e0db]/60">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#fdf2f2] flex items-center justify-center text-[#7a2a2a]">
                            <i class="fas fa-address-card text-[10px] sm:text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#2c2420]">Appointment Information</p>
                            <p class="text-[11px] text-[#6b5e57] hidden sm:block">Overview of the student, counselor, schedule, and appointment status.</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                        <div class="info-card">
                            <div class="info-card-icon bg-[#fdf2f2] text-[#7a2a2a]">
                                <i class="fas fa-user-graduate text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <h2 class="info-title">Student</h2>
                                <div class="info-value">
                                    {{ $appointment->student?->user?->first_name ?? 'N/A' }} {{ $appointment->student?->user?->last_name ?? '' }}
                                </div>
                                <div class="info-subvalue">{{ $appointment->student?->student_id ?? '' }}</div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon bg-[#f5f0eb] text-[#6b5e57]">
                                <i class="fas fa-user-tie text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <h2 class="info-title">Counselor</h2>
                                <div class="info-value">
                                    {{ $appointment->counselor?->user?->first_name ?? 'N/A' }} {{ $appointment->counselor?->user?->last_name ?? '' }}
                                </div>
                                <div class="info-subvalue">{{ $appointment->counselor?->college?->name ?? '' }}</div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon bg-[#fef9e7] text-[#9a7b0a]">
                                <i class="fas fa-calendar-alt text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <h2 class="info-title">Date &amp; Time</h2>
                                <div class="info-value">
                                    {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') : 'N/A' }}
                                </div>
                                <div class="info-subvalue">
                                    {{ $appointment->start_time ? \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') : '' }}
                                    @if($appointment->end_time)
                                        - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-card-icon bg-[#f5f0eb] text-[#6b5e57]">
                                <i class="fas fa-signal text-xs sm:text-sm"></i>
                            </div>
                            <div>
                                <h2 class="info-title">Status</h2>
                                <div class="inline-flex px-2.5 py-1 mt-1 text-[10px] sm:text-[11px] font-semibold rounded-lg bg-[#f5f0eb] text-[#5c4d47] border border-[#e5e0db]/70">
                                    {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm">
                <div class="px-4 sm:px-5 py-3 border-b border-[#e5e0db]/60 bg-[#faf8f5]/50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#fdf2f2] flex items-center justify-center text-[#7a2a2a]">
                            <i class="fas fa-clipboard-list text-[10px] sm:text-xs"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-medium text-[#2c2420]">Notes ({{ $sessionNotes->count() }})</h2>
                            <p class="text-[11px] text-[#6b5e57] hidden sm:block">Detailed notes, mood indicators, and follow-up actions for this appointment.</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-4 space-y-3 sm:space-y-4">
                    @forelse($sessionNotes as $note)
                        <div class="note-card group">
                            <div class="note-card-pattern"></div>

                            <div class="relative p-3.5 sm:p-4">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2 mb-3">
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-[#2c2420]">
                                            {{ $note->session_date?->format('M j, Y') ?? 'Session' }}
                                        </div>
                                        <div class="text-xs text-[#8b7e76] mt-1 flex flex-wrap items-center gap-1.5">
                                            <span>{{ $note->session_type_label ?? $note->session_type }}</span>
                                            @if(!empty($note->mood_level_label))
                                                <span class="text-[#c4b8b1]">•</span>
                                                <span>{{ $note->mood_level_label }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-[10px] sm:text-xs text-[#8b7e76] inline-flex items-center rounded-lg bg-[#faf8f5] border border-[#e5e0db]/60 px-2.5 py-1 w-fit">
                                        <i class="fas fa-clock mr-1.5 text-[#a89f97] text-[9px] sm:text-[10px]"></i>
                                        Created {{ $note->created_at?->format('M j, Y g:i A') }}
                                    </div>
                                </div>

                                <div class="space-y-2.5 sm:space-y-3">
                                    <div class="rounded-lg border border-[#e5e0db]/50 bg-[#faf8f5]/60 p-3">
                                        <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.16em] text-[#8b7e76] mb-1.5">Notes</div>
                                        <div class="text-xs sm:text-[11px] leading-6 text-[#4a3f3a] whitespace-pre-line">{{ $note->notes }}</div>
                                    </div>

                                    @if(!empty($note->follow_up_actions))
                                        <div class="rounded-lg border border-[#d4af37]/30 bg-[#fef9e7]/50 p-3">
                                            <div class="text-[9px] sm:text-[10px] font-bold uppercase tracking-[0.16em] text-[#9a7b0a] mb-1.5">Follow-up Actions</div>
                                            <div class="text-xs sm:text-[11px] leading-6 text-[#4a3f3a] whitespace-pre-line">{{ $note->follow_up_actions }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 sm:py-10">
                            <div class="mx-auto w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#f5f0eb] flex items-center justify-center shadow-inner">
                                <i class="fas fa-clipboard text-xl sm:text-2xl text-[#a89f97]"></i>
                            </div>
                            <div class="mt-4 text-xs font-medium text-[#6b5e57]">No session notes found for this appointment.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --maroon-900: #3a0c0c;
            --maroon-800: #5c1a1a;
            --maroon-700: #7a2a2a;
            --gold-400: #d4af37;
            --bg-warm: #faf8f5;
            --border-soft: #e5e0db;
            --text-primary: #2c2420;
            --text-secondary: #6b5e57;
            --text-muted: #8b7e76;
        }

        .session-detail-shell {
            min-height: 100%;
        }

        .session-detail-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            opacity: 0.3;
        }

        .session-detail-glow-1 {
            top: -20px;
            left: -40px;
            width: 180px;
            height: 180px;
            background: var(--gold-400);
        }

        .session-detail-glow-2 {
            bottom: -30px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: var(--maroon-800);
        }

        .info-card {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.85rem;
            border: 1px solid var(--border-soft);
            border-radius: 0.75rem;
            background: rgba(255,255,255,0.96);
            box-shadow: 0 2px 8px rgba(44,36,32,0.04);
            transition: box-shadow 0.2s ease;
        }

        .info-card:hover {
            box-shadow: 0 4px 12px rgba(44,36,32,0.06);
        }

        .info-card-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-title {
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .info-subvalue {
            font-size: 0.72rem;
            color: var(--text-secondary);
            margin-top: 0.1rem;
        }

        .note-card {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid var(--border-soft);
            background: rgba(255,255,255,0.96);
            box-shadow: 0 2px 8px rgba(44,36,32,0.04);
            transition: all 0.2s ease;
        }

        .note-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(44,36,32,0.06);
        }

        .note-card-pattern {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
            pointer-events: none;
        }

        @media (max-width: 639px) {
            .info-card { padding: 0.75rem; gap: 0.65rem; }
            .info-card-icon { width: 1.8rem; height: 1.8rem; }
            .info-value { font-size: 0.8rem; }
        }
    </style>
@endsection