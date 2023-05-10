<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Navaid extends Model
{
    protected $table = 'nav_navaids';

    protected $fillable = ['ident', 'type', 'name', 'heading', 'ident', 'frequency', 'remarks'];

    protected $appends = [''];

    public function aerodromes(): BelongsToMany
    {
        return $this->belongsToMany(Aerodrome::class, 'nav_aerodrome_navaids', 'navaid_id', 'aerodrome_id');
    }
}
