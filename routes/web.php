<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Routes for Product Management
|--------------------------------------------------------------------------
*/

// Halaman untuk membuat produk baru
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Menyimpan produk baru
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

// Menampilkan detail produk berdasarkan ID
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| Home & Static Pages
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', function () {
    return view('home.index');
})->name('index');

// Halaman detail (contoh untuk produk atau informasi lainnya)
Route::get('/detail', function () {
    return view('details.index');
})->name('detail');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| Additional Routes for Product Management
|--------------------------------------------------------------------------
*/

// Menambahkan produk (form add product)
Route::get('/product/add', function () {
    return view('addProduct.index');
})->name('addProduct');

// Mengelola produk
Route::get('/product/manage', function () {
    return view('manageProduct.index');
})->name('manageProduct');
