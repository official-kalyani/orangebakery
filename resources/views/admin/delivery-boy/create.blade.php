@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Create Delivery Boy</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <form method="post" action="{{route('admin.delivery-boy.store')}}">
                @csrf
                <div class="card-body">
                  <div class="form-group {{ $errors->has('shop_name') ? ' has-error' : '' }}
                    ">
                   <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name">
                     @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="phone">Phone <span class="required">*</span></label>
                    <input name="phone" type="number" class="form-control" id="phone" placeholder="Enter Phone">
                     @if ($errors->has('phone'))
                        <span class="required">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">Email <span class="required">*</span></label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Enter Email">
                     @if ($errors->has('email'))
                        <span class="required">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create</button>
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
