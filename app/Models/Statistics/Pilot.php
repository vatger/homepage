<?php

namespace App\Models\Statistics;

use App\Models\Navigation\Aerodrome;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pilot extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'statistics_pilots';

    /**
     * Array of automatically casted fields
     *
     * @var array
     */
    public $casts = [
        'departed_at' => 'datetime',
        'arrived_at' => 'datetime',
        'arrived_alternate_at' => 'datetime',
        'connected_at' => 'datetime',
        'disconnected_at' => 'datetime',
    ];

    /**
     * List of guarded fields
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the departure that owns the Pilot
     *
     * @return BelongsTo
     */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(Aerodrome::class, 'departure_id', 'id');
    }

    /**
     * Get the destination that owns the Pilot
     *
     * @return BelongsTo
     */
    public function destination(): BelongsTo
    {
        return $this->belongsTo(Aerodrome::class, 'destination_id', 'id');
    }

    /**
     * Get the alternate that owns the Pilot
     *
     * @return BelongsTo
     */
    public function alternate(): BelongsTo
    {
        return $this->belongsTo(Aerodrome::class, 'alternate_id', 'id');
    }

    /**
     * Only completed flights
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query
            ->whereNotNull('departure_id')
            ->where(function ($query) {
                return $query->whereNotNull('destination_id')->orWhereNotNull('alternate_id');
            })
            ->where('disconnected_at', '!=', null)
            ->where('departed_at', '!=', null)
            ->where(function ($query) {
                return $query->where('arrived_at', '!=', null)->orWhere('arrived_alternate_at', '!=', null);
            });
    }

    /**
     * Only flights related to german airfields
     */
    public function scopeGermany(Builder $query): Builder
    {
        $germanAirports = Aerodrome::isDe()
            ->get()
            ->pluck('id')
            ->toArray();

        return $query
            ->whereIn('departure_id', $germanAirports)
            ->orWhereIn('destination_id', $germanAirports)
            ->orWhereIn('alternate_id', $germanAirports);
    }

    /**
     * Only for a given cid
     */
    public function scopeForCid(Builder $query, int $cid): Builder
    {
        return $query->where('account_id', $cid);
    }
}
