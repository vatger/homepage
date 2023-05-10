<?php

namespace Database\Seeders;

use App\Models\Membership\User\User;
use App\Models\Membership\User\UserData;
use App\Models\Membership\User\UserSetting;
use Illuminate\Database\Seeder;

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
            for ($i = 20000000; $i < 20000000 + 9999; $i++) {
                User::query()->updateOrCreate([
                    'id' => $i,
                    'firstname' => 'Test',
                    'lastname' => "$i",
                    'email' => "$i@mail.com",
                ]);

                if (
                    UserData::query()
                        ->where('account_id', $i)
                        ->exists()
                ) {
                    continue;
                }

                UserData::query()->updateOrCreate([
                    'account_id' => $i,
                    'rating_atc' => 3,
                    'rating_pilot' => 1,
                    'region_code' => 'EMEA',
                    'region_name' => 'Europe, Middle East and Africa',
                    'division_code' => 'EUD',
                    'division_name' => 'Europe (except UK)',
                    'subdivision_code' => null,
                    'subdivision_name' => null,
                ]);

                if (
                    UserSetting::query()
                        ->where('account_id', $i)
                        ->exists()
                ) {
                    continue;
                }

                UserSetting::query()->updateOrCreate([
                    'account_id' => $i,
                    'language' => 'en',
                ]);
            }
        }
    }
}
