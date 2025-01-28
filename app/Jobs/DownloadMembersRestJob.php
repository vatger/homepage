<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\UserVatsimDetail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadMembersRestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $count = 20;

    public int $refresh_time = 60 * 60 * 24 * 7;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserVatsimDetail::where('last_download', '<', Carbon::now()->timestamp - $this->refresh_time)
            ->orderBy('last_download')
            ->take($this->count)
            ->each(function (UserVatsimDetail $userVatsimDetail) {
                CoreApiLibrary2::downloadMember($userVatsimDetail->user);
            });
    }
}
