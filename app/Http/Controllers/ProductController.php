<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use app\Models\Photo;

class ProductController extends Controller
{
    /**
     * Tampilkan semua produk.
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    /**
     * Simpan produk baru (termasuk video).
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
     * Tampilkan detail produk tertentu.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Perbarui video produk tertentu.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'video' => 'nullable|url',
        ]);

        $product = Product::findOrFail($id);

        // Hanya perbarui video
        $product->update([
            'video' => $request->input('video'),
        ]);

        return redirect()->route('products.index')->with('success', 'Video berhasil diperbarui.');
    }

    /**
     * Hapus produk tertentu.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus produk
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
