@extends('layouts.admin')

@section('title', 'Feedback Details - Admin Panel')

@section('content')

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
                        <h1 class="text-3xl font-bold text-gray-800">Edit User</h1>
                        <p class="text-gray-600 mt-2">Manage user account information and role-specific details</p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-gray-800">{{ $user->first_name }} {{ $user->last_name }}</div>
                        <div class="text-sm text-gray-600 capitalize">{{ $user->role }}</div>
                    </div>
                </div>
            </div>

            <!-- Status Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Tabs -->
            <div class="bg-white rounded-xl shadow-sm mb-6">
                <div class="border-b">
                    <nav class="flex -mb-px">
                        <button id="personal-tab" class="tab-active py-4 px-6 text-center font-medium text-gray-600 hover:text-blue-600 transition">
                            Personal Information
                        </button>
                        <button id="role-tab" class="py-4 px-6 text-center font-medium text-gray-600 hover:text-blue-600 transition">
                            {{ ucfirst($user->role) }} Profile
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Personal Information Tab -->
            <div id="personal-content" class="tab-content">
                <div class="bg-white rounded-xl shadow-sm p-6 profile-card">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Personal Information</h2>

                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
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

                        <div class="mt-8 flex justify-between">
                            <a href="{{ route('admin.users') }}"
                               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Users
                            </a>
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                <i class="fas fa-save mr-2"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Role-specific Profile Tab -->
            <div id="role-content" class="tab-content hidden">
                <div class="bg-white rounded-xl shadow-sm p-6 profile-card">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        {{ ucfirst($user->role) }} Profile Information
                    </h2>

                    @if($user->role === 'student')
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Student ID -->
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                                    <input type="text" id="student_id" name="student_id"
                                           value="{{ old('student_id', $user->student->student_id ?? '') }}"
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
                                        <option value="1st Year" {{ old('year_level', $user->student->year_level ?? '') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ old('year_level', $user->student->year_level ?? '') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ old('year_level', $user->student->year_level ?? '') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ old('year_level', $user->student->year_level ?? '') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                        <option value="5th Year" {{ old('year_level', $user->student->year_level ?? '') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                                        <option value="Graduate" {{ old('year_level', $user->student->year_level ?? '') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    </select>
                                    @error('year_level')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Course -->
                                <div class="md:col-span-2">
                                    <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                                    <input type="text" id="course" name="course"
                                           value="{{ old('course', $user->student->course ?? '') }}"
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
                                        @foreach($colleges as $college)
                                            <option value="{{ $college->id }}"
                                                    {{ old('college_id', $user->student->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('college_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex justify-between">
                                <a href="{{ route('admin.users') }}"
                                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                                </a>
                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Update Student Profile
                                </button>
                            </div>
                        </form>

                    @elseif($user->role === 'counselor')
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Position -->
                                <div>
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                    <input type="text" id="position" name="position"
                                           value="{{ old('position', $user->counselor->position ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    @error('position')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Credentials -->
                                <div>
                                    <label for="credentials" class="block text-sm font-medium text-gray-700 mb-2">Credentials</label>
                                    <input type="text" id="credentials" name="credentials"
                                           value="{{ old('credentials', $user->counselor->credentials ?? '') }}"
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
                                        @foreach($colleges as $college)
                                            <option value="{{ $college->id }}"
                                                    {{ old('college_id', $user->counselor->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('college_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Specialization -->
                                <div class="md:col-span-2">
                                    <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                                    <textarea id="specialization" name="specialization" rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">{{ old('specialization', $user->counselor->specialization ?? '') }}</textarea>
                                    @error('specialization')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Is Head Counselor -->
                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_head" value="1"
                                               {{ old('is_head', $user->counselor->is_head ?? '') ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <span class="ms-2 text-sm text-gray-600">Head Counselor</span>
                                    </label>
                                    @error('is_head')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex justify-between">
                                <a href="{{ route('admin.users') }}"
                                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                                </a>
                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Update Counselor Profile
                                </button>
                            </div>
                        </form>

                    @elseif($user->role === 'admin')
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('patch')

                            <div class="grid grid-cols-1 gap-6">
                                <!-- Admin Credentials -->
                                <div>
                                    <label for="credentials" class="block text-sm font-medium text-gray-700 mb-2">Admin Credentials</label>
                                    <input type="text" id="credentials" name="credentials"
                                           value="{{ old('credentials', $user->admin->credentials ?? '') }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                           placeholder="e.g., System Administrator, Head Admin">
                                    @error('credentials')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-8 flex justify-between">
                                <a href="{{ route('admin.users') }}"
                                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i> Back to Users
                                </a>
                                <button type="submit"
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="fas fa-save mr-2"></i> Update Admin Profile
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

      

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabs = {
                'personal-tab': 'personal-content',
                'role-tab': 'role-content'
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

            // Auto-hide status messages


        });
    </script>
</body>
</html>
