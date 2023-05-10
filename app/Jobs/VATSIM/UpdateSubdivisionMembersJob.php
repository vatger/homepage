<?php

namespace App\Jobs\VATSIM;

use App\Libraries\VATSIM\APILibrary;
use App\Models\Membership\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateSubdivisionMembersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $_subdivisionAccounts;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_subdivisionAccounts = APILibrary::CachedSubdivisionMembers();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[UpdateSubdivisionMembersJob]::Handle::Starting Update of subdivision members.');

        // TODO: Update data of local accounts

        Log::info('[UpdateSubdivisionMembersJob]::Handle::Completed subdivision members update.');
    }
}
