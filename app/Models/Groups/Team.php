<?php

namespace App\Models\Groups;

use App\Models\Membership\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Team extends SpatieRole
{
    protected $table = 'group_teams';

    protected $fillable = ['super_team_id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'group_teams';
    }

    public function super_team(): BelongsTo|Team|null
    {
        return $this->belongsTo(Team::class, 'super_team_id');
    }

    public function subteams(): HasMany|Team
    {
        return $this->hasMany(Team::class, 'super_team_id', 'id');
    }

    public function external_groups(): HasMany|TeamExternalGroup
    {
        return $this->hasMany(TeamExternalGroup::class, 'team_id', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            User::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.role_pivot_key', 'role_id'),
            config('permission.column_names.model_morph_key', 'model_id'),
        )->using(TeamMembership::class);
    }
}
