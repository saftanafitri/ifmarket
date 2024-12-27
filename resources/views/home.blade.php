@extends('layouts.app')

@section('title', 'Teknik Informatika')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="header-section d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-left">Products</h2>
                @php $activeCategory = request()->route('category') ?? 'All'; @endphp
                <div class="sorting-buttons">
                    <a href="{{ route('products.filter', 'All') }}" class="sort-btn {{ $activeCategory === 'All' ? 'active' : '' }}">All</a>
                    <a href="{{ route('products.filter', 'Kerja Praktik(KP)') }}" class="sort-btn {{ $activeCategory === 'Kerja Praktik(KP)' ? 'active' : '' }}">Kerja Praktik (KP)</a>
                    <a href="{{ route('products.filter', 'Tugas Akhir(TA)') }}" class="sort-btn {{ $activeCategory === 'Tugas Akhir(TA)' ? 'active' : '' }}">Tugas Akhir (TA)</a>
                    <a href="{{ route('products.filter', 'Penelitian') }}" class="sort-btn {{ $activeCategory === 'Penelitian' ? 'active' : '' }}">Penelitian</a>
                    <a href="{{ route('products.filter', 'Pengabdian Masyarakat') }}" class="sort-btn {{ $activeCategory === 'Pengabdian Masyarakat' ? 'active' : '' }}">Pengabdian pada Masyarakat</a>
                    <a href="{{ route('products.filter', 'Tugas Kuliah') }}" class="sort-btn {{ $activeCategory === 'Tugas Kuliah' ? 'active' : '' }}">Tugas Kuliah</a>
                </div>
                
            </div>
            <div class="row g-6">
                <div class="row" id="product-list">
                    @foreach ($products as $product)
                    <div class="col-md-4 col-lg-2 product-item" data-category="{{ $product->category->name }}">
                            <div class="card">
                                @foreach ($product->photos as $photo)
                                    <img src="{{ route('products.show', $photo->url) }}" class="card-img-top" alt="{{ $product->name }}">
                                @endforeach
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ $product->description }}</p>
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                            class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                            <path fill-rule="evenodd"
                                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                        </svg>
                                        <p class="mb-0 text-nowrap">{{ $product->seller_name }}</p>
                                    </div>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn bg-custom1  btn-sm mt-3">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
    </section>
    <!-- Latest News Section -->

     <section>
            <div class="container mb-5">
                <h2 class="text-center mb-4">Latest Products</h2>
                    <div class="row g-4">
                        @forelse($products as $product)
                            <div class="col-md-4">
                                <div class="card">
                                    <!-- Ganti 'news.jpg' dengan gambar produk -->
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->name }}</h5>
                                        <p class="card-text">{{ $product->description }}</p>
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
                                        <a href="{{ route('products.show', $product->id) }}" class="btn bg-custom1 btn-sm">View More</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center">No products available in this category.</p>
                        @endforelse
                    </div>
                </div>
        
        <script>
            function sortBy(category, button) {
                // Reset active state on buttons
                document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
        
                // Show/hide products
                const productItems = document.querySelectorAll('.product-item');
                productItems.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    if (category === 'All' || itemCategory === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }
        </script>
        
    </section>
@endsection
