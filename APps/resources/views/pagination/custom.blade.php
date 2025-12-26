@if ($paginator->hasPages())
<div class="d-flex justify-center align-center gap-2 mt-4">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed;">← Previous</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-secondary">← Previous</a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <span class="btn btn-secondary" style="opacity: 0.5;">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="btn" style="background: #007bff; color: white; cursor: default;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-secondary">Next →</a>
    @else
        <span class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed;">Next →</span>
    @endif
</div>
@endif
