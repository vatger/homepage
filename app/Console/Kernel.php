<?php

namespace App\Console;

use App\Jobs\CleanupJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('vatger:update-subdivision-members')->hourlyAt(10);
        $schedule->command('vatger:update-rest-members')->everyFiveMinutes();
        $schedule->command('vatger:update-nav-stations')->everyFourHours();
        $schedule->command('vatger:update-teamspeak')->everyFifteenMinutes();
        $schedule
            ->job(new CleanupJob())
            ->weekly()
            ->mondays()
            ->at('05:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
