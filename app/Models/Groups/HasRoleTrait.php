<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

trait HasRoleTrait
{
    public function role(): HasOne|Role
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    protected static function booted(): void
    {
        static::saving(function (self $team) {
            if (! $team->role_id) {
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
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $role?->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $team->update(['role_id' => null]);
        });
    }
}
