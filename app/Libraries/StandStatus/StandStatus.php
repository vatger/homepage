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
        $stands = $this->setStandExtensions(['R', 'L', 'A', 'B', 'C']);
    }

    /**
     * Does a stand has sidestands?
     *
     * @param  [type] $standId [description]
     * @return [type]          [description]
     */
    public function standSides($standID)
    {
        $standSides = [];

        // Consider only those sidestands that are stands with appendix
        // R, L, A, B, C
        // but start in the same way as a normal stand
        // so we will check if a stand ends on one of those letter and remove it
        $standBase = '';
        if (Str::endsWith($standID, 'R')) {
            $standBase = Str::replaceLast('R', '', $standID);
        }
        if (Str::endsWith($standID, 'L')) {
            $standBase = Str::replaceLast('L', '', $standID);
        }
        if (Str::endsWith($standID, 'A')) {
            $standBase = Str::replaceLast('A', '', $standID);
        }
        if (Str::endsWith($standID, 'B')) {
            $standBase = Str::replaceLast('B', '', $standID);
        }
        if (Str::endsWith($standID, 'C')) {
            $standBase = Str::replaceLast('C', '', $standID);
        }

        // Check if stand has a side already
        if (Str::endsWith($standID, 'R') || Str::endsWith($standID, 'L')) {
            // Our stand is already L/R
            if (Str::endsWith($standID, 'R')) {
                if (isset($stands[$standBase . 'L'])) {
                    $standSides[] = $standBase . 'L';
                }
                if (isset($stands[$standBase . 'C'])) {
                    $standSides[] = $standBase . 'C';
                }
                if (isset($stands[$standBase])) {
                    $standSides[] = $standBase;
                }
            }
            if (Str::endsWith($standID, 'L')) {
                if (isset($stands[$standBase . 'R'])) {
                    $standSides[] = $standBase . 'R';
                }
                if (isset($stands[$standBase . 'C'])) {
                    $standSides[] = $standBase . 'C';
                }
                if (isset($stands[$standBase])) {
                    $standSides[] = $standBase;
                }
            }
        } elseif (strstr($standID, 'A') || strstr($standID, 'B')) {
            // Our stand already is A / B
            if (Str::endsWith($standID, 'A')) {
                if (isset($stands[$standBase . 'B'])) {
                    $standSides[] = $standBase . 'B';
                }
                if (isset($stands[$standBase . 'C'])) {
                    $standSides[] = $standBase . 'C';
                }
                if (isset($stands[$standBase])) {
                    $standSides[] = $standBase;
                }
            }
            if (Str::endsWith($standID, 'B')) {
                if (isset($stands[$standBase . 'A'])) {
                    $standSides[] = $standBase . 'A';
                }
                if (isset($stands[$standBase . 'C'])) {
                    $standSides[] = $standBase . 'C';
                }
                if (isset($stands[$standBase])) {
                    $standSides[] = $standBase;
                }
            }
        } else {
            // Stand itself has no side, but may have L / R / A / B sides
            if (isset($stands[$standBase . 'L'])) {
                $standSides[] = $standBase . 'L';
            }
            if (isset($stands[$standBase . 'R'])) {
                $standSides[] = $standBase . 'R';
            }
            if (isset($stands[$standBase . 'C'])) {
                $standSides[] = $standBase . 'C';
            }
            if (isset($stands[$standBase . 'A'])) {
                $standSides[] = $standBase . 'A';
            }
            if (isset($stands[$standBase . 'B'])) {
                $standSides[] = $standBase . 'B';
            }
        }

        if (0 == count($standSides)) {
            return false;
        } else {
            return $standSides;
        }
    }
}
