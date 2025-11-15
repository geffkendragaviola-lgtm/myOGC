@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Create New Resource</h1>
                <p class="text-gray-600 mt-2">Add a new mental health resource for students</p>
            </div>
            <a href="{{ route('counselor.resources.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Resources
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('counselor.resources.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon *</label>
                    <select name="icon"
                            id="icon"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select an icon</option>
                        @foreach($icons as $icon)
                            <option value="{{ $icon }}" {{ old('icon') == $icon ? 'selected' : '' }}>
                                {{ $icon }}
                            </option>
                        @endforeach
                    </select>
                    @error('icon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select name="category"
                            id="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="">Select a category</option>
                        @foreach($categories as $value => $label)
                            <option value="{{ $value }}" {{ old('category') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button Text -->
                <div>
                    <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text *</label>
                    <input type="text"
                           name="button_text"
                           id="button_text"
                           value="{{ old('button_text') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Watch Video, Read eBook, Access Content"
                           required>
                    @error('button_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link -->
                <div class="md:col-span-2">
                    <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Resource Link *</label>
                    <input type="url"
                           name="link"
                           id="link"
                           value="{{ old('link') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="https://example.com/resource or https://youtube.com/watch?v=..."
                           required>
                    @error('link')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Resource Image</label>
                    <input type="file"
                           name="image"
                           id="image"
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Upload a custom image for this resource (optional)</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- YouTube Thumbnail Option -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="use_yt_thumbnail"
                               id="use_yt_thumbnail"
                               value="1"
                               {{ old('use_yt_thumbnail') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="use_yt_thumbnail" class="ml-2 block text-sm text-gray-700">
                            Use YouTube thumbnail (for YouTube links only)
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 ml-6">
                        If checked and this is a YouTube link, the YouTube video thumbnail will be used automatically
                    </p>
                </div>

                <!-- Disclaimer Section -->
                <div class="md:col-span-2 border-t pt-6 mt-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Disclaimer Settings</h3>

                    <!-- Show Disclaimer Checkbox -->
                    <div class="mb-4">
                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="show_disclaimer"
                                   id="show_disclaimer"
                                   value="1"
                                   {{ old('show_disclaimer') ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="show_disclaimer" class="ml-2 block text-sm font-medium text-gray-700">
                                Show disclaimer for this resource
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 ml-6">
                            When checked, a disclaimer will be displayed prominently with this resource
                        </p>
                    </div>

                    <!-- Disclaimer Text -->
                    <div id="disclaimer_text_container" class="{{ old('show_disclaimer') ? '' : 'hidden' }}">
                        <label for="disclaimer_text" class="block text-sm font-medium text-gray-700 mb-2">
                            Custom Disclaimer Text (Optional)
                        </label>
                        <textarea name="disclaimer_text"
                                  id="disclaimer_text"
                                  rows="3"
                                  placeholder="Enter custom disclaimer text. If left empty, a default disclaimer will be used."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('disclaimer_text') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            Default disclaimer: "We do not claim ownership of this content. All rights, credits, and copyrights belong to the original owners. These resources are shared for educational and informational purposes only."
                        </p>
                        @error('disclaimer_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Active Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox"
                               name="is_active"
                               id="is_active"
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Make this resource active and visible to students
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('counselor.resources.index') }}"
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create Resource
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-check YouTube thumbnail option for YouTube links
    document.getElementById('link').addEventListener('input', function() {
        const link = this.value;
        const ytCheckbox = document.getElementById('use_yt_thumbnail');

        if (link.includes('youtube.com') || link.includes('youtu.be')) {
            ytCheckbox.checked = true;
        }
    });

    // Toggle disclaimer text visibility
    document.getElementById('show_disclaimer').addEventListener('change', function() {
        const disclaimerContainer = document.getElementById('disclaimer_text_container');
        if (this.checked) {
            disclaimerContainer.classList.remove('hidden');
        } else {
            disclaimerContainer.classList.add('hidden');
        }
    });
</script>
@endsection
