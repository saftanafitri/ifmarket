<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHomeRequest;
use App\Http\Requests\UpdateHomeRequest;
use App\Models\Home;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua produk
        $products = Product::with(['category', 'photos'])->get();
        
        // Ambil maksimal 3 produk terbaru tanpa filter kategori
        $latestProducts = Product::with('photos')
            ->latest() // Urutkan berdasarkan waktu terbaru
            ->take(3) // Ambil maksimal 3 produk
            ->get();
        
        // Kategori aktif default adalah "All"
        $activeCategory = 'All';
        
        return view('home.index', compact('products', 'latestProducts', 'activeCategory'));
    }
    
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHomeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Home $home)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Home $home)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHomeRequest $request, Home $home)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Home $home)
    {
        //
    }
    public function filter($category = 'All')
    {
        // Ambil produk berdasarkan kategori
        if ($category !== 'All') {
            $products = Product::whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            })->with(['category', 'photos'])->get();
    
            // Ambil maksimal 3 produk terbaru berdasarkan kategori
            $latestProducts = Product::whereHas('category', function ($query) use ($category) {
                $query->where('name', $category);
            })
            ->with('photos')
            ->latest()
            ->take(3)
            ->get();
        } else {
            // Jika kategori "All", ambil semua produk
            $products = Product::with(['category', 'photos'])->get();
    
            // Ambil maksimal 3 produk terbaru tanpa filter kategori
            $latestProducts = Product::with('photos')
                ->latest()
                ->take(3)
                ->get();
        }
    
        // Kirimkan kategori aktif untuk menyesuaikan tombol
        $activeCategory = $category;
    
        // Kirim data ke view
        return view('products.filter', compact('products', 'latestProducts', 'activeCategory'));
    }
}
