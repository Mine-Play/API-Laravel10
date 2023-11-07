<?php

namespace App\Helpers;
use Illuminate\Support\Str;


class Avatar {
    public static function classic(String $skin){
        $avatar = new Avatar2D($skin, 64);
        return $avatar->base64();
    }
}


class Avatar2D extends Base {
    protected $BASE_SIZE = 8;

    /**
     * Class constructor
     */
    public function __construct($name, $size) {
        parent::__construct($name, $size);
    }

    /**
     * Concrete implementation of the CreateImage() method.
     */
    protected function createImage() {
        $size = $this->BASE_SIZE;
        imagesavealpha($this->skin, true);
        $this->image = imagecreatetruecolor($size, $size);
        imagecopyresampled($this->image, $this->skin, 0, 0, 8, 8, $size, $size, 8, 8);
        if($this->checkHatTransparency())
            imagecopyresampled($this->image, $this->skin, 0, 0, 40, 8, $size, $size, 8, 8);
    }
}

abstract class Base {
    protected $path;         // skin path
    protected $size;         // image height

    protected $skin;         // skin source
    protected $image;        // final image

    /**
     * Class constructor
     */
    protected function __construct(String $path, $size) {
        $this->path = $path;
        $this->size = $size;
    }

    /**
     * Return Base64
     */
    public function base64() {
        $skinExists = $this->loadSkin();

        // Check cache...

        if($skinExists) {
            $this->createImage();

            if($this->BASE_SIZE != $this->size)
                $this->resize();
            ob_start();
            imagePng($this->image);
            $base_64 = base64_encode(ob_get_clean());
            return "data:image/png;base64,".$base_64;
        } else {
            return false;
        }
    }

    /**
     * Load the skin
     */
    protected function loadSkin() {
        $this->skin = @imagecreatefrompng($this->path);

        if($this->skin === false)
            return false;

        imagesavealpha($this->skin, true);
        return true;
    }

    /**
     * Resize method
     */
    protected function resize() {
        $imgResized = imagecreatetruecolor($this->size, $this->size);
        imagecopyresampled($imgResized, $this->image, 0, 0, 0, 0, $this->size, $this->size, $this->BASE_SIZE, $this->BASE_SIZE);
        imagedestroy($this->image);
        $this->image = $imgResized;
    }

    /**
     * Helper method to detect transparent hats
     */
    protected function checkHatTransparency() {
        for($i = 0; $i < 8; $i++) {
            for($j = 0; $j < 8; $j++) {
                $rgb = imagecolorsforindex($this->skin, imagecolorat($this->skin, 40+$j, 8+$i));
                if($rgb["alpha"] == 127) {
                    return true;
                    break;
                }
            }
        }
        return false;
    }

    /**
     * Helper method to create transparent image
     */
    protected function createTransparentImage($width, $height) {
        $image = imagecreatetruecolor($width, $height);

        imagealphablending($image, true);
        $color = imagecolortransparent($image, imagecolorallocatealpha($image, 0, 0, 0, 127));
        imagefill($image, 0, 0, $color);
        imagesavealpha($image, true);

        return $image;
    }

    /**
     * Method to create image
     * This method needs to be implemented by the inheriting class
     */
    abstract protected function createImage();
}