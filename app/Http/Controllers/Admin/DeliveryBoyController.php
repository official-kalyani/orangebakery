<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;

class DeliveryBoyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DeliveryBoy::orderBy('id','DESC')->paginate(10);
        return view('admin.delivery-boy.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.delivery-boy.create');
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
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if (DeliveryBoy::where('email', '=', $request->email)->count() > 0)
        {
           return back()->with('flash_error', 'Email Already Exists');
        }
        if (DeliveryBoy::where('phone', '=', $request->phone)->count() > 0)
        {
           return back()->with('flash_error', 'Phone Number Already Exists');
        }
        else
        {
            $user = new DeliveryBoy();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            $user->save();

            return back()->with('flash_success', 'DeliveryBoy created successfully');
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
        $data = DeliveryBoy::findOrFail($id);
        return view('admin.delivery-boy.edit',compact('data'));
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
            'name' => 'required',
        ]);

        $user = DeliveryBoy::find($id);

        $user->name = $request->name;
        $user->save();

        return back()->with('flash_success', 'DeliveryBoy Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = DeliveryBoy::findOrFail($id);
        $data->delete();
        return back()->with('flash_success', 'DeliveryBoy Deleted Successfully!');
    }
}
