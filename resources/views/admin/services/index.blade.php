@extends('layouts.admin')

@section('title', 'Services - Admin Panel')

@section('content')
<style>
    .admin-shell { position: relative; overflow: hidden; background: #faf8f5; min-height: 100vh; }
    .admin-glow { position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.25; }
    .admin-glow.one { top: -30px; left: -40px; width: 200px; height: 200px; background: #d4af37; }
    .admin-glow.two { bottom: -30px; right: -60px; width: 220px; height: 220px; background: #5c1a1a; }
    .hero-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid #e5e0db; background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
    }
    .hero-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.06), transparent 30%);
    }
    .hero-icon {
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        width: 2.75rem; height: 2.75rem; border-radius: 0.75rem; color: #fef9e7;
        background: linear-gradient(135deg, #5c1a1a 0%, #7a2a2a 100%);
        box-shadow: 0 4px 12px rgba(92,26,26,0.15);
    }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 0.4rem; border-radius: 999px;
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.8);
        padding: 0.2rem 0.55rem; font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.16em; color: #7a2a2a;
    }
    .hero-badge-dot { width: 0.3rem; height: 0.3rem; border-radius: 999px; background: #d4af37; }
    .panel-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid #e5e0db; background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
    }
    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, #5c1a1a 0%, #d4af37 50%, #5c1a1a 100%); }
</style>

<div class="min-h-screen admin-shell">
    <div class="admin-glow one"></div>
    <div class="admin-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">

        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex items-start gap-3">
                    <div class="hero-icon"><i class="fas fa-concierge-bell text-base sm:text-lg"></i></div>
                    <div class="min-w-0">
                        <div class="hero-badge"><span class="hero-badge-dot"></span>Admin Panel</div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">Services</h1>
                        <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5">Manage the services shown on the dashboard (seeded in ServiceSeeder).</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="panel-card overflow-hidden">
            <div class="panel-topline"></div>
            <div class="overflow-x-auto">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#e5e0db]">
                <thead class="bg-[#faf8f5]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#8b7e76]">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#8b7e76]">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#8b7e76]">Route</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#8b7e76]">Active</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-[#8b7e76]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e5e0db]">
                    @foreach($services as $service)
                        <tr>
                            <td class="px-4 py-3 text-sm text-[#2c2420]">{{ $service->order }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 flex-shrink-0 overflow-hidden rounded-lg border border-[#e5e0db] bg-[#faf8f5]">
                                        @php
                                            $img = $service->image_url;
                                            $imgSrc = $img && preg_match('/^https?:\/\//i', $img) ? $img : ($img ? asset('storage/' . $img) : null);
                                        @endphp
                                        @if($imgSrc)
                                            <img src="{{ $imgSrc }}" alt="" class="h-full w-full object-cover" onerror="this.style.display='none'" />
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-semibold text-[#2c2420] truncate max-w-[420px]">{{ $service->title }}</div>
                                        <div class="text-xs text-[#8b7e76] truncate max-w-[520px]">{{ $service->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-[#6b5e57] font-mono">{{ $service->route_name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($service->is_active)
                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">Active</span>
                                @else
                                    <span class="inline-flex items-center rounded-full border border-[#e5e0db] bg-[#faf8f5] px-2.5 py-1 text-xs font-semibold text-[#6b5e57]">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.services.edit', $service) }}" class="inline-flex items-center rounded-lg border border-[#e5e0db] bg-white px-3 py-2 text-xs font-semibold text-[#2c2420] hover:bg-[#faf8f5]">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if($services->count() === 0)
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-[#8b7e76]">No services found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection
