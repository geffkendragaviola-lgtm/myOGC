@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8 max-w-4xl">
            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ isset($announcement) ? route('counselor.announcements.update', $announcement) : route('counselor.announcements.store') }}"
                      method="POST">
                    @csrf
                    @if(isset($announcement))
                        @method('PUT')
                    @endif

                    <div class="space-y-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title"
                                   value="{{ old('title', $announcement->title ?? '') }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                   required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                            <textarea name="content" id="content" rows="6"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                                      required>{{ old('content', $announcement->content ?? '') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date (Optional)</label>
                                <input type="date" name="start_date" id="start_date"
                                       value="{{ old('start_date', isset($announcement->start_date) ? $announcement->start_date->format('Y-m-d') : '') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                @error('start_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date (Optional)</label>
                                <input type="date" name="end_date" id="end_date"
                                       value="{{ old('end_date', isset($announcement->end_date) ? $announcement->end_date->format('Y-m-d') : '') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                                @error('end_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Active (visible to students)</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                                {{ isset($announcement) ? 'Update' : 'Create' }} Announcement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
