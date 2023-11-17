@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#">Previous</a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">Previous</a>
                </li>
            @endif
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disable">
                        <a class="page-link" href="#">{{ $element }}</a>
                    </li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($paginator->currentPage() > 4 && $page === 2)
                            <li class="page-item disabled">
                                <a class="page-link" href="#">...</a>
                            </li>
                        @endif
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a class="page-link" href="#">{{ $page }}</a>
                            </li>
                        @elseif (
                            $page === $paginator->currentPage() + 1 ||
                                $page === $paginator->currentPage() + 2 ||
                                $page === $paginator->currentPage() - 1 ||
                                $page === $paginator->currentPage() - 2 ||
                                $page === $paginator->lastPage() ||
                                $page === 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                        @if ($paginator->currentPage() < $paginator->lastPage() - 3 && $page === $paginator->lastPage() - 1)
                            <li class="page-item disabled">
                                <a class="page-link" href="#">...</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#">Next</a>
                </li>
            @endif
        </ul>
    </nav>
@endif
