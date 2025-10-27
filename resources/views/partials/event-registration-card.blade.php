@php
    $event = $registration->event;
    $student = Auth::user()->student; // Get the student from the authenticated user
    $isRequiredEvent = $event->is_required && $event->isRequiredForStudent($student);
@endphp

<div class="event-card bg-white rounded-xl shadow-sm overflow-hidden border-l-4 {{ $isRequiredEvent ? 'border-red-500' : 'border-blue-500' }}">
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
                <span class="status-badge bg-green-100 text-green-800 backdrop-blur-sm">
                    {{ ucfirst($registration->status) }}
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
                <i class="far fa-map-marker-alt mr-2 text-red-500"></i>
                <span class="line-clamp-1">{{ $event->location }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-600">
                <i class="far fa-calendar-check mr-2 text-purple-500"></i>
                <span>Registered: {{ $registration->registered_at->format('M j, Y') }}</span>
            </div>
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
            @if($registration->status === 'registered' && $event->is_upcoming)
                @if($isRequiredEvent)
                    <!-- Required Event - Cannot Cancel -->
                    <button class="flex-1 bg-gray-400 text-white text-sm px-3 py-2 rounded-lg cursor-not-allowed flex items-center justify-center"
                            disabled title="Required events cannot be cancelled">
                        <i class="fas fa-lock mr-1"></i>
                        <span class="hidden sm:inline">Cannot Cancel</span>
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
            @elseif($registration->status === 'attended')
                <span class="flex-1 bg-green-100 text-green-700 text-sm px-3 py-2 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-double mr-1"></i>
                    <span class="hidden sm:inline">Attended</span>
                </span>
            @elseif(!$event->is_upcoming)
                <span class="flex-1 bg-gray-100 text-gray-600 text-sm px-3 py-2 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-times mr-1"></i>
                    <span class="hidden sm:inline">Event Ended</span>
                </span>
            @endif

            <!-- View Details Button -->
            <button onclick="toggleDetails('details-{{ $registration->id }}')"
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

        <!-- Expandable Details -->
        <div id="details-{{ $registration->id }}" class="hidden mt-4 pt-4 border-t border-gray-100">
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
                        @else
                            Unlimited capacity
                        @endif
                    </span>
                </div>

                <!-- Registration Date -->
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-700">Registration Date:</span>
                    <span class="text-gray-600">{{ $registration->registered_at->format('M j, Y g:i A') }}</span>
                </div>

                <!-- Event Requirements Information -->
                @if($isRequiredEvent)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-red-500 mr-2"></i>
                            <span class="text-red-800 font-medium text-sm">Required Event</span>
                        </div>
                        <p class="text-red-700 text-xs mt-1">
                            This event is required for your college. Attendance is mandatory and registration cannot be cancelled.
                        </p>
                    </div>
                @endif

                <!-- Cancellation Info for Optional Events -->
                @if(!$isRequiredEvent && $event->is_upcoming && $registration->status === 'registered')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-blue-800 font-medium text-sm">Cancellation Policy</span>
                        </div>
                        <p class="text-blue-700 text-xs mt-1">
                            You can cancel your registration up to 24 hours before the event starts.
                            @if($event->isCancellationAllowed())
                                <br><strong>Cancellation cutoff: {{ $event->getCancellationCutoffTime() }}</strong>
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
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

    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function toggleDetails(id) {
        const element = document.getElementById(id);
        element.classList.toggle('hidden');
    }
</script>
