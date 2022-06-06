@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Obcoins</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
                <div class="card-body">
                  <form role="form" id="myform" method="post" action="{{ route('admin.manage-obcoins.update',$data->id) }}">
                    @csrf
                    @method('PATCH')
                    <div class="form-group {{ $errors->has('precentage') ? ' has-error' : '' }}">
                      <label for="precentage">Percentage <span class="required">*</span></label>
                      <input name="precentage" type="number" min="1" class="form-control" id="precentage" placeholder="Enter Percentage" value="{{$data->precentage}}">
                    </div>
                    <div class="form-group {{ $errors->has('paisa') ? ' has-error' : '' }}">
                      <label for="paisa">Paisa <span class="required">*</span></label>
                      <input name="paisa" type="number" min="1" class="form-control" id="paisa" placeholder="Enter Paisa" value="{{$data->paisa}}">
                    </div>
                    <div class="form-group {{ $errors->has('deliveryCharge') ? ' has-error' : '' }}">
                      <label for="deliveryCharge">Delivery Charge <span class="required">*</span></label>
                      <input name="deliveryCharge" type="number" min="1" class="form-control" id="deliveryCharge" placeholder="Enter Delivery Charge" value="{{$data->deliveryCharge}}">
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary upload-image">Update</button>
                    </div>
                  </form>
                </div>
          </div>
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
