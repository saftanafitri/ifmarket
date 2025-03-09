<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHomeRequest;
use App\Http\Requests\UpdateHomeRequest;
use Illuminate\Http\Request;
use App\Models\Home;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $category = $request->query('category', 'All'); // Default 'All'

    // Ambil semua produk (dengan filter kategori jika dipilih)
    $products = Product::with(['category', 'sellers', 'photos'])
        ->when($category !== 'All', function ($query) use ($category) {
            return $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        })
        ->paginate(12);

    // Ambil produk terbaru berdasarkan filter kategori
    $latestProducts = Product::with(['category', 'sellers', 'photos'])
        ->when($category !== 'All', function ($query) use ($category) {
            return $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        })
        ->latest()
        ->take(3)
        ->get();

    // Mengirim data ke view
    return view('home.index', compact('products', 'latestProducts', 'category'));
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
        public function filter($Category = 'All')
        {
            if ($Category === 'All') {
                return redirect()->route('home.index');
            }

            // Ambil produk berdasarkan kategori
            $products = Product::whereHas('category', function ($query) use ($Category) {
                $query->where('name', $Category);
            })->with(['category', 'photos'])->paginate(12);

            // Ambil maksimal 3 produk terbaru berdasarkan kategori
            $latestProducts = Product::whereHas('category', function ($query) use ($Category) {
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
