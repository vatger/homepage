<?php

namespace App\Models\Groups;

enum ServiceRoleType: string
{
    case TeamspeakServergroup = 'ts.servergroup';
    case ForumGroup = 'board.group';
    case ForumModerator = 'board.moderator';
    case SupportGroup = 'osticket.group';
    case BookstackGroup = 'kb.group';
    case VikunjaGroup = 'vikunja.group';
    case NextcloudGroup = 'nextcloud.group';
    case GitHubGroup = 'github.group';
}
