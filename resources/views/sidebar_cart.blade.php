<div id="refresh-sidebar-cart">
    <div id="sticky-anchor">
        <h2 style="font-size: 24px; color: #fb6700; text-transform: uppercase;">Cart</h2>
    </div>
    <hr>
    <div class="sidebar">
         
        @if(session('cart'))
        <?php $total = 0; ?>
        @foreach((array) session('cart') as $id => $details)
        <?php
            $type = "delivery";
            $store_id = "";
            if ($type == "delivery" || $type == "" || $store_id == ""){
              $product =  \App\Models\Product::where('id',$id)->first();
              $product_image = \App\Models\ProductImage::where('product_id',$id)->where('is_featured',1)->first();

              $customizeImage = \App\Models\CustomizeImage::where('product_id',$id)->first(); 
              $product_price = \App\Models\ProductPrice::where('id',$details['price_id'])->where('show_price',1)->first();
            }  
            else{
              $product =  \App\Models\Product::where('parent_product_id',$id)->first();
              $product_image = \App\Models\ProductImage::where('product_id',$details['id'])->where('is_featured',1)->first();

              $customizeImage = \App\Models\CustomizeImage::where('user_id',Auth::user()->id)->where('product_id',$details['id'])->first(); 
              $product_price = \App\Models\ProductPrice::where('id',$details['price_id'])->where('show_price',1)->first();
            } 

            $total += $product_price->price * $details['quantity']

        ?>
        <section>
            <div class="row">
                <div class="col-lg-4">
                    <img src="https://images.dominos.co.in/cart/farmhouse.png" style="width: 100%;">
                </div>
                <div class="col-lg-8">
                    <h5>{{ $product->name }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="qty mt-3">
                        <span class="minus<?php echo $details['id']; ?> bg-dark">-</span>
                        <input type="hidden" class="session_id{{$details['id']}}" value="{{ $details['id'] }}"> 
                        <input type="text" class="count{{$details['id']}} update-cart-quantity qty" name="qty" value="{{ $details['quantity'] }}">
                        <input type="hidden" class="price_id{{$details['id']}}" value="{{ $details['price_id'] }}"> 
                        <span class="plus<?php echo $details['id']; ?> bg-dark">+</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="qty mt-3">
                        &#8377; {{ $product_price->price }}
                    </div>
                </div>
            </div>
        </section>
        <script type="text/javascript">
            $('.count<?php echo $details['id']; ?>').prop('disabled', true);
            $(".plus<?php echo $details['id']; ?>").click(function (e) {
                e.preventDefault();
                var quantity = parseInt($('.count<?php echo $details['id']; ?>').val());
                var id = parseInt($('.session_id<?php echo $details['id']; ?>').val());
                var price_id = parseInt($('.price_id<?php echo $details['id']; ?>').val());
                
                if (quantity>=1) {
                    $(this).attr('disabled', true);
                    $('.count<?php echo $details['id']; ?>').val(quantity+1);
                    var quantity = quantity+1;
                    $.ajax({
                        url: '{{ url('update-cart') }}',
                        method: "PATCH",
                        data: {_token: '{{ csrf_token() }}', id: id, quantity: quantity,price_id:price_id},
                        dataType: "json",
                        success: function (response) {
                            
                            $("#refresh-sidebar-cart").html(response.data);
                            $("#cartCounter").html(response.cartCounter);
                            $(this).attr('disabled', false);
                            $(".sidebar_cartCounter").html(response.cartCounter);
                        }
                    });
                }
            });
            $(".minus<?php echo $details['id']; ?>").click(function (e) {
                e.preventDefault();
                var quantity = parseInt($('.count<?php echo $details['id']; ?>').val());
                var id = parseInt($('.session_id<?php echo $details['id']; ?>').val());
                var price_id = parseInt($('.price_id<?php echo $details['id']; ?>').val());

                if (quantity>1) {
                    $(this).attr('disabled', true);
                    $('.count<?php echo $details['id']; ?>').val(quantity-1);
                    var quantity = quantity-1;
                    $.ajax({
                        url: '{{ url('update-cart') }}',
                        method: "PATCH",
                        data: {_token: '{{ csrf_token() }}', id: id, quantity: quantity,price_id:price_id},
                        dataType: "json",
                        success: function (response) {
                            
                            $("#refresh-sidebar-cart").html(response.data);
                            $("#cartCounter").html(response.cartCounter);
                            $(".sidebar_cartCounter").html(response.cartCounter);
                            $(this).attr('disabled', false);
                        }
                    });
                }
                else{
                    $(this).attr('disabled', true);
                    $.ajax({
                        url: '{{ url('remove-from-cart') }}',
                        method: "DELETE",
                        data: {_token: '{{ csrf_token() }}', id: id},
                        dataType: "json",
                        success: function (response) {
                            $("#refresh-sidebar-cart").html(response.data);
                            $("#cartCounter").html(response.cartCounter);
                            $(this).attr('disabled', false);
                            $(".sidebar_cartCounter").html(response.cartCounter);
                        }
                    });
                }
            });
        </script>
        <style>
            .qty .count<?php echo $details['id']; ?> {
                color: #000;
                display: inline-block;
                vertical-align: top;
                font-size: 25px;
                font-weight: 700;
                line-height: 30px;
                padding: 0 2px
                ;min-width: 35px;
                text-align: center;
            }
            .qty .plus<?php echo $details['id']; ?> {
                cursor: pointer;
                display: inline-block;
                vertical-align: top;
                color: white;
                width: 30px;
                height: 30px;
                font: 30px/1 Arial,sans-serif;
                text-align: center;
                border-radius: 50%;
                }
            .qty .minus<?php echo $details['id']; ?> {
                cursor: pointer;
                display: inline-block;
                vertical-align: top;
                color: white;
                width: 30px;
                height: 30px;
                font: 30px/1 Arial,sans-serif;
                text-align: center;
                border-radius: 50%;
                background-clip: padding-box;
            }
            .minus<?php echo $details['id']; ?>:hover{
                background-color: #717fe0 !important;
            }
            .plus<?php echo $details['id']; ?>:hover{
                background-color: #717fe0 !important;
            }
        </style>
        @endforeach
        <div class="row">
            <div class="col-md-6 mt-4">
                <h5>Subtotal</h5>
            </div>
            <div class="col-md-6 mt-3">
                <h5><span id="totalPrice">&#8377; <?php echo @$total; ?></span></h5>
            </div>
        </div>
        <div class="row">
            <a style="color: white;" href="{{url('/cart')}}" class="btn cart-btn float-right">Proceed to Checkout</a>
        </div>
        @else
            <img style="width: 100%;" class="img-responsive" src="{{url('/images/cart-empty.png')}}">
        @endif
        <!--Section: Block Content-->
    </div><!-- sidebar ends -->
</div>