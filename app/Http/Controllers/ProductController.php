<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

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

    /**
     * Simpan produk baru (termasuk video).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'required|string',
            'seller_name' => 'required|string|max:255',
            'email' => 'required|email',
            'video' => 'nullable|url',
            'product_link' => 'required|string|max:255', // Tambahkan validasi untuk product_link
        ]);
        

        // Membuat produk baru
        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'seller_name' => $request->seller_name,
            'email' => $request->email,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'github' => $request->github,
            'product_link' => $request->product_link ?? '', // Berikan nilai default jika kosong
            'video' => $request->video,
        ]);
        
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function create()
    {
         // Ambil semua kategori dari tabel categories
    $categories = Category::all();
    
    // Kirim variabel $categories ke view addproduct
    return view('addproduct', compact('categories')); 
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
