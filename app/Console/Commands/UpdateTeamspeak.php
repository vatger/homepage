<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSubdivisionMembersJob;
use App\Jobs\UpdateTeamspeakJob;
use Illuminate\Console\Command;

class UpdateTeamspeak extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:update-teamspeak';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to trigger teamspeak';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new UpdateTeamspeakJob());
    }
}
