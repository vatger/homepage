<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Relations\MorphPivot;

class TeamMembership extends MorphPivot
{
    protected $table = 'group_team_memberships';
}
