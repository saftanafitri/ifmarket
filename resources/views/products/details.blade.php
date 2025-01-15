@extends('layouts.app')

@section('title', 'Teknik Informatika - Detail Produk')

@section('content')

<section class="py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product details</li>
        </ol>
    </nav>

    <div class="product-container">
        <!-- Bagian Gambar dan Galeri -->
        <div class="product-image-container">
            <div class="main-image-container">
                @if ($youtubeID)
                    <!-- Video Utama -->
                    <iframe id="main-video"
                            src="https://www.youtube.com/embed/{{ $youtubeID }}"
                            class="embed-responsive-item"
                            style="width: 100%; height: 300px; border: none;"
                            allowfullscreen>
                    </iframe>
                @elseif ($product->photos->isNotEmpty())
                    <img id="main-image"
                         src="{{ route('private.file', ['path' => 'public/' . $product->photos->first()->url]) }}"
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
                <button class="scroll-button left" onclick="scrollGallery('left')">&lt;</button>
                <div class="thumbnails-wrapper">
                    @foreach ($product->photos as $photo)
                        <div class="thumbnail-item" onclick="updateMainImage('{{ route('private.file', ['path' => 'public/' . $photo->url]) }}')">
                            <img src="{{ route('private.file', ['path' => 'public/' . $photo->url]) }}" alt="Thumbnail" class="thumbnail-img">
                        </div>
                    @endforeach
                </div>
                <button class="scroll-button right" onclick="scrollGallery('right')">&gt;</button>
            </div>
        </div>
    
        <!-- Detail Produk -->
        <div class="product-details">
            <h2>{{ $product->name }}</h2>
            <div class="profile-container">
                <div class="profile-info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"></path>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"></path>
                    </svg>
                    <span class="profile-name">{{ $product->seller_name }}</span>
                    <div class="social-icons">
                        <a href="{{ $product->instagram }}" target="_blank" rel="noopener noreferrer">
                            <img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" class="social-logo" alt="Instagram">
                        </a>
                        <a href="{{ $product->linkedin }}" target="_blank" rel="noopener noreferrer">
                            <img src="https://cdn-icons-png.flaticon.com/512/174/174857.png" class="social-logo" alt="LinkedIn">
                        </a>
                        <a href="{{ $product->github }}" target="_blank" rel="noopener noreferrer">
                            <img src="https://cdn-icons-png.flaticon.com/512/733/733553.png" class="social-logo" alt="GitHub">
                        </a>                        
                    </div>
                </div>
            </div>
            <p style="text-align: justify;">{{ $product->description }}</p>
        </div>
    </div>                 

            <!-- JavaScript -->
            <script>
                function updateMainImage(imageUrl) {
                    document.getElementById('main-image').src = imageUrl;
                }

                function scrollGallery(direction) {
                const wrapper = document.querySelector('.thumbnails-wrapper');
                const scrollAmount = 150; // Sesuaikan jarak scroll
                wrapper.scrollBy({
                    top: 0,
                    left: direction === 'left' ? -scrollAmount : scrollAmount,
                    behavior: 'smooth',
                });
            }
  
        </script>
        <!-- Tombol Lihat Produk -->
        <div class="text-align">
            <a href="{{ $product->product_link }}" class="btn btn-custom" target="_blank" rel="noopener noreferrer">Lihat Produk</a>
        </div>
        </div>
        <!-- Related Products -->
       <div class="container py-5">
        <h2>Related Products</h2>
        <div class="row">
            @forelse($relatedProducts as $related)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        @if ($related->photos->isNotEmpty())
                            <img src="{{ route('private.file', ['path' => 'public/' . $related->photos->first()->url]) }}" 
                                class="card-img-top"
                                alt="{{ $related->name }}">
                        @else
                            <img src="https://via.placeholder.com/150" 
                                class="card-img-top" 
                                alt="No Image Available">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $related->name }}</h5>
                        <div class="profile-container">
                            <div class="profile-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-circle">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"></path>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"></path>
                                </svg>
                                <span class="profile-name">{{ $product->seller_name }}</span>
                            </div>
                        </div>
                            <a href="{{ route('products.show', $related->slug) }}" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <p>No related products found.</p>
            @endforelse
        </div>
    </div>
        </section>
@endsection

