@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Send Email</h3>
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
                      <form method="post" action="{{url('/admin/sendEmails')}}">
                          @csrf
                          <!-- <div class="col-md-12">
                              <div class="form-group">
                                  <label>Select User Type</label>
                                  <select class="form-control" name="user_type">
                                      <option value="all">All</option>
                                  </select>
                              </div>
                          </div> -->
                          <div class="col-md-12">
                            <div class="form-group">
                                <label for="subject">Subject <span class="required">*</span></label>
                                <input id="subject" type="text" class="form-control" name="subject">
                                @if ($errors->has('subject'))
                                    <span class="required">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif 
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                                <label for="desc">Description <span class="required">*</span></label>
                                <textarea id="desc" name="desc" class="form-control" rows="5"></textarea>
                                @if ($errors->has('desc'))
                                    <span class="required">
                                        <strong>{{ $errors->first('desc') }}</strong>
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
