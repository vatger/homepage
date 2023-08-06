<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use IsGroupTrait;

    protected $table = 'staff_teams';
}
