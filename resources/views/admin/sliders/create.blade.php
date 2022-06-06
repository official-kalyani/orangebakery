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
            <h3 class="card-title">Create Home Page Sliders</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
            <div class="card-body">
              <div class="form-group">
                <label for="imagename">Image Name : </label>
                <input name="imagename" type="text" class="form-control" id="imagename" placeholder="Enter Image Name">
              </div>
              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input class="custom-control-input" name="show_in_app_home" type="checkbox" id="show_in_app_home" value="yes" checked>
                  <label for="show_in_app_home" class="custom-control-label">Show In App Home</label>
                </div>
                <div class="custom-control custom-checkbox">
                  <input class="custom-control-input" name="show_in_website_home" type="checkbox" id="show_in_website_home" value="yes" checked>
                  <label for="show_in_website_home" class="custom-control-label">Show In Website Home</label>
                </div>
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
              <input type="hidden" readonly name="has_image" id="has_image" value="0" />
              <div class="form-group">
                <button type="submit" class="btn btn-primary upload-image">Upload</button>
              </div>
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
        width: 900,
        height: 267,
        type: 'square' //square
    },
    boundary: {
        width: 1000,
        height: 367
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
  $(this).html('Uploading...');
  $(this).attr('disabled', true);
  resize.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function (img) {
    var imagename = $('#imagename').val();
    var show_in_website_home = $('#show_in_website_home').val();
    var show_in_app_home = $('#show_in_app_home').val();
     var has_image = $('#has_image').val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{route('admin.sliders.store')}}",
      type: "POST",
      data: {"image":img,"imagename":imagename,"show_in_website_home":show_in_website_home,"show_in_app_home":show_in_app_home,"has_image":has_image},
      success: function (data) {
        location.reload();
        console.log(data);
      }
    });
  });
});


</script>

@endsection
