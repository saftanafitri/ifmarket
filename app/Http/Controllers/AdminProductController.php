<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    // Menambahkan middleware 'admin' di konstruktor
    public function __construct()
    {
        $this->middleware('admin'); // Hanya admin yang bisa mengakses controller ini
    }

    // Method untuk menampilkan halaman admin (Dashboard Admin)
    public function index()
    {
        // Ambil produk yang statusnya 'pending' untuk ditampilkan di halaman admin
        $pendingProducts = Product::where('status', 'pending')->get();

        return view('admin.products.admin', compact('pendingProducts'));  // Mengarahkan ke view admin.blade.php
    }

    // Menampilkan produk yang pending
    public function pending()
    {
        $pendingProducts = Product::where('status', 'pending')->get();
        return view('admin.products.pending', compact('pendingProducts'));
    }

    // Menyetujui produk
    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);  // Update status produk menjadi 'approved'

        return redirect()->route('admin.products.pending')->with('success', 'Produk telah disetujui.');
    }

    // Menolak produk
    public function reject(Product $product)
    {
        $product->update(['status' => 'rejected']);  // Update status produk menjadi 'rejected'

        return redirect()->route('admin.products.pending')->with('success', 'Produk telah ditolak.');
    }
}
