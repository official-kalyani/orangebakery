@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>My Orders</h2>
            <ul>
                <li><a href="{{url('/')}}">Home </a> >></li>
                <li><a href="{{url('/disclaimer')}}">My Orders</a> </li>
            </ul>
        </div>
    </div>
</div>

<div class="cart-secion my-5">
    <div class="container">
        @if(count($orders))
        <div class="card">
            <div class="card-body">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Order Id</th>
                        <th>Order Amount</th>
                        <th>Payable Amount</th>
                        <th>Coupons</th>
                        <th>Used Coins</th>
                        <th>Orderitems</th>
                        <th>Order Status</th>
                        <th>Order Date</th>
                        <th>Payment Type</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                    <?php
                        $orderItems = \App\Models\Orderlist::where('order_id',$order->order_id)->get();
                    ?>
                      <tr>
                        <td><a href="{{url('user/orderdetails')}}?order_id={{$order->order_id}}">{{$order->order_id}}</a></td>
                        <td>&#8377; {{$order->order_price}}.00</td>
                        <td>&#8377; {{@$order->payable_price}}.00</td>
                        <td>{{@$order->coupon_code}}</td>
                        <td>{{@$order->cbcoin_amount}}</td>
                        <td>{{count($orderItems)}}</td>
                        <td>
                            @if($order->status == "order_preparing") Order Preparing @endif @if($order->status == "ontheway") Order Ontheway @endif @if($order->status == "delivered") Order Delivered @endif @if($order->status == "cancelled") Order Cancelled @endif @if($order->status == "order_received") Order Received @endif
                            @if($order->status == "order_confirmed") Order Confirmed @endif
                            @if($order->status == "order_rejected") Order Rejected @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('jS M Y') }}
                        </td>
                        <td>@if($order->payment_type == "cod") Pay On Delivery @else Card Payment @endif</td>
                      </tr>
                    @endforeach
                    </tbody>
                  </table>
                <div class="text-center py-3">
                    {{$orders->links()}}
                </div>
            </div>
        </div>
        @else
            <h3 class="text-center">No Orders</h3>
            <div style="padding-top: 100px;"></div>
        @endif
    </div>
</div>

@endsection