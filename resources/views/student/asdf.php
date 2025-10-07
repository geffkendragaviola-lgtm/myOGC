<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Event Registrations - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    @include('partials.navbar')

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
        @elseif($registrations->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <i class="fas fa-calendar-plus text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Event Registrations</h3>
                <p class="text-gray-500 mb-6">You haven't registered for any events yet.</p>
                <a href="{{ route('student.events.available') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    Browse Available Events
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6">
                @foreach($registrations as $registration)
                    @php $event = $registration->event; @endphp
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full capitalize">
                                            {{ $event->type }}
                                        </span>
                                        <span class="text-sm {{ $registration->status === 'registered' ? 'text-green-600' : 'text-gray-600' }}">
                                            Status: {{ ucfirst($registration->status) }}
                                        </span>
                                    </div>

                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $event->title }}</h3>

                                    <div class="flex flex-wrap gap-4 mb-3 text-sm text-gray-600">
                                        <span><i class="far fa-calendar mr-1"></i> {{ $event->date_range }}</span>
                                        <span><i class="far fa-clock mr-1"></i> {{ $event->time_range }}</span>
                                        <span><i class="far fa-map-marker-alt mr-1"></i> {{ $event->location }}</span>
                                        <span><i class="far fa-calendar-check mr-1"></i> Registered: {{ $registration->registered_at->format('M j, Y g:i A') }}</span>
                                    </div>

                                    <p class="text-gray-600 mb-4">{{ $event->description }}</p>
                                </div>

                                <div class="mt-4 md:mt-0 md:ml-6">
                                    @if($registration->status === 'registered' && $event->is_upcoming)
                                        <form action="{{ route('student.events.cancel', $event) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                                Cancel Registration
                                            </button>
                                        </form>
                                    @elseif(!$event->is_upcoming)
                                        <span class="text-gray-500 text-sm">Event ended</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @include('partials.footer')
</body>
</html>
