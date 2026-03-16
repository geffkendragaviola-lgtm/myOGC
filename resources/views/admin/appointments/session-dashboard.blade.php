@extends('layouts.admin')

@section('title', 'Appointment Sessions - Admin Panel')

@section('content')
    <div class="container mx-auto px-6 py-8 max-w-6xl">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Appointment Sessions</h1>
                    <p class="text-gray-600 mt-2">View all appointment session notes across the system.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.appointments') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Appointments
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('admin.appointment-sessions.dashboard') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <input type="text"
                                   id="search"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Search by student name, counselor, student id, or concern..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All</option>
                            @foreach(\App\Models\Appointment::getStatuses() as $s)
                                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <select id="date_range" name="date_range"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="" {{ request('date_range') === null ? 'selected' : '' }}>All Time</option>
                            <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="upcoming" {{ request('date_range') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ request('date_range') === 'past' ? 'selected' : '' }}>Past</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            @if($appointments->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">No session notes found.</p>
                    <p class="text-gray-400 text-sm mt-1">Appointments will appear here once session notes are created.</p>
                    <a href="{{ route('admin.appointments') }}"
                       class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-list mr-2"></i>Go to Appointments
                    </a>
                </div>
            @else
                <div class="p-6 space-y-5">
                    @foreach($appointments as $appointment)
                        <div class="bg-white border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex items-start gap-3">
                                            <div class="min-w-0">
                                                <h2 class="text-lg font-semibold text-gray-900 truncate">
                                                    {{ $appointment->session_sequence_label ?? ($appointment->booking_type === 'Initial Interview' ? 'Initial Interview' : 'Session') }} -
                                                    {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                                </h2>
                                                <div class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-gray-600">
                                                    <span class="inline-flex items-center">
                                                        <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                                        {{ $appointment->appointment_date->format('M j, Y') }}
                                                    </span>
                                                    <span class="inline-flex items-center">
                                                        <i class="fas fa-clock mr-2 text-gray-400"></i>
                                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                                    </span>
                                                    <span class="inline-flex items-center">
                                                        <i class="fas fa-user-tie mr-2 text-gray-400"></i>
                                                        {{ $appointment->counselor?->user?->first_name ?? 'N/A' }} {{ $appointment->counselor?->user?->last_name ?? '' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-start md:items-end gap-2">
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                        <div class="text-xs text-gray-500 inline-flex items-center">
                                            <i class="fas fa-id-card mr-2 text-gray-400"></i>
                                            Student ID: {{ $appointment->student->student_id }}
                                        </div>
                                    </div>
                                </div>

                                <p class="mt-4 text-sm text-gray-700">
                                    {{ \Illuminate\Support\Str::limit($appointment->concern, 180) }}
                                </p>

                                <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="text-sm text-gray-600">
                                        {{ $appointment->sessionNotes->count() }} note(s)
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.appointments.session-notes', $appointment) }}"
                                           class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition text-sm">
                                            <i class="fas fa-eye mr-2"></i> View Session Notes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
