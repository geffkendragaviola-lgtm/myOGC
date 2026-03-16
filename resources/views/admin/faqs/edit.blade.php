@extends('layouts.admin')

@section('title', 'Edit FAQ - Admin Panel')

@section('content')
    <div class="container mx-auto px-6 py-8 max-w-4xl">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Edit FAQ</h1>
                    <p class="text-gray-600 mt-2">Update the question and answer</p>
                </div>
                <a href="{{ route('admin.faqs.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to FAQs
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6">
            <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="question" class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
                        <textarea name="question" id="question" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('question', $faq->question) }}</textarea>
                        @error('question')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">Answer *</label>
                        <textarea name="answer" id="answer" rows="6" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('answer', $faq->answer) }}</textarea>
                        @error('answer')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category (optional)</label>
                            <input type="text" name="category" id="category" value="{{ old('category', $faq->category) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order</label>
                            <input type="number" name="order" id="order" value="{{ old('order', $faq->order ?? 0) }}" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.faqs.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-save mr-2"></i> Update FAQ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
