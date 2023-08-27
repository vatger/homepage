<?php

namespace App\Models\Groups;

use App\Models\Membership\User\User;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

trait HasRoleTrait
{
    public function role(): HasOne|Role
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function members(): BelongsToMany|User
    {
        $g = $this->role()->first();
        return $g->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id');
    }

    protected static function booted(): void
    {
        static::saving(function (self $team) {
            if (!$team->role_id) {
                $role = Role::create(['name' => Str::slug($team->name), 'type' => self::class]);
                $team->role_id = $role->id;
            }
            $role = Role::where('id', $team->role_id)->first();
            $role->name = Str::slug($team->name);
            $role->save();
        });
        static::deleting(function (self $team) {
            $role = $team->role;
            Team::where('super_team_id', $team->id)->update(['super_team_id' => null]);
            $team->update(['group_id' => null]);
            $role?->delete();
        });
    }
}
