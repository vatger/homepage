<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS
        if (config('app.forcehttps') == true) {
            URL::forceScheme('https');
        }

        // Set default timezone to UTC
        date_default_timezone_set(config('app.timezone', 'UTC'));
    }
}
