<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
    $products = Product::where('status', 'pending')->latest()->paginate(10); 

    return view('admin.dashboard', compact('products'));
}
}