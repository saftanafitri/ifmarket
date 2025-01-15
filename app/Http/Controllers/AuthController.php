<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil URL login dari .env
        $loginUrl = env('SALAM_API_LOGIN_URL');

        if (!$loginUrl) {
            return back()->withErrors(['error' => 'URL login tidak dikonfigurasi.']);
        }

                // Log request login
        Log::info('Mengirim request login ke API Salam', [
            'username' => $credentials['username'],
            'login_url' => $loginUrl,
        ]);

        // Kirim permintaan POST ke API SALAM
        $response = Http::withToken(env('SALAM_API_TOKEN'))
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($loginUrl, [
                'username' => $credentials['username'],
                'password' => $credentials['password'],
            ]);

            Log::info('Respons dari API SALAM:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

        if ($response->successful()) {
            // Ambil data user dari response
            $data = $response->json();

            // Ambil data username dan password dari API
            $apiUsername = $data['data']['username'];
            $apiPassword = $credentials['password']; // Gunakan password asli dari input, API tidak mengembalikan password

            // Simpan atau perbarui data pengguna di database
            $user = User::updateOrCreate(
                ['username' => $apiUsername], // Cek berdasarkan username
                ['password' => Hash::make($apiPassword)] // Enkripsi password sebelum menyimpan
            );

            // Login pengguna menggunakan model User
            Auth::login($user);

            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            Log::info('Login berhasil', [
                'user' => $user
            ]);

            return redirect()->route('home.index')->with('success', 'Login berhasil!');
        } else {
            return back()->withErrors(['error' => 'Login gagal, periksa username dan password.']);
        }
    }

    public function logout(Request $request)
    {
        // Hapus session dan logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User telah logout');

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
