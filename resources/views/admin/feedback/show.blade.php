@extends('layouts.admin')

@section('title', 'Feedback Details - Admin Panel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Feedback Details</h1>
                <p class="text-gray-600 mt-2">Complete view of student feedback submission</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.feedback.index') }}"
                   class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                </a>
                <a href="{{ route('admin.feedback.export', ['search' => $feedback->id]) }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-download mr-2"></i> Export
                </a>
            </div>
        </div>

        <!-- Feedback Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-6">
                <div class="flex justify-between items-start">
                    <div class="flex items-center">
                        @if($feedback->is_anonymous)
                            <div class="bg-white bg-opacity-20 p-4 rounded-full mr-4">
                                <i class="fas fa-user-secret text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Anonymous Feedback Submission</h2>
                                <p class="text-indigo-100 mt-1">Student identity protected for privacy</p>
                            </div>
                        @else
                            <div class="bg-white bg-opacity-20 p-4 rounded-full mr-4">
                                <i class="fas fa-user text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">
                                    {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                </h2>
                                <p class="text-indigo-100 mt-1">{{ $feedback->user->email }}</p>
                                @if($feedback->user->student)
                                    <p class="text-indigo-200 text-sm mt-1">
                                        {{ $feedback->user->student->student_id }} •
                                        {{ $feedback->user->student->college->name ?? 'No College' }} •
                                        {{ $feedback->user->student->year_level ?? 'N/A' }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="text-right bg-white bg-opacity-20 rounded-lg px-4 py-3">
                        <div class="text-white text-4xl font-bold mb-1">
                            {{ $feedback->satisfaction_rating }}/5
                        </div>
                        <div class="text-indigo-100 text-lg">
                            {{ \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating) }}
                        </div>
                        <div class="text-yellow-300 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $feedback->satisfaction_rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8">
                <!-- Service & Timeline Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Service Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-cog text-blue-500 mr-2"></i>
                            Service Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Service Availed</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900 capitalize">{{ $feedback->service_availed }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Submission Type</label>
                                <p class="mt-1">
                                    @if($feedback->is_anonymous)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-user-secret mr-1"></i> Anonymous Submission
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-user mr-1"></i> Identified Submission
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Information -->
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-clock text-green-500 mr-2"></i>
                            Timeline Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Submitted On</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ $feedback->created_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                                <p class="mt-1 text-sm text-gray-700">
                                    {{ $feedback->updated_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Feedback ID</label>
                                <p class="mt-1 text-sm font-mono text-gray-600">#{{ $feedback->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Visualization -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-yellow-500 mr-2"></i>
                        Satisfaction Rating
                    </h3>
                    <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                        <div class="text-center">
                            <div class="text-yellow-400 text-5xl mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $feedback->satisfaction_rating ? '' : '-o' }} mx-1"></i>
                                @endfor
                            </div>
                            <div class="text-3xl font-bold text-gray-800 mb-2">
                                {{ $feedback->satisfaction_rating }} out of 5 Stars
                            </div>
                            <div class="text-xl text-gray-600 mb-4">
                                {{ \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating) }}
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-yellow-400 h-3 rounded-full"
                                     style="width: {{ ($feedback->satisfaction_rating / 5) * 100 }}%"></div>
                            </div>
                            <div class="text-sm text-gray-500 mt-2">
                                {{ number_format(($feedback->satisfaction_rating / 5) * 100, 1) }}% Satisfaction Rate
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Comments -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-comment-dots text-blue-500 mr-2"></i>
                        Student Comments & Feedback
                    </h3>
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        @if($feedback->comments)
                            <div class="prose max-w-none">
                                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap text-lg">{{ $feedback->comments }}</p>
                            </div>
                            <div class="mt-4 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ str_word_count($feedback->comments) }} words, {{ strlen($feedback->comments) }} characters
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-comment-slash text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 text-lg">No comments provided by the student.</p>
                                <p class="text-gray-400 text-sm mt-1">The student only submitted a rating without additional comments.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- System Information -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-database text-gray-500 mr-2"></i>
                        System Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block font-medium text-gray-600 mb-1">Database Record</label>
                            <div class="text-gray-900">ID: #{{ $feedback->id }}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block font-medium text-gray-600 mb-1">User Account</label>
                            <div class="text-gray-900">
                                @if($feedback->is_anonymous)
                                    <span class="text-gray-400">Hidden (Anonymous)</span>
                                @else
                                    User ID: {{ $feedback->user_id }}
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <label class="block font-medium text-gray-600 mb-1">Submission IP</label>
                            <div class="text-gray-900">Recorded in system logs</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between items-center">
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                This feedback is part of the system's quality assurance process.
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.feedback.index') }}"
                   class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                    <i class="fas fa-list mr-2"></i> Back to All Feedback
                </a>
                @if(!$feedback->is_anonymous && $feedback->user->student)
                <a href="{{ route('admin.students') }}?search={{ $feedback->user->student->student_id }}"
                   class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-user-graduate mr-2"></i> View Student Profile
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
