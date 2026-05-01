@extends('layouts.admin')

@section('title', 'Appointments - Admin Panel')

@section('content')
    <div class="appointments-shell relative overflow-hidden min-h-screen bg-[#faf8f5]">
        <div class="appointments-glow appointments-glow-1"></div>
        <div class="appointments-glow appointments-glow-2"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
            <!-- Header Section -->
            <div class="mb-5 sm:mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                    <div class="hero-card group">
                        <div class="hero-card-pattern"></div>
                        <div class="relative flex items-start gap-3 p-4 sm:p-5">
                            <div class="hero-icon">
                                <i class="fas fa-calendar-check text-base sm:text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="hero-badge">
                                    <span class="hero-badge-dot"></span>
                                    Appointments Overview
                                </div>
                                <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Appointments</h1>
                                <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                    Manage, review, and monitor all counseling appointments with a cleaner and more polished admin experience.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                            <div class="flex items-center gap-3 text-center sm:text-left">
                                <div class="summary-icon flex-shrink-0">
                                    <i class="fas fa-calendar-week text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="summary-label">This Month</p>
                                    <p class="summary-value">{{ $totalAppointmentsThisMonth }}</p>
                                    <p class="summary-subtext hidden sm:block">Appointments logged</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=all"
                   class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#fdf2f2] text-[#7a2a2a] group-hover:bg-[#fce4e4]">
                            <i class="fas fa-chart-simple text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Total</p>
                            <p class="stat-value">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=pending"
                   class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#fef9e7] text-[#9a7b0a] group-hover:bg-[#fef3d1]">
                            <i class="fas fa-hourglass-half text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Pending</p>
                            <p class="stat-value">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=approved"
                   class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#ecfdf5] text-[#059669] group-hover:bg-[#d1fae5]">
                            <i class="fas fa-circle-check text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Approved</p>
                            <p class="stat-value">{{ $stats['approved'] ?? 0 }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=completed"
                   class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#f8fafc] text-[#475569] group-hover:bg-[#f1f5f9]">
                            <i class="fas fa-circle-dot text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Completed</p>
                            <p class="stat-value">{{ $stats['completed'] ?? 0 }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=rejected_by_student"
                   class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#fff1f2] text-[#be123c] group-hover:bg-[#ffe4e6]">
                            <i class="fas fa-user-xmark text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Rejected by Student</p>
                            <p class="stat-value">{{ $stats['rejected_by_student'] ?? 0 }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=cancelled_by_student"
                   class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#fffbeb] text-[#b45309] group-hover:bg-[#fef3d1]">
                            <i class="fas fa-user-clock text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Cancelled by Student</p>
                            <p class="stat-value">{{ $stats['cancelled_by_student'] ?? 0 }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=referred_total"
                   class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#fef9e7] text-[#7a2a2a] group-hover:bg-[#fef3d1]">
                            <i class="fas fa-right-left text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">Referred</p>
                            <p class="stat-value">{{ $stats['referred_total'] ?? 0 }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=no_show"
                   class="stat-card group">
                    <div class="stat-card-pattern"></div>
                    <div class="relative flex items-center gap-2.5 sm:gap-3">
                        <div class="stat-icon bg-[#f5f0eb] text-[#6b5e57] group-hover:bg-[#e5e0db]">
                            <i class="fas fa-user-slash text-sm sm:text-base"></i>
                        </div>
                        <div>
                            <p class="stat-label">No Show</p>
                            <p class="stat-value">{{ $stats['no_show'] ?? 0 }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Filter Bar -->
            <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm mb-5 sm:mb-6">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-[#5c1a1a] via-[#d4af37] to-[#5c1a1a]"></div>

                <div class="p-3 sm:p-4">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
                        <div>
                            <label class="filter-label">Search</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 sm:left-3.5 top-1/2 -translate-y-1/2 text-[#a89f97] text-[10px] sm:text-xs"></i>
                                <input type="text"
                                       name="search"
                                       value="{{ $search }}"
                                       placeholder="Case #, student..."
                                       class="filter-input pl-9 sm:pl-10" />
                            </div>
                        </div>

                        <div>
                            <label class="filter-label">College</label>
                            <select name="college" class="filter-input bg-white">
                                <option value="">All Colleges</option>
                                @foreach($colleges as $c)
                                    <option value="{{ $c->id }}" {{ $collegeId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="filter-label">Counselor</label>
                            <select name="counselor" class="filter-input bg-white">
                                <option value="">All Counselors</option>
                                @foreach($counselorsList as $c)
                                    <option value="{{ $c->id }}" {{ $counselorId == $c->id ? 'selected' : '' }}>
                                        {{ $c->user->first_name ?? '' }} {{ $c->user->last_name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="filter-label">Date</label>
                            <input type="date" name="date" value="{{ $date }}" class="filter-input bg-white text-[#2c2420]" />
                        </div>

                        <div>
                            <label class="filter-label">Status</label>
                            <select name="status" class="filter-input bg-white">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Statuses</option>
                                <option value="rejected_by_student" {{ $status === 'rejected_by_student' ? 'selected' : '' }}>Rejected by Student</option>
                                <option value="cancelled_by_student" {{ $status === 'cancelled_by_student' ? 'selected' : '' }}>Cancelled by Student</option>
                                <option value="referred_total" {{ $status === 'referred_total' ? 'selected' : '' }}>Referred</option>
                                @foreach($statuses as $s)
                                    @if($s === 'rejected') @continue @endif
                                    <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end gap-2 sm:gap-3">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] text-white font-medium shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 text-xs sm:text-sm">
                                <i class="fas fa-search text-[10px] sm:text-xs"></i>
                                <span>Apply</span>
                            </button>
                            <a href="{{ route('admin.appointments') }}" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 rounded-lg bg-[#f5f0eb] text-[#6b5e57] hover:bg-[#e5e0db] transition font-medium text-xs sm:text-sm">
                                <i class="fas fa-rotate-left"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="relative overflow-hidden rounded-xl border border-[#e5e0db]/80 bg-white/95 backdrop-blur-sm shadow-sm">
                <div class="px-4 sm:px-5 py-3 border-b border-[#e5e0db]/60 bg-[#faf8f5]/50">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#fdf2f2] flex items-center justify-center text-[#7a2a2a]">
                                <i class="fas fa-table-list text-[10px] sm:text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-[#2c2420]">Appointments</p>
                                <p class="text-[11px] text-[#6b5e57] hidden sm:block">Complete record overview</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">
                        <thead>
                            <tr class="bg-[#faf8f5] border-b border-[#e5e0db]/80">
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Case #</span>
                                </th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Student</span>
                                </th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Counselor</span>
                                </th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Schedule</span>
                                </th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Type</span>
                                </th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Status</span>
                                </th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Notes</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#e5e0db]/50">
                            @forelse($appointments as $appointment)
                                @php
                                    $hasNotes = $appointment->sessionNotes->count() > 0;
                                    $notesCount = $appointment->sessionNotes->count();
                                @endphp
                                <tr class="group hover:bg-[#fdf9f6] transition-colors duration-150 cursor-pointer" onclick="showAppointmentDetails({{ $appointment->id }})">
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                        <span class="inline-flex rounded-md border border-[#e5e0db]/70 bg-[#faf8f5] px-2 py-0.5 text-[11px] font-mono font-medium text-[#5c4d47]">
                                            {{ $appointment->case_number ?? ('#' . $appointment->id) }}
                                        </span>
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                        <div class="flex items-center gap-2 sm:gap-2.5">
                                            <div class="w-8 h-8 rounded-md bg-[#fdf2f2] flex items-center justify-center shadow-inner">
                                                <i class="fas fa-user-graduate text-[#7a2a2a] text-[10px] sm:text-xs"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs sm:text-sm font-medium text-[#2c2420] truncate">{{ $appointment->student->user->first_name ?? 'N/A' }} {{ $appointment->student->user->last_name ?? '' }}</p>
                                                <p class="text-[10px] sm:text-[11px] text-[#8b7e76] truncate">{{ $appointment->student->student_id ?? 'No ID' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                        <div class="flex items-center gap-2 sm:gap-2.5">
                                            <div class="w-8 h-8 rounded-md bg-[#f5f0eb] flex items-center justify-center shadow-inner">
                                                <i class="fas fa-user-doctor text-[#8b7e76] text-[10px] sm:text-xs"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-xs sm:text-sm font-medium text-[#2c2420] truncate">{{ $appointment->counselor->user->first_name ?? 'N/A' }} {{ $appointment->counselor->user->last_name ?? '' }}</p>
                                                <p class="text-[10px] sm:text-[11px] text-[#8b7e76] truncate">{{ $appointment->counselor->college->name ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3 whitespace-nowrap">
                                        <p class="text-xs sm:text-sm font-medium text-[#2c2420]">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</p>
                                        <p class="text-[10px] sm:text-[11px] text-[#8b7e76]">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</p>
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] sm:text-[11px] font-medium bg-[#f5f0eb] text-[#6b5e57] border border-[#e5e0db]/70">
                                            {{ $appointment->booking_type ?? 'Regular' }}
                                        </span>
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                        @php
                                            $statusLabels = [
                                                'reschedule_requested' => 'Reschedule Requested (Pending Student Approval)',
                                                'reschedule_rejected' => 'Rejected by Student',
                                                'rescheduled' => 'Scheduled (Rescheduled)',
                                            ];

                                            $statusDisplay = $statusLabels[$appointment->status] ?? ucfirst(str_replace('_', ' ', $appointment->status));

                                            $referralOutcomeDisplay = null;
                                            if ($appointment->referral_outcome) {
                                                $referralOutcomeDisplay = ucfirst(str_replace('_', ' ', $appointment->referral_outcome));
                                            }

                                            if ($appointment->is_referred) {
                                                $originalName = ($appointment->originalCounselor && $appointment->originalCounselor->user)
                                                    ? ($appointment->originalCounselor->user->first_name . ' ' . $appointment->originalCounselor->user->last_name)
                                                    : 'Unknown Counselor';
                                                $referredName = ($appointment->referredCounselor && $appointment->referredCounselor->user)
                                                    ? ($appointment->referredCounselor->user->first_name . ' ' . $appointment->referredCounselor->user->last_name)
                                                    : 'Unknown Counselor';

                                                $suffix = '';
                                                if ($appointment->referral_previous_status === 'rescheduled') {
                                                    $suffix .= ' (Rescheduled)';
                                                }

                                                $statusDisplay = "Referred from {$originalName} to {$referredName}{$suffix}";
                                            }
                                        @endphp

                                        <div class="flex flex-wrap gap-1 sm:gap-1.5">
                                            <span class="inline-flex px-2 py-0.5 text-[10px] sm:text-[11px] font-medium rounded-full shadow-sm
                                                {{ $appointment->status === 'pending' ? 'bg-[#fef9e7] text-[#9a7b0a] border border-[#d4af37]/30' :
                                                   ($appointment->status === 'approved' ? 'bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30' :
                                                   ($appointment->status === 'rejected' ? 'bg-[#fdf2f2] text-[#b91c1c] border border-[#b91c1c]/30' :
                                                   ($appointment->status === 'completed' ? 'bg-[#f5f0eb] text-[#475569] border border-[#e5e0db]/70' :
                                                   ($appointment->status === 'referred' ? 'bg-[#fef9e7] text-[#9a7b0a] border border-[#d4af37]/30' :
                                                   'bg-[#f5f0eb] text-[#6b5e57] border border-[#e5e0db]/70')))) }}">
                                                {{ $statusDisplay }}
                                            </span>

                                            @if($appointment->is_referred && $referralOutcomeDisplay)
                                                <span class="inline-flex px-2 py-0.5 text-[10px] sm:text-[11px] font-medium rounded-full shadow-sm
                                                    {{ $appointment->referral_outcome === 'approved' ? 'bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30' :
                                                       ($appointment->referral_outcome === 'rejected' ? 'bg-[#fdf2f2] text-[#b91c1c] border border-[#b91c1c]/30' : 'bg-[#f5f0eb] text-[#6b5e57] border border-[#e5e0db]/70') }}">
                                                    {{ $referralOutcomeDisplay }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3">
                                        @if($hasNotes)
                                            <div class="inline-flex items-center gap-1.5 rounded-full bg-[#ecfdf5] border border-[#10b981]/20 px-2 py-0.5">
                                                <div class="w-1 h-1 sm:w-1.5 sm:h-1.5 bg-[#10b981] rounded-full animate-pulse"></div>
                                                <span class="text-[10px] sm:text-xs font-medium text-[#059669]">{{ $notesCount }}</span>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-1.5 rounded-full bg-[#f8fafc] border border-[#e5e0db]/60 px-2 py-0.5">
                                                <i class="fas fa-file-lines text-[#a89f97] text-[10px] sm:text-xs"></i>
                                                <span class="text-[10px] sm:text-xs text-[#8b7e76]">None</span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#f5f0eb] flex items-center justify-center shadow-inner">
                                                <i class="fas fa-calendar-xmark text-[#a89f97] text-xl sm:text-2xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-[#6b5e57] font-medium text-sm">No appointments found</p>
                                                <p class="text-xs text-[#8b7e76] mt-1">Try adjusting your search or filters</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                    {{ $appointments->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
                </div>
            </div>

            <!-- Modal -->
            <div id="appointmentModal" class="hidden fixed inset-0 z-[2000] flex items-center justify-center p-4" style="background:rgba(44,36,32,0.5);backdrop-filter:blur(4px);" onclick="if(event.target===this)closeAppointmentModal()">
                <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden" style="border:1px solid #e5e0db;max-height:90vh;display:flex;flex-direction:column;">
                    <!-- Top gradient bar -->
                    <div style="height:4px;background:linear-gradient(90deg,#5c1a1a 0%,#d4af37 50%,#5c1a1a 100%);flex-shrink:0;"></div>

                    <!-- Header -->
                    <div style="background:linear-gradient(135deg,#5c1a1a 0%,#3a0c0c 100%);padding:1.25rem 1.5rem;flex-shrink:0;" class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div id="am-avatar" class="w-12 h-12 rounded-xl overflow-hidden flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2);">
                                <i class="fas fa-calendar-check text-white text-lg"></i>
                            </div>
                            <div>
                                <div id="am-title" class="text-white font-bold text-base">Appointment Details</div>
                                <div id="am-subtitle" class="text-[10px] font-semibold uppercase tracking-widest mt-0.5" style="color:rgba(212,175,55,0.9);">Loading...</div>
                            </div>
                        </div>
                        <button onclick="closeAppointmentModal()" class="text-white/70 hover:text-white transition text-xl leading-none">&times;</button>
                    </div>

                    <!-- Body -->
                    <div id="appointmentDetails" class="p-5 overflow-y-auto flex-1">
                        <div class="flex flex-col items-center justify-center py-12">
                            <div class="w-10 h-10 border-4 border-[#e5e0db] border-t-[#7a2a2a] rounded-full animate-spin"></div>
                            <p class="mt-4 text-sm text-[#8b7e76]">Loading...</p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div id="am-footer" class="px-5 pb-5 pt-3 flex justify-end gap-2 border-t border-[#e5e0db]/60 flex-shrink-0">
                        <button onclick="closeAppointmentModal()" class="px-4 py-2 rounded-lg text-xs font-semibold text-[#6b5e57] bg-white border border-[#e5e0db] hover:bg-[#f5f0eb] transition">Close</button>
                    </div>
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

        .appointments-shell {
            min-height: 100%;
        }

        .appointments-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            opacity: 0.3;
        }

        .appointments-glow-1 {
            top: -20px;
            left: -40px;
            width: 180px;
            height: 180px;
            background: var(--gold-400);
        }

        .appointments-glow-2 {
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

        .primary-btn {
            border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
            display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
            color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
            box-shadow: 0 4px 10px rgba(92,26,26,0.15);
        }
        .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

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

        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.25s ease-out;
        }

        #appointmentModal .overflow-y-auto::-webkit-scrollbar { width: 4px; }
        #appointmentModal .overflow-y-auto::-webkit-scrollbar-track { background: #f5f0eb; border-radius: 99px; }
        #appointmentModal .overflow-y-auto::-webkit-scrollbar-thumb { background: #d4af37; border-radius: 99px; }
        #appointmentModal .overflow-y-auto::-webkit-scrollbar-thumb:hover { background: #c9a227; }

        tbody tr { transition: background-color 0.15s ease; }

        @media (max-width: 639px) {
            .stat-card { padding: 0.7rem; }
            .stat-icon { width: 1.75rem; height: 1.75rem; }
            .stat-value { font-size: 1rem; }
        }
    </style>

    <script>
        function showAppointmentDetails(appointmentId) {
            const modal = document.getElementById('appointmentModal');
            const details = document.getElementById('appointmentDetails');
            const footer = document.getElementById('am-footer');

            document.getElementById('am-subtitle').textContent = 'Loading...';
            details.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-10 h-10 border-4 border-[#e5e0db] border-t-[#7a2a2a] rounded-full animate-spin"></div>
                    <p class="mt-4 text-sm text-[#8b7e76]">Loading...</p>
                </div>`;
            footer.innerHTML = `<button onclick="closeAppointmentModal()" class="px-4 py-2 rounded-lg text-xs font-semibold text-[#6b5e57] bg-white border border-[#e5e0db] hover:bg-[#f5f0eb] transition">Close</button>`;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            fetch(`/admin/appointments/${appointmentId}/details`)
                .then(r => { if (!r.ok) throw new Error(); return r.json(); })
                .then(data => {
                    const a = data.appointment ?? {};
                    const student = data.student ?? {};
                    const counselor = data.counselor ?? {};

                    document.getElementById('am-title').textContent = a.case_number ? `Case #${a.case_number}` : `Appointment #${appointmentId}`;
                    document.getElementById('am-subtitle').textContent = data.formatted_date ?? '';

                    const statusColors = {
                        pending:   'background:#fef9e7;color:#9a7b0a;border:1px solid rgba(212,175,55,0.3)',
                        approved:  'background:#ecfdf5;color:#059669;border:1px solid rgba(16,185,129,0.3)',
                        completed: 'background:#f5f0eb;color:#475569;border:1px solid #e5e0db',
                        rejected:  'background:#fdf2f2;color:#b91c1c;border:1px solid rgba(185,28,28,0.3)',
                        cancelled: 'background:#f5f0eb;color:#6b5e57;border:1px solid #e5e0db',
                        referred:  'background:#fef9e7;color:#9a7b0a;border:1px solid rgba(212,175,55,0.3)',
                    };
                    const sc = statusColors[a.status] ?? 'background:#f5f0eb;color:#6b5e57;border:1px solid #e5e0db';
                    const statusLabel = a.status_display ?? (a.status ?? 'N/A');

                    details.innerHTML = `
                        <div class="space-y-3">
                            <!-- Status badge -->
                            <div class="flex items-center gap-2 flex-wrap">
                                <span style="${sc};padding:0.25rem 0.75rem;border-radius:999px;font-size:0.7rem;font-weight:700;">${statusLabel}</span>
                                ${a.booking_type ? `<span style="background:#f5f0eb;color:#6b5e57;border:1px solid #e5e0db;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.7rem;font-weight:600;">${a.booking_type}</span>` : ''}
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Student -->
                                <div class="rounded-xl p-3" style="background:#faf8f5;border:1px solid #e5e0db;">
                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-[#8b7e76] mb-2 flex items-center gap-1.5">
                                        <i class="fas fa-user-graduate text-[#7a2a2a]"></i> Student
                                    </div>
                                    <div class="text-sm font-bold text-[#2c2420]">${(student.user?.first_name ?? '') + ' ' + (student.user?.last_name ?? '') || 'N/A'}</div>
                                    <div class="text-xs text-[#6b5e57] mt-0.5 font-mono">${student.student_id ?? ''}</div>
                                    <div class="text-xs text-[#8b7e76] mt-0.5">${student.college?.name ?? ''}</div>
                                    <div class="text-xs text-[#8b7e76]">${student.course ?? ''} ${student.year_level ? '· Yr ' + student.year_level : ''}</div>
                                </div>

                                <!-- Counselor -->
                                <div class="rounded-xl p-3" style="background:#faf8f5;border:1px solid #e5e0db;">
                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-[#8b7e76] mb-2 flex items-center gap-1.5">
                                        <i class="fas fa-user-doctor text-[#7a2a2a]"></i> Counselor
                                    </div>
                                    <div class="text-sm font-bold text-[#2c2420]">${counselor.name ?? 'N/A'}</div>
                                    <div class="text-xs text-[#8b7e76] mt-0.5">${counselor.college?.name ?? ''}</div>
                                </div>

                                <!-- Schedule -->
                                <div class="rounded-xl p-3" style="background:#faf8f5;border:1px solid #e5e0db;">
                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-[#8b7e76] mb-2 flex items-center gap-1.5">
                                        <i class="fas fa-calendar-days text-[#7a2a2a]"></i> Schedule
                                    </div>
                                    <div class="text-sm font-bold text-[#2c2420]">${data.formatted_date ?? 'N/A'}</div>
                                    <div class="text-xs text-[#6b5e57] mt-0.5">${data.formatted_time ?? ''}</div>
                                </div>

                                <!-- Concern -->
                                ${a.concern ? `
                                <div class="rounded-xl p-3" style="background:#faf8f5;border:1px solid #e5e0db;">
                                    <div class="text-[10px] font-semibold uppercase tracking-wider text-[#8b7e76] mb-2 flex items-center gap-1.5">
                                        <i class="fas fa-comment-dots text-[#7a2a2a]"></i> Concern
                                    </div>
                                    <div class="text-xs text-[#2c2420] leading-relaxed">${a.concern}</div>
                                </div>` : ''}
                            </div>

                            <!-- Notes -->
                            ${a.notes ? `
                            <div class="rounded-xl p-3" style="background:#faf8f5;border:1px solid #e5e0db;">
                                <div class="text-[10px] font-semibold uppercase tracking-wider text-[#8b7e76] mb-2 flex items-center gap-1.5">
                                    <i class="fas fa-sticky-note text-[#7a2a2a]"></i> Notes
                                </div>
                                <div class="text-xs text-[#2c2420] leading-relaxed whitespace-pre-line">${a.notes}</div>
                            </div>` : ''}

                            <!-- Referral -->
                            ${(data.referral?.referred_from_name || data.referral?.referred_to_name) ? `
                            <div class="rounded-xl p-3" style="background:#fef9e7;border:1px solid rgba(212,175,55,0.3);">
                                <div class="text-[10px] font-semibold uppercase tracking-wider mb-2 flex items-center gap-1.5" style="color:#9a7b0a;">
                                    <i class="fas fa-arrow-right-arrow-left"></i> Referral
                                </div>
                                ${data.referral.referred_from_name ? `<div class="text-xs text-[#4a3f3a]"><span class="font-semibold">From:</span> ${data.referral.referred_from_name}</div>` : ''}
                                ${data.referral.referred_to_name ? `<div class="text-xs text-[#4a3f3a] mt-0.5"><span class="font-semibold">To:</span> ${data.referral.referred_to_name}</div>` : ''}
                                ${a.referral_reason ? `<div class="text-xs text-[#4a3f3a] mt-1">${a.referral_reason}</div>` : ''}
                            </div>` : ''}
                        </div>`;

                    // Update footer with student profile link if available
                    footer.innerHTML = `
                        ${data.student?.profile_url ? `<a href="${data.student.profile_url}" class="px-4 py-2 rounded-lg text-xs font-semibold text-[#fef9e7] transition" style="background:linear-gradient(135deg,#5c1a1a,#7a2a2a);box-shadow:0 4px 10px rgba(92,26,26,0.2);">
                            <i class="fas fa-user-graduate mr-1.5"></i> View Student
                        </a>` : ''}
                        <button onclick="closeAppointmentModal()" class="px-4 py-2 rounded-lg text-xs font-semibold text-[#6b5e57] bg-white border border-[#e5e0db] hover:bg-[#f5f0eb] transition">Close</button>`;
                })
                .catch(() => {
                    details.innerHTML = `
                        <div class="text-center py-10">
                            <i class="fas fa-exclamation-triangle text-[#b91c1c] text-2xl mb-3"></i>
                            <p class="text-sm font-medium text-[#b91c1c]">Error loading details</p>
                            <p class="text-xs text-[#8b7e76] mt-1">Please try again</p>
                        </div>`;
                });
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAppointmentModal();
        });
    </script>
@endsection