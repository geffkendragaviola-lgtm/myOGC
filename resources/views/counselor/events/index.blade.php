
    <style>
        .events-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .events-navbar {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Manage Events</h1>
                        <p class="text-gray-600 mt-2">Create and manage mental health events, workshops, and seminars</p>
                    </div>
                    <a href="{{ route('counselor.events.create') }}"
                       class="mt-4 md:mt-0 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-plus mr-2"></i> Create New Event
                    </a>
                </div>
            </div>
                <!-- Stats -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <i class="fas fa-calendar text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Total Events</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $events->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <i class="fas fa-play-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Active Events</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $events->where('is_active', true)->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <i class="fas fa-users text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-600">Upcoming Events</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $events->where('event_start_date', '>=', now()->toDateString())->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Status Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Events Grid -->
            @if($events->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Events Created Yet</h3>
                    <p class="text-gray-500 mb-6">Start by creating your first event to help students with mental health awareness.</p>
                    <a href="{{ route('counselor.events.create') }}"
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Create Your First Event
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="event-card bg-white rounded-xl shadow-sm overflow-hidden">
                            <!-- Event Header -->
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-4 text-white">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <span class="inline-block bg-white bg-opacity-20 text-xs px-2 py-1 rounded-full mb-2 capitalize">
                                            {{ $event->type }}
                                        </span>
                                        <h3 class="text-lg font-bold">{{ $event->title }}</h3>
                                    </div>
                                    <div class="flex space-x-2">
                                        <span class="status-badge {{ $event->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $event->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Details -->
                            <div class="p-4">
                                <!-- Date and Time -->
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i class="far fa-calendar mr-2"></i>
                                    <span>{{ $event->date_range }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i class="far fa-clock mr-2"></i>
                                    <span>{{ $event->time_range }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 mb-3">
                                    <i class="far fa-map-marker-alt mr-2"></i>
                                    <span>{{ $event->location }}</span>
                                </div>
                                @if($event->max_attendees)
                                    <div class="flex items-center text-sm text-gray-600 mb-3">
                                        <i class="far fa-users mr-2"></i>
                                        <span>Max {{ $event->max_attendees }} attendees</span>
                                    </div>
                                @endif

                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ Str::limit($event->description, 100) }}
                                </p>

                               <!-- Action Buttons -->
<div class="flex flex-wrap gap-2">
    <a href="{{ route('counselor.events.registrations', $event) }}"
       class="flex-1 bg-purple-100 text-purple-700 text-sm px-3 py-2 rounded-lg hover:bg-purple-200 transition text-center">
        <i class="fas fa-users mr-1"></i> Registrations
    </a>

    <a href="{{ route('counselor.events.edit', $event) }}"
       class="flex-1 bg-blue-100 text-blue-700 text-sm px-3 py-2 rounded-lg hover:bg-blue-200 transition text-center">
        <i class="fas fa-edit mr-1"></i> Edit
    </a>

    <form action="{{ route('counselor.events.toggle-status', $event) }}" method="POST" class="flex-1">
        @csrf
        @method('PATCH')
        <button type="submit"
                class="w-full {{ $event->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} text-sm px-3 py-2 rounded-lg transition">
            <i class="fas {{ $event->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
            {{ $event->is_active ? 'Deactivate' : 'Activate' }}
        </button>
    </form>

    <form action="{{ route('counselor.events.destroy', $event) }}" method="POST" class="flex-1"
          onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="w-full bg-red-100 text-red-700 text-sm px-3 py-2 rounded-lg hover:bg-red-200 transition">
            <i class="fas fa-trash mr-1"></i> Delete
        </button>
    </form>
</div>

                                <!-- Created Info -->
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <p class="text-xs text-gray-500">
                                        Created: {{ $event->created_at->format('M j, Y g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif
        </div>



@endsection
