<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AerodromeSeeder::class);

        $this->call(StationSeeder::class);

        //$this->call(PermissionSeeder::class);

        //$this->call(RegionalgroupSeeder::class);

        //$this->call(DemoSeeder::class);
    }
}
