<?php

namespace App\Models\TeamSpeak;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    protected $table = 'teamspeak_registration';

    protected $primaryKey = 'id';

    protected $fillable = ['*'];

    protected $attributes = ['registration_ip' => '0.0.0.0', 'last_ip' => '0.0.0.0'];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * Delete a registration while cascading the confirmation.
     */
    public function delete(): void
    {
        if ($this->confirmation) {
            $this->confirmation->delete();
        }
        parent::delete();
    }

    /**
     * The confirmation to this registration.
     */
    public function confirmation(): HasOne
    {
        return $this->hasOne(Confirmation::class, 'registration_id', 'id');
    }

    /**
     * The associated account.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id');
    }
}
