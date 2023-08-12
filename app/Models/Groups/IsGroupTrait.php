<?php

namespace App\Models\Groups;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

trait IsGroupTrait
{
    public function group(): HasOne|Group
    {
        return $this->hasOne(Group::class, 'id', 'group_id');
    }

    public function members(): BelongsToMany|User
    {
        $g = $this->group()->first();
        return $g->belongsToMany(User::class, 'model_has_groups', 'group_id', 'model_id');
    }

    protected static function booted(): void
    {
        static::saving(function (self $team) {
            if (!$team->group_id) {
                $group = Group::create(['name' => Str::slug($team->name), 'type' => self::class]);
                $team->group_id = $group->id;
            }
            $group = Group::where('id', $team->group_id)->first();
            $group->name = Str::slug($team->name);
            $group->save();
        });
        static::deleted(function (self $team) {
            $group = $team->group;
            $group->delete();
        });
    }
}
