<?php

namespace App\Models\Membership\Concerns;

use App\Models\Groups\Fir;
use App\Models\Membership\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirMembership extends Pivot
{
    use SoftDeletes;

    public $table = 'user_firs';
    public $incrementing = true;

    protected $fillable = ['joined_at', 'active_fir_member_at'];

    protected $casts = [
        'joined_at' => 'date',
        'active_fir_member_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->piv;
    }

    public function fir(): BelongsTo
    {
        return $this->belongsTo(Fir::class);
    }
}
