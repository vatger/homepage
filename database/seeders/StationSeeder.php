<?php

namespace Database\Seeders;

use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\Station;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datafile = json_decode(Storage::get('navigation/navigation_stations.json'), false);

        $stations_data = $datafile->data;

        $count = count($stations_data);
        DB::statement('DELETE FROM nav_stations');
        $this->command->getOutput()->writeln('Truncated stations table.');

        $this->command->getOutput()->writeln('Starting seeding of new information...');
        $this->command->getOutput()->progressStart($count);
        foreach ($stations_data as $s) {
            $ns = new Station();
            $ns->name = $s->name;
            $ns->ident = $s->ident;
            $ns->frequency = $s->frequency;
            $ns->description = $s->description;
            $ns->bookable = $s->bookable;
            $ns->atis = $s->atis;
            $ns->save();
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
        $this->command->getOutput()->writeln('Finished seeding.');

        $this->command->getOutput()->writeln('Starting to assign stations to aerodromes...');
        $stations = Station::all();
        $this->command->getOutput()->progressStart($count);
        foreach ($stations as $s) {
            $icao = substr($s->ident, 0, 4);
            $aerodrome = Aerodrome::icao($icao)->first();
            if ($aerodrome != null) {
                $s->aerodromes()->attach($aerodrome);
            }
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
        $this->command->getOutput()->writeln('Finished assigning stations to aerodromes.');
    }
}
