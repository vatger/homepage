<?php

namespace App\Console\Commands;

use App\Jobs\WorkRemovalJob;
use App\Models\Membership\GdprRemoval;
use App\Models\Tech\Job;
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

    public static int $count_do = 10;

    public static int $count_candidates = 100;

    public static int $count_start = 20;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (Job::count() > self::$count_start) {
            return;
        }

        $candidates = GdprRemoval::whereNull('completed_at')
            ->whereNull('canceled_at')
            ->orderBy('started_at', 'asc')
            ->limit(static::$count_candidates)
            ->get();

        $numbers = range(0, static::$count_candidates - 1);
        shuffle($numbers);
        $selected_numbers = array_slice($numbers, 0, static::$count_do);

        foreach ($selected_numbers as $number) {
            $gdpr_removal = $candidates->at($number);
            dispatch(new WorkRemovalJob($gdpr_removal));
        }

    }
}
