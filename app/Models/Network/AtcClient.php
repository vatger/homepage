<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Model;

class AtcClient extends Model
{
    protected $table = 'networkdata_atc';

    protected $fillable = ['account_id', 'connected_at', 'callsign'];

    public $dates = ['connected_at', 'disconnected_at'];

    protected $appends = ['fixedFrequency'];

    /**
     * The account that might be the controller...
     * But only if the controller is registered within the system,
     * else this will return null
     */
    public function account()
    {
        return $this->belongsTo(\App\Models\Membership\Account::class, 'account_id', 'id');
    }

    /**
     * Station is offline
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeOffline($query)
    {
        return $query->whereNotNull('disconnected_at');
    }

    /**
     * Is the station considered online?
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeOnline($query)
    {
        return $query->whereNull('disconnected_at');
    }

    /**
     * Find station by icao.
     * THIS ONLY CHECKS IF THE ICAO OF AN AERODROME IS CONTAINED
     * @param  [type] $query [description]
     * @param  [type] $icao  [description]
     * @return [type]        [description]
     */
    public function scopeIcao($query, $icao)
    {
        return $query->where('callsign', 'LIKE', '%' . $icao . '%');
    }

    /**
     * Get all clients that match one of the given callsigns
     *
     * @param  [type] $query     [description]
     * @param  array  $callsigns [description]
     * @return [type]            [description]
     */
    public function scopeWithCallsignIn($query, array $callsigns)
    {
        return $query->where(function ($query) use ($callsigns) {
            foreach ($callsigns as $callsign) {
                $query->orWhere('callsign', 'LIKE', $callsign);
            }
        });
    }

    /**
     * Is the station within the german airspace
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeIsDe($query)
    {
        return $query->where(function ($subQuery) {
            return $subQuery
                ->where('callsign', 'LIKE', 'ED__%')
                ->orWhere('callsign', 'LIKE', 'ETA_%')
                ->orWhere('callsign', 'LIKE', 'ETH_%')
                ->orWhere('callsign', 'LIKE', 'ETI_%')
                ->orWhere('callsign', 'LIKE', 'ETM_%')
                ->orWhere('callsign', 'LIKE', 'ETN_%')
                ->orWhere('callsign', 'LIKE', 'ETS_%');
        });
    }

    /**
     * Format the frequency to always have 3 digits after the divider
     *
     * @return [type] [description]
     */
    public function getFixedFrequencyAttribute()
    {
        return number_format($this->frequency, 3);
    }
}
