<?php
if (!function_exists('iasset')) {
    function iasset($path): string
    {
        return \App\Libraries\ImageHelperLibrary::asset($path, null);
    }
}
