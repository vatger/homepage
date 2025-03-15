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
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class GDPRFinalDeletion
{
    public User $user;

    /**
     * @throws ReflectionException
     */
    public function run(): bool
    {
        $reflection = new ReflectionClass(self::class);
        $methods = $reflection->getMethods(ReflectionMethod::IS_PRIVATE);
        foreach ($methods as $method) {
            $method->setAccessible(true);
            // Allow access to private methods
            $method->invoke($this, $this->user); // Call the private method on the current instance
        }

        return false;
    }

    private function bookings(?User $user, int $user_id): bool
    {
        $atcBookings = AtcBooking::where('controller_id', $user_id)->get();
        foreach ($atcBookings as $atcBooking) {
            try {
                ATCBookingsApi::deleteBooking($atcBooking);
            } catch (\Exception $e) {
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
        return false;
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
