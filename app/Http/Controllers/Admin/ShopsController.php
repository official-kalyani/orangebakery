<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use App\Models\Location;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Shop::orderBy('id','DESC')->paginate(10);
        return view('admin.shops.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = Location::orderBy('id','DESC')->get();
        return view('admin.shops.create',compact('locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Shop::where('email', '=', Input::get('email'))->exists())
        {
           return back()->with('flash_error', 'Email id Already Exists');
        }
        else
        {
            $this->validate($request, [
                'shop_name' => 'required',
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'location' => 'required',
                'short_address' => 'required',
                'long_address' => 'required',
                'password' => ['required', 'min:6'],
            ]);

            $shop = new Shop();
            $shop->shop_name = $request->shop_name;
            $shop->name = $request->name;
            $shop->phone = $request->phone;
            $shop->email = $request->email;
            $shop->password = bcrypt($request->password);
            $shop->location = $request->location;
            $shop->short_address = $request->short_address;
            $shop->long_address = $request->long_address;
            $shop->save();
            return back()->with('flash_success', 'Shop Created Successfully!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $locations = Location::orderBy('id','DESC')->get();
        $data = Shop::findOrFail($id);
        return view('admin.shops.edit',compact('data','locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'shop_name' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'location' => 'required',
            'short_address' => 'required',
            'long_address' => 'required',
        ]);

        $shop = Shop::find($id);
        $shop->shop_name = $request->shop_name;
        $shop->name = $request->name;
        $shop->phone = $request->phone;
        $shop->email = $request->email;
        $shop->location = $request->location;
        $shop->short_address = $request->short_address;
        $shop->long_address = $request->long_address;
        $shop->save();
        return back()->with('flash_success', 'Shop Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Shop::findOrFail($id);
        $data->delete();
        return back()->with('flash_success', 'Shop Deleted Successfully!');
    }
}
