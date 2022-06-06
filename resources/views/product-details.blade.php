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
                 $type = Request::cookie('type');
                 $store_id = Request::cookie('store_id');
                 if($type == 'pickup' && $store_id !='') {
                    $product_image = \App\Models\ProductImage::where('product_id',$products->parent_product_id)->where('is_featured',1)->first();
                 } else {
                    $product_image = \App\Models\ProductImage::where('product_id',$products->id)->where('is_featured',1)->first();
                 }
                 
                 $customize_shape = \App\Models\CustomizeShape::where('id',@$customizeImage->customize_shape_id)->first();
                 $customize_flavour = \App\Models\CustomizeFlavour::where('id',@$customizeImage->customize_flavour_id)->first();
                ?>
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
                    <p style="font-style:italic; font-weight:bold;"><span style="color:red;">*</span>The image shown above is for reference purpose only. The actual product may vary.</p>
                </div>
                @if(isset($customizeImage->user_id))
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                          <th scope="row">Customize Shape</th>
                          <td>{{ @$customize_shape->name }}</td>
                        </tr>
                        <tr>
                          <th scope="row">Customize Flavour</th>
                          <td>{{ @$customize_flavour->name }}</td>
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
                @endif
            </section>
        </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="detcont">
                    <h3>{{$products->name}}</h3>                                        
                    <hr>
                    <div class="product_meta">
                        <?php $categoryname = \App\Models\Category::where('id',$products->category_id)->first(); ?>
                        <span class="enon_wrapper">{{@$categoryname->name}}
                    </div>
                    <hr/>
                    <?php
                     if($type == 'pickup' && $store_id !='') {
                        $product_first_price = \App\Models\ProductPrice::where('product_id',$products->parent_product_id)->where('show_price',1)->first();
                     } else {
                        $product_first_price = \App\Models\ProductPrice::where('product_id',$products->id)->where('show_price',1)->first();
                     }                   
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
                                        @guest
                                        <button href="#" href="javascript:void(0);" data-toggle="modal" data-target="#loginModal" class="button addtocart" >Buy Now</button>
                                        @else
                                        <button href="#" href="javascript:void(0);" data-id="{{ $products->id }}" data-title="{{ $products->name }}" data-priceid="<?php echo @$product_first_price->id; ?>" class="button addtocart obBuyNow" >Buy Now</button>
                                        @endif                                        
                                        <button href="#" href="javascript:void(0);" data-id="{{ $products->id }}" data-title="{{ $products->name }}" data-priceid="<?php echo @$product_first_price->id; ?>" class="button addtocart add-to-cart" >Add To cart</button>
                                    </div>
                                <!-- </form> -->
                            </div>
                            
                        </div>
                        <div class="col-md-12" style="margin-top:5px; font-style:italic;font-weight:bold;">
                          <label>Please order before  @foreach($delivercharge as $setting){{ @$setting['ordertime'] }}
                            @endforeach
                           for today delivery</label>
                        </div>
                        @if(@$products->category_id == 1)
                        <div class="col-md-12 form-group" style="margin-top:5px;">
                          <label>Message on Cake</label>
                          <input type="text" class="form-control" id="message" maxlength="25" placeholder="Enter your message" />                         
                        </div>
                        <div class="col-md-12 form-group" style="margin-top:5px;">
                        <p>It is recommended to set the character limit as follows :</p>
                          <ul>
                            <li>500 Grams - 10 Characters</li>
                            <li>1 Kg - 20 Characters</li>
                            <li>2 Kgs - 25 Characters</li>
                          </ul>
                          <p>Character size may vary depending on the design & size of the cake. Please keep in mind while typing your message to avoid overlapping issue.</p>
                        </div>
                        @endif
                        
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
                                    <input id="product_price_id{{@$price->id}}" type="radio" data-mrpprice="{{@$price->mrp_price}}" value="{{$price->price}}" class="form-check-input productPriceId" name="product_price_id" @if($priceCounter == 1) checked @endif>
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
                        <a href="{{url('/customize')}}?product_id={{$products->id}}" class="btn addtocart">Customize Image</a>
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
                            <div class="col-md-12">
                                {{$allrating->username}}
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


 
<script>
     <?php foreach ($product_prices as $price) { ?>
        <?php if(isset($price->weight)) { ?>
            $('#product_price_id<?php echo $price->id; ?>').on('click', function (ev) {
                var product_price_id = $('#product_price_id<?php echo $price->id; ?>').val();
                var price_id = <?php echo $price->id; ?>;
                $('#var_price').html('&#8377;'+product_price_id+'.00');
                $('#var_mrp_price').html('&#8377;'+$(this).data('mrpprice')+'.00');
                $('#price').val(product_price_id);
                $('#price_id').val(price_id);
            });
            
        <?php } ?>
    <?php } ?>
</script>
<script>


    $(function () {


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
                    data: {"review_text": review_text, "_token": _token, "rating": rating, "product_id": product_id},
                    dataType: "json",
                    success: function (res) {
                        //$('.ratingFormSubmit').html('Submit');
                        //$('.ratingFormSubmit').attr('disabled', false);
                        if (res.status == 1) {
                            location.reload();
                            //$('.ratingFormSubmit').html('Submitted');
                            //$('.ratingFormSubmit').attr('disabled', true);
                        } else if (res.status == 0) {
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
            var id = $(this).data('id');
            var message = $('#message').val();
            var ele = $(this);
            var price_id = $('#price_id').val();
            var title = $(this).data('title');
            $.ajax({
                url: '{{ url('add-to-cart') }}' + '/' + ele.attr("data-id"),
                method: "get",
                data: {_token: '{{ csrf_token() }}', "price_id": price_id, "message": message},
                dataType: "json",
                success: function (response) {

                    ele.siblings('.btn-loading').hide();
                    Swal.fire(
                            'Added!',
                            title + ' Added to Cart',
                            'success'
                            )
                    $("#cartCounter").html(response.cartCounter);
                    $("#refresh-sidebar-cart").html(response.data);
                    $(".sidebar_cartCounter").html(response.cartCounter);
                }
            });
        });


        $('.xzoom, .xzoom-gallery').xzoom({zoomWidth: 400, title: true, tint: '#333', Xoffset: 15});
        $('.xzoom2, .xzoom-gallery2').xzoom({position: '#xzoom2-id', tint: '#ffa200'});
        $('.xzoom3, .xzoom-gallery3').xzoom({position: 'lens', lensShape: 'circle', sourceClass: 'xzoom-hidden'});
        $('.xzoom4, .xzoom-gallery4').xzoom({tint: '#006699', Xoffset: 15});
        $('.xzoom5, .xzoom-gallery5').xzoom({tint: '#006699', Xoffset: 15});

        //Integration with hammer.js
        var isTouchSupported = 'ontouchstart' in window;

        if (isTouchSupported) {
            //If touch device
            $('.xzoom, .xzoom2, .xzoom3, .xzoom4, .xzoom5').each(function () {
                var xzoom = $(this).data('xzoom');
                xzoom.eventunbind();
            });

            $('.xzoom, .xzoom2, .xzoom3').each(function () {
                var xzoom = $(this).data('xzoom');
                $(this).hammer().on("tap", function (event) {
                    event.pageX = event.gesture.center.pageX;
                    event.pageY = event.gesture.center.pageY;
                    var s = 1, ls;

                    xzoom.eventmove = function (element) {
                        element.hammer().on('drag', function (event) {
                            event.pageX = event.gesture.center.pageX;
                            event.pageY = event.gesture.center.pageY;
                            xzoom.movezoom(event);
                            event.gesture.preventDefault();
                        });
                    }

                    xzoom.eventleave = function (element) {
                        element.hammer().on('tap', function (event) {
                            xzoom.closezoom();
                        });
                    }
                    xzoom.openzoom(event);
                });
            });

            $('.xzoom4').each(function () {
                var xzoom = $(this).data('xzoom');
                $(this).hammer().on("tap", function (event) {
                    event.pageX = event.gesture.center.pageX;
                    event.pageY = event.gesture.center.pageY;
                    var s = 1, ls;

                    xzoom.eventmove = function (element) {
                        element.hammer().on('drag', function (event) {
                            event.pageX = event.gesture.center.pageX;
                            event.pageY = event.gesture.center.pageY;
                            xzoom.movezoom(event);
                            event.gesture.preventDefault();
                        });
                    }

                    var counter = 0;
                    xzoom.eventclick = function (element) {
                        element.hammer().on('tap', function () {
                            counter++;
                            if (counter == 1)
                                setTimeout(openfancy, 300);
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

            $('.xzoom5').each(function () {
                var xzoom = $(this).data('xzoom');
                $(this).hammer().on("tap", function (event) {
                    event.pageX = event.gesture.center.pageX;
                    event.pageY = event.gesture.center.pageY;
                    var s = 1, ls;

                    xzoom.eventmove = function (element) {
                        element.hammer().on('drag', function (event) {
                            event.pageX = event.gesture.center.pageX;
                            event.pageY = event.gesture.center.pageY;
                            xzoom.movezoom(event);
                            event.gesture.preventDefault();
                        });
                    }

                    var counter = 0;
                    xzoom.eventclick = function (element) {
                        element.hammer().on('tap', function () {
                            counter++;
                            if (counter == 1)
                                setTimeout(openmagnific, 300);
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
                            $.magnificPopup.open({items: images, type: 'image', gallery: {enabled: true}});
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
            $('#xzoom-fancy').bind('click', function (event) {
                var xzoom = $(this).data('xzoom');
                xzoom.closezoom();
                $.fancybox.open(xzoom.gallery().cgallery, {padding: 0, helpers: {overlay: {locked: false}}});
                event.preventDefault();
            });

            //Integration with magnific popup plugin
            $('#xzoom-magnific').bind('click', function (event) {
                var xzoom = $(this).data('xzoom');
                xzoom.closezoom();
                var gallery = xzoom.gallery().cgallery;
                var i, images = new Array();
                for (i in gallery) {
                    images[i] = {src: gallery[i]};
                }
                $.magnificPopup.open({items: images, type: 'image', gallery: {enabled: true}});
                event.preventDefault();
            });
        }
    });

</script>
@endsection