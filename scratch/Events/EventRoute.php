<?php

namespace Events;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class EventRoute extends Model
{
    use HasFactory;

    protected $table = 'event_routes';

    protected $dates = ['begins_at', 'ends_at', 'created_at', 'updated_at'];

    protected $appends = ['joined_by_me'];

    protected $fillable = ['name', 'begins_at', 'ends_at', 'description', 'flight_rules', 'visible', 'aircrafts', 'link', 'img_url', 'require_order'];

    /**
     * Get all of the legs for the EventRoute
     *
     * @return HasMany
     */
    public function legs(): HasMany
    {
        return $this->hasMany(RouteLeg::class, 'route_id', 'id');
    }

    /**
     * Get all of the accounts for the EventRoute
     *
     * @return Collection
     */
    public function getAccountsAttribute(): Collection
    {
        $accounts = collect();
        foreach ($this->legs as $l) {
            foreach ($l->accounts as $a) {
                if (!$accounts->contains($a)) {
                    $accounts->push($a);
                }
            }
        }
        return $accounts;
    }

    /**
     * Am I part of this?
     *
     * @return bool
     */
    public function getJoinedByMeAttribute(): bool
    {
        if (Auth::check()) {
            return $this->accounts->contains(Auth::user());
        }

        return false;
    }
}
