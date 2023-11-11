<?php

namespace App\Jobs;

use App\Libraries\VATSIM\APILibrary;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Spatie\FlareClient\Api;

class UpdateSubdivisionMembersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[UpdateSubdivisionMembersJob]::Starting');
        APILibrary::SubdivisionMembersUpdate(update_vatger_membership: true);
        Log::info('[UpdateSubdivisionMembersJob]::Completed');
    }
}
