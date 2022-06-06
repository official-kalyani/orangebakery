<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Intervention\Image\Facades\Image as Image;
use Auth;
use App\Models\Obcoin;

use App\Utils\Config;
use App\Utils\Images;
use App\Utils\Uploader;
use Storage;

$obcoin = Obcoin::where('id',1)->first();
define('CBCOIN_PRECENTAGE',$obcoin ->precentage);
define('CBCOIN_PAISA',$obcoin ->paisa);
define('DELIVERY_CHARGE',$obcoin ->deliveryCharge);

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function uploadFiles($files,$object_id, $crop_arr, $randomtime, $org_width, $org_height) {
        if (!empty($files['name'])) {
        $file_ext = pathinfo($files['name'], PATHINFO_EXTENSION);
        $filename = $randomtime . '.' .$file_ext;
            if ($filename) {
            $this->uploadAttachments($filename, $files, $object_id,$crop_arr,$randomtime, $org_width, $org_height);
            }
        }
      return $filename;
    }
    function uploadAttachments($filename,$fileinfo,$object_id,$crop_arr,$randomtime, $org_width, $org_height) {
        // echo $_SERVER['DOCUMENT_ROOT'].'/'.'uploads';
        // exit();
        defined('BASEPATH') ? '' : define("BASEPATH", dirname(__FILE__) . "/..");
        $config = Config::getInstance();
        $config->setUploadDir($_SERVER['DOCUMENT_ROOT'].'/'.'uploads'); //path for images uploaded
        $config->setMimeTypes(array("jpg", "gif", "png", "jpeg", "jfif")); //allowed extensions
        $uploadpath = "uploads/";
        $uploader = new Uploader($object_id);
        $ret = $uploader->uploadAttachments($filename,$fileinfo,$uploadpath,$object_id,$crop_arr,$randomtime, $org_width, $org_height);
        //$uploader->deleteAll($_SERVER['DOCUMENT_ROOT'].'/'.'uploads/'.$object_id.'/');
        return $ret;
    }
}
