@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
        <!-- Main Content -->
        <div class="container mx-auto px-6 py-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Manage Resources</h1>
                        <p class="text-gray-600 mt-2">Create and manage mental health resources for students</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('counselor.resources.create') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-plus mr-2"></i> Add New Resource
                        </a>
                    </div>
                </div>
            </div>

            <!-- Resources Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if($resources->isEmpty())
                    <div class="p-8 text-center">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No Resources Yet</h3>
                        <p class="text-gray-500">Get started by creating your first resource.</p>
                        <a href="{{ route('counselor.resources.create') }}"
                           class="inline-block mt-4 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Create Resource
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resource</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($resources as $resource)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <i class="{{ $resource->icon }} text-blue-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $resource->title }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 truncate max-w-xs">
                                                        {{ $resource->description }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                                {{ $resource->category_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($resource->link)
                                                <a href="{{ $resource->link }}"
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-800 transition text-sm"
                                                   title="Open resource link">
                                                    <i class="fas fa-external-link-alt mr-1 text-xs"></i>
                                                    <span class="truncate max-w-xs">Visit Link</span>
                                                </a>
                                                <div class="text-xs text-gray-500 mt-1 truncate max-w-xs" title="{{ $resource->link }}">
                                                    {{ Str::limit($resource->link, 40) }}
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-sm">No link</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form action="{{ route('counselor.resources.update-status', $resource) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="is_active" value="{{ $resource->is_active ? 0 : 1 }}">
                                                <button type="submit"
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $resource->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }} transition">
                                                    {{ $resource->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $resource->order }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('counselor.resources.edit', $resource) }}"
                                                   class="text-blue-600 hover:text-blue-900 transition"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('counselor.resources.destroy', $resource) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 transition"
                                                            onclick="return confirm('Are you sure you want to delete this resource?')"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
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
        // Auto-dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 500);
                }, 5000);
            });
        });

        // Handle status updates with AJAX for better UX
        document.addEventListener('DOMContentLoaded', function() {
            const statusForms = document.querySelectorAll('form[action*="update-status"]');

            statusForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const resourceId = this.action.split('/').pop();

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
                            // Reload the page to show updated status
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.location.reload();
                    });
                });
            });
        });
    </script>
@endsection
