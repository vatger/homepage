<?php

namespace App\Jobs\Forum;

use App\Libraries\XenForoLibrary;
use App\Models\Membership\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateForumAccountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $_lastUpdatedAccount = null;

    private $_totalAccounts = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (Cache::has('org.vatsim-germany.forum.updater.lastUpdatedAccount')) {
            $this->_lastUpdatedAccount = Cache::get('org.vatsim-germany.forum.updater.lastUpdatedAccount');
        } else {
            $this->_lastUpdatedAccount = 0;
        }

        $this->_totalAccounts = User::count();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $chunkSize = $this->_totalAccounts > 500 ? ceil($this->_totalAccounts / 12) : 100;

        $accountsToUpdate = User::where('id', '>=', $this->_lastUpdatedAccount + 1)
            ->take($chunkSize)
            ->get();

        foreach ($accountsToUpdate as $acc) {
            if ($acc->settings == null) {
                continue;
            }
            if ($acc->settings->forum_id == null) {
                continue;
            }

            if ($acc->IsCurrentlyBanned) {
                XenForoLibrary::banForumAccount($acc);
            } else {
                XenForoLibrary::updateForumAccount($acc);
            }
        }

        if ($accountsToUpdate->count() < $chunkSize) {
            Cache::forget('org.vatsim-germany.forum.updater.lastUpdatedAccount');
        } else {
            Cache::put('org.vatsim-germany.forum.updater.lastUpdatedAccount', $accountsToUpdate->last()->id);
        }
    }
}
