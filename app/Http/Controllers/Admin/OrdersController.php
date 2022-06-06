<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use Carbon\Carbon;
use App\Shop;
use App\Models\Order;
use App\Models\Orderlist;
use App\Models\Address;
use PDF;
use DB;
use App\User;
use Maatwebsite\Excel\Facades\Excel;


class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

        if (isset($_REQUEST['report_type']) && $_REQUEST['report_type'] == "pdf") {
            $pdf = \App::make('dompdf.wrapper');
            $pdf->loadHTML($this->convertUserDataPdfHtml($data));
            return $pdf->stream();
        }

        if (isset($_REQUEST['report_type']) && $_REQUEST['report_type'] == "excel") {
             $order_array[] = array('Name', 'Phone', 'Email','Location','Additional Address','Address Type','Order Items','Transaction Info');

                foreach ($data as $data_arr) {
                    if ($data_arr->amount_paid == "0") {
                        $amount_paid = "PENDING";
                    }
                    else{
                        $amount_paid = "PAID";
                    }

                    $address = Address::where('id',$data_arr->user_address_id)->first();

                    $orderitems = DB::table('orderlists')
                    ->join('products','products.id','orderlists.item_id')
                    ->select('orderlists.*','products.name as itemname')
                    ->where('orderlists.order_id',$data_arr->order_id)
                    ->get();

                    if (count($orderitems)) {
                        foreach($orderitems as $orderitem)
                        {
                            $Order_items = '';
                            $Order_items .= "Product Name - ".$orderitem->itemname. ",";
                            $Order_items .= "Item Quantity - ".$orderitem->item_quantity. ",";
                            $Order_items .= "Selling Price - ".$orderitem->item_selling_price. ",";
                        }
                    }
                    $transaction_info= '';
                    $transaction_info = '';
                    $transaction_info .= "Order Id - ".$data_arr->order_id. ",";
                    $transaction_info .= "Order Status - ".$data_arr->status. ",";
                    $transaction_info .= "Payment Type - ".$data_arr->payment_type. ",";
                    $transaction_info .= "Amount - ".$data_arr->order_price. ",";
                    $transaction_info .= "Has Paid - ".$amount_paid. ",";

                      $order_array[] = array(
                       'Name'  => $address->name,
                       'Phone'   => $address->phone,
                       'Email' => $address->email,
                       'Location' => $address->location,
                       'Additional Address' => $address->additional_address,
                       'Address Type' => $address->address_type,
                       'Order Items' => $Order_items,
                       'Transaction Info' => $transaction_info,
                      );



                    
                }   
                Excel::create('order_report', function($excel) use ($order_array){
                    $excel->setTitle('order_report');
                    $excel->sheet('order_report', function($sheet) use ($order_array){
                    $sheet->fromArray($order_array, null, 'A1', false, false);
                });
                })->download('xlsx');
        }

        if (isset($_REQUEST['report_type']) && $_REQUEST['report_type'] == "csv") {
             $order_array[] = array('Name', 'Phone', 'Email','Location','Additional Address','Address Type','Order Items','Transaction Info');

                foreach ($data as $data_arr) {
                    if ($data_arr->amount_paid == "0") {
                        $amount_paid = "PENDING";
                    }
                    else{
                        $amount_paid = "PAID";
                    }

                    $address = Address::where('id',$data_arr->user_address_id)->first();

                    $orderitems = DB::table('orderlists')
                    ->join('products','products.id','orderlists.item_id')
                    ->select('orderlists.*','products.name as itemname')
                    ->where('orderlists.order_id',$data_arr->order_id)
                    ->get();

                    if (count($orderitems)) {
                        foreach($orderitems as $orderitem)
                        {
                            $Order_items = '';
                            $Order_items .= "Product Name - ".$orderitem->itemname. ",";
                            $Order_items .= "Item Quantity - ".$orderitem->item_quantity. ",";
                            $Order_items .= "Selling Price - ".$orderitem->item_selling_price. ",";
                        }
                    }
                    $transaction_info= '';
                    $transaction_info = '';
                    $transaction_info .= "Order Id - ".$data_arr->order_id. ",";
                    $transaction_info .= "Order Status - ".$data_arr->status. ",";
                    $transaction_info .= "Payment Type - ".$data_arr->payment_type. ",";
                    $transaction_info .= "Amount - ".$data_arr->order_price. ",";
                    $transaction_info .= "Has Paid - ".$amount_paid. ",";

                      $order_array[] = array(
                       'Name'  => $address->name,
                       'Phone'   => $address->phone,
                       'Email' => $address->email,
                       'Location' => $address->location,
                       'Additional Address' => $address->additional_address,
                       'Address Type' => $address->address_type,
                       'Order Items' => $Order_items,
                       'Transaction Info' => $transaction_info,
                      );
                }   
                Excel::create('order_report', function($excel) use ($order_array){
                  $excel->setTitle('order_report');
                  $excel->sheet('order_report', function($sheet) use ($order_array){
                   $sheet->fromArray($order_array, null, 'A1', false, false);
                  });
                })->download('csv');
        }

        return view('admin.orders.index',compact('data','deliveryboy','locations'));
    }

    function convertUserDataPdfHtml($datas)
    {
        $output = '
         <style>
            table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            }
        </style>
         <h3 align="center">Orders Report</h3>
         <table>
          <tr>
            <th>User Info</th>
            <th>Product Info</th>
            <th>Transaction Info</th>
         </tr>
         '; 
        foreach($datas as $data) {
            if ($data->amount_paid == "0") {
                $amount_paid = "PENDING";
            }
            else{
                $amount_paid = "PAID";
            }

            $orderitems = DB::table('orderlists')
                ->join('products','products.id','orderlists.item_id')
                ->select('orderlists.*','products.name as itemname')
                ->where('orderlists.order_id',$data->order_id)
                ->get();

            $address = Address::where('id',$data->user_address_id)->first();
            $output .= '<tr>';
                $output .= '<td>'."<b>Name</b> - ".$address->name.", <b>Phone</b> - ".$address->phone.", <b>Email</b> - ".$address->email.", <b>Location</b> - ".$address->location.", <b>Additional Address</b> - ".$address->additional_address.", <b>Address Type</b> - ".$address->address_type.'</td>';

                $output .= '<td>';
                 if (count($orderitems)) {
                    foreach($orderitems as $orderitem)
                    {
                        $output .= "<b>Product Name</b> -".$orderitem->itemname . ", <b>Item Quantity</b> - ".$orderitem->item_quantity.", <b>Selling Price</b> - ".$orderitem->item_selling_price;
                    }
                 } else {
                    $output .= '---';
                 }
                $output .= '</td>';

                $output .= '<td>'."<b>Order Id</b> - ".$data->order_id.", <b>Order Status</b> - ".$data->status.", <b>Payment Type</b> - ".$data->payment_type.", <b>Amount</b> - ".$data->order_price.", <b>Has Paid</b> - ". $amount_paid .'</td>';


        }
        $output .= '</table>';
        return $output;
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
        $orderitems = $user = $address = array();
        $order = Order::where('order_id',$request->order_id)->first();
        if(isset($order->id)){
               $orderitems = DB::table('orderlists')
                ->join('products','products.id','orderlists.item_id')
                ->select('orderlists.*','products.name as itemname')
                ->where('orderlists.order_id',$order->order_id)
                ->get();
            $user = User::where('id',$order->user_id)->first();
            $address = Address::where('id',$order->user_address_id)->first();
        }
        // print_r($orders);exit();
        return view('admin.orderDetails.orderdetail',compact('orderitems','order','user', 'address'));
    }
}
