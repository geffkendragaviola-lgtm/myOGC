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
                        <div class="text-white text-sm font-semibold">Submitted</div>
                        <div class="text-blue-100">{{ $feedback->created_at->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-6">
                <!-- Allowed Student Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Student Information (Limited View)</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600">College</label>
                                <p class="mt-1 text-gray-900 font-semibold">{{ $feedback->user->student->college->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Sex at Birth</label>
                                <p class="mt-1 text-gray-900 font-semibold">{{ $feedback->user->sex ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Age (in years)</label>
                                <p class="mt-1 text-gray-900 font-semibold">{{ $feedback->user->age ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Region of Residence</label>
                                <p class="mt-1 text-gray-900 font-semibold">{{ $feedback->user->region_of_residence ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email Address</label>
                                <p class="mt-1 text-gray-900 font-semibold">{{ $feedback->user->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Mobile Number (optional)</label>
                                <p class="mt-1 text-gray-900 font-semibold">
                                    @if($feedback->share_mobile)
                                        {{ $feedback->user->phone_number ?? 'N/A' }}
                                    @else
                                        Not shared
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

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
                                <label class="block text-sm font-medium text-gray-600">Personnel you transacted with</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $feedback->personnel_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Citizen's Charter -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Citizen's Charter (CC)</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div>
                            <div class="text-sm font-medium text-gray-600">CC1</div>
                            @php
                                $cc1Map = [
                                    'A' => 'I know what a CC is and I saw this office\'s CC.',
                                    'B' => 'I know what a CC is but I did NOT see this office\'s CC.',
                                    'C' => 'I learned of the CC only when I saw this office\'s CC.',
                                    'D' => 'I do not know what a CC is and I did not see one in this office.',
                                ];

                                $cc1Value = $feedback->cc1;
                                $cc1Display = $cc1Map[$cc1Value] ?? ($cc1Value ?? 'N/A');
                            @endphp
                            <div class="text-gray-900">{{ $cc1Display }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-600">CC2</div>
                            <div class="text-gray-900">{{ $feedback->cc2 ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-600">CC3</div>
                            <div class="text-gray-900">{{ $feedback->cc3 ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Service Quality Dimensions -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Service Quality Dimensions (SQD)</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        @php
                            $sqdQuestions = [
                                'sqd0' => 'SQD0. I am satisfied with the service that I availed.',
                                'sqd1' => 'SQD1. I spent a reasonable amount of time for my transaction.',
                                'sqd2' => "SQD2. The office followed the transaction's requirements and steps based on the information provided.",
                                'sqd3_1' => 'SQD3-1. The steps (including payment) I needed to do for my transaction were easy and simple.',
                                'sqd3_2' => 'SQD3-2. The receiving/ waiting/ processing/ working area, office facilities, etc. has visual appeal and comfiness.',
                                'sqd4' => 'SQD4. I easily found information about my transaction from the office or its website.',
                                'sqd5' => 'SQD5. I paid a reasonable amount of fees for my transaction.',
                                'sqd6' => 'SQD6. I feel the office was fair to everyone, or "walang palakasan", during my transaction.',
                                'sqd7_1' => 'SQD7-1. I was treated courteously by the staff, and (if asked for help) the staff was helpful.',
                                'sqd7_2' => 'SQD7-2. The staff is knowledgeable of the functions and/or operations of the office.',
                                'sqd7_3' => 'SQD7-3. The staff has the ability to complete the transaction.',
                                'sqd8' => 'SQD8. I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me.',
                                'sqd9' => 'SQD9. The staff shows professionalism, politeness, and willingness to help.',
                            ];
                        @endphp

                        <div class="grid grid-cols-1 gap-3">
                            @foreach($sqdQuestions as $key => $label)
                                <div class="flex justify-between items-start border-b border-gray-200 pb-2">
                                    <div class="pr-4 text-gray-800">{{ $label }}</div>
                                    <div class="font-semibold text-gray-900">{{ $feedback->{$key} ?? 'N/A' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Comments -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Comments/Suggestions</h3>
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
                        <div>
                            <span class="font-medium text-gray-600">Visible To:</span>
                            <span class="ml-2 text-gray-900">
                                @if(is_null($feedback->target_counselor_id))
                                    All Counselors
                                @else
                                    Selected Counselor
                                @endif
                            </span>
                        </div>
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
        </div>
    </div>
</div>
@endsection
