<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminApiController extends Controller
{
    public function dashboard(Request $request)
    {
        return response()->json([
            'message' => 'Selamat datang di API Admin',
            'admin' => $request->user(), 
        ]);
    }
}
