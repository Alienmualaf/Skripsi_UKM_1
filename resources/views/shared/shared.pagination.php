@if ($paginator->hasPages())
    <div class="custom-pagination-container">
        <div class="custom-pagination-info">
            Menampilkan {{ $paginator->firstItem() ?? 0 }} sampai {{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
        </div>
        <div class="custom-pagination-nav">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="custom-pagination-btn disabled">
                    <i class="ph ph-caret-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="custom-pagination-btn" rel="prev">
                    <i class="ph ph-caret-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="custom-pagination-btn disabled">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="custom-pagination-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="custom-pagination-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="custom-pagination-btn" rel="next">
                    <i class="ph ph-caret-right"></i>
                </a>
            @else
                <span class="custom-pagination-btn disabled">
                    <i class="ph ph-caret-right"></i>
                </span>
            @endif
        </div>
    </div>
@endif
