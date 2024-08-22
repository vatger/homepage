<?php

namespace App\Jobs;

use App\Libraries\GDPRLibrary;
use App\Models\Membership\User\GdprRemoval;
use App\Models\Membership\User\UserVatgerDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class UpdateGDPRRemovalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {

        UserVatgerDetail::with("user")
            ->whereNotNull('delete_at')
            ->where('delete_at', '<', now()->subHours(24))
            ->cursor()
            ->each(function ($vatger_details) {
                GDPRLibrary::start_deletion($vatger_details->user);
            });

        GdprRemoval::cursor()->each(function ($gdpr_removal) {
            GDPRLibrary::work($gdpr_removal);
        });
    }


}
