<?php

namespace App\Models\Membership\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Membership\User\UserVatsimDetail
 *
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $registered_at
 * @property string|null $last_rating_change_at
 * @property int $rating_atc
 * @property int $rating_pilot
 * @property int $rating_military
 * @property float $time_atc
 * @property float $time_pilot
 * @property string|null $country_code
 * @property string|null $country_name
 * @property string|null $region_code
 * @property string|null $region_name
 * @property string|null $division_code
 * @property string|null $division_name
 * @property string|null $subdivision_code
 * @property string|null $subdivision_name
 * @property-read string $rating_atc_long
 * @property-read string $rating_atc_short
 * @property-read string $rating_military_short
 * @property-read string $rating_mititary_long
 * @property-read string $rating_pilot_long
 * @property-read string $rating_pilot_short
 * @property-read \App\Models\Membership\User\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereCountryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereDivisionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereDivisionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereLastRatingChangeAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereRatingAtc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereRatingMilitary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereRatingPilot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereRegionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereRegionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereRegisteredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereSubdivisionCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereSubdivisionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereTimeAtc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereTimePilot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVatsimDetail whereUserId($value)
 * @property-read string $rating_military_long
 * @mixin \Eloquent
 */
class UserVatsimDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $table = 'user_vatsim_details';

    protected $fillable = [
        'user_id',
        'rating_atc',
        'rating_pilot',
        'rating_military',
        'country_code',
        'country_name',
        'region_code',
        'region_name',
        'division_code',
        'division_name',
        'subdivision_code',
        'subdivision_name',
    ];

    protected $appends = [
        'rating_atc_short',
        'rating_pilot_short',
        'rating_military_short',
        'rating_atc_long',
        'rating_pilot_long',
        'rating_military_long',
    ];

    protected $casts = ['registered_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function isVaccSet()
    {
        return $this->subdivision_code != null;
    }

    public function getRatingAtcShortAttribute(): string
    {
        return match ($this->rating_atc) {
            -1 => 'INAC',
            0 => 'SUS',
            1 => 'OBS',
            2 => 'S1',
            3 => 'S2',
            4 => 'S3',
            5 => 'C1',
            6 => 'C2',
            7 => 'C3',
            8 => 'I1',
            9 => 'I2',
            10 => 'I3',
            11 => 'SUP',
            12 => 'ADM',
            default => 'err',
        };
    }

    public function getRatingAtcLongAttribute(): string
    {
        return match ($this->rating_atc) {
            -1 => 'Inactive',
            0 => 'Suspended',
            1 => 'Observer',
            2 => 'Tower Trainee',
            3 => 'Tower Controller',
            4 => 'Senior Student',
            5 => 'Enroute Controller',
            6 => 'Controller 2 (not in use)',
            7 => 'Senior Controller',
            8 => 'Instructor',
            9 => 'Instructor 2 (not in use)',
            10 => 'Senior Instructor',
            11 => 'Supervisor',
            12 => 'Administrator',
            default => 'Unknown',
        };
    }

    public function getRatingPilotShortAttribute(): string
    {
        return match ($this->rating_pilot) {
            0 => 'NEW',
            1 => 'PPL',
            3 => 'IR',
            7 => 'CMEL',
            15 => 'ATPL',
            31 => 'FI',
            63 => 'FE',
            default => 'err',
        };
    }

    public function getRatingPilotLongAttribute(): string
    {
        return match ($this->rating_pilot) {
            0 => 'Basic Member',
            1 => 'Private Pilot License',
            3 => 'Instrument Rating',
            7 => 'Commercial Multi-Engine License',
            15 => 'Airline Transport Pilot License',
            31 => 'Flight Instructor',
            63 => 'Flight Examiner',
            default => 'Unknown',
        };
    }

    public function getRatingMilitaryShortAttribute(): string
    {
        return match ($this->rating_military) {
            0 => 'M0',
            1 => 'M1',
            3 => 'M2',
            7 => 'M3',
            15 => 'M4',
            default => 'err',
        };
    }

    public function getRatingMilitaryLongAttribute(): string
    {
        return match ($this->rating_military) {
            0 => 'No military rating',
            1 => 'Military Pilot License',
            3 => 'Military Instrument Rating',
            7 => 'Military Multi-Engine Rating',
            15 => 'Military Mission Ready Pilot',
            default => 'Unknown',
        };
    }
}
