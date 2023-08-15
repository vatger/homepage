<?php

namespace App\Models\Membership\User;

use App\Models\Groups\Fir;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserFirDEL extends Model
{
    protected $primaryKey = 'user_id';
    protected $table = 'user_firs';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function firInformation(): HasOne
    {
        return $this->hasOne(Fir::class, 'id', 'fir_id');
    }
}
