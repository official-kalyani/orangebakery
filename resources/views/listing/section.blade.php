@extends('layouts.app')
@section('content')
<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>{{$Check_section_items->name}}</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/bestseller')}}">{{$Check_section_items->name}}</a> </li>
            </ul>
        </div>
    </div> 
</div>
<div class="sellerslide" style="padding-top: 100px; padding-bottom: 100px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
            <div class="row">
                @if(count($section_items))
                @foreach($section_items as $section_item)
                    <?php
                    $type = Request::cookie('type');
                    $store_id = Request::cookie('store_id');
                    if($type == 'pickup' && $store_id !='') {
                        $products = \App\Models\Product::wherenotNull('parent_product_id')->wherenotNull('store_id')->where('store_id',$store_id)->where('id',$section_item->product_id)->where('steps_completed',3)->paginate(12);
                    } else {
                       $products = \App\Models\Product::whereNull('parent_product_id')->where('id',$section_item->product_id)->where('steps_completed',3)->paginate(12); 
                    }

                    ?>
                    @if(count($products))
                    @foreach($products as $product)
                    <?php  
                        $product_price = \App\Models\ProductPrice::where('product_id',$product->id)->where('show_price',1)->first();
                        $product_image = \App\Models\ProductImage::where('product_id',$product->id)->where('is_featured',1)->first();
                        $customize = \App\Models\ProductImage::where('product_id',$product->id)->where('customize',1)->first();
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
                    @endif
                @endforeach
            </div>
                @if(count($products))
                {{$products->links()}}
                @endif
                @endif
            </div>
        </div>
    </div>
</div>

@endsection