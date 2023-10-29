<?php

namespace Database\Seeders;

use App\Models\Membership\User\User;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'last_seen_at' => Carbon::now(),
                'registered_at' => $row->created_at ?? Carbon::now(),
                'active_member_at' => $row->created_at ?? Carbon::now(),
                'vatger_member_at' => $row->created_at ?? Carbon::now(),
                'active_vatger_member_at' => $row->created_at ?? Carbon::now(),
                'warning_inactive_at' => null,
                'inactive_at' => null,
                'warning_delete_at' => null,
                'delete_at' => null,
            ]);
            // fir membership
            // todo
            $rgs = [
                1 => 'EDWW',
                2 => 'EDBB',
                3 => 'EDLL',
                4 => 'EDFF',
                5 => 'EDMM',
            ];
            
            self::DB_old('regionalgroups_account_regionalgroup')
                ->where('account_id', $row->id)
                ->where('guest', 0)
                ->first();
            sleep(1);
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
    }
}
