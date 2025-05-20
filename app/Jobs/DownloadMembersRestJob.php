<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\GdprRemoval;
use App\Models\Membership\UserVatsimDetail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class DownloadMembersRestJob implements ShouldQueue
{
    use Queueable;

    public int $refresh_time = 60 * 60 * 24 * 7;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $count = CoreApiLibrary2::checkLimit() - 5;
        if ($count <= 0) {
            return;
        }
        $gdpr_user_ids = GdprRemoval::whereNull(['canceled_at', 'completed_at'])
            ->get(['user_id'])
            ->select('user_id')
            ->flatten()
            ->values()
            ->toArray();
        $time = Carbon::now()->timestamp - $this->refresh_time;
        UserVatsimDetail::where('last_download', '<', $time)
            ->whereIntegerNotInRaw('user_id', $gdpr_user_ids)
            ->orderBy('last_download')
            ->take($count)
            ->each(function (UserVatsimDetail $userVatsimDetail) {
                CoreApiLibrary2::downloadMember($userVatsimDetail->user);
            });
    }
}
