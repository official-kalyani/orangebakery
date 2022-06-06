<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Helpers\Helper;
use App\Models\CategoryImage;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::wherenotNull('parent_id')->orderBy('id','DESC')->paginate(10);
        return view('admin.subcategory.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.subcategory.create',compact('categories'));
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
            $shop->meta_title = $request->meta_title;
            $shop->meta_desc = $request->meta_desc;
            $shop->name = $request->name;
            $shop->parent_id = $request->parent_id;
            $shop->is_normal = $request->is_normal;
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
                    $shop->parent_id = $request->parent_id;
                    $shop->slug = $slug;
                    if ($request->is_normal == "yes") {
                        $shop->is_normal = "yes";
                    }
                    else{
                        $shop->is_normal = "no";
                    } 
                    $shop->save();
                    $arr['succ'] = 1;
                    $arr['id'] = $shop->id;
                }
            } 
        }
        return response()->json($arr);
    }

    public function step2(Request $request)
    {
        $category = Category::find($request->id);
        if ($category) {
            $banners = CategoryImage::where('category_id',$category->id)->get();
            return view('admin.subcategory.step2',compact('banners'));
        }
        else{
            abort(404);
        }
    }

    public function multipleimage(Request $request)
    {
        $arr = array();
        $arr['succ'] = 0;
        if (isset($_POST['image']) && $_POST['image']!='') {
            $banner = CategoryImage::find($request->id);
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
            $banner->image = $image_name;
            if ($banner->save()) {
                $arr['succ'] = 1;
            }
        }
        echo json_encode($arr);
    }
    
    public function edit($id)
    {
        $categories = Category::whereNull('parent_id')->orderBy('id','DESC')->get();
        $data = Category::findOrFail($id);
        return view('admin.subcategory.edit',compact('data','categories'));
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
