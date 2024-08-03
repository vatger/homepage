<?php

namespace App\Console\Commands;

use App\Jobs\DownloadMembersSubdivisionJob;
use App\Jobs\ProcessMembersSubdivisionJob;
use Illuminate\Console\Command;

class ProcessMembersSubdivision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:process-members-subdivision';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update all subdivision members';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        dispatch(new ProcessMembersSubdivisionJob());
    }
}
