<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Runway extends Model
{
    protected $table = 'navigation_runways';

    protected $fillable = ['aerodrome_id', 'ident', 'heading', 'width', 'length', 'surface_type', 'threshold', 'opposite_id'];

    protected $appends = ['surfaceTypeString'];

    public const SURFACE_TYPE_ASPHALT = 1;
    public const SURFACE_TYPE_CONCRETE = 2;
    public const SURFACE_TYPE_GRASS = 3;
    public const SURFACE_TYPE_WATER = 4;
    public const SURFACE_TYPE_SAND = 5;
    public const SURFACE_TYPE_GRE = 6;

    public function aerodrome(): BelongsTo
    {
        return $this->belongsTo(Aerodrome::class);
    }

    public function opposite(): BelongsTo
    {
        return $this->belongsTo(self::class, 'opposite_id', 'id');
    }

    public function getSurfaceTypeStringAttribute(): string
    {
        return match ($this->surface_type) {
            self::SURFACE_TYPE_ASPHALT => trans('pilot.aerodromes.aerodrome.navigation.surface.asphalt'),
            self::SURFACE_TYPE_CONCRETE => trans('pilot.aerodromes.aerodrome.navigation.surface.concrete'),
            self::SURFACE_TYPE_GRASS => trans('pilot.aerodromes.aerodrome.navigation.surface.grass'),
            self::SURFACE_TYPE_WATER => trans('pilot.aerodromes.aerodrome.navigation.surface.water'),
            self::SURFACE_TYPE_SAND => trans('pilot.aerodromes.aerodrome.navigation.surface.sand'),
            self::SURFACE_TYPE_GRE => trans('pilot.aerodromes.aerodrome.navigation.surface.graded'),
            default => trans('pilot.aerodromes.aerodrome.navigation.surface.unknown'),
        };
    }
}
