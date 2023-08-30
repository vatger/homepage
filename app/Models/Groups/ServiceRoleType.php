<?php

namespace App\Models\Groups;

enum ServiceRoleType: string
{
    case TeamspeakServergroup = 'ts.servergroup';
    case ForumGroup = 'board.group';
}
