<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\UserVatsimDetail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class DownloadMembersRestJob implements ShouldQueue
{
    use Queueable;

    public int $count = 20;

    public int $refresh_time = 60 * 60 * 24 * 7;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $time = Carbon::now()->timestamp - $this->refresh_time;
        UserVatsimDetail::where('last_download', '<', $time)
            ->orderBy('last_download')
            ->take($this->count)
            ->each(function (UserVatsimDetail $userVatsimDetail) {
                CoreApiLibrary2::downloadMember($userVatsimDetail->user);
            });
    }
}
