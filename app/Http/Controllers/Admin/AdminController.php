<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Admin;
use Carbon\Carbon;
use App\Models\Cbcoin;
use App\Models\Order;
use App\User;
use App\Models\Orderlist;
use Auth;

class AdminController extends Controller
{
    public function contacts()
    {
    	$data = Contact::orderBy('id','DESC')->paginate(10);
        return view('admin.contacts.index',compact('data'));
    }

    public function destroyContacts(Request $request)
    {
        Contact::destroy($request->id);
        return back()->with('flash_success', 'Contact Message Deleted successfully');
    }
    
    public function newsletter()
    {
        $data = Newsletter::orderBy('id','DESC')->paginate(10);
        return view('admin.newsletter.index',compact('data'));
    }

    public function customers()
    {
    	$data = User::orderBy('id','DESC')->paginate(10);
        return view('admin.customers.index',compact('data'));
    }

    public function changePassword()
    {
        $admin = Admin::where('id',Auth::user()->id)->first();
        return view('admin.change-password.index',compact('admin'));
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
            $admin = Admin::where('id',Auth::user()->id)->first();
            $admin->name = $request->name;
            $admin->password = bcrypt($request->password);
            $admin->save();
            return back()->with('flash_success', 'Password Changed');
        }
    }

    public function customerObcoins()
    {
        $coins = Cbcoin::orderBy('id','DESC')->paginate(10);
        return view('admin.customer-obcoins.index',compact('coins'));
    }

    public function home()
    {
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

        return view('admin.home',compact('todaySale','totalSale','todayDelivered','totalDelivered','todayCustomer','totalCustomer','todayCancel','totalCancel','todayCollection','totalCollection','todayOrder','totalOrder'));
    }
}
