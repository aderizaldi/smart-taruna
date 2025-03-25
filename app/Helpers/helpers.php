<?php

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

/**
 * @param $image
 * @param $path
 * @param int $quality
 * @return string
 */
if (!function_exists('save_as_webp')) {
    function save_as_webp($image, $path, $quality = 70)
    {
        File::ensureDirectoryExists(storage_path('app/public/') . $path);

        $filename = explode('.', $image->hashName())[0];

        $image_manager = new ImageManager(new Driver);
        $image = $image_manager->read($image);
        $encoded = $image->toWebp($quality);
        $encoded->save("storage/" . $path . $filename . ".webp");

        return $path . $filename . ".webp";
    }
}
