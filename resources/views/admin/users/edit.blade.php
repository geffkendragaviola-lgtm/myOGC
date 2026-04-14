@extends('layouts.admin')

@section('title', 'Edit User - Admin Panel')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.users') }}" class="inline-flex items-center text-[#F00000] hover:text-[#820000] mb-4">
            <i class="fas fa-arrow-left mr-2"></i> Back to Users
        </a>
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

    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-700 border border-red-200">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    <div class="font-semibold">Please fix the errors below.</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button id="personal-tab" class="tab-active py-4 px-6 text-center font-medium border-b-2 border-[#F00000] text-[#F00000]">
                    Personal Information
                </button>
                <button id="role-tab" class="py-4 px-6 text-center font-medium text-gray-500 hover:text-[#F00000] transition border-b-2 border-transparent">
                    {{ ucfirst($user->role) }} Profile
                </button>
            </nav>
        </div>
    </div>

    <!-- Personal Information Tab -->
    <div id="personal-content" class="tab-content">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Personal Information</h2>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Middle Name -->
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('middle_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('phone_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birthdate -->
                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-2">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('birthdate')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sex -->
                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                        <select id="sex" name="sex"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                            <option value="">Select</option>
                            <option value="male" {{ old('sex', $user->sex) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', $user->sex) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('sex', $user->sex) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('sex')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Civil Status -->
                    <div>
                        <label for="civil_status" class="block text-sm font-medium text-gray-700 mb-2">Civil Status</label>
                        <select id="civil_status" name="civil_status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                            <option value="">Select</option>
                            @foreach(['single','married','divorced','widowed'] as $cs)
                                <option value="{{ $cs }}" {{ old('civil_status', $user->civil_status) == $cs ? 'selected' : '' }}>{{ ucfirst($cs) }}</option>
                            @endforeach
                        </select>
                        @error('civil_status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Birthplace -->
                    <div>
                        <label for="birthplace" class="block text-sm font-medium text-gray-700 mb-2">Birthplace</label>
                        <input type="text" id="birthplace" name="birthplace" value="{{ old('birthplace', $user->birthplace) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('birthplace')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Religion -->
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                        <input type="text" id="religion" name="religion" value="{{ old('religion', $user->religion) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('religion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Citizenship -->
                    <div>
                        <label for="citizenship" class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                        <input type="text" id="citizenship" name="citizenship" value="{{ old('citizenship', $user->citizenship) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                        @error('citizenship')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('admin.users') }}"
                       class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Users
                    </a>
                    <button type="submit"
                            class="bg-[#F00000] text-white px-6 py-2 rounded-md hover:bg-[#D40000] transition flex items-center">
                        <i class="fas fa-save mr-2"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Role-specific Profile Tab -->
    <div id="role-content" class="tab-content hidden">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">
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
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                            @error('student_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year Level -->
                        <div>
                            <label for="year_level" class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                            <select id="year_level" name="year_level"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
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
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]"
                                   placeholder="e.g., Bachelor of Science in Computer Science">
                            @error('course')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- College -->
                        <div class="md:col-span-2">
                            <label for="college_id" class="block text-sm font-medium text-gray-700 mb-2">College</label>
                            <select id="college_id" name="college_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
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
                           class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Users
                        </a>
                        <button type="submit"
                                class="bg-[#F00000] text-white px-6 py-2 rounded-md hover:bg-[#D40000] transition flex items-center">
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
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                            @error('position')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Credentials -->
                        <div>
                            <label for="credentials" class="block text-sm font-medium text-gray-700 mb-2">Credentials</label>
                            <input type="text" id="credentials" name="credentials"
                                   value="{{ old('credentials', $user->counselor->credentials ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                            @error('credentials')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- College -->
                        <div class="md:col-span-2">
                            <label for="counselor_college_id" class="block text-sm font-medium text-gray-700 mb-2">Assigned College</label>
                            <select id="counselor_college_id" name="counselor_college_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">
                                <option value="">Select College</option>
                                @foreach($colleges as $college)
                                    <option value="{{ $college->id }}"
                                            {{ old('counselor_college_id', $user->counselor->college_id ?? '') == $college->id ? 'selected' : '' }}>
                                        {{ $college->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('counselor_college_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Specialization -->
                        <div class="md:col-span-2">
                            <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                            <textarea id="specialization" name="specialization" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]">{{ old('specialization', $user->counselor->specialization ?? '') }}</textarea>
                            @error('specialization')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Head Counselor -->
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_head" value="1"
                                       {{ old('is_head', $user->counselor->is_head ?? false) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-[#F00000] shadow-sm focus:ring-[#F00000]">
                                <span class="ml-2 text-sm text-gray-600">Head Counselor</span>
                            </label>
                            @error('is_head')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('admin.users') }}"
                           class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Users
                        </a>
                        <button type="submit"
                                class="bg-[#F00000] text-white px-6 py-2 rounded-md hover:bg-[#D40000] transition flex items-center">
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
                            <label for="admin_credentials" class="block text-sm font-medium text-gray-700 mb-2">Admin Credentials</label>
                            <input type="text" id="admin_credentials" name="admin_credentials"
                                   value="{{ old('admin_credentials', $user->admin->credentials ?? '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#F00000] focus:border-[#F00000]"
                                   placeholder="e.g., System Administrator, Head Admin">
                            @error('admin_credentials')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('admin.users') }}"
                           class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600 transition flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Users
                        </a>
                        <button type="submit"
                                class="bg-[#F00000] text-white px-6 py-2 rounded-md hover:bg-[#D40000] transition flex items-center">
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
        const personalTab = document.getElementById('personal-tab');
        const roleTab = document.getElementById('role-tab');
        const personalContent = document.getElementById('personal-content');
        const roleContent = document.getElementById('role-content');

        function switchToPersonal() {
            personalTab.classList.add('tab-active');
            personalTab.classList.remove('border-transparent');
            personalTab.classList.add('border-[#F00000]', 'text-[#F00000]');
            
            roleTab.classList.remove('tab-active');
            roleTab.classList.add('border-transparent');
            roleTab.classList.remove('border-[#F00000]', 'text-[#F00000]');
            roleTab.classList.add('text-gray-500');
            
            personalContent.classList.remove('hidden');
            roleContent.classList.add('hidden');
        }

        function switchToRole() {
            roleTab.classList.add('tab-active');
            roleTab.classList.remove('border-transparent');
            roleTab.classList.add('border-[#F00000]', 'text-[#F00000]');
            
            personalTab.classList.remove('tab-active');
            personalTab.classList.add('border-transparent');
            personalTab.classList.remove('border-[#F00000]', 'text-[#F00000]');
            personalTab.classList.add('text-gray-500');
            
            roleContent.classList.remove('hidden');
            personalContent.classList.add('hidden');
        }

        personalTab.addEventListener('click', switchToPersonal);
        roleTab.addEventListener('click', switchToRole);
    });
</script>
@endsection