<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Slider::all();
        return view('admin.sliders.index',compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banners = Slider::all();
        return view('admin.sliders.create',compact('banners'));
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
            $banner = Slider::find($_POST['id']);
        }
        else 
        {
            $banner = new Slider;
        }
        if (isset($_POST['image']) && $_POST['image']!='' && isset($_POST['has_image']) && $_POST['has_image'] == 1) {
            $image = $request->image;
            $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/sliders/';
            if (!is_dir($user_home_dir)) {
                mkdir($user_home_dir, 0777);
            }
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/sliders/'.$image_name);
            file_put_contents($path, $image);
            $banner->image = $image_name;
            
        }
        $banner->imagename = $request->imagename; 
        if ($request->show_in_website_home == "yes") {
            $banner->show_in_website_home = $request->show_in_website_home; 
        }
        else{
            $banner->show_in_website_home = "no"; 
        }
        if ($request->show_in_app_home == "yes") {
            $banner->show_in_app_home = $request->show_in_app_home; 
        }
        else{
            $banner->show_in_app_home = "no"; 
        }
        if ($banner->save()) {
            $arr['succ'] = 1;
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
        $data = Slider::findOrFail($id);
        return view('admin.sliders.edit',compact('data'));
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
    public function destroy(Request $request)
    {
        $id = $request->id;
        $banner = Slider::findOrFail($id);
        @unlink('uploads/sliders/'.$banner->image);
        $banner->delete();
        return redirect()->back();
    }
}
