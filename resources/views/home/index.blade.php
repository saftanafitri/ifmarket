
@extends('layouts.app')

@section('title', 'Sakti Product')

@section('content')
            <div class="py-4">
                <div class="container">
                <form action="{{ route('products.search') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text"
                            name="query"
                            class="form-control"
                            placeholder="Cari produk...">
                        <button class="btn btn-warning">
                            🔍
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <section class="py-5">
        <div class="container">
            <div class="header-section d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-left">Produk</h2>

                <div class="sorting-dropdown">
                    <div class="dropdown">
                        <button class="btn btn-warning btn-sm dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            {{ $category === 'All' || !$category ? 'Semua' : $category }}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('home.index', ['category' => 'All']) }}">Semua</a></li>
                            <li><a class="dropdown-item" href="{{ route('home.index', ['category' => 'Kerja Praktik(KP)']) }}">Kerja Praktik (KP)</a></li>
                            <li><a class="dropdown-item" href="{{ route('home.index', ['category' => 'Tugas Akhir(TA)']) }}">Tugas Akhir (TA)</a></li>
                            <li><a class="dropdown-item" href="{{ route('home.index', ['category' => 'Penelitian']) }}">Penelitian</a></li>
                            <li><a class="dropdown-item" href="{{ route('home.index', ['category' => 'Pengabdian Masyarakat']) }}">Pengabdian Masyarakat</a></li>
                            <li><a class="dropdown-item" href="{{ route('home.index', ['category' => 'Tugas Kuliah']) }}">Tugas Kuliah</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            {{-- Menampilkan hasil pencarian jika ada parameter query --}}
            @if(request('query'))
                <h2 class="text-center my-4">Hasil Pencarian untuk: "{{ request('query') }}"</h2>
            @endif

            <div class="row g-4" id="product-list">
                @forelse ($products as $product)
                <div class="col-6 col-md-4 col-lg-2 product-item" data-category="{{ $product->category?->name ?? 'Tanpa Kategori' }}">
                    <div class="card h-100">
                        @if ($product->photos->isNotEmpty())
                            <img src="{{ $product->photos->first()->full_url }}"
                                 alt="{{ $product->name }}"
                                 class="card-img-top"
                                 style="border-radius: 0;">
                        @else
                            <img src="{{ asset('images/placeholder.png') }}" 
                                 alt="Gambar Tidak Tersedia" 
                                 class="card-img-top" 
                                 style="border-radius: 0;">
                        @endif
                        <div class="card-body">
                            <h3 class="card-title">{{ $product->name ?? 'Nama Tidak Tersedia' }}</h3>
                            <div class="d-flex align-items-center">
                                @if($product->sellers->isNotEmpty())
                                {{-- Cek apakah seller punya foto profil --}}
                                @if($product->sellers->first()->foto_user)
                                    <img src="{{ $product->sellers->first()->foto_user }}" alt="{{ $product->sellers->first()->name }}" class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">
                                @else
                                    {{-- Jika tidak punya, tampilkan ikon default --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                        class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                        <path fill-rule="evenodd"
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                    </svg>
                                @endif
                                <span>{{ $product->sellers->first()->name }}</span>
                            @else
                                <span>Pemilik Tidak Dikenal</span>
                            @endif                          </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="btn custom-btn btn-sm mt-2">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                @empty
                    <p class="text-center">Tidak ada produk yang ditemukan</p>
                @endforelse
            </div>
        </div>
    </section>
     {{ $products->links('vendor.pagination.bootstrap-5') }}

    {{-- Bagian Produk Terbaru --}} 
    <section>
        <div class="container mb-5">
            <h2 class="text-center mb-4">Produk Terbaru</h2>
            <div class="row g-4">
                @forelse($latestProducts as $product)
                    <div class="col-lg-4">
                        <div class="card">
                            @if ($product->photos->isNotEmpty())
                                <img src="{{ $product->photos->first()->full_url }}"
                                     alt="{{ $product->name }}"
                                     class="card-img"
                                     style="border-radius: 0;">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}" 
                                     alt="Gambar Tidak Tersedia" 
                                     class="card-img" 
                                     style="border-radius: 0;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="text-muted small">{{ $product->category->name }}</p>
                                <div class="d-flex align-items-center">
                                    @if($product->sellers->isNotEmpty())
                                    {{-- Cek apakah seller punya foto profil --}}
                                    @if($product->sellers->first()->foto_user)
                                        <img src="{{ $product->sellers->first()->foto_user }}" alt="{{ $product->sellers->first()->name }}" class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">
                                    @else
                                        {{-- Jika tidak punya, tampilkan ikon default --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                            class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                            <path fill-rule="evenodd"
                                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                        </svg>
                                    @endif
                                    <span>{{ $product->sellers->first()->name }}</span>
                                @else
                                    <span>Pemilik Tidak Dikenal</span>
                                @endif
                                </div>
                                <p class="text-muted small">{{ $product->created_at->format('d M Y') }}</p>
                                <a href="{{ route('products.show', $product->slug) }}" class="btn custom-btn btn-sm">Lihat Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Tidak ada produk tersedia di kategori ini.</p>
                @endforelse
            </div>
            <script src="{{ asset('js/index.js') }}"></script>
        </div>
    </section>    
@endsection
