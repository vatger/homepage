<?php

namespace App\Console\Commands\VATSIM;

use App\Jobs\VATSIM\UpdateRatingTimesJob;
use Illuminate\Console\Command;

class UpdateRatingTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatsim:updateRatingTimes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the rating times of members';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // All we need to do is to dispatch the worker
        UpdateRatingTimesJob::dispatch();
        return 0;
    }
}
