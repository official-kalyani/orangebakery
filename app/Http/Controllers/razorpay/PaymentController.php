<?php

namespace App\Http\Controllers\razorpay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Orderlist;
use App\Models\ProductPrice;
use App\Models\Address;
use App\Models\Cbcoin;
use App\Models\Coupon;
use App\Models\CustomizeImage;
use App\Models\Notification;
use Carbon\Carbon;
use Redirect,Response;
use App\User;
use App\Models\Location;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use Auth;
use Razorpay\Api\Api;
use DB;

class PaymentController extends Controller
{
	public function checkout(Request $request)
	{              
            if (Auth::check()) {
                $plusCoins = Cbcoin::where('user_id',Auth::user()->id)->where('type','PLUS')->where('status',1)->get()->sum("coins");
                $minusCoins = Cbcoin::where('user_id',Auth::user()->id)->where('type','MINUS')->where('status',3)->get()->sum("coins");
                $coins = $plusCoins - $minusCoins;
		$delivery_charge = $this->getDeliveryCharge();
		$addresses = Address::where('user_id',Auth::user()->id)->get();
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
		return view('checkoutnew',compact('addresses','delivery_charge','coins','addOnProducts'));
            } else {
                return redirect('/');
                exit();
            }
	}
        public function redeemCoins(Request $request){
            $arr = array();
            if (Auth::check()) {
                $plusCoins = Cbcoin::where('user_id',Auth::user()->id)->where('type','PLUS')->where('status',1)->get()->sum("coins");
                $minusCoins = Cbcoin::where('user_id',Auth::user()->id)->where('type','MINUS')->where('status',3)->get()->sum("coins");
                if(($request->mycoins == $plusCoins - $minusCoins) && $request->redeemcoin <= 100){ 
                    $applied_coin_amount_text = ($request->redeemcoin*50)/100;
                    if(is_float($applied_coin_amount_text)){
                        $applied_coin_amount_text = $applied_coin_amount_text . '0';
                    } else {
                        $applied_coin_amount_text = $applied_coin_amount_text . '.00';
                    }
                    $arr['applied_coins'] = $request->redeemcoin;
                    $arr['applied_coin_amount_text'] = $applied_coin_amount_text;
                    $arr['applied_coin_amount'] = ($request->redeemcoin*50)/100;
                    $arr['success'] = true;
                    $arr['message'] = "Your ".$request->redeemcoin." coins has been redeemed successfully.";   
                } else {
                   $arr['success'] = false;
                   $arr['message'] = "You can redeem maximum 100 coins per order."; 
                }                
            } else {
                $arr['success'] = false;
                $arr['message'] = "You are not a authorized user to access this page.";
            }
            return response()->json($arr);
        }
	public function redirectTocheckout(Request $request)
	{
		return redirect('/cart');
	}
        public function placeOrder(Request $request){
            if(isset(Auth::user()->id)){
                $deliveryCharge = $this->getDeliveryCharge();
                $store_id = $request->cookie('store_id');
                $type = $request->cookie('type');
                $subTotal = array();
                if (isset($request->address_id_old) && $request->address_id_old !='') {
                    $address = Address::find($request->address_id_old);
                } else {
                    $address = new Address;
                    $address->user_id = Auth::user()->id;
                }
                $address->additional_address = $request->long_address;
                $address->location = $request->short_address;
                $address->address_type = $request->address_type;
                $address->name = $request->name;
                $address->email = $request->email;
                $address->pincode = $request->pincode;
                $address->phone = $request->phone;
                $address->has_default = $request->has_default;
                $address->save(); 
                
                // $order_id = uniqid('OBORD');
                $permitted_chars = '0123456789';
                $order_id = 'OBA'.substr(str_shuffle($permitted_chars), 0, 4);
                $order = new Order;
                $order->user_id = Auth::user()->id;
                $order->order_id = $order_id;
                $order->user_address_id = $address->id;
                $order->status = "order_received";
                $order->countdowm_time = Carbon::now()->addMinutes(5);
                $order->type = @$type;
                $order->store_id = @$store_id;
                $order->takeaway_to_delivery = $request->change_takeaway_to_delivery;
                $order->save(); 
                
                foreach((array) session('cart') as $item){
                    if($type == 'pickup' && $store_id !='') {
                        $product = Product::where('id',$item['parent_product_id'])->first();
                     } else {
                       $product = Product::where('id',$item['id'])->first();		               
                     }
                    $product_price = \App\Models\ProductPrice::where('product_id',$product->id)->where('show_price',1)->first();
                    $subTotal[] = $product_price->price;
                    $orderList = new Orderlist;
                    $orderList->user_id = Auth::user()->id;
                    $orderList->order_id = $order_id;
                    $orderList->item_id = $item['id'];
                    $orderList->parent_product_id = $item['parent_product_id'];
                    $orderList->item_selling_price = $product_price->price;
                    $orderList->item_mrp_price = $product_price->mrp_price;
                    $orderList->item_quantity = $item['quantity'];
                    $orderList->price_id = $product_price->id;
                    $orderList->cart_status = "1";
                    $orderList->save();
                }
                $sub_total_amount = array_sum($subTotal);
                $coupon_id = $coupon_code = '' ; $coupon_amount = $applied_coin_amount = 0;
                if (isset($request->couponcode) && $request->couponcode!='') {
                    $retCoupon = $this->getCouponAmount($request->couponcode,$sub_total_amount);
                    if(!empty($retCoupon)){
                        $coupon_id = $retCoupon['coupon_id'];
                        $coupon_code = $retCoupon['coupon_code'];
                        $coupon_amount = $retCoupon['coupon_amount'];
                    }
                }
                if (isset($request->user_applied_coins) && $request->user_applied_coins!='') {
                   $applied_coin_amount = ($request->user_applied_coins*50)/100;
                }
                
                $payable_price = ($sub_total_amount+$deliveryCharge)-($coupon_amount+$applied_coin_amount);
                $order->order_price = $sub_total_amount;
                $order->payable_price = $payable_price;
                $order->coupon_id = $coupon_id; 
                $order->coupon_code = $coupon_code;
                $order->coupon_amount = $coupon_amount;
                $order->cbcoin_amount = $applied_coin_amount;
                $order->delivery_charge = $deliveryCharge;
                
                if ($request->payment_type == "card") {
                  $order->payment_type = "online";
                  $order->razorpay_payment_id = $request->razorpay_payment_id;
                  $api = new Api(config('custom.razor_key'), config('custom.razor_secret'));
                  $payment = $api->payment->fetch($request->razorpay_payment_id);
                  if(($payment['status']=='authorized' && $request->razorpay_payment_id==$payment['id']) && ($payment['amount'] / 100) == $payable_price){                  
                      $order->payment_status = "success";
                  }else {
                      $order->payment_status = "failed";
                  }
                } else {
                   $order->payment_type = "cod"; 
                }
                $order->save();
                
                if (isset($request->user_applied_coins) && $request->user_applied_coins!='') {
                    $cbCoin = new Cbcoin;
                    $cbCoin->order_id = $order->order_id;
                    $cbCoin->coins = $request->user_applied_coins;
                    $cbCoin->user_id = Auth::user()->id;
                    $cbCoin->type = "MINUS";
                    $cbCoin->status = 3;//Pedning
                    $cbCoin->expiry_at = Carbon::now()->addMonth();
                    $cbCoin->save();
                }
                if (isset($order->order_id) && $order->order_id!='') {
                    $cbCoin = new Cbcoin;
                    $cbCoin->order_id = $order->order_id;
                    $cbCoin->coins = ($sub_total_amount*10)/100;
                    $cbCoin->user_id = Auth::user()->id;
                    $cbCoin->type = "PLUS";
                    $cbCoin->status = 2;//Pedning
                    $cbCoin->expiry_at = Carbon::now()->addMonth();
                    $cbCoin->save();
                }
                $notification = new Notification;
                $notification->order_id = $order->order_id;
                $notification->status = 'unread';
                $notification->save();
                
                $request->session()->flush();
                
                $arr = array('msg' => 'Order placed successfully.', 'status' => true);
			
            } else {
                $arr = array('msg' => 'Authentication Error', 'status' => true);
            }
          return Response()->json($arr);
        }

        public function pay_success(Request $request){
		$store_id = $request->cookie('store_id');
                $type = $request->cookie('type');
		if ($request->payment_type == "cash_on_delivery") {
			// print_r($_REQUEST);
			// exit();
			$coupon = Coupon::where('coupon_code',@$request->couponcode)->first();
			if ($coupon) {
				$coupon_id = $coupon->id;
			}
			else{
				$coupon_id = NULL;
			}
			
			$address_id = $request->address_id_old;
			$deliveryCharge = $request->delivery_charge;
			$coupon_amount = $request->coupon_amount;
			$cbcoinsDiscount = $request->cbcoins_used;

			foreach((array) session('cart') as $id => $details){
				$product_price = ProductPrice::
				where('id',$details['price_id'])->first();
				$totalAmount[] = $product_price->price;
			}
			$finalAmount = $deliveryCharge+array_sum($totalAmount)-$coupon_amount-$cbcoinsDiscount;

			$order_id = uniqid();
			$order = new Order;
			$order->user_id = Auth::user()->id;
			$order->order_id = $order_id;
			$order->coupon_id = $coupon_id; 
			$order->user_address_id = $address_id;
			$order->status = "order_received";
			$order->payment_type = "cod";
			$order->amount_paid = "0";
			$order->save();

			$order->order_price = array_sum($totalAmount);
			$order->payable_price = $finalAmount;
			$order->coupon_amount = $request->coupon_amount;
			$order->delivery_charge = $deliveryCharge;
			$order->cbcoin_amount = $cbcoinsDiscount;

			if ($type == "delivery" && $type == "") {
				$order->type = "delivery";
			}
			else if ($request->type == "takeaway") {
				$order->type = "takeaway";
				$order->store_id = $store_id;
			}
			$order->countdowm_time = Carbon::now()->addMinutes(5);
			$order->save();

			$notification = new Notification;
			$notification->order_id = $order->order_id;
			$notification->status = 'unread';
			$notification->save();

			foreach((array) session('cart') as $id => $details){
				$customizeImage = CustomizeImage::where('user_id',Auth::user()->id)->where('product_id',$details['id'])->first(); 

				$cake_price = ProductPrice::where('id',$details['price_id'])->first(); 
		        $orderList = new Orderlist;
		        $orderList->user_id = Auth::user()->id;
		        $orderList->order_id = $order_id;
		        $orderList->item_id = $details['id'];
		        $orderList->item_selling_price = $cake_price->price;
		        $orderList->item_mrp_price = $cake_price->mrp_price;
		        $orderList->item_quantity = $details['quantity'];
		        $orderList->price_id = $product_price->id;
		        $orderList->cart_status = "1";
		        $orderList->save();
		        if ($customizeImage) {
		        	$orderList->customize_image = $customizeImage->id;
		        }
		        else{
		        	$orderList->customize_image = NULL;
		        }
				$orderList->save();
		    }

		    $request->session()->flush();

	        //$htmlCart = view('_header_cart')->render();
		    if (isset($request->address_id_old) && $request->address_id_old !='') {
		    	$address = Address::find($request->address_id_old);
		    }
		    else{
		    	$address = new Address;
		    	$address->user_id = Auth::user()->id;
		    }
			$address->additional_address = $request->long_address;
			$address->location = $request->short_address;
			$address->address_type = $request->address_type;
			$address->name = $request->name;
			$address->email = $request->email;
			$address->pincode = $request->pincode;
			$address->phone = $request->phone;
			$address->has_default = $request->has_default;
			$address->save();

			if (isset($request->cbcoins_used) && $request->cbcoins_used!='') {
          		$cbCoin = new Cbcoin;
          		$cbCoin->order_id = $order->order_id;
	          	$cbCoin->coins = $request->cbcoins_used;
	          	$cbCoin->user_id = Auth::user()->id;
	          	$cbCoin->type = "MINUS";
	          	$cbCoin->status = 3;//Pedning
	          	$cbCoin->expiry_at = Carbon::now()->addMonth();
	          	$cbCoin->save();
          	}
			
			if (isset($request->coinswillAdded) && $request->coinswillAdded!='') {
				$cbCoin = new Cbcoin;
				$cbCoin->order_id = $order->order_id;
	          	$cbCoin->coins = $request->coinswillAdded;
	          	$cbCoin->user_id = Auth::user()->id;
	          	$cbCoin->type = "PLUS";
	          	$cbCoin->status = 2;//Pedning
	          	$cbCoin->expiry_at = Carbon::now()->addMonth();
	          	$cbCoin->save();
			}
          	
			$arr = array('msg' => 'Payment successful.', 'status' => true);
			return Response()->json($arr); 
		}
		else if ($request->payment_type == "razorpay") {
			// print_r($_REQUEST);
			// exit();
			if (isset($request->couponcode) && $request->couponcode!='') {
				$coupon = Coupon::where('coupon_code',@$request->couponcode)->first();
				$coupon_id = $coupon->id;
			}
			else{
				$coupon_id = NULL;
			}
			
			
			$address_id = $request->address_id_old;
			$deliveryCharge = $request->delivery_charge;
			$coupon_amount = $request->coupon_amount;
			$cbcoinsDiscount = $request->cbcoins_used;

			foreach((array) session('cart') as $id => $details){
				$product_price = ProductPrice::
				where('id',$details['price_id'])->first();
				$totalAmount[] = $product_price->price;
			}
			$finalAmount = $deliveryCharge+array_sum($totalAmount)-$coupon_amount-$cbcoinsDiscount;

			$order_id = uniqid();
			$order = new Order;
			$order->user_id = Auth::user()->id;
			$order->order_id = $order_id;
			$order->coupon_id = $coupon_id; 
			$order->user_address_id = $address_id;
			$order->status = "order_received";
			$order->payment_type = "online";
			$order->amount_paid = "1";
			$order->save();
			$order->order_price = array_sum($totalAmount);
			$order->payable_price = $finalAmount;
			$order->coupon_amount = $request->coupon_amount;
			$order->delivery_charge = $deliveryCharge;
			$order->cbcoin_amount = $cbcoinsDiscount;
			if ($type == "delivery" && $type == "") {
				$order->type = "delivery";
			}
			else if ($request->type == "takeaway") {
				$order->type = "takeaway";
				$order->store_id = $store_id;
			}
			$order->countdowm_time = Carbon::now()->addMinutes(5);
			$order->save();

			$notification = new Notification;
			$notification->order_id = $order->order_id;
			$notification->status = 'unread';
			$notification->save();

			foreach((array) session('cart') as $id => $details){
				$cake_price = ProductPrice::where('id',$details['price_id'])->first(); 
		        $orderList = new Orderlist;
		        $orderList->user_id = Auth::user()->id;
		        $orderList->order_id = $order_id;
		        $orderList->item_id = $details['id'];
		        $orderList->item_selling_price = $cake_price->price;
		        $orderList->item_mrp_price = $cake_price->mrp_price;
		        $orderList->item_quantity = $details['quantity'];
		        $orderList->price_id = $product_price->id;
		        $orderList->cart_status = "1";
		        $orderList->save();
		    }

                        $request->session()->flush();

                        if (isset($request->address_id_old) && $request->address_id_old !='') {
                            $address = Address::find($request->address_id_old);
                        }
                        else{
                            $address = new Address;
                            $address->user_id = Auth::user()->id;
                        }
			$address->additional_address = $request->long_address;
			$address->location = $request->short_address;
			$address->address_type = $request->address_type;
			$address->name = $request->name;
			$address->email = $request->email;
			$address->pincode = $request->pincode;
			$address->phone = $request->phone;
			$address->has_default = $request->has_default;
			$address->save();

			if (isset($request->cbcoins_used) && $request->cbcoins_used!='') {
                            $cbCoin = new Cbcoin;
                            $cbCoin->order_id = $order->order_id;
                            $cbCoin->coins = $request->cbcoins_used;
                            $cbCoin->user_id = Auth::user()->id;
                            $cbCoin->type = "MINUS";
                            $cbCoin->status = 3;
                            $cbCoin->expiry_at = Carbon::now()->addMonth();
                            $cbCoin->save();
                        }
			
			if (isset($request->coinswillAdded) && $request->coinswillAdded!='') {
                            $cbCoin = new Cbcoin;
                            $cbCoin->order_id = $order->order_id;
                            $cbCoin->coins = $request->coinswillAdded;
                            $cbCoin->user_id = Auth::user()->id;
                            $cbCoin->type = "PLUS";
                            $cbCoin->status = 2;
                            $cbCoin->expiry_at = Carbon::now()->addMonth();
                            $cbCoin->save();
			}
          	
			$arr = array('msg' => 'Payment successful.', 'status' => true);
			return Response()->json($arr); 
		}  
	}

	public function thank_you()
	{
		return view('thankyou');
	}

	public function payment()
	{
		return view('payment');
	}
        public function getDeliveryCharge(){
            $deliveryCharge = 60;
            return $deliveryCharge;
        }
        public function getCouponAmount($couponcode, $sub_total){
              $arr = array();
               if (isset($couponcode) && $couponcode !=''){
                 $todayDate = date("Y-m-d");
                 $coupon = DB::table('coupons')            
                 ->where('coupon_code',$couponcode)
                 ->where('coupons.validity_till','>=',$todayDate)
                 ->where('coupons.minimum_order','<=',$sub_total)
                 ->select('coupons.*')
                 ->first();
               }
               if (isset($coupon->id) && $coupon->coupon_code == $couponcode) {
                    if ($coupon->discount_type == 'percentage') {
                        $discount_amount = ($sub_total * $coupon->discount_amount)/100;
                        $payable_amout = $sub_total - $discount_amount;
                    }
                    if ($coupon->discount_type == 'flat') {
                        $discount_amount = $coupon->discount_amount;
                        $payable_amout = $sub_total - $discount_amount;
                    }
                $arr['success'] = true;
                $arr['coupon_id'] = $coupon->id;
                $arr['coupon_amount'] = round($discount_amount);                
                $arr['coupon_code'] = $coupon->coupon_code;

            }
            return $arr;
        }
}