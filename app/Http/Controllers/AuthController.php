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
    // public function loginUser(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'username' => 'required|string',
    //         'password' => 'required|string',
    //     ]);

    //     $user = User::where('username', $credentials['username'])->first();

    //     if (!$user || !Hash::check($credentials['password'], $user->password)) {
    //         return back()->withErrors(['error' => 'Login gagal, periksa username dan password.']);
    //     }

    //     Auth::login($user);
    //     $request->session()->regenerate();

    //     return redirect()->route('home.index')->with('success', 'Login berhasil sebagai user lokal!');
    // }

    public function loginApi(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginUrl = env('SALAM_API_LOGIN_URL');

        Log::info('Mengirim request login ke API', [
            'url' => $loginUrl,
            'username' => $credentials['username']
        ]);

        $response = Http::withToken(env('SALAM_API_TOKEN'))
            ->post($loginUrl, [
                'username' => $credentials['username'],
                'password' => $credentials['password'],
            ]);

        Log::info('Respons API:', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        if ($response->successful()) {
        $data = $response->json();

        if (!isset($data['data']['username'])) {
            return back()->withErrors(['error' => $data['message'] ?? 'Login gagal, respons API tidak valid.']);
        }

        // Jika login sukses
        $user = User::updateOrCreate(
            ['username' => $data['data']['username']],
            ['password' => Hash::make($credentials['password'])]
        );

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home.index')->with('success', 'Login berhasil sebagai user API!');
    }

    // Tambahan untuk menangkap pesan error dari API jika statusnya bukan 200
    $errorResponse = $response->json();
    return back()->withErrors(['error' => $errorResponse['message'] ?? 'Login gagal, periksa username dan password.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
