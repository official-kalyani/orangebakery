@extends('layouts.app')
@section('content')
<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
@if(count($sliders))
<div class="hero-banner">
    <div id="sliders" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php $banner=0; ?>
            @foreach($sliders as $slider)
                <li data-target="#sliders" data-slide-to="{{$banner++}}" @if($banner == 0) class="active" @endif ></li>
            @endforeach
        </ol> 
        <div class="carousel-inner">
            <?php $banner1=0; ?>
            @foreach($sliders as $slider)
            <?php $banner1++; ?>
            <div class="carousel-item @if($banner1 == 1) active @else @endif">
                <img class="d-block w-100" src="{{ URL::to('/') }}/uploads/category/{{ @$slider->image }}" alt="First slide">
            </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#sliders" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#sliders" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
@else
<div class="hero-banner">
    <img class="d-block w-100" src="https://orangebakery.in/uploads/category/1597657177.png" alt="First slide">
</div>

@endif
<div class="container">
    <div class="row">
        <div class="col-sm-12">
                @if(count($products) > 0)
                <div class="row">
                    @foreach($products as $product)
                        <?php  
                        $type = Request::cookie('type');
                        $store_id = Request::cookie('store_id');
                        if($type == 'pickup' && $store_id !='') {
                            $product_price = \App\Models\ProductPrice::where('product_id',$product->parent_product_id)->where('show_price',1)->first();  
                            $product_image = \App\Models\ProductImage::where('product_id',$product->parent_product_id)->where('is_featured',1)->first();
                            $customize = \App\Models\ProductImage::where('product_id',$product->parent_product_id)->where('customize',1)->first(); 
                        } else {
                           $product_price = \App\Models\ProductPrice::where('product_id',$product->id)->where('show_price',1)->first(); 
                            $product_image = \App\Models\ProductImage::where('product_id',$product->id)->where('is_featured',1)->first(); 
                            $customize = \App\Models\ProductImage::where('product_id',$product->id)->where('customize',1)->first(); 
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
                 <div class="text-center py-3">
                    {{$products->links()}}
                </div>
                @else
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center" style="padding-top: 20px; padding-bottom: 20px;">No {{@$category->name}} Found</h4>
                    </div>
                </div>
                @endif
        </div>
    </div>
</div>
@endsection
