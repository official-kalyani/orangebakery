@extends('layouts.app')

@section('content')
<?php $total = 0 ?>
@foreach((array) session('cart') as $id => $details)
<?php 

$product_price = \App\Models\ProductPrice::where('product_id',$details['id'])->where('show_price',1)->first();
$total += $product_price->price * $details['quantity']

?>
@endforeach

<form id="paymentform">
  <div class="row">
    <div class="col-md-6">
      <h3 class="text-center">Billing Information</h3>
      <hr>
      <div class="form-group">
        <label for="firstname"><strong>First name :</strong></label>
        <input type="text" id="firstname" name="firstname" class="form-control">
      </div>
      <div class="form-group">
        <label for="lastname"><strong>Last name :</strong></label>
        <input type="text" id="lastname" name="lastname" class="form-control">
      </div>
      <div class="form-group">
        <label for="email"><strong>Email :</strong></label>
        <input type="emsil" id="email" name="email" class="form-control">
      </div>
      <div class="form-group">
        <label for="phone"><strong>Phone :</strong></label>
        <input type="text" id="phone" name="phone" class="form-control">
      </div>
      <div class="form-group">
        <label for="password"><strong>Password :</strong></label>
        <input type="password" id="password" name="password" class="form-control">
      </div>
      <div class="form-group">
        
      </div>
      <!-- <div class="form-group">
        <label for="cpassword"><strong>Confirm Password :</strong></label>
        <input type="password" id="cpassword" name="cpassword" class="form-control">
      </div> -->
    </div>
    <div class="col-md-6">
      <h3 class="text-center">Your Items</h3>
      <hr>
      <div class="row">
        <div class="col-md-6">
          <h4>Total Amount</h4>
        </div>
        <div class="col-md-6">
          <h4>&nbsp;&#8377; {{$init_payable_price_static}}</h4>
        </div>
        <div class="col-md-6">
          <h4>Delivery Charge</h4>
        </div>
        <div class="col-md-6">
          <h4>+ &#8377; {{$shipping_charge}}</h4>
        </div>
        @if(isset($discount_amount))
        <div class="col-md-6">
          <h4>Coupon Discount(<small>{{$coupon_name}}</small>)</h4>
        </div>
        <div class="col-md-6">
          <h4>- &#8377; {{$discount_amount}}</h4>
        </div>
        @endif
        <div class="col-md-6">
          <h4>Total Payable</h4>
        </div>
        <div class="col-md-6">
          <h4>&#8377; {{$final_payable_amount}}</h4>
        </div>
        <div class="col-md-6">
          
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <a href="javascript:void(0)" class="btn btn-primary buy_now" data-amount="{{ $final_payable_amount }}" data-id="1">Paynow &#8377; {{ $final_payable_amount }}</a> 
          </div>
        </div>
      </div>
    </div>
  </div>
</form>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script> -->
<script>
  // $("#paymentform").validate({
 //        rules: {
 //            firstname: {
 //                required: true,
 //                minlength: 2,
 //                lettersonly: true
 //            },
 //            lastname: {
 //                required: true,
 //                minlength: 2,
 //                lettersonly: true
 //            },
 //            email: {
 //                required: true,
 //                mail: true,
 //            },
 //            phone:{
 //              required: true, 
 //              phoneno: true,
 //            },
 //            password: {
 //                required: true,
 //                minlength: 8,
 //                alphaNumeric: true
 //            },
 //            cpassword : {
 //                required: true,
 //                equalTo : "#password"
 //            },
 //        },
 //        messages: {
 //            firstname: {
 //                required: "Please enter your full name",
 //                minlength: "Your full name must be at least 2 characters long"
 //            },
 //            lastname: {
 //                required: "Please enter your full name",
 //                minlength: "Your full name must be at least 2 characters long"
 //            },
 //            email: {
 //                required: "Please enter your email address",
 //                mail: 'Please enter a valid email address'
 //            },
 //            phone: {
 //                required: "Please enter phone number",
 //                phoneno: true,
 //            },
 //            password: {
 //                required: "Please provide a password",
 //                minlength: "Password field must be minimum 8 character length with at least 1 number and 1 alphabet",
 //                alphaNumeric: "Password field must be minimum 8 character length with at least 1 number and 1 alphabet",
 //            },
 //            cpassword: "Confirm password should be same as password",
 //        },
 //    });
     $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
     }); 
     $('body').on('click', '.buy_now', function(e){
       var firstname = $('#firstname').val();
       var lastname = $('#lastname').val();
       var email = $('#email').val();
       var phone = $('#phone').val();
       var password = $('#password').val();
       var totalAmount = $(this).attr("data-amount");
       var product_id =  $(this).attr("data-id");
       var options = {
       "key": "rzp_test_lUEKw7XYTGNoYD",
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
                firstname : firstname,
                lastname : lastname,
                email : email,
                phone : phone,
                password : password,
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
     e.preventDefault();
     });
</script>
@endsection