<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Seller;
use App\Models\Photo;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{

    protected ImageManager $imageManager;

        public function __construct()
        {
            $this->imageManager = new ImageManager(new Driver());
        }

    /**
     * Display a listing of the resource.*/
    public function index(Request $request)
    {
        $category = $request->query('category', 'All'); // Default 'All'
        $products = Product::approved()
            ->with(['category', 'sellers', 'photos'])
            ->when($category !== 'All', function ($query) use ($category) {
                return $query->whereHas('category', function ($q) use ($category) {
                    $q->where('name', $category);
                });
            })
            ->paginate(12)
            ->onEachSide(2);

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
        // 1. Validasi input, termasuk mengecek nama produk unik di database
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'required|string',
            'seller_name' => 'required|array|min:1',
            'seller_name.*' => 'string|max:255',
            'videoLink' => 'nullable|url',
            'email' => 'required|email',
            'instagram' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'github' => 'nullable|url',
            'product_link' => 'required|url',
            'productPhotos' => 'required|array|min:1',
            'productPhotos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Jika validasi dari backend gagal, kirim response error JSON
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Ambil data yang sudah divalidasi
        $productData = $validator->validated();

        // 3. Proses penyimpanan produk 
        $product = Product::create([
            'name' => $productData['name'],
            'category_id' => $productData['category_id'],
            'description' => $productData['description'],
            'video' => $productData['videoLink'] ?? null,
            'email' => $productData['email'],
            'instagram' => $productData['instagram'] ?? null,
            'linkedin' => $productData['linkedin'] ?? null,
            'github' => $productData['github'] ?? null,
            'product_link' => $productData['product_link'],
            'seller_name' => $productData['seller_name'][0],
            'slug' => Str::slug($productData['name']),
            'user_id' => Auth::id(),
        ]);

        // Simpan data seller
        foreach ($productData['seller_name'] as $sellerName) {
            Seller::create([
                'product_id' => $product->id,
                'name' => $sellerName,
            ]);
        }
        
        // Simpan & resize foto produk
        if ($request->hasFile('productPhotos')) {
            foreach ($request->file('productPhotos') as $imageFile) {

                $fileName = $product->id . '-' . uniqid() . '.jpg';
                $storagePath = 'products/' . $fileName;

                $image = $this->imageManager
                    ->read($imageFile)
                    ->resize(800, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->toJpeg(75);

                // SIMPAN KE STORAGE (local / eksternal)
                Storage::disk('public')->put($storagePath, (string) $image);
                Photo::create([
                    'product_id' => $product->id,
                    'url'        => $storagePath, // SIMPAN PATH SAJA
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dikirim dan sedang menunggu persetujuan admin.'
        ]);
    }
    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $product = Product::approved()
            ->with(['category', 'photos', 'sellers'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::approved()
            ->with(['category', 'photos', 'sellers'])
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

        $products = $products = Product::approved()
            ->whereHas('category', function ($query) use ($Category) {$query
            ->where('name', $Category);})
            ->with(['category', 'photos'])->paginate(12)->onEachSide(2);

        $latestProducts = Product::approved()->whereHas('category', function ($query) use ($Category) {
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
            foreach ($request->file('productPhotos') as $imageFile) {

                $fileName = $product->id . '-' . uniqid() . '.jpg';
                $storagePath = 'products/' . $fileName;

                $image = $this->imageManager
                    ->read($imageFile)
                    ->resize(800, 800, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->toJpeg(75);

                Storage::disk('public')->put($storagePath, (string) $image);
                Photo::create([
                    'product_id' => $product->id,
                    'url'        => $storagePath,
                ]);
            }
        }

    return redirect()->route('products.show', $newSlug)->with('success', 'Produk berhasil diperbarui!');
            
    }
    public function search(Request $request)
    {
    
        $category = $request->input('category', 'All');
        $query = $request->input('query');
        $products = Product::approved()->with('category', 'photos', 'sellers');
    
        if ($category !== 'All') {
            $products = $products->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);
            });
        }

        if ($query) {
            $products = $products->where('name', 'ilike', '%' . $query . '%'); 
        }
    
        $products = $products->paginate(12)->onEachSide(2); 
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
    
        Storage::delete($photo->url);
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
             Storage::delete($photo->url);
        }

        // Hapus relasi sebelum menghapus produk utama
        $product->photos()->delete();
        $product->sellers()->delete();
        $product->delete();

        return redirect()->route('home.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function checkName(Request $request)
    {
        // Validasi bahwa ada input 'name' yang dikirim
        $request->validate(['name' => 'required|string']);
        $inputName = strtolower(trim($request->name));
        $isTaken = Product::withoutGlobalScopes()
                        ->whereRaw('LOWER(TRIM(name)) = ?', [$inputName])
                        ->exists();

        return response()->json([
            'exists' => $isTaken
        ]);
    }
}