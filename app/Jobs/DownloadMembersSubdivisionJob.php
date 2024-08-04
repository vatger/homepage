<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Jobs\Job;
use Storage;

class DownloadMembersSubdivisionJob implements ShouldQueue
{
    use Queueable;

    public ?int $start_time = null;
    public readonly int $limit;
    public readonly int $chunk_size;

    /**
     * Create a new job instance.
     */
    public function __construct(public readonly int $offset = 0)
    {
        $this->limit = 1000;
        $this->chunk_size = 1;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->start_time = Carbon::now()->timestamp;
        $offset = $this->offset;
        $data = CoreApiLibrary2::downloadSubdivisionMembers($offset, $this->limit);
        $chunks = array_chunk($data, $this->chunk_size);
        foreach ($chunks as $chunk_key => $chunk) {
            $pos = $this->offset + $chunk_key * $this->chunk_size;
            Storage::put("jobs/members/list.$this->start_time+$pos.json", json_encode($chunk));
        }
        if ($offset == 0) return;
        if (\App\Models\Tech\Job::count() > 64) return;
        $job = new self($offset);
        dispatch($job);
    }
}
