@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8 max-w-4xl">
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

            <!-- Edit Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Announcement</h2>
                    <p class="text-gray-600 mt-1">Update your announcement details below.</p>
                </div>

                <form action="{{ route('counselor.announcements.update', $announcement) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="title"
                                   id="title"
                                   value="{{ old('title', $announcement->title) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="Enter announcement title"
                                   required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content"
                                      id="content"
                                      rows="8"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                      placeholder="Enter announcement content"
                                      required>{{ old('content', $announcement->content) }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">You can use multiple lines for better formatting.</p>
                        </div>

                        <!-- Date Range -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Start Date -->
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Start Date
                                </label>
                                <input type="date"
                                       name="start_date"
                                       id="start_date"
                                       value="{{ old('start_date', $announcement->start_date ? $announcement->start_date->format('Y-m-d') : '') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-1">Leave empty to start immediately</p>
                            </div>

                            <!-- End Date -->
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    End Date
                                </label>
                                <input type="date"
                                       name="end_date"
                                       id="end_date"
                                       value="{{ old('end_date', $announcement->end_date ? $announcement->end_date->format('Y-m-d') : '') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('end_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <p class="text-sm text-gray-500 mt-1">Leave empty for no end date</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">
                                        Announcement Status
                                    </label>
                                    <p class="text-sm text-gray-600">
                                        When active, this announcement will be visible to students.
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="is_active"
                                           id="is_active"
                                           value="1"
                                           {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                                           class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 text-sm text-gray-700 font-medium">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Current Status Display -->
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Current Status</h3>
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
                                    Created: {{ $announcement->created_at->format('M j, Y g:i A') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-200">
                            <div class="mb-4 sm:mb-0">
                                <a href="{{ route('counselor.announcements.index') }}"
                                   class="text-gray-600 hover:text-gray-800 transition flex items-center">
                                    <i class="fas fa-times mr-2"></i> Cancel
                                </a>
                            </div>
                            <div class="flex space-x-3">
                                <!-- Preview Button -->
                                <button type="button"
                                        onclick="previewAnnouncement()"
                                        class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                                    <i class="fas fa-eye mr-2"></i> Preview
                                </button>

                                <!-- Update Button -->
                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Update Announcement
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-sm text-blue-600 font-semibold" id="previewDate">
                            {{ \Carbon\Carbon::now()->format('F j, Y') }}
                        </div>
                        <div class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded" id="previewDateRange">
                            <!-- Date range will be populated by JavaScript -->
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-4" id="previewTitle"></h3>
                    <div class="text-gray-600 whitespace-pre-line" id="previewContent"></div>

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
        function previewAnnouncement() {
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Update preview content
            document.getElementById('previewTitle').textContent = title || 'No title';
            document.getElementById('previewContent').textContent = content || 'No content';

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
