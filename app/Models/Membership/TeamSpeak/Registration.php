<?php

namespace App\Models\Membership\TeamSpeak;

use App\Models\Membership\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory;

    use SoftDeletes;
    //use LogsActivity;

    protected $table = 'teamspeak_registration';

    protected $primaryKey = 'id';

    protected $fillable = ['*'];

    protected $attributes = ['registration_ip' => '0.0.0.0', 'last_ip' => '0.0.0.0'];

    protected $dates = ['created_at', 'updated_at', 'last_login'];

    /**
     * Delete a registration while cascading the confirmation.
     *
     * @return void [type] [description]
     */
    public function delete()
    {
        if ($this->confirmation) {
            $this->confirmation->delete();
        }
        parent::delete();
    }

    /**
     * The confirmation to this registration.
     *
     * @return HasOne [type] [description]
     */
    public function confirmation(): HasOne
    {
        return $this->hasOne(Confirmation::class, 'registration_id', 'id');
    }

    /**
     * The associated account.
     *
     * @return BelongsTo [type] [description]
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'account_id', 'id');
    }
}
