@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Send Sms</h3>
              </div>
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                <div class="card">
                      <form method="post" action="{{url('/admin/sendSms')}}">
                          @csrf
                          <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone">Phone <span class="required">*</span></label>
                                <input id="phone" name="phone" placeholder="Enter Phone Number" class="form-control" maxlength="10" minlength="10" type="text" value="{{old('phone')}}">
                                @if ($errors->has('phone'))
                                    <span class="required">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif 
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                                <label for="sms">SMS <span class="required">*</span></label>
                                <textarea id="sms" name="sms" placeholder="Enter Sms" class="form-control" rows="5">{{old('sms')}}</textarea>
                                @if ($errors->has('sms'))
                                    <span class="required">
                                        <strong>{{ $errors->first('sms') }}</strong>
                                    </span>
                                @endif 
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" name="submit" value="Send" class="btn btn-primary">
                            </div>
                          </div>
                      </form>
                  </div>
              </div>
                </div>
              </div>
              <!-- /.card-body -->
              
        </div>
        <!-- /.card -->
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
@endsection
