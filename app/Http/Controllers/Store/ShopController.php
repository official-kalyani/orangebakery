<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Shop;
use Auth;

class ShopController extends Controller
{
    public function changePassword()
    {
        $admin = Shop::where('id',Auth::user()->id)->first();
        return view('shop.change-password.index',compact('admin'));
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
            $admin = Shop::where('id',Auth::user()->id)->first();
            $admin->name = $request->name;
            $admin->password = bcrypt($request->password);
            $admin->save();
            return back()->with('flash_success', 'Password Changed');
        }
    }
}
