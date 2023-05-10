<?php

namespace App\Console\Commands\Development;

use App\Models\Membership\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetLocalPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'development:setpassword {cid? : The cid of the account}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the local password for a given cid. Will work only if the environment is not in production.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (env('APP_ENV') !== 'production') {
            // If the argument was not supplied directly
            $cid = $this->argument('cid') == null ? $this->ask('Which account shall be updated?') : $this->argument('cid');

            $this->info('Searching account with cid: ' . $cid);
            $account = User::find($cid);
            if ($account != null && $this->confirm('Do you want to set the local password for ' . $account->username . '?')) {
                $newPassword = $this->secret('The new password shall be?');
                $account->password = Hash::make($newPassword);
                $account->save();
                $this->info('Updated password for account ' . $account->username . '!');
            } else {
                $this->error('No account found!');
            }
        } else {
            $this->error('Application is in production!');
        }
    }
}
