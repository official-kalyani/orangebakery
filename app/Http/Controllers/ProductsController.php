<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Location;
use App\Models\Occasion;
use App\Models\Section;
use App\Models\ProductRating;
use App\Models\SectionItem;
use App\Models\CustomizeImage;
use App\Models\Order;
use App\Models\CustomizeFlavour;
use App\Models\CustomizeShape;
use App\Models\CustomizeCake;
use App\Models\Slider;
use App\Setting;
use App\Models\Cbcoin;
use App\CustomizeGallery;
use App\CustomizeColor;
use App\user_customize_cake;
use App\Models\CategoryImage;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;

use DB;
use Auth;
use Session;
 
class ProductsController extends Controller
{
    public function index(Request $request)
    {
        //$request->session()->flush();
        $id = $request->cookie('store_id');
        $type = $request->cookie('type');
        $categories = Category::whereNull('parent_id')->where('slug','!=','add-on-products')->get();
        // new slug added for addon products
        $occasions = Occasion::all();
        $subcategories = Category::wherenotNull('parent_id')->get();
        $sections = Section::all();
        $sliders = Slider::where('show_in_website_home','yes')->get();
        $cakesbyFlavours = Category::wherenotNull('parent_id')->where('is_normal','no')->get();
        if ($type == "" || $id == "") {
            $products = Product::whereNull('parent_product_id')->where('steps_completed',3)->get();
            return view('welcome', compact('products','categories','occasions','subcategories','sections','id','type','sliders','cakesbyFlavours'));
        }
        else{
            if ($type == "pickup") {
                $products = Product::wherenotNull('parent_product_id')->wherenotNull('store_id')->where('store_id',$id)->where('steps_completed',3)->get();
                return view('welcome', compact('products','categories','occasions','subcategories','sections','id','type','sliders','cakesbyFlavours'));
            }
            if ($type == "delivery") {
                $products = Product::whereNull('parent_product_id')->whereNull('store_id')->where('steps_completed',3)->get();
                return view('welcome', compact('products','categories','occasions','subcategories','sections','id','type','sliders','cakesbyFlavours'));
            }
        }
    }

    public function categoryAllProducts(Request $request,$slug)
    {
        $store_id = $request->cookie('store_id');
        $type = $request->cookie('type');
        $category = Category::where('slug',$slug)->first();
         if ($category) {
                if($type == 'pickup' && $store_id !='') {
                    $products_tbl = DB::table('products')
                                   ->join('product_prices','product_prices.product_id','products.parent_product_id')
                                   ->where('products.category_id',$category->id)
                                   ->wherenotNull('products.parent_product_id')
                                   ->where('products.store_id',$store_id)
                                   ->where('products.steps_completed',3)
                                   ->where('product_prices.show_price',1)
                                   ->select('products.*','product_prices.price','product_prices.mrp_price','product_prices.weight'); 
                } else {
                    $products_tbl = DB::table('products')
                                   ->join('product_prices','product_prices.product_id','products.id')
                                   ->where('products.category_id',$category->id)
                                   ->whereNull('products.parent_product_id')
                                   ->where('products.steps_completed',3)
                                   ->where('product_prices.show_price',1)
                                   ->select('products.*','product_prices.price','product_prices.mrp_price','product_prices.weight');
                }
                if (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "high-to-low")
                {
                    $products_tbl->orderBy('product_prices.price','DESC');
                }
                elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "low-to-high") 
                {
                    $products_tbl->orderBy('product_prices.price','ASC');
                }
                elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "popular") 
                {
                    $products_tbl->orderBy('product_prices.price','ASC');
                }
                else
                {
                    $products_tbl->orderBy('products.id','desc');
                }
                $products = $products_tbl->distinct()->paginate(12);
            return view('listing.category-allproducts',compact('category','products'));
        }
        else{
            abort('404');
        }
    }

    public function categoryWiseCakeListing(Request $request,$slug)
    {
        $category = Category::where('slug',$slug)->first();
        if ($category) {
            $location = Location::all();
            $id = $request->cookie('store_id');
            $type = $request->cookie('type');
            //echo $id.$type; exit();
            $occasions = Occasion::all();
            $sliders = CategoryImage::where('category_id',$category->id)->get();
            $cakesbyFlavours = Category::wherenotNull('parent_id')->where('is_normal','no')->get();
            $cakes = array();
           
                // glow cake
                $cat = Category::where('id',33)->first();
                if($type == 'pickup') {
                    $prods = Product::where('subcategory_id',$cat->id)->where('store_id',$id)->where('steps_completed',3)->get();
                }else {
                    $prods = Product::whereNull('parent_product_id')->where('subcategory_id',$cat->id)->where('steps_completed',3)->get();  
                }
                $prod_arr = array();
                if(count($prods)>0) {
                    foreach($prods as $prod) {
                        $prod_arr[] = $this->getProductInfo($prod->id, $type, $prod->parent_product_id);       
                    }
                }
                $glowcakes =  array(
                    'title' => $cat->name,
                    'slug' => $cat->slug,
                    'products' => $prod_arr,
                );

                // Photo cake
                $cat = Category::where('id',31)->first();
                if($type == 'pickup') {
                    $prods = Product::where('subcategory_id',$cat->id)->where('store_id',$id)->where('steps_completed',3)->get();
                }else {
                    $prods = Product::whereNull('parent_product_id')->where('subcategory_id',$cat->id)->where('steps_completed',3)->get();  
                }
                $prod_arr = array();
                if(count($prods)>0) {
                    foreach($prods as $prod) {
                        $prod_arr[] = $this->getProductInfo($prod->id, $type, $prod->parent_product_id); 
                    }
                }
                $photocakes =  array(
                    'title' => $cat->name,
                    'slug' => $cat->slug,
                    'products' => $prod_arr,
                );

                //premium cake
                $cat = Category::where('id',35)->first();
                if($type == 'pickup') {
                    $prods = Product::where('subcategory_id',$cat->id)->where('store_id',$id)->where('steps_completed',3)->get();
                }else {
                    $prods = Product::whereNull('parent_product_id')->where('subcategory_id',$cat->id)->where('steps_completed',3)->get();  
                }
                $prod_arr = array();
                if(count($prods)>0) {
                    foreach($prods as $prod) {
                        $prod_arr[] = $this->getProductInfo($prod->id, $type, $prod->parent_product_id); 
                    }
                }
                $premiumcakes =  array(
                    'title' => $cat->name,
                    'slug' => $cat->slug,
                    'products' => $prod_arr,
                );          
            
                $cakes['glowcakes'] = $glowcakes;
                $cakes['flavours'] = Category::wherenotNull('parent_id')->where('is_normal','no')->get();
                $cakes['photocake'] = $photocakes;
                $cakes['occasions'] = Occasion::all();
                $cakes['premiumcake'] = $premiumcakes;
                // echo "<pre>";
                // print_r($cakes);
                // exit();
                $allCakeSubcategories = Category::where('parent_id',1)->orderBy('id','desc')->get();
                $categories = Category::whereNull('parent_id')->get();
                $subcategories = Category::wherenotNull('parent_id')->where('parent_id',$category->id)->get();
                return view('listing.category-wise-cake-listing',compact('categories','occasions','category','subcategories','type','id','allCakeSubcategories','sliders','cakesbyFlavours','location', 'cakes'));
        }
        else{
            abort('404');
        }
    }

    public function subCategoryWiseCakeListing(Request $request,$slug)
    {
        if ($slug == "all") 
        {
            $subcategories = Category::wherenotNull('parent_id')->get();
            return view('listing.cakes-by-flavour-all-listing',compact('subcategories'));
        }
        else
        {
            $id = $request->cookie('store_id');
            $type = $request->cookie('type');
            $categories = Category::all();
            $occasions = Occasion::all();
            $category = Category::where('slug',$slug)->first();
            $subcategories = Category::wherenotNull('parent_id')->get();
            @$category_id = $category->id;
            if ($category) {
                $sliders = CategoryImage::where('category_id',$category->id)->get();
                if ($type == "" || $id == "") {
                    $products = Product::whereNull('parent_product_id')->whereIn('subcategory_id', array($category_id))->where('steps_completed',3)->paginate(12);
                }
                else{
                    if ($type == "pickup") {
                        $products = Product::wherenotNull('parent_product_id')->wherenotNull('store_id')->where('store_id',$id)->whereIn('subcategory_id', array($category_id))->where('steps_completed',3)->paginate(12);
                    }
                    if ($type == "delivery") {
                        $products = Product::whereNull('parent_product_id')->whereNull('store_id')->whereIn('subcategory_id', array($category_id))->where('steps_completed',3)->paginate(12);
                    }
                }
                return view('listing.sub-category-wise-cake-listing',compact('categories','occasions','category','products','subcategories','id','type','sliders'));
            }
            else{
                abort('404');
            }
        }
    }

    public function occasionWiseCakeListing(Request $request,$slug)
    {
        $store_id = $request->cookie('store_id');
        $type = $request->cookie('type');
        $categories = Category::all();
        $occasions = Occasion::all();
        $occasion = Occasion::where('slug',$slug)->first();
        $category = Category::whereNull('parent_id')->get();
        $occasion_id = $occasion->id;
        if ($occasion) {
            if($type == 'pickup' && $store_id !='') {
                $products_tbl = DB::table('products')
                                ->join('product_prices','product_prices.product_id','products.parent_product_id')
                                ->wherenotNull('products.parent_product_id')
                                ->where('products.steps_completed',3)
                                ->where('product_prices.show_price',1)
                                ->wherenotNull('products.store_id')
                                ->where('products.store_id',$store_id)
                                ->whereIn('products.occasion_id', array($occasion_id))
                                ->select('products.*','product_prices.price','product_prices.mrp_price','product_prices.weight');
 
                    if (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "high-to-low")
                    {
                        $products_tbl->orderBy('product_prices.price','DESC');
                    }
                    elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "low-to-high") 
                    {
                        $products_tbl->orderBy('product_prices.price','ASC');
                    }
                    elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "popular") 
                    {
                        $products_tbl->orderBy('product_prices.price','ASC');
                    }
                    else
                    {
                        $products_tbl->orderBy('products.id','desc');
                    }
                    $products = $products_tbl->distinct()->paginate(12);
                
            } else {
                $products_tbl = DB::table('products')
                                ->join('product_prices','product_prices.product_id','products.id')
                                ->whereNull('products.parent_product_id')
                                ->where('products.steps_completed',3)
                                ->where('product_prices.show_price',1)
                                ->whereIn('products.occasion_id', array($occasion_id))
                                ->select('products.*','product_prices.price','product_prices.mrp_price','product_prices.weight');
 
                    if (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "high-to-low")
                    {
                        $products_tbl->orderBy('product_prices.price','DESC');
                    }
                    elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "low-to-high") 
                    {
                        $products_tbl->orderBy('product_prices.price','ASC');
                    }
                    elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "popular") 
                    {
                        $products_tbl->orderBy('product_prices.price','ASC');
                    }
                    else
                    {
                        $products_tbl->orderBy('products.id','desc');
                    }
                    $products = $products_tbl->distinct()->paginate(12);
            }
//            echo "<pre>";
//            print_r($products);
//            exit();
            return view('listing.occasion-wise-cake-listing',compact('categories','occasions','occasion','products','category'));
        }
        else{
            abort('404');
        }
    }

    public function section(Request $request,$id)
    {
        $store_id = $request->cookie('store_id');
        $type = $request->cookie('type');
        $Check_section_items = Section::where('id',$id)->first();
        if ($Check_section_items) {
            $section_items = SectionItem::where('section_id',$id)->get();
            return view('listing.section',compact('section_items','store_id','type','Check_section_items'));
        }
        else{
            abort('404');
        }
        
    }

    public function productsDetails(Request $request,$slug)
    {

        $id = $request->cookie('store_id');
        $type = $request->cookie('type');
        $delivercharge = Setting::all(); 
        // if(!$delivercharge){
        //     $delivercharge = array();
        // }  
        // print $delivercharge->ordertime;            
        if ($slug == "all") 
        {
            if ($type == "" || $id == "" || $type == "delivery") {
                $products_tbl = DB::table('products')
                                ->join('product_prices','product_prices.product_id','products.id')
                                //->join('product_ratings','product_ratings.product_id','products.id')
                                ->whereNull('products.parent_product_id')
                                ->where('products.steps_completed',3)
                                ->where('products.id','!=','products.id')
                                ->where('product_prices.show_price',1)
                                ->select('products.*','product_prices.price','product_prices.mrp_price','product_prices.weight');
                
                if (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "high-to-low")
                {
                    $products_tbl->orderBy('product_prices.price','DESC');
                }
                elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "low-to-high") 
                {
                    $products_tbl->orderBy('product_prices.price','ASC');
                }
                elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "popular") 
                {
                    $products_tbl->orderBy('product_prices.price','ASC');
                }
                else
                {
                    $products_tbl->orderBy('products.id','desc');
                }
                 $products = $products_tbl->distinct('products.id')->paginate(12);

            }
            else{
                $products_tbl = DB::table('products')
                                ->join('product_prices','product_prices.product_id','products.parent_product_id')
                                ->wherenotNull('products.parent_product_id')
                                ->where('products.steps_completed',3)
                                ->where('product_prices.show_price',1)
                                ->wherenotNull('products.store_id')
                                ->where('products.store_id',$id)
                                ->select('products.*','product_prices.price','product_prices.mrp_price','product_prices.weight');
 
                if (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "high-to-low")
                {
                    $products_tbl->orderBy('product_prices.price','DESC');
                }
                elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "low-to-high") 
                {
                    $products_tbl->orderBy('product_prices.price','ASC');
                }
                elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "popular") 
                {
                    $products_tbl->orderBy('product_prices.price','ASC');
                }
                else
                {
                    $products_tbl->orderBy('products.id','desc');
                }
                $products = $products_tbl->distinct('products.id')->paginate(12);
            }
            return view('listing.allproducts',compact('products','id','type'));
        }
        else
        {
            $checkProduct = Product::where('slug',$slug)->where('steps_completed',3)->first();
            $product_id = $checkProduct->id;
            if ($checkProduct) {                
                if($type == 'pickup' && $id !=''){
                    $products = Product::wherenotNull('parent_product_id')->wherenotNull('store_id')->where('parent_product_id',$product_id)->where('store_id',$id)->first();
                    $allproducts = Product::wherenotNull('parent_product_id')->wherenotNull('store_id')->where('store_id',$id)->where('steps_completed',3)->get();
                } else {
                    $products = Product::find($product_id);
                    $allproducts = Product::whereNull('parent_product_id')->whereNull('store_id')->where('steps_completed',3)->get();
                }       
                $product_images = ProductImage::where('product_id',$product_id)->get();
                $product_prices = ProductPrice::where('product_id',$product_id)->get();
                $customizeImage = $rating = array();
                if(Auth::check()) {
                  @$user_id = Auth::user()->id;     
                  $customizeImage = CustomizeImage::where('user_id',Auth::user()->id)->where('product_id',$products->id)->first();
                  $rating = ProductRating::where('user_id',Auth::user()->id)->where('product_id',$product_id)->first();  
                }     
           
                $allratings = DB::table('product_ratings')
                            ->join('users','users.id','product_ratings.user_id')
                            ->select('users.name as username','product_ratings.*')
                            ->where('product_ratings.product_id',$product_id)
                            ->get();
                return view('product-details',compact('products','product_images','product_prices','allproducts','customizeImage','rating','allratings','delivercharge'));
            }
            else{
                abort('404');
            }
        }
    }

    public function cart(Request $request)
    {
        $store_id = $request->cookie('store_id');
        $type = $request->cookie('type');
          if($type == 'pickup' && $store_id !=''){
                   $addOnProducts = DB::table('products')
                   ->join('product_prices','product_prices.product_id','products.parent_product_id')
                   ->join('product_images','product_images.product_id','products.parent_product_id')
                   ->where('products.steps_completed',3)
                   ->where('products.category_id',43)
                   ->where('product_prices.show_price',1)
                   ->where('product_images.is_featured',1)
                   ->select('products.*','product_prices.price','product_prices.mrp_price', 'product_images.images')
                   ->get();
            } else {
                $addOnProducts = DB::table('products')
                   ->join('product_prices','product_prices.product_id','products.id')
                   ->join('product_images','product_images.product_id','products.id')
                   ->where('products.steps_completed',3)
                   ->where('products.category_id',43)
                   ->where('product_prices.show_price',1)
                   ->where('product_images.is_featured',1)
                   ->select('products.*','product_prices.id as price_id','product_prices.price','product_prices.mrp_price', 'product_images.images')
                   ->get();
            }
        return view('cart', compact('addOnProducts'));
        
    }
    public function addToCart(Request $request,$id)
    {
        $product = Product::find($id);

        if(!$product) {

            abort(404);

        }

        $cart = session()->get('cart');
        // if cart is empty then this the first product
        if(!$cart) {
            $cart = [
                $id => [
                    "id" => $product->id,
                    "parent_product_id" => $product->parent_product_id,
                    "quantity" => 1,
                    "price_id" => $request->price_id,
                    "message" => $message = $request->message,
                ]
            ];

            session()->put('cart', $cart);

            $htmlCart = view('layouts.sidebar_cart')->render();
            $cartCounter = count((array) session('cart'));
            return response()->json(['msg' => 'Added To Cart!', 'data' => $htmlCart, 'cartCounter' => $cartCounter]);

            //return redirect()->back()->with('success', 'Added To Cart!');
        }

        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {

            $cart[$id]['quantity']++;

            session()->put('cart', $cart);

            $htmlCart = view('layouts.sidebar_cart')->render();
            $cartCounter = count((array) session('cart'));
            return response()->json(['msg' => 'Added To Cart!', 'data' => $htmlCart, 'cartCounter' => $cartCounter]);

            //return redirect()->back()->with('success', 'Added To Cart!');

        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "id" => $product->id,
            "parent_product_id" => $product->parent_product_id,
            "quantity" => 1,
            "price_id" => $request->price_id,
            "message" => $message = $request->message,
        ];

        session()->put('cart', $cart);

        $htmlCart = view('layouts.sidebar_cart')->render();
        $cartCounter = count((array) session('cart'));
        return response()->json(['msg' => 'Added To Cart!', 'data' => $htmlCart, 'cartCounter' => $cartCounter]);

        //return redirect()->back()->with('success', 'Added To Cart!');
    }

    public function addToCartMain(Request $request,$id)
    {
        $product = Product::find($id);
        if(!$product) {
            abort(404);
        }
        $cart = session()->get('cart');
        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {

            $cart[$id]['quantity']++;

            session()->put('cart', $cart);

            $product_price = ProductPrice::where('id',$request->price_id)->where('show_price',1)->first(); 
            $subTotal = $cart[$request->id]['quantity'] * $product_price->price;

            $total = $this->getCartTotal();
            $maincart = view('layouts.maincart')->render();
            $cartCounter = count((array) session('cart'));

            return response()->json(['msg' => 'Cart updated successfully', 'total' => $total, 'subTotal' => $subTotal,'totalPrice'=> $total,'maincart'=>$maincart]);
        }
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "id" => $product->id,
            "parent_product_id" => $product->parent_product_id,
            "quantity" => 1,
            "price_id" => $request->price_id,
            "message" => $message = $request->message,
        ];

        session()->put('cart', $cart);

        $product_price = ProductPrice::where('id',$request->price_id)->where('show_price',1)->first(); 
        $subTotal = $cart[$request->id]['quantity'] * $product_price->price;

        $total = $this->getCartTotal();
        $maincart = view('layouts.maincart')->render();
        $cartCounter = count((array) session('cart'));

        return response()->json(['msg' => 'Cart updated successfully', 'total' => $total, 'subTotal' => $subTotal,'totalPrice'=> $total,'maincart'=>$maincart]);

        //return redirect()->back()->with('success', 'Added To Cart!');
    }

    public function update(Request $request)
    {
        if($request->id and $request->quantity)
        {
            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);
            $product_price = ProductPrice::where('id',$request->price_id)->where('show_price',1)->first(); 
            $subTotal = $cart[$request->id]['quantity'] * $product_price->price;

            $total = $this->getCartTotal();
            $htmlCart = view('layouts.sidebar_cart')->render();
            $maincart = view('layouts.maincart')->render();

            return response()->json(['msg' => 'Cart updated successfully', 'total' => $total, 'subTotal' => $subTotal,'data'=>$htmlCart,'totalPrice'=> $total,'maincart'=>$maincart]);

            //session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {

            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            $total = $this->getCartTotal();

            $htmlCart = view('layouts.sidebar_cart')->render();
            $maincart = view('layouts.maincart')->render();

            $cartCounter = count((array) session('cart'));
            return response()->json(['msg' => 'Product removed successfully', 'data' => $htmlCart, 'total' => $total,'cartCounter' => $cartCounter,'maincart'=>$maincart]);

            //session()->flash('success', 'Product removed successfully');
        }
    }

    public function clearAll(Request $request)
    {

        $request->session()->flush();

        $htmlCart = view('layouts.app')->render();

        return response()->json(['msg' => 'All Products removed successfully']);
    }


    /**
     * getCartTotal
     *
     *
     * @return float|int
     */
    private function getCartTotal()
    {
        $total = 0;

        $cart = session()->get('cart');

        foreach($cart as $id => $details) {
            $product_price = ProductPrice::where('id',$details['price_id'])->where('show_price',1)->first(); 
            $total += $product_price->price * $details['quantity'];
        }
        return $total;
 
    }

    public function cancelOrder(Request $request)
    {
        $orders = Order::where('order_id',$request->order_id)->where('user_id',Auth::user()->id)->first();
        if ($orders->status == "order_confirmed" || $orders->status == "order_preparing" || $orders->status == "ontheway" || $orders->status == "delivered") {
            $arr['status'] = 2;
        }
        else{
            $orders->status = "cancelled";
            $arr['status'] = 1;
            $orders->save();
        }
        return response()->json($arr);
    }

    public function fetchProducts(Request $request)
    {
        if($request->get('query'))
        {
        $query = $request->get('query');
        $searchResults = DB::select("
            (
                SELECT
                    'product' as `type`,
                    `products`.`id` as `id`,
                    `products`.`name` as `name`,
                    `products`.`slug` as `slug`
                FROM `products`
                WHERE
                    `products`.`name` LIKE '%{$query}%'
            )
            UNION
            (
                SELECT
                    'category' as `type`,
                    `categories`.`id` as `id`,
                    `categories`.`name` as `name`,
                    `categories`.`slug` as `slug`
                FROM `categories`
                WHERE
                    (`categories`.`name` LIKE '%{$query}%' AND `categories`.`parent_id` IS NULL)
            )
            UNION
            (
                SELECT
                    'subcategory' as `type`,
                    `categories`.`id` as `id`,
                    `categories`.`name` as `name`,
                    `categories`.`slug` as `slug`
                FROM `categories`
                WHERE
                    (`categories`.`name` LIKE '%{$query}%' AND `categories`.`parent_id` IS NOT NULL)
            )
            UNION
            (
                SELECT
                    'occasion' as `type`,
                    `occasions`.`id` as `id`,
                    `occasions`.`name` as `name`,
                    `occasions`.`slug` as `slug`
                FROM `occasions`
                WHERE
                    `occasions`.`name` LIKE '%{$query}%'
            )
            ");

          
        $products_counter = 0;
        $category_counter = 0;
        $subcategory_counter = 0;
        $occasion_counter = 0;
        $output = '<ul id="cakes-list">';
        foreach($searchResults as $row)
        {
            if ($row->type == "product") {
                $products_counter++;
                if ($products_counter == 1) {
                    $output .= '<li style="background:#fb6700;">Products</li>
                    ';
                }
                else{
                    $output .= '
                        <li><a href="products/'.$row->slug.'">'.$row->name.'</a></li>
                    ';
                }
                
            }
            if ($row->type == "category") {
                $category_counter++;
                if ($category_counter == 1) {
                    $output .= '<li style="background:#fb6700;">Category</li>
                                <li><a href="category/'.$row->slug.'">'.$row->name.'</a></li>
                    ';
                }
                else{
                    $output .= '
                        <li><a href="category/'.$row->slug.'">'.$row->name.'</a></li>
                    ';
                }
            }
            if ($row->type == "subcategory") {
                $subcategory_counter++;
                if ($subcategory_counter == 1) {
                    $output .= '<li style="background:#fb6700;">Subcategory</li>
                                <li><a href="flavour/'.$row->slug.'">'.$row->name.'</a></li>
                    ';
                }
                else{
                    $output .= '
                        <li><a href="flavour/'.$row->slug.'">'.$row->name.'</a></li>
                    ';
                }
            }
            if ($row->type == "occasion") {
                $occasion_counter++;
                if ($occasion_counter == 1) {
                    $output .= '<li style="background:#fb6700;">Occasion</li>
                                <li><a href="occasion/'.$row->slug.'">'.$row->name.'</a></li>
                    ';
                }
                $output .= '
                    <li><a href="occasion/'.$row->slug.'">'.$row->name.'</a></li>
                ';
            }
        }
          $output .= '</ul>';
          echo $output;
        }
    }

    public function customize(Request $request)
    {
         $product_id = $_GET['product_id'];
        $flavour_img = '';
        $product = Product::find($product_id);
        $user_id = Auth::user()->id;
        // echo "<pre>";
        // print_r($product);exit();
        if ($product->is_customize == 1) {
           if (Auth::user()) {

            $customize_flavours = CustomizeFlavour::where('product_id',$product_id)->get();
            $customize_galleries = CustomizeGallery::all();           
            $customize_color = CustomizeColor::all();
            $customize_gallery = '';
            if(isset($_REQUEST['gallery']) && $_REQUEST['gallery']!=''){
                $customize_gallery = CustomizeGallery::find($_REQUEST['gallery']);
            }

            $user_customize_cake = user_customize_cake::where('product_id',$request->product_id)->where('user_id',$user_id)->first();
            $customize_img = ProductImage::where('product_id',$product_id)->where('customize',1)->first();
            if(isset($_REQUEST['flavour']) && $_REQUEST['flavour'] !=''){
                $flavour = $_REQUEST['flavour'];
                $flavour_img = CustomizeFlavour::find($flavour);
            }
                return view('customize.index',compact('product','customize_flavours','customize_img','flavour_img','customize_galleries','customize_gallery','customize_color','user_customize_cake'));
            } else {
                return redirect()->back();
            } 

            }
            else{
                 return redirect()->back();
            }
        
    }

    public function removeCustomImage(Request $request)
    {
        $customImage = CustomizeImage::where('user_id',Auth::user()->id)->where('product_id',$request->product_id)->first();
        @unlink(public_path('uploads/customized/'.$customImage->image));
        $customImage->delete();
        return redirect()->back();
    }

    public function deleteCustomizedImage(Request $request)
    {
        $user_id = Auth::user()->id;
        echo $request->product_id;
        $user_customize_cake = user_customize_cake::where('product_id',$request->product_id)->where('user_id',$user_id)->first();
        @unlink(public_path('uploads/'.$user_id.'/'.$user_customize_cake->image));
        $user_customize_cake->image = null;
        $user_customize_cake->save();
        return redirect()->back();
        // $customImage = CustomizeImage::where('user_id',Auth::user()->id)->where('product_id',$request->product_id)->first();
        // @unlink(public_path('uploads/customized/'.$customImage->image));
        // $customImage->delete();
        // return redirect()->back();
    }
    public function uploadCustomizedImage(Request $request)
    {
        if ($request->hasFile('banner')) {
            $crop_arr = array(
                'x1'=>$_POST['banner_x1'],
                'y1'=>$_POST['banner_y1'],
                'x2'=>$_POST['banner_x2'],
                'y2'=>$_POST['banner_y2'],
                'w'=>$_POST['banner_w'],
                'h'=>$_POST['banner_h']
                );
            $randomtime = rand().time();
            if($request->product_shape == 'round'){
              $org_width = 400; $org_height = 400;   
            } else {
              $org_width = 600; $org_height = 400;
            }
            $user_id = Auth::user()->id;
            $filename = $this->uploadFiles($_FILES['banner'], $user_id, $crop_arr, $randomtime, $org_width, $org_height);


            $user_customize_cake = user_customize_cake::where('product_id',$request->product_id)
            ->where('user_id',$user_id)->first();
            if ($user_customize_cake) {
                @unlink(public_path('uploads/'.$user_id.'/'.$user_customize_cake->image));
                 DB::table('user_customize_cakes')
                ->where('product_id',$request->product_id)
                ->where('user_id',$user_id)
                ->update(['image' => $filename]); 
                return back()->with('flash_success', 'Inserted');
               
            }else{
                 $customize_cake = new user_customize_cake();
            $customize_cake->product_id = $request->product_id;
             $customize_cake->user_id = Auth::user()->id;
             $customize_cake->image = $filename;

             $customize_cake->save();
             return back()->with('flash_success', 'Inserted');
            }
            // print_r($user_customize_cake);exit();
            
             
        }
        // exit();

    }
    public function saveCustomizedImage(Request $request)
    {
        $this->validate($request, [
            'customize_shape_id' => 'required',
            'customize_flavour_id' => 'required',
            'message_on_cake' => 'required',
        ]);
        $product = Product::find($request->product_id);
        $store_id = $request->cookie('store_id');
        $type = $request->cookie('type');
        if(isset($product->id)){
            if($type == 'pickup' && $store_id !=''){
               $c_image = CustomizeImage::where('type','pickup')->where('store_id',$store_id)->where('user_id',Auth::user()->id)->where('product_id',$product->id)->first();
            } else {
                $c_image = CustomizeImage::where('user_id',Auth::user()->id)->where('product_id',$product->id)->first();
            }
            if (isset($c_image->id)) {
                if($request->hasfile('photo_cake'))
                 {
                     @unlink(public_path('uploads/customized/'.$c_image->photo_cake));
                     $c_image->delete();
                     $file = $request->file('photo_cake');
                     $filename = time() . '.' . $file->getClientOriginalExtension($file);
                     $filePath = 'uploads/customized/' . $filename;
                     $file->move(public_path('uploads/customized'),$filePath); 
                     $c_image->photo_cake = $filename;  
                 }
                 $c_image->customize_shape_id = $request->customize_shape_id;
                 $c_image->customize_flavour_id = $request->customize_flavour_id;
                 $c_image->message_on_cake = $request->message_on_cake;
                 $c_image->save(); 
            }
            else{
                $c_image = new CustomizeImage;
                if($request->hasfile('photo_cake'))
                {
                    $file = $request->file('photo_cake');
                    $filename = time() . '.' . $file->getClientOriginalExtension($file);
                    $filePath = 'uploads/customized/' . $filename;
                    $file->move(public_path('uploads/customized'),$filePath); 
                    $c_image->photo_cake = $filename;  
                }
                $c_image->user_id = Auth::user()->id;
                $c_image->product_id = $request->product_id;
                $c_image->customize_shape_id = $request->customize_shape_id;
                $c_image->customize_flavour_id = $request->customize_flavour_id;
                $c_image->message_on_cake = $request->message_on_cake;
                $c_image->type = @$type;
                $c_image->store_id = @$store_id;
                $c_image->save();
            }
        }
        
 
        //return redirect($request->return_url);
        
        return redirect('products/'.$product->slug);
        
    }

    public function rateProduct(Request $request)
    {
        $user_id = Auth::user()->id;
        $ratingCheck = ProductRating::where('user_id',$user_id)->where('product_id',$request->product_id)->first();
        if ($ratingCheck) {
            $ratingCheck->review_text = $request->review_text;
            $ratingCheck->rating = $request->rating;
            $ratingCheck->save();
        }
        else{
            $rating = new ProductRating;
            $rating->product_id = $request->product_id;
            $rating->user_id = Auth::user()->id;
            $rating->review_text = $request->review_text;
            $rating->rating = $request->rating;
            $rating->save();
        }
        
        $arr['status'] = 1;
        return response()->json($arr);
    }

    public function blogListing()
    {
        $blogs = Blog::orderBy('id','DESC')->paginate(10);
        return view('blog.index',compact('blogs')); 
    }

    public function blogDetails($slug)
    {
        $blog = Blog::where('slug',$slug)->first();
        if ($blog) {
            return view('blog.details',compact('blog')); 
        }
        else{
            abort(404);
        }
        
    }

    public function getProductInfo($product_id='', $type='', $product_parent_id='') { // product id

        $product = Product::find($product_id);
        $productId = $type == 'pickup' ? $product_parent_id : $product_id;
        $product_price = ProductPrice::where('product_id',$productId)->where('show_price',1)->first(); 
        $product_image = ProductImage::where('product_id',$productId)->where('is_featured',1)->first(); 
        $customize = ProductImage::where('product_id',$productId)->where('customize',1)->first();
        $discountPrice = $product_price['mrp_price'] - $product_price['price'];
        $discountPercentage = ($product_price['price'] / $product_price['mrp_price'])*100;  
        $product_arr = array(
            'id' => $product->id,
            'parent_product_id' => $product->parent_product_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'product_image' => $product_image->images,
            'price_id' => $product_price->id,
            'price' => $product_price->price,
            'mrp_price' => $product_price->mrp_price,
            'discount' => $discountPercentage,
            'is_customize' => $product->is_customize,
            'customize_images' => @$customize->images
        );
        return $product_arr;
    }
}
