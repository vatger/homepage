<?php

namespace Database\Seeders;

use App\Libraries\NavLibrary;
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
    public function run(): void
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0');
        //DB::table('nav_aerodrome_stations')->truncate();
        //DB::table('nav_stations')->truncate();
        //DB::statement('SET FOREIGN_KEY_CHECKS=1');
        //$this->command->getOutput()->writeln('Truncated stations table.');

        $this->command->getOutput()->writeln('Starting seeding...');
        NavLibrary::sync_stations();
        $this->command->getOutput()->writeln('Finished seeding.');
    }
}
