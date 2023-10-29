<?php

namespace Database\Seeders;

use App\Models\Membership\User\User;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\Types\Collection;

class MigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->copy_users();
    }

    private static function DB_old(string $table = null): Connection|Builder
    {
        if ($table) {
            return DB::connection('mysql_old')->table($table);
        }
        return DB::connection('mysql_old');
    }

    private function copy_users(): void
    {
        $rows = self::DB_old('membership_accounts')->get();
        $this->command->getOutput()->info('copy_users');
        $this->command->getOutput()->progressStart($rows->count());

        foreach ($rows as $row) {
            $row_settings = self::DB_old('membership_account_settings')
                ->where('account_id', $row->id)
                ->first();
            $row_data = self::DB_old('membership_account_data')
                ->where('account_id', $row->id)
                ->first();

            // user table
            $user = User::updateOrCreate([
                'id' => $row->id,
                'firstname' => $row->firstname,
                'lastname' => $row->lastname,
                'email' => $row->email,
            ]);
            // user passwords
            $user->passwords()->update([
                'oauth_access_token' => null,
                'oauth_refresh_token' => null,
                'oauth_token_expires' => null,
            ]);
            // user settings
            $user->settings()->update([
                'language' => $row_settings->language,
            ]);
            // vatsim data
            $user->vatsimDetails()->update([
                'rating_atc' => 0,
                'rating_pilot' => 0,
                'country_code' => '-',
                'country_name' => '-',
                'region_code' => '-',
                'region_name' => '-',
                'division_code' => '-',
                'division_name' => '-',
                'subdivision_code' => '-',
                'subdivision_name' => '-',
            ]);

            // vatger data
            $user->vatgerDetails()->update([
                'registered_at' => $row->created_at ?? Carbon::now(),
            ]);

            sleep(1);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
    }
}
