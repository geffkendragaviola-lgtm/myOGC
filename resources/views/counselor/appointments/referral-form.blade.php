@extends('layouts.app')

@section('title', 'Refer Appointment - OGC')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center mb-6">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Refer Appointment</h1>
                    <p class="text-gray-600">Refer this appointment to another counselor</p>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-gray-800 mb-2">Appointment Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Student:</span>
                        <p class="font-medium">{{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Date & Time:</span>
                        <p class="font-medium">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                            at {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                        </p>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-600">Concern:</span>
                        <p class="font-medium">{{ $appointment->concern }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('counselor.appointments.refer', $appointment) }}" method="POST">
                @csrf

                <!-- Counselor Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Select Counselor to Refer To</label>
                    <select name="referred_to_counselor_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Choose a counselor</option>
                        @foreach($availableCounselors as $counselor)
                            <option value="{{ $counselor->id }}">
                                {{ $counselor->user->first_name }} {{ $counselor->user->last_name }} - {{ $counselor->position }}
                            </option>
                        @endforeach
                    </select>
                    @error('referred_to_counselor_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Referral Reason -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Reason for Referral</label>
                    <textarea name="referral_reason" rows="4"
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Please explain why you are referring this appointment..."
                              required></textarea>
                    @error('referral_reason')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('counselor.appointments') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-paper-plane mr-2"></i>Refer Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
