<?php

namespace App\Console\Commands;

use App\Jobs\DownloadMembersSubdivisionJob;
use Illuminate\Console\Command;

class DownloadMembersSubdivision extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:download-members-subdivision';

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
        dispatch(new DownloadMembersSubdivisionJob);
    }
}
