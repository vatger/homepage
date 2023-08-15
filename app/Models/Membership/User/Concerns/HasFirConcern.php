<?php

namespace App\Models\Membership\User\Concerns;

use App\Models\Groups\Fir;
use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Concerns\CanBeOneOfMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserFirPivot extends Pivot
{
    public $table = 'user_firs';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fir()
    {
        return $this->belongsTo(Fir::class);
    }
}

trait HasFirConcern
{
    /**
     * All FIRs including left ones
     */
    public function firs(): HasManyThrough
    {
        return $this->hasManyThrough(Fir::class, UserFirPivot::class, 'user_id', 'id', 'id', 'fir_id');
    }
    
    public function fir(): HasOneThrough
    {
        return $this->hasOneThrough(Fir::class, UserFirPivot::class, 'user_id', 'id', 'id', 'fir_id');
    }
}
