@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<div class="container mx-auto px-6 py-8">

<!-- Welcome Section -->
<div class="bg-gradient-to-r from-[#820000] to-[#5a0000] rounded-xl shadow-lg p-8 mb-8 text-white">
    <div class="flex flex-col md:flex-row md:justify-between md:items-start">
        <div>
            <h1 class="text-3xl font-bold mb-3">
                Welcome, {{ $counselor->user->first_name }}!
            </h1>

            <!-- Colleges -->
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($allColleges as $college)
                    <span class="bg-white bg-opacity-20 text-black px-3 py-1 rounded-full text-sm font-medium backdrop-blur-sm">
                        <i class="fas fa-university mr-1"></i>{{ $college->name }}
                    </span>
                @endforeach
            </div>

            <!-- Position & Credentials -->
            <p class="text-red-100 text-sm">
                <i class="fas fa-user-tie mr-2"></i>{{ $counselor->position }} • {{ $counselor->credentials }}
            </p>
        </div>

        <!-- Date -->
        <div class="mt-4 md:mt-0 text-left md:text-right">
            <p class="text-sm text-red-100">Today is</p>
            <p class="text-xl font-semibold text-white">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </div>
</div>


    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border-t-4 border-[#820000] rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-[#820000] rounded-lg">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs font-medium uppercase">Pending Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $appointmentStats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border-t-4 border-[#820000] rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-[#820000] rounded-lg">
                    <i class="fas fa-check-circle text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs font-medium uppercase">Approved Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $appointmentStats['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white border-t-4 border-[#820000] rounded-lg shadow-sm p-6 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-[#820000] rounded-lg">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-xs font-medium uppercase">Total Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $appointmentStats['total'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-day text-[#820000] mr-3"></i> Today's Appointments
                </h2>
            </div>
            <div class="p-6">
                @if($todayAppointments->isEmpty())
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-check text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No appointments scheduled for today.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($todayAppointments as $appointment)
                            <div class="border-l-4 border-[#820000] bg-gray-50 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">
                                            {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            <i class="fas fa-id-card mr-1"></i>{{ $appointment->student->student_id }}
                                        </p>
                                    </div>
                                    <span class="px-3 py-1 text-xs rounded-full font-semibold
                                        {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2 font-medium">
                                    <i class="fas fa-clock text-[#820000] mr-2"></i>
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($appointment->end_time)->format('g:i A') }}
                                </p>
                                <p class="text-sm text-gray-600 truncate">
                                    <i class="fas fa-comment text-gray-400 mr-2"></i>
                                    {{ $appointment->concern }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right -->
        <div class="space-y-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bolt text-[#820000] mr-3"></i> Quick Actions
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('counselor.calendar') }}"
                           class="bg-gradient-to-br from-[#820000] to-[#5a0000] text-white rounded-lg p-6 text-center hover:shadow-lg transition transform hover:-translate-y-1">
                            <i class="fas fa-calendar-alt text-3xl mb-3"></i>
                            <p class="font-semibold">View Calendar</p>
                        </a>
                        <a href="{{ route('counselor.appointments') }}?status=pending"
                           class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-lg p-6 text-center hover:shadow-lg transition transform hover:-translate-y-1">
                            <i class="fas fa-clock text-3xl mb-3"></i>
                            <p class="font-semibold">Pending Requests</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-red-50 to-white">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-calendar-plus text-[#820000] mr-3"></i> Upcoming Appointments
                    </h2>
                </div>
                <div class="p-6">
                    @if($upcomingAppointments->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-gray-500">No upcoming appointments.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($upcomingAppointments as $appointment)
                                <div class="flex items-center justify-between p-4 bg-gray-50 border-l-4 border-[#820000] rounded-lg hover:shadow-md transition">
                                    <div class="flex items-center space-x-4">
                                        <div class="text-center bg-white rounded-lg p-3 shadow-sm">
                                            <p class="text-sm font-bold text-[#820000]">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j') }}
                                            </p>
                                            <p class="text-xs text-gray-600 font-medium">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <i class="fas fa-university mr-1"></i>{{ $appointment->student->college->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 text-xs rounded-full font-semibold
                                        {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
