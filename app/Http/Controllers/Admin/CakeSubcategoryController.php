<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CakeCategory;
use App\Helpers\Helper;

class CakeSubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = CakeCategory::wherenotNull('parent_id')->orderBy('id','DESC')->paginate(10);
        return view('admin.cake-subcategory.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CakeCategory::whereNull('parent_id')->orderBy('id','DESC')->get();
        return view('admin.cake-subcategory.create',compact('categories'));
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
            $shop = CakeCategory::find($id);
            if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            
                @unlink('uploads/cakecategory/'.$shop->image);
                $image = $request->image;
                $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/cakecategory/';
                if (!is_dir($user_home_dir)) {
                    mkdir($user_home_dir, 0777);
                }
                list($type, $image) = explode(';', $image);
                list(, $image)      = explode(',', $image);
                $image = base64_decode($image);
                $image_name= time().'.png';
                $path = public_path('uploads/cakecategory/'.$image_name);
                file_put_contents($path, $image);
                $shop->image = $image_name;
            }
            $shop->meta_title = $request->meta_title;
            $shop->meta_desc = $request->meta_desc;
            $shop->name = $request->name;
            $shop->parent_id = $request->parent_id;
            $shop->save();
            $arr['succ'] = 1;
        }
        else
        {
            $shop = new CakeCategory;
            $slug = Helper::getBlogUrl($request->name);
            if (CakeCategory::where('slug', '=', $slug)->count() > 0)
            {
                $arr['succ'] = 2;
            }
            else{
                if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            
                    @unlink('uploads/cakecategory/'.$shop->image);
                    $image = $request->image;
                    $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/cakecategory/';
                    if (!is_dir($user_home_dir)) {
                        mkdir($user_home_dir, 0777);
                    }
                    list($type, $image) = explode(';', $image);
                    list(, $image)      = explode(',', $image);
                    $image = base64_decode($image);
                    $image_name= time().'.png';
                    $path = public_path('uploads/cakecategory/'.$image_name);
                    file_put_contents($path, $image);
                    $shop->image = $image_name;
                    $shop->name = $request->name;
                    $shop->meta_title = $request->meta_title;
                    $shop->meta_desc = $request->meta_desc;
                    $shop->parent_id = $request->parent_id;
                    $shop->slug = $slug; 
                    $shop->save();
                    $arr['succ'] = 1;
                }
            } 
        }
        echo json_encode($arr);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = CakeCategory::whereNull('parent_id')->orderBy('id','DESC')->get();
        $data = CakeCategory::findOrFail($id);
        return view('admin.cake-subcategory.edit',compact('data','categories'));
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
        $shop = CakeCategory::findOrFail($id);
        @unlink('uploads/category/'.$shop->image);
        $shop->delete();
        return back()->with('flash_success', 'Cake Sub Category Deleted Successfully!');
    }
}
