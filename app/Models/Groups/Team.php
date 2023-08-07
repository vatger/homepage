<?php

namespace App\Models\Groups;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    use IsGroupTrait;

    protected $table = 'staff_teams';

    protected static function booted(): void
    {
        static::saving(function (Team $team) {
            if (!$team->group_id) {
                $group = Group::create(['name' => $team->name, 'type' => 'team']);
                $team->group_id = $group->id;
            }
            $group = Group::where('id', $team->group_id)->first();
            $group->name = $team->name;
            $group->save();
        });
        static::deleted(function (Team $team) {
            $group = $team->group;
            $group->delete();
        });
    }
}
