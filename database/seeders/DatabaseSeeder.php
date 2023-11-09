<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        $this->call(MembershipFirSeeder::class);

        //$this->call(DemoSeeder::class);

        $this->call(NavFirSeeder::class);

        $this->call(AerodromeSeeder::class);

        $this->call(StationSeeder::class);
    }
}
