<?php

namespace App\Console\Commands;

use App\Jobs\UpdateRestMembersJob;
use App\Jobs\UpdateSubdivisionMembersJob;
use Illuminate\Console\Command;

class UpdateRestMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:update-rest-members';

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
        dispatch(new UpdateRestMembersJob());
    }
}
