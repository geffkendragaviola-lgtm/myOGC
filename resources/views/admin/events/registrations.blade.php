
    <style>
        .registrations-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }

        .status-registered { background-color: #dbeafe; color: #1e40af; }
        .status-attended { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
    </style>
@extends('layouts.admin')

@section('title', 'Admin Dashboard - OGC')

@section('content')

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Event Registrations</h1>
                        <p class="text-gray-600 mt-2">{{ $event->title }}</p>
                        <p class="text-gray-500 text-sm mt-1">
                            <i class="far fa-calendar mr-1"></i> {{ $event->date_range }} •
                            <i class="far fa-clock mr-1"></i> {{ $event->time_range }} •
                            <i class="far fa-map-marker-alt mr-1"></i> {{ $event->location }}
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 flex space-x-3">
                        <a href="{{ route('counselor.events.export-registrations', $event) }}"
                           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                            <i class="fas fa-file-export mr-2"></i> Export CSV
                        </a>
                        <a href="{{ route('counselor.events.index') }}"
                           class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                            <i class="fas fa-calendar mr-2"></i> Back to Events
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Registrations</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registrationStats['total'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-user-check text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Registered</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registrationStats['registered'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Attended</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registrationStats['attended'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 rounded-lg">
                            <i class="fas fa-user-times text-red-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Cancelled</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registrationStats['cancelled'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registrations Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Student Registrations</h2>
                </div>

                @if($registrations->isEmpty())
                    <div class="p-8 text-center">
                        <i class="fas fa-users-slash text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Registrations Yet</h3>
                        <p class="text-gray-500">No students have registered for this event yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Info</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Info</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($registrations as $registration)
                                    @php
                                        $student = $registration->student;
                                        $user = $student->user;
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <!-- Student Information -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-blue-600 font-semibold">
                                                        {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->first_name }}
                                                        {{ $user->middle_name ? $user->middle_name . ' ' : '' }}
                                                        {{ $user->last_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        ID: {{ $student->student_id ?? 'N/A' }}
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        Age: {{ $user->age ?? 'N/A' }} • {{ $user->sex ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contact Information -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->phone_number ?? 'No phone' }}</div>
                                        </td>

                                       <!-- Academic Information -->
<td class="px-6 py-4 whitespace-nowrap">
    <div class="text-sm font-medium text-gray-900">
        {{ $student->college->name ?? 'N/A' }}
    </div>
    <div class="text-sm text-gray-500">
        {{ $student->year_level ?? 'N/A' }}
    </div>
    <div class="text-sm text-gray-500">
        {{ $student->course ?? 'N/A' }}
    </div>
</td>

                                        <!-- Registration Information -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge status-{{ $registration->status }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $registration->registered_at->format('M j, Y g:i A') }}
                                            </div>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if($registration->status === 'registered' && $event->is_upcoming)
                                                    <form action="{{ route('counselor.events.update-registration-status', [$event, $registration]) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="attended">
                                                        <button type="submit"
                                                                class="text-green-600 hover:text-green-900 transition"
                                                                title="Mark as Attended">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($registration->status === 'attended')
                                                    <span class="text-green-500" title="Attended">
                                                        <i class="fas fa-check-double"></i>
                                                    </span>
                                                @endif

                                                @if(in_array($registration->status, ['registered', 'attended']))
                                                    <form action="{{ route('counselor.events.update-registration-status', [$event, $registration]) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit"
                                                                class="text-red-600 hover:text-red-900 transition"
                                                                onclick="return confirm('Are you sure you want to cancel this registration?')"
                                                                title="Cancel Registration">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Registration Summary -->
            @if(!$registrations->isEmpty())
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Registration Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-700">
                        <div>
                            <strong>Total Capacity:</strong>
                            {{ $event->max_attendees ? $event->max_attendees . ' students' : 'Unlimited' }}
                        </div>
                        <div>
                            <strong>Available Slots:</strong>
                            {{ $event->available_slots }}
                            @if($event->max_attendees)
                                ({{ number_format(($event->registered_count / $event->max_attendees) * 100, 1) }}% filled)
                            @endif
                        </div>
                        <div>
                            <strong>Registration Rate:</strong>
                            {{ number_format(($registrationStats['registered'] / max(1, $registrationStats['total'])) * 100, 1) }}% active registrations
                        </div>
                        <div>
                            <strong>Attendance Rate:</strong>
                            {{ number_format(($registrationStats['attended'] / max(1, $registrationStats['total'])) * 100, 1) }}% attended
                        </div>
                    </div>
                </div>
            @endif
        </div>



@endsection
