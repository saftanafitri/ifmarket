<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

/*
|---------------------------------------------------------------------------|
| Rute untuk Produk                                                           |
|---------------------------------------------------------------------------|
*/

// Rute untuk daftar produk berdasarkan kategori
Route::get('/products/category/{category?}', [ProductController::class, 'filter'])->name('products.filter');

// Menampilkan semua produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Menampilkan detail produk berdasarkan nama
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Sumber daya produk (CRUD)
// Route::resource('products', ProductController::class);

/*
|---------------------------------------------------------------------------|
| Rute untuk Halaman Statis                                                   |
|---------------------------------------------------------------------------|
*/

// Rute untuk landing page
Route::get('/', function () {
    return view('landing');
})->name('index');

// Rute untuk halaman home
Route::get('/home', [HomeController::class, 'index'])->name('home.index');

// Detail halaman statis
Route::get('/detail', function () {
    return view('details');
})->name('detail');

/*
|---------------------------------------------------------------------------|
| Rute untuk Autentikasi                                                      |
|---------------------------------------------------------------------------|
*/

// Group Auth
Route::prefix('auth')->group(function () {
    // Halaman Login hanya dapat diakses oleh pengguna yang belum login (guest)
    Route::middleware('guest')->get('/login', function () {
        return view('auth.login'); // Menampilkan halaman login
    })->name('login');

    // Login Submission
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // Logout
    Route::post('/logout', function () {
        session()->forget(['is_logged_in', 'user_data']); // Hapus status login dan data pengguna dari session
        return redirect()->route('login'); // Redirect ke halaman login
    })->name('logout');
});


/*
|---------------------------------------------------------------------------|
| Rute untuk Manajemen Produk                                                |
|---------------------------------------------------------------------------|
*/


// Gunakan Middleware untuk melindungi rute yang membutuhkan login
Route::middleware(['auth'])->group(function () {
    // Membuat produk baru
    Route::get('/product/addproduct', [ProductController::class, 'create'])->name('products.create');
    Route::post('/home/products', [ProductController::class, 'store'])->name('products.store');

    // Halaman untuk mengelola produk
    Route::get('/product/manage', [ProductController::class, 'manageProducts'])->name('manageProduct');
});

/*
|---------------------------------------------------------------------------|
| Rute untuk mengakses file di storage                                        |
|---------------------------------------------------------------------------|
*/

Route::get('/storage/private/{path}', function ($path) {
    $filePath = "private/{$path}";

    // Periksa apakah file ada
    if (!Storage::disk('local')->exists($filePath)) {
        abort(404, 'File not found.');
    }

    // Sajikan file
    return response()->file(storage_path("app/{$filePath}"));
})->where('path', '.*')->name('private.file');
