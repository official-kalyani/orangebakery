@extends('shop.layouts.app')
@section('content')
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary"> 
              <div class="card-header">
                <h3 class="card-title">Update Products</h3>
              </div>
              <!-- /.card-header --> 
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              
              <form method="post" action="{{route('shop.products.store')}}">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name" value="{{@$products->name}}" style="background-color: white;" readonly="">
                    @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <?php @$category = \App\Models\Category::where('id',$products->category)->first(); ?>
                  <div class="form-group">
                    <label>Select Category <span class="required">*</span></label>
                    <select class="form-control" name="category" id="category">
                      <option value="{{@$products->category}}">{{@$category->name}}</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="price">Price <span class="required">*</span></label>
                    <input name="price" type="number" min="0" class="form-control" id="price" placeholder="Enter Price" value="{{@$products->price}}" style="background-color: white;" readonly="">
                    @if ($errors->has('price'))
                        <span class="required">
                            <strong>{{ $errors->first('price') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                    <label for="stock_quantity">Stock Quantity <span class="required">*</span></label>
                    <input name="stock_quantity" type="number" min="0" class="form-control" id="stock_quantity" placeholder="Enter Stock Quantity" value="{{@$products->stock_quantity}}">
                    @if ($errors->has('stock_quantity '))
                        <span class="required">
                            <strong>{{ $errors->first('stock_quantity ') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                      <label for="description">Description <span class="required">*</span></label>
                      <textarea class="form-control" id="description" name="description" style="background-color: white;" readonly="">{{@$products->description}}</textarea>
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
                    <label for="meta_title">Meta Title <span class="required">*</span></label>
                    <input name="meta_title" type="text" class="form-control" id="meta_title" placeholder="Enter Meta Title" value="{{@$products->meta_title}}" style="background-color: white;" readonly="">
                    @if ($errors->has('meta_title'))
                        <span class="required">
                            <strong>{{ $errors->first('meta_title') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                    <label for="meta_desc">Meta Description <span class="required">*</span></label>
                    <input name="meta_desc" type="text" class="form-control" id="meta_desc" placeholder="Enter Meta Description" value="{{@$products->meta_desc}}" style="background-color: white;" readonly="">
                    @if ($errors->has('meta_desc'))
                        <span class="required">
                            <strong>{{ $errors->first('meta_desc') }}</strong>
                        </span>
                    @endif 
                  </div>
                  <div class="form-group">
                    @if (isset($_REQUEST['id']) && $_REQUEST['id'] !='')
                    <input type="hidden" name="id" value="{{$_REQUEST['id']}}">
                      <button type="submit" class="btn btn-primary">Update</button>
                    @else
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
$('#name').change(function() {
  $('#meta_title').val($(this).val());
  $('#meta_desc').val($(this).val());
}); 
</script>
@endsection
