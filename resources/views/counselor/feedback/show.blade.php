@extends('layouts.app')

@section('title', 'Feedback Details - OGC')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Feedback Details</h1>
                <p class="text-gray-600 mt-2">Detailed view of student feedback submission</p>
            </div>
            <a href="{{ route('counselor.feedback.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>

        <!-- Feedback Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        @if($feedback->is_anonymous)
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <i class="fas fa-user-secret text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Anonymous Feedback</h2>
                                <p class="text-blue-100">Identity protected</p>
                            </div>
                        @else
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">
                                    {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                                </h2>
                                <p class="text-blue-100">{{ $feedback->user->email }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-white text-3xl font-bold mb-1">
                            {{ $feedback->satisfaction_rating }}/5
                        </div>
                        <div class="text-blue-100">
                            {{ \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <!-- Service Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Service Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Service Availed</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $feedback->service_availed }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Submission Date</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">
                                    {{ $feedback->created_at->format('F j, Y \a\t g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Display -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Satisfaction Rating</h3>
                    <div class="flex items-center justify-center bg-yellow-50 rounded-lg p-6">
                        <div class="text-center">
                            <div class="text-yellow-400 text-4xl mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $feedback->satisfaction_rating ? '' : '-o' }} mx-1"></i>
                                @endfor
                            </div>
                            <div class="text-2xl font-bold text-gray-800">
                                {{ $feedback->satisfaction_rating }} out of 5 stars
                            </div>
                            <div class="text-lg text-gray-600 mt-1">
                                {{ \App\Models\Feedback::getRatingLabel($feedback->satisfaction_rating) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Student Comments</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @if($feedback->comments)
                            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $feedback->comments }}</p>
                        @else
                            <p class="text-gray-500 italic">No comments provided by the student.</p>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Additional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-600">Feedback ID:</span>
                            <span class="ml-2 text-gray-900">#{{ $feedback->id }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Anonymous Submission:</span>
                            <span class="ml-2">
                                @if($feedback->is_anonymous)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        No
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Last Updated:</span>
                            <span class="ml-2 text-gray-900">{{ $feedback->updated_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        @if(!$feedback->is_anonymous)
                        <div>
                            <span class="font-medium text-gray-600">Student ID:</span>
                            <span class="ml-2 text-gray-900">
                                @if($feedback->user->student)
                                    {{ $feedback->user->student->student_id ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('counselor.feedback.index') }}"
               class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition flex items-center">
                <i class="fas fa-list mr-2"></i> Back to List
            </a>
            @if(!$feedback->is_anonymous && $feedback->user->student)
            <a href="{{ route('counselor.students.profile', $feedback->user->student) }}"
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-user-graduate mr-2"></i> View Student Profile
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
