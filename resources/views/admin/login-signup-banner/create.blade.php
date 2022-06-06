@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
<section class="content">
  <div class="container-fluid">
   <div class="row">
    <div class="col-md-12">
      <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Create Login/Signup Banner</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
            <div class="card-body">
              <div class="form-group">
                <label for="imagename">Image Name : </label>
                <input name="imagename" type="text" class="form-control" id="imagename" placeholder="Enter Image Name">
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="image">Image : </label>
                  <div class="custom-file">
                    <input name="image" type="file" class="custom-file-input" id="image">
                    <label class="custom-file-label" for="image">Choose Image</label>
                  </div>
                </div>
                <div class="col-md-8">
                  <div id="upload-demo"></div>
                </div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary upload-image">Upload</button>
              </div>
            </div>
            <h2>Banners</h2>
            <div class="row">
              @foreach($banners as $row)
              <div class="col-sm-3">
                <div style="position: relative;">
                  <img src="{{ URL::to('/') }}/uploads/sliders/{{ @$row->image }}" style="width: 100%;" />
                  <button style="position: absolute; right: 0px;
                    font-size: 18px;" id="delete-multiple-image{{ $row->id }}" form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i>
                  </button> 
                  </div>
                <div>
                  
                  <form action="{{url('admin/sliders/delete-login-signup-sliders')}}?id={{$row->id}}" id="resource-delete-{{ $row->id }}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                  @csrf
                  </form>
                </div>
              </div>
              @endforeach
            </div>
      </div>
    </div>
   </div>
  </div><!-- /.container-fluid -->
</section>

<script type="text/javascript">

var resize = $('#upload-demo').croppie({
    enableExif: true,
    enableOrientation: true,    
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 400,
        height: 200,
        type: 'square' //square
    },
    boundary: {
        width: 500,
        height: 250
    }
});


$('#image').on('change', function () { 
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
  $(this).html('Uploading...');
  $(this).attr('disabled', true);
  resize.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function (img) {
    var imagename = $('#imagename').val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{route('admin.login-signup-banner.store')}}",
      type: "POST",
      data: {"image":img,"imagename":imagename},
      success: function (data) {
        location.reload();
        console.log(data);
      }
    });
  });
});


</script>

@endsection
