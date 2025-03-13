<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Passport::ignoreRoutes();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS
        if (config('app.forcehttps')) {
            URL::forceScheme('https');
        }

        // Set default timezone to UTC
        date_default_timezone_set(config('app.timezone', 'UTC'));
    }
}
