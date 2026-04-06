
@extends('layouts.admin')

@section('title', 'Counselors - Admin Panel')

@section('content')

        <div class="container mx-auto px-6 py-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Counselors</h1>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ $search }}"
                               placeholder="Search counselor name, email, position, credentials..."
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
                        <button type="submit" class="bg-[#F00000] text-white px-6 py-2 rounded-lg hover:bg-[#D40000]">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('admin.counselors') }}" class="bg-gray-100 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-200 inline-flex items-center">
                            <i class="fas fa-rotate-left mr-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Counselor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">College</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credentials</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($counselors as $counselor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $counselor->user->first_name }} {{ $counselor->user->last_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $counselor->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $counselor->college->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $counselor->position ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $counselor->credentials ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $counselor->created_at?->format('M j, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No counselors found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t">
                    {{ $counselors->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
