<?php

namespace App\Console\Commands\Housekeeping;

use App\Models\Statistics\ATC;
use App\Models\Statistics\Pilot;
use Illuminate\Console\Command;

class ClearStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'housekeeping:clearstatistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all statistics tables';

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
        ATC::truncate();
        Pilot::truncate();
    }
}
