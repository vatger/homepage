<?php

namespace Database\Seeders;

use App\Models\Groups\Fir;
use App\Models\Groups\Team;
use Illuminate\Database\Seeder;

class FirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['EDWW', 'EDGG', 'EDMM'];
        foreach ($data as $d) {
            $f = new Fir();
            $f->name = $d;
            $f->slug = $d;
            $f->description = $d;
            $f->mail = $d . '@vatger.de';

            $t = new Team();
            $t->name = $d . ' Leitung';
            $t->save();

            $f->team_id = $t->id;
            $f->save();
        }
    }
}
