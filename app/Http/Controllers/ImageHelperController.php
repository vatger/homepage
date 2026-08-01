<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

class ImageHelperController extends Controller
{
    protected static ?ImageManager $manager = null;

    private static function manager(): ImageManager
    {
        if (! self::$manager) {
            try {
                self::$manager = new ImageManager(ImagickDriver::class);
            } catch (\Throwable) {
                self::$manager = new ImageManager(GdDriver::class);
            }
        }

        return self::$manager;
    }

    private static function getKey(string $img, int $sizeX = 0, int $sizeY = 0): string
    {
        return str_replace('=', '_', base64_encode(urlencode($img).'//'.$sizeX.'//'.$sizeY));
    }

    private static function getKeyValues(string $key): array
    {
        $key = explode('.', $key)[0];
        $decoded = base64_decode(str_replace('_', '=', $key));
        $data = explode('//', $decoded);

        return ['img' => urldecode($data[0]), 'sizeX' => $data[1], 'sizeY' => $data[2]];
    }

    public static function buildFromLink(string $img, int $sizeX = 0, int $sizeY = 0): string
    {
        $url = $img;

        // Use basename() function to return the base name of file
        $file_name = basename($url);

        $downloadPath = Storage::path('public/images/'.$file_name);
        file_put_contents($downloadPath, file_get_contents($url));

        $image = self::manager()->decodePath($downloadPath);

        if ($sizeX == 0 && $sizeY == 0) {
            $sizeX = $image->width();
            $sizeY = $image->height();
        } elseif ($sizeX == 0) {
            $scale = floatval($sizeY) / $image->height();
            $sizeX = (int) round($scale * $image->width());
        } elseif ($sizeY == 0) {
            $scale = floatval($sizeX) / $image->width();
            $sizeY = (int) round($scale * $image->height());
        }

        $image->resize($sizeX, $sizeY);
        $encoded = $image->encode();
        $key = self::getKey($img, $sizeX, $sizeY).'.'.ltrim($encoded->mimetype(), 'image/');

        $path = Storage::path('public/images/'.$key);
        // dd(self::getKeyValues($key));
        $encoded->save($path);

        return $path;
    }
}
