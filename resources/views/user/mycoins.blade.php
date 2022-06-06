@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>My coins</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="#">My coins</a> </li>
            </ul>
        </div>
    </div>
</div>
<div class="container" style="padding-top: 100px; padding-bottom: 100px;">
	<div class="row">
      	<div class="col-lg-12 col-12">
          	<div class="card">
	          <div class="card-header">
	          	<h3 style="text-align: center;">Avilable Coins : {{$avilableCoins}}</h3>
	          </div>
	          <!-- /.card-header -->
	          <div class="card-body">
	            <table class="table table-bordered">
	              <thead>                  
	                <tr>
	                  <th>Coins</th>
	                  <th>Coin Received On Purchase</th>
	                  <th>Status</th>
	                  <th>Coin Received On</th>
	                  <th>Coin Expiry Date On</th>
	                </tr>
	              </thead> 
	              	<tbody>
	                @foreach($coins as $coin)
                    <?php 
                        $order = \App\Models\Order::where('order_id',$coin->order_id)->first();
                        if (isset($order)) {
                            $order_price = $order->order_price;
                        }
                        else{
                            $order_price = "SIGNUP BONOUS";
                        }
                    ?>
                        	<td><span style="color: green; font-size: 30px;">{{$coin->coins}}</span> @if($coin->type == "MINUS") <span style="color: red; font-size: 30px;">(-)</span> @else <span style="color: blue; font-size: 30px;">(+)</span> @endif</td>
                        	<td>
                                @if($order_price == "SIGNUP BONOUS")
                                    {{$order_price}}
                                @else
                                    (Order id - <a href="{{url('user/orderdetails')}}?order_id={{$coin->order_id}}">{{$coin->order_id}}</a> )
                                @endif
                        	<td>
                        		@if($coin->status == 1)
                        			ACTIVE
                        		@elseif($coin->status == 2)
                        			USED
                                @else
                                    EXPIRED
                        		@endif
                        	</td>
                        	<td>{{ \Carbon\Carbon::parse($coin->created_at)->format('jS M Y') }}</td>
                        	<td>{{ \Carbon\Carbon::parse($coin->expiry_at)->format('jS M Y') }}</td>
                    	</tr>
               		 </tbody>
	   				@endforeach
	              </tbody>
	            </table>
	          </div>
	      	</div>
  		</div>
  	</div>
</div>
@endsection