@extends('layouts.app')
@section('content')
<?php 
    $products->description = preg_replace("/^<p.*?>/", "",$products->description);
    $products->description = preg_replace("|</p>$|", "",$products->description);
    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Product Details</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="#">Product Details</a> </li>
            </ul>
        </div>
    </div>
</div>
<div class="product-details-info mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12">
            <script type="text/javascript" src="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1565190285/Scripts/xzoom.min.js"></script>
            <link rel="stylesheet" type="text/css" href="https://res.cloudinary.com/dxfq3iotg/raw/upload/v1565190284/Scripts/xzoom.css" media="all" />
                
            <div class="container d-flex justify-content-center">
            <section id="default" class="padding-top0">
                <?php

                $product_image = \App\Models\ProductImage::where('product_id',$products->id)->where('is_featured',1)->first();
                $customize_shape = \App\Models\CustomizeShape::where('id',@$customizeImage->customize_shape_id)->first();
                $customize_flavour = \App\Models\CustomizeFlavour::where('id',@$customizeImage->customize_flavour_id)->first();
                ?>
                
                @if(isset($customizeImage->user_id))
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                          <th scope="row">Customize Shape</th>
                          <td><img src="{{ URL::to('/') }}/uploads/customize-shapes/{{ @$customize_shape->image }}" class="w-50"></td>
                        </tr>
                        <tr>
                          <th scope="row">Customize Flavour</th>
                          <td><img src="{{ URL::to('/') }}/uploads/customize-flavours/{{ @$customize_flavour->image }}" class="w-50"></td>
                        </tr>
                        <tr>
                          <th scope="row">Message On Cake</th>
                          <td>{{$customizeImage->message_on_cake}}</td>
                        </tr>
                        @if($customizeImage->photo_cake != NULL)
                        <tr>
                          <th scope="row">Photo Cake</th>
                          <td><img src="{{ URL::to('/') }}/uploads/customized/{{ @$customizeImage->photo_cake }}" class="w-50"></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                @else 
                <div class="row">
                    <div class="large-5 column">
                        <div class="xzoom-container"> 
                            <img class="xzoom" id="xzoom-default" src="{{ URL::to('/') }}/uploads/product/{{ @$product_image->images }}" xoriginal="{{ URL::to('/') }}/uploads/product/{{ @$product_image->images }}" />
                            <div class="xzoom-thumbs">
                            <br><br> 
                                @foreach($product_images as $row)
                                    <a href="{{ URL::to('/') }}/uploads/product/{{ @$row->images }}"><img class="xzoom-gallery" width="80" src="{{ URL::to('/') }}/uploads/product/{{ @$row->images }}" xpreview="{{ URL::to('/') }}/uploads/product/{{ @$row->images }}"></a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </section>
        </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="detcont">
                    <h3>{{$products->name}}</h3>
                    <div style="text-align: center;" id="rateYooProduct">
                        
                    </div>
                    <hr>
                    <div class="product_meta">
                        <?php $categoryname = \App\Models\Category::where('id',$products->category_id)->first(); ?>
                        <span class="enon_wrapper">{{@$categoryname->name}}
                    </div>
                    <hr/>
                    <?php
                    $product_first_price = \App\Models\ProductPrice::where('product_id',$products->id)->where('show_price',1)->first();
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="price">
                                <del><span class="amount">
                                    <span id="var_mrp_price"> &#8377;{{@$product_first_price->mrp_price}}.00</span>
                                </del>
                            
                                <ins><span class="amount">
                                    <span id="var_price">&#8377;{{@$product_first_price->price}}.00</span>
                                </ins>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="cartarea">
                                <!-- <form class="cart" method="post" enctype="multiple/form-data"> -->
                                    <input type="hidden" id="price_id" value="{{@$product_first_price->id}}">
                                    <div class="quantitybox">
                                        <button href="#" href="javascript:void(0);" data-id="{{ $products->id }}" class="button addtocart add-to-cart" >Add To cart</button>
                                    </div>
                                <!-- </form> -->
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="cartarea">
                    <?php $priceCounter = 0; ?>
                    @foreach($product_prices as $price)
                       <?php $priceCounter++; ?>
                        @if(@$price->weight != NULL)
                            <div class="col-md-6">
                                <div class="form-check">
                                  <label class="form-check-label">
                                    <input id="product_price_id{{@$price->id}}" type="radio" value="{{$price->price}}" class="form-check-input" name="product_price_id" @if($priceCounter == 1) checked @endif>
                                    {{@$price->weight}} - <span class="price"><del>&#8377;{{@$price->mrp_price}}.00</del></span> &#8377;{{$price->price}}.00
                                  </label>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    </div>
                    @if($products->is_customize == 1)
                    @guest
                        <button data-toggle="modal" data-target="#loginModal" class="btn addtocart">Customize Image</button>
                    @else

                    @if(isset($customizeImage->user_id))
                        <form method="post" action="{{url('removeCustomImage')}}">
                            @csrf
                            <input type="hidden" value="{{$products->id}}" name="product_id">
                            <button class="button addtocart remove" onclick="return confirm('Are you sure you want to delete this image ?')" >Remove Customize Image</button>
                        </form>
                    @else
                        <a href="{{url('/customize')}}?product_id={{$products->id}}&return_url={{$actual_link}}" class="btn addtocart">Customize Image</a>
                    @endif
                    @endguest
                    @endif
                    <hr>
                    
                    <div class="shares">
                        <ul class="socialul">
                            <li><a href="https://www.facebook.com/sharer/sharer.php?u={{$actual_link}}"><i class="fab fa-facebook-square"></i></a></li>
                            <li><a href="https://twitter.com/home?status={{$actual_link}}"><i class="fab fa-twitter-square"></i></a></li>
                            <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={{$actual_link}}/&title=&summary=&source="><i class="fab fa-linkedin"></i></a></li>
                            <li><a href="https://pinterest.com/pin/create/button/?url={{$actual_link}}/&media=&description="><i class="fab fa-pinterest-square"></i></a></li>
                        </ul>

                    </div>
                    <hr>
                    <div class="description">
                        <h5>Description</h5>
                        <p>{{@$products->description}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="container">
                    @if($rating)
                    <div class="rateyo"
                         data-rateyo-rating="<?php  echo @$rating->rating ?: 3 ?>"
                         data-rateyo-num-stars="5"
                         ></div>
                         <span class='result'>Rating: 0</span>
                         <input type="hidden" id="Ratingresult" name="">
                    </div>
                    
                    <form id="ratingForm">
                         <div class="form-group">
                            <textarea id="review_text" rows="4" cols="7" placeholder="Please Write the review" class="form-control">{{@$rating->review_text}}</textarea>
                        </div>
                        <input type="hidden" id="product_id" value="{{@$products->id}}">
                        <input type="hidden" id="rating" value="{{@$rating->rating}}">
                        <div class="form-group">
                            <button class="btn addtocart ratingFormSubmit" type="submit" name="reting_btn">Submit</button>
                        </div>
                    </form>
                    @endif
                    @if(count($allratings) > 0)
                    <h3>All Reviews</h3>
                    @endif
                    <div class="row">
                    @foreach($allratings as $allrating)
                        <div class="col-md-6">
                            <div class="col-md-6">
                                {{$allrating->username}}
                            </div>
                            <div class="col-md-6">
                                <div id="rateYoo{{$allrating->id}}"></div>
                            </div>
                        </div>
                        <p>{{$allrating->review_text}}</p>
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
    </div>
</div>


 <div class="trending-slider pb-4 pt-4">
    <div class="container">
        <h2 class="h2">Recent Product</h2>
        <span id="status"></span>
        <section class="recent-products slider">
            @foreach($allproducts as $allproduct)
            <?php
            $product_price = \App\Models\ProductPrice::where('product_id',$allproduct->id)->where('show_price',1)->first(); 
            $product_image = \App\Models\ProductImage::where('product_id',$allproduct->id)->where('is_featured',1)->first();
            $customize = \App\Models\ProductImage::where('product_id',$allproduct->id)->where('customize',1)->first();
            $discountPrice = @$product_price->mrp_price - $product_price->price;
                    $discountPercentage = ($product_price->price / @$product_price->mrp_price)*100;
            ?>
            <div class="slide">
                <div class="product-box text-center">
                    <div class="p-img">
                        <a style="color: black;" href="{{url('/')}}/products/{{$allproduct->slug}}"><img src="{{ URL::to('/') }}/uploads/product/{{ @$product_image->images }}" alt="" class="w-100"></a>
                    </div>
                    <div class="product-text">
                       <p class="product-name"><a href="{{url('/')}}/products/{{$allproduct->slug}}">{{$allproduct->name}}</a></p>
                        <input type="hidden" id="price_id{{$allproduct->id}}" value="{{$product_price->id}}">
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

                        <div style="text-align: center;" id="rateYooProduct{{$allproduct->id}}"></div>
                        
                        <div class="row">
                            @if($allproduct->is_customize == 1)
                            <div class="col-md-6">
                                @guest
                                    <button data-toggle="modal" data-target="#loginModal" class="btn cart-btn a_btn">Customize</button>
                                @else
                                <a href="{{url('/customize')}}?product_id={{$allproduct->id}}&return_url={{$actual_link}}" class="btn cart-btn a_btn">Customize</a>
                                @endguest
                            </div>
                            <div class="col-md-6">
                                <a href="#" href="javascript:void(0);" data-id="{{ $allproduct->id }}" class="btn cart-btn a_btn add-to-cart-all{{$allproduct->id}}">Add To cart</a>
                            </div>
                            @else
                            <div class="col-md-12">
                                <a href="#" href="javascript:void(0);" data-id="{{ $allproduct->id }}" class="btn cart-btn a_btn add-to-cart-all{{$allproduct->id}}">Add To cart</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </section>
    </div>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
</div>
<script>
     <?php foreach ($product_prices as $price) { ?>
        <?php if(isset($price->weight)) { ?>
            $('#product_price_id<?php echo $price->id; ?>').on('click', function (ev) {
                var product_price_id = $('#product_price_id<?php echo $price->id; ?>').val();
                var price_id = <?php echo $price->id; ?>;
                $('#var_price').html('&#8377;'+product_price_id+'.00');
                $('#var_mrp_price').html('&#8377;'+product_price_id+'.00');
                $('#price').val(product_price_id);
                $('#price_id').val(price_id);
            });
            
        <?php } ?>
    <?php } ?>
</script>
<script>
(function ($) {
    $(document).ready(function() {
    <?php
        $allProductsratings = \App\Models\ProductRating::where('product_id',$products->id)->get();
        $totalProductRating = array();
        foreach ($allProductsratings as $allProductsrating) {
            $totalProductRating[] = $allProductsrating->rating;
        }
        $totalProductRatingcount = count($allProductsratings);
        if ($totalProductRatingcount != 0) {
            $prductsingleRating = array_sum($totalProductRating) / $totalProductRatingcount;
        }
        else{
            $prductsingleRating = 0;
        }
    ?>
    $("#rateYooProduct").rateYo({
        rating: <?php echo $prductsingleRating; ?>,
        readOnly: true,
        starWidth: "20px"
    }); 
    $(function () {
        <?php if (isset($rating->id)) { ?>
            $('.result').text('Rating: '+ <?php echo @$rating->rating ?>);
            $('#Ratingresult').val(<?php echo @$rating->rating ?>);
        <?php } ?>
      $(".rateyo").rateYo().on("rateyo.change", function (e, data) {
        var rating = data.rating;
        $(this).parent().find('.result').text('Rating: '+ rating);
        $(this).parent().find('#Ratingresult').val(rating);
       });
    });
    <?php foreach ($allratings as $allrating) { ?>
        $("#rateYoo<?php echo $allrating->id; ?>").rateYo({
            rating: <?php echo $allrating->rating; ?>,
            readOnly: true,
            starWidth: "20px"
        });
    <?php } ?>

    $("#ratingForm").validate({
        rules: {
            review_text: {
                required: true,
            },
        },
        messages: {
            review_text: {
                required: "Please enter Review",
            },
        },
        submitHandler: function (form) {
          $('#ratingFormMessage').html('');
          //$('.ratingFormSubmit').html('Sending...');
          //$('.ratingFormSubmit').attr('disabled', true);
          var review_text = $('#review_text').val();
          var rating = $('#Ratingresult').val();
          var product_id = $('#product_id').val();
          var _token = "{{@csrf_token()}}";
            $.ajax({
              url: "{{url('rateProduct')}}",
              method: "POST",
              data: {"review_text":review_text,"_token":_token,"rating":rating,"product_id":product_id},
              dataType: "json",
              success: function (res) {
                //$('.ratingFormSubmit').html('Submit');
                //$('.ratingFormSubmit').attr('disabled', false);
                if (res.status == 1) {
                    location.reload();
                  //$('.ratingFormSubmit').html('Submitted');
                  //$('.ratingFormSubmit').attr('disabled', true);
                }
                else if (res.status == 0){
                  $('#newslettersubmit_message').html(res.message);
                }
              
              }
            });
          return false; 
        }
    });
    //Cart

    $(".add-to-cart").click(function (e) {
            e.preventDefault();

            var ele = $(this);
            var price_id = $('#price_id').val();                

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
    <?php foreach ($allproducts as $allproduct) { ?>
    <?php
        $allratings = \App\Models\ProductRating::where('product_id',$allproduct->id)->get();
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
        $("#rateYooProduct<?php echo $allproduct->id; ?>").css('margin','0 auto');
        $("#rateYooProduct<?php echo $allproduct->id; ?>").rateYo({
            rating: <?php echo $prductRating; ?>,
            readOnly: true,
            starWidth: "20px"
        });        
        $(".add-to-cart-all<?php echo $allproduct->id; ?>").click(function (e) {
                e.preventDefault();

                var ele = $(this);
                var price_id = $('#price_id<?php echo $allproduct->id; ?>').val();
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

    $('.xzoom, .xzoom-gallery').xzoom({zoomWidth: 400, title: true, tint: '#333', Xoffset: 15});
    $('.xzoom2, .xzoom-gallery2').xzoom({position: '#xzoom2-id', tint: '#ffa200'});
    $('.xzoom3, .xzoom-gallery3').xzoom({position: 'lens', lensShape: 'circle', sourceClass: 'xzoom-hidden'});
    $('.xzoom4, .xzoom-gallery4').xzoom({tint: '#006699', Xoffset: 15});
    $('.xzoom5, .xzoom-gallery5').xzoom({tint: '#006699', Xoffset: 15});

    //Integration with hammer.js
    var isTouchSupported = 'ontouchstart' in window;

    if (isTouchSupported) {
    //If touch device
    $('.xzoom, .xzoom2, .xzoom3, .xzoom4, .xzoom5').each(function(){
    var xzoom = $(this).data('xzoom');
    xzoom.eventunbind();
    });

    $('.xzoom, .xzoom2, .xzoom3').each(function() {
    var xzoom = $(this).data('xzoom');
    $(this).hammer().on("tap", function(event) {
    event.pageX = event.gesture.center.pageX;
    event.pageY = event.gesture.center.pageY;
    var s = 1, ls;

    xzoom.eventmove = function(element) {
    element.hammer().on('drag', function(event) {
    event.pageX = event.gesture.center.pageX;
    event.pageY = event.gesture.center.pageY;
    xzoom.movezoom(event);
    event.gesture.preventDefault();
    });
    }

    xzoom.eventleave = function(element) {
    element.hammer().on('tap', function(event) {
    xzoom.closezoom();
    });
    }
    xzoom.openzoom(event);
    });
    });

    $('.xzoom4').each(function() {
    var xzoom = $(this).data('xzoom');
    $(this).hammer().on("tap", function(event) {
    event.pageX = event.gesture.center.pageX;
    event.pageY = event.gesture.center.pageY;
    var s = 1, ls;

    xzoom.eventmove = function(element) {
    element.hammer().on('drag', function(event) {
    event.pageX = event.gesture.center.pageX;
    event.pageY = event.gesture.center.pageY;
    xzoom.movezoom(event);
    event.gesture.preventDefault();
    });
    }

    var counter = 0;
    xzoom.eventclick = function(element) {
    element.hammer().on('tap', function() {
    counter++;
    if (counter == 1) setTimeout(openfancy,300);
    event.gesture.preventDefault();
    });
    }

    function openfancy() {
    if (counter == 2) {
    xzoom.closezoom();
    $.fancybox.open(xzoom.gallery().cgallery);
    } else {
    xzoom.closezoom();
    }
    counter = 0;
    }
    xzoom.openzoom(event);
    });
    });

    $('.xzoom5').each(function() {
    var xzoom = $(this).data('xzoom');
    $(this).hammer().on("tap", function(event) {
    event.pageX = event.gesture.center.pageX;
    event.pageY = event.gesture.center.pageY;
    var s = 1, ls;

    xzoom.eventmove = function(element) {
    element.hammer().on('drag', function(event) {
    event.pageX = event.gesture.center.pageX;
    event.pageY = event.gesture.center.pageY;
    xzoom.movezoom(event);
    event.gesture.preventDefault();
    });
    }

    var counter = 0;
    xzoom.eventclick = function(element) {
    element.hammer().on('tap', function() {
    counter++;
    if (counter == 1) setTimeout(openmagnific,300);
    event.gesture.preventDefault();
    });
    }

    function openmagnific() {
    if (counter == 2) {
    xzoom.closezoom();
    var gallery = xzoom.gallery().cgallery;
    var i, images = new Array();
    for (i in gallery) {
    images[i] = {src: gallery[i]};
    }
    $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
    } else {
    xzoom.closezoom();
    }
    counter = 0;
    }
    xzoom.openzoom(event);
    });
    });

    } else {
    //If not touch device

    //Integration with fancybox plugin
    $('#xzoom-fancy').bind('click', function(event) {
    var xzoom = $(this).data('xzoom');
    xzoom.closezoom();
    $.fancybox.open(xzoom.gallery().cgallery, {padding: 0, helpers: {overlay: {locked: false}}});
    event.preventDefault();
    });

    //Integration with magnific popup plugin
    $('#xzoom-magnific').bind('click', function(event) {
    var xzoom = $(this).data('xzoom');
    xzoom.closezoom();
    var gallery = xzoom.gallery().cgallery;
    var i, images = new Array();
    for (i in gallery) {
    images[i] = {src: gallery[i]};
    }
    $.magnificPopup.open({items: images, type:'image', gallery: {enabled: true}});
    event.preventDefault();
    });
    }
    });
})(jQuery);
</script>
@endsection