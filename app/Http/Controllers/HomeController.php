<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan daftar produk.
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'All');
    
        // Membuat query builder
        $productsQuery = Product::with(['category', 'sellers', 'photos'])
            ->where('status', 'approved')
            ->when($category !== 'All', function ($query) use ($category) {
                return $query->whereHas('category', function ($q) use ($category) {
                    $q->where('name', $category);
                });
            });
    
        // Mengeksekusi query dan mengkonfigurasi pagination
        /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
        $products = $productsQuery->latest()->paginate(12);
        $products->onEachSide(2);

        $latestProducts = Product::with(['category', 'sellers', 'photos'])
            ->where('status', 'approved')
            ->when($category !== 'All', function ($query) use ($category) {
                return $query->whereHas('category', function ($q) use ($category) {
                    $q->where('name', 'LIKE', $category);
                });
            })
            ->latest()
            ->take(3)
            ->get();
    
        $isLoggedIn = Auth::check();
        $isUser = $isLoggedIn && Auth::user()->role_id === 2;
    
        return view('home.index', compact(
            'products', 'latestProducts', 'category', 'isLoggedIn', 'isUser'
        ));    
    }       
    
    /**
     * Menampilkan produk yang sudah difilter berdasarkan kategori.
     */
    public function filter($Category = 'All')
    {
        if ($Category === 'All') {
            return redirect()->route('home.index');
        }

        $productsQuery = Product::where('status', 'approved')
            ->whereHas('category', function ($query) use ($Category) {
                $query->where('name', $Category);
            })
            ->with(['category', 'photos']);

        // Mengeksekusi query dan mengkonfigurasi pagination
        /** @var \Illuminate\Pagination\LengthAwarePaginator $products */
        $products = $productsQuery->latest()->paginate(12);
        $products->onEachSide(2);

        $latestProducts = Product::where('status', 'approved')
            ->whereHas('category', function ($query) use ($Category) {
                $query->where('name', $Category);
            })
            ->with('photos')
            ->latest()
            ->take(3)
            ->get();

        $activeCategory = $Category;

        return view('home.index', compact('products', 'latestProducts', 'activeCategory'));
    }
}