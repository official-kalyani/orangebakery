@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
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
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name" value="{{$data->name}}">
                    <span  id="required_name"></span>
                  </div>
                  <div class="form-group">
                    <label>Select Category <span class="required">*</span></label>
                    <select class="form-control" name="category" id="category">
                      @foreach($categories as $category)
                      <option @if($category->id == $data->category) selected @endif value="{{$category->id}}">{{$category->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <label for="image">Product Image</label>
                      <img src="{{ URL::to('/') }}/uploads/product/{{ @$data->image }}" alt="" class="img-responsive" style="width: 100%;"/>
                      <input type="hidden" id="id" name="id" value="{{ @$data->id }}"> 
                      &nbsp;
                      <div class="custom-file">
                        <input name="image" type="file" class="custom-file-input" id="image">
                        <span  id="required_image"></span>
                        <label class="custom-file-label" for="image">Change Image</label>
                      </div>
                      <input type="hidden" readonly name="has_image" id="has_image" value="0" />
                    </div>
                    <div class="col-md-8">
                      <div id="upload-demo"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="price">Price <span class="required">*</span></label>
                    <input name="price" type="number" min="0" class="form-control" id="price" placeholder="Enter Price" value="{{$data->price}}">
                    <span  id="required_price"></span>
                  </div>
                  <div class="form-group">
                      <label for="description">Description <span class="required">*</span></label>
                      <textarea class="form-control" id="description" name="description">{{$data->description}}</textarea>
                      <script>
                            CKEDITOR.replace( 'description' );
                      </script>
                      <span  id="required_description"></span>
                    </div>
                  <div class="form-group">
                    <label for="meta_title">Meta Title <span class="required">*</span></label>
                    <input name="meta_title" type="text" class="form-control" id="meta_title" placeholder="Enter Meta Title" value="{{$data->meta_title}}">
                    <span  id="required_meta_title"></span>
                  </div>
                  <div class="form-group">
                    <label for="meta_desc">Meta Description <span class="required">*</span></label>
                    <input name="meta_desc" type="text" class="form-control" id="meta_desc" placeholder="Enter Meta Description" value="{{$data->meta_desc}}">
                    <span  id="required_meta_desc"></span>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary upload-image">Update</button>
                  </div>
                </div>
          </div>
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<script type="text/javascript">

$('#name').change(function() {
  $('#meta_title').val($(this).val());
  $('#meta_desc').val($(this).val());
}); 

var resize = $('#upload-demo').croppie({
    enableExif: true,
    enableOrientation: true,    
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 500,
        height: 300,
        type: 'square' //square
    },
    boundary: {
        width: 500,
        height: 300
    }
});


$('#image').on('change', function () {
$('#has_image').val(1);  
  var reader = new FileReader();
    reader.onload = function (e) {
      resize.croppie('bind',{
        url: e.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(this.files[0]);
});


$('.upload-image').on('click', function (ev) {
  var requiredCheck = 1;
  if ($('#name').val() == '') {
    var requiredCheck = 0;
    $('#required_name').html('Please Enter Product Name');
  }
  if ($('#price').val() == '') {
    var requiredCheck = 0;
    $('#required_price').html('Please Enter Price');
  }
  if (CKEDITOR.instances['description'].getData() == '') {
    var requiredCheck = 0;
    $('#required_description').html('Please Enter Description');
  }
  if ($('#meta_title').val() == '') {
    var requiredCheck = 0;
    $('#required_meta_title').html('Please Enter Meta Title');
  }
  if ($('#meta_desc').val() == '') {
    var requiredCheck = 0;
    $('#required_meta_desc').html('Please Enter Meta Description');
  }
  $(this).html('Update');
  //$(this).attr('disabled', false);
  if (requiredCheck == 1) {
    resize.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (img) {
      var name = $('#name').val();
      var price = $('#price').val();
      var description = CKEDITOR.instances['description'].getData();
      var meta_title = $('#meta_title').val();
      var meta_desc = $('#meta_desc').val();
      var has_image = $('#has_image').val();
      var category = $('#category').val();
      var id = $('#id').val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{route('admin.products.store')}}",
        type: "POST",
        data: {"image":img,"name":name,"has_image":has_image,"price":price,"description":description,"meta_title":meta_title,"meta_desc":meta_desc,"category":category,"id":id},
        success: function (res) {
          alert('Product Updated');
          location.reload();
          console.log(res);
        }
      });
    });
  }
});


</script>
@endsection
