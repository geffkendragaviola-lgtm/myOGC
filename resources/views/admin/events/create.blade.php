@extends('layouts.admin')

@section('title', 'Create Event - Admin Panel')

@section('content')
    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Create New Event</h1>
            <p class="text-gray-600 mt-2">Fill in the details below to create a new mental health event</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Event Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('admin.events.store') }}">
                @csrf

                <!-- Event Details Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Event Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Event Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Event Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]"
                                   placeholder="Enter event title" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Event Type</label>
                            <select id="type" name="type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]" required>
                                <option value="">Select Event Type</option>
                                <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="seminar" {{ old('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="webinar" {{ old('type') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                <option value="conference" {{ old('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assign to Counselor -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to Counselor</label>
                            <select id="user_id" name="user_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]" required>
                                <option value="">Select Counselor</option>
                                @foreach($counselors as $counselor)
                                    <option value="{{ $counselor->user_id }}" {{ old('user_id') == $counselor->user_id ? 'selected' : '' }}>
                                        {{ $counselor->user->first_name }} {{ $counselor->user->last_name }} - {{ $counselor->position }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Attendees -->
                        <div>
                            <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-2">Max Attendees (Optional)</label>
                            <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]"
                                   placeholder="Leave empty for unlimited" min="1">
                            @error('max_attendees')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Date and Time Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Date and Time</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div>
                            <label for="event_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]" required>
                            @error('event_start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="event_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]" required>
                            @error('event_end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                            <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]" required>
                            @error('start_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                            <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]" required>
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location and Description Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Location and Description</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]"
                                   placeholder="Enter event location (e.g., Room 101, Online, etc.)" required>
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="5"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]"
                                      placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Target Audience Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Target Audience</h3>
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Colleges (Multi-select) -->
                        <div>
                            <label for="colleges" class="block text-sm font-medium text-gray-700 mb-2">
                                Target Colleges (Optional - Leave empty for all colleges)
                            </label>
                            <select id="colleges" name="colleges[]" multiple
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]"
                                    size="6">
                                @foreach($colleges ?? [] as $college)
                                    <option value="{{ $college->id }}" {{ in_array($college->id, old('colleges', [])) ? 'selected' : '' }}>
                                        {{ $college->name }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple colleges. Leave empty to make this event available to all colleges.</p>
                            @error('colleges')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Event Options Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Event Options</h3>
                    <div class="space-y-4">
                        <!-- Is Required Event -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" id="is_required" name="is_required" value="1"
                                   {{ old('is_required') ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="is_required" class="ml-3 text-sm font-medium text-gray-700">
                                <span class="font-semibold text-red-600">Required Event</span> - This event is mandatory for selected colleges
                            </label>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-[#F00000] border-gray-300 rounded focus:ring-[#F00000]">
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                                Activate this event immediately
                            </label>
                        </div>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <button type="reset"
                            class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                        <i class="fas fa-redo mr-2"></i> Reset Form
                    </button>
                    <button type="submit"
                            class="bg-[#F00000] text-white px-6 py-2 rounded-md hover:bg-[#D40000] transition flex items-center">
                        <i class="fas fa-save mr-2"></i> Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add some client-side validation and UX enhancements
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('event_start_date');
            const endDate = document.getElementById('event_end_date');
            const startTime = document.getElementById('start_time');
            const endTime = document.getElementById('end_time');

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            startDate.min = today;
            endDate.min = today;

            // Update end date min when start date changes
            startDate.addEventListener('change', function() {
                endDate.min = this.value;
            });

            // Validate time range
            function validateTimeRange() {
                if (startDate.value === endDate.value && startTime.value && endTime.value) {
                    if (startTime.value >= endTime.value) {
                        endTime.setCustomValidity('End time must be after start time');
                    } else {
                        endTime.setCustomValidity('');
                    }
                }
            }

            startTime.addEventListener('change', validateTimeRange);
            endTime.addEventListener('change', validateTimeRange);
            startDate.addEventListener('change', validateTimeRange);
            endDate.addEventListener('change', validateTimeRange);
        });
    </script>
@endsection