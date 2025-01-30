<?php

namespace App\Jobs;

use App\Libraries\GDPRLibrary;
use App\Models\Membership\UserVatgerDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class StartRemovalsJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        UserVatgerDetail::with('user')
            ->whereNotNull('delete_at')
            ->where('delete_at', '<', now()->subHours(24))
            ->cursor()
            ->each(function (UserVatgerDetail $vatger_details) {
                GDPRLibrary::start_deletion($vatger_details->user);
            });
    }
}
