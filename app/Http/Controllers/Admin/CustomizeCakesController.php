<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomizeCake;

class CustomizeCakesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = CustomizeCake::all();
        return view('admin.customize-cakes.index',compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banners = CustomizeCake::all();
        return view('admin.customize-cakes.create',compact('banners'));
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
        if (isset($_POST['image']) && $_POST['image']!='') {
            $banner = new CustomizeCake;
            $image = $request->image;
            $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/customize-cakes/';
            if (!is_dir($user_home_dir)) {
                mkdir($user_home_dir, 0777);
            }
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/customize-cakes/'.$image_name);
            file_put_contents($path, $image);
            $banner->image = $image_name;
            if ($banner->save()) {
                $arr['succ'] = 1;
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
        //
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
        $banner = CustomizeCake::findOrFail($id);
        @unlink('uploads/customize-cakes/'.$banner->image);
        $banner->delete();
        return redirect()->back();
    }
}
