<?php

namespace App\Libraries;

use App\Libraries\VATSIM\ATCBookingsApi;
use App\Models\AtcBooking;
use App\Models\Membership\Concerns\FirMembership;
use App\Models\Membership\SurveyKey;
use App\Models\Membership\User;
use App\Models\Membership\UserBan;
use App\Models\Membership\UserPassword;
use App\Models\Membership\UserSetting;
use App\Models\Membership\UserStaffDetail;
use App\Models\Membership\UserVatgerDetail;
use App\Models\Membership\UserVatsimDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use ReflectionMethod;

class GDPRFinalDeletion
{
    protected ?User $user;

    protected int $user_id;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->user_id = $user->id;
    }

    public static function deleteUser(User $user): bool
    {
        $g = new self($user);

        return $g->run();
    }

    public function check(): bool
    {
        $removal = GDPRLibrary::get_current_removal($this->user);
        $started_at = Carbon::parse($removal->started_at);

        return $removal != null && $started_at->diffInDays() > 30 && count($removal->pending_services) == 1;
    }

    public function run(bool $debug = false): bool
    {
        if (! $this->check()) {
            return false;
        }
        $removal = GDPRLibrary::get_current_removal($this->user);
        try {
            DB::beginTransaction();
            $reflection = new ReflectionClass(self::class);
            $methods = $reflection->getMethods(ReflectionMethod::IS_PRIVATE);
            $status_ok = true;
            foreach ($methods as $method) {
                $method->setAccessible(true);
                // Allow access to private methods
                $res = $method->invoke($this, $this->user, $this->user_id); // Call the private method on the current instance
                if ($debug) {
                    dump([$method->name => $res]);
                }
                $status_ok = $status_ok && $res;
            }
            $this->user->delete();
            if ($status_ok) {
                DB::commit();
            } else {
                DB::rollBack();

                return false;
            }

        } catch (\Throwable $e) {
            \Log::error($e);
            try {
                DB::rollBack();
            } catch (\Throwable $e) {
            }

            return false;
        }
        GDPRLibrary::finalize($removal);

        return true;
    }

    private function bookings(?User $user, int $user_id): bool
    {
        $atcBookings = AtcBooking::where('controller_id', $user_id)->get();
        foreach ($atcBookings as $atcBooking) {
            try {
                ATCBookingsApi::deleteBooking($atcBooking);
            } catch (\Exception $e) {
                \Log::warning($e->getMessage());
            }
            $atcBooking->delete();
        }

        return true;
    }

    private function notifications(?User $user, int $user_id): bool
    {
        if (! $user) {
            return true;
        }
        $notifications = $user->notifications;
        foreach ($notifications as $notification) {
            $notification->delete();
        }

        return true;
    }

    private function oauth(?User $user, int $user_id): bool
    {
        DB::table('oauth_auth_codes')->where('user_id', $user_id)->first();
        DB::table('oauth_access_tokens')->where('user_id', $user_id)->first();

        return true;
    }

    private function user_bans(?User $user, int $user_id): bool
    {
        UserBan::where('user_id', $user_id)->delete();

        return true;
    }

    private function user_firs(?User $user, int $user_id): bool
    {
        FirMembership::where('user_id', $user_id)->delete();

        return true;
    }

    private function user_passwords(?User $user, int $user_id): bool
    {
        UserPassword::where('user_id', $user_id)->delete();

        return true;
    }

    private function user_settings(?User $user, int $user_id): bool
    {
        UserSetting::where('user_id', $user_id)->delete();

        return true;
    }

    private function user_staff_details(?User $user, int $user_id): bool
    {
        UserStaffDetail::where('user_id', $user_id)->delete();

        return true;
    }

    private function user_surveykeys(?User $user, int $user_id): bool
    {
        SurveyKey::where('user_id', $user_id)->delete();

        return true;
    }

    private function user_vatger_details(?User $user, int $user_id): bool
    {
        UserVatgerDetail::where('user_id', $user_id)->delete();

        return true;
    }

    private function user_vatsim_details(?User $user, int $user_id): bool
    {
        UserVatsimDetail::where('user_id', $user_id)->delete();

        return true;
    }
}
