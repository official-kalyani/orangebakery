@extends('shop.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Master Products</h3>
              </div>
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              @if(Session::has('flash_danger'))
                  <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_danger') }}
                  </div>
              @endif
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Name</th>
                      <th>Category</th>
                      <!-- <th>Sub Category</th> -->
                      <th>Occasion</th>
                      <th>Price</th>
                      <th>Image</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $row)
                    <tr>
                      <td>{{@$row->name}}</td>
                      <?php $category = \App\Models\Category::where('id',$row->category_id)->first(); ?>
                      <td>{{@$category->name}}</td>
                      <td>
                      <?php @$occasion_id = explode(',', $row->occasion_id); ?>
                        @foreach($occasions as $occasion)
                            @if(@in_array(@$occasion->id,@$occasion_id))
                              {{$occasion->name}}, 
                            @endif
                        @endforeach
                      </td>
                      <td><?php $product_prices = \App\Models\ProductPrice::where('product_id',$row->id)->where('show_price',1)->get(); ?>
                        @foreach($product_prices as $product_price)
                          @if($product_price->weight == NULL)
                            &#8377; <strong>{{$product_price->price}}</strong>
                          @else
                            <strong>{{$product_price->weight}}</strong> K.G - &#8377; <strong>{{$product_price->price}}</strong>,
                          @endif
                        @endforeach
                      </td>
                      <?php
                      $product_image = \App\Models\ProductImage::where('product_id',$row->id)->where('is_featured',1)->first();
                      ?>
                      <td><img src="{{ URL::to('/') }}/uploads/product/{{ @$product_image->images }}" class="img-fluid" alt=""/ width="20%">
                      <td><a href="{{url('/shop/clone-master-products')}}?id={{$row->id}}">Clone</a></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <br>
                
              </div>
              <!-- /.card-body -->
              
        </div>
        <!-- /.card -->
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
