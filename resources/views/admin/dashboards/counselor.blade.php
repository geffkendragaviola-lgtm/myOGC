@extends('layouts.admin')

@section('title', 'Counselors - Admin Panel')

@section('content')

<div class="counselors-shell relative overflow-hidden min-h-screen bg-[#faf8f5]">
    <div class="counselors-glow glow-one"></div>
    <div class="counselors-glow glow-two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        
        <!-- Header Section -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card group">
                    <div class="hero-card-pattern"></div>
                    <div class="relative flex items-start gap-3 p-4 sm:p-5">
                        <div class="hero-icon">
                            <i class="fas fa-user-doctor text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge">
                                <span class="hero-badge-dot"></span>
                                Counselor Directory
                            </div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Counselors</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                                Manage and oversee all counselor records in a cleaner, more refined admin workspace.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-card-pattern"></div>
                    <div class="relative h-full flex items-center gap-3 p-4">
                        <div class="summary-icon">
                            <i class="fas fa-address-book text-sm"></i>
                        </div>
                        <div>
                            <p class="summary-label">Total Records</p>
                            <p class="summary-value">{{ $counselors->total() }}</p>
                            <p class="summary-subtext">Live counselor directory count</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="panel-card mb-5 sm:mb-6">
            <div class="panel-topline"></div>
            <div class="p-3 sm:p-4">
                <form method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 sm:left-3.5 top-1/2 -translate-y-1/2 text-[#a89f97] text-[10px] sm:text-xs"></i>
                            <input type="text" name="search" value="{{ $search }}"
                                   placeholder="Search counselor name, email, position, credentials..."
                                   class="input-field w-full pl-8 sm:pl-9 pr-3 py-2 sm:py-2.5 text-xs sm:text-sm">
                        </div>
                    </div>

                    <div class="w-full md:w-56 lg:w-64 min-w-0">
                        <select name="college" class="input-field w-full px-3 py-2 sm:py-2.5 bg-white text-[#4a3f3a] text-xs sm:text-sm">
                            <option value="" {{ empty($college) ? 'selected' : '' }}>All Colleges</option>
                            @foreach($colleges as $c)
                                <option value="{{ $c->id }}" {{ (string)($college ?? '') === (string)$c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="search-btn w-full md:w-auto px-4 py-2 sm:py-2.5 font-medium flex items-center justify-center gap-2 text-xs sm:text-sm rounded-lg">
                            <i class="fas fa-search text-[10px] sm:text-xs"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Counselors Table Card -->
        <div class="panel-card overflow-hidden">
            <!-- Table Header Stats -->
            <div class="table-header-bar">
                <div class="flex items-center gap-2.5">
                    <div class="table-header-icon">
                        <i class="fas fa-user-doctor text-[#9a7b0a] text-[10px] sm:text-xs"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-medium text-[#2c2420]">Counselor Directory</h2>
                        <p class="text-[10px] sm:text-[11px] text-[#8b7e76]">Total records: {{ $counselors->total() }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="table-live-pill">
                        <i class="fas fa-clock mr-1 text-[9px]"></i> Live data
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-[700px] md:min-w-full divide-y divide-[#e5e0db]/60 w-full">
                    <thead class="bg-[#faf8f5]/85">
                        <tr>
                            <th class="px-4 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Counselor</th>
                            <th class="px-4 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">College</th>
                            <th class="px-4 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Position</th>
                            <th class="px-4 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Credentials</th>
                            <th class="px-4 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Created</th>
                            <th class="px-4 py-3 text-left text-[10px] sm:text-[11px] font-semibold text-[#8b7e76] uppercase tracking-[0.14em] whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-[#e5e0db]/50">
                        @forelse($counselors as $counselor)
                            <tr class="table-row group">
                                <td class="px-4 py-3 sm:py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="avatar-badge overflow-hidden" style="{{ $counselor->user->profile_picture ? 'background:none;padding:0;' : '' }}">
                                            @if($counselor->user->profile_picture)
                                                <img src="{{ asset('storage/' . $counselor->user->profile_picture) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                {{ strtoupper(substr($counselor->user->first_name, 0, 1)) }}{{ strtoupper(substr($counselor->user->last_name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs sm:text-sm font-semibold text-[#2c2420] truncate">
                                                {{ $counselor->user->first_name }} {{ $counselor->user->last_name }}
                                            </div>
                                            <div class="text-[10px] sm:text-[11px] text-[#8b7e76] font-mono break-all truncate max-w-[120px] sm:max-w-xs">
                                                {{ $counselor->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3 sm:py-3.5">
                                    <div class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-[#4a3f3a]">
                                        <span class="mini-icon bg-[#f5f0eb] text-[#8b7e76]">
                                            <i class="fas fa-school text-[10px]"></i>
                                        </span>
                                        <span class="truncate max-w-[100px] sm:max-w-[140px]">{{ $counselor->college->name ?? 'N/A' }}</span>
                                    </div>
                                </td>

                                <td class="px-4 py-3 sm:py-3.5">
                                    <span class="info-pill">
                                        <i class="fas fa-briefcase mr-1.5 text-[9px] sm:text-[10px]"></i>
                                        <span class="truncate max-w-[120px]">{{ $counselor->position ?? 'N/A' }}</span>
                                    </span>
                                </td>

                                <td class="px-4 py-3 sm:py-3.5">
                                    <div class="text-xs sm:text-sm text-[#4a3f3a]">
                                        @if($counselor->credentials)
                                            <span class="inline-flex items-center gap-1.5 max-w-[140px] truncate">
                                                <i class="fas fa-certificate text-[#c9a227] text-[10px] sm:text-xs shrink-0"></i>
                                                <span class="truncate">{{ $counselor->credentials }}</span>
                                            </span>
                                        @else
                                            <span class="text-[#c4b8b1] text-[10px] sm:text-xs">—</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-3 sm:py-3.5 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-[#8b7e76]">
                                        <span class="mini-icon bg-[#fdf2f2] text-[#7a2a2a]/60">
                                            <i class="fas fa-calendar-days-days text-[10px]"></i>
                                        </span>
                                        {{ $counselor->created_at?->format('M j, Y') ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 sm:py-3.5 whitespace-nowrap">
                                    <a href="{{ route('admin.counselors.edit', $counselor) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] sm:text-xs font-semibold text-[#7a2a2a] bg-[rgba(122,42,42,0.07)] hover:bg-[rgba(122,42,42,0.14)] transition-colors">
                                        <i class="fas fa-pen-to-square"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 sm:py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="empty-state-icon mb-3">
                                            <i class="fas fa-user-doctor-slash text-[#a89f97] text-lg"></i>
                                        </div>
                                        <p class="text-xs sm:text-sm font-medium text-[#6b5e57]">No counselors found.</p>
                                        <p class="text-[10px] sm:text-xs text-[#8b7e76] mt-1">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination Section -->
            @if($counselors->hasPages())
            <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                <div class="flex items-center justify-center">
                    <div class="pagination-wrap flex items-center gap-2 justify-center">
                        {{ $counselors->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

            <style>
                .pagination-wrap nav { display: inline-flex; }
                .pagination-wrap .relative { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
                .pagination-wrap span, .pagination-wrap a {
                    display: inline-flex; align-items: center; justify-content: center;
                    min-width: 28px; height: 28px; padding: 0 8px; border-radius: 8px;
                    font-size: 11px; font-weight: 600; transition: all 0.2s ease;
                }
                .pagination-wrap span[aria-current="page"] span {
                    background: #5c1a1a;
                    color: white;
                }
                .pagination-wrap a {
                    background: white; color: #6b5e57; border: 1px solid #e5e0db;
                }
                .pagination-wrap a:hover {
                    background: #fdf2f2; color: #5c1a1a; border-color: rgba(212, 175, 55, 0.4);
                }
            </style>
            @else
            <div class="px-4 sm:px-5 py-3 sm:py-3.5 border-t border-[#e5e0db]/60 bg-[#faf8f5]/40">
                <div class="flex items-center justify-center gap-2 text-[10px] sm:text-xs text-[#8b7e76]">
                    <i class="fas fa-circle-check text-[#059669]"></i>
                    <span>Showing all <span class="font-semibold text-[#2c2420]">{{ $counselors->total() }}</span> counselors</span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --maroon-900: #3a0c0c;
        --maroon-800: #5c1a1a;
        --maroon-700: #7a2a2a;
        --gold-400: #d4af37;
        --bg-warm: #faf8f5;
        --border-soft: #e5e0db;
        --text-primary: #2c2420;
        --text-secondary: #6b5e57;
        --text-muted: #8b7e76;
    }

    .counselors-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
    }

    .counselors-glow {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        opacity: 0.25;
    }

    .glow-one {
        top: -40px;
        left: -50px;
        width: 220px;
        height: 220px;
        background: var(--gold-400);
    }

    .glow-two {
        bottom: -40px;
        right: -80px;
        width: 260px;
        height: 260px;
        background: var(--maroon-800);
    }

    .hero-card,
    .panel-card {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        border: 1px solid var(--border-soft);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 8px rgba(44, 36, 32, 0.04);
        transition: box-shadow 0.2s ease;
    }

    .hero-card:hover,
    .panel-card:hover {
        box-shadow: 0 4px 12px rgba(44, 36, 32, 0.06);
    }

    .hero-card-pattern {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at top left, rgba(212,175,55,0.08), transparent 35%),
            radial-gradient(circle at bottom right, rgba(92,26,26,0.06), transparent 40%);
        pointer-events: none;
    }

    .hero-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
        flex-shrink: 0;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3);
        background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: var(--maroon-700);
    }

    .hero-badge-dot {
        width: 0.3rem;
        height: 0.3rem;
        border-radius: 999px;
        background: var(--gold-400);
    }

    .summary-card {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        border: 1px solid rgba(92,26,26,0.15);
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-900) 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(58,12,12,0.15);
        min-width: 200px;
    }

    .summary-card-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.15;
        background: radial-gradient(circle at top right, var(--gold-400), transparent 40%);
    }

    .summary-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fef9e7;
        flex-shrink: 0;
    }

    .summary-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: rgba(255,255,255,0.7);
    }

    .summary-value {
        font-size: 1.5rem;
        line-height: 1;
        font-weight: 800;
        margin-top: 0.35rem;
    }

    .summary-subtext {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.8);
        margin-top: 0.25rem;
    }

    .panel-topline {
        position: absolute;
        inset-inline: 0;
        top: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%);
    }

    .panel-header {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid var(--border-soft)/60;
    }

    .panel-header-icon,
    .table-header-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .panel-header-icon {
        background: rgba(254,249,231,0.7);
        color: var(--maroon-700);
    }

    .panel-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .panel-subtitle {
        font-size: 0.68rem;
        color: var(--text-muted);
        margin-top: 0.1rem;
    }

    .input-field {
        border: 1px solid var(--border-soft);
        border-radius: 0.6rem;
        background: rgba(255,255,255,0.9);
        color: var(--text-primary);
        outline: none;
        transition: all 0.2s ease;
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.02);
        font-size: 0.8rem;
    }

    .input-field:focus {
        border-color: var(--maroon-700);
        box-shadow: 0 0 0 3px rgba(92,26,26,0.08);
    }

    .search-btn {
        border-radius: 0.6rem;
        color: #fef9e7;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
        transition: all 0.2s ease;
    }

    .search-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(92,26,26,0.2);
    }

    .table-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.6rem;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid var(--border-soft)/60;
        background: rgba(250,248,245,0.4);
    }

    .table-header-icon {
        background: rgba(254,249,231,0.6);
    }

    .table-live-pill {
        display: inline-flex;
        align-items: center;
        font-size: 0.65rem;
        color: var(--text-secondary);
        background: rgba(250,248,245,0.6);
        border: 1px solid var(--border-soft);
        padding: 0.25rem 0.5rem;
        border-radius: 999px;
        font-weight: 500;
    }

    .table-row {
        transition: background-color 0.15s ease;
    }

    .table-row:hover {
        background: rgba(254,249,231,0.35);
    }

    .avatar-badge {
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 0.65rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7a2a2a;
        font-weight: 700;
        font-size: 0.7rem;
        background: linear-gradient(135deg, #fef9e7 0%, #f5e6b8 100%);
        border: 1px solid rgba(212,175,55,0.3);
        flex-shrink: 0;
    }

    .mini-icon {
        width: 1.4rem;
        height: 1.4rem;
        border-radius: 0.45rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.55rem;
        border-radius: 999px;
        background: rgba(245,240,235,0.7);
        color: var(--text-secondary);
        font-size: 0.68rem;
        font-weight: 600;
        border: 1px solid var(--border-soft)/60;
    }

    .empty-state-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(245,240,235,0.6);
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.04);
    }

    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .table-header-bar { padding: 0.65rem 1rem; }
        .avatar-badge { width: 2rem; height: 2rem; font-size: 0.65rem; }
    }
</style>

@endsection