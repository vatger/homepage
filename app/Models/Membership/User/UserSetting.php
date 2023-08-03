<?php

namespace App\Models\Membership\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class UserSetting extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $table = 'user_settings';

    protected $fillable = ['language'];

    protected $appends = ['gdpr_agreed', 'termsofuse_agreed', 'agreed'];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getGdprAgreedAttribute(): bool
    {
        return $this->gdpr_agreed_at > Carbon::createFromTimestamp(Storage::lastModified('policies/gdpr.html')) &&
            $this->gdpr_agreed_at > Carbon::createFromTimestamp(Storage::lastModified('policies/imprint.html'));
        /* config('vatger.gdpr_date')*/
    }

    public function getTermsofuseAgreedAttribute(): bool
    {
        return $this->termsofuse_agreed_at > Carbon::createFromTimestamp(Storage::lastModified('policies/termsofuse.txt'));
        /* config('vatger.termsofuse_date') */
    }

    public function getAgreedAttribute(): bool
    {
        return $this->gdpr_agreed && $this->termsofuse_agreed;
    }
}
