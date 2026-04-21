@extends('layouts.admin')

@section('title', 'Users Management - Admin Panel')

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

    .users-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .users-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25;
    }
    .users-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .users-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before, .glass-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }

    .hero-icon, .panel-icon, .table-header-icon {
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
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%); pointer-events: none;
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

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon, .table-header-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; }
    .panel-icon { background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .table-header-icon { background: rgba(254,249,231,0.6); color: var(--gold-500); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    .field-label { display: block; font-size: 0.65rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.08em; }
    .input-field, .select-field {
        width: 100%; border: 1px solid var(--border-soft); border-radius: 0.6rem;
        background: rgba(255,255,255,0.9); color: var(--text-primary); outline: none;
        transition: all 0.2s ease; font-size: 0.8rem; padding: 0.55rem 0.75rem;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
    }
    .input-field:focus, .select-field:focus { border-color: var(--maroon-700); box-shadow: 0 0 0 3px rgba(92,26,26,0.08); }

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

    .avatar-badge {
        flex-shrink: 0; height: 2.5rem; width: 2.5rem; border-radius: 0.65rem;
        display: flex; align-items: center; justify-content: center; color: var(--maroon-700);
        font-weight: 700; font-size: 0.75rem; background: rgba(254,249,231,0.6);
        border: 1px solid rgba(212,175,55,0.3);
    }
    .detail-chip {
        display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.25rem 0.5rem;
        border-radius: 999px; background: rgba(245,240,235,0.7); border: 1px solid var(--border-soft);
        color: var(--text-secondary); font-size: 0.68rem; font-weight: 600;
    }
    .action-link { color: var(--text-secondary); transition: all 0.18s ease; }
    .action-link:hover { color: var(--maroon-700); transform: translateY(-1px); }

    .pagination-shell { padding: 0.75rem 1.25rem; border-top: 1px solid var(--border-soft)/60; background: rgba(250,248,245,0.4); }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .input-field, .select-field { padding: 0.6rem 0.75rem; font-size: 0.85rem; }
        .primary-btn { width: 100%; justify-content: center; }
        .avatar-badge { height: 2.25rem; width: 2.25rem; font-size: 0.7rem; }
    }
</style>

<div class="min-h-screen users-shell">
    <div class="users-glow one"></div>
    <div class="users-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-users-cog text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                User Administration
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Users Management</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Manage and oversee all user accounts across the platform.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-user-plus text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Quick Action</p>
                                <p class="summary-value">Create User</p>
                                <p class="summary-subtext hidden sm:block">Add a new student, counselor, or admin account.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.create') }}" class="primary-btn px-5 py-2.5 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-user-plus mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Create User</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <div class="panel-header">
                <div class="panel-icon"><i class="fas fa-sliders text-[9px] sm:text-xs"></i></div>
                <div>
                    <h2 class="panel-title">Search and Filter</h2>
                    <p class="panel-subtitle hidden sm:block">Find users by name, email, or role.</p>
                </div>
            </div>

            <div class="p-3 sm:p-4 md:p-5">
                <form method="GET" class="flex flex-col md:flex-row gap-3 sm:gap-4">
                    <div class="flex-1 min-w-0">
                        <label class="field-label">Search</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-[#a89f97] text-xs"></i>
                            <input type="text" name="search" value="{{ $search }}"
                                   placeholder="Search by name or email..."
                                   class="input-field pl-9 sm:pl-11 text-xs sm:text-sm">
                        </div>
                    </div>

                    <div class="w-full md:w-48 lg:w-56">
                        <label class="field-label">Role</label>
                        <select name="role" class="select-field text-xs sm:text-sm">
                            <option value="all" {{ $role === 'all' ? 'selected' : '' }}>All Roles</option>
                            <option value="student" {{ $role === 'student' ? 'selected' : '' }}>Students</option>
                            <option value="counselor" {{ $role === 'counselor' ? 'selected' : '' }}>Counselors</option>
                            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admins</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="primary-btn w-full md:w-auto px-5 py-2.5 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-search mr-1.5 text-[9px] sm:text-xs"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="panel-card overflow-hidden">
            <div class="table-header-bar">
                <div class="flex items-center gap-3">
                    <div class="table-header-icon">
                        <i class="fas fa-users text-[9px] sm:text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-medium text-[#2c2420]">User List</h2>
                        <p class="text-[10px] sm:text-xs text-[#8b7e76]">Total: {{ $users->total() }} users</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="table-live-pill">
                        <i class="fas fa-clock mr-1 text-[9px]"></i> Last updated recently
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto -webkit-overflow-scrolling: touch;">
                <table class="w-full min-w-[750px] divide-y divide-[#e5e0db]/60">
                    <thead class="bg-[#faf8f5]/80">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">User</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Role</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Details</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Created</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-[10px] sm:text-xs font-semibold text-[#8b7e76] uppercase tracking-wider whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                        @foreach($users as $user)
                        <tr class="table-row group">
                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-2.5 sm:gap-3">
                                    <div class="avatar-badge overflow-hidden" style="{{ $user->profile_picture ? 'background:none;padding:0;' : '' }}">
                                        @if($user->profile_picture)
                                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate max-w-[120px] sm:max-w-[160px]">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </div>
                                        <div class="text-[10px] sm:text-xs text-[#8b7e76] font-mono truncate max-w-[120px] sm:max-w-[160px]">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                <span class="px-2 sm:px-3 py-1 inline-flex items-center text-[10px] sm:text-xs font-semibold rounded-full
                                    {{ $user->role === 'admin' ? 'bg-[#fdf2f2] text-[#b91c1c] border border-[#b91c1c]/30' :
                                       ($user->role === 'counselor' ? 'bg-[#fffbeb] text-[#7a2a2a] border border-[#d4af37]/30' :
                                       'bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30') }}">
                                    <i class="fas {{ $user->role === 'admin' ? 'fa-shield-halved' : ($user->role === 'counselor' ? 'fa-user-doctor' : 'fa-user-graduate') }} mr-1.5 text-[9px] sm:text-xs"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>

                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-xs sm:text-sm text-[#6b5e57]">
                                @if($user->role === 'student' && $user->student)
                                    <span class="detail-chip">
                                        <i class="fas fa-id-card text-[#7a2a2a] text-[9px] sm:text-xs"></i>
                                        <span>{{ $user->student->student_id }}</span>
                                    </span>
                                @elseif($user->role === 'counselor' && $user->counselor)
                                    <span class="detail-chip">
                                        <i class="fas fa-briefcase text-[#c9a227] text-[9px] sm:text-xs"></i>
                                        <span>{{ $user->counselor->position }}</span>
                                    </span>
                                @elseif($user->role === 'admin' && $user->admin)
                                    <span class="detail-chip">
                                        <i class="fas fa-key text-[#7a2a2a] text-[9px] sm:text-xs"></i>
                                        <span>{{ $user->admin->credentials }}</span>
                                    </span>
                                @else
                                    <span class="text-[#a89f97] text-xs italic">—</span>
                                @endif
                            </td>

                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-xs sm:text-sm text-[#6b5e57]">
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-calendar-days-days text-[#7a2a2a]/60 text-[9px] sm:text-xs"></i>
                                    {{ $user->created_at->format('M j, Y') }}
                                </div>
                            </td>

                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="action-link" title="Edit User">
                                        <i class="fas fa-pen-to-square text-xs sm:text-sm"></i>
                                    </a>
                                    <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link text-[#b91c1c] hover:text-[#7a2a2a]" title="Delete User">
                                            <i class="fas fa-trash-can-alt text-xs sm:text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="pagination-shell">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2 text-[10px] sm:text-xs text-[#8b7e76]">
                        <i class="fas fa-database text-[#a89f97]"></i>
                        <span>Showing
                            <span class="font-semibold text-[#2c2420]">{{ $users->firstItem() ?? 0 }}</span>
                            to
                            <span class="font-semibold text-[#2c2420]">{{ $users->lastItem() ?? 0 }}</span>
                            of
                            <span class="font-semibold text-[#2c2420]">{{ $users->total() }}</span>
                            results
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-2">
                        @if ($users->onFirstPage())
                            <span class="px-3 py-1.5 text-[10px] sm:text-sm text-[#a89f97] bg-[#f5f0eb] rounded-lg cursor-not-allowed flex items-center gap-2">
                                <i class="fas fa-chevron-left text-[9px]"></i>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1.5 text-[10px] sm:text-sm text-[#4a3f3a] bg-white border border-[#e5e0db] rounded-lg hover:bg-[#fdf2f2] hover:text-[#7a2a2a] hover:border-[#d4af37]/40 transition-all duration-200 flex items-center gap-2">
                                <i class="fas fa-chevron-left text-[9px]"></i>
                            </a>
                        @endif

                        <div class="flex items-center gap-1.5">
                            @php
                                $currentPage = $users->currentPage();
                                $lastPage = $users->lastPage();
                                $start = max(1, $currentPage - 2);
                                $end = min($lastPage, $currentPage + 2);

                                if ($start > 1) {
                                    echo '<a href="' . $users->url(1) . '" class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center text-[10px] sm:text-sm font-medium text-[#4a3f3a] bg-white border border-[#e5e0db] rounded-lg hover:bg-[#fdf2f2] hover:text-[#7a2a2a] hover:border-[#d4af37]/40 transition-all duration-200">1</a>';
                                    if ($start > 2) {
                                        echo '<span class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center text-[10px] sm:text-sm text-[#a89f97]">...</span>';
                                    }
                                }

                                for ($i = $start; $i <= $end; $i++) {
                                    if ($i == $currentPage) {
                                        echo '<span class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center text-[10px] sm:text-sm font-semibold text-white bg-gradient-to-r from-[#5c1a1a] to-[#7a2a2a] rounded-lg shadow-sm">' . $i . '</span>';
                                    } else {
                                        echo '<a href="' . $users->url($i) . '" class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center text-[10px] sm:text-sm font-medium text-[#4a3f3a] bg-white border border-[#e5e0db] rounded-lg hover:bg-[#fdf2f2] hover:text-[#7a2a2a] hover:border-[#d4af37]/40 transition-all duration-200">' . $i . '</a>';
                                    }
                                }

                                if ($end < $lastPage) {
                                    if ($end < $lastPage - 1) {
                                        echo '<span class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center text-[10px] sm:text-sm text-[#a89f97]">...</span>';
                                    }
                                    echo '<a href="' . $users->url($lastPage) . '" class="w-7 h-7 sm:w-9 sm:h-9 flex items-center justify-center text-[10px] sm:text-sm font-medium text-[#4a3f3a] bg-white border border-[#e5e0db] rounded-lg hover:bg-[#fdf2f2] hover:text-[#7a2a2a] hover:border-[#d4af37]/40 transition-all duration-200">' . $lastPage . '</a>';
                                }
                            @endphp
                        </div>

                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1.5 text-[10px] sm:text-sm text-[#4a3f3a] bg-white border border-[#e5e0db] rounded-lg hover:bg-[#fdf2f2] hover:text-[#7a2a2a] hover:border-[#d4af37]/40 transition-all duration-200 flex items-center gap-2">
                                <i class="fas fa-chevron-right text-[9px]"></i>
                            </a>
                        @else
                            <span class="px-3 py-1.5 text-[10px] sm:text-sm text-[#a89f97] bg-[#f5f0eb] rounded-lg cursor-not-allowed flex items-center gap-2">
                                <i class="fas fa-chevron-right text-[9px]"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @else
            <div class="pagination-shell">
                <div class="flex items-center justify-center gap-2 text-[10px] sm:text-xs text-[#8b7e76]">
                    <i class="fas fa-circle-check text-[#059669]"></i>
                    <span>Showing all <span class="font-semibold text-[#2c2420]">{{ $users->total() }}</span> users</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection