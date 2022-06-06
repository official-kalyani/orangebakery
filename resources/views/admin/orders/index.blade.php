@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="{{asset('/admin/css/jquery-ui.css')}}">
<script src="{{asset('/admin/js/jquery-1.12.4.js')}}"></script>
<script src="{{asset('/admin/js/jquery-ui.js')}}"></script>
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
                <form method="get">
                  <div class="container">
                    <div class="row">
                    <div class="form-group col-md-3">
                        <label>Report Type</label>
                        <select class="form-control" name="report_type">
                            <option @if(@$_REQUEST['report_type'] == 'none') selected @endif value="none">None</option>
                            <option @if(@$_REQUEST['report_type'] == 'pdf') selected @endif value="pdf">PDF</option>
                            <option @if(@$_REQUEST['report_type'] == 'csv') selected @endif value="csv">CSV</option>
                            <option @if(@$_REQUEST['report_type'] == 'excel') selected @endif value="excel">XLSX</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="payment_type">Payment Type:</label>
                      <select id="payment_type" name="payment_type" class="form-control">
                        <option value="all" @if(@$_REQUEST['payment_type'] == 'all') selected @endif>All</option>
                        <option value="cod" @if(@$_REQUEST['payment_type'] == 'cod') selected @endif>Cash On Delivery</option>
                        <option value="online" @if(@$_REQUEST['payment_type'] == 'online') selected @endif>Online</option>
                      </select> 
                    </div>
                    <div class="form-group col-md-3">
                      <label for="type">Order Type:</label>
                      <select id="type" name="type" class="form-control">
                        <option value="all" @if(@$_REQUEST['type'] == 'all') selected @endif>All</option>
                        <option value="delivery" @if(@$_REQUEST['type'] == 'delivery') selected @endif>Delivery</option>
                        <option value="takeaway" @if(@$_REQUEST['type'] == 'takeaway') selected @endif>Takeaway</option>
                      </select> 
                    </div>
                    <div class="form-group col-md-3">
                      <label for="store_id">Location:</label>
                      <select id="store_id" name="store_id" class="form-control">
                        <option value="all" @if(@$_REQUEST['store_id'] == 'all') selected @endif>All</option>
                        @foreach($locations as $location)
                          <option value="{{$location->id}}" @if(@$_REQUEST['store_id'] == $location->id) selected @endif>{{@$location->shop_name}}</option>
                        @endforeach
                      </select> 
                    </div>
                    <div class="form-group col-md-3">
                      <label for="status">Order Status:</label>
                      <select name="status" id="status" class="form-control">
                        <option value="all" @if(@$_REQUEST['status'] == 'all') selected @endif>All</option>
                        <option value="order_received" @if(@$_REQUEST['status'] == 'order_received') selected @endif>Order Received</option>
                        <option value="order_preparing" @if(@$_REQUEST['status'] == 'order_preparing') selected @endif>Order Preparing</option>
                        <option value="ontheway" @if(@$_REQUEST['status'] == 'ontheway') selected @endif>Order Ontheway</option>
                        <option value="delivered" @if(@$_REQUEST['status'] == 'delivered') selected @endif>Order Delivered</option>
                        <option value="cancelled" @if(@$_REQUEST['status'] == 'cancelled') selected @endif>Order Cancelled</option>
                      </select> 
                    </div>
                    <?php
                      $from_date =  @$_REQUEST['from_date']; 
                      $to_date =  @$_REQUEST['to_date']; 
                      $to_date =  @$_REQUEST['to_date']; 
                    ?>
                    <div class="form-group col-md-3">
                        <label for="txtFromDate">From:</label>
                       <input type="text" id="txtFromDate" class="form-control" name="from_date" placeholder="From Date" autocomplete="off" @if(@$_REQUEST['from_date']) value="{{$from_date}}" @endif>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="txtToDate">To:</label>
                       <input type="text" id="txtToDate" class="form-control" name="to_date" placeholder="To Date" autocomplete="off" @if(@$_REQUEST['to_date']) value="{{$to_date}}" @endif>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="txtToDate" style="color: white;">To:</label>
                      <input type="submit" name="filter" class="form-control btn btn-primary" value="Filter">
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Order id</th>
                      <th>Order Price</th>
                      <th>Payment Type</th>
                      <th>Coupon Code</th>
                      <th>Name</th>
                      <th>Assigne To</th>
                      <th>Order Status</th>
                      <th>Order Type</th>
                      <th>Ordered Date</th>
                      <!-- <th>Delivered Date</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $row)
                    <?php
                      $coupon = \App\Models\Coupon::where('id',$row->coupon_id)->first();
                      $deliveryBoy = \App\Models\DeliveryBoy::where('id',$row->assignto)->first();
                      $username = \App\User::where('id',$row->user_id)->first();
                    ?>
                    <tr>
                      <td>
                        <!-- {{ @$row->order_id}} -->
                         <a href="{{ url('/admin/orderDetails')}}?order_id={{$row->order_id}}">{{ @$row->order_id}}</a><br />
                          <span>{{ @$username->name}}</span><br />
                          <span>{{ @$username->phone}}</span>
                      </td>
                      <td>
                        <span>Order :&#8377;{{ @$row->order_price}}.00</span><br />
                          <span>Payable :&#8377;{{ @$row->payable_price}}.00</span><br />
                          <span>Pay Status : {{ @$row->payment_status}}</span>

                      </td>
                      <td><?php if ($row->payment_type == "cod") {
                          echo "Cash On Delivery";
                      }
                      else{
                          echo "Online";
                      }?></td>
                      
                      <td>
                        {{ @$coupon->coupon_code}}
                      </td>
                      <td>{{ @$username->name}}</td>
                      <td>{{ @$deliveryBoy->name }}<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-assign{{ @$row->order_id}}">
                        Assign
                      </button></td>
                      <td>@if($row->status == "order_preparing") Order Preparing @endif @if($row->status == "ontheway") Order Ontheway @endif @if($row->status == "delivered") Order Delivered @endif @if($row->status == "cancelled") Order Cancelled @endif @if($row->status == "order_received") Order Received @endif<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-status{{ @$row->id}}">
                        Change Status
                      </button></td>
                      <td>
                        <!-- @if($row->type == "delivery")
                          Delivery
                        @else
                          <?php
                              $shop = \App\Shop::where('id',$row->store_id)->first(); 
                          ?>
                          Takeaway
                          {{@$shop->shop_name}}
                        @endif -->
                        <span><?php echo $row->type=='pickup'?"Takeaway":'Delivery'; ?></span> <br />
                          <span>Pay Type : <?php echo $row->payment_type == "cod"?"COD":"Online"; ?></span>
                      </td>
                      <td>{{ \Carbon\Carbon::parse($row->created_at)->format('jS M Y') }}</td>

                      <!-- <td>{{ @$row->delivered_date}}</td> -->
                    </tr>
                    <div class="modal fade" id="modal-assign{{ @$row->order_id}}">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Delivery Boy</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="col-sm-12">
                              <form method="post" action="{{url('/admin/assignDelivery')}}">
                                @csrf
                                <div class="form-group">
                                  <label>Select Delivery Boy</label>
                                  <select class="form-control" name="deliveryboy">
                                    @foreach($deliveryboy as $deliveryboys)
                                    <option @if($deliveryboys->id == $row->assignto) selected @endif value="{{@$deliveryboys->id}}">{{ @$deliveryboys->name }}</option>
                                    @endforeach
                                  </select>
                                  <input type="hidden" name="order_id" value="{{ @$row->order_id}}">
                                </div>
                                <div class="text-center"> 
                                 <button type="submit" class="btn btn-primary">Assign</button>
                                </div>
                              </form>
                            </div>
                          </div>
                          <!-- <div class="modal-footer justify-content-right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div> -->
                        </div>
                        <!-- /.modal-content -->
                      </div>
                    </div>
                    <!-- /.modal-dialog -->
                    <div class="modal fade" id="modal-status{{ @$row->id}}">
                        <div class="modal-dialog modal-sm">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Change Status</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="col-sm-12">
                                <form method="post" action="{{url('/admin/changeOrderStatus')}}">
                                  @csrf
                                  <div class="form-group">
                                    <label>Select Status</label>
                                    <select class="form-control" name="order_status">
                                      <option @if($row->status == "order_status") selected @endif value="order_received">Order Received</option>
                                      <option @if($row->status == "order_preparing") selected @endif value="order_preparing">Order Preparing</option>
                                      <option @if($row->status == "ontheway") selected @endif value="ontheway">Order Ontheway</option>
                                      <option @if($row->status == "delivered") selected @endif value="delivered">Order Delivered</option>
                                      <option @if($row->status == "cancelled") selected @endif value="cancelled">Order Cancelled</option>
                                    </select>
                                    <input type="hidden" name="order_id" value="{{ @$row->order_id}}">
                                  </div>
                                  <div class="text-center"> 
                                   <button type="submit" class="btn btn-primary">Assign</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                    </div>
                  <!-- /.modal -->
                    @endforeach
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              {!! $data->links() !!}
        </div>
        <!-- /.card -->
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
<script>
  $( function() {
    $( "#txtFromDate" ).datepicker();
    $( "#txtToDate" ).datepicker();
  } );
  </script>
@endsection
