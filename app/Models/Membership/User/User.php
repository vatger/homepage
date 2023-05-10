<?php

namespace App\Models\Membership\User;

use App\Models\Feedback\ControllerFeedback;
use App\Models\Membership\TeamSpeak\Registration;
use App\Models\Membership\User\Concerns\HasBanConcern;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens, HasBanConcern;

    protected $table = 'user_users';

    protected $fillable = ['id', 'firstname', 'lastname', 'email', 'email_backup'];

    protected $hidden = ['email', 'email_backup'];

    protected $casts = [''];

    protected $appends = ['username'];

    public $incrementing = false;

    public function passwords(): HasOne
    {
        return $this->hasOne(UserPassword::class, 'user_id', 'id');
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_id', 'id');
    }

    public function serviceAccounts(): HasOne
    {
        return $this->hasOne(UserServiceAccount::class, 'user_id', 'id');
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
        return $this->hasMany(Registration::class, 'user_id', 'id');
    }

    public function membershipNotes(): HasMany
    {
        return $this->hasMany(UserPassword::class, 'user_id', 'id');
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

    public function setRememberToken($value): void
    {
        $this->passwords()->update(['remember_token' => $value]);
    }

    public function getRememberToken(): string|null
    {
        return $this->passwords()
            ?->first()
            ?->get('remember_token');
    }
}
