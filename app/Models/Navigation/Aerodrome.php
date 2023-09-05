<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as DBuilder;
use Illuminate\Database\Eloquent\Builder as EBuilder;

class Aerodrome extends Model
{
    protected $table = 'nav_aerodromes';

    protected $fillable = ['*'];

    /**
     * The FIR is assigned to
     */
    public function fir(): BelongsTo|Fir
    {
        return $this->belongsTo(Fir::class);
    }

    /**
     * Get all assigned stations
     */
    public function stations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class, 'nav_aerodrome_stations', 'aerodrome_id', 'station_id')
            ->withPivot('order')
            ->orderByPivot('order', 'ASC');
    }

    public function links(): HasMany
    {
        return $this->hasMany(AerodromeLink::class, 'aerodrome_id', 'id');
    }

    /**
     * Get an aerodrome by its icao
     */
    public function scopeIcao(DBuilder|EBuilder $query, string $icao): DBuilder|EBuilder
    {
        return $query->where('icao', $icao);
    }

    /**
     * Get only aerodromes that are assigned to Germany
     */
    public function scopeIsDe(DBuilder|EBuilder $query): DBuilder|EBuilder
    {
        return $query->where('country_short', 'DE');
    }

    /**
     * Load the current atc activity at the aerodrome
     */
    //public function getControllerActivityAttribute(): mixed
    //{
    //    if ($this->stations->count() > 0) {
    //        return AtcClient::withCallsignIn(
    //            $this->stations
    //                ->pluck('ident')
    //                ->push('%' . $this->icao . '%')
    //                ->all(),
    //        )
    //            ->online()
    //           ->get();
    //    }
    //    return AtcClient::icao('%' . $this->icao . '%')
    //        ->online()
    //        ->get();
    //}

    /**
     * Is something in the vicinity of the airport?
     */
    public function containsCoordinates(float $latitude, float $longitude): bool
    {
        return $latitude < $this->latitude + 0.06 &&
            $latitude > $this->latitude - 0.06 &&
            $longitude < $this->longitude + 0.06 &&
            $longitude > $this->longitude - 0.06;
    }
}
