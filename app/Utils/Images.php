<?php
namespace App\Utils;
class Images {

    private $image;
    private $image_type;
    private $config;

    public function __construct() {
        $this->config = Config::getInstance();
    }

    public function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    public function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 100, $permissions = null) {
        //$compression = Poster::model()->getCompressionSize();
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename, 0);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    public function output($image_type = IMAGETYPE_JPEG) {
        $compression = Poster::model()->getCompressionSize();
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, '', $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    public function getWidth() {
        return imagesx($this->image);
    }

    public function getHeight() {
        return imagesy($this->image);
    }

    public function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    public function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    public function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    public function resize($width, $height) {
        /* New code */
        $nWidth = (int) $width;
        $nHeight = (int) $height;
        $newImg = imagecreatetruecolor($nWidth, $nHeight);
        //imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        //imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
        imagefill($newImg, 0, 0, $transparent);
        imagecopyresampled($newImg, $this->image, 0, 0, 0, 0, $nWidth, $nHeight, (int) $this->getWidth(), (int) $this->getHeight());
        $this->image = $newImg;
    }

}

?>