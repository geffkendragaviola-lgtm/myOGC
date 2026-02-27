@extends('layouts.app')

@section('title', 'Feedback Management - OGC')

@section('content')
<div class="container-fluid px-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Student Feedback</h1>
            <p class="text-gray-600 mt-2">View and manage all student feedback submissions</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('counselor.feedback.export', request()->query()) }}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                <i class="fas fa-file-export mr-2"></i> Export CSV
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-comments text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Feedback</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-star text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Average Rating</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['average_rating'] }}/5</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <i class="fas fa-user-secret text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Anonymous Feedback</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['anonymous_count'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-orange-100 p-3 rounded-full mr-4">
                    <i class="fas fa-chart-bar text-orange-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Rating Distribution</p>
                    <p class="text-lg font-bold text-gray-800">
                        @foreach($stats['rating_distribution'] as $rating => $count)
                            {{ $rating }}★:{{ $count }}
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <form method="GET" action="{{ route('counselor.feedback.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by student, service, or comments..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Rating Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <select name="rating" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} ★ - {{ \App\Models\Feedback::getRatingLabel($i) }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Service Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                <select name="service" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Services</option>
                    @foreach($serviceTypes as $service)
                        <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>
                            {{ $service }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                <select name="date_range" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Time</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>

            <!-- Anonymous Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Anonymous</label>
                <select name="anonymous" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="1" {{ request('anonymous') == '1' ? 'selected' : '' }}>Anonymous Only</option>
                    <option value="0" {{ request('anonymous') == '0' ? 'selected' : '' }}>Non-anonymous Only</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="md:col-span-5 flex justify-end space-x-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-filter mr-2"></i> Apply Filters
                </button>
                <a href="{{ route('counselor.feedback.index') }}"
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Feedback Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Student Info
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Service & Personnel
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Comments
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Submitted
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($feedbacks as $feedback)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($feedback->is_anonymous)
                                    <div class="bg-purple-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-user-secret text-purple-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Anonymous</div>
                                        <div class="text-sm text-gray-500">{{ $feedback->user->student->college->name ?? 'N/A' }}</div>
                                    </div>
                                @else
                                    <div class="bg-blue-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $feedback->user->student->college->name ?? 'N/A' }}
                                            <span class="text-gray-400">|</span>
                                            Sex at Birth: {{ $feedback->user->sex ?? 'N/A' }}
                                            <span class="text-gray-400">|</span>
                                            Age: {{ $feedback->user->age ?? 'N/A' }}
                                            <span class="text-gray-400">|</span>
                                            Region: {{ $feedback->user->region_of_residence ?? 'N/A' }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $feedback->service_availed }}</div>
                            <div class="text-sm text-gray-500">
                                Personnel: {{ $feedback->personnel_name ?? 'N/A' }}
                            </div>
                           
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">
                                @if($feedback->comments)
                                    {{ Str::limit($feedback->comments, 100) }}
                                @else
                                    <span class="text-gray-400">No comments</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $feedback->created_at->format('M j, Y') }}
                            <div class="text-xs text-gray-400">
                                {{ $feedback->created_at->format('g:i A') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('counselor.feedback.show', $feedback) }}"
                               class="text-blue-600 hover:text-blue-900 transition mr-3"
                               title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-comments text-4xl mb-4"></i>
                                <p class="text-lg">No feedback found.</p>
                                <p class="text-sm mt-2">No feedback submissions match your current filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($feedbacks->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
