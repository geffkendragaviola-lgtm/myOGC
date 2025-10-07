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
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">CLIENT SATISFACTION SURVEY - GUIDANCE AND COUNSELING</h2>

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
                As we journey towards influencing the future, the Office of the Vice Chancellor for Planning and Development (OVCPD), through the Office of Monitoring and Evaluation (OME), is conducting a semestral performance evaluation of offices frontline services of the University. This survey will definitely help the management improve the delivery of its services to the clientele.
            </p>

            <p class="text-gray-600 mb-4 leading-relaxed">
                In view of this, we would like to know and gather your thoughts on how a particular office have served your needs and meet your satisfaction in terms of the services you have availed by taking time in answering this survey. Your objective and honest answer in this survey shall be highly appreciated.
            </p>

            <p class="text-gray-600 mb-8 leading-relaxed">
                Rest assured that all your responses will be treated with utmost confidentiality. Let us journey together to a greater web.
            </p>

            <form method="POST" action="{{ route('feedback.store') }}">
                @csrf

                <div class="mb-6">
                    <label for="service_availed" class="block text-lg font-semibold text-gray-800 mb-2">Service/Transaction Availed:</label>
                    <select
                        id="service_availed"
                        name="service_availed"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    >
                        <option value="" disabled selected>Select service</option>
                        <option value="counseling">Counseling</option>
                        <option value="mental_health_corner">Mental Health Corner</option>
                        <option value="consultation">Consultation</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-lg font-semibold text-gray-800 mb-2">How satisfied are you with the service you received?</label>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <div class="rating-option">
                                <input
                                    type="radio"
                                    id="rating-{{ $rating }}"
                                    name="satisfaction_rating"
                                    value="{{ $rating }}"
                                    class="hidden"
                                    required
                                />
                                <label
                                    for="rating-{{ $rating }}"
                                    class="block p-3 text-center border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition"
                                >
                                    {{ $rating }} ({{ \App\Models\Feedback::getRatingLabel($rating) }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label for="feedback" class="block text-lg font-semibold text-gray-800 mb-2">Feedback/Comments:</label>
                    <textarea
                        id="feedback"
                        name="feedback"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        rows="5"
                        placeholder="Please share your feedback or suggestions for improvement"
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
