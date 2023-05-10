<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingGroup extends Model
{
    protected $table = 'booking_groups';

    protected $fillable = ['name'];

    public function bookings(): HasMany
    {
        return $this->hasMany(AtcBooking::class, 'booking_group_id', 'id');
    }
}
