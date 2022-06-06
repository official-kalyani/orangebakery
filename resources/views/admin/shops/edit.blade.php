@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Store</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <form method="post" action="{{route('admin.shops.update',$data->id)}}">
                @csrf
                @method('PATCH')
                <div class="card-body">
                  <div class="form-group {{ $errors->has('shop_name') ? ' has-error' : '' }}">
                    <label for="shop_name">Store Name <span class="required">*</span></label>
                    <input name="shop_name" type="text" class="form-control" id="shop_name" placeholder="Enter Store Name" value="{{$data->shop_name}}">
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
                    <textarea class="form-control" name="short_address" id="short_address">{{$data->short_address}}</textarea>
                    @if ($errors->has('short_address'))
                        <span class="required">
                            <strong>{{ $errors->first('short_address') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group {{ $errors->has('long_address') ? ' has-error' : '' }}">
                    <label for="long_address">Long Address <span class="required">*</span></label>
                    <textarea class="form-control" name="long_address" id="long_address" rows="6">{{$data->long_address}}</textarea>
                    @if ($errors->has('long_address'))
                        <span class="required">
                            <strong>{{ $errors->first('long_address') }}</strong>
                        </span>
                    @endif  
                  </div>
                   <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Username" value="{{$data->name}}">
                     @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label for="phone">Phone <span class="required">*</span></label>
                    <input name="phone" type="number" maxlength="10" class="form-control" id="phone" placeholder="Enter Phone" value="{{$data->phone}}">
                     @if ($errors->has('phone'))
                        <span class="required">
                            <strong>{{ $errors->first('phone') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email">Email <span class="required">*</span></label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="Enter Email" value="{{$data->email}}">
                     @if ($errors->has('email'))
                        <span class="required">
                            <strong>{{ $errors->first('email') }}</strong>
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
