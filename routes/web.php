<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;

/*
|--------------------------------------------------------------------------
| Rute Area Publik & User
|--------------------------------------------------------------------------
|
| Semua rute di dalam grup ini akan dilindungi oleh middleware 'user-access'.
| Jika admin yang sedang login mencoba mengakses rute-rute ini,
| ia akan otomatis di-logout terlebih dahulu.
|
*/
Route::middleware(['user-access'])->group(function () {

    // Rute Produk (Terbuka untuk tamu dan user)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/category/{category}', [ProductController::class, 'filter'])->name('products.filter');
    Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/api/product/{slug}', [ProductController::class, 'getUpdatedProduct'])->name('api.product.details');

    // Landing Page & Halaman Statis (Terbuka untuk tamu dan user)
    Route::get('/', fn () => view('landing'))->name('index');
    Route::get('/detail', fn () => view('details'))->name('detail');
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');

    // Rute Autentikasi Login (Hanya untuk tamu)
    Route::prefix('auth')->group(function () {
        Route::middleware(['onlyguest'])->get('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/login', [AuthController::class, 'authentication'])->name('auth.login');
    });

    // Manajemen Produk (Hanya untuk User Biasa yang Login)
    Route::middleware(['auth', 'onlyuser'])->group(function () {
        Route::get('/product/addproduct', [ProductController::class, 'create'])->name('products.create');
        Route::post('/home/products', [ProductController::class, 'store'])->name('products.store');
        Route::post('/products/check-name', [ProductController::class, 'checkName'])->name('products.checkName');
        Route::get('/product/manage', [ProductController::class, 'manageProducts'])->name('manageProduct');
        Route::get('/products/{slug}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{slug}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{slug}/photos/{photoId}', [ProductController::class, 'deletePhoto'])->name('products.photos.delete');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

});


/*
|--------------------------------------------------------------------------
| Rute Logout (Global)
|--------------------------------------------------------------------------
|
| Ditempatkan di luar grup agar bisa diakses oleh semua peran yang login
| (baik user maupun admin dari dashboard-nya).
|
*/
Route::middleware('auth')->post('/auth/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Rute Area Admin
|--------------------------------------------------------------------------
|
| Grup ini tetap sama, terpisah dari area user dan dilindungi
| oleh middleware 'auth' dan 'onlyadmin'.
|
*/
Route::middleware(['auth', 'onlyadmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/products/pending', [AdminProductController::class, 'pending'])->name('admin.products.pending');
    Route::get('/admin/products/{slug}', [AdminProductController::class, 'show'])->name('admin.products.show');
    Route::post('/admin/products/{id}/approve', [AdminProductController::class, 'approve'])->name('admin.products.approve');
    Route::post('/admin/products/{id}/reject', [AdminProductController::class, 'reject'])->name('admin.products.reject');
});