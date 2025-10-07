<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Use your existing navbar from dashboard -->

    <div class="container mx-auto px-6 py-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Available Events</h1>
            <p class="text-gray-600 mt-2">Register for upcoming mental health events and workshops</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        @if($events->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="fas fa-calendar-times text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Events Available</h3>
                <p class="text-gray-500">Check back later for upcoming events.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                                <div class="flex-1">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mb-2 capitalize">
                                        {{ $event->type }}
                                    </span>
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>

                                    <div class="flex flex-wrap gap-4 mb-3 text-sm text-gray-600">
                                        <span><i class="far fa-calendar mr-1"></i> {{ $event->date_range }}</span>
                                        <span><i class="far fa-clock mr-1"></i> {{ $event->time_range }}</span>
                                        <span><i class="far fa-map-marker-alt mr-1"></i> {{ $event->location }}</span>
                                        @if($event->max_attendees)
                                            <span><i class="far fa-users mr-1"></i> {{ $event->available_slots }} slots left</span>
                                        @endif
                                    </div>

                                    <p class="text-gray-600 mb-4">{{ $event->description }}</p>

                                    @if($event->user)
                                        <p class="text-sm text-gray-500">
                                            <i class="far fa-user mr-1"></i> Organized by:
                                            {{ $event->user->first_name }} {{ $event->user->last_name }}
                                        </p>
                                    @endif
                                </div>

                                <div class="mt-4 md:mt-0 md:ml-6 flex flex-col items-end">
                                    @if($student)
                                        @if($event->isRegisteredByStudent($student))
                                            <form action="{{ route('student.events.cancel', $event) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition mb-2">
                                                    Cancel Registration
                                                </button>
                                            </form>
                                            <span class="text-green-600 font-semibold text-sm">
                                                <i class="fas fa-check-circle"></i> Registered
                                            </span>
                                        @elseif($event->hasAvailableSlots())
                                            <form action="{{ route('student.events.register', $event) }}" method="POST">
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
                                        <p class="text-yellow-600 text-sm text-center">
                                            <i class="fas fa-exclamation-triangle"></i><br>
                                            Complete your student profile to register
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


</body>
</html>
