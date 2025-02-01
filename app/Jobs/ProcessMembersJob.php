<?php

namespace App\Jobs;

use App\Libraries\MembershipLibrary;
use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ProcessMembersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $files = Storage::files('jobs/members/');

        if (count($files) == 0) {
            return;
        }

        $file = $files[rand(0, min([count($files) - 1, 10]))];

        $time = intval(explode('+', trim($file, 'jobs/merlit.n'))[0]);
        if ($time == 0) {
            return;
        }

        $data = json_decode(Storage::get($file));
        Storage::delete($file);

        $user = User::find($data->id);
        CoreApiLibrary2::insertMemberData($user, $data, $time);
        MembershipLibrary::update($user);

        if (count($files) > 3) {
            dispatch(new self);
        }

    }
}
