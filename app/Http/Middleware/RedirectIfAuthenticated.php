<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // === AWAL PERUBAHAN ===

                // Cek jika role pengguna adalah 'admin'
                if (Auth::user()->role === 'admin') {
                    // Arahkan ke dashboard admin menggunakan nama rute
                    return redirect()->route('admin.dashboard');
                }

                // Jika bukan admin, arahkan ke HOME default untuk user biasa
                return redirect(RouteServiceProvider::HOME);

                // === AKHIR PERUBAHAN ===
            }
        }

        return $next($request);
    }
}