<?php

namespace App\Models\Membership\User\Concerns;

use App\Models\Groups\Fir;
use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirMembership extends Pivot
{
    use SoftDeletes;

    public $table = 'user_firs';
    public $incrementing = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->piv;
    }

    public function fir(): BelongsTo
    {
        return $this->belongsTo(Fir::class);
    }
}
