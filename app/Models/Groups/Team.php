<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use IsGroupTrait;

    protected $table = 'teams';
}
