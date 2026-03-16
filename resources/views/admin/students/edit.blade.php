@extends('layouts.admin')

@section('title', 'Edit Student - Admin Panel')

@section('content')

        <div class="container mx-auto px-6 py-8 max-w-6xl">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Student</h1>
                        <p class="text-gray-600 mt-2">Update student profile details</p>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-semibold text-gray-900">{{ $student->user->first_name }} {{ $student->user->last_name }}</div>
                        <div class="text-sm text-gray-600">{{ $student->student_id }}</div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
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

            <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">User Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $student->user->first_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('first_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $student->user->middle_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('middle_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $student->user->last_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('last_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $student->user->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $student->user->phone_number) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('phone_number')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birthdate</label>
                            <input type="date" name="birthdate" value="{{ old('birthdate', optional($student->user->birthdate)->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('birthdate')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sex</label>
                            <select name="sex" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                <option value="male" {{ old('sex', $student->user->sex) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('sex', $student->user->sex) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('sex', $student->user->sex) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('sex')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Civil Status</label>
                            <select name="civil_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['single','married','not legally married','divorced','widowed','separated','others'] as $cs)
                                    <option value="{{ $cs }}" {{ old('civil_status', $student->user->civil_status) === $cs ? 'selected' : '' }}>{{ ucfirst($cs) }}</option>
                                @endforeach
                            </select>
                            @error('civil_status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number of Children</label>
                            <input type="number" min="0" name="number_of_children" value="{{ old('number_of_children', $student->user->number_of_children) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('number_of_children')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Citizenship</label>
                            <input type="text" name="citizenship" value="{{ old('citizenship', $student->user->citizenship) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('citizenship')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birthplace</label>
                            <input type="text" name="birthplace" value="{{ old('birthplace', $student->user->birthplace) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('birthplace')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Religion</label>
                            <input type="text" name="religion" value="{{ old('religion', $student->user->religion) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('religion')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('address', $student->user->address) }}</textarea>
                            @error('address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Student Record</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                            <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('student_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">College</label>
                            <select name="college_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                @foreach($colleges as $c)
                                    <option value="{{ $c->id }}" {{ (string)old('college_id', $student->college_id) === (string)$c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('college_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                            <input type="text" name="course" value="{{ old('course', $student->course) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('course')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Year Level</label>
                            <input type="text" name="year_level" value="{{ old('year_level', $student->year_level) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('year_level')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">MSU SASE Score</label>
                            <input type="number" step="0.01" name="msu_sase_score" value="{{ old('msu_sase_score', $student->msu_sase_score) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('msu_sase_score')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                            <input type="text" name="academic_year" value="{{ old('academic_year', $student->academic_year) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('academic_year')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student Status</label>
                            <select name="student_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                @foreach(['new','transferee','returnee','shiftee'] as $st)
                                    <option value="{{ $st }}" {{ old('student_status', $student->student_status) === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                            @error('student_status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Personal Data</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nickname</label>
                            <input type="text" name="personal[nickname]" value="{{ old('personal.nickname', $student->personalData->nickname ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('personal.nickname')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stays With</label>
                            <select name="personal[stays_with]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['parents/guardian','board/roommates','relatives','friends','employer','living on my own'] as $sw)
                                    <option value="{{ $sw }}" {{ old('personal.stays_with', $student->personalData->stays_with ?? '') === $sw ? 'selected' : '' }}>{{ $sw }}</option>
                                @endforeach
                            </select>
                            @error('personal.stays_with')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Working Student</label>
                            <select name="personal[working_student]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['yes full time','yes part time','no but planning to work','no and have no plan to work'] as $ws)
                                    <option value="{{ $ws }}" {{ old('personal.working_student', $student->personalData->working_student ?? '') === $ws ? 'selected' : '' }}>{{ $ws }}</option>
                                @endforeach
                            </select>
                            @error('personal.working_student')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sex Identity</label>
                            <select name="personal[sex_identity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['male/man','female/woman','transsex male/man','transsex female/woman','sex variant/nonconforming','not listed','prefer not to say'] as $si)
                                    <option value="{{ $si }}" {{ old('personal.sex_identity', $student->personalData->sex_identity ?? '') === $si ? 'selected' : '' }}>{{ $si }}</option>
                                @endforeach
                            </select>
                            @error('personal.sex_identity')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Romantic Attraction</label>
                            <select name="personal[romantic_attraction]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['my same sex','opposite sex','both men and women','all sexes','neither sex','prefer not to answer'] as $ra)
                                    <option value="{{ $ra }}" {{ old('personal.romantic_attraction', $student->personalData->romantic_attraction ?? '') === $ra ? 'selected' : '' }}>{{ $ra }}</option>
                                @endforeach
                            </select>
                            @error('personal.romantic_attraction')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Home Address</label>
                            <textarea name="personal[home_address]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('personal.home_address', $student->personalData->home_address ?? '') }}</textarea>
                            @error('personal.home_address')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Talents / Skills (comma or new line separated)</label>
                            <textarea name="personal[talents_skills]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('personal.talents_skills', is_array($student->personalData->talents_skills ?? null) ? implode(", ", $student->personalData->talents_skills) : ($student->personalData->talents_skills ?? '')) }}</textarea>
                            @error('personal.talents_skills')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Leisure Activities (comma or new line separated)</label>
                            <textarea name="personal[leisure_activities]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('personal.leisure_activities', is_array($student->personalData->leisure_activities ?? null) ? implode(", ", $student->personalData->leisure_activities) : ($student->personalData->leisure_activities ?? '')) }}</textarea>
                            @error('personal.leisure_activities')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Serious Medical Condition</label>
                            <input type="text" name="personal[serious_medical_condition]" value="{{ old('personal.serious_medical_condition', $student->personalData->serious_medical_condition ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('personal.serious_medical_condition')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Physical Disability</label>
                            <input type="text" name="personal[physical_disability]" value="{{ old('personal.physical_disability', $student->personalData->physical_disability ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('personal.physical_disability')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Family Data</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Father Name</label>
                            <input type="text" name="family[father_name]" value="{{ old('family.father_name', $student->familyData->father_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            @error('family.father_name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center mt-6">
                            <input type="hidden" name="family[father_deceased]" value="0">
                            <input type="checkbox" name="family[father_deceased]" value="1" class="mr-2" {{ old('family.father_deceased', $student->familyData->father_deceased ?? false) ? 'checked' : '' }}>
                            <label class="text-sm font-medium text-gray-700">Father Deceased</label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Father Occupation</label>
                            <input type="text" name="family[father_occupation]" value="{{ old('family.father_occupation', $student->familyData->father_occupation ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Father Phone</label>
                            <input type="text" name="family[father_phone_number]" value="{{ old('family.father_phone_number', $student->familyData->father_phone_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mother Name</label>
                            <input type="text" name="family[mother_name]" value="{{ old('family.mother_name', $student->familyData->mother_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div class="flex items-center mt-6">
                            <input type="hidden" name="family[mother_deceased]" value="0">
                            <input type="checkbox" name="family[mother_deceased]" value="1" class="mr-2" {{ old('family.mother_deceased', $student->familyData->mother_deceased ?? false) ? 'checked' : '' }}>
                            <label class="text-sm font-medium text-gray-700">Mother Deceased</label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mother Occupation</label>
                            <input type="text" name="family[mother_occupation]" value="{{ old('family.mother_occupation', $student->familyData->mother_occupation ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mother Phone</label>
                            <input type="text" name="family[mother_phone_number]" value="{{ old('family.mother_phone_number', $student->familyData->mother_phone_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Parents Marital Status</label>
                            <select name="family[parents_marital_status]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['married','not legally married','separated','both parents remarried','one parent remarried'] as $pms)
                                    <option value="{{ $pms }}" {{ old('family.parents_marital_status', $student->familyData->parents_marital_status ?? '') === $pms ? 'selected' : '' }}>{{ $pms }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Family Monthly Income</label>
                            <select name="family[family_monthly_income]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['below 3k','3001-5000','5001-8000','8001-10000','10001-15000','15001-20000','20001 above'] as $fmi)
                                    <option value="{{ $fmi }}" {{ old('family.family_monthly_income', $student->familyData->family_monthly_income ?? '') === $fmi ? 'selected' : '' }}>{{ $fmi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ordinal Position</label>
                            <select name="family[ordinal_position]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['only child','eldest','middle','youngest'] as $op)
                                    <option value="{{ $op }}" {{ old('family.ordinal_position', $student->familyData->ordinal_position ?? '') === $op ? 'selected' : '' }}>{{ $op }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Number of Siblings</label>
                            <input type="number" min="0" name="family[number_of_siblings]" value="{{ old('family.number_of_siblings', $student->familyData->number_of_siblings ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Home Environment Description</label>
                            <textarea name="family[home_environment_description]" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('family.home_environment_description', $student->familyData->home_environment_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Academic Data</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SHS GPA</label>
                            <input type="number" step="0.01" name="academic[shs_gpa]" value="{{ old('academic.shs_gpa', $student->academicData->shs_gpa ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div class="flex items-center mt-6">
                            <input type="hidden" name="academic[is_scholar]" value="0">
                            <input type="checkbox" name="academic[is_scholar]" value="1" class="mr-2" {{ old('academic.is_scholar', $student->academicData->is_scholar ?? false) ? 'checked' : '' }}>
                            <label class="text-sm font-medium text-gray-700">Scholar</label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Scholarship Type</label>
                            <input type="text" name="academic[scholarship_type]" value="{{ old('academic.scholarship_type', $student->academicData->scholarship_type ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SHS Track</label>
                            <select name="academic[shs_track]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['academic','arts/design','tech-voc','sports'] as $track)
                                    <option value="{{ $track }}" {{ old('academic.shs_track', $student->academicData->shs_track ?? '') === $track ? 'selected' : '' }}>{{ $track }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">SHS Strand</label>
                            <select name="academic[shs_strand]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['GA','STEM','HUMMS','ABM'] as $strand)
                                    <option value="{{ $strand }}" {{ old('academic.shs_strand', $student->academicData->shs_strand ?? '') === $strand ? 'selected' : '' }}>{{ $strand }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Awards / Honors (comma or new line separated)</label>
                            <textarea name="academic[awards_honors]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('academic.awards_honors', is_array($student->academicData->awards_honors ?? null) ? implode(", ", $student->academicData->awards_honors) : ($student->academicData->awards_honors ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student Organizations (comma or new line separated)</label>
                            <textarea name="academic[student_organizations]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('academic.student_organizations', is_array($student->academicData->student_organizations ?? null) ? implode(", ", $student->academicData->student_organizations) : ($student->academicData->student_organizations ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Co-curricular Activities (comma or new line separated)</label>
                            <textarea name="academic[co_curricular_activities]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('academic.co_curricular_activities', is_array($student->academicData->co_curricular_activities ?? null) ? implode(", ", $student->academicData->co_curricular_activities) : ($student->academicData->co_curricular_activities ?? '')) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Career Option 1</label>
                            <input type="text" name="academic[career_option_1]" value="{{ old('academic.career_option_1', $student->academicData->career_option_1 ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Career Option 2</label>
                            <input type="text" name="academic[career_option_2]" value="{{ old('academic.career_option_2', $student->academicData->career_option_2 ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Career Option 3</label>
                            <input type="text" name="academic[career_option_3]" value="{{ old('academic.career_option_3', $student->academicData->career_option_3 ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Choice By</label>
                            <select name="academic[course_choice_by]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['own choice','parents choice','relative choice','sibling choice','according to MSU-SASE score/slot','others'] as $ccb)
                                    <option value="{{ $ccb }}" {{ old('academic.course_choice_by', $student->academicData->course_choice_by ?? '') === $ccb ? 'selected' : '' }}>{{ $ccb }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Choice Reason</label>
                            <textarea name="academic[course_choice_reason]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('academic.course_choice_reason', $student->academicData->course_choice_reason ?? '') }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">MSU Choice Reasons (comma or new line separated)</label>
                            <textarea name="academic[msu_choice_reasons]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('academic.msu_choice_reasons', is_array($student->academicData->msu_choice_reasons ?? null) ? implode(", ", $student->academicData->msu_choice_reasons) : ($student->academicData->msu_choice_reasons ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Future Career Plans</label>
                            <textarea name="academic[future_career_plans]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('academic.future_career_plans', $student->academicData->future_career_plans ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Learning Resources</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Internet Access</label>
                            <select name="learning[internet_access]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['no internet access','limited internet access','full internet access'] as $ia)
                                    <option value="{{ $ia }}" {{ old('learning.internet_access', $student->learningResources->internet_access ?? '') === $ia ? 'selected' : '' }}>{{ $ia }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Distance Learning Readiness</label>
                            <select name="learning[distance_learning_readiness]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['fully ready','ready','a little ready','not ready'] as $dlr)
                                    <option value="{{ $dlr }}" {{ old('learning.distance_learning_readiness', $student->learningResources->distance_learning_readiness ?? '') === $dlr ? 'selected' : '' }}>{{ $dlr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Technology Gadgets (comma or new line separated)</label>
                            <textarea name="learning[technology_gadgets]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('learning.technology_gadgets', is_array($student->learningResources->technology_gadgets ?? null) ? implode(", ", $student->learningResources->technology_gadgets) : ($student->learningResources->technology_gadgets ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Internet Connectivity (comma or new line separated)</label>
                            <textarea name="learning[internet_connectivity]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('learning.internet_connectivity', is_array($student->learningResources->internet_connectivity ?? null) ? implode(", ", $student->learningResources->internet_connectivity) : ($student->learningResources->internet_connectivity ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Learning Space Description</label>
                            <textarea name="learning[learning_space_description]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('learning.learning_space_description', $student->learningResources->learning_space_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Psychosocial Data</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Personality Characteristics (comma or new line separated)</label>
                            <textarea name="psychosocial[personality_characteristics]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('psychosocial.personality_characteristics', is_array($student->psychosocialData->personality_characteristics ?? null) ? implode(", ", $student->psychosocialData->personality_characteristics) : ($student->psychosocialData->personality_characteristics ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Coping Mechanisms (comma or new line separated)</label>
                            <textarea name="psychosocial[coping_mechanisms]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('psychosocial.coping_mechanisms', is_array($student->psychosocialData->coping_mechanisms ?? null) ? implode(", ", $student->psychosocialData->coping_mechanisms) : ($student->psychosocialData->coping_mechanisms ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Problem Sharing Targets (comma or new line separated)</label>
                            <textarea name="psychosocial[problem_sharing_targets]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('psychosocial.problem_sharing_targets', is_array($student->psychosocialData->problem_sharing_targets ?? null) ? implode(", ", $student->psychosocialData->problem_sharing_targets) : ($student->psychosocialData->problem_sharing_targets ?? '')) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mental Health Perception</label>
                            <input type="text" name="psychosocial[mental_health_perception]" value="{{ old('psychosocial.mental_health_perception', $student->psychosocialData->mental_health_perception ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>

                        <div class="flex items-center mt-6">
                            <input type="hidden" name="psychosocial[had_counseling_before]" value="0">
                            <input type="checkbox" name="psychosocial[had_counseling_before]" value="1" class="mr-2" {{ old('psychosocial.had_counseling_before', $student->psychosocialData->had_counseling_before ?? false) ? 'checked' : '' }}>
                            <label class="text-sm font-medium text-gray-700">Had Counseling Before</label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="psychosocial[sought_psychologist_help]" value="0">
                            <input type="checkbox" name="psychosocial[sought_psychologist_help]" value="1" class="mr-2" {{ old('psychosocial.sought_psychologist_help', $student->psychosocialData->sought_psychologist_help ?? false) ? 'checked' : '' }}>
                            <label class="text-sm font-medium text-gray-700">Sought Psychologist Help</label>
                        </div>

                        <div class="flex items-center">
                            <input type="hidden" name="psychosocial[needs_immediate_counseling]" value="0">
                            <input type="checkbox" name="psychosocial[needs_immediate_counseling]" value="1" class="mr-2" {{ old('psychosocial.needs_immediate_counseling', $student->psychosocialData->needs_immediate_counseling ?? false) ? 'checked' : '' }}>
                            <label class="text-sm font-medium text-gray-700">Needs Immediate Counseling</label>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Future Counseling Concerns</label>
                            <textarea name="psychosocial[future_counseling_concerns]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('psychosocial.future_counseling_concerns', $student->psychosocialData->future_counseling_concerns ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Needs Assessment</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Improvement Needs (comma or new line separated)</label>
                            <textarea name="needs[improvement_needs]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('needs.improvement_needs', is_array($student->needsAssessment->improvement_needs ?? null) ? implode(", ", $student->needsAssessment->improvement_needs) : ($student->needsAssessment->improvement_needs ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Financial Assistance Needs (comma or new line separated)</label>
                            <textarea name="needs[financial_assistance_needs]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('needs.financial_assistance_needs', is_array($student->needsAssessment->financial_assistance_needs ?? null) ? implode(", ", $student->needsAssessment->financial_assistance_needs) : ($student->needsAssessment->financial_assistance_needs ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Personal / Social Needs (comma or new line separated)</label>
                            <textarea name="needs[personal_social_needs]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('needs.personal_social_needs', is_array($student->needsAssessment->personal_social_needs ?? null) ? implode(", ", $student->needsAssessment->personal_social_needs) : ($student->needsAssessment->personal_social_needs ?? '')) }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stress Responses (comma or new line separated)</label>
                            <textarea name="needs[stress_responses]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('needs.stress_responses', is_array($student->needsAssessment->stress_responses ?? null) ? implode(", ", $student->needsAssessment->stress_responses) : ($student->needsAssessment->stress_responses ?? '')) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Easy Discussion Target</label>
                            <select name="needs[easy_discussion_target]" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">Select</option>
                                @foreach(['guidance counselor','parents','teachers','brothers/sisters','friends/relatives','nobody','others'] as $edt)
                                    <option value="{{ $edt }}" {{ old('needs.easy_discussion_target', $student->needsAssessment->easy_discussion_target ?? '') === $edt ? 'selected' : '' }}>{{ $edt }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Counseling Perceptions (comma or new line separated)</label>
                            <textarea name="needs[counseling_perceptions]" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ old('needs.counseling_perceptions', is_array($student->needsAssessment->counseling_perceptions ?? null) ? implode(", ", $student->needsAssessment->counseling_perceptions) : ($student->needsAssessment->counseling_perceptions ?? '')) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.students') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Students
                    </a>

                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
