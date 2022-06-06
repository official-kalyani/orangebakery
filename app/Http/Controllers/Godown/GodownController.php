<?php

namespace App\Http\Controllers\Godown;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use App\Godown;
use App\Shop;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use App\User;
use App\Models\Orderlist;
use App\Product;
use Carbon\Carbon;
use App\Models\Notification;


class GodownController extends Controller
{
    public function deliveryBoy()
    {
    	$data = DeliveryBoy::orderBy('id','DESC')->paginate(10);
        return view('godown.delivery-boy.index',compact('data'));
    }

    public function changePassword()
    {
        $admin = Godown::where('id',Auth::user()->id)->first();
        return view('godown.change-password.index',compact('admin'));
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($request->password != $request->confirm_password) {
            return back()->with('flash_danger', 'Password and Confirm Password Must be same!');
        }
        else{
            $admin = Godown::where('id',Auth::user()->id)->first();
            $admin->name = $request->name;
            $admin->password = bcrypt($request->password);
            $admin->save();
            return back()->with('flash_success', 'Password Changed');
        }
    }

    public function orderLists(Request $request)
    {
        $locations = Shop::all();
        $data_tbl = Order::wherenotNull('order_id');
        $deliveryboy = DeliveryBoy::all();
        if (isset($request->payment_type) && $request->payment_type!='' && $request->payment_type!='all') {
            $data_tbl->where('payment_type',$request->payment_type);
        }
        if (isset($request->type) && $request->type!='' && $request->type!='all') {
            if ($request->type == "delivery") {
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
        return view('godown.manage-orders.index',compact('data','deliveryboy','locations'));
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
        // print '<pre>';
       
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

    public function orderDetails(Request $request)
    {
        $orderitems = DB::table('orderlists')
                ->join('products','products.id','orderlists.item_id')
                ->select('orderlists.*','products.name as itemname')
                ->where('orderlists.order_id',$request->order_id)
                ->get();
        $orders = Order::where('order_id',$request->order_id)->first();

        return view('godown.order-details.index',compact('orderitems','orders'));
    }

    public function ChangePaidStatus(Request $request)
    {
        $orders = Order::where('order_id',$request->order_id)->first();
        $orders->amount_paid = "1";
        $orders->save();

        return back()->with('flash_success', 'Amount Paid');
    }

    public function markReadNotification(Request $request)
    {
        DB::statement("
            UPDATE notifications SET status = 'read'
        ");

        $notifications = view('godown.layout.notifications')->render();
        $new_notification =  \App\Models\Notification::where('status','unread')->latest()->get();
        $notification_today =  \App\Models\Notification::whereDate('created_at', \Carbon\Carbon::today())->get();
        return response()->json(['msg' => 'All notifications mark as read','new_notification'=> count($new_notification),'notification_today'=> count($notification_today)]);
    }

    public function get_refresh(Request $request)
    {
        $notifications = view('godown.layout.notifications')->render();
        $new_notification_audio =  \App\Models\Notification::where('status','unread')->latest()->get();
        return response()->json(['msg' => 'Refresh Completed','notifications'=> $notifications]);
        // return response()->json(['msg' => 'Refresh Completed','notifications'=> $notifications,'new_notification_audio' => count($new_notification_audio)]);
    }

    public function home()
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

        $todayOrder = Order::whereDate('created_at', Carbon::today())->get();
        $totalOrder = Order::all();

        $todayDelivered = Order::whereDate('delivered_date', Carbon::today())->get();
        $totalDelivered = Order::wherenotNull('delivered_date')->get();

        $todayCustomer = User::whereDate('created_at', Carbon::today())->get();
        $totalCustomer = User::all();

        $todayCancel = Order::where('status','cancelled')->whereDate('created_at', Carbon::today())->get();
        $totalCancel = Order::where('status','cancelled')->get();

        $todayCollection = Order::where('amount_paid','1')->whereDate('created_at', Carbon::today())->get();
        $totalCollection = Order::where('amount_paid','1')->get();

        return view('godown.home',compact('todaySale','totalSale','todayDelivered','totalDelivered','todayCustomer','totalCustomer','todayCancel','totalCancel','todayCollection','totalCollection','todayOrder','totalOrder'));
    }

    public function orderAction(Request $request)
    {
        $order = Order::where('order_id',$request->order_id)->first();
        $notifications = Notification::where('order_id',$request->order_id)->first();
        $order->status = $request->order;
        
        if ($request->order == "order_confirmed") {
            $order->action_type = "accept";
            $notifications->action_type = "accept";
        }
        else if ($request->order == "order_rejected") {
            $order->action_type = "reject";
            $notifications->action_type = "accept";
        }
        $order->order_made_action_on = now();
        $order->save();
        $notifications->save();
        return back()->with('flash_success', 'order accepted');
    }
}
