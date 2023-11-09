<?php

namespace App\Models;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Model;

class SurveyKey extends Model
{
    protected $table = 'user_surveykeys';
    protected $casts = [
        'valid_till' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
