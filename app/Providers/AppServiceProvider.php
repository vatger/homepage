<?php

namespace App\Providers;

use App\Models\Membership\User;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

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

        // For Scramble api documentation
        Gate::define('viewApiDocs', function (?User $user = null): bool {
            return $user !== null;
        });

        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });

    }
}
