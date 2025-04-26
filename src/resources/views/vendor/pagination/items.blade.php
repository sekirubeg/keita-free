@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">&laquo;</a>
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
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
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
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link" aria-hidden="true">&raquo;</span>
                </li>
            @endif

        </ul>
    </nav>
        <style>
        .pagination {
            display: flex;
            justify-content: center;
            padding: 1rem 0;
        }

        .pagination .page-item .page-link {
            color: #fff;
            background-color: #333; /* 黒に近いグレー */
            border: 1px solid #555;
            margin: 0 3px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .pagination .page-item .page-link:hover {
            background-color: #555;
            color: #fff;
        }

        .pagination .page-item.active .page-link {
            background-color: #000; /* 現在ページは完全な黒 */
            border-color: #000;
        }

        .pagination .page-item.disabled .page-link {
            background-color: #222; /* 無効ボタンはさらに暗く */
            color: #777;
            cursor: not-allowed;
        }
    </style>
@endif
