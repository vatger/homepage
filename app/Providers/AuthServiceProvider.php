<?php

namespace App\Providers;

use App\Models\Filebase\MediaFile;
use App\Models\Membership\Role;
use App\Models\Membership\User\User;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Chart;
use App\Models\Navigation\Navaid;
use App\Models\Navigation\Station;
use App\Models\Regionalgroup_remove\Regionalgroup;
use App\Policies\AerodromePolicy;
use App\Policies\ChartPolicy;
use App\Policies\MediaPolicy;
use App\Policies\RegionalgroupPolicy;
use App\Policies\RolePolicy;
use App\Policies\RunwayPolicy;
use App\Policies\StationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Aerodrome::class => AerodromePolicy::class,
        Chart::class => ChartPolicy::class,
        Role::class => RolePolicy::class,
        Navaid::class => RunwayPolicy::class,
        Station::class => StationPolicy::class,
        Regionalgroup::class => RegionalgroupPolicy::class,
        MediaFile::class => MediaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('administration-access', function (User $user) {
            return $user->can('administration.access') ? Response::allow() : Response::deny('TODO: lang(`administration.accessDenied`)');
        });

        Gate::define('membership-access', function (User $user) {
            return $user->can('membership.roles.viewAny') || $user->can('membership.users.viewAny')
                ? Response::allow()
                : Response::deny('TODO: lang(`administration.membership.accessDenied`)');
        });

        Gate::define('tech-access', function (User $user) {
            return $user->can('tech.viewAny') ? Response::allow() : Response::deny();
        });
    }
}
