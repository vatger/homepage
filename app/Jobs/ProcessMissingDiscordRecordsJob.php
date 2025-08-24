<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\DiscordUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessMissingDiscordRecordsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dicord_user = DiscordUser::whereNull('user_id')->inRandomOrder()->first();
        if (! $dicord_user) {
            return;
        }
        if (CoreApiLibrary2::checkLimit() < 2) {
            return;
        }
        CoreApiLibrary2::findDiscord($dicord_user->discord_id);
    }
}
