<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\File;

class ImageDownloader {

    /**
     * Save image and return its relative path
     * @param $url
     * @return string
     */
    static function save($url) {
        $folder = str_random(2);
        $newName = $folder . '/' . str_random(40);

        $contents = file_get_contents($url);

        \Storage::disk('photos')->put( $newName,  $contents );

        Image::make(public_path().'/photos/'.$newName)
            ->resize(1280, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save(public_path().'/photos/'.$newName.'.jpg', 94);

        \File::delete(public_path().'/photos/'.$newName);

        return $newName.'.jpg';
    }

}
