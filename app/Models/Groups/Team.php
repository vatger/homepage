<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    use HasRoleTrait;

    protected $table = 'teams';

    protected $fillable = ['super_team_id', 'role_id'];

    public function super_team(): HasOne|Team
    {
        return $this->hasOne(Team::class, 'id', 'super_team_id');
    }

    public function subteams(): HasMany|Team
    {
        return $this->hasMany(Team::class, 'super_team_id', 'id');
    }

    public function service_roles(): HasMany|ServiceRole
    {
        return $this->hasMany(ServiceRole::class, 'team_id', 'id');
    }
}
