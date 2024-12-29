<?php $__env->startSection('title', 'Teknik Informatika'); ?>

<?php $__env->startSection('content'); ?>
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

                <?php $activeCategory = request()->route('category') ?? null; ?>
                <div class="sorting-dropdown">
        <form action="<?php echo e(route('products.filter', $activeCategory)); ?>" method="GET" id="category-form">
        <select name="category" id="category-select" class="form-select" onchange="location.href=this.value;">
            <option value="<?php echo e(route('products.filter', 'All')); ?>" <?php echo e($activeCategory === 'All' ? 'selected' : ''); ?>>All</option>
            <option value="<?php echo e(route('products.filter', 'Kerja Praktik(KP)')); ?>" <?php echo e($activeCategory === 'Kerja Praktik(KP)' ? 'selected' : ''); ?>>Kerja Praktik (KP)</option>
            <option value="<?php echo e(route('products.filter', 'Tugas Akhir(TA)')); ?>" <?php echo e($activeCategory === 'Tugas Akhir(TA)' ? 'selected' : ''); ?>>Tugas Akhir (TA)</option>
            <option value="<?php echo e(route('products.filter', 'Penelitian')); ?>" <?php echo e($activeCategory === 'Penelitian' ? 'selected' : ''); ?>>Penelitian</option>
            <option value="<?php echo e(route('products.filter', 'Pengabdian Masyarakat')); ?>" <?php echo e($activeCategory === 'Pengabdian Masyarakat' ? 'selected' : ''); ?>>Pengabdian pada Masyarakat</option>
            <option value="<?php echo e(route('products.filter', 'Tugas Kuliah')); ?>" <?php echo e($activeCategory === 'Tugas Kuliah' ? 'selected' : ''); ?>>Tugas Kuliah</option>
        </select>
    </form>
</div>
</div>

            <div class="row" id="product-list">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4 col-lg-2 product-item" data-category="<?php echo e($product->category->name); ?>">
                        <div class="card">
                            <?php if($product->photos->isNotEmpty()): ?>
                                
                                <img src="<?php echo e(route('private.file', ['path' => 'public/' . $product->photos->first()->url])); ?>" 
                                     alt="<?php echo e($product->name); ?>" 
                                     class="card-img-top" 
                                     style="border-radius: 0;">
                            <?php else: ?>
                                
                                <img src="<?php echo e(asset('images/placeholder.png')); ?>" 
                                     alt="No Image Available" 
                                     class="card-img-top" 
                                     style="border-radius: 0;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo e($product->name); ?></h3>
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                        class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                        <path fill-rule="evenodd"
                                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                    </svg>
                                    <p class="mb-0 text-nowrap"><?php echo e($product->seller_name); ?></p>
                                </div>
                                <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn custom-btn btn-sm mt-3">View</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            </div>
        </div>
    </section>
                <section>
                    <div class="container mb-5">
                        <h2 class="text-center mb-4">Latest Products</h2>
                        <div class="row g-4">
                            <?php $__empty_1 = true; $__currentLoopData = $latestProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="col-md-4">
                                    <div class="card">
                                        
                                        <?php if($product->photos->isNotEmpty()): ?>
                                            <img src="<?php echo e(route('private.file', ['path' => 'public/' . $product->photos->first()->url])); ?>" 
                                                 alt="<?php echo e($product->name); ?>" 
                                                 class="card-img" 
                                                 style="border-radius: 0;">
                                        <?php else: ?>
                                            
                                            <img src="<?php echo e(asset('images/placeholder.png')); ?>" 
                                                 alt="No Image Available" 
                                                 class="card-img" 
                                                 style="border-radius: 0;">
                                        <?php endif; ?>
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo e($product->name); ?></h5>
                                            <div class="d-flex align-items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                                    class="bi bi-person-circle me-2 text-dark" viewBox="0 0 16 16">
                                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                                    <path fill-rule="evenodd"
                                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                                </svg>
                                                <p class="mb-0 text-nowrap"><?php echo e($product->seller_name); ?></p>
                                            </div>
                                            <p class="text-muted small"><?php echo e($product->created_at->format('d M Y')); ?></p>
                                            <a href="<?php echo e(route('products.show', $product->id)); ?>" class="btn custom-btn btn-sm">View More</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-center">No products available in this category.</p>
                            <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\ifmarket\resources\views/home/index.blade.php ENDPATH**/ ?>