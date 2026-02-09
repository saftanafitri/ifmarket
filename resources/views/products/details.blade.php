@extends('layouts.app')

@section('title', 'Sakti Product - Detail Produk')

@section('content')

<section id="product-details-section" 
         class="py-5" 
         data-api-url="{{ route('api.product.details', ['slug' => $product->slug]) }}">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
        </ol>
    </nav>

    <div class="product-container">
        <!-- Bagian Gambar dan Galeri -->
        <div class="product-image-container">
            <div class="main-image-container">
                @if ($product->photos->isNotEmpty())
                    <img id="main-image"
                         src="{{ asset('storage/' . $product->photos->first()->url) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid">
                @else
                    <img id="main-image"
                         src="https://via.placeholder.com/300"
                         alt="No Image Available"
                         class="img-fluid">
                @endif
            </div>

            <!-- Galeri Thumbnail -->
            <div class="gallery-container">
                <button class="scroll-btn left" onclick="scrollGallery('left')" aria-label="Scroll Left">‹</button>
            
                <div class="thumbnails-wrapper">
                    @foreach ($product->photos as $photo)
                        <div class="thumbnail-item @if ($loop->first) active @endif" 
                             onclick="updateMainImage(this, '{{ asset('storage/' . $photo->url) }}')">
                            <img src="{{ asset('storage/' . $photo->url) }}" alt="Thumbnail" class="thumbnail-img">
                        </div>
                    @endforeach
                </div>
            
                <button class="scroll-btn right" onclick="scrollGallery('right')" aria-label="Scroll Right">›</button>
            </div>
        </div>

        <!-- Detail Produk -->
        <div class="product-details">
            <h2>{{ $product->name }}</h2>
            <div class="profile-container">
                <div class="profile-info">
                    @if($product->sellers->isNotEmpty() && $product->sellers->first()->foto_user)
                        <img src="{{ $product->sellers->first()->foto_user }}" alt="{{ $product->sellers->first()->name }}" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"></path>
                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"></path>
                        </svg>
                    @endif
                    <span class="profile-name"> {{ $product->sellers->first()->name }}</span>
                    <div class="social-icons">
                        <!-- Instagram -->
                        <a href="{{ $product->instagram }}" target="_blank" rel="noopener noreferrer">
                            <img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" class="social-logo" alt="Instagram">
                        </a>
                        
                        <!-- LinkedIn -->
                        <a href="{{ $product->linkedin }}" target="_blank" rel="noopener noreferrer">
                            <img src="https://cdn-icons-png.flaticon.com/512/174/174857.png" class="social-logo" alt="LinkedIn">
                        </a>
                        
                        <!-- GitHub -->
                        <a href="{{ $product->github }}" target="_blank" rel="noopener noreferrer">
                            <img src="https://cdn-icons-png.flaticon.com/512/733/733553.png" class="social-logo" alt="GitHub">
                        </a>    
                        
                        <!-- Email -->
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $product->email }}" target="_blank" rel="noopener noreferrer">
                            <img src="https://cdn-icons-png.flaticon.com/512/732/732200.png" class="social-logo" alt="Email">
                        </a>
                    </div>                                     
                </div>
            </div>
            <p style="text-align: justify;">{{ $product->description }}</p>

            <!-- Link Video YouTube -->
            @if ($youtubeID)
                <p><a href="https://www.youtube.com/watch?v={{ $youtubeID }}" target="_blank" class="youtube-link">Lihat Video Produk</a> </p>
            @endif
        </div>
    </div>

    <!-- CSS untuk Link YouTube -->
    <style>
        .youtube-link {
            text-decoration: underline;
            color: blue;
            cursor: pointer;
        }

        .youtube-link:hover {
            color: darkblue;
        }
    </style>

        <!-- Tombol Lihat Produk -->
        <div class="text-align">
            <a href="{{ $product->product_link }}" class="btn btn-custom" target="_blank" rel="noopener noreferrer">Lihat Produk</a>
        </div>
        </div>
             <!-- Related Products -->
             <div class="container mb-5">
                <h2 class="text-center mb-4">Produk Terkait</h2>
                <div class="row g-4">
                    @forelse($relatedProducts as $related)
                        <div class="col-lg-4">
                            <div class="card">
                                @if ($related->photos->isNotEmpty())
                                    <img src="{{ asset('storage/' . $product->photos->first()->url) }}"
                                         alt="{{ $related->name }}" 
                                         class="card-img" 
                                         style="border-radius: 0;">
                                @else
                                    <img src="{{ asset('images/placeholder.png') }}" 
                                         alt="No Image Available" 
                                         class="card-img" 
                                         style="border-radius: 0;">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $related->name }}</h5>
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                            class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                            <path fill-rule="evenodd"
                                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                        </svg>
                                        <p class="mb-0 text-nowrap">{{ $related->seller_name }}</p>
                                    </div>
                                    <p class="text-muted small">{{ $related->created_at->format('d M Y') }}</p>
                                    <a href="{{ route('products.show', $related->slug) }}" class="btn custom-btn btn-sm">Lihat Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center">Tidak ada produk terkait yang tersedia.</p>
                    @endforelse
                </div> 
                <script src="{{ asset('js/detailproduk.js') }}"></script>
            </div>           
        </section>
@endsection