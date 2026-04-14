@extends('layouts.admin')

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
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/40">
    <div class="container mx-auto px-4 md:px-6 py-6 md:py-8 max-w-5xl event-form-container">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-red-500 text-lg"></i>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Edit Event</h1>
                    </div>
                    <p class="text-gray-500 text-sm ml-1">Update the event details below</p>
                </div>
                <a href="{{ route('admin.events') }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2 text-sm"></i> Back to Events
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-emerald-500"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mr-2 text-red-500 mt-0.5"></i>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Event Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <form method="POST" action="{{ route('admin.events.update', $event) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Event Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Event Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none form-input"
                               placeholder="Enter event title" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Event Type <span class="text-red-500">*</span></label>
                        <select id="type" name="type"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 outline-none bg-white text-gray-700" required>
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
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Assign to Counselor <span class="text-red-500">*</span></label>
                        <select id="user_id" name="user_id"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 outline-none bg-white text-gray-700" required>
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
                        <label for="max_attendees" class="block text-sm font-medium text-gray-700 mb-2">Max Attendees <span class="text-gray-400 text-xs">(Optional)</span></label>
                        <input type="number" id="max_attendees" name="max_attendees" value="{{ old('max_attendees', $event->max_attendees) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none form-input"
                               placeholder="Leave empty for unlimited" min="1">
                        @error('max_attendees')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="event_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" id="event_start_date" name="event_start_date" value="{{ old('event_start_date', $event->event_start_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none form-input" required>
                        @error('event_start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="event_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date <span class="text-red-500">*</span></label>
                        <input type="date" id="event_end_date" name="event_end_date" value="{{ old('event_end_date', $event->event_end_date->format('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none form-input" required>
                        @error('event_end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time <span class="text-red-500">*</span></label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none form-input" required>
                        @error('start_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time <span class="text-red-500">*</span></label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none form-input" required>
                        @error('end_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="md:col-span-2">
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location <span class="text-red-500">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none form-input"
                               placeholder="Enter event location (e.g., Room 101, Online, etc.)" required>
                        @error('location')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description <span class="text-red-500">*</span></label>
                        <textarea id="description" name="description" rows="5"
                                  class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none form-input"
                                  placeholder="Describe the event, its purpose, and what attendees can expect..." required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="md:col-span-2">
                        <div class="flex items-center p-4 bg-amber-50 rounded-xl border border-amber-100">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $event->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                                Activate this event
                            </label>
                        </div>
                        @error('is_active')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Event Information Card -->
                <div class="mt-8 p-5 bg-gradient-to-r from-amber-50 to-white border border-amber-100 rounded-xl">
                    <h3 class="text-base font-semibold text-amber-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-amber-500"></i>
                        Current Event Information
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-500 block text-xs uppercase tracking-wide">Counselor</span>
                            <span class="text-gray-800">{{ $event->user->first_name }} {{ $event->user->last_name }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500 block text-xs uppercase tracking-wide">Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $event->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $event->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500 block text-xs uppercase tracking-wide">Registrations</span>
                            <span class="text-gray-800">{{ $event->registrations->where('status', 'registered')->count() }} active</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-500 block text-xs uppercase tracking-wide">Created</span>
                            <span class="text-gray-800">{{ $event->created_at->format('M j, Y g:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('admin.events') }}"
                       class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-all duration-200 text-center">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-save text-sm"></i>
                        Update Event
                    </button>
                </div>
            </form>
        </div>
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
            if (endDate.value && endDate.value < this.value) {
                endDate.value = this.value;
            }
        });

        // Validate time range
        function validateTimeRange() {
            if (startDate.value === endDate.value && startTime.value && endTime.value) {
                if (startTime.value >= endTime.value) {
                    endTime.setCustomValidity('End time must be after start time');
                } else {
                    endTime.setCustomValidity('');
                }
            } else {
                endTime.setCustomValidity('');
            }
        }

        startTime.addEventListener('change', validateTimeRange);
        endTime.addEventListener('change', validateTimeRange);
        startDate.addEventListener('change', validateTimeRange);
        endDate.addEventListener('change', validateTimeRange);

        // Show confirmation for significant changes
        const form = document.querySelector('form');
        let originalData = new FormData(form);

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