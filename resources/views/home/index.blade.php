@extends('layouts.app')

@section('title', 'Teknik Informatika')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="header-section d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-left">Products</h2>
                <style>
    #category-select {
        background-color: #f8d219; /* Warna latar dropdown */
        color:#f9f8db; /* Warna teks */
        border: 2px solid #f1c40f; /* Garis tepi kuning */
        border-radius: 5px; /* Membulatkan sudut */
        padding: 5px; /* Jarak teks ke tepi */
        font-size: 16px; /* Ukuran teks */
    }

    #category-select option {
        background-color: #f8d219; /* Warna latar untuk opsi */
        color: #f9f8db; /* Warna teks */
    }
    .card-img-top {
        width: 100%; /* Memastikan lebar gambar mengikuti lebar kartu */
        height: 75px; /* Tetapkan tinggi tetap untuk gambar */
        object-fit: cover; /* Memastikan gambar tetap terlihat bagus dengan cropping jika perlu */
    }
    .card-img {
        width: 100%; /* Mengatur lebar gambar sesuai dengan kartu */
        height: 200px; /* Menetapkan tinggi tetap untuk gambar */
        object-fit: cover; /* Menjaga rasio aspek dan memotong bagian yang tidak sesuai */
    }
</style>

                @php $activeCategory = request()->route('category') ?? null; @endphp
                <div class="sorting-dropdown">
        <form action="{{ route('products.filter', $activeCategory) }}" method="GET" id="category-form">
        <select name="category" id="category-select" class="form-select" onchange="location.href=this.value;">
            <option value="{{ route('products.filter', 'All') }}" {{ $activeCategory === 'All' ? 'selected' : '' }}>All</option>
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
                    <div class="col-md-4 col-lg-2 product-item" data-category="{{ $product->category->name }}">
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
                                    <p class="mb-0 text-nowrap">{{ $product->seller_name }}</p>
                                </div>
                                <a href="{{ route('products.show', $product->id) }}" class="btn custom-btn btn-sm mt-3">View</a>
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
                                <div class="col-md-4">
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
                                            <a href="{{ route('products.show', $product->id) }}" class="btn custom-btn btn-sm">View More</a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center">No products available in this category.</p>
                            @endforelse
                        </div>
                    </div>
                </section>

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
@endsection
