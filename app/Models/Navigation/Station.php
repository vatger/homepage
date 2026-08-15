<?php

namespace App\Models\Navigation;

use App\Models\AtcBooking;
use Database\Factories\StationFactory;
use Illuminate\Contracts\Database\Eloquent\Builder as DBuilder;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QBuilder;

class Station extends Model
{
    use HasFactory;

    protected $table = 'nav_stations';

    protected static function newFactory(): Factory
    {
        return StationFactory::new();
    }

    protected $fillable = ['name', 'ident', 'frequency', 'active'];

    protected $appends = ['fixedFrequency'];

    public function aerodromes(): BelongsToMany
    {
        return $this->belongsToMany(Aerodrome::class, 'nav_aerodrome_stations', 'station_id', 'aerodrome_id')->withPivot('order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(AtcBooking::class, 'station_id', 'id');
    }

    public function getFixedFrequencyAttribute(): string
    {
        return number_format($this->frequency, 3);
    }

    public function scopeBookable(QBuilder|EBuilder|DBuilder $query): QBuilder|EBuilder|DBuilder
    {
        return $query->where('active', true)->whereNot('ident', 'LIKE', '%_ATIS');
    }
}
