
@extends('layouts.admin')

@section('title', 'Students - Admin Panel')

@section('content')

        <div class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Students</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-graduation-cap text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Students</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalStudents ?? $students->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-school text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Students per College</p>
                            <p class="text-xs text-gray-500">Click a college to filter</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach(($studentsPerCollege ?? []) as $collegeStat)
                            <a
                                href="{{ route('admin.students', array_filter(['search' => $search, 'college' => $collegeStat->id])) }}"
                                class="border rounded-lg p-3 hover:bg-gray-50 transition"
                            >
                                <div class="text-sm font-medium text-gray-900">{{ $collegeStat->name }}</div>
                                <div class="text-xs text-gray-500">{{ $collegeStat->students_count }} student{{ $collegeStat->students_count == 1 ? '' : 's' }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search student ID, name, email, course..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <select name="college" class="px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="" {{ empty($college) ? 'selected' : '' }}>All Colleges</option>
                            @foreach($colleges as $c)
                                <option value="{{ $c->id }}" {{ (string)($college ?? '') === (string)$c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('admin.students') }}" class="bg-gray-100 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-200 inline-flex items-center">
                            <i class="fas fa-rotate-left mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">College Counselor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Course / Year</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Session</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $student->user->first_name }} {{ $student->user->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $student->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->student_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @php
                                        $headCounselor = isset($collegeCounselors) ? ($collegeCounselors[$student->college_id] ?? null) : null;
                                    @endphp
                                    <div>{{ $student->college->name ?? 'N/A' }}</div>
                                    <div class="text-gray-500">
                                        {{ $headCounselor ? ($headCounselor->user->first_name . ' ' . $headCounselor->user->last_name) : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>{{ $student->course }}</div>
                                    <div class="text-gray-500">{{ $student->year_level }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $student->lastSessionNote?->session_date?->format('M j, Y') ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ucfirst($student->student_status ?? 'new') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.students.edit', $student) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No students found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
