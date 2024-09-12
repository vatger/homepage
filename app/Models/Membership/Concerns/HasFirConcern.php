<?php

namespace App\Models\Membership\Concerns;

use App\Models\Groups\Fir;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

trait HasFirConcern
{
    /**
     * All FIRs including left ones
     */
    public function firs(): HasManyThrough|Fir
    {
        return $this->hasManyThrough(Fir::class, FirMembership::class, 'user_id', 'id', 'id', 'fir_id')
            ->withTrashedParents()
            ->select('*');
    }

    public function fir(): HasOneThrough|Fir
    {
        return $this->hasOneThrough(Fir::class, FirMembership::class, 'user_id', 'id', 'id', 'fir_id')->select('*');
    }

    public function fir_membership(): HasOne|FirMembership
    {
        return $this->hasOne(FirMembership::class);
    }
}
