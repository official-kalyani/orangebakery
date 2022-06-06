@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/testimonial.css') }}">
<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
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
                <img class="d-block w-100" src="{{ URL::to('/') }}/uploads/sliders/{{ @$slider->image }}" alt="First slide">
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
<div class="carousel-caption">
<div class="col-md-5 offset-md-7 text-left">
    <div class="banner-content"></p>
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
                                        <?php $location = \App\Models\Location::all(); ?>
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
<div class="category mt-4">
    <div class="container">
        <div class="row">
            <div class="col-12"> 
               <h2 class="h2">Explore Menu</h2>
            </div>
        </div>
        <div class="row">
           @foreach($categories as $category)
                   <div class="col-md-3 col-6 mb-3">
       <div class="c-box text-center">
       <a href="{{url('/')}}/category/{{$category->slug}}">
                            <img src="{{ URL::to('/') }}/uploads/category/{{ @$category->image }}" alt="{{$category->name}}" class="w-100">
                    </a>
                    <div  class="m-2 pb-2">
                            <a href="{{url('/')}}/category/{{$category->slug}}">  {{$category->name}}
                               </a></div>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</div>
@if ($type == "delivery" || $type == "" || $id == "")
<?php $counter = 1;?>
    @foreach($sections as $section)
        <?php  $section_items = \App\Models\SectionItem::where('section_id',$section->id)->get(); ?>
    <div class="sellerslide">
        <div class="container">
            <div class="row1">
                <div class="col-12">
                    @if($section->type == "Product")
                    <h2 class="h2" style="display:inline-block">{{$section->name}}</h2>
                        <a href="{{url('/')}}/section/{{$section->id}}" class="btn cart-btn float-right">View All</a>
        
                    <section class="best-seller slider">
                        @foreach($section_items as $section_item)
                        <?php  $products = \App\Models\Product::where('id',$section_item->product_id)->where('steps_completed',3)->get(); ?>
                        @foreach($products as $product)
                        <?php  
                        $product_price = \App\Models\ProductPrice::where('product_id',$product->id)->where('show_price',1)->first(); 
                         $product_image = \App\Models\ProductImage::where('product_id',$product->id)->where('is_featured',1)->first(); 
                         $customize = \App\Models\ProductImage::where('product_id',$product->id)->where('customize',1)->first();
                        $discountPrice = $product_price['mrp_price'] - $product_price['price'];
                        $discountPercentage = 0;
                        if($product_price['price']!=0 && $product_price['mrp_price']!=0){
                        $discountPercentage = ($product_price['price'] / $product_price['mrp_price'])*100;
                        }
                        $counter ++;
                        ?>
                            <div class="slide"> 
                                <div class="product-box text-center">
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
                        @endforeach
                    </section>
                   
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="sellerslide">
        <div class="container">
            <div class="row1">
                <div class="col-12">
                    <h2 class="h2">Products</h2>
                    <section class="best-seller slider">
                        @foreach($products as $product)
                        <?php  
                        $product_price = \App\Models\ProductPrice::where('product_id',$product->parent_product_id)->where('show_price',1)->first();  
                        $product_image = \App\Models\ProductImage::where('product_id',$product->parent_product_id)->where('is_featured',1)->first();
                        $customize = \App\Models\ProductImage::where('product_id',$product->parent_product_id)->where('customize',1)->first();
                        $discountPrice = $product_price->mrp_price - $product_price->price;
                        $discountPercentage = ($product_price->price / $product_price->mrp_price)*100;
                        ?>
                            <div class="slide"> 
                                <div class="product-box text-center">
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
                    </section>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="sellerslide py-3">
    <div class="container">
        @foreach($sections as $section)
            @if($section->type == "Offer")
            <div class="col-md-12">
                <img src="{{ URL::to('/') }}/uploads/offers/{{ @$section->image }}" alt="{{ @$section->name }}" class="w-100">
            </div>
            @endif
        @endforeach
    </div>
</div>
<?php $testimonials = \App\Models\Testimonial::where('show_in_website_home','yes')->get(); ?>
<?php $testimonial_banner=$testimonial_banner1=0; ?>
<section id="testim" class="testim">
    <div class="wrap">
        <span id="right-arrow" class="arrow right fa fa-chevron-right"></span>
        <span id="left-arrow" class="arrow left fa fa-chevron-left "></span>
        <ul id="testim-dots" class="dots">
            @foreach($testimonials as $testimonial)
            <?php $testimonial_banner1++; ?>
                <li class="dot @if($testimonial_banner1 == 1) active @else @endif"></li>
            @endforeach
        </ul>
        <div id="testim-content" class="cont">

            @foreach($testimonials as $testimonial)
            <?php $testimonial_banner++; ?>
            <div class="@if($testimonial_banner == 1) active @else @endif">
                <div class="img"><img src="{{ URL::to('/') }}/uploads/testimonials/{{ @$testimonial->image }}" alt=""></div>
                <h2>{{$testimonial->title}}</h2>
                <p>{{$testimonial->description}}</p>                   
            </div>
            @endforeach
        </div>
    </div>
    @if(count($testimonials))
    <div class="text-center pb-5">
        <a href="{{url('/')}}/testimonials" class="btn cart-btn">View All Testimonials</a>
    </div>
    @endif
</section>



<script src="https://use.fontawesome.com/1744f3f671.js"></script>
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
<script>
    
'use strict'
var testim = document.getElementById("testim"),
        testimDots = Array.prototype.slice.call(document.getElementById("testim-dots").children),
    testimContent = Array.prototype.slice.call(document.getElementById("testim-content").children),
    testimLeftArrow = document.getElementById("left-arrow"),
    testimRightArrow = document.getElementById("right-arrow"),
    testimSpeed = 4500,
    currentSlide = 0,
    currentActive = 0,
    testimTimer,
        touchStartPos,
        touchEndPos,
        touchPosDiff,
        ignoreTouch = 30;
;

window.onload = function() {

    // Testim Script
    function playSlide(slide) {
        for (var k = 0; k < testimDots.length; k++) {
            testimContent[k].classList.remove("active");
            testimContent[k].classList.remove("inactive");
            testimDots[k].classList.remove("active");
        }

        if (slide < 0) {
            slide = currentSlide = testimContent.length-1;
        }

        if (slide > testimContent.length - 1) {
            slide = currentSlide = 0;
        }

        if (currentActive != currentSlide) {
            testimContent[currentActive].classList.add("inactive");            
        }
        testimContent[slide].classList.add("active");
        testimDots[slide].classList.add("active");

        currentActive = currentSlide;
    
        clearTimeout(testimTimer);
        testimTimer = setTimeout(function() {
            playSlide(currentSlide += 1);
        }, testimSpeed)
    }

    testimLeftArrow.addEventListener("click", function() {
        playSlide(currentSlide -= 1);
    })

    testimRightArrow.addEventListener("click", function() {
        playSlide(currentSlide += 1);
    })    

    for (var l = 0; l < testimDots.length; l++) {
        testimDots[l].addEventListener("click", function() {
            playSlide(currentSlide = testimDots.indexOf(this));
        })
    }

    playSlide(currentSlide);

    // keyboard shortcuts
    document.addEventListener("keyup", function(e) {
        switch (e.keyCode) {
            case 37:
                testimLeftArrow.click();
                break;
                
            case 39:
                testimRightArrow.click();
                break;

            case 39:
                testimRightArrow.click();
                break;

            default:
                break;
        }
    })
        
        testim.addEventListener("touchstart", function(e) {
                touchStartPos = e.changedTouches[0].clientX;
        })
    
        testim.addEventListener("touchend", function(e) {
                touchEndPos = e.changedTouches[0].clientX;
            
                touchPosDiff = touchStartPos - touchEndPos;
            
                console.log(touchPosDiff);
                console.log(touchStartPos); 
                console.log(touchEndPos);   

            
                if (touchPosDiff > 0 + ignoreTouch) {
                        testimLeftArrow.click();
                } else if (touchPosDiff < 0 - ignoreTouch) {
                        testimRightArrow.click();
                } else {
                    return;
                }
            
        })
}
</script>
@endsection