<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events - Mental Health Corner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .events-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .events-navbar {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .events-main-content {
            min-height: calc(100vh - 200px);
            background: linear-gradient(to bottom, #f8fafc, #e2e8f0);
        }

        .events-page-header {
            background: linear-gradient(rgba(30, 64, 175, 0.9), rgba(30, 64, 175, 0.8)), url('https://images.unsplash.com/photo-1596363505724-6d24f19ad5a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
            padding: 3rem 0;
            color: white;
        }

        .event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .college-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .required-badge {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .empty-state {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
</head>
<body class="bg-gray-50">
    <div class="events-container">
        <!-- Navbar -->
        <nav class="events-navbar py-4">
            <div class="container mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="text-white font-bold text-2xl mr-10">OGC</div>
                    <div class="hidden md:flex space-x-8">
                        <a href="{{ route('dashboard') }}" class="text-white font-semibold hover:text-yellow-300 transition">Home</a>

                        <div class="relative group">
                            <button class="text-white font-semibold hover:text-yellow-300 transition flex items-center">
                                Services <i class="fas fa-chevron-down ml-1 text-sm"></i>
                            </button>
                            <div class="absolute hidden group-hover:block bg-white rounded-md shadow-lg py-2 mt-1 w-48 z-10">
                                <a href="{{ route('bap') }}" class="block px-4 py-2 text-gray-800 hover:bg-blue-100">Book an Appointment</a>
                                <a href="{{ route('mhc') }}" class="block px-4 py-2 text-blue-600 bg-blue-50 font-semibold">Mental Health Corner</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="text-white p-2 rounded-full hover:bg-blue-700 transition">
                        <i class="fas fa-bell"></i>
                    </button>

                    <div class="relative">
                        <button class="text-white p-2 rounded-full hover:bg-blue-700 transition">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden">
                            <div class="px-4 py-2 border-b">
                                <div class="font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="events-main-content">
            <!-- Page Header -->
            <div class="events-page-header">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="text-center md:text-left">
                            <h1 class="text-4xl font-bold mb-4">All Events</h1>
                            <p class="text-xl max-w-3xl">Browse all upcoming mental health events and workshops</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('mhc') }}"
                               class="bg-white text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition font-semibold inline-flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Mental Health Corner
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mx-auto px-6 py-8">
                @php
                    $student = Auth::user()->student;

                    if ($student) {
                        $events = \App\Models\Event::with(['user', 'colleges', 'registrations' => function($query) use ($student) {
                                $query->where('student_id', $student->id);
                            }])
                            ->upcoming()
                            ->active()
                            ->forCollege($student->college_id)
                            ->orderBy('event_start_date')
                            ->orderBy('start_time')
                            ->get();

                        // Get unique event types for filter
                        $eventTypes = $events->pluck('type')->unique()->sort();
                    } else {
                        $events = collect();
                        $eventTypes = collect();
                    }
                @endphp

                <!-- Filters Section -->
                <div class="filter-section p-6 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Filter Events</h3>
                            <div class="flex flex-wrap gap-4">
                                <select id="typeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Event Types</option>
                                    @foreach($eventTypes as $type)
                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>

                                <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Events</option>
                                    <option value="required">Required Events</option>
                                    <option value="optional">Optional Events</option>
                                    <option value="registered">My Registrations</option>
                                    <option value="available">Available to Register</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-sm text-gray-600">
                            <span class="font-semibold">{{ $events->count() }}</span> events found
                        </div>
                    </div>
                </div>

                @if(!$student)
                    <!-- Student Profile Not Complete -->
                    <div class="empty-state p-8 text-center">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-4">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-4xl mb-4"></i>
                            <h3 class="text-xl font-semibold text-yellow-800 mb-2">Complete Your Student Profile</h3>
                            <p class="text-yellow-700 mb-4">Please complete your student profile to view and register for events.</p>
                            <a href="{{ route('student.profile') }}"
                               class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition inline-flex items-center">
                                <i class="fas fa-user-edit mr-2"></i> Complete Profile
                            </a>
                        </div>
                    </div>
                @elseif($events->isEmpty())
                    <!-- No Events Available -->
                    <div class="empty-state p-12 text-center">
                        <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-2xl font-semibold text-gray-600 mb-2">No Events Available</h3>
                        <p class="text-gray-500 mb-6">There are currently no upcoming events available for your college.</p>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 max-w-md mx-auto">
                            <p class="text-blue-700 text-sm">
                                <i class="fas fa-info-circle mr-2"></i>
                                New events will appear here when they are scheduled by your counselors.
                            </p>
                        </div>
                    </div>
                @else
                    <!-- Events Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8" id="eventsGrid">
                        @foreach($events as $event)
                            @php
                                $isRequiredEvent = $event->is_required && $event->isRequiredForStudent($student);
                            $isRegistered = $event->isRegisteredByStudent($student);
                            $hasAvailableSlots = $event->hasAvailableSlots();
                            $isUpcoming = $event->is_upcoming;
                            $registration = $event->getStudentRegistration($student);
                            $status = $registration ? $registration->status : 'not_registered';
                            $registrationDate = $registration ? $registration->registered_at : null;
                            $isAutoRegistered = $isRequiredEvent && $isRegistered;
                            $canCancel = $isRegistered && $isUpcoming && !$isRequiredEvent;
                            $canRegister = !$isRegistered && $hasAvailableSlots && !$isRequiredEvent;
                            $isEventFull = !$hasAvailableSlots && !$isRegistered;
                            $isRequiredAutoRegister = $isRequiredEvent && !$isRegistered;
                            @endphp

                            <div class="event-card bg-white rounded-xl shadow-sm overflow-hidden border-l-4 {{ $isRequiredEvent ? 'border-red-500' : 'border-blue-500' }}"
                                 data-type="{{ $event->type }}"
                                 data-required="{{ $isRequiredEvent ? 'true' : 'false' }}"
                                 data-registered="{{ $isRegistered ? 'true' : 'false' }}"
                                 data-available="{{ $canRegister ? 'true' : 'false' }}">

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
                                                @if($isRequiredEvent)
                                                    <span class="required-badge text-xs backdrop-blur-sm bg-red-600/90">
                                                        <i class="fas fa-exclamation-circle mr-1"></i> Required
                                                    </span>
                                                @endif
                                            </div>
                                            @if($isRegistered)
                                                <span class="status-badge status-active backdrop-blur-sm bg-black/50 text-white">
                                                    Registered
                                                </span>
                                            @elseif($isRequiredEvent)
                                                <span class="status-badge bg-blue-600/90 backdrop-blur-sm text-white">
                                                    Required
                                                </span>
                                            @else
                                                <span class="status-badge bg-gray-600/90 backdrop-blur-sm text-white">
                                                    Available
                                                </span>
                                            @endif
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

                                    <!-- Date Badge -->
                                    <div class="absolute top-3 left-3 bg-blue-600/90 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                                        {{ \Carbon\Carbon::parse($event->event_start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($event->event_end_date)->format('M d') }}
                                    </div>
                                </div>

                                <!-- Event Details -->
                                <div class="p-4">
                                    <!-- Date and Time -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-clock mr-2 text-green-500"></i>
                                            <span>{{ $event->time_range }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="far fa-map-marker-alt mr-2 text-red-500"></i>
                                            <span class="line-clamp-1">{{ $event->location }}</span>
                                        </div>
                                        @if($event->max_attendees)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="far fa-users mr-2 text-purple-500"></i>
                                                <span>{{ $event->registered_count }}/{{ $event->max_attendees }} registered</span>
                                            </div>
                                        @endif
                                        @if($registrationDate)
                                            <div class="flex items-center text-sm text-gray-600">
                                                <i class="far fa-calendar-check mr-2 text-blue-500"></i>
                                                <span>Registered: {{ $registrationDate->format('M j, Y') }}</span>
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
                                        @if($isRegistered)
                                            @if($isRequiredEvent)
                                                <!-- Required Event - Cannot Cancel -->
                                                <button class="flex-1 bg-gray-400 text-white text-sm px-3 py-2 rounded-lg cursor-not-allowed flex items-center justify-center"
                                                        disabled title="Required events cannot be cancelled">
                                                    <i class="fas fa-lock mr-1"></i>
                                                    <span class="hidden sm:inline">Auto Registered</span>
                                                </button>
                                            @else
                                                <!-- Optional Event - Can Cancel -->
                                                <form action="{{ route('student.events.cancel', $event) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit"
                                                            class="w-full bg-red-100 text-red-700 text-sm px-3 py-2 rounded-lg hover:bg-red-200 transition flex items-center justify-center"
                                                            onclick="return confirm('Are you sure you want to cancel your registration for this event?')">
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                        <span class="hidden sm:inline">Cancel</span>
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif($isRequiredEvent)
                                            <!-- Required Event - Auto Register -->
                                            <span class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-user-check mr-1"></i>
                                                <span class="hidden sm:inline">Required</span>
                                            </span>
                                        @elseif($hasAvailableSlots)
                                            <!-- Available Event - Can Register -->
                                            <form action="{{ route('student.events.register', $event) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit"
                                                        class="w-full bg-blue-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                                                    <i class="fas fa-calendar-plus mr-1"></i>
                                                    <span class="hidden sm:inline">Register</span>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Event Full -->
                                            <button class="flex-1 bg-gray-400 text-white text-sm px-3 py-2 rounded-lg cursor-not-allowed flex items-center justify-center"
                                                    disabled>
                                                <i class="fas fa-calendar-times mr-1"></i>
                                                <span class="hidden sm:inline">Full</span>
                                            </button>
                                        @endif

                                        <!-- View Details Button -->
                                        <button onclick="toggleDetails('details-{{ $event->id }}')"
                                                class="flex-1 bg-blue-100 text-blue-700 text-sm px-3 py-2 rounded-lg hover:bg-blue-200 transition flex items-center justify-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            <span class="hidden sm:inline">Details</span>
                                        </button>
                                    </div>

                                    <!-- Event Status and Created Info -->
                                    <div class="mt-3 pt-3 border-t border-gray-100">
                                        <div class="flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                <i class="far fa-user mr-1"></i>
                                                {{ $event->user->first_name }} {{ $event->user->last_name }}
                                            </div>
                                            <div class="text-xs">
                                                @if($isUpcoming)
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

                                    <!-- Expandable Details -->
                                    <div id="details-{{ $event->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
                                        <div class="space-y-3">
                                            <!-- Full Description -->
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700 mb-1">Description:</p>
                                                <p class="text-gray-600 text-sm leading-relaxed">{{ $event->description }}</p>
                                            </div>

                                            <!-- Capacity Info -->
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-700">Capacity:</span>
                                                <span class="text-gray-600">
                                                    @if($event->max_attendees)
                                                        {{ $event->registered_count }}/{{ $event->max_attendees }} registered
                                                        ({{ $event->available_slots }} available)
                                                    @else
                                                        Unlimited capacity
                                                    @endif
                                                </span>
                                            </div>

                                            <!-- Event Requirements Information -->
                                            @if($isRequiredEvent)
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-info-circle text-red-500 mr-2"></i>
                                                        <span class="text-red-800 font-medium text-sm">Required Event</span>
                                                    </div>
                                                    <p class="text-red-700 text-xs mt-1">
                                                        This event is required for your college. Attendance is mandatory.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-6 text-center">
                <p>&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeFilter = document.getElementById('typeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const eventsGrid = document.getElementById('eventsGrid');
            const eventCards = eventsGrid ? Array.from(eventsGrid.getElementsByClassName('event-card')) : [];

            function filterEvents() {
                const selectedType = typeFilter.value;
                const selectedStatus = statusFilter.value;

                eventCards.forEach(card => {
                    let show = true;

                    // Filter by type
                    if (selectedType && card.dataset.type !== selectedType) {
                        show = false;
                    }

                    // Filter by status
                    if (selectedStatus) {
                        switch (selectedStatus) {
                            case 'required':
                                if (card.dataset.required !== 'true') show = false;
                                break;
                            case 'optional':
                                if (card.dataset.required === 'true') show = false;
                                break;
                            case 'registered':
                                if (card.dataset.registered !== 'true') show = false;
                                break;
                            case 'available':
                                if (card.dataset.available !== 'true') show = false;
                                break;
                        }
                    }

                    card.style.display = show ? 'block' : 'none';
                });

                // Update event count
                const visibleCount = eventCards.filter(card => card.style.display !== 'none').length;
                const countElement = document.querySelector('.text-sm.text-gray-600 span.font-semibold');
                if (countElement) {
                    countElement.textContent = visibleCount;
                }
            }

            if (typeFilter) typeFilter.addEventListener('change', filterEvents);
            if (statusFilter) statusFilter.addEventListener('change', filterEvents);

            // Profile dropdown functionality
            const profileButton = document.querySelector('.relative button');
            const profileDropdown = document.querySelector('.relative .absolute');

            if (profileButton && profileDropdown) {
                profileButton.addEventListener('click', function() {
                    profileDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.relative')) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }
        });

        function toggleDetails(id) {
            const element = document.getElementById(id);
            element.classList.toggle('hidden');
        }
    </script>
</body>
</html>
