<?php

namespace App\Jobs;

use App\Models\Tech\SysLog;
use App\OpenApi\Models\ApiLog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

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

        self::cleanup_cached_images();
    }


    public static function cleanup_cached_images(int $days = 30): void
    {
        $directory = storage_path('app/public/image_cache');

        $files = File::allFiles($directory);
        $now = Carbon::now();
        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(File::lastModified($file));
            if ($now->diffInDays($lastModified, true) >= $days) {
                File::delete($file);
            }
        }

        self::cleanup_empty_directories($directory);
    }

    static function cleanup_empty_directories(string $directory): void
    {
        $directories = File::directories($directory);
        foreach ($directories as $subDirectory) {
            self::cleanup_empty_directories($subDirectory);
            if (count(File::allFiles($subDirectory)) === 0) {
                File::deleteDirectory($subDirectory);
            }
        }
    }

}
