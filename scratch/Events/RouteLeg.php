<?php

namespace Events;

use App\Models\Membership\User\User;
use App\Models\Navigation\Aerodrome;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RouteLeg extends Model
{
    use HasFactory;

    protected $table = 'event_routelegs';

    protected $fillable = ['route_id', 'departureaerodrome_id', 'arrivalaerodrome_id'];

    /**
     * Get the route that owns the RouteLeg
     *
     * @return BelongsTo
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(EventRoute::class, 'route_id', 'id');
    }

    /**
     * The accounts that belong to the RouteLeg
     *
     * @return BelongsToMany
     */
    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_account_routelegs', 'routeleg_id', 'account_id')->withPivot([
            'completed_at',
            'flight_data_id',
        ]);
    }

    /**
     * Get the departure that owns the RouteLeg
     *
     * @return BelongsTo
     */
    public function departure(): BelongsTo
    {
        return $this->belongsTo(Aerodrome::class, 'departureaerodrome_id', 'id');
    }

    /**
     * Get the arrival that owns the RouteLeg
     *
     * @return BelongsTo
     */
    public function arrival(): BelongsTo
    {
        return $this->belongsTo(Aerodrome::class, 'arrivalaerodrome_id', 'id');
    }
}
