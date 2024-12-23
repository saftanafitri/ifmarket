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


        // dd($request->file('productPhotos'));
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
            'video'=>$request->videoLink,
            'email' => $request->email,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'github' => $request->github,
            'product_link' => $request->productLink
        ]);

        // Photo ini akan di masukkan kedalam product id;
        if($request->hasFile('productPhotos')){
            $images = $request->file('productPhotos');

            foreach ($images as $key => $image) {
                // Store the image file
                $imagePath = 'storage/products/' . $product->id . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/products', $product->id . '.' . $image->getClientOriginalExtension());

                // Save image details in the database
                Photo::create([
                    'product_id' => $product->id,
                    'url' => 'storage/' . $imagePath,
                ]);
            }
        };

        return redirect()->route('index')->with('success', 'Product created successfully');
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
