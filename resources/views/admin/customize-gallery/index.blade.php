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
                <div class="row">
                  <div class="col-md-6">
                    <h3 class="card-title">Update Products</h3>
                  </div>
                  <div class="col-md-6">
                 
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
              <div class="card-body">
              
                <br>
               
                <div class="row">
                  <div class="col-md-4">
                    <label for="image">Product Image <span class="required">*</span></label>
                    <div class="custom-file">
                      <input name="image" type="file" class="custom-file-input" id="image">
                      <label class="custom-file-label" for="image">Choose Image</label>
                    </div>
                   
                    <input type="hidden" readonly name="has_image" id="has_image" value="0" />
                  </div>
                  <div class="col-md-8">
                    <div id="upload-demo"></div>
                  </div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary upload-image">Upload</button>
                </div>
                <h2>Product Images</h2>
                <div class="row">
                  @foreach($images as $row)
                  <div class="col-sm-3">
                    <div style="position: relative;">
                      <img src="{{ URL::to('/') }}/uploads/customize-gallery/{{ @$row->image }}" style="width: 100%;" />
                      <button style="position: absolute; right: 0px;
                        font-size: 18px;" id="delete-multiple-image{{ $row->id }}" form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i>
                      </button>  
                      </div>
                    <div>
                      
                      <form action="{{url('admin/customize-galleries/delete-multiple-image')}}?id={{$row->id}}" id="resource-delete-{{ $row->id }}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                      @csrf
                      </form>
                    </div>
                    
                  </div>
                  @endforeach
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
        height: 500,
        type: 'square' //square
    },
    boundary: {
        width: 600,
        height: 600
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
    var id = $('#id').val();
    var has_image = $('#has_image').val();
    var category_id = $('#category_id').val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{url('admin/customize-galleries/storeMultipleImages')}}",
      type: "POST",
      data: {"image":img},
      success: function (data) {
        location.reload();
        console.log(data);
      }
    });
  });
});




</script>
@endsection
