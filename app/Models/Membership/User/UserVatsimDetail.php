<?php

namespace App\Models\Membership\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVatsimDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';

    protected $table = 'user_vatsim_details';

    protected $fillable = [
        'user_id',
        'rating_atc',
        'rating_pilot',
        'country_code',
        'country_name',
        'region_code',
        'region_name',
        'division_code',
        'division_name',
        'subdivision_code',
        'subdivision_name',
    ];

    protected $appends = ['rating_atc_short', 'rating_pilot_short', 'rating_atc_long', 'rating_pilot_long'];

    protected $dates = ['registered_at'];

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
        return match ($this->rating_atc) {
            0 => 'NEW',
            1 => 'PPL',
            3 => 'IR',
            7 => 'CMEL',
            15 => 'ATPL',
            default => 'err',
        };
    }

    public function getRatingPilotLongAttribute(): string
    {
        return match ($this->rating_atc) {
            0 => 'Basic Member',
            1 => 'Private Pilot License',
            3 => 'Instrument Rating',
            7 => 'Commercial Multi-Engine License',
            15 => 'Airline Transport Pilot License',
            default => 'Unknown',
        };
    }
}
