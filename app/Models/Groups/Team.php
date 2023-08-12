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
}
