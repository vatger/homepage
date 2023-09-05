<?php

namespace App\Console\Commands;

use App\Jobs\UpdateSubdivisionMembersJob;
use Illuminate\Console\Command;

class UpdateSubdivisionMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatsim:update-subdivision-members';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update all subdivision members';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new UpdateSubdivisionMembersJob());
    }
}
