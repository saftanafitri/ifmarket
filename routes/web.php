<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PhotoController;

/*
|--------------------------------------------------------------------------
| Home & Static Pages
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', function () {
    return view('home');
})->name('index');

// Halaman detail (contoh untuk produk atau informasi lainnya)
Route::get('/detail', function () {
    return view('details');
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
| Routes for Product Management (Video)
|--------------------------------------------------------------------------
*/

Route::resource('products', ProductController::class)->except(['edit']); // CRUD untuk produk (video)

/*
|--------------------------------------------------------------------------
| Routes for Photo Management (Foto)
|--------------------------------------------------------------------------
*/

// Tampilkan semua foto untuk produk tertentu
//Route::get('products/{productId}/photos', [PhotoController::class, 'index'])->name('photos.index');

// Tambahkan foto untuk produk tertentu
//Route::post('products/{productId}/photos', [PhotoController::class, 'store'])->name('photos.store');

// Perbarui foto
//Route::patch('photos/{id}', [PhotoController::class, 'update'])->name('photos.update');

// Hapus foto
//Route::delete('photos/{id}', [PhotoController::class, 'destroy'])->name('photos.destroy');
//Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');


/*
|--------------------------------------------------------------------------
| Manage Products Page (Optional)
|--------------------------------------------------------------------------
*/

Route::get('/product/manage', function () {
    return view('manageProduct.index');
})->name('manageProduct');
Route::resource('products', ProductController::class);
