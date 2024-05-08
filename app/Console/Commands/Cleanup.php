<?php

namespace App\Console\Commands;

use App\Jobs\CleanupJob;
use Illuminate\Console\Command;

class Cleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to cleanup some logs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        dispatch(new CleanupJob());
    }
}
