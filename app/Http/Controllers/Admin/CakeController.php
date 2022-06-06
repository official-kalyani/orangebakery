<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cake;
use App\Models\CakeCategory;
use App\Models\CakePrice;
use App\Models\CakeImage;
use App\Models\Occasion;
use App\Models\Flavour;
use App\Helpers\Helper;


class CakeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Cake::whereNull('parent_cake_id')->orderBy('id','DESC')->paginate(10);
        return view('admin.cakes.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = CakeCategory::whereNull('parent_id')->get();
        $occasions = Occasion::all();
        $flavours = Flavour::all();
        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $cake = Cake::find($id);
            $data = CakePrice::where('cake_id',$cake->id)->get();
            $occasion_id = explode(',', $cake->occasion);
            $flavour_id = explode(',', $cake->flavour);
            return view('admin.cakes.create',compact('cake','categories','data','occasions','flavours','occasion_id','flavour_id')); 
        }
        else{
            return view('admin.cakes.create',compact('categories','occasions','flavours')); 
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->id) && $request->id !='') {
            $this->validate($request, [
                'name' => 'required',
                'flavour' => 'required', 
                'occasion' => 'required',
                'description' => 'required',
                'stock_quantity' => 'required',
            ]);
        }
        else
        {
            $this->validate($request, [
                'name' => 'required',
                'flavour' => 'required', 
                'occasion' => 'required',
                'description' => 'required',  
                'price' => 'required',
                'weight' => 'required',
                'stock_quantity' => 'required',
            ]);
        }
        

        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $cake = Cake::find($id);
        }
        else
        {
            $cake = new Cake;
        }
        $cake->name = $request->name;
        $cake->flavour = implode($request->flavour,',');
        $cake->occasion = implode($request->occasion,',');
        $cake->description = $request->description;
        $cake->meta_title = $request->meta_title;
        $cake->meta_desc = $request->meta_desc;
        $cake->stock_quantity = $request->stock_quantity;
        $cake->save();
        $cake->slug = Helper::getBlogUrl($cake->name).'-cakes-'.$cake->id;
        $cake->save();

        if (isset($request->id) && $request->id !='') {

        }
        else
        {
            $data = $request->all();
            $price = $data['price'];
            $weight = $data['weight'];

            foreach($price as $key => $input) {
                $cake_price = new CakePrice();
                $cake_price->price = $price[$key];
                $cake_price->weight = $weight[$key];
                $cake_price->cake_id = $cake->id;
                $cake_price->save();
            }
        }

        if (isset($request->id) && $request->id !='') {
            return back()->with('flash_success', 'Cake Updated Successfully!');
        }
        else{
            return redirect('/admin/cakes/create/step2?id='.$cake->id);
        }
        
    }

    public function addMorePriceWeight(Request $request)
    {
        $data = $request->all();
        $price = $data['price'];
        $weight = $data['weight'];

        foreach($price as $key => $input) {
            $cake_price = new CakePrice();
            $cake_price->price = $price[$key];
            $cake_price->weight = $weight[$key];
            $cake_price->cake_id = $request->id;
            $cake_price->save();
        }
        return back()->with('flash_success', 'Cake Price Successfully!');
    }

    public function updatePriceWeight(Request $request)
    {
        $id = $request->id;
        $cake = CakePrice::find($id);
        $cake->price = $request->price;
        $cake->weight = $request->weight;
        $cake->save();
        return back()->with('flash_success', 'Cake Price Updated Successfully!');
    }

    public function deletePriceWeight(Request $request)
    {
        $cake = CakePrice::findOrFail($request->id);
        $cake->delete();
        return back()->with('flash_success', 'Cake Deleted Successfully!');
    }

    public function step2(Request $request)
    {
        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $images = CakeImage::where('cake_id',$id)->get();
            return view('admin.cakes.step2',compact('images'));
        }
        else{
            return view('admin.cakes.step2');
        }
        return view('admin.cakes.step2');
    }

    public function storeMultipleImages(Request $request)
    {
        $arr = array();
        $arr['succ'] = 0;
        if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            @unlink('uploads/cakes/'.$product->image);
            $image = $request->image;
            $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/cakes/';
            if (!is_dir($user_home_dir)) {
                mkdir($user_home_dir, 0777);
            }
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/cakes/'.$image_name);
            file_put_contents($path, $image);

            $product = new CakeImage; 
            $product->cake_id = $request->id;

            $data = CakeImage::where('cake_id',$request->id)->first();
            if ($data) {
                $product->is_featured = 0;
            }else{
                $product->is_featured = 1;
            }
            $product->image = $image_name;
            $product->save();
            $arr['succ'] = 1;
            echo json_encode($arr);           
        }
    }

    public function deleteMultipleImages(Request $request)
    {
        $id = $_REQUEST['id'];
        $data = CakeImage::find($id);
        $delete = unlink(public_path('uploads/cakes/'.$data->image));
        if ($delete = true) {
            if ($data->delete()) {
                return back()->with('flash_success', 'Images Deleted Successfully!');
            }
        }
    }

    public function makeFeatureImage(Request $request)
    {
        $arr['succ'] = 0;
        $id = $request->id;
        $imageId = $request->imageId;

        CakeImage::where('cake_id', $id)->update([
            'is_featured' => 0
        ]);

        $data = CakeImage::where('cake_id',$id)->where('id',$imageId)->first();
        $data->is_featured = 1;
        if ($data->save()) {
            $arr['id'] = $id;
            $arr['succ'] = 1;
        }
        echo json_encode($arr); 

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
    public function destroy($id)
    {
        $data = Cake::findOrFail($id);

        $cake_price = CakePrice::where('cake_id',$id);
        $cake_price->delete();

        $cake_images = CakeImage::where('cake_id',$id);
        foreach ($cake_images as $row) {
            @unlink(public_path('uploads/cakes/'.$row->image));
        }
        $cake_images->delete();
        
        $data->delete();
        return back()->with('flash_success', 'Cakes Deleted  Successfully!');
    }
}
