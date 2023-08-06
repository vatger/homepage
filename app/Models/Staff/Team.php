<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    protected $table = 'staff_teams';

    public function group(): BelongsTo|Group
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
