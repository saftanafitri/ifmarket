<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    /**
     * Menampilkan daftar produk pending untuk admin (web view).
     */
    public function pending()
    {
        $products = Product::with(['user', 'category'])
            ->where('status', 'pending')
            ->get();

        return view('admin.products.pending', compact('products'));
    }

    /**
     * Menampilkan detail produk untuk admin (web view).
     */
    public function show($slug)
    {
        $product = Product::with(['photos', 'category', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();
    
        return view('admin.products.detail', compact('product'));
    }
    
    /**
     * Approve produk (web request POST).
     */
    public function approve($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'approved']);

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil disetujui.');
    }

    /**
     * Reject produk (web request POST).
     */
    public function reject($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['status' => 'rejected']);

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditolak.');
    }
}