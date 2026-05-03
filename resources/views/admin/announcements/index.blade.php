@extends('layouts.admin')

@section('title', 'Admin Dashboard - OGC')

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

    .announce-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .announce-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .announce-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .announce-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .summary-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover, .summary-card:hover { 
        box-shadow: 0 4px 14px rgba(44,36,32,0.06); 
    }
    .hero-card::before, .panel-card::before, .glass-card::before, .summary-card::before {
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
        min-width: 200px;
    }
    .summary-card::before {
        content: ""; position: absolute; inset: 0; opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
    }
    .summary-icon {
        width: 2.5rem; height: 2.5rem; border-radius: 0.75rem; display: flex;
        align-items: center; justify-content: center; background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1); color: #fef9e7; flex-shrink: 0;
    }
    .summary-label { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.7); }
    .summary-value { font-size: 1.5rem; line-height: 1; font-weight: 800; margin-top: 0.35rem; }
    .summary-subtext { font-size: 0.7rem; color: rgba(255,255,255,0.8); margin-top: 0.25rem; }

    .stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        border: 1px solid rgba(229, 224, 219, 0.8);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        padding: 0.85rem !important;
        box-shadow: 0 2px 8px rgba(44, 36, 32, 0.04);
        transition: all 0.2s ease;
        display: block !important;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(44, 36, 32, 0.06);
    }

    .stat-card-pattern {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at top right, rgba(212, 175, 55, 0.06), transparent 30%);
        pointer-events: none;
    }
    .stat-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .stat-label {
        font-size: 0.6rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #8b7e76;
        margin-bottom: 0.15rem;
    }
    .stat-value {
        font-size: 1.1rem;
        line-height: 1;
        font-weight: 700;
        color: #2c2420;
    }

    .primary-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { 
        width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; 
        align-items: center; justify-content: center; 
        background: rgba(254,249,231,0.7); color: var(--maroon-700); 
    }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .alert-success {
        display: flex; align-items: center; gap: 0.5rem;
        border: 1px solid rgba(16,185,129,0.3); background: rgba(240,253,244,0.9);
        border-radius: 0.6rem; padding: 0.75rem 1rem; color: #065f46;
        font-size: 0.8rem; font-weight: 500;
    }

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
        padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.4);
    }
    .table-live-pill {
        display: inline-flex; align-items: center; font-size: 0.65rem; color: var(--text-secondary);
        background: rgba(250,248,245,0.6); border: 1px solid var(--border-soft); padding: 0.25rem 0.5rem;
        border-radius: 999px; font-weight: 500;
    }
    .table-row { transition: background-color 0.15s ease; }
    .table-row:hover { background: rgba(254,249,231,0.35); }

    .status-chip {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.2rem 0.45rem; border-radius: 999px;
        font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;
    }
    .status-chip.maroon { background: rgba(253,242,242,0.9); color: #7a2a2a; border: 1px solid rgba(185,28,28,0.25); }
    .status-chip.gold { background: rgba(254,249,231,0.9); color: #7a2a2a; border: 1px solid rgba(212,175,55,0.3); }
    .status-chip.green { background: rgba(240,253,244,0.9); color: #065f46; border: 1px solid rgba(16,185,129,0.25); }
    .status-chip.gray { background: rgba(245,240,235,0.9); color: var(--text-secondary); border: 1px solid var(--border-soft); }

    .action-icon {
        display: inline-flex; align-items: center; justify-content: center;
        width: 1.75rem; height: 1.75rem; border-radius: 0.5rem;
        color: var(--text-secondary); transition: all 0.18s ease;
        font-size: 0.75rem;
    }
    .action-icon:hover { transform: translateY(-1px); color: var(--maroon-700); background: rgba(254,249,231,0.6); }
    .action-icon.danger:hover { color: #b91c1c; background: rgba(253,242,242,0.8); }
    .action-icon.success:hover { color: #059669; background: rgba(240,253,244,0.8); }
    .action-icon.warning:hover { color: #d97706; background: rgba(254,249,231,0.9); }

    .empty-state {
        text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);
    }
    .empty-state-icon {
        width: 4rem; height: 4rem; border-radius: 1rem;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(254,249,231,0.7); color: var(--maroon-700);
        margin-bottom: 1rem; font-size: 1.25rem;
    }

    .pagination-shell { padding: 0.75rem 1.25rem; border-top: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.4); }

    @media (max-width: 639px) {
        .input-field, .select-field { padding: 0.5rem 0.7rem; font-size: 0.85rem; }
        .panel-header { padding: 0.75rem 1rem; }
        .primary-btn { width: 100%; justify-content: center; }
        .summary-card { flex-direction: column; text-align: center; }
        .summary-card .flex { flex-direction: column; gap: 0.75rem !important; }
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .action-icon { width: 2rem; height: 2rem; }
    }
</style>

<div class="min-h-screen announce-shell">
    <div class="announce-glow one"></div>
    <div class="announce-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-bullhorn text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Outreach
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Manage Announcements</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Create and manage your announcements for students
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
                                <p class="summary-value">Create Announcement</p>
                                <p class="summary-subtext hidden sm:block">Publish a new message to students.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.announcements.create') }}" class="primary-btn px-5 py-2.5 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Create Announcement</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="stat-card group">
                <div class="stat-card-pattern"></div>
                <div class="relative flex items-center gap-2.5 sm:gap-3">
                    <div class="stat-icon bg-[#fdf2f2] text-[#7a2a2a] group-hover:bg-[#fce4e4]">
                        <i class="fas fa-bullhorn text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <p class="stat-label">Total</p>
                        <p class="stat-value">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card group">
                <div class="stat-card-pattern"></div>
                <div class="relative flex items-center gap-2.5 sm:gap-3">
                    <div class="stat-icon bg-[#ecfdf5] text-[#059669] group-hover:bg-[#d1fae5]">
                        <i class="fas fa-circle-play text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <p class="stat-label">Active</p>
                        <p class="stat-value">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card group">
                <div class="stat-card-pattern"></div>
                <div class="relative flex items-center gap-2.5 sm:gap-3">
                    <div class="stat-icon bg-[#fffbeb] text-[#b45309] group-hover:bg-[#fef3d1]">
                        <i class="fas fa-clock text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <p class="stat-label">Scheduled</p>
                        <p class="stat-value">{{ $stats['scheduled'] }}</p>
                    </div>
                </div>
            </div>

            <div class="stat-card group">
                <div class="stat-card-pattern"></div>
                <div class="relative flex items-center gap-2.5 sm:gap-3">
                    <div class="stat-icon bg-[#f8fafc] text-[#475569] group-hover:bg-[#f1f5f9]">
                        <i class="fas fa-circle-check text-sm sm:text-base"></i>
                    </div>
                    <div>
                        <p class="stat-label">Completed</p>
                        <p class="stat-value">{{ $stats['completed'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <div class="p-3 sm:p-4">
                <form method="GET" action="{{ route('admin.announcements.index') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="sm:col-span-2">
                            <label for="search" class="field-label">Search</label>
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#a89f97] text-[9px] sm:text-xs"></i>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                       placeholder="    Search title or content..."
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

        <!-- Announcements Table -->
        <div class="panel-card overflow-hidden relative">
            <div class="table-header-bar">
                <div class="flex items-center gap-3">
                    <div class="table-header-icon">
                        <i class="fas fa-bullhorn text-[#7a2a2a] text-[10px] sm:text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-medium text-[#2c2420]">Announcements List</h2>
                        <p class="text-[10px] sm:text-xs text-[#8b7e76]">Showing <span class="font-bold text-[#2c2420]">{{ $announcements->firstItem() ?? 0 }} - {{ $announcements->lastItem() ?? 0 }}</span> of <span class="font-bold text-[#2c2420]">{{ $announcements->total() }}</span></p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto -webkit-overflow-scrolling: touch;">
                <table class="w-full min-w-[850px] divide-y divide-[#e5e0db]/60">
                    <thead class="bg-[#faf8f5]/80">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Title & Content</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Author & Target</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Status</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Dates</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Created</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                        @forelse($announcements as $announcement)
                            <tr class="table-row group">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[180px] sm:max-w-[220px]">{{ $announcement->title }}</div>
                                    <div class="text-[10px] sm:text-xs text-[#8b7e76] mt-1 truncate max-w-[180px] sm:max-w-[220px]">
                                        {{ Str::limit($announcement->content, 80) }}
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="text-xs sm:text-sm font-medium text-[#2c2420]">
                                        {{ $announcement->user->first_name ?? 'System' }} {{ $announcement->user->last_name ?? 'Admin' }}
                                    </div>
                                    <div class="text-[10px] sm:text-xs text-[#8b7e76] mt-1 max-w-[150px] truncate" title="{{ $announcement->targeted_colleges }}">
                                        {{ Str::limit($announcement->targeted_colleges, 30) }}
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="status-chip {{ $announcement->status_color === 'red' ? 'maroon' : ($announcement->status_color === 'yellow' ? 'gold' : ($announcement->status_color === 'green' ? 'green' : 'gray')) }}">
                                            {{ ucfirst($announcement->status) }}
                                        </span>
                                        @if($announcement->is_active)
                                            <span class="status-chip green">Active</span>
                                        @else
                                            <span class="status-chip gray">Inactive</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-xs sm:text-sm text-[#6b5e57]">
                                    @if($announcement->start_date || $announcement->end_date)
                                        <div><strong>Start:</strong> {{ $announcement->start_date?->format('M j, Y') ?? 'Immediate' }}</div>
                                        <div><strong>End:</strong> {{ $announcement->end_date?->format('M j, Y') ?? 'No end' }}</div>
                                    @else
                                        <span class="text-[#a89f97] italic">No date restrictions</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-xs sm:text-sm text-[#6b5e57]">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-calendar-days-days text-[#7a2a2a]/60 text-[9px] sm:text-xs"></i>
                                        {{ $announcement->created_at->format('M j, Y') }}
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 sm:gap-2">
                                        <button onclick="togglePin({{ $announcement->id }}, this)"
                                                class="action-icon {{ $announcement->is_pinned ? 'text-yellow-500' : '' }}"
                                                title="{{ $announcement->is_pinned ? 'Unpin' : 'Pin to top' }}">
                                            <i class="fas fa-thumbtack {{ $announcement->is_pinned ? '' : 'opacity-40' }}"></i>
                                        </button>

                                        <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                           class="action-icon" title="Edit">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>

                                        @if($announcement->is_active)
                                            <form action="{{ route('admin.announcements.toggle-status', $announcement) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="action-icon warning"
                                                        title="Deactivate">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.announcements.complete', $announcement) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="action-icon success"
                                                        title="Mark as Completed"
                                                        onclick="return confirm('Mark this announcement as completed?')">
                                                    <i class="fas fa-circle-check"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.announcements.toggle-status', $announcement) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="action-icon success"
                                                        title="Activate">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="action-icon danger"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this announcement?')">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 sm:px-6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-bullhorn"></i>
                                        </div>
                                        <p class="text-sm sm:text-base font-medium text-[#2c2420]">No announcements found.</p>
                                        <p class="text-xs sm:text-sm text-[#8b7e76] mt-1">Create your first announcement to get started.</p>
                                        <a href="{{ route('admin.announcements.create') }}"
                                           class="primary-btn px-4 py-2 text-xs sm:text-sm rounded-lg mt-4">
                                            <i class="fas fa-plus mr-1.5 text-[9px] sm:text-xs"></i>
                                            Create Announcement
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                {{ $announcements->appends(request()->query())->links('vendor.pagination.counselor-resources') }}
            </div>
        </div>
    </div>
</div>

<script>
function togglePin(id, btn) {
    fetch(`/admin/announcements/${id}/toggle-pin`, {
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
