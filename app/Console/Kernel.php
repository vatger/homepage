<?php

namespace App\Console;

use App\Jobs\ProcessMissingDiscordRecordsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('vatger:download-members-subdivision')->everyTwoHours();
        $schedule->command('vatger:download-members-rest')->everyMinute();
        $schedule->command('vatger:process-members')->everyMinute();
        $schedule->job(new ProcessMissingDiscordRecordsJob)->everyTwoMinutes();

        $schedule->command('vatger:update-nav-stations')->everyFourHours();
        $schedule->command('vatger:update-teamspeak')->everyFifteenMinutes();
        $schedule->command('vatger:cleanup')->hourly();
        $schedule->command('vatger:start-removals')->everySixHours();
        $schedule->command('vatger:work-removals')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
