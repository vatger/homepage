<?php

namespace App\Console\Commands;

use App\Models\Groups\Team;
use App\Models\Membership\User;
use Illuminate\Console\Command;

class AddTeamMember extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vatger:add-team-member';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to the add a member to a team';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user_id = $this->ask('user_id?');
        $user = User::findOrFail($user_id);
        $team_name = $this->ask('team_name?');
        $team = Team::where('name', 'LIKE', $team_name)->firstOrFail();
        $user->assignRole($team);
    }
}
