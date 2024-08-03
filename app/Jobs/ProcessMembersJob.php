<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\User\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessMembersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $files = Storage::files('jobs/members/');
        if (empty($files)) return;
        $file = $files[0];
        $time = intval(explode('+', trim($file, "jobs/merlit.n"))[0]);
        if ($time == 0) return;
        $data = json_decode(Storage::get($file));

        if (is_array($data)) {
            foreach ($data as $d) {
                CoreApiLibrary2::insertMemberData(User::find($d->id), $d, membership_refresh: true, timestamp: $time);
            }
        } elseif (is_object($data)) {
            CoreApiLibrary2::insertMemberData(User::find($data->id), $data, membership_refresh: true, timestamp: $time);
        }

        Storage::delete($file);
    }

}
