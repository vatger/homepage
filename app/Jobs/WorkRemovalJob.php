<?php

namespace App\Jobs;

use App\Libraries\GDPRLibrary;
use App\Models\Membership\GdprRemoval;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WorkRemovalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public GdprRemoval $gdprRemoval;

    public function __construct(GdprRemoval $gdprRemoval)
    {
        $this->gdprRemoval = $gdprRemoval;
    }

    public function handle(): void
    {
        GDPRLibrary::work($this->gdprRemoval);
    }
}
