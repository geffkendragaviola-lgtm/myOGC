<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Create New Admin Account</h2>
        <p class="text-gray-600 text-center mt-2">Register a new administrator for the system</p>
    </div>

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

        <!-- Role (hidden, set to admin) -->
        <input type="hidden" name="role" value="admin">

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

        <!-- Admin-specific fields -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">Administrator Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Position -->
                <div>
                    <x-input-label for="position" :value="__('Position')" />
                    <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position')" required autocomplete="organization-title" placeholder="e.g., System Administrator" />
                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                </div>

                <!-- Department -->
                <div>
                    <x-input-label for="department" :value="__('Department')" />
                    <x-text-input id="department" class="block mt-1 w-full" type="text" name="department" :value="old('department')" required autocomplete="organization" placeholder="e.g., IT Department" />
                    <x-input-error :messages="$errors->get('department')" class="mt-2" />
                </div>

                <!-- Employee ID -->
                <div>
                    <x-input-label for="employee_id" :value="__('Employee ID')" />
                    <x-text-input id="employee_id" class="block mt-1 w-full" type="text" name="employee_id" :value="old('employee_id')" required autocomplete="off" placeholder="e.g., EMP-2024-001" />
                    <x-input-error :messages="$errors->get('employee_id')" class="mt-2" />
                </div>

                <!-- Office Location -->
                <div>
                    <x-input-label for="office_location" :value="__('Office Location')" />
                    <x-text-input id="office_location" class="block mt-1 w-full" type="text" name="office_location" :value="old('office_location')" autocomplete="off" placeholder="e.g., Main Building, Room 101" />
                    <x-input-error :messages="$errors->get('office_location')" class="mt-2" />
                </div>

                <!-- Extension -->
                <div class="md:col-span-2">
                    <x-input-label for="extension" :value="__('Extension')" />
                    <x-text-input id="extension" class="block mt-1 w-full" type="text" name="extension" :value="old('extension')" autocomplete="off" placeholder="e.g., 1234" />
                    <x-input-error :messages="$errors->get('extension')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Password -->
        <div class="mt-6">
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

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Back to Login') }}
            </a>

            <x-primary-button class="ms-4 bg-blue-600 hover:bg-blue-700">
                <i class="fas fa-user-plus mr-2"></i>
                {{ __('Create Admin Account') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            // Auto-calculate age if birthdate is already filled (on page load)
            if (birthdateInput.value) {
                calculateAge();
            }
        });
    </script>

</x-guest-layout>
