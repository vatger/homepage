<?php

namespace Database\Seeders;

use App\Models\Navigation\Aerodrome;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AerodromeSeeder extends Seeder
{
    public function load_ger(): void
    {
        // german airports
        $airports_DE = json_decode(Storage::get('navigation/navigation_aerodromes_DE.json'), false)->data;
        $this->command->getOutput()->writeln('Loaded '.count($airports_DE).' DE aerodromes from file.');
        $this->command->getOutput()->writeln('Starting seeding of new information...');
        $this->command->getOutput()->progressStart(count($airports_DE));
        foreach ($airports_DE as $a) {
            Aerodrome::query()->create([
                'icao' => $a->icao,
                'name' => $a->name,
                'description' => $a->description,
                'iata' => $a->iata,
                'elevation' => (float) $a->elevation,
                'latitude' => (float) $a->latitude,
                'longitude' => (float) $a->longitude,
                'city' => $a->city,
                'country_long' => $a->country,
                'country_short' => $a->country,
                'state' => $a->state,
            ]);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
    }

    public function update_data(): void
    {
        // data update, mainly position and
        $this->command->getOutput()->writeln('Starting updating from the internet...');
        $this->command->getOutput()->progressStart(Aerodrome::query()->count());
        $map = [
            'id' => 0,
            'ident' => 1,
            'type' => 2,
            'name' => 3,
            'latitude_deg' => 4,
            'longitude_deg' => 5,
            'elevation_ft' => 6,
        ];
        $fun = function ($data) use ($map) {
            if (empty($data) || empty($data[1]) || empty($data[3]) || empty($data[4]) || empty($data[5]) || empty($data[6])) {
                return;
            }

            // Check if we have a german airport
            $icao_country = strtolower($data[$map['ident']]);
            if ($icao_country == 'de' || $icao_country == 'et') {
                $this->command->getOutput()->progressAdvance();

                return;
            }

            $found = Aerodrome::query()
                ->where('icao', $data[$map['ident']])
                ->first();
            if (empty($found)) {
                return;
            }
            $found->elevation = (float) $data[$map['elevation_ft']];
            $found->latitude = (float) $data[$map['latitude_deg']];
            $found->longitude = (float) $data[$map['longitude_deg']];
            $found->save();
        };
        $this->readCSV_by_line('https://davidmegginson.github.io/ourairports-data/airports.csv', ',', $fun, true);
        $this->command->getOutput()->progressFinish();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $current_a_count = Aerodrome::all()->count();
        if ($current_a_count > 10) {
            $response = $this->command->getOutput()->ask('Already found '.$current_a_count.' aerodromes. Do you want to skip this seeder? (Y/n)');
            if (str_contains($response, 'y') || str_contains($response, 'Y') || empty($response)) {
                $this->command->getOutput()->info('Skipping...');

                return;
            }
        }
        // Hacky workaround to truncate table with foreign key :)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('nav_aerodromes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->getOutput()->writeln('Truncated aerodromes table.');

        $this->load_ger();
        $this->update_data();

        $this->command->getOutput()->writeln('Finished seeding.');
    }

    private function readCSV_by_line(string $csvFile, string $delimiter, callable $function, bool $skip_first_line = false): void
    {
        Storage::put('navigation/update_airports_data_temp.csv', file_get_contents($csvFile));
        $path = Storage::path('navigation/update_airports_data_temp.csv');
        $file_handle = fopen($path, 'r');
        while (! feof($file_handle)) {
            $dataline = fgetcsv($file_handle, 0, $delimiter);
            if ($skip_first_line) {
                $skip_first_line = false;

                continue;
            }
            $function($dataline);
        }
        fclose($file_handle);
        Storage::delete($path);
    }
}
