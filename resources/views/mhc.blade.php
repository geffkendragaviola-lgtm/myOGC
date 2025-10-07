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

            .mhc-event-date {
                background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
                color: white;
                border-radius: 12px;
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

            .mhc-faq-item {
    transition: all 0.3s ease;
}

.mhc-faq-item.mhc-faq-active {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.mhc-faq-question {
    transition: background-color 0.3s ease;
}

.mhc-faq-answer {
    transition: max-height 0.3s ease;
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
<!-- In your Mental Health Corner view, update the events section -->
<section class="mb-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Upcoming Events</h2>
        <a href="{{ route('student.events.available') }}" class="text-blue-600 hover:text-blue-800 font-semibold">View All Events</a>
    </div>

    @php
        $events = \App\Models\Event::with('user')->upcoming()->active()->limit(3)->get();
        $student = Auth::user()->student;
    @endphp

    @if($events->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <p class="text-gray-500">No upcoming events scheduled.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($events as $event)
                <div class="mhc-event-card bg-white rounded-xl shadow-md overflow-hidden flex flex-col md:flex-row">
                    <!-- Event Date Range -->
                    <div class="mhc-event-date flex flex-col items-center justify-center p-6 md:w-32 bg-blue-100">
                        <span class="text-lg font-bold text-blue-800">
                            {{ \Carbon\Carbon::parse($event->event_start_date)->format('M d') }}
                        </span>
                        <span class="text-lg font-bold text-blue-800">
                            – {{ \Carbon\Carbon::parse($event->event_end_date)->format('M d, Y') }}
                        </span>
                    </div>

                    <!-- Event Details -->
                    <div class="p-6 flex-1">
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-2 capitalize">
                            {{ $event->type }}
                        </span>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>

                        <div class="flex flex-wrap gap-4 mb-3 text-sm text-gray-600">
                            <span>
                                <i class="far fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                – {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                            </span>
                            <span>
                                <i class="far fa-map-marker-alt mr-1"></i> {{ $event->location }}
                            </span>
                            @if($event->max_attendees)
                                <span>
                                    <i class="far fa-users mr-1"></i>
                                    {{ $event->available_slots }} of {{ $event->max_attendees }} slots available
                                </span>
                            @endif
                        </div>

                        <p class="text-gray-600 mb-4">{{ $event->description }}</p>

                        <!-- Show event creator if available -->
                        @if($event->user)
                            <p class="text-sm text-gray-500 mb-4">
                                <i class="far fa-user mr-1"></i> Organized by:
                                {{ $event->user->first_name }} {{ $event->user->last_name }}
                            </p>
                        @endif

                        <!-- Registration Button -->
                        @if($student)
                            @if($event->isRegisteredByStudent($student))
                                <form action="{{ route('student.events.cancel', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                        Cancel Registration
                                    </button>
                                </form>
                                <span class="ml-3 text-green-600 font-semibold">
                                    <i class="fas fa-check-circle"></i> Registered
                                </span>
                            @elseif($event->hasAvailableSlots())
                                <form action="{{ route('student.events.register', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                        Register Now
                                    </button>
                                </form>
                            @else
                                <button class="bg-gray-400 text-white px-4 py-2 rounded-lg cursor-not-allowed" disabled>
                                    Event Full
                                </button>
                            @endif
                        @else
                            <p class="text-yellow-600 text-sm">
                                <i class="fas fa-exclamation-triangle"></i>
                                Complete your student profile to register for events.
                            </p>
                        @endif
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
        <a href="#" class="text-blue-600 hover:text-blue-800 font-semibold">Browse All Resources</a>
    </div>

    @php
        $resources = \App\Models\Resource::active()->ordered()->get();
    @endphp

    @if($resources->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <p class="text-gray-500">No resources available at this time.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($resources as $resource)
                <div class="mhc-category-card bg-white rounded-xl shadow-md p-6 text-center">
                    <i class="{{ $resource->icon }} mhc-category-icon mb-4 text-3xl text-blue-600"></i>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $resource->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ $resource->description }}</p>

                    @if($resource->link)
                        <a href="{{ $resource->link }}" target="_blank" rel="noopener noreferrer"
                           class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition">
                            {{ $resource->button_text }}
                        </a>
                    @else
                        <button class="inline-block bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition">
                            {{ $resource->button_text }}
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
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
