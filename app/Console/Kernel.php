<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Update atc and piloting hours
        $schedule->command('vatsim:updateRatingTimes')->hourly();
        // Update atc and pilot statistics
        $schedule->command('statistics:update')->everyFiveMinutes();

        // Schedule AIRAC DFS chart updates
        // as of writing latest release was 27.01.2022
        // as an airac is updated every 28 days we can continuesly calculate the next update
        // IMPORTANT:
        // If needed, you may specify how many minutes must pass before the "without overlapping" lock expires.
        // By default, the lock will expire after 24 hours:
        // ENABLE ONLY IF STAFF ALLOWS IT
        $schedule
            ->command('dfs:charts --cycle')
            ->when(function () {
                $initialDate = \Carbon\Carbon::create(2022, 1, 27, 6, 0, 0, 'UTC'); // use 06:00 as time due to the dfs aip is updated at 03:00.
                $now = \Carbon\Carbon::now()->utc();
                $diff = $now->floatDiffInRealDays($initialDate);
                return $diff % 28 == 0;
            })
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
