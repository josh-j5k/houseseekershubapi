<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageCompressHelper
{


    public static function compress($original_image, int $max_width, int $quality, string $folder, string $subfolder, string|null $file_folder = null): string
    {


        $path = public_path($original_image);
        $dir = $file_folder ? "images/$folder/$subfolder/$file_folder" : "images/$folder/$subfolder";

        if (!is_dir($dir)) {
            mkdir($dir, recursive: true);
        }
        $imageType = getimagesize($path)['mime'];
        $img = null;
        if ($imageType === 'image/png') {
            $img = imagecreatefrompng($path);
        } elseif ($imageType === 'image/jpeg') {
            $img = imagecreatefromjpeg($path);
        }



        $MAX_WIDTH = $max_width;

        imagepalettetotruecolor($img);
        $scale_size = $MAX_WIDTH / getimagesize($path)[0];

        $MAX_HEIGHT = getimagesize($path)[1] * $scale_size;
        $resized_img = imagescale($img, $MAX_WIDTH, $MAX_HEIGHT);
        // Storage::disk('public')->putFile($dir, );
        imagewebp($resized_img, $dir, $quality);
        imagedestroy($img);
        imagedestroy($resized_img);

        $url = $dir;
        return $url;
    }
}
