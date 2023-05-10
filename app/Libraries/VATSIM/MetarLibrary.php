<?php

namespace App\Libraries\VATSIM;

use App\Libraries\VATSIM\DataFeedLibrary;
use Illuminate\Support\Str;

class MetarLibrary
{
    /**
     * Decode some metar / atis stuff
     *
     * @param array Array of ICAO codes
     * @return array
     */
    public static function decoded($icaos)
    {
        $results = [];

        $vatsimAtises = DataFeedLibrary::Atises();

        foreach ($icaos as $icao) {
            $metar = DataFeedLibrary::Metar($icao);

            foreach ($vatsimAtises as $va) {
                if (substr($va->callsign, 0, 4) == $icao) {
                    $results[$icao]['atisLetter'] = $va->atis_code;
                    $results[$icao]['atisOwner'] = $va->name;
                    $results[$icao]['atisFrequency'] = $va->frequency;
                    $results[$icao]['combinedAtis'] = self::decodeAtis($va);
                }
            }

            // $qnhPos = -1;
            if (preg_match('/Q\d{4}/', $metar, $qnhPos, PREG_OFFSET_CAPTURE) === 1) {
                $results[$icao]['metar'] = $metar;
                // $results[$icao]['qnh'] = substr($metar, $qnhPos[0], 4);
                // Pressurehpa = 1013.25 * ( PressureinHg / 29.92) = 33.865 * PressureinHg
                // PressureinHg = 29.92 * ( Pressurehpa / 1013.2) = 0.02953 * Pressurehpa
                $qnh = intval(substr($qnhPos[0][0], 1));
                $results[$icao]['qnh'] = $qnhPos[0][0];
                $results[$icao]['alt'] = sprintf('%.2f', round(0.02953 * $qnh, 2, PHP_ROUND_HALF_UP));
                // Calculate TL based on this table
                // == Übergangsflächen in Deutschland ==
                // |- QNH ab !! QNH bis !! TRL
                // |-
                // | 943 || 977 || 80
                // |-
                // | 978 || 1013 || 70
                // |-
                // | 1014 || 1050 || 60
                // |-
                // | 1051 || || 50
                $tl = '80'; // Set highest as default
                if ($qnh <= 977) {
                    $tl = '80';
                } elseif ($qnh <= 1013) {
                    $tl = '70';
                } elseif ($qnh <= 1050) {
                    $tl = '60';
                } else {
                    $tl = '50';
                }
                $results[$icao]['tl'] = $tl;
            } elseif (preg_match('/A\d{4}/', $metar, $inhgPos, PREG_OFFSET_CAPTURE) === 1) {
                $results[$icao]['metar'] = $metar;
                // $results[$icao]['qnh'] = substr($metar, $qnhPos[0], 4);
                // Pressurehpa = 1013.25 * ( PressureinHg / 29.92) = 33.865 * PressureinHg
                // PressureinHg = 29.92 * ( Pressurehpa / 1013.2) = 0.02953 * Pressurehpa
                $inhg = intval(substr($inhgPos[0][0], 1)) / 100;

                $results[$icao]['qnh'] = sprintf('%.0f', round(33.865 * $inhg, 0, PHP_ROUND_HALF_UP));
                $results[$icao]['alt'] = sprintf('%.2f', round($inhg, 2, PHP_ROUND_HALF_UP));
                // Assume Fixed TL outside QNH regions
                $tl = '180';
                $results[$icao]['tl'] = $tl;
            } else {
                $results[$icao]['metar'] = $metar;
                $results[$icao]['qnh'] = '----';
                $results[$icao]['alt'] = '--.--';
                $results[$icao]['tl'] = '--';
            }
        }

        return $results;
    }

    /**
     * Filter a given atis array to find active runways
     *
     * @param array
     *
     * @return string
     */
    public static function decodeAtis($atis)
    {
        $combinedAtis = '';
        foreach ($atis->text_atis as $key => $value) {
            $combinedAtis .= $value . ' ';
        }
        $combinedAtis = trim($combinedAtis);

        $rwyInUseString = '';

        if (preg_match('/RUNWAYS IN USE/', $combinedAtis) !== false) {
            $rwyInUseString = Str::after($combinedAtis, 'RUNWAYS IN USE');
        } elseif (preg_match('/RUNWAY \d{2}(L|R|C)? IN/', $combinedAtis) !== false) {
            $rwyInUseString = Str::after($combinedAtis, 'RUNWAY');
            $rwyInUseString = Str::before($rwyInUseString, 'IN USE');
        } elseif (preg_match('/RWY/', $combinedAtis) !== false) {
            $rwyInUseString = Str::after($$combinedAtis, 'RWY');
        } else {
            $rwyInUseString = $combinedAtis;
        }

        if (preg_match('/TRL/', $rwyInUseString) !== false) {
            $rwyInUseString = Str::before($rwyInUseString, 'TRL');
        }
        if (preg_match('/TRANSITION/', $rwyInUseString) !== false) {
            $rwyInUseString = Str::before($rwyInUseString, 'TRANSITION');
        }

        $rwyInUseString = preg_replace(
            [
                '/ARRIVAL RWY /',
                '/FOR LANDING/',
                '/FOR TAKE OFF/',
                '/DEPARTURE RWY/',
                '/AND /',
                '/\. EXPECT ILS (Y |Z )?APPROACH \d{2}(L|R|C)?/',
                '/\./',
            ],
            '',
            $rwyInUseString,
        );

        return trim($rwyInUseString);
    }
}
