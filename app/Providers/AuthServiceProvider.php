<?php

namespace App\Providers;

use App\Models\Groups\Team;
use App\Models\Membership\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Passport::tokensCan(config('openid.passport.tokens_can'));

        $this->registerPolicies();

        Gate::define('membership.teams.edit.members.subteam-check', function (User $user, Team $team) {
            if ($user->hasPermissionTo('membership.teams.edit.members')) {
                return true;
            }
            if (! $user->hasPermissionTo('membership.teams.edit.members.subteam')) {
                return false;
            }
            if (empty($team->super_team) || ! $user->hasRole($team->super_team->role)) {
                return false;
            }
            if ($team->super_team->role->hasPermissionTo('membership.teams.edit.members.subteam')) {
                return true;
            }

            return false;
        });
    }
}
