@extends('layouts.admin')

@section('title', 'Resources - Admin Panel')

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

    .resources-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .resources-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .resources-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .resources-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover {
        box-shadow: 0 4px 14px rgba(44,36,32,0.06);
    }
    .hero-card::before, .panel-card::before, .glass-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .table-header-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .hero-icon {
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.16em; color: var(--maroon-700);
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: var(--gold-400); }

    .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%); color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
        pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.2rem; line-height: 1.2; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.2rem; }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); z-index: 10; }

    .table-header-bar {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.6rem;
        padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60;
        background: rgba(250,248,245,0.4);
    }
    .table-header-icon {
        width: 2rem; height: 2rem; border-radius: 0.6rem;
        background: rgba(254,249,231,0.6);
    }
    .table-live-pill {
        display: inline-flex; align-items: center; font-size: 0.65rem; color: var(--text-secondary);
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft); padding: 0.25rem 0.5rem;
        border-radius: 999px; font-weight: 500;
    }
    .table-row { transition: background-color 0.15s ease; }
    .table-row:hover { background: rgba(254,249,231,0.35); }

    .resource-icon-badge {
        width: 2.5rem; height: 2.5rem; border-radius: 0.65rem;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        background: rgba(245,240,235,0.6); box-shadow: inset 0 1px 0 rgba(255,255,255,0.4);
    }

    .category-pill {
        display: inline-flex; align-items: center; padding: 0.2rem 0.5rem; border-radius: 999px;
        background: #fffbeb; color: #7a5a1a; font-size: 0.68rem; font-weight: 600; border: 1px solid rgba(212,175,55,0.3);
        text-transform: capitalize;
    }

    .action-link { color: var(--text-secondary); transition: all 0.18s ease; }
    .action-link:hover { color: var(--maroon-800); transform: translateY(-1px); }
    .pin-active { color: #7a2a2a !important; }
    .delete-link { color: #b91c1c; transition: all 0.18s ease; }
    .delete-link:hover { color: var(--maroon-900); transform: translateY(-1px); }
    .empty-state-icon {
        width: 3.5rem; height: 3.5rem; border-radius: 999px; display: flex;
        align-items: center; justify-content: center; background: rgba(245,240,235,0.6);
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.04); margin-inline: auto;
    }

    .visit-link {
        display: inline-flex; align-items: center; color: var(--maroon-700); font-weight: 600; transition: all 0.18s ease;
    }
    .visit-link:hover { color: var(--maroon-900); transform: translateY(-1px); }
    .status-btn { transition: all 0.2s ease; }
    .status-btn:hover { transform: translateY(-1px); }

    .filter-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.35rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }
    .filter-input {
        width: 100%;
        border: 1px solid var(--border-soft);
        border-radius: 0.6rem;
        background: rgba(255,255,255,0.9);
        color: var(--text-primary);
        outline: none;
        transition: all 0.2s ease;
        font-size: 0.8rem;
        padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .filter-input:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

    @media (max-width: 639px) {
        .primary-btn { width: 100%; justify-content: center; }
        .table-header-bar { padding: 0.65rem 1rem; }
    }
</style>

<div class="min-h-screen resources-shell">
    <div class="resources-glow one"></div>
    <div class="resources-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        
        <!-- Header Card -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-book-open text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Resource Management
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Manage Resources</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Create and manage mental health resources for students.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-plus text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Quick Action</p>
                                <p class="summary-value">Add New Resource</p>
                                <p class="summary-subtext hidden sm:block">Create another resource for the student library.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.resources.create') }}"
                           class="primary-btn px-5 py-2.5 whitespace-nowrap text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i> Add New Resource
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card mb-5 sm:mb-6 overflow-hidden relative">
            <div class="panel-topline z-10"></div>
            <div class="p-4 sm:p-5">
                <form method="GET" action="{{ route('admin.resources.index') }}" class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[140px]">
                        <label class="filter-label">Search</label>
                        <div class="relative">
                            <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#a89f97] text-xs"></i>
                            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="   Title, description..." class="filter-input pl-9" />
                        </div>
                    </div>

                    <div class="min-w-[120px]">
                        <label class="filter-label">Category</label>
                        <select name="category" class="filter-input bg-white">
                            <option value="all" {{ ($category ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                            @foreach(($categories ?? []) as $key => $label)
                                <option value="{{ $key }}" {{ ($category ?? '') === (string)$key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-[110px]">
                        <label class="filter-label">Status</label>
                        <select name="status" class="filter-input bg-white">
                            <option value="all" {{ ($status ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                            <option value="active" {{ ($status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="min-w-[110px]">
                        <label class="filter-label">Pinned</label>
                        <select name="pinned" class="filter-input bg-white">
                            <option value="all" {{ ($pinned ?? 'all') === 'all' ? 'selected' : '' }}>All</option>
                            <option value="pinned" {{ ($pinned ?? '') === 'pinned' ? 'selected' : '' }}>Pinned</option>
                            <option value="unpinned" {{ ($pinned ?? '') === 'unpinned' ? 'selected' : '' }}>Unpinned</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="primary-btn px-4 py-2 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-filter mr-1.5 text-[9px] sm:text-xs"></i> Apply
                        </button>
                        <a href="{{ route('admin.resources.index') }}" class="secondary-btn px-4 py-2 text-xs sm:text-sm rounded-lg text-center">
                            <i class="fas fa-rotate-left mr-1.5 text-[9px] sm:text-xs"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="panel-card overflow-hidden relative">
            @if($resources->count() === 0)
                <div class="p-6 sm:p-10 md:p-12 text-center">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-folder-open text-[#a89f97] text-2xl sm:text-3xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-[#4a3f3a] mb-2">No Resources Yet</h3>
                    <p class="text-[#8b7e76] text-xs sm:text-sm">Get started by creating your first resource.</p>
                    <a href="{{ route('admin.resources.create') }}"
                       class="inline-flex items-center mt-4 sm:mt-5 px-5 py-2.5 primary-btn text-xs sm:text-sm rounded-lg">
                        <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i> Create Resource
                    </a>
                </div>
            @else
                <!-- Table Header Stats -->
                <div class="table-header-bar">
                    <div class="flex items-center gap-3">
                        <div class="table-header-icon">
                            <i class="fas fa-folder-open text-[#7a2a2a] text-[10px] sm:text-xs"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-[#2c2420]">Resource Library</h2>
                            <p class="text-[10px] sm:text-xs text-[#8b7e76]">Total resources: <span class="font-bold text-[#2c2420]">{{ $resources->total() }}</span></p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto -webkit-overflow-scrolling: touch;">
                    <table class="w-full min-w-[850px]">
                        <thead class="bg-[#faf8f5]/80">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Resource</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Category</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Link</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                            @foreach($resources as $resource)
                                <tr class="table-row">
                                    <td class="px-3 sm:px-6 py-3.5">
                                        <div class="flex items-center gap-2.5 sm:gap-3">
                                            <div class="resource-icon-badge">
                                                <i class="{{ $resource->icon }} text-[#7a2a2a] text-base"></i>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[140px] sm:max-w-[180px]">
                                                    {{ $resource->title }}
                                                </div>
                                                <div class="text-[10px] sm:text-xs text-[#8b7e76] truncate max-w-[140px] sm:max-w-xs">
                                                    {{ $resource->description }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 sm:px-6 py-3.5 whitespace-nowrap">
                                        <span class="category-pill">
                                            <i class="fas fa-tag mr-1 text-[8px] sm:text-xs"></i>
                                            {{ $resource->category_label }}
                                        </span>
                                    </td>

                                    <td class="px-3 sm:px-6 py-3.5">
                                        @if($resource->link)
                                            <a href="{{ $resource->link }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="visit-link text-xs sm:text-sm"
                                               title="Open resource link">
                                                <i class="fas fa-external-link-alt mr-1.5 text-[8px] sm:text-xs"></i>
                                                <span class="hidden sm:inline">Visit Link</span>
                                                <span class="sm:hidden">Open</span>
                                            </a>
                                            <div class="text-[9px] sm:text-xs text-[#a89f97] mt-1 truncate max-w-[100px] sm:max-w-xs font-mono" title="{{ $resource->link }}">
                                                {{ Str::limit($resource->link, 35) }}
                                            </div>
                                        @else
                                            <span class="text-[#a89f97] text-xs sm:text-sm italic">No link</span>
                                        @endif
                                    </td>

                                    <td class="px-3 sm:px-6 py-3.5 whitespace-nowrap">
                                        <form action="{{ route('admin.resources.update-status', $resource) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_active" value="{{ $resource->is_active ? 0 : 1 }}">
                                            <button type="submit"
                                                    class="status-btn inline-flex items-center px-2 sm:px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-semibold
                                                    {{ $resource->is_active ? 'bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30 hover:bg-[#d1fae5]' : 'bg-[#f5f0eb] text-[#6b5e57] border border-[#e5e0db]/70 hover:bg-[#e5e0db]' }}">
                                                <i class="fas {{ $resource->is_active ? 'fa-circle-check' : 'fa-circle' }} mr-1.5 text-[8px] sm:text-xs"></i>
                                                {{ $resource->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form>
                                    </td>

                                    <td class="px-3 sm:px-6 py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 sm:gap-3">
                                            <button onclick="togglePin('resource', {{ $resource->id }}, this)"
                                                    class="action-link {{ $resource->is_pinned ? 'pin-active' : '' }}"
                                                    title="{{ $resource->is_pinned ? 'Unpin' : 'Pin to top' }}">
                                                <i class="fas fa-thumbtack text-[10px] sm:text-base {{ $resource->is_pinned ? '' : 'opacity-40' }}"></i>
                                            </button>
                                            <a href="{{ route('admin.resources.edit', $resource) }}"
                                               class="action-link"
                                               title="Edit Resource">
                                                <i class="fas fa-pen-to-square text-[10px] sm:text-base"></i>
                                            </a>

                                            <form action="{{ route('admin.resources.destroy', $resource) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="delete-link"
                                                        onclick="return confirm('Are you sure you want to delete this resource?')"
                                                        title="Delete Resource">
                                                    <i class="fas fa-trash-can-alt text-[10px] sm:text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                    {{ $resources->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
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

    function togglePin(type, id, btn) {
        const url = type === 'faq'
            ? `/admin/faqs/${id}/pin`
            : `/admin/resources/${id}/pin`;

        const tokenMeta = document.querySelector('meta[name=csrf-token]');
        const csrfToken = tokenMeta ? tokenMeta.content : '{{ csrf_token() }}';

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            const icon = btn.querySelector('i');
            if (data.is_pinned) {
                btn.classList.add('pin-active');
                icon.classList.remove('opacity-40');
                btn.title = 'Unpin';
            } else {
                btn.classList.remove('pin-active');
                icon.classList.add('opacity-40');
                btn.title = 'Pin to top';
            }
        })
        .catch(() => window.location.reload());
    }
</script>
@endsection