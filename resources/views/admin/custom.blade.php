@if ($paginator->hasPages())
    <nav>
        <style>
            .pagination {
                display: flex;
                justify-content: center;
                list-style: none;
                padding: 0;
            }

            .pagination .page-item {
                margin: 0 5px;
            }

            .pagination .page-item .page-link {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 1px solid #ddd;
                text-decoration: none;
                color: #333;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .pagination .page-item .page-link:hover {
                background-color: #e0e0e0;
                border-color: #ddd;
            }

            .pagination .page-item.active .page-link {
                background-color: #6f42c1;
                color: white;
                border-color: #6f42c1;
            }

            .pagination .page-item.disabled .page-link {
                color: #6c757d;
                pointer-events: none;
                background-color: #f5f5f5;
                border-color: #ddd;
            }

            .pagination .page-item .page-link svg {
                width: 20px;
                height: 20px;
            }
        </style>

        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&lt;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt;</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    {{-- Show first 2 pages, last 2 pages, and current page with ellipsis --}}
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage() || $page == 1 || $page == $paginator->lastPage() || ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2))
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @elseif ($page == $paginator->currentPage() - 3 || $page == $paginator->currentPage() + 3)
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&gt;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&gt;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
