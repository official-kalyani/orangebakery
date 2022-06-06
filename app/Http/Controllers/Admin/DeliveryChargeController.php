<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Setting;

class DeliveryChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Setting::orderBy('id','DESC')->paginate(10);
        
       return view('admin.deliverycharge.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.deliverycharge.create');
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
            'deliverycharge' => 'required',
            'ordertime' => 'required',
            
        ]);

            $delcharge = new Setting();

            $delcharge->delivery_charge = $request->deliverycharge;
            $delcharge->ordertime = $request->ordertime;

            $delcharge->save();

             return redirect('/admin/deliverycharge/');
        
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
        $data = Setting::findOrFail($id);
        return view('admin.deliverycharge.edit',compact('data'));
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
            'deliverycharge' => 'required',
        ]);

        $user = Setting::find($id);

        $user->delivery_charge = $request->deliverycharge;
        $user->ordertime = $request->ordertime;
        $user->save();

        return redirect('/admin/deliverycharge');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Setting::findOrFail($id);
        $data->delete();
        return back()->with('flash_success', 'DeliveryCharges Deleted Successfully!');
    }

    public function add(Request $request, $id)
    {

    }
}
