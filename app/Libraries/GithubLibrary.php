<?php

namespace App\Libraries;

use App\Models\Groups\ServiceRoleType;
use App\Models\Membership\User;

class GithubLibrary extends BaseGithubLibrary
{
    public static function check_user(User $user): bool
    {
        $nav_bool = self::check_user_nav($user);
        $vatger_bool = self::check_user_vatger($user);

        return $nav_bool && $vatger_bool;
    }

    private static function check_user_nav(User $user): bool
    {
        $roles = $user->service_role_ids(ServiceRoleType::GitHubGroup, cast_to_int: false);
        $roles = collect($roles)->map(fn ($role) => str_replace('vatger-nav.', '', $role))->toArray();
        $orga_member = self::github_is_in_organization($user, 'vatger-nav');
        if (empty($roles) && $orga_member) {
            self::github_remove_from_organization($user, 'vatger-nav');

            return true;
        }
        if (! empty($roles) && ! $orga_member) {
            self::github_add_to_organization($user, 'vatger-nav');

            return true;
        }
        $current_teams = self::github_get_member_teams($user, 'vatger-nav');

        $to_delete = array_diff($current_teams, $roles);
        $to_add = array_diff($roles, $current_teams);

        foreach ($to_add as $team_slug) {
            self::github_team_add_member($user, 'vatger-nav', $team_slug);
        }

        foreach ($to_delete as $team_slug) {
            self::github_team_remove_member($user, 'vatger-nav', $team_slug);
        }

        return true;
    }

    private static function check_user_vatger(User $user)
    {
        $roles = $user->service_role_ids(ServiceRoleType::GitHubGroup, cast_to_int: false);
        $roles = collect($roles)->map(fn ($role) => str_replace('vatger.', '', $role))->toArray();
        $orga_member = self::github_is_in_organization($user, 'vatger');
        if (empty($roles) && $orga_member) {
            self::github_remove_from_organization($user, 'vatger');

            return true;
        }
        if (! empty($roles) && ! $orga_member) {
            self::github_add_to_organization($user, 'vatger');

            return true;
        }
        $current_teams = self::github_get_member_teams($user, 'vatger');

        $to_delete = array_diff($current_teams, $roles);
        $to_add = array_diff($roles, $current_teams);

        foreach ($to_add as $team_slug) {
            self::github_team_add_member($user, 'vatger', $team_slug);
        }

        foreach ($to_delete as $team_slug) {
            self::github_team_remove_member($user, 'vatger', $team_slug);
        }

        return true;
    }
}
