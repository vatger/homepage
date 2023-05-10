<?php

namespace App\Libraries\EuroScope;

use App\Libraries\VATSIM\DataFeedLibrary;
use App\Models\Navigation\Aerodrome;
use Illuminate\Support\Str;

class ScenarioLibrary
{
    /**
     * Name of the scenario
     * @var string
     */
    private $_name = 'EuroScopeScenario';

    /**
     * Range around aerodrome(s)
     * @var int
     */
    private $_range = 150;

    /**
     * List of aerodrome icao codes
     * @var array
     */
    private $_icaos = [];

    /**
     * Total number of departures
     * @var int
     */
    private $_departures = 50;

    /**
     * Total number of arrivals
     * @var int
     */
    private $_arrivals = 50;

    /**
     * Departure traffic altitude limitation
     * @var int
     */
    private $_departureAltLimit = 10000;

    /**
     * Squawk range limits
     * @var array
     */
    private $_squawkRange = ['min' => 0001, 'max' => 7777];

    /**
     * Station ident of the initial scenario controller
     * @var string
     */
    private $_initialPseudoPilot = '';

    /**
     * Keep track of departure routings
     * @var array
     */
    private $_departureRoutes = [];

    /**
     * Arrival routes
     * @var array
     */
    private $_arrivalRoutes = [];

    /**
     * ATC stations for aerodromes
     * @var Collection
     */
    private $_stations;

    /**
     * The scenario output
     * @var string
     */
    private $_scenario = '';

    /**
     * Method __construct
     *
     * @param $data The data that describes the scenario
     *
     * @return void
     */
    function __construct($data)
    {
        $this->_name = $data['name'];

        if (Str::contains($data['icao'], ',')) {
            $this->_icaos = explode(',', $data['icao']);
        } else {
            $this->_icaos[] = $data['icao'];
        }

        $this->_range = $data['range'];

        $this->_departures = round($data['maxFlights'] * ($data['depArrScale'] / 100));
        $this->_arrivals = round($data['maxFlights'] * (1 - $data['depArrScale'] / 100));
        $this->_departureAltLimit = $data['depAltLimit'];
        $this->_squawkRange['min'] = $data['minSquawk'];
        $this->_squawkRange['max'] = $data['maxSquawk'];
        $this->_initialPseudoPilot = $data['initialPseudo'];

        $aerodromes = Aerodrome::whereIn('icao', $this->_icaos)->get();
        $this->_stations = collect();
        foreach ($aerodromes as $aerodrome) {
            $aerodrome->loadMissing('stations');
            $this->_stations = $this->_stations->merge($aerodrome->stations);
        }

        foreach ($this->_icaos as $icao) {
            $this->_findFlightplans($icao, $aerodromes);
        }

        foreach ($aerodromes as $aerodrome) {
            $aerodrome->loadMissing('runways');
            foreach ($aerodrome->runways as $rwy) {
                $coords = preg_replace('/\sE/', ':E', $rwy->threshold);
                $coords = preg_replace('/E\s/', 'E', $coords);
                $coords = preg_replace('/N\s(\d{2})/', 'N0\1', $coords);
                $coords = preg_replace('/\s/', '.', $coords);
                $this->_scenario .= 'ILS' . $rwy->ident . ':' . $coords . ':' . $rwy->heading . "\n";
            }
        }
    }

    /**
     * Get the name of the scenario
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Method _buildScenario
     * This function will build the scenario
     *
     * @return string
     */
    public function _buildScenario()
    {
        $this->_scenario .= "\n";
        foreach ($this->_stations as $station) {
            $this->_scenario .= 'CONTROLLER:' . $station->ident . ':' . $station->fixedFrequency . "\n";
        }

        $this->_scenario .= "\n";

        foreach ($this->_departureRoutes as $dr) {
            $this->_renderFlight($dr);
        }
        foreach ($this->_arrivalRoutes as $ar) {
            $this->_renderFlight($ar);
        }

        return $this->_scenario;
    }

    /**
     * Method _findFlightplans
     * Find and store flightplans from the datafeed
     * that match a given icao code
     *
     * @param $icao The aerodromes icao code
     * @param $aerodromes A collection of Aerodromes
     *
     * @return void
     */
    protected function _findFlightplans($icao, $aerodromes)
    {
        foreach (DataFeedLibrary::Pilots() as $pilot) {
            // {"cid":VID,"name":"abcdef","callsign":"SAS2185","server":"UK","pilot_rating":0,"latitude":55.62582,"longitude":12.64449,"altitude":25,
            // "groundspeed":0,"transponder":"2010","heading":47,"qnh_i_hg":29.91,"qnh_mb":1013,
            // "flight_plan":{"flight_rules":"I","aircraft":"B738","aircraft_faa":"B738","aircraft_short":"B738","departure":"EDDF","arrival":"EKCH",
            // "alternate":"ESGG","cruise_tas":"450","altitude":"32000","deptime":"1250","enroute_time":"0120","fuel_time":"0300","remarks":" /V/",
            // "route":"TOBAK7M/25C  TOBAK DCT AMETU UN850 WRB N850 PIROT DCT SAS DCT BKD DCT NOBRI M726 ROSOK Q296 MONAK","revision_id":3,
            // "assigned_transponder":"2010"},"logon_time":"2021-10-25T12:46:17.5792952Z","last_updated":"2021-10-26T07:28:30.4240774Z"}
            if (!isset($pilot->flight_plan) || $pilot->flight_plan == null) {
                continue;
            }
            $inRange = false;
            foreach ($aerodromes as $a) {
                $inRange = $this->_isInRange($pilot, $a);
            }
            if (!$inRange) {
                continue;
            }

            if (
                $pilot->flight_plan->departure == $icao &&
                $pilot->flight_plan->departure != $pilot->flight_plan->arrival &&
                $pilot->altitude <= $this->_departureAltLimit
            ) {
                $this->_departureRoutes[] = $pilot;
            }
            if ($pilot->flight_plan->arrival == $icao) {
                $distFromArrival = 0;
                foreach ($aerodromes as $a) {
                    if ($a->icao == $pilot->flight_plan->arrival) {
                        $distFromArrival = $this->_calculateDistance($a->latitude, $pilot->latitude, $a->longitude, $pilot->longitude);
                        break;
                    }
                }
                // Skip inbound traffic that is more than 90nm away and below FP-Alt-10%
                if ($distFromArrival > 90 && $pilot->altitude < intval($pilot->flight_plan->altitude) - 0.1 * intval($pilot->flight_plan->altitude)) {
                    continue;
                }

                $this->_arrivalRoutes[] = $pilot;
            }
        }
        if (sizeof($this->_departureRoutes) > $this->_departures) {
            $this->_departureRoutes = array_slice($this->_departureRoutes, 0, $this->_departures);
        }
        if (sizeof($this->_arrivalRoutes) > $this->_arrivals) {
            $this->_arrivalRoutes = array_slice($this->_arrivalRoutes, 0, $this->_arrivals);
        }
    }

    /**
     * Calculate the distance between two points
     * Returns the distance in nautical miles
     * @param float $lat1
     * @param float $lat2
     * @param float $lon1
     * @param float $lon2
     * @return float
     */
    protected function _calculateDistance($lat1, $lat2, $lon1, $lon2)
    {
        return 2 *
            3961 *
            asin(
                sqrt(
                    pow(sin(deg2rad(($lat2 - $lat1) / 2)), 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * pow(sin(deg2rad(($lon2 - $lon1) / 2)), 2),
                ),
            );
    }

    /**
     * Test if a given pair of coordinates is within range of eachother
     * @param $flight The flightplan
     * @param $aerodrome The aerodrome
     * @return bool
     */
    protected function _isInRange($flight, $aerodrome)
    {
        $dist = $this->_calculateDistance($aerodrome->latitude, $flight->latitude, $aerodrome->longitude, $flight->longitude);
        return $dist <= $this->_range;
    }

    /**
     * Build the flightplan format for the scenario file
     *
     * @param  [type] $flight [description]
     * @return [type]         [description]
     */
    protected function _renderFlight($flight)
    {
        // @<transponder flag>:<callsign>:<squawk code>:1:<latitude>:<longitude>:<altitude>:0:<heading>:0
        // $FP<callsign>:*A:<flight plan type>:<aircraft type>:<true air speed>:<origin airport>:<departure time EST>:<departure time ACT>:<final cruising altitude>:<destination airport>:<HRS en route>:<MINS en route>:<HRS fuel>:<MINS fuel>:<alternate airport>:<remarks>:<route>
        $this->_scenario .=
            "@N:$flight->callsign:" .
            $this->_renderSquawk() .
            ":1:$flight->latitude:$flight->longitude:$flight->altitude:0:" .
            $this->_calculateInitialHeading($flight->heading) .
            ":0\n";
        $this->_scenario .=
            '$FP' .
            "$flight->callsign:*A:" .
            $flight->flight_plan->flight_rules .
            ':' .
            $flight->flight_plan->aircraft .
            '::' .
            $flight->flight_plan->departure .
            ':' .
            $flight->flight_plan->deptime .
            ':' .
            $flight->flight_plan->deptime .
            ':' .
            $flight->flight_plan->altitude .
            ':' .
            $flight->flight_plan->arrival .
            ':::::' .
            $flight->flight_plan->alternate .
            ':' .
            $flight->flight_plan->remarks .
            ':' .
            $flight->flight_plan->route .
            "\n";

        $this->_scenario .= '$ROUTE:FPA' . "\n";
        $this->_scenario .= "START:0\n";
        $this->_scenario .= "INITIALPSEUDOPILOT:$this->_initialPseudoPilot\n\n";
    }

    /**
     * Calculate the initial heading to match EuroScope's screenspace
     *
     * @param  int $hdg [description]
     * @return int      [description]
     */
    protected function _calculateInitialHeading($hdg)
    {
        return (int) ($hdg * 2.88 + 0.5) << 2;
    }

    /**
     * Find a squawk
     *
     * @return [type] [description]
     */
    protected function _renderSquawk()
    {
        $squawk = rand($this->_squawkRange['min'], $this->_squawkRange['max']);
        while (!$this->_isSquawkValid($squawk)) {
            $squawk = rand($this->_squawkRange['min'], $this->_squawkRange['max']);
        }
        return $squawk;
    }

    /**
     * Is a given 4 digit squawk code within valid range?
     * So that any digit is less or equal to 7.
     *
     * @param  string  $sq [description]
     * @return boolean     [description]
     */
    protected function _isSquawkValid($sq)
    {
        return preg_match('/^[0-7]{4}/', $sq) && $sq < 7778;
    }
}
