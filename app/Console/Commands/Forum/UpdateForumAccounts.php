<?php

namespace App\Console\Commands\Forum;

use App\Jobs\Forum\UpdateForumAccountsJob;
use Illuminate\Console\Command;

class UpdateForumAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forum:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update forum accounts to match current standings.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        UpdateForumAccountsJob::dispatch();
    }
}
