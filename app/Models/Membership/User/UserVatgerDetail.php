<?php

namespace App\Models\Membership\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVatgerDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $table = 'user_vatger_details';

    protected $fillable = ['last_seen_at', 'vatger_member_at'];

    public $timestamps = false;

    protected $casts = ['last_seen_at' => 'datetime', 'registered_at' => 'datetime', 'vatger_member_at' => 'datetime', 'inactive_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function check_status(User $user): void
    {
        $user->loadMissing('vatgerDetails', 'vatsimDetails');
        if ($user->vatgerDetails->vatger_member_at == null && $user->vatsimDetails->subdivision_code == 'GER') {
            $user->vatgerDetails->update(['vatger_member_at' => Carbon::now()]);
        } elseif ($user->vatgerDetails->vatger_member_at != null && $user->vatsimDetails->subdivision_code != 'GER') {
            $user->vatgerDetails->update(['vatger_member_at' => null]);
        }
    }
}
