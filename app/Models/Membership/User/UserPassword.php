<?php

namespace App\Models\Membership\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPassword extends Model
{
    use HasFactory;

    protected $table = 'user_passwords';

    public $timestamps = false;

    protected $fillable = ['remember_token', 'oauth_access_token', 'oauth_refresh_token', 'oauth_token_expires'];

    protected $hidden = ['password', 'remember_token', 'oauth_access_token', 'oauth_refresh_token', 'oauth_token_expires'];

    /**
     * Returns the user belonging to these credentials
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
