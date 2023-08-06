<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SubTeam extends Model
{
    use IsGroupTrait;

    protected $table = 'staff_subteams';

    public function team(): BelongsTo|Team
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    public function leadership(): BelongsTo|Leadership
    {
        //idk if this works, otherwise with belongs through table
        return $this->team()->belongsTo(Leadership::class, 'leadership_id', 'id');
    }
}
