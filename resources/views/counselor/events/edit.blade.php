
    <style>
        .event-form-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .event-form-navbar {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8 max-w-4xl">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Edit Event</h1>
                <p class="text-gray-600 mt-2">Update the event details below</p>
            </div>

            <!-- Event Form -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form method="POST" action="{{ route('counselor.events.update', $event) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Event Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Enter event title" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Event Type *</label>
                            <select id="type" name="type"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="">Select Event Type</option>
                                <option value="webinar" {{ old('type', $event->type) == 'webinar' ? 'selected' : '' }}>Webinar</option>
                                <option value="workshop" {{ old('type', $event->type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="seminar" {{ old('type', $event->type) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="activity" {{ old('type', $event->type) == 'activity' ? 'selected' : '' }}>Activity</option>
                                <option value="conference" {{ old('type', $event->type) == 'conference' ? 'selected' : '' }}>Conference</option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Attendees -->
                        <div>
                            <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-2">Max Attendees (Optional)</label>
                            <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Leave empty for unlimited" min="1">
                            @error('max_attendees')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label for="event_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                            <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date', $event->event_start_date->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            @error('event_start_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="event_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                            <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date', $event->event_end_date->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            @error('event_end_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Time -->
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                            <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            @error('start_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div>
                            <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                            <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            @error('end_time')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="md:col-span-2">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location *</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Enter event location (e.g., Room 101, Online, etc.)" required>
                            @error('location')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea id="description" name="description" rows="5"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                      placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="md:col-span-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                       {{ old('is_active', $event->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="ml-2 text-sm text-gray-700">
                                    Activate this event
                                </label>
                            </div>
                            @error('is_active')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-8 flex flex-col md:flex-row gap-4 justify-end">
                        <a href="{{ route('counselor.events.index') }}"
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




@endsection
