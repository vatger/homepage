<?php

namespace App\Models;

use App\Models\Membership\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamspeakRegistration extends Model
{
    use SoftDeletes;

    protected $table = 'teamspeak_registrations';

    protected $fillable = ['*'];

    protected $attributes = ['registration_ip' => '0.0.0.0', 'last_ip' => '0.0.0.0'];

    protected $dates = ['created_at', 'updated_at', 'last_login'];

    /**
     * The associated account.
     */
    public function user(): BelongsTo|User
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
