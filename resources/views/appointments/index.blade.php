@extends('layouts.student')

@section('title', 'Student Dashboard - OGC')

@section('content')
<div class="container mx-auto px-6 py-8">

    {{-- Header Section --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">My Appointments</h1>
        <a href="{{ route('appointments.create') }}"
           class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Book New Appointment
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistics Cards --}}
    @php
        $stats = [
            'total' => $appointments->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'approved' => $appointments->where('status', 'approved')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'referred' => $appointments->where('status', 'referred')->count(),
            'with_assignments' => $appointments->where('status', 'completed')->filter(function($appointment) {
                return $appointment->latestSessionNote && $appointment->latestSessionNote->follow_up_actions;
            })->count()
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        {{-- Total Appointments --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Appointments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        {{-- Approved --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Approved</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        {{-- Referred --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg mr-4">
                    <i class="fas fa-exchange-alt text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Referred</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['referred'] }}</p>
                </div>
            </div>
        </div>

        {{-- With Assignments --}}
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-lg mr-4">
                    <i class="fas fa-tasks text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">With Assignments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['with_assignments'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Search and Filters Section --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('appointments.index') }}" class="space-y-4 md:space-y-0 md:grid md:grid-cols-4 md:gap-4">

            {{-- Search by Date --}}
            <div>
                <label for="search_date" class="block text-sm font-medium text-gray-700 mb-2">Search by Date</label>
                <input type="date"
                       name="search_date"
                       id="search_date"
                       value="{{ request('search_date') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                <select name="status"
                        id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="reschedule_requested" {{ request('status') == 'reschedule_requested' ? 'selected' : '' }}>Reschedule Requested</option>
                    <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                    <option value="reschedule_rejected" {{ request('status') == 'reschedule_rejected' ? 'selected' : '' }}>Rejected by Student</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="referred" {{ request('status') == 'referred' ? 'selected' : '' }}>Referred</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Assignment Filter --}}
            <div>
                <label for="has_assignment" class="block text-sm font-medium text-gray-700 mb-2">Assignment Filter</label>
                <select name="has_assignment"
                        id="has_assignment"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Appointments</option>
                    <option value="yes" {{ request('has_assignment') == 'yes' ? 'selected' : '' }}>With Assignments</option>
                    <option value="no" {{ request('has_assignment') == 'no' ? 'selected' : '' }}>Without Assignments</option>
                </select>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-end space-x-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
                <a href="{{ route('appointments.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                    <i class="fas fa-refresh mr-2"></i> Reset
                </a>
            </div>
        </form>

        {{-- Active Filters Display --}}
        @if(request()->anyFilled(['search_date', 'status', 'has_assignment']))
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-sm">
                    <span class="text-gray-600">Active filters:</span>
                    @if(request('search_date'))
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full flex items-center">
                            Date: {{ \Carbon\Carbon::parse(request('search_date'))->format('M j, Y') }}
                            <a href="{{ request()->fullUrlWithQuery(['search_date' => null]) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full flex items-center">
                            @php
                                $filterStatusLabels = [
                                    'reschedule_rejected' => 'Rejected by Student',
                                    'reschedule_requested' => 'Reschedule Requested (Pending Student Approval)',
                                    'rescheduled' => 'Scheduled (Rescheduled)',
                                ];
                            @endphp
                            Status: {{ $filterStatusLabels[request('status')] ?? ucwords(str_replace('_', ' ', request('status'))) }}
                            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="ml-1 text-green-600 hover:text-green-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('has_assignment'))
                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full flex items-center">
                            Assignments: {{ request('has_assignment') == 'yes' ? 'With Assignments' : 'Without Assignments' }}
                            <a href="{{ request()->fullUrlWithQuery(['has_assignment' => null]) }}" class="ml-1 text-purple-600 hover:text-purple-800">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                </div>
                <span class="text-sm text-gray-500">
                    {{ $appointments->count() }} appointment(s) found
                </span>
            </div>
        </div>
        @endif
    </div>

    {{-- Quick Filter Buttons --}}
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('appointments.index') }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ !request('status') && !request('has_assignment') && !request('search_date') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-list mr-2"></i> All Appointments
            </a>
            <a href="{{ route('appointments.index', ['status' => 'pending']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-clock mr-2"></i> Pending
            </a>
            <a href="{{ route('appointments.index', ['status' => 'approved']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-check mr-2"></i> Approved
            </a>
            <a href="{{ route('appointments.index', ['status' => 'reschedule_requested']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'reschedule_requested' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-calendar-alt mr-2"></i> Reschedule Requested
            </a>
            <a href="{{ route('appointments.index', ['status' => 'rescheduled']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'rescheduled' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-calendar-alt mr-2"></i> Rescheduled
            </a>
            <a href="{{ route('appointments.index', ['status' => 'reschedule_rejected']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'reschedule_rejected' ? 'bg-rose-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-times mr-2"></i> Rejected by Student
            </a>
            <a href="{{ route('appointments.index', ['status' => 'completed']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'completed' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-check-double mr-2"></i> Completed
            </a>
            <a href="{{ route('appointments.index', ['status' => 'referred']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'referred' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-exchange-alt mr-2"></i> Referred
            </a>
            <a href="{{ route('appointments.index', ['status' => 'rejected']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-times mr-2"></i> Rejected
            </a>
            <a href="{{ route('appointments.index', ['status' => 'cancelled']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('status') == 'cancelled' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-ban mr-2"></i> Cancelled
            </a>
            <a href="{{ route('appointments.index', ['has_assignment' => 'yes']) }}"
               class="px-4 py-2 rounded-lg transition flex items-center {{ request('has_assignment') == 'yes' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                <i class="fas fa-tasks mr-2"></i> With Assignments
            </a>
        </div>
    </div>

    {{-- Appointments Table --}}
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        @if($appointments->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg">No appointments found.</p>
                @if(request()->anyFilled(['search_date', 'status', 'has_assignment']))
                    <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
                    <a href="{{ route('appointments.index') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                        Clear all filters
                    </a>
                @else
                    <a href="{{ route('appointments.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                        Book your first appointment
                    </a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Counselor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Concern</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Referral Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($appointments as $appointment)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                    </div>
                                    @if($appointment->status === 'reschedule_requested' && $appointment->proposed_date)
                                        <div class="text-xs text-orange-700 mt-1">
                                            Proposed: {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j, Y') }}
                                            {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($appointment->proposed_end_time)->format('g:i A') }}
                                        </div>
                                        @if($appointment->reschedule_reason)
                                            <div class="text-xs text-orange-600">
                                                Reason: {{ $appointment->reschedule_reason }}
                                            </div>
                                        @endif
                                    @elseif($appointment->status === 'referred' && $appointment->proposed_date)
                                        <div class="text-xs text-purple-700 mt-1">
                                            Proposed: {{ \Carbon\Carbon::parse($appointment->proposed_date)->format('M j, Y') }}
                                            {{ \Carbon\Carbon::parse($appointment->proposed_start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($appointment->proposed_end_time)->format('g:i A') }}
                                        </div>
                                        @if($appointment->referral_reason)
                                            <div class="text-xs text-purple-600">
                                                Reason: {{ $appointment->referral_reason }}
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $appointment->counselor->user->first_name }} {{ $appointment->counselor->user->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $appointment->counselor->position }}
                                    </div>
                                    @if($appointment->is_referred && $appointment->original_counselor_id)
                                        <div class="text-xs text-purple-600 mt-1">
                                            <i class="fas fa-exchange-alt mr-1"></i>
                                            Originally with: {{ $appointment->originalCounselor->user->first_name }} {{ $appointment->originalCounselor->user->last_name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $appointment->concern }}">
                                        {{ $appointment->concern }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'referred' => 'bg-purple-100 text-purple-800',
                                            'rescheduled' => 'bg-indigo-100 text-indigo-800',
                                            'reschedule_requested' => 'bg-orange-100 text-orange-800',
                                            'reschedule_rejected' => 'bg-rose-100 text-rose-800'
                                        ];
                                        $statusLabels = [
                                            'rescheduled' => 'Scheduled (Rescheduled)',
                                            'reschedule_requested' => 'Reschedule Requested (Pending Student Approval)',
                                            'reschedule_rejected' => 'Rejected by Student',
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$appointment->status] }}">
                                        {{ $appointment->status_with_referral ?? ($statusLabels[$appointment->status] ?? ucwords(str_replace('_', ' ', $appointment->status))) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appointment->status === 'referred' && $appointment->is_referred)
                                        <div class="text-sm">
                                            <div class="font-medium text-purple-700">
                                                <i class="fas fa-user-md mr-1"></i>
                                                {{ $appointment->referredCounselor->user->first_name }} {{ $appointment->referredCounselor->user->last_name }}
                                            </div>
                                            <div class="text-xs text-purple-600 mt-1">
                                                <i class="fas fa-university mr-1"></i>
                                                {{ $appointment->referredCounselor->college->name ?? 'N/A' }}
                                                @if($appointment->student->college_id != $appointment->referredCounselor->college_id)
                                                    <span class="text-orange-600 ml-1">(Different College)</span>
                                                @endif
                                            </div>
                                            @if($appointment->referral_reason)
                                                <button type="button"
                                                        onclick="showReferralReason(
                                                            '{{ addslashes($appointment->referral_reason) }}',
                                                            '{{ $appointment->originalCounselor->user->first_name }} {{ $appointment->originalCounselor->user->last_name }}',
                                                            '{{ $appointment->referredCounselor->user->first_name }} {{ $appointment->referredCounselor->user->last_name }}',
                                                            {{ $appointment->student->college_id != $appointment->referredCounselor->college_id ? 'true' : 'false' }}
                                                        )"
                                                        class="text-xs text-purple-600 hover:text-purple-800 mt-1 flex items-center">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    View referral reason
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($appointment->status === 'completed')
                                        @if($appointment->latestSessionNote && $appointment->latestSessionNote->follow_up_actions)
                                            <button type="button"
                                                    onclick="showFollowUpActions('{{ addslashes($appointment->latestSessionNote->follow_up_actions) }}')"
                                                    class="bg-green-100 text-green-800 text-xs px-3 py-2 rounded-full flex items-center hover:bg-green-200 transition cursor-pointer">
                                                <i class="fas fa-tasks mr-2"></i>
                                                <span>View Assignment</span>
                                            </button>
                                        @else
                                            <span class="bg-gray-100 text-gray-600 text-xs px-3 py-2 rounded-full flex items-center">
                                                <i class="fas fa-times-circle mr-2"></i>
                                                <span>No Assignment</span>
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($appointment->status === 'reschedule_requested' && Auth::user()->role === 'student')
                                        <div class="flex items-center space-x-2">
                                            <form action="{{ route('appointments.reschedule.accept', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-green-700 hover:text-green-900 px-3 py-1 border border-green-300 rounded hover:bg-green-50 transition"
                                                        onclick="return confirm('Accept the new appointment time?')">
                                                    <i class="fas fa-check mr-1"></i>Accept
                                                </button>
                                            </form>
                                            <form action="{{ route('appointments.reschedule.reject', $appointment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-300 rounded hover:bg-red-50 transition"
                                                        onclick="return confirm('Reject the proposed time and keep the original schedule?')">
                                                    <i class="fas fa-times mr-1"></i>Reject
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($appointment->status === 'referred' && Auth::user()->role === 'student')
                                        @if($appointment->proposed_date && $appointment->proposed_start_time && $appointment->proposed_end_time)
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('appointments.referral.accept', $appointment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="text-green-700 hover:text-green-900 px-3 py-1 border border-green-300 rounded hover:bg-green-50 transition"
                                                            onclick="return confirm('Accept the referral schedule with the new counselor?')">
                                                        <i class="fas fa-check mr-1"></i>Accept
                                                    </button>
                                                </form>
                                                <form action="{{ route('appointments.referral.reject', $appointment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-300 rounded hover:bg-red-50 transition"
                                                            onclick="return confirm('Cancel this referral and keep your original counselor?')">
                                                        <i class="fas fa-times mr-1"></i>Cancel
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-purple-600 italic">Awaiting referral details</span>
                                        @endif
                                    @elseif(in_array($appointment->status, ['pending', 'approved', 'rescheduled']) && Auth::user()->role === 'student')
                                        <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-300 rounded hover:bg-red-50 transition"
                                                    onclick="return confirm('Are you sure you want to cancel this appointment? The time slot will become available for others.')">
                                                <i class="fas fa-times mr-1"></i>Cancel
                                            </button>
                                        </form>
                                    @elseif($appointment->status === 'reschedule_requested')
                                        <span class="text-orange-600 italic">Awaiting your response</span>
                                    @elseif($appointment->status === 'cancelled')
                                        <span class="text-gray-500 italic">Cancelled</span>
                                    @elseif($appointment->status === 'rejected')
                                        <span class="text-red-500 italic">Rejected</span>
                                    @elseif($appointment->status === 'reschedule_rejected')
                                        <span class="text-rose-500 italic">Rejected by student</span>
                                    @elseif($appointment->status === 'referred')
                                        <span class="text-purple-500 italic">Referred</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Modal for Follow-up Actions --}}
<div id="followUpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-tasks mr-2 text-blue-600"></i>Your Assignments
                </h3>
                <button onclick="closeFollowUpModal()" class="text-gray-400 hover:text-gray-600 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2">
                <p id="followUpContent" class="text-gray-700 whitespace-pre-line p-4 bg-gray-50 rounded-lg max-h-96 overflow-y-auto border border-gray-200"></p>
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeFollowUpModal()"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-times mr-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal for Referral Reason --}}
<div id="referralReasonModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-info-circle mr-2 text-purple-600"></i>Referral Reason
                </h3>
                <button onclick="closeReferralReasonModal()" class="text-gray-400 hover:text-gray-600 text-lg">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mt-2">
                <div id="referralCounselorInfo" class="mb-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                    <p class="text-sm text-purple-700" id="counselorInfoText"></p>
                </div>
                <p id="referralReasonContent" class="text-gray-700 whitespace-pre-line p-4 bg-purple-50 rounded-lg max-h-96 overflow-y-auto border border-purple-200"></p>
            </div>
            <div class="flex justify-end mt-4">
                <button onclick="closeReferralReasonModal()"
                        class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition flex items-center">
                    <i class="fas fa-times mr-2"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to show follow-up actions modal
function showFollowUpActions(followUpActions) {
    // Set the follow-up actions content
    document.getElementById('followUpContent').textContent = followUpActions || 'No assignments provided.';

    // Show the modal
    document.getElementById('followUpModal').classList.remove('hidden');
}

// Function to close follow-up actions modal
function closeFollowUpModal() {
    document.getElementById('followUpModal').classList.add('hidden');
}

// Function to show referral reason modal
function showReferralReason(reason, originalCounselorName = '', referredCounselorName = '', isDifferentCollege = false) {
    // Set the referral reason content
    document.getElementById('referralReasonContent').textContent = reason || 'No referral reason provided.';

    // Build counselor info text
    let counselorInfo = '';
    if (originalCounselorName) {
        counselorInfo += `Originally with: ${originalCounselorName}`;
    }
    if (referredCounselorName) {
        counselorInfo += originalCounselorName ? ` → Referred to: ${referredCounselorName}` : `Referred to: ${referredCounselorName}`;
    }
    if (isDifferentCollege) {
        counselorInfo += ' (Different College)';
    }

    document.getElementById('counselorInfoText').textContent = counselorInfo || 'No counselor information available.';
    document.getElementById('referralReasonModal').classList.remove('hidden');
}

// Function to close referral reason modal
function closeReferralReasonModal() {
    document.getElementById('referralReasonModal').classList.add('hidden');
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'followUpModal') {
        closeFollowUpModal();
    }
    if (e.target.id === 'referralReasonModal') {
        closeReferralReasonModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFollowUpModal();
        closeReferralReasonModal();
    }
});



</script>

<style>
.whitespace-pre-line {
    white-space: pre-line;
}

/* Modal animations */
.fixed {
    transition: opacity 0.3s ease;
}
</style>
@endsection
