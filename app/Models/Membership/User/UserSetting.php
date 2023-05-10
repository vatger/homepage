<?php

namespace App\Models\Membership\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->gdpr_agreed_at > Carbon::createFromFormat('Y-m-d', '2022-12-01'); /* config('vatger.gdpr_date')*/
    }

    public function getTermsofuseAgreedAttribute(): bool
    {
        return $this->termsofuse_agreed_at > Carbon::createFromFormat('Y-m-d', '2022-12-02'); /* config('vatger.termsofuse_date') */
    }

    public function getAgreedAttribute(): bool
    {
        return $this->gdpr_agreed && $this->termsofuse_agreed;
    }
}
