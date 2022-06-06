<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CustomizeGallery;

class CustomizeGalleryController extends Controller
{
    public function index()
    {
     	$images = CustomizeGallery::all();  
        return view('admin.customize-gallery.index',compact('images'));
    }
    public function storeMultipleImages(Request $request)
    {
        $arr = array();
        $arr['succ'] = 0;
        if (isset($_POST['image']) && $_POST['image']!='' ) {
            @unlink('uploads/customize-gallery/'.$product->image);
            $image = $request->image;
            $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/customize-gallery/';
            if (!is_dir($user_home_dir)) {
                mkdir($user_home_dir, 0777);
            }
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/customize-gallery/'.$image_name);
            file_put_contents($path, $image);

            $product = new CustomizeGallery; 
            $product->image = $image_name;
            $product->save();

            

            $arr['succ'] = 1;
        }
        return response()->json($arr);
    }
    public function deleteMultipleImages(Request $request)
    {
        $id = $_REQUEST['id'];
        $data = CustomizeGallery::find($id);
        $delete = @unlink(public_path('uploads/customize-gallery/'.$data->image));
        if ($delete = true) {
            if ($data->delete()) {
                return back()->with('flash_success', 'Images Deleted Successfully!');
            }
        }
    }
}
