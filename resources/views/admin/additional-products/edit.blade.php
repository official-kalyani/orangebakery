@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Additional Products</h3>
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
                  <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name" value="{{$data->name}}">
                    <span style="font-size: 17px;" id="required_name"></span> 
                  </div>
                  <div class="form-group">
                    <label for="price">Price <span class="required">*</span></label>
                    <input name="price" min="1" type="number" class="form-control" id="price" placeholder="Enter Price" value="{{$data->price}}">
                    <span style="font-size: 17px;" id="required_price"></span>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <label for="image">Image <span class="required">*</span></label>
                      <img src="{{ URL::to('/') }}/uploads/additional-products/{{ @$data->image }}" alt="" class="img-responsive" style="width: 100%;"/>
                      <input type="hidden" id="id" name="id" value="{{ @$data->id }}"> 
                      &nbsp;
                      <div class="custom-file">
                        <input name="image" type="file" class="custom-file-input" id="image">
                        <label class="custom-file-label" for="image">Change Image</label>
                      </div>
                      <input type="hidden" readonly name="has_image" id="has_image" value="0" />
                    </div>
                    <div class="col-md-8">
                      <div id="upload-demo"></div>
                    </div>
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

var resize = $('#upload-demo').croppie({
    enableExif: true,
    enableOrientation: true,    
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 500,
        height: 300,
        type: 'square' //square
    },
    boundary: {
        width: 600,
        height: 400
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
    $('#required_name').html('Please Enter Name');
  }
  if ($('#price').val() == '') {
    var requiredCheck = 0;
    $('#required_price').html('Please Enter Price');
  }
  if ($('#has_image').val() == 1) {
    $(this).html('Uploading...');
    $(this).attr('disabled', true);
  }
  else{
    $(this).html('Update');
    $(this).attr('disabled', false);
  }
  if (requiredCheck == 1) {
    resize.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (img) {
      var name = $('#name').val();
      var has_image = $('#has_image').val();
      var id = $('#id').val();
      var price = $('#price').val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{route('admin.additional-products.store')}}",
        type: "POST",
        data: {"image":img,"name":name,"has_image":has_image,"id":id,"price":price},
        success: function (data) {
          alert('Category Updated');
          location.reload();
          console.log(data);
        }
      });
    });
  }
});


</script>
@endsection
