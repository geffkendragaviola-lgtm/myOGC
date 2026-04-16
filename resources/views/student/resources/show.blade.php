<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $resource->title }} — Mental Health Corner</title>
    <link rel="icon" type="image/png" href="{{ asset('images/msu-iit-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

    * { box-sizing: border-box; }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--bg-warm);
        color: var(--text-primary);
        min-height: 100vh;
    }

    /* ── Topbar ── */
    .topbar {
        position: sticky; top: 0; z-index: 50;
        background: linear-gradient(90deg, var(--maroon-900) 0%, var(--maroon-800) 55%, var(--maroon-700) 100%);
        border-bottom: 1px solid rgba(212,175,55,0.25);
        box-shadow: 0 4px 20px rgba(58,12,12,0.2);
        padding: 0.85rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    }
    .topbar-brand {
        display: flex; align-items: center; gap: 0.65rem;
        color: #fff; font-weight: 800; font-size: 1rem; text-decoration: none;
    }
    .topbar-brand span { color: var(--gold-400); }
    .topbar-back {
        display: inline-flex; align-items: center; gap: 0.5rem;
        color: rgba(255,255,255,0.85); font-size: 0.8rem; font-weight: 600;
        padding: 0.45rem 1rem; border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.18);
        background: rgba(255,255,255,0.08);
        transition: all 0.2s; text-decoration: none;
    }
    .topbar-back:hover { background: rgba(255,255,255,0.16); color: #fff; }

    /* ── Page shell ── */
    .page-shell {
        position: relative;
        background:
            radial-gradient(circle at top left, rgba(212,175,55,0.06), transparent 28%),
            radial-gradient(circle at bottom right, rgba(92,26,26,0.06), transparent 28%),
            var(--bg-warm);
    }

    /* ── Hero banner ── */
    .hero-banner {
        position: relative; width: 100%; height: 26rem; overflow: hidden;
        background: linear-gradient(135deg, var(--maroon-900) 0%, var(--maroon-700) 100%);
    }
    .hero-banner img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 8s ease;
    }
    .hero-banner:hover img { transform: scale(1.04); }
    .hero-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(30,8,8,0.82) 0%, rgba(30,8,8,0.3) 50%, transparent 100%);
    }
    .hero-content {
        position: absolute; bottom: 0; left: 0; right: 0;
        padding: 2.5rem 2rem 2rem;
        max-width: 56rem; margin: 0 auto;
    }
    .hero-category {
        display: inline-flex; align-items: center; gap: 0.45rem;
        background: rgba(212,175,55,0.92); color: var(--maroon-900);
        padding: 0.3rem 0.85rem; border-radius: 999px;
        font-size: 0.72rem; font-weight: 800; text-transform: uppercase;
        letter-spacing: 0.12em; margin-bottom: 0.75rem;
    }
    .hero-title {
        font-size: clamp(1.5rem, 4vw, 2.4rem);
        font-weight: 800; color: #fff; line-height: 1.15;
        letter-spacing: -0.025em;
        text-shadow: 0 2px 16px rgba(0,0,0,0.35);
    }

    /* ── Breadcrumb ── */
    .breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-size: 0.78rem; color: var(--text-muted);
        padding: 1.25rem 0 0;
    }
    .breadcrumb a { color: var(--text-secondary); font-weight: 500; transition: color 0.18s; }
    .breadcrumb a:hover { color: var(--maroon-700); }
    .breadcrumb-sep { color: var(--border-soft); }

    /* ── Layout grid ── */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 21rem;
        gap: 2.25rem;
        align-items: start;
        padding: 2rem 0 4rem;
    }
    @media (max-width: 1023px) {
        .content-grid { grid-template-columns: 1fr; }
        .hero-banner { height: 18rem; }
    }
    @media (max-width: 639px) {
        .hero-banner { height: 13rem; }
        .hero-content { padding: 1.5rem 1.25rem 1.25rem; }
        .hero-title { font-size: 1.25rem; }
        .topbar { padding: 0.65rem 1rem; }
        .topbar-brand { font-size: 0.875rem; }
        .topbar-back { padding: 0.35rem 0.75rem; font-size: 0.75rem; }
        .content-grid { padding: 1.25rem 0 2.5rem; }
        .max-w-screen-xl { padding-left: 1rem; padding-right: 1rem; }
    }

    /* ── Article card ── */
    .article-card {
        position: relative; overflow: hidden;
        background: rgba(255,255,255,0.98);
        border: 1px solid var(--border-soft);
        border-radius: 1.25rem;
        box-shadow: 0 4px 28px rgba(44,36,32,0.07);
    }
    .article-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 35%);
    }
    .article-topline {
        position: absolute; inset-inline: 0; top: 0; height: 3px;
        background: linear-gradient(90deg, var(--maroon-800) 0%, var(--gold-400) 50%, var(--maroon-800) 100%);
    }

    /* ── Meta row ── */
    .meta-row {
        display: flex; flex-wrap: wrap; align-items: center; gap: 1.25rem;
        padding-bottom: 1.35rem;
        border-bottom: 1px solid var(--border-soft);
        margin-bottom: 1.75rem;
    }
    .meta-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        font-size: 0.8rem; color: var(--text-secondary);
    }
    .meta-chip i { color: var(--gold-500); font-size: 0.72rem; }

    /* ── Article body ── */
    .article-body {
        font-size: 1.05rem;
        line-height: 1.9;
        color: var(--text-primary);
        white-space: pre-line;
        word-break: break-word;
    }

    /* ── CTA ── */
    .cta-btn {
        display: inline-flex; align-items: center; gap: 0.65rem;
        padding: 0.9rem 2rem; border-radius: 0.75rem;
        background: linear-gradient(135deg, var(--maroon-800) 0%, var(--maroon-700) 100%);
        color: #fef9e7; font-weight: 700; font-size: 1rem;
        box-shadow: 0 6px 20px rgba(92,26,26,0.22);
        transition: all 0.22s; text-decoration: none;
    }
    .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(92,26,26,0.3); color: #fef9e7; }

    /* ── Disclaimer ── */
    .disclaimer-box {
        background: #fff7ed; border: 1px solid #fdba74;
        border-radius: 0.75rem; padding: 1rem; cursor: pointer;
        transition: background 0.18s;
    }
    .disclaimer-box:hover { background: #fff3e0; }
    .disclaimer-header {
        display: flex; align-items: center; justify-content: space-between;
        font-size: 0.75rem; font-weight: 700; color: #9a3412;
        text-transform: uppercase; letter-spacing: 0.06em;
    }
    .disclaimer-body {
        margin-top: 0.75rem; font-size: 0.82rem; color: #7c2d12; line-height: 1.6;
    }

    /* ── Sidebar cards ── */
    .side-card {
        position: relative; overflow: hidden;
        background: rgba(255,255,255,0.98);
        border: 1px solid var(--border-soft);
        border-radius: 1rem;
        box-shadow: 0 2px 14px rgba(44,36,32,0.05);
    }
    .side-card::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background: radial-gradient(circle at top right, rgba(212,175,55,0.05), transparent 30%);
    }
    .side-topline {
        position: absolute; inset-inline: 0; top: 0; height: 2.5px;
        background: linear-gradient(90deg, var(--maroon-800), var(--gold-400));
    }
    .side-label {
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.12em; color: var(--text-muted);
        padding: 1rem 1.25rem 0.5rem;
    }

    /* ── Related items ── */
    .related-item {
        display: flex; align-items: center; gap: 0.9rem;
        padding: 0.85rem 1.25rem;
        border-top: 1px solid var(--border-soft);
        transition: background 0.15s; text-decoration: none;
    }
    .related-item:hover { background: rgba(254,249,231,0.55); }
    .related-thumb {
        width: 3.75rem; height: 3.75rem; border-radius: 0.65rem; flex-shrink: 0;
        background: linear-gradient(135deg, var(--maroon-800), var(--maroon-700));
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .related-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .related-thumb i { color: rgba(255,255,255,0.65); font-size: 1.15rem; }
    .related-name {
        font-size: 0.84rem; font-weight: 600; color: var(--text-primary); line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .related-sub { font-size: 0.72rem; color: var(--text-muted); margin-top: 0.2rem; }

    /* ── Footer ── */
    .page-footer {
        background: linear-gradient(180deg, var(--maroon-900) 0%, #2a0808 100%);
        color: rgba(255,255,255,0.7);
        text-align: center; padding: 2rem 1rem;
        font-size: 0.8rem;
        border-top: 1px solid rgba(255,255,255,0.06);
    }
    .page-footer span { color: var(--gold-400); font-weight: 700; }
    </style>
</head>
<body class="page-shell">

    {{-- Topbar --}}
    <header class="topbar">
        <a href="{{ route('mhc') }}" class="topbar-brand">
            <img src="{{ asset('images/msu-iit-logo.png') }}" alt="MSU-IIT" class="h-7 w-7 object-contain" onerror="this.style.display='none'">
            <span>my.OGC</span>
        </a>
        <a href="{{ route('student.resources.category', $category) }}" class="topbar-back">
            <i class="fas fa-arrow-left text-[10px]"></i>
            Back to {{ $categories[$category] }}
        </a>
    </header>

    {{-- Hero banner --}}
    <div class="hero-banner">
        <img src="{{ $resource->image_url }}"
             alt="{{ $resource->title }}"
             onerror="this.style.display='none'">
        <div class="hero-overlay"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 w-full">
            <div class="hero-content">
                <div class="hero-category">
                    <i class="{{ $resource->icon }} text-[10px]"></i>
                    {{ $categories[$category] }}
                </div>
                <h1 class="hero-title">{{ $resource->title }}</h1>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb">
            <a href="{{ route('mhc') }}"><i class="fas fa-heart text-[10px]"></i> Mental Health Corner</a>
            <span class="breadcrumb-sep">/</span>
            <a href="{{ route('student.resources.category', $category) }}">{{ $categories[$category] }}</a>
            <span class="breadcrumb-sep">/</span>
            <span class="font-medium text-[var(--text-primary)] truncate max-w-[220px]">{{ $resource->title }}</span>
        </nav>

        <div class="content-grid">

            {{-- Article --}}
            <div class="space-y-5">
                <div class="article-card">
                    <div class="article-topline"></div>
                    <div class="p-7 sm:p-9">

                        {{-- Meta --}}
                        <div class="meta-row">
                            @if($resource->user)
                            <span class="meta-chip">
                                <i class="fas fa-user-doctor"></i>
                                {{ $resource->user->first_name }} {{ $resource->user->last_name }}
                            </span>
                            @endif
                            <span class="meta-chip">
                                <i class="fas fa-tag"></i>
                                {{ $categories[$category] }}
                            </span>
                            <span class="meta-chip">
                                <i class="fas fa-calendar-days"></i>
                                {{ $resource->created_at->format('F j, Y') }}
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="article-body">{{ $resource->description }}</div>

                        {{-- Disclaimer --}}
                        @if($resource->show_disclaimer)
                        <div class="mt-7">
                            <div class="disclaimer-box" onclick="toggleDisclaimer()">
                                <div class="disclaimer-header">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-circle-exclamation text-amber-500"></i>
                                        Content Disclaimer
                                    </div>
                                    <i class="fas fa-chevron-down text-orange-600 transition-transform duration-200" id="disc-icon"></i>
                                </div>
                                <div id="disc-body" class="hidden disclaimer-body">
                                    {{ $resource->display_disclaimer }}
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- CTA --}}
                        @if($resource->link)
                        <div class="mt-9 pt-7 border-t border-[var(--border-soft)]">
                            <p class="text-sm text-[var(--text-muted)] mb-3">Ready to explore this resource?</p>
                            <a href="{{ $resource->link }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="cta-btn">
                                <i class="fas fa-external-link-alt text-sm"></i>
                                {{ $resource->button_text }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="space-y-5">

                {{-- About card --}}
                <div class="side-card">
                    <div class="side-topline"></div>
                    <p class="side-label">About this resource</p>
                    <div class="px-5 pb-5 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(254,249,231,0.8);color:var(--maroon-700);">
                                <i class="{{ $resource->icon }} text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[0.68rem] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Category</p>
                                <p class="text-sm font-semibold text-[var(--text-primary)] mt-0.5">{{ $categories[$category] }}</p>
                            </div>
                        </div>
                        @if($resource->user)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(254,249,231,0.8);color:var(--maroon-700);">
                                <i class="fas fa-user-doctor text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[0.68rem] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Added by</p>
                                <p class="text-sm font-semibold text-[var(--text-primary)] mt-0.5">
                                    {{ $resource->user->first_name }} {{ $resource->user->last_name }}
                                </p>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(254,249,231,0.8);color:var(--maroon-700);">
                                <i class="fas fa-calendar text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[0.68rem] font-semibold uppercase tracking-wider text-[var(--text-muted)]">Published</p>
                                <p class="text-sm font-semibold text-[var(--text-primary)] mt-0.5">
                                    {{ $resource->created_at->format('M j, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Related --}}
                @if($related->count())
                <div class="side-card">
                    <div class="side-topline"></div>
                    <p class="side-label">More in {{ $categories[$category] }}</p>
                    @foreach($related as $rel)
                    <a href="{{ route('student.resources.show', [$category, $rel]) }}" class="related-item">
                        <div class="related-thumb">
                            @if($rel->image_url && !str_contains($rel->image_url, 'default-resource'))
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}"
                                     onerror="this.parentElement.innerHTML='<i class=\'{{ $rel->icon }}\'></i>'">
                            @else
                                <i class="{{ $rel->icon }}"></i>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="related-name">{{ $rel->title }}</p>
                            <p class="related-sub">{{ Str::limit($rel->description, 60) }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                {{-- Back to MHC --}}
                <div class="side-card p-5" style="padding-top:1.25rem;">
                    <div class="side-topline"></div>
                    <p class="text-sm font-semibold text-[var(--text-primary)] mb-1">Looking for more?</p>
                    <p class="text-xs text-[var(--text-muted)] mb-4 leading-relaxed">Browse all resource categories in the Mental Health Corner.</p>
                    <a href="{{ route('mhc') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-[var(--maroon-700)] hover:text-[var(--maroon-900)] transition">
                        <i class="fas fa-heart text-[10px]"></i> Mental Health Corner
                    </a>
                </div>

            </aside>
        </div>
    </main>

    <footer class="page-footer">
        <p><span>MSU-IIT</span> Office of Guidance and Counseling &mdash; Mental Health Corner</p>
        <p class="mt-1 text-xs opacity-60">We're here whenever you need us.</p>
    </footer>

    <script>
    function toggleDisclaimer() {
        const body = document.getElementById('disc-body');
        const icon = document.getElementById('disc-icon');
        body.classList.toggle('hidden');
        icon.style.transform = body.classList.contains('hidden') ? '' : 'rotate(180deg)';
    }
    </script>
</body>
</html>
