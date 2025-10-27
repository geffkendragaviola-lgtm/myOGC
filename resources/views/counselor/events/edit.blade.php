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
            <form method="POST" action="{{ route('counselor.events.update', $event) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Event Image -->
                    <div class="md:col-span-2">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Event Image (Optional)</label>

                        <!-- Current Image Preview -->
                        @if($event->image)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                                <div class="relative inline-block">
                                    <img src="{{ $event->image_url }}"
                                         alt="{{ $event->title }}"
                                         class="w-64 h-48 object-cover rounded-lg shadow-md">
                                    <button type="button"
                                            onclick="confirmImageRemoval()"
                                            class="absolute top-2 right-2 bg-red-600 text-white p-1 rounded-full hover:bg-red-700 transition">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_image" id="remove_image" value="0">
                            </div>
                        @endif

                        <!-- Image Upload -->
                        <div class="flex items-center justify-center w-full">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                    <p class="mb-2 text-sm text-gray-500">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 2MB)</p>
                                    @if($event->image)
                                        <p class="text-xs text-blue-500 mt-2">Uploading a new image will replace the current one</p>
                                    @endif
                                </div>
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                            </label>
                        </div>

                        <!-- New Image Preview -->
                        <div id="image-preview" class="mt-4 hidden">
                            <p class="text-sm text-gray-600 mb-2">New Image Preview:</p>
                            <img id="preview" class="w-64 h-48 object-cover rounded-lg shadow-md">
                        </div>

                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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

                    <!-- College Selection -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">College Availability *</label>

                        <!-- All Colleges Option -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="radio" id="for_all_colleges_true" name="for_all_colleges" value="1"
                                       {{ old('for_all_colleges', $event->for_all_colleges) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <label for="for_all_colleges_true" class="ml-2 text-sm text-gray-700">
                                    All Colleges - Event available to students from all colleges
                                </label>
                            </div>

                            <div class="flex items-center mt-2">
                                <input type="radio" id="for_all_colleges_false" name="for_all_colleges" value="0"
                                       {{ old('for_all_colleges', $event->for_all_colleges) === '0' || !$event->for_all_colleges ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <label for="for_all_colleges_false" class="ml-2 text-sm text-gray-700">
                                    Specific Colleges - Choose which colleges can see this event
                                </label>
                            </div>
                        </div>

                        <!-- College Selection (shown only when specific colleges is selected) -->
                        <div id="colleges_selection" class="{{ old('for_all_colleges', $event->for_all_colleges) === '0' || !$event->for_all_colleges ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Colleges</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-4 bg-gray-50">
                                @foreach($colleges as $college)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="college_{{ $college->id }}" name="colleges[]"
                                               value="{{ $college->id }}"
                                               {{ in_array($college->id, old('colleges', $selectedColleges)) ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="college_{{ $college->id }}" class="ml-2 text-sm text-gray-700">
                                            {{ $college->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-sm text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Selected colleges will automatically be saved when you update the event.
                            </p>
                            @error('colleges')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Required Event -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="is_required" name="is_required" value="1"
                                   {{ old('is_required', $event->is_required) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_required" class="ml-2 text-sm text-gray-700">
                                Required Event - Students from selected colleges must attend this event
                            </label>
                        </div>
                        <p class="text-sm text-gray-500 ml-6 mt-1">
                            When checked, this event will be marked as mandatory for students in the selected colleges.
                        </p>
                        @error('is_required')
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
                        <p class="text-sm text-gray-500 ml-6 mt-1">
                            Active events are visible to students. Uncheck to hide this event from students.
                        </p>
                        @error('is_active')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Current Event Information -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">Current Event Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700">College Availability:</span>
                            <span class="ml-2">
                                @if($event->for_all_colleges)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                        All Colleges
                                    </span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $event->colleges->count() }} Specific {{ Str::plural('College', $event->colleges->count()) }}
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Event Status:</span>
                            <span class="ml-2">
                                @if($event->is_required)
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                                        Required Event
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
                                        Optional Event
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Current Registrations:</span>
                            <span class="ml-2">{{ $event->registered_count }} registered</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700">Available Slots:</span>
                            <span class="ml-2">
                                @if($event->max_attendees)
                                    {{ $event->available_slots }} of {{ $event->max_attendees }}
                                @else
                                    Unlimited
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Show current colleges if specific colleges are selected -->
                    @if(!$event->for_all_colleges && $event->colleges->isNotEmpty())
                        <div class="mt-3">
                            <span class="font-medium text-gray-700">Currently Available For:</span>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($event->colleges as $college)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                        {{ $college->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const allCollegesRadio = document.getElementById('for_all_colleges_true');
            const specificCollegesRadio = document.getElementById('for_all_colleges_false');
            const collegesSelection = document.getElementById('colleges_selection');

            function toggleCollegesSelection() {
                if (specificCollegesRadio.checked) {
                    collegesSelection.classList.remove('hidden');
                } else {
                    collegesSelection.classList.add('hidden');
                }
            }

            allCollegesRadio.addEventListener('change', toggleCollegesSelection);
            specificCollegesRadio.addEventListener('change', toggleCollegesSelection);

            // Image preview functionality
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('image-preview');
            const preview = document.getElementById('preview');

            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.addEventListener('load', function() {
                        preview.setAttribute('src', this.result);
                        imagePreview.classList.remove('hidden');
                    });
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.classList.add('hidden');
                }
            });

            // Initial toggle
            toggleCollegesSelection();
        });

        function confirmImageRemoval() {
            if (confirm('Are you sure you want to remove the current event image?')) {
                document.getElementById('remove_image').value = '1';
                // Hide the current image preview
                event.target.closest('.mb-4').style.display = 'none';
            }
        }
    </script>

@endsection
