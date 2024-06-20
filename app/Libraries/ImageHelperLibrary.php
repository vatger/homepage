<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageHelperLibrary extends BaseLibrary
{
    private Client $client;

    private ImageManager $manager;

    public function __construct()
    {
        $this->client = self::constructClient([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', // Spoof the User-Agent Header
                'Referer' => config('app.url'),
                'Host' => parse_url(config('app.url'), PHP_URL_HOST),
            ]
        ]);
        $this->manager = new ImageManager(new Driver());
    }

    public function get(string $filename, string $url, int $width = 1920): string
    {
        $filepath = "public/image_cache/" . $filename . ".webp";
        if (Storage::exists($filepath)) {
            return Storage::url($filepath);
        }
        try {
            $response = $this->client->get($url);
            throw_if($response->getStatusCode() != 200, new \Exception());
            $data = $response->getBody()->getContents();
            $image = $this->manager->read($data);
            $image->scale(width: 1920);
            Storage::put($filepath, $image->toWebp()->toString());
            return Storage::url($filepath);
        } catch (\Throwable $e) {
            return $url;
        }
    }


}
