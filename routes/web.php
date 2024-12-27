<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;

/*
|---------------------------------------------------------------------------
| Rute untuk Produk
|---------------------------------------------------------------------------
*/

// Rute untuk daftar produk berdasarkan kategori
Route::get('/products/category/{category}', [ProductController::class, 'filter'])->name('products.filter');

// Menampilkan semua produk
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Menampilkan detail produk berdasarkan ID
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

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('index');

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


Route::get('/products/{filename}', function ($filename) {
    $path = storage_path('app/private/public/products/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $file = file_get_contents($path);
    $mimeType = mime_content_type($path);

    return response($file, 200)->header('Content-Type', $mimeType);
})->name('products.show');


Route::get('/product/manage', function () {
    return view('manageproduct');
})->name('manageProduct');
