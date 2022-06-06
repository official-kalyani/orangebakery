<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cake;
use App\Models\CakeImage;
use App\Models\CakePrice;
use App\Models\Product;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Section;
use App\Models\Slider;
use App\Models\Occasion;
use App\Models\Newsletter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Mail\LoginOtpEmail;
use App\Mail\SignupOtpEmail;
use App\Models\Cbcoin;
use App\Models\Testimonial;
use Carbon\Carbon;
use App\Shop;
use Session;
use App\User;
use DB;
use Auth;
use Mail;

class HomeController extends Controller
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
    public function getDynamicStore(Request $request)
    {
    	$output ="";
     	$select = $request->get('select');
     	$value = $request->get('value');
     	$data = Shop::where($select, $value)->get();
     	$output .= '<option value="">Select Store</option>';
	    foreach($data as $row)
	    {
	      $output .= '<option value="'.$row->id.'">'.$row->shop_name.'</option>';
	    }
	    echo $output;
    }

    public function getDynamicStoreAddress(Request $request)
    {
      $output ="";
      $select = $request->get('select');
      $value = $request->get('value');
      $data = Shop::where('id', $value)->first();
      //$output .= '<span><strong>Takeaway Your Order from </strong></span>';
      $output .= '<br>';
      $output .= '<span>'.$data->long_address.'</span>';
      $output .= '<br>';
      $output .= '<form method="post" action="'.url("setCookie").'">';
      $output .= '<input type="hidden" name="_token" value="'.csrf_token().'">';
      $output .= '<input type="hidden" value="'.$data->id.'" name="store_id">';
      $output .= '<input type="hidden" value="pickup" name="type">';
      $output .= '<button class="btn cart-btn proceed">PROCEED</button>';
      $output .= '</form>';
      echo $output;
    }

    public function setCookie(Request $request){
        if($request->cookie('type') != $request->type) {
          Session::flush();
        }
        if(isset($request->store_id) && $request->cookie('store_id') != $request->store_id){
          Session::flush();
        }     
      	$type = $request->type;
      	$minutes = 9999999999999999;
        if ($type == "pickup") {
            return redirect()->back()
            ->withCookie(cookie('store_id', $request->store_id, $minutes))
            ->withCookie(cookie('type', $type, $minutes));          
        }
        if ($type == "delivery") {
            return redirect()->back()->withCookie(cookie('type', $type, $minutes));
        }
    }

    public function checkCoupon(Request $request)
    {
        $arr = array();
        $todayDate = date("Y-m-d");
        $sub_total = $request->sub_total;
        if (isset($request->couponcode) && $request->couponcode !=''){
            $couponcode = DB::table('coupons')            
            ->where('coupon_code',$request->couponcode)
            ->where('coupons.validity_till','>=',$todayDate)
            ->where('coupons.minimum_order','<=',$sub_total)
            ->select('coupons.*')
            ->first();
        }
        if (isset($couponcode->id) && $couponcode->coupon_code === $request->couponcode) {
            if ($couponcode->discount_type == 'percentage') {
                $discount_amount = ($sub_total * $couponcode->discount_amount)/100;
                $payable_amout = $sub_total - $discount_amount;
            }
            if ($couponcode->discount_type == 'flat') {
                $discount_amount = $couponcode->discount_amount;
                $payable_amout = $sub_total - $discount_amount;
            }
            $arr['discount_amount'] = round($discount_amount);
            $arr['discount_type'] = $couponcode->discount_type;
            $arr['payable_amout'] = round($payable_amout);
            $arr['success'] = true;
            $arr['status_code'] = "200";
            $arr['total_amount'] = $sub_total;
            $arr['message'] = "Coupon Applied";
            $arr['coupon_code'] = $couponcode->coupon_code;

        }
        else
        {
            $arr['total_amount'] = $sub_total;
            $arr['success'] = false;
            $arr['status_code'] = "400";
            $arr['message'] = "Not a valid Coupon";
            $arr['coupon_code'] = $request->couponcode;
        }
        return response()->json($arr);
    }

    public function userSignUp(Request $request)
    {
      $arr = array();
      $checkEmail = User::where('email',$request->email)->first();
      $checkPhone = User::where('phone',$request->phone)->first();
      if ($checkEmail) {
        $ret['message'] = "Email Id Already Exists";
        return response()->json($ret);
      }
      if ($checkPhone) {
        $ret['message'] = "Mobile no Already Exists";
        return response()->json($ret);
      }
      else{
          $user = new User;
          $user->name = $request->name;
          $user->email = $request->email;
          $user->phone = $request->phone;
          $user->password = $request->password;
          $user->last_used_platform = "WEB";
          $user->save();
          $cbCoin = new Cbcoin;
          $cbCoin->user_id = $user->id;
          $cbCoin->coins = 50;
          $cbCoin->type = "PLUS";
          $cbCoin->save();
          $cbCoin->expiry_at = Carbon::now()->addMonth();
          $cbCoin->save();
          $curl = curl_init();
          $otp_code = mt_rand(100000, 999999);
          $msg = urlencode("Your Verification code is ".$otp_code);
          $mobileno = "91".$user->phone;
          $authkey = '324839AfzulCKGe5e7de4baP1';
          $senderid = 'RIXOSY';
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?mobiles=$mobileno&authkey=$authkey&route=4&sender=$senderid&message=$msg&country=91",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_SSL_VERIFYHOST => 0,
                 CURLOPT_SSL_VERIFYPEER => 0,
          ));
          $response = curl_exec($curl);
          $err = curl_error($curl);
          curl_close($curl);
          $arr = array();
          if(!$err){
             $arr['status'] = true;
             $arr['otp_code'] = $otp_code;
             $arr['otp_response'] = $response;
          } else {
             $arr['status'] = false;
             $arr['otp_code'] = $otp_code;
             $arr['otp_response'] = $response;
          }
          $user->otp = $arr['otp_code'];
          $data = ['name' => $user->name , 'otp' => $otp_code];
          Mail::to($user->email)->send(new SignupOtpEmail($data));
          if ($user->save()) 
          {
            $ret['user_id'] = $user->id;
            //$ret['otp_code'] = $user->otp;
            $ret['otp_response'] = $arr['otp_response'];
            $ret['statusCode'] = (string) 200;
            $ret['status'] = "success";
            $ret['message'] = "User Signed Up";
            return response()->json($ret, $ret['statusCode']);
          }
          else
          {
            $ret['statusCode'] = (string) 400;
            $ret['status'] = "failure";
            $ret['message'] = "Something went wrong";
            return response()->json($ret, $ret['statusCode']);
          }
      }
    }

    public function validateOtp(Request $request)
    {
      $otp = $request->otp;
      $user_id = $request->user_id;
      $user = User::find($user_id);
      if ($user) {
        if ($user->otp == $otp) {
          $user->has_verified = 1;
          $user->access_token = md5(uniqid(rand().time(), true));
          $user->save();
          Auth::login($user, true);
          $ret['status'] = 1;
          $ret['message'] = "Otp Matched";
          return response()->json($ret);
        }
        else{
          $ret['status'] = 0;
          $ret['message'] = "Please Enter Correct Otp";
          return response()->json($ret);
        }
      }
    }

    public function validateForgotPasswordOtp(Request $request)
    {
      $otp = $request->otp;
      $user_id = $request->user_id;
      $user = User::find($user_id);
      if ($user) {
        if ($user->otp == $otp) {
          $user->has_verified = 1;
          $user->save();
          //Auth::login($user, true);
          $ret['user_id'] = $user->id;
          $ret['status'] = 1;
          $ret['message'] = "Otp Matched";
          return response()->json($ret);
        }
        else{
          $ret['status'] = 0;
          $ret['message'] = "Please Enter Correct Otp";
          return response()->json($ret);
        }
      }
    }

    public function loginWithOTP(Request $request)
    {
      if(is_numeric($request->email)){
        if (strlen($request->email) > 10) {
          $ret['message'] = "Mobile Number must be 10 charachters";
          return response()->json($ret);
        }
        elseif (strlen($request->email) < 10) {
          $ret['message'] = "Mobile Number must be 10 charachters";
          return response()->json($ret);
        }
        elseif (strlen($request->email) == 10) {
          $user = User::where('phone',$request->email)->first();
          $phone = $user->phone;
          $user = User::where('phone',$phone)->first();
          if ($user) {
            $curl = curl_init();
            $otp_code = mt_rand(100000, 999999);
            $msg = urlencode("Your Verification code is ".$otp_code);
            $mobileno = "91".$user->phone;
            $authkey = '324839AfzulCKGe5e7de4baP1';
            $senderid = 'RIXOSY';
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?mobiles=$mobileno&authkey=$authkey&route=4&sender=$senderid&message=$msg&country=91",
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => "",
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 30,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => "GET",
                   CURLOPT_SSL_VERIFYHOST => 0,
                   CURLOPT_SSL_VERIFYPEER => 0,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $arr = array();
            if(!$err){
               $arr['status'] = true;
               $arr['otp_code'] = $otp_code;
               $arr['otp_response'] = $response;
            } else {
               $arr['status'] = false;
               $arr['otp_code'] = $otp_code;
               $arr['otp_response'] = $response;
            }
            $user->otp = $arr['otp_code'];
            if ($user->save()) 
            {
              $ret['user_id'] = $user->id;
              $ret['status'] = 1;
              $ret['message'] = "Otp Sent";
              $ret['sending_id'] = "phone";
              return response()->json($ret);
            }
            else
            {
              $ret['status'] = 0;
              $ret['message'] = "Something Went Wrong";
              return response()->json($ret);
            }
          }
          else
          {
            $ret['status'] = 0;
            $ret['message'] = "Mobile not Found in serve";
            return response()->json($ret);
          }
        }
      }

      elseif (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        $user = User::where('email',$request->email)->first();
        if ($user) {
          $otp_code = mt_rand(100000, 999999);
          $data = ['name' => $user->name , 'otp' => $otp_code];;
          Mail::to($user->email)->send(new LoginOtpEmail($data));
          $user->otp = $otp_code;
          if ($user->save()) 
          {
            $ret['user_id'] = $user->id;
            $ret['status'] = 1;
            $ret['message'] = "Otp Sent";
            $ret['sending_id'] = "email";
            return response()->json($ret);
          }
          else
          {
            $ret['status'] = 0;
            $ret['message'] = "Something Went Wrong";
            return response()->json($ret);
          }
        }
        else{
          $ret['message'] = "Email Not Found in server";
          return response()->json($ret);
        }
      }
    }

    public function ForgotPasswordOtp(Request $request)
    {
      if(is_numeric($request->email)){
        if (strlen($request->email) > 10) {
          $ret['message'] = "Mobile Number must be 10 charachters";
          return response()->json($ret);
        }
        elseif (strlen($request->email) < 10) {
          $ret['message'] = "Mobile Number must be 10 charachters";
          return response()->json($ret);
        }
        elseif (strlen($request->email) == 10) {
          $user = User::where('phone',$request->email)->first();
          $phone = $user->phone;
          $user = User::where('phone',$phone)->first();
          if ($user) {
            $curl = curl_init();
            $otp_code = mt_rand(100000, 999999);
            $msg = urlencode("Your Verification code is ".$otp_code);
            $mobileno = "91".$user->phone;
            $authkey = '324839AfzulCKGe5e7de4baP1';
            $senderid = 'RIXOSY';
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?mobiles=$mobileno&authkey=$authkey&route=4&sender=$senderid&message=$msg&country=91",
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_ENCODING => "",
                   CURLOPT_MAXREDIRS => 10,
                   CURLOPT_TIMEOUT => 30,
                   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                   CURLOPT_CUSTOMREQUEST => "GET",
                   CURLOPT_SSL_VERIFYHOST => 0,
                   CURLOPT_SSL_VERIFYPEER => 0,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $arr = array();
            if(!$err){
               $arr['status'] = true;
               $arr['otp_code'] = $otp_code;
               $arr['otp_response'] = $response;
            } else {
               $arr['status'] = false;
               $arr['otp_code'] = $otp_code;
               $arr['otp_response'] = $response;
            }
            $user->otp = $arr['otp_code'];
            if ($user->save()) 
            {
              $ret['user_id'] = $user->id;
              $ret['status'] = 1;
              $ret['message'] = "Otp Sent";
              $ret['sending_id'] = "phone";
              return response()->json($ret);
            }
            else
            {
              $ret['status'] = 0;
              $ret['message'] = "Something Went Wrong";
              return response()->json($ret);
            }
          }
          else
          {
            $ret['status'] = 0;
            $ret['message'] = "Mobile not Found in serve";
            return response()->json($ret);
          }
        }
      }

      elseif (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        $user = User::where('email',$request->email)->first();
        if ($user) {
          $otp_code = mt_rand(100000, 999999);
          $data = ['name' => $user->name , 'otp' => $otp_code];;
              Mail::to($user->email)->send(new LoginOtpEmail($data));
          $user->otp = $otp_code;
          if ($user->save()) 
          {
            $ret['user_id'] = $user->id;
            $ret['status'] = 1;
            $ret['message'] = "Otp Sent";
            $ret['sending_id'] = "email";
            return response()->json($ret);
          }
          else
          {
            $ret['status'] = 0;
            $ret['message'] = "Something Went Wrong";
            return response()->json($ret);
          }
        }
        else{
          $ret['message'] = "Email Not Found in server";
          return response()->json($ret);
        }
      }
    }

    public function changePassword(Request $request)
    {
      $user_id = $request->user_id;
      $user = User::find($user_id);
      $user->password = $request->password;
      $user->save();
      Auth::login($user, true);
      $ret['status'] = 1;
      $ret['message'] = "Password Changed";
      return response()->json($ret);
    }

    public function resendOtp(Request $request)
    {
      $user_id = $request->user_id;
      $user = User::find($user_id);
      if ($user) {
        if ($request->sending_id == "email") {
          $otp_code = mt_rand(100000, 999999);
          $data = ['name' => $user->name , 'otp' => $otp_code];;
              Mail::to($user->email)->send(new LoginOtpEmail($data));
          $user->otp = $otp_code;
          if ($user->save()) 
          {
            $ret['user_id'] = $user->id;
            $ret['status'] = 1;
            $ret['message'] = "Otp Sent";
            $ret['sending_id'] = "email";
            return response()->json($ret);
          }
          else
          {
            $ret['status'] = 0;
            $ret['message'] = "Something Went Wrong";
            return response()->json($ret);
          }
        }
        if ($request->sending_id == "phone") {
          $curl = curl_init();
          $otp_code = mt_rand(100000, 999999);
          $msg = urlencode("Your Verification code is ".$otp_code);
          $mobileno = "91".$user->phone;
          $authkey = '324839AfzulCKGe5e7de4baP1';
          $senderid = 'RIXOSY';
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?mobiles=$mobileno&authkey=$authkey&route=4&sender=$senderid&message=$msg&country=91",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_SSL_VERIFYHOST => 0,
                 CURLOPT_SSL_VERIFYPEER => 0,
          ));
          $response = curl_exec($curl);
          $err = curl_error($curl);
          curl_close($curl);
          $arr = array();
          if(!$err){
             $arr['status'] = true;
             $arr['otp_code'] = $otp_code;
             $arr['otp_response'] = $response;
          } else {
             $arr['status'] = false;
             $arr['otp_code'] = $otp_code;
             $arr['otp_response'] = $response;
          }
          $user->otp = $arr['otp_code'];
          if ($user->save()) 
          {
            $ret['user_id'] = $user->id;
            $ret['status'] = 1;
            $ret['message'] = "Otp Sent";
            return response()->json($ret);
          }
          else
          {
            $ret['status'] = 0;
            $ret['message'] = "Something Went Wrong";
            return response()->json($ret);
          }
        }
      }
    }

    public function signupResendOtp(Request $request)
    {
      $user_id = $request->user_id;
      $user = User::find($user_id);
      if ($user) {
          $curl = curl_init();
          $otp_code = mt_rand(100000, 999999);
          $msg = urlencode("Your Verification code is ".$otp_code);
          $mobileno = "91".$user->phone;
          $authkey = '324839AfzulCKGe5e7de4baP1';
          $senderid = 'RIXOSY';
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?mobiles=$mobileno&authkey=$authkey&route=4&sender=$senderid&message=$msg&country=91",
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING => "",
                 CURLOPT_MAXREDIRS => 10,
                 CURLOPT_TIMEOUT => 30,
                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST => "GET",
                 CURLOPT_SSL_VERIFYHOST => 0,
                 CURLOPT_SSL_VERIFYPEER => 0,
          ));
          $response = curl_exec($curl);
          $err = curl_error($curl);
          curl_close($curl);
          $arr = array();
          if(!$err){
             $arr['status'] = true;
             $arr['otp_code'] = $otp_code;
             $arr['otp_response'] = $response;
          } else {
             $arr['status'] = false;
             $arr['otp_code'] = $otp_code;
             $arr['otp_response'] = $response;
          }
          $user->otp = $arr['otp_code'];
          if ($user->save()) 
          {
            $ret['user_id'] = $user->id;
            $ret['status'] = 1;
            $ret['message'] = "Otp Sent";
            return response()->json($ret);
          }
          else
          {
            $ret['status'] = 0;
            $ret['message'] = "Something Went Wrong";
            return response()->json($ret);
        }
      }
    }

    public function loginWithPassword(Request $request)
    {
      if(is_numeric($request->email)){
        $user = User::where('phone',$request->email)->where('password',$request->password)->first();
      }

      elseif (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
        $user = User::where('email',$request->email)->where('password',$request->password)->first();
      }
      
      if ($user) 
      {
        Auth::login($user, true);
        $ret['status'] = 1;
        return response()->json($ret);
      }
      else
      {
        $ret['status'] = 0;
        $ret['message'] = "Email,Phone or Password is incorrect";
        return response()->json($ret);
      }
    }

    // public function loginUserOtp(Request $request)
    // {
    //   if(is_numeric($request->email)){
    //     $user = User::where('phone',$request->email)->where('password',$request->password)->first();
    //   }

    //   elseif (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
    //     $user = User::where('email',$request->email)->where('password',$request->password)->first();
    //   }
      
    //   if ($user) 
    //   {
    //     Auth::login($user, true);
    //     $ret['status'] = 1;
    //     return response()->json($ret);
    //   }
    //   else
    //   {
    //     $ret['status'] = 0;
    //     $ret['message'] = "Email,Phone or Password is incorrect";
    //     return response()->json($ret);
    //   }
    // }

    public function saveContact(Request $request)
    {
      $this->validate($request, [
          'firstname' => 'required',
          'lastname' => 'required',
          'email' => 'required',
          'phone' => 'required',
      ]);

      $contact =  new Contact;
      $contact->firstname = $request->firstname;
      $contact->lastname = $request->lastname;
      $contact->email = $request->email;
      $contact->phone = $request->phone;
      $contact->message = $request->message;
      $contact->save();
      return back()->with('flash_success', 'Message Sent Successfully!');
    }

    public function subscribeNewsLetter(Request $request)
    {
      $checkEmail = Newsletter::where('email',$request->email)->first();
      if ($checkEmail) {
        $ret['message'] = "Already Subscribed";
        $ret['status'] = 0;
        return response()->json($ret);
      }
      else{
        $email = new Newsletter;
        $email->email = $request->email;
        $email->save();
        $ret['status'] = 1;
        return response()->json($ret);
      }
    }

    public function contact(Request $request)
    {
      //$request->session()->flush();
      return view('pages.contact');
    }
    public function disclaimer()
    {
      return view('pages.disclaimer');
    }
    public function privacyPolicy()
    {
      return view('pages.privacy-policy');
    }
    public function faq()
    {
      return view('pages.faq');
    }
    public function termsCondition()
    {
      return view('pages.terms-condition');
    }
    public function feedback()
    {
      return view('pages.feedback');
    }
    public function forgot()
    {
      return view('pages.forgot');
    }
    public function testimonials()
    {
      $title = "Testimonials | OrangeBakery";
      $testimonials = Testimonial::where('show_in_website_home','yes')->orderBy('id','desc')->paginate(7);
      return view('listing.testimonials',compact('testimonials','title'));
    }
}
