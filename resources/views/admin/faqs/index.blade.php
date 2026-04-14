@extends('layouts.admin')

@section('title', 'FAQs - Admin Panel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/40">
    <div class="container mx-auto px-4 md:px-6 py-6 md:py-8">
        
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                            <i class="fas fa-question-circle text-red-500 text-lg"></i>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Frequently Asked Questions</h1>
                    </div>
                    <p class="text-gray-500 text-sm ml-1">Create and manage questions and answers shown to users</p>
                </div>
                <div>
                    <a href="{{ route('admin.faqs.create') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium">
                        <i class="fas fa-plus mr-2 text-sm"></i> Add FAQ
                    </a>
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-8">
            <form method="GET" action="{{ route('admin.faqs.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   placeholder="Search question, answer, or category..."
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none">
                        </div>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 outline-none bg-white text-gray-700">
                            <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-filter text-sm"></i>
                            <span>Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- FAQs Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($faqs->isEmpty())
                <div class="p-12 text-center">
                    <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-question-circle text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No FAQs Yet</h3>
                    <p class="text-gray-400 text-sm">Create your first FAQ to help users.</p>
                    <a href="{{ route('admin.faqs.create') }}"
                       class="inline-flex items-center mt-5 px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium">
                        <i class="fas fa-plus mr-2 text-sm"></i> Create FAQ
                    </a>
                </div>
            @else
                <!-- Table Header Stats -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-red-50 flex items-center justify-center">
                            <i class="fas fa-question-circle text-red-500 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700">FAQ Library</h2>
                            <p class="text-xs text-gray-400">Total FAQs: {{ $faqs->total() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                            <i class="far fa-clock mr-1"></i> Live data
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Question</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($faqs as $faq)
                                <tr class="hover:bg-gray-50/40 transition-colors duration-150 group">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-800">{{ Str::limit($faq->question, 140) }}</div>
                                        <div class="text-xs text-gray-500 mt-1.5 leading-relaxed">{{ Str::limit($faq->answer, 160) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($faq->category)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                                <i class="fas fa-tag mr-1 text-xs"></i>
                                                {{ $faq->category }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                            {{ $faq->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-gray-100 text-gray-500 border border-gray-100' }}">
                                            <i class="fas {{ $faq->is_active ? 'fa-check-circle' : 'fa-circle' }} mr-1.5 text-xs"></i>
                                            {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <div class="h-6 w-6 rounded-md bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-sort-numeric-down-alt text-gray-400 text-xs"></i>
                                            </div>
                                            <span class="text-sm font-mono text-gray-600">{{ $faq->order }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.faqs.edit', $faq) }}" 
                                               class="text-gray-500 hover:text-gray-800 transition-colors duration-200" 
                                               title="Edit FAQ">
                                                <i class="fas fa-edit text-base"></i>
                                            </a>
                                            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-400 hover:text-red-700 transition-colors duration-200"
                                                        onclick="return confirm('Are you sure you want to delete this FAQ?')" 
                                                        title="Delete FAQ">
                                                    <i class="fas fa-trash-alt text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Enhanced Pagination Section -->
                @if($faqs->hasPages())
                <div class="px-6 py-5 border-t border-gray-100 bg-white">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <i class="fas fa-database text-gray-400 text-xs"></i>
                            <span>Showing 
                                <span class="font-semibold text-gray-700">{{ $faqs->firstItem() ?? 0 }}</span> 
                                to 
                                <span class="font-semibold text-gray-700">{{ $faqs->lastItem() ?? 0 }}</span> 
                                of 
                                <span class="font-semibold text-gray-700">{{ $faqs->total() }}</span> 
                                results
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            {{ $faqs->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>

                <!-- Pagination Custom Styles -->
                <style>
                    .flex.items-center.gap-2 nav {
                        display: inline-flex;
                    }
                    .flex.items-center.gap-2 .relative {
                        display: flex;
                        gap: 6px;
                        align-items: center;
                        flex-wrap: wrap;
                    }
                    .flex.items-center.gap-2 span, 
                    .flex.items-center.gap-2 a {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        min-width: 36px;
                        height: 36px;
                        padding: 0 10px;
                        border-radius: 12px;
                        font-size: 13px;
                        font-weight: 500;
                        transition: all 0.2s ease;
                    }
                    .flex.items-center.gap-2 span[aria-current="page"] span {
                        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                        color: white;
                        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.25);
                    }
                    .flex.items-center.gap-2 a {
                        background: white;
                        color: #4B5563;
                        border: 1px solid #E5E7EB;
                    }
                    .flex.items-center.gap-2 a:hover {
                        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                        color: white;
                        border-color: transparent;
                        transform: translateY(-1px);
                        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
                    }
                </style>
                @else
                <div class="px-6 py-5 border-t border-gray-100 bg-white">
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                        <span>Showing all <span class="font-semibold text-gray-700">{{ $faqs->total() }}</span> FAQs</span>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection