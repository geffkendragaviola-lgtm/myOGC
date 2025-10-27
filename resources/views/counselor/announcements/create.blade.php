@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            {{ isset($announcement) ? 'Edit Announcement' : 'Create New Announcement' }}
        </h1>
        <p class="text-gray-600 mt-2">
            {{ isset($announcement) ? 'Update the announcement details below' : 'Fill in the details below to create a new announcement' }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ isset($announcement) ? route('counselor.announcements.update', $announcement) : route('counselor.announcements.store') }}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($announcement))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Announcement Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Announcement Image (Optional)</label>

                    <!-- Current Image Preview -->
                    @if(isset($announcement) && $announcement->image_url)
                        <div class="mb-4">
                            <div class="relative inline-block">
                                <img src="{{ $announcement->image_url }}"
                                     alt="Current announcement image"
                                     class="h-64 w-full object-cover rounded-lg shadow-md mb-2">
                                <a href="{{ route('counselor.announcements.remove-image', $announcement) }}"
                                   class="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition"
                                   onclick="return confirm('Are you sure you want to remove this image?')"
                                   title="Remove Image">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                            <p class="text-sm text-gray-500">Current image - upload a new one to replace</p>
                        </div>
                    @endif

                    <!-- Image Upload Area -->
                    <div class="flex items-center justify-center w-full">
                        <label for="image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF (MAX. 10MB)</p>
                            </div>
                            <input id="image" name="image" type="file" class="hidden" accept="image/*" />
                        </label>
                    </div>

                    <!-- New Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <div class="relative inline-block">
                            <img id="preview" class="max-w-full h-64 object-cover rounded-lg shadow-md">
                            <button type="button" onclick="removeImagePreview()"
                                    class="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition"
                                    title="Remove Image">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" id="title"
                           value="{{ old('title', $announcement->title ?? '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Enter announcement title" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="md:col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                    <textarea name="content" id="content" rows="6"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Enter announcement content..." required>{{ old('content', $announcement->content ?? '') }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- College Targeting -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">College Availability *</label>

                    <!-- All Colleges Option -->
                    <div class="mb-4 space-y-3">
                        <div class="flex items-center">
                            <input type="radio" id="for_all_colleges_true" name="for_all_colleges" value="1"
                                   {{ old('for_all_colleges', $announcement->for_all_colleges ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="for_all_colleges_true" class="ml-2 text-sm text-gray-700">
                                All Colleges - Announcement visible to students from all colleges
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="for_all_colleges_false" name="for_all_colleges" value="0"
                                   {{ old('for_all_colleges', $announcement->for_all_colleges ?? '') === '0' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="for_all_colleges_false" class="ml-2 text-sm text-gray-700">
                                Specific Colleges - Choose which colleges can see this announcement
                            </label>
                        </div>
                    </div>

                    <!-- College Selection (shown only when specific colleges is selected) -->
                    <div id="colleges_selection" class="{{ old('for_all_colleges', $announcement->for_all_colleges ?? true) ? 'hidden' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Colleges *</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-4 bg-white">
                            @foreach($colleges as $college)
                                <div class="flex items-center">
                                    <input type="checkbox" id="college_{{ $college->id }}" name="colleges[]"
                                           value="{{ $college->id }}"
                                           {{ in_array($college->id, old('colleges', $selectedColleges ?? [])) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="college_{{ $college->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $college->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('colleges')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dates -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date (Optional)</label>
                    <input type="date" name="start_date" id="start_date"
                           value="{{ old('start_date', isset($announcement->start_date) ? $announcement->start_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ old('end_date', isset($announcement->end_date) ? $announcement->end_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Activate this announcement immediately
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex flex-col md:flex-row gap-4 justify-end">
                <a href="{{ route('counselor.announcements.index') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>
                    {{ isset($announcement) ? 'Update Announcement' : 'Create Announcement' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // College selection toggle
    const allCollegesRadio = document.getElementById('for_all_colleges_true');
    const specificCollegesRadio = document.getElementById('for_all_colleges_false');
    const collegesSelection = document.getElementById('colleges_selection');

    function toggleCollegesSelection() {
        if (specificCollegesRadio.checked) {
            collegesSelection.classList.remove('hidden');
        } else {
            collegesSelection.classList.add('hidden');
            // Uncheck all college checkboxes when "all colleges" is selected
            document.querySelectorAll('input[name="colleges[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
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
            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                this.value = '';
                return;
            }

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

    // Remove image preview
    window.removeImagePreview = function() {
        imageInput.value = '';
        imagePreview.classList.add('hidden');
    }

    // Initial toggle
    toggleCollegesSelection();
});
</script>
@endsection
