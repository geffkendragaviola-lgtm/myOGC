<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Add this at the top of your form -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- First Name -->
        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="given-name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Middle Name -->
        <div class="mt-4">
            <x-input-label for="middle_name" :value="__('Middle Name')" />
            <x-text-input id="middle_name" class="block mt-1 w-full" type="text" name="middle_name" :value="old('middle_name')" autocomplete="additional-name" />
            <x-input-error :messages="$errors->get('middle_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="family-name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

<!-- Birthdate -->
<div class="mt-4">
    <x-input-label for="birthdate" :value="__('Birthdate')" />
    <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate')" autocomplete="bday-year" />
    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
</div>

        <!-- Age (auto-calculated, read-only) -->
        <div class="mt-4">
            <x-input-label for="age" :value="__('Age')" />
            <x-text-input id="age" class="block mt-1 w-full bg-gray-100" type="number" name="age" :value="old('age')" readonly autocomplete="off" />
            <x-input-error :messages="$errors->get('age')" class="mt-2" />
        </div>

        <!-- Sex -->
        <div class="mt-4">
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
        <div class="mt-4">
            <x-input-label for="birthplace" :value="__('Birthplace')" />
            <x-text-input id="birthplace" class="block mt-1 w-full" type="text" name="birthplace" :value="old('birthplace')" autocomplete="address-level2" />
            <x-input-error :messages="$errors->get('birthplace')" class="mt-2" />
        </div>

        <!-- Religion -->
        <div class="mt-4">
            <x-input-label for="religion" :value="__('Religion')" />
            <x-text-input id="religion" class="block mt-1 w-full" type="text" name="religion" :value="old('religion')" autocomplete="off" />
            <x-input-error :messages="$errors->get('religion')" class="mt-2" />
        </div>

        <!-- Affiliation -->
        <div class="mt-4">
            <x-input-label for="affiliation" :value="__('Affiliation')" />
            <x-text-input id="affiliation" class="block mt-1 w-full" type="text" name="affiliation" :value="old('affiliation')" autocomplete="organization" />
            <x-input-error :messages="$errors->get('affiliation')" class="mt-2" />
        </div>

        <!-- Civil Status -->
        <div class="mt-4">
            <x-input-label for="civil_status" :value="__('Civil Status')" />
            <select id="civil_status" name="civil_status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off">
                <option value="">Select Civil Status</option>
                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
            </select>
            <x-input-error :messages="$errors->get('civil_status')" class="mt-2" />
        </div>

        <!-- Citizenship -->
        <div class="mt-4">
            <x-input-label for="citizenship" :value="__('Citizenship')" />
            <x-text-input id="citizenship" class="block mt-1 w-full" type="text" name="citizenship" :value="old('citizenship')" autocomplete="country-name" />
            <x-input-error :messages="$errors->get('citizenship')" class="mt-2" />
        </div>

        <!-- Address -->
        <div class="mt-4">
            <x-input-label for="address" :value="__('Address')" />
            <textarea id="address" name="address" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="street-address">{{ old('address') }}</textarea>
            <x-input-error :messages="$errors->get('address')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number" :value="old('phone_number')" autocomplete="tel" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('MSU-IIT Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="email"
                pattern="^[a-zA-Z0-9._%+-]+@g\.msuiit\.edu\.ph$"
                title="Please use your MSU-IIT email (@g.msuiit.edu.ph)" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required autocomplete="off">
                <option value="">Select Role</option>
                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="counselor" {{ old('role') == 'counselor' ? 'selected' : '' }}>Counselor</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Student-specific fields -->
        <div id="student-fields" class="mt-4 hidden">
            <div class="mt-4">
                <x-input-label for="student_id" :value="__('Student ID')" />
                <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')" autocomplete="off" />
                <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="year_level" :value="__('Year Level')" />
                <select id="year_level" name="year_level" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off">
                    <option value="">Select Year Level</option>
                    <option value="1st Year" {{ old('year_level') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                    <option value="2nd Year" {{ old('year_level') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                    <option value="3rd Year" {{ old('year_level') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                    <option value="4th Year" {{ old('year_level') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                    <option value="5th Year" {{ old('year_level') == '5th Year' ? 'selected' : '' }}>5th Year</option>
                </select>
                <x-input-error :messages="$errors->get('year_level')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="course" :value="__('Course')" />
                <x-text-input id="course" class="block mt-1 w-full" type="text" name="course" :value="old('course')" autocomplete="off" />
                <x-input-error :messages="$errors->get('course')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="college_id" :value="__('College')" />
                <select id="college_id" name="college_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off">
                    <option value="">Select College</option>
                    @foreach($colleges as $college)
                        <option value="{{ $college->id }}" {{ old('college_id') == $college->id ? 'selected' : '' }}>
                            {{ $college->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('college_id')" class="mt-2" />
            </div>
        </div>

        <!-- Counselor-specific fields -->
        <div id="counselor-fields" class="mt-4 hidden">
            <div class="mt-4">
                <x-input-label for="position" :value="__('Position')" />
                <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position')" autocomplete="organization-title" />
                <x-input-error :messages="$errors->get('position')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="credentials" :value="__('Credentials')" />
                <x-text-input id="credentials" class="block mt-1 w-full" type="text" name="credentials" :value="old('credentials')" autocomplete="off" />
                <x-input-error :messages="$errors->get('credentials')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="counselor_college_id" :value="__('College')" />
                <select id="counselor_college_id" name="counselor_college_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autocomplete="off">
                    <option value="">Select College</option>
                    @foreach($colleges as $college)
                        <option value="{{ $college->id }}" {{ old('counselor_college_id') == $college->id ? 'selected' : '' }}>
                            {{ $college->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('counselor_college_id')" class="mt-2" />
            </div>

            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_head" value="1" {{ old('is_head') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" autocomplete="off">
                    <span class="ms-2 text-sm text-gray-600">Head Counselor</span>
                </label>
                <x-input-error :messages="$errors->get('is_head')" class="mt-2" />
            </div>
        </div>

        <!-- Admin-specific fields -->
        <div id="admin-fields" class="mt-4 hidden">
            <div class="mt-4">
                <x-input-label for="admin_credentials" :value="__('Admin Credentials')" />
                <x-text-input id="admin_credentials" class="block mt-1 w-full" type="text" name="admin_credentials" :value="old('admin_credentials')" autocomplete="off" />
                <x-input-error :messages="$errors->get('admin_credentials')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

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

                // Reset all role-specific fields to empty when hidden
                if (roleSelect.value !== 'student') {
                    document.getElementById('student_id').value = '';
                    document.getElementById('year_level').value = '';
                    document.getElementById('course').value = '';
                    document.getElementById('college_id').value = '';
                }

                if (roleSelect.value !== 'counselor') {
                    document.getElementById('position').value = '';
                    document.getElementById('credentials').value = '';
                    document.getElementById('counselor_college_id').value = '';
                    document.querySelector('input[name="is_head"]').checked = false;
                }

                if (roleSelect.value !== 'admin') {
                    document.getElementById('admin_credentials').value = '';
                }

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
            toggleRoleFields(); // run on page load
        });
    </script>

</x-guest-layout>
