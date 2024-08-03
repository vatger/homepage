<?php

namespace App\Providers;

use App\Libraries\ImageHelperLibrary;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
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
