<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSubdivisionMembersJob;
use App\Libraries\NavLibrary;
use Illuminate\Console\Command;

class UpdateNavStations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:update-nav-stations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to the ATC stations DB from the github repo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting update-nav-stations.');
        NavLibrary::sync_stations();
        $this->info('Finished update-nav-stations.');
    }
}
