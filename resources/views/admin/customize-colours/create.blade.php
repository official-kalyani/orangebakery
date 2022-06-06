@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Create Colour</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <form method="post" action="{{route('admin.customize-colours.store')}}">
                @csrf
                <div class="card-body">
                  <div class="form-group {{ $errors->has('shop_name') ? ' has-error' : '' }}
                    ">
                   <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Colour <span class="required">*</span></label>
                    <!-- <input name="name" type="colour" class="form-control" id="name" placeholder="Enter Colour"> -->
                    <input type="color" id="color" name="color" value="">
                     @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('name') }}</strong>
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
