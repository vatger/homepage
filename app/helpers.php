<?php
if (!function_exists('iasset')) {
    function iasset(string $path, ?int $width): string
    {
        return \App\Libraries\ImageHelperLibrary::asset($path, $width);
    }
}
