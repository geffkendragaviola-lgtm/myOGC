@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')


        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8 max-w-4xl">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Edit Session Notes</h1>
                        <p class="text-gray-600 mt-2">
                            for {{ $sessionNote->student->user->first_name }} {{ $sessionNote->student->user->last_name }}
                            ({{ $sessionNote->student->student_id }})
                        </p>
                        <p class="text-gray-500 text-sm">
                            {{ $sessionNote->student->college->name ?? 'N/A' }} • {{ $sessionNote->student->year_level }}
                        </p>
                        <p class="text-blue-600 text-sm mt-1">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Session Date: {{ $sessionNote->session_date->format('F j, Y') }}
                        </p>
                    </div>
                    <a href="{{ route('counselor.session-notes.index', $sessionNote->student) }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Notes
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form action="{{ route('counselor.session-notes.update', $sessionNote) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Session Date -->
                        <div>
                            <label for="session_date" class="block text-sm font-medium text-gray-700 mb-2">Session Date *</label>
                            <input type="date"
                                   name="session_date"
                                   id="session_date"
                                   value="{{ old('session_date', $sessionNote->session_date->format('Y-m-d')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                            @error('session_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Session Type -->
                        <div>
                            <label for="session_type" class="block text-sm font-medium text-gray-700 mb-2">Session Type *</label>
                            <select name="session_type"
                                    id="session_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                <option value="">Select session type</option>
                                @foreach($sessionTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('session_type', $sessionNote->session_type) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('session_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mood Level -->
                        <div>
                            <label for="mood_level" class="block text-sm font-medium text-gray-700 mb-2">Student's Mood Level</label>
                            <select name="mood_level"
                                    id="mood_level"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Select mood level</option>
                                @foreach($moodLevels as $value => $label)
                                    <option value="{{ $value }}" {{ old('mood_level', $sessionNote->mood_level) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mood_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

          <!-- Session Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Session Notes *</label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="8"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Document the session details, topics discussed, observations, and any important insights..."
                                  required>{{ old('notes', $sessionNote->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Minimum 10 characters required</p>
                    </div>

                    <!-- Follow-up Actions -->
                    <div class="mt-6">
                        <label for="follow_up_actions" class="block text-sm font-medium text-gray-700 mb-2">Follow-up Actions</label>
                        <textarea name="follow_up_actions"
                                  id="follow_up_actions"
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Any recommended actions, homework, or follow-up tasks for the student...">{{ old('follow_up_actions', $sessionNote->follow_up_actions) }}</textarea>
                        @error('follow_up_actions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('counselor.session-notes.index', $sessionNote->student) }}"
                           class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                            Cancel
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Update Session Notes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pre-populate follow-up appointment fields if they exist
    @if($sessionNote->requires_follow_up && $sessionNote->appointment)
        const requiresFollowUpCheckbox = document.getElementById('requires_follow_up');
        const followupContainer = document.getElementById('followup_appointment_container');
        const followupDateInput = document.getElementById('followup_appointment_date');
        const followupConcernInput = document.getElementById('followup_concern');
        const followupSelectedTime = document.getElementById('followup_selected_time');

        // Check the requires follow-up checkbox
        requiresFollowUpCheckbox.checked = true;

        // Show the container
        followupContainer.classList.remove('hidden');

        // Pre-populate the date and concern
        followupDateInput.value = '{{ $sessionNote->appointment->appointment_date->format('Y-m-d') }}';
        followupConcernInput.value = '{{ $sessionNote->appointment->concern }}';
        followupSelectedTime.value = '{{ $sessionNote->appointment->start_time }}';

        // Auto-approve if the appointment is already approved
        @if($sessionNote->appointment->status === 'approved')
        document.getElementById('auto_approve_followup').checked = true;
        @endif

        // Load time slots for the pre-populated date
        setTimeout(() => {
            loadFollowupTimeSlots();

            // After slots are loaded, select the existing time slot
            setTimeout(() => {
                const existingSlot = document.querySelector(`[data-start="{{ $sessionNote->appointment->start_time }}"]`);
                if (existingSlot && existingSlot.classList.contains('available')) {
                    existingSlot.click();
                }
            }, 1000);
        }, 500);
    @endif
});
</script>
@endsection
