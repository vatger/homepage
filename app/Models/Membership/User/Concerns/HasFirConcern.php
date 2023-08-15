<?php

namespace App\Models\Membership\User\Concerns;

use App\Models\Groups\Fir;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasFirConcern
{
    /**
     * All FIRs including left ones
     */
    public function firs(): BelongsToMany|Fir
    {
        return $this->belongsToMany(Fir::class, 'user_firs')->withPivot(['joined_at', 'deleted_at']);
    }

    public function fir(): BelongsTo|Fir
    {
        return $this->belongsTo(Fir::class, relation: 'user_firs');
    }
}
