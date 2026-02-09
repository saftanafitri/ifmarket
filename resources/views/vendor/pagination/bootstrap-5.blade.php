@if ($paginator->hasPages())
    <nav>
        <ul class="pagination custom-pagination justify-content-end">
            {{-- Tombol "Sebelumnya" --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Sebelumnya</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
                </li>
            @endif

            {{-- LOGIKA BARU UNTUK NOMOR HALAMAN --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $range = 2; // jumlah halaman kiri-kanan halaman aktif
            @endphp

            @for ($page = 1; $page <= $last; $page++)
                @if (
                    $page == 1 ||
                    $page == $last ||
                    ($page >= $current - $range && $page <= $current + $range)
                )
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                        </li>
                    @endif
                @elseif (
                    $page == 2 && $current > $range + 2 ||
                    $page == $last - 1 && $current < $last - ($range + 1)
                )
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endfor
            {{-- AKHIR LOGIKA BARU --}}

            {{-- Tombol "Berikutnya" --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Berikutnya</span>
                </li>
            @endif
        </ul>
    </nav>
@endif