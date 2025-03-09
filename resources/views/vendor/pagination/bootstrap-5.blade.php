@if ($paginator->hasPages())
    <nav>
        <ul class="pagination custom-pagination justify-content-end">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link bg-light text-warning">Sebelumnya</span></li>
            @else
                <li class="page-item"><a class="page-link text-warning bg-warning" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a></li>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link bg-light text-warning">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link bg-warning border-warning text-dark">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link text-warning" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link text-warning bg-warning" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a></li>
            @else
                <li class="page-item disabled"><span class="page-link bg-light text-warning">Berikutnya</span></li>
            @endif
        </ul>
    </nav>
@endif
