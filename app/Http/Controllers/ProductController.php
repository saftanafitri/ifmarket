<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data produk dan kirim ke view
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Jika tidak ada produk, Anda bisa mengirimkan data kosong atau null
        return view('products.create', ['product' => null]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'productPhotos' => 'nullable|array|max:9',
            'productPhotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'productVideo' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:102400', // Maksimum 100MB
        ]);

        // Menyimpan foto produk
        $photoPaths = [];
        if ($request->hasFile('productPhotos')) {
            foreach ($request->file('productPhotos') as $file) {
                $photoPaths[] = $file->store('products/photos', 'public');
            }
        }

        // Menyimpan video produk
        $videoPath = null;
        if ($request->hasFile('productVideo')) {
            $videoPath = $request->file('productVideo')->store('products/videos', 'public');
        }

        // Simpan data produk ke database
        Product::create([
            'name' => $request->input('name'),
            'photos' => $photoPaths, // Menyimpan path foto (array)
            'video' => $videoPath,  // Menyimpan path video
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Ambil data produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Kirim data ke view
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id); // Ambil produk berdasarkan ID
        return view('products.edit', compact('product'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        // Validasi dan pembaruan data produk akan ditambahkan di sini
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Hapus data produk
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
