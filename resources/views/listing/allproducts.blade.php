@extends('layouts.app')
@section('content')
<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>ALL Products</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/products/all')}}">ALL Products</a> </li>
            </ul>
        </div>
    </div>
</div> 
<div class="occasion py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="oc-title text-center mb-2 sorting_div">
                    <a href="?s=popular">Popular</a>
                </div>
            </div>
             <div class="col-md-4">
                <div class="oc-title text-center mb-2 sorting_div">
                    <a href="?s=high-to-low">High to Low</a>
                </div>
            </div>
             <div class="col-md-4">
                <div class="oc-title text-center mb-2 sorting_div">
                    <a href="?s=low-to-high">Low to High</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <!-- <h2 class="h2">CAKES</h2> -->
            <span id="status"></span>
            @if(count($products) > 0)
             <div class="row">
                @foreach($products as $product)
                    <?php
                        if ($type == "delivery" || $type == "" || $id == ""){
                             $product_price = \App\Models\ProductPrice::where('product_id',$product->id)->where('show_price',1)->first(); 
                             $product_image = \App\Models\ProductImage::where('product_id',$product->id)->where('is_featured',1)->first(); 
                             $customize = \App\Models\ProductImage::where('product_id',$product->id)->where('customize',1)->first();
                        }  
                        else{
                             $product_price = \App\Models\ProductPrice::where('product_id',$product->parent_product_id)->where('show_price',1)->first();  
                            $product_image = \App\Models\ProductImage::where('product_id',$product->parent_product_id)->where('is_featured',1)->first();
                            $customize = \App\Models\ProductImage::where('product_id',$product->parent_product_id)->where('customize',1)->first();
                        }
                        $discountPrice = $product_price->mrp_price - $product_price->price;
                        $discountPercentage = ($product_price->price / $product_price->mrp_price)*100;
                    ?>
                     <div class="col-md-3">
                        <div class="product-box text-center mb-3">
                            <div class="p-img">
                                <a style="color: black;" href="{{url('/')}}/products/{{$product->slug}}"><img src="{{ URL::to('/') }}/uploads/product/{{ @$product_image->images }}" alt="" class="w-100"></a>
                            </div>
                            <div class="product-text">
                                <p class="product-name"><a href="{{url('/')}}/products/{{$product->slug}}">{{$product->name}}</a></p>
                                 <div class="price py-3">
                                    <div class="selling_price text-right">
                                        ₹{{@$product_price->price}}.00
                                    </div>
                                    <div class="mrp_price text-center">
                                        <del>
                                            ₹{{@$product_price->mrp_price}}.00
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
                                        <a href="javascript:void(0);" data-id="{{ $product->id }}" data-title="{{ $product->name }}" data-priceid="<?php echo @$product_price->id; ?>" class="btn cart-btn a_btn obBuyNow">Buy Now</a>
                                        @endguest
                                    </div>
                                    <div class="col-md-6">
                                        <a href="javascript:void(0);" data-id="{{ $product->id }}" data-title="{{ $product->name }}" data-priceid="<?php echo @$product_price->id; ?>" class="btn cart-btn a_btn obcart">Add To Cart</a>
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
                @endforeach
            </div>
            @else
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center" style="padding-top: 20px; padding-bottom: 20px;">No Products Found</h4>
                </div>
            </div>
            @endif
                <div class="text-center py-3">
                    {{$products->links()}}
                </div>
        </div>
    </div>
</div>
<div class="download-app">
    <div class="container-fluid">
        <div class="col-md-4">
            <h5>Unlock Exclusive Offers</h5>
            <p>For lightning fast ordering experience download the cake app
            </p>
            <div class="row">
                <div class="col-md-6">
                    <a target="_blank" href="https://play.google.com/store/apps/details?id=com.orangebakeryshop"><img src="{{ URL::to('/') }}/images/gplay.png" alt="" class="img-fluid"></a>   
                </div>
                <!-- <div class="col-md-6">
                    <img src="images/appl.png" alt="" class="w-100">
                </div> -->
            </div>
        </div>
    </div>
</div>

@endsection