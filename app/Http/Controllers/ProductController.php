<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['sellers', 'photos', 'category'])->get();
        $latestProducts = Product::latest()->with('photos')->take(3)->get();
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
            'seller_name' => 'required|array|min:1',
            'seller_name.*' => 'string|max:255',
            'videoLink' => 'nullable|url',
            'email' => 'required|email',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'product_link' => 'required|url',
            'productPhotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Generate slug dari nama produk
        $slug = Str::slug($request->name);
    
        // Simpan produk dengan slug
        $product = Product::create(array_merge(
            $request->only([
                'name', 'category_id', 'description', 'video', 'email', 'instagram', 'linkedin', 'github', 'product_link'
            ]),
            [
                'seller_name' => $request->seller_name[0],
                'slug' => Str::slug($request->name),
                'video' => $request->videoLink
            ]
        ));
    
        // Simpan data seller ke tabel sellers
        foreach ($request->seller_name as $sellerName) {
            Seller::create([
                'product_id' => $product->id,
                'name' => $sellerName,
            ]);
        }
    
        // Simpan foto produk
        if ($request->hasFile('productPhotos')) {
            foreach ($request->file('productPhotos') as $image) {
                $fileName = $product->id . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('private/public', $fileName);
    
                Photo::create([
                    'product_id' => $product->id,
                    'url' => $fileName,
                ]);
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Produk berhasil disimpan.');
    }    

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'photos', 'sellers'])->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::with('photos')
            ->where('category_id', $product->category_id)
            ->where('slug', '!=', $product->slug)
            ->take(3)
            ->get();

        $youtubeID = $this->parseYoutubeID($product->video);

        return view('products.details', compact('product', 'relatedProducts', 'youtubeID'));
    }

    private function parseYoutubeID($url)
    {
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }

    public function filter($Category = 'All')
    {
        if ($Category !== 'All') {
            // Ambil produk berdasarkan kategori
            $products = Product::whereHas('category', function ($query) use ($Category) {
                $query->where('name', $Category);
            })->with(['category', 'photos'])->get();
    
            // Ambil maksimal 3 produk terbaru berdasarkan kategori
            $latestProducts = Product::whereHas('category', function ($query) use ($Category) {
                $query->where('name', $Category);
            })
            ->with('photos')
            ->latest()
            ->take(3)
            ->get();
        } else {
            // Ambil semua produk tanpa filter kategori
            $products = Product::with(['category', 'photos'])->get();
        
            // Ambil maksimal 3 produk terbaru tanpa filter kategori
            $latestProducts = Product::with('photos')
                ->latest()
                ->take(3)
                ->get();
        }
    
        // Kirimkan kategori aktif untuk menyesuaikan tombol
        $activeCategory = $Category;
    
        // Kirim data ke view
        return view('home.index', compact('products', 'latestProducts', 'activeCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::with('sellers')->findOrFail($id);
        $categories = DB::table('categories')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'description' => 'required|string',
            'seller_name' => 'required|array|min:1', // Validasi array seller
            'seller_name.*' => 'string|max:255',
            'product_link' => 'required|url',
            'productPhotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        // Update data produk
        $product->update($request->only([
            'name', 'category_id', 'description', 'email', 'instagram', 'linkedin', 'github', 'product_link', 'video'
        ]));
    
        // Update data seller
        Seller::where('product_id', $product->id)->delete();
        foreach ($request->seller_name as $sellerName) {
            Seller::create([
                'product_id' => $product->id,
                'name' => $sellerName,
            ]);
        }
    
        // Update foto produk
        if ($request->hasFile('productPhotos')) {
            Photo::where('product_id', $product->id)->delete();
            foreach ($request->file('productPhotos') as $image) {
                $fileName = $product->id . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('public/products', $fileName);
    
                Photo::create([
                    'product_id' => $product->id,
                    'url' => $fileName,
                ]);
            }
        }
    
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::with(['photos', 'sellers'])->findOrFail($id);

        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->url);
        }

        $product->photos()->delete();
        $product->sellers()->delete();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
