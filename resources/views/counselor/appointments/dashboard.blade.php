@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<div class="container mx-auto px-6 py-8">

<!-- Welcome Section -->
<div class="bg-white rounded-xl shadow-md p-6 mb-8">
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                Welcome, {{ $counselor->user->first_name }}!
            </h1>

            <!-- Colleges -->
            <div class="flex flex-wrap gap-2 mb-2">
                @foreach($allColleges as $college)
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        {{ $college->name }}
                    </span>
                @endforeach
            </div>

            <!-- Position & Credentials -->
            <p class="text-gray-500 text-sm mt-1">
                {{ $counselor->position }} • {{ $counselor->credentials }}
            </p>
        </div>

        <!-- Date -->
        <div class="text-right">
            <p class="text-sm text-gray-500">Today is</p>
            <p class="text-lg font-semibold text-gray-800">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </div>
</div>


    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pending Appointments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $appointmentStats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Approved Appointments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $appointmentStats['approved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Appointments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $appointmentStats['total'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left -->
        <div class="bg-white rounded-xl shadow-md">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-day text-blue-600 mr-3"></i> Today's Appointments
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
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">
                                            {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                        </h3>
                                        <p class="text-sm text-gray-600">{{ $appointment->student->student_id }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mb-2">
                                    <i class="fas fa-clock text-gray-400 mr-2"></i>
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
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-bolt text-green-600 mr-3"></i> Quick Actions
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('counselor.calendar') }}"
                           class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center hover:bg-blue-100 transition">
                            <i class="fas fa-calendar-alt text-blue-600 text-2xl mb-2"></i>
                            <p class="font-semibold text-blue-800">View Calendar</p>
                        </a>
                        <a href="{{ route('counselor.appointments') }}?status=pending"
                           class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center hover:bg-yellow-100 transition">
                            <i class="fas fa-clock text-yellow-600 text-2xl mb-2"></i>
                            <p class="font-semibold text-yellow-800">Pending Requests</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upcoming Appointments -->
            <div class="bg-white rounded-xl shadow-md">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-calendar-plus text-purple-600 mr-3"></i> Upcoming Appointments
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
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <div class="text-center">
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{ $appointment->student->user->first_name }} {{ $appointment->student->user->last_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $appointment->student->college->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full
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
