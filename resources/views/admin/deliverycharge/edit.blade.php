@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Delivery Charge</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <form method="post" action="{{route('admin.deliverycharge.update',$data->id)}}">
                @csrf
                @method('PATCH')
                <div class="card-body">
                  <div class="form-group {{ $errors->has('shop_name') ? ' has-error' : '' }}
                    ">
                  <div class="form-group">
                    <label for="name">Delivery Charge <span class="required">*</span></label>
                    <input name="deliverycharge" type="text" class="form-control" id="deliverycharge" placeholder="Enter Deliverycharge" value="{{$data->delivery_charge}}">
                    @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('deliverycharge') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                    <label for="name">Ordertime <span class="required">*</span></label>
                    <input name="ordertime" type="text" class="form-control" id="ordertime" placeholder="Enter Ordertime" value="{{$data->ordertime}}">
                    @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('ordertime') }}</strong>
                        </span>
                    @endif 
                  </div>
                  
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </div>
              </form>
          </div>
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
