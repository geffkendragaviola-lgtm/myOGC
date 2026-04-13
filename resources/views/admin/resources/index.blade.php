@extends('layouts.admin')

@section('title', 'Resources - Admin Panel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100/40">
    <div class="container mx-auto px-4 md:px-6 py-6 md:py-8">
        
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                            <i class="fas fa-book-open text-red-500 text-lg"></i>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Manage Resources</h1>
                    </div>
                    <p class="text-gray-500 text-sm ml-1">Create and manage mental health resources for students</p>
                </div>
                <div>
                    <a href="{{ route('admin.resources.create') }}"
                       class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium">
                        <i class="fas fa-plus mr-2 text-sm"></i> Add New Resource
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($resources->isEmpty())
                <div class="p-12 text-center">
                    <div class="h-20 w-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box-open text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Resources Yet</h3>
                    <p class="text-gray-400 text-sm">Get started by creating your first resource.</p>
                    <a href="{{ route('admin.resources.create') }}"
                       class="inline-flex items-center mt-5 px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:shadow-lg hover:shadow-red-500/25 transition-all duration-200 font-medium">
                        <i class="fas fa-plus mr-2 text-sm"></i> Create Resource
                    </a>
                </div>
            @else
                <!-- Table Header Stats -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-red-50 flex items-center justify-center">
                            <i class="fas fa-folder-open text-red-500 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-gray-700">Resource Library</h2>
                            <p class="text-xs text-gray-400">Total resources: {{ $resources->count() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                            <i class="far fa-clock mr-1"></i> Live data
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Resource</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Link</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($resources as $resource)
                                <tr class="hover:bg-gray-50/40 transition-colors duration-150 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                                <i class="{{ $resource->icon }} text-red-500 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-800">
                                                    {{ $resource->title }}
                                                </div>
                                                <div class="text-xs text-gray-500 max-w-xs truncate">
                                                    {{ $resource->description }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100 capitalize">
                                            <i class="fas fa-tag mr-1 text-xs"></i>
                                            {{ $resource->category_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($resource->link)
                                            <a href="{{ $resource->link }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="inline-flex items-center text-red-500 hover:text-red-700 transition text-sm font-medium"
                                               title="Open resource link">
                                                <i class="fas fa-external-link-alt mr-1.5 text-xs"></i>
                                                <span>Visit Link</span>
                                            </a>
                                            <div class="text-xs text-gray-400 mt-1 truncate max-w-xs font-mono" title="{{ $resource->link }}">
                                                {{ Str::limit($resource->link, 35) }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm italic">No link provided</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.resources.update-status', $resource) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $resource->is_active ? 0 : 1 }}">
                                            <button type="submit"
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-all duration-200 
                                                    {{ $resource->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 border border-gray-100 hover:bg-gray-200' }}">
                                                <i class="fas {{ $resource->is_active ? 'fa-check-circle' : 'fa-circle' }} mr-1.5 text-xs"></i>
                                                {{ $resource->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <div class="h-6 w-6 rounded-md bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-sort-numeric-down-alt text-gray-400 text-xs"></i>
                                            </div>
                                            <span class="text-sm font-mono text-gray-600">{{ $resource->order }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('admin.resources.edit', $resource) }}"
                                               class="text-gray-500 hover:text-gray-800 transition-colors duration-200"
                                               title="Edit Resource">
                                                <i class="fas fa-edit text-base"></i>
                                            </a>

                                            <form action="{{ route('admin.resources.destroy', $resource) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-400 hover:text-red-700 transition-colors duration-200"
                                                        onclick="return confirm('Are you sure you want to delete this resource?')"
                                                        title="Delete Resource">
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
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusForms = document.querySelectorAll('form[action*="update-status"], form[action*="/status"]');

        statusForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(() => window.location.reload());
            });
        });
    });
</script>
@endsection