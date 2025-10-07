@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')

        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Manage Announcements</h1>
                    <p class="text-gray-600 mt-2">Create and manage your announcements for students</p>
                </div>
                <a href="{{ route('counselor.announcements.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Create Announcement
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-bullhorn text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Announcements</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $announcements->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-full mr-4">
                            <i class="fas fa-play-circle text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Active</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $announcements->where('is_active', true)->where('status', 'active')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Scheduled</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $announcements->where('status', 'scheduled')->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-gray-100 p-3 rounded-full mr-4">
                            <i class="fas fa-check-circle text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Completed</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $announcements->where('is_active', false)->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title & Content
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dates
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($announcements as $announcement)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $announcement->title }}</div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs mt-1">
                                        {{ Str::limit($announcement->content, 80) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        bg-{{ $announcement->status_color }}-100 text-{{ $announcement->status_color }}-800">
                                        {{ ucfirst($announcement->status) }}
                                    </span>
                                    @if($announcement->is_active)
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($announcement->start_date || $announcement->end_date)
                                        <div><strong>Start:</strong> {{ $announcement->start_date?->format('M j, Y') ?? 'Immediate' }}</div>
                                        <div><strong>End:</strong> {{ $announcement->end_date?->format('M j, Y') ?? 'No end' }}</div>
                                    @else
                                        No date restrictions
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $announcement->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('counselor.announcements.edit', $announcement) }}"
                                           class="text-blue-600 hover:text-blue-900 transition"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if($announcement->is_active)
                                            <form action="{{ route('counselor.announcements.toggle-status', $announcement) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-yellow-600 hover:text-yellow-900 transition"
                                                        title="Deactivate">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('counselor.announcements.complete', $announcement) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-900 transition"
                                                        title="Mark as Completed"
                                                        onclick="return confirm('Mark this announcement as completed?')">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('counselor.announcements.toggle-status', $announcement) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-green-600 hover:text-green-900 transition"
                                                        title="Activate">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('counselor.announcements.destroy', $announcement) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 transition"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-bullhorn text-4xl mb-4"></i>
                                        <p class="text-lg">No announcements found.</p>
                                        <p class="text-sm mt-2">Create your first announcement to get started.</p>
                                        <a href="{{ route('counselor.announcements.create') }}"
                                           class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                            Create Announcement
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                @if($announcements->hasPages())
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        {{ $announcements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
