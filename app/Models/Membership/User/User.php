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

    /**
     * Returns the user's settings / preferences
     *
     * @return HasOne
     */
    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function serviceAccounts(): HasOne
    {
        return $this->hasOne(UserServiceAccount::class, 'user_id', 'id');
    }

    /**
     * Returns the VATGER Details for this user
     *
     * @return HasOne
     */
    public function vatgerDetails(): HasOne
    {
        return $this->hasOne(UserVatgerDetail::class, 'user_id', 'id');
    }

    /**
     * Returns the vatsim details for this user
     *
     * @return HasOne
     */
    public function vatsimDetails(): HasOne
    {
        return $this->hasOne(UserVatsimDetail::class, 'user_id', 'id');
    }

    /**
     * Returns collection of teamspeak registrations made by this user
     *
     * @return HasMany
     */
    public function teamspeakRegistrations(): HasMany
    {
        return $this->hasMany(TeamspeakRegistration::class, 'user_id', 'id');
    }

    /**
     * Returns collection of feedback submitted for this user
     *
     * @return HasMany
     */
    public function controllerFeedback(): HasMany
    {
        return $this->hasMany(ControllerFeedback::class, 'controller_id', 'id');
    }

    /**
     * Returns collection of feedback submitted by this user
     *
     * @return HasMany
     */
    public function controllerReports(): HasMany
    {
        return $this->hasMany(ControllerFeedback::class, 'reporter_id', 'id');
    }

    /**
     * Returns computed property of first and last name, i.e. the full name of the user
     *
     * @return string
     */
    public function getUsernameAttribute(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Returns computed property of first and last name, i.e. the full name of the user
     *
     * @return string
     */
    public function getUsernameShortAttribute(): string
    {
        return $this->firstname . ' ' . \Str::substr($this->lastname, 0, 1) . '.';
    }

    /**
     * Returns the current user's auth information (remember_tokens, oauth_access_tokens, etc.)
     *
     * @return HasOne
     */
    public function passwords(): HasOne
    {
        return $this->hasOne(UserPassword::class, 'user_id', 'id');
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param string $value
     * @return void
     */
    public function setRememberToken($value): void
    {
        $this->passwords()->update(['remember_token' => $value]);
    }

    /**
     * Gets the token value for the "remember me"
     *
     * @return string|null
     */
    public function getRememberToken(): string|null
    {
        return $this->passwords()?->value('remember_token');
    }
}
