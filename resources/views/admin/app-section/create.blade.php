@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
<style type="text/css">
  #Product{
    display: none;
  }
  #Offer{
    display: none;
  }
  .showImagebtn{
    display: none;
  }
  #showForm{
    display: none;
  }
  .showForm{
    display: none;
  }
</style>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Create App Section</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              <div id="showForm">
                <form method="post" action="{{route('admin.app-section.store')}}">
                @csrf
                <input type="hidden" name="has_product_id" value="1">
              </div>
                <div class="card-body">
                   <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name">
                     @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif  
                  </div>
                  <div class="form-group">
                    <label for="type">Type <span class="required">*</span></label>
                    <select name="type" id="type" class="form-control">
                        <option value="">Select Type</option>
                        <option value="Product">Product</option>
                        <option value="Offer">Offer</option>
                    </select>
                  </div>
                  <div id="Product">
                    <div class="form-group">
                      <label for="type">Select Product<span class="required">*</span></label>
                      <select style="height:300px;" multiple name="product_id[]" id="product_id" class="form-control">
                        @foreach($products as $product)
                          <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div id="Offer">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-4">
                          <label for="image">Image <span class="required">*</span></label>
                          &nbsp;
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
                    </div>
                  </div>
                  <div class="form-group">
                  <div class="showForm">
                    <button type="submit" class="btn btn-primary">Create</button>
                  </div>
                  <div class="showImagebtn">
                    <button type="submit" class="btn btn-primary upload-image">Create</button>
                  </div>
                </div>
              
                </form>
              </div>
          </div>
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<script>
  $('#type').on('click', function() {
      if (this.value == "Product") {
        $('#Product').show();
        $('#showForm').show();
        $('.showForm').show();
        $('#Offer').hide();
        $('.showImagebtn').hide();
      }
      if (this.value == "Offer") {
        $('#showForm').hide();
        $('.showForm').hide();
        $('#Product').hide();
        $('#Offer').show();
        $('.showImagebtn').show();
      }
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
  if ($('#has_image').val() == 1) {
    $(this).html('Uploading...');
    $(this).attr('disabled', true);
  }
  else{
    $(this).html('Create');
    $(this).attr('disabled', false);
  }
  resize.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function (img) {
    var name = $('#name').val();
    var has_image = $('#has_image').val();
    var type = $('#type').val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{route('admin.app-section.store')}}",
      type: "POST",
      data: {"image":img,"name":name,"has_image":has_image,"type":type},
      success: function (res) {
        alert('App Section Created');
        location.reload();
        console.log(res);
      }
    });
  });
});

</script>
@endsection
