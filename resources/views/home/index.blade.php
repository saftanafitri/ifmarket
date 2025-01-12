@extends('layouts.app')

@section('title', 'Teknik Informatika')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="header-section d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-left">Products</h2>

            @php $activeCategory = request()->route('category') ?? null; @endphp
            
            <div class="sorting-dropdown">
            <form action="{{ route('products.filter', $activeCategory) }}" method="GET" id="category-form">
            <select name="category" id="category-select" class="form-select" onchange="location.href=this.value;">
            <option value="{{ route('products.filter', ['category' => 'All']) }}" {{ $activeCategory === 'All' ? 'selected' : '' }}>All</option>
            <option value="{{ route('products.filter', 'Kerja Praktik(KP)') }}" {{ $activeCategory === 'Kerja Praktik(KP)' ? 'selected' : '' }}>Kerja Praktik (KP)</option>
            <option value="{{ route('products.filter', 'Tugas Akhir(TA)') }}" {{ $activeCategory === 'Tugas Akhir(TA)' ? 'selected' : '' }}>Tugas Akhir (TA)</option>
            <option value="{{ route('products.filter', 'Penelitian') }}" {{ $activeCategory === 'Penelitian' ? 'selected' : '' }}>Penelitian</option>
            <option value="{{ route('products.filter', 'Pengabdian Masyarakat') }}" {{ $activeCategory === 'Pengabdian Masyarakat' ? 'selected' : '' }}>Pengabdian pada Masyarakat</option>
            <option value="{{ route('products.filter', 'Tugas Kuliah') }}" {{ $activeCategory === 'Tugas Kuliah' ? 'selected' : '' }}>Tugas Kuliah</option>
        </select>
        </form>
    </div>
        </div>
            <div class="row" id="product-list">
                @foreach ($products as $product)
                    <div class="col-md-5 col-lg-2 product-item" data-category="{{ $product->category->name }}">
                        <div class="card">
                            @if ($product->photos->isNotEmpty())
                                {{-- Menampilkan hanya foto pertama --}}
                                <img src="{{ route('private.file', ['path' => 'public/' . $product->photos->first()->url]) }}" 
                                     alt="{{ $product->name }}" 
                                     class="card-img-top" 
                                     style="border-radius: 0;">
                            @else
                                {{-- Placeholder jika tidak ada foto --}}
                                <img src="{{ asset('images/placeholder.png') }}" 
                                     alt="No Image Available" 
                                     class="card-img-top" 
                                     style="border-radius: 0;">
                            @endif
                            <div class="card-body">
                                <h3 class="card-title">{{ $product->name }}</h3>
                                <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg>
                                <span>{{ $product->sellers->first()->name ?? 'Tidak ada seller' }}</span>
                            </div>
                                <a href="{{ route('products.show', $product->slug) }}" class="btn custom-btn btn-sm mt-2">View Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            </div>
        </div>
        </section>
                <section>
                    <div class="container mb-5">
                        <h2 class="text-center mb-4">Latest Products</h2>
                        <div class="row g-4">
                            @forelse($latestProducts as $product)
                                <div class="col-lg-4">
                                    <div class="card">
                                        {{-- Menampilkan hanya foto pertama --}}
                                        @if ($product->photos->isNotEmpty())
                                            <img src="{{ route('private.file', ['path' => 'public/' . $product->photos->first()->url]) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="card-img" 
                                                 style="border-radius: 0;">
                                        @else
                                            {{-- Placeholder jika tidak ada foto --}}
                                            <img src="{{ asset('images/placeholder.png') }}" 
                                                 alt="No Image Available" 
                                                 class="card-img" 
                                                 style="border-radius: 0;">
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $product->name }}</h5>
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                                    class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                    <path fill-rule="evenodd"
                                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                                </svg>
                                                <p class="mb-0 text-nowrap">{{ $product->seller_name }}</p>
                                            </div>
                                            <p class="text-muted small">{{ $product->created_at->format('d M Y') }}</p>
                                            <a href="{{ route('products.show', $product->slug) }}" class="btn custom-btn btn-sm">View More</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">No products available in this category.</p>
                            @endforelse
                        </div>
                        <script src="{{ asset('index/index.js') }}"></script>
                    </div>
                </section>
        @endsection
