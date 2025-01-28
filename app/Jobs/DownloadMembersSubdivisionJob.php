<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Cache;

class DownloadMembersSubdivisionJob implements ShouldQueue
{
    use Queueable;

    public readonly int $limit;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->limit = 1000;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $offset = Cache::get('DownloadMembersSubdivisionJob.offset', 0);
        CoreApiLibrary2::downloadSubdivisionMembers($offset, $this->limit);
        Cache::put('DownloadMembersSubdivisionJob.offset', $offset);
    }
}
