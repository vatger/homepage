<?php

namespace App\Jobs;

use App\Libraries\GDPRLibrary;
use App\Models\Membership\GdprRemoval;
use App\Models\Membership\UserVatgerDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class UpdateGDPRRemovalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $start_id = 0;

    private string $cache_key = 'UpdateGDPRRemovalsJob.start_id';

    public function __construct()
    {
        if (\Cache::has($this->cache_key)) {
            $this->start_id = \Cache::get($this->cache_key);
        }
        if (GdprRemoval::where('id', '>=', $this->start_id)->count() == 0) {
            $this->start_id = 0;
        }
    }


    public function handle(): void
    {

        UserVatgerDetail::with('user')
            ->whereNotNull('delete_at')
            ->where('delete_at', '<', now()->subHours(24))
            ->limit(20000)
            ->cursor()
            ->each(function ($vatger_details) {
                GDPRLibrary::start_deletion($vatger_details->user);
            });

        GdprRemoval::where('id', '>=', $this->start_id)
            ->whereNull('completed_at')
            ->whereNull('canceled_at')
            ->limit(10)
            ->cursor()
            ->each(function (GdprRemoval $gdpr_removal) {
                GDPRLibrary::work($gdpr_removal);
                \Cache::put($this->cache_key, $gdpr_removal->id + 1);
            });
    }


}
