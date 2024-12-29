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
        // Ambil semua produk
        $products = Product::with(['category', 'photos'])->get();
    
        // Ambil maksimal 3 produk terbaru tanpa filter kategori
        $latestProducts = Product::with('photos')
            ->latest()
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
        $categories = DB::table('categories')->get();
        return view('products.addproduct', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'description' => 'required|string',
            'seller_name' => 'required|array', // Pastikan seller_name adalah array
            'videoLink' => 'nullable|url',
            'email' => 'required|email',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'productLink' => 'required|url',
            'productPhotos.*' => 'image|mimes:jpeg,png,jpg|max:2048', // Validasi file gambar
        ]);
    
        // Ubah array menjadi JSON string sebelum disimpan
        $sellerNames = implode(', ', $request->seller_name);
    
        // Simpan data produk
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'seller_name' => $sellerNames, // Simpan dalam format string
            'video' => $request->videoLink,
            'email' => $request->email,
            'instagram' => $request->instagram,
            'linkedin' => $request->linkedin,
            'github' => $request->github,
            'product_link' => $request->productLink
        ]);
    
        // Simpan foto produk
        if ($request->hasFile('productPhotos')) {
            $images = $request->file('productPhotos');
    
            foreach ($images as $image) {
                // Generate nama file unik
                $fileName = $product->id . '-' . time() . '.' . $image->getClientOriginalExtension();
    
                // Simpan ke folder storage
                $imagePath = $image->storeAs('private/public', $fileName);
    
                // Simpan informasi gambar ke database
                Photo::create([
                    'product_id' => $product->id,
                    'url' => $fileName, // Simpan path relatif
                ]);
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Produk berhasil disimpan.');
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Ambil produk berdasarkan ID
        $product = Product::with(['category', 'photos'])->findOrFail($id);
    
        // Ambil produk terkait berdasarkan kategori yang sama, kecuali produk yang sedang ditampilkan
        $relatedProducts = Product::with('photos')
            ->where('category_id', $product->category_id) // Filter kategori yang sama
            ->where('id', '!=', $product->id) // Kecualikan produk yang sedang dilihat
            ->take(3) // Batasi jumlah produk terkait
            ->get();
    
        // Kirim data ke view
        return view('detailsproduct.details', compact('product', 'relatedProducts'));
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
        return view('home.index', compact('products', 'latestProducts', 'activeCategory'));
    }

}
