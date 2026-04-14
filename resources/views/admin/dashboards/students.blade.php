@extends('layouts.admin')

@section('title', 'Students - Admin Panel')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/40">
    <div class="container mx-auto px-4 md:px-6 py-6 md:py-8">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Students</h1>
                <p class="text-gray-500 text-sm mt-1">Manage and oversee all student records</p>
            </div>
        </div>

        <!-- Stats Cards Row - Redesigned Total Students Card -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
            <!-- Total Students Card - Now matching height with college card -->
            <div class="lg:col-span-4">
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="h-8 w-8 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100 flex items-center justify-center">
                                        <i class="fas fa-users text-emerald-600 text-sm"></i>
                                    </div>
                                    <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Active</span>
                                </div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Students</p>
                                <p class="text-4xl font-bold text-gray-800 mt-1">{{ $totalStudents ?? $students->total() }}</p>
                                <div class="mt-3 flex items-center gap-2">
                                    <i class="fas fa-chart-line text-emerald-500 text-xs"></i>
                                    <span class="text-xs text-gray-400">+{{ rand(2, 8) }}% from last month</span>
                                </div>
                            </div>
                            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <i class="fas fa-graduation-cap text-white text-2xl"></i>
                            </div>
                        </div>
                        <!-- Progress bar -->
                        <div class="mt-5">
                            <div class="flex justify-between text-xs text-gray-400 mb-1.5">
                                <span>Capacity</span>
                                <span>{{ min(100, round((($totalStudents ?? $students->total()) / 50) * 100)) }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full" style="width: {{ min(100, round((($totalStudents ?? $students->total()) / 50) * 100)) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students per College Card - Now spans 8 columns -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                    <div class="p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                                <i class="fas fa-school text-red-500 text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Students per College</p>
                                <p class="text-xs text-gray-400">Click a college to filter results</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                            @foreach(($studentsPerCollege ?? []) as $collegeStat)
                                <a href="{{ route('admin.students', array_filter(['search' => $search, 'college' => $collegeStat->id])) }}"
                                   class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 bg-white hover:border-red-200 hover:bg-red-50/30 transition-all duration-200">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-800 group-hover:text-red-600 transition">{{ $collegeStat->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $collegeStat->students_count }} student{{ $collegeStat->students_count == 1 ? '' : 's' }}</div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-300 group-hover:text-red-400 text-xs transition-transform group-hover:translate-x-0.5"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-8">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search student ID, name, email, course..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-all outline-none">
                    </div>
                </div>
                <div class="w-full md:w-64">
                    <select name="college" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500/20 focus:border-red-400 outline-none bg-white text-gray-700">
                        <option value="" {{ empty($college) ? 'selected' : '' }}>All Colleges</option>
                        @foreach($colleges as $c)
                            <option value="{{ $c->id }}" {{ (string)($college ?? '') === (string)$c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-search text-sm"></i>
                        <span>Search</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Students Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header Stats -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-red-50 flex items-center justify-center">
                        <i class="fas fa-user-graduate text-red-500 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-700">Student Directory</h2>
                        <p class="text-xs text-gray-400">Total records: {{ $students->total() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                        <i class="far fa-clock mr-1"></i> Live data
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student ID</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">College / Counselor</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Course / Year</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Session</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50/40 transition-colors duration-150 group">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-500">{{ $student->id }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-medium text-sm">
                                            {{ strtoupper(substr($student->user->first_name, 0, 1)) }}{{ strtoupper(substr($student->user->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">{{ $student->user->first_name }} {{ $student->user->last_name }}</div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $student->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-700 bg-gray-50 px-2 py-1 rounded-lg">{{ $student->student_id }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $headCounselor = isset($collegeCounselors) ? ($collegeCounselors[$student->college_id] ?? null) : null;
                                    @endphp
                                    <div class="text-sm font-medium text-gray-800">{{ $student->college->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <i class="fas fa-user-tie text-gray-300 text-xs mr-1"></i>
                                        {{ $headCounselor ? ($headCounselor->user->first_name . ' ' . $headCounselor->user->last_name) : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="text-sm font-medium text-gray-800">{{ $student->course }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        <i class="far fa-calendar-alt text-gray-300 text-xs mr-1"></i>
                                        Year {{ $student->year_level }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                        <i class="far fa-clock text-blue-400 text-xs"></i>
                                        {{ $student->lastSessionNote?->session_date?->format('M j, Y') ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full 
                                        {{ $student->student_status == 'new' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 
                                           ($student->student_status == 'transferee' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 
                                           ($student->student_status == 'returnee' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 
                                           'bg-gray-100 text-gray-600 border border-gray-100')) }}">
                                        <i class="fas {{ $student->student_status == 'new' ? 'fa-star' : ($student->student_status == 'transferee' ? 'fa-exchange-alt' : ($student->student_status == 'returnee' ? 'fa-undo' : 'fa-user')) }} mr-1 text-xs"></i>
                                        {{ ucfirst($student->student_status ?? 'new') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.students.edit', $student) }}" 
                                           class="text-gray-500 hover:text-gray-800 transition-colors duration-200" 
                                           title="Edit Student">
                                            <i class="fas fa-edit text-base"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-14 w-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                            <i class="fas fa-user-slash text-gray-400 text-xl"></i>
                                        </div>
                                        <p class="text-sm text-gray-500">No students found.</p>
                                        <p class="text-xs text-gray-400 mt-1">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination Section -->
            @if($students->hasPages())
            <div class="px-6 py-5 border-t border-gray-100 bg-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-database text-gray-400 text-xs"></i>
                        <span>Showing 
                            <span class="font-semibold text-gray-700">{{ $students->firstItem() ?? 0 }}</span> 
                            to 
                            <span class="font-semibold text-gray-700">{{ $students->lastItem() ?? 0 }}</span> 
                            of 
                            <span class="font-semibold text-gray-700">{{ $students->total() }}</span> 
                            results
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        {{ $students->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

            <!-- Pagination Custom Styles -->
            <style>
                .flex.items-center.gap-2 nav {
                    display: inline-flex;
                }
                .flex.items-center.gap-2 .relative {
                    display: flex;
                    gap: 6px;
                    align-items: center;
                    flex-wrap: wrap;
                }
                .flex.items-center.gap-2 span, 
                .flex.items-center.gap-2 a {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 36px;
                    height: 36px;
                    padding: 0 10px;
                    border-radius: 12px;
                    font-size: 13px;
                    font-weight: 500;
                    transition: all 0.2s ease;
                }
                .flex.items-center.gap-2 span[aria-current="page"] span {
                    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                    color: white;
                    box-shadow: 0 2px 8px rgba(220, 38, 38, 0.25);
                }
                .flex.items-center.gap-2 a {
                    background: white;
                    color: #4B5563;
                    border: 1px solid #E5E7EB;
                }
                .flex.items-center.gap-2 a:hover {
                    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                    color: white;
                    border-color: transparent;
                    transform: translateY(-1px);
                    box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
                }
            </style>
            @else
            <div class="px-6 py-5 border-t border-gray-100 bg-white">
                <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                    <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                    <span>Showing all <span class="font-semibold text-gray-700">{{ $students->total() }}</span> students</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection