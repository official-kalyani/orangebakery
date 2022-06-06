@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Checkout</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/checkout')}}">Checkout</a> </li>
            </ul>
        </div>
    </div>
</div>
<!-- section start -->
<section class="section-b-space my-5">
    <div class="container">
        <div class="checkout-page">
            <div class="checkout-form">
                <form id="paymentform">
                    @if(count($addresses) > 0)
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="checkout-title">
                                <h3>Billing Details</h3>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <?php $counterAddress = 0; ?>
                                    @foreach($addresses as $row)
                                    <?php $counterAddress++; ?>
                                    <div class="row">
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-check-inline">
                                              <label class="form-check-label">
                                            @if($row->has_default == 1)
                                               <input type="hidden" id="address_id"  value="{{$row->id}}">
                                            @else
                                               <input type="hidden" id="address_id1"  value="{{$row->id}}">
                                            @endif
                                                <input type="hidden" id="address_id_old" name="address_id_old" placeholder="Enter Name">
                                                <input type="radio" class="form-check-input address_id{{$row->id}}" name="address_id" value="{{$row->id}}" @if($row->has_default==1) checked @else @endif>{{$row->name}} @if($row->has_default==1)<span style="color: blue;">(Default Address)</span> @endif
                                              </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    @endforeach
                                    <br>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{url('user/profile#Createlocation')}}">Add More Address</a>
                                </div>
                            </div>
                            <div class="row check-out">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Name <span class="required">*</span></div>
                                    <input type="text" id="name" name="name" placeholder="Enter Name">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <div class="field-label">Phone <span class="required">*</span></div>
                                    <input type="text" id="phone" name="phone" placeholder="Enter Phone">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <div class="field-label">Email Address <span class="required">*</span></div>
                                    <input type="email" id="email" name="email" placeholder="Enter Email Address">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <div class="field-label">Country <span class="required">*</span></div>
                                    <select class="form-control" name="country" id="country">
                                        <option value="india">India</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <div class="field-label">State <span class="required">*</span></div>
                                    <select class="form-control" name="state" id="state">
                                        <option value="odisha">Odisha</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">City <span class="required">*</span></div>
                                    <select name="city" id="city">
                                        <option value="keonjhar">Keonjhar</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Area <span class="required">*</span></div>
                                    <select name="area">
                                        <option value="area1">Area1</option>
                                        <option value="area2">Area2</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Address Type <span class="required">*</span></div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" value="Home">Home
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" value="Office">Office
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" value="Public">Public
                                      </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Make My Default Address <span class="required">*</span></div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="has_default" value="1">Yes
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="has_default" value="0">No
                                      </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="short_address">Address <span class="required">*</span></div>
                                    <input type="text" id="short_address" name="short_address" placeholder="Enter Location">
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="long_address">Secondary Address</div>
                                    <textarea rows="4" cols="50" name="long_address" id="long_address" class="form-control"></textarea>
                                </div>
                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                    <div class="pincode">Pin Code <span class="required">*</span></div>
                                    <input type="text" id="pincode" name="pincode" placeholder="Enter Pin Code">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            @include('layouts.checkout_modal')
                            <div class="checkout-details">
                                <div class="order-box">
                                    <div class="title-box">
                                        <div>Product <span>Total</span></div>
                                    </div>
                                    <ul class="qty">
                            <?php $total = 0; $deliveryCharge = DELIVERY_CHARGE; ?>
                            @if(session('cart'))
                                @foreach((array) session('cart') as $id => $details)
                                    <?php
                                    $cake =  \App\Models\Product::where('id',$id)->first(); 
                                    $cake_price = \App\Models\ProductPrice::where('id',$details['price_id'])->where('show_price',1)->first(); 
                                    $product_image = \App\Models\ProductImage::where('product_id',$details['id'])->where('is_featured',1)->first(); 

                                    $total += @$cake_price->price * $details['quantity'] 

                                    ?>
                                <li>{{ $cake->name }} × {{ $details['quantity'] }} <span>
                                    {{ $cake_price->price * $details['quantity'] }}.00 INR</span></li>
                                @endforeach
                            @endif
                                    </ul>
                                    <ul class="sub-total">
                                        <li>Subtotal <span class="count">{{@$total}}.00 INR</span></li>
                                        <li>Delivery Charge <span class="count">{{@$shipping_charge}}.00 INR</span></li>
                                        @if(isset($discount_amount))
                                        <li>Coupon Applied ({{@$coupon_name}}) <span class="count">- {{@$discount_amount}} INR</span></li>
                                        @endif
                                        @if(isset($discounted_coin_amount) && $discounted_coin_amount!='')
                                        <li>Ob Coins Discount ({{@$discounted_coin_amount}}) <span class="count">- {{@$discounted_coin_amount_price}} INR</span></li>
                                        @endif
                                    </ul>
                                    <ul class="total">
                                        <li><strong>Total</strong> <span class="count"><?php echo $total + $shipping_charge - $discount_amount - $discounted_coin_amount  ?>.00 INR</span></li>
                                    </ul>
                                </div>
                                <div class="payment-box">
                                    <div class="upper-box">
                                        <div class="payment-options">
                                            <ul>
                                                <li>
                                                    <div class="form-check">
                                                      <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="cash_on_delivery" checked name="payment_type">Cash On Delivery
                                                      </label>
                                                    </div>
                                                </li>
                                               <li>
                                                    <div class="form-check">
                                                      <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="razorpay" name="payment_type">Card Payments
                                                      </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <input type="hidden" id="coupon_amount" value="{{@$discount_amount}}">
                                    <input type="hidden" id="delivery_charge" value="{{$shipping_charge}}">
                                    <input type="hidden" id="amount" value="{{ $final_payable_amount }}">
                                    <input type="hidden" id="cbcoins_used" value="{{ $discounted_coin_amount_price }}">
                                    <input type="hidden" value="{{ $coinswillAdded }}" id="coinswillAddedToWallet">
                                    <input type="hidden" id="couponcode" value="{{@$coupon_name}}">
                                    <div class="text-right"><button type="submit" href="#" class="cart-btn btn submit">Place Order</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="checkout-title">
                                <h3>Billing Details</h3>
                            </div>
                            <div class="row check-out">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Name <span class="required">*</span></div>
                                    <input type="text" id="name" name="name" placeholder="Enter Name" value="{{@Auth::user()->name}}">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <div class="field-label">Phone <span class="required">*</span></div>
                                    <input type="text" id="phone" name="phone" placeholder="Enter Phone" value="{{@Auth::user()->phone}}">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                    <div class="field-label">Email Address <span class="required">*</span></div>
                                    <input type="email" id="email" name="email" placeholder="Enter Email Address" value="{{@Auth::user()->email}}">
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <div class="field-label">Country <span class="required">*</span></div>
                                    <select class="form-control" name="country" id="country">
                                        <option value="india">India</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <div class="field-label">State <span class="required">*</span></div>
                                    <select class="form-control" name="state" id="state">
                                        <option value="odisha">Odisha</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">City <span class="required">*</span></div>
                                    <select name="city" id="city">
                                        <option value="keonjhar">Keonjhar</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Address Type <span class="required">*</span></div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" value="Home" checked="">Home
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" value="Office">Office
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" value="Public">Public
                                      </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-label">Make My Default Address <span class="required">*</span></div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="has_default" value="1" checked="">Yes
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="has_default" value="0">No
                                      </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="short_address">Address <span class="required">*</span></div>
                                    <input type="text" id="short_address" name="short_address" placeholder="Enter Location">
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="long_address">Secondary Address</div>
                                    <textarea rows="4" cols="50" name="long_address" id="long_address" class="form-control"></textarea>
                                </div>
                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                    <div class="pincode">Pin Code <span class="required">*</span></div>
                                    <input type="text" id="pincode" name="pincode" placeholder="Enter Pin Code">
                                </div>
                                <!-- <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="checkbox" name="shipping-option" id="account-option"> &ensp;
                                    <label for="account-option">Create An Account?</label>
                                </div>
                                 <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                   <input type="button" class="btn cart-btn" value="Save">
                                </div> -->
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            @include('layouts.checkout_modal')
                            <div class="checkout-details">
                                <div class="order-box">
                                    <div class="title-box">
                                        <div>Product <span>Total</span></div>
                                    </div>
                                    <ul class="qty">
                            <?php $total = 0; $deliveryCharge = DELIVERY_CHARGE; ?>
                            @if(session('cart'))
                                @foreach((array) session('cart') as $id => $details)
                                    <?php
                                    $cake =  \App\Models\Product::where('id',$id)->first(); 
                                    $cake_price = \App\Models\ProductPrice::where('product_id',$details['id'])->where('show_price',1)->first(); 
                                    $product_image = \App\Models\ProductImage::where('product_id',$details['id'])->where('is_featured',1)->first(); 

                                    $total += @$cake_price->price * $details['quantity'] 

                                    ?>
                                <li>{{ $cake->name }} × {{ $details['quantity'] }} <span>
                                    {{ $cake_price->price * $details['quantity'] }}.00 INR</span></li>
                                @endforeach
                            @endif
                                    </ul>
                                    <ul class="sub-total">
                                        <li>Subtotal <span class="count">{{@$total}}.00 INR</span></li>
                                        <li>Delivery Charge <span class="count">+{{@$shipping_charge}}.00 INR</span></li>
                                        @if(isset($discount_amount))
                                        <li>Coupon Applied ({{@$coupon_name}}) <span class="count">- {{@$discount_amount}}.00 INR</span></li>
                                        @endif
                                        @if(isset($discounted_coin_amount) && $discounted_coin_amount!='')
                                        <li>Ob coins Discount ({{@$discounted_coin_amount}}) <span class="count">- {{@$discounted_coin_amount_price}}.00 INR</span></li>
                                        @endif
                                    </ul>
                                    <ul class="total">
                                        <li><strong>Total</strong> <span class="count">{{$final_payable_amount}}.00 INR</span></li>
                                    </ul>
                                </div>
                                <div class="payment-box">
                                    <div class="upper-box">
                                        <div class="payment-options">
                                            <ul>
                                                <li>
                                                    <div class="form-check">
                                                      <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="cash_on_delivery" checked name="payment_type">Cash On Delivery
                                                      </label>
                                                    </div>
                                                </li>
                                               <li>
                                                    <div class="form-check">
                                                      <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" value="razorpay" name="payment_type">Card Payment
                                                      </label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <input type="hidden" id="coupon_amount" value="{{@$discount_amount}}">
                                    <input type="hidden" id="delivery_charge" value="{{$shipping_charge}}">
                                    <input type="hidden" id="amount" value="{{ $final_payable_amount }}">
                                    <input type="hidden" id="cbcoins_used" value="{{ $discounted_coin_amount_price }}">
                                    <input type="hidden" value="{{ $coinswillAdded }}" id="coinswillAddedToWallet">
                                    <input type="hidden" id="couponcode" value="{{@$coupon_name}}">
                                    <div class="text-right"><button type="submit" href="#" class="cart-btn btn submit">Place Order</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</section>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
<?php if(count($addresses) > 0) { ?>
    var address_id = $('#address_id').val();
    $('#address_id_old').val(address_id);
    $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: "{{url('user/profile/fetchAddress')}}",
        data: {"address_id":address_id},
        dataType: "json",
        success: function (data) {
          $('#address_type').val(data.address_type);
          if (data.address_type == "Home") {
            $('input:radio[name=address_type]')[0].checked = true;
          }
          else if(data.address_type == "Office") {
            $('input:radio[name=address_type]')[1].checked = true;
          }
          else if(data.address_type == "Public") {
            $('input:radio[name=address_type]')[2].checked = true;
          }
          if (data.has_default == 1) {
            $('input:radio[name=has_default]')[0].checked = true;
          }
          else{
            $('input:radio[name=has_default]')[1].checked = true;
          }
          $('#short_address').val(data.location);
          $('#long_address').val(data.additional_address);
          $('#pincode').val(data.pincode);
          $('#address_id').val(data.id);
          $('#phone').val(data.phone);
          $('#name').val(data.name);
          $('#email').val(data.email);
        }
      });
    <?php foreach($addresses as $address){ ?>
        $('.address_id<?php echo $address->id; ?>').on('click', function (ev) {
          var address_id = $('.address_id<?php echo $address->id; ?>').val();
          $('#address_id_old').val(address_id);
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "{{url('user/profile/fetchAddress')}}",
            data: {"address_id":address_id},
            dataType: "json",
            success: function (data) {
              $('#address_type').val(data.address_type);
              if (data.address_type == "Home") {
                $('input:radio[name=address_type]')[0].checked = true;
              }
              else if(data.address_type == "Office") {
                $('input:radio[name=address_type]')[1].checked = true;
              }
              else if(data.address_type == "Public") {
                $('input:radio[name=address_type]')[2].checked = true;
              }
              if (data.has_default == 1) {
                $('input:radio[name=has_default]')[0].checked = true;
              }
              else{
                $('input:radio[name=has_default]')[1].checked = true;
              }
              $('#short_address').val(data.location);
              $('#long_address').val(data.additional_address);
              $('#pincode').val(data.pincode);
              $('#address_id').val(data.id);
              $('#phone').val(data.phone);
              $('#name').val(data.name);
              $('#email').val(data.email);
              $('#address_id_old').val(data.id);
            }
          });
        });
        <?php } ?>
<?php } ?>

    $("#paymentform").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                lettersonly: true
            },
            email: {
                required: true,
                mail: true,
            },
            phone:{
              required: true, 
            },
            short_address: {
                required: true,
                minlength: 2,
                //lettersonly: true
            },
            // long_address: {
            //     required: true,
            //     minlength: 15,
            //     lettersonly: true
            // },
            pincode: {
                required: true,
                minlength: 6,
                maxlength: 6,
                lettersonly: false
            },
        },
        messages: {
            name: {
                required: "Please enter your full name",
                minlength: "Your full name must be at least 2 characters long"
            },
            email: {
                required: "Please enter your email address",
                mail: 'Please enter a valid email address'
            },
            phone: {
                required: "Please enter phone number",
            },
            short_address: {
                required: "Please enter location",
            },
            // long_address: {
            //     required: "Please secondary locatin",
            // },
            Pincode: {
                required: "Please enter Pincode",
                minlength : "Pincode must be 6 characters",
                maxlength : "Pincode must be 6 characters",
                lettersonly: "Pincode must be in number"
            },
        },
        submitHandler: function (form) {
            var payment_type = $("input[name=payment_type]:checked").val();
            if (payment_type == "cash_on_delivery") {
                $('.submit').html('Sending...');
                $('.submit').attr('disabled', true);
                var name = $('#name').val();
                var email = $('#email').val();
                var phone = $('#phone').val();
                var totalAmount = $('#amount').val();
                var short_address = $('#short_address').val();
                var long_address = $('#long_address').val();
                var couponcode = $('#couponcode').val();
                var address_id_old = $('#address_id_old').val();
                var pincode = $('#pincode').val();
                var address_type = $("input[name=address_type]:checked").val();
                var has_default = $("input[name=has_default]:checked").val();
                var product_id =  1;
                var coinswillAdded = parseInt($('#coinswillAddedToWallet').val());
                var cbcoins_used = $('#cbcoins_used').val();
                var delivery_charge = $('#delivery_charge').val();
                var coupon_amount = $('#coupon_amount').val();
                var _token = "{{@csrf_token()}}";
                $.ajax({
                   url: "{{url('/pay-success')}}",
                   type: 'post',
                   dataType: 'json',
                    data: {
                    totalAmount : totalAmount,
                    product_id : product_id,
                    couponcode: couponcode,
                    name : name,
                    email : email,
                    phone : phone,
                    short_address : short_address,
                    long_address : long_address,
                    pincode : pincode,
                    address_type : address_type,
                    _token: _token,
                    has_default: has_default,
                    payment_type : payment_type,
                    address_id_old : address_id_old,
                    coinswillAdded : coinswillAdded,
                    cbcoins_used : cbcoins_used,
                    delivery_charge : delivery_charge,
                    coupon_amount : coupon_amount,
                   }, 
                   success: function (msg) {
                        $('.submit').html('PLACE ORDER');
                        $('.submit').attr('disabled', true);
                        window.location.href = "{{url('/thank-you')}}"
                        console.log(msg);
                   }
               });
            }
            else{
                //alert(1);
                //$('.submit').click(function(e){
                   //form.preventDefault();
                   var name = $('#name').val();
                   var email = $('#email').val();
                   var phone = $('#phone').val();
                   var totalAmount = $('#amount').val();
                   var short_address = $('#short_address').val();
                   var long_address = $('#long_address').val();
                   var address_id_old = $('#address_id_old').val();
                   var pincode = $('#pincode').val();
                   var couponcode = $('#couponcode').val();
                   var address_type = $("input[name=address_type]:checked").val();
                   var has_default = $("input[name=has_default]:checked").val();
                   var product_id =  1;
                   var coinswillAdded = parseInt($('#coinswillAddedToWallet').val());
                   var cbcoins_used = $('#cbcoins_used').val();
                   var delivery_charge = $('#delivery_charge').val();
                   var coupon_amount = $('#coupon_amount').val();
                   var _token = "{{@csrf_token()}}";
                   var options = {
                   "key": "rzp_test_dAqZvsGVmghE3X",
                   "amount": (totalAmount*100), // 2000 paise = INR 20
                   "name": "Cakeshop",
                   "description": "Payment",
                   "image": "https://www.w3adda.com/wp-content/uploads/2019/07/w3a-fb-dp.png",
                   "handler": function (response){
                         $.ajax({
                           url: "{{url('/pay-success')}}",
                           type: 'post',
                           dataType: 'json',
                            data: {
                            razorpay_payment_id: response.razorpay_payment_id , 
                            totalAmount : totalAmount,
                            product_id : product_id,
                            couponcode: couponcode,
                            name : name,
                            email : email,
                            phone : phone,
                            short_address : short_address,
                            long_address : long_address,
                            pincode : pincode,
                            address_type : address_type,
                            _token: _token,
                            has_default: has_default,
                            payment_type: payment_type,
                            address_id_old : address_id_old,
                            coinswillAdded : coinswillAdded,
                            cbcoins_used : cbcoins_used,
                            delivery_charge : delivery_charge,
                            coupon_amount : coupon_amount
                           }, 
                           success: function (msg) {
                               window.location.href = "{{url('/thank-you')}}"
                               console.log(msg);
                           }
                       });
                         },
                    "prefill": {
                       "contact": '1234567890',
                       "email":   'xxxxxxxxx@gmail.com',
                    },
                    "theme": {
                       "color": "#528FF0"
                    }
                 };
                 var rzp1 = new Razorpay(options);
                 rzp1.open();
                 
                //});
            }
        }
    });
});
 </script>
@endsection