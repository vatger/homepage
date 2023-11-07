<?php

namespace App\Models\Membership\User;

enum UserBanType: string
{
    case vatger_ban = 'vatger_ban';
    case vatsim_inactivity = 'vatsim_inactivity';
    case vatsim_ban = 'vatsim_ban';
}
