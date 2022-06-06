<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductPrice;
use App\Models\Occasion;
use Auth;
use App\Shop;
use App\Models\Order;
use App\Models\Orderlist;
use App\Models\DeliveryBoy;
use DB;
use App\User;
use App\Models\Address;
use Carbon\Carbon;

class ProductController extends Controller
{


    public function index()
    {
        $occasions = Occasion::all();
        $data = Product::wherenotNull('parent_product_id')->where('store_id',Auth::user()->id)->orderBy('id','DESC')->paginate(10);
        return view('shop.products.index',compact('data','occasions'));
    }

    public function dashboard()
    {
        // $orders = Order::where('countdowm_time', '<=','created_at')
        // ->wherenotNull('order_made_action_on')
        // ->get();
        //echo Carbon::now()->addMinutes(5);
        // dd($orders);
        //exit();
        $order = Order::all();

        $todaySale = Orderlist::whereDate('created_at', Carbon::today())->get();
        $totalSale = Orderlist::all();

        $todayOrder = Order::where('type','pickup')->whereDate('created_at', Carbon::today())->get();
        $totalOrder = Order::all();

        $todayDelivered = Order::where('type','pickup')->whereDate('delivered_date', Carbon::today())->get();
        $totalDelivered = Order::where('type','pickup')->wherenotNull('delivered_date')->get();

        $todayCustomer = User::whereDate('created_at', Carbon::today())->get();
        $totalCustomer = User::all();

        $todayCancel = Order::where('type','pickup')->where('status','cancelled')->whereDate('created_at', Carbon::today())->get();
        $totalCancel = Order::where('type','pickup')->where('status','cancelled')->get();

        $todayCollection = Order::where('type','pickup')->where('amount_paid','1')->whereDate('created_at', Carbon::today())->get();
        $totalCollection = Order::where('type','pickup')->where('amount_paid','1')->get();

        return view('shop.home',compact('todaySale','totalSale','todayDelivered','totalDelivered','todayCustomer','totalCustomer','todayCancel','totalCancel','todayCollection','totalCollection','todayOrder','totalOrder'));
    }
    public function create(Request $request)
    {
        $categories = Category::whereNull('parent_id')->get();
        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            if ($products = Product::where('id',@$id)->where('store_id',Auth::user()->id)->first()) {
                return view('shop.products.create',compact('categories','products')); 
            }
            else{
                abort(404);
            }
            
        }
        
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'stock_quantity' => 'required',
        ]);

        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $shop = Product::where('id',$id)->where('store_id',Auth::user()->id)->first();
            $shop->stock_quantity = $request->stock_quantity;
            $shop->save();
            return back()->with('flash_success', 'Stock Quantity Updated Successfully!');
        }

    }

    public function masterProducts()
    {
        $data = Product::whereNull('parent_product_id')->orderBy('id','DESC')->paginate(20);
        $occasions = Occasion::all();
        return view('shop.master-products.index',compact('data','occasions'));
    }

    public function cloneMasterProducts(Request $request)
    {
        $product_id = $request->id;

        $product = Product::find($product_id);

        $newProduct = Product::where('parent_product_id',$product_id)->where('store_id',Auth::user()->id)->first();

        if ($newProduct) {
            return back()->with('flash_danger', 'Product Already Cloned!');
        }
        else{
            $newProduct = new Product;
            $newProduct->name = $product->name;
            $newProduct->description = $product->description;
            $newProduct->category_id = $product->category_id;
            $newProduct->subcategory_id = $product->subcategory_id;
            $newProduct->occasion_id = $product->occasion_id;
            $newProduct->meta_title = $product->meta_title;
            $newProduct->meta_desc = $product->meta_desc;
            $newProduct->slug = $product->slug;
            $newProduct->stock_quantity = 0;
            $newProduct->store_id = Auth::user()->id;
            $newProduct->parent_product_id = $product->id;
            $newProduct->is_customize = $product->is_customize;
            $newProduct->is_photocake = $product->is_photocake;
            $newProduct->steps_completed = $product->steps_completed;
            $newProduct->save();
            return back()->with('flash_success', 'Product Cloned Successfully!');
        }
    }
    public function myOrders(Request $request){
        // echo Auth::user()->id;
       $locations = Shop::all();
        $data_tbl = Order::wherenotNull('order_id')->where('type','pickup')->where('store_id',Auth::user()->id);
        $deliveryboy = DeliveryBoy::all();
        if (isset($request->payment_type) && $request->payment_type!='' && $request->payment_type!='all') {
            $data_tbl->where('payment_type',$request->payment_type);
            // print_r($data_tbl);
        }
        if (isset($request->type) && $request->type!='' && $request->type!='all') {
            if ($request->type == "pickup") {
                $data_tbl->where('type',$request->type);
            }
            else{
                $data_tbl->where('type',$request->type);
                $data_tbl->where('store_id',$request->store_id);
            }
            
        }
        if (isset($request->status) && $request->status!='' && $request->status!='all') {
            $data_tbl->where('status',$request->status);
        }
        if(($request->has('from_date') && $request->from_date!='' ) && ($request->has('to_date') && $request->to_date!='')){
            $from_date = Carbon::parse($request->from_date);
            $to_date = Carbon::parse($request->to_date);
            $data_tbl->whereBetween('created_at', [$from_date, $to_date]);
        }
        $data = $data_tbl->orderBy('id','DESC')->paginate(15);
        // echo "<pre>"; print_r($data);exit();
        return view('shop.products.myorders',compact('data','deliveryboy','locations'));
    }
    public function orderDetails(Request $request)
    {
       $orderitems = $user = $address = array();
        $order = Order::where('order_id',$request->order_id)
                ->where('type','pickup')
                ->first();
        if(isset($order->id)){
               $orderitems = DB::table('orderlists')
                ->join('products','products.id','orderlists.item_id')
                ->select('orderlists.*','products.name as itemname')
                ->where('orderlists.order_id',$order->order_id)
                ->get();
            $user = User::where('id',$order->user_id)->first();
            $address = Address::where('id',$order->user_address_id)->first();
        }
//        echo "<pre>";
//        print_r($address);
//        exit();
        
        return view('shop.products.orderdetail',compact('orderitems','order','user', 'address'));
    }
    public function assignDelivery(Request $request)
    {
        $deliveryboy_id = $request->deliveryboy;
        $orders = Order::where('order_id',$request->order_id)->first();
        $orders->assignto = $deliveryboy_id;
        $orders->save();

        return back()->with('flash_success', 'Order Assigned');
    }
     public function changeOrderStatus(Request $request)
    {

        $order_status = $request->order_status;
        $orders = Order::where('order_id',$request->order_id)->first();
        $orderlists = Orderlist::where('order_id',$orders->order_id)->get(); 
         print '<pre>';
        print_r($orderlists);
        if ($order_status == 'delivered' && $orders->type == 'pickup' && $orders->store_id != '') {
          foreach ($orderlists as $orderlist) {
              $product = Product::where('id',$orderlist->item_id)->first();
               // print_r($product);
               $quntity = $product->stock_quantity - $orderlist->item_quantity;
               // echo $orderlist->id;
               $product->stock_quantity =$quntity;
               $product->save();
          }
        }
        // exit();
        $orders->status = $order_status;
        $orders->save();

        return back()->with('flash_success', 'Status Updated');
    }
}
