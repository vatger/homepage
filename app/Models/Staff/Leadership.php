<?php

namespace App\Models\Staff;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leadership extends Model
{
    protected $table = 'staff_leaderships';

    public function group(): BelongsTo|Group
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
