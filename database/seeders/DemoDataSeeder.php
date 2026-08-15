<?php

namespace Database\Seeders;

use App\Models\AtcBooking;
use App\Models\Groups\Fir;
use App\Models\Groups\Team;
use App\Models\Membership\User;
use App\Models\Navigation\Aerodrome;
use App\Models\Navigation\AerodromeLink;
use App\Models\Navigation\Station;
use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Populate a local installation with safe, synthetic homepage data.
 *
 * This seeder is intentionally opt-in and must not be added to the default
 * production seed path.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->warn('Demo data was not created because APP_ENV=production.');

            return;
        }

        Partner::factory()->count(6)->create();

        $users = User::factory()->count(12)->vatgerMember()->create();

        // Navigation data is imported from VATGER-Nav by the regular seeders.
        // Do not manufacture airport or station records here: those records
        // contain operational identifiers, frequencies, and relationships.
        $stations = Station::query()->bookable()->inRandomOrder()->limit(8)->get();
        $aerodromes = Aerodrome::query()
            ->isDe()
            ->where('active', true)
            ->with('stations')
            ->inRandomOrder()
            ->limit(6)
            ->get();

        if ($stations->isEmpty() || $aerodromes->isEmpty()) {
            throw new \RuntimeException('No imported navigation data found. Run the regular database seeders before DemoDataSeeder.');
        }

        $firs = Fir::query()->whereIn('slug', ['EDWW', 'EDGG', 'EDMM'])->get()->keyBy('slug');
        if ($firs->count() !== 3) {
            throw new \RuntimeException('The EDWW, EDGG, and EDMM membership FIRs are missing. Run the regular database seeders first.');
        }

        foreach ($users as $user) {
            $fir = $firs->get(['EDWW', 'EDGG', 'EDMM'][$user->id % 3]);

            if (DB::table('user_firs')->where(['user_id' => $user->id, 'fir_id' => $fir->id])->exists()) {
                continue;
            }

            DB::table('user_firs')->insert([
                'user_id' => $user->id,
                'fir_id' => $fir->id,
                'joined_at' => now()->subYear(),
                'active_fir_member_at' => now()->subMonths(6),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $teams = Team::query()->whereIn('name', ['EDWW Leitung', 'EDGG Leitung', 'EDMM Leitung'])->get();
        foreach ($teams as $team) {
            $users->random(2)->each(function (User $user) use ($team): void {
                if (! $user->hasRole($team->role)) {
                    $user->assignRole($team->role);
                }
            });
        }

        foreach ($aerodromes as $aerodrome) {
            AerodromeLink::factory()->for($aerodrome, 'aerodrome')->count(2)->create();
        }

        AtcBooking::factory()->count(18)->create([
            'controller_id' => fn () => $users->random()->id,
            'station_id' => fn () => $stations->random()->id,
        ]);

        $this->command?->info('Synthetic homepage demo data created.');
    }
}
