<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Photo;


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
        $categories = DB::table('categories')->get();
        return view('addProduct.index', compact('categories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|string|max:255',
        //     'category' => 'required|string',
        //     'description' => 'required|string',
        //     'seller_name' => 'required|string',
        //     'email' => 'required|string',
        //     'productLink' => 'required|string'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        // Menyimpan produk
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'seller_name' => $request->seller_name,
            'email' => $request->email,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'github' => $request->github,
            'product_link' => $request->productLink


        ]);
        // id => identitas
        // simpan video dan foto
        // simapn video berhasil maka update table product berdasarkan $product->id

        // foto productPhotos
        // video productVideo
        // Menyimpan gambar-gambar produk
        // if ($request->hasFile('images')) {
        //     foreach ($request->file('images') as $image) {
        //         $path = $image->store('product_images', 'public');  // Menyimpan gambar ke folder 'storage/app/public/product_images'

        //         Photo::create([
        //             'product_id' => $product->id,
        //             'image_path' => $path,
        //         ]);
        //     }
        // }
        return redirect()->route('index')->with('success', 'Product created successfully');
    }

    /*public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Validate category
            'description' => 'required|string',
            'seller_name' => 'required|string|max:255',
            'email' => 'required|email',
            'productPhotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate product photos
        ]);

        // Store product data in the products table
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'seller_name' => $request->seller_name,
            'email' => $request->email,
        ]);

        // Process the uploaded product photos
        if ($request->hasFile('productPhotos')) {
            foreach ($request->file('productPhotos') as $file) {
                // Store the file and get the file path
                $path = $file->store('products/photos', 'public');

                // Create a new photo entry in the photos table
                Photo::create([
                    'url' => $path,
                    'product_id' => $product->id, // Link photo to the product
                ]);
            }
        }

        // Redirect back with a success message
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }*/

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
