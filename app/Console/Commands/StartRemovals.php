<?php

namespace App\Console\Commands;

use App\Jobs\StartRemovalsJob;
use Illuminate\Console\Command;

class StartRemovals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:start-removals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to trigger GDPR removals';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        dispatch(new StartRemovalsJob);
    }
}
