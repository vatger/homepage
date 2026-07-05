<?php

use App\Libraries\ImageHelperLibrary;

if (! function_exists('iasset')) {
    function iasset(string $path, ?int $width = null): string
    {
        return ImageHelperLibrary::asset($path, $width);
    }
}
