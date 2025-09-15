@if ($paginator->hasPages())
    <nav aria-label="Simple Pagination Navigation">
        <ul class="pagination justify-content-center mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        <span style="font-size: 14px;">‹</span>
                        <span class="ms-1">Trước</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <span style="font-size: 14px;">‹</span>
                        <span class="ms-1">Trước</span>
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span class="me-1">Tiếp</span>
                        <span style="font-size: 14px;">›</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        <span class="me-1">Tiếp</span>
                        <span style="font-size: 14px;">›</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
