<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Office of Guidance and Counseling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .profile-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .profile-navbar {
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .tab-active {
            border-bottom: 3px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="profile-container">
        <!-- Navigation -->
        <nav class="profile-navbar py-4">
            <div class="container mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="text-white font-bold text-2xl mr-10">OGC</div>
                    <div class="hidden md:flex space-x-8">
                        <a href="{{ route('dashboard') }}" class="text-white font-semibold hover:text-yellow-300 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="text-white">
                        Welcome, {{ Auth::user()->first_name }}
                    </div>
                    <a href="{{ route('dashboard') }}" class="text-white p-2 rounded-full hover:bg-blue-700 transition">
                        <i class="fas fa-home"></i>
                    </a>
                </div>
            </div>
        </nav>

        <!-- Profile Content -->
        <div class="container mx-auto px-6 py-8 max-w-6xl">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Profile Settings</h1>
                        <p class="text-gray-600 mt-2">Manage your account information and preferences</p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                        <div class="text-sm text-gray-600 capitalize">{{ Auth::user()->role }}</div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-xl shadow-sm mb-6">
                <div class="border-b">
                    <nav class="flex -mb-px">
                        <button id="personal-tab" class="tab-active py-4 px-6 text-center font-medium text-gray-600 hover:text-blue-600 transition">
                            Personal Information
                        </button>
                        <button id="role-tab" class="py-4 px-6 text-center font-medium text-gray-600 hover:text-blue-600 transition">
                            {{ ucfirst(Auth::user()->role) }} Profile
                        </button>
                        <button id="password-tab" class="py-4 px-6 text-center font-medium text-gray-600 hover:text-blue-600 transition">
                            Change Password
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Status Messages -->
            @if(session('status'))
                <div class="mb-6 p-4 rounded-lg
                    @if(session('status') == 'profile-updated') bg-green-50 text-green-700 border border-green-200
                    @elseif(session('status') == 'password-updated') bg-green-50 text-green-700 border border-green-200
                    @elseif(session('status') == 'student-profile-updated') bg-green-50 text-green-700 border border-green-200
                    @elseif(session('status') == 'counselor-profile-updated') bg-green-50 text-green-700 border border-green-200
                    @else bg-blue-50 text-blue-700 border border-blue-200 @endif">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        @switch(session('status'))
                            @case('profile-updated')
                                Profile information updated successfully.
                                @break
                            @case('password-updated')
                                Password updated successfully.
                                @break
                            @case('student-profile-updated')
                                Student profile updated successfully.
                                @break
                            @case('counselor-profile-updated')
                                Counselor profile updated successfully.
                                @break
                            @default
                                Profile updated successfully.
                        @endswitch
                    </div>
                </div>
            @endif

            <!-- Personal Information Tab -->
            <div id="personal-content" class="tab-content">
                <div class="bg-white rounded-xl shadow-sm p-6 profile-card">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Personal Information</h2>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('first_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name -->
                            <div>
                                <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('middle_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('last_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

<!-- Birthdate -->
<div>
    <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">Birthdate</label>
    <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '') }}"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
    @error('birthdate')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

                            <!-- Age (Read-only) -->
                            <div>
                                <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                                <input type="number" id="age" name="age" value="{{ old('age', $user->age) }}" readonly
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('age')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sex -->
                            <div>
                                <label for="sex" class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                                <select id="sex" name="sex" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Select Sex</option>
                                    <option value="male" {{ old('sex', $user->sex) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('sex', $user->sex) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('sex', $user->sex) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sex')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Birthplace -->
                            <div>
                                <label for="birthplace" class="block text-sm font-medium text-gray-700 mb-2">Birthplace</label>
                                <input type="text" id="birthplace" name="birthplace" value="{{ old('birthplace', $user->birthplace) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('birthplace')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Religion -->
                            <div>
                                <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                                <input type="text" id="religion" name="religion" value="{{ old('religion', $user->religion) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('religion')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Affiliation -->
                            <div>
                                <label for="affiliation" class="block text-sm font-medium text-gray-700 mb-2">Affiliation</label>
                                <input type="text" id="affiliation" name="affiliation" value="{{ old('affiliation', $user->affiliation) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('affiliation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Civil Status -->
                            <div>
                                <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-2">Civil Status</label>
                                <select id="civil_status" name="civil_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">Select Civil Status</option>
                                    <option value="single" {{ old('civil_status', $user->civil_status) == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('civil_status', $user->civil_status) == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('civil_status', $user->civil_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('civil_status', $user->civil_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                @error('civil_status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Citizenship -->
                            <div>
                                <label for="citizenship" class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                                <input type="text" id="citizenship" name="citizenship" value="{{ old('citizenship', $user->citizenship) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('citizenship')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('phone_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="address" name="address" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Role-specific Profile Tab -->
            <div id="role-content" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm p-6 profile-card">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        {{ ucfirst(Auth::user()->role) }} Profile Information
                    </h2>

                    @if(Auth::user()->role === 'student')
                        <form method="POST" action="{{ route('profile.student.update') }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Student ID -->
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                                    <input type="text" id="student_id" name="student_id"
                                           value="{{ old('student_id', $studentProfile->student_id ?? '') }}"   
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    @error('student_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Year Level -->
                                <div>
                                    <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                                    <select id="year_level" name="year_level"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year" {{ old('year_level', $studentProfile->year_level ?? '') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level', $studentProfile->year_level ?? '') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level', $studentProfile->year_level ?? '') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level', $studentProfile->year_level ?? '') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                        <option value="5th Year" {{ old('year_level', $studentProfile->year_level ?? '') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                                        <option value="Graduate" {{ old('year_level', $studentProfile->year_level ?? '') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    </select>
                                    @error('year_level')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Course -->
                                <div class="md:col-span-2">
                                    <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                                    <input type="text" id="course" name="course"
                                           value="{{ old('course', $studentProfile->course ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                           placeholder="e.g., Bachelor of Science in Computer Science">
                                    @error('course')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- College -->
                                <div class="md:col-span-2">
                                    <label for="college_id" class="block text-sm font-medium text-gray-700 mb-2">College</label>
                                    <select id="college_id" name="college_id"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="">Select College</option>
                                        @foreach(\App\Models\College::all() as $college)
                                            <option value="{{ $college->id }}"
                                                    {{ old('college_id', $studentProfile->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('college_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Save Student Profile
                                </button>
                            </div>
                        </form>

                    @elseif(Auth::user()->role === 'counselor')
                        <form method="POST" action="{{ route('profile.counselor.update') }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Position -->
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                    <input type="text" id="position" name="position"
                                           value="{{ old('position', $counselorProfile->position ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    @error('position')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Credentials -->
                                <div>
                                    <label for="credentials" class="block text-sm font-medium text-gray-700 mb-2">Credentials</label>
                                    <input type="text" id="credentials" name="credentials"
                                           value="{{ old('credentials', $counselorProfile->credentials ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    @error('credentials')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- College -->
                                <div class="md:col-span-2">
                                    <label for="college_id" class="block text-sm font-medium text-gray-700 mb-2">Assigned College</label>
                                    <select id="college_id" name="college_id"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                        <option value="">Select College</option>
                                        @foreach(\App\Models\College::all() as $college)
                                            <option value="{{ $college->id }}"
                                                    {{ old('college_id', $counselorProfile->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('college_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Is Head Counselor -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_head" value="1"
                                               {{ old('is_head', $counselorProfile->is_head ?? '') ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ms-2 text-sm text-gray-600">Head Counselor</span>
                                    </label>
                                    @error('is_head')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Save Counselor Profile
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Change Password Tab -->
            <div id="password-content" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm p-6 profile-card">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Change Password</h2>

                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('patch')

                        <div class="space-y-6 max-w-md">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" id="current_password" name="current_password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                <input type="password" id="password" name="password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                <i class="fas fa-key mr-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-12">
            <div class="container mx-auto px-6 text-center">
                <p>&copy; 2025 Office of Guidance and Counseling. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabs = {
                'personal-tab': 'personal-content',
                'role-tab': 'role-content',
                'password-tab': 'password-content'
            };

            function switchTab(activeTab) {
                // Remove active class from all tabs
                Object.keys(tabs).forEach(tabId => {
                    document.getElementById(tabId).classList.remove('tab-active');
                    document.getElementById(tabs[tabId]).classList.add('hidden');
                });

                // Add active class to clicked tab
                document.getElementById(activeTab).classList.add('tab-active');
                document.getElementById(tabs[activeTab]).classList.remove('hidden');
            }

            // Add click event listeners to tabs
            Object.keys(tabs).forEach(tabId => {
                document.getElementById(tabId).addEventListener('click', () => switchTab(tabId));
            });

            // Auto-calculate age when birthdate changes
            const birthdateInput = document.getElementById('birthdate');
            const ageInput = document.getElementById('age');

            if (birthdateInput && ageInput) {
                birthdateInput.addEventListener('change', function() {
                    if (this.value) {
                        const birthDate = new Date(this.value);
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
                });
            }

            // Only fade out status messages (success/error messages)
            const statusMessages = document.querySelectorAll('.mb-6.bg-');
            statusMessages.forEach(message => {
                // Check if it's actually a status message (has specific background colors)
                const hasStatusStyle = message.classList.contains('bg-green-50') ||
                                      message.classList.contains('bg-blue-50') ||
                                      message.classList.contains('bg-red-50') ||
                                      message.classList.contains('bg-yellow-50');

                if (hasStatusStyle) {
                    setTimeout(() => {
                        message.style.transition = 'opacity 0.5s ease';
                        message.style.opacity = '0';
                        setTimeout(() => {
                            if (message.parentNode) {
                                message.remove();
                            }
                        }, 500);
                    }, 5000);
                }
            });
        });
    </script>
</body>
</html>
