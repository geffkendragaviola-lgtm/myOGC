@extends('layouts.app')

@section('title', $categories[$category] . ' - Mental Health Corner')

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
        --student-accent: #fef3c7;
        --student-warning: #fff7ed;
        --student-warning-border: #fdba74;
        --student-warning-text: #9a3412;
    }

    .resources-shell {
        position: relative;
        overflow: hidden;
        background: var(--bg-warm);
        min-height: 100vh;
    }
    .resources-glow {
        position: absolute; border-radius: 50%; filter: blur(80px); pointer-events: none; opacity: 0.2;
    }
    .resources-glow.one { top: -40px; left: -50px; width: 240px; height: 240px; background: var(--gold-400); }
    .resources-glow.two { bottom: -50px; right: -70px; width: 280px; height: 280px; background: var(--maroon-800); }

    .hero-card, .panel-card, .glass-card, .resource-card {
        position: relative; overflow: hidden; border-radius: 0.75rem;
        border: 1px solid var(--border-soft); background: rgba(255,255,255,0.95);
        backdrop-filter: blur(8px); box-shadow: 0 2px 8px rgba(44,36,32,0.04);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .hero-card:hover, .panel-card:hover, .glass-card:hover { box-shadow: 0 4px 14px rgba(44,36,32,0.06); }
    .resource-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(92,26,26,0.08); }
    .hero-card::before, .panel-card::before, .glass-card::before, .resource-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 35%);
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
        border: 1px solid rgba(212,175,55,0.3); background: rgba(254,249,231,0.9);
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

    .primary-btn, .secondary-btn, .resource-btn {
        border-radius: 0.6rem; font-weight: 600; transition: all 0.2s ease;
        display: inline-flex; align-items: center; justify-content: center; white-space: nowrap;
        font-size: 0.8rem; padding: 0.55rem 1rem;
    }
    .primary-btn {
        color: #fef9e7; background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        box-shadow: 0 4px 10px rgba(92,26,26,0.15);
    }
    .primary-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(92,26,26,0.2); }
    .secondary-btn {
        color: var(--text-primary); background: rgba(255,255,255,0.95);
        border: 1px solid var(--border-soft);
    }
    .secondary-btn:hover { background: rgba(254,249,231,0.7); border-color: var(--maroon-700); }
    .resource-btn {
        background: linear-gradient(135deg, var(--maroon-700) 0%, var(--maroon-800) 100%);
        color: white; border: none; width: 100%; justify-content: center;
    }
    .resource-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(122,42,42,0.2); }
    .resource-btn.external::after {
        content: "\f35d"; font-family: "Font Awesome 6 Free"; font-weight: 900;
        font-size: 0.7rem; margin-left: 0.4rem; opacity: 0.9;
    }
    .resource-btn:disabled {
        opacity: 0.6; cursor: not-allowed; transform: none !important;
    }

    .panel-topline { position: absolute; inset-inline: 0; top: 0; height: 3px; background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%); }
    .panel-header { display: flex; align-items: center; gap: 0.7rem; padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--border-soft)/60; }
    .panel-icon { width: 2rem; height: 2rem; border-radius: 0.6rem; display: flex; align-items: center; justify-content: center; background: rgba(254,249,231,0.7); color: var(--maroon-700); }
    .panel-title { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); }
    .panel-subtitle { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.1rem; }

    /* Resource Card Specific Styles */
    .resource-image {
        position: relative; height: 12rem; overflow: hidden;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        display: flex; align-items: center; justify-content: center;
    }
    .resource-image img {
        width: 100%; height: 100%; object-fit: contain; padding: 1.5rem;
        background: white; transition: transform 0.3s ease;
    }
    .resource-card:hover .resource-image img { transform: scale(1.03); }
    .resource-icon-overlay {
        position: absolute; top: 0.75rem; right: 0.75rem;
        width: 2.5rem; height: 2.5rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.95); color: var(--maroon-700);
        font-size: 1rem; box-shadow: 0 4px 12px rgba(44,36,32,0.1);
    }

    .resource-title {
        font-size: 1.1rem; font-weight: 700; color: var(--text-primary);
        line-height: 1.3; margin-bottom: 0.5rem;
    }
    .resource-desc {
        font-size: 0.8rem; color: var(--text-secondary);
        line-height: 1.5; white-space: pre-line; word-wrap: break-word;
    }

    /* Disclaimer Box - Student Friendly */
    .disclaimer-box {
        background: var(--student-warning); border: 1px solid var(--student-warning-border);
        border-radius: 0.6rem; padding: 0.6rem; cursor: pointer;
        transition: all 0.2s ease;
    }
    .disclaimer-box:hover { background: rgba(255,247,237,0.95); }
    .disclaimer-header {
        display: flex; align-items: center; justify-content: space-between;
        font-size: 0.7rem; font-weight: 700; color: var(--student-warning-text);
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .disclaimer-header i { color: #f59e0b; margin-right: 0.3rem; }
    .disclaimer-content {
        margin-top: 0.5rem; font-size: 0.7rem; color: var(--student-warning-text);
        line-height: 1.4; white-space: pre-line;
    }
    .disclaimer-icon {
        transition: transform 0.2s ease; color: #ea580c;
    }
    .disclaimer-icon.rotated { transform: rotate(180deg); }

    .empty-state {
        text-align: center; padding: 2.5rem 1.5rem;
        background: rgba(255,255,255,0.95); border: 1px solid var(--border-soft);
        border-radius: 0.75rem;
    }
    .empty-state-icon {
        width: 4rem; height: 4rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem; font-size: 1.5rem;
        background: rgba(254,249,231,0.8); color: var(--maroon-700);
        border: 2px dashed var(--gold-400);
    }

    .back-link {
        display: inline-flex; align-items: center; gap: 0.4rem;
        color: var(--maroon-700); font-size: 0.75rem; font-weight: 600;
        transition: all 0.18s ease;
    }
    .back-link:hover { color: var(--maroon-900); transform: translateX(-2px); }

    /* Responsive Utilities */
    @media (max-width: 900px) {
        .grid.grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
        .grid.grid-cols-4 { grid-template-columns: repeat(2, 1fr); }
        .grid.lg\:grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
        .grid.lg\:grid-cols-4 { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 639px) {
        .panel-header { padding: 0.75rem 1rem; }
        .primary-btn, .secondary-btn, .resource-btn { width: 100%; justify-content: center; padding: 0.7rem; font-size: 0.75rem; }
        .hero-card { padding: 1rem !important; }
        .hero-icon { width: 2.25rem; height: 2.25rem; }
        .resource-image { height: 10rem; }
        .resource-icon-overlay { width: 2.25rem; height: 2.25rem; font-size: 0.9rem; }
        .resource-title { font-size: 1rem; }
        .resource-desc { font-size: 0.75rem; }
        .disclaimer-box { padding: 0.5rem; }
        .disclaimer-header { font-size: 0.65rem; }
        .disclaimer-content { font-size: 0.65rem; }
        .resources-grid-mobile { grid-template-columns: 1fr !important; gap: 1rem !important; }
        .grid.grid-cols-3,
        .grid.grid-cols-4,
        .grid.lg\:grid-cols-3,
        .grid.lg\:grid-cols-4 { grid-template-columns: 1fr; }
    }
</style>

<div class="min-h-screen resources-shell">
    <div class="resources-glow one"></div>
    <div class="resources-glow two"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-5 md:py-8">
        <!-- Header -->
        <div class="mb-5 sm:mb-6">
            <div class="hero-card">
                <div class="relative p-4 sm:p-5 flex items-start gap-3">
                    <div class="hero-icon">
                        <i class="fas fa-book-open text-base sm:text-lg"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('mhc') }}" class="back-link mb-2">
                            <i class="fas fa-arrow-left text-[9px]"></i> Back to Mental Health Corner
                        </a>
                        <div class="hero-badge">
                            <span class="hero-badge-dot"></span>
                            Resources
                        </div>
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-semibold tracking-tight text-[#2c2420] mt-2">{{ $categories[$category] }}</h1>
                        <p class="text-[#6b5e57] text-xs sm:text-sm mt-1.5 max-w-2xl">
                            Browse helpful mental health resources curated for your wellbeing.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resources Grid -->
        @if($resources->isEmpty())
            <div class="glass-card empty-state mb-6">
                <div class="empty-state-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#2c2420] mb-2">No Resources Yet</h3>
                <p class="text-[#6b5e57] text-sm mb-1 max-w-md mx-auto">
                    No resources available in this category yet.
                </p>
                <p class="text-[#8b7e76] text-[0.75rem]">Check back later for new content — we're always adding more support for you.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6 resources-grid-mobile">
                @foreach($resources as $resource)
                    <div class="resource-card flex flex-col">
                        <!-- Resource Image Container -->
                        <div class="resource-image">
                            <img src="{{ $resource->image_url }}"
                                 alt="{{ $resource->title }}"
                                 onerror="this.parentElement.style.background='linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%)'">

                            <!-- Icon Overlay -->
                            <div class="resource-icon-overlay">
                                <i class="{{ $resource->icon }}"></i>
                            </div>
                        </div>

                        <!-- Resource Content -->
                        <div class="p-4 sm:p-5 flex flex-col flex-grow">
                            <h3 class="resource-title">{{ $resource->title }}</h3>

                            <!-- Full Description -->
                            <div class="mb-3 flex-grow">
                                <p class="resource-desc">{{ $resource->description }}</p>
                            </div>

                            <!-- Disclaimer Section - Expandable -->
                            @if($resource->show_disclaimer)
                                <div class="mb-3">
                                    <div class="disclaimer-box" onclick="toggleDisclaimer({{ $resource->id }})">
                                        <div class="disclaimer-header">
                                            <div class="flex items-center">
                                                <i class="fas fa-circle-exclamation"></i>
                                                <span>Disclaimer</span>
                                            </div>
                                            <i class="fas fa-chevron-down disclaimer-icon" id="disclaimer-icon-{{ $resource->id }}"></i>
                                        </div>
                                        <div id="disclaimer-content-{{ $resource->id }}" class="hidden disclaimer-content">
                                            {{ $resource->display_disclaimer }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Button -->
                            @if($resource->link)
                                <a href="{{ $resource->link }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="resource-btn external">
                                    {{ $resource->button_text }}
                                </a>
                            @else
                                <button class="resource-btn" disabled>
                                    {{ $resource->button_text }}
                                </button>
                            @endif
                            <a href="{{ route('student.resources.show', [$category, $resource]) }}"
                               class="mt-2 text-center text-xs font-semibold text-[var(--maroon-700)] hover:text-[var(--maroon-900)] transition block">
                                View full details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    function toggleDisclaimer(resourceId) {
        const content = document.getElementById(`disclaimer-content-${resourceId}`);
        const icon = document.getElementById(`disclaimer-icon-${resourceId}`);

        content.classList.toggle('hidden');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
        icon.classList.toggle('rotated');
    }
</script>
@endsection