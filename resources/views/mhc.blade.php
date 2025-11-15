<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Health Corner - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .mhc-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .mhc-navbar {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .mhc-main-content {
            min-height: calc(100vh - 200px);
            background: linear-gradient(to bottom, #f8fafc, #e2e8f0);
        }

        .mhc-page-header {
            background: linear-gradient(rgba(30, 64, 175, 0.9), rgba(30, 64, 175, 0.8)), url('https://images.unsplash.com/photo-1596363505724-6d24f19ad5a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
            background-size: cover;
            background-position: center;
            padding: 3rem 0;
            color: white;
        }

        .mhc-event-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .mhc-event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .mhc-category-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .mhc-category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .mhc-category-icon {
            font-size: 2rem;
            color: #3b82f6;
        }

        .mhc-faq-item {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .mhc-faq-question {
            background: white;
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
        }

        .mhc-faq-answer {
            background: #f8fafc;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }

        .mhc-faq-active .mhc-faq-answer {
            max-height: 200px;
            padding: 1.5rem;
        }

        .mhc-profile-dropdown {
            position: relative;
        }

        .mhc-profile-dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            padding: 1rem;
            min-width: 200px;
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .mhc-college-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .mhc-required-badge {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
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
    <div class="mhc-container">
        <!-- Navbar -->
        <nav class="mhc-navbar py-4">
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

                    <div class="mhc-profile-dropdown">
                        <button class="text-white p-2 rounded-full hover:bg-blue-700 transition">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="mhc-profile-dropdown-content hidden">
                            <div class="mb-3 border-b pb-2">
                                <div class="font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                                <div class="text-xs text-blue-600 capitalize">Role: {{ Auth::user()->role }}</div>
                            </div>
                            <a href="" class="block py-2 text-gray-700 hover:text-blue-600">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t pt-2 mt-2">
                                @csrf
                                <button type="submit" class="w-full text-left block py-2 text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="mhc-main-content">
            <!-- Page Header -->
            <div class="mhc-page-header">
                <div class="container mx-auto px-6 text-center">
                    <h1 class="text-4xl font-bold mb-4">Mental Health Corner</h1>
                    <p class="text-xl max-w-3xl mx-auto">Explore resources to support your mental well-being, including upcoming events, educational materials, and self-help resources.</p>
                </div>
            </div>

            <div class="container mx-auto px-6 py-8">
                <!-- Events Section -->
                <section class="mb-12">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Upcoming Events</h2>
                        <a href="{{ route('student.events.available') }}" class="text-blue-600 hover:text-blue-800 font-semibold">View All Events</a>
                    </div>

                    @php
                        $student = Auth::user()->student;
                        // Get events available for the student's college
                        if ($student) {
                            $events = \App\Models\Event::with(['user', 'colleges'])
                                ->upcoming()
                                ->active()
                                ->forCollege($student->college_id)
                                ->limit(3)
                                ->get();
                        } else {
                            $events = collect();
                        }
                    @endphp

                    @if(!$student)
                        <div class="bg-white rounded-xl shadow-md p-8 text-center">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mb-2"></i>
                                <p class="text-yellow-800 font-semibold">Complete Your Profile</p>
                                <p class="text-yellow-700 text-sm mt-1">Please complete your student profile to view and register for events.</p>
                            </div>
                        </div>
                    @elseif($events->isEmpty())
                        <div class="bg-white rounded-xl shadow-md p-8 text-center">
                            <p class="text-gray-500">No upcoming events available for your college at this time.</p>
                        </div>
                    @else
                        <!-- Events Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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

                                <div class="mhc-event-card bg-white rounded-xl shadow-md overflow-hidden border-l-4 {{ $isRequiredEvent ? 'border-red-500' : 'border-blue-500' }}">

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
                                                        <span class="mhc-required-badge text-xs backdrop-blur-sm bg-red-600/90">
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
                                                <span class="mhc-college-badge text-xs backdrop-blur-sm bg-green-600/90">
                                                    <i class="fas fa-globe mr-1"></i> All Colleges
                                                </span>
                                            @else
                                                <span class="mhc-college-badge text-xs backdrop-blur-sm bg-blue-600/90">
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
                                            <button onclick="toggleDetails('mhc-details-{{ $event->id }}')"
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
                                        <div id="mhc-details-{{ $event->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
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
                </section>

                <!-- Resources Section -->
<section class="mb-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Access Resources</h2>
    </div>

    @php
        $categories = \App\Models\Resource::getCategories();
        $categoryResources = [];
        foreach ($categories as $key => $name) {
            $categoryResources[$key] = \App\Models\Resource::byCategory($key)->active()->ordered()->get();
        }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($categories as $key => $name)
            @php
                $resources = $categoryResources[$key];
                $firstResource = $resources->first();
            @endphp

            <div class="mhc-category-card bg-white rounded-xl shadow-md overflow-hidden">
                <!-- Category Header Image -->
                <div class="relative h-32 bg-gradient-to-r from-blue-500 to-purple-600">
                    @if($firstResource && $firstResource->image_url)
                        <img src="{{ $firstResource->image_url }}"
                             alt="{{ $name }}"
                             class="w-full h-full object-cover opacity-80">
                    @endif
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <i class="{{ $firstResource->icon ?? 'fas fa-folder' }} text-white text-3xl"></i>
                    </div>
                </div>

                <!-- Category Content -->
                <div class="p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $name }}</h3>
                    <p class="text-gray-600 mb-4 text-sm">
                        {{ $resources->count() }} resource{{ $resources->count() !== 1 ? 's' : '' }} available
                    </p>

                    <a href="{{ route('student.resources.category', $key) }}"
                       class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition font-semibold">
                        @if($key === 'youtube') Explore Videos
                        @elseif($key === 'ebooks') Browse eBooks
                        @elseif($key === 'private') Access Content
                        @else View Resources
                        @endif
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</section>

                <!-- FAQs Section -->
                <section class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Frequently Asked Questions</h2>

                    @php
                        $faqs = \App\Models\FAQ::active()->ordered()->get();
                    @endphp

                    @if($faqs->isEmpty())
                        <div class="bg-white rounded-xl shadow-md p-8 text-center">
                            <p class="text-gray-500">No FAQs available at this time.</p>
                        </div>
                    @else
                        <div>
                            @foreach($faqs as $faq)
                                <div class="mhc-faq-item bg-white rounded-lg shadow-sm mb-4 overflow-hidden" id="faq-{{ $faq->id }}">
                                    <div class="mhc-faq-question cursor-pointer p-6 flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition" onclick="toggleFaq({{ $faq->id }})">
                                        <span class="font-semibold text-gray-800 text-lg">{{ $faq->question }}</span>
                                        <i class="fas fa-chevron-down text-gray-600 transition-transform"></i>
                                    </div>
                                    <div class="mhc-faq-answer overflow-hidden max-h-0 transition-all duration-300">
                                        <div class="p-6 bg-white">
                                            <p class="text-gray-600 leading-relaxed">{{ $faq->answer }}</p>
                                            @if($faq->category)
                                                <p class="text-sm text-gray-500 mt-3">
                                                    Category: <span class="capitalize">{{ $faq->category }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        </main>

        <script>
            // FAQ toggle functionality
            function toggleFaq(id) {
                const faqItem = document.getElementById('faq-' + id);
                const answer = faqItem.querySelector('.mhc-faq-answer');
                const icon = faqItem.querySelector('.mhc-faq-question i');

                // Toggle active class
                faqItem.classList.toggle('mhc-faq-active');

                // Toggle height for smooth animation
                if (faqItem.classList.contains('mhc-faq-active')) {
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    answer.style.maxHeight = '0';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            }

            // Initialize first FAQ as open on page load
            document.addEventListener('DOMContentLoaded', function() {
                const firstFaq = document.querySelector('.mhc-faq-item');
                if (firstFaq) {
                    toggleFaq(firstFaq.id.split('-')[1]);
                }
            });

            // Profile dropdown functionality
            document.addEventListener('DOMContentLoaded', function() {
                const profileDropdownBtn = document.querySelector('.mhc-profile-dropdown button');
                const profileDropdown = document.querySelector('.mhc-profile-dropdown-content');

                profileDropdownBtn.addEventListener('click', function() {
                    profileDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.mhc-profile-dropdown')) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            });

            // Toggle event details
            function toggleDetails(id) {
                const element = document.getElementById(id);
                element.classList.toggle('hidden');
            }
        </script>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-6 text-center">
                <p>&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
