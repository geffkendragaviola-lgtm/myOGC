@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Announcement</h1>
        <p class="text-gray-600 mt-2">Update the announcement details below</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ route('counselor.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Announcement Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Announcement Image (Optional)</label>

<!-- Current Image Preview -->
@if($announcement->image_url)
    <div class="mb-4">
        <div class="relative inline-block">
            <img src="{{ $announcement->image_url }}"
                 alt="Current announcement image"
                 class="h-64 w-full object-cover rounded-lg shadow-md mb-2">

            <!-- Simple form outside the main form -->
        </div>
        <p class="text-sm text-gray-500">Current image - upload a new one to replace</p>

        <!-- Form placed OUTSIDE the main edit form -->
        <form action="{{ route('counselor.announcements.remove-image', $announcement) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition flex items-center"
                    onclick="return confirm('Are you sure you want to remove this image?')">
                <i class="fas fa-times mr-2"></i> Remove Current Image
            </button>
        </form>
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
                           value="{{ old('title', $announcement->title) }}"
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
                              placeholder="Enter announcement content..." required>{{ old('content', $announcement->content) }}</textarea>
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
                                   {{ old('for_all_colleges', $announcement->for_all_colleges) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="for_all_colleges_true" class="ml-2 text-sm text-gray-700">
                                All Colleges - Announcement visible to students from all colleges
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" id="for_all_colleges_false" name="for_all_colleges" value="0"
                                   {{ old('for_all_colleges', $announcement->for_all_colleges) === '0' ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="for_all_colleges_false" class="ml-2 text-sm text-gray-700">
                                Specific Colleges - Choose which colleges can see this announcement
                            </label>
                        </div>
                    </div>

                    <!-- College Selection (shown only when specific colleges is selected) -->
                    <div id="colleges_selection" class="{{ old('for_all_colleges', $announcement->for_all_colleges) ? 'hidden' : '' }}">
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
                           value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Leave empty to start immediately</p>
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                    <input type="date" name="end_date" id="end_date"
                           value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Leave empty for no end date</p>
                </div>

                <!-- Active Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">
                            Activate this announcement immediately
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Status Display -->
                <div class="md:col-span-2">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <h3 class="text-sm font-medium text-blue-800 mb-2">Current Status</h3>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="flex items-center space-x-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($announcement->status === 'active') bg-green-100 text-green-800
                                    @elseif($announcement->status === 'scheduled') bg-blue-100 text-blue-800
                                    @elseif($announcement->status === 'expired') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($announcement->status) }}
                                </span>
                                <span class="text-blue-600">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Created: {{ $announcement->created_at->format('M j, Y') }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-users mr-1"></i>
                                @if($announcement->for_all_colleges)
                                    Visible to all colleges
                                @else
                                    Targeted to {{ $announcement->colleges->count() }} college(s)
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex flex-col md:flex-row gap-4 justify-end">
                <a href="{{ route('counselor.announcements.index') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-center">
                    Cancel
                </a>

                <!-- Preview Button -->
                <button type="button"
                        onclick="previewAnnouncement()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center justify-center">
                    <i class="fas fa-eye mr-2"></i> Preview
                </button>

                <!-- Update Button -->
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i> Update Announcement
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Announcement Preview</h3>
                <button type="button"
                        onclick="closePreview()"
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="border border-gray-200 rounded-lg p-6 bg-white">
                <!-- Image Preview -->
                <div id="previewImageContainer" class="mb-4 hidden">
                    <img id="previewImage" class="w-full h-48 object-cover rounded-lg shadow-md">
                </div>

                <!-- College Targeting Preview -->
                <div class="flex flex-wrap gap-2 mb-4" id="previewColleges">
                    <!-- College badges will be populated by JavaScript -->
                </div>

                <div class="flex justify-between items-start mb-4">
                    <div class="text-sm text-blue-600 font-semibold" id="previewDate">
                        {{ \Carbon\Carbon::now()->format('F j, Y') }}
                    </div>
                    <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded hidden" id="previewDateRange">
                        <!-- Date range will be populated by JavaScript -->
                    </div>
                </div>

                <h3 class="text-xl font-bold text-gray-800 mb-4" id="previewTitle"></h3>
                <div class="text-gray-600 whitespace-pre-line leading-relaxed" id="previewContent"></div>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        Posted by: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button"
                        onclick="closePreview()"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Close Preview
                </button>
            </div>
        </div>
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
            // Validate file size (2MB)
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

function previewAnnouncement() {
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const forAllColleges = document.getElementById('for_all_colleges_true').checked;
    const selectedColleges = Array.from(document.querySelectorAll('input[name="colleges[]"]:checked')).map(cb => cb.nextElementSibling.textContent.trim());
    const imageFile = document.getElementById('image').files[0];

    // Update preview content
    document.getElementById('previewTitle').textContent = title || 'No title';
    document.getElementById('previewContent').textContent = content || 'No content';

    // Update college targeting preview
    const collegesContainer = document.getElementById('previewColleges');
    collegesContainer.innerHTML = '';

    if (forAllColleges) {
        collegesContainer.innerHTML = `
            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">
                <i class="fas fa-globe mr-1"></i> All Colleges
            </span>
        `;
    } else if (selectedColleges.length > 0) {
        selectedColleges.slice(0, 3).forEach(college => {
            const badge = document.createElement('span');
            badge.className = 'bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium';
            badge.innerHTML = `<i class="fas fa-university mr-1"></i> ${college}`;
            collegesContainer.appendChild(badge);
        });
        if (selectedColleges.length > 3) {
            const moreBadge = document.createElement('span');
            moreBadge.className = 'bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium';
            moreBadge.textContent = `+${selectedColleges.length - 3} more`;
            collegesContainer.appendChild(moreBadge);
        }
    }

    // Update image preview
    const imageContainer = document.getElementById('previewImageContainer');
    const previewImage = document.getElementById('previewImage');

    if (imageFile) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            imageContainer.classList.remove('hidden');
        };
        reader.readAsDataURL(imageFile);
    } else if ('{{ $announcement->image_url }}') {
        previewImage.src = '{{ $announcement->image_url }}';
        imageContainer.classList.remove('hidden');
    } else {
        imageContainer.classList.add('hidden');
    }

    // Update date range
    const dateRangeElement = document.getElementById('previewDateRange');
    if (startDate && endDate) {
        const start = new Date(startDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        const end = new Date(endDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        dateRangeElement.textContent = `Valid: ${start} - ${end}`;
        dateRangeElement.classList.remove('hidden');
    } else if (startDate) {
        const start = new Date(startDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        dateRangeElement.textContent = `Starts: ${start}`;
        dateRangeElement.classList.remove('hidden');
    } else if (endDate) {
        const end = new Date(endDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        dateRangeElement.textContent = `Until: ${end}`;
        dateRangeElement.classList.remove('hidden');
    } else {
        dateRangeElement.classList.add('hidden');
    }

    // Show modal
    document.getElementById('previewModal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreview();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePreview();
    }
});

// Auto-update end date minimum based on start date
document.getElementById('start_date').addEventListener('change', function() {
    const endDateInput = document.getElementById('end_date');
    if (this.value) {
        endDateInput.min = this.value;
    } else {
        endDateInput.min = '';
    }
});
</script>
@endsection
