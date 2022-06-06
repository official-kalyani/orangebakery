@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Thank You</h2>
            <ul>
                <li><a href="{{ URL::to('/') }}"> Home </a> >></li>
                <!-- <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100"> -->
                <li><a href="{{url('/thank-you')}}">Thank You</a> </li>
            </ul>
        </div>
    </div>
</div>
<div class="cart-secion my-5">
    <div class="container">
        <!-- <h2 class="text-center">Thank You for your order.</h2> -->
        <div class="jumbotron text-center">
          <h1 class="display-3">Thank You!</h1>
          <p class="lead"><strong>Your Order</strong> placed successfully.</p>
          <hr>
          <p>
            Having trouble? <a href="{{ URL::to('/contact')}}" >Contact us</a>
          </p>
          <p class="lead">
            <a class="btn btn-warn" href="{{ URL::to('/') }}" role="button">Continue to homepage</a>
          </p>
        </div>
        <div class="text-center">
            <a href="{{url('user/orders')}}" class="btn cart-btn">View Orders</a>
        </div>
    </div>
</div>
@endsection