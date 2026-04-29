<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - OGC</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #faf6f0 0%, #f5ede0 50%, #faf6f0 100%);
            min-height: 100vh;
        }

        .main-card {
            background: linear-gradient(to bottom, #ffffff 0%, #fffdfb 100%);
            box-shadow: 0 10px 40px rgba(122, 42, 42, 0.08), 0 2px 8px rgba(122, 42, 42, 0.04);
            border: 1px solid rgba(212, 175, 55, 0.1);
        }

        .form-section {
            background: linear-gradient(to bottom right, #fffdfb, #faf8f4);
            border: 1px solid rgba(212, 175, 55, 0.15);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .form-section:hover {
            box-shadow: 0 4px 16px rgba(212, 175, 55, 0.12);
            border-color: rgba(212, 175, 55, 0.25);
        }

        .section-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #7a2a2a 0%, #5c1a1a 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #d4af37;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px rgba(122, 42, 42, 0.2);
        }

        .form-input, .form-select, .form-textarea {
            border: 2px solid rgba(212, 175, 55, 0.2);
            background: #ffffff;
            transition: all 0.3s ease;
            border-radius: 10px;
            padding: 0.875rem 1rem;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #d4af37;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
            background: #fffdfb;
        }

        .time-slot {
            transition: all 0.3s ease;
            position: relative;
            border-radius: 10px;
            font-weight: 500;
        }

        .time-slot.available {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid #86efac;
            color: #166534;
        }

        .time-slot.available:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.2);
            border-color: #4ade80;
        }

        .time-slot.booked {
            background: repeating-linear-gradient(
                45deg,
                #fafafa,
                #fafafa 6px,
                #f0f0f0 6px,
                #f0f0f0 12px
            );
            border: 2px solid #d1d5db;
            color: #9ca3af;
            position: relative;
        }

        .time-slot.booked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 15%;
            right: 15%;
            height: 2px;
            background: #ef4444;
            transform: translateY(-50%);
        }

        .time-slot.selected {
            background: linear-gradient(135deg, #7a2a2a 0%, #5c1a1a 100%) !important;
            border: 2px solid #d4af37 !important;
            color: #ffffff !important;
            box-shadow: 0 8px 24px rgba(122, 42, 42, 0.3), 0 0 0 4px rgba(212, 175, 55, 0.2);
            transform: scale(1.05);
        }

        .time-slot.selected .text-green-600 {
            color: #d4af37 !important;
        }

        .time-slot.booked:hover {
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        .time-slot[disabled] {
            pointer-events: none;
            opacity: 0.5;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid rgba(212, 175, 55, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #7a2a2a 0%, #5c1a1a 100%);
            color: #ffffff;
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(122, 42, 42, 0.2);
            border: 2px solid transparent;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(122, 42, 42, 0.3);
            border-color: #d4af37;
        }

        .btn-primary:disabled {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            cursor: not-allowed;
            box-shadow: none;
        }

        .btn-secondary {
            background: #ffffff;
            color: #7a2a2a;
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            border: 2px solid rgba(122, 42, 42, 0.2);
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #faf6f0;
            border-color: #7a2a2a;
            transform: translateY(-2px);
        }

        .page-header {
            background: linear-gradient(135deg, #7a2a2a 0%, #5c1a1a 100%);
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 24px rgba(122, 42, 42, 0.2);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-header h1 {
            color: #ffffff;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            color: #d4af37;
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 768px) {
            .container { padding-left: 1rem; padding-right: 1rem; }
            .form-section { padding: 1rem; }
            .page-header { padding: 1.5rem; }
            .page-header h1 { font-size: 1.5rem; }
        }
        
        @media (max-width: 640px) {
            .container { padding-left: 0.85rem; padding-right: 0.85rem; }
            .max-w-4xl { max-width: 100%; }
            .grid.grid-cols-2 { grid-template-columns: 1fr; }
            .grid.sm\:grid-cols-2 { grid-template-columns: 1fr; }
            .grid.grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
            .grid.grid-cols-4 { grid-template-columns: repeat(2, 1fr); }
            .legend-item { padding: 0.375rem 0.75rem; font-size: 0.875rem; }
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-6 py-8">
        <div class="max-w-5xl mx-auto">
            <!-- Page Header -->
            <div class="page-header">
                <h1><i class="fas fa-calendar-check mr-3"></i>Book an Appointment</h1>
                <p>Schedule a session with one of our professional counselors</p>
            </div>

            <!-- Main Form Card -->
            <div class="main-card rounded-2xl p-8">
                <form id="appointmentForm" action="{{ route('appointments.store') }}" method="POST">
                    @csrf

                    <!-- Counselor Selection -->
                    <div class="form-section">
                        <div class="flex items-start gap-4">
                            <div class="section-icon flex-shrink-0">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-gray-800 font-bold text-lg mb-1">Select Counselor</label>
                                <p class="text-gray-600 text-sm mb-3">Choose a counselor based on their specialization</p>
                                <select name="counselor_id" id="counselorSelect" class="form-select w-full" required>
                                    <option value="">Choose a counselor</option>
                                    @foreach($counselors as $counselor)
                                        <option value="{{ $counselor->id }}">
                                            {{ $counselor->user->first_name }} {{ $counselor->user->last_name }} - {{ $counselor->position }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="form-section">
                        <div class="flex items-start gap-4">
                            <div class="section-icon flex-shrink-0">
                                <i class="fas fa-calendar-days"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-gray-800 font-bold text-lg mb-1">Select Date</label>
                                <p class="text-gray-600 text-sm mb-3">Pick a date for your appointment</p>
                                <input type="date" name="appointment_date" id="dateSelect"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="form-input w-full" required>
                            </div>
                        </div>
                    </div>

                    <!-- Time Slots -->
                    <div class="form-section">
                        <div class="flex items-start gap-4">
                            <div class="section-icon flex-shrink-0">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-gray-800 font-bold text-lg mb-1">Available Time Slots</label>
                                <p class="text-gray-600 text-sm mb-4">Select your preferred time slot</p>

                                <!-- Legend -->
                                <div class="flex flex-wrap gap-3 mb-5">
                                    <div class="legend-item">
                                        <div class="w-4 h-4 bg-gradient-to-br from-green-100 to-green-200 border-2 border-green-400 rounded"></div>
                                        <span class="text-gray-700 text-sm font-medium">Available</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="w-4 h-4 bg-gray-100 border-2 border-gray-400 rounded"></div>
                                        <span class="text-gray-700 text-sm font-medium">Booked</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="w-4 h-4 bg-gradient-to-br from-red-900 to-red-950 border-2 border-yellow-500 rounded"></div>
                                        <span class="text-gray-700 text-sm font-medium">Selected</span>
                                    </div>
                                </div>

                                <div id="timeSlots" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                    <div class="col-span-full text-gray-500 text-center p-6 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                                        <i class="fas fa-info-circle text-2xl mb-2 text-gray-400"></i>
                                        <p class="font-medium">Select a counselor and date to see available time slots</p>
                                    </div>
                                </div>
                                <input type="hidden" name="start_time" id="selectedTime" required>
                            </div>
                        </div>
                    </div>

                    <!-- Concern -->
                    <div class="form-section">
                        <div class="flex items-start gap-4">
                            <div class="section-icon flex-shrink-0">
                                <i class="fas fa-comment-dots"></i>
                            </div>
                            <div class="flex-1">
                                <label class="block text-gray-800 font-bold text-lg mb-1">Presenting Problem</label>
                                <p class="text-gray-600 text-sm mb-3">Briefly describe your reason for booking</p>
                                <textarea name="concern" rows="5" class="form-textarea w-full" placeholder="Share what brings you here today. This helps your counselor prepare for your session." required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">
                        <a href="{{ route('appointments.index') }}" class="btn-secondary text-center">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" id="submitBtn" class="btn-primary">
                            <i class="fas fa-check-circle mr-2"></i>Book Appointment
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
                timeSlots.innerHTML = '<div class="col-span-full text-gray-500 text-center p-6 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50"><i class="fas fa-info-circle text-2xl mb-2 text-gray-400"></i><p class="font-medium">Select a counselor and date to see available time slots</p></div>';
                selectedTime.value = '';
                updateSubmitButton();
                return;
            }

            // Show loading
            timeSlots.innerHTML = '<div class="col-span-full text-gray-500 text-center p-6 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50"><i class="fas fa-spinner fa-spin text-2xl mb-2 text-gray-400"></i><p class="font-medium">Loading available slots...</p></div>';
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
                            <div class="col-span-full text-center p-6 border-2 border-dashed border-red-300 rounded-xl bg-red-50">
                                <i class="fas fa-calendar-xmark text-red-400 text-3xl mb-3"></i>
                                <p class="text-red-600 font-bold text-lg">No working hours for this date</p>
                                <p class="text-red-500 text-sm mt-2">Please choose another date</p>
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
                            slotElement.className = 'time-slot booked p-4 border-2 rounded-lg text-center cursor-not-allowed';
                            slotElement.disabled = true;
                            slotElement.title = 'This time slot is already booked';
                            slotElement.innerHTML = `
                                <div class="font-semibold">${slot.display}</div>
                                <div class="text-xs text-red-500 mt-2 flex items-center justify-center">
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
                            slotElement.className = 'time-slot available p-4 border-2 rounded-lg text-center cursor-pointer';
                            slotElement.innerHTML = `
                                <div class="font-semibold text-base">${slot.display}</div>
                                <div class="text-xs text-green-600 mt-2 flex items-center justify-center">
                                    <i class="fas fa-circle-check mr-1"></i> Available
                                </div>
                            `;

                            slotElement.addEventListener('click', function() {
                                // Remove selection from all available slots
                                document.querySelectorAll('.time-slot.available').forEach(s => {
                                    s.classList.remove('selected');
                                });

                                // Select this slot
                                this.classList.add('selected');

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
                        message.className = 'col-span-full text-center p-5 bg-yellow-50 border-2 border-yellow-300 rounded-xl mt-4';
                        message.innerHTML = `
                            <div class="flex items-center justify-center text-yellow-700 mb-2">
                                <i class="fas fa-exclamation-triangle text-2xl mr-3"></i>
                                <span class="font-bold text-lg">All time slots are booked for this date</span>
                            </div>
                            <p class="text-yellow-600 text-sm">Please choose another date or select a different counselor</p>
                        `;
                        timeSlots.appendChild(message);
                    }

                    // Show summary
                    const summary = document.createElement('div');
                    summary.className = 'col-span-full text-center text-sm text-gray-600 mt-5 p-3 bg-white rounded-lg border border-gray-200';
                    summary.innerHTML = `
                        <i class="fas fa-info-circle mr-2"></i>
                        Showing ${allSlots.length} time slots:
                        <span class="text-green-600 font-semibold">${data.available_slots.length} available</span>,
                        <span class="text-red-600 font-semibold">${data.booked_slots.length} booked</span>
                    `;
                    timeSlots.appendChild(summary);

                })
                .catch(error => {
                    console.error('Error fetching time slots:', error);
                    timeSlots.innerHTML = `
                        <div class="col-span-full text-center p-6 border-2 border-dashed border-red-300 rounded-xl bg-red-50">
                            <i class="fas fa-circle-exclamation text-red-400 text-3xl mb-3"></i>
                            <p class="text-red-600 font-bold text-lg">Error loading time slots</p>
                            <p class="text-red-500 text-sm mt-2">Please try again or contact support</p>
                        </div>
                    `;
                });
        }

        function updateSubmitButton() {
            if (selectedTime.value) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');
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
