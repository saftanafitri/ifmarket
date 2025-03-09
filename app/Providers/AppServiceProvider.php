<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(){
    // Force Laravel to use the URL from .env (APP_URL)
    // URL::forceRootUrl(config('app.url'));

    //     // Optional: Force HTTPS if you're using SSL
    //     if (config('app.env') === 'production') {
    //         URL::forceScheme('https');
    //     }
    }
}
