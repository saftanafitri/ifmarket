<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
/*
|---------------------------------------------------------------------------
| Rute untuk Produk
|---------------------------------------------------------------------------
*/

// Rute untuk daftar produk berdasarkan kategori
Route::get('/products/category/{category?}', [ProductController::class, 'filter'])->name('products.filter');
// Menampilkan semua produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Menampilkan detail produk berdasarkan nama
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
// Membuat produk baru
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('products', [ProductController::class, 'store'])->name('products.store');
// Sumber daya produk (CRUD)
//Route::resource('products', ProductController::class);
Route::get('addproduct', [ProductController::class, 'create'])->name('products.create');

/*
|---------------------------------------------------------------------------
| Rute untuk Halaman Statis
|---------------------------------------------------------------------------
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
|---------------------------------------------------------------------------
| Rute untuk Autentikasi
|---------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    // Login
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function () {
        return redirect()->route('index'); // Redirect ke halaman beranda setelah login
    })->name('login.submit');

    // Register
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', function () {
        return redirect()->route('login'); // Redirect ke halaman login setelah register
    })->name('register.submit');
});

/*
|---------------------------------------------------------------------------
| Rute untuk Manajemen Produk
|---------------------------------------------------------------------------
*/

Route::get('/product/manage', function () {
    return view('manageproduct');
})->name('manageProduct');


Route::get('/storage/private/{path}', function ($path) {
    $filePath = "private/{$path}";

    // Periksa apakah file ada
    if (!Storage::disk('local')->exists($filePath)) {
        abort(404, 'File not found.');
    }

    // Sajikan file
    return response()->file(storage_path("app/{$filePath}"));
})->where('path', '.*')->name('private.file');
