@extends('godown.layout.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Change Password</h3>
              </div>
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              @if(Session::has('flash_danger'))
                  <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_danger') }}
                  </div>
              @endif
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="{{ url('godown/changepassword') }}">
                  @csrf
                  <div class="form-group">
                      <label for="price">Name <span class="required">*</span></label>
                      <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" / value="{{$admin->name}}">
                      @if ($errors->has('name'))
                          <span class="required">
                              <strong>{{ $errors->first('name') }}</strong>
                          </span>
                      @endif  
                    </div>
                    <div class="form-group">
                        <label for="price">Email <span class="required">*</span></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" / value="{{$admin->email}}" readonly="">
                        @if ($errors->has('email'))
                            <span class="required">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif  
                    </div>
                    <div class="form-group">
                        <label for="price">New Password <span class="required">*</span></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter New Password" />
                        @if ($errors->has('password'))
                            <span class="required">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif  
                    </div>
                    <div class="form-group">
                      <label for="price">Confirm Password <span class="required">*</span></label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" />
                        @if ($errors->has('confirm_password'))
                            <span class="required">
                                <strong>{{ $errors->first('confirm_password') }}</strong>
                            </span>
                        @endif  
                    </div>
                    <div class="form-group">
                      <button id="submit" type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
              </div>
              <!-- /.card-body -->
              
        </div>
        <!-- /.card -->
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
