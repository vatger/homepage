<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use IsGroupTrait;

    protected $table = 'staff_teams';

    protected static function booted(): void
    {
        static::creating(function (Team $team) {
            $group = Group::create(['name' => $team->name, 'type' => 'team']);
            $team->group_id = $group->id;
            dd($team);
        });
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
