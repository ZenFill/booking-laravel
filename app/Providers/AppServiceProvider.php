<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // <--- 1. Tambahkan Baris Ini

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
        Paginator::useTailwind(); // <--- 2. Tambahkan Baris Ini

        // Enforce strict mode in non-production environments
        // Preventing lazy loading, silently discarding attributes, etc.
        \Illuminate\Database\Eloquent\Model::shouldBeStrict(!\Illuminate\Support\Facades\App::isProduction());
    }
}
