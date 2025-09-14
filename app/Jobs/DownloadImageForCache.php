<?php

namespace App\Jobs;

use App\Libraries\ImageHelperLibrary;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DownloadImageForCache
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly string $filepath, public readonly string $url, public readonly int $width)
    {
        Log::debug('DownloadImageForCache __construct');
    }

    public function handle(): void
    {
        Log::debug("DownloadImageForCache start $this->filepath");
        $img_library = new ImageHelperLibrary;
        $img_library->download_and_save($this->filepath, $this->url, $this->width);
        Log::debug("DownloadImageForCache end $this->filepath");
    }
}
