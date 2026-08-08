<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TeamOld extends Model
{
    use HasRoleTrait;

    protected $table = 'teams';

    protected $fillable = ['super_team_id', 'role_id'];

    public function super_team(): HasOne|TeamOld
    {
        return $this->hasOne(TeamOld::class, 'id', 'super_team_id');
    }

    public function subteams(): HasMany|TeamOld
    {
        return $this->hasMany(TeamOld::class, 'super_team_id', 'id');
    }

    public function external_groups(): HasMany|TeamExternalGroup
    {
        return $this->hasMany(TeamExternalGroup::class, 'team_id', 'id');
    }
}
