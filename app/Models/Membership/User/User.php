<?php

namespace App\Models\Membership\User;

use App\Models\Feedback\ControllerFeedback;
use App\Models\Membership\TeamspeakRegistration;
use App\Models\Membership\User\Concerns\HasBanConcern;
use App\Models\Membership\User\Concerns\HasFirConcern;
use App\Models\Membership\User\Concerns\HasTeamConcern;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens, HasBanConcern, HasFirConcern, HasTeamConcern;

    protected $table = 'user_users';

    protected $fillable = ['id', 'firstname', 'lastname', 'email', 'email_backup'];

    protected $hidden = ['email', 'email_backup'];

    protected $casts = [''];

    protected $appends = ['username', 'username_short'];

    public $incrementing = false;

    protected static function booted(): void
    {
        static::saved(function (self $user) {
            $user->passwords()->updateOrCreate([]);
            $user->settings()->updateOrCreate([]);
            $user->vatgerDetails()->updateOrCreate([]);
            $user->vatsimDetails()->updateOrCreate([]);
        });
        static::deleting(function (self $user) {
            $user->passwords()->delete();
            $user->settings()->delete();
            $user->vatgerDetails()->delete();
            $user->vatsimDetails()->delete();
        });
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_id', 'id');
    }

    public function vatgerDetails(): HasOne
    {
        return $this->hasOne(UserVatgerDetail::class, 'user_id', 'id');
    }

    public function vatsimDetails(): HasOne
    {
        return $this->hasOne(UserVatsimDetail::class, 'user_id', 'id');
    }

    public function teamspeakRegistrations(): HasMany
    {
        return $this->hasMany(TeamspeakRegistration::class, 'user_id', 'id');
    }

    public function controllerFeedback(): HasMany
    {
        return $this->hasMany(ControllerFeedback::class, 'controller_id', 'id');
    }

    public function controllerReports(): HasMany
    {
        return $this->hasMany(ControllerFeedback::class, 'reporter_id', 'id');
    }

    public function getUsernameAttribute(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getUsernameShortAttribute(): string
    {
        return $this->firstname . ' ' . \Str::substr($this->lastname, 0, 1) . '.';
    }

    public function passwords(): HasOne
    {
        return $this->hasOne(UserPassword::class, 'user_id', 'id');
    }

    public function setRememberToken($value): void
    {
        $this->passwords()->update(['remember_token' => $value]);
    }

    public function getRememberToken(): ?string
    {
        return $this->passwords()?->value('remember_token');
    }
}
