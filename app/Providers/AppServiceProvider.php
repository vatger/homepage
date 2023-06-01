<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
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
        /* Load Languages */
        //if (file_exists(base_path('modules/atciss/resources/lang/en/atciss.php'))) {
        //    $this->loadTranslationsFrom(base_path('modules/atciss/resources/lang'), 'atciss');
        //}

        /* Load Migrations */
        //if (is_dir(base_path('modules/atciss/database/migrations/')) !== false) {
        //    $this->loadMigrationsFrom(base_path('modules/atciss/database/migrations'));
        //}

        // Force HTTPS
        if (config('app.forcehttps') == true) {
            URL::forceScheme('https');
        }

        // Pagination for Collection
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
                $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                return (new LengthAwarePaginator($this->forPage($page, $perPage), $this->count(), $perPage, $page, $options))->withPath('');
            });
        }
    }
}
