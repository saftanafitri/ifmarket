<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Photo;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.*/
public function index(Request $request)
{
    $category = $request->query('category', 'All'); // Default 'All'

    // Ambil semua produk yang disetujui (dengan filter kategori jika dipilih)
    $products = Product::approved()
        ->with(['category', 'sellers', 'photos'])
        ->when($category !== 'All', function ($query) use ($category) {
            return $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        })
        ->paginate(12);

    // Ambil produk terbaru yang disetujui berdasarkan filter kategori
    $latestProducts = Product::approved()
        ->with(['category', 'sellers', 'photos'])
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
            'productPhotos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $slug = Str::slug($request->name);
        $product = Product::create(array_merge(
            $request->only([
                'name', 'category_id', 'description', 'video', 'email', 'instagram', 'linkedin', 'github', 'product_link'
            ]),
            [
                'seller_name' => $request->seller_name[0],
                'slug' => Str::slug($request->name),
                'video' => $request->videoLink,
                'user_id' => Auth::id(),
            ]
        ));
    
        // Simpan data seller ke tabel sellers
        foreach ($request->seller_name as $sellerName) {
            Seller::create([
                'product_id' => $product->id,
                'name' => $sellerName,
            ]);
        }
        
// Simpan & resize foto produk
if ($request->hasFile('productPhotos')) {
    $request->validate([
        'productPhotos.*' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validasi format & ukuran
    ]);

    foreach ($request->file('productPhotos') as $image) {
        $fileName = $product->id . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
        $storagePath = "public/products/{$fileName}"; 

        // Ambil ukuran asli sebelum diresize
        // $originalSize = $image->getSize(); 

        // Resize & simpan gambar
        $resizedImage = Image::make($image)
            ->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save(storage_path("app/{$storagePath}"), 50); // Simpan di storage

        // **Pastikan file benar-benar tersimpan sebelum cek ukuran**
        // if (file_exists(storage_path("app/{$storagePath}"))) {
        //     $scaledSize = filesize(storage_path("app/{$storagePath}"));
        //     dd('Sebelum: ' . $originalSize . ' bytes, Sesudah: ' . $scaledSize . ' bytes');
        // } else {
        //     dd("File tidak ditemukan: " . storage_path("app/{$storagePath}"));
        // }

        // Simpan path gambar ke database
        Photo::create([
            'product_id' => $product->id,
            'url' => "storage/products/{$fileName}", // Path relatif ke storage/public
        ]);
    }
}

return redirect()->route('home.index')->with('success', 'Produk berhasil disimpan.');
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
        if ($Category === 'All') {
            return redirect()->route('home.index');
        }

        $products = Product::whereHas('category', function ($query) use ($Category) {
            $query->where('name', $Category);
        })->with(['category', 'photos'])->paginate(12);

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


    public function manageProducts()
    {
        
        $user = Auth::user(); 
        $products = Product::where('user_id', $user->id)->with('photos')->get();
        return view('products.manageproduct', compact('products'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $categories = Category::all(); // Mengambil semua kategori untuk dropdown
    
        return view('products.editproduct', compact('product', 'categories'));
    }
    

    /**
     * Update the specified resource in storage.
     */

public function update(Request $request, $slug)
{
    $product = Product::where('slug', $slug)->firstOrFail();

    // Validasi input
    $request->validate([
        'name'          => 'required|string|max:255',
        'category_id'   => 'required|integer|exists:categories,id',
        'description'   => 'required|string',
        'seller_name'   => 'required|array|min:1', // Array seller
        'seller_name.*' => 'string|max:255',
        'seller_id'     => 'nullable|array', // ID seller untuk yang diedit
        'seller_id.*'   => 'nullable|integer|exists:sellers,id',
        'email'         => 'required|email|max:255',
        'instagram'     => 'nullable|url|max:255',
        'linkedin'      => 'nullable|url|max:255',
        'github'        => 'nullable|url|max:255',
        'product_link'  => 'required|url|max:255',
        'video'         => 'nullable|url|max:255',
        'productPhotos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Update data produk
    $product->update([
        'name'         => $request->name,
        'category_id'  => $request->category_id,
        'description'  => $request->description,
        'email'        => $request->email,
        'instagram'    => $request->instagram,
        'linkedin'     => $request->linkedin,
        'github'       => $request->github,
        'product_link' => $request->product_link,
        'video'        => $request->video,
    ]);

    $newSlug = Str::slug($request->name);
    $count = Product::where('slug', $newSlug)->where('id', '!=', $product->id)->count();
    if ($count > 0) {
        $newSlug = $newSlug . '-' . uniqid();  
    }

    $product->update(['slug' => $newSlug]);
    $existingSellers = $product->sellers->keyBy('id');
    $submittedSellerIds = [];

    foreach ($request->seller_name as $index => $sellerName) {
        if (!empty($request->seller_id[$index]) && isset($existingSellers[$request->seller_id[$index]])) {
            $existingSellers[$request->seller_id[$index]]->update(['name' => $sellerName]);
            $submittedSellerIds[] = $request->seller_id[$index];
        } else {
            $newSeller = Seller::create([
                'product_id' => $product->id,
                'name'       => $sellerName,
            ]);
            $submittedSellerIds[] = $newSeller->id;
        }
    }

    // Hapus seller yang tidak ada dalam input terbaru
    $sellersToDelete = $existingSellers->keys()->diff($submittedSellerIds);
    if ($sellersToDelete->isNotEmpty()) {
        Seller::whereIn('id', $sellersToDelete)->delete();
    }


if ($request->hasFile('productPhotos')) {
        $request->validate([
        'productPhotos.*' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);
    foreach ($request->file('productPhotos') as $image) {
        $fileName = $product->id . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
        $storagePath = storage_path("app/public/products/{$fileName}"); 

        // Resize dan simpan gambar
        Image::make($image)
            ->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio(); 
                $constraint->upsize(); 
            })
            ->save($storagePath, 50); 

        // Simpan path gambar ke database
        Photo::create([
            'product_id' => $product->id,
            'url'        => "storage/products/{$fileName}", // Path relatif
        ]);
    }
}

return redirect()->route('products.show', $newSlug)->with('success', 'Produk berhasil diperbarui!');
          
}
    public function search(Request $request)
    {
    
        $category = $request->input('category', 'All');
        $query = $request->input('query');
        $products = Product::with('category', 'photos', 'sellers');
    
        if ($category !== 'All') {
            $products = $products->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        if ($query) {
            $products = $products->where('name', 'ilike', '%' . $query . '%'); 
        }
    
        $products = $products->paginate(12); 
        $latestProducts = Product::with('photos')->latest()->take(3)->get();
        $activeCategory = $category;
    
        // Kirim data ke view
        return view('home.index', compact('products', 'latestProducts', 'category', 'query'));
    }
    
   
    public function deletePhoto($slug, $photoId)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $photo = Photo::where('id', $photoId)->where('product_id', $product->id)->first();
    
        if (!$photo) {
            return response()->json(['success' => false, 'message' => 'Foto tidak ditemukan.'], 404);
        }
    
        Storage::disk('public')->delete('products/' . $photo->url);
        $photo->delete();
        return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
    }               

    public function getUpdatedProduct($slug)
    {
        $product = Product::with(['photos', 'sellers'])->where('slug', $slug)->first();
        
        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }
    
        return response()->json([
            'name'                 => $product->name,
            'description'          => $product->description,
            'seller_name'          => $product->sellers->first()->name, // Seller utama
            'email'                => $product->email,
            'instagram'            => $product->instagram,
            'linkedin'             => $product->linkedin,
            'github'               => $product->github,
            'product_link'         => $product->product_link,
            'videoLink'            => $product->videoLink,
            'photos'               => $product->photos->map(function ($photo) {
                return [
                    'url' => route('public.file', ['path' => 'products/' . $photo->url]),
                ];
            })
        ]);
    }    
    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $product = Product::with(['photos', 'sellers'])->findOrFail($id);
    if (Auth::id() !== $product->user_id) {
        return redirect()->route('products.index')->with('error', 'Anda tidak memiliki izin untuk menghapus produk ini.');
    }

    foreach ($product->photos as $photo) {
        Storage::disk('public')->delete("products/{$photo->url}");
    }

    // Hapus relasi sebelum menghapus produk utama
    $product->photos()->delete();
    $product->sellers()->delete();
    $product->delete();

    return redirect()->route('home.index')->with('success', 'Produk berhasil dihapus.');
}

}
