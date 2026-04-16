<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback - Office of Guidance and Counseling</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            /* Softer Maroon Palette */
            --maroon-soft: #7a2a2a;      /* Primary - warm, readable */
            --maroon-medium: #5c1a1a;    /* Secondary - for depth */
            --maroon-dark: #3a0c0c;      /* Accent - sparing use */
            --gold-primary: #d4af37;
            --gold-secondary: #c9a227;
            --bg-warm: #faf8f5;
            --border-soft: #e5e0db;
            --text-primary: #2c2420;
            --text-secondary: #6b5e57;
            --text-muted: #8b7e76;
        }

        .feedback-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-warm);
        }

        /* Fixed/Sticky Navbar matching Dashboard */
        .feedback-navbar {
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(135deg, var(--maroon-soft) 0%, var(--maroon-medium) 100%);
            box-shadow: 0 4px 20px rgba(122, 42, 42, 0.25);
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            backdrop-filter: blur(8px);
        }

        .feedback-navbar.scrolled {
            box-shadow: 0 6px 24px rgba(122, 42, 42, 0.35);
        }

        .gold-text { color: var(--gold-primary); }
        
        .survey-card {
            background: rgba(255,255,255,0.98);
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(44, 36, 32, 0.08);
            border: 1px solid var(--border-soft);
            position: relative;
            overflow: hidden;
        }
        
        /* Subtle gold accent at top of card */
        .survey-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, transparent, var(--gold-primary), var(--maroon-soft), var(--gold-primary), transparent);
        }

        /* Custom Inputs matching Dashboard */
        .custom-input, .custom-select, .custom-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-soft);
            border-radius: 0.6rem;
            background: rgba(255,255,255,0.9);
            color: var(--text-primary);
            outline: none;
            transition: all 0.2s ease;
            box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
        }

        .custom-input:focus, .custom-select:focus, .custom-textarea:focus {
            border-color: var(--maroon-soft);
            box-shadow: 0 0 0 3px rgba(122, 42, 42, 0.15);
        }

        /* Custom Radio/Checkbox */
        .custom-radio, .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid var(--border-soft);
            border-radius: 0.25rem;
            background: white;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        
        .custom-radio { border-radius: 50%; }

        .custom-radio:checked, .custom-checkbox:checked {
            background: var(--maroon-soft);
            border-color: var(--maroon-soft);
        }

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            width: 0.35rem;
            height: 0.7rem;
            border: solid white;
            border-width: 0 2px 2px 0;
        }

        .custom-radio:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 0.5rem;
            height: 0.5rem;
            background: white;
            border-radius: 50%;
        }

        /* Rating Buttons */
        .rating-btn {
            transition: all 0.2s ease;
            border: 1px solid var(--border-soft);
            background: white;
            color: var(--text-secondary);
        }
        
        .rating-btn:hover {
            border-color: var(--gold-primary);
            background: rgba(212, 175, 55, 0.05);
            transform: translateY(-2px);
        }

        input[type="radio"]:checked + .rating-btn {
            background: linear-gradient(135deg, var(--maroon-soft) 0%, var(--maroon-medium) 100%);
            color: white;
            border-color: var(--maroon-soft);
            box-shadow: 0 4px 6px rgba(122, 42, 42, 0.2);
            transform: translateY(-2px);
        }

        /* Submit Button */
        .submit-btn {
            background: linear-gradient(135deg, var(--maroon-soft) 0%, var(--maroon-medium) 100%);
            color: white;
            font-weight: 600;
            letter-spacing: 0.05em;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(122, 42, 42, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(122, 42, 42, 0.4);
            filter: brightness(1.1);
        }

        /* Profile Dropdown */
        .profile-dropdown-content {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            padding: 1rem;
            min-width: 220px;
            z-index: 1000;
            margin-top: 0.5rem;
            border: 1px solid var(--border-soft);
        }

        /* Alerts */
        .alert-success {
            background: rgba(209, 250, 229, 0.6);
            border-left: 4px solid #059669;
            color: #047857;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        .alert-error {
            background: rgba(254, 226, 226, 0.6);
            border-left: 4px solid #b91c1c;
            color: #b91c1c;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        /* Section Headers */
        .section-header {
            color: var(--maroon-medium);
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .section-header::before {
            content: "";
            display: block;
            width: 4px;
            height: 1.2rem;
            background: var(--gold-primary);
            border-radius: 2px;
        }
    </style>
</head>
<body class="bg-[var(--bg-warm)] feedback-container min-h-screen flex flex-col">
    
    <!-- Navbar -->
    <nav class="feedback-navbar py-4" id="mainNavbar">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center">
                <div class="text-white font-bold text-2xl mr-10 tracking-wide">
                    <span class="gold-text">OGC</span>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="{{ route('dashboard') }}" class="text-white font-semibold hover:text-[var(--gold-primary)] transition">Home</a>
                    <a href="{{ route('feedback') }}" class="text-white font-semibold text-[var(--gold-primary)] transition">Feedback</a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition">
                        <i class="fas fa-bell"></i>
                    </button>

                    <div class="profile-dropdown relative">
                        <button class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition focus:outline-none">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="profile-dropdown-content hidden">
                            <div class="mb-3 border-b pb-2 border-[var(--border-soft)]">
                                <div class="font-semibold text-[var(--text-primary)]">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-sm text-[var(--text-secondary)]">{{ Auth::user()->email }}</div>
                                <div class="text-xs text-[var(--maroon-soft)] capitalize font-semibold mt-1">Role: {{ Auth::user()->role }}</div>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block py-2 text-[var(--text-primary)] hover:text-[var(--maroon-soft)] transition">
                                <i class="fas fa-user-circle mr-2"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t pt-2 mt-2 border-[var(--border-soft)]">
                                @csrf
                                <button type="submit" class="w-full text-left block py-2 text-[var(--text-primary)] hover:text-[var(--maroon-soft)] transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-white font-semibold hover:text-[var(--gold-primary)] transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-white text-[var(--maroon-medium)] font-semibold py-2 px-4 rounded-lg hover:bg-[var(--gold-primary)] hover:text-white transition shadow-sm">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Survey Content -->
    <div class="container mx-auto px-4 sm:px-6 py-8 flex-grow">
        <div class="text-center mb-8 max-w-3xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-[var(--text-primary)] mb-4 drop-shadow-sm">
                Office of Guidance and Counseling<br>Client Satisfaction Measurement Survey
            </h2>
            <div class="w-24 h-1 bg-gradient-to-r from-transparent via-[var(--gold-primary)] to-transparent mx-auto"></div>
        </div>

        <div class="survey-container max-w-4xl mx-auto survey-card p-6 sm:p-8 md:p-10">

            @if($errors->any())
                <div class="alert-error mb-6">
                    <div class="font-semibold mb-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i> Please fix the following errors:
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1 ml-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4 text-[var(--text-secondary)] leading-relaxed text-sm sm:text-base mb-8 bg-[rgba(250,248,245,0.5)] p-5 rounded-lg border border-[var(--border-soft)]">
                <p>
                    As we journey towards influencing the future, the Office of the Vice-Chancellor for Strategic Initiatives (OVCSI), through the Office of Monitoring and Evaluation (OME), conducts a semestral performance evaluation of internal and external services of the offices in the University. This survey will definitely help the management improve the delivery of its services to the clientele.
                </p>
                <p>
                    In view of this, we would like to know and gather your thoughts on how a particular office have served your needs and meet your satisfaction in terms of the services you have availed by taking time in answering this survey. Your objective and honest answer in this survey will be highly appreciated.
                </p>
                <p>
                    This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices. Your feedback on your recently concluded transaction will help this office provide a better service. Personal information shared will be kept confidential and you always have the option to not answer this form.
                </p>
                <p class="font-semibold text-[var(--maroon-soft)] italic">
                    Let us journey together to a greater MSU-IIT!
                </p>
            </div>

            <form method="POST" action="{{ route('feedback.store') }}">
                @csrf

                <!-- Mobile Number Option -->
                <div class="mb-8 p-4 bg-[rgba(255,255,255,0.6)] rounded-lg border border-[var(--border-soft)]">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="share_mobile" value="1" class="custom-checkbox mt-1">
                        <span class="ml-3 text-[var(--text-secondary)] leading-snug">
                            <strong class="text-[var(--text-primary)]">Mobile Number (optional):</strong> I allow the counselor to see my mobile number for follow-up purposes.
                        </span>
                    </label>
                </div>

                <!-- Service Availed -->
                <div class="mb-6">
                    <label for="service_availed" class="section-header">Service/Transaction Availed:</label>
                    <select id="service_availed" name="service_availed" class="custom-select" required>
                        <option value="" disabled selected>Select service...</option>
                        <option value="COUNSELING SERVICES">COUNSELING SERVICES</option>
                        <option value="TESTING-TEST ADMINISTRATION">TESTING-TEST ADMINISTRATION</option>
                        <option value="TESTING - TEST INTERPRETATION">TESTING - TEST INTERPRETATION</option>
                        <option value="REQUEST FOR ISSUANCE OF CERTIFICATION AND REQUEST FOR ISSUANCE OF RECOMMENDATION LETTER">REQUEST FOR ISSUANCE OF CERTIFICATION AND REQUEST FOR ISSUANCE OF RECOMMENDATION LETTER</option>
                        <option value="INITIAL INTERVIEW FOR FRESHMEN">INITIAL INTERVIEW FOR FRESHMEN</option>
                        <option value="REFERRAL SERVICE">REFERRAL SERVICE</option>
                    </select>
                </div>

                <!-- Personnel -->
                <div class="mb-8">
                    <label for="target_counselor_id" class="section-header">Personnel you transacted with:</label>
                    <select id="target_counselor_id" name="target_counselor_id" class="custom-select" required>
                        <option value="" disabled selected>Select personnel...</option>
                        @isset($counselors)
                            @foreach($counselors as $counselor)
                                <option value="{{ $counselor->id }}">{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}</option>
                            @endforeach
                        @endisset
                        <option value="unidentified">I can't identify / No specific personnel</option>
                    </select>
                </div>

                <!-- Citizen's Charter Section -->
                <div class="mb-8 p-6 bg-[rgba(255,249,230,0.3)] rounded-xl border border-[rgba(212,175,55,0.2)]">
                    <h3 class="section-header text-lg mb-2">Citizen's Charter (CC)</h3>
                    <p class="text-[var(--text-secondary)] text-sm mb-6">
                        The Citizen's Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times among others.
                    </p>

                    <!-- CC1 -->
                    <div class="mb-6">
                        <label class="block font-semibold text-[var(--text-primary)] mb-3 text-sm sm:text-base">CC1. Which of the following best describes your awareness of a Citizen's Charter?</label>
                        <div class="space-y-3">
                            @foreach([
                                'A' => 'I know what a CC is and I saw this office\'s CC.',
                                'B' => 'I know what a CC is but I did NOT see this office\'s CC.',
                                'C' => 'I learned of the CC only when I saw this office\'s CC.',
                                'D' => 'I do not know what a CC is and I did not see one in this office.'
                            ] as $code => $opt)
                                <label class="flex items-start cursor-pointer group">
                                    <input type="radio" name="cc1" value="{{ $code }}" class="custom-radio mt-1" required>
                                    <span class="ml-3 text-[var(--text-secondary)] group-hover:text-[var(--text-primary)] transition">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- CC2 -->
                    <div class="mb-6">
                        <label class="block font-semibold text-[var(--text-primary)] mb-3 text-sm sm:text-base">CC2. If aware of CC (answered 1-3 in CC1), would you say that the CC of this was?</label>
                        <div class="space-y-3">
                            @foreach(['Easy to see','Somewhat easy to see','Difficult to see','Not visible at all','N/A'] as $opt)
                                <label class="flex items-start cursor-pointer group">
                                    <input type="radio" name="cc2" value="{{ $opt }}" class="custom-radio mt-1">
                                    <span class="ml-3 text-[var(--text-secondary)] group-hover:text-[var(--text-primary)] transition">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- CC3 -->
                    <div>
                        <label class="block font-semibold text-[var(--text-primary)] mb-3 text-sm sm:text-base">CC3. If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?</label>
                        <div class="space-y-3">
                            @foreach(['Helped very much','Somewhat helped','Did not help','N/A'] as $opt)
                                <label class="flex items-start cursor-pointer group">
                                    <input type="radio" name="cc3" value="{{ $opt }}" class="custom-radio mt-1">
                                    <span class="ml-3 text-[var(--text-secondary)] group-hover:text-[var(--text-primary)] transition">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Service Quality Dimensions -->
                <div class="mb-8">
                    <h3 class="section-header text-lg mb-2">Service Quality Dimensions (SQD)</h3>
                    <p class="text-[var(--text-secondary)] text-sm mb-6 bg-[rgba(250,248,245,0.6)] p-3 rounded border border-[var(--border-soft)]">
                        Please tick the option that best corresponds to your answer.<br>
                        <span class="font-semibold">(1 = Very Dissatisfied, 2 = Dissatisfied, 3 = Neutral, 4 = Satisfied, 5 = Very Satisfied)</span>
                    </p>

                    @php
                        $sqdQuestions = [
                            'sqd0' => 'SQD0. I am satisfied with the service that I availed.',
                            'sqd1' => 'SQD1. I spent a reasonable amount of time for my transaction.',
                            'sqd2' => 'SQD2. The office followed the transaction\'s requirements and steps based on the information provided.',
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

                    @foreach($sqdQuestions as $key => $label)
                        <div class="mb-6 border-b border-[var(--border-soft)] pb-6 last:border-0 last:pb-0">
                            <div class="font-medium text-[var(--text-primary)] mb-3 text-sm sm:text-base">{{ $label }}</div>
                            <div class="grid grid-cols-5 gap-2 sm:gap-3">
                                @foreach([1,2,3,4,5] as $v)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="{{ $key }}" value="{{ $v }}" class="hidden peer" required>
                                        <span class="rating-btn block p-2 sm:p-3 text-center rounded-lg font-semibold text-sm sm:text-base peer-checked:text-white">
                                            {{ $v }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Comments -->
                <div class="mb-8">
                    <label for="comments" class="section-header">Comments/Suggestions</label>
                    <p class="text-[var(--text-secondary)] text-sm mb-3">To better improve our service, please state your comments/suggestions and the issues you have encountered below:</p>
                    <textarea id="comments" name="comments" class="custom-textarea" rows="5" placeholder="Your comments/suggestions..."></textarea>
                </div>

                <!-- Anonymous Option -->
                <div class="mb-8 p-4 bg-[rgba(255,255,255,0.6)] rounded-lg border border-[var(--border-soft)]">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_anonymous" value="1" class="custom-checkbox">
                        <span class="ml-3 text-[var(--text-secondary)] font-medium">Submit anonymously</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn w-full py-4 px-6 rounded-lg text-lg shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Survey
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-[var(--maroon-medium)] to-[var(--maroon-dark)] text-white py-8 mt-auto">
        <div class="container mx-auto px-6 text-center">
            <p class="text-gray-300">&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
            <p class="text-xs text-gray-400 mt-2">Committed to your mental health and well-being</p>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('mainNavbar');
        if (navbar) {
            window.addEventListener('scroll', function() {
                if (window.scrollY > 10) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });
        }

        // Profile dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const profileDropdownBtn = document.querySelector('.profile-dropdown button');
            const profileDropdown = document.querySelector('.profile-dropdown-content');

            if (profileDropdownBtn && profileDropdown) {
                profileDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.profile-dropdown')) {
                        profileDropdown.classList.add('hidden');
                    }
                });
                
                // Prevent closing when clicking inside
                profileDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });
    </script>
</body>
</html>