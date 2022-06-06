@extends('layouts.app')
@section('content')
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<link href="{{ asset('ship-track/css/style.css') }}" rel="stylesheet" type="text/css" media="all" />
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Track Order</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="#">Track Order</a> </li>
            </ul>
        </div>
    </div>
</div>
<div class="cart-secion">
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if($order->status == "cancelled")
                <h1 class="text-center" style="padding-top: 100px; padding-bottom: 100px;">Order Cancelled</h1>
                @else
                <div class="container">
                    <div class="header">
                        <h1>Track Order</h1>
                    </div>
                    <div class="content">
                        <div class="content1">
                            <h2>Order ID: {{$_REQUEST['order_id']}}</h2>
                        </div>
                        <div class="content2">
                            <div class="content2-header1">
                                <p>Shipped Via : <br><span>Bike</span></p>
                            </div>
                            <div class="content2-header1">
                                <p>Status : <br><span>@if($order->status == "order_preparing") Order Preparing @endif @if($order->status == "ontheway") Order Ontheway @endif @if($order->status == "delivered") Order Delivered @endif @if($order->status == "cancelled") Order Cancelled @endif @if($order->status == "order_received") Order Received @endif</span></p>
                            </div>
                            <div class="content2-header1">
                                <p>Orderd Date : <br><span>{{ \Carbon\Carbon::parse($order->created_at)->format('jS M Y') }}</span></p>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="content3">
                            <div class="shipment">
                                @if($order->status == "order_preparing" || $order->status == "ontheway" || $order->status == "delivered" || $order->status == "order_received" || $order->status == "order_confirmed")
                                <div class="<?php if ($order->status == 'order_received') { ?> delivery <?php } else { ?> confirm <?php } ?>">
                                    <div class="imgcircle">
                                        <img src="{{ URL::to('/') }}/ship-track/images/confirm.png" alt="confirm order">
                                    </div>
                                    <span class="line"></span>
                                    <p>Order Received</p>
                                </div>
                                <div class="<?php if ($order->status == 'order_confirmed') { ?> delivery <?php } else { ?> confirm <?php } ?>">
                                    <div class="imgcircle">
                                        <img src="{{ URL::to('/') }}/ship-track/images/process.png" alt="process order">
                                    </div>
                                    <span class="line"></span>
                                    <p>Order Confirmed</p>
                                </div>
                                <div class="<?php if ($order->status == 'order_preparing') { ?> delivery <?php } else { ?> confirm <?php } ?>">
                                    <div class="imgcircle">
                                        <img src="{{ URL::to('/') }}/ship-track/images/process.png" alt="process order">
                                    </div>
                                    <span class="line"></span>
                                    <p>Order Preparing</p>
                                </div>
                                @if($order->type != 'pickup')
                                
                                <div class="<?php if ($order->status == 'ontheway') { ?> delivery <?php } else { ?> confirm <?php } ?>">
                                    <div class="imgcircle">
                                        <img src="{{ URL::to('/') }}/ship-track/images/dispatch.png" alt="dispatch product">
                                    </div>
                                    <span class="line"></span>
                                    <p>On The Way</p>
                                </div>
                                <div class="<?php if ($order->status == 'delivered') { ?> delivery <?php } else { ?> confirm <?php } ?>">
                                    <div class="imgcircle">
                                        <img src="{{ URL::to('/') }}/ship-track/images/delivery.png" alt="delivery">
                                    </div>
                                    <p>Delivered</p>
                                </div> 
                                @endif
                                <div class="<?php if ($order->status == 'delivered') { ?> delivery <?php } else { ?> confirm <?php } ?>">
                                    <div class="imgcircle">
                                        <img src="{{ URL::to('/') }}/ship-track/images/delivery.png" alt="delivery">
                                    </div>
                                    <p>Ready for takeaway</p>
                                </div>                     
                                @endif
                            </div>  
                        </div>
                    </div>
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <h3><u>Order Details</u></h3>
                                    <p><strong>Order Id</strong> : {{$order->order_id}}</p>
                                    <p><strong>Order Status</strong> :
                                        <?php
                                        if ($order->status == 'order_preparing') {
                                            echo "Order Preparing";
                                        } else if ($order->status == 'ontheway') {
                                            echo "Order Ontheway";
                                        } else if ($order->status == 'delivered') {
                                            echo "Order Delivered";
                                        } else if ($order->status == 'cancelled') {
                                            echo "Order Cancelled";
                                        } else if ($order->status == 'order_received') {
                                            echo "Order Received";
                                        } else if ($order->status == 'order_confirmed') {
                                            echo "Order Confirmed";
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></p>
                                    <p><strong>Order Type</strong> : 
                                        <?php
                                        if ($order->payment_type == "cod") {
                                            echo "Cash On Delivery";
                                        } else {
                                            echo "Card Payment";
                                        }
                                        ?></p>
                                    @if($order->payment_type == "online")
                                    <p><strong>Payment Status</strong> : {{$order->payment_status}}</p>
                                    @endif
                                    <p><strong>Payable Amount</strong> : &#8377;{{$order->payable_price}}.00</p>
                                    <p><strong>Order Amount</strong> : &#8377;{{$order->order_price}}.00</p>
                                    @if($order->cbcoin_amount != "")
                                    <p><strong>Coins Applied</strong> : &#8377;{{$order->cbcoin_amount}}</p>
                                    @endif
                                    <p><strong>Coupons</strong> : </p>
                                    <p><strong>Note</strong> : You can not cancel the order once it's confirmed</p>
                                    @if($order->status == "order_received")
                                     <p><button class="btn btn-danger pull-left cancel-order">Cancel Order</button>
                                       <input type="hidden" id="order_id" value="{{$_REQUEST['order_id']}}"></p>
                                    @endif


                                </div>
                                <div class="col-lg-4">
                                    <h3><u>Delivery Details</u></h3>
                                    <p><strong>Name</strong> : {{$address->name}}</p>
                                    <p><strong>Phone</strong> : <a href="tel:+91{{$address->phone}}">{{$address->phone}}</a></p>
                                    <p><strong>Email</strong> : {{$address->email}}</p>
                                    <p><strong>Location</strong> - {{$address->location}}</p>
                                    <p><strong>Additional Address</strong> : {{$address->additional_address}}</p>
                                    <p><strong>Address Type</strong> : {{$address->address_type}}</p>
                                    <p><strong>Pincode</strong> : {{$address->pincode}}</p>
                                    <div id="map"></div>
                                </div>
                                <div class="col-lg-4">
                                    <h3><u>User Details</u></h3>
                                    <p><strong>Name</strong> : {{$user->name}}</p>
                                    <p><strong>Phone</strong> : <a href="tel:+91{{$user->phone}}">{{$user->phone}}</a></p>
                                    <p><strong>Email</strong> : {{$user->email}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>                  
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Image</th>
                                        <th>Mrp Price</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                    @foreach($orderitems as $item)
                                        <td>{{ @$item->itemname }}</td>
                                        <td><img class="w-25" src="{{ URL::to('/') }}/uploads/product/{{ @$item->images }}"></td>
                                        <td>&#8377;{{ @$item->item_mrp_price }}.00</td>
                                        <td>&#8377;{{ @$item->item_selling_price }}.00</td>
                                        <td>{{@$item->item_quantity}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection