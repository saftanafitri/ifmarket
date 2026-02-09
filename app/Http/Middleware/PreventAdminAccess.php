<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah ada pengguna yang login DAN perannya adalah 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Jika ya, logout pengguna tersebut
            Auth::logout();

            // Hapus sesi dan buat ulang token CSRF
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Arahkan kembali ke halaman yang sama, tapi sekarang sebagai tamu
            return redirect($request->fullUrl())->with('info', 'Anda telah logout dari sesi admin.');
        }

        // Jika bukan admin, izinkan untuk melanjutkan
        return $next($request);
    }
}