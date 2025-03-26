@extends('layouts.app')

@section('title', 'Sakti Product - Detail Produk')

@section('content')

<section class="py-5">
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
                         src="{{ asset($product->photos->first()->url) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid"
                         style="width: 100%; height: 350px; border: none;">
                @else
                    <img id="main-image"
                         src="https://via.placeholder.com/300"
                         alt="No Image Available"
                         class="img-fluid">
                @endif
            </div>

            <!-- Galeri Thumbnail -->
            <div class="gallery-container">
                <div class="thumbnails-wrapper">
                    @foreach ($product->photos as $photo)
                        <div class="thumbnail-item" onclick="updateMainImage('{{ asset($photo->url) }}')">
                            <img src="{{ asset($photo->url) }}" alt="Thumbnail" class="thumbnail-img">
                        </div>
                    @endforeach
                </div>
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

    <!-- JavaScript -->
    <script>
        function fetchUpdatedProduct() {
            fetch("{{ route('api.product.details', ['slug' => $product->slug]) }}")
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error(data.error);
                        return;
                    }

                    // Update nama produk
                    document.querySelector('.product-details h2').innerText = data.name;

                    // Update deskripsi produk
                    document.querySelector('.product-details p').innerText = data.description;

                    // Update nama seller
                    document.querySelector('.profile-name').innerText = data.seller_name;

                    // Update tautan media sosial
                    document.querySelector('a[href*="instagram.com"]').href = data.instagram;
                    document.querySelector('a[href*="linkedin.com"]').href = data.linkedin;
                    document.querySelector('a[href*="github.com"]').href = data.github;
                    document.querySelector('a[href*="mail.google.com"]').href = "https://mail.google.com/mail/?view=cm&fs=1&to=" + data.email;

                    // Update foto produk utama
                    if (data.photos.length > 0) {
                        document.getElementById('main-image').src = data.photos[0].url;
                    }

                    // Update thumbnail galeri
                    const galleryContainer = document.querySelector('.thumbnails-wrapper');
                    galleryContainer.innerHTML = ''; // Kosongkan galeri lama

                    data.photos.forEach(photo => {
                        const thumbnailItem = document.createElement('div');
                        thumbnailItem.classList.add('thumbnail-item');
                        thumbnailItem.innerHTML = `<img src="${photo.url}" alt="Thumbnail" class="thumbnail-img">`;
                        thumbnailItem.onclick = function () {
                            document.getElementById('main-image').src = photo.url;
                        };
                        galleryContainer.appendChild(thumbnailItem);
                    });
                })
                .catch(error => console.error('Error fetching updated product:', error));
        }

        setInterval(fetchUpdatedProduct, 50000);

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
             <div class="container mb-5">
                <h2 class="text-center mb-4">Produk Terkait</h2>
                <div class="row g-4">
                    @forelse($relatedProducts as $related)
                        <div class="col-lg-4">
                            <div class="card">
                                @if ($related->photos->isNotEmpty())
                                    <img src="{{ asset($related->photos->first()->url) }}"
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
            </div>            
        </section>
@endsection