@extends('layouts.admin')

@section('title', 'Counselors - Admin Panel')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/40">
    <div class="container mx-auto px-4 md:px-6 py-6 md:py-8">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Counselors</h1>
                <p class="text-gray-500 text-sm mt-1">Manage and oversee all counselor records</p>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6 mb-8">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search counselor name, email, position, credentials..."
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

        <!-- Counselors Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Table Header Stats -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-user-tie text-amber-500 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-700">Counselor Directory</h2>
                        <p class="text-xs text-gray-400">Total records: {{ $counselors->total() }}</p>
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
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Counselor</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">College</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Position</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Credentials</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($counselors as $counselor)
                            <tr class="hover:bg-gray-50/40 transition-colors duration-150 group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center text-amber-700 font-medium text-sm">
                                            {{ strtoupper(substr($counselor->user->first_name, 0, 1)) }}{{ strtoupper(substr($counselor->user->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800">{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}</div>
                                            <div class="text-xs text-gray-500 font-mono">{{ $counselor->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-school text-gray-300 text-xs"></i>
                                        <span class="text-sm text-gray-700">{{ $counselor->college->name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                        <i class="fas fa-briefcase mr-1 text-xs"></i>
                                        {{ $counselor->position ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700">
                                        @if($counselor->credentials)
                                            <span class="inline-flex items-center gap-1">
                                                <i class="fas fa-certificate text-amber-500 text-xs"></i>
                                                {{ $counselor->credentials }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                        <i class="far fa-calendar-alt text-blue-400 text-xs"></i>
                                        {{ $counselor->created_at?->format('M j, Y') ?? 'N/A' }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-14 w-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                            <i class="fas fa-user-tie-slash text-gray-400 text-xl"></i>
                                        </div>
                                        <p class="text-sm text-gray-500">No counselors found.</p>
                                        <p class="text-xs text-gray-400 mt-1">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination Section -->
            @if($counselors->hasPages())
            <div class="px-6 py-5 border-t border-gray-100 bg-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-database text-gray-400 text-xs"></i>
                        <span>Showing 
                            <span class="font-semibold text-gray-700">{{ $counselors->firstItem() ?? 0 }}</span> 
                            to 
                            <span class="font-semibold text-gray-700">{{ $counselors->lastItem() ?? 0 }}</span> 
                            of 
                            <span class="font-semibold text-gray-700">{{ $counselors->total() }}</span> 
                            results
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        {{ $counselors->appends(request()->query())->links() }}
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
                    <span>Showing all <span class="font-semibold text-gray-700">{{ $counselors->total() }}</span> counselors</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection