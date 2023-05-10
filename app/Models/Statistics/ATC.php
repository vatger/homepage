<?php

namespace App\Models\Statistics;

use App\Models\Navigation\Station;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ATC extends Model
{
    /**
     * The name of the database table to use
     *
     * @var string
     */
    protected $table = 'statistics_atc';

    /**
     * Array of guarded fields
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Array of automatically casted fields
     *
     * @var array
     */
    public $casts = [
        'connected_at' => 'datetime',
        'disconnected_at' => 'datetime',
    ];

    /**
     * Filter models to only grab completed sessions
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereNotNull('connected_at')->whereNotNull('disconnected_at');
    }

    /**
     * Get only sessions that are related to locally added/maintained stations
     */
    public function scopeDe(Builder $query): Builder
    {
        return $query->whereIn(
            'station_ident',
            Station::all()
                ->pluck('ident')
                ->toArray(),
        );
    }

    /**
     * Get only models related to an account id
     */
    public function scopeForCid(Builder $query, $cid): Builder
    {
        return $query->where('account_id', $cid);
    }
}
