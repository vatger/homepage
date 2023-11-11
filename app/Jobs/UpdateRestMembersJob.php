<?php

namespace App\Jobs;

use App\Libraries\VATSIM\APILibrary;
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
                $cache_key = 'vatsim.api.member_update.' . $user->id;
                $cache_exists = Cache::has($cache_key);
                $cached_val = $cache_exists ? Carbon::parse(Cache::get($cache_key)) : Carbon::now()->subDay();
                if (!$cache_exists || $cached_val->diffInSeconds(Carbon::now()) > self::$refresh_time) {
                    $this->collection->add(['id' => $user->id, 'time' => $cached_val->timestamp]);
                }
            });
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('[UpdateRestMembersJob]::Starting');
        $total_to_update = $this->collection->count();
        $really_updating = min($total_to_update, max($total_to_update / 12, 100));
        $collection_to_update = $this->collection->sortBy('time')->take($really_updating);
        $collection_to_update->lazy()->each(function ($obj) {
            $user = User::find($obj['id']);
            if (!$user) {
                return;
            }
            APILibrary::MemberUpdate($user, update_vatger_membership: true);
        });
        Log::info("[UpdateRestMembersJob]::Completed $really_updating of $total_to_update updated");
    }
}
