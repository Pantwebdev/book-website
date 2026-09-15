@php
    $currentPage = $products->currentPage();
    $lastPage = $products->lastPage();
    $paginationRange = [];

    if ($currentPage > 4) {
        $paginationRange[] = 1;
        $paginationRange[] = 2;
        $paginationRange[] = '...';
    }

    for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++) {
        $paginationRange[] = $i;
    }

    if ($currentPage < $lastPage - 3) {
        $paginationRange[] = '...';
        $paginationRange[] = $lastPage - 1;
        $paginationRange[] = $lastPage;
    }
@endphp

@if ($products->hasPages())
<nav class="d-flex justify-content-center">
    <ul class="pagination">
        {{-- Previous --}}
        @if ($products->onFirstPage())
            <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
        @else
            <li class="page-item">
                <a href="javascript:void(0);" class="page-link" data-page="{{ $currentPage - 1 }}">&lsaquo;</a>
            </li>
        @endif

        {{-- Page Numbers --}}
        @foreach ($paginationRange as $page)
            @if ($page === '...')
                <li class="page-item disabled"><span class="page-link">…</span></li>
            @else
                <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="page-link" data-page="{{ $page }}">{{ $page }}</a>
                </li>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($products->hasMorePages())
            <li class="page-item">
                <a href="javascript:void(0);" class="page-link" data-page="{{ $currentPage + 1 }}">&rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
        @endif
    </ul>
</nav>
@endif