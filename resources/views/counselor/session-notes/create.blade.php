@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8 max-w-4xl">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Create Session Notes</h1>
                        <p class="text-gray-600 mt-2">
                            for {{ $student->user->first_name }} {{ $student->user->last_name }}
                            ({{ $student->student_id }})
                        </p>
                        <p class="text-gray-500 text-sm">
                            {{ $student->college->name ?? 'N/A' }} • {{ $student->year_level }}
                        </p>
<!-- In the form section -->
@if($appointment)
    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

    <!-- Add a visual indicator that this is for a specific appointment -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-calendar-alt text-blue-500 mr-3"></i>
            <div>
                <h4 class="font-semibold text-blue-800">Creating notes for appointment on {{ $appointment->appointment_date->format('F j, Y') }}</h4>
                <p class="text-blue-600 text-sm">Time: {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}</p>
            </div>
        </div>
    </div>
@endif
                    </div>
                    <a href="{{ route('counselor.session-notes.index', $student) }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Notes
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form action="{{ route('counselor.session-notes.store', $student) }}" method="POST">
                    @csrf

                    @if($appointment)
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Session Date -->
                        <div>
                            <label for="session_date" class="block text-sm font-medium text-gray-700 mb-2">Session Date *</label>
                            <input type="date"
                                   name="session_date"
                                   id="session_date"
                                   value="{{ old('session_date', $appointment ? $appointment->appointment_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
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
                                    <option value="{{ $value }}" {{ old('session_type') == $value ? 'selected' : '' }}>
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
                                    <option value="{{ $value }}" {{ old('mood_level') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mood_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Requires Follow-up -->
                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="requires_follow_up"
                                   id="requires_follow_up"
                                   value="1"
                                   {{ old('requires_follow_up') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="requires_follow_up" class="ml-2 block text-sm text-gray-700">
                                Schedule follow-up appointment
                            </label>
                        </div>
                    </div>

                    <!-- Follow-up Appointment Scheduling -->
<!-- Follow-up Appointment Scheduling -->
<div id="followup_appointment_container" class="mt-6 hidden">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="text-lg font-semibold text-blue-800 mb-3">
            <i class="fas fa-calendar-plus mr-2"></i>Schedule Follow-up Appointment
        </h4>
 <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-yellow-600 mt-1 mr-2"></i>
                <div>
                    <p class="text-yellow-800 text-sm font-medium">Note about follow-up appointments:</p>
                    <p class="text-yellow-700 text-xs mt-1">
                        This will schedule a new appointment for the student. Session notes for the follow-up
                        should be created after that appointment occurs.
                    </p>
                </div>
            </div>
        </div>

        <!-- Counselor Selection (auto-filled) -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-blue-700 mb-2">Counselor</label>
            <div class="p-3 bg-white border border-blue-300 rounded-lg">
                <p class="text-blue-800 font-medium">
                    {{ Auth::user()->counselor->user->first_name }} {{ Auth::user()->counselor->user->last_name }}
                </p>
                <p class="text-blue-600 text-sm">{{ Auth::user()->counselor->position }}</p>
            </div>
        </div>

                            <!-- Follow-up Date -->
                            <div class="mb-4">
                                <label for="followup_appointment_date" class="block text-sm font-medium text-blue-700 mb-2">Follow-up Date *</label>
                                <input type="date"
                                       name="followup_appointment_date"
                                       id="followup_appointment_date"
                                       value="{{ old('followup_appointment_date') }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('followup_appointment_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Available Time Slots -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-blue-700 mb-2">Available Time Slots</label>

                                <!-- Legend -->
                                <div class="flex flex-wrap gap-3 mb-3 text-xs">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-100 border-2 border-green-500 rounded mr-1"></div>
                                        <span class="text-blue-600">Available</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-gray-100 border-2 border-gray-400 rounded mr-1"></div>
                                        <span class="text-blue-600">Booked</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-blue-100 border-2 border-blue-500 rounded mr-1"></div>
                                        <span class="text-blue-600">Selected</span>
                                    </div>
                                </div>

                                <div id="followup_time_slots" class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 bg-white border border-blue-300 rounded-lg">
                                    <div class="col-span-2 text-center text-blue-500 text-sm py-4">
                                        Select a follow-up date to see available time slots
                                    </div>
                                </div>
                                <input type="hidden" name="followup_start_time" id="followup_selected_time">
                                @error('followup_start_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Follow-up Concern (pre-filled from current session) -->
                            <div>
                                <label for="followup_concern" class="block text-sm font-medium text-blue-700 mb-2">Follow-up Concern *</label>
                                <textarea name="followup_concern"
                                          id="followup_concern"
                                          rows="3"
                                          class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Brief description of what to discuss in the follow-up session...">{{ old('followup_concern', 'Follow-up session') }}</textarea>
                                @error('followup_concern')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Auto-approve option -->
                            <div class="mt-3 flex items-center">
                                <input type="checkbox"
                                       name="auto_approve_followup"
                                       id="auto_approve_followup"
                                       value="1"
                                       {{ old('auto_approve_followup', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-blue-300 rounded">
                                <label for="auto_approve_followup" class="ml-2 block text-sm text-blue-700">
                                    Auto-approve this follow-up appointment
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Session Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Session Notes *</label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="8"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Document the session details, topics discussed, observations, and any important insights..."
                                  required>{{ old('notes') }}</textarea>
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
                                  placeholder="Any recommended actions, homework, or follow-up tasks for the student...">{{ old('follow_up_actions') }}</textarea>
                        @error('follow_up_actions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Recent Appointments (if any) -->
                    @if($recentAppointments->isNotEmpty())
                        <div class="mt-6 bg-gray-50 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-3">Recent Appointments with This Student</h3>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @foreach($recentAppointments as $recentAppt)
                                    <div class="text-xs text-gray-600 border-l-2 border-blue-500 pl-2">
                                        <strong>{{ $recentAppt->appointment_date->format('M j, Y') }}</strong>:
                                        {{ Str::limit($recentAppt->concern, 80) }}
                                        @if($recentAppt->sessionNotes->isNotEmpty())
                                            <span class="text-green-600 ml-2">
                                                <i class="fas fa-clipboard-check"></i> Has notes
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('counselor.session-notes.index', $student) }}"
                           class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                            Cancel
                        </a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Save Session Notes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show/hide follow-up appointment scheduling based on checkbox
        document.getElementById('requires_follow_up').addEventListener('change', function() {
            const followupContainer = document.getElementById('followup_appointment_container');

            if (this.checked) {
                followupContainer.classList.remove('hidden');
                // Set default follow-up appointment date to 1 week from session date
                const sessionDate = document.getElementById('session_date').value;
                if (sessionDate) {
                    const followupDate = new Date(sessionDate);
                    followupDate.setDate(followupDate.getDate() + 7);
                    document.getElementById('followup_appointment_date').value = followupDate.toISOString().split('T')[0];
                    loadFollowupTimeSlots();
                }
            } else {
                followupContainer.classList.add('hidden');
            }
        });

        // Follow-up appointment scheduling functionality
        document.addEventListener('DOMContentLoaded', function() {
            const requiresFollowUpCheckbox = document.getElementById('requires_follow_up');
            const followupContainer = document.getElementById('followup_appointment_container');
            const followupDateInput = document.getElementById('followup_appointment_date');
            const followupTimeSlots = document.getElementById('followup_time_slots');
            const followupSelectedTime = document.getElementById('followup_selected_time');
            const counselorId = {{ Auth::user()->counselor->id }};
            let currentFollowupSelectedSlot = null;

            // Load time slots when follow-up date changes
            followupDateInput.addEventListener('change', loadFollowupTimeSlots);

            function loadFollowupTimeSlots() {
                const date = followupDateInput.value;

                if (!date) {
                    followupTimeSlots.innerHTML = '<div class="col-span-2 text-center text-blue-500 text-sm py-4">Select a follow-up date to see available time slots</div>';
                    followupSelectedTime.value = '';
                    currentFollowupSelectedSlot = null;
                    return;
                }

                // Show loading
                followupTimeSlots.innerHTML = '<div class="col-span-2 text-center text-blue-500 text-sm py-4">Loading available slots...</div>';
                followupSelectedTime.value = '';
                currentFollowupSelectedSlot = null;

                fetch(`/counselor/appointments/followup-available-slots?counselor_id=${counselorId}&date=${date}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Follow-up slots response:', data);

                        followupSelectedTime.value = '';
                        currentFollowupSelectedSlot = null;

                        if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                            followupTimeSlots.innerHTML = `
                                <div class="col-span-2 text-center p-4 border-2 border-dashed border-red-300 rounded-lg bg-red-50">
                                    <p class="text-red-600 font-semibold text-sm">No working hours for this date</p>
                                    <p class="text-red-500 text-xs mt-1">Please choose another date</p>
                                </div>
                            `;
                            return;
                        }

                        followupTimeSlots.innerHTML = '';

                        // Combine and sort all slots
                        const allSlots = [...data.available_slots, ...data.booked_slots].sort((a, b) =>
                            a.start.localeCompare(b.start)
                        );

                        // Create time slot buttons
                        allSlots.forEach(slot => {
                            const slotElement = document.createElement('button');
                            slotElement.type = 'button';
                            slotElement.dataset.start = slot.start;
                            slotElement.dataset.end = slot.end;
                            slotElement.dataset.status = slot.status;

                            if (slot.status === 'booked') {
                                // Booked slot
                                slotElement.className = 'time-slot booked p-2 border border-gray-400 rounded text-center bg-gray-100 text-gray-500 cursor-not-allowed text-xs';
                                slotElement.disabled = true;
                                slotElement.title = 'This time slot is already booked';
                                slotElement.innerHTML = `
                                    <div class="font-medium line-through">${slot.display}</div>
                                    <div class="text-red-500 mt-1">
                                        <i class="fas fa-lock text-xs"></i>
                                    </div>
                                `;
                                slotElement.style.pointerEvents = 'none';
                            } else {
                                // Available slot
                                slotElement.className = 'time-slot available p-2 border border-green-500 rounded text-center bg-green-50 text-gray-700 hover:border-green-600 hover:bg-green-100 transition cursor-pointer text-xs';
                                slotElement.innerHTML = `
                                    <div class="font-medium">${slot.display}</div>
                                    <div class="text-green-600 mt-1">
                                        <i class="fas fa-check-circle text-xs"></i>
                                    </div>
                                `;

                                slotElement.addEventListener('click', function() {
                                    // Remove selection from all available slots
                                    followupTimeSlots.querySelectorAll('.time-slot.available').forEach(s => {
                                        s.classList.remove('selected', 'border-blue-500', 'bg-blue-100', 'text-blue-700');
                                        s.classList.add('border-green-500', 'bg-green-50', 'text-gray-700');
                                    });

                                    // Select this slot
                                    this.classList.remove('border-green-500', 'bg-green-50', 'text-gray-700');
                                    this.classList.add('selected', 'border-blue-500', 'bg-blue-100', 'text-blue-700');

                                    followupSelectedTime.value = slot.start;
                                    currentFollowupSelectedSlot = slot.start;

                                    console.log('Selected follow-up time:', slot.start);
                                });
                            }

                            followupTimeSlots.appendChild(slotElement);
                        });

                        // Show message if all slots are booked
                        if (data.available_slots.length === 0 && data.booked_slots.length > 0) {
                            const message = document.createElement('div');
                            message.className = 'col-span-2 text-center p-3 bg-yellow-50 border border-yellow-300 rounded text-xs mt-2';
                            message.innerHTML = `
                                <div class="text-yellow-700 mb-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <span class="font-semibold">All time slots are booked</span>
                                </div>
                                <p class="text-yellow-600">Please choose another date</p>
                            `;
                            followupTimeSlots.appendChild(message);
                        }

                    })
                    .catch(error => {
                        console.error('Error fetching follow-up time slots:', error);
                        followupTimeSlots.innerHTML = `
                            <div class="col-span-2 text-center p-4 border-2 border-dashed border-red-300 rounded-lg bg-red-50">
                                <p class="text-red-600 font-semibold text-sm">Error loading time slots</p>
                                <p class="text-red-500 text-xs mt-1">Please try again</p>
                            </div>
                        `;
                    });
            }

            // Initialize if requires follow-up is already checked
            if (requiresFollowUpCheckbox.checked) {
                requiresFollowUpCheckbox.dispatchEvent(new Event('change'));
            }
        });

        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }, 5000);
            });
        });
    </script>
@endsection
