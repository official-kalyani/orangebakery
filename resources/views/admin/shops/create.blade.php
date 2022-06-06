@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Create Store</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <form method="post" action="{{route('admin.shops.store')}}">
                @csrf
                <div class="card-body">
                  <div class="form-group {{ $errors->has('shop_name') ? ' has-error' : '' }}">
                    <label for="shop_name">Store Name <span class="required">*</span></label>
                    <input name="shop_name" type="text" class="form-control" id="shop_name" placeholder="Enter Store Name">
                    @if ($errors->has('shop_name'))
                        <span class="required">
                            <strong>{{ $errors->first('shop_name') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group {{ $errors->has('location') ? ' has-error' : '' }}">
                    <label for="location">Location <span class="required">*</span></label>
                    <select class="form-control" name="location" id="location">
                      @foreach($locations as $location)
                      <option value="{{$location->id}}">{{$location->name}}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('location'))
                        <span class="required">
                            <strong>{{ $errors->first('location') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group {{ $errors->has('short_address') ? ' has-error' : '' }}">
                    <label for="short_address">Short Address <span class="required">*</span></label>
                    <textarea class="form-control" name="short_address" id="short_address"></textarea>
                    @if ($errors->has('short_address'))
                        <span class="required">
                            <strong>{{ $errors->first('short_address') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group {{ $errors->has('long_address') ? ' has-error' : '' }}">
                    <label for="long_address">Long Address <span class="required">*</span></label>
                    <textarea class="form-control" name="long_address" id="long_address" rows="6"></textarea>
                    @if ($errors->has('long_address'))
                        <span class="required">
                            <strong>{{ $errors->first('long_address') }}</strong>
                        </span>
                    @endif  
                  </div>
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
                  <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password">Password <span class="required">*</span></label>
                    <input name="password" type="password" class="form-control" id="password" placeholder="Enter Password">
                     @if ($errors->has('password'))
                        <span class="required">
                            <strong>{{ $errors->first('password') }}</strong>
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
