<?php

namespace Database\Seeders;

use App\Models\Navigation\Fir;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NavFirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['EDWW', 'EDGG', 'EDMM', 'EDUU', 'EDYY'];
        foreach ($data as $d) {
            try {
                $f = new Fir();
                $f->slug = $d;
                $f->description = $d;
                $f->name = $d;
                $f->uir = str_ends_with($d, 'UU') || str_ends_with($d, 'YY');
                $f->save();
            } catch (\Exception $e) {
            }
        }
    }
}
