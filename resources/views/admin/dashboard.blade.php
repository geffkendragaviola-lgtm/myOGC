@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@section('content')
    <div class="min-h-screen bg-slate-50/80 relative overflow-hidden font-sans">
        <!-- Subtle Background Elements -->
        <div class="absolute top-0 left-0 w-full h-80 bg-gradient-to-b from-[#820000]/[0.03] to-transparent pointer-events-none"></div>
        <div class="absolute -top-20 -right-20 w-96 h-96 bg-[#F8650C]/[0.04] rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 -left-20 w-72 h-72 bg-[#820000]/[0.03] rounded-full blur-3xl pointer-events-none"></div>

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-7xl">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Overview</h1>
                    <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        System Operational • {{ now()->format('l, F j, Y') }}
                    </p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-10">
                
                @php
                    $statsCards = [
                        ['label' => 'Total Users', 'value' => $stats['total_users'], 'icon' => 'fa-users', 'color' => '#820000', 'bg' => 'from-[#820000] to-[#a00000]', 'width' => '100%'],
                        ['label' => 'Students', 'value' => $stats['total_students'], 'icon' => 'fa-graduation-cap', 'color' => '#F8650C', 'bg' => 'from-[#F8650C] to-[#ff7b2c]', 'width' => '75%'],
                        ['label' => 'Counselors', 'value' => $stats['total_counselors'], 'icon' => 'fa-user-tie', 'color' => '#FFC917', 'bg' => 'from-[#FFC917] to-[#ffdd4a]', 'width' => '60%'],
                        ['label' => 'Admins', 'value' => $stats['total_admins'], 'icon' => 'fa-user-shield', 'color' => '#4B5563', 'bg' => 'from-gray-600 to-gray-800', 'width' => '40%'],
                    ];
                @endphp

                @foreach($statsCards as $stat)
                <div class="group bg-white rounded-xl border border-slate-200/60 p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">{{ $stat['label'] }}</p>
                            <h3 class="text-2xl font-semibold text-slate-800 mt-0.5">{{ number_format($stat['value']) }}</h3>
                        </div>
                        <div class="p-2.5 rounded-lg bg-gradient-to-br {{ $stat['bg'] }} shadow-sm text-white">
                            <i class="fas {{ $stat['icon'] }} text-base"></i>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold inline-block px-2 py-0.5 rounded-md text-{{ $stat['color'] === '#820000' ? 'red' : ($stat['color'] === '#F8650C' ? 'orange' : 'yellow') }}-600 bg-{{ $stat['color'] === '#820000' ? 'red' : ($stat['color'] === '#F8650C' ? 'orange' : 'yellow') }}-100">
                                Capacity
                            </span>
                            <span class="text-xs font-medium text-slate-500">{{ $stat['width'] }}</span>
                        </div>
                        <div class="overflow-hidden h-1.5 w-full bg-slate-100 rounded-full">
                            <div style="width: {{ $stat['width'] }}" class="h-full rounded-full bg-gradient-to-r {{ $stat['bg'] }} transition-all duration-1000 ease-out"></div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Active Now Card -->
                <div class="group bg-gradient-to-br from-[#820000] to-[#5e0000] rounded-xl p-5 shadow-sm shadow-[#820000]/10 text-white relative overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="absolute top-0 right-0 -mt-6 -mr-6 w-28 h-28 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div>
                            <p class="text-xs font-medium text-white/60 uppercase tracking-wide">Active Sessions</p>
                            <h3 class="text-2xl font-semibold mt-0.5">{{ rand(12, 48) }}</h3>
                        </div>
                        <div class="p-2.5 rounded-lg bg-white/10 backdrop-blur-sm border border-white/10">
                            <i class="fas fa-bolt text-white text-base"></i>
                        </div>
                    </div>
                    <div class="mt-4 relative z-10">
                        <div class="flex items-center gap-2 text-xs text-white/70">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-70"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Live Activity
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left: Quick Access -->
                <div class="lg:col-span-1 space-y-5">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-slate-800">Quick Access</h2>
                        <a href="#" class="text-xs font-medium text-[#820000] hover:text-[#6a0000] transition-colors">View All</a>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('admin.users.create') }}" class="flex items-center p-3.5 bg-white rounded-xl border border-slate-200/60 shadow-sm hover:shadow-md hover:border-[#820000]/20 group transition-all duration-200">
                            <div class="h-10 w-10 rounded-lg bg-red-50 text-[#820000] flex items-center justify-center group-hover:bg-[#820000] group-hover:text-white transition-colors duration-200">
                                <i class="fas fa-user-plus text-base"></i>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-slate-800 group-hover:text-[#820000] transition-colors truncate">Add New User</h4>
                                <p class="text-xs text-slate-500 truncate">Create administrator or staff account</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 group-hover:text-[#820000] group-hover:translate-x-0.5 transition-all text-xs"></i>
                        </a>

                        <a href="{{ route('admin.students') }}" class="flex items-center p-3.5 bg-white rounded-xl border border-slate-200/60 shadow-sm hover:shadow-md hover:border-[#F8650C]/20 group transition-all duration-200">
                            <div class="h-10 w-10 rounded-lg bg-orange-50 text-[#F8650C] flex items-center justify-center group-hover:bg-[#F8650C] group-hover:text-white transition-colors duration-200">
                                <i class="fas fa-graduation-cap text-base"></i>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-slate-800 group-hover:text-[#F8650C] transition-colors truncate">Student Records</h4>
                                <p class="text-xs text-slate-500 truncate">Manage enrollments and grades</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 group-hover:text-[#F8650C] group-hover:translate-x-0.5 transition-all text-xs"></i>
                        </a>

                        <a href="{{ route('admin.counselors') }}" class="flex items-center p-3.5 bg-white rounded-xl border border-slate-200/60 shadow-sm hover:shadow-md hover:border-yellow-400/20 group transition-all duration-200">
                            <div class="h-10 w-10 rounded-lg bg-yellow-50 text-[#FFC917] flex items-center justify-center group-hover:bg-[#FFC917] group-hover:text-white transition-colors duration-200">
                                <i class="fas fa-comments text-base"></i>
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-slate-800 group-hover:text-[#FFC917] transition-colors truncate">Counseling Logs</h4>
                                <p class="text-xs text-slate-500 truncate">Review recent sessions</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 group-hover:text-[#FFC917] group-hover:translate-x-0.5 transition-all text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Right: Recent Users Table -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-slate-200/60 shadow-sm overflow-hidden flex flex-col">
                        <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/40 flex justify-between items-center">
                            <div>
                                <h2 class="text-base font-semibold text-slate-800">Recent Registrations</h2>
                                <p class="text-xs text-slate-500 mt-0.5">Latest accounts created in the system</p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">User Details</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-50">
                                    @forelse($recentUsers as $user)
                                    <tr class="hover:bg-slate-50/60 transition-colors duration-150 group">
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-9 w-9">
                                                    <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-semibold text-xs border border-slate-200">
                                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-semibold text-slate-900 group-hover:text-[#820000] transition-colors">
                                                        {{ $user->first_name }} {{ $user->last_name }}
                                                    </div>
                                                    <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            @php
                                                $roleStyles = [
                                                    'admin' => 'bg-red-50 text-red-700 border-red-100 icon-bg-red',
                                                    'counselor' => 'bg-yellow-50 text-yellow-800 border-yellow-100 icon-bg-yellow',
                                                    'student' => 'bg-blue-50 text-blue-700 border-blue-100 icon-bg-blue',
                                                ];
                                                $style = $roleStyles[$user->role] ?? 'bg-gray-50 text-gray-600';
                                                $icons = [
                                                    'admin' => 'fa-user-shield',
                                                    'counselor' => 'fa-user-tie',
                                                    'student' => 'fa-graduation-cap'
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-md border {{ $style }}">
                                                <i class="fas {{ $icons[$user->role] ?? 'fa-user' }} mr-1.5 mt-0.5 opacity-70"></i>
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full"></span>
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap text-xs text-slate-500">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-10 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <i class="fas fa-folder-open text-3xl mb-2 opacity-40"></i>
                                                <p class="text-sm font-medium">No recent users found</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/30 flex items-center justify-between">
                            <span class="text-xs text-slate-500">Showing <span class="font-medium text-slate-800">{{ $recentUsers->count() }}</span> results</span>
                            <div class="flex gap-1.5">
                                <button class="px-3 py-1.5 border border-slate-200 rounded-md text-xs font-medium text-slate-500 hover:bg-white hover:text-[#820000] hover:border-slate-300 transition disabled:opacity-50">Previous</button>
                                <button class="px-3 py-1.5 border border-slate-200 rounded-md text-xs font-medium text-slate-500 hover:bg-white hover:text-[#820000] hover:border-slate-300 transition">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection