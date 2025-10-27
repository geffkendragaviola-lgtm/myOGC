@extends('layouts.admin')

@section('title', 'Feedback Details - Admin Panel')

@section('content')


    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Back Button and Header -->
        <div class="mb-6">
            <a href="{{ route('admin.users') }}" class="inline-flex items-center text-blue-600 hover:text-blue-900 mb-4">
                <i class="fas fa-arrow-left mr-2"></i> Back to Users
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Create New User</h1>
            <p class="text-gray-600 mt-2">Add a new user to the system</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Create User Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <!-- Personal Information Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <!-- Middle Name -->
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <!-- Birthdate -->
                        <div>
                            <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">Birthdate</label>
                            <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Age (auto-calculated) -->
                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                            <input type="number" id="age" name="age" value="{{ old('age') }}" readonly
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Sex -->
                        <div>
                            <label for="sex" class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                            <select id="sex" name="sex" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Sex</option>
                                <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('sex') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Birthplace -->
                        <div>
                            <label for="birthplace" class="block text-sm font-medium text-gray-700 mb-2">Birthplace</label>
                            <input type="text" id="birthplace" name="birthplace" value="{{ old('birthplace') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Religion -->
                        <div>
                            <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text" id="religion" name="religion" value="{{ old('religion') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>


                        <!-- Civil Status -->
                        <div>
                            <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-2">Civil Status</label>
                            <select id="civil_status" name="civil_status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Civil Status</option>
                                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>

                        <!-- Citizenship -->
                        <div>
                            <label for="citizenship" class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                            <input type="text" id="citizenship" name="citizenship" value="{{ old('citizenship') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea id="address" name="address" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Account Information Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>

                        <!-- Role -->
                        <div class="md:col-span-2">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role *</label>
                            <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select Role</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="counselor" {{ old('role') == 'counselor' ? 'selected' : '' }}>Counselor</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Student-specific fields -->
                <div id="student-fields" class="mb-8 hidden">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Student Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student ID -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student ID *</label>
                            <input type="text" id="student_id" name="student_id" value="{{ old('student_id') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Year Level -->
                        <div>
                            <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level *</label>
                            <select id="year_level" name="year_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Year Level</option>
                                <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                <option value="5th Year" {{ old('year_level') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                                <option value="Graduate" {{ old('year_level') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                            </select>
                        </div>

                        <!-- Course -->
                        <div class="md:col-span-2">
                            <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Course *</label>
                            <input type="text" id="course" name="course" value="{{ old('course') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- College -->
                        <div class="md:col-span-2">
                            <label for="college_id" class="block text-sm font-medium text-gray-700 mb-2">College *</label>
                            <select id="college_id" name="college_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select College</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}" {{ old('college_id') == $college->id ? 'selected' : '' }}>
                                        {{ $college->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Counselor-specific fields -->
                <div id="counselor-fields" class="mb-8 hidden">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Counselor Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position *</label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Credentials -->
                        <div>
                            <label for="credentials" class="block text-sm font-medium text-gray-700 mb-2">Credentials *</label>
                            <input type="text" id="credentials" name="credentials" value="{{ old('credentials') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- College -->
                        <div class="md:col-span-2">
                            <label for="counselor_college_id" class="block text-sm font-medium text-gray-700 mb-2">College *</label>
                            <select id="counselor_college_id" name="counselor_college_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select College</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}" {{ old('counselor_college_id') == $college->id ? 'selected' : '' }}>
                                        {{ $college->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Is Head Counselor -->
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_head" value="1" {{ old('is_head') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Head Counselor</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Admin-specific fields -->
                <div id="admin-fields" class="mb-8 hidden">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800 border-b pb-2">Admin Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Admin Credentials -->
                        <div class="md:col-span-2">
                            <label for="admin_credentials" class="block text-sm font-medium text-gray-700 mb-2">Admin Credentials *</label>
                            <input type="text" id="admin_credentials" name="admin_credentials" value="{{ old('admin_credentials') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., System Administrator">
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.users') }}" class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition flex items-center">
                        <i class="fas fa-user-plus mr-2"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const studentFields = document.getElementById('student-fields');
            const counselorFields = document.getElementById('counselor-fields');
            const adminFields = document.getElementById('admin-fields');
            const birthdateInput = document.getElementById('birthdate');
            const ageInput = document.getElementById('age');

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

            function toggleRoleFields() {
                // Hide all fields first
                studentFields.classList.add('hidden');
                counselorFields.classList.add('hidden');
                adminFields.classList.add('hidden');

                // Show fields based on selected role
                if (roleSelect.value === 'student') {
                    studentFields.classList.remove('hidden');
                } else if (roleSelect.value === 'counselor') {
                    counselorFields.classList.remove('hidden');
                } else if (roleSelect.value === 'admin') {
                    adminFields.classList.remove('hidden');
                }
            }

            roleSelect.addEventListener('change', toggleRoleFields);

            // Initialize on page load
            toggleRoleFields();
        });
    </script>
@endsection
