<?php

namespace App\Libraries;

use App\Jobs\UpdateAccountJob;
use App\Jobs\UpdateTeamspeakJob;
use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Libraries\VATSIM\APILibrary;
use App\Models\Membership\User\User;
use App\Models\Membership\User\UserBan;
use App\Models\Membership\User\UserBanType;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder as QBuilder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

    public static function update(User $user, bool $async = false, bool $cache = true, bool $api_refresh = true): void
    {
        if ($async) {
            dispatch(new UpdateAccountJob($user));
            return;
        }
        if ($api_refresh) {
            APILibrary::MemberUpdate($user);
            $user = $user->refresh();
        }
        self::check_bans($user);
        self::check_status($user, $cache);

        # TODO: Handle all changes that might have triggered this function
        // 1. Handle forum permission / role assignment
        XenForoLibrary::updateForumAccount($user);
        // 2. Handle Teamspeak roles
        TeamSpeakWebQuery::checkUser($user);
        // 3. Handle OS Ticktet
        try {
            OSTicketLibrary::check_user($user);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        // 4. Handle Bookstack (kb)

        // 5. Handle DMS

        // 6. Vikunja
        //$VL = new VikunjaLibrary();
        //$VL->check_user($user);

        Log::info('[MembershipLibrary::handleMembershipChange]::' . $user->id . '::Membership Update Triggered!');
    }

    protected static function check_bans(User $user): void
    {
        $now = Carbon::now();
        $vatsim_inactive = $user->vatsimDetails->rating_atc == -1;
        $vatsim_suspended = $user->vatsimDetails->rating_atc == 0;

        // VATSIM inactivity
        $vatsim_inactive_ban = UserBan::where('user_id', $user->id)
            ->where('type', UserBanType::vatsim_inactivity)
            ->where(function (QBuilder|EBuilder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->first();

        if ($vatsim_inactive && !$vatsim_inactive_ban) {
            $b = new UserBan();
            $b->user_id = $user->id;
            $b->type = UserBanType::vatsim_inactivity;
            $b->save();
        }
        if (!$vatsim_inactive && $vatsim_inactive_ban) {
            $vatsim_inactive_ban->endBanNow();
        }

        // VATSIM suspension
        $vatsim_suspened_ban = UserBan::where('user_id', $user->id)
            ->where('type', UserBanType::vatsim_ban)
            ->where(function (QBuilder|EBuilder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->first();

        if ($vatsim_suspended && !$vatsim_suspened_ban) {
            $b = new UserBan();
            $b->user_id = $user->id;
            $b->type = UserBanType::vatsim_ban;
            $b->save();
        }
        if (!$vatsim_suspended && $vatsim_suspened_ban) {
            $vatsim_suspened_ban->endBanNow();
        }
    }

    protected static function check_status(User $user, bool $cache = true): void
    {
        $cache_key = 'membership.checked_status.' . $user->id;
        if ($cache && Cache::has($cache_key)) {
            return;
        }

        $warning_inactive = Carbon::now()->diffInDays($user->vatgerDetails->last_seen_at, true) > 180 - 30;
        $inactive = Carbon::now()->diffInDays($user->vatgerDetails->last_seen_at, true) > 180;
        $warning_delete = Carbon::now()->diffInDays($user->vatgerDetails->last_seen_at, true) > 180 * 2 - 30;
        $delete = Carbon::now()->diffInDays($user->vatgerDetails->last_seen_at, true) > 180 * 2;

        // user is active clear all flags
        if (!$warning_inactive) {
            $user->vatgerDetails->update([
                'warning_inactive_at' => null,
                'inactive_at' => null,
                'warning_delete_at' => null,
                'delete_at' => null,
            ]);
        }

        App::setLocale($user->settings->language);

        if ($warning_inactive && !$user->vatgerDetails->warning_inactive_at) {
            $user->vatgerDetails->update(['warning_inactive_at' => Carbon::now()]);
            //TODO send email
            $date = $user->vatgerDetails->last_seen_at->addDays(180);
            $n = new BasicNotification(
                __('membership_library.inactivity_warning.title'),
                __('membership_library.inactivity_warning.message', ['date' => $date->format('d.m.Y')]),
                'VATGER Membership System',
                __('membership_library.inactivity_warning.link'),
                route('vatsim.authentication.connect.login'),
                valid_till: $date,
                delete_at: $date->addDay(),
            );
            $user->notify($n);
        }
        if ($inactive && !$user->vatgerDetails->inactive_at) {
            $user->vatgerDetails->update(['inactive_at' => Carbon::now()]);
            //TODO send email
            $date = $user->vatgerDetails->last_seen_at->addDays(180);
            $n = new BasicNotification(
                __('membership_library.inactivity_notice.title'),
                __('membership_library.inactivity_notice.message', ['date' => $date->format('d.m.Y')]),
                'VATGER Membership System',
                __('membership_library.inactivity_notice.link'),
                route('vatsim.authentication.connect.login'),
                valid_till: $date,
                delete_at: $date->addDay(),
            );
            $user->notify($n);
        }
        if ($warning_delete && !$user->vatgerDetails->warning_delete_at) {
            $user->vatgerDetails->update(['warning_delete_at' => Carbon::now()]);
            //TODO send email
            $date = $user->vatgerDetails->last_seen_at->addDays(180 * 2);
            $n = new BasicNotification(
                __('membership_library.deletion_warning.title'),
                __('membership_library.deletion_warning.message', ['date' => $date->format('d.m.Y')]),
                'VATGER Membership System',
                __('membership_library.deletion_warning.link'),
                route('vatsim.authentication.connect.login'),
                valid_till: $date,
                delete_at: $date->addDay(),
            );
            $user->notify($n);
        }
        if ($delete && !$user->vatgerDetails->delete_at) {
            $user->vatgerDetails->update(['delete_at' => Carbon::now()]);
            //TODO send email
            $date = $user->vatgerDetails->last_seen_at->addDays(180 * 2);
            $n = new BasicNotification(
                __('membership_library.deletion_notice.title'),
                __('membership_library.deletion_notice.message', ['date' => $date->format('d.m.Y')]),
                'VATGER Membership System',
                __('membership_library.deletion_notice.link'),
                route('vatsim.authentication.connect.login'),
                valid_till: $date,
                delete_at: $date->addDay(),
            );
            $user->notify($n);
        }

        $user = $user->fresh();

        // user has become inactive so reset the continuous active times
        if (
            $inactive &&
            ($user->vatgerDetails->active_member_at || $user->vatgerDetails->active_vatger_member_at || $user->fir_membership?->active_fir_member_at)
        ) {
            $user->vatgerDetails->update([
                'active_member_at' => null,
                'active_vatger_member_at' => null,
            ]);
            $user->fir_membership?->update(['active_fir_member_at' => null]);
            $user = $user->fresh();
        }

        // user became vatger full member start time (independent from inactive status)
        if ($user->vatgerDetails->vatger_member_at == null && $user->vatsimDetails->subdivision_code == 'GER') {
            $user->vatgerDetails->update(['vatger_member_at' => Carbon::now()]);
            $user = $user->fresh();
        }
        // user is guest member start continuous active times
        if (!$inactive && $user->vatgerDetails->active_member_at == null) {
            $user->vatgerDetails->update(['active_member_at' => Carbon::now()]);
            $user = $user->fresh();
        }
        // user is vatger full member start continuous active times
        if (!$inactive && $user->vatgerDetails->active_vatger_member_at == null && $user->vatsimDetails->subdivision_code == 'GER') {
            $user->vatgerDetails->update(['active_vatger_member_at' => Carbon::now()]);
            $user = $user->fresh();
        }
        if (!$inactive && $user->fir?->active_fir_member_at == null && $user->vatsimDetails->subdivision_code == 'GER') {
            $user->fir_membership?->update(['active_fir_member_at' => Carbon::now()]);
        }
        //user has left vatger full member
        if (
            $user->vatsimDetails->subdivision_code != 'GER' &&
            ($user->vatgerDetails->active_member_at || $user->vatgerDetails->active_vatger_member_at)
        ) {
            //TODO kick from FIRs
            $user->fir_membership?->delete();
            $user->vatgerDetails->update(['vatger_member_at' => null, 'active_vatger_member_at' => null]);
            $user = $user->fresh();
        }

        Cache::put($cache_key, Carbon::now(), 60 * 10);
    }
}
