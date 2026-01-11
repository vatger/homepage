<?php

namespace App\Libraries;

use App\Jobs\DownloadImageForCache;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

class ImageHelperLibrary extends BaseLibrary
{
    private Client $client;

    private ImageManager $manager;

    public function __construct()
    {
        $this->client = self::constructClient([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', // Spoof the User-Agent MyHeader
                'Referer' => config('app.url'),
                'Host' => parse_url(config('app.url'), PHP_URL_HOST),
            ],
        ]);
        $this->manager = ImageManager::withDriver(GdDriver::class);
    }

    public function get(string $filename, string $url, int $width = 1920, bool $fast = false): string
    {
        $filepath = 'public/image_cache/'.$filename.'.webp';
        if (Storage::exists($filepath)) {
            return Storage::url($filepath);
        }
        if ($fast) {
            Storage::put($filepath, $this->manager->create(1, 1)->toWebp()->toString());
            dispatch(new DownloadImageForCache($filepath, $url, $width));

            return Storage::url($filepath);
        }

        return self::download_and_save($filepath, $url, $width);
    }

    public function download_and_save(string $filepath, string $url, int $width): string
    {
        try {
            $response = $this->client->get($url);
            throw_if($response->getStatusCode() != 200, new \Exception);
            $data = $response->getBody()->getContents();
            $image = $this->manager->read($data);
            $image->scale(width: $width);
            Storage::delete($filepath);
            Storage::put($filepath, $image->toWebp()->toString());

            return Storage::url($filepath);
        } catch (\Throwable $e) {
            return '';
        }
    }

    public static function static_get(string $url, ?int $width = null): string
    {
        $filename = str_replace(' ', '-', $url);
        $filename = preg_replace('/[^A-Za-z0-9\-]/', '', $filename);

        $filepath = 'public/image_cache/get/'.$filename.'.webp';
        if (Storage::exists($filepath)) {
            return Storage::url($filepath);
        }
        $lib = new self;

        try {
            $response = $lib->client->get($url);
            throw_if($response->getStatusCode() != 200, new \Exception);
            $data = $response->getBody()->getContents();
            $image = $lib->manager->read($data);
            if ($width != null) {
                $image->scale(width: $width);
            }
            Storage::put($filepath, $image->toWebp()->toString());

            return Storage::url($filepath);
        } catch (\Throwable $e) {
            return $url;
        }
    }

    public static function asset(string $url, ?int $width = null): string
    {
        $filename = str_replace(' ', '-', $url);
        $filename = preg_replace('/[^A-Za-z0-9\-\/.]/', '', $filename);
        if ($width != null) {
            $filename .= '-w'.$width.'px';
        }

        $filepath = 'public/image_cache/asset/'.$filename.'.webp';
        if (Storage::exists($filepath)) {
            return Storage::url($filepath);
        }
        $lib = new self;

        try {
            $image = $lib->manager->read(File::get(public_path($url)));

            if ($width == null && $image->width() > 1920) {
                $image->scale(1920);
            }
            if ($width != null) {
                $image->scale(width: $width);
            }

            Storage::put($filepath, $image->toWebp()->toString());

            return Storage::url($filepath);
        } catch (\Throwable $e) {
            return $url;
        }
    }
}
