<?php

namespace App\Models;

use App\Models\Membership\User;
use App\Models\Navigation\Station;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as DBuilder;

/**
 * ATC Booking
 *
 * This class is a representaion of an AtcSessionBooking
 */
class AtcBooking extends Model
{
    protected $table = 'booking_bookings';

    protected $fillable = ['station_id', 'controller_id', 'starts_at', 'ends_at', 'voice', 'training', 'exam', 'event'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Custom attributes that shall be appended anytime
     *
     * @var array
     */
    protected $appends = ['startTime', 'endTime'];

    /**
     * Get the station that owns the AtcSessionBooking
     */
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'station_id', 'id');
    }

    /**
     * The controller that made the booking
     */
    public function controller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'controller_id', 'id')->select(['id', 'firstname', 'lastname']);
    }

    /**
     * Readable time format of the starting time
     *
     * @return string
     */
    public function getStartTimeAttribute()
    {
        return $this->starts_at->format('H:i');
    }

    /**
     * Readable time format of the ending time
     *
     * @return string
     */
    public function getEndTimeAttribute()
    {
        return $this->ends_at->format('H:i');
    }

    /**
     * Get all bookings for a given event
     *
     * @param  Builder  $query
     * @param  int  $id  The id of the event
     * @return Builder
     */
    public function scopeForEvent($query, $id)
    {
        return $query->where('event', true)->where('event_id', $id);
    }

    /**
     * All bookings an account has made
     *
     * @param  Builder  $query
     * @param  int  $id  The id of the account
     * @return Builder
     */
    public function scopeForAccountId($query, $id)
    {
        return $query->where('controller_id', $id);
    }

    /**
     * All bookings for a given station id
     *
     * @param  Builder  $query
     * @param  int  $id  The id of the station
     * @return Builder
     */
    public function scopeForStation($query, $id)
    {
        return $query->where('station_id', $id);
    }

    /**
     * Only bookings that have a scheduled end date in the future
     */
    public function scopeFuture(DBuilder|EBuilder $query): DBuilder|EBuilder
    {
        return $query->where(
            'ends_at',
            '>=',
            Carbon::now()
                ->utc()
                ->subHours(2),
        );
    }
}
