@extends('layouts.student')

@section('title', 'Book Appointment - OGC')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Book an Appointment</h1>

            <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
                @csrf

                <!-- Counselor Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Select Counselor</label>

                    <!-- Counselor Type Selection -->
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="counselor_type" value="college" checked
                                   class="counselor-type-radio text-blue-600 focus:ring-blue-500">
                            <span class="ml-2">Counselors from my college</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" name="counselor_type" value="referred"
                                   class="counselor-type-radio text-blue-600 focus:ring-blue-500">
                            <span class="ml-2">Previously referred counselors</span>
                        </label>
                    </div>

                    <select name="counselor_id" id="counselorSelect"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Choose a counselor</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>

                    <!-- Loading indicator -->
                    <div id="counselorLoading" class="hidden mt-2 text-blue-600">
                        Loading counselors...
                    </div>
                </div>

                <!-- Rest of the form remains the same -->
                <!-- Date Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Select Date</label>
                    <input type="date" name="appointment_date" id="dateSelect"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <!-- Time Slots -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Available Time Slots</label>
                    <div id="timeSlots" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">
                            Select a counselor and date to see available time slots
                        </div>
                    </div>
                    <input type="hidden" name="start_time" id="selectedTime" required>
                </div>

                <!-- Concern -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Concern</label>
                    <textarea name="concern" rows="4"
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Please describe your concern..." required></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('appointments.index') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Book Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const counselorTypeRadios = document.querySelectorAll('.counselor-type-radio');
    const counselorSelect = document.getElementById('counselorSelect');
    const counselorLoading = document.getElementById('counselorLoading');
    const dateSelect = document.getElementById('dateSelect');
    const timeSlots = document.getElementById('timeSlots');
    const selectedTime = document.getElementById('selectedTime');

    let currentSelectedSlot = null;
    let collegeCounselors = {!! json_encode($counselors->map(function($c) {
        return [
            'id' => $c->id,
            'name' => $c->user->first_name . ' ' . $c->user->last_name,
            'position' => $c->position,
            'college' => $c->college->name ?? 'N/A',
            'display_text' => $c->user->first_name . ' ' . $c->user->last_name . ' - ' . $c->position . ' (' . ($c->college->name ?? 'N/A') . ')'
        ];
    })) !!};

    // Load counselors based on type selection
    function loadCounselors(type) {
        counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';
        counselorLoading.classList.remove('hidden');

        if (type === 'college') {
            // Use pre-loaded college counselors
            populateCounselorSelect(collegeCounselors);
            counselorLoading.classList.add('hidden');
        } else {
            // Load referred counselors via AJAX
            fetch('/appointments/referred-counselors')
                .then(response => response.json())
                .then(data => {
                    populateCounselorSelect(data);
                    counselorLoading.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error loading referred counselors:', error);
                    counselorLoading.classList.add('hidden');
                    counselorSelect.innerHTML = '<option value="">Error loading counselors</option>';
                });
        }
    }

    function populateCounselorSelect(counselors) {
        counselorSelect.innerHTML = '<option value="">Choose a counselor</option>';

        if (counselors.length === 0) {
            counselorSelect.innerHTML = '<option value="">No counselors available</option>';
            return;
        }

        counselors.forEach(counselor => {
            const option = document.createElement('option');
            option.value = counselor.id;
            option.textContent = counselor.display_text || counselor.name;
            counselorSelect.appendChild(option);
        });
    }

    // Load time slots function (same as before)
    function loadAvailableSlots() {
        const counselorId = counselorSelect.value;
        const date = dateSelect.value;

        if (!counselorId || !date) {
            timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a counselor and date to see available time slots</div>';
            selectedTime.value = '';
            return;
        }

        timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Loading available slots...</div>';

        fetch(`/appointments/available-slots?counselor_id=${counselorId}&date=${date}`)
            .then(response => response.json())
            .then(data => {
                // ... existing time slot loading logic ...
                if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                    timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">No working hours for this date. Please choose another date.</div>';
                    selectedTime.value = '';
                    return;
                }

                timeSlots.innerHTML = '';

                const allSlots = [...data.available_slots, ...data.booked_slots].sort((a, b) =>
                    a.start.localeCompare(b.start)
                );

                allSlots.forEach(slot => {
                    const slotElement = document.createElement('button');
                    slotElement.type = 'button';

                    if (slot.status === 'booked') {
                        slotElement.className = 'time-slot p-4 border-2 border-gray-200 rounded-lg text-center bg-gray-100 text-gray-400 cursor-not-allowed';
                        slotElement.disabled = true;
                        slotElement.title = 'Already booked';
                        slotElement.innerHTML = `
                            ${slot.display}
                            <div class="text-xs text-red-500 mt-1">Booked</div>
                        `;
                    } else {
                        slotElement.className = 'time-slot p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer';
                        slotElement.textContent = slot.display;

                        slotElement.addEventListener('click', function() {
                            document.querySelectorAll('.time-slot:not(:disabled)').forEach(s => {
                                s.classList.remove('border-blue-500', 'bg-blue-100', 'text-blue-700');
                                s.classList.add('border-gray-200', 'text-gray-700');
                            });

                            this.classList.remove('border-gray-200', 'text-gray-700');
                            this.classList.add('border-blue-500', 'bg-blue-100', 'text-blue-700');

                            selectedTime.value = slot.start;
                            currentSelectedSlot = slot.start;
                        });
                    }

                    slotElement.dataset.start = slot.start;
                    slotElement.dataset.end = slot.end;
                    slotElement.dataset.status = slot.status;
                    timeSlots.appendChild(slotElement);
                });

                if (data.available_slots.length === 0 && data.booked_slots.length > 0) {
                    const message = document.createElement('div');
                    message.className = 'col-span-full text-center p-4 bg-yellow-50 border border-yellow-200 rounded-lg';
                    message.innerHTML = `
                        <p class="text-yellow-700 font-semibold">All time slots are booked for this date</p>
                        <p class="text-yellow-600 text-sm mt-1">Please choose another date or counselor</p>
                    `;
                    timeSlots.prepend(message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">Error loading time slots. Please try again.</div>';
            });
    }

    // Event listeners
    counselorTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                loadCounselors(this.value);
                // Reset time slots when counselor type changes
                timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a counselor and date to see available time slots</div>';
                selectedTime.value = '';
            }
        });
    });

    counselorSelect.addEventListener('change', loadAvailableSlots);
    dateSelect.addEventListener('change', loadAvailableSlots);

    // Load initial college counselors
    loadCounselors('college');
});
</script>
@endsection
