<?php

namespace App\Jobs;

use App\Models\Tech\SysLog;
use App\OpenApi\Models\ApiLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CleanupJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $cutoff_date = Carbon::now()->subDays(28);

        SysLog::where('created_at', '<', $cutoff_date)->delete();

        ApiLog::where('created_at', '<', $cutoff_date)->delete();
    }
}
