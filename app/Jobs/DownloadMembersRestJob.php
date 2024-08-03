<?php

namespace App\Jobs;

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

class DownloadMembersRestJob implements ShouldQueue
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
     */
    public function handle(): void
    {
        Log::info('[UpdateRestMembersJob]::Starting');
        $this->collection->lazy()->each(self::handle_user(...));
        Log::info("[UpdateRestMembersJob]::Completed  $this->really_updating of $this->total_to_update updated");
    }

    static function handle_user($obj): void
    {
        $user = User::find($obj['id']);
        if (!$user) {
            return;
        }
        $start_time = Carbon::now()->timestamp;
        $data = CoreApiLibrary2::downloadMember($user);
        if (!$data) return;
        \Storage::put("jobs/members/member.$start_time+$user->id.json", json_encode($data));
    }
}
