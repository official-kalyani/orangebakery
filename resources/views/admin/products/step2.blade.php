@extends('admin.layouts.app')
@section('content')
@if($products->is_customize != 1)
<style type="text/css">
  .has_show_customize{
    display: none;
  }
</style>
@endif
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
                    <a class="float-right" href="{{url('admin/products/create/')}}?id=<?php echo $_REQUEST['id']; ?>">Back to Step 1</a>
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
                <form method="post" action="{{url('admin/products/saveStep2')}}">
                  @csrf
                  @if($checkCategory)
                    <div class="form-group">
                      <label>Select Subcategory <span class="required">*</span></label>
                      <select class="form-control" name="subcategory_id" id="subcategory_id">
                        @foreach($subcategories as $subcategory)
                          <option value="{{$subcategory->id}}" @if($subcategory->id == @$products->subcategory_id) selected @endif>{{$subcategory->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Select Occasions <span class="required">*</span></label><br>
                      @foreach($occasions as $occasion)
                        <label><input type="checkbox" @if(@in_array(@$occasion->id,@$occasion_id)) checked @endif  value="{{$occasion->id}}" name="occasion_id[]"> {{$occasion->name}}</label>
                      @endforeach
                    </div>  
                    <div class="form-group">
                      <div class="dynamicRadio">
                        <div class="row">
                          <div class="col-md-3"><label>Weight</label>
                              <span class="required">*</span>
                              <input name = "weight[]" type="text" class="form-control" Placeholder="Enter Cake Weight" />
                          </div>
                          <div class="col-md-3">
                              <label>MRP Price</label>
                              <span class="required">*</span>
                              <input name = "mrp_price[]" type="number" min="0" class="form-control" Placeholder="Enter Cake MRP Price" />
                          </div>
                          <div class="col-md-3">
                              <label>Selling Price</label>
                              <span class="required">*</span>
                              <input name = "selling_price[]" type="number" min="0" class="form-control" Placeholder="Enter Cake Selling Price" />
                          </div>
                          <div class="col-md-2">
                              <label>&nbsp;</label><br>
                              <input id="btnCakePrice" class="btn-primary" type="button" value="Add More" />
                          </div>
                        <div>
                      </div>
                    </div>
                    <div id="CakeContainer"></div>
                    <input type="hidden" name="has_subcategory" value="1">
                    <input type="hidden" name="category_id" value="{{$products->category_id}}">
                  @else
                    <div class="form-group">
                        <label for="mrp_price">Mrp Price <span class="required">*</span></label>
                        <input name="mrp_price" type="number" min="0" class="form-control" id="price" placeholder="Enter Mrp Price" value="{{@$product_price->mrp_price}}">
                        @if ($errors->has('mrp_price'))
                            <span class="required">
                                <strong>{{ $errors->first('mrp_price') }}</strong>
                            </span>
                        @endif 
                    </div>
                    <div class="form-group">
                        <label for="price">Selling Price <span class="required">*</span></label>
                        <input name="price" type="number" min="0" class="form-control" id="price" placeholder="Enter Selling Price" value="{{@$product_price->price}}">
                        @if ($errors->has('price'))
                            <span class="required">
                                <strong>{{ $errors->first('price') }}</strong>
                            </span>
                        @endif 
                    </div>
                    <div class="form-group">
                        <label for="stock_quantity">Stock Quantity <span class="required">*</span></label>
                        <input name="stock_quantity" type="number" min="0" class="form-control" id="stock_quantity " placeholder="Enter Stock Quantity" value="{{@$products->stock_quantity }}">
                        @if ($errors->has('stock_quantity '))
                            <span class="required">
                                <strong>{{ $errors->first('stock_quantity ') }}</strong>
                            </span>
                        @endif 
                    </div>
                    <input type="hidden" name="has_subcategory" value="0">
                    <input type="hidden" name="category_id" value="{{$products->category_id}}">
                  @endif
                  <br>
                  <input type="hidden" name="product_id" value="{{@$_REQUEST['id']}}">
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                  </div>
                </form>
                <br>
                @if($checkCategory)
                <h3>Update Price</h3>
                <label>&nbsp;</label>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Weight</th>
                      <th>MRP Price</th>
                      <th>Selling Price</th>
                      <th>Show Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $row)
                    <tr>
                      <td>{{$row->weight}}</td>
                      <td>&#8377; {{$row->mrp_price}}</td>
                      <td>&#8377; {{$row->price}}</td>
                      <td><?php echo $row->show_price==1?'Yes':'No'; ?></td>
                      <td><a data-toggle="modal" data-target="#myModal{{$row->id}}" class="btn"><i class="fas fa-edit" style="color: blue" ></i></a>
                        <button form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i></button>
                        <form id="resource-delete-{{ $row->id }}" action="{{ url('admin/products/deletePriceWeight') }}?id={{$row->id}}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                          @csrf
                        </form></td>
                    </tr>
                    <div class="container">
                      <div class="modal fade" id="myModal{{$row->id}}" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-body">
                              <form method="post" action="{{url('/admin/products/updatePriceWeight')}}">
                                @csrf
                                <div class="form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                                  <label for="weight">Weight <span class="required">*</span></label>
                                  <input name="weight" type="text" class="form-control" id="weight" placeholder="Enter Cake Weight" value="{{@$row->weight}}">
                                </div>
                                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                  <label for="mrp_price">MRP Price <span class="required">*</span></label>
                                  <input name="mrp_price" type="text" class="form-control" id="mrp_price" placeholder="Enter Cake MRP Price" value="{{@$row->mrp_price}}">
                                </div>
                                <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                  <label for="price">Selling Price <span class="required">*</span></label>
                                  <input name="price" type="text" class="form-control" id="price" placeholder="Enter Cake Selling Price" value="{{@$row->price}}">
                                </div>
                                <div class="form-group">
                                   <label for="show_price">Is this your price to show ? <span class="required">*</span></label>
                                   <input type="radio" value="1" name="show_price" <?php if($row->show_price == 1) {?> checked <?php } ?> /> Yes
                                   <input type="radio" value="0" name="show_price" <?php if($row->show_price == 0) {?> checked <?php } ?>/> No
                                </div>
                                <input type="hidden" name="id" value="{{@$row->id}}">
                                <div class="form-group">
                                  <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                                </form>
                            </div>
                            
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endforeach
                  </tbody>
                </table>
                @endif
                <div class="row">
                  <div class="col-md-4">
                    <label for="image">Product Image <span class="required">*</span></label>
                    <div class="custom-file">
                      <input name="image" type="file" class="custom-file-input" id="image">
                      <label class="custom-file-label" for="image">Choose Image</label>
                    </div>
                    <input type="hidden" id="category_id" value="{{$products->category_id}}">
                    <input type="hidden" id="id" value="{{ @$_REQUEST['id'] }}"> 
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
                      <img src="{{ URL::to('/') }}/uploads/product/{{ @$row->images }}" style="width: 100%;" />
                      <button style="position: absolute; right: 0px;
                        font-size: 18px;" id="delete-multiple-image{{ $row->id }}" form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i>
                      </button>  
                      </div>
                    <div>
                      
                      <form action="{{url('admin/products/delete-multiple-image')}}?id={{$row->id}}" id="resource-delete-{{ $row->id }}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                      @csrf
                      </form>
                    </div>
                    <div>
                      <label><input type="radio" value="{{$row->id}}" id="featureimage{{$row->id}}" @if($row->is_featured == 1) checked @endif name="featureimage">Make Featured Image</label>
                    </div>
                    <div>
                      @if($products->is_customize == 1)
                      <label><input type="radio" value="{{$row->id}}" id="customIMage{{$row->id}}" @if($row->customize == 1) checked @endif name="customIMage">Customize</label>
                      @endif
                    </div>
                  </div>
                  @endforeach
                </div>
                <hr>
                <div class="has_show_customize">
                    <div class="form-group">
                      <label>Customized Flavours</label>
                      <div class="form-group">
                         <!--  <form method="post" action="{{url('/admin/products/saveFlavours')}}" enctype="multipart/form-data">
                                @csrf -->
                                <div class="form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                                  <label for="name">Name <span class="required">*</span></label>
                                  <input name="flavourname" type="text" class="form-control" id="flavourname" placeholder="Enter Name" value="">
                                </div>
                                <!-- <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                  <label for="image">Image <span class="required">*</span></label>
                                  <div class="custom-file">
                                    <input name="flavourimage" type="file" class="custom-file-input" id="flavourimage">
                                    <label class="custom-file-label" for="image">Choose Image</label>
                                  </div>
                    
                                </div> -->
                                <div class="row">
                  <div class="col-md-4">
                    <label for="image">Flavour Image <span class="required">*</span></label>
                    <div class="custom-file">
                      <input name="image2" type="file" class="custom-file-input" id="image2">
                      <label class="custom-file-label" for="image">Choose Image</label>
                    </div>
                   
                    <input type="hidden" readonly name="has_image2" id="has_image2" value="0" />
                  </div>
                  <div class="col-md-8">
                    <div id="upload-demo2"></div>
                  </div>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-primary upload-image2">Upload</button>
                </div>
               <!--  <h2>Product Images</h2>
                <div class="row">
                  @foreach($images as $row)
                  <div class="col-sm-3">
                    <div style="position: relative;">
                      <img src="{{ URL::to('/') }}/uploads/flavour/{{ @$row->image }}" style="width: 100%;" />
                      <button style="position: absolute; right: 0px;
                        font-size: 18px;" id="delete-multiple-image{{ $row->id }}" form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i>
                      </button>  
                      </div>
                    <div>
                      
                      <form action="{{url('admin/customize-flavours/delete-multiple-image')}}?id={{$row->id}}" id="resource-delete-{{ $row->id }}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                      @csrf
                      </form>
                    </div>
                    
                  </div>
                  @endforeach
                </div> -->
                                
                                <input type="hidden" name="product_id" id="product_id" value="{{@$_REQUEST['id']}}">
                                <!-- <div class="form-group">
                                  <button type="submit" class="btn btn-primary">Save</button>
                                </div> -->
                                <!-- </form> -->
                      </div>
                    </div>
                  
                  <hr>
                  <h3>Customized Flavours</h3>

                  @if(Session::has('flash_success'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                  {{ Session::get('flash_success') }}
                  </div>
              @endif
                <label>&nbsp;</label>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Image</th>
                      <th>Action</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    @if($products->is_customize == 1)
                    @foreach($flavour as $row)
                    <tr>
                      <td>{{ $row->id }}</td>
                      <td>{{ $row->name }}</td>
                      <td> <img src="{{ URL::to('/') }}/uploads/flavour/{{ @$row->image }}" style="width: 30%;" /> </td>
                      <td><button form="resource-delete-{{ $row->id }}"><i style="color: red;" class="fas fa-trash-alt"></i></button>
                        <form id="resource-delete-{{ $row->id }}" action="{{ url('admin/products/deleteFlavour') }}?id={{$row->id}}" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete this item?');" method="post">
                          @csrf
                        </form></td>
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
                </table>
                </div>
              </div>
          </div>
        </div>
       </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
<script type="text/javascript">

$("#btnCakePrice").bind("click", function () {
    var div = $("<div />");
    div.html(GetDynamicCakePriceWeight(""));
    $("#CakeContainer").append(div);
});
$("body").on("click", ".removeRadio", function () {
    $(this).closest(".dynamicRadio").remove();
});
function GetDynamicCakePriceWeight(value) {
    return '<div class="dynamicRadio"><div class="row"><div class="col-md-3"><label>Weight</label><span class="required">*</span><input name = "weight[]" type="text" min="0" class="form-control" Placeholder="Enter Cake Weight" /></div><div class="col-md-3"><label>MRP Price</label><span class="required">*</span><input name = "mrp_price[]" type="number" min="0" class="form-control" Placeholder="Enter Cake MRP Price" /></div><div class="col-md-3"><label>Selling Price</label><span class="required">*</span><input name = "selling_price[]" type="number" min="0" class="form-control" Placeholder="Enter Cake Selling Price" /></div><div class="col-md-2"><label>&nbsp;</label><br><input type="button" value="Remove" class="removeRadio btn btn-danger" /></div></div><div>'
}

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
    var id = $('#id').val();
    var has_image = $('#has_image').val();
    var category_id = $('#category_id').val();
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{url('admin/products/storeMultipleImages')}}",
      type: "POST",
      data: {"image":img,"id":id,"has_image":has_image,"category_id":category_id},
      success: function (data) {
        location.reload();
        console.log(data);
      }
    });
  });
});

<?php foreach($images as $row){ ?>
$('#featureimage<?php echo $row->id; ?>').on('click', function (ev) {
  var imageId = $('#featureimage<?php echo $row->id; ?>').val();
  var id = $('#id').val();
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    url: "{{url('admin/products/makeFeatureImage')}}",
    data: {"imageId":imageId,"id":id},
    dataType: "json",
    success: function (data) {
      location.reload();
      if (data.succ == 1) {
          location.reload();
      } 
      console.log(data);
    }
  });
});
<?php } ?>

<?php foreach($images as $row){ ?>
$('#customIMage<?php echo $row->id; ?>').on('click', function (ev) {
  var imageId = $('#customIMage<?php echo $row->id; ?>').val();
  var id = $('#id').val();
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    url: "{{url('admin/products/makeCustomizeImage')}}",
    data: {"imageId":imageId,"id":id},
    dataType: "json",
    success: function (data) {
      location.reload();
      if (data.succ == 1) {
          location.reload();
      } 
      console.log(data);
    }
  });
});
<?php } ?>
</script>


<!-- customize flavour -->

<script type="text/javascript">

var resize2 = $('#upload-demo2').croppie({
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


$('#image2').on('change', function () {

  var reader2 = new FileReader();
    reader2.onload = function (e) {
      resize2.croppie('bind',{
        url: e.target.result
      }).then(function(){
        console.log('jQuery bind complete');
      });
    }
    reader2.readAsDataURL(this.files[0]);
});


$('.upload-image2').on('click', function (ev) {
  
    $(this).html('Uploading...');
    $(this).attr('disabled', true);
  
  
  resize2.croppie('result', {
    type: 'canvas',
    size: 'viewport'
  }).then(function (img) {
    var flavourname = $('#flavourname').val();
    var product_id = $('#product_id').val();
    // alert(flavourname);
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: "{{url('admin/products/saveFlavours')}}",
      type: "POST",
      data: {"image":img,"flavourname":flavourname,"product_id":product_id},
      success: function (data) {
        location.reload();
        // alert(data);
      }
    });
  });
});




</script>
@endsection
