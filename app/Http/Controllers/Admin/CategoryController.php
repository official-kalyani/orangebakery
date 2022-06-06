<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryImage;
use App\Helpers\Helper;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $data = Category::whereNull('parent_id')->orderBy('id','DESC')->paginate(10);
        return view('admin.category.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $arr = array();
        $arr['succ'] = 0; 

        if(isset($_POST['id']) && $_POST['id']!='') 
        {
            $id = $_REQUEST['id'];
            $shop = Category::find($id);
            if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            
                @unlink('uploads/category/'.$shop->image);
                $image = $request->image;
                $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/category/';
                if (!is_dir($user_home_dir)) {
                    mkdir($user_home_dir, 0777);
                }
                list($type, $image) = explode(';', $image);
                list(, $image)      = explode(',', $image);
                $image = base64_decode($image);
                $image_name= time().'.png';
                $path = public_path('uploads/category/'.$image_name);
                file_put_contents($path, $image);
                $shop->image = $image_name;
            }
            $shop->name = $request->name;
            $shop->meta_title = $request->meta_title;
            $shop->meta_desc = $request->meta_desc;
            $shop->save();
            $arr['succ'] = 1;
        }
        else
        {
            $shop = new Category;
            $slug = Helper::getBlogUrl($request->name);
            if (Category::where('slug', '=', $slug)->count() > 0)
            {
                $arr['succ'] = 2;
            }
            else{
                if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            
                    @unlink('uploads/category/'.$shop->image);
                    $image = $request->image;
                    $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/category/';
                    if (!is_dir($user_home_dir)) {
                        mkdir($user_home_dir, 0777);
                    }
                    list($type, $image) = explode(';', $image);
                    list(, $image)      = explode(',', $image);
                    $image = base64_decode($image);
                    $image_name= time().'.png';
                    $path = public_path('uploads/category/'.$image_name);
                    file_put_contents($path, $image);
                    $shop->image = $image_name;
                    $shop->name = $request->name;
                    $shop->meta_title = $request->meta_title;
                    $shop->meta_desc = $request->meta_desc;
                    $shop->slug = $slug; 
                    $shop->save();
                    $arr['succ'] = 1;
                    $arr['id'] = $shop->id;
                }
            } 
        }
        return response()->json($arr);
    }

    public function multipleimage(Request $request)
    {
        $arr = array();
        $arr['succ'] = 0;
        if (isset($_POST['image']) && $_POST['image']!='') {
            $banner = new CategoryImage;
            $image = $request->image;
            $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/category/';
            if (!is_dir($user_home_dir)) {
                mkdir($user_home_dir, 0777);
            }
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/category/'.$image_name);
            file_put_contents($path, $image);
            $banner->category_id = $request->id;
            $banner->image = $image_name;
            $banner->imagename = $request->imagename; 

            if ($banner->save()) {
                $arr['succ'] = 1;
            }
        }
        echo json_encode($arr);
    }

    public function deleteSliders(Request $request)
    {
        $id = $_REQUEST['id'];
        $data = CategoryImage::find($id);
        @$delete = unlink(public_path('uploads/category/'.$data->image));
        if ($delete = true) {
            if ($data->delete()) {
                return back()->with('flash_success', 'Images Deleted Successfully!');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function step2(Request $request)
    {
        $category = Category::find($request->id);
        if ($category) {
            $banners = CategoryImage::where('category_id',$category->id)->get();
            return view('admin.category.step2',compact('banners'));
        }
        else{
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Category::findOrFail($id);
        return view('admin.category.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = Category::findOrFail($id);
        @unlink('uploads/category/'.$shop->image);
        $shop->delete();
        return back()->with('flash_success', 'Category Deleted Successfully!');
    }
}
