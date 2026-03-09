@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Track pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn page-btn--prev" aria-label="Previous page" aria-disabled="true">&larr;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn page-btn--prev" aria-label="Previous page">&larr;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-ellipsis">&hellip;</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a href="{{ $url }}" class="page-btn active">{{ $page }}</a>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn page-btn--next" aria-label="Next page">&rarr;</a>
        @else
            <span class="page-btn page-btn--next" aria-label="Next page" aria-disabled="true">&rarr;</span>
        @endif
    </nav>
@endif
