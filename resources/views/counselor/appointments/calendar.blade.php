@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')

<body class="bg-gray-50">

    <div class="container mx-auto px-6 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Appointment Calendar</h1>
            <div class="flex space-x-4">
                <a href="{{ route('counselor.dashboard') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
                <a href="{{ route('counselor.appointments') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-list mr-2"></i>View All Appointments
                </a>
            </div>
        </div>

        <!-- Date Navigation -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @php
                        $prevDate = $date->copy()->subDay();
                        $nextDate = $date->copy()->addDay();

                        // Skip weekends when navigating
                        while ($prevDate->isWeekend()) {
                            $prevDate->subDay();
                        }
                        while ($nextDate->isWeekend()) {
                            $nextDate->addDay();
                        }
                    @endphp

                    <a href="?date={{ $prevDate->format('Y-m-d') }}"
                       class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-chevron-left text-gray-600"></i>
                    </a>

                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $date->format('l, F j, Y') }}
                        @if($date->isWeekend())
                            <span class="text-sm text-red-600 font-normal">(Weekend - No Appointments)</span>
                        @endif
                    </h2>

                    <a href="?date={{ $nextDate->format('Y-m-d') }}"
                       class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <i class="fas fa-chevron-right text-gray-600"></i>
                    </a>
                </div>

                <form method="GET" class="flex items-center space-x-2">
                    <input type="date" name="date" value="{{ $selectedDate }}"
                           class="p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           min="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Go
                    </button>
                    @php
                        $today = \Carbon\Carbon::today();
                        $nextAvailableDay = $today->copy();
                        // Skip weekends for "Today" button
                        while ($nextAvailableDay->isWeekend()) {
                            $nextAvailableDay->addDay();
                        }
                    @endphp
                    <a href="?date={{ $nextAvailableDay->format('Y-m-d') }}"
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Next Available Day
                    </a>
                </form>
            </div>
        </div>

        <!-- Weekend Message -->
        @if($date->isWeekend())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6 text-center">
            <div class="flex items-center justify-center space-x-3">
                <i class="fas fa-calendar-times text-yellow-500 text-2xl"></i>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-800">Weekend Schedule</h3>
                    <p class="text-yellow-700">No appointments are scheduled on weekends. Please select a weekday to view appointments.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Time Slots Grid - Only show on weekdays -->
        @if(!$date->isWeekend())
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 p-6">
                <!-- Morning Session (8AM - 12PM) -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-800 border-b pb-2">Morning (8AM - 12PM)</h3>
                    @php
                        $morningSlots = ['08:00', '09:00', '10:00', '11:00'];
                    @endphp

                    @foreach($morningSlots as $slot)
                        @php
                            // Find appointment for this exact time slot using formatted time
                            $appointment = $appointments->first(function($appt) use ($slot) {
                                return isset($appt->formatted_start_time) && $appt->formatted_start_time === $slot;
                            });
                        @endphp

                        <div class="p-3 border rounded-lg
                            @if($appointment)
                                @if($appointment->status === 'pending') bg-yellow-50 border-yellow-200
                                @elseif($appointment->status === 'approved') bg-green-50 border-green-200
                                @elseif($appointment->status === 'completed') bg-blue-50 border-blue-200
                                @elseif($appointment->status === 'rejected') bg-red-50 border-red-200
                                @elseif($appointment->status === 'cancelled') bg-gray-50 border-gray-200
                                @else bg-gray-50 border-gray-200 @endif
                            @else
                                bg-gray-50 border-gray-200
                            @endif">

                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($slot)->format('g:i A') }}</span>
                                @if($appointment)
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->status === 'approved') bg-green-100 text-green-800
                                        @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                                        @elseif($appointment->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($appointment->status === 'cancelled') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500">Available</span>
                                @endif
                            </div>

                            @if($appointment)
                                <!-- Show appointment details -->
                                <div class="text-sm">
                                    <p class="font-semibold text-gray-800">
                                        {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                    </p>
                                    <p class="text-gray-600 text-xs">{{ $appointment->student->student_id }}</p>
                                    <p class="text-gray-500 text-xs mt-1">{{ Str::limit($appointment->concern, 50) }}</p>
                                </div>

                                <div class="mt-2 flex space-x-2 flex-wrap gap-1">
                                    <button onclick="showAppointmentDetails({{ $appointment->id }})"
                                            class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition">
                                        View
                                    </button>
                                    @if($appointment->status === 'pending')
                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 transition">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 transition">
                                                Reject
                                            </button>
                                        </form>
                                    @elseif($appointment->status === 'approved')
                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition">
                                                Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <!-- Show available slot -->
                                <p class="text-sm text-gray-500">No appointment</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Afternoon Session (1PM - 5PM) -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-800 border-b pb-2">Afternoon (1PM - 5PM)</h3>
                    @php
                        $afternoonSlots = ['13:00', '14:00', '15:00', '16:00'];
                    @endphp

                    @foreach($afternoonSlots as $slot)
                        @php
                            // Find appointment for this exact time slot using formatted time
                            $appointment = $appointments->first(function($appt) use ($slot) {
                                return isset($appt->formatted_start_time) && $appt->formatted_start_time === $slot;
                            });
                        @endphp

                        <div class="p-3 border rounded-lg
                            @if($appointment)
                                @if($appointment->status === 'pending') bg-yellow-50 border-yellow-200
                                @elseif($appointment->status === 'approved') bg-green-50 border-green-200
                                @elseif($appointment->status === 'completed') bg-blue-50 border-blue-200
                                @elseif($appointment->status === 'rejected') bg-red-50 border-red-200
                                @elseif($appointment->status === 'cancelled') bg-gray-50 border-gray-200
                                @else bg-gray-50 border-gray-200 @endif
                            @else
                                bg-gray-50 border-gray-200
                            @endif">

                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($slot)->format('g:i A') }}</span>
                                @if($appointment)
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($appointment->status === 'approved') bg-green-100 text-green-800
                                        @elseif($appointment->status === 'completed') bg-blue-100 text-blue-800
                                        @elseif($appointment->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($appointment->status === 'cancelled') bg-gray-100 text-gray-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-500">Available</span>
                                @endif
                            </div>

                            @if($appointment)
                                <!-- Show appointment details -->
                                <div class="text-sm">
                                    <p class="font-semibold text-gray-800">
                                        {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                    </p>
                                    <p class="text-gray-600 text-xs">{{ $appointment->student->student_id }}</p>
                                    <p class="text-gray-500 text-xs mt-1">{{ Str::limit($appointment->concern, 50) }}</p>
                                </div>

                                <div class="mt-2 flex space-x-2 flex-wrap gap-1">
                                    <button onclick="showAppointmentDetails({{ $appointment->id }})"
                                            class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition">
                                        View
                                    </button>
                                    @if($appointment->status === 'pending')
                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 transition">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 transition">
                                                Reject
                                            </button>
                                        </form>
                                    @elseif($appointment->status === 'approved')
                                        <form action="{{ route('counselor.appointments.update-status', $appointment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition">
                                                Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <!-- Show available slot -->
                                <p class="text-sm text-gray-500">No appointment</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Legend & Summary -->
                <div class="lg:col-span-2">
                    <h3 class="font-semibold text-gray-800 border-b pb-2 mb-3">Legend & Summary</h3>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded"></div>
                            <span class="text-sm text-gray-700">Pending Approval</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                            <span class="text-sm text-gray-700">Approved</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded"></div>
                            <span class="text-sm text-gray-700">Completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-red-100 border border-red-300 rounded"></div>
                            <span class="text-sm text-gray-700">Rejected</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-gray-100 border border-gray-300 rounded"></div>
                            <span class="text-sm text-gray-700">Available Slot</span>
                        </div>
                    </div>

                    <!-- Daily Summary -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-3">Daily Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Bookings:</span>
                                <span class="font-semibold">{{ $appointments->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pending:</span>
                                <span class="font-semibold text-yellow-600">{{ $appointments->where('status', 'pending')->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Approved:</span>
                                <span class="font-semibold text-green-600">{{ $appointments->where('status', 'approved')->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Completed:</span>
                                <span class="font-semibold text-blue-600">{{ $appointments->where('status', 'completed')->count() }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Available Slots:</span>
                                @php
                                    $bookedSlots = $appointments->whereIn('status', ['pending', 'approved', 'completed'])->count();
                                    $availableSlots = 8 - $bookedSlots;
                                @endphp
                                <span class="font-semibold text-gray-600">{{ $availableSlots }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="mt-4 bg-white border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Quick Stats</h4>
                        <div class="text-sm text-gray-600">
                            <p>• {{ $appointments->whereIn('status', ['pending', 'approved'])->count() }} active appointments today</p>
                            <p>• {{ $appointments->where('status', 'pending')->count() }} need your attention</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    <!-- Appointment Details Modal -->
    <div id="appointmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-800">Appointment Details</h3>
                    <button onclick="closeAppointmentModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="appointmentDetails" class="p-6">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>

    <script>
        function showAppointmentDetails(appointmentId) {
            fetch(`/counselor/appointments/${appointmentId}/details`)
                .then(response => response.json())
                .then(data => {
                    const modal = document.getElementById('appointmentModal');
                    const details = document.getElementById('appointmentDetails');

                    details.innerHTML = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Student Name</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.student.user.first_name} ${data.student.user.last_name}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Student ID</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.student.student_id}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">College</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.student.college?.name || 'N/A'}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Year Level</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.student.year_level}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Date</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.formatted_date}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Time</label>
                                    <p class="mt-1 text-sm text-gray-900">${data.formatted_time}</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Concern</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">${data.appointment.concern}</p>
                            </div>

                            ${data.appointment.notes ? `
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Counselor Notes</label>
                                <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">${data.appointment.notes}</p>
                            </div>
                            ` : ''}

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    ${data.appointment.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                      data.appointment.status === 'approved' ? 'bg-green-100 text-green-800' :
                                      data.appointment.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                      data.appointment.status === 'completed' ? 'bg-blue-100 text-blue-800' :
                                      'bg-gray-100 text-gray-800'}">
                                    ${data.appointment.status.charAt(0).toUpperCase() + data.appointment.status.slice(1)}
                                </span>
                            </div>
                        </div>
                    `;

                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching appointment details:', error);
                    alert('Error loading appointment details. Please try again.');
                });
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('appointmentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAppointmentModal();
            }
        });
    </script>
@endsection
