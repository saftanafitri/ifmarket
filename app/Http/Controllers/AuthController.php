<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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

        // Periksa apakah URL login tersedia
        if (!$loginUrl) {
            return back()->withErrors(['error' => 'URL login tidak dikonfigurasi.']);
        }

        // Log data request sebelum dikirim ke API
        Log::info('Mengirim request ke SALAM API', [
            'url' => $loginUrl,
            'token' => env('SALAM_API_TOKEN'),
            'username' => $credentials['username'],
            'password' => $credentials['password'],
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

        // Log respons dari API
        Log::info('Respons dari SALAM API:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->successful()) {
            // Jika login berhasil, ambil data dari response
            $data = $response->json();

            // Simpan data login ke session
            session([
                'is_logged_in' => true,
                'user_data' => $data, // Simpan semua data user yang dikembalikan API
            ]);

            // Redirect ke halaman home
            return redirect()->route('home.index')->with('success', 'Login berhasil!');
        } else {
            // Jika login gagal
            return back()->withErrors(['error' => 'Login gagal, periksa username dan password.']);
        }
    }

    public function logout()
    {
        // Hapus session login
        session()->forget(['is_logged_in', 'user_data']);

        // Redirect ke halaman login
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
