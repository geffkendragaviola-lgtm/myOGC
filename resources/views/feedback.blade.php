<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .feedback-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .feedback-navbar {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .survey-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .rating-option {
            transition: all 0.3s ease;
        }

        .rating-option:hover {
            transform: translateY(-2px);
        }

        .rating-option input[type="radio"]:checked + label {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .rating-option input[type="radio"]:checked + span {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            padding: 1rem;
            min-width: 200px;
            z-index: 1000;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-50 feedback-container">
    <!-- Navbar -->
    <nav class="feedback-navbar py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center">
                <div class="text-white font-bold text-2xl mr-10">OGC</div>
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('dashboard') }}" class="text-white font-semibold hover:text-yellow-300 transition">Home</a>



                    <a href="{{ route('feedback') }}" class="text-white font-semibold text-yellow-300 transition">Feedback</a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                @auth


                    <button class="text-white p-2 rounded-full hover:bg-blue-700 transition">
                        <i class="fas fa-bell"></i>
                    </button>

                    <div class="profile-dropdown">
                        <button class="text-white p-2 rounded-full hover:bg-blue-700 transition">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="profile-dropdown-content hidden">
                            <div class="mb-3 border-b pb-2">
                                <div class="font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
                                <div class="text-xs text-blue-600 capitalize">Role: {{ Auth::user()->role }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block py-2 text-gray-700 hover:text-blue-600">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t pt-2 mt-2">
                                @csrf
                                <button type="submit" class="w-full text-left block py-2 text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-white font-semibold hover:text-yellow-300 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-blue-700 font-semibold py-2 px-4 rounded-lg hover:bg-blue-50 transition">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Survey Content -->
    <div class="container mx-auto px-6 py-8">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">OFFICE OF GUIDANCE AND COUNSELING- Client Satisfaction Measurement Survey</h2>

        <div class="survey-container bg-white rounded-xl shadow-md p-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="text-gray-600 mb-4 leading-relaxed">
                As we journey towards influencing the future, the Office of the Vice-Chancellor for Strategic Initiatives (OVCSI), through the Office of Monitoring and Evaluation (OME), conducts a semestral performance evaluation of internal and external services of the offices in the University. This survey will definitely help the management improve the delivery of its services to the clientele.
            </p>

            <p class="text-gray-600 mb-4 leading-relaxed">
                In view of this, we would like to know and gather your thoughts on how a particular office have served your needs and meet your satisfaction in terms of the services you have availed by taking time in answering this survey. Your objective and honest answer in this survey will be highly appreciated.
            </p>

            <p class="text-gray-600 mb-4 leading-relaxed">
                This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices. Your feedback on your recently concluded transaction will help this office provide a better service. Personal information shared will be kept confidential and you always have the option to not answer this form.
            </p>

            <p class="text-gray-600 mb-8 leading-relaxed">
                Let us journey together to a greater MSU-IIT!
            </p>

            <form method="POST" action="{{ route('feedback.store') }}">
                @csrf

                <div class="mb-6">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="share_mobile"
                            value="1"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="ml-2 text-gray-600">Mobile Number (optional): I allow the counselor to see my mobile number</span>
                    </label>
                </div>

                <div class="mb-6">
                    <label for="service_availed" class="block text-lg font-semibold text-gray-800 mb-2">Service/Transaction Availed:</label>
                    <select
                        id="service_availed"
                        name="service_availed"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="" disabled selected>Select service</option>
                        <option value="COUNSELING SERVICES">COUNSELING SERVICES</option>
                        <option value="TESTING-TEST ADMINISTRATION">TESTING-TEST ADMINISTRATION</option>
                        <option value="TESTING - TEST INTERPRETATION">TESTING - TEST INTERPRETATION</option>
                        <option value="REQUEST FOR ISSUANCE OF CERTIFICATION AND REQUEST FOR ISSUANCE OF RECOMMENDATION LETTER">REQUEST FOR ISSUANCE OF CERTIFICATION AND REQUEST FOR ISSUANCE OF RECOMMENDATION LETTER</option>
                        <option value="INITIAL INTERVIEW FOR FRESHMEN">INITIAL INTERVIEW FOR FRESHMEN</option>
                        <option value="REFERRAL SERVICE">REFERRAL SERVICE</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="target_counselor_id" class="block text-lg font-semibold text-gray-800 mb-2">Personnel you transacted with:</label>
                    <select
                        id="target_counselor_id"
                        name="target_counselor_id"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="" disabled selected>Select personnel</option>
                        @isset($counselors)
                            @foreach($counselors as $counselor)
                                <option value="{{ $counselor->id }}">{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}</option>
                            @endforeach
                        @endisset
                        <option value="unidentified">I can't identify</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-lg font-semibold text-gray-800 mb-2">Tick your answer to the Citizen's Charter (CC) questions.</label>
                    <p class="text-gray-600 mb-4 leading-relaxed">
                        The Citizen's Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times among others.
                    </p>

                    <div class="mb-5">
                        <label class="block font-medium text-gray-800 mb-2">CC1. Which of the following best describes your awareness of a Citizen's Charter?</label>
                        <div class="space-y-2">
                            @foreach([
                                'A' => 'I know what a CC is and I saw this office\'s CC.',
                                'B' => 'I know what a CC is but I did NOT see this office\'s CC.',
                                'C' => 'I learned of the CC only when I saw this office\'s CC.',
                                'D' => 'I do not know what a CC is and I did not see one in this office.'
                            ] as $code => $opt)
                                <label class="flex items-start">
                                    <input type="radio" name="cc1" value="{{ $code }}" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                                    <span class="ml-2 text-gray-700">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block font-medium text-gray-800 mb-2">CC2. If aware of CC (answered 1-3 in CC1), would you say that the CC of this office was ?</label>
                        <div class="space-y-2">
                            @foreach(['Easy to see','Somewhat easy to see','Difficult to see','Not visible at all','N/A'] as $opt)
                                <label class="flex items-start">
                                    <input type="radio" name="cc2" value="{{ $opt }}" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-gray-700">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="block font-medium text-gray-800 mb-2">CC3. If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?</label>
                        <div class="space-y-2">
                            @foreach(['Helped very much','Somewhat helped','Did not help','N/A'] as $opt)
                                <label class="flex items-start">
                                    <input type="radio" name="cc3" value="{{ $opt }}" class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-gray-700">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-lg font-semibold text-gray-800 mb-2">For Service Quality Dimensions 0-9, please tick the option that best corresponds to your answer.</label>
                    <p class="text-gray-600 mb-4">(1 = Very Dissatisfied, 2 = Dissatisfied, 3 = Neutral, 4 = Satisfied, 5 = Very Satisfied)</p>

                    @php
                        $sqdQuestions = [
                            'sqd0' => 'SQD0. I am satisfied with the service that I availed.',
                            'sqd1' => 'SQD1. I spent a reasonable amount of time for my transaction.',
                            'sqd2' => 'SQD2. The office followed the transaction\'s requirements and steps based on the information provided.',
                            'sqd3_1' => 'SQD3-1. The steps (including payment) I needed to do for my transaction were easy and simple.',
                            'sqd3_2' => 'SQD3-2. The receiving/ waiting/ processing/ working area, office facilities, etc. has visual appeal and comfiness.',
                            'sqd4' => 'SQD4. I easily found information about my transaction from the office or its website.',
                            'sqd5' => 'SQD5. I paid a reasonable amount of fees for my transaction.',
                            'sqd6' => 'SQD6. I feel the office was fair to everyone, or \u201cwalang palakasan\u201d, during my transaction.',
                            'sqd7_1' => 'SQD7-1. I was treated courteously by the staff, and (if asked for help) the staff was helpful.',
                            'sqd7_2' => 'SQD7-2. The staff is knowledgeable of the functions and/or operations of the office.',
                            'sqd7_3' => 'SQD7-3. The staff has the ability to complete the transaction.',
                            'sqd8' => 'SQD8. I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me.',
                            'sqd9' => 'SQD9. The staff shows professionalism, politeness, and willingness to help.',
                        ];
                    @endphp

                    @foreach($sqdQuestions as $key => $label)
                        <div class="mb-5">
                            <div class="font-medium text-gray-800 mb-2">{{ $label }}</div>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach([1,2,3,4,5] as $v)
                                    <label class="rating-option">
                                        <input type="radio" name="{{ $key }}" value="{{ $v }}" class="hidden" required>
                                        <span class="block p-2 text-center border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">{{ $v }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-6">
                    <label for="comments" class="block text-lg font-semibold text-gray-800 mb-2">To better improve our service, please state your comments/suggestions and the issues you have encountered below:</label>
                    <textarea
                        id="comments"
                        name="comments"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        rows="5"
                        placeholder="Your comments/suggestions"
                    ></textarea>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input
                            type="checkbox"
                            name="is_anonymous"
                            value="1"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                        >
                        <span class="ml-2 text-gray-600">Submit anonymously</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition text-lg font-semibold"
                >
                    Submit Survey
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileDropdownBtn = document.querySelector('.profile-dropdown button');
            const profileDropdown = document.querySelector('.profile-dropdown-content');

            if (profileDropdownBtn && profileDropdown) {
                profileDropdownBtn.addEventListener('click', function() {
                    profileDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.profile-dropdown')) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html>
