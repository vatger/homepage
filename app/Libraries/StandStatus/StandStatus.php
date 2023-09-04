<?php

namespace App\Libraries\StandStatus;

use CobaltGrid\VatsimStandStatus\Exceptions\CoordinateOutOfBoundsException;
use CobaltGrid\VatsimStandStatus\StandStatus as BaseStatus;
use Illuminate\Support\Str;

class StandStatus extends BaseStatus
{
    private float $maxStandDistance = 0.03; // In kilometeres
    private bool $hideStandSidesWhenOccupied = true;
    private int $maxDistanceFromAirport = 5; // In kilometeres
    private int $maxAircraftAltitude = 3000; // In feet
    private int $maxAircraftGroundspeed = 10; // In knots

    /**
     * Initialize the Status class
     * @param [type]  $latitude           [description]
     * @param [type]  $longitude          [description]
     * @throws CoordinateOutOfBoundsException
     */
    function __construct($latitude, $longitude)
    {
        parent::__construct($latitude, $longitude);
        $this->setMaxStandDistance($this->maxStandDistance);
        $this->setHideStandSidesWhenOccupied($this->hideStandSidesWhenOccupied);
        $this->setMaxDistanceFromAirport($this->maxDistanceFromAirport);
        $this->setMaxAircraftAltitude($this->maxAircraftAltitude);
        $this->setMaxAircraftGroundspeed($this->maxAircraftGroundspeed);
        $this->setStandExtensions(['R', 'L', 'A', 'B', 'C']);
    }
}
