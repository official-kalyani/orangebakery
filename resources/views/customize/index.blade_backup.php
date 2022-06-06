@extends('layouts.app')
@section('content')
<style type="text/css">
  .error,span{
      color:red;
      font-size: 13px;
  }
.container1 {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer; 
  font-size: 15px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.container1 input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 25px;
  width: 25px;
  background-color: #eee;
  border-radius: 50%;
}

/* On mouse-over, add a grey background color */
.container1:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the radio button is checked, add a blue background */
.container1 input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.container1 input:checked ~ .checkmark:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.container1 .checkmark:after {
  top: 9px;
  left: 9px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: white;
}
</style>
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Customize Your Cake</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="#">Customize Your Cake</a> </li>
            </ul>
        </div>
    </div>
</div>
<form method="post" action="{{url('saveCustomizedImage')}}" enctype='multipart/form-data'>
@csrf
<input type="hidden" name="product_id" value="{{$_REQUEST['product_id']}}">
<input type="hidden" name="return_url" value="{{$_REQUEST['return_url']}}">
@if(count($customize_shapes))
<div class="occasion">
    <div class="container">
        <h2 class="h2">Select Shapes</h2>
        <div class="row">
            <?php $istCounter = 0; ?>
            @foreach($customize_shapes as $customize_shape)
            <?php $istCounter++; ?>
            <div class="col-sm-2">
                <img src="{{ URL::to('/') }}/uploads/customize-shapes/{{$customize_shape->image}}" alt="" class="w-100">
                <label class="container1">
                    <input type="radio" @if($istCounter == 1) checked @endif value="{{$customize_shape->id}}" name="customize_shape_id">
                    <span class="checkmark"></span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@if(count($customize_flavours))
<div class="occasion">
    <div class="container">
        <h2 class="h2">Select Flavours</h2>
        <div class="row">
            <?php $istCounter1 = 0; ?>
            @foreach($customize_flavours as $customize_flavour)
            <?php $istCounter1++; ?>
            <div class="col-sm-2" style="z-index:99999;">
                <img src="{{ URL::to('/') }}/uploads/customize-flavours/{{$customize_flavour->image}}" alt="" class="w-100">
                <label class="container1">
                    <input type="radio" @if($istCounter1 == 1) checked @endif value="{{$customize_flavour->id}}" name="customize_flavour_id">
                    <span class="checkmark"></span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<div class="occasion">
    <div class="container">
        <div class="col-sm-6">
            <h2 class="h2">Message on your cake</h2>
            <div class="form-group">
                <textarea name="message_on_cake" class="form-control @error('message_on_cake') is-invalid @enderror" rows="7"></textarea>
                @if ($errors->has('message_on_cake'))
                    <span class="required">
                        <strong>{{ $errors->first('message_on_cake') }}</strong>
                    </span>
                @endif 
            </div>
        </div>
    </div>
</div>
<?php
$product = \App\Models\Product::where('id',$_REQUEST['product_id'])->first();?>@if($product->is_photocake == 1)
<div class="occasion">
    <div class="container">
        <div class="col-sm-6">
            <h2 class="h2">Upload your photo cake:</h2>
            <input class="form-control" name="photo_cake" type="file"/>
        </div>
    </div>
</div>
@endif
<div class="occasion">
    <div class="container">
        <div class="col-sm-6">
            <input type="submit" name="submit" class="cart-btn">
        </div>
    </div>
</div>
</form>
@endsection