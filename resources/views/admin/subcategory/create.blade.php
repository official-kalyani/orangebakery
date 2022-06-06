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
                <h3 class="card-title">Create Subcategory</h3>
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
                    <label>Select Category <span class="required">*</span></label>
                    <select class="form-control" id="parent_id">
                      @foreach($categories as $category)
                      <option value="{{$category->id}}">{{$category->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name">Subcategory Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Subcategory Name">
                    <span style="font-size: 17px;" id="required_name"></span>
                  </div>
                  <div class="row"> 
                    <div class="col-md-4">
                      <label for="image">Subcategory Icon <span class="required">*</span></label>
                      <div class="custom-file">
                        <input name="image" type="file" class="custom-file-input" id="image">
                        <label class="custom-file-label" for="image">Choose Image</label>
                      </div>
                      <span style="font-size: 17px;" id="required_image"></span>
                      <input type="hidden" readonly name="has_image" id="has_image" value="0" />
                    </div>
                    <div class="col-md-8">
                      <div id="upload-demo"></div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input is_normal" type="radio" id="customRadio1" name="is_normal" value="yes" checked="">
                          <label for="customRadio1" class="custom-control-label">Normal</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input is_normal" type="radio" id="customRadio2" name="is_normal" value="no">
                          <label for="customRadio2" class="custom-control-label" >Cakes By Flavour</label>
                        </div>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="meta_title">Meta Title <span class="required">*</span></label>
                    <input name="meta_title" type="text" class="form-control" id="meta_title" placeholder="Enter Meta Title">
                    <span  id="required_meta_title"></span>
                  </div>
                  <div class="form-group">
                    <label for="meta_desc">Meta Description <span class="required">*</span></label>
                    <input name="meta_desc" type="text" class="form-control" id="meta_desc" placeholder="Enter Meta Description">
                    <span  id="required_meta_desc"></span>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary upload-image">Create</button>
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
  var requiredCheck = 1;
  if ($('#name').val() == '') {
    var requiredCheck = 0;
    $('#required_name').html('Please Enter subcategory Name');
  }
  if ($('#meta_title').val() == '') {
    var requiredCheck = 0;
    $('#required_meta_title').html('Please Enter Meta Title');
  }
  if ($('#meta_desc').val() == '') {
    var requiredCheck = 0;
    $('#required_meta_desc').html('Please Enter Meta Description');
  }
  if ($('#has_image').val() != 1) {
    var requiredCheck = 0;
    $('#required_image').html('Please Choose Image');
  }
  if ($('#has_image').val() == 1) {
    $(this).html('Uploading...');
    $(this).attr('disabled', true);
  }
  else{
    $(this).html('Create');
    $(this).attr('disabled', false);
  }
  if (requiredCheck == 1) {
    resize.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (img) {
      var name = $('#name').val();
      var has_image = $('#has_image').val();
      var parent_id = $('#parent_id').val();
      var meta_title = $('#meta_title').val();
      var meta_desc = $('#meta_desc').val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{route('admin.subcategory.store')}}",
        type: "POST",
        data: {"image":img,"name":name,"has_image":has_image,"parent_id":parent_id,"meta_title":meta_title,"meta_desc":meta_desc},
        success: function (res) {
          if (res.succ == 1) {
            location.href="{{url('/admin/subcategory/create/step2')}}?id="+res.id;
          }
          if (res.succ == 2) {
            alert('subcategory Already Created Create a new One');
            location.reload();
          }
          
          console.log(res);
        }
      });
    });
  }
});


</script>
@endsection
