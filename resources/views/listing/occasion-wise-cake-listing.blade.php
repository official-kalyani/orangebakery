@extends('layouts.app')
@section('content')
<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>{{@$occasion->name}}</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/')}}/flavour/{{$occasion->slug}}">{{@$occasion->name}}</a> </li>
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
<div class="ckebflov pt-3 pb-5">
    <div class="container">
        <h2 class="h2">Cakes by Ocassion</h2>
        <div class="row">
            <div class="col-sm-12">                  
                <section class="flavour-listings">
                    @foreach($occasions as $occasion_arr)
                        <div class="text-center">
                            <a href="{{url('/')}}/occasion/{{$occasion_arr->slug}}">
                                <img src="{{ URL::to('/') }}/uploads/occasion/{{ @$occasion_arr->image }}" alt="{{$occasion_arr->name}}" class="w-100">
                            </a>
                            <p><a style="color: black;" href="{{url('/')}}/occasion/{{$occasion_arr->slug}}">{{$occasion_arr->name}}</a></p>
                        </div>
                    @endforeach
                </section>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h2 class="h2">{{$occasion->name}}</h2>
            <span id="status"></span>
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
            @else
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center" style="padding-top: 20px; padding-bottom: 20px;">No {{@$occasion->name}} Found</h4>
                </div>
            </div>
            @endif
            <div class="text-center py-3">
                {{$products->links()}}
            </div>
        </div>
    </div>
</div>
<script>
    <?php foreach ($products as $product) { ?>
    <?php
        $allratings = \App\Models\ProductRating::where('product_id',$product->id)->get();
        $totalRating = array();
        foreach ($allratings as $allrating) {
            $totalRating[] = $allrating->rating;
        }
        $totalRatingcount = count($allratings);
        if ($totalRatingcount != 0) {
            $prductRating = array_sum($totalRating) / $totalRatingcount;
        }
        else{
            $prductRating = 0;
        }
    ?>
    $("#rateYooProduct<?php echo $product->id; ?>").css('margin','0 auto');
    $("#rateYooProduct<?php echo $product->id; ?>").rateYo({
        rating: <?php echo $prductRating; ?>,
        readOnly: true,
        starWidth: "20px"
    });     
    $(".add-to-cart<?php echo $product->id; ?>").click(function (e) {
            e.preventDefault();

            var ele = $(this);
            var price_id = $('#price_id<?php echo $product->id; ?>').val();

            $.ajax({
                url: '{{ url('add-to-cart') }}' + '/' + ele.attr("data-id"),
                method: "get",
                data: {_token: '{{ csrf_token() }}',"price_id":price_id},
                dataType: "json",
                success: function (response) {

                    ele.siblings('.btn-loading').hide();
                    Swal.fire(
                      'Added!',
                      'Cake Added to Cart',
                      'success'
                    )
                    $("#cartCounter").html(response.cartCounter);
                    $("#refresh-sidebar-cart").html(response.data);
                    $(".sidebar_cartCounter").html(response.cartCounter);
                }   
            });
    });
<?php } ?>
</script>
@endsection
