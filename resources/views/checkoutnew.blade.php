@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Checkout</h2>
            <ul>
                <li><a href="{{url('/')}}">Home </a> >></li>
                <li><a href="{{url('/checkout')}}">Checkout</a> </li>
            </ul>
        </div>
    </div>
</div>
<?php
    $type = Request::cookie('type');
    $store_id = Request::cookie('store_id');  
?>
<style>
    #applied_coupon_code{
        font-weight: bold;
    }
</style>
<!-- section start -->
<section class="section-b-space my-5">
    <div class="container">
        <div class="checkout-page">
            @if(session('cart'))
            <div class="checkout-form">
                <form id="paymentform">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <div class="checkout-title">
                                <h3>Billing Details</h3>
                            </div>
                            @if(count($addresses) > 0)
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
                            @endif
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
                                    <div class="field-label">Email Address (Optional)</div>
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
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <div class="field-label">City <span class="required">*</span></div>
                                    <select name="city" id="city">
                                        <option value="keonjhar">Keonjhar</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <div class="field-label">Area <span class="required">*</span></div>
                                    <select name="area">
                                        <option value="area1">Area1</option>
                                        <option value="area2">Area2</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <div class="field-label">Address Type <span class="required">*</span></div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" checked value="Home">Home
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" value="Office">Office
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="address_type" value="Other">Other
                                      </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-sm-6 col-xs-6">
                                    <div class="field-label">Make My Default Address <span class="required">*</span></div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="has_default" checked value="1">Yes
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
                                    <input type="text" id="short_address" name="short_address" placeholder="Enter your address">
                                </div>
                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                    <div class="long_address">Nearest Landmark</div>
                                    <input type="text" name="long_address" id="long_address" placeholder="Enter your landmark address">
                                </div>
                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                    <div class="pincode">Pin Code <span class="required">*</span></div>
                                    <input type="text" id="pincode" name="pincode" placeholder="Enter Pin Code">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-xs-12">
                            <?php if($type == 'pickup' && $store_id !='') { ?>
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <p>You have selected takeaway and now you can change your delivery type from Takeaway to Delivery</p>
                                    <label class="checkbox checkbox-inline" style="color: black;">            
                                        <input type="checkbox" id="change_takeaway_to_delivery" value="yes" />
                                        <i class="input-helper"></i>
                                        Change to Delivery
                                    </label>
                                </div>
                            </div>
                            <?php } ?>   
                            <div class="checkout-details">
                                <div class="order-box">
                                 <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Items</strong></p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p><strong>Price</strong></p>
                                    </div>
                                 </div>
                                    
                                  <?php 
                                  $sub_total = 0;
                                  foreach(session('cart') as $item) { 
                                        if($type == 'pickup' && $store_id !='') {
                                            $product =  \App\Models\Product::where('id',$item['parent_product_id'])->first();
                                         } else {
                                           $product =  \App\Models\Product::where('id',$item['id'])->first();		               
                                         }   
                                        $product_price = \App\Models\ProductPrice::where('product_id',$product->id)->where('show_price',1)->first();
                                        $productIdForImage = $product->id;
                                        if (Auth::check()) {
                                            $customizeImage = \App\Models\CustomizeImage::where('user_id',Auth::user()->id)->where('product_id',$item['id'])->first();
                                            if(isset($customizeImage) && count($customizeImage)>0){
                                                $productIdForImage = $customizeImage->product_id;
                                            } 
                                        } 
                                        $product_image = \App\Models\ProductImage::where('product_id',$productIdForImage)->where('is_featured',1)->first();
                                        $sub_total += $product_price->price * $item['quantity']
                                    ?>
                                    <div class="row">
                                       <div class="col-md-6">
                                           <p><?php echo $product->name .' x ' . $item['quantity']; ?> </p>
                                       </div>
                                       <div class="col-md-6 text-right">
                                           <p><?php echo $product_price->price * $item['quantity']; ?>.00 INR</p>
                                       </div>
                                    </div> 
                                  <?php } ?>
                                    <hr />
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Sub Total</p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p><?php echo $sub_total ; ?>.00 INR</p>
                                    </div>
                                 </div>
                                 <div class="row" id="coupon_applied" style="margin-top:10px; display: none;">
                                    <div class="col-md-8">
                                        Coupon <span id="applied_coupon_code"></span> applied successfully 
                                        <button type="button" id="remove_coupon" class="cart-btn btn btn-sm submit">Remove</button>                        
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <p style="color:red;">- <span id="applied_coupon_amount">00</span>.00 INR</p>
                                    </div>
                                 </div>
                                 <div class="row" id="coupon_apply" style="margin-top:10px;">
                                    <div class="col-md-12">
                                        <input placeholder="Apply Your Coupon Code" class="coupon_input" type="text" id="couponcode" autocomplete="off"> 
                                        <button type="button" class="coupon_btn" id="applyCouponBtn">Apply</button>                                        
                                    </div>
                                 </div>
                                    <hr />
                                 <div class="row" id="coin_applied" style="margin-top:10px; display: none;">
                                    <div class="col-md-8">
                                        You have redeemed <span id="applied_coins"></span> coin successfully.
                                        <button type="button" id="remove_coin" class="cart-btn btn btn-sm submit">Remove</button>                        
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <p style="color:red;">- <span id="applied_coin_amount_text">00.00</span> INR</p>
                                    </div>
                                 </div>
                                  <div class="row" id="coin_apply" style="margin-top:10px;">
                                    <div class="col-md-12">                                         
                                        <input placeholder="Redeem OB Coins" class="coupon_input" type="text" id="redeemcoin" autocomplete="off"> 
                                        <button type="button" class="coupon_btn" id="redeemCoinBtn">Redeem</button> 
                                    </div>                                      
                                 </div>
                                    <div class="row">
                                    <div class="col-md-12">
                                         <input type="hidden" value="{{$coins}}" id="mycoins" />
                                         <small>1 OB Coin = 50 Paisa</small><br />
                                         <small>Your available OB Coins is <strong>{{$coins}}</strong> and you can redeem maximum <strong>100</strong> coins from it.</small>
                                     </div>
                                    </div>
                                    <hr />
                                 <div class="row" style="margin-top:10px;">
                                    <div class="col-md-6">
                                        <p>Delivery Charge</p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p><?php echo $delivery_charge; ?>.00 INR</p>
                                    </div>
                                 </div>
                                 <div class="row" style="margin-top:10px;">
                                    <div class="col-md-6">
                                        <p>Payable Amount</p>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <p id="payable_amount_text"><?php echo $sub_total + $delivery_charge; ?>.00 INR</p>
                                    </div>
                                 </div>
                                    <hr />
                                 <div class="row" style="margin-top:10px;">
                                    <div class="col-md-6">
                                        <label class="form-check-label">
                                            <input type="radio" value="card" name="payment_type" checked /> Card Payments
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-check-label">
                                            <input type="radio" value="cod" name="payment_type" /> Pay On Delivery
                                        </label>
                                    </div>
                                 </div>
                                </div>
                                <div class="payment-box">
                                    <input type="hidden" id="sub_total" value="<?php echo $sub_total; ?>">
                                    <input type="hidden" id="user_applied_coupon_amount">
                                    <input type="hidden" id="user_applied_coins">
                                    <input type="hidden" id="user_applied_coin_amount">
                                    <input type="hidden" id="payable_amount" value="<?php echo $sub_total + $delivery_charge; ?>">
                                    <div class="text-right"><button type="submit" id="placeorderbtn" class="cart-btn btn btn-block submit">Place Order</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
            @else
            No cart to checkout continue shopping
            @endif
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="container">
            <div class="row1">
                <div class="col-12">
                    <h2 class="h2" style="display:inline-block">Add-On Products</h2>        
                    <section class="best-seller slider">
                        <?php foreach($addOnProducts as $product){ 
                            $discountPrice = $product->mrp_price - $product->price;
                            $discountPercentage = ($product->price / $product->mrp_price)*100;
                            ?>
                            <div class="slide"> 
                                <div class="product-box text-center">
                                    <div class="p-img">
                                        <a style="color: black;" href="{{url('/')}}/products/{{$product->slug}}"><img src="{{ URL::to('/') }}/uploads/product/{{ @$product->images }}" alt="{{$product->name}}" class="w-100"></a>
                                    </div>
                                    <div class="product-text">
                                        <p class="product-name"><a href="{{url('/')}}/products/{{$product->slug}}">{{$product->name}}</a></p>
                                         <div class="price py-3">
                                            <div class="selling_price text-right">
                                               &#8377;{{@$product->price}}.00
                                            </div>
                                            <div class="mrp_price text-center">
                                                <del>
                                                   &#8377;{{@$product->mrp_price}}.00 
                                                </del>
                                            </div>
                                            <div class="percent text-left">
                                                <span >
                                                {{100-round($discountPercentage)}}% off
                                                </span>
                                            </div>
                                            <div class="clearfix"></div> 
                                        </div>
                                        <div class="row">
                                         <div class="col-md-6">
                                             @guest
                                             <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Buy Now</button>                                
                                             @else
                                             <a href="javascript:void(0);" data-id="{{ $product->id }}" data-title="{{ $product->name }}" data-priceid="<?php echo @$product->price_id; ?>" class="btn cart-btn a_btn obBuyNow">Buy Now</a>
                                             @endguest
                                         </div>
                                         <div class="col-md-6">
                                             <a href="javascript:void(0);" data-id="{{ $product->id }}" data-title="{{ $product->name }}" data-priceid="<?php echo @$product->price_id; ?>" class="btn cart-btn a_btn addon_obcart">Add To Cart</a>
                                         </div>
                                         @if($product->is_customize == 1)                             
                                         <div class="col-md-12">
                                           @guest
                                             <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Customize</button>
                                           @else
                                             <a href="{{url('/customize')}}?product_id={{$product->id}}" class="btn cart-btn a_btn">Customize</a>
                                           @endguest
                                         </div>
                                         @endif                            
                                     </div>
                                    </div>
                                </div> 
                            </div>
                        <?php } ?>
                    </section>
                </div>
            </div>
        </div>
</section>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script>
$(document).ready(function() {
    $('#applyCouponBtn').click(function(){
        $('#coin_apply').hide();
        if ($('#couponcode').val() != '') {            
          var form_data = new FormData();
          form_data.append("couponcode", $('#couponcode').val());
          form_data.append("sub_total", $('#sub_total').val());
          form_data.append("_token", "{{@csrf_token()}}");
          $.ajax({
              url: "{{url('checkCoupon')}}",
              method: "POST",
              data: form_data,
              contentType: false,
              cache: false,
              processData: false,
              dataType: "json",
              success: function (data)
              {
                if (data.success) {
                    $('#coupon_apply').hide();
                    $('#coupon_applied').show();
                    $('#applied_coupon_code').html(data.coupon_code);
                    $('#applied_coupon_amount').html(data.discount_amount); 
                    $('#user_applied_coupon_amount').val(data.discount_amount);
                    var payable_amount = $('#payable_amount').val() - data.discount_amount;
                    $('#payable_amount').val(payable_amount);
                    if(Number.isInteger(payable_amount)){
                        $('#payable_amount_text').html(payable_amount+'.00 INR');
                    } else {
                        $('#payable_amount_text').html(payable_amount+'0 INR');
                    }
                    
                    $('#payable_amount_text').html();
                    Swal.fire({
                    title: 'Great Deal!',
                    text: "Your coupon "+data.coupon_code+" has been applied successfully",
                    icon: 'success',
                    confirmButtonColor: '#fb6700',
                    confirmButtonText: 'Continue'
                  });
                } 
                else {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'Invalid Coupon Code',
                    });
                }
              }
          });
      }
    });
    
     $('#remove_coupon').click(function(){
        $('#coupon_apply').show();
        $('#coupon_applied').hide();
        $('#couponcode').val('');
        var payable_amount = parseFloat($('#payable_amount').val()) + parseFloat($('#user_applied_coupon_amount').val());
        $('#payable_amount').val(payable_amount);        
        if(Number.isInteger(payable_amount)){
           $('#payable_amount_text').html(payable_amount+'.00 INR');
        } else {
           $('#payable_amount_text').html(payable_amount+'0 INR');
        }
        $('#user_applied_coupon_amount').val('');
     });
     
     $('#redeemCoinBtn').click(function(){
        // alert();
        $('#coupon_apply').hide();
        var redeemcoin = $('#redeemcoin').val();
        var mycoins = $('#mycoins').val();
        if (redeemcoin != '') {
          var form_data = new FormData();
          form_data.append("redeemcoin", redeemcoin);
          form_data.append("mycoins", mycoins);
          form_data.append("_token", "{{@csrf_token()}}");
          $.ajax({
              url: "{{url('redeemCoins')}}",
              method: "POST",
              data: form_data,
              contentType: false,
              cache: false,
              processData: false,
              dataType: "json",
              success: function (data)
              {
                if (data.success) {
                    $('#coin_apply').hide();
                    $('#coin_applied').show();
                    $('#applied_coins').html(data.applied_coins);
                    $('#applied_coin_amount_text').html(data.applied_coin_amount_text);
                    $('#user_applied_coins').val(data.applied_coins);
                    $('#user_applied_coin_amount').val(data.applied_coin_amount);
                    var payable_amount = $('#payable_amount').val() - data.applied_coin_amount;
                    $('#payable_amount').val(payable_amount);
                    if(Number.isInteger(payable_amount)){
                        $('#payable_amount_text').html(payable_amount+'.00 INR');
                    } else {
                        $('#payable_amount_text').html(payable_amount+'0 INR');
                    }
                    Swal.fire({
                    title: 'Great Deal!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#fb6700',
                    confirmButtonText: 'Continue'
                  });
                } 
                else {
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: data.message,
                    });
                }
              }
          });
      }
     });
     
     $('#remove_coin').click(function(){
        $('#coin_apply').show();
        $('#coin_applied').hide();
        $('#redeemcoin').val('');
        var payable_amount = parseFloat($('#payable_amount').val()) + parseFloat($('#user_applied_coin_amount').val());
        $('#payable_amount').val(payable_amount);              
        if(Number.isInteger(payable_amount)){
            $('#payable_amount_text').html(payable_amount+'.00 INR');
        } else {
            $('#payable_amount_text').html(payable_amount+'0 INR');
        }
        $('#user_applied_coin_amount').val(''); 
        $('#user_applied_coins').val('');
     });
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
            // email: {
            //     required: true,
            //     mail: true,
            // },
            phone:{
              required: true, 
            },
            short_address: {
                required: true,
                minlength: 2,
                //lettersonly: true
            },
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
            Pincode: {
                required: "Please enter Pincode",
                minlength : "Pincode must be 6 characters",
                maxlength : "Pincode must be 6 characters",
                lettersonly: "Pincode must be in number"
            },
        },
        submitHandler: function (form) {
            var payment_type = $("input[name=payment_type]:checked").val();
            var change_takeaway_to_delivery = 'No';
            if($('#change_takeaway_to_delivery').prop("checked") == true){
                change_takeaway_to_delivery = 'Yes';
            }
            $('#placeorderbtn').html('Sending...');
            $('#placeorderbtn').attr('disabled', true);
            if (payment_type == "cod") {
                
                var _token = "{{@csrf_token()}}";
                $.ajax({
                   url: "{{url('/place-order')}}",
                   type: 'post',
                   dataType: 'json',
                    data: {                  
                    name : $('#name').val(),
                    email : $('#email').val(),
                    phone : $('#phone').val(),
                    short_address : $('#short_address').val(),
                    long_address : $('#long_address').val(),
                    pincode : $('#pincode').val(),
                    address_type : $("input[name=address_type]:checked").val(),                    
                    has_default: $("input[name=has_default]:checked").val(),
                    product_id : 1,
                    totalAmount : $('#payable_amount').val(),
                    payment_type : payment_type,
                    address_id_old : $('#address_id_old').val(),
                    change_takeaway_to_delivery : change_takeaway_to_delivery,
                    user_applied_coins : $('#user_applied_coins').val(),
                    couponcode: $('#couponcode').val(),
                    _token: _token,
                   }, 
                   success: function (msg) {                        
                            Swal.fire({
                              title: 'Order Placed!',
                              text: 'Congrats!. Your order has been successfully placed',
                              icon: 'success',
                              confirmButtonColor: '#fb6700',
                              confirmButtonText: 'Continue'
                            }).then((result) => {
                                 window.location.href = "{{url('/thank-you')}}"
                            });
                     }
               });
            }
            else if(payment_type == "card"){
                   var totalAmount = $('#payable_amount').val();
                   var _token = "{{@csrf_token()}}";
                   var options = {
                   "key": "rzp_test_dAqZvsGVmghE3X",
                   "amount": (totalAmount*100), // 2000 paise = INR 20
                   "name": "Orangebakery",
                   "description": "Orangebakery Payment",
                   "image": "https://orangebakery.in/images/logo.png",
                   "handler": function (response){
                         $.ajax({
                           url: "{{url('/place-order')}}",
                           type: 'post',
                           dataType: 'json',
                            data: {
                            razorpay_payment_id: response.razorpay_payment_id, 
                            name : $('#name').val(),
                            email : $('#email').val(),
                            phone : $('#phone').val(),
                            short_address : $('#short_address').val(),
                            long_address : $('#long_address').val(),
                            pincode : $('#pincode').val(),
                            address_type : $("input[name=address_type]:checked").val(),                    
                            has_default: $("input[name=has_default]:checked").val(),
                            product_id : 1,
                            totalAmount : $('#amount').val(),
                            payment_type : payment_type,
                            address_id_old : $('#address_id_old').val(),
                            change_takeaway_to_delivery : change_takeaway_to_delivery,
                            user_applied_coins : $('#user_applied_coins').val(),
                            couponcode: $('#couponcode').val(),
                            _token: _token,
                           }, 
                           success: function (msg) {
                                Swal.fire({
                                  title: 'Order Placed!',
                                  text: 'Congrats!. Your order has been successfully placed',
                                  icon: 'success',
                                  confirmButtonColor: '#fb6700',
                                  confirmButtonText: 'Continue'
                                }).then((result) => {
                                     window.location.href = "{{url('/thank-you')}}"
                                });
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