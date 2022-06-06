@extends('admin.layouts.app')
@section('content')

<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary"> 
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <h3 class="card-title">Create DelieryCharge</h3>
                  </div>
                  <!-- <div class="col-md-6">
                    @if (isset($_REQUEST['id']) && $_REQUEST['id'] !='')
                      <a class="float-right" href="{{url('admin/products/create/step2')}}?id=<?php echo $_REQUEST['id']; ?>">Upload Multiple Images</a>
                    @endif
                  </div> -->
                </div>
              </div>
              <!-- /.card-header --> 
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif

              @if(Session::has('flash_failure'))
                  <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_failure') }}
                  </div>
              @endif
              
              <form method="post" action="{{route('admin.deliverycharge.store')}}">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Delivery Charge <span class="required">*</span></label>
                    <input name="deliverycharge" type="text" class="form-control" id="deliverycharge" placeholder="Enter Deliverycharge" value="">
                    @if ($errors->has('deliverycharge'))
                        <span class="required">
                            <strong>{{ $errors->first('deliverycharge') }}</strong>
                        </span>
                    @endif 
                  </div>
                  
                 <div class="form-group">
                    <label for="name">Order Time <span class="required">*</span></label>
                    <input name="ordertime" type="text" class="form-control" id="ordertime" placeholder="Enter Ordertime" value="">
                    @if ($errors->has('ordertime'))
                        <span class="required">
                            <strong>{{ $errors->first('ordertime') }}</strong>
                        </span>
                    @endif 
                  </div>
                  
                  <div class="form-group">
                    
                      <button type="submit" class="btn btn-primary">Submit</button>
                   
                  </div>
                </div>
              </form>
          </div>
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
