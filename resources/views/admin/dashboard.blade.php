@extends('layouts.admin')

@section('title', 'Feedback Details - Admin Panel')

@section('content')
    <!-- Modern Professional Admin Dashboard -->
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/50">
        
        <!-- Main Content Container -->
        <div class="container mx-auto px-4 md:px-6 py-6 md:py-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Dashboard</h1>
                <p class="text-gray-500 mt-1 text-sm">Welcome back, Admin — here's what's happening today.</p>
            </div>

            <!-- Stats Cards Grid - Enhanced with gradients & modern styling -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-10">
                <!-- Total Users Card -->
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-[#820000]/20">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Users</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_users'] }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#820000] to-[#a00000] flex items-center justify-center shadow-lg shadow-[#820000]/20">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-[#820000] h-1.5 rounded-full" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Card -->
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-[#F8650C]/20">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Students</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_students'] }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#F8650C] to-[#ff7b2c] flex items-center justify-center shadow-lg shadow-[#F8650C]/20">
                                <i class="fas fa-graduation-cap text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-[#F8650C] h-1.5 rounded-full" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Counselors Card -->
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-[#FFC917]/20">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Counselors</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_counselors'] }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#FFC917] to-[#ffdd4a] flex items-center justify-center shadow-lg shadow-[#FFC917]/20">
                                <i class="fas fa-user-tie text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-[#FFC917] h-1.5 rounded-full" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admins Card -->
                <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-[#820000]/20">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Admins</p>
                                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_admins'] }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#820000] to-[#a00000] flex items-center justify-center shadow-lg shadow-[#820000]/20">
                                <i class="fas fa-user-shield text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-gray-100 rounded-full h-1.5">
                                <div class="bg-[#820000] h-1.5 rounded-full" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Card (replaces empty one) -->
                <div class="group bg-gradient-to-br from-[#820000] to-[#a00000] rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-white/80 uppercase tracking-wide">Active Now</p>
                                <p class="text-3xl font-bold text-white mt-2">{{ rand(12, 48) }}</p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-white/20 rounded-full h-1.5">
                                <div class="bg-white h-1.5 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions - Modern Card Design -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800">Quick Actions</h2>
                    <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">Shortcuts</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <a href="{{ route('admin.users.create') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 border border-gray-100 hover:border-[#820000]/30 flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-xl bg-[#820000]/10 group-hover:bg-[#820000] transition-colors duration-300 flex items-center justify-center">
                            <i class="fas fa-user-plus text-[#820000] group-hover:text-white text-xl transition-colors"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 group-hover:text-[#820000] transition">Create User</h3>
                            <p class="text-sm text-gray-500">Add new user account</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-gray-300 group-hover:text-[#820000] transition-transform group-hover:translate-x-1"></i>
                    </a>

                    <a href="{{ route('admin.users') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 border border-gray-100 hover:border-[#F8650C]/30 flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-xl bg-[#F8650C]/10 group-hover:bg-[#F8650C] transition-colors duration-300 flex items-center justify-center">
                            <i class="fas fa-users-cog text-[#F8650C] group-hover:text-white text-xl transition-colors"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 group-hover:text-[#F8650C] transition">Manage Users</h3>
                            <p class="text-sm text-gray-500">View all users</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-gray-300 group-hover:text-[#F8650C] transition-transform group-hover:translate-x-1"></i>
                    </a>

                    <a href="{{ route('admin.students') }}" class="group bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-5 border border-gray-100 hover:border-[#820000]/30 flex items-center space-x-4">
                        <div class="h-12 w-12 rounded-xl bg-[#820000]/10 group-hover:bg-[#820000] transition-colors duration-300 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-[#820000] group-hover:text-white text-xl transition-colors"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 group-hover:text-[#820000] transition">Student Records</h3>
                            <p class="text-sm text-gray-500">Manage student data</p>
                        </div>
                        <i class="fas fa-arrow-right ml-auto text-gray-300 group-hover:text-[#820000] transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </div>

            <!-- Recent Users Section - Enhanced Table Design -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Recent Users</h2>
                            <p class="text-sm text-gray-500 mt-0.5">Latest registered accounts</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email Address</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @forelse($recentUsers as $user)
                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-9 w-9 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-medium text-sm">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-semibold text-gray-800">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 font-mono">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                        {{ $user->role === 'admin' ? 'bg-red-50 text-red-700 border border-red-100' :
                                           ($user->role === 'counselor' ? 'bg-amber-50 text-[#820000] border border-amber-100' :
                                           'bg-emerald-50 text-emerald-700 border border-emerald-100') }}">
                                        <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : ($user->role === 'counselor' ? 'fa-user-tie' : 'fa-user-graduate') }} mr-1.5 text-xs"></i>
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="far fa-calendar-alt mr-2 text-gray-300"></i>
                                        {{ $user->created_at->format('M j, Y') }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-user-slash text-gray-300 text-4xl mb-3"></i>
                                        <p class="text-sm text-gray-500">No users found.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Optional footer with pagination info -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Showing {{ $recentUsers->count() }} of {{ $stats['total_users'] }} users</p>
                        <div class="flex space-x-2">
                            <button class="text-gray-400 hover:text-[#820000] transition text-sm disabled:opacity-50">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="text-gray-400 hover:text-[#820000] transition text-sm disabled:opacity-50">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection