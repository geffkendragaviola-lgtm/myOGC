@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
    <div class="container mx-auto px-6 py-8 max-w-5xl">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Appointment Session</h1>
                    <p class="text-gray-600 mt-2">
                        {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                        ({{ $appointment->student->student_id }})
                    </p>
                    <p class="text-gray-500 text-sm">
                        {{ $appointment->student->college->name ?? 'N/A' }} • {{ $appointment->student->course }} • {{ $appointment->student->year_level }}
                    </p>
                </div>
                <div class="flex gap-3">
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
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Category</div>
                            <div class="text-sm text-gray-900">{{ $appointment->status === 'referred' ? 'Referred' : 'Booked' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Case Number</div>
                            <div class="text-sm text-gray-900">{{ $appointment->case_number ?: '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date of Referral</div>
                            <div class="text-sm text-gray-900">{{ $appointment->referral_requested_at ? $appointment->referral_requested_at->format('F j, Y g:i A') : '—' }}</div>
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
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Age</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->age ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date of Birth</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->birthdate ? $appointment->student->user->birthdate->format('Y-m-d') : '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sex</div>
                            <div class="text-sm text-gray-900">{{ $appointment->student->user->sex ? ucfirst($appointment->student->user->sex) : '—' }}</div>
                        </div>
                        <div>
                            <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Address</div>
                            <div class="text-sm text-gray-900 whitespace-pre-line">{{ $appointment->student->user->address ?: '—' }}</div>
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
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Session Notes</h2>

                    <form action="{{ route('counselor.appointments.session.store', $appointment) }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="appointment_type" class="block text-sm font-medium text-gray-700 mb-2">Type of Appointment *</label>
                                <select name="appointment_type"
                                        id="appointment_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required>
                                    <option value="">Select type</option>
                                    @foreach($appointmentTypeOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('appointment_type', $latestSessionNote->appointment_type ?? '') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('appointment_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Root Causes</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach($rootCauseOptions as $value => $label)
                                    <label class="flex items-center space-x-2 text-sm text-gray-700">
                                        <input type="checkbox"
                                               name="root_causes[]"
                                               value="{{ $value }}"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                               {{ in_array($value, old('root_causes', $latestSessionNote->root_causes ?? []), true) ? 'checked' : '' }}>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('root_causes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('root_causes.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Session Notes *</label>
                            <textarea name="notes"
                                      id="notes"
                                      rows="10"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      required>{{ old('notes', $latestSessionNote->notes ?? '') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="follow_up_actions" class="block text-sm font-medium text-gray-700 mb-2">Assignment / Follow-up Actions</label>
                            <textarea name="follow_up_actions"
                                      id="follow_up_actions"
                                      rows="5"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('follow_up_actions', $latestSessionNote->follow_up_actions ?? '') }}</textarea>
                            @error('follow_up_actions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Save Session Notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
