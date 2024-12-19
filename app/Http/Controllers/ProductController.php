<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all products and send them to the view
        $products = Product::all();
        return view('home.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('addProduct.index');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
        return 'ok';
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Retrieve product by ID
        $product = Product::findOrFail($id);

        // Send data to the view
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Retrieve the product by ID
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Validate data
        $request->validate([
            'name' => 'required|string|max:255',
            'productPhotos' => 'nullable|array|max:9',
            'productPhotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Only images
        ]);

        // Update product photos
        $photoPaths = json_decode($product->photos, true) ?: [];
        if ($request->hasFile('productPhotos')) {
            // Delete old photos (optional)
            foreach ($photoPaths as $photo) {
                Storage::disk('public')->delete($photo);
            }

            // Store new photos
            $photoPaths = [];
            foreach ($request->file('productPhotos') as $file) {
                $photoPaths[] = $file->store('products/photos', 'public');
            }
        }

        // Update product data
        $product->update([
            'name' => $request->input('name'),
            'photos' => json_encode($photoPaths), // Update photos path as JSON
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Delete associated photos
        $photoPaths = json_decode($product->photos, true) ?: [];
        foreach ($photoPaths as $photo) {
            Storage::disk('public')->delete($photo);
        }

        // Delete the product
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
