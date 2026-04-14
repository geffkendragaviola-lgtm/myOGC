@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Appointment Management</h1>
                    <p class="text-gray-600 mt-2">Manage student appointments and session notes across all assigned colleges</p>
                    @if(isset($allColleges) && $allColleges->count() > 1)
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-500 mr-2">Assigned to:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach($allColleges as $college)
                                <span class="bg-gray-100 text-[#820000] px-2 py-1 rounded-full text-xs college-badge">
                                    {{ $college->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('counselor.dashboard') }}"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('counselor.appointments.create') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i>Book New Appointment
                    </a>
                    <a href="{{ route('counselor.calendar') }}"
                    class="px-4 py-2 bg-[#F00000] text-white rounded-lg hover:bg-[#D40000] transition">
                        <i class="fas fa-calendar-alt mr-2"></i>View Calendar
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-9 gap-6 mb-6">
            <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('page', 'status', 'referral_direction')) }}&status=all"
               class="bg-white rounded-xl shadow-sm p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <i class="fas fa-calendar-check text-[#F00000] text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Appointments</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? $appointments->total() }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=rejected"
               class="bg-white rounded-xl shadow-sm p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-[#FFF0F0] rounded-lg">
                        <i class="fas fa-times-circle text-[#820000] text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Rejected</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['rejected'] ?? $appointments->where('status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=cancelled"
               class="bg-white rounded-xl shadow-sm p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <i class="fas fa-ban text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Cancelled</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['cancelled'] ?? $appointments->where('status', 'cancelled')->count() }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=pending"
               class="bg-white rounded-xl shadow-sm p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-[#FFF9E6] rounded-lg">
                        <i class="fas fa-clock text-[#FFC917] text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] ?? $appointments->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=approved"
               class="bg-white rounded-xl shadow-sm p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-[#FFF9E6] rounded-lg">
                        <i class="fas fa-check-circle text-[#F8650C] text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Approved</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['approved'] ?? $appointments->where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('page', 'status')) }}&status=completed"
               class="bg-white rounded-xl shadow-sm p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <i class="fas fa-flag-checkered text-[#F00000] text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['completed'] ?? $appointments->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </a>

           

            <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('page', 'referral_direction')) }}&referral_direction={{ ($referralDirection ?? request('referral_direction')) === 'in' ? '' : 'in' }}"
               class="bg-white rounded-xl shadow-sm p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-[#FFF9E6] rounded-lg">
                        <i class="fas fa-reply text-[#F00000] text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Referred In</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['referred_in'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('page', 'referral_direction')) }}&referral_direction={{ ($referralDirection ?? request('referral_direction')) === 'out' ? '' : 'out' }}"
               class="bg-white rounded-xl shadow-sm p-6 block hover:shadow-md transition">
                <div class="flex items-center">
                    <div class="p-3 bg-violet-100 rounded-lg">
                        <i class="fas fa-share text-violet-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Referred Out</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['referred_out'] ?? 0 }}</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Search and Filters Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('counselor.appointments') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Appointments</label>
                        <div class="relative">
                            <input type="text"
                                id="search"
                                name="search"
                                placeholder="Search by student name, ID, college, or concern..."
                                value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000] transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <select id="date_range" name="date_range"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000] transition">
                            <option value="">All Dates</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="upcoming" {{ request('date_range') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ request('date_range') == 'past' ? 'selected' : '' }}>Past Appointments</option>
                        </select>
                    </div>

                    <!-- College Filter -->
                    <div>
                        <label for="college" class="block text-sm font-medium text-gray-700 mb-2">College</label>
                        <select id="college" name="college"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000] transition">
                            <option value="">All Colleges</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}" {{ request('college') == $college->id ? 'selected' : '' }}>
                                    {{ $college->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $appointments->firstItem() ?? 0 }}-{{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() }} appointments
                        @if(isset($allColleges) && $allColleges->count() > 1)
                            <span class="text-[#F00000] ml-2">(Across {{ $allColleges->count() }} colleges)</span>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('counselor.appointments') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            <i class="fas fa-refresh mr-2"></i>Reset
                        </a>
                        <button type="submit"
                                class="px-4 py-2 bg-[#F00000] text-white rounded-lg hover:bg-[#D40000] transition">
                            <i class="fas fa-filter mr-2"></i>Apply Filters
                        </button>
                        
                    </div>
                </div>
            </form>
        </div>

        <!-- Status Filter -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex flex-wrap gap-2">

                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=all"
                class="px-4 py-2 rounded-lg {{ ($status === 'all' || !request('status')) ? 'bg-[#F00000] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    All Appointments
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=pending"
                class="px-4 py-2 rounded-lg {{ $status === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Pending
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=approved"
                class="px-4 py-2 rounded-lg {{ $status === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Approved
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=completed"
                class="px-4 py-2 rounded-lg {{ $status === 'completed' ? 'bg-[#F00000] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Completed
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=reschedule_requested"
                class="px-4 py-2 rounded-lg {{ $status === 'reschedule_requested' ? 'bg-orange-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Reschedule Requested
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=rescheduled"
                class="px-4 py-2 rounded-lg {{ $status === 'rescheduled' ? 'bg-[#F00000] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Rescheduled
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=reschedule_rejected"
                class="px-4 py-2 rounded-lg {{ $status === 'reschedule_rejected' ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Rejected by Student
                </a>
                               <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('referral_direction', 'page')) }}&referral_direction=in"
                class="px-4 py-2 rounded-lg {{ ($referralDirection ?? request('referral_direction')) === 'in' ? 'bg-[#F00000] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Referred In
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('referral_direction', 'page')) }}&referral_direction=out"
                class="px-4 py-2 rounded-lg {{ ($referralDirection ?? request('referral_direction')) === 'out' ? 'bg-violet-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Referred Out
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=rejected"
                class="px-4 py-2 rounded-lg {{ $status === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Rejected
                </a>
                <a href="{{ route('counselor.appointments') }}?{{ http_build_query(request()->except('status', 'page')) }}&status=cancelled"
                class="px-4 py-2 rounded-lg {{ $status === 'cancelled' ? 'bg-gray-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Cancelled
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 fade-in">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 fade-in">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Appointments Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($appointments->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No appointments found.</p>
                    <p class="text-gray-400 text-sm mt-1">When students book appointments, they will appear here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full" id="appointmentsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">College</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($appointments as $appointment)
                                @php
                                    // Define status colors with ALL possible statuses
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'approved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800',
                                        'completed' => 'bg-gray-100 text-[#820000]',
                                        'referred' => 'bg-[#FFF9E6] text-[#820000]',
                                        'rescheduled' => 'bg-[#FFF9E6] text-[#820000]',
                                        'reschedule_requested' => 'bg-orange-100 text-orange-800',
                                        'reschedule_rejected' => 'bg-rose-100 text-rose-800'
                                    ];

                                    // Safe status color lookup with fallback
                                    $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800';

                                    $statusText = $appointment->display_status;
                                    $referralBadgeText = $appointment->referral_badge;

                                    // Add special styling for referred appointments
                                    $rowClass = 'hover:bg-gray-50 transition fade-in';
                                    if ($appointment->is_referred_out) {
                                        $rowClass = 'hover:bg-[#FFF9E6] transition fade-in bg-[#FFF9E6]';
                                    } elseif ($appointment->is_referred_in) {
                                        $rowClass = 'hover:bg-[#FFE100] transition fade-in bg-[#FFF9E6]';
                                    }
                                @endphp
                                <tr class="{{ $rowClass }} cursor-pointer" onclick="showAppointmentDetails({{ $appointment->id }})">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-[#F00000]"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                                    @if($appointment->is_referred_out)
                                                        <span class="ml-2 text-xs bg-[#FFF9E6] text-[#820000] px-2 py-1 rounded-full">
                                                            <i class="fas fa-share"></i> Referred Out
                                                        </span>
                                                    @elseif($appointment->is_referred_in)
                                                        <span class="ml-2 text-xs bg-gray-100 text-[#820000] px-2 py-1 rounded-full">
                                                            <i class="fas fa-reply"></i> Referred In
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $appointment->student->student_id }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $appointment->student->user->sex ?? 'Not provided' }}
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    Year {{ $appointment->student->year_level }}
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Date & Time Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($appointment->status === 'reschedule_requested' && $appointment->proposed_date)
                                            <div class="text-xs font-semibold text-orange-700 uppercase tracking-wide">
                                                New (Proposed)
                                            </div>
                                            <div class="text-sm text-orange-700 font-semibold">
                                                {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j, Y') }}
                                            </div>
                                            <div class="text-sm text-orange-700">
                                                {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($appointment->proposed_end_time)->format('g:i A') }}
                                            </div>
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mt-2">
                                                Old (Current)
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                            </div>
                                        @elseif($appointment->status === 'referred' && $appointment->proposed_date)
                                            <div class="text-xs font-semibold text-[#820000] uppercase tracking-wide">
                                                New (Proposed)
                                            </div>
                                            <div class="text-sm text-[#820000] font-semibold">
                                                {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j, Y') }}
                                            </div>
                                            <div class="text-sm text-[#820000]">
                                                {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($appointment->proposed_end_time)->format('g:i A') }}
                                            </div>
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mt-2">
                                                Old (Current)
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                            </div>
                                        @else
                                            <div class="text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                            </div>
                                        @endif
                                    </td>


                                    <!-- College Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $appointment->student->college->name ?? 'N/A' }}
                                        </div>
                                    </td>

                                    <!-- Booking Type Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-gray-900">
                                                {{ $appointment->booking_type ? ucwords(str_replace('_', ' ', $appointment->booking_type)) : '—' }}{{ $appointment->notes && str_contains(strtolower($appointment->notes), 'follow-up appointment') ? ' - Follow up' : '' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $appointment->session_sequence_label ?? '' }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Status Column -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                                {{ $statusText }}
                                            </span>
                                            @if($referralBadgeText)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-[#FFF9E6] text-[#820000]">
                                                    {{ $referralBadgeText }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Actions Column -->
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation();">
                                        @if(!in_array($appointment->status, ['cancelled', 'rejected'], true))
                                            <div class="flex space-x-2">
                                                <!-- Status Management Actions - Available for current counselor AND referred-to counselor -->
                                                @if(in_array($appointment->getEffectiveCounselorId(), $counselorIdList, true))
                                                    @if($appointment->status === 'pending')
                                                        <!-- Approve button -->
                                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit"
                                                                    class="text-green-600 hover:text-green-900 transition"
                                                                    onclick="return confirm('Approve this appointment?')"
                                                                    title="Approve Appointment">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <!-- Reject/Transfer buttons -->
                                                        <button onclick="showRejectionOptions({{ $appointment->id }})"
                                                                class="text-red-600 hover:text-red-900 transition"
                                                                title="Reject or Transfer Appointment">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @elseif(in_array($appointment->status, ['approved', 'rescheduled'], true))
                                                        <!-- Complete and Cancel buttons -->
                                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit"
                                                                    class="text-[#F00000] hover:text-[#820000] transition"
                                                                    onclick="return confirm('Mark this appointment as completed?')"
                                                                    title="Mark as Completed">
                                                                <i class="fas fa-flag-checkered"></i>
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <button type="submit"
                                                                    class="text-orange-600 hover:text-orange-900 transition"
                                                                    onclick="return confirm('Cancel this appointment?')"
                                                                    title="Cancel Appointment">
                                                                <i class="fas fa-ban"></i>
                                                            </button>
                                                        </form>
                                                    @elseif($appointment->status === 'referred' && in_array($appointment->referred_to_counselor_id, $counselorIdList, true))
                                                        <!-- Special actions for referred appointments where this counselor is the receiver -->
                                                        <form action="{{ route('counselor.appointments.referral.accept', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="text-green-600 hover:text-green-900 transition"
                                                                    onclick="return confirm('Accept this referred appointment and schedule it?')"
                                                                    title="Accept Referred Appointment">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('counselor.appointments.referral.reject', $appointment) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="text-red-600 hover:text-red-900 transition"
                                                                    onclick="return confirm('Reject this referred appointment?')"
                                                                    title="Reject Referred Appointment">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                <!-- Reschedule option for effective counselor -->
                                                @if(in_array($appointment->getEffectiveCounselorId(), $counselorIdList, true) && in_array($appointment->status, ['pending', 'approved', 'referred', 'rescheduled', 'reschedule_rejected'], true))
                                                    <button onclick="showRescheduleModal({{ $appointment->id }}, {{ $appointment->getEffectiveCounselorId() }}, '{{ $appointment->appointment_date->format('Y-m-d') }}')"
                                                            class="text-orange-600 hover:text-orange-900 transition"
                                                            title="Reschedule Appointment">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </button>
                                                @endif
                                                <!-- Referral option for current counselor -->
                                                @if(in_array($appointment->counselor_id, $counselorIdList, true) && in_array($appointment->status, ['pending', 'approved', 'rescheduled', 'reschedule_rejected'], true) && !(isset($referralBadgeText) && $referralBadgeText && \Illuminate\Support\Str::startsWith($referralBadgeText, 'Referred from')))
                                                    <button onclick="showReferralModal({{ $appointment->id }}, '{{ $appointment->appointment_date->format('Y-m-d') }}', {{ $appointment->student_id }}, {{ $appointment->counselor_id }})"
                                                            class="text-[#820000] hover:text-[#820000] transition"
                                                            title="Refer to Another Counselor">
                                                        <i class="fas fa-share"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        @endif

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $appointments->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Appointment Details Modal -->
        <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl w-[900px] mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Appointment Details</h3>
                        <button onclick="closeAppointmentModal()" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="appointmentDetails" class="p-6">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>

        <!-- Rejection Options Modal -->
        <div id="rejectionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Reject Appointment</h3>
                        <button onclick="closeRejectionModal()" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">Confirm rejection for this appointment.</p>

                    <form id="directRejectForm" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <input type="hidden" name="notes" id="rejectionNotes" value="I am unavailable at this time. Please book with another counselor.">
                        <button type="submit"
                                class="w-full text-left p-4 border border-red-300 rounded-lg hover:bg-red-50 transition">
                            <div class="flex items-center">
                                <div class="p-2 bg-[#FFF0F0] rounded-lg mr-3">
                                    <i class="fas fa-times text-red-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-red-800">Reject Appointment</h4>
                                    <p class="text-sm text-red-600">Appointment will be cancelled</p>
                                </div>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Referral Modal -->
        <div id="referralModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Refer Appointment</h3>
                        <button onclick="closeReferralModal()" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <form id="referralForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label for="referralCounselorSelect" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Counselor
                                </label>
                                <select id="referralCounselorSelect" name="referred_to_counselor_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000] transition"
                                        required>
                                    <option value="">Loading counselors...</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Choose a counselor from any college.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                                <div class="border border-gray-200 rounded-xl bg-white p-4 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <button type="button" id="referralCalendarPrev"
                                                class="h-9 w-9 rounded-full border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                            ‹
                                        </button>
                                        <h3 id="referralCalendarMonthLabel" class="text-lg font-semibold text-gray-800"></h3>
                                        <button type="button" id="referralCalendarNext"
                                                class="h-9 w-9 rounded-full border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                            ›
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-7 text-xs font-semibold text-gray-500 mb-2">
                                        <span class="text-center">Sun</span>
                                        <span class="text-center">Mon</span>
                                        <span class="text-center">Tue</span>
                                        <span class="text-center">Wed</span>
                                        <span class="text-center">Thu</span>
                                        <span class="text-center">Fri</span>
                                        <span class="text-center">Sat</span>
                                    </div>
                                    <div id="referralCalendarGrid" class="grid grid-cols-7 gap-2 text-sm"></div>
                                    <p id="referralCalendarStatus" class="mt-3 text-sm text-gray-500">
                                        Select a counselor to load available dates.
                                    </p>
                                </div>
                                <input type="hidden" name="appointment_date" id="referralDateSelect" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots</label>
                                <div id="referralTimeSlots" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">
                                        Select a date to see available time slots
                                    </div>
                                </div>
                                <input type="hidden" name="start_time" id="referralSelectedTime" required>
                            </div>

                            <div>
                                <label for="referral_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason (optional)</label>
                                <textarea id="referral_reason" name="referral_reason" rows="3"
                                          class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#F00000] focus:border-[#F8650C]"
                                          placeholder="Explain the reason for referring the student..."></textarea>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeReferralModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-[#820000] text-white rounded-lg hover:bg-[#820000] transition">
                                    Send Referral Request
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reschedule Modal -->
        <div id="rescheduleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">Reschedule Appointment</h3>
                        <button onclick="closeRescheduleModal()" class="text-gray-500 hover:text-gray-700 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <form id="rescheduleForm" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                                <div class="border border-gray-200 rounded-xl bg-white p-4 shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <button type="button" id="rescheduleCalendarPrev"
                                                class="h-9 w-9 rounded-full border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                            ‹
                                        </button>
                                        <h3 id="rescheduleCalendarMonthLabel" class="text-lg font-semibold text-gray-800"></h3>
                                        <button type="button" id="rescheduleCalendarNext"
                                                class="h-9 w-9 rounded-full border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                                            ›
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-7 text-xs font-semibold text-gray-500 mb-2">
                                        <span class="text-center">Sun</span>
                                        <span class="text-center">Mon</span>
                                        <span class="text-center">Tue</span>
                                        <span class="text-center">Wed</span>
                                        <span class="text-center">Thu</span>
                                        <span class="text-center">Fri</span>
                                        <span class="text-center">Sat</span>
                                    </div>
                                    <div id="rescheduleCalendarGrid" class="grid grid-cols-7 gap-2 text-sm"></div>
                                    <p id="rescheduleCalendarStatus" class="mt-3 text-sm text-gray-500">
                                        Select a counselor to load available dates.
                                    </p>
                                </div>
                                <input type="hidden" name="appointment_date" id="rescheduleDateSelect" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots</label>
                                <div id="rescheduleTimeSlots" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">
                                        Select a date to see available time slots
                                    </div>
                                </div>
                                <input type="hidden" name="start_time" id="rescheduleSelectedTime" required>
                            </div>
                            <div>
                                <label for="reschedule_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason (optional)</label>
                                <textarea id="reschedule_reason" name="reason" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-[#F00000] focus:border-[#F8650C]" placeholder="Explain the reason for rescheduling..."></textarea>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeRescheduleModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-[#F00000] text-white rounded-lg hover:bg-[#D40000] transition">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Export to Excel functionality
            function exportAppointmentsToExcel() {
                // Show loading indicator
                const exportBtn = event.target;
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
                exportBtn.disabled = true;

                try {
                    const table = document.getElementById('appointmentsTable');

                    // Create a copy of the table for export (without action buttons)
                    const exportTable = table.cloneNode(true);

                    // Remove action column from export
                    const headers = exportTable.getElementsByTagName('thead')[0].rows[0].cells;
                    const actionHeaderIndex = Array.from(headers).findIndex(th =>
                        th.textContent.trim() === 'Actions'
                    );

                    if (actionHeaderIndex > -1) {
                        // Remove action header
                        headers[actionHeaderIndex].remove();

                        // Remove action cells from all rows
                        const rows = exportTable.getElementsByTagName('tbody')[0].rows;
                        for (let row of rows) {
                            if (row.cells.length > actionHeaderIndex) {
                                row.deleteCell(actionHeaderIndex);
                            }
                        }
                    }

                    const ws = XLSX.utils.table_to_sheet(exportTable);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Appointments");

                    // Get current date for filename
                    const today = new Date().toISOString().split('T')[0];
                    const fileName = `appointments_${today}.xlsx`;

                    XLSX.writeFile(wb, fileName);

                } catch (error) {
                    console.error('Error exporting to Excel:', error);
                    alert('Error exporting appointments. Please try again.');
                } finally {
                    // Restore button state
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                }
            }

            // Enhanced export with all data (including filtered results)
            function exportAllAppointmentsToExcel() {
                // Get current filters
                const search = document.getElementById('search').value;
                const dateRange = document.getElementById('date_range').value;
                const college = document.getElementById('college').value;
                const status = '{{ $status }}';

                // Show loading
                const exportBtn = event.target;
                const originalText = exportBtn.innerHTML;
                exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Exporting...';
                exportBtn.disabled = true;

                // Create export URL with current filters
                let exportUrl = '{{ route("counselor.appointments.export") }}?';
                const params = new URLSearchParams();

                if (search) params.append('search', search);
                if (dateRange) params.append('date_range', dateRange);
                if (college) params.append('college', college);
                if (status && status !== 'all') params.append('status', status);

                exportUrl += params.toString();

                // Trigger download
                window.location.href = exportUrl;

                // Restore button after a delay
                setTimeout(() => {
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                }, 3000);
            }

            // Rejection Options Modal
            let currentAppointmentId = null;

            function showRejectionOptions(appointmentId) {
                currentAppointmentId = appointmentId;

                // Update form actions with the correct appointment ID
                const directRejectForm = document.getElementById('directRejectForm');
                directRejectForm.action = `/counselor/appointments/${appointmentId}/update-status`;

                document.getElementById('rejectionModal').classList.remove('hidden');
            }

            function closeRejectionModal() {
                document.getElementById('rejectionModal').classList.add('hidden');
                currentAppointmentId = null;
            }

            // Transfer Options Modal
            // Referral Modal
            let referralCounselorId = null;
            let referralCurrentMonth = null;
            let referralSelectedDate = null;
            let referralAvailabilityByDate = new Map();
            let referralAvailabilityRequestId = 0;

            const referralMinDate = new Date();
            referralMinDate.setHours(0, 0, 0, 0);

            function referralFormatDateValue(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function referralFormatMonthLabel(date) {
                return date.toLocaleString('en-US', { month: 'long', year: 'numeric' });
            }

            function referralIsSameDay(a, b) {
                return a && b &&
                    a.getFullYear() === b.getFullYear() &&
                    a.getMonth() === b.getMonth() &&
                    a.getDate() === b.getDate();
            }

            function setReferralCalendarStatus(message, tone = 'muted') {
                const calendarStatus = document.getElementById('referralCalendarStatus');
                if (!calendarStatus) {
                    return;
                }
                calendarStatus.textContent = message;
                calendarStatus.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');
                if (tone === 'success') {
                    calendarStatus.classList.add('text-green-600');
                } else if (tone === 'error') {
                    calendarStatus.classList.add('text-red-600');
                } else {
                    calendarStatus.classList.add('text-gray-500');
                }
            }

            function renderReferralCalendar() {
                const calendarGrid = document.getElementById('referralCalendarGrid');
                const calendarMonthLabel = document.getElementById('referralCalendarMonthLabel');

                if (!calendarGrid || !calendarMonthLabel || !referralCurrentMonth) {
                    return;
                }

                calendarMonthLabel.textContent = referralFormatMonthLabel(referralCurrentMonth);
                calendarGrid.innerHTML = '';

                const firstDayOfMonth = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth(), 1);
                const startDay = firstDayOfMonth.getDay();
                const daysInMonth = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth() + 1, 0).getDate();

                for (let i = 0; i < startDay; i++) {
                    const spacer = document.createElement('div');
                    calendarGrid.appendChild(spacer);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth(), day);
                    const dateValue = referralFormatDateValue(date);
                    const isPast = date < referralMinDate;
                    const availabilityKnown = referralAvailabilityByDate.has(dateValue);
                    const isAvailable = referralAvailabilityByDate.get(dateValue) === true;
                    const isDisabled = !referralCounselorId || isPast || !availabilityKnown || !isAvailable;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = day;
                    button.disabled = isDisabled;
                    button.className = 'h-10 w-10 md:h-11 md:w-11 rounded-lg border text-sm font-medium transition';

                    if (isDisabled) {
                        button.classList.add('border-transparent', 'text-gray-300', 'cursor-not-allowed');
                    } else {
                        button.classList.add('border-[#F00000]/30', 'text-[#F00000]', 'hover:bg-[#F00000]/10');
                    }

                    if (referralSelectedDate && referralIsSameDay(referralSelectedDate, date)) {
                        button.classList.remove('border-[#F00000]/30', 'text-[#F00000]', 'hover:bg-[#F00000]/10');
                        button.classList.add('bg-[#F00000]', 'text-white', 'border-[#F00000]');
                    }

                    button.addEventListener('click', () => {
                        if (button.disabled) {
                            return;
                        }
                        referralSelectedDate = date;
                        document.getElementById('referralDateSelect').value = referralFormatDateValue(date);
                        document.getElementById('referralSelectedTime').value = '';
                        setReferralCalendarStatus(
                            `Selected date: ${date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`,
                            'success'
                        );
                        renderReferralCalendar();
                        loadReferralAvailableSlots();
                    });

                    calendarGrid.appendChild(button);
                }
            }

            async function loadReferralMonthAvailability() {
                referralAvailabilityByDate = new Map();
                renderReferralCalendar();

                if (!referralCounselorId) {
                    setReferralCalendarStatus('Select a counselor to load available dates.');
                    return;
                }

                const requestId = ++referralAvailabilityRequestId;
                setReferralCalendarStatus('Checking available dates...');
                const monthValue = `${referralCurrentMonth.getFullYear()}-${String(referralCurrentMonth.getMonth() + 1).padStart(2, '0')}`;

                try {
                    const response = await fetch(`/appointments/available-dates?counselor_id=${referralCounselorId}&month=${monthValue}&allow_today=1`);
                    const data = await response.json();
                    if (requestId !== referralAvailabilityRequestId) {
                        return;
                    }
                    const availability = data.availability || {};
                    Object.keys(availability).forEach(dateValue => {
                        referralAvailabilityByDate.set(dateValue, availability[dateValue] === true);
                    });
                } catch (error) {
                    if (requestId !== referralAvailabilityRequestId) {
                        return;
                    }
                }

                if (requestId !== referralAvailabilityRequestId) {
                    return;
                }

                const hasAnyAvailability = Array.from(referralAvailabilityByDate.values()).some(value => value);
                if (!hasAnyAvailability) {
                    setReferralCalendarStatus('No available dates for this counselor in the selected month.', 'error');
                } else {
                    setReferralCalendarStatus('Available dates are highlighted. Select a date to continue.');
                }

                if (referralSelectedDate && (!referralAvailabilityByDate.get(referralFormatDateValue(referralSelectedDate)))) {
                    referralSelectedDate = null;
                    document.getElementById('referralDateSelect').value = '';
                    document.getElementById('referralSelectedTime').value = '';
                }

                renderReferralCalendar();
                if (referralSelectedDate) {
                    loadReferralAvailableSlots();
                }
            }

            function loadReferralAvailableSlots() {
                const date = document.getElementById('referralDateSelect').value;
                const timeSlots = document.getElementById('referralTimeSlots');
                const selectedTime = document.getElementById('referralSelectedTime');

                if (!referralCounselorId || !date) {
                    timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a date to see available time slots</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Loading available slots...</div>';

                fetch(`{{ route('appointments.available-slots') }}?counselor_id=${referralCounselorId}&date=${date}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        timeSlots.innerHTML = `<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">${data.message}</div>`;
                        selectedTime.value = '';
                        return;
                    }

                    if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">No working hours for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    const availableSlots = [...data.available_slots].sort((a, b) => a.start.localeCompare(b.start));

                    if (availableSlots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-yellow-700 text-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">No available time slots for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    timeSlots.innerHTML = '';

                    availableSlots.forEach(slot => {
                        const slotElement = document.createElement('button');
                        slotElement.type = 'button';
                        slotElement.className = 'referral-time-slot p-4 border-2 border-gray-200 rounded-lg text-center hover:border-[#F00000] hover:bg-[#FFE100] transition cursor-pointer';
                        slotElement.textContent = slot.display;

                        slotElement.addEventListener('click', function() {
                            document.querySelectorAll('.referral-time-slot').forEach(s => {
                                s.classList.remove('border-[#F00000]', 'bg-gray-100', 'text-[#D40000]');
                                s.classList.add('border-gray-200', 'text-gray-700');
                            });

                            this.classList.remove('border-gray-200', 'text-gray-700');
                            this.classList.add('border-[#F00000]', 'bg-gray-100', 'text-[#D40000]');

                            selectedTime.value = slot.start;
                        });

                        slotElement.dataset.start = slot.start;
                        slotElement.dataset.end = slot.end;
                        slotElement.dataset.status = slot.status;
                        timeSlots.appendChild(slotElement);
                    });
                })
                .catch(() => {
                    timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">Error loading time slots. Please try again.</div>';
                });
            }

            function showReferralModal(appointmentId, currentDate, studentId, currentCounselorId) {
                referralCounselorId = null;
                const modal = document.getElementById('referralModal');
                const form = document.getElementById('referralForm');
                const counselorSelect = document.getElementById('referralCounselorSelect');
                const timeSlots = document.getElementById('referralTimeSlots');
                const dateSelect = document.getElementById('referralDateSelect');
                const selectedTime = document.getElementById('referralSelectedTime');

                form.action = `/counselor/appointments/${appointmentId}/refer`;

                const parsedDate = currentDate ? new Date(`${currentDate}T00:00:00`) : null;
                referralSelectedDate = parsedDate;
                referralCurrentMonth = new Date(
                    (parsedDate || referralMinDate).getFullYear(),
                    (parsedDate || referralMinDate).getMonth(),
                    1
                );

                dateSelect.value = parsedDate ? referralFormatDateValue(parsedDate) : '';
                selectedTime.value = '';
                timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a date to see available time slots</div>';

                counselorSelect.innerHTML = '<option value="">Loading counselors...</option>';

                fetch(`{{ route('counselor.appointments.available-counselors') }}?student_id=${studentId}&current_counselor_id=${currentCounselorId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(counselors => {
                    counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';

                    if (counselors.error || !Array.isArray(counselors) || counselors.length === 0) {
                        counselorSelect.innerHTML = '<option value="">No counselors available</option>';
                        return;
                    }

                    counselors.sort((a, b) => a.display_text.localeCompare(b.display_text));
                    counselors.forEach(counselor => {
                        const option = document.createElement('option');
                        option.value = counselor.id;
                        option.textContent = counselor.display_text || counselor.name;
                        counselorSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    counselorSelect.innerHTML = '<option value="">Error loading counselors</option>';
                });

                counselorSelect.onchange = () => {
                    referralCounselorId = counselorSelect.value || null;
                    referralSelectedDate = parsedDate;
                    dateSelect.value = parsedDate ? referralFormatDateValue(parsedDate) : '';
                    selectedTime.value = '';
                    loadReferralMonthAvailability();
                };

                loadReferralMonthAvailability();
                modal.classList.remove('hidden');
            }

            function closeReferralModal() {
                document.getElementById('referralModal').classList.add('hidden');
                referralCounselorId = null;
                referralSelectedDate = null;
            }

            // Reschedule Modal
            let rescheduleCounselorId = null;
            let rescheduleCurrentMonth = null;
            let rescheduleSelectedDate = null;
            let rescheduleAvailabilityByDate = new Map();
            let rescheduleAvailabilityRequestId = 0;

            const rescheduleMinDate = new Date();
            rescheduleMinDate.setHours(0, 0, 0, 0);

            function rescheduleFormatDateValue(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function rescheduleFormatMonthLabel(date) {
                return date.toLocaleString('en-US', { month: 'long', year: 'numeric' });
            }

            function rescheduleIsSameDay(a, b) {
                return a && b &&
                    a.getFullYear() === b.getFullYear() &&
                    a.getMonth() === b.getMonth() &&
                    a.getDate() === b.getDate();
            }

            function setRescheduleCalendarStatus(message, tone = 'muted') {
                const calendarStatus = document.getElementById('rescheduleCalendarStatus');
                if (!calendarStatus) {
                    return;
                }
                calendarStatus.textContent = message;
                calendarStatus.classList.remove('text-gray-500', 'text-green-600', 'text-red-600');
                if (tone === 'success') {
                    calendarStatus.classList.add('text-green-600');
                } else if (tone === 'error') {
                    calendarStatus.classList.add('text-red-600');
                } else {
                    calendarStatus.classList.add('text-gray-500');
                }
            }

            function renderRescheduleCalendar() {
                const calendarGrid = document.getElementById('rescheduleCalendarGrid');
                const calendarMonthLabel = document.getElementById('rescheduleCalendarMonthLabel');

                if (!calendarGrid || !calendarMonthLabel || !rescheduleCurrentMonth) {
                    return;
                }

                calendarMonthLabel.textContent = rescheduleFormatMonthLabel(rescheduleCurrentMonth);
                calendarGrid.innerHTML = '';

                const firstDayOfMonth = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth(), 1);
                const startDay = firstDayOfMonth.getDay();
                const daysInMonth = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth() + 1, 0).getDate();

                for (let i = 0; i < startDay; i++) {
                    const spacer = document.createElement('div');
                    calendarGrid.appendChild(spacer);
                }

                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth(), day);
                    const dateValue = rescheduleFormatDateValue(date);
                    const isPast = date < rescheduleMinDate;
                    const availabilityKnown = rescheduleAvailabilityByDate.has(dateValue);
                    const isAvailable = rescheduleAvailabilityByDate.get(dateValue) === true;
                    const isDisabled = !rescheduleCounselorId || isPast || !availabilityKnown || !isAvailable;

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.textContent = day;
                    button.disabled = isDisabled;
                    button.className = 'h-10 w-10 md:h-11 md:w-11 rounded-lg border text-sm font-medium transition';

                    if (isDisabled) {
                        button.classList.add('border-transparent', 'text-gray-300', 'cursor-not-allowed');
                    } else {
                        button.classList.add('border-[#F00000]/30', 'text-[#F00000]', 'hover:bg-[#F00000]/10');
                    }

                    if (rescheduleSelectedDate && rescheduleIsSameDay(rescheduleSelectedDate, date)) {
                        button.classList.remove('border-[#F00000]/30', 'text-[#F00000]', 'hover:bg-[#F00000]/10');
                        button.classList.add('bg-[#F00000]', 'text-white', 'border-[#F00000]');
                    }

                    button.addEventListener('click', () => {
                        if (button.disabled) {
                            return;
                        }
                        rescheduleSelectedDate = date;
                        document.getElementById('rescheduleDateSelect').value = rescheduleFormatDateValue(date);
                        document.getElementById('rescheduleSelectedTime').value = '';
                        setRescheduleCalendarStatus(
                            `Selected date: ${date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}`,
                            'success'
                        );
                        renderRescheduleCalendar();
                        loadRescheduleAvailableSlots();
                    });

                    calendarGrid.appendChild(button);
                }
            }

            async function loadRescheduleMonthAvailability() {
                rescheduleAvailabilityByDate = new Map();
                renderRescheduleCalendar();

                if (!rescheduleCounselorId) {
                    setRescheduleCalendarStatus('Select a counselor to load available dates.');
                    return;
                }

                const requestId = ++rescheduleAvailabilityRequestId;
                setRescheduleCalendarStatus('Checking available dates...');
                const monthValue = `${rescheduleCurrentMonth.getFullYear()}-${String(rescheduleCurrentMonth.getMonth() + 1).padStart(2, '0')}`;

                try {
                    const response = await fetch(`/appointments/available-dates?counselor_id=${rescheduleCounselorId}&month=${monthValue}&allow_today=1`);
                    const data = await response.json();
                    if (requestId !== rescheduleAvailabilityRequestId) {
                        return;
                    }
                    const availability = data.availability || {};
                    Object.keys(availability).forEach(dateValue => {
                        rescheduleAvailabilityByDate.set(dateValue, availability[dateValue] === true);
                    });
                } catch (error) {
                    if (requestId !== rescheduleAvailabilityRequestId) {
                        return;
                    }
                }

                if (requestId !== rescheduleAvailabilityRequestId) {
                    return;
                }

                const hasAnyAvailability = Array.from(rescheduleAvailabilityByDate.values()).some(value => value);
                if (!hasAnyAvailability) {
                    setRescheduleCalendarStatus('No available dates for this counselor in the selected month.', 'error');
                } else {
                    setRescheduleCalendarStatus('Available dates are highlighted. Select a date to continue.');
                }

                if (rescheduleSelectedDate && (!rescheduleAvailabilityByDate.get(rescheduleFormatDateValue(rescheduleSelectedDate)))) {
                    rescheduleSelectedDate = null;
                    document.getElementById('rescheduleDateSelect').value = '';
                    document.getElementById('rescheduleSelectedTime').value = '';
                }

                renderRescheduleCalendar();
                if (rescheduleSelectedDate) {
                    loadRescheduleAvailableSlots();
                }
            }

            function loadRescheduleAvailableSlots() {
                const date = document.getElementById('rescheduleDateSelect').value;
                const timeSlots = document.getElementById('rescheduleTimeSlots');
                const selectedTime = document.getElementById('rescheduleSelectedTime');

                if (!rescheduleCounselorId || !date) {
                    timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a date to see available time slots</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Loading available slots...</div>';

                fetch(`{{ route('appointments.available-slots') }}?counselor_id=${rescheduleCounselorId}&date=${date}`, {
                    headers: { 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        timeSlots.innerHTML = `<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">${data.message}</div>`;
                        selectedTime.value = '';
                        return;
                    }

                    if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">No working hours for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    const availableSlots = [...data.available_slots].sort((a, b) => a.start.localeCompare(b.start));

                    if (availableSlots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-yellow-700 text-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg">No available time slots for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    timeSlots.innerHTML = '';

                    availableSlots.forEach(slot => {
                        const slotElement = document.createElement('button');
                        slotElement.type = 'button';
                        slotElement.className = 'reschedule-time-slot p-4 border-2 border-gray-200 rounded-lg text-center hover:border-[#F00000] hover:bg-[#FFE100] transition cursor-pointer';
                        slotElement.textContent = slot.display;

                        slotElement.addEventListener('click', function() {
                            document.querySelectorAll('.reschedule-time-slot').forEach(s => {
                                s.classList.remove('border-[#F00000]', 'bg-gray-100', 'text-[#D40000]');
                                s.classList.add('border-gray-200', 'text-gray-700');
                            });

                            this.classList.remove('border-gray-200', 'text-gray-700');
                            this.classList.add('border-[#F00000]', 'bg-gray-100', 'text-[#D40000]');

                            selectedTime.value = slot.start;
                        });

                        slotElement.dataset.start = slot.start;
                        slotElement.dataset.end = slot.end;
                        slotElement.dataset.status = slot.status;
                        timeSlots.appendChild(slotElement);
                    });
                })
                .catch(() => {
                    timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">Error loading time slots. Please try again.</div>';
                });
            }

            function showRescheduleModal(appointmentId, counselorId, currentDate) {
                rescheduleCounselorId = counselorId;
                const modal = document.getElementById('rescheduleModal');
                const form = document.getElementById('rescheduleForm');
                const timeSlots = document.getElementById('rescheduleTimeSlots');
                const dateSelect = document.getElementById('rescheduleDateSelect');
                const selectedTime = document.getElementById('rescheduleSelectedTime');

                form.action = `/counselor/appointments/${appointmentId}/reschedule`;

                const parsedDate = currentDate ? new Date(`${currentDate}T00:00:00`) : null;
                rescheduleSelectedDate = parsedDate;
                rescheduleCurrentMonth = new Date(
                    (parsedDate || rescheduleMinDate).getFullYear(),
                    (parsedDate || rescheduleMinDate).getMonth(),
                    1
                );

                dateSelect.value = parsedDate ? rescheduleFormatDateValue(parsedDate) : '';
                selectedTime.value = '';
                timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a date to see available time slots</div>';

                loadRescheduleMonthAvailability();
                modal.classList.remove('hidden');
            }

            function closeRescheduleModal() {
                document.getElementById('rescheduleModal').classList.add('hidden');
                rescheduleCounselorId = null;
                rescheduleSelectedDate = null;
            }

            document.getElementById('referralCalendarPrev')?.addEventListener('click', function() {
                const prevMonth = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth() - 1, 1);
                const minMonth = new Date(referralMinDate.getFullYear(), referralMinDate.getMonth(), 1);
                if (prevMonth < minMonth) {
                    return;
                }
                referralCurrentMonth = prevMonth;
                loadReferralMonthAvailability();
            });

            document.getElementById('referralCalendarNext')?.addEventListener('click', function() {
                referralCurrentMonth = new Date(referralCurrentMonth.getFullYear(), referralCurrentMonth.getMonth() + 1, 1);
                loadReferralMonthAvailability();
            });

            document.getElementById('rescheduleCalendarPrev')?.addEventListener('click', function() {
                const prevMonth = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth() - 1, 1);
                const minMonth = new Date(rescheduleMinDate.getFullYear(), rescheduleMinDate.getMonth(), 1);
                if (prevMonth < minMonth) {
                    return;
                }
                rescheduleCurrentMonth = prevMonth;
                loadRescheduleMonthAvailability();
            });

            document.getElementById('rescheduleCalendarNext')?.addEventListener('click', function() {
                rescheduleCurrentMonth = new Date(rescheduleCurrentMonth.getFullYear(), rescheduleCurrentMonth.getMonth() + 1, 1);
                loadRescheduleMonthAvailability();
            });

            // Appointment Details Modal
            function showAppointmentDetails(appointmentId) {
                fetch(`/counselor/appointments/${appointmentId}/details`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const modal = document.getElementById('appointmentModal');
                        const details = document.getElementById('appointmentDetails');

                        details.innerHTML = `
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Student Name</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.student.user.first_name} ${data.student.user.last_name}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Student ID</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.student.student_id}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">College</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.student.college?.name || 'N/A'}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Year Level</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.student.year_level}</p>
                                    </div>
                                </div>
                            

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.formatted_date}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Time</label>
                                        <p class="mt-1 text-sm text-gray-900">${data.formatted_time}</p>
                                    </div>
                                </div>

                                ${(data.appointment.status === 'referred' && data.formatted_proposed_date && data.formatted_proposed_time) ? `
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-[#820000]">Proposed Date</label>
                                        <p class="mt-1 text-sm text-[#820000]">${data.formatted_proposed_date}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-[#820000]">Proposed Time</label>
                                        <p class="mt-1 text-sm text-[#820000]">${data.formatted_proposed_time}</p>
                                    </div>
                                </div>
                                ` : ''}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type of Booking</label>
                                <p class="mt-1 text-sm text-gray-900">${data.appointment.booking_type || 'N/A'}</p>
                            </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Concern</label>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">${data.appointment.concern}</p>
                                </div>

                                ${data.appointment.notes ? `
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Counselor Notes</label>
                                    <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">${data.appointment.notes}</p>
                                </div>
                                ` : ''}

                                ${(data.appointment.is_referred || data.appointment.referral_reason || data.referral?.referred_to_name || data.referral?.referred_from_name) ? `
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Referral Details</label>
                                    <div class="mt-1 p-3 rounded-lg border border-[#FFC917] bg-[#FFF9E6] space-y-1">
                                        ${data.referral?.referred_from_name ? `
                                        <p class="text-sm text-[#820000]"><span class="font-medium">Referred from:</span> ${data.referral.referred_from_name}</p>
                                        ` : ''}
                                        ${data.referral?.referred_to_name ? `
                                        <p class="text-sm text-[#820000]"><span class="font-medium">Referred to:</span> ${data.referral.referred_to_name}</p>
                                        ` : ''}
                                        ${data.formatted_referral_date ? `
                                        <p class="text-sm text-[#820000]"><span class="font-medium">Referral date:</span> ${data.formatted_referral_date}</p>
                                        ` : ''}
                                        ${data.appointment.referral_reason ? `
                                        <div class="pt-2">
                                            <p class="text-xs font-medium text-[#820000]">Reason:</p>
                                            <p class="text-sm text-[#820000] whitespace-pre-line">${data.appointment.referral_reason}</p>
                                        </div>
                                        ` : ''}
                                    </div>
                                </div>
                                ` : ''}

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        ${data.appointment.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                        data.appointment.status === 'approved' ? 'bg-green-100 text-green-800' :
                                        data.appointment.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                        data.appointment.status === 'referred' ? 'bg-[#FFF9E6] text-[#820000]' :
                                        'bg-gray-100 text-gray-800'}">
                                        ${data.appointment.status_display || (data.appointment.status.charAt(0).toUpperCase() + data.appointment.status.slice(1))}
                                    </span>
                                </div>

                                ${data.appointment.has_session_notes ? `
                                <div class="border-t pt-4 mt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Session Notes</h4>
                                    <div class="space-y-3">
                                        ${data.session_notes.map(note => `
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <div class="flex justify-between items-start mb-2">
                                                    <span class="text-xs font-medium text-gray-600">
                                                        ${note.session_date} • ${note.session_type_label}
                                                    </span>
                                                    ${note.mood_level ? `
                                                    <span class="text-xs px-2 py-1 rounded-full
                                                        ${note.mood_level === 'very_good' ? 'bg-green-100 text-green-800' :
                                                        note.mood_level === 'good' ? 'bg-gray-100 text-[#820000]' :
                                                        note.mood_level === 'neutral' ? 'bg-yellow-100 text-yellow-800' :
                                                        note.mood_level === 'low' ? 'bg-orange-100 text-orange-800' :
                                                        'bg-red-100 text-red-800'}">
                                                        ${note.mood_level_label}
                                                    </span>
                                                    ` : ''}
                                                </div>
                                                <p class="text-sm text-gray-700 whitespace-pre-line">${note.notes}</p>
                                                ${note.follow_up_actions ? `
                                                <div class="mt-2">
                                                    <p class="text-xs font-medium text-gray-600">Follow-up:</p>
                                                    <p class="text-sm text-gray-700 whitespace-pre-line">${note.follow_up_actions}</p>
                                                </div>
                                                ` : ''}
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                                ` : ''}

                                ${data.appointment.status === 'completed' && !data.appointment.has_session_notes ? `
                                <div class="border-t pt-4 mt-4">
                                    <a href="/counselor/appointments/${data.appointment.id}/session"
                                    class="inline-flex items-center text-[#F00000] hover:text-[#820000] text-sm">
                                        <i class="fas fa-plus mr-1"></i> Add Session Notes
                                    </a>
                                </div>
                                ` : ''}

                                <div class="border-t pt-4 mt-4 flex justify-end">
                                    <div class="flex gap-2">
                                        ${data.appointment.session_url ? `
                                        <a href="${data.appointment.session_url}"
                                           class="inline-flex items-center px-4 py-2 bg-[#820000] text-white rounded-lg hover:bg-[#820000] transition text-sm">
                                            <i class="fas fa-clipboard mr-2"></i> Open Appointment Session
                                        </a>
                                        ` : ''}
                                        <a href="${data.student.profile_url}"
                                           class="inline-flex items-center px-4 py-2 bg-[#F00000] text-white rounded-lg hover:bg-[#D40000] transition text-sm">
                                            <i class="fas fa-user mr-2"></i> View Student Profile
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;

                        modal.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error fetching appointment details:', error);
                        const modal = document.getElementById('appointmentModal');
                        const details = document.getElementById('appointmentDetails');
                        details.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-exclamation-triangle text-4xl text-red-300 mb-4"></i>
                                <p class="text-red-500">Error loading appointment details. Please try again.</p>
                            </div>
                        `;
                        modal.classList.remove('hidden');
                    });
            }

            function closeAppointmentModal() {
                document.getElementById('appointmentModal').classList.add('hidden');
            }

            // Helper functions for styling
            function getSessionNoteBorderColor(sessionType) {
                const colors = {
                    'initial': 'border-[#F00000]',
                    'follow_up': 'border-green-500',
                    'crisis': 'border-red-500',
                    'regular': 'border-[#820000]'
                };
                return colors[sessionType] || 'border-gray-500';
            }

            function getMoodLevelColor(moodLevel) {
                const colors = {
                    'very_good': 'bg-green-100 text-green-800',
                    'good': 'bg-gray-100 text-[#820000]',
                    'neutral': 'bg-yellow-100 text-yellow-800',
                    'low': 'bg-orange-100 text-orange-800',
                    'very_low': 'bg-red-100 text-red-800'
                };
                return colors[moodLevel] || 'bg-gray-100 text-gray-800';
            }

            // Close modals when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.id === 'appointmentModal') {
                    closeAppointmentModal();
                }
                if (e.target.id === 'rejectionModal') {
                    closeRejectionModal();
                }
                if (e.target.id === 'referralModal') {
                    closeReferralModal();
                }
                if (e.target.id === 'rescheduleModal') {
                    closeRescheduleModal();
                }
            });
        </script>
    @endsection
