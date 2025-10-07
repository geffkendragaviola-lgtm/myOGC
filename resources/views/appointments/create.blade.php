@extends('layouts.student')

@section('title', 'Student Dashboard - OGC')

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
                        <select name="counselor_id" id="counselorSelect" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Choose a counselor</option>
                            @foreach($counselors as $counselor)
                                <option value="{{ $counselor->id }}">
                                    {{ $counselor->user->first_name }} {{ $counselor->user->last_name }} - {{ $counselor->position }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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
                        <textarea name="concern" rows="4" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Please describe your concern..." required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('appointments.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Book Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const counselorSelect = document.getElementById('counselorSelect');
        const dateSelect = document.getElementById('dateSelect');
        const timeSlots = document.getElementById('timeSlots');
        const selectedTime = document.getElementById('selectedTime');
        let currentSelectedSlot = null;

        function loadAvailableSlots() {
            const counselorId = counselorSelect.value;
            const date = dateSelect.value;

            if (!counselorId || !date) {
                timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a counselor and date to see available time slots</div>';
                selectedTime.value = '';
                return;
            }

            // Show loading
            timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Loading available slots...</div>';

            fetch(`/appointments/available-slots?counselor_id=${counselorId}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                        timeSlots.innerHTML = '<div class="text-red-500 text-center p-4 border-2 border-dashed border-red-300 rounded-lg">No working hours for this date. Please choose another date.</div>';
                        selectedTime.value = '';
                        return;
                    }

                    timeSlots.innerHTML = '';

                    // Show all possible time slots (both available and booked)
                    const allSlots = [...data.available_slots, ...data.booked_slots].sort((a, b) =>
                        a.start.localeCompare(b.start)
                    );

                    allSlots.forEach(slot => {
                        const slotElement = document.createElement('button');
                        slotElement.type = 'button';

                        if (slot.status === 'booked') {
                            // Booked slot - disabled
                            slotElement.className = 'time-slot p-4 border-2 border-gray-200 rounded-lg text-center bg-gray-100 text-gray-400 cursor-not-allowed';
                            slotElement.disabled = true;
                            slotElement.title = 'Already booked';
                            slotElement.innerHTML = `
                                ${slot.display}
                                <div class="text-xs text-red-500 mt-1">Booked</div>
                            `;
                        } else {
                            // Available slot - clickable
                            slotElement.className = 'time-slot p-4 border-2 border-gray-200 rounded-lg text-center hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer';
                            slotElement.textContent = slot.display;

                            slotElement.addEventListener('click', function() {
                                // Remove selection from all slots
                                document.querySelectorAll('.time-slot:not(:disabled)').forEach(s => {
                                    s.classList.remove('border-blue-500', 'bg-blue-100', 'text-blue-700');
                                    s.classList.add('border-gray-200', 'text-gray-700');
                                });

                                // Select this slot
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

                    // If no available slots but there are booked slots
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

        counselorSelect.addEventListener('change', loadAvailableSlots);
        dateSelect.addEventListener('change', loadAvailableSlots);
    });
</script>
@endsection
