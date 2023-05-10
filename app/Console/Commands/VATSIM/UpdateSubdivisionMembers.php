<?php

namespace App\Console\Commands\VATSIM;

use Illuminate\Console\Command;

class UpdateSubdivisionMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatsim:updateSubdivisionMembers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update all subdivision members';

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
        return 0;
    }
}
