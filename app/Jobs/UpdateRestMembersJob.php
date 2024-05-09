<?php

namespace App\Jobs;

use App\Libraries\VATSIM\APILibrary;
use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\User\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateRestMembersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Collection $collection;

    public int $total_to_update = 0;
    public int $really_updating = 0;

    public static int $refresh_time = 60 * 60 * 12;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->collection = collect();
        User::select('id')
            ->lazy()
            ->each(function (object $user) {
                $cache_key = CoreApiLibrary2::$cache_key_user . $user->id;
                $cache_exists = Cache::has($cache_key);
                $cached_val = $cache_exists ? Carbon::createFromTimestamp(intval(Cache::get($cache_key))) : Carbon::now()->subDay();
                if (!$cache_exists || $cached_val->diffInSeconds(Carbon::now(), true) > self::$refresh_time) {
                    $this->collection->add(['id' => $user->id, 'time' => $cached_val->timestamp]);
                }
            });
        $this->total_to_update = $this->collection->count();
        $this->really_updating = min($this->total_to_update, $this->total_to_update / 24, 50);
        $this->collection = $this->collection->sortBy('time')->take($this->really_updating);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[UpdateRestMembersJob]::Starting');
        $this->collection->lazy()->each(function ($obj) {
            $user = User::find($obj['id']);
            if (!$user) {
                return;
            }
            CoreApiLibrary2::updateMember($user, update_vatger_membership: true);
        });
        Log::info("[UpdateRestMembersJob]::Completed  $this->really_updating of $this->total_to_update updated");
    }
}
