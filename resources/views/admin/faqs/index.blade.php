@extends('layouts.admin')

@section('title', 'FAQs - Admin Panel')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Frequently Asked Questions</h1>
                    <p class="text-gray-600 mt-2">Create and manage questions and answers shown to users</p>
                </div>
                <div>
                    <a href="{{ route('admin.faqs.create') }}"
                       class="bg-[#F00000] text-white px-4 py-2 rounded-lg hover:bg-[#D40000] transition flex items-center">
                        <i class="fas fa-plus mr-2"></i> Add FAQ
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <form method="GET" action="{{ route('admin.faqs.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   placeholder="Search question, answer, or category..."
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000] transition">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000] transition">
                            <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full md:w-auto px-4 py-2 bg-[#F00000] text-white rounded-lg hover:bg-[#D40000] transition">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if($faqs->isEmpty())
                <div class="p-8 text-center">
                    <i class="fas fa-question-circle text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No FAQs Yet</h3>
                    <p class="text-gray-500">Create your first FAQ to help users.</p>
                    <a href="{{ route('admin.faqs.create') }}"
                       class="inline-block mt-4 bg-[#F00000] text-white px-6 py-2 rounded-lg hover:bg-[#D40000] transition">
                        Create FAQ
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($faqs as $faq)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($faq->question, 140) }}</div>
                                        <div class="text-sm text-gray-500 mt-1">{{ Str::limit($faq->answer, 160) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $faq->category ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $faq->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $faq->order }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-[#F00000] hover:text-[#820000] transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition"
                                                        onclick="return confirm('Are you sure you want to delete this FAQ?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $faqs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
