@extends('layouts.admin')

@section('title', 'Feedback Management - Admin Panel')

@section('content')
<div class="container-fluid px-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Feedback Management</h1>
            <p class="text-gray-600 mt-2">View and manage all student feedback submissions across the system</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.feedback.export', request()->query()) }}"
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

    <!-- Admin Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
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

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
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

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
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

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="bg-orange-100 p-3 rounded-full mr-4">
                    <i class="fas fa-chart-pie text-orange-600"></i>
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

    <!-- Advanced Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4 border border-gray-200">
        <form method="GET" action="{{ route('admin.feedback.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                <a href="{{ route('admin.feedback.index') }}"
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Feedback Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">All Feedback Submissions</h3>
                <span class="text-sm text-gray-600">
                    Showing {{ $feedbacks->firstItem() }} - {{ $feedbacks->lastItem() }} of {{ $feedbacks->total() }} feedback entries
                </span>
            </div>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Student Information
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Service & Rating
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Comments
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Submission Details
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
                                    <div class="bg-purple-100 p-3 rounded-full mr-3">
                                        <i class="fas fa-user-secret text-purple-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">Anonymous User</div>
                                        <div class="text-xs text-gray-500">Identity Protected</div>
                                    </div>
                                @else
                                    <div class="bg-blue-100 p-3 rounded-full mr-3">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $feedback->user->email }}</div>
                                        @if($feedback->user->student)
                                            <div class="text-xs text-gray-400">
                                                {{ $feedback->user->student->student_id }} • {{ $feedback->user->student->college->name ?? 'N/A' }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $feedback->service_availed }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <div class="text-yellow-400 mr-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $feedback->satisfaction_rating ? '' : '-o' }} text-sm"></i>
                                    @endfor
                                </div>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $feedback->satisfaction_rating }}/5
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating) }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs">
                                @if($feedback->comments)
                                    {{ Str::limit($feedback->comments, 120) }}
                                @else
                                    <span class="text-gray-400 italic">No comments provided</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="font-medium">{{ $feedback->created_at->format('M j, Y') }}</div>
                            <div class="text-xs text-gray-400">
                                {{ $feedback->created_at->format('g:i A') }}
                            </div>
                            <div class="mt-1">
                                @if($feedback->is_anonymous)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-user-secret mr-1"></i> Anonymous
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-user mr-1"></i> Identified
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.feedback.show', $feedback) }}"
                                   class="text-blue-600 hover:text-blue-900 transition"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!$feedback->is_anonymous && $feedback->user->student)
                                <a href="{{ route('admin.students') }}?search={{ $feedback->user->student->student_id }}"
                                   class="text-green-600 hover:text-green-900 transition"
                                   title="View Student Profile">
                                    <i class="fas fa-user-graduate"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-comments text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No feedback submissions found</p>
                                <p class="text-sm mt-2">No feedback matches your current search criteria.</p>
                                @if(request()->hasAny(['search', 'rating', 'service', 'date_range', 'anonymous']))
                                    <a href="{{ route('admin.feedback.index') }}"
                                       class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                                        Clear Filters
                                    </a>
                                @endif
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
