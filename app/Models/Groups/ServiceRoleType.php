<?php

namespace App\Models\Groups;

enum ServiceRoleType: string
{
    case TeamspeakServergroup = 'ts.servergroup';
    case ForumGroup = 'board.group';
    case SupportGroup = 'osticket.group';
}
