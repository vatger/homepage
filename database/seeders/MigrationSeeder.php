<?php

namespace Database\Seeders;

use App\Libraries\MembershipLibrary;
use App\Libraries\VATSIM\APILibrary;
use App\Models\Groups\Fir;
use App\Models\Membership\User\Concerns\FirMembership;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
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
            $user = User::where('id', $row->id)->first();
            if (!$user) {
                $user = User::create([
                    'id' => $row->id,
                    'firstname' => $row->firstname,
                    'lastname' => $row->lastname,
                    'email' => $row->email,
                ]);
            }
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

            $row_rg = self::DB_old('regionalgroups_account_regionalgroup')
                ->where('account_id', $row->id)
                ->where('guest', 0)
                ->first();

            $new_fir = null;
            $old_fir = 'none';

            if ($row_rg) {
                FirMembership::where('user_id', $row->id)->delete();
                $f = new FirMembership();
                $f->user_id = $row->id;
                $old_fir = $rgs[$row_rg->regionalgroup_id];
                switch ($row_rg->regionalgroup_id) {
                    case 1:
                    case 2:
                        $new_fir = Fir::where('slug', 'LIKE', 'EDWW')->first();
                        break;
                    case 3:
                    case 4:
                        $new_fir = Fir::where('slug', 'LIKE', 'EDGG')->first();
                        break;
                    case 5:
                        $new_fir = Fir::where('slug', 'LIKE', 'EDMM')->first();
                        break;
                }
                $f->fir_id = $new_fir->id;
                $f->joined_at = $row_rg->created_at;
                $f->active_fir_member_at = $row_rg->created_at;
                $f->save();
            }

            $this->command
                ->getOutput()
                ->comment($row->id . ', ' . $row->firstname . ', ' . $row->lastname . ', ' . $old_fir . '->' . ($new_fir ? $new_fir->name : 'none'));

            // fetch some data from the API
            $user = User::where('id', $row->id)->first();
            if (!APILibrary::MemberUpdate($user, false)) {
                $this->command->error('VATSIM API Fail: User ' . $user->id);
            }
            MembershipLibrary::update($user, cache: false);
            
            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
    }
}
