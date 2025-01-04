<?php

if (! function_exists('iasset')) {
    function iasset(string $path, ?int $width = null): string
    {
        return \App\Libraries\ImageHelperLibrary::asset($path, $width);
    }
}
