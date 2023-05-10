<?php

namespace Database\Seeders;

use App\Models\Regionalgroup\FlightInformationRegion;
use App\Models\Regionalgroup\Regionalgroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegionalgroupSeeder extends Seeder
{
    private $firs = ['FIR Bremen', 'FIR Langen', 'FIR Munich'];

    private $regionalgroups = [
        ['name' => 'RG Bremen', 'fir_id' => 1],
        ['name' => 'RG Berlin', 'fir_id' => 1],
        ['name' => 'RG Düsseldorf', 'fir_id' => 2],
        ['name' => 'RG Frankfurt', 'fir_id' => 2],
        ['name' => 'RG München', 'fir_id' => 3],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear database tables first
        DB::statement('DELETE FROM regionalgroups_firs');
        DB::statement('DELETE FROM regionalgroups_regionalgroups');

        foreach ($this->firs as $fir) {
            $f = new FlightInformationRegion();
            $f->name = $fir;
            $f->save();
        }

        foreach ($this->regionalgroups as $rg) {
            $regionalgroup = new Regionalgroup();
            $regionalgroup->name = $rg['name'];
            $regionalgroup->fir_id = $rg['fir_id'];
            $regionalgroup->save();
        }
    }
}
