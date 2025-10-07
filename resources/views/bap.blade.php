<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - OGC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .time-slot {
            transition: all 0.2s ease;
            position: relative;
        }

        .time-slot.available:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
        }

        .time-slot.booked {
            background: repeating-linear-gradient(
                45deg,
                #f3f4f6,
                #f3f4f6 4px,
                #e5e7eb 4px,
                #e5e7eb 8px
            );
            position: relative;
        }

        .time-slot.booked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 10%;
            right: 10%;
            height: 2px;
            background: #ef4444;
            transform: rotate(-45deg);
        }

        .time-slot.selected {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            transform: scale(1.02);
        }

        /* Disable hover effects for booked slots */
        .time-slot.booked:hover {
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* Ensure booked slots are completely disabled */
        .time-slot[disabled] {
            pointer-events: none;
            opacity: 0.6;
        }
    </style>
</head>
<body class="bg-gray-50">
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

                        <!-- Legend -->
                        <div class="flex flex-wrap gap-4 mb-4 text-sm">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-100 border-2 border-green-500 rounded mr-2"></div>
                                <span class="text-gray-600">Available</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-gray-100 border-2 border-gray-400 rounded mr-2"></div>
                                <span class="text-gray-600">Booked</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-100 border-2 border-blue-500 rounded mr-2"></div>
                                <span class="text-gray-600">Selected</span>
                            </div>
                        </div>

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
                        <button type="submit" id="submitBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
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
        const submitBtn = document.getElementById('submitBtn');
        let currentSelectedSlot = null;

        function loadAvailableSlots() {
            const counselorId = counselorSelect.value;
            const date = dateSelect.value;

            if (!counselorId || !date) {
                timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Select a counselor and date to see available time slots</div>';
                selectedTime.value = '';
                updateSubmitButton();
                return;
            }

            // Show loading
            timeSlots.innerHTML = '<div class="text-gray-500 text-center p-4 border-2 border-dashed border-gray-300 rounded-lg">Loading available slots...</div>';
            selectedTime.value = '';
            currentSelectedSlot = null;
            updateSubmitButton();

            fetch(`/appointments/available-slots?counselor_id=${counselorId}&date=${date}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Server response:', data);

                    // Clear previous selection
                    selectedTime.value = '';
                    currentSelectedSlot = null;
                    updateSubmitButton();

                    if (data.available_slots.length === 0 && data.booked_slots.length === 0) {
                        timeSlots.innerHTML = `
                            <div class="col-span-full text-center p-6 border-2 border-dashed border-red-300 rounded-lg bg-red-50">
                                <i class="fas fa-calendar-times text-red-400 text-2xl mb-2"></i>
                                <p class="text-red-600 font-semibold">No working hours for this date</p>
                                <p class="text-red-500 text-sm mt-1">Please choose another date</p>
                            </div>
                        `;
                        return;
                    }

                    timeSlots.innerHTML = '';

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
                            // Booked slot - completely disabled and unclickable
                            slotElement.className = 'time-slot booked p-4 border-2 border-gray-400 rounded-lg text-center bg-gray-100 text-gray-500 cursor-not-allowed';
                            slotElement.disabled = true;
                            slotElement.title = 'This time slot is already booked';
                            slotElement.innerHTML = `
                                <div class="font-medium line-through">${slot.display}</div>
                                <div class="text-xs text-red-500 mt-1 flex items-center justify-center">
                                    <i class="fas fa-lock mr-1"></i> Booked
                                </div>
                            `;

                            // Add event listener to prevent any clicks
                            slotElement.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                return false;
                            });

                            // Additional protection - make it completely unclickable
                            slotElement.style.pointerEvents = 'none';

                        } else {
                            // Available slot - fully clickable
                            slotElement.className = 'time-slot available p-4 border-2 border-green-500 rounded-lg text-center bg-green-50 text-gray-700 hover:border-green-600 hover:bg-green-100 transition cursor-pointer';
                            slotElement.innerHTML = `
                                <div class="font-medium">${slot.display}</div>
                                <div class="text-xs text-green-600 mt-1 flex items-center justify-center">
                                    <i class="fas fa-check-circle mr-1"></i> Available
                                </div>
                            `;

                            slotElement.addEventListener('click', function() {
                                // Remove selection from all available slots
                                document.querySelectorAll('.time-slot.available').forEach(s => {
                                    s.classList.remove('selected', 'border-blue-500', 'bg-blue-100', 'text-blue-700');
                                    s.classList.add('border-green-500', 'bg-green-50', 'text-gray-700');
                                });

                                // Select this slot
                                this.classList.remove('border-green-500', 'bg-green-50', 'text-gray-700');
                                this.classList.add('selected', 'border-blue-500', 'bg-blue-100', 'text-blue-700');

                                selectedTime.value = slot.start;
                                currentSelectedSlot = slot.start;
                                updateSubmitButton();

                                console.log('Selected time:', slot.start);
                            });
                        }

                        timeSlots.appendChild(slotElement);
                    });

                    // Show message if all slots are booked
                    if (data.available_slots.length === 0 && data.booked_slots.length > 0) {
                        const message = document.createElement('div');
                        message.className = 'col-span-full text-center p-4 bg-yellow-50 border border-yellow-300 rounded-lg mt-4';
                        message.innerHTML = `
                            <div class="flex items-center justify-center text-yellow-700 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-semibold">All time slots are booked for this date</span>
                            </div>
                            <p class="text-yellow-600 text-sm">Please choose another date or select a different counselor</p>
                        `;
                        timeSlots.appendChild(message);
                    }

                    // Show summary
                    const summary = document.createElement('div');
                    summary.className = 'col-span-full text-center text-sm text-gray-600 mt-4';
                    summary.innerHTML = `
                        Showing ${allSlots.length} time slots:
                        <span class="text-green-600">${data.available_slots.length} available</span>,
                        <span class="text-red-600">${data.booked_slots.length} booked</span>
                    `;
                    timeSlots.appendChild(summary);

                })
                .catch(error => {
                    console.error('Error fetching time slots:', error);
                    timeSlots.innerHTML = `
                        <div class="col-span-full text-center p-6 border-2 border-dashed border-red-300 rounded-lg bg-red-50">
                            <i class="fas fa-exclamation-circle text-red-400 text-2xl mb-2"></i>
                            <p class="text-red-600 font-semibold">Error loading time slots</p>
                            <p class="text-red-500 text-sm mt-1">Please try again or contact support</p>
                        </div>
                    `;
                });
        }

        function updateSubmitButton() {
            if (selectedTime.value) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        }

        // Form validation to prevent submission without time selection
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            if (!selectedTime.value) {
                e.preventDefault();
                alert('Please select an available time slot before booking.');
                return false;
            }
        });

        counselorSelect.addEventListener('change', loadAvailableSlots);
        dateSelect.addEventListener('change', loadAvailableSlots);

        // Initial load if values are already selected
        if (counselorSelect.value && dateSelect.value) {
            loadAvailableSlots();
        }

        // Initialize submit button state
        updateSubmitButton();
    });
    </script>
</body>
</html>
