<?php

namespace App\Libraries;

use App\Jobs\UpdateAccountJob;
use App\Libraries\TeamSpeak\TeamSpeakWebQuery;
use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\User\GdprRemoval;
use App\Models\Membership\User\User;
use App\Models\Membership\User\UserBan;
use App\Models\Membership\User\UserBanType;
use App\Notifications\BasicNotification;
use Carbon\Carbon;
use Exception;
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
        $data = ['last_seen_at' => Carbon::now()];

        // if we are currently in the removal process, don't set this
        if (!GdprRemoval::where('user_id', $user->id)->whereNull('completed_at')->exists()) {
            $data['deleted_at'] = null;
        }

        $user->vatgerDetails->update($data);
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
            CoreApiLibrary2::updateMember($user, $cache ? 60 : 0);
            $user = $user->fresh();
        }
        self::check_bans($user);
        self::check_status($user, $cache);
        self::check_staff($user);

        $user = $user->fresh();

        # TODO: Handle all changes that might have triggered this function

        // 1. Handle forum permission / role assignment
        if (BaseLibrary::is_active(BaseLibrary::SyncForum)) {
            try {
                XenForoLibrary::updateForumAccount($user);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
        // 2. Handle Teamspeak roles
        if (BaseLibrary::is_active(BaseLibrary::SyncTeamspeak)) {
            try {
                TeamSpeakWebQuery::checkUser($user);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
        // 3. Handle OS Ticktet
        if (BaseLibrary::is_active(BaseLibrary::SyncOSTicket)) {
            try {
                OSTicketLibrary::check_user($user);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
        // 4. Handle Bookstack (kb)
        if (BaseLibrary::is_active(BaseLibrary::SyncKnowledgebase)) {
            try {
                BookstackLibrary::check_user($user);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
        // 5. Handle DMS
        if (BaseLibrary::is_active(BaseLibrary::SyncDMS)) {
            try {
                NextcloudLibrary::check_user($user);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        // 6. Vikunja
        if (BaseLibrary::is_active(BaseLibrary::SyncVikunja)) {
            try {
                $VL = VikunjaLibrary::get_instance();
                $VL->check_user($user);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
        // 7. Discord
        if (BaseLibrary::is_active(BaseLibrary::SyncDiscord)) {
            try {
                DiscordLibrary::check_user($user);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        Log::info('[MembershipLibrary::handleMembershipChange]::' . $user->id . '::Membership Update Triggered!');
    }

    protected static function check_bans(User $user): void
    {
        $now = Carbon::now();
        $vatsim_pilot_inactive = $user->vatsimDetails->rating_atc == -1 && $user->vatsimDetails->rating_pilot == -1;
        $vatsim_inactive = $user->vatsimDetails->rating_atc == -1;
        $vatsim_suspended = $user->vatsimDetails->rating_atc == 0;

        // VATSIM pilot rating / new member orientation
        $vatsim_pilot_inactive_ban = UserBan::where('user_id', $user->id)
            ->where('type', UserBanType::pilot_rating_incomplete)
            ->where(function (QBuilder|EBuilder $query) use ($now) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->first();

        if ($vatsim_pilot_inactive && !$vatsim_pilot_inactive_ban) {
            $b = new UserBan();
            $b->user_id = $user->id;
            $b->type = UserBanType::pilot_rating_incomplete;
            $b->save();
        }
        if (!$vatsim_pilot_inactive && $vatsim_pilot_inactive_ban) {
            $vatsim_pilot_inactive_ban->endBanNow();
        }


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

    protected static function check_staff(User $user): void
    {
        if ($user->staffDetails?->staff_email_created) {
            if (!$user->can('mail.use')) {
                if (!$user->staffDetails->delete_staff_email_at) {
                    $deldate = $user->staffDetails->delete_staff_email_at = Carbon::now()->addDays(30);
                    $user->staffDetails->save();

                    $date = $deldate->format('d.m.Y');
                    $time = $deldate->format('H:i:s');

                    $user->notify(
                        new BasicNotification(
                            'Deine VATSIM Germany E-Mail Adresse',
                            "Du bist nicht mehr im Besitz einer Staffrolle, die zu einer VATSIM Germany E-Mail Adresse berechtigt. Daher werden wir deine VATSIM Germany E-Mail Adresse am $date um $time löschen. Bitte sichere dir bis dahin alle relevanten Daten.",
                            'Tech Leitung',
                            Carbon::now()->addDays(14),
                            Carbon::now()->addDays(365),
                        ),
                    );
                }
            } else {
                if ($user->staffDetails->delete_staff_email_at) {
                    $user->staffDetails->delete_staff_email_at = null;
                    $user->staffDetails->save();
                }
            }
        }
        if ($user->staffDetails?->delete_mail_at < now() && $user->staffDetails?->delete_staff_email_at != null) {
            if (BaseLibrary::is_active(BaseLibrary::SyncMailcow)) {
                if (MailcowLibrary::delete_email($user->staffDetails->staff_email)) {
                    $user->staffDetails->staff_email_created = false;
                    $user->staffDetails->staff_email = null;
                    $user->staffDetails->delete_staff_email_at = null;
                    $user->staffDetails->save();
                }
            }
        }

        if ($user->staffDetails?->leaving_staff_at < now() && !$user->staffDetails?->staff_email_created) {
            $user->staff_details?->delete();
        }
    }

    protected static function check_status(User $user, bool $cache = true): void
    {
        $cache_key = 'membership.checked_status.' . $user->id;
        if ($cache && Cache::has($cache_key)) {
            return;
        }

        // if we are currently in the removal process, don't continue
        if (GdprRemoval::where('user_id', $user->id)->whereNull('completed_at')->exists()) {
            return;
        }

        $warning_inactive = Carbon::now()->diffInDays($user->vatgerDetails->last_seen_at, true) > 180 - 30;
        $inactive = Carbon::now()->diffInDays($user->vatgerDetails->last_seen_at, true) > 180;
        $warning_delete = Carbon::now()->diffInDays($user->vatgerDetails->last_seen_at, true) > 180 * 2 - 30;
        $delete = Carbon::now()->diffInDays($user->vatgerDetails->last_seen_at, true) > 180 * 2;

        // user is active clear all flags
        if (!$warning_inactive) {
            $data = [
                'warning_inactive_at' => null,
                'inactive_at' => null,
                'warning_delete_at' => null,
                //'delete_at' => null,
            ];
            $user->vatgerDetails->update($data);
        }

        App::setLocale($user->settings->language);

        if ($warning_inactive && !$user->vatgerDetails->warning_inactive_at) {
            $user->vatgerDetails->update(['warning_inactive_at' => Carbon::now()]);
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
            $date = $user->vatgerDetails->last_seen_at->addDays(180 * 2);
            $n = new BasicNotification(
                __('membership_library.deletion_notice.title'),
                __('membership_library.deletion_notice.message', ['date' => $date->format('d.m.Y H:i')]),
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
            //kick from FIRs
            $user->fir_membership?->delete();
            $user->vatgerDetails->update(['vatger_member_at' => null, 'active_vatger_member_at' => null]);
            $user = $user->fresh();
        }

        Cache::put($cache_key, Carbon::now(), 60 * 10);
    }
}
