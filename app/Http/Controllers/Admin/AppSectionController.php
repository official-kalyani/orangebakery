<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SectionItem;
use App\Models\Product;

class AppSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Section::orderBy('id','DESC')->paginate(10);
        return view('admin.app-section.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::whereNull('parent_product_id')->get();
        return view('admin.app-section.create',compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has_product_id == 1) {
            $this->validate($request, [
                'name' => 'required',
                'type' => 'required',
            ]);
            $section = new Section;
            $section->name = $request->name;
            $section->type = $request->type;
            $section->save();

            foreach ($request->product_id as $val) {
                $section_item = new SectionItem;
                $section_item->section_id = $section->id;
                $section_item->product_id = $val;
                $section_item->save();
            }
        
            return back()->with('flash_success', 'App Section Created Successfully!');

        }
        else{
            $arr = array();
            $arr['succ'] = 0;

            if (isset($_POST['id']) && $_POST['id'] !='') {
                $section = Section::find($_POST['id']);
                if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
                
                    @unlink('uploads/offers/'.$shop->image);
                    $image = $request->image;
                    $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/offers/';
                    if (!is_dir($user_home_dir)) {
                        mkdir($user_home_dir, 0777);
                    }
                    list($type, $image) = explode(';', $image);
                    list(, $image)      = explode(',', $image);
                    $image = base64_decode($image);
                    $image_name= time().'.png';
                    $path = public_path('uploads/offers/'.$image_name);
                    file_put_contents($path, $image);

                    
                    
                    $section->image = $image_name;
                    
                }
                $section->name = $request->name;
                $section->save();
                $arr['succ'] = 1;
            }
            else
            { 
                if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
                
                    @unlink('uploads/offers/'.$shop->image);
                    $image = $request->image;
                    $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/offers/';
                    if (!is_dir($user_home_dir)) {
                        mkdir($user_home_dir, 0777);
                    }
                    list($type, $image) = explode(';', $image);
                    list(, $image)      = explode(',', $image);
                    $image = base64_decode($image);
                    $image_name= time().'.png';
                    $path = public_path('uploads/offers/'.$image_name);
                    file_put_contents($path, $image);

                    $section = new Section;
                    $section->name = $request->name;
                    $section->type = $request->type;
                    $section->image = $image_name;
                    $section->save();

                    $section_item = new SectionItem;
                    $section_item->section_id = $section->id;
                    $section_item->save();
                    $arr['succ'] = 1;
                }
            }
        }
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
        $data = Section::findOrFail($id);
        if ($data->type == "Product") {
            $products = Product::whereNull('parent_product_id')->get();
            $section_items = SectionItem::where('section_id',$data->id)->get();
            foreach ($products as $product) {
                $array1[] = $product->id;
            }
            foreach ($section_items as $section_item) {
               @$array2[] = $section_item->product_id;
            }
            @$fliterProducts = @array_diff($array1, @$array2);
        }
        
        return view('admin.app-section.edit',compact('data','products','section_items','fliterProducts'));
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
        if ($request->has_product_id == 1) {
            
            $this->validate($request, [
                'name' => 'required',
                //'product_id' => 'required',
            ]);

            $section = Section::find($id);
            $section->name = $request->name;
            $section->save();

            if (isset($request->product_id) && $request->product_id !='') {
                foreach ($request->product_id as $val) {
                    $section_item = new SectionItem;
                    $section_item->section_id = $section->id;
                    $section_item->product_id = $val;
                    $section_item->save();
                }
            }

            return back()->with('flash_success', 'App Section Updated Successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section_item = SectionItem::where('section_id',$id)->first();
        $section_item->delete();
        $section = Section::findOrFail($id);
        @unlink('uploads/offers/'.$section->image);
        $section->delete();
        return back()->with('flash_success', 'Section Deleted Successfully!');
    }

    public function deleteSectionProducts(Request $request)
    {
        $section_item = SectionItem::where('id',$request->id)->delete();
        return back()->with('flash_success', 'Section Product Deleted Successfully!');
    }
}
