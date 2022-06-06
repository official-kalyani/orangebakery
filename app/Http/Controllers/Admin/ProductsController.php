<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\DeliveryCharges;
use App\Models\ProductPrice;
use App\Models\Occasion;
use App\Models\CustomizeFlavour;
use App\Helpers\Helper;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $occasions = Occasion::all();
        $product_tbl = Product::whereNull('parent_product_id');
        if (isset($request->category_id) && $request->category_id!='' && $request->category_id!='all') {
            $product_tbl->where('category_id',$request->category_id);
        }
        if (isset($request->subcategory_id) && $request->subcategory_id!='' && $request->subcategory_id!='all') {
            $product_tbl->where('subcategory_id',$request->subcategory_id);
        }
        if (isset($request->occasion_id) && $request->occasion_id!='' && $request->occasion_id!='all') {
            $product_tbl->where('occasion_id',$request->occasion_id);
        }
        $data = $product_tbl->orderBy('id','desc')->paginate(20);
        return view('admin.products.index',compact('data','occasions'));
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::whereNull('parent_id')->get();
        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $products = Product::find($id);
            return view('admin.products.create',compact('categories','products')); 
        }
        else{
            return view('admin.products.create',compact('categories')); 
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
        // echo "string";exit();
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'description' => 'required',
            'meta_title' => 'required',
            'meta_desc' => 'required',
        ]);
        $slug = Helper::getBlogUrl($request->name);
        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $shop = Product::find($id);
        }
        else
        {
            if (Product::where('slug', $slug)->count() > 0)
            {
                return back()->with('flash_failure', 'Product Name Already exits!');
            }
            else
            {
                $shop = new Product;
            }
        } 
        $shop->name = $request->name;
        $shop->category_id = $request->category_id;
        $shop->description = $request->description;
        $shop->meta_title = $request->meta_title;
        $shop->meta_desc = $request->meta_desc;
        $shop->is_photocake = $request->is_photocake;
        $shop->is_customize = $request->is_customize;
        $shop->shape = $request->shape;

        $shop->save();
        $shop->slug = $slug;
        $shop->save();
        return redirect('/admin/products/create/step2?id='.$shop->id);
    }

    public function step2(Request $request)
    {
        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $products = Product::find($id);
            $product_price = ProductPrice::where('product_id',$id)->first();
            $subcategories = Category::wherenotNull('parent_id')->where('parent_id',$products->category_id)->get();
            $occasions = Occasion::all();
            $images = ProductImage::where('product_id',$id)->get();
            @$subcategory_id = explode(',', $products->subcategory_id);
            @$occasion_id = explode(',', $products->occasion_id);
            $data = ProductPrice::where('product_id',$id)->get();
            $checkCategory = Product::whereNull('parent_product_id')->where('category_id',1)->where('id',$id)->first();
            // echo "<pre>";
            // echo count($subcategories);
            // exit(); 
            $flavour = CustomizeFlavour::where('product_id',$id)->get();
            //  echo "<pre>";
            // echo count($flavour);
            // exit(); 
            return view('admin.products.step2',compact('images','subcategories','subcategory_id','products','product_price','data','checkCategory','occasions','occasion_id','flavour'));
        }
        else{
            return view('admin.products.step2');
        }
    }

    public function saveStep2(Request $request)
    {
        if ($request->has_subcategory == 1) {
            $this->validate($request, [
                'subcategory_id' => 'required',
                'occasion_id' => 'required'
            ]);
        }
        else
        {
            $this->validate($request, [
                'mrp_price' => 'required',
                'price' => 'required',
                'stock_quantity' => 'required'
            ]);
        }
        

        if (isset($request->product_id) && $request->product_id !='') {
            if (isset($request->has_subcategory) && $request->has_subcategory !='') {
                if ($request->has_subcategory == 1) {
                    $products = Product::find($request->product_id);
                    $products->subcategory_id = $request->subcategory_id;
                    $products->occasion_id = implode(',',$request->occasion_id);
                    $products->save();
                    $data = $request->all();
                    $mrp_price = $data['mrp_price'];
                    $selling_price = $data['selling_price'];
                    $weight = $data['weight'];
                    if (count($mrp_price)) {
                        foreach($mrp_price as $key => $input) {
                            if ($mrp_price[$key]!=null && $selling_price[$key]!=null && $weight[$key]!=null) {
                                $cake_price = new ProductPrice();
                                $cake_price->mrp_price = $mrp_price[$key];
                                $cake_price->price = $selling_price[$key];
                                $cake_price->weight = $weight[$key];
                                $cake_price->product_id = $request->product_id;
                                $cake_price->category_id = $request->category_id;
                                $cake_price->save();
                            }
                        }
                    }
                }
                if ($request->has_subcategory == 0) {
                    $product_id = $request->product_id;
                    $products = Product::find($product_id);
                    $products->stock_quantity = $request->stock_quantity;
                    $products->save();

                    $cake_price = ProductPrice::where('product_id',$product_id)->first();

                    if ($cake_price) {
                        $cake_price = ProductPrice::where('product_id',$product_id)->first();
                    }
                    else{
                        $cake_price = new ProductPrice();
                    }
                    $cake_price->product_id = $product_id;
                    $cake_price->category_id = $request->category_id;
                    $cake_price->price = $request->price;
                    $cake_price->mrp_price = $request->mrp_price;
                    $cake_price->show_price = 1;
                    $cake_price->save();
                }
                return back()->with('flash_success', 'Product Updated Successfully!');
            }
        }
    }

    public function updatePriceWeight(Request $request)
    {
        $id = $request->id;
        $cake = ProductPrice::find($id);
        $cake->mrp_price = $request->mrp_price;
        $cake->price = $request->price;
        $cake->weight = $request->weight;       
        if($request->show_price){
          ProductPrice::where('product_id',$cake->product_id)->update(array('show_price'=>0));  
          $cake->show_price = $request->show_price;
        }
        $cake->save();
        return back()->with('flash_success', 'Cake Price Updated Successfully!');
    }
    public function saveFlavours(Request $request)
    {
            // print "<pre>";
            // print $request;exit();
        $this->validate($request, [
                'flavourname' => 'required',
                // 'flavourimage' => 'required',
                 'image' => 'required',
            ]);
        // print $request;exit();
        $flavour = new CustomizeFlavour();
        $flavour->product_id = $request->product_id; 
        $flavour->name = $request->flavourname; 
        $arr = array();
        $arr['succ'] = 0;
        if (isset($_POST['image']) && $_POST['image']!='') {
            // @unlink('uploads/flavour/'.$flavour->image);
            $image = $request->image;
            $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/flavour/';
            if (!is_dir($user_home_dir)) {
                mkdir($user_home_dir, 0777);
            }
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/flavour/'.$image_name);
            file_put_contents($path, $image);

            

            
            $flavour->image = $image_name;
         

           
        }

        // if ($request->hasfile('flavourimage') ) {
            
        //     $imageName = time().'.'.$request->flavourimage->extension();     
        //      $request->flavourimage->move(public_path('uploads/flavour/'), $imageName);
            
                  
        //     $flavour->image = $imageName;    
        // }
       $flavour->save();
         // dd($flavour);

        return back()->with('flash_success', 'Flavour inserted Successfully!');
    }

    public function deletePriceWeight(Request $request)
    {
        $cake = ProductPrice::findOrFail($request->id);
        $cake->delete();
        return back()->with('flash_success', 'Cake Deleted Successfully!');
    }

    public function storeMultipleImages(Request $request)
    {
        $arr = array();
        $arr['succ'] = 0;
        if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            @unlink('uploads/product/'.$product->image);
            $image = $request->image;
            $user_home_dir = $_SERVER['DOCUMENT_ROOT'].'/'.'uploads/product/';
            if (!is_dir($user_home_dir)) {
                mkdir($user_home_dir, 0777);
            }
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/product/'.$image_name);
            file_put_contents($path, $image);

            $product = new ProductImage; 
            $product->product_id = $request->id;

            $data = ProductImage::where('product_id',$request->id)->first();
            if ($data) {
                $product->is_featured = 0;
                $product->customize = 0;
            }else{
                $product->is_featured = 1;
                $product->customize = 0;
            }
            $product->images = $image_name;
            $product->category_id = $request->category_id;
            $product->save();

            $parentProduct = Product::find($request->id);
            $parentProduct->steps_completed = 3;
            $parentProduct->save();

            $arr['succ'] = 1;
        }
        return response()->json($arr);
    }

    public function deleteMultipleImages(Request $request)
    {
        $id = $_REQUEST['id'];
        $data = ProductImage::find($id);
        $delete = @unlink(public_path('uploads/product/'.$data->images));
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

        ProductImage::where('product_id', $id)->update([
            'is_featured' => 0
        ]);

        $data = ProductImage::where('product_id',$id)->where('id',$imageId)->first();
        $data->is_featured = 1;
        if ($data->save()) {
            $arr['id'] = $id;
            $arr['succ'] = 1;
        }
        echo json_encode($arr); 

    }

    public function makeCustomizeImage(Request $request)
    {
        $arr['succ'] = 0;
        $id = $request->id;
        $imageId = $request->imageId;

        ProductImage::where('product_id', $id)->update([
            'customize' => 0
        ]);

        $data = ProductImage::where('product_id',$id)->where('id',$imageId)->first();
        $data->customize = 1;
        if ($data->save()) {
            $arr['id'] = $id;
            $arr['succ'] = 1;
        }
        echo json_encode($arr); 

    }

    public function edit($id)
    {
        $categories = Category::whereNull('parent_id')->orderBy('id','DESC')->get();
        $data = Product::findOrFail($id);
        return view('admin.products.edit',compact('data','categories'));
    }

    public function destroy($id)
    {
        $data = Product::findOrFail($id);
        $product_images = ProductImage::where('product_id',$id)->get();
        foreach ($product_images as $row) {
            @unlink(public_path('uploads/product/'.$row->images));
            $row->delete();
        }
        $data->delete();
        return back()->with('flash_success', 'Product Deleted  Successfully!');
    }

     public function deleteFlavour(Request $request)
    {
        $data = CustomizeFlavour::findOrFail($request->id);
        $flavour_images = CustomizeFlavour::where('id',$request->id)->get();
        foreach ($flavour_images as $row) {
            @unlink(public_path('uploads/flavour/'.$row->image));
            $row->delete();
        }
        $data->delete();
       


        return back()->with('flash_success', 'Flavour Deleted  Successfully!');
    }

    
}
