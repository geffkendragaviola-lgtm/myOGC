<x-guest-layout>
    @php
        $verifiedEmail = session('registration_email_verified');
        $pendingEmail = session('registration_email_pending');
    @endphp

    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!$verifiedEmail)
        <div class="mb-8 p-6 border border-gray-200 rounded-lg">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Verify MSU-IIT Email</h2>

            <form method="POST" action="{{ route('register.email.send') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @csrf
                <div class="md:col-span-2">
                    <x-input-label for="verify_email" :value="__('MSU-IIT Email')" />
                    <x-text-input id="verify_email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email', $pendingEmail)"
                        required
                        autocomplete="email"
                        pattern="^[a-zA-Z0-9._%+-]+@g\.msuiit\.edu\.ph$"
                        title="Please use your MSU-IIT email (@g.msuiit.edu.ph)" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-primary-button>
                        {{ __('Send Verification Code') }}
                    </x-primary-button>
                </div>
            </form>

            <form method="POST" action="{{ route('register.email.verify') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <x-input-label for="verification_code" :value="__('Verification Code')" />
                    <x-text-input id="verification_code"
                        class="block mt-1 w-full"
                        type="text"
                        name="code"
                        inputmode="numeric"
                        maxlength="6"
                        placeholder="Enter 6-digit code"
                        required />
                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                </div>

                <div class="flex items-end">
                    <x-primary-button>
                        {{ __('Verify Email') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    @else
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-800">Registration Steps</h2>
                <p class="text-sm text-gray-600 mt-1">Complete each section to continue.</p>
                <div id="registrationStepIndicators" class="mt-4 flex flex-wrap gap-2"></div>
                <div class="mt-4 h-2 w-full bg-gray-200 rounded-full">
                    <div id="registrationProgress" class="h-2 bg-indigo-600 rounded-full" style="width: 0%"></div>
                </div>
            </div>

            <!-- Basic Information Section -->
            <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="Basic Information">
                <h2 class="text-xl font-bold mb-4 text-gray-800">Basic Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- First Name -->
                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                </div>

                <!-- Middle Name -->
                <div>
                    <x-input-label for="middle_name" :value="__('Middle Name')" />
                    <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name')" autocomplete="additional-name" />
                    <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
                </div>

                <!-- Last Name -->
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                </div>

                <!-- Birthdate -->
                <div>
                    <x-input-label for="birthdate" :value="__('Birthdate')" />
                    <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate')" autocomplete="bday-year" />
                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                </div>

                <!-- Age (auto-calculated, read-only) -->
                <div>
                    <x-input-label for="age" :value="__('Age')" />
                    <x-text-input id="age" class="block mt-1 w-full bg-gray-100" type="number" name="age" :value="old('age')" readonly autocomplete="off" />
                    <x-input-error :messages="$errors->get('age')" class="mt-2" />
                </div>

                <!-- Sex -->
                <div>
                    <x-input-label for="sex" :value="__('Sex')" />
                    <select id="sex" name="sex" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="sex">
                        <option value="">Select Sex</option>
                        <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('sex') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('sex')" class="mt-2" />
                </div>

                <!-- Birthplace -->
                <div>
                    <x-input-label for="birthplace" :value="__('Birthplace')" />
                    <x-text-input id="birthplace" class="block mt-1 w-full" type="text" name="birthplace" :value="old('birthplace')" autocomplete="address-level2" />
                    <x-input-error :messages="$errors->get('birthplace')" class="mt-2" />
                </div>

                <!-- Religion -->
                <div>
                    <x-input-label for="religion" :value="__('Religion')" />
                    <x-text-input id="religion" class="block mt-1 w-full" type="text" name="religion" :value="old('religion')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('religion')" class="mt-2" />
                </div>

                <!-- Civil Status -->
                <div>
                    <x-input-label for="civil_status" :value="__('Civil Status')" />
                    <select id="civil_status" name="civil_status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off">
                        <option value="">Select Civil Status</option>
                        <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                        <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="not legally married" {{ old('civil_status') == 'not legally married' ? 'selected' : '' }}>Not Legally Married</option>
                        <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                        <option value="separated" {{ old('civil_status') == 'separated' ? 'selected' : '' }}>Separated</option>
                        <option value="others" {{ old('civil_status') == 'others' ? 'selected' : '' }}>Others</option>
                    </select>
                    <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
                </div>
                <div id="civil_status_other_container" class="{{ old('civil_status') == 'others' ? '' : 'hidden' }}">
                    <x-input-label for="civil_status_other" :value="__('Please specify civil status')" />
                    <x-text-input id="civil_status_other" class="block mt-1 w-full" type="text" name="civil_status_other" :value="old('civil_status_other')" placeholder="Please specify" />
                    <x-input-error :messages="$errors->get('civil_status_other')" class="mt-2" />
                </div>

                <!-- Number of Children -->
                <div>
                    <x-input-label for="number_of_children" :value="__('Number of Children')" />
                    <x-text-input id="number_of_children" class="block mt-1 w-full" type="number" name="number_of_children" :value="old('number_of_children', 0)" min="0" autocomplete="off" />
                    <x-input-error :messages="$errors->get('number_of_children')" class="mt-2" />
                </div>

                <!-- Citizenship -->
                <div>
                    <x-input-label for="citizenship" :value="__('Citizenship')" />
                    <x-text-input id="citizenship" class="block mt-1 w-full" type="text" name="citizenship" :value="old('citizenship')" autocomplete="country-name" />
                    <x-input-error :messages="$errors->get('citizenship')" class="mt-2" />
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <x-input-label for="address" :value="__('Address (in Iligan City)')" />
                    <textarea id="address" name="address" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="street-address">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <!-- Phone Number -->
                <div>
                    <x-input-label for="phone_number" :value="__('Phone Number')" />
                    <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" autocomplete="tel" />
                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                </div>

                <!-- Verified Email -->
                <div>
                    <x-input-label for="email" :value="__('MSU-IIT Email')" />
                    <x-text-input id="email"
                        class="block mt-1 w-full bg-gray-100"
                        type="email"
                        name="email_display"
                        :value="$verifiedEmail"
                        readonly />
                    <input type="hidden" name="email" value="{{ $verifiedEmail }}">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end">
                <button type="button" class="step-next inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: School Data
                </button>
            </div>
        </div>

        <!-- School Data Section -->
        <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="School Data">
            <h2 class="text-xl font-bold mb-4 text-gray-800">School Data</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Student ID -->
                <div>
                    <x-input-label for="student_id" :value="__('Student ID')" />
                    <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')" required autocomplete="off" />
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <!-- Year Level -->
                <div>
                    <x-input-label for="year_level" :value="__('Year Level')" />
                    <select id="year_level" name="year_level" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off" required>
                        <option value="">Select Year Level</option>
                        <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                        <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                        <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        <option value="5th Year" {{ old('year_level') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                    </select>
                    <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
                </div>

                <!-- Initial Interview Completion (2nd Year Only) -->
                <div id="initialInterviewCompletedWrapper" class="hidden">
                    <x-input-label for="initial_interview_completed" :value="__('Initial Interview Completion')" />
                    <select id="initial_interview_completed" name="initial_interview_completed" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off">
                        <option value="">Select an option</option>
                        <option value="yes" {{ old('initial_interview_completed') == 'yes' ? 'selected' : '' }}>Yes, I already completed it</option>
                        <option value="no" {{ old('initial_interview_completed') == 'no' ? 'selected' : '' }}>No, I have not completed it yet</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">Required for 2nd year students only.</p>
                    <x-input-error :messages="$errors->get('initial_interview_completed')" class="mt-2" />
                </div>

                <!-- Course -->
                <div>
                    <x-input-label for="course" :value="__('Course')" />
                    <x-text-input id="course" class="block mt-1 w-full" type="text" name="course" :value="old('course')" required autocomplete="off" />
                    <x-input-error :messages="$errors->get('course')" class="mt-2" />
                </div>

                <!-- College -->
                <div>
                    <x-input-label for="college_id" :value="__('College')" />
                    <select id="college_id" name="college_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off" required>
                        <option value="">Select College</option>
                        @foreach($colleges as $college)
                            <option value="{{ $college->id }}" {{ old('college_id') == $college->id ? 'selected' : '' }}>
                                {{ $college->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
                </div>

                <!-- MSU-SASE Score -->
                <div>
                    <x-input-label for="msu_sase_score" :value="__('MSU-SASE Score')" />
                    <x-text-input id="msu_sase_score" class="block mt-1 w-full" type="number" step="0.01" name="msu_sase_score" :value="old('msu_sase_score')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('msu_sase_score')" class="mt-2" />
                </div>

                <!-- Academic Year -->
                <div>
                    <x-input-label for="academic_year" :value="__('Academic Year')" />
                    <x-text-input id="academic_year" class="block mt-1 w-full" type="text" name="academic_year" :value="old('academic_year')" placeholder="e.g., 2024-2025" autocomplete="off" />
                    <x-input-error :messages="$errors->get('academic_year')" class="mt-2" />
                </div>

                <!-- Student Status -->
                <div>
                    <x-input-label for="student_status" :value="__('Student Status')" />
                    <select id="student_status" name="student_status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off" required>
                        <option value="">Select Status</option>
                        <option value="new" {{ old('student_status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="transferee" {{ old('student_status') == 'transferee' ? 'selected' : '' }}>Transferee</option>
                        <option value="returnee" {{ old('student_status') == 'returnee' ? 'selected' : '' }}>Returnee</option>
                        <option value="shiftee" {{ old('student_status') == 'shiftee' ? 'selected' : '' }}>Shiftee</option>
                    </select>
                    <x-input-error :messages="$errors->get('student_status')" class="mt-2" />
                </div>

                <!-- Profile Picture -->
                <div class="md:col-span-2">
                    <x-input-label for="profile_picture" :value="__('Profile Picture')" />
                    <input id="profile_picture" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" type="file" name="profile_picture" accept="image/*" />
                    <x-input-error :messages="$errors->get('profile_picture')" class="mt-2" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="step-back inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Back
                </button>
                <button type="button" class="step-next inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: Personal Data
                </button>
            </div>
        </div>

        <!-- Personal Data Section -->
        <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="Personal Data">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Personal Data</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nickname -->
                <div>
                    <x-input-label for="nickname" :value="__('Nickname')" />
                    <x-text-input id="nickname" class="block mt-1 w-full" type="text" name="nickname" :value="old('nickname')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('nickname')" class="mt-2" />
                </div>

                <!-- Home Address -->
                <div class="md:col-span-2">
                    <x-input-label for="home_address" :value="__('Home Address')" />
                    <textarea id="home_address" name="home_address" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('home_address') }}</textarea>
                    <x-input-error :messages="$errors->get('home_address')" class="mt-2" />
                </div>

                <!-- Stays With -->
                <div>
                    <x-input-label for="stays_with" :value="__('Stays With')" />
                    <select id="stays_with" name="stays_with" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Option</option>
                        <option value="parents/guardian" {{ old('stays_with') == 'parents/guardian' ? 'selected' : '' }}>Parents/Guardian</option>
                        <option value="board/roommates" {{ old('stays_with') == 'board/roommates' ? 'selected' : '' }}>Board/Roommates</option>
                        <option value="relatives" {{ old('stays_with') == 'relatives' ? 'selected' : '' }}>Relatives</option>
                        <option value="friends" {{ old('stays_with') == 'friends' ? 'selected' : '' }}>Friends</option>
                        <option value="employer" {{ old('stays_with') == 'employer' ? 'selected' : '' }}>Employer</option>
                        <option value="living on my own" {{ old('stays_with') == 'living on my own' ? 'selected' : '' }}>Living on my own</option>
                    </select>
                    <x-input-error :messages="$errors->get('stays_with')" class="mt-2" />
                </div>

<!-- Working Student -->
<div>
    <x-input-label for="working_student" :value="__('Working Student')" />
    <select id="working_student" name="working_student" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Select Option</option>
        <option value="yes full time" {{ old('working_student') == 'yes full time' ? 'selected' : '' }}>Yes, Full-time</option>
        <option value="yes part time" {{ old('working_student') == 'yes part time' ? 'selected' : '' }}>Yes, Part-time</option>
        <option value="no but planning to work" {{ old('working_student') == 'no but planning to work' ? 'selected' : '' }}>No, but planning to work</option>
        <option value="no and have no plan to work" {{ old('working_student') == 'no and have no plan to work' ? 'selected' : '' }}>No, and have no plan to work</option>
    </select>
    <x-input-error :messages="$errors->get('working_student')" class="mt-2" />
</div>

                <!-- Talents/Skills -->
                <div class="md:col-span-2">
                    <x-input-label for="talents_skills" :value="__('Talents/Skills (comma-separated)')" />
                    <textarea id="talents_skills" name="talents_skills" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., singing, programming, sports">{{ old('talents_skills') }}</textarea>
                    <x-input-error :messages="$errors->get('talents_skills')" class="mt-2" />
                </div>

                <!-- Leisure Activities -->
                <div class="md:col-span-2">
                    <x-input-label for="leisure_activities" :value="__('Leisure/Recreational Activities (comma-separated)')" />
                    <textarea id="leisure_activities" name="leisure_activities" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., reading, gaming, hiking">{{ old('leisure_activities') }}</textarea>
                    <x-input-error :messages="$errors->get('leisure_activities')" class="mt-2" />
                </div>

                <!-- Serious Medical Condition -->
<div>
    <x-input-label for="serious_medical_condition" :value="__('Serious Medical Condition')" />
    <x-text-input id="serious_medical_condition" class="block mt-1 w-full" type="text" name="serious_medical_condition" :value="old('serious_medical_condition')" placeholder="Specify medical condition or leave blank if none" />
    <x-input-error :messages="$errors->get('serious_medical_condition')" class="mt-2" />
</div>

<div>
    <x-input-label for="physical_disability" :value="__('Physical Disability')" />
    <x-text-input id="physical_disability" class="block mt-1 w-full" type="text" name="physical_disability" :value="old('physical_disability')" placeholder="Specify physical disability or leave blank if none" />
    <x-input-error :messages="$errors->get('physical_disability')" class="mt-2" />
</div>
                <!-- Sex Identity -->
                <div>
                    <x-input-label for="sex_identity" :value="__('Sex Identity')" />
                    <select id="sex_identity" name="sex_identity" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Sex Identity</option>
                        <option value="male/man" {{ old('sex_identity') == 'male/man' ? 'selected' : '' }}>Male/Man</option>
                        <option value="female/woman" {{ old('sex_identity') == 'female/woman' ? 'selected' : '' }}>Female/Woman</option>
                        <option value="transsex male/man" {{ old('sex_identity') == 'transsex male/man' ? 'selected' : '' }}>Transsex Male/Man</option>
                        <option value="transsex female/woman" {{ old('sex_identity') == 'transsex female/woman' ? 'selected' : '' }}>Transsex Female/Woman</option>
                        <option value="sex variant/nonconforming" {{ old('sex_identity') == 'sex variant/nonconforming' ? 'selected' : '' }}>Sex Variant/Nonconforming</option>
                        <option value="not listed" {{ old('sex_identity') == 'not listed' ? 'selected' : '' }}>Not Listed</option>
                        <option value="prefer not to say" {{ old('sex_identity') == 'prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                    <x-input-error :messages="$errors->get('sex_identity')" class="mt-2" />
                </div>

                <!-- Romantic Attraction -->
                <div>
                    <x-input-label for="romantic_attraction" :value="__('Romantic/Emotional/Sexual Attraction')" />
                    <select id="romantic_attraction" name="romantic_attraction" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Option</option>
                        <option value="my same sex" {{ old('romantic_attraction') == 'my same sex' ? 'selected' : '' }}>My same sex</option>
                        <option value="opposite sex" {{ old('romantic_attraction') == 'opposite sex' ? 'selected' : '' }}>Opposite sex</option>
                        <option value="both men and women" {{ old('romantic_attraction') == 'both men and women' ? 'selected' : '' }}>Both men and women</option>
                        <option value="all sexes" {{ old('romantic_attraction') == 'all sexes' ? 'selected' : '' }}>All sexes</option>
                        <option value="neither sex" {{ old('romantic_attraction') == 'neither sex' ? 'selected' : '' }}>Neither sex</option>
                        <option value="prefer not to answer" {{ old('romantic_attraction') == 'prefer not to answer' ? 'selected' : '' }}>Prefer not to answer</option>
                    </select>
                    <x-input-error :messages="$errors->get('romantic_attraction')" class="mt-2" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="step-back inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Back
                </button>
                <button type="button" class="step-next inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: Family Data
                </button>
            </div>
        </div>

        <!-- Family Data Section -->
        <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="Family Data">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Family Data</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Father's Name -->
                <div>
                    <x-input-label for="father_name" :value="__('Father\'s Name')" />
                    <x-text-input id="father_name" class="block mt-1 w-full" type="text" name="father_name" :value="old('father_name')" autocomplete="off" required />
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="father_deceased" value="1" {{ old('father_deceased') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">Deceased</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('father_name')" class="mt-2" />
                </div>

                <!-- Father's Occupation -->
                <div>
                    <x-input-label for="father_occupation" :value="__('Father\'s Occupation')" />
                    <x-text-input id="father_occupation" class="block mt-1 w-full" type="text" name="father_occupation" :value="old('father_occupation')" autocomplete="off" required />
                    <x-input-error :messages="$errors->get('father_occupation')" class="mt-2" />
                </div>

                <!-- Father's Phone Number -->
                <div>
                    <x-input-label for="father_phone_number" :value="__('Father\'s Phone Number')" />
                    <x-text-input id="father_phone_number" class="block mt-1 w-full" type="text" name="father_phone_number" :value="old('father_phone_number')" autocomplete="off" required />
                    <x-input-error :messages="$errors->get('father_phone_number')" class="mt-2" />
                </div>

                <!-- Mother's Name -->
                <div>
                    <x-input-label for="mother_name" :value="__('Mother\'s Name')" />
                    <x-text-input id="mother_name" class="block mt-1 w-full" type="text" name="mother_name" :value="old('mother_name')" autocomplete="off" required />
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="mother_deceased" value="1" {{ old('mother_deceased') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">Deceased</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('mother_name')" class="mt-2" />
                </div>

                <!-- Mother's Occupation -->
                <div>
                    <x-input-label for="mother_occupation" :value="__('Mother\'s Occupation')" />
                    <x-text-input id="mother_occupation" class="block mt-1 w-full" type="text" name="mother_occupation" :value="old('mother_occupation')" autocomplete="off" required />
                    <x-input-error :messages="$errors->get('mother_occupation')" class="mt-2" />
                </div>

                <!-- Mother's Phone Number -->
                <div>
                    <x-input-label for="mother_phone_number" :value="__('Mother\'s Phone Number')" />
                    <x-text-input id="mother_phone_number" class="block mt-1 w-full" type="text" name="mother_phone_number" :value="old('mother_phone_number')" autocomplete="off" required />
                    <x-input-error :messages="$errors->get('mother_phone_number')" class="mt-2" />
                </div>

                <!-- Parents' Marital Status -->
                <div>
                    <x-input-label for="parents_marital_status" :value="__('Parents\' Marital Status')" />
                    <select id="parents_marital_status" name="parents_marital_status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Select Status</option>
                        <option value="married" {{ old('parents_marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                        <option value="not legally married" {{ old('parents_marital_status') == 'not legally married' ? 'selected' : '' }}>Not Legally Married</option>
                        <option value="separated" {{ old('parents_marital_status') == 'separated' ? 'selected' : '' }}>Separated</option>
                        <option value="both parents remarried" {{ old('parents_marital_status') == 'both parents remarried' ? 'selected' : '' }}>Both Parents Remarried</option>
                        <option value="one parent remarried" {{ old('parents_marital_status') == 'one parent remarried' ? 'selected' : '' }}>One Parent Remarried</option>
                    </select>
                    <x-input-error :messages="$errors->get('parents_marital_status')" class="mt-2" />
                </div>

                <!-- Family Monthly Income -->
                <div>
                    <x-input-label for="family_monthly_income" :value="__('Family Monthly Income')" />
                    <select id="family_monthly_income" name="family_monthly_income" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Select Income Range</option>
                        <option value="below 3k" {{ old('family_monthly_income') == 'below 3k' ? 'selected' : '' }}>Below ₱3,000</option>
                        <option value="3001-5000" {{ old('family_monthly_income') == '3001-5000' ? 'selected' : '' }}>₱3,001 - ₱5,000</option>
                        <option value="5001-8000" {{ old('family_monthly_income') == '5001-8000' ? 'selected' : '' }}>₱5,001 - ₱8,000</option>
                        <option value="8001-10000" {{ old('family_monthly_income') == '8001-10000' ? 'selected' : '' }}>₱8,001 - ₱10,000</option>
                        <option value="10001-15000" {{ old('family_monthly_income') == '10001-15000' ? 'selected' : '' }}>₱10,001 - ₱15,000</option>
                        <option value="15001-20000" {{ old('family_monthly_income') == '15001-20000' ? 'selected' : '' }}>₱15,001 - ₱20,000</option>
                        <option value="20001 above" {{ old('family_monthly_income') == '20001 above' ? 'selected' : '' }}>₱20,001 and above</option>
                    </select>
                    <x-input-error :messages="$errors->get('family_monthly_income')" class="mt-2" />
                </div>

                <!-- Guardian Name -->
                <div>
                    <x-input-label for="guardian_name" :value="__('Guardian Name (if not staying with parents)')" />
                    <x-text-input id="guardian_name" class="block mt-1 w-full" type="text" name="guardian_name" :value="old('guardian_name')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('guardian_name')" class="mt-2" />
                </div>

                <!-- Guardian Occupation -->
                <div>
                    <x-input-label for="guardian_occupation" :value="__('Guardian Occupation')" />
                    <x-text-input id="guardian_occupation" class="block mt-1 w-full" type="text" name="guardian_occupation" :value="old('guardian_occupation')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('guardian_occupation')" class="mt-2" />
                </div>

                <!-- Guardian Phone Number -->
                <div>
                    <x-input-label for="guardian_phone_number" :value="__('Guardian Phone Number')" />
                    <x-text-input id="guardian_phone_number" class="block mt-1 w-full" type="text" name="guardian_phone_number" :value="old('guardian_phone_number')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('guardian_phone_number')" class="mt-2" />
                </div>

                <!-- Guardian Relationship -->
                <div>
                    <x-input-label for="guardian_relationship" :value="__('Relationship with Guardian')" />
                    <x-text-input id="guardian_relationship" class="block mt-1 w-full" type="text" name="guardian_relationship" :value="old('guardian_relationship')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('guardian_relationship')" class="mt-2" />
                </div>

                <!-- Ordinal Position -->
                <div>
                    <x-input-label for="ordinal_position" :value="__('Ordinal Position in Family')" />
                    <select id="ordinal_position" name="ordinal_position" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Select Position</option>
                        <option value="only child" {{ old('ordinal_position') == 'only child' ? 'selected' : '' }}>Only Child</option>
                        <option value="eldest" {{ old('ordinal_position') == 'eldest' ? 'selected' : '' }}>Eldest</option>
                        <option value="middle" {{ old('ordinal_position') == 'middle' ? 'selected' : '' }}>Middle</option>
                        <option value="youngest" {{ old('ordinal_position') == 'youngest' ? 'selected' : '' }}>Youngest</option>
                    </select>
                    <x-input-error :messages="$errors->get('ordinal_position')" class="mt-2" />
                </div>

                <!-- Number of Siblings -->
                <div>
                    <x-input-label for="number_of_siblings" :value="__('Number of Siblings (excluding yourself)')" />
                    <x-text-input id="number_of_siblings" class="block mt-1 w-full" type="number" name="number_of_siblings" :value="old('number_of_siblings', 0)" min="0" autocomplete="off" required />
                    <x-input-error :messages="$errors->get('number_of_siblings')" class="mt-2" />
                </div>

                <!-- Home Environment Description -->
                <div class="md:col-span-2">
                    <x-input-label for="home_environment_description" :value="__('Describe Your Home Environment')" />
                    <textarea id="home_environment_description" name="home_environment_description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('home_environment_description') }}</textarea>
                    <x-input-error :messages="$errors->get('home_environment_description')" class="mt-2" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="step-back inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Back
                </button>
                <button type="button" class="step-next inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: Academic & Career
                </button>
            </div>
        </div>

        <!-- Academic and Career Data Section -->
        <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="Academic & Career">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Academic and Career Data</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- SHS GPA -->
                <div>
                    <x-input-label for="shs_gpa" :value="__('SHS General Average/GPA')" />
                    <x-text-input id="shs_gpa" class="block mt-1 w-full" type="number" step="0.01" name="shs_gpa" :value="old('shs_gpa')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('shs_gpa')" class="mt-2" />
                </div>

                <!-- Is Scholar -->
                <div>
                    <x-input-label for="is_scholar" :value="__('Are you a scholar?')" />
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_scholar" value="1" {{ old('is_scholar') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">Yes</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('is_scholar')" class="mt-2" />
                </div>

                <!-- Scholarship Type -->
                <div>
                    <x-input-label for="scholarship_type" :value="__('If yes, scholarship type')" />
                    <x-text-input id="scholarship_type" class="block mt-1 w-full" type="text" name="scholarship_type" :value="old('scholarship_type')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('scholarship_type')" class="mt-2" />
                </div>

                <!-- School Last Attended -->
                <div>
                    <x-input-label for="school_last_attended" :value="__('School Last Attended')" />
                    <x-text-input id="school_last_attended" class="block mt-1 w-full" type="text" name="school_last_attended" :value="old('school_last_attended')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('school_last_attended')" class="mt-2" />
                </div>

                <!-- School Address -->
                <div class="md:col-span-2">
                    <x-input-label for="school_address" :value="__('School Address')" />
                    <textarea id="school_address" name="school_address" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('school_address') }}</textarea>
                    <x-input-error :messages="$errors->get('school_address')" class="mt-2" />
                </div>

                <!-- SHS Track -->
                <div>
                    <x-input-label for="shs_track" :value="__('SHS Track')" />
                    <select id="shs_track" name="shs_track" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Track</option>
                        <option value="academic" {{ old('shs_track') == 'academic' ? 'selected' : '' }}>Academic</option>
                        <option value="arts/design" {{ old('shs_track') == 'arts/design' ? 'selected' : '' }}>Arts/Design</option>
                        <option value="tech-voc" {{ old('shs_track') == 'tech-voc' ? 'selected' : '' }}>Tech-Voc</option>
                        <option value="sports" {{ old('shs_track') == 'sports' ? 'selected' : '' }}>Sports</option>
                    </select>
                    <x-input-error :messages="$errors->get('shs_track')" class="mt-2" />
                </div>

                <!-- SHS Strand -->
                <div>
                    <x-input-label for="shs_strand" :value="__('SHS Strand')" />
                    <select id="shs_strand" name="shs_strand" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Strand</option>
                        <option value="GA" {{ old('shs_strand') == 'GA' ? 'selected' : '' }}>GA</option>
                        <option value="STEM" {{ old('shs_strand') == 'STEM' ? 'selected' : '' }}>STEM</option>
                        <option value="HUMMS" {{ old('shs_strand') == 'HUMMS' ? 'selected' : '' }}>HUMMS</option>
                        <option value="ABM" {{ old('shs_strand') == 'ABM' ? 'selected' : '' }}>ABM</option>
                    </select>
                    <x-input-error :messages="$errors->get('shs_strand')" class="mt-2" />
                </div>

                <!-- Awards/Honors -->
                <div class="md:col-span-2">
                    <x-input-label for="awards_honors" :value="__('Awards/Honors Received (comma-separated)')" />
                    <textarea id="awards_honors" name="awards_honors" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Valedictorian, Best in Math, None">{{ old('awards_honors') }}</textarea>
                    <x-input-error :messages="$errors->get('awards_honors')" class="mt-2" />
                </div>

                <!-- Student Organizations -->
                <div class="md:col-span-2">
                    <x-input-label for="student_organizations" :value="__('Student Organizations (comma-separated)')" />
                    <textarea id="student_organizations" name="student_organizations" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Math Club, Debate Team">{{ old('student_organizations') }}</textarea>
                    <x-input-error :messages="$errors->get('student_organizations')" class="mt-2" />
                </div>

                <!-- Co-curricular Activities -->
                <div class="md:col-span-2">
                    <x-input-label for="co_curricular_activities" :value="__('Co-curricular Activities (comma-separated)')" />
                    <textarea id="co_curricular_activities" name="co_curricular_activities" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., Sports, Clubs, Volunteering">{{ old('co_curricular_activities') }}</textarea>
                    <x-input-error :messages="$errors->get('co_curricular_activities')" class="mt-2" />
                </div>

                <!-- Career Options -->
                <div>
                    <x-input-label for="career_option_1" :value="__('1st Career/Course Option')" />
                    <x-text-input id="career_option_1" class="block mt-1 w-full" type="text" name="career_option_1" :value="old('career_option_1')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('career_option_1')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="career_option_2" :value="__('2nd Career/Course Option')" />
                    <x-text-input id="career_option_2" class="block mt-1 w-full" type="text" name="career_option_2" :value="old('career_option_2')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('career_option_2')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="career_option_3" :value="__('3rd Career/Course Option')" />
                    <x-text-input id="career_option_3" class="block mt-1 w-full" type="text" name="career_option_3" :value="old('career_option_3')" autocomplete="off" />
                    <x-input-error :messages="$errors->get('career_option_3')" class="mt-2" />
                </div>

                <!-- Course Choice By -->
                <div>
                    <x-input-label for="course_choice_by" :value="__('Whose choice is your course?')" />
                    <select id="course_choice_by" name="course_choice_by" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Option</option>
                        <option value="own choice" {{ old('course_choice_by') == 'own choice' ? 'selected' : '' }}>Own Choice</option>
                        <option value="parents choice" {{ old('course_choice_by') == 'parents choice' ? 'selected' : '' }}>Parents' Choice</option>
                        <option value="relative choice" {{ old('course_choice_by') == 'relative choice' ? 'selected' : '' }}>Relative's Choice</option>
                        <option value="sibling choice" {{ old('course_choice_by') == 'sibling choice' ? 'selected' : '' }}>Sibling's Choice</option>
                        <option value="according to MSU-SASE score/slot" {{ old('course_choice_by') == 'according to MSU-SASE score/slot' ? 'selected' : '' }}>According to MSU-SASE Score/Slot</option>
                        <option value="others" {{ old('course_choice_by') == 'others' ? 'selected' : '' }}>Others</option>
                    </select>
                    <x-input-error :messages="$errors->get('course_choice_by')" class="mt-2" />
                </div>
                <div id="course_choice_other_container" class="{{ old('course_choice_by') == 'others' ? '' : 'hidden' }}">
                    <x-input-label for="course_choice_other" :value="__('Please specify the course choice')" />
                    <x-text-input id="course_choice_other" class="block mt-1 w-full" type="text" name="course_choice_other" :value="old('course_choice_other')" placeholder="Please specify" />
                    <x-input-error :messages="$errors->get('course_choice_other')" class="mt-2" />
                </div>

                <!-- Course Choice Reason -->
                <div class="md:col-span-2">
                    <x-input-label for="course_choice_reason" :value="__('Reason for choosing the course')" />
                    <textarea id="course_choice_reason" name="course_choice_reason" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('course_choice_reason') }}</textarea>
                    <x-input-error :messages="$errors->get('course_choice_reason')" class="mt-2" />
                </div>

                <!-- MSU Choice Reasons -->
<div class="md:col-span-2">
    <x-input-label for="msu_choice_reasons" :value="__('What makes you choose MSU-IIT for your college schooling?')" />
    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
        <label class="inline-flex items-center">
            <input type="checkbox" name="msu_choice_reasons[]" value="Quality Education" {{ in_array('Quality Education', old('msu_choice_reasons', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Quality Education</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="msu_choice_reasons[]" value="Affordable Tuition Fees" {{ in_array('Affordable Tuition Fees', old('msu_choice_reasons', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Affordable Tuition Fees</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="msu_choice_reasons[]" value="Scholarships" {{ in_array('Scholarships', old('msu_choice_reasons', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Scholarships</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="msu_choice_reasons[]" value="Proximity" {{ in_array('Proximity', old('msu_choice_reasons', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Proximity</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="msu_choice_reasons[]" value="Only school offering my course" {{ in_array('Only school offering my course', old('msu_choice_reasons', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Only school offering my course</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="msu_choice_reasons[]" value="Prestigious Institution" {{ in_array('Prestigious Institution', old('msu_choice_reasons', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Prestigious Institution</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="msu_choice_reasons[]" value="Others" {{ in_array('Others', old('msu_choice_reasons', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Others:</span>
        </label>
    </div>
    <div id="msu_choice_other_container" class="{{ in_array('Others', old('msu_choice_reasons', [])) ? '' : 'hidden' }}">
        <x-text-input id="msu_choice_other" class="block mt-1 w-full md:w-1/2" type="text" name="msu_choice_other" :value="old('msu_choice_other')" placeholder="Please specify" />
    </div>
    <x-input-error :messages="$errors->get('msu_choice_reasons')" class="mt-2" />
</div>

                <!-- Future Career Plans -->
                <div class="md:col-span-2">
                    <x-input-label for="future_career_plans" :value="__('What career do you see yourself pursuing after college education?')" />
                    <textarea id="future_career_plans" name="future_career_plans" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('future_career_plans') }}</textarea>
                    <x-input-error :messages="$errors->get('future_career_plans')" class="mt-2" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="step-back inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Back
                </button>
                <button type="button" class="step-next inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: Learning Resources
                </button>
            </div>
        </div>

        <!-- Distance Learning Resources Section -->
        <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="Learning Resources">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Distance Learning Resources</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Internet Access -->
                <div>
                    <x-input-label for="internet_access" :value="__('Internet Access and Resources')" />
                    <select id="internet_access" name="internet_access" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Option</option>
                        <option value="no internet access" {{ old('internet_access') == 'no internet access' ? 'selected' : '' }}>No Internet Access</option>
                        <option value="limited internet access" {{ old('internet_access') == 'limited internet access' ? 'selected' : '' }}>Limited Internet Access</option>
                        <option value="full internet access" {{ old('internet_access') == 'full internet access' ? 'selected' : '' }}>Full Internet Access</option>
                    </select>
                    <x-input-error :messages="$errors->get('internet_access')" class="mt-2" />
                </div>

                <!-- Technology Gadgets -->
<div>
    <x-input-label for="technology_gadgets" :value="__('Technology Gadgets')" />
    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
        <label class="inline-flex items-center">
            <input type="checkbox" name="technology_gadgets[]" value="None" {{ in_array('None', old('technology_gadgets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">None</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="technology_gadgets[]" value="Mobile phone" {{ in_array('Mobile phone', old('technology_gadgets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Mobile phone</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="technology_gadgets[]" value="Smartphone" {{ in_array('Smartphone', old('technology_gadgets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Smartphone</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="technology_gadgets[]" value="Tablet/iPad" {{ in_array('Tablet/iPad', old('technology_gadgets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Tablet/iPad</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="technology_gadgets[]" value="Laptop/Notebook" {{ in_array('Laptop/Notebook', old('technology_gadgets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Laptop/Notebook</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="technology_gadgets[]" value="PC/Desktop" {{ in_array('PC/Desktop', old('technology_gadgets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">PC/Desktop</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="technology_gadgets[]" value="Other" {{ in_array('Other', old('technology_gadgets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Other:</span>
        </label>
    </div>
    <div id="technology_gadgets_other_container" class="{{ in_array('Other', old('technology_gadgets', [])) ? '' : 'hidden' }}">
        <x-text-input id="technology_gadgets_other" class="block mt-1 w-full md:w-1/2" type="text" name="technology_gadgets_other" :value="old('technology_gadgets_other')" placeholder="Please specify" />
    </div>
    <x-input-error :messages="$errors->get('technology_gadgets')" class="mt-2" />
</div>

                <!-- Internet Connectivity -->
<div>
    <x-input-label for="internet_connectivity" :value="__('Means of Internet Connectivity')" />
    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
        <label class="inline-flex items-center">
            <input type="checkbox" name="internet_connectivity[]" value="Home Internet" {{ in_array('Home Internet', old('internet_connectivity', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Home Internet</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="internet_connectivity[]" value="Relative's Internet" {{ in_array("Relative's Internet", old('internet_connectivity', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Relative's Internet</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="internet_connectivity[]" value="Neighbor's Internet" {{ in_array("Neighbor's Internet", old('internet_connectivity', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Neighbor's Internet</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="internet_connectivity[]" value="Mobile Data" {{ in_array('Mobile Data', old('internet_connectivity', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Mobile Data</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="internet_connectivity[]" value="Piso Net" {{ in_array('Piso Net', old('internet_connectivity', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Piso Net</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="internet_connectivity[]" value="Internet Café" {{ in_array('Internet Café', old('internet_connectivity', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Internet Café</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="internet_connectivity[]" value="No Internet" {{ in_array('No Internet', old('internet_connectivity', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">No Internet</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="internet_connectivity[]" value="Others" {{ in_array('Others', old('internet_connectivity', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Others:</span>
        </label>
    </div>
    <div id="internet_connectivity_other_container" class="{{ in_array('Others', old('internet_connectivity', [])) ? '' : 'hidden' }}">
        <x-text-input id="internet_connectivity_other" class="block mt-1 w-full md:w-1/2" type="text" name="internet_connectivity_other" :value="old('internet_connectivity_other')" placeholder="Please specify" />
    </div>
    <x-input-error :messages="$errors->get('internet_connectivity')" class="mt-2" />
</div>

                <!-- Distance Learning Readiness -->
                <div>
                    <x-input-label for="distance_learning_readiness" :value="__('Distance Learning Readiness')" />
                    <select id="distance_learning_readiness" name="distance_learning_readiness" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Readiness Level</option>
                        <option value="fully ready" {{ old('distance_learning_readiness') == 'fully ready' ? 'selected' : '' }}>Fully Ready</option>
                        <option value="ready" {{ old('distance_learning_readiness') == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="a little ready" {{ old('distance_learning_readiness') == 'a little ready' ? 'selected' : '' }}>A Little Ready</option>
                        <option value="not ready" {{ old('distance_learning_readiness') == 'not ready' ? 'selected' : '' }}>Not Ready</option>
                    </select>
                    <x-input-error :messages="$errors->get('distance_learning_readiness')" class="mt-2" />
                </div>

                <!-- Learning Space -->
                <div class="md:col-span-2">
                    <x-input-label for="learning_space_description" :value="__('Describe Your Learning Space')" />
                    <textarea id="learning_space_description" name="learning_space_description" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describe your study area and environment">{{ old('learning_space_description') }}</textarea>
                    <x-input-error :messages="$errors->get('learning_space_description')" class="mt-2" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="step-back inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Back
                </button>
                <button type="button" class="step-next inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: Psychosocial
                </button>
            </div>
        </div>

        <!-- Psychosocial Well-being Section -->
        <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="Psychosocial Well-being">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Psychosocial Well-being</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Personality Characteristics -->
                <div class="md:col-span-2">
                    <x-input-label for="personality_characteristics" :value="__('What are some characteristics of your personality? (comma-separated)')" />
                    <textarea id="personality_characteristics" name="personality_characteristics" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., organized, outgoing, analytical, creative">{{ old('personality_characteristics') }}</textarea>
                    <x-input-error :messages="$errors->get('personality_characteristics')" class="mt-2" />
                </div>

                <!-- Coping Mechanisms -->
                <div class="md:col-span-2">
                    <x-input-label for="coping_mechanisms" :value="__('How do you usually deal with a bad day? (comma-separated)')" />
                    <textarea id="coping_mechanisms" name="coping_mechanisms" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., talk to friends, listen to music, exercise, meditate">{{ old('coping_mechanisms') }}</textarea>
                    <x-input-error :messages="$errors->get('coping_mechanisms')" class="mt-2" />
                </div>

                <!-- Mental Health Perception -->
<div class="md:col-span-2">
    <x-input-label for="mental_health_perception" :value="__('How do you perceive your mental health at present?')" />
    <x-text-input id="mental_health_perception" class="block mt-1 w-full" type="text" name="mental_health_perception" :value="old('mental_health_perception')" placeholder="Describe your mental health perception" />
    <x-input-error :messages="$errors->get('mental_health_perception')" class="mt-2" />
</div>

                <!-- Counseling Experience -->
<div>
    <x-input-label for="had_counseling_before" :value="__('Have you experienced counseling before?')" />
    <div class="mt-2">
        <label class="inline-flex items-center mr-4">
            <input type="radio" name="had_counseling_before" value="1" {{ old('had_counseling_before') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Yes</span>
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="had_counseling_before" value="0" {{ old('had_counseling_before') == '0' || !old('had_counseling_before') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">No</span>
        </label>
    </div>
    <x-input-error :messages="$errors->get('had_counseling_before')" class="mt-2" />
</div>

                <!-- Psychologist Help -->
<div>
    <x-input-label for="sought_psychologist_help" :value="__('Seeking help from a psychologist/psychiatrist?')" />
    <div class="mt-2">
        <label class="inline-flex items-center mr-4">
            <input type="radio" name="sought_psychologist_help" value="1" {{ old('sought_psychologist_help') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Yes</span>
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="sought_psychologist_help" value="0" {{ old('sought_psychologist_help') == '0' || !old('sought_psychologist_help') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">No</span>
        </label>
    </div>
    <x-input-error :messages="$errors->get('sought_psychologist_help')" class="mt-2" />
</div>
                <!-- Problem Sharing Targets -->
<div class="md:col-span-2">
    <x-input-label for="problem_sharing_targets" :value="__('To whom do you share your problems with?')" />
    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
        <label class="inline-flex items-center">
            <input type="checkbox" name="problem_sharing_targets[]" value="Mother" {{ in_array('Mother', old('problem_sharing_targets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Mother</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="problem_sharing_targets[]" value="Father" {{ in_array('Father', old('problem_sharing_targets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Father</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="problem_sharing_targets[]" value="Brother/Sister" {{ in_array('Brother/Sister', old('problem_sharing_targets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Brother/Sister</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="problem_sharing_targets[]" value="Friends" {{ in_array('Friends', old('problem_sharing_targets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Friends</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="problem_sharing_targets[]" value="Counselor" {{ in_array('Counselor', old('problem_sharing_targets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Counselor</span>
        </label>
        <label class="inline-flex items-center">
            <input type="checkbox" name="problem_sharing_targets[]" value="Others" {{ in_array('Others', old('problem_sharing_targets', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Others:</span>
        </label>
    </div>
    <div id="problem_sharing_other_container" class="{{ in_array('Others', old('problem_sharing_targets', [])) ? '' : 'hidden' }}">
        <x-text-input id="problem_sharing_other" class="block mt-1 w-full md:w-1/2" type="text" name="problem_sharing_other" :value="old('problem_sharing_other')" placeholder="Please specify" />
    </div>
    <x-input-error :messages="$errors->get('problem_sharing_targets')" class="mt-2" />
</div>
                <!-- Immediate Counseling Need -->
<div>
    <x-input-label for="needs_immediate_counseling" :value="__('Need immediate counseling?')" />
    <div class="mt-2">
        <label class="inline-flex items-center mr-4">
            <input type="radio" name="needs_immediate_counseling" value="1" {{ old('needs_immediate_counseling') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">Yes</span>
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="needs_immediate_counseling" value="0" {{ old('needs_immediate_counseling') == '0' || !old('needs_immediate_counseling') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">No</span>
        </label>
    </div>
    <x-input-error :messages="$errors->get('needs_immediate_counseling')" class="mt-2" />
</div>

                <!-- Future Counseling Concerns -->
                <div class="md:col-span-2">
                    <x-input-label for="future_counseling_concerns" :value="__('What concerns would you like to discuss with a counselor?')" />
                    <textarea id="future_counseling_concerns" name="future_counseling_concerns" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('future_counseling_concerns') }}</textarea>
                    <x-input-error :messages="$errors->get('future_counseling_concerns')" class="mt-2" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="step-back inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Back
                </button>
                <button type="button" class="step-next inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: Needs Assessment
                </button>
            </div>
        </div>

        <!-- Needs Assessment Section -->
        <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="Needs Assessment">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Needs Assessment</h2>

            <!-- Improvement Needs -->
            <div class="mb-6">
                <x-input-label value="I have the need to improve the following:" />
                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    @php
                        $improvementNeeds = [
                            'Study habits',
                            'Note-taking skills',
                            'Time-management skills',
                            'Career decision/choices',
                            'Math skills',
                            'Reading comprehension',
                            'Memory skills',
                            'Test-taking skills',
                            'Grade point average',
                            'Reading speed',
                            'Others'
                        ];
                    @endphp
                    @foreach($improvementNeeds as $need)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="improvement_needs[]" value="{{ $need }}" {{ in_array($need, old('improvement_needs', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">{{ $need }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="improvement_needs_other_container" class="{{ in_array('Others', old('improvement_needs', [])) ? '' : 'hidden' }}">
                    <x-text-input id="improvement_needs_other" class="block mt-1 w-full md:w-1/2" type="text" name="improvement_needs_other" :value="old('improvement_needs_other')" placeholder="Please specify" />
                </div>
                <x-input-error :messages="$errors->get('improvement_needs')" class="mt-2" />
            </div>

            <!-- Financial Assistance Needs -->
            <div class="mb-6">
                <x-input-label value="I need assistance in terms of:" />
                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    @php
                        $financialNeeds = [
                            'Personal budget',
                            'Grants/scholarships',
                            'Loans',
                            'Others'
                        ];
                    @endphp
                    @foreach($financialNeeds as $need)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="financial_assistance_needs[]" value="{{ $need }}" {{ in_array($need, old('financial_assistance_needs', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">{{ $need }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="financial_assistance_needs_other_container" class="{{ in_array('Others', old('financial_assistance_needs', [])) ? '' : 'hidden' }}">
                    <x-text-input id="financial_assistance_needs_other" class="block mt-1 w-full md:w-1/2" type="text" name="financial_assistance_needs_other" :value="old('financial_assistance_needs_other')" placeholder="Please specify" />
                </div>
                <x-input-error :messages="$errors->get('financial_assistance_needs')" class="mt-2" />
            </div>

            <!-- Personal-Social Needs -->
            <div class="mb-6">
                <x-input-label value="I need assistance in terms of Personal-Social:" />
                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    @php
                        $personalSocialNeeds = [
                            'Stress management',
                            'Substance abuse',
                            'Dealing with relationships (Boy/Girl)',
                            'Anxiety',
                            'Handling conflicts/anger',
                            'Coping with peer pressure',
                            'Student-teacher conflict',
                            'Coping with physical disability',
                            'Student-teacher/school personnel relationship',
                            'Depression/Sadness',
                            'Motivation',
                            'Self-image (how you feel about yourself)',
                            'Grief/loss due to parental separation',
                            'Grief/loss due to death',
                            'Physical/psychological abuse',
                            'Bullying',
                            'Cyber-bullying',
                            'Others'
                        ];
                    @endphp
                    @foreach($personalSocialNeeds as $need)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="personal_social_needs[]" value="{{ $need }}" {{ in_array($need, old('personal_social_needs', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">{{ $need }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="personal_social_needs_other_container" class="{{ in_array('Others', old('personal_social_needs', [])) ? '' : 'hidden' }}">
                    <x-text-input id="personal_social_needs_other" class="block mt-1 w-full md:w-1/2" type="text" name="personal_social_needs_other" :value="old('personal_social_needs_other')" placeholder="Please specify" />
                </div>
                <x-input-error :messages="$errors->get('personal_social_needs')" class="mt-2" />
            </div>

            <!-- Stress Responses -->
            <div class="mb-6">
                <x-input-label value="When you experienced feeling upset or pushed to the limit, how did you respond?" />
                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                    @php
                        $stressResponses = [
                            'Tried to be funny and make light of it all',
                            'Talked to a teacher or counselor in school',
                            'Ate food',
                            'Tried to stay away from home',
                            'Drank beer, wine, liquor',
                            'Used drugs not prescribed by doctor',
                            'Listened to music',
                            'Watched movies or TV shows',
                            'Smoked',
                            'Tried to solve my problem',
                            'Read books, novels, etc.',
                            'Worked hard on school work/projects',
                            'Attempted to end my life',
                            'Got more involved in school activities',
                            'Tried to make my own decision',
                            'Talked things out with parents',
                            'Cried',
                            'Tried to improve myself',
                            'Strolled around on a car/jeepney ride',
                            'Tried to think of the good things in life',
                            'Prayed',
                            'Thought it would be better dead',
                            'Talked to a minister/priest/pastor',
                            'Told myself the problem is not important',
                            'Blamed others for what went wrong',
                            'Played video games',
                            'Surfed the internet',
                            'Hurt myself',
                            'Talked to a friend',
                            'Daydreamed about how I would like things to be',
                            'Got professional counseling',
                            'Went to church',
                            'Slept',
                            'Got angry',
                            'Kept my silence',
                            'Others'
                        ];
                    @endphp
                    @foreach($stressResponses as $response)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="stress_responses[]" value="{{ $response }}" {{ in_array($response, old('stress_responses', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">{{ $response }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="stress_responses_other_container" class="{{ in_array('Others', old('stress_responses', [])) ? '' : 'hidden' }}">
                    <x-text-input id="stress_responses_other" class="block mt-1 w-full md:w-1/2" type="text" name="stress_responses_other" :value="old('stress_responses_other')" placeholder="Please specify" />
                </div>
                <x-input-error :messages="$errors->get('stress_responses')" class="mt-2" />
            </div>

            <!-- Easy Discussion Target -->
            <div class="mb-6">
                <x-input-label value="I can easily discuss my problems with my:" />
                <div class="mt-2">
                    @php
                        $discussionTargets = [
                            'guidance counselor' => 'Guidance counselor in school',
                            'parents' => 'Parents',
                            'teachers' => 'Teacher(s)',
                            'brothers/sisters' => 'Brothers/Sisters',
                            'friends/relatives' => 'Friends/Relatives',
                            'nobody' => 'Nobody',
                            'others' => 'Others'
                        ];
                    @endphp
                    @foreach($discussionTargets as $value => $label)
                        <label class="inline-flex items-center mr-4 mb-2">
                            <input type="radio" name="easy_discussion_target" value="{{ $value }}" {{ old('easy_discussion_target') == $value ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ms-2 text-sm text-gray-600">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                <div id="easy_discussion_other_container" class="{{ old('easy_discussion_target') == 'others' ? '' : 'hidden' }}">
                    <x-text-input id="easy_discussion_other" class="block mt-1 w-full md:w-1/2" type="text" name="easy_discussion_other" :value="old('easy_discussion_other')" placeholder="Please specify" />
                </div>
                <x-input-error :messages="$errors->get('easy_discussion_target')" class="mt-2" />
            </div>

            <!-- Counseling Perceptions -->
            <div class="mb-6">
                <x-input-label value="How often did you experience or perceive the following?" />
                <div class="mt-4 overflow-x-auto rounded-lg border border-gray-200 bg-white">
                    <table class="min-w-[860px] w-full table-fixed divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-1/2 px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statement</th>
                                <th class="w-1/12 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Always</th>
                                <th class="w-1/12 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Oftentimes</th>
                                <th class="w-1/12 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Sometimes</th>
                                <th class="w-1/12 px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider whitespace-nowrap">Never</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php
                                $counselingStatements = [
                                    'I willfully came for counseling when I had a problem.',
                                    'I experienced counseling upon referral by teachers, friends, parents, etc.',
                                    'I know that help is available at the Guidance and Counseling Center of MSU-IIT.',
                                    'I am afraid to go to the Guidance and Counseling Center of MSU-IIT.',
                                    'I am shy to ask assistance/seek counseling from my guidance counselor.'
                                ];
                                $frequencyOptions = ['always', 'oftentimes', 'sometimes', 'never'];
                            @endphp
                            @foreach($counselingStatements as $index => $statement)
                                <tr class="odd:bg-white even:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $statement }}</td>
                                    @foreach($frequencyOptions as $frequency)
                                        <td class="px-4 py-3 text-center">
                                            <input type="radio" name="counseling_perceptions[{{ $index }}]" value="{{ $frequency }}" {{ old("counseling_perceptions.{$index}") == $frequency ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <x-input-error :messages="$errors->get('counseling_perceptions')" class="mt-2" />
            </div>
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="step-back inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Back
                </button>
                <button type="button" class="step-next inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Next: Account Security
                </button>
            </div>
        </div>

        <!-- Account Security Section -->
        <div class="registration-step mb-8 p-6 border border-gray-200 rounded-lg" data-title="Account Security">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Account Security</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>
            <!-- Hidden role field (always student) -->
            <input type="hidden" name="role" value="student">
            <div class="mt-6 flex items-center justify-between">
                <button type="button" class="step-back inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Back
                </button>
                <div class="flex items-center gap-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                    <x-primary-button>
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </div>
        </div>
        </form>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const birthdateInput = document.getElementById('birthdate');
            const ageInput = document.getElementById('age');
            const yearLevelSelect = document.getElementById('year_level');
            const initialInterviewWrapper = document.getElementById('initialInterviewCompletedWrapper');
            const initialInterviewSelect = document.getElementById('initial_interview_completed');

            // Calculate age from birthdate
            function calculateAge() {
                if (birthdateInput.value) {
                    const birthDate = new Date(birthdateInput.value);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }

                    ageInput.value = age;
                } else {
                    ageInput.value = '';
                }
            }

            birthdateInput.addEventListener('change', calculateAge);

            function toggleInitialInterviewField() {
                if (!yearLevelSelect || !initialInterviewWrapper || !initialInterviewSelect) {
                    return;
                }
                const isSecondYear = yearLevelSelect.value === '2nd Year';
                initialInterviewWrapper.classList.toggle('hidden', !isSecondYear);
                initialInterviewSelect.required = isSecondYear;
                if (!isSecondYear) {
                    initialInterviewSelect.value = '';
                }
            }

            if (yearLevelSelect) {
                yearLevelSelect.addEventListener('change', toggleInitialInterviewField);
                toggleInitialInterviewField();
            }

            // Handle conditional fields
            const isScholarCheckbox = document.querySelector('input[name="is_scholar"]');
            const scholarshipTypeInput = document.getElementById('scholarship_type');

            function toggleScholarField() {
                if (isScholarCheckbox.checked) {
                    scholarshipTypeInput.closest('div').style.display = 'block';
                } else {
                    scholarshipTypeInput.closest('div').style.display = 'none';
                    scholarshipTypeInput.value = '';
                }
            }

            if (isScholarCheckbox) {
                isScholarCheckbox.addEventListener('change', toggleScholarField);
                toggleScholarField(); // Initial state
            }

            const stepPanels = Array.from(document.querySelectorAll('.registration-step'));
            if (stepPanels.length) {
                const indicatorContainer = document.getElementById('registrationStepIndicators');
                const progressBar = document.getElementById('registrationProgress');
                const totalSteps = stepPanels.length;
                let currentStep = 0;

                const updateIndicators = () => {
                    if (!indicatorContainer) {
                        return;
                    }
                    indicatorContainer.innerHTML = '';
                    stepPanels.forEach((panel, index) => {
                        const label = panel.dataset.title || `Step ${index + 1}`;
                        const pill = document.createElement('span');
                        const isActive = index === currentStep;
                        const isComplete = index < currentStep;
                        pill.className = [
                            'px-3',
                            'py-1',
                            'rounded-full',
                            'text-xs',
                            'font-semibold',
                            'border',
                            isActive
                                ? 'bg-indigo-600 text-white border-indigo-600'
                                : isComplete
                                    ? 'bg-indigo-50 text-indigo-700 border-indigo-200'
                                    : 'bg-white text-gray-600 border-gray-200'
                        ].join(' ');
                        pill.textContent = `${index + 1}. ${label}`;
                        indicatorContainer.appendChild(pill);
                    });
                };

                const updateProgress = () => {
                    if (progressBar) {
                        const percent = ((currentStep + 1) / totalSteps) * 100;
                        progressBar.style.width = `${percent}%`;
                    }
                };

                const updateButtons = () => {
                    stepPanels.forEach((panel, index) => {
                        const backButton = panel.querySelector('.step-back');
                        const nextButton = panel.querySelector('.step-next');
                        if (backButton) {
                            backButton.classList.toggle('invisible', index === 0);
                        }
                        if (nextButton) {
                            nextButton.classList.toggle('hidden', index === totalSteps - 1);
                        }
                    });
                };

                const validateStep = (panel) => {
                    const requiredFields = Array.from(panel.querySelectorAll('[required]'));
                    for (const field of requiredFields) {
                        if (!field.checkValidity()) {
                            field.reportValidity();
                            return false;
                        }
                    }
                    return true;
                };

                const showStep = (index) => {
                    currentStep = index;
                    stepPanels.forEach((panel, panelIndex) => {
                        panel.classList.toggle('hidden', panelIndex !== currentStep);
                    });
                    updateIndicators();
                    updateProgress();
                    updateButtons();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                };

                stepPanels.forEach((panel, index) => {
                    const nextButton = panel.querySelector('.step-next');
                    const backButton = panel.querySelector('.step-back');

                    if (nextButton) {
                        nextButton.addEventListener('click', () => {
                            if (!validateStep(panel)) {
                                return;
                            }
                            showStep(Math.min(index + 1, totalSteps - 1));
                        });
                    }

                    if (backButton) {
                        backButton.addEventListener('click', () => {
                            showStep(Math.max(index - 1, 0));
                        });
                    }
                });

                showStep(0);
            }
        });



           // Handle medical condition fields (legacy radio support)
    const medicalConditionRadios = document.querySelectorAll('input[name="serious_medical_condition"]');
    const medicalSpecifyContainer = document.getElementById('serious_medical_specify_container');
    if (medicalConditionRadios.length && medicalSpecifyContainer) {
        function toggleMedicalConditionFields() {
            const selectedValue = document.querySelector('input[name="serious_medical_condition"]:checked')?.value;

            if (selectedValue === 'specify') {
                medicalSpecifyContainer.classList.remove('hidden');
            } else {
                medicalSpecifyContainer.classList.add('hidden');
            }
        }

        medicalConditionRadios.forEach(radio => {
            radio.addEventListener('change', toggleMedicalConditionFields);
        });
        toggleMedicalConditionFields(); // Initial state
    }

    // Handle physical disability fields (legacy radio support)
    const disabilityRadios = document.querySelectorAll('input[name="physical_disability"]');
    const disabilitySpecifyContainer = document.getElementById('physical_disability_specify_container');
    if (disabilityRadios.length && disabilitySpecifyContainer) {
        function toggleDisabilityFields() {
            const selectedValue = document.querySelector('input[name="physical_disability"]:checked')?.value;

            if (selectedValue === 'specify') {
                disabilitySpecifyContainer.classList.remove('hidden');
            } else {
                disabilitySpecifyContainer.classList.add('hidden');
            }
        }

        disabilityRadios.forEach(radio => {
            radio.addEventListener('change', toggleDisabilityFields);
        });
        toggleDisabilityFields(); // Initial state
    }

    // Handle "others" checkboxes
    function setupOthersCheckbox(checkboxName, containerId, otherValue = 'Others') {
        const checkboxes = document.querySelectorAll(`input[name="${checkboxName}[]"]`);
        const otherContainer = document.getElementById(containerId);
        if (!otherContainer) {
            return;
        }
        const otherInput = otherContainer ? otherContainer.querySelector('input, textarea') : null;

        function toggleOtherField() {
            const othersChecked = Array.from(checkboxes).some(cb =>
                cb.value === otherValue && cb.checked
            );

            if (othersChecked) {
                otherContainer.classList.remove('hidden');
                if (otherInput) {
                    otherInput.required = true;
                    otherInput.focus();
                }
            } else {
                otherContainer.classList.add('hidden');
                if (otherInput) {
                    otherInput.required = false;
                    otherInput.value = '';
                }
            }
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleOtherField);
        });
        toggleOtherField(); // Initial state
    }

    // Set up all "others" checkboxes
    setupOthersCheckbox('msu_choice_reasons', 'msu_choice_other_container');
    setupOthersCheckbox('technology_gadgets', 'technology_gadgets_other_container', 'Other');
    setupOthersCheckbox('internet_connectivity', 'internet_connectivity_other_container');
    setupOthersCheckbox('problem_sharing_targets', 'problem_sharing_other_container');

    // Handle needs assessment "others" fields
    setupOthersCheckbox('improvement_needs', 'improvement_needs_other_container');
    setupOthersCheckbox('financial_assistance_needs', 'financial_assistance_needs_other_container');
    setupOthersCheckbox('personal_social_needs', 'personal_social_needs_other_container');
    setupOthersCheckbox('stress_responses', 'stress_responses_other_container');

    const courseChoiceSelect = document.getElementById('course_choice_by');
    const courseChoiceOtherContainer = document.getElementById('course_choice_other_container');
    const courseChoiceOtherInput = document.getElementById('course_choice_other');
    if (courseChoiceSelect && courseChoiceOtherContainer && courseChoiceOtherInput) {
        const toggleCourseChoiceOther = () => {
            if (courseChoiceSelect.value === 'others') {
                courseChoiceOtherContainer.classList.remove('hidden');
                courseChoiceOtherInput.required = true;
            } else {
                courseChoiceOtherContainer.classList.add('hidden');
                courseChoiceOtherInput.required = false;
                courseChoiceOtherInput.value = '';
            }
        };
        courseChoiceSelect.addEventListener('change', toggleCourseChoiceOther);
        toggleCourseChoiceOther();
    }

    const civilStatusSelect = document.getElementById('civil_status');
    const civilStatusOtherContainer = document.getElementById('civil_status_other_container');
    const civilStatusOtherInput = document.getElementById('civil_status_other');
    if (civilStatusSelect && civilStatusOtherContainer && civilStatusOtherInput) {
        const toggleCivilStatusOther = () => {
            if (civilStatusSelect.value === 'others') {
                civilStatusOtherContainer.classList.remove('hidden');
                civilStatusOtherInput.required = true;
            } else {
                civilStatusOtherContainer.classList.add('hidden');
                civilStatusOtherInput.required = false;
                civilStatusOtherInput.value = '';
            }
        };
        civilStatusSelect.addEventListener('change', toggleCivilStatusOther);
        toggleCivilStatusOther();
    }

    const easyDiscussionRadios = document.querySelectorAll('input[name="easy_discussion_target"]');
    const easyDiscussionOtherContainer = document.getElementById('easy_discussion_other_container');
    const easyDiscussionOtherInput = document.getElementById('easy_discussion_other');
    if (easyDiscussionRadios.length && easyDiscussionOtherContainer && easyDiscussionOtherInput) {
        const toggleEasyDiscussionOther = () => {
            const selectedValue = document.querySelector('input[name="easy_discussion_target"]:checked')?.value;
            if (selectedValue === 'others') {
                easyDiscussionOtherContainer.classList.remove('hidden');
                easyDiscussionOtherInput.required = true;
            } else {
                easyDiscussionOtherContainer.classList.add('hidden');
                easyDiscussionOtherInput.required = false;
                easyDiscussionOtherInput.value = '';
            }
        };
        easyDiscussionRadios.forEach(radio => {
            radio.addEventListener('change', toggleEasyDiscussionOther);
        });
        toggleEasyDiscussionOther();
    }

    // Existing scholar field handling
    const isScholarCheckbox = document.querySelector('input[name="is_scholar"]');
    const scholarshipTypeInput = document.getElementById('scholarship_type');

    function toggleScholarField() {
        if (isScholarCheckbox.checked) {
            scholarshipTypeInput.closest('div').style.display = 'block';
        } else {
            scholarshipTypeInput.closest('div').style.display = 'none';
            scholarshipTypeInput.value = '';
        }
    }

    if (isScholarCheckbox) {
        isScholarCheckbox.addEventListener('change', toggleScholarField);
        toggleScholarField(); // Initial state
    }

        </script>
    @endif

</x-guest-layout>
