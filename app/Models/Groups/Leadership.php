<?php

namespace App\Models\Groups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leadership extends Model
{
    use IsGroupTrait;

    protected $table = 'staff_leaderships';
}
