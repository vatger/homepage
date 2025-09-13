<?php

namespace App\Jobs;

use App\Libraries\ImageHelperLibrary;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadImageForCache
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public readonly string $filepath, public readonly string $url, public readonly int $width) {}

    public function handle(): void
    {
        $img_library = new ImageHelperLibrary;
        $img_library->download_and_save($this->filepath, $this->url, $this->width);
    }
}
