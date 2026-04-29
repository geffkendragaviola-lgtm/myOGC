@extends('layouts.admin')

@section('title', 'Appointments - Admin Panel')

@section('content')
    <div class="appointments-shell relative min-h-screen bg-[#faf8f5]">
        <div class="appointments-glow appointments-glow-1"></div>
        <div class="appointments-glow appointments-glow-2"></div>

        <div class="relative max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Header Section -->
            <div class="mb-6 sm:mb-8">
                <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                    <div class="relative overflow-hidden rounded-xl border border-[#d4af37]/20 bg-white/95 backdrop-blur-sm shadow-sm">
                        <div class="absolute inset-0 bg-gradient-to-br from-[#fdf2f2] via-white to-[#fef9e7]/40"></div>
                        <div class="relative px-4 sm:px-5 py-4 sm:py-5">
                            <div class="flex items-start gap-3 sm:gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-[#5c1a1a] to-[#7a2a2a] text-[#d4af37] shadow-sm flex items-center justify-center shrink-0">
                                    <i class="fas fa-calendar-check text-base sm:text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="inline-flex items-center gap-1.5 sm:gap-2 rounded-full border border-[#d4af37]/20 bg-[#fef9e7]/70 px-2 sm:px-2.5 py-0.5 text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.2em] text-[#7a2a2a] mb-1.5 sm:mb-2">
                                        <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full bg-[#d4af37]"></span>
                                        Appointments Overview
                                    </div>
                                    <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420]">Appointments</h1>
                                    <p class="mt-1 text-xs sm:text-sm text-[#6b5e57] max-w-xl">
                                        Manage, review, and monitor all counseling appointments with a cleaner and more polished admin experience.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-xl border border-[#5c1a1a]/10 bg-gradient-to-br from-[#5c1a1a] to-[#3a0c0c] text-white shadow-sm min-w-[200px] sm:min-w-[240px]">
                        <div class="absolute inset-0 opacity-15 bg-[radial-gradient(circle_at_top_right,#d4af37,transparent_40%)]"></div>
                        <div class="relative h-full px-4 sm:px-5 py-3.5 sm:py-4 flex items-center gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg bg-white/10 border border-white/10 backdrop-blur-sm flex items-center justify-center shrink-0">
                                <i class="fas fa-calendar-week text-base sm:text-[15px] text-[#d4af37]"></i>
                            </div>
                            <div>
                                <p class="text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.22em] text-white/70">This Month</p>
                                <p class="text-xl sm:text-2xl font-bold leading-none mt-1">{{ $totalAppointmentsThisMonth }}</p>
                                <p class="text-[11px] text-white/80 mt-1">Appointments logged</p>
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
                <div class="px-4 sm:px-5 py-3 border-b border-[#e5e0db]/60">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#fef9e7] flex items-center justify-center text-[#9a7b0a]">
                            <i class="fas fa-sliders text-[10px] sm:text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-[#2c2420]">Filter Appointments</p>
                            <p class="text-[11px] text-[#6b5e57] hidden sm:block">Refine results without altering stored data.</p>
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-4">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="filter-label">Search</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 sm:left-3.5 top-1/2 -translate-y-1/2 text-[#a89f97] text-[10px] sm:text-xs"></i>
                                <input type="text"
                                       name="search"
                                       value="{{ $search }}"
                                       placeholder="Case #, student, counselor..."
                                       class="filter-input pl-9 sm:pl-10" />
                            </div>
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
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-center whitespace-nowrap">
                                    <span class="text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.15em]">Action</span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-[#e5e0db]/50">
                            @forelse($appointments as $appointment)
                                @php
                                    $hasNotes = $appointment->sessionNotes->count() > 0;
                                    $notesCount = $appointment->sessionNotes->count();
                                @endphp
                                <tr class="group hover:bg-[#fdf9f6] transition-colors duration-150">
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

                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3 text-center">
                                        <button onclick="showAppointmentDetails({{ $appointment->id }})"
                                                class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-lg border border-[#7a2a2a]/20 bg-[#fdf2f2] text-[#7a2a2a] font-medium hover:bg-[#5c1a1a] hover:text-[#d4af37] hover:border-[#5c1a1a] shadow-sm transition-all duration-200 text-[10px] sm:text-xs">
                                            <i class="fas fa-eye text-[9px] sm:text-[10px]"></i>
                                            <span>View</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center">
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

                <!-- Enhanced Pagination Section -->
                @if($appointments->hasPages())
                <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                    <div class="flex items-center justify-center">
                        <div class="pagination-wrap flex items-center gap-2 justify-center">
                            {{ $appointments->appends(request()->query())->links() }}
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
                        background: #5c1a1a;
                        color: white;
                    }
                    .pagination-wrap a {
                        background: white; color: #6b5e57; border: 1px solid #e5e0db;
                    }
                    .pagination-wrap a:hover {
                        background: #fdf2f2; color: #5c1a1a; border-color: rgba(212, 175, 55, 0.4);
                    }
                </style>
                @else
                <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                    <div class="flex items-center justify-center gap-2 text-[10px] sm:text-xs text-[#8b7e76]">
                        <i class="fas fa-circle-check text-[#059669]"></i>
                        <span>Showing all <span class="font-semibold text-[#2c2420]">{{ $appointments->count() }}</span> appointments</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Modal -->
            <div id="appointmentModal" class="fixed inset-0 bg-[#2c2420]/40 backdrop-blur-sm flex items-center justify-center hidden z-50 transition-all duration-200 p-3 sm:p-4">
                <div class="bg-white rounded-xl shadow-xl shadow-[#2c2420]/10 w-full max-w-3xl max-h-[90vh] overflow-y-auto animate-fade-in-up border border-[#e5e0db]/60">
                    <div class="sticky top-0 bg-white/95 backdrop-blur-sm rounded-t-xl px-4 sm:px-5 py-3.5 sm:py-4 border-b border-[#e5e0db]/60 z-10">
                        <div class="flex justify-between items-center gap-3 sm:gap-4">
                            <div class="flex items-center gap-2.5 sm:gap-3">
                                <div class="w-8 h-8 sm:w-9 sm:h-9 bg-[#fdf2f2] rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-[#7a2a2a] text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold text-[#2c2420]">Appointment Details</h3>
                                    <p class="text-[10px] sm:text-[11px] text-[#8b7e76] mt-0.5 hidden sm:block">Review detailed information for this record.</p>
                                </div>
                            </div>
                            <button onclick="closeAppointmentModal()" class="w-7 h-7 sm:w-8 sm:h-8 bg-[#f5f0eb] rounded-lg flex items-center justify-center text-[#6b5e57] hover:bg-[#e5e0db] transition">
                                <i class="fas fa-xmark text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div id="appointmentDetails" class="p-4 sm:p-5">
                        <div class="flex flex-col items-center justify-center py-8 sm:py-10">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 border-3 sm:border-4 border-[#e5e0db] border-t-[#7a2a2a] rounded-full animate-spin"></div>
                            <p class="mt-3 text-xs sm:text-sm text-[#8b7e76]">Loading appointment details...</p>
                        </div>
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
            
            details.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="w-12 h-12 border-4 border-[#e5e0db] border-t-[#7a2a2a] rounded-full animate-spin"></div>
                    <p class="mt-4 text-[#8b7e76]">Loading appointment details...</p>
                </div>
            `;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            fetch(`/admin/appointments/${appointmentId}/details`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    let sessionNotesHtml = '';

                    details.innerHTML = `
                        <div class="space-y-4 sm:space-y-5">
                            <!-- Student & Counselor Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                                <div class="bg-gradient-to-br from-[#fdf2f2]/60 to-transparent rounded-lg p-3.5 sm:p-4 border border-[#e5e0db]/60">
                                    <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#fdf2f2] rounded-md flex items-center justify-center">
                                            <i class="fas fa-user-graduate text-[#7a2a2a] text-xs sm:text-sm"></i>
                                        </div>
                                        <h4 class="text-sm font-medium text-[#4a3f3a]">Student Information</h4>
                                    </div>
                                    <div class="space-y-1.5 sm:space-y-2">
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Name:</span> <span class="text-[#2c2420]">${data.student?.user?.first_name || 'N/A'} ${data.student?.user?.last_name || ''}</span></p>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Student ID:</span> <span class="text-[#2c2420]">${data.student?.student_id || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">College:</span> <span class="text-[#2c2420]">${data.student?.college?.name || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Year Level:</span> <span class="text-[#2c2420]">${data.student?.year_level || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Course:</span> <span class="text-[#2c2420]">${data.student?.course || 'N/A'}</span></p>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-br from-[#f5f0eb]/60 to-transparent rounded-lg p-3.5 sm:p-4 border border-[#e5e0db]/60">
                                    <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#f5f0eb] rounded-md flex items-center justify-center">
                                            <i class="fas fa-user-doctor text-[#6b5e57] text-xs sm:text-sm"></i>
                                        </div>
                                        <h4 class="text-sm font-medium text-[#4a3f3a]">Counselor Information</h4>
                                    </div>
                                    <div class="space-y-1.5 sm:space-y-2">
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Name:</span> <span class="text-[#2c2420]">${data.counselor?.name || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">College:</span> <span class="text-[#2c2420]">${data.counselor?.college?.name || 'N/A'}</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Appointment Details Card -->
                            <div class="bg-[#faf8f5] rounded-lg p-3.5 sm:p-4 border border-[#e5e0db]/60">
                                <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#fdf2f2] rounded-md flex items-center justify-center">
                                        <i class="fas fa-calendar-days text-[#7a2a2a] text-xs sm:text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-medium text-[#4a3f3a]">Appointment Details</h4>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                                    <div>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Date:</span> <span class="text-[#2c2420]">${data.formatted_date || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Time:</span> <span class="text-[#2c2420]">${data.formatted_time || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Type:</span> <span class="text-[#2c2420]">${data.appointment?.booking_type || 'N/A'}</span></p>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Category:</span> <span class="text-[#2c2420]">${data.appointment?.booking_category ? data.appointment.booking_category.charAt(0).toUpperCase() + data.appointment.booking_category.slice(1).replace('-', ' ') : 'N/A'}</span></p>
                                    </div>
                                    <div>
                                        <p class="text-sm"><span class="font-medium text-[#8b7e76]">Status:</span> 
                                            <span class="inline-flex ml-2 px-1.5 py-0.5 text-xs font-medium rounded-full
                                                ${data.appointment?.status === 'pending' ? 'bg-[#fef9e7] text-[#9a7b0a]' :
                                                data.appointment?.status === 'approved' ? 'bg-[#ecfdf5] text-[#059669]' :
                                                data.appointment?.status === 'completed' ? 'bg-[#f5f0eb] text-[#475569]' :
                                                'bg-[#f5f0eb] text-[#6b5e57]'}">
                                                ${data.appointment?.status_display || 'N/A'}
                                            </span>
                                        </p>
                                        ${data.appointment?.case_number ? `<p class="text-sm mt-1"><span class="font-medium text-[#8b7e76]">Case #:</span> <span class="text-[#2c2420] font-mono">${data.appointment.case_number}</span></p>` : ''}
                                    </div>
                                </div>
                            </div>



                            <!-- Counselor Notes Section -->
                            ${data.appointment?.notes ? `
                            <div class="bg-[#faf8f5] rounded-lg p-3.5 sm:p-4 border border-[#e5e0db]/60">
                                <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#f5f0eb] rounded-md flex items-center justify-center">
                                        <i class="fas fa-sticky-note text-[#6b5e57] text-xs sm:text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-medium text-[#4a3f3a]">Counselor's Notes</h4>
                                </div>
                                <p class="text-sm text-[#4a3f3a] whitespace-pre-line leading-relaxed">${data.appointment.notes}</p>
                            </div>
                            ` : ''}

                            <!-- Referral Details -->
                            ${(data.appointment?.is_referred || data.appointment?.referral_reason || data.referral?.referred_to_name || data.referral?.referred_from_name) ? `
                            <div class="bg-[#fef9e7] rounded-lg p-3.5 sm:p-4 border border-[#d4af37]/30">
                                <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-[#d4af37]/20 rounded-md flex items-center justify-center">
                                        <i class="fas fa-arrow-right-arrow-left text-[#9a7b0a] text-xs sm:text-sm"></i>
                                    </div>
                                    <h4 class="text-sm font-medium text-[#9a7b0a]">Referral Details</h4>
                                </div>
                                <div class="space-y-1.5 sm:space-y-2 text-sm">
                                    ${data.referral?.referred_from_name ? `<p><span class="font-medium text-[#9a7b0a]">Referred from:</span> <span class="text-[#4a3f3a]">${data.referral.referred_from_name}</span></p>` : ''}
                                    ${data.referral?.referred_to_name ? `<p><span class="font-medium text-[#9a7b0a]">Referred to:</span> <span class="text-[#4a3f3a]">${data.referral.referred_to_name}</span></p>` : ''}
                                    ${data.formatted_referral_date ? `<p><span class="font-medium text-[#9a7b0a]">Referral date:</span> <span class="text-[#4a3f3a]">${data.formatted_referral_date}</span></p>` : ''}
                                    ${data.appointment?.referral_reason ? `<p class="mt-2"><span class="font-medium text-[#9a7b0a]">Reason:</span><br><span class="text-[#4a3f3a]">${data.appointment.referral_reason}</span></p>` : ''}
                                </div>
                            </div>
                            ` : ''}

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap justify-end gap-2.5 sm:gap-3 pt-3 sm:pt-4 border-t border-[#e5e0db]/60">
                                ${data.student?.profile_url ? `
                                <a href="${data.student.profile_url}"
                                   class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#5c1a1a] text-[#d4af37] rounded-lg hover:bg-[#7a2a2a] transition text-xs sm:text-sm font-medium">
                                    <i class="fas fa-user-graduate text-xs sm:text-sm"></i>
                                    View Student Profile
                                </a>
                                ` : ''}
                                <button onclick="closeAppointmentModal()"
                                        class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-[#f5f0eb] text-[#6b5e57] rounded-lg hover:bg-[#e5e0db] transition text-xs sm:text-sm font-medium">
                                    <i class="fas fa-xmark text-xs sm:text-sm"></i>
                                    Close
                                </button>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error fetching appointment details:', error);
                    details.innerHTML = `
                        <div class="text-center py-12">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#fdf2f2] rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-exclamation-triangle text-[#b91c1c] text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-[#b91c1c] font-medium">Error loading appointment details</p>
                            <p class="text-xs sm:text-sm text-[#8b7e76] mt-1">Please try again</p>
                            <button onclick="closeAppointmentModal()" class="mt-4 px-4 py-2 bg-[#f5f0eb] text-[#6b5e57] rounded-lg hover:bg-[#e5e0db] transition text-sm">Close</button>
                        </div>
                    `;
                });
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAppointmentModal();
            }
        });
    </script>
@endsection