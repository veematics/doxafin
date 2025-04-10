@if ($paginator->hasPages())
    <ul class="pagination pagination-sm mb-0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">
                    <svg class="icon">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-chevron-left"></use>
                    </svg>
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <svg class="icon">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-chevron-left"></use>
                    </svg>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    <svg class="icon">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-chevron-right"></use>
                    </svg>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">
                    <svg class="icon">
                        <use xlink:href="{{ asset('assets/icons/free/free.svg') }}#cil-chevron-right"></use>
                    </svg>
                </span>
            </li>
        @endif
    </ul>
@endif