@extends('godown.layout.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
            @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
            <div class="card-header">
                <h3 class="card-title">Order Details</h3>
              </div>
              <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-lg-6">
                  <h3><u>Order Details</u></h3>
                  <p><strong>Order Id</strong> : {{$orders->order_id}}</p>
                  <p><strong>Order Status</strong> : @if($orders->status == "order_preparing") Order Preparing @endif @if($orders->status == "ontheway") Order Ontheway @endif @if($orders->status == "delivered") Order Delivered @endif @if($orders->status == "cancelled") Order Cancelled @endif @if($orders->status == "order_received") Order Received @endif</p>
                  <p><strong>Payment Type</strong> : <?php if ($orders->payment_type == "cod") {
                          echo "Cash On Delivery";
                      }
                      else{
                          echo "Online";
                      }?></p>
                  <p><strong>Amount</strong> : &#8377;{{$orders->order_price}}</p>
                  <p><strong>Has Paid</strong> : @if($orders->amount_paid == "0") Pending @else Paid @endif</p>
                  @if($orders->amount_paid == "0")
                  <form action="{{url('/godown/ChangePaidStatus')}}" method="post">
                      @csrf
                      <div class="form-group">
                          <input type="hidden" name="order_id" value="{{$orders->order_id}}">
                          <label class="checkbox-inline"><input type="checkbox" value=""  checked > Yes, Paid</label><br>
                          <button class="btn btn-success">Submit</button>
                      </div>
                    </form>
                  @endif
                  <form method="get" action="https://www.google.com/maps?saddr=<?php echo @$_GET['saddr']; ?>&daddr=<?php echo @$_GET['daddr']; ?>">
                    <div class="form-group">
                      <label class="form">From:</label>
                      <input type="text" class="form-control" value="" placeholder="Enter Your Location" name="saddr">
                    </div>
                    <div class="form-group">
                      <label class="form">To:</label>
                      <input type="text" value="{{$orders->location}}" class="form-control" name="daddr">
                    </div>
                    <button class="btn btn-primary">Get Direction</button>
                  </form>
                </div>
                <?php 
                  $address = \App\Models\Address::where('id',$orders->user_address_id)->first();
                ?>
                <div class="col-lg-6">
                  <h3><u>User Details</u></h3>
                  <p><strong>Name</strong> : {{$address->name}}</p>
                  <p><strong>Phone</strong> : <a href="tel:+91{{$orders->phone}}">{{$address->phone}}</a></p>
                  <p><strong>Email</strong> : {{$address->email}}</p>
                  <p><strong>Location</strong> - {{$address->location}}</p>
                  <p><strong>Additional Address</strong> : {{$address->additional_address}}</p>
                  <p><strong>Address Type</strong> : {{$address->address_type}}</p>
                  <div id="map"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-12">
                  <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">Orders List</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>                  
                        <tr>
                          <th>Item Name</th>
                          <th>Image</th>
                          <th>Mrp Price</th>
                          <th>Selling Price</th>
                          <th>Quantity</th>
                        </tr>
                      </thead> 
                      <tbody>
                        @foreach($orderitems as $row)
                        <?php
                        $product_image = \App\Models\ProductImage::where('product_id',$row->item_id)->where('is_featured',1)->first();
                        $customizeImage = \App\Models\CustomizeImage::where('id',$row->customize_image)->first(); 
                        $customize_shape = \App\Models\CustomizeShape::where('id',@$customizeImage->customize_shape_id)->first();
                        ?>
                        <tr> 
                          <td>{{ @$row->itemname }}</td>
                          <td>
                            @if(isset($customizeImage->user_id))
                              <img class="w-100" src="{{ URL::to('/') }}/uploads/customize-shapes/{{ @$customize_shape->image }}"> 
                            @else 
                              <img class="w-100" src="{{ URL::to('/') }}/uploads/product/{{ @$product_image->images }}">
                            @endif
                          </td>
                          <td>&#8377;{{ @$row->item_mrp_price }}</td>
                          <td>&#8377;{{ @$row->item_selling_price }}</td>
                          <td>{{ @$row->item_quantity }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <hr><hr>
                    <h3>Order Action</h3>
                    @if($orders->order_made_action_on != NULL)

                    Order @if($orders->action_type == "accept") Accepted @else Rejected @endif on - {{ $orders->order_made_action_on }}
                    
                    @else
                        <form method="post" action="{{ url('godown/orderAction') }}">
                          @csrf
                          <div class="form-group">
                              <label class="radio-inline"><input type="radio" value="order_confirmed" name="order" checked>Accept Order</label>
                              <label class="radio-inline"><input type="radio" value="order_rejected" name="order">Reject Order</label>
                              <input type="hidden" name="order_id" value="{{$orders->order_id}}">
                            </div>
                            <div class="form-group">
                              <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    @endif
                  </div>
              </div>
          </div>
        </div>
        </div>
        <!-- /.card -->
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
