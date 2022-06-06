<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Occasion;
use App\Models\Flavour;
use App\Models\Cake;
use App\Models\CakePrice;
use App\Models\CakeImage;
use Auth;

class CakeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $occasions = Occasion::all();
        $flavours = Flavour::all(); 
        $data = Cake::wherenotNull('parent_cake_id')->where('store_id',Auth::user()->id)->orderBy('id','DESC')->paginate(10);
        // echo "<pre>";
        // print_r($data);
        // exit();
        return view('shop.cakes.index',compact('data','occasions','flavours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $occasions = Occasion::all();
        $flavours = Flavour::all();
        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $cake = Cake::find($id);
            $data = CakePrice::where('cake_id',$cake->parent_cake_id)->get();
            $occasion_id = explode(',', $cake->occasion);
            $flavour_id = explode(',', $cake->flavour);
            if ($cakes = Cake::where('id',@$id)->where('store_id',Auth::user()->id)->first()) {
                return view('shop.cakes.create',compact('occasions','cakes','flavours','data','occasion_id','flavour_id')); 
            }
            else{
                abort(404);
            }
            
        }
        
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
            'stock_quantity' => 'required',
        ]);

        if (isset($request->id) && $request->id !='') {
            $id = $request->id;
            $cake = cake::where('id',$id)->where('store_id',Auth::user()->id)->first();
            $cake->stock_quantity = $request->stock_quantity;
            $cake->save();
            return back()->with('flash_success', 'Stock Quantity Updated Successfully!');
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
        //
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
        //
    }

    public function cloneMasterCakes(Request $request)
    {
        $cake_id = $request->id;

        $cake = Cake::find($cake_id);

        $checkCake = Cake::where('parent_cake_id',$cake_id)->where('store_id',Auth::user()->id)->first();

        if ($checkCake) {
            return back()->with('flash_danger', 'Cake Already Cloned!');
        }
        else{
            $newCake = new Cake;
            $newCake->name = $cake->name;
            $newCake->description = $cake->description;
            $newCake->flavour = $cake->flavour;
            $newCake->occasion = $cake->occasion;
            $newCake->meta_title = $cake->meta_title;
            $newCake->meta_desc = $cake->meta_desc;
            $newCake->stock_quantity = 0;
            $newCake->slug = $cake->slug;
            $newCake->store_id = Auth::user()->id;
            $newCake->parent_cake_id = $cake->id;
            $newCake->save();
            return back()->with('flash_success', 'Cake Cloned Successfully!');
        }
    }

    public function masterCakes(Request $request)
    {
        $occasions = Occasion::all();
        $flavours = Flavour::all(); 
        $data = Cake::whereNull('parent_cake_id')->orderBy('id','DESC')->paginate(10);
        return view('shop.master-cakes.index',compact('data','occasions','flavours'));
    }
}
