<?php

namespace App\Http\Controllers\Pilot\Livemap;

use App\Http\Controllers\Controller;
use App\Libraries\EuroScope\SectorDataLibrary;
use App\Libraries\VATSIM\DataFeedLibrary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LivemapController extends Controller
{
    protected $aliases = [
        'ADR_W_CTR' => ['LJLA', 'LDZO', 'LQSB', 'LAAA', 'LWSS', 'LYBA'],
        'FTW_51_CTR' => ['KZFW'],
        'ATL_43_CTR' => ['KZTL'],
        'MTL_CTR' => ['CZUL'],
        'BOS_CTR' => ['KZBW'],
        'TOR_PI_CTR' => ['CZYZ'],
        'CLE_CTR' => ['KZOB'],
        'CHI_35_CTR' => ['KZAU'],
        'MIA_46_CTR' => ['KZMA'],
        'JAX_35_CTR' => ['KZJX'],
        'IND_CTR' => ['KZID'],
        'NY_CTR' => ['KZNY'],
        'ASIA_W_FSS' => ['OAKX', 'OPLR', 'VIDF', 'VNSM', 'VEGF', 'OPKR', 'VABF', 'VRMF', 'VCCF', 'VOMF', 'VECD', 'VGFR'],
        'ML-SNO_CTR' => ['YSNO', 'YWON', 'YYWE', 'YEKW', 'YHUM'],
        'GUM_CTR' => ['PGZU'],
        'LON_CTR' => ['EGTT-N', 'EGTT-C', 'EGTT-S', 'EGTT-W'],
        'LON_SC_CTR' => ['EGTT-S', 'EGTT-C'],
        'SCO_CTR' => ['EGPX-E', 'EGPX-W'],
    ];

    protected $boundaryFile = 'navigation/sectors/fir_boundaries.json';

    protected $uirFile = 'navigation/sectors/UIR.dat';

    protected $boundaries = null;

    protected $uirs = [];

    protected $vaccPositions = null;

    public function __construct()
    {
        parent::__construct();

        $this->boundaries = json_decode(Storage::get($this->boundaryFile));

        $this->vaccPositions = $this->boundaries->vatger->positions;

        // Load UIR Data
        $uirFileContent = Storage::get($this->uirFile);
        foreach (explode("\n", $uirFileContent) as $line) {
            if ($line == '') {
                continue;
            }
            // ADR_E|Adria Radar|LYBA,LWSS,LAAA
            $split = explode('|', $line);
            $this->uirs[$split[0]] = $split[2];
        }
    }

    /**
     * Method index
     *
     * Display the livemap view
     *
     * @return void
     */
    public function index()
    {
        return $this->prepareView('homepage.pilots.livemap.livemap');
    }

    /**
     * Method getConnectedAtc
     *
     * Get all online atc stations
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function getConnectedAtc(Request $request)
    {
        if (!$request->ajax()) {
            abort(401);
        }

        return json_encode(DataFeedLibrary::Controllers());
    }

    /**
     * Method getConnectedPilots
     *
     * Get all connected pilot clients
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function getConnectedPilots(Request $request)
    {
        if (!$request->ajax()) {
            abort(401);
        }

        return json_encode(DataFeedLibrary::Pilots());
    }

    /**
     * Method getControllerDetails
     *
     * Get detailed information for a given atc station
     *
     * @param Request $request [explicite description]
     * @param string $callsign [explicite description]
     *
     * @return void
     */
    public function getControllerDetails(Request $request, string $callsign)
    {
        if (!$request->ajax()) {
            abort(401);
        }

        foreach (DataFeedLibrary::Controllers() as $controller) {
            if ($controller->callsign == $callsign) {
                return json_encode($controller);
            }
        }
        return null;
    }

    /**
     * Get a single sector boundary matching the callsing.
     *
     * @param [type] $callsign [description]
     *
     * @return [type] [description]
     */
    public function getSector($callsign = null)
    {
        if (null == $callsign) {
            return json_encode([]);
        }

        if (!$this->_isEseSector($callsign)) {
            return $this->_getSimpleSector($callsign);
        } else {
            return $this->_getEseSector($callsign);
        }
    }

    /**
     * Load sector information from simple data.
     *
     * This is for any non vatger sector
     *
     * @param [type] $callsign [description]
     *
     * @return [type] [description]
     */
    private function _getSimpleSector($callsign)
    {
        if (array_key_exists($callsign, $this->aliases)) {
            $response['multiple'] = true;
            $boundryLines = [];
            foreach ($this->boundaries->general as $key => $fir) {
                foreach ($this->aliases[$callsign] as $subsector) {
                    if ($fir->icao == $subsector) {
                        $boundryLines[] = $fir->points;
                    }
                }
            }
            $response['points'] = $boundryLines;

            return json_encode($response);
        } elseif (array_key_exists(explode('_', $callsign)[0], $this->uirs)) {
            $response['multiple'] = true;
            $sectors = [];

            $firs = explode(',', $this->uirs[explode('_', $callsign)[0]]);
            foreach ($firs as $fir) {
                foreach ($this->boundaries->general as $k => $f) {
                    if ($f->icao == $fir) {
                        $sectors[] = $f->points;
                    }
                }
            }

            $response['points'] = $sectors;

            return json_encode($response);
        } else {
            $possibleCandidates = [];
            $possibleCandidates[] = $callsign;
            $possibleCandidates[] = explode('_', $callsign)[0] . '-' . explode('_', $callsign)[1];
            $possibleCandidates[] = explode('_', $callsign)[0];
            foreach ($this->boundaries->general as $k => $fir) {
                foreach ($possibleCandidates as $pc) {
                    if ($fir->icao == $pc) {
                        $response['multiple'] = false;
                        $response['points'] = $fir->points;

                        return $response;
                    }
                }
            }
        }

        return json_encode(['multiple' => false, 'points' => []]);
    }

    /**
     * Get sector data from the ese section
     * then make some sense of it and generate sector boundary.
     *
     * @param [type] $callsign [description]
     *
     * @return [type] [description]
     */
    private function _getEseSector($callsign)
    {
        foreach ($this->vaccPositions as $position) {
            if ($position->name == $callsign) {
                // We have a matching position. Now find all sectors that are somehow interesting
                $sectors = [];

                foreach ($this->boundaries->vatger->airspace->sectors as $sector) {
                    if (!property_exists($sector, 'owner')) {
                        continue;
                    }
                    if (Str::endsWith($callsign, ['_CTR', '_FSS'])) {
                        if (intval($sector->lowerLimit) < 24500) {
                            continue;
                        }
                    } elseif (Str::endsWith($callsign, ['_APP', '_DEP'])) {
                        if (intval($sector->upperLimit > 24500)) {
                            continue;
                        }
                    } else {
                        if (intval($sector->upperLimit > 5500)) {
                            continue;
                        }
                    }
                    // If the airspace owners contains the position... add it
                    $owners = explode(':', Str::after($sector->owner, ':'));
                    if (in_array($position->ident, $owners)) {
                        $sectors[] = $this->_getEseSectorByName($sector->name);
                    }
                }

                $response['multiple'] = true;
                $response['points'] = $sectors;
                // dd($response);
                return json_encode($response);
            }
        }
    }

    private function _getEseSectorByName($sectorName)
    {
        $sectors = [];
        foreach ($this->boundaries->vatger->airspace->sectors as $sector) {
            if ($sector->name == $sectorName) {
                $borders = explode(':', Str::after($sector->border, ':'));
                foreach ($borders as $border) {
                    foreach ($this->boundaries->vatger->airspace->lines as $line) {
                        if ($line->name == $border) {
                            $coords = [];
                            foreach ($line->coords as $coord) {
                                $coordSplit = explode(':', Str::after($coord, ':'));
                                $coords[] = [
                                    SectorDataLibrary::convertDMSToDecimal($coordSplit[0]),
                                    SectorDataLibrary::convertDMSToDecimal($coordSplit[1]),
                                ];
                            }
                            $sectors = array_merge($sectors, $coords);
                        }
                    }
                }
            }
        }
        return $sectors;
    }

    /**
     * Is the callsign within the vacc definitions.
     *
     * @param [type] $callsign [description]
     *
     * @return bool [description]
     */
    private function _isEseSector($callsign)
    {
        foreach ($this->vaccPositions as $id => $pos) {
            if ($pos->name == $callsign) {
                return true;
            }
        }

        return false;
    }
}
