<?php

namespace App\Libraries\Membership;

use App\Libraries\Forum\XenForoLibrary;
use App\Libraries\Gitlab\GitlabLibrary;
use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MembershipLibrary
{
    public static function seen(User $user): void
    {
        $user->vatgerDetails->update(['last_seen_at' => Carbon::now()]);
        $user = $user->fresh();
        self::check_status($user);
    }

    public static function check_status(User $user): void
    {
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
        if ($inactive && ($vatger->active_member_at || $vatger->active_vatger_member_at)) {
            $user->vatgerDetails->update([
                'active_member_at' => null,
                'active_vatger_member_at' => null,
            ]);
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
        //user has left vatger full member
        if ($vatsim->subdivision_code != 'GER' && ($vatger->active_member_at || $vatger->active_vatger_member_at)) {
            //TODO kick from FIRs
            $user->vatgerDetails->update(['vatger_member_at' => null, 'active_vatger_member_at' => null]);
            $user = $user->fresh();
        }
    }

    public static function handleMembershipChange(User $user)
    {
        $user = $user->refresh();
        $user = $user->load('settings', 'userData', 'roles');
        # TODO: Handle all changes that might have triggered this function

        // 1. Handle forum permission / role assignment
        // Call forum library to sync changes to forum
        XenForoLibrary::updateForumAccount($user);
        // 2. Handle git access
        TeamSpeakWebQuery::checkUser($user);
        // 3. Handle other stuff
        Log::info('[MembershipLibrary::handleMembershipChange]::' . $user->id . '::Membership Update Triggered!');
    }

    public static function getAllServiceRoles(?string $service_type = null): array
    {
    }
}
