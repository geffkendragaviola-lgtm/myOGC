@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex items-center justify-center gap-1.5 flex-wrap">

    {{-- Prev arrow --}}
    @if ($paginator->onFirstPage())
        <span class="pag-btn pag-disabled" aria-disabled="true">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pag-btn pag-nav" aria-label="Previous">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
        </a>
    @endif

    {{-- Page numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="pag-btn pag-dots">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pag-btn pag-active" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pag-btn pag-page" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next arrow --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pag-btn pag-nav" aria-label="Next">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </a>
    @else
        <span class="pag-btn pag-disabled" aria-disabled="true">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        </span>
    @endif

</nav>

<style>
    .pag-btn {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 28px; height: 28px; padding: 0 8px; border-radius: 8px;
        font-size: 11px; font-weight: 600; transition: all 0.2s ease; text-decoration: none;
    }
    .pag-active { background: #5c1a1a; color: #fff; }
    .pag-page   { background: #fff; color: #6b5e57; border: 1px solid #e5e0db; }
    .pag-page:hover { background: #fdf2f2; color: #5c1a1a; border-color: rgba(212,175,55,0.4); }
    .pag-nav    { background: #fff; color: #6b5e57; border: 1px solid #e5e0db; }
    .pag-nav:hover { background: #fdf2f2; color: #5c1a1a; border-color: rgba(212,175,55,0.4); }
    .pag-disabled { background: #f5f3f0; color: #c4b8b0; border: 1px solid #e5e0db; cursor: default; }
    .pag-dots   { background: transparent; color: #8b7e76; border: none; cursor: default; }
</style>
@endif
