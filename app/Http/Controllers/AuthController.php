<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function authentication(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
    
        // ==== 1. login sebagai Admin ====
        $admin = User::where('username', $credentials['username'])
            ->where('role_id', 1)
            ->first();
    
        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            Auth::login($admin);
            $request->session()->regenerate();
    
            // Buat token Sanctum & simpan di session (untuk keperluan API admin)
            $token = $admin->createToken('admin-token')->plainTextToken;
            session(['admin_token' => $token]);
    
            return redirect()->route('admin.dashboard');
        }
    
        // ==== 2. login sebagai User Eksternal ====
        $response = Http::withToken(env('API_TOKEN'))->post(env('API_LOGIN_URL'), [
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ]);
    
        // // Log::info('Respons API:', [
        // //     'status' => $response->status(),
        // ]);
    
        if ($response->successful() && isset($response['data']['username'])) {
            $data = $response['data'];
    
            // Buat atau update user di DB lokal
            $user = User::updateOrCreate(
                ['username' => $data['username']],
                [
                    'password' => Hash::make($credentials['password']),
                    'role_id' => 2, // Role: user biasa
                ]
            );
    
            Auth::login($user);
            $request->session()->regenerate();
    
            // Simpan foto profil dari API ke session
            if (isset($data['foto_user'])) {
                session(['foto_user' => $data['foto_user']]);
            }
    
            // Simpan token dari API eksternal jika tersedia
            if (isset($data['token'])) {
                session(['user_api_token' => $data['token']]);
            }
    
            return redirect()->route('home.index');
        }
    
        // ==== 3. Gagal Login ====
        return redirect()->route('login')->withErrors([
            'error' => $response->json()['message'] ?? 'Login gagal. Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectBasedOnRole($role_id)
    {
        return match ($role_id) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('home.index'),
            default => redirect()->route('login')->withErrors(['error' => 'Role tidak dikenali.']),
        };
    }
}
