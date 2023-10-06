<?php

namespace App\Helpers;
use Illuminate\Support\Str;

class Avatar {
    
    public static function classic($skin) {
        $im = imagecreatefrompng($skin);
        $image = imagecrop($im, ['x' => 9, 'y' => 9, 'width' => 7, 'height' => 7]);
        header ("Content-type: image/png");
        imagepng($image);
        //var_dump($image);
        //imagejpeg($image,$newfilename);
}
}
