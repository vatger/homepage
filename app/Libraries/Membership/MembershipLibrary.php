<?php

namespace App\Libraries\Membership;

use App\Libraries\Forum\XenForoLibrary;
use App\Libraries\Gitlab\GitlabLibrary;
use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Models\Membership\User\Concerns\FirMembership;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use function Symfony\Component\String\u;

class MembershipLibrary
{
    /**
     * The user has been seen on the website.
     * Only call this if we really saw the user.
     * @param User $user
     * @return void
     */
    public static function seen(User $user): void
    {
        $user->vatgerDetails->update(['last_seen_at' => Carbon::now()]);
        $user = $user->fresh();
        self::check_status($user);
    }

    /**
     * The
     * @param User $user
     * @param bool $cache
     * @return void
     */
    public static function check_status(User $user, bool $cache = true): void
    {
        $cache_key = 'membership.checked_status.' . $user->id;
        if ($cache && Cache::has($cache_key)) {
            return;
        }

        $user->loadMissing('vatgerDetails', 'vatsimDetails');
        $vatger = $user->vatgerDetails;
        $vatsim = $user->vatsimDetails;

        $warning_inactive = Carbon::now()->diffInDays($vatger->last_seen_at, true) > 180 - 30;
        $inactive = Carbon::now()->diffInDays($vatger->last_seen_at, true) > 180;
        $warning_delete = Carbon::now()->diffInDays($vatger->last_seen_at, true) > 180 * 2 - 30;
        $delete = Carbon::now()->diffInDays($vatger->last_seen_at, true) > 180 * 2;

        // user is active clear all flags
        if (!$warning_inactive) {
            $user->vatgerDetails->update([
                'warning_inactive_at' => null,
                'inactive_at' => null,
                'warning_delete_at' => null,
                'delete_at' => null,
            ]);
        }

        if ($warning_inactive && !$vatger->warning_inactive_at) {
            //TODO send email
            $user->vatgerDetails->update(['warning_inactive_at' => Carbon::now()]);
        }
        if ($inactive && !$vatger->inactive_at) {
            //TODO send email
            $user->vatgerDetails->update(['inactive_at' => Carbon::now()]);
        }
        if ($warning_delete && !$vatger->warning_delete_at) {
            //TODO send email
            $user->vatgerDetails->update(['warning_delete_at' => Carbon::now()]);
        }
        if ($delete && !$vatger->delete_at) {
            //TODO send email
            $user->vatgerDetails->update(['delete_at' => Carbon::now()]);
        }

        // user has become inactive so reset the continuous active times
        if ($inactive && ($vatger->active_member_at || $vatger->active_vatger_member_at || $user->fir?->active_fir_member_at)) {
            $user->vatgerDetails->update([
                'active_member_at' => null,
                'active_vatger_member_at' => null,
            ]);
            $user->fir?->update(['active_fir_member_at' => null]);
            $user = $user->fresh();
        }

        // user became vatger full member start time (independent from inactive status)
        if ($vatger->vatger_member_at == null && $vatsim->subdivision_code == 'GER') {
            $user->vatgerDetails->update(['vatger_member_at' => Carbon::now()]);
            $user = $user->fresh();
        }
        // user is guest member start continuous active times
        if (!$inactive && $vatger->active_member_at == null) {
            $user->vatgerDetails->update(['active_member_at' => Carbon::now()]);
            $user = $user->fresh();
        }
        // user is vatger full member start continuous active times
        if (!$inactive && $vatger->active_vatger_member_at == null && $vatsim->subdivision_code == 'GER') {
            $user->vatgerDetails->update(['active_vatger_member_at' => Carbon::now()]);
            $user = $user->fresh();
        }
        if (!$inactive && $user->fir?->active_fir_member_at == null && $vatsim->subdivision_code == 'GER') {
            $user->fir_membership?->update(['active_fir_member_at' => Carbon::now()]);
        }
        //user has left vatger full member
        if ($vatsim->subdivision_code != 'GER' && ($vatger->active_member_at || $vatger->active_vatger_member_at)) {
            //TODO kick from FIRs
            $user->fir_membership?->delete();
            $user->vatgerDetails->update(['vatger_member_at' => null, 'active_vatger_member_at' => null]);
            $user = $user->fresh();
        }

        Cache::put($cache_key, Carbon::now(), 60);
    }

    public static function update(User $user, bool $async = false): void
    {
        $user = $user->refresh();
        self::check_status($user, false);
        # TODO: Handle all changes that might have triggered this function

        // 1. Handle forum permission / role assignment
        XenForoLibrary::updateForumAccount($user);
        // 2. Handle Teamspeak roles
        TeamSpeakWebQuery::checkUser($user);
        //

        //
        Log::info('[MembershipLibrary::handleMembershipChange]::' . $user->id . '::Membership Update Triggered!');
    }
}
