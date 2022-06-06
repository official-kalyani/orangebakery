@extends('admin.layouts.app')
@section('content')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Create Coupons</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <form role="form" id="myform" method="post" action="{{ route('admin.coupons.update',$data->id) }}">
                @csrf
                @method('PATCH')
                <div class="container">
                 <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="price">Coupon Code <span class="required">*</span></label>
                        <input type="text" name="coupon_code" id="coupon_code" class="form-control" placeholder="Enter Coupon Code" value="{{ $data->coupon_code }}" />
                        @if ($errors->has('coupon_code'))
                                <span class="required">
                                    <strong>{{ $errors->first('coupon_code') }}</strong>
                                </span>
                            @endif  
                      </div>
                      <div class="form-group">
                        <label for="price">Coupon Heading <span class="required">*</span></label>
                        <input type="text" name="coupon_heading" id="coupon_heading" class="form-control" value="{{ $data->coupon_heading }}" placeholder="Enter Coupon Heading" />
                        @if ($errors->has('coupon_code'))
                                <span class="required">
                                    <strong>{{ $errors->first('coupon_code') }}</strong>
                                </span>
                            @endif  
                      </div>
                      <div class="form-group">
                        <label for="price">Coupon Description</label>
                        <textarea class="form-control" name="coupon_desc" rows="7" placeholder="Enter Coupon Description">{{ $data->coupon_desc }}</textarea>
                        @if ($errors->has('coupon_code'))
                                <span class="required">
                                    <strong>{{ $errors->first('coupon_code') }}</strong>
                                </span>
                            @endif  
                      </div>
                      <label for="chkYes">
                          <input type="radio" class="discount_type"  name="discount_type" value="percentage" @if($data->discount_type == 'percentage') checked @endif />
                          Percentage
                      </label>
                      <label for="chkNo">
                          <input type="radio" value="flat" class="discount_type" name="discount_type" @if($data->discount_type == 'flat') checked @endif />
                          Flat
                      </label>
                        <div class="form-group">
                         <label for="percentage">Discount Amount <span class="required">*</span></label>
                            <input type="number" name="discount_amount" id="discount_amount" class="form-control" min="1" value="{{ $data->discount_amount }}" placeholder="Enter Discount Amount" />
                            @if ($errors->has('coupon_code'))
                                <span class="required">
                                    <strong>{{ $errors->first('coupon_code') }}</strong>
                                </span>
                            @endif  
                        </div>
                        <div class="form-group">
                         <label for="flat">Apply On Minimum Order Amount <span class="required">*</span></label>
                            <input type="number" id="minimum_order" name="minimum_order" class="form-control" min="1" value="{{ $data->minimum_order }}" style="background: white;" placeholder="Enter Minimum Order Amount"/>
                            @if ($errors->has('coupon_code'))
                                <span class="required">
                                    <strong>{{ $errors->first('coupon_code') }}</strong>
                                </span>
                            @endif  
                        </div>
                        <div class="form-group">
                            <label for="price">Valid Till <span class="required">*</span></label>
                            <input type="date" name="validity_till" id="validity_till" class="form-control" min="0" value="{{ $data->validity_till }}" placeholder="Vaild Till" />
                            @if ($errors->has('coupon_code'))
                                <span class="required">
                                    <strong>{{ $errors->first('coupon_code') }}</strong>
                                </span>
                            @endif  
                          </div>

                         <div class="form-group">
                          <button id="submit" type="submit" class="btn btn-primary">Update Coupon</button>
                        </div>
                    </div>
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
