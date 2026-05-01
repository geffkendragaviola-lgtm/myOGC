@extends('layouts.admin')

@section('title', 'FAQs - Admin Panel')

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

    .faqs-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .faqs-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .faqs-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .faqs-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

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

    .hero-icon, .panel-icon {
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
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.25rem; }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }

    .field-label {
        display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary);
        margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em;
    }
    .input-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
        padding: 0.55rem 0.75rem;
    }
    .input-field:focus, .select-field:focus {
        border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }

    .table-header-bar {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.6rem;
        padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60;
        background: rgba(250,248,245,0.4);
    }
    .table-header-icon {
        width: 1.75rem; height: 1.75rem; border-radius: 0.6rem; display: flex;
        align-items: center; justify-content: center; background: rgba(254,249,231,0.6); flex-shrink: 0;
    }
    .table-live-pill {
        display: inline-flex; align-items: center; font-size: 0.65rem; color: var(--text-secondary);
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft); padding: 0.25rem 0.5rem;
        border-radius: 999px; font-weight: 500;
    }
    .table-row { transition: background-color 0.15s ease; }
    .table-row:hover { background: rgba(254,249,231,0.35); }

    .faq-category-pill {
        display: inline-flex; align-items: center; padding: 0.2rem 0.5rem; border-radius: 999px;
        background: #fffbeb; color: #7a5a1a; font-size: 0.68rem; font-weight: 600; border: 1px solid rgba(212,175,55,0.3);
    }
    .action-link { color: var(--text-secondary); transition: all 0.18s ease; }
    .action-link:hover { color: var(--maroon-700); transform: translateY(-1px); }
    .delete-link { color: #b91c1c; transition: all 0.18s ease; }
    .delete-link:hover { color: var(--maroon-900); transform: translateY(-1px); }

    .empty-state-icon {
        width: 3rem; height: 3rem; border-radius: 999px; display: flex;
        align-items: center; justify-content: center; background: rgba(245,240,235,0.6);
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.04); margin-inline: auto;
    }

    @media (max-width: 639px) {
        .input-field, .select-field { padding: 0.5rem 0.7rem; font-size: 0.85rem; }
        .primary-btn { width: 100%; justify-content: center; }
        .table-header-bar { padding: 0.65rem 1rem; }
    }
</style>

<div class="min-h-screen faqs-shell">
    <div class="faqs-glow one"></div>
    <div class="faqs-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-circle-question text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                FAQ Management
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Frequently Asked Questions</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Create and manage questions and answers shown to users.
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
                                <p class="summary-value">Add FAQ</p>
                                <p class="summary-subtext hidden sm:block">Create a new entry for the FAQ library.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.faqs.create') }}"
                           class="primary-btn px-5 py-2.5 whitespace-nowrap text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i> Add FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <div class="p-3 sm:p-4">
                <form method="GET" action="{{ route('admin.faqs.index') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="sm:col-span-2">
                            <label for="search" class="field-label">Search</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#a89f97] text-[9px] sm:text-xs"></i>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                       placeholder="    Search question, answer, or category..."
                                       class="input-field pl-8 sm:pl-9 text-xs sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="status" class="field-label">Status</label>
                            <select id="status" name="status" class="select-field bg-white text-[#4a3f3a] text-xs sm:text-sm">
                                <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="primary-btn w-full px-4 py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg">
                                <i class="fas fa-filter text-[9px] sm:text-xs mr-1.5"></i>
                                <span>Filter</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel-card overflow-hidden">
            @if($faqs->count() === 0)
                <div class="p-6 sm:p-8 text-center">
                    <div class="empty-state-icon mb-3">
                        <i class="fas fa-circle-question text-[#a89f97] text-xl sm:text-2xl"></i>
                    </div>
                    <h3 class="text-base sm:text-lg font-semibold text-[#4a3f3a] mb-1.5">No FAQs Yet</h3>
                    <p class="text-[#8b7e76] text-xs sm:text-sm">Create your first FAQ to help users.</p>
                    <a href="{{ route('admin.faqs.create') }}"
                       class="inline-flex items-center mt-4 px-4 py-2.5 primary-btn text-xs sm:text-sm rounded-lg">
                        <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i> Create FAQ
                    </a>
                </div>
            @else
                <div class="table-header-bar">
                    <div class="flex items-center gap-2.5">
                        <div class="table-header-icon">
                            <i class="fas fa-circle-question text-[#7a2a2a] text-[9px] sm:text-xs"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-[#2c2420]">FAQ Library</h2>
                            <p class="text-[10px] sm:text-[11px] text-[#8b7e76]">Total FAQs: {{ $faqs->total() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="table-live-pill">
                            <i class="fas fa-clock mr-1 text-[9px]"></i> Live data
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] sm:min-w-[900px]">
                        <thead class="bg-[#faf8f5]/85">
                            <tr>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Question</th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Category</th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Status</th>
                                <th class="px-3 sm:px-4 py-2.5 sm:py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                            @foreach($faqs as $faq)
                                <tr class="table-row">
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5">
                                        <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[200px] sm:max-w-none">{{ Str::limit($faq->question, 140) }}</div>
                                        <div class="text-[10px] sm:text-xs text-[#8b7e76] mt-1.5 leading-relaxed line-clamp-2">{{ Str::limit($faq->answer, 160) }}</div>
                                    </td>
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5 whitespace-nowrap">
                                        @if($faq->category)
                                            <span class="faq-category-pill">
                                                <i class="fas fa-tag mr-1.5 text-[9px] sm:text-[10px]"></i>
                                                <span class="truncate max-w-[100px]">{{ $faq->category }}</span>
                                            </span>
                                        @else
                                            <span class="text-[#a89f97] text-[10px] sm:text-xs italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold 
                                            {{ $faq->is_active ? 'bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30' : 'bg-[#f5f0eb] text-[#6b5e57] border border-[#e5e0db]/70' }}">
                                            <i class="fas {{ $faq->is_active ? 'fa-circle-check' : 'fa-circle' }} mr-1.5 text-[9px] sm:text-[10px]"></i>
                                            {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-4 py-2.5 sm:py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 sm:gap-2.5">
                                            <button onclick="togglePin('faq', {{ $faq->id }}, this)"
                                                    class="action-link {{ $faq->is_pinned ? 'text-yellow-500' : '' }}"
                                                    title="{{ $faq->is_pinned ? 'Unpin' : 'Pin to top' }}">
                                                <i class="fas fa-thumbtack text-[10px] sm:text-sm {{ $faq->is_pinned ? 'rotate-0' : 'opacity-40' }}"></i>
                                            </button>
                                            <a href="{{ route('admin.faqs.edit', $faq) }}" 
                                               class="action-link" 
                                               title="Edit FAQ">
                                                <i class="fas fa-pen-to-square text-[10px] sm:text-sm"></i>
                                            </a>
                                            <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="delete-link"
                                                        onclick="return confirm('Are you sure you want to delete this FAQ?')" 
                                                        title="Delete FAQ">
                                                    <i class="fas fa-trash-can-alt text-[10px] sm:text-sm"></i>
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
                    {{ $faqs->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function togglePin(type, id, btn) {
    fetch(`/admin/faqs/${id}/pin`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const icon = btn.querySelector('i');
        if (data.is_pinned) {
            btn.classList.add('text-yellow-500');
            icon.classList.remove('opacity-40');
            btn.title = 'Unpin';
        } else {
            btn.classList.remove('text-yellow-500');
            icon.classList.add('opacity-40');
            btn.title = 'Pin to top';
        }
    });
}
</script>
@endsection
