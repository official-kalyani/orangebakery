@extends('admin.layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
<style type="text/css">

</style> 
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
       <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update App Section</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
              @if($data->type == "Product")
              <div class="container">
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Product Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($section_items as $section_item)
                    <?php $product_arr = \App\Models\Product::where('id',$section_item->product_id)->first(); ?>
                    <tr>
                      <td>{{@$product_arr->name}}</td>
                      <td>
                        <button form="resource-delete-{{ $section_item->id }}"><i style="color: red;" class="fas fa-trash-alt"></i></button>
                        <form id="resource-delete-{{ $section_item->id }}" action="{{ url('admin/app-section/deleteProductSection') }}?id={{$section_item->id}}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                          @csrf
                        </form></td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
            </div>

                <form method="post" action="{{route('admin.app-section.update',$data->id)}}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="has_product_id" value="1">
              @endif
                <div class="card-body">
                  <div class="form-group">
                    <label for="name">Name <span class="required">*</span></label>
                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Name" value="{{$data->name}}">
                     @if ($errors->has('name'))
                        <span class="required">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif  
                  </div>
                  @if($data->type == "Product")
                  <div class="form-group">
                    <label for="type">Type - Product</label>
                  </div>
                  
                  <div class="form-group">
                    <label for="type">Add More Products<span class="required">*</span></label>
                    <select style="height:300px;" multiple name="product_id[]" id="product_id" class="form-control">
                      @foreach($products as $product)
                        @if(in_array($product->id,$fliterProducts))
                        <option value="{{$product->id}}" >{{$product->name}}</option>
                        @endif
                      @endforeach
                    </select>
                  </div>
                  @endif
                  @if($data->type == "Offer")
                  <div class="form-group">
                    <label for="type">Type - Image</label>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label for="image">Image <span class="required">*</span></label>
                        <img src="{{ URL::to('/') }}/uploads/offers/{{ @$data->image }}" alt="" class="img-responsive" style="width: 100%;"/>
                        <input type="hidden" id="id" value="{{ @$data->id }}"> 
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
                  @endif
                  @if($data->type == "Product")
                  <div class="form-group">
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                  </form>
                  @endif
                  @if($data->type == "Offer")
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary upload-image">Update</button>
                  </div>
                  @endif
                </div>

          </div>
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<script>
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
    var id = $('#id').val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{route('admin.app-section.store')}}",
      type: "POST",
      data: {"image":img,"name":name,"has_image":has_image,"id":id},
      success: function (res) {
        alert('App Section Updated');
        location.reload();
        console.log(res);
      }
    });
  });
});

</script>
@endsection
