<?php

namespace App\Utils;
Class Uploader { 
    private $actual_dir;    //actual directory
    private $config;
    private $hashed_file_name;  //hashed timestamp of uploaded file's original name
    private $user_home_dir; //home dir of user
    private $uid; //user id
    public function __construct($uid) {
        //constructor imports config and sets users dir to his id in upload dir
        $this->config = Config::getInstance();
        $this->user_home_dir = $this->config->getUploadDir() . "/" . $uid;
        $this->actual_dir = "";
        $this->uid = $uid;
    }

    private function _checkUpload() {
        try {
            if (empty($_FILES)) {
                throw new Exception("This method should be called after a file upload.");
            }
        } catch (Exception $e) {
            echo $e->getMessage() . " in file " . __FILE__ . " at line " . __LINE__;
            die();
        }

        try {
            if (!in_array(strtolower($this->generateExtension()), $this->config->getMimeTypes())) {
                throw new Exception("This file extension is not allowed.");
            }
        } catch (Exception $e) {
            echo $e->getMessage() . " in file " . __FILE__ . " at line " . __LINE__;
            die();
        }
    }

    public function uploadsample($uploadpath = "public") {
        $this->s3->batch()->create_object($this->config->getBucketName(), $uploadpath . '/no-image-a.png', array(
            'fileUpload' => $this->user_home_dir . "/" . 'no-image-a.png',
            'acl' => AmazonS3::ACL_PUBLIC
        ));
        $file_upload_response = $this->s3->batch()->send();
        //print_r($file_upload_response);
        //print "<pre>";print_r($file_upload_response);exit;
    }

    private function generateExtension() {
        //return the extension of uploaded file
        return pathinfo($_FILES["Filedata"]["name"], PATHINFO_EXTENSION);
    }

    private function hashFilename() {
        $this->_checkUpload(); //check if a file was uploaded
        $this->hashed_file_name = MD5(date("F/d/Y H:i:s")) . "." . $this->generateExtension();
    }

    private function filter_file_list($arr) {
        return array_values(array_filter(array_map(array($this, 'file_path'), $arr)));
    }

    function file_path($file) {
        return !is_dir($file) ? realpath($file) : null;
    }

    public function deleteAll($directory, $empty = false) {
        //function for deleting a folder an it's contents
        if (substr($directory, -1) == "/") {
            $directory = substr($directory, 0, -1);
        }

        if (!file_exists($directory) || !is_dir($directory)) {
            return false;
        } elseif (!is_readable($directory)) {
            return false;
        } else {
            $directoryHandle = opendir($directory);

            while ($contents = readdir($directoryHandle)) {
                if ($contents != '' && $contents != '.' && $contents != '..') {
                    $path = $directory . "/" . $contents;

                    if (is_dir($path)) {
                        $this->deleteAll($path);
                    } else {
                        unlink($path);
                    }
                }
            }

            closedir($directoryHandle);

            if ($empty == false) {
                if (!rmdir($directory)) {
                    return false;
                }
            }

            return true;
        }
    }
    public function setActualDir($dir) {
        $this->actual_dir = $dir;
    }

    public function ls() {
        $bucket = $this->config->getBucketName();
        $response = $this->s3->get_object_list($bucket, array(
            'prefix' => $this->actual_dir
        ));

        echo "List of " . $this->actual_dir . "/<br/><br/>";
        $dirs = array_map(array($this, 'determineDirs'), $response);
        $dirs = array_unique($dirs);
        //var_dump($dirs);
        foreach ($dirs as $entry) {
            echo $entry . "<br/>";
        }
    }

    public function cd($dirname) {
        if ($dirname == "..") {
            $dirs = explode("/", $this->actual_dir);
            array_pop($dirs);
            $this->actual_dir = implode("/", $dirs);
        } else {
            if ($this->actual_dir == "") {
                $this->actual_dir = $dirname;
            } else {
                $this->actual_dir = $this->actual_dir . "/" . $dirname;
            }
        }
        setcookie("Uploader_dir", $this->actual_dir);
        echo $this->actual_dir;
    }

    public function mkdir($dirname) {
        if ($this->actual_dir == "") {
            $dirname = $dirname;
        } else {
            $dirname = $this->actual_dir . "/" . $dirname;
        }


        $response = $this->s3->create_object($this->config->getBucketName(), $dirname . '/', array(
            'body' => '',
            'length' => 0,
            'acl' => AmazonS3::ACL_PUBLIC
        ));

        if ($response->isOK()) {
            echo "Directory $dirname created";
        } else {
            echo "Directory $dirname already exists!";
        }
    }

    public function rm($name) {
        if ($this->actual_dir == "") {
            $path = $name;
        } else {
            $path = $this->actual_dir . "/" . $name;
        }
        $response = $this->s3->delete_object($this->config->getBucketName(), $path);

        echo ($response->isOK()) ? "Delete of $path successful" : "Delete of $path failed";
    }

    public function getLink($name) {
        if ($this->actual_dir == "") {
            $path = $name;
        } else {
            $path = $this->actual_dir . "/" . $name;
        }
        echo $this->s3->get_object_url($this->config->getBucketName(), $path);
    }

    public function uploadAttachments($filename, $fileinfo, $uploadpath = "uploads/", $object_id, $crop_arr,$randomtime, $org_width, $org_height) {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $_FILES["Filedata"] = $fileinfo;
        $this->_checkUpload();  //check if a file was uploaded
        $this->hashFilename();  //hash actual timestamp to be new file name
        $this->image = new Images();
        if ($fileinfo["error"] > 0) {
            echo "Return Code: " . $fileinfo["error"] . "<br />";
        } else {
            if (!is_dir($this->user_home_dir)) {
                mkdir($this->user_home_dir, 0777);
            }
            if ($ext == "jpg" || $ext == "gif" || $ext == "png" || $ext == 'jpeg' || $ext == 'svg' || 'jfif') {
                $sExt = $this->jCropImage($fileinfo, $randomtime, $this->user_home_dir, $org_width, $org_height, $quality = 90, $crop_arr);
                $this->image->load($this->user_home_dir . '/' . $randomtime . $sExt);
            }
            //chmod($this->user_home_dir . '/' . $filename, 0775);
            // $client = new \Aws\S3\S3Client(array(
            //     'credentials' => [
            //         'key'    => 'AKIAJWGRKDHR5BTXHO2Q',
            //         'secret' => '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',
            //     ],
            //     'region'  => 'ap-south-1',
            //     'version' => '2006-03-01',
            // ));
            // $path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'.$object_id; // change "upload_to_s3" to the directory you'd like to upload
            // $dest = 's3://dogsofamerica/'.$object_id; // change "uploads" to where ever you'd like your directory to be
            // $manager = new \Aws\S3\Transfer($client, $path, $dest);
            // $manager->transfer();
            
            // $this->deleteDir($this->user_home_dir);
            return @$filename;
        }
    }
    public function deleteDir($dir)
    {
       if (substr($dir, strlen($dir)-1, 1) != '/')
           $dir .= '/';

       if ($handle = opendir($dir))
       {
           while ($obj = readdir($handle))
           {
               if ($obj != '.' && $obj != '..')
               {
                   if (is_dir($dir.$obj))
                   {
                       if (!$this->deleteDir($dir.$obj))
                           return false;
                   }
                   elseif (is_file($dir.$obj))
                   {
                       if (!unlink($dir.$obj))
                           return false;
                   }
               }
           }
           closedir($handle);
           if (!@rmdir($dir))
               return false;
           return true;
       }
       return false;
    }
    public function jCropImage($files, $time, $uploadpath = "uploads/", $width = 100, $height = 100, $quality = 90, $crop_arr = array()) {
        if (!empty($files['name'])) {
            if (is_uploaded_file($files['tmp_name'])) {
                // new unique filename
                $sTempFileName = $uploadpath . '/' . $time;
                // move uploaded file into cache folder
                move_uploaded_file($files['tmp_name'], $sTempFileName);
                // change file permission to 644
                @chmod($sTempFileName, 0644);
                if (file_exists($sTempFileName) && filesize($sTempFileName) > 0) {
                    $aSize = getimagesize($sTempFileName); // try to obtain image info
                    if (!$aSize) {
                        @unlink($sTempFileName);
                        return;
                    }
                    // check for image type
                    switch ($aSize[2]) {                        
                        case IMAGETYPE_JPEG:
                            $sExt = '.jpg';
                            // create a new image from file 
                            $vImg = @imagecreatefromjpeg($sTempFileName);
                            break;
                        case IMAGETYPE_GIF:
                            $sExt = '.gif';
                            // create a new image from file 
                            $vImg = @imagecreatefromgif($sTempFileName);
                            break;
                        case IMAGETYPE_PNG:
                            $sExt = '.png';
                            // create a new image from file 
                            $vImg = @imagecreatefrompng($sTempFileName);
                            break;
                        default:
                            $vImg = @imagecreatefromjpeg($sTempFileName);
                            @unlink($sTempFileName);
                            return;
                    }
                    
                    // create a new true color image
                    $vDstImg = @imagecreatetruecolor($width, $height);
                    imagecopyresampled($vDstImg, $vImg, 0, 0, (int) $crop_arr['x1'], (int) $crop_arr['y1'], $width, $height, (int) $crop_arr['w'], (int) $crop_arr['h']);
                        $sResultFileName = $sTempFileName . $sExt;
                    imagejpeg($vDstImg, $sResultFileName, $quality);
                    @unlink($sTempFileName);
                    return $sExt;
                }
            }
        }
    }

    public function test()
    {
        echo "Uploader";
    }
    
   

}

?>
