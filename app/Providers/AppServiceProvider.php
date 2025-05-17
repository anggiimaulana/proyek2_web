<?php

namespace App\Providers;

use App\Http\Middleware\AuthenticateKuwu;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // Daftarkan middleware dengan nama 'auth.kuwu'
        Route::aliasMiddleware('auth.kuwu', AuthenticateKuwu::class);

        // Atau langsung registrasi middleware dengan nama 'auth.kuwu'
        // Kalau middlewareGroup gak jalan, coba ini:
        // Route::aliasMiddleware('auth.kuwu', AuthenticateKuwu::class);

    }
}
