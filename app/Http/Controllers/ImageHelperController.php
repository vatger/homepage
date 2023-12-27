<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageHelperController extends Controller
{
    protected static ?ImageManager $manager = null;

    private static function manager(): ImageManager
    {
        if (!self::$manager) {
            try {
                self::$manager = ImageManager::imagick();
            } catch (\Exception $e) {
                self::$manager = ImageManager::gd();
            }
        }
        return self::$manager;
    }

    private static function getKey(string $img, int $sizeX = 0, int $sizeY = 0): string
    {
        return str_replace('=', '_', base64_encode(urlencode($img) . '//' . $sizeX . '//' . $sizeY));
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

        file_put_contents(Storage::path('public/images/' . $file_name), file_get_contents($url));

        $image = self::manager()->read($img);

        if ($sizeX == 0 && $sizeY == 0) {
            $sizeX = $image->size()->width();
            $sizeY = $image->size()->height();
        } elseif ($sizeX == 0) {
            $scale = floatval($sizeY) / $image->size()->height();
            $sizeX = (int) round($scale * $image->size()->width());
        } elseif ($sizeY == 0) {
            $scale = floatval($sizeX) / $image->size()->width();
            $sizeY = (int) round($scale * $image->size()->height());
        }

        $image->resize($sizeX, $sizeY);
        $encoded = $image->encode();
        $key = self::getKey($img, $sizeX, $sizeY) . '.' . ltrim($encoded->mimetype(), 'image/');

        $path = Storage::path('public/images/' . $key);
        //dd(self::getKeyValues($key));
        $encoded->save($path);
        return $path;
    }
}
