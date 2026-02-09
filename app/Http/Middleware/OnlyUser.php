<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
 
public function handle(Request $request, Closure $next)
{
    // Cek dulu apakah user sudah login
    if (!Auth::check()) {
        return redirect()->route('auth.login'); 
    }

    if (Auth::user()->role_id != 2) {
        return redirect()->route('admin.dashboard'); 
    }

    // Jika user biasa, izinkan akses
    return $next($request);
}
}
