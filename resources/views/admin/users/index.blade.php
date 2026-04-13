@extends('layouts.admin')

@section('title', 'Feedback Details - Admin Panel')

@section('content')

    <!-- Modern Professional Users Management -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/40">
        
        <!-- Main Content Container -->
        <div class="container mx-auto px-4 md:px-6 py-6 md:py-8">
            
            <!-- Enhanced Header with gradient accent -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Users Management</h1>
                    <p class="text-gray-500 text-sm mt-1">Manage and oversee all user accounts across the platform</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-[#F00000] to-[#D40000] text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-300 transform hover:-translate-y-0.5 font-medium">
                    <i class="fas fa-user-plus mr-2 text-sm"></i>
                    <span>Create User</span>
                </a>
            </div>

            <!-- Enhanced Filters Card with modern styling -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-8">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" name="search" value="{{ $search }}"
                                   placeholder="Search by name or email..."
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#F00000]/20 focus:border-[#F00000] transition-all outline-none">
                        </div>
                    </div>
                    <div class="w-full md:w-48">
                        <select name="role" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#F00000]/20 focus:border-[#F00000] outline-none bg-white text-gray-700">
                            <option value="all" {{ $role === 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Students</option>
                            <option value="counselor" {{ $role === 'counselor' ? 'selected' : '' }}>Counselors</option>
                            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admins</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gradient-to-r from-[#F00000] to-[#D40000] text-white rounded-xl hover:shadow-md transition-all duration-200 font-medium flex items-center justify-center gap-2">
                            <i class="fas fa-search text-sm"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Enhanced Users Table Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Table Header Stats -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-[#F00000]/10 flex items-center justify-center">
                            <i class="fas fa-users text-[#F00000] text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700">User List</h2>
                            <p class="text-xs text-gray-400">Total: {{ $users->total() }} users</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                            <i class="far fa-clock mr-1"></i> Last updated recently
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50/40 transition-colors duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-medium text-sm">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-800">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex items-center text-xs font-semibold rounded-full
                                        {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border border-red-100' :
                                           ($user->role === 'counselor' ? 'bg-amber-50 text-[#820000] border border-amber-100' :
                                           'bg-emerald-50 text-emerald-700 border border-emerald-100') }}">
                                        <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : ($user->role === 'counselor' ? 'fa-user-tie' : 'fa-user-graduate') }} mr-1.5 text-xs"></i>
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if($user->role === 'student' && $user->student)
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-id-card text-indigo-400 text-xs"></i>
                                            <span>{{ $user->student->student_id }}</span>
                                        </div>
                                    @elseif($user->role === 'counselor' && $user->counselor)
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-briefcase text-amber-500 text-xs"></i>
                                            <span>{{ $user->counselor->position }}</span>
                                        </div>
                                    @elseif($user->role === 'admin' && $user->admin)
                                        <div class="flex items-center gap-1.5">
                                            <i class="fas fa-key text-purple-500 text-xs"></i>
                                            <span>{{ $user->admin->credentials }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center gap-1.5">
                                        <i class="far fa-calendar-alt text-blue-400 text-xs"></i>
                                        {{ $user->created_at->format('M j, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-500 hover:text-gray-800 transition-colors duration-200" title="Edit User">
                                            <i class="fas fa-edit text-base"></i>
                                        </a>
                                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-700 transition-colors duration-200" title="Delete User">
                                                <i class="fas fa-trash-alt text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Enhanced Stylish Pagination Section - Professional & Clean -->
@if($users->hasPages())
<div class="px-6 py-5 border-t border-gray-100 bg-white">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <!-- Results Info - Left Side -->
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <i class="fas fa-database text-gray-400 text-xs"></i>
            <span>Showing 
                <span class="font-semibold text-gray-700">{{ $users->firstItem() ?? 0 }}</span> 
                to 
                <span class="font-semibold text-gray-700">{{ $users->lastItem() ?? 0 }}</span> 
                of 
                <span class="font-semibold text-gray-700">{{ $users->total() }}</span> 
                results
            </span>
        </div>

        <!-- Pagination Controls - Right Side -->
        <div class="flex items-center gap-2">
            <!-- Previous Page Link -->
            @if ($users->onFirstPage())
                <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed flex items-center gap-2">
                    <i class="fas fa-chevron-left text-xs"></i>
                </span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gradient-to-r hover:from-[#F00000] hover:to-[#D40000] hover:text-white hover:border-transparent transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-chevron-left text-xs"></i>
                </a>
            @endif

            <!-- Page Number Links -->
            <div class="flex items-center gap-1.5">
                @php
                    $currentPage = $users->currentPage();
                    $lastPage = $users->lastPage();
                    $start = max(1, $currentPage - 2);
                    $end = min($lastPage, $currentPage + 2);
                    
                    if ($start > 1) {
                        echo '<a href="' . $users->url(1) . '" class="w-9 h-9 flex items-center justify-center text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gradient-to-r hover:from-[#F00000] hover:to-[#D40000] hover:text-white hover:border-transparent transition-all duration-200">1</a>';
                        if ($start > 2) {
                            echo '<span class="w-9 h-9 flex items-center justify-center text-sm text-gray-400">...</span>';
                        }
                    }
                    
                    for ($i = $start; $i <= $end; $i++) {
                        if ($i == $currentPage) {
                            echo '<span class="w-9 h-9 flex items-center justify-center text-sm font-semibold text-white bg-gradient-to-r from-[#F00000] to-[#D40000] rounded-xl shadow-md shadow-red-500/25">' . $i . '</span>';
                        } else {
                            echo '<a href="' . $users->url($i) . '" class="w-9 h-9 flex items-center justify-center text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gradient-to-r hover:from-[#F00000] hover:to-[#D40000] hover:text-white hover:border-transparent transition-all duration-200">' . $i . '</a>';
                        }
                    }
                    
                    if ($end < $lastPage) {
                        if ($end < $lastPage - 1) {
                            echo '<span class="w-9 h-9 flex items-center justify-center text-sm text-gray-400">...</span>';
                        }
                        echo '<a href="' . $users->url($lastPage) . '" class="w-9 h-9 flex items-center justify-center text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gradient-to-r hover:from-[#F00000] hover:to-[#D40000] hover:text-white hover:border-transparent transition-all duration-200">' . $lastPage . '</a>';
                    }
                @endphp
            </div>

            <!-- Next Page Link -->
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="px-4 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gradient-to-r hover:from-[#F00000] hover:to-[#D40000] hover:text-white hover:border-transparent transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            @else
                <span class="px-4 py-2 text-sm text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed flex items-center gap-2">
                    <i class="fas fa-chevron-right text-xs"></i>
                </span>
            @endif
        </div>
    </div>
</div>
@else
<!-- No Pagination Needed - Showing all results -->
<div class="px-6 py-5 border-t border-gray-100 bg-white">
    <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
        <i class="fas fa-check-circle text-green-500 text-xs"></i>
        <span>Showing all <span class="font-semibold text-gray-700">{{ $users->total() }}</span> users</span>
    </div>
</div>
@endif
        </div>
    </div>
@endsection