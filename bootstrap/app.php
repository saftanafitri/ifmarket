<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->web(append: [
                \App\Http\Middleware\PreventBackHistory::class,
        ]);
        $middleware->alias([
            'onlyadmin' => \App\Http\Middleware\OnlyAdmin::class,
            'onlyuser' => \App\Http\Middleware\OnlyUser::class,
            'onlyguest' => \App\Http\Middleware\OnlyGuest::class, 
            'user-access' => \App\Http\Middleware\PreventAdminAccess::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Illuminate\Session\TokenMismatchException $e) {
            return redirect()->route('login')
                ->with('error', 'Sesi Anda telah berakhir, halaman perlu dimuat ulang. Silakan login kembali.');
        });
    })->create();
