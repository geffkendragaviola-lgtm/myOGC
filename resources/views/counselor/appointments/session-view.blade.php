@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
    <div class="container mx-auto px-6 py-8 max-w-5xl">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">View Appointment Session</h1>
                    <p class="text-gray-600 mt-2">
                        {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                        ({{ $appointment->student->student_id }})
                    </p>
                    <p class="text-gray-500 text-sm">
                        {{ $appointment->student->college->name ?? 'N/A' }} • {{ $appointment->student->course }} • {{ $appointment->student->year_level }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('counselor.appointment-sessions.dashboard') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Sessions
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Appointment Details</h2>

                    <div class="space-y-3">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date</div>
                            <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('F j, Y') }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Time</div>
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</div>
                            <div class="text-sm text-gray-900">{{ ucfirst($appointment->status) }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Booking Type</div>
                            <div class="text-sm text-gray-900">{{ $appointment->booking_type ?: '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Case Number</div>
                            <div class="text-sm text-gray-900">{{ $appointment->case_number ?: '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Student Details</h2>

                    <div class="space-y-3">
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Name</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->full_name }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->email ?: '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Phone Number</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->phone_number ?: '—' }}</div>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('counselor.students.profile', $appointment->student) }}"
                               class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm">
                                <i class="fas fa-user mr-2"></i> View Student Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 mb-1">Session Notes</h2>
                            <p class="text-sm text-gray-500">Read-only view of the latest session note for this appointment.</p>
                        </div>
                        <div>
                            <a href="{{ route('counselor.appointments.session', $appointment) }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg border border-[#7c1d2a]/40 text-[#7c1d2a] hover:bg-[#7c1d2a]/10 transition text-sm">
                                <i class="fas fa-pen mr-2"></i> Edit Session
                            </a>
                        </div>
                    </div>

                    @if(!$latestSessionNote)
                        <div class="mt-6 text-sm text-gray-600">
                            No session notes found for this appointment.
                        </div>
                    @else
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Type of Appointment</div>
                                <div class="text-sm text-gray-900">
                                    {{ $latestSessionNote->appointment_type ? ucwords(str_replace('_', ' ', $latestSessionNote->appointment_type)) : '—' }}
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Session Date</div>
                                <div class="text-sm text-gray-900">
                                    {{ $latestSessionNote->session_date ? $latestSessionNote->session_date->format('F j, Y') : '—' }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Root Causes</div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @php
                                    $rootCauses = is_array($latestSessionNote->root_causes ?? null) ? $latestSessionNote->root_causes : [];
                                @endphp
                                @if(empty($rootCauses))
                                    <span class="text-sm text-gray-700">—</span>
                                @else
                                    @foreach($rootCauses as $cause)
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                            {{ ucwords(str_replace('_', ' ', $cause)) }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Session Notes</div>
                            <div class="mt-2 text-sm text-gray-900 whitespace-pre-line border border-gray-200 rounded-lg p-4 bg-gray-50">
                                {{ $latestSessionNote->notes }}
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Assignment / Follow-up Actions</div>
                            <div class="mt-2 text-sm text-gray-900 whitespace-pre-line border border-gray-200 rounded-lg p-4 bg-gray-50">
                                {{ $latestSessionNote->follow_up_actions ?: '—' }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
