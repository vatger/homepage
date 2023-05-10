<?php

namespace App\Models\Navigation;

use App\Models\Booking\AtcBooking;
use App\Models\Feedback\ControllerFeedback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Station extends Model
{
    protected $table = 'nav_stations';

    protected $fillable = ['name', 'ident', 'frequency', 'atis', 'bookable'];

    protected $appends = ['fixedFrequency'];

    public function aerodromes(): BelongsToMany
    {
        return $this->belongsToMany(Aerodrome::class, 'nav_aerodrome_station', 'station_id', 'aerodrome_id')->withPivot('order');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(AtcBooking::class, 'station_id', 'id');
    }

    public function controllerFeedbacks(): HasMany
    {
        return $this->hasMany(ControllerFeedback::class, 'station_id', 'id');
    }

    public function getFixedFrequencyAttribute(): string
    {
        return number_format($this->frequency, 3);
    }

    public function scopeBookable(Builder $query): Builder
    {
        return $query->where('bookable', true);
    }

    public function scopeAtis(Builder $query): Builder
    {
        return $query->where('atis', true);
    }
}
