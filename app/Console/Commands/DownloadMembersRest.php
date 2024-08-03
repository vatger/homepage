<?php

namespace App\Console\Commands;

use App\Jobs\DownloadMembersRestJob;
use App\Jobs\UpdateSubdivisionMembersJob;
use Illuminate\Console\Command;

class DownloadMembersRest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:download-members-rest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update all non subdivision members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new DownloadMembersRestJob());
    }
}
