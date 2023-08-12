<?php

namespace App\Models\Membership\User;

use App\Models\Groups\FIRCommunity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserFir extends Model
{
    protected $primaryKey = 'user_id';
    protected $table = 'user_firs';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function firInformation(): HasOne
    {
        return $this->hasOne(FIRCommunity::class, 'fir_id', 'id');
    }
}
