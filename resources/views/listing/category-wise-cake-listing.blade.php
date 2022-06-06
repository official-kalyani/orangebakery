@extends('layouts.app')
@section('content')
<?php 
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$type = Request::cookie('type');
$store_id = Request::cookie('store_id');
?>
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
@else
<div class="hero-banner">
    <img class="d-block w-100" src="https://orangebakery.in/uploads/category/1597657177.png" alt="First slide">
</div>

@endif

@if($category->id == 1)
<div class="carousel-caption">
<div class="col-md-5 offset-md-7 text-left">
    <div class="banner-content">
        <form class="form-inline">
            <label class="radio radio-inline" style="color: black;">
                <input type="radio" value="delivery_selected" id="select_delivery_delivery" name="select_delivery_delivery"
                <?php if ($type == "" || $id == "") { ?>
                    checked
                <?php } else { ?>
                    <?php if ($type == "delivery") { ?>
                        checked
                    <?php } ?>
                <?php } ?> >
                <i class="input-helper"></i>
                Delivery
            </label>
            <label class="radio radio-inline" style="color: black;">
                <input type="radio" value="takeaway_selected" id="select_delivery_takeaway" name="select_delivery_delivery" data-toggle="modal" data-target="#delivery_pickup_modal"
                    <?php if ($type == "pickup") { ?>
                        checked
                    <?php } ?> >

                <i class="input-helper"></i>
                Takeaway
            </label>
        </form>
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="delivery_pickup_modal" role="dialog">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-body">
        <section class="login-page section-b-space my-5">
            <div class="container">
                <div class="row" id="loginModal_content">
                    <div class="col-lg-12">
                        <span class="online">Buy From</span> 
                        <span class="ob"> Orange Bakery</span>
                        <div class="theme-card">
                            <label class="radio radio-inline">
                                <input type="radio" name="delivery_type" id="delivery_type_delivery" class="delivery_type" value="delivery">
                                <i class="input-helper"></i>
                                Delivery
                            </label>
                            <label class="radio radio-inline">
                                <input type="radio" id="delivery_type_takeaway" class="delivery_type" value="pick_up" name="delivery_type">
                                <i class="input-helper"></i>
                                Takeaway
                            </label>
                            <div id="show_delivery">
                                <br>
                                 <div class="form-group">
                                    <br>
                                    <form method="post" action="{{url('setCookie')}}">
                                        @csrf
                                    <!-- <input type="hidden" value="delivery" name="type"> -->
                                    <!-- <button class="btn cart-btn" id="proceed">PROCEED</button> -->
                                    </form>
                                </div>
                            </div>
                            <div id="show_pick_up">
                                <br>
                                <span>Which store would you like to Takeaway order from</span>
                                <div class="form-group">
                                    <br>
                                    <select id="location" name="location" class="form-control dynamic">
                                        @foreach($location as $row)
                                            <option value="{{$row->id}}">{{$row->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select id="store" name="store" class="form-control dynamic">
                                        <option value="" selected="disabled">Select Store</option>
                                    </select>
                                </div>
                                
                            </div>
                            <div class="spinimg">
                                <img alt="Spin" src="{{asset('images/spin.gif')}}" style="width:60px;height:60px;">
                            </div>
                            <div id="getstorelocation">
                                
                            </div>
                            {{ csrf_field() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>
<!-- Modal -->
</div>
<div class="container">
    <h2 class="h2 text-center owncake">Build Your Own Cake</h2>
</div>
<div class="container">
    <div class="sellerslide">
        <?php
        foreach($cakes as $key => $val){
            if($key=='glowcakes')
            {  ?>
            <div class="row">
                <div class="col-8"> 
                    <h2 class="h2">{{$val['title']}}</h2></div>
                <div class="col-4 text-right"> <a href="{{url('/')}}/flavour/{{$val['slug']}}" class="obtn">View All</a> </div>
            </div>
             <section class="cake-subcategory-product-listing slider">
               <?php  foreach($val['products'] as $product){
                    ?>
                    <div class="slide"> 
                        <div class="product-box text-center">
                            <div class="p-img">
                                <a style="color: black;" href="{{url('/')}}/products/{{$product['slug']}}"><img src="{{ URL::to('/') }}/uploads/product/{{ @$product['product_image'] }}" alt="" class="w-100"></a>
                            </div>
                            <div class="product-text">
                                <p class="product-name"><a href="{{url('/')}}/products/{{$product['slug']}}">{{$product['name']}}</a></p>
                                    <div class="price py-3">
                                    <div class="selling_price text-right">
                                        ₹{{@$product['price']}}.00
                                    </div>
                                    <div class="mrp_price text-center">
                                        <del>
                                            ₹{{@$product['mrp_price']}}.00
                                        </del>
                                    </div>
                                    <div class="percent text-left">
                                        <span >
                                        {{100-round($product['discount'])}}% off
                                        </span>
                                    </div>
                                    <div class="clearfix"></div> 
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @guest
                                        <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Buy Now</button>                                
                                        @else
                                        <a href="javascript:void(0);" data-id="{{ $product['id'] }}" data-title="{{ $product['name'] }}" data-priceid="<?php echo @$product['price_id']; ?>" class="btn cart-btn a_btn obBuyNow">Buy Now</a>
                                        @endguest
                                    </div>
                                    <div class="col-md-6">
                                        <a href="javascript:void(0);" data-id="{{ $product['id'] }}" data-title="{{ $product['name'] }}" data-priceid="<?php echo @$product['price_id']; ?>" class="btn cart-btn a_btn obcart">Add To Cart</a>
                                    </div>
                                    @if($product['is_customize'] == 1)                             
                                    <div class="col-md-12">
                                      @guest
                                        <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Customize</button>
                                      @else
                                        <a href="{{url('/customize')}}?product_id={{$product['id']}}" class="btn cart-btn a_btn">Customize</a>
                                      @endguest
                                    </div>
                                    @endif                            
                                </div>
                            </div>
                        </div> 
                    </div>
               <?php  } ?>
               </section>
           <?php  }
            if($key=='flavours')
            { ?>
                <div class="ckebflov pt-3 pb-5">
                        <h2 class="h2">Cakes by Flavour</h2>
                        <div class="row">
                            <div class="col-sm-12">                  
                                <section class="flavour-listings">
                                @foreach($val as $flavour)
                                    <div class="text-center">
                                        <a href="{{url('/')}}/flavour/{{$flavour->slug}}">
                                            <img src="{{ URL::to('/') }}/uploads/category/{{ @$flavour->image }}" alt="{{$flavour->name}}" class="w-100">
                                        </a>
                                        <p><a style="color: black;" href="{{url('/')}}/flavour/{{$flavour->slug}}">{{$flavour->name}}</a></p>
                                    </div>
                                @endforeach

                                </section>
                            </div>
                        </div>
                </div>
            <?php }
            if($key=='photocake')
            {
                ?>
                <div class="row">
                    <div class="col-8"> 
                        <h2 class="h2">{{$val['title']}}</h2></div>
                    <div class="col-4 text-right"> <a href="{{url('/')}}/flavour/{{$val['slug']}}" class="obtn">View All</a> </div>
                </div>
             <section class="cake-subcategory-product-listing slider">
               <?php  foreach($val['products'] as $product){ 
                    ?>
                    <div class="slide"> 
                        <div class="product-box text-center">
                            <div class="p-img">
                                <a style="color: black;" href="{{url('/')}}/products/{{$product['slug']}}"><img src="{{ URL::to('/') }}/uploads/product/{{ @$product['product_image'] }}" alt="" class="w-100"></a>
                            </div>
                            <div class="product-text">
                                <p class="product-name"><a href="{{url('/')}}/products/{{$product['slug']}}">{{$product['name']}}</a></p>
                                    <div class="price py-3">
                                    <div class="selling_price text-right">
                                        ₹{{@$product['price']}}.00
                                    </div>
                                    <div class="mrp_price text-center">
                                        <del>
                                            ₹{{@$product['mrp_price']}}.00
                                        </del>
                                    </div>
                                    <div class="percent text-left">
                                        <span >
                                        {{100-round($product['discount'])}}% off
                                        </span>
                                    </div>
                                    <div class="clearfix"></div> 
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        @guest
                                        <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Buy Now</button>                                
                                        @else
                                        <a href="javascript:void(0);" data-id="{{ $product['id'] }}" data-title="{{ $product['name'] }}" data-priceid="<?php echo @$product['price_id']; ?>" class="btn cart-btn a_btn obBuyNow">Buy Now</a>
                                        @endguest
                                    </div>
                                    <div class="col-md-6">
                                        <a href="javascript:void(0);" data-id="{{ $product['id'] }}" data-title="{{ $product['name'] }}" data-priceid="<?php echo @$product['price_id']; ?>" class="btn cart-btn a_btn obcart">Add To Cart</a>
                                    </div>
                                    @if($product['is_customize'] == 1)                             
                                    <div class="col-md-12">
                                      @guest
                                        <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Customize</button>
                                      @else
                                        <a href="{{url('/customize')}}?product_id={{$product['id']}}" class="btn cart-btn a_btn">Customize</a>
                                      @endguest
                                    </div>
                                    @endif                            
                                </div>
                            </div>
                        </div> 
                    </div>
               <?php  } ?>
               </section>
           <?php 
            }
            if($key=='occasions')
            {
                ?>
                <div class="ckebflov pt-3 pb-5">
                        <h2 class="h2">Cakes by Occasion</h2>
                        <div class="row">
                            <div class="col-sm-12">                  
                                <section class="flavour-listings">
                                @foreach($val as $occasion)
                                    <div class="text-center">
                                        <a href="{{url('/')}}/occasion/{{$occasion->slug}}">
                                            <img src="{{ URL::to('/') }}/uploads/occasion/{{ @$occasion->image }}" alt="{{$occasion->name}}" class="w-100">
                                        </a>
                                        <p><a style="color: black;" href="{{url('/')}}/occasion/{{$occasion->slug}}">{{$occasion->name}}</a></p>
                                    </div>
                                @endforeach

                                </section>
                            </div>
                        </div>
                </div>
            <?php  
            }
            if($key=='premiumcake')
            {
                ?>
                      <div class="row">
                          <div class="col-8"> 
                              <h2 class="h2">{{$val['title']}}</h2></div>
                          <div class="col-4 text-right"> <a href="{{url('/')}}/flavour/{{$val['slug']}}" class="obtn">View All</a> </div>
                      </div>
                    <section class="cake-subcategory-product-listing slider">
                        <?php  foreach($val['products'] as $product){
                            ?>
                            <div class="slide"> 
                                <div class="product-box text-center">
                                    <div class="p-img">
                                        <a style="color: black;" href="{{url('/')}}/products/{{$product['slug']}}"><img src="{{ URL::to('/') }}/uploads/product/{{ @$product['product_image'] }}" alt="" class="w-100"></a>
                                    </div>
                                    <div class="product-text">
                                        <p class="product-name"><a href="{{url('/')}}/products/{{$product['slug']}}">{{$product['name']}}</a></p>
                                            <div class="price py-3">
                                            <div class="selling_price text-right">
                                                ₹{{@$product['price']}}.00
                                            </div>
                                            <div class="mrp_price text-center">
                                                <del>
                                                    ₹{{@$product['mrp_price']}}.00
                                                </del>
                                            </div>
                                            <div class="percent text-left">
                                                <span >
                                                {{100-round($product['discount'])}}% off
                                                </span>
                                            </div>
                                            <div class="clearfix"></div> 
                                        </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    @guest
                                                    <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Buy Now</button>                                
                                                    @else
                                                    <a href="javascript:void(0);" data-id="{{ $product['id'] }}" data-title="{{ $product['name'] }}" data-priceid="<?php echo @$product['price_id']; ?>" class="btn cart-btn a_btn obBuyNow">Buy Now</a>
                                                    @endguest
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="javascript:void(0);" data-id="{{ $product['id'] }}" data-title="{{ $product['name'] }}" data-priceid="<?php echo @$product['price_id']; ?>" class="btn cart-btn a_btn obcart">Add To Cart</a>
                                                </div>
                                                @if($product['is_customize'] == 1)                             
                                                <div class="col-md-12">
                                                  @guest
                                                    <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Customize</button>
                                                  @else
                                                    <a href="{{url('/customize')}}?product_id={{$product['id']}}" class="btn cart-btn a_btn">Customize</a>
                                                  @endguest
                                                </div>
                                                @endif                            
                                            </div>
                                    </div>
                                </div> 
                            </div>
                        <?php  } ?>
                </section>
         <?php
            }
        }
        ?>
 </div>
    <div class="text-center py-3">
        <a href="{{url('/')}}/category/cake/allproducts" class="btn cart-btn">VIEW ALL CAKES</a>
    </div>
</div>




@else
<?php
        if($type == 'pickup' && $store_id !='') {
            $products_tbl = DB::table('products')
                        ->join('product_prices','product_prices.product_id','products.id')
                        ->join('product_images','product_images.product_id','products.id')
                        ->where('products.category_id',$category->id)
                        ->wherenotNull('products.parent_product_id')
                        ->where('products.store_id',$store_id)
                        ->where('products.steps_completed',3)
                        ->select('products.*','product_prices.price','product_prices.mrp_price','product_prices.weight');
        } else {
            $products_tbl = DB::table('products')
                        ->join('product_prices','product_prices.product_id','products.id')
                        ->join('product_images','product_images.product_id','products.id')
                        ->where('products.category_id',$category->id)
                        ->whereNull('products.parent_product_id')
                        ->where('products.steps_completed',3)
                        ->select('products.*','product_prices.price','product_prices.mrp_price','product_prices.weight');
        }

    if (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "high-to-low")
    {
        $products_tbl->orderBy('product_prices.price','DESC');
    }
    elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "low-to-high") 
    {
        $products_tbl->orderBy('product_prices.price','ASC');
    }
    elseif (isset($_REQUEST['s']) && $_REQUEST['s'] != '' && $_REQUEST['s'] == "popular") 
    {
        $products_tbl->orderBy('product_prices.price','ASC');
    }
    else
    {
        $products_tbl->orderBy('products.id','desc');
    }
    $products = $products_tbl->distinct()->paginate(12);
?>
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
    <h2 class="h2">{{$category->name}}</h2>
    <span id="status"></span>
    @if(count($products) > 0)
     <div class="row">
        @foreach($products as $product)
            <?php
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
                                <a href="javascript:void(0);" data-id="{{ $product->id }}" data-title="{{ $product->name }}" data-priceid="<?php echo @$product_price->id; ?>" class="btn cart-btn a_btn obcart">Add To cart 3</a>
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
            <h4 class="text-center" style="padding-top: 20px; padding-bottom: 20px;">No {{$category->name}} Found</h4>
        </div>
    </div>
    @endif
    <div class="text-center py-3">
        {{$products->links()}}
    </div>
</div>
<script type="text/javascript">
    $(window).load(function(){
   setTimeout(function(){
       $('#loginModal').modal('show');
   }, 12000);
});
</script>
@endif
@endsection
