@extends('layouts.admin')

@section('title', 'Admin Dashboard - OGC')

@section('content')
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

    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container mx-auto px-6 py-8 events-container">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Manage Events</h1>
                <p class="text-gray-600 mt-2">Admin panel for managing all mental health events, workshops, and seminars</p>
            </div>
            <a href="{{ route('admin.events.create') }}"
               class="mt-4 md:mt-0 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-plus mr-2"></i> Create New Event
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="stats-card bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-calendar text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Events</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $events->total() }}</p>
                </div>
            </div>
        </div>
        <div class="stats-card bg-white rounded-xl shadow-sm p-6">
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
        <div class="stats-card bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Registrations</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalRegistrations ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="stats-card bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <i class="fas fa-user-tie text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Active Counselors</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $counselors->count() }}</p>
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

    <!-- Filters -->
    <div class="filter-card p-6 mb-6">
        <form method="GET" action="{{ route('admin.events') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <input type="text" name="search"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Search events..." value="{{ request('search') }}">
                </div>
                <div>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                    </select>
                </div>
                <div>
                    <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="seminar" {{ request('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="webinar" {{ request('type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                        <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <select name="counselor" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ request('counselor') == 'all' ? 'selected' : '' }}>All Counselors</option>
                        @foreach($counselors as $counselor)
                            <option value="{{ $counselor->user_id }}" {{ request('counselor') == $counselor->user_id ? 'selected' : '' }}>
                                {{ $counselor->user->first_name }} {{ $counselor->user->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Events Grid -->
    @if($events->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Events Found</h3>
            <p class="text-gray-500 mb-6">No events match your current filters or no events have been created yet.</p>
            <a href="{{ route('admin.events.create') }}"
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
                                <p class="text-sm text-blue-100 mt-1">
                                    <i class="fas fa-user-tie mr-1"></i>
                                    {{ $event->user->first_name }} {{ $event->user->last_name }}
                                </p>
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
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <i class="fas fa-users mr-2"></i>
                            <span>
                                {{ $event->registrations->where('status', 'registered')->count() }} registered
                                @if($event->max_attendees)
                                    / {{ $event->max_attendees }} max
                                @endif
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                            {{ Str::limit($event->description, 100) }}
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.events.registrations', $event) }}"
                               class="flex-1 bg-purple-100 text-purple-700 text-sm px-3 py-2 rounded-lg hover:bg-purple-200 transition text-center">
                                <i class="fas fa-users mr-1"></i> Registrations
                            </a>

                            <a href="{{ route('admin.events.edit', $event) }}"
                               class="flex-1 bg-blue-100 text-blue-700 text-sm px-3 py-2 rounded-lg hover:bg-blue-200 transition text-center">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>

                            <form action="{{ route('admin.events.toggle-status', $event) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full {{ $event->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} text-sm px-3 py-2 rounded-lg transition">
                                    <i class="fas {{ $event->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                    {{ $event->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="flex-1"
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

        <!-- Pagination -->
        <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="text-sm text-gray-600 mb-4 md:mb-0">
                    Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} events
                </div>
                <div class="flex space-x-2">
                    @if($events->onFirstPage())
                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $events->previousPageUrl() }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Previous</a>
                    @endif

                    @if($events->hasMorePages())
                        <a href="{{ $events->nextPageUrl() }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Next</a>
                    @else
                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
