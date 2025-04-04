<?php $__env->startSection('title', 'Teknik Informatika - Tambah Produk'); ?>

<?php $__env->startSection('content'); ?>

<section class="py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Product details</li>
        </ol>
    </nav>

        <!-- Gaya CSS -->
        <style>
            /* Tata letak kontainer utama */
            .product-container {
                display: flex;
                gap: 20px;
                margin: 20px 0;
                align-items: flex-start;
            }

            /* Bagian gambar */
            .product-image-container {
                flex: 1;
            }

            /* Detail produk */
            .product-details {
                flex: 2;
            }

            .product-details h2 {
                font-size: 2rem;
                font-weight: bold;
                color: #000;
                margin-bottom: 10px;
            }

            .product-details p {
                font-size: 1rem;
                color: #555;
                line-height: 1.5;
            }

            /* Tombol khusus */
            .btn-custom {
                background-color: #007bff;
                color: #fff;
                padding: 10px 20px;
                border-radius: 5px;
                text-decoration: none;
                display: inline-block;
                margin-top: 15px;
            }

            .btn-custom:hover {
                background-color: #0056b3;
                color: #fff;
            }

            /* Thumbnail */
            .gallery-item img {
                cursor: pointer;
                transition: transform 0.3s ease-in-out;
            }

            .gallery-item img:hover {
                transform: scale(1.1); /* Efek zoom saat hover */
            }
        </style>

        <!-- Kontainer utama untuk gambar dan teks -->
        <div class="product-container">
            <!-- Bagian gambar -->
            <div class="product-image-container">
                <?php if($product->photos->isNotEmpty()): ?>
                    <!-- Gambar Utama -->
                    <div class="main-image-container text-center">
                        <img id="main-image"
                            src="<?php echo e(route('private.file', ['path' => 'public/' . $product->photos->first()->url])); ?>"
                            alt="<?php echo e($product->name); ?>"
                            class="img-fluid mb-3">
                    </div>
                <?php endif; ?>

                <?php if($product->photos->count() > 1): ?>
                    <!-- Galeri dengan Navigasi -->
                    <div id="gallery-carousel" class="carousel slide mt-4" data-bs-ride="carousel">
                        <!-- Tombol Navigasi -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#gallery-carousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#gallery-carousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>

                        <!-- Kontainer Galeri -->
                        <div class="carousel-inner" style="max-width: 300px; margin: auto;">
                            <?php $__currentLoopData = $product->photos->skip(1)->chunk(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="carousel-item <?php echo e($loop->first ? 'active' : ''); ?>">
                                    <div class="d-flex justify-content-center">
                                        <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="gallery-item me-2">
                                                <img src="<?php echo e(route('private.file', ['path' => 'public/' . $photo->url])); ?>"
                                                    class="img-thumbnail"
                                                    alt="<?php echo e($product->name); ?>"
                                                    onclick="changeImage(this)"
                                                    style="cursor: pointer; width: 80px; height: 80px;">
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tombol Lihat Produk -->
                <div class="text-center">
                    <a href="<?php echo e($product->product_link); ?>" class="btn btn-custom" target="_blank" rel="noopener noreferrer">Lihat Produk</a>
                </div>
            </div>

            <!-- Bagian teks (detail produk) -->
            <div class="product-details">
                <h2><?php echo e($product->name); ?></h2>
                <p><?php echo e($product->description); ?></p>
            </div>
        </div>
        <div class="profile-container">
            <!-- Info Pengirim -->
            <div class="profile-info">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-circle">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"></path>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"></path>
                </svg>
                <span class="profile-name"><?php echo e($product->seller_name); ?></span>
            </div>

        <!-- JavaScript -->
        <script>
            // Fungsi untuk mengganti gambar utama
            function changeImage(element) {
                const mainImage = document.getElementById('main-image');
                mainImage.src = element.src; // Ganti src gambar utama dengan src gambar yang diklik
                mainImage.alt = element.alt; // Ganti alt gambar utama
            }
        </script>
    </section>

        <!-- Related Products -->
        <div class="container py-5">
            <h2>Related Products</h2>
            <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="d-flex">
                                <?php if($related->photos->isNotEmpty()): ?>
                                    <img src="<?php echo e(route('private.file', ['path' => 'public/' . $related->photos->first()->url])); ?>" 
                                         class="card-img-left"
                                         alt="<?php echo e($related->name); ?>">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/150" 
                                         class="card-img-left" 
                                         alt="No Image Available">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo e($related->name); ?></h5>
                                    <p class="card-text"><?php echo e(Str::limit($related->description, 50)); ?></p>
                                    <a href="<?php echo e(route('products.show', $related->id)); ?>" class="btn btn-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No related products found.</p>
                <?php endif; ?>
            </div>
        </div>
            </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\ifmarket\resources\views/detailsproduct/details.blade.php ENDPATH**/ ?>