<?php

namespace App\Models\Membership\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVatsimDetail extends Model
{
    protected $primaryKey = 'user_id';

    protected $table = 'user_vatsim_details';

    protected $guarded = [];
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
