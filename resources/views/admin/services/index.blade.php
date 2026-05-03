@extends('layouts.admin')

@section('title', 'Services - Admin Panel')

@section('content')
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

    .services-shell { position: relative; overflow: hidden; background: var(--bg-warm); min-height: 100vh; }
    .services-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25; }
    .services-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: var(--gold-400); }
    .services-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: var(--maroon-800); }

    .hero-card, .panel-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .hero-card::before, .panel-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .hero-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
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
        display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem;
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
        padding: 0.5rem 0.75rem;
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }

    .table-header-bar {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.6rem;
        padding: 0.75rem 1.25rem; border-bottom: 1px solid rgba(229,224,219,0.6);
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

    .action-link { color: var(--text-secondary); transition: all 0.18s ease; }
    .action-link:hover { color: var(--maroon-700); transform: translateY(-1px); }

    .service-card {
        background: linear-gradient(180deg, #fffdfb, #faf6f0);
        border: 1px solid var(--border-soft);
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(44,36,32,0.05);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
    }
    .service-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(44,36,32,0.1);
        border-color: rgba(143,29,29,0.16);
    }
    .service-card-img {
        width: 100%; height: 11rem; object-fit: contain;
        background-color: rgba(0,0,0,0.15);
        transition: transform 0.4s ease;
    }
    .service-card:hover .service-card-img { transform: scale(1.04); }
    .service-card-img-placeholder {
        width: 100%; height: 11rem;
        background: linear-gradient(135deg, rgba(92,26,26,0.06), rgba(212,175,55,0.08));
        display: flex; align-items: center; justify-content: center;
        color: var(--text-muted); font-size: 2rem;
    }

    .empty-state-icon {
        width: 3rem; height: 3rem; border-radius: 999px; display: flex;
        align-items: center; justify-content: center; background: rgba(245,240,235,0.6);
        box-shadow: inset 0 1px 2px rgba(44,36,32,0.04); margin-inline: auto;
    }
</style>

<div class="min-h-screen services-shell">
    <div class="services-glow one"></div>
    <div class="services-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-4 items-stretch">
                <div class="hero-card">
                    <div class="relative p-4 sm:p-5 flex items-start gap-3">
                        <div class="hero-icon">
                            <i class="fas fa-concierge-bell text-base sm:text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="hero-badge"><span class="hero-badge-dot"></span>Admin Panel</div>
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Services</h1>
                            <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">Manage the services shown on the dashboard.</p>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="relative h-full flex flex-col sm:flex-row items-center justify-between gap-3 p-4">
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-layer-group text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Total Services</p>
                                <p class="summary-value">{{ $services->count() }}</p>
                                <p class="summary-subtext hidden sm:block">All entries in the system.</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-center sm:text-left">
                            <div class="summary-icon flex-shrink-0">
                                <i class="fas fa-eye text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="summary-label">Visible Now</p>
                                <p class="summary-value">{{ $services->where('is_active', true)->count() }}</p>
                                <p class="summary-subtext hidden sm:block">Shown on dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-card overflow-hidden">
            <div class="panel-topline"></div>

            <div class="p-3 sm:p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3 sm:gap-4">
                    @forelse($services as $service)
                        @php
                            $img = $service->image_url;
                            $imgSrc = $img && preg_match('/^https?:\/\//i', $img) ? $img : ($img ? asset('storage/' . $img) : null);
                        @endphp
                        <div class="service-card">
                            <div class="overflow-hidden">
                                @if($imgSrc)
                                    <img src="{{ $imgSrc }}" alt="{{ $service->title }}" class="service-card-img" onerror="this.style.display='none'" />
                                @else
                                    <div class="service-card-img-placeholder">
                                        <i class="fas fa-concierge-bell"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 sm:p-5">
                                <h3 class="text-sm sm:text-base font-bold text-[#2c2420] mb-1.5">{{ $service->title }}</h3>
                                <p class="text-xs sm:text-sm text-[#6b5e57] line-clamp-3 mb-4">{{ $service->description }}</p>

                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-semibold
                                        {{ $service->is_active ? 'bg-[#ecfdf5] text-[#059669] border border-[#10b981]/30' : 'bg-[#f5f0eb] text-[#6b5e57] border border-[#e5e0db]/70' }}">
                                        <i class="fas {{ $service->is_active ? 'fa-circle-check' : 'fa-circle' }} mr-1.5 text-[9px]"></i>
                                        {{ $service->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <a href="{{ route('admin.services.edit', $service) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] sm:text-xs font-semibold text-[#7a2a2a] bg-[rgba(122,42,42,0.07)] hover:bg-[rgba(122,42,42,0.14)] transition-colors">
                                        <i class="fas fa-pen-to-square"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-2 xl:col-span-3 text-center py-8 sm:py-10">
                            <div class="empty-state-icon mb-3">
                                <i class="fas fa-concierge-bell text-[#a89f97] text-xl sm:text-2xl"></i>
                            </div>
                            <p class="text-sm font-semibold text-[#4a3f3a]">No Services Yet</p>
                            <p class="text-xs text-[#8b7e76] mt-1">No services have been added yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
