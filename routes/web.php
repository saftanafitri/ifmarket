<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminProductController;
use Illuminate\Support\Facades\Auth;

/*
|---------------------------------------------------------------------------|
| Rute untuk Produk                                                         |
|---------------------------------------------------------------------------|
*/

// Menampilkan semua produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/category/{category}', [ProductController::class, 'filter'])->name('products.filter');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/api/product/{slug}', [ProductController::class, 'getUpdatedProduct'])->name('api.product.details');

/*
|---------------------------------------------------------------------------|
| Rute untuk Halaman Statis                                                 |
|---------------------------------------------------------------------------|
*/

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('index');

// Halaman home 
Route::get('/home', [HomeController::class, 'index'])->name('home.index');

// Detail halaman statis
Route::get('/detail', function () {
    return view('details');
})->name('detail');

/*
|---------------------------------------------------------------------------|
| Rute untuk Autentikasi (Login & Logout)                                   |
|---------------------------------------------------------------------------|
*/

Route::prefix('auth')->group(function () {
    Route::middleware('guest')->get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login/admin', [AuthController::class, 'loginAdmin'])->name('login.admin');
    Route::post('/login/user', [AuthController::class, 'loginUser'])->name('login.user');
    Route::post('/login/api', [AuthController::class, 'loginApi'])->name('login.api');

    Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');
});


/*
|---------------------------------------------------------------------------|
| Rute untuk Manajemen Produk (Hanya User yang Sudah Login)                 |
|---------------------------------------------------------------------------|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/product/addproduct', [ProductController::class, 'create'])->name('products.create');
    Route::post('/home/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/product/manage', [ProductController::class, 'manageProducts'])->name('manageProduct');
    Route::get('/products/{slug}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{slug}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{slug}/photos/{photoId}', [ProductController::class, 'deletePhoto'])->name('products.photos.delete');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});

/*
|---------------------------------------------------------------------------|
| Rute untuk Admin (Hanya Bisa Diakses oleh Admin)                          |
|---------------------------------------------------------------------------|
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/pending', [AdminProductController::class, 'pending'])->name('admin.products.pending');
    Route::post('/products/{product}/approve', [AdminProductController::class, 'approve'])->name('admin.products.approve');
    Route::post('/products/{product}/reject', [AdminProductController::class, 'reject'])->name('admin.products.reject');
});

/*
|---------------------------------------------------------------------------|
| Rute untuk Mengakses File di Storage                                      |
|---------------------------------------------------------------------------|
*/

// Route::get('/storage/private/{path}', function ($path) {
//     $filePath = "private/{$path}";

//     // Cek apakah file ada
//     if (!Storage::exists($filePath)) {
//         abort(404, 'File not found.');
//     }

//     // Hanya izinkan akses untuk format tertentu
//     $allowedExtensions = ['jpg', 'jpeg', 'png'];
//     $extension = pathinfo($filePath, PATHINFO_EXTENSION);

//     if (!in_array($extension, $allowedExtensions)) {
//         abort(403, 'Access denied.');
//     }

//     // Kirim file sebagai response
//     return Response::file(storage_path("app/{$filePath}"));
// })->where('path', '.*')->name('private.file');
