<?php

namespace App\Console\Commands\VATSIM;

use App\Libraries\VATSIM\DataFeedLibrary;
use App\Models\Navigation\Aerodrome;
use App\Models\Statistics\ATC;
use App\Models\Statistics\Pilot;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will handle statistics table update.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $timestampNow = Carbon::now()->utc();

        // Run through the currently connected pilots and update our statistics
        // Log::info('[UpdateStatisticsJob]::Handle::Starting statistics updates. Starting with pilots.');
        $this->handlePilots($timestampNow);
        // Log::info('[UpdateStatisticsJob]::Handle::Starting statistics updates. Finished with pilots.');
        // Do the same for atc
        // Log::info('[UpdateStatisticsJob]::Handle::Starting statistics updates. Starting with controllers.');
        $this->handleATC($timestampNow);
        // Log::info('[UpdateStatisticsJob]::Handle::Starting statistics updates. Finished with controllers.');

        Cache::forever('org.vatsim-germany.statistics.lastUpdatedAt', $timestampNow);

        return 0;
    }

    /**
     * Handle pilot statistics updates
     *
     * This will check for flights completed / abandoned or other stuff
     * Data will be updated in database directly when changes apply.
     *
     * @return void
     */
    private function handlePilots($timestampNow)
    {
        $onlinePilots = collect(DataFeedLibrary::Pilots());

        $stats = Pilot::whereNull('disconnected_at')->get();

        foreach ($stats as $s) {
            if (
                !$onlinePilots->contains(function ($value, $key) use ($s) {
                    return $value->callsign == $s->callsign && $s->account_id == $value->cid;
                })
            ) {
                // Not online anymore
                if ($s->departed_at != null) {
                    $s->disconnected_at = $timestampNow;
                    $s->arrived_at = $timestampNow;
                    $s->save();
                } else {
                    $s->delete();
                }
            } else {
                // Still online
                // Let's check for departed, arrived or diverted
                $op = $onlinePilots->firstWhere('cid', $s->account_id);
                if ($op == null) {
                    continue;
                }

                $needsFreshAfterRevision = false;

                if ($s->departure_id == null || $s->destination_id == null || $s->altername_id == null) {
                    if ($op->flight_plan != null) {
                        $depPort = Aerodrome::icao($op->flight_plan->departure)->first();
                        $desPort = Aerodrome::icao($op->flight_plan->arrival)->first();
                        $altPort = Aerodrome::icao($op->flight_plan->alternate)->first();
                        $s->departure_id = $depPort != null ? $depPort->id : null;
                        $s->destination_id = $desPort != null ? $desPort->id : null;
                        $s->alternate_id = $altPort != null ? $altPort->id : null;
                        $s->route = $op->flight_plan->route;
                        if ($op->flight_plan->revision_id != $s->revision_id) {
                            $s->revision_id = $op->flight_plan->revision_id;
                            $needsFreshAfterRevision = true;
                        }
                        $s->save();
                    }
                }

                /**
                 * Flight plan has changed. So we must retrieve the updated flight information
                 */
                if ($needsFreshAfterRevision) {
                    $s->fresh();
                }

                if ($s->departed_at == null) {
                    $depPort = Aerodrome::find($s->departure_id);
                    if ($depPort != null) {
                        if (!$depPort->containsCoordinates($op->latitude, $op->longitude)) {
                            if ($op->altitude > $depPort->elevation + 150 && $op->groundspeed > 35) {
                                $s->departed_at = Carbon::parse($op->last_updated)->utc();
                            }
                        }
                    }
                }

                if ($s->arrived_at == null) {
                    $desPort = Aerodrome::find($s->destination_id);
                    if ($desPort != null) {
                        if ($desPort->containsCoordinates($op->latitude, $op->longitude)) {
                            if ($op->altitude < $desPort->elevation + 150 && $op->groundspeed < 35) {
                                $s->arrived_at = Carbon::parse($op->last_updated)->utc();
                            }
                        }
                    }
                }

                if ($s->arrived_alternate_at == null) {
                    $altPort = Aerodrome::find($s->alternate_id);
                    if ($altPort != null) {
                        if ($altPort->containsCoordinates($op->latitude, $op->longitude)) {
                            if ($op->altitude < $altPort->elevation + 150 && $op->groundspeed < 35) {
                                $s->arrived_alternate_at = Carbon::parse($op->last_updated)->utc();
                            }
                        }
                    }
                }

                $s->save();
            }
        }

        $stats->fresh(); // Refresh the stats due to previous changes
        foreach ($onlinePilots as $op) {
            if (
                !$stats->contains(function ($value, $key) use ($op) {
                    return $value->account_id == $op->cid && $value->callsign == $op->callsign;
                })
            ) {
                // {"cid":1555161,"name":"BLABLA","callsign":"EZY2132","server":"UK-1","pilot_rating":0,
                // "latitude":46.17662,"longitude":-1.19384,"altitude":78,"groundspeed":0,"transponder":"7020","heading":135,"qnh_i_hg":30.0,"qnh_mb":1016,
                // "flight_plan":{"flight_rules":"I","aircraft":"A320","aircraft_faa":"A320","aircraft_short":"A320","departure":"EGKK","arrival":"LFBH","alternate":"LFBD",
                // "cruise_tas":"450","altitude":"31000","deptime":"2000","enroute_time":"0102","fuel_time":"0132","remarks":"NEW PILOT A320 - UK VIRTUAL /V/",
                // "route":"IMVUR N63 SAM M195 MARUK N621 LELNA UN621 DOMOK UT260 UPALO UP87 REN R14 NTS A25 LUSON LUSON5L LFBH/27","revision_id":3},
                // "logon_time":"2021-06-02T19:54:48.9614619Z","last_updated":"2021-06-03T14:14:31.5179605Z"}
                if ($op->flight_plan != null) {
                    $depPort = Aerodrome::icao($op->flight_plan->departure)->first();
                    $desPort = Aerodrome::icao($op->flight_plan->arrival)->first();
                    $altPort = Aerodrome::icao($op->flight_plan->alternate)->first();
                    $s = Pilot::create([
                        'account_id' => $op->cid,
                        'callsign' => $op->callsign,
                        'departure_id' => $depPort != null ? $depPort->id : null,
                        'destination_id' => $desPort != null ? $desPort->id : null,
                        'alternate_id' => $altPort != null ? $altPort->id : null,
                        'aircraft_short' => $op->flight_plan->aircraft_short,
                        'route' => $op->flight_plan->route,
                        'revision_id' => $op->flight_plan->revision_id,
                        'connected_at' => Carbon::parse($op->logon_time)->utc(),
                    ]);
                } else {
                    $s = Pilot::create([
                        'account_id' => $op->cid,
                        'callsign' => $op->callsign,
                        'route' => '',
                        'connected_at' => Carbon::parse($op->logon_time)->utc(),
                    ]);
                }

                $s->save();
            }
        }
    }

    /**
     * Handle atc statistic updates
     *
     * @return void
     */
    private function handleATC($timestampNow)
    {
        // {"cid":1481566,"name":"Tjark","callsign":"EDDH_TWR","frequency":"126.850","facility":4,
        //  "rating":3,"server":"GERMANY-2","visual_range":50,"text_atis":["Hamburg Tower - Moin","Main Tower","ATIS on 124.320 - PDC EDDH"],
        //  "last_updated":"2021-06-01T06:28:29.0937925Z","logon_time":"2021-06-01T05:31:08.0820857Z"}
        $onlineAtcs = collect(DataFeedLibrary::Controllers());
        // $onlineAtcs = collect();
        $stats = ATC::whereNull('disconnected_at')->get();

        foreach ($stats as $s) {
            if (
                !$onlineAtcs->contains(function ($value, $key) use ($s) {
                    // $value contains the atc ONLINE object
                    return $s->account_id == $value->cid && $value->callsign == $s->station_ident;
                })
            ) {
                $s->disconnected_at = $timestampNow;
                $s->save();
            }
        }
        $stats->fresh();
        foreach ($onlineAtcs as $oa) {
            if (
                !$stats->contains(function ($value, $key) use ($oa) {
                    return $value->account_id == $oa->cid && $value->station_ident == $oa->callsign;
                })
            ) {
                if (\Illuminate\Support\Str::endsWith($oa->callsign, ['FSS', 'CTR', 'APP', 'DEP', 'TWR', 'GND', 'DEL'])) {
                    $s = ATC::create([
                        'account_id' => $oa->cid,
                        'station_ident' => $oa->callsign,
                        'connected_at' => Carbon::parse($oa->logon_time)->utc(),
                        'rating' => $oa->rating,
                    ]);
                    $s->save();
                }
            }
        }
    }
}
