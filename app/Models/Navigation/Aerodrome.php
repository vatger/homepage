<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Aerodrome extends Model
{
    protected $table = 'nav_aerodromes';

    protected $fillable = ['useChartfox'];

    protected static array $logAttributes = ['*'];

    /**
     * All regionalgroups this aerodrome is assigned to
     */
    //public function regionalgroups(): BelongsToMany
    //{
    //    return $this->belongsToMany(Regionalgroup::class, 'navigation_aerodrome_regionalgroup', 'regionalgroup_id', 'aerodrome_id');
    //}

    /**
     * Get all assigned stations
     */
    public function stations(): BelongsToMany
    {
        return $this->belongsToMany(Station::class, 'nav_aerodrome_stations', 'aerodrome_id', 'station_id')
            ->withPivot('order')
            ->orderByPivot('order', 'ASC');
    }

    /**
     * All runways this aerodrome has
     */
    public function runways(): HasMany
    {
        $this->hasMany(Runway::class, 'aerodrome_id', 'id');
    }

    /**
     * All associated navaids
     */
    public function navaids(): BelongsToMany
    {
        return $this->belongsToMany(Navaid::class, 'nav_aerodrome_navaids', 'aerodrome_id', 'navaid_id');
    }

    /**
     * The charts associated with this aerodrome
     *
     * @return BelongsToMany [type] [description]
     */
    //public function charts()
    //{
    //    return $this->belongsToMany(Chart::class, 'navigation_aerodrome_charts', 'aerodrome_id', 'chart_id');
    //}

    /**
     * Get an aerodrome by its icao
     */
    public function scopeIcao(Builder $query, string $icao): Builder
    {
        return $query->where('icao', $icao);
    }

    /**
     * Get only aerodromes that are assigned to Germany
     */
    public function scopeIsDe(Builder $query): Builder
    {
        return $query->where('country_short', 'DE');
    }

    /*
     * The country the aerodrome is situated at
     *
     * public function countryDetail(): BelongsTo
     * {
     * return $this->belongsTo(Country::class, 'country', 'alpha_2');
     * }
     */

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
