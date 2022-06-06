<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redis;


// require_once "Image.class.php";
// require_once "Config.class.php";
// require_once "Uploader.class.php";

class Helper
{
    /**
     * @param int $user_id User-id
     *
     * @return string
     **/
    public static function getBlogUrl($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = strtolower($string); // Convert to lowercase
 
        return $string;
    }
}