@extends('admin.layouts.app')
@section('content')
<style>
.card-body {
    padding:1.25rem;
}
</style>

    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
            @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">�</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
            <div class="card-header">
                <h3 class="card-title">Order Details</h3>
              </div>
              <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
              <div class="col-lg-12 col-12">
                  <div class="card">
                    
                      <div class="row">
                          <div class="col-md-6">
                            <table class="table table-bordered">
                              <thead> 
                                <tr>
                                  <th colspan="2">Order Details</th>
                                </tr> 
                                <tr>
                                  <td>Order Id : </td>
                                  <td><?php echo $order->order_id; ?></td>
                                </tr>
                                <tr>
                                  <td>Order Price : </td>
                                  <td>&#8377;<?php echo $order->order_price; ?></td>
                                </tr>
                                <tr>
                                  <td>Payable Amount : </td>
                                  <td>&#8377;<?php echo $order->payable_price; ?></td>
                                </tr>
                                <tr>
                                  <td>Coupon : </td>
                                  <td><?php echo $order->coupon_code; ?></td>
                                </tr>
                                <tr>
                                  <td>Coupon Discount : </td>
                                  <td>&#8377;<?php echo $order->coupon_amount; ?></td>
                                </tr>
                                <tr>
                                  <td>Coins : </td>
                                  <td>&#8377;<?php echo $order->cbcoin_amount; ?></td>
                                </tr>
                                <tr>
                                 <tr>
                                  <td>Payment Type : </td>
                                  <td><?php echo $order->payment_type=='cod'?'COD':'Online'; ?></td>
                                </tr>
                                <tr>
                                  <td>Payment Status : </td>
                                  <td><?php echo $order->payment_status; ?></td>
                                </tr>
                                <tr>
                                  <td>Order Type : </td>
                                  <td><?php echo $order->type=='pickup'?'Takeaway':'Delivery'; ?></td>
                                </tr>
                                <tr>
                                  <td>Order Status : </td>
                                  <td><?php echo $order->status; ?></td>
                                </tr>
                                <tr>
                                  <td>Store Name : </td>
                                  <td><?php echo $order->store_id; ?></td>
                                </tr>
                                <tr>
                                  <td>Takeaway to delivery : </td>
                                  <td><?php echo $order->takeaway_to_delivery; ?></td>
                                </tr>
                                  <td>Ordered On : </td>
                                  <td><?php echo $order->created_at; ?></td>
                                </tr>
                              </thead> 
                             </tbody>
                            </table>
                          </div>
                         <div class="col-md-6">
                          <table class="table table-bordered">
                              <thead> 
                                <tr>
                                  <th colspan="2">User Details</th>
                                </tr>
                                <tr>
                                  <td>Name : </td>
                                  <td><?php echo $user->name; ?></td>
                                </tr>
                                <tr>
                                  <td>Phone : </td>
                                  <td><?php echo $user->phone; ?></td>
                                </tr>
                                <tr>
                                  <td>Email : </td>
                                  <td><?php echo $user->email; ?></td>
                                </tr>
                                <tr>
                                  <td>Verified User : </td>
                                  <td><?php echo $user->has_verified=="1"?"Yes":"No"; ?></td>
                                </tr>
                              </thead> 
                             </tbody>
                            </table>
                           <table class="table table-bordered">
                              <thead> 
                                <tr>
                                  <th colspan="2">Delivery Details</th>
                                </tr>
                                <tr>
                                  <td>Name : </td>
                                  <td><?php echo $address->name; ?></td>
                                </tr>
                                <tr>
                                  <td>Phone : </td>
                                  <td><?php echo $address->phone; ?></td>
                                </tr>
                                <tr>
                                  <td>Email : </td>
                                  <td><?php echo $address->email; ?></td>
                                </tr>
                                <tr>
                                  <td>Address 1 : </td>
                                  <td><?php echo $address->location; ?></td>
                                </tr>
                                <tr>
                                  <td>Address 2: </td>
                                  <td><?php echo $address->additional_address; ?></td>
                                </tr>
                                <tr>
                                  <td>Floor : </td>
                                  <td><?php echo $address->floor; ?></td>
                                </tr>
                                <tr>
                                  <td>How to reach : </td>
                                  <td><?php echo $address->how_to_reach; ?></td>
                                </tr>
                                <tr>
                                  <td>Address Type : </td>
                                  <td><?php echo $address->address_type; ?></td>
                                </tr>
                              </thead> 
                             </tbody>
                            </table>
                         </div>
                          <div class="col-md-12">
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
                        $product_id = $row->item_id;
                        if($order->type = "pickup" && $order->store_id != '') {
                          $product_id = $row->parent_product_id;  
                        }
                        $product_image = \App\Models\ProductImage::where('product_id',$product_id)->where('is_featured',1)->first();
                        $customizeImage = \App\Models\CustomizeImage::where('id',$row->customize_image)->first(); 
                        ?>
                        <tr> 
                          <td>
                              {{ @$row->itemname }}
                          </td>
                          <td>
                           <img src="{{ URL::to('/') }}/uploads/product/{{ @$product_image->images }}" style="width: 100px;">
                          </td>
                          <td>&#8377;{{ @$row->item_mrp_price }}</td>
                          <td>&#8377;{{ @$row->item_selling_price }}</td>
                          <td>{{ @$row->item_quantity }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                          </div>
                      </div>
                      
<!--                  <div class="card-body">
                    
                    <hr><hr>
                    <h3>Order Action</h3>
                    @if($order->order_made_action_on != NULL)

                    Order @if($order->action_type == "accept") Accepted @else Rejected @endif on - {{ $order->order_made_action_on }}
                    
                    @else
                        <form method="post" action="{{ url('godown/orderAction') }}">
                          @csrf
                          <div class="form-group">
                              <label class="radio-inline"><input type="radio" value="order_confirmed" name="order" checked>Accept Order</label>
                              <label class="radio-inline"><input type="radio" value="order_rejected" name="order">Reject Order</label>
                              <input type="hidden" name="order_id" value="{{$order->order_id}}">
                            </div>
                            <div class="form-group">
                              <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    @endif
                  </div>-->
              </div>
          </div>
        </div>
        
                
              <div class="row">
                <div class="col-lg-6">

<!--                  @if($order->amount_paid == "0")
                  <form action="{{url('/godown/ChangePaidStatus')}}" method="post">
                      @csrf
                      <div class="form-group">
                          <input type="hidden" name="order_id" value="{{$order->order_id}}">
                          <label class="checkbox-inline"><input type="checkbox" value=""  checked > Yes, Paid</label><br>
                          <button class="btn btn-success">Submit</button>
                      </div>
                    </form>
                  @endif-->
                  <form method="get" action="https://www.google.com/maps?saddr=<?php echo @$_GET['saddr']; ?>&daddr=<?php echo @$_GET['daddr']; ?>">
                    <div class="form-group">
                      <label class="form">From:</label>
                      <input type="text" class="form-control" placeholder="Enter Your Location" name="saddr">
                    </div>
                    <div class="form-group">
                      <label class="form">To:</label>
                      <input type="text" value="{{$address->location}}" class="form-control" name="daddr">
                    </div>
                    <button class="btn btn-primary">Get Direction</button>
                  </form>
                </div>
                <div class="col-lg-6">
                  <div id="map"></div>
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
