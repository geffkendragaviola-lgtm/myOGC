@extends('layouts.app')

@section('title', 'Edit Event - Admin Dashboard')

@section('content')
<style>
    .event-form-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-input {
        transition: all 0.3s ease;
    }

    .form-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>

<!-- Main Content -->
<div class="container mx-auto px-6 py-8 max-w-4xl event-form-container">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit Event</h1>
                <p class="text-gray-600 mt-2">Update the event details below</p>
            </div>
            <a href="{{ route('admin.events') }}"
               class="mt-4 md:mt-0 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Events
            </a>
        </div>
    </div>

    <!-- Event Form -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.events.update', $event) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Event Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input"
                           placeholder="Enter event title" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Event Type *</label>
                    <select id="type" name="type"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input" required>
                        <option value="">Select Event Type</option>
                        <option value="workshop" {{ old('type', $event->type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="seminar" {{ old('type', $event->type) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="webinar" {{ old('type', $event->type) == 'webinar' ? 'selected' : '' }}>Webinar</option>
                        <option value="conference" {{ old('type', $event->type) == 'conference' ? 'selected' : '' }}>Conference</option>
                        <option value="other" {{ old('type', $event->type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assign to Counselor -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to Counselor *</label>
                    <select id="user_id" name="user_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input" required>
                        <option value="">Select Counselor</option>
                        @foreach($counselors as $counselor)
                            <option value="{{ $counselor->user_id }}" {{ old('user_id', $event->user_id) == $counselor->user_id ? 'selected' : '' }}>
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
                    <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input"
                           placeholder="Leave empty for unlimited" min="1">
                    @error('max_attendees')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="event_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                    <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date', $event->event_start_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input" required>
                    @error('event_start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="event_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                    <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date', $event->event_end_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input" required>
                    @error('event_end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input" required>
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input" required>
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div class="md:col-span-2">
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input"
                           placeholder="Enter event location (e.g., Room 101, Online, etc.)" required>
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="5"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition form-input"
                              placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $event->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                            Activate this event
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Event Information -->
            <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="text-lg font-semibold text-blue-800 mb-4">Current Event Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-blue-700">Current Counselor:</span>
                        <span class="text-blue-900">{{ $event->user->first_name }} {{ $event->user->last_name }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-700">Current Status:</span>
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $event->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-700">Registrations:</span>
                        <span class="text-blue-900">{{ $event->registrations->where('status', 'registered')->count() }} active</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-700">Created:</span>
                        <span class="text-blue-900">{{ $event->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex flex-col md:flex-row gap-4 justify-end">
                <a href="{{ route('admin.events') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i> Update Event
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Add client-side validation and UX enhancements
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

        // Show confirmation for significant changes
        const form = document.querySelector('form');
        const originalData = new FormData(form);

        form.addEventListener('submit', function(e) {
            let hasSignificantChanges = false;
            const currentData = new FormData(form);

            // Check for changes in critical fields
            const criticalFields = ['user_id', 'event_start_date', 'event_end_date', 'start_time', 'end_time'];

            for (let [key, value] of currentData.entries()) {
                if (criticalFields.includes(key)) {
                    const originalValue = originalData.get(key);
                    if (originalValue !== value) {
                        hasSignificantChanges = true;
                        break;
                    }
                }
            }

            if (hasSignificantChanges) {
                if (!confirm('You are making significant changes to this event. This may affect student registrations. Continue?')) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
@endsection
