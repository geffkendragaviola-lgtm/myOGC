@extends('layouts.admin')

@section('title', 'Edit Resource - Admin Panel')

@section('content')
        <div class="container mx-auto px-6 py-8 max-w-4xl">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Edit Resource</h1>
                        <p class="text-gray-600 mt-2">Update the resource details</p>
                    </div>
                    <a href="{{ route('admin.resources.index') }}"
                       class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Resources
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <form action="{{ route('admin.resources.update', $resource) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $resource->title) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea name="description" id="description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent" required>{{ old('description', $resource->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon *</label>
                            <select name="icon" id="icon"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent" required>
                                <option value="">Select an icon</option>
                                @foreach($icons as $icon)
                                    <option value="{{ $icon }}" {{ old('icon', $resource->icon) == $icon ? 'selected' : '' }}>{{ $icon }}</option>
                                @endforeach
                            </select>
                            @error('icon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" id="category"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent" required>
                                <option value="">Select a category</option>
                                @foreach($categories as $value => $label)
                                    <option value="{{ $value }}" {{ old('category', $resource->category) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text *</label>
                            <input type="text" name="button_text" id="button_text" value="{{ old('button_text', $resource->button_text) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent" required>
                            @error('button_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                            <input type="number" name="order" id="order" value="{{ old('order', $resource->order ?? 0) }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent">
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Resource Link *</label>
                            <input type="url" name="link" id="link" value="{{ old('link', $resource->link) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent" required>
                            @error('link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Replace Image (optional)</label>
                            <input type="file" name="image" id="image" accept="image/*" class="w-full">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" name="use_yt_thumbnail" id="use_yt_thumbnail" value="1" {{ old('use_yt_thumbnail', $resource->use_yt_thumbnail) ? 'checked' : '' }}
                                       class="h-4 w-4 text-[#F00000] focus:ring-[#F00000] border-gray-300 rounded">
                                <label for="use_yt_thumbnail" class="ml-2 block text-sm text-gray-700">Use YouTube thumbnail (for YouTube links)</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="show_disclaimer" id="show_disclaimer" value="1" {{ old('show_disclaimer', $resource->show_disclaimer) ? 'checked' : '' }}
                                       class="h-4 w-4 text-[#F00000] focus:ring-[#F00000] border-gray-300 rounded">
                                <label for="show_disclaimer" class="ml-2 block text-sm text-gray-700">Show disclaimer</label>
                            </div>

                            <div>
                                <label for="disclaimer_text" class="block text-sm font-medium text-gray-700 mb-2">Disclaimer Text (optional)</label>
                                <textarea name="disclaimer_text" id="disclaimer_text" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-transparent">{{ old('disclaimer_text', $resource->disclaimer_text) }}</textarea>
                                @error('disclaimer_text')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $resource->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-[#F00000] focus:ring-[#F00000] border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-700">Make this resource active and visible to students</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('admin.resources.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">Cancel</a>
                        <button type="submit" class="bg-[#F00000] text-white px-6 py-2 rounded-lg hover:bg-[#D40000] transition flex items-center">
                            <i class="fas fa-save mr-2"></i> Update Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>
@endsection
