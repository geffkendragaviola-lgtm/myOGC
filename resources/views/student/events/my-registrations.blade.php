@extends('layouts.student')

@section('title', 'Student Dashboard - OGC')

@section('content')

    <div class="container mx-auto px-6 py-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800">My Event Registrations</h1>
            <p class="text-gray-600 mt-2">View and manage your event registrations</p>
        </div>

        @if(!$student)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">Student Profile Required</h3>
                <p class="text-yellow-700 mb-4">You need to complete your student profile before you can register for events.</p>
                <a href="{{ route('profile.edit') }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition">
                    Complete Profile
                </a>
            </div>
        @else
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-calendar text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Total Registrations</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registrations->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-play-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Active Registrations</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registrations->where('status', 'registered')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Attended Events</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registrations->where('status', 'attended')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <i class="fas fa-times-circle text-orange-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Cancelled</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $registrations->where('status', 'cancelled')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs for Active vs Cancelled Registrations -->
            <div class="bg-white rounded-xl shadow-sm mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button id="active-tab" class="tab-button active py-4 px-6 text-center border-b-2 border-blue-500 font-medium text-blue-600">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Active Registrations ({{ $registrations->where('status', 'registered')->count() }})
                        </button>
                        <button id="cancelled-tab" class="tab-button py-4 px-6 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                            <i class="fas fa-history mr-2"></i>
                            Cancellation History ({{ $registrations->where('status', 'cancelled')->count() }})
                        </button>
                        <button id="attended-tab" class="tab-button py-4 px-6 text-center border-b-2 border-transparent font-medium text-gray-500 hover:text-gray-700">
                            <i class="fas fa-check-double mr-2"></i>
                            Attended Events ({{ $registrations->where('status', 'attended')->count() }})
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Active Registrations Section -->
            <div id="active-section" class="tab-content active">
                @if($registrations->where('status', 'registered')->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Active Registrations</h3>
                        <p class="text-gray-500 mb-6">You don't have any upcoming event registrations.</p>
                        <a href="{{ route('student.events.available') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            Browse Available Events
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($registrations->where('status', 'registered') as $registration)
                            @include('partials.event-registration-card', ['registration' => $registration])
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Cancelled Registrations Section -->
            <div id="cancelled-section" class="tab-content hidden">
                @if($registrations->where('status', 'cancelled')->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <i class="fas fa-calendar-check text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Cancellation History</h3>
                        <p class="text-gray-500 mb-6">You haven't cancelled any event registrations.</p>
                    </div>
                @else
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Your Cancelled Registrations</h3>
                        <p class="text-gray-600 text-sm">These are events you previously registered for but cancelled.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($registrations->where('status', 'cancelled') as $registration)
                            <div class="event-card bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-orange-500 opacity-80">
                                <!-- Event Image Header -->
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                    <img src="{{ $registration->event->image_url }}"
                                         alt="{{ $registration->event->title }}"
                                         class="w-full h-full object-cover">

                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                                    <!-- Content Overlay -->
                                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="inline-block bg-orange-600/90 text-white text-xs px-2 py-1 rounded-full capitalize backdrop-blur-sm">
                                                {{ $registration->event->type }}
                                            </span>
                                            <span class="status-badge bg-orange-100 text-orange-800 backdrop-blur-sm">
                                                Cancelled
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-bold text-white line-clamp-2">{{ $registration->event->title }}</h3>
                                    </div>

                                    <!-- Cancelled Overlay -->
                                    <div class="absolute inset-0 bg-orange-500/20 flex items-center justify-center">
                                        <div class="bg-orange-600 text-white px-4 py-2 rounded-lg transform rotate-12">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Cancelled
                                        </div>
                                    </div>
                                </div>

                                <!-- Event Details -->
                                <div class="p-4">
                                    <!-- Date and Time -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-calendar mr-2 text-blue-500"></i>
                                            <span>{{ $registration->event->date_range }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-clock mr-2 text-green-500"></i>
                                            <span>{{ $registration->event->time_range }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-map-marker-alt mr-2 text-red-500"></i>
                                            <span class="line-clamp-1">{{ $registration->event->location }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-calendar-times mr-2 text-orange-500"></i>
                                            <span>Cancelled: {{ $registration->cancelled_at ? $registration->cancelled_at->format('M j, Y g:i A') : 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                        {{ Str::limit($registration->event->description, 120) }}
                                    </p>

                                    <!-- Re-registration Option -->
                                    @if($registration->event->is_registration_open && $registration->event->hasAvailableSlots())
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                            <div class="flex items-center mb-2">
                                                <i class="fas fa-redo-alt text-blue-500 mr-2"></i>
                                                <span class="text-blue-800 font-medium text-sm">Re-registration Available</span>
                                            </div>
                                            <p class="text-blue-700 text-xs mb-3">
                                                This event still has available slots. You can register again if you'd like to attend.
                                            </p>
                                            <form action="{{ route('student.events.re-register', $registration->event) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full bg-blue-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                                                    <i class="fas fa-redo-alt mr-2"></i>
                                                    Re-register for Event
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-info-circle text-gray-500 mr-2"></i>
                                                <span class="text-gray-700 text-sm">Re-registration not available</span>
                                            </div>
                                            <p class="text-gray-600 text-xs mt-1">
                                                @if(!$registration->event->is_registration_open)
                                                    Event registration is closed.
                                                @elseif(!$registration->event->hasAvailableSlots())
                                                    Event is full.
                                                @else
                                                    Cannot re-register for this event.
                                                @endif
                                            </p>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="flex gap-2">
                                        <button onclick="toggleDetails('cancelled-details-{{ $registration->id }}')"
                                                class="flex-1 bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-lg hover:bg-gray-200 transition flex items-center justify-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Details
                                        </button>
                 
                                    </div>

                                    <!-- Expandable Details -->
                                    <div id="cancelled-details-{{ $registration->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                                        <div class="space-y-3">
                                            <!-- Full Description -->
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 mb-1">Description:</p>
                                                <p class="text-gray-600 text-sm leading-relaxed">{{ $registration->event->description }}</p>
                                            </div>

                                            <!-- Registration Timeline -->
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-700">Originally Registered:</span>
                                                    <span class="text-gray-600">{{ $registration->registered_at->format('M j, Y g:i A') }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-700">Cancelled On:</span>
                                                    <span class="text-gray-600">{{ $registration->cancelled_at ? $registration->cancelled_at->format('M j, Y g:i A') : 'N/A' }}</span>
                                                </div>
                                            </div>

                                            <!-- Event Status -->
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-gray-700">Event Status:</span>
                                                <span class="{{ $registration->event->is_upcoming ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }} px-2 py-1 rounded-full text-xs">
                                                    {{ $registration->event->is_upcoming ? 'Upcoming' : 'Past' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Attended Events Section -->
            <div id="attended-section" class="tab-content hidden">
                @if($registrations->where('status', 'attended')->isEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <i class="fas fa-calendar-check text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Attended Events</h3>
                        <p class="text-gray-500 mb-6">You haven't attended any events yet.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($registrations->where('status', 'attended') as $registration)
                            <div class="event-card bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-green-500">
                                <!-- Event Image Header -->
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                    <img src="{{ $registration->event->image_url }}"
                                         alt="{{ $registration->event->title }}"
                                         class="w-full h-full object-cover">

                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                                    <!-- Content Overlay -->
                                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="inline-block bg-green-600/90 text-white text-xs px-2 py-1 rounded-full capitalize backdrop-blur-sm">
                                                {{ $registration->event->type }}
                                            </span>
                                            <span class="status-badge bg-green-100 text-green-800 backdrop-blur-sm">
                                                Attended
                                            </span>
                                        </div>
                                        <h3 class="text-lg font-bold text-white line-clamp-2">{{ $registration->event->title }}</h3>
                                    </div>

                                    <!-- Attended Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-green-600 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                                            <i class="fas fa-check-circle mr-1"></i> Completed
                                        </span>
                                    </div>
                                </div>

                                <!-- Event Details -->
                                <div class="p-4">
                                    <!-- Date and Time -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-calendar mr-2 text-blue-500"></i>
                                            <span>{{ $registration->event->date_range }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-clock mr-2 text-green-500"></i>
                                            <span>{{ $registration->event->time_range }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-map-marker-alt mr-2 text-red-500"></i>
                                            <span class="line-clamp-1">{{ $registration->event->location }}</span>
                                        </div>
                                    </div>

                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                        {{ Str::limit($registration->event->description, 120) }}
                                    </p>

                                    <div class="flex gap-2">
                                        <button onclick="toggleDetails('attended-details-{{ $registration->id }}')"
                                                class="flex-1 bg-gray-100 text-gray-700 text-sm px-3 py-2 rounded-lg hover:bg-gray-200 transition flex items-center justify-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Details
                                        </button>
                                    </div>

                                    <!-- Expandable Details -->
                                    <div id="attended-details-{{ $registration->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 mb-1">Description:</p>
                                                <p class="text-gray-600 text-sm leading-relaxed">{{ $registration->event->description }}</p>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-700">Attended on:</span>
                                                <span class="text-gray-600">{{ $registration->updated_at->format('M j, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    <style>
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

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            border-bottom-color: #3b82f6;
            color: #2563eb;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>

    <script>
        function toggleDetails(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }

        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    contents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Show corresponding content
                    const tabId = this.id.replace('-tab', '-section');
                    document.getElementById(tabId).classList.add('active');
                });
            });
        });
    </script>
@endsection
