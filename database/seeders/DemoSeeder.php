<?php

namespace Database\Seeders;

use App\Libraries\MembershipLibrary;
use App\Models\Membership\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Session;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') !== 'production') {
            for ($i = 20000000; $i < 20000000 + 20; $i++) {
                $user = User::updateOrCreate([
                    'id' => $i,
                    'firstname' => 'Test',
                    'lastname' => "$i",
                    'email' => "$i@mail.com",
                ]);

                $user->passwords()->updateOrCreate([]);
                $user->settings()->updateOrCreate([]);
                $user->settings()->update([
                    'language' => Session::has('language') ? Session::get('language') : 'de',
                ]);

                $user->vatgerDetails()->updateOrCreate([]);

                $user->vatsimDetails()->updateOrCreate([]);
                $user->vatsimDetails()->update([
                    'rating_atc' => 3,
                    'rating_pilot' => 0b11,
                    'country_code' => 'DE',
                    'country_name' => 'Germany',
                    'region_code' => 'EMEA',
                    'region_name' => 'Europe ...',
                    'division_code' => 'EUD',
                    'division_name' => 'VATEUD',
                    'subdivision_code' => 'GER',
                    'subdivision_name' => 'Germany',
                ]);

                MembershipLibrary::seen($user);

                $user->tokens()->delete();
                $user->createToken('api-token');
            }
        }
    }
}
