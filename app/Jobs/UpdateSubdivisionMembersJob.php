<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateSubdivisionMembersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public static string $offset_key = 'UpdateSubdivisionMembersJob.offset';
    protected int $offset;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->offset = 0;
        if (Cache::has(self::$offset_key)) {
            $this->offset = Cache::get(self::$offset_key);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info('[UpdateSubdivisionMembersJob]::Starting');
        $new_offset = CoreApiLibrary2::updateSubdivisionMembers($this->offset);
        Cache::put(self::$offset_key, $new_offset);
        Log::info('[UpdateSubdivisionMembersJob]::Completed');
    }
}
