<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Client;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $client = new Client();

        $username = $request->input('username');
        $password = $request->input('password');

        try {
            $response = $client->post('https://api.uinsgd.ac.id/salam/v1/index.php/Auth/loginKalam/', [
                'form_params' => [
                    'username' => $username,
                    'password' => $password,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if ($data['status'] == 'success') {
                // Cek apakah pengguna sudah ada di database
                $existingUser = User::where('username', $username)->first();

                if (!$existingUser) {
                    // Simpan data ke database jika belum ada
                    User::create([
                        'username' => $username,
                        'password' => bcrypt($password), // Simpan password dalam format terenkripsi
                    ]);
                }

                // Login pengguna dan redirect ke halaman dashboard
                session(['user' => $data['userData']]);
                return redirect()->route('home.index');
            } else {
                return redirect()->back()->with('error', 'Username atau password salah');
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan', 'message' => $e->getMessage()]);
        }
    }
}
