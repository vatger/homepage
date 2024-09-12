<?php

namespace App\Models\Membership;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVatgerDetail extends Model
{
    protected $primaryKey = 'user_id';

    protected $table = 'user_vatger_details';

    protected $fillable = [
        'last_seen_at',
        'registered_at',
        'active_member_at',
        'vatger_member_at',
        'active_vatger_member_at',
        'warning_inactive_at',
        'inactive_at',
        'warning_delete_at',
        'delete_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'last_seen_at' => 'datetime',
        'registered_at' => 'datetime',
        'active_member_at' => 'datetime',
        'vatger_member_at' => 'datetime',
        'active_vatger_member_at' => 'datetime',
        'warning_inactive_at' => 'datetime',
        'inactive_at' => 'datetime',
        'warning_delete_at' => 'datetime',
        'delete_at' => 'datetime',
    ];

    protected $appends = [
        'is_inactive',
        'is_vatger_member',
        'is_fir_member',
        'is_vatger_active_member',
        'is_fir_active_member',
        'is_vatger_voter',
        'is_fir_voter',
        'can_change_fir',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getIsInactiveAttribute(): bool
    {
        return !!$this->inactive_at;
    }

    public function getIsVatgerMemberAttribute(): bool
    {
        return !!$this->vatger_member_at;
    }

    public function getIsFirMemberAttribute(): bool
    {
        return !!$this->user->fir;
    }

    public function getIsVatgerActiveMemberAttribute(): bool
    {
        return !!$this->active_vatger_member_at;
    }

    public function getIsFirActiveMemberAttribute(): bool
    {
        return !!$this->user->fir?->active_fir_member_at;
    }

    public function getIsVatgerVoterAttribute(): bool
    {
        return Carbon::now()->diffInDays($this->active_vatger_member_at, true) >= 180;
    }

    public function getIsFirVoterAttribute(): bool
    {
        return $this->getIsFirActiveMemberAttribute() && Carbon::now()->diffInDays($this->user->fir?->active_fir_member_at, true) >= 180;
    }

    public function getCanChangeFirAttribute(): bool
    {
        return empty($this->getCanChangeFirReasonAttribute());
    }

    public function getCanChangeFirReasonAttribute(): ?string
    {
        //dd($this->user->settings->language);
        if (!$this->getIsVatgerMemberAttribute()) {
            return __('vatger-details.nomember', locale: $this->user->settings->language);
        }

        $latest_fir = $this->user
            ->firs()
            ->orderByDesc('joined_at')
            ->first();

        if (empty($latest_fir)) {
            return null;
        }

        $joined = Carbon::create($latest_fir->joined_at);
        $diff = Carbon::now()->diffInDays($joined, true);
        if ($diff < 90) {
            return __(
                'vatger-details.lastfir',
                [
                    'leftdate' => $joined->format('d.m.Y H:i'),
                    'waitdays' => 90 - $diff,
                    'waitdate' => Carbon::create($joined)
                        ->addDays(90)
                        ->format('d.m.Y H:i'),
                ],
                locale: $this->user->settings->language,
            );
        }

        return null;
    }
}
