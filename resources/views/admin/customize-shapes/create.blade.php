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
            <h3 class="card-title">Create Customize Shapes</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="name">Shape Name : </label>
                    <input type="text" class="form-control" placeholder="Enter your shape name" name="name" id="shapename" />
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="image">Customize Image : </label>
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
            
            <div class="row" style="display:none">
            <h2>Iamges</h2>
              @foreach($banners as $row)
              <div class="col-sm-3">
                <div style="position: relative;">
                  <img src="{{ URL::to('/') }}/uploads/customize-shapes/{{ @$row->image }}" style="width: 100%;" />
                  <button style="position: absolute; right: 0px;
                    font-size: 18px;" id="delete-multiple-image{{ $row->id }}" form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i>
                  </button> 
                  </div>
                <div>
                  
                  <form action="{{url('admin/customize-shapes/delete-customize-shapes')}}?id={{$row->id}}" id="resource-delete-{{ $row->id }}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
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
        width: 200,
        height: 200,
        type: 'square' //square
    },
    boundary: {
        width: 300,
        height: 300
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
  var shapename = $('#shapename').val();
  resize.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function (img) {
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{route('admin.customize-shapes.store')}}",
      type: "POST",
      data: {"image":img, 'shapename':shapename},
      success: function (data) {
        location.reload();
        console.log(data);
      }
    });
  });
});


</script>

@endsection
