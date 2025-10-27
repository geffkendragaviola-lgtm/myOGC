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

    .required-badge {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .college-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-100 rounded-lg">
                        <i class="fas fa-exclamation-circle text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Required Events</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $events->where('is_required', true)->count() }}</p>
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
                    <div class="event-card bg-white rounded-xl shadow-sm overflow-hidden border-l-4 {{ $event->is_required ? 'border-red-500' : 'border-blue-500' }}">
                        <!-- Event Image Header -->
                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            <img src="{{ $event->image_url }}"
                                 alt="{{ $event->title }}"
                                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                            <!-- Content Overlay -->
                            <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-block bg-blue-600/90 text-white text-xs px-2 py-1 rounded-full capitalize backdrop-blur-sm">
                                            {{ $event->type }}
                                        </span>
                                        @if($event->is_required)
                                            <span class="required-badge text-xs backdrop-blur-sm bg-red-600/90">
                                                <i class="fas fa-exclamation-circle mr-1"></i> Required
                                            </span>
                                        @endif
                                    </div>
                                    <span class="status-badge {{ $event->is_active ? 'status-active' : 'status-inactive' }} backdrop-blur-sm bg-black/50 text-white">
                                        {{ $event->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-white line-clamp-2">{{ $event->title }}</h3>
                            </div>

                            <!-- College Badge -->
                            <div class="absolute top-3 right-3">
                                @if($event->for_all_colleges)
                                    <span class="college-badge text-xs backdrop-blur-sm bg-green-600/90">
                                        <i class="fas fa-globe mr-1"></i> All Colleges
                                    </span>
                                @else
                                    <span class="college-badge text-xs backdrop-blur-sm bg-blue-600/90">
                                        <i class="fas fa-university mr-1"></i> {{ $event->colleges->count() }} Colleges
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Event Details -->
                        <div class="p-4">
                            <!-- Date and Time -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="far fa-calendar mr-2 text-blue-500"></i>
                                    <span>{{ $event->date_range }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="far fa-clock mr-2 text-green-500"></i>
                                    <span>{{ $event->time_range }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    <span class="line-clamp-1">{{ $event->location }}</span>
                                </div>
                                @if($event->max_attendees)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-users mr-2 text-purple-500"></i>
                                        <span>{{ $event->registered_count }}/{{ $event->max_attendees }} registered</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-users mr-2 text-purple-500"></i>
                                        <span>{{ $event->registered_count }} registered (Unlimited capacity)</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                {{ Str::limit($event->description, 120) }}
                            </p>

                            <!-- Specific Colleges -->
                            @if(!$event->for_all_colleges && $event->colleges->isNotEmpty())
                                <div class="mb-4">
                                    <p class="text-xs font-semibold text-gray-700 mb-2">Available for:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($event->colleges->take(2) as $college)
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                {{ $college->name }}
                                            </span>
                                        @endforeach
                                        @if($event->colleges->count() > 2)
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                +{{ $event->colleges->count() - 2 }} more
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('counselor.events.registrations', $event) }}"
                                   class="flex-1 bg-purple-100 text-purple-700 text-sm px-3 py-2 rounded-lg hover:bg-purple-200 transition text-center flex items-center justify-center">
                                    <i class="fas fa-users mr-1"></i>
                                    <span class="hidden sm:inline">Registrations</span>
                                </a>

                                <a href="{{ route('counselor.events.edit', $event) }}"
                                   class="flex-1 bg-blue-100 text-blue-700 text-sm px-3 py-2 rounded-lg hover:bg-blue-200 transition text-center flex items-center justify-center">
                                    <i class="fas fa-edit mr-1"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>

                                <form action="{{ route('counselor.events.toggle-status', $event) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="w-full {{ $event->is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} text-sm px-3 py-2 rounded-lg transition flex items-center justify-center">
                                        <i class="fas {{ $event->is_active ? 'fa-pause' : 'fa-play' }} mr-1"></i>
                                        <span class="hidden sm:inline">{{ $event->is_active ? 'Deactivate' : 'Activate' }}</span>
                                    </button>
                                </form>

                                <form action="{{ route('counselor.events.destroy', $event) }}" method="POST" class="flex-1"
                                      onsubmit="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full bg-red-100 text-red-700 text-sm px-3 py-2 rounded-lg hover:bg-red-200 transition flex items-center justify-center">
                                        <i class="fas fa-trash mr-1"></i>
                                        <span class="hidden sm:inline">Delete</span>
                                    </button>
                                </form>
                            </div>

                            <!-- Event Status and Created Info -->
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">
                                        <i class="far fa-calendar-plus mr-1"></i>
                                        {{ $event->created_at->format('M j, Y') }}
                                    </div>
                                    <div class="text-xs">
                                        @if($event->is_upcoming)
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                <i class="fas fa-clock mr-1"></i> Upcoming
                                            </span>
                                        @else
                                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                                <i class="fas fa-history mr-1"></i> Past
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Quick Stats Footer -->
            <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Event Overview</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <div class="text-lg font-bold text-blue-600">{{ $events->where('is_active', true)->count() }}</div>
                        <div class="text-blue-700">Active Events</div>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <div class="text-lg font-bold text-green-600">
                            {{ $events->where('event_start_date', '>=', now()->toDateString())->count() }}
                        </div>
                        <div class="text-green-700">Upcoming</div>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <div class="text-lg font-bold text-purple-600">
                            {{ $events->where('is_required', true)->count() }}
                        </div>
                        <div class="text-purple-700">Required</div>
                    </div>
                    <div class="text-center p-3 bg-orange-50 rounded-lg">
                        <div class="text-lg font-bold text-orange-600">
                            {{ $events->sum('registered_count') }}
                        </div>
                        <div class="text-orange-700">Total Registrations</div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection
