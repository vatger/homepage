<?php

namespace App\Models\Membership\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserSetting extends Model
{
    protected $primaryKey = 'user_id';

    protected $table = 'user_settings';

    protected $fillable = ['gdpr_agreed_at', 'imprint_agreed_at', 'termsofuse_agreed_at', 'satzung_agreed_at', 'language'];

    protected $dates = [
        'gdpr_agreed_at' => 'date',
        'imprint_agreed_at' => 'date',
        'termsofuse_agreed_at' => 'date',
        'satzung_agreed_at' => 'date',
    ];
    protected $appends = ['gdpr_agreed', 'imprint_agreed', 'termsofuse_agreed', 'satzung_agreed', 'agreed'];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getGdprAgreedAttribute(): bool
    {
        return $this->gdpr_agreed_at > Carbon::createFromTimestamp(Storage::lastModified('policies/gdpr.html'));
    }

    public function getImprintAgreedAttribute(): bool
    {
        return $this->imprint_agreed_at > Carbon::createFromTimestamp(Storage::lastModified('policies/imprint.html'));
    }

    public function getTermsofuseAgreedAttribute(): bool
    {
        return $this->termsofuse_agreed_at > Carbon::createFromTimestamp(Storage::lastModified('policies/termsofuse.txt'));
        /* config('vatger.termsofuse_date') */
    }

    public function getSatzungAgreedAttribute(): bool
    {
        return $this->satzung_agreed_at > Carbon::createFromTimestamp(Storage::lastModified('policies/satzung.txt'));
        /* config('vatger.termsofuse_date') */
    }

    public function getAgreedAttribute(): bool
    {
        return $this->gdpr_agreed && $this->termsofuse_agreed && $this->imprint_agreed && $this->satzung_agreed;
    }
}
