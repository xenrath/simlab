@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-0">
                        <i class="fas fa-chevron-left mr-1"></i>
                        Prev
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-0" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left mr-1"></i>
                        Prev
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-0" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Next
                        <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-0">
                        Next
                        <i class="fas fa-chevron-right ml-1"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
