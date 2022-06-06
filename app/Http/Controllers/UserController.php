<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Orderlist;
use App\Models\Address;
use App\Models\Cbcoin;
use App\User;
use Auth;
use DB;

class UserController extends Controller
{
    public function order()
    {
        $orders = Order::where('user_id',Auth::user()->id)
        ->orderBy('id', 'DESC')->paginate(10);
    	return view('user.orderlists',compact('orders'));
    }

    public function orderdetails(Request $request)
    {
    	$user_id = Auth::user()->id;
    	$order_id = $request->order_id;
    	$order = Order::where('user_id',$user_id)->where('order_id',$order_id)->first();
    	if ($order) {
            if($order->type == 'pickup'){
                $orderitems = DB::table('orderlists')
                ->join('products','products.id','orderlists.item_id')
                ->join('product_images','product_images.product_id','orderlists.parent_product_id')
                ->where('product_images.is_featured',1)
                ->where('orderlists.order_id',$request->order_id)
                ->select('orderlists.*','products.name as itemname', 'product_images.images')
                ->get();
            } else {
               $orderitems = DB::table('orderlists')
                ->join('products','products.id','orderlists.item_id')
                ->join('product_images','product_images.product_id','orderlists.item_id')
                ->where('product_images.is_featured',1)
                ->where('orderlists.order_id',$request->order_id)
                ->select('orderlists.*','products.name as itemname', 'product_images.images')
                ->get();
            }
            $user = User::where('id',$order->user_id)->first();
            $address = Address::where('id',$order->user_address_id)->first();
	    return view('user.orderdetails',compact('order','orderitems','user','address'));
    	}
    	else{
    		abort('404');
    	}
    }

    public function profile()
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $addresses = Address::where('user_id',$user_id)->get();
        $singleAddress = Address::where('user_id',$user_id)->first();

        return view('user.profile',compact('user','addresses','singleAddress'));
    }

    public function updateProfile(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
        ]);

        if ($request->password != $request->confirm_password) {
            return back()->with('flash_danger', 'Password and Confirm Password Must be same!');
        }
        else{
            $admin = User::where('id',Auth::user()->id)->first();
            $admin->name = $request->name;
            if ($request->password != $request->confirm_password) {
                $admin->password = $request->password;
            }
            $admin->save();
            return back()->with('flash_success', 'Profile Updated');
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $arr = array();
        $arr['succ'] = 0; 

        $id = Auth::user()->id;
        $user = User::find($id);
        if (isset($_POST['image']) && $_POST['image']!='' && $_POST['has_image']!= '' && $_POST['has_image'] == 1) {
            $image = $request->image;
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $image = base64_decode($image);
            $image_name= time().'.png';
            $path = public_path('uploads/profile/'.$image_name);
            file_put_contents($path, $image);
            $user->avatar = $image_name;
            $user->save();
            $arr['succ'] = 1;
        } 
        
    }

    public function createAddress(Request $request)
    {
        $address = new Address();
        $address->name = $request->name;
        $address->email = $request->email;
        $address->address_type = $request->address_type;
        if ($request->has_default == 1) {
            if(Auth::check()) {
                $user_id = Auth::user()->id;
                DB::statement("
                    UPDATE addresses SET has_default = 0 WHERE user_id = $user_id
                ");
            }
            $address->has_default = $request->has_default;
        }
        else{
            $address->has_default = $request->has_default;
        }
        $address->location = $request->short_address;
        $address->additional_address = $request->additional_address;
        $address->pincode = $request->pincode;
        $address->user_id = Auth::user()->id;
        $address->phone = $request->phoneno;
        $address->save();
        $arr['succ'] = 1;
        return response()->json($arr);
    }

    public function fetchAddress(Request $request)
    {
        $address_id = $request->address_id;
        $address = Address::where('id',$address_id)->first();
        return $address;
    }

    public function fetchCheckoutAddress(Request $request)
    {
        
    }

    public function updateAddress(Request $request)
    {
        $address_id = $request->address_idd;
        $address = Address::where('id',$address_id)->first();
        $address->address_type = $request->address_type;
        if ($request->has_default == 1) {
            if(Auth::check()) {
                $user_id = Auth::user()->id;
                DB::statement("
                    UPDATE addresses SET has_default = 0 WHERE user_id = $user_id
                ");
            }
            $address->has_default = $request->has_default;
        }
        else{
            $address->has_default = $request->has_default;
        }
        $address->location = $request->short_address;
        $address->additional_address = $request->additional_address;
        $address->pincode = $request->pincode;
        $address->phone = $request->phoneno;
        $address->name = $request->name;
        $address->email = $request->email;
        $address->save();
        return back()->with('flash_success', 'Address Updated');
    }

    public function coins(Request $request)
    {
        // $date = today()->format('Y-m-d');
        // $cbcoins = Cbcoin::where('expiry_at', '<=', $date)->get();
        // foreach ($cbcoins as $cbcoin) {
        //     Cbcoin::where('id', $cbcoin->id)->update([
        //     'status' => 0
        //     ]);
        // }
        // exit();
        $user_id = Auth::user()->id;
        $coins = Cbcoin::where('user_id',$user_id)->get();
        $plusCoins = Cbcoin::where('user_id',$user_id)->where('type','PLUS')->get();
        $minusCoins = Cbcoin::where('user_id',$user_id)->where('type','MINUS')->get();
        if (count($minusCoins) > 0) {
            foreach ($minusCoins as $minusCoin) {
                $totalMinusCoin[] = $minusCoin->coins;
            }
            $minuscoin = array_sum($totalMinusCoin);
        }
        else{
            $minuscoin = 0;
        }
        foreach ($plusCoins as $plusCoin) {
            $totalPlusCoin[] = $plusCoin->coins;
        }
        
        $avilableCoins = array_sum($totalPlusCoin) - $minuscoin;
        return view('user.mycoins',compact('coins','avilableCoins'));
    }
}
