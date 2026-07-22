<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
    public function boot(): void
    {
        // Project menggunakan Bootstrap 5, sehingga pagination Laravel
        // juga harus memakai template Bootstrap 5 (bukan Tailwind).
        Paginator::useBootstrapFive();
    }
}
