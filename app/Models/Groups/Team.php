<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    use IsGroupTrait;

    protected $table = 'teams';

    public function super_team(): HasOne|Team
    {
        return $this->hasOne(Team::class, 'id', 'super_team_id');
    }
}
