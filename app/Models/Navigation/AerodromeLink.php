<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AerodromeLink extends Model
{
    protected $table = 'nav_links';

    public function aerodrome(): BelongsTo
    {
        return $this->belongsTo(Aerodrome::class, 'aerodrome_id', 'id');
    }
}
