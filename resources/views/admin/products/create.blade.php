@extends('admin.layouts.app')
@section('content')
@if(@$products->is_customize == 0)
<style type="text/css">
  .has_show_photocake{
    display: none;
  }
</style>
@endif
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
                    <h3 class="card-title">Create Products</h3>
                  </div>
                  <div class="col-md-6">
                    @if (isset($_REQUEST['id']) && $_REQUEST['id'] !='')
                      <a class="float-right" href="{{url('admin/products/create/step2')}}?id=<?php echo $_REQUEST['id']; ?>">Upload Multiple Images</a>
                    @endif
                  </div>
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
              
              <form method="post" action="{{route('admin.products.store')}}">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name" value="{{@$products->name}}">
                    @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                      <label for="description">Description <span class="required">*</span></label>
                      <textarea class="form-control" id="description" name="description">{{@$products->description}}</textarea>
                      <script>
                            CKEDITOR.replace( 'description' );
                      </script>
                      @if ($errors->has('description'))
                        <span class="required">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                      @endif 
                  </div>
                  <div class="form-group">
                    <label>Product Category <span class="required">*</span></label>
                    <select class="form-control" name="category_id" id="category_id">
                      @foreach($categories as $category)
                      <option value="{{$category->id}}" @if($category->id == @$products->category_id) selected @endif>{{$category->name}}</option>
                      @endforeach
                    </select>
                    @if ($errors->has('category_id'))
                        <span class="required">
                            <strong>{{ $errors->first('category_id') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                    <label>Is this cake has option to customize ?</label>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input is_customize" type="radio" id="customRadio1" name="is_customize" value="1" @if(@$products->is_customize == 1) checked @endif>
                          <label for="customRadio1" class="custom-control-label">Yes</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input is_customize" type="radio" id="customRadio2" name="is_customize" value="0" @if(@$products->is_customize == 0) checked @endif>
                          <label for="customRadio2" class="custom-control-label" >No</label>
                        </div>
                      </div>
                  </div>
                  <div class="has_show_photocake">
                    <div class="form-group">
                      <label>Is this cake has option to Photo Cake ?</label>
                      <div class="form-group">
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="customRadio3" name="is_photocake" value="1" @if(@$products->is_photocake == 1) checked @endif>
                            <label for="customRadio3" class="custom-control-label" >Yes</label>
                          </div>
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="customRadio4" name="is_photocake" value="0" @if(@$products->is_photocake == 0) checked @endif>
                            <label for="customRadio4" class="custom-control-label">No</label>
                          </div>
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="customRadio5" name="shape" value="round" @if(@$products->shape == 'round') checked @endif>
                            <label for="customRadio5" class="custom-control-label">Round Cake</label>
                          </div>
                          <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="customRadio6" name="shape" value="square" @if(@$products->shape == 'square') checked @endif>
                            <label for="customRadio6" class="custom-control-label">Square Cake</label>
                          </div>
                          
                        </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="meta_title">Meta Title <span class="required">*</span></label>
                    <input name="meta_title" type="text" class="form-control" id="meta_title" placeholder="Enter Meta Title" value="{{@$products->meta_title}}">
                    @if ($errors->has('meta_title'))
                        <span class="required">
                            <strong>{{ $errors->first('meta_title') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                    <label for="meta_desc">Meta Description <span class="required">*</span></label>
                    <input name="meta_desc" type="text" class="form-control" id="meta_desc" placeholder="Enter Meta Description" value="{{@$products->meta_desc}}">
                    @if ($errors->has('meta_desc'))
                        <span class="required">
                            <strong>{{ $errors->first('meta_desc') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                    @if (isset($_REQUEST['id']) && $_REQUEST['id'] !='')
                      <input type="hidden" name="id" value="{{$_REQUEST['id']}}">
                      <button type="submit" class="btn btn-primary">Next</button>
                    @else
                      <button type="submit" class="btn btn-primary">Next</button>
                    @endif
                  </div>
                </div>
              </form>
          </div>
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
<script type="text/javascript">
$(document).ready(function(){
  $('.is_customize').click(function(){
    if($(this).val() == 1){
      $('.has_show_photocake').show();
    } else {
      $('.has_show_photocake').hide();
    }
  });
  
$('#name').change(function() {
  $('#meta_title').val($(this).val());
  $('#meta_desc').val($(this).val());
}); 


});
</script>
@endsection
