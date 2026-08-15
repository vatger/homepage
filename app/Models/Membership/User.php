<?php

namespace App\Models\Membership;

use App\Models\Membership\Concerns\HasBanConcern;
use App\Models\Membership\Concerns\HasFirConcern;
use App\Models\Membership\Concerns\HasGDPRConcern;
use App\Models\Membership\Concerns\HasTeamConcern;
use App\Models\TeamspeakRegistration;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements OAuthenticatable
{
    use HasApiTokens, HasBanConcern, HasFactory, HasFirConcern, HasGDPRConcern, Notifiable;
    use HasRoles, HasTeamConcern {
        HasTeamConcern::teams insteadof HasRoles;
    }

    protected $table = 'user_users';

    protected $fillable = ['id', 'firstname', 'lastname', 'email', 'email_backup'];

    protected $hidden = ['email', 'email_backup'];

    protected $casts = [''];

    protected $appends = ['username', 'username_short'];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public $incrementing = false;

    protected static function booted(): void
    {
        static::saved(function (self $user) {
            $user->passwords()->updateOrCreate(['user_id' => $user->id]);
            $user->settings()->firstOrCreate(['user_id' => $user->id], ['language' => 'de']);
            $user->vatgerDetails()->updateOrCreate(['user_id' => $user->id]);
            $user->vatsimDetails()->updateOrCreate(['user_id' => $user->id]);
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

    public function surveyKeys(): HasMany
    {
        return $this->hasMany(SurveyKey::class, 'user_id', 'id');
    }

    public function staffDetails(): HasOne
    {
        return $this->hasOne(UserStaffDetail::class, 'user_id', 'id');
    }

    public function getUsernameAttribute(): string
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getUsernameShortAttribute(): string
    {
        return $this->firstname.' '.\Str::substr($this->lastname, 0, 1).'.';
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
