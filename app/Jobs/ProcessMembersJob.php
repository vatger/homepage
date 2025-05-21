<?php

namespace App\Jobs;

use App\Libraries\MembershipLibrary;
use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\User;
use App\Models\Tech\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
        Log::debug($file);
        $time = intval(explode('+', trim($file, 'jobs/merlit.n'))[0]);
        if ($time == 0) {
            return;
        }

        $data = json_decode(Storage::get($file));
        Storage::delete($file);
        Log::debug($data);
        $number_jobs = Cache::remember('ProcessMembersJob', 60, fn () => Job::count());

        if (count($files) > 0 && $number_jobs < 100) {
            dispatch(new self);
        }
        if (count($files) > 100 && $number_jobs < 100) {
            dispatch(new self);
        }

        if (! $data || $data?->id) {
            return;
        }

        $user = User::find($data->id);
        if (! empty($user)) {
            Log::debug($user);
            Log::debug($time);
            CoreApiLibrary2::insertMemberData($user, $data, $time);
            Log::debug('done');
            MembershipLibrary::update($user);
        }
    }
}
