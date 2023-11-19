<?php

namespace App\Models;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyKey extends Model
{
    protected $table = 'user_surveykeys';
    protected $casts = [
        'valid_till' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
