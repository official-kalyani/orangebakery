<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
// namespace App\Http\Controllers;

use App\Exports\TestimonialExport;
use Maatwebsite\Excel\Facades\Excel;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Testimonial::all();
        return view('admin.testimonials.index',compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banners = Testimonial::all();
        return view('admin.testimonials.create',compact('banners'));
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
            $testimonial = Testimonial::find($_POST['id']);
        }
        else 
        {
            $testimonial = new Testimonial;
        }
        if (isset($_POST['image']) && $_POST['image']!='' && isset($_POST['has_image']) && $_POST['has_image'] == 1) {
            $image = $request->image;
            $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/testimonials/';
            if (!is_dir($user_home_dir)) {
                mkdir($user_home_dir, 0777);
            }
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/testimonials/'.$image_name);
            file_put_contents($path, $image);
            $testimonial->image = $image_name;
            
        }
        if ($request->show_in_website_home == "yes") {
            $testimonial->show_in_website_home = $request->show_in_website_home; 
        }
        else{
            $testimonial->show_in_website_home = "no"; 
        }
        if ($request->show_in_app_home == "yes") {
            $testimonial->show_in_app_home = $request->show_in_app_home; 
        }
        else{
            $testimonial->show_in_app_home = "no"; 
        }
        $testimonial->title = $request->title; 
        $testimonial->description = $request->description; 

        if ($testimonial->save()) {
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
        $data = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit',compact('data'));
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
    public function deleteTestimonials(Request $request)
    {
        $id = $request->id;
        $banner = Testimonial::findOrFail($id);
        @unlink('uploads/testimonials/'.$banner->image);
        $banner->delete();
        return redirect()->back();
    }

    public function export() 
    {
        return Excel::download(new TestimonialExport, 'users.xlsx');
    }
   
    function excel()
    {
     // $customer_data = DB::table('testimonials')->get()->toArray();
     $customer_data = Testimonial::all();
     $customer_array[] = array('title', 'description', 'show_in_app_home', 'show_in_website_home');
     foreach($customer_data as $customer)
     {
      $customer_array[] = array(
       'title'  => $customer->title,
       'description'   => $customer->description,
       'show_in_app_home'    => $customer->show_in_app_home,
       'show_in_website_home'  => $customer->show_in_website_home
      );
     }
     Excel::create('Customer Data', function($excel) use ($customer_array){
      $excel->setTitle('Customer Data');
      $excel->sheet('Customer Data', function($sheet) use ($customer_array){
       $sheet->fromArray($customer_array, null, 'A1', false, false);
      });
     })->download('xlsx');
      return redirect('admin/testimonials');
    }
}
