<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Coupon;

class CouponsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Coupon::orderBy('id','DESC')->paginate(10);
        return view('admin.coupons.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'coupon_code' => 'required',
            'coupon_heading' => 'required',
            //'coupon_desc' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required',
            'minimum_order' => 'required',
            'validity_till' => 'required',
        ]);

        if (Coupon::where('coupon_code', '=', $request->coupon_code)->count() > 0)
        {
           return back()->with('flash_error', 'Coupons Exists Can not Create Same Coupon Twice');
        }
        else
        {
            $user = new Coupon();

            $user->coupon_code = $request->coupon_code;
            $user->coupon_heading = $request->coupon_heading;
            $user->coupon_desc = $request->coupon_desc;
            $user->discount_type = $request->discount_type;
            $user->discount_amount = $request->discount_amount;
            $user->minimum_order = $request->minimum_order;
            $user->validity_till = $request->validity_till;

            $user->save();

            return back()->with('flash_success', 'Coupons added successfully');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Coupon::findOrFail($id);
        return view('admin.coupons.edit',compact('data'));
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
            'coupon_code' => 'required',
            'coupon_heading' => 'required',
            //'coupon_desc' => 'required',
            'discount_type' => 'required',
            'discount_amount' => 'required',
            'minimum_order' => 'required',
            'validity_till' => 'required',
        ]);

        $user = Coupon::find($id);
        $user->coupon_code = $request->coupon_code;
        $user->coupon_heading = $request->coupon_heading;
        $user->coupon_desc = $request->coupon_desc;
        $user->discount_type = $request->discount_type;
        $user->discount_amount = $request->discount_amount;
        $user->minimum_order = $request->minimum_order;
        $user->validity_till = $request->validity_till;

        $user->save();

        return back()->with('flash_success', 'Coupons added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Coupon::findOrFail($id);
        $data->delete();
        return back()->with('flash_success', 'Coupon Deleted Successfully!');
    }
}
