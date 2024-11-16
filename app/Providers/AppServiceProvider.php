<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; 
use Illuminate\Support\Facades\URL; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      Paginator::useBootstrap();
    }

    

    public function boot()
    {
        // Forzar HTTPS
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
