<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlyGuest
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
        if (Auth::check()) {
            // Arahkan pengguna yang sudah login ke halaman sesuai perannya
            if (Auth::user()->role_id == 1) {
                return redirect('admin.dashboard'); 
            } elseif (Auth::user()->role_id == 2) {
                return redirect('home.index');
            }
        }
        return $next($request);
    }
}
