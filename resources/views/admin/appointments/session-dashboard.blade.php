@extends('layouts.admin')

@section('title', 'Appointment Sessions - Admin Panel')

@section('content')
    <div class="session-notes-shell relative overflow-hidden min-h-screen bg-[#faf8f5]">
        <div class="session-glow session-glow-1"></div>
        <div class="session-glow session-glow-2"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                    <div class="relative overflow-hidden rounded-xl border border-[#d4af37]/20 bg-white/95 backdrop-blur-sm shadow-sm">
                        <div class="absolute inset-0 bg-gradient-to-br from-[#fdf2f2] via-white to-[#fef9e7]/40"></div>
                        <div class="relative px-4 sm:px-5 py-4 sm:py-5">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg bg-gradient-to-br from-[#5c1a1a] to-[#7a2a2a] text-[#d4af37] shadow-sm flex items-center justify-center shrink-0">
                                    <i class="fas fa-clipboard-list text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full border border-[#d4af37]/20 bg-[#fef9e7]/70 px-2 sm:px-2.5 py-0.5 text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.2em] text-[#7a2a2a] mb-1.5 sm:mb-2">
                                        <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-[#d4af37]"></span>
                                        Session Notes Directory
                                    </div>
                                    <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420]">Appointment Sessions</h1>
                                    <p class="mt-1 text-xs sm:text-sm text-[#6b5e57] max-w-xl">
                                        View and review all appointment session notes across the system in one polished admin workspace.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-xl border border-[#5c1a1a]/10 bg-gradient-to-br from-[#5c1a1a] to-[#3a0c0c] text-white shadow-sm min-w-[200px]">
                        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(circle_at_top_right,#d4af37,transparent_40%)]"></div>
                        <div class="relative h-full px-4 sm:px-5 py-3.5 sm:py-4 flex flex-col justify-between gap-3">
                            <div>
                                <p class="text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.22em] text-white/70">Navigation</p>
                                <p class="text-base sm:text-lg font-bold leading-tight mt-1 sm:mt-1.5">Manage Notes</p>
                                <p class="text-[11px] text-white/80 mt-1 hidden sm:block">Return to appointments overview anytime.</p>
                            </div>
                            <div class="mt-2 sm:mt-3">
                                <a href="{{ route('admin.appointments') }}"
                                   class="inline-flex items-center justify-center w-full sm:w-auto bg-white/15 hover:bg-white/25 border border-white/10 text-[#d4af37] px-3 py-2 rounded-lg transition shadow-sm backdrop-blur-sm text-xs sm:text-sm font-medium">
                                    <i class="fas fa-arrow-left mr-1.5 text-[10px] sm:text-xs"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm mb-5 sm:mb-6">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-[#5c1a1a] via-[#d4af37] to-[#5c1a1a]"></div>

                <div class="px-4 sm:px-5 py-3 border-b border-[#e5e0db]/60">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#fef9e7] flex items-center justify-center text-[#9a7b0a]">
                            <i class="fas fa-sliders-h text-[10px] sm:text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#2c2420]">Filter Session Notes</p>
                            <p class="text-[11px] text-[#6b5e57] hidden sm:block">Refine the records by search term, status, and date range.</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-4">
                    <form method="GET" action="{{ route('admin.appointment-sessions.dashboard') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4">
                            <div class="md:col-span-2">
                                <label for="search" class="filter-label">Search</label>
                                <div class="relative">
                                    <input type="text"
                                           id="search"
                                           name="search"
                                           value="{{ request('search') }}"
                                           placeholder="Search by student name, counselor, student id, or concern..."
                                           class="filter-input pl-9 sm:pl-10">
                                    <div class="absolute inset-y-0 left-0 pl-3 sm:pl-3.5 flex items-center pointer-events-none">
                                        <i class="fas fa-search text-[#a89f97] text-[10px] sm:text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="status" class="filter-label">Status</label>
                                <select id="status" name="status" class="filter-input bg-white">
                                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All</option>
                                    @foreach(\App\Models\Appointment::getStatuses() as $s)
                                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date_range" class="filter-label">Date Range</label>
                                <select id="date_range" name="date_range" class="filter-input bg-white">
                                    <option value="" {{ request('date_range') === null ? 'selected' : '' }}>All Time</option>
                                    <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>This Week</option>
                                    <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>This Month</option>
                                    <option value="upcoming" {{ request('date_range') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="past" {{ request('date_range') === 'past' ? 'selected' : '' }}>Past</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end mt-3 sm:mt-4">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] text-white font-medium shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 text-xs sm:text-sm">
                                <i class="fas fa-filter mr-1.5 text-[10px] sm:text-xs"></i>Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Content -->
            <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm">
                @if($appointments->isEmpty())
                    <div class="text-center py-10 sm:py-12 px-4 sm:px-5">
                        <div class="mx-auto w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#f5f0eb] flex items-center justify-center shadow-inner">
                            <i class="fas fa-clipboard-list text-xl sm:text-2xl text-[#a89f97]"></i>
                        </div>
                        <p class="text-[#6b5e57] text-base font-semibold mt-4">No session notes found.</p>
                        <p class="text-[#8b7e76] text-xs mt-1">Appointments will appear here once session notes are created.</p>
                        <a href="{{ route('admin.appointments') }}"
                           class="inline-flex items-center mt-4 bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] text-[#d4af37] px-4 py-2 rounded-lg hover:shadow-md transition text-sm font-medium">
                            <i class="fas fa-list mr-1.5 text-[10px] sm:text-xs"></i>Go to Appointments
                        </a>
                    </div>
                @else
                    <div class="p-3 sm:p-4 space-y-3 sm:space-y-4">
                        @foreach($appointments as $appointment)
                            <div class="session-card group">
                                <div class="session-card-pattern"></div>

                                <div class="relative p-3.5 sm:p-4">
                                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-3 sm:gap-4">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-start gap-3">
                                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-[#fdf2f2] flex items-center justify-center shadow-inner shrink-0">
                                                    <i class="fas fa-file-medical-alt text-[#7a2a2a] text-xs sm:text-sm"></i>
                                                </div>

                                                <div class="min-w-0">
                                                    <h2 class="text-sm sm:text-base font-semibold text-[#2c2420] truncate">
                                                        {{ $appointment->session_sequence_label ?? ($appointment->booking_type === 'Initial Interview' ? 'Initial Interview' : 'Session') }} -
                                                        {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                                    </h2>

                                                    <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] sm:text-xs text-[#8b7e76]">
                                                        <span class="inline-flex items-center">
                                                            <i class="fas fa-calendar-alt mr-1.5 text-[#a89f97]"></i>
                                                            {{ $appointment->appointment_date->format('M j, Y') }}
                                                        </span>
                                                        <span class="inline-flex items-center">
                                                            <i class="fas fa-clock mr-1.5 text-[#a89f97]"></i>
                                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                                        </span>
                                                        <span class="inline-flex items-center">
                                                            <i class="fas fa-user-tie mr-1.5 text-[#a89f97]"></i>
                                                            {{ $appointment->counselor?->user?->first_name ?? 'N/A' }} {{ $appointment->counselor?->user?->last_name ?? '' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-start lg:items-end gap-2 shrink-0">
                                            <span class="inline-flex px-2.5 py-1 text-[10px] sm:text-[11px] font-semibold rounded-lg bg-[#f5f0eb] text-[#5c4d47] border border-[#e5e0db]/70">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                            </span>
                                            <div class="text-[10px] sm:text-[11px] text-[#8b7e76] inline-flex items-center rounded-lg bg-[#faf8f5] border border-[#e5e0db]/60 px-2.5 py-1">
                                                <i class="fas fa-id-card mr-1.5 text-[#a89f97]"></i>
                                                ID: {{ $appointment->student->student_id }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 rounded-lg border border-[#e5e0db]/50 bg-[#faf8f5]/60 px-3 py-2.5">
                                        <p class="text-xs leading-6 text-[#4a3f3a]">
                                            {{ \Illuminate\Support\Str::limit($appointment->concern, 160) }}
                                        </p>
                                    </div>

                                    <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                        <div class="inline-flex items-center gap-1.5 rounded-full bg-[#fdf2f2] border border-[#7a2a2a]/15 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-[#7a2a2a] w-fit">
                                            <i class="fas fa-clipboard-check text-[9px] sm:text-[10px]"></i>
                                            {{ $appointment->sessionNotes->count() }} note(s)
                                        </div>

                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('admin.appointments.session-notes', $appointment) }}"
                                               class="inline-flex items-center px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-lg border border-[#d4af37]/40 text-[#9a7b0a] hover:bg-[#fef9e7] transition text-[10px] sm:text-xs font-medium shadow-sm">
                                                <i class="fas fa-eye mr-1.5 text-[9px] sm:text-[10px]"></i> View Session Notes
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-[#faf8f5]/50 px-4 py-3 border-t border-[#e5e0db]/60 sm:px-5">
                        {{ $appointments->links() }}
                    </div>
                @endif
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

        .session-notes-shell {
            min-height: 100%;
        }

        .session-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            opacity: 0.3;
        }

        .session-glow-1 {
            top: -20px;
            left: -40px;
            width: 180px;
            height: 180px;
            background: var(--gold-400);
        }

        .session-glow-2 {
            bottom: -30px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: var(--maroon-800);
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

        .session-card {
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
            border: 1px solid rgba(229, 224, 219, 0.8);
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 2px 8px rgba(44, 36, 32, 0.04);
            transition: all 0.2s ease;
        }

        .session-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(44, 36, 32, 0.06);
        }

        .session-card-pattern {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(212, 175, 55, 0.06), transparent 30%);
            pointer-events: none;
        }
    </style>
@endsection