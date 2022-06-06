@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Cart</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/cart')}}">Cart</a> </li>
            </ul>
        </div>
    </div>
</div>
<div class="cart-secion my-5">
  <div class="container">
    @include('layouts.maincart')
  </div> 
  
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

  <div class="clearfix"></div>
</div>
<script>
 //document.addEventListener('contextmenu', function(e) {
   //e.preventDefault();
 //});
</script>
@endsection