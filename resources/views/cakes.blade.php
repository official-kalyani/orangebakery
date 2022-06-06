@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <div class="container">
        <div class="form-group">
            <input type="text" placeholder="Search Products" id="search_product_name" class="form-control">
            <div id="productLists">
            </div>
            {{ csrf_field() }}
        </div>
    </div>

    <div class="container products">

        <span id="status"></span>

        <div class="row">

            @foreach($products as $product)
                <?php
                    $product_image = \App\Models\ProductImage::where('product_id',$product->id)->where('is_featured',1)->first();
                    $product->description = preg_replace("/^<p.*?>/", "",$product->description);
                    $product->description = preg_replace("|</p>$|", "",$product->description);
                    @$product_price = \App\Models\ProductPrice::where('product_id',$product->id)->where('show_price',1)->first();
                ?>
                <div class="col-xs-18 col-sm-6 col-md-3">
                    <div class="thumbnail">
                        <img src="{{ URL::to('/') }}/uploads/product/{{ @$product_image->images }}" width="500" height="300">
                        <div class="caption">
                            <h4>{{ $product->name }}</h4>
                            <p>{{ str_limit(strtolower($product->description), 50) }}</p>
                            <p data-price="{{ $product_price->price }}"><strong>Price: </strong>&#8377;{{ @$product_price->price }}</p>
                            <p class="btn-holder"><a href="javascript:void(0);" data-id="{{ $product->id }}" class="btn btn-warning btn-block text-center add-to-cart" role="button">Add to cart</a>
                                <i class="fa fa-circle-o-notch fa-spin btn-loading" style="font-size:24px; display: none"></i>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div><!-- End row -->

    </div>

@endsection

@section('scripts')

    <script type="text/javascript">
        $(".add-to-cart").click(function (e) {
            e.preventDefault();

            var ele = $(this);

            //ele.siblings('.btn-loading').show();

            $.ajax({
                url: '{{ url('add-to-cart') }}' + '/' + ele.attr("data-id"),
                method: "get",
                data: {_token: '{{ csrf_token() }}'},
                dataType: "json",
                success: function (response) {

                    ele.siblings('.btn-loading').hide();

                    $("span#status").html('<div class="alert alert-success">'+response.msg+'</div>');
                    $("#header-bar").html(response.data);
                }
            });
        });
        $('#search_product_name').keyup(function(){ 
            var query = $(this).val();
            if(query != '')
            {
             var _token = $('input[name="_token"]').val();
             $.ajax({
              url:"{{ url('/fetchProducts') }}",
              method:"POST",
              data:{query:query, _token:_token},
              success:function(data){
               $('#productLists').fadeIn();  
                        $('#productLists').html(data);
              }
             });
            }
        });

        $(document).on('click', 'li', function(){  
            $('#search_product_name').val($(this).text());  
            $('#productLists').fadeOut();  
        });  
    </script>

@stop