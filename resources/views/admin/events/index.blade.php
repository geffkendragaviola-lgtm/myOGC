@extends('layouts.admin')

@section('title', 'Admin Dashboard - OGC')

@section('content')
<style>
    .events-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .events-navbar {
        background: linear-gradient(90deg, #F8650C 0%, #FFC917 100%);
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

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/40">
    <div class="container mx-auto px-4 md:px-6 py-6 md:py-8 events-container">
        
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-red-500 text-lg"></i>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Manage Events</h1>
                    </div>
                    <p class="text-gray-500 text-sm ml-1">Admin panel for managing all mental health events, workshops, and seminars</p>
                </div>
                <div>
                    <a href="{{ route('admin.events.create') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium">
                        <i class="fas fa-plus mr-2 text-sm"></i> Create New Event
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Events -->
            <div class="stats-card bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Events</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $events->total() }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>

            <!-- Active Events -->
            <div class="stats-card bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active Events</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $events->where('is_active', true)->count() }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center">
                        <i class="fas fa-play-circle text-emerald-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-1.5 rounded-full" style="width: {{ $events->total() > 0 ? ($events->where('is_active', true)->count() / $events->total()) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Total Registrations -->
            <div class="stats-card bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Registrations</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalRegistrations ?? 0 }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 flex items-center justify-center">
                        <i class="fas fa-users text-amber-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-1.5 rounded-full" style="width: {{ min(100, ($totalRegistrations ?? 0) / 10) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Active Counselors -->
            <div class="stats-card bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Active Counselors</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $counselors->count() }}</p>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                        <i class="fas fa-user-tie text-red-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-gray-100 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-1.5 rounded-full" style="width: {{ min(100, ($counselors->count() / 20) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-emerald-500"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-8">
            <form method="GET" action="{{ route('admin.events') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" name="search"
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none"
                                   placeholder="Search events..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 outline-none bg-white text-gray-700">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                        </select>
                    </div>
                    <div>
                        <select name="type" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 outline-none bg-white text-gray-700">
                            <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                            <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                            <option value="seminar" {{ request('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                            <option value="webinar" {{ request('type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                            <option value="conference" {{ request('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                            <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <select name="counselor" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 outline-none bg-white text-gray-700">
                            <option value="all" {{ request('counselor') == 'all' ? 'selected' : '' }}>All Counselors</option>
                            @foreach($counselors as $counselor)
                                <option value="{{ $counselor->user_id }}" {{ request('counselor') == $counselor->user_id ? 'selected' : '' }}>
                                    {{ $counselor->user->first_name }} {{ $counselor->user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-2.5 rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-filter text-sm"></i>
                            <span>Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Events Grid -->
        @if($events->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-calendar-plus text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Events Found</h3>
                <p class="text-gray-400 text-sm mb-6">No events match your current filters or no events have been created yet.</p>
                <a href="{{ route('admin.events.create') }}"
                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium">
                    <i class="fas fa-plus mr-2 text-sm"></i> Create Your First Event
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="event-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <!-- Event Header -->
                        <div class="bg-gradient-to-r from-red-600 to-red-700 p-5 text-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="inline-block bg-white/20 backdrop-blur-sm text-xs px-2.5 py-1 rounded-full mb-2 capitalize font-medium">
                                        {{ $event->type }}
                                    </span>
                                    <h3 class="text-lg font-bold">{{ $event->title }}</h3>
                                    <p class="text-sm text-white/80 mt-1">
                                        <i class="fas fa-user-tie mr-1"></i>
                                        {{ $event->user->first_name }} {{ $event->user->last_name }}
                                    </p>
                                </div>
                                <div>
                                    <span class="status-badge {{ $event->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $event->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="p-5">
                            <!-- Date and Time -->
                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                <i class="far fa-calendar text-blue-400 mr-2 w-4"></i>
                                <span>{{ $event->date_range }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                <i class="far fa-clock text-amber-400 mr-2 w-4"></i>
                                <span>{{ $event->time_range }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt text-red-400 mr-2 w-4"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                <i class="fas fa-users text-emerald-400 mr-2 w-4"></i>
                                <span>
                                    {{ $event->registrations->where('status', 'registered')->count() }} registered
                                    @if($event->max_attendees)
                                        / {{ $event->max_attendees }} max
                                    @endif
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-500 text-sm mb-5 line-clamp-2 leading-relaxed">
                                {{ Str::limit($event->description, 100) }}
                            </p>

                            <!-- Action Buttons -->
                            <div class="grid grid-cols-2 gap-2 mb-4">
                                <a href="{{ route('admin.events.registrations', $event) }}"
                                   class="bg-amber-50 text-amber-700 text-sm px-3 py-2 rounded-xl hover:bg-amber-100 transition text-center font-medium">
                                    <i class="fas fa-users mr-1"></i> Registrations
                                </a>

                                <a href="{{ route('admin.events.edit', $event) }}"
                                   class="bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-xl hover:bg-gray-200 transition text-center font-medium">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                <form action="{{ route('admin.events.toggle-status', $event) }}" method="POST" class="contents">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="{{ $event->is_active ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} text-sm px-3 py-2 rounded-xl transition font-medium">
                                        <i class="fas {{ $event->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                        {{ $event->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="contents"
                                      onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-50 text-red-700 text-sm px-3 py-2 rounded-xl hover:bg-red-100 transition font-medium">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>

                            <!-- Created Info -->
                            <div class="pt-3 border-t border-gray-100">
                                <p class="text-xs text-gray-400">
                                    <i class="far fa-clock mr-1"></i>
                                    Created: {{ $event->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Enhanced Pagination -->
            @if($events->hasPages())
            <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-database text-gray-400 text-xs"></i>
                        <span>Showing 
                            <span class="font-semibold text-gray-700">{{ $events->firstItem() ?? 0 }}</span> 
                            to 
                            <span class="font-semibold text-gray-700">{{ $events->lastItem() ?? 0 }}</span> 
                            of 
                            <span class="font-semibold text-gray-700">{{ $events->total() }}</span> 
                            events
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($events->onFirstPage())
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $events->previousPageUrl() }}" class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200">Previous</a>
                        @endif

                        @if($events->hasMorePages())
                            <a href="{{ $events->nextPageUrl() }}" class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200">Next</a>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                    <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                    <span>Showing all <span class="font-semibold text-gray-700">{{ $events->total() }}</span> events</span>
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection