<?php

namespace App\Console\Commands;

use App\Jobs\WorkRemovalJob;
use App\Models\Job;
use App\Models\Membership\GdprRemoval;
use Illuminate\Console\Command;

class WorkRemovals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:work-removals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to trigger GDPR removals';

    public static int $count = 1000;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (Job::count() > self::$count) {
            return;
        }

        GdprRemoval::whereNull('completed_at')
            ->whereNull('canceled_at')
            ->limit(static::$count)
            ->cursor()
            ->each(function (GdprRemoval $gdpr_removal) {
                dispatch(new WorkRemovalJob($gdpr_removal));
            });
    }
}
