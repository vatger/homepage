<?php

namespace App\Console\Commands;

use App\Jobs\UpdateGDPRRemovalsJob;
use Illuminate\Console\Command;

class UpdateGDPRRemovals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:update-removals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to trigger GDPR removals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new UpdateGDPRRemovalsJob());
    }
}
