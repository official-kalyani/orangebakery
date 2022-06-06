@extends('godown.layout.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">Accept / Reject Order</h3>
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
                <form method="post" action="{{ url('godown/orderAction') }}">
                  @csrf
                  <div class="form-group">
                      <label class="radio-inline"><input type="radio" name="order" checked>Accept Order</label>
                      <label class="radio-inline"><input type="radio" name="order">Reject Order</label>
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
