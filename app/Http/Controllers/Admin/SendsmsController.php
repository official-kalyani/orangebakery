<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Mail;
use App\Mail\EmailBlast;
//use GuzzleHttp\Client;
use Twilio\Rest\Client;



class SendsmsController extends Controller
{
    public function email()
    {
    	// return view('admin.email',compact('users'));
        return view('admin.email');
    }
    public function sms()
    {
    	return view('admin.sms');
    }
    public function pushNotofication()
    {
    	return view('admin.push-notification');
    }

    public function sendEmails(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'desc' => 'required',
        ]);

        $users = User::orderBy('id','DESC')->get();
        foreach ($users as $user) {
            $data = ['message' => 'This is a test!', 'name' => $user->name , 'subject' => $request->subject, 'desc' => $request->desc];;
            Mail::to($user->email)->send(new EmailBlast($data));
        }
        return back()->with('flash_success', 'Emails Send Successfully!');
    }

    public function sendSms(Request $request)
    {
        $this->validate($request, [
            'sms' => 'required',
            'phone' => 'required|regex:/[0-9]/'
        ]);

        // $account_sid = getenv("TWILIO_SID");
        // $auth_token = getenv("TWILIO_AUTH_TOKEN");
        // $twilio_number = getenv("TWILIO_NUMBER");
        // $client = new Client($account_sid, $auth_token);
        $phone = "+91".$request->phone;
        $body = $request->sms;
        $curl = curl_init();        
        $authkey = '324839AfzulCKGe5e7de4baP1';
          $senderid = 'RIXOSY';
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.msg91.com/api/sendhttp.php?mobiles=$phone&authkey=$authkey&route=4&sender=$senderid&message=$body&country=91",
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
          // if(!$err){
          //    $arr['status'] = true;
          //    $arr['otp_code'] = $otp_code;
          //    $arr['otp_response'] = $response;
          // } else {
          //    $arr['status'] = false;
          //    $arr['otp_code'] = $otp_code;
          //    $arr['otp_response'] = $response;
          // }
        // $client->messages->create($phone, ['from' => $twilio_number, 'body' => $request->sms] );

        return back()->with('flash_success', 'Message Send Successfully To '.$phone.'');
    }

    public function sendNotification(Request $request)
    {
        $this->validate($request, [
            'notification' => 'required',
        ]);

        return back()->with('flash_success', 'Notification Send Successfully To User !');
    }
}
