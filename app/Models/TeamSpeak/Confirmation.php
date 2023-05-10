<?php

namespace App\Models\TeamSpeak;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Confirmation extends Model
{
    public $incrementing = false;

    protected $table = 'teamspeak_confirmation';

    protected $primaryKey = 'registration_id';

    public function registration(): BelongsTo
    {
        return $this->belongsTo(\App\Models\TeamSpeak\Registration::class, 'registration_id', 'id');
    }
}
