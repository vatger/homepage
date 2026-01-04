<?php

use App\Jobs\ProcessMissingDiscordRecordsJob;
use Illuminate\Support\Facades\Schedule;

Schedule::command('vatger:download-members-subdivision')->everyTwoHours();
Schedule::command('vatger:download-members-rest')->everyMinute();
Schedule::command('vatger:process-members')->everyMinute();
Schedule::job(new ProcessMissingDiscordRecordsJob)->everyTwoMinutes();
Schedule::command('vatger:update-nav-stations')->everyFourHours();
Schedule::command('vatger:update-teamspeak')->everyFifteenMinutes();
Schedule::command('vatger:cleanup')->hourly();
Schedule::command('vatger:start-removals')->everySixHours();
Schedule::command('vatger:work-removals')->everyMinute();
