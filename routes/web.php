<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProductController;

/*
|--------------------------------------------------------------------------
| Public Routes (Bisa diakses tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => view('landing'))->name('index');

/* Home bisa dilihat guest */
Route::get('/home', [HomeController::class, 'index'])->name('home.index');

/* Produk publik */
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/category/{category}', [ProductController::class, 'filter'])->name('products.filter');
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('products.show');
});

/* API */
Route::get('/api/product/{slug}', [ProductController::class, 'getUpdatedProduct'])
    ->name('api.product.details');


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authentication'])->name('auth.login');
});

Route::middleware('auth')->post('/auth/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| User Area (Role 2)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'onlyuser'])->group(function () {

        Route::get('/product/add-product', [ProductController::class, 'create'])->name('products.create');
        Route::post('/home/store-product', [ProductController::class, 'store'])->name('products.store');
        Route::post('/product/check-name', [ProductController::class, 'checkName'])->name('products.checkName');

        Route::get('/product/manage', [ProductController::class, 'manageProducts'])->name('manageProduct');

        Route::get('/products/{slug}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{slug}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{slug}/photos/{photoId}', [ProductController::class, 'deletePhoto'])->name('products.photos.delete');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

});


/*
|--------------------------------------------------------------------------
| Admin Area (Role 1)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth', 'onlyadmin'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::prefix('products')->group(function () {

            Route::get('/pending', [AdminProductController::class, 'pending'])
                ->name('admin.products.pending');

            Route::get('/{slug}', [AdminProductController::class, 'show'])
                ->name('admin.products.show');

            Route::post('/{id}/approve', [AdminProductController::class, 'approve'])
                ->name('admin.products.approve');

            Route::post('/{id}/reject', [AdminProductController::class, 'reject'])
                ->name('admin.products.reject');
        });

});
