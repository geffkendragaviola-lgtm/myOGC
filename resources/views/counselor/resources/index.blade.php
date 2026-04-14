@extends('layouts.app')

@section('title', 'Counselor Dashboard - OGC')

@section('content')
<style>
    :root {
        --maroon-900: #3a0c0c;
        --maroon-800: #5c1a1a;
        --maroon-700: #7a2a2a;
        --gold-500: #c9a227;
        --gold-400: #d4af37;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
    }

    /* Base Layout & Glow */
    .resource-shell {
        position: relative;
        background: var(--bg-warm);
        min-height: 100vh;
        padding-bottom: 3rem;
    }
    .resource-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2; z-index: 0;
    }
    .resource-glow.one { top: -50px; left: -50px; width: 250px; height: 250px; background: var(--gold-400); }
    .resource-glow.two { bottom: 10%; right: -50px; width: 220px; height: 220px; background: var(--maroon-800); }

    /* Glass Cards */
    .panel-card {
        position: relative; z-index: 1; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .panel-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    /* Header Specifics */
    .page-header h1 { color: var(--text-primary); font-weight: 700; letter-spacing: -0.02em; }
    .page-header p { color: var(--text-secondary); }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; font-weight: 600; border-radius: 0.6rem;
        padding: 0.6rem 1rem; display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(92,26,26,0.15); transition: all 0.2s ease;
        border: none; text-decoration: none; white-space: nowrap;
    }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    /* Table Styling */
    .table-container { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 800px; }
    .custom-table thead th {
        background: rgba(250,248,245,0.8); color: var(--text-muted);
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
        padding: 0.75rem 1rem; border-bottom: 1px solid var(--border-soft);
        text-align: left;
    }
    .custom-table tbody td {
        padding: 0.85rem 1rem; border-bottom: 1px solid rgba(229, 224, 219, 0.5);
        color: var(--text-secondary); font-size: 0.8rem; vertical-align: middle;
    }
    .custom-table tbody tr:last-child td { border-bottom: none; }
    .custom-table tbody tr:hover { background: rgba(254,249,231,0.3); }

    /* Resource Icon & Info */
    .resource-icon-box {
        width: 2.5rem; height: 2.5rem; border-radius: 50%;
        background: rgba(250,248,245,0.8); border: 1px solid var(--border-soft);
        display: flex; align-items: center; justify-content: center;
        color: var(--maroon-700); font-size: 1rem; flex-shrink: 0;
    }
    .resource-title { font-weight: 600; color: var(--text-primary); font-size: 0.85rem; }
    .resource-desc { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.1rem; max-width: 200px; }

    /* Badges */
    .badge-category {
        display: inline-flex; align-items: center; padding: 0.25rem 0.6rem;
        border-radius: 999px; font-size: 0.65rem; font-weight: 600; text-transform: capitalize;
        background: rgba(229, 231, 235, 0.6); color: var(--maroon-800);
        border: 1px solid var(--border-soft);
    }
    
    .status-toggle-btn {
        display: inline-flex; align-items: center; padding: 0.25rem 0.6rem;
        border-radius: 999px; font-size: 0.65rem; font-weight: 600;
        border: none; cursor: pointer; transition: all 0.2s ease;
        background: rgba(209, 250, 229, 0.8); color: #047857;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    .status-toggle-btn.inactive {
        background: rgba(254, 226, 226, 0.8); color: #b91c1c;
        border: 1px solid rgba(185, 28, 28, 0.2);
    }
    .status-toggle-btn:hover { transform: translateY(-1px); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

    /* Links */
    .link-text {
        color: var(--maroon-700); font-weight: 500; font-size: 0.8rem;
        display: inline-flex; align-items: center; gap: 0.3rem;
        transition: color 0.2s;
    }
    .link-text:hover { color: var(--maroon-900); }
    .link-sub { font-size: 0.65rem; color: var(--text-muted); display: block; margin-top: 0.1rem; max-width: 150px; }

    /* Action Icons */
    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 2rem; height: 2rem; border-radius: 0.5rem;
        transition: all 0.2s ease; background: transparent; border: none; cursor: pointer;
    }
    .action-edit { color: var(--maroon-700); } .action-edit:hover { background: rgba(122, 42, 42, 0.1); color: var(--maroon-900); }
    .action-delete { color: #b91c1c; } .action-delete:hover { background: rgba(185, 28, 28, 0.1); color: #991b1b; }

    /* Empty State */
    .empty-state { padding: 3rem 1rem; text-align: center; }
    .empty-icon { color: var(--border-soft); font-size: 3.5rem; margin-bottom: 1rem; }
    .empty-title { color: var(--text-secondary); font-weight: 600; font-size: 1.1rem; }
    .empty-text { color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem; }

    /* Alerts (Styled for Theme) */
    .alert-toast {
        position: fixed; top: 1rem; right: 1rem; z-index: 50;
        padding: 0.75rem 1rem; border-radius: 0.6rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 0.75rem;
        font-weight: 500; font-size: 0.85rem;
        animation: slideIn 0.3s ease-out;
    }
    .alert-success { background: white; border-left: 4px solid #059669; color: #047857; }
    .alert-error { background: white; border-left: 4px solid #b91c1c; color: #b91c1c; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

    /* Mobile Adjustments */
    @media (max-width: 639px) {
        .header-actions { flex-direction: column; width: 100%; }
        .header-actions .btn-primary { width: 100%; }
        .custom-table { font-size: 0.75rem; }
        .custom-table thead th, .custom-table tbody td { padding: 0.6rem 0.5rem; }
        .resource-icon-box { width: 2rem; height: 2rem; font-size: 0.8rem; }
        .resource-desc { max-width: 120px; }
        .link-sub { max-width: 80px; }
        .action-btn { width: 1.75rem; height: 1.75rem; }
    }
</style>

<div class="min-h-screen resource-shell">
    <div class="resource-glow one"></div>
    <div class="resource-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-6 md:py-8">
        
        <!-- Header -->
        <div class="mb-6 panel-card p-5 sm:p-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                <div class="page-header">
                    <h1 class="text-xl sm:text-2xl font-bold">Manage Resources</h1>
                    <p class="text-sm mt-1">Create and manage mental health resources for students.</p>
                </div>
                <div class="header-actions flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <a href="{{ route('counselor.resources.create') }}"
                       class="btn-primary">
                        <i class="fas fa-plus mr-2 text-xs"></i> Add New Resource
                    </a>
                </div>
            </div>
        </div>

        <!-- Resources Table -->
        <div class="panel-card overflow-hidden">
            @if($resources->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-box-open empty-icon"></i>
                    <h3 class="empty-title">No Resources Yet</h3>
                    <p class="empty-text">Get started by creating your first resource.</p>
                    <a href="{{ route('counselor.resources.create') }}"
                       class="btn-primary">
                        Create Resource
                    </a>
                </div>
            @else
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th class="w-1/4">Resource</th>
                                <th class="w-[10%]">Category</th>
                                <th class="w-[20%]">Link</th>
                                <th class="w-[10%]">Status</th>
                                <th class="w-[5%]">Order</th>
                                <th class="w-[15%] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border-soft)]/50">
                            @foreach($resources as $resource)
                                <tr class="hover:bg-[rgba(254,249,231,0.3)] transition">
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="resource-icon-box">
                                                <i class="{{ $resource->icon }}"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="resource-title truncate">{{ $resource->title }}</div>
                                                <div class="resource-desc truncate">{{ $resource->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-category">
                                            {{ $resource->category_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($resource->link)
                                            <a href="{{ $resource->link }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="link-text"
                                               title="Open resource link">
                                                <i class="fas fa-external-link-alt text-[10px]"></i>
                                                <span>Visit Link</span>
                                            </a>
                                            <span class="link-sub truncate" title="{{ $resource->link }}">
                                                {{ Str::limit($resource->link, 30) }}
                                            </span>
                                        @else
                                            <span class="text-[var(--text-muted)] text-xs italic">No link</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('counselor.resources.update-status', $resource) }}" method="POST" class="inline status-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $resource->is_active ? 0 : 1 }}">
                                            <button type="submit"
                                                    class="status-toggle-btn {{ $resource->is_active ? '' : 'inactive' }}">
                                                {{ $resource->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="font-mono text-xs text-[var(--text-muted)]">
                                        {{ $resource->order }}
                                    </td>
                                    <td class="text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('counselor.resources.edit', $resource) }}"
                                               class="action-btn action-edit"
                                               title="Edit">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>

                                            <form action="{{ route('counselor.resources.destroy', $resource) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn action-delete"
                                                        onclick="return confirm('Are you sure you want to delete this resource?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash text-xs"></i>
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
    // Auto-dismiss alerts (Styled for Theme)
    document.addEventListener('DOMContentLoaded', function() {
        // Note: Assuming Laravel session flashes are rendered somewhere in layout or here
        // If not present in HTML, this script waits for them. 
        // For this specific view, we assume standard Laravel flash messages might be injected.
        // If you use a specific alert component, ensure it has these classes or adapt selector.
        const alerts = document.querySelectorAll('.alert-toast'); 
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

    // Handle status updates with AJAX
    document.addEventListener('DOMContentLoaded', function() {
        const statusForms = document.querySelectorAll('.status-form');

        statusForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const button = this.querySelector('button');
                const originalText = button.innerText;
                
                // Optional: Add loading state
                button.disabled = true;
                button.style.opacity = '0.7';

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
                        // Reload the page to show updated status and styling
                        window.location.reload();
                    } else {
                        button.disabled = false;
                        button.style.opacity = '1';
                        alert('Failed to update status.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.disabled = false;
                    button.style.opacity = '1';
                    window.location.reload();
                });
            });
        });
    });
</script>
@endsection