@extends('layouts.admin')

@section('title', 'Session Notes - Admin Panel')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Session Notes</h1>
                <p class="text-sm text-gray-600">Appointment {{ $appointment->case_number ?? ('#' . $appointment->id) }}</p>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('admin.appointments') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Appointments
                </a>
                @if($appointment->student)
                    <a href="{{ route('admin.students.edit', $appointment->student) }}" class="px-4 py-2 bg-[#F00000] text-white rounded-lg hover:bg-[#D40000] transition">
                        <i class="fas fa-user mr-2"></i>View Student Details
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-sm font-semibold text-gray-700 mb-2">Student</h2>
                    <div class="text-sm text-gray-900">
                        {{ $appointment->student?->user?->first_name ?? 'N/A' }} {{ $appointment->student?->user?->last_name ?? '' }}
                    </div>
                    <div class="text-xs text-gray-500">{{ $appointment->student?->student_id ?? '' }}</div>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-700 mb-2">Counselor</h2>
                    <div class="text-sm text-gray-900">
                        {{ $appointment->counselor?->user?->first_name ?? 'N/A' }} {{ $appointment->counselor?->user?->last_name ?? '' }}
                    </div>
                    <div class="text-xs text-gray-500">{{ $appointment->counselor?->college?->name ?? '' }}</div>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-700 mb-2">Date &amp; Time</h2>
                    <div class="text-sm text-gray-900">
                        {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') : 'N/A' }}
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $appointment->start_time ? \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') : '' }}
                        @if($appointment->end_time)
                            - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                        @endif
                    </div>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-700 mb-2">Status</h2>
                    <div class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Notes ({{ $sessionNotes->count() }})</h2>
            </div>

            <div class="p-6 space-y-4">
                @forelse($sessionNotes as $note)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-3">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $note->session_date?->format('M j, Y') ?? 'Session' }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $note->session_type_label ?? $note->session_type }}
                                    @if(!empty($note->mood_level_label))
                                        • {{ $note->mood_level_label }}
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                Created {{ $note->created_at?->format('M j, Y g:i A') }}
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <div class="text-xs font-medium text-gray-600 mb-1">Notes</div>
                                <div class="text-sm text-gray-800 whitespace-pre-line">{{ $note->notes }}</div>
                            </div>

                            @if(!empty($note->follow_up_actions))
                                <div>
                                    <div class="text-xs font-medium text-gray-600 mb-1">Follow-up Actions</div>
                                    <div class="text-sm text-gray-800 whitespace-pre-line">{{ $note->follow_up_actions }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-sm text-gray-500">No session notes found for this appointment.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
