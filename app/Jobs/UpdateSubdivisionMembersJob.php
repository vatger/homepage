<?php

namespace App\Jobs;

use App\Libraries\VATSIM\APILibrary;
use App\Models\Membership\Account;
use App\Models\Membership\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info('[UpdateSubdivisionMembersJob]::Handle::Starting Update of subdivision members.');
        $subdivision_accounts = APILibrary::CachedSubdivisionMembers();
        if (empty($subdivision_accounts)) {
            Log::alert('[UpdateSubdivisionMembersJob]::Handle::No accounts.');
        }

        foreach ($subdivision_accounts as $a) {
            $user = User::find($a?->id);
            if (!$user) {
                continue;
            }
            $user->update([
                'firstname' => $a->name_first,
                'lastname' => $a->name_last,
                'email' => $a->email,
            ]);
            $user->vatsimDetails->update([
                'rating_atc' => $a->rating,
                'rating_pilot' => $a->pilotrating,
                'rating_military' => $a->militaryrating,
                'region_code' => $a->region,
                'division_code' => $a->division,
                'subdivision_code' => $a->subdivision,
                'last_rating_change_at' => $a->lastratingchange,
                'registered_at' => $a->reg_date,
                'country_code' => $a->country,
            ]);
        }
        
        Log::info('[UpdateSubdivisionMembersJob]::Handle::Completed subdivision members update.');
    }
}
