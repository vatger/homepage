<?php

namespace App\Jobs;

use App\Libraries\VATSIM\CoreApiLibrary2;
use App\Models\Membership\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VatsimWebhookJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public object $data) {}

    public function handle(): void
    {
        try {
            $user = User::find($this->data?->resource);
            if (empty($user)) {
                return;
            }
            CoreApiLibrary2::downloadMember($user);
        } catch (\Exception $e) {
            \Log::error($e);
        }
    }
}
