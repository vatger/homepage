<?php

namespace App\Models\Navigation;

use Database\Factories\AerodromeLinkFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AerodromeLink extends Model
{
    use HasFactory;

    protected $table = 'nav_links';

    protected static function newFactory(): Factory
    {
        return AerodromeLinkFactory::new();
    }

    public function aerodrome(): BelongsTo
    {
        return $this->belongsTo(Aerodrome::class, 'aerodrome_id', 'id');
    }
}
