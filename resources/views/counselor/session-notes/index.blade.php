@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')

        <div class="container mx-auto px-6 py-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Session Notes</h1>
                        <p class="text-gray-600 mt-2">
                            for {{ $student->user->first_name }} {{ $student->user->last_name }}
                            ({{ $student->student_id }})
                        </p>
                        <p class="text-gray-500 text-sm">
                            {{ $student->college->name ?? 'N/A' }} • {{ $student->year_level }}
                        </p>
                        <p class="text-sm text-blue-600 mt-1">
                            <i class="fas fa-clipboard-list mr-1"></i>
                            {{ $sessionNotes->count() }} session note(s) found
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a href="{{ route('counselor.session-notes.create', $student) }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-plus mr-2"></i> New Session Note
                        </a>
                        <a href="{{ route('counselor.appointments') }}"
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Appointments
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <!-- Session Notes List -->
            @if($sessionNotes->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                    <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Session Notes Yet</h3>
                    <p class="text-gray-500">Start documenting your counseling sessions with this student.</p>
                    <a href="{{ route('counselor.session-notes.create', $student) }}"
                       class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        Create First Session Note
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($sessionNotes as $note)
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        {{ $note->session_type_label }}
                                        @if($note->appointment)
                                            <span class="text-sm text-gray-500 ml-2">
                                                (Appointment: {{ $note->appointment->appointment_date->format('M j, Y') }})
                                            </span>
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        {{ $note->session_date->format('F j, Y') }} •
                                        Created: {{ $note->created_at->format('M j, Y g:i A') }}
                                    </p>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('counselor.session-notes.edit', $note) }}"
                                       class="text-blue-600 hover:text-blue-800 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Mood Level -->
                            @if($note->mood_level)
                                <div class="mb-4">
                                    @php
                                        // Fixed nested ternary with proper parentheses
                                        $moodColor = ($note->mood_level === 'very_good') ? 'bg-green-100 text-green-800' :
                                                    (($note->mood_level === 'good') ? 'bg-blue-100 text-blue-800' :
                                                    (($note->mood_level === 'neutral') ? 'bg-yellow-100 text-yellow-800' :
                                                    (($note->mood_level === 'low') ? 'bg-orange-100 text-orange-800' :
                                                    'bg-red-100 text-red-800')));
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $moodColor }}">
                                        <i class="fas fa-smile mr-1"></i>
                                        Mood: {{ $note->mood_level_label }}
                                    </span>
                                </div>
                            @endif

                            <!-- Session Notes -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Session Notes:</h4>
                                <p class="text-gray-600 whitespace-pre-line">{{ $note->notes }}</p>
                            </div>

                            <!-- Follow-up Actions -->
                            @if($note->follow_up_actions)
                                <div class="mb-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Follow-up Actions:</h4>
                                    <p class="text-gray-600 whitespace-pre-line">{{ $note->follow_up_actions }}</p>
                                </div>
                            @endif

                            <!-- Follow-up Required -->
                            @if($note->requires_follow_up && $note->next_session_date)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-check text-yellow-600 mr-2"></i>
                                        <span class="text-sm font-medium text-yellow-800">
                                            Follow-up scheduled for {{ $note->next_session_date->format('F j, Y') }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Removed pagination section since we're using a Collection -->
            @endif
        </div>
    </div>


@endsection
