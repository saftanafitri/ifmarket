<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


// 1. API login admin
Route::post('/admin/login', function (Request $request) {
    $credentials = $request->validate([
        'username' => ['required'],
        'password' => ['required'],
    ]);

    $user = User::where('username', $credentials['username'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Login gagal'], 401);
    }

    if ($user->role_id !== 1) {
        return response()->json(['message' => 'Bukan admin'], 403);
    }

    $token = $user->createToken('admin-token')->plainTextToken;

    return response()->json([
        'message' => 'Login berhasil',
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
        ],
    ]);
});