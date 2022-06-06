<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Obcoin;

class ManageObcoinsController extends Controller
{
    public function index()
    {
        $data = Obcoin::orderBy('id','DESC')->paginate(10);
        return view('admin.manage-obcoins.index',compact('data'));
    }

    public function edit($id)
    {
        $data = Obcoin::findOrFail($id);
        return view('admin.manage-obcoins.edit',compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'precentage' => 'required',
            'paisa' => 'required',
            'deliveryCharge' => 'required'
        ]);

        $user = Obcoin::find($id);
        $user->precentage = $request->precentage;
        $user->paisa = $request->paisa;
        $user->deliveryCharge = $request->deliveryCharge;
        $user->save();
        return back()->with('flash_success', 'Obcoins updated successfully');
    }
}
