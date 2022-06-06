@extends('layouts.app')
@section('content')

<style>
.drawing-area{
    position: absolute;
    top: 60px;
    left: 60px;
    z-index: 10;
    width: 200px;
    height: 400px;
}
#tshirt-canvas{
    border: dotted 3px #fff;
}
.canvas-container{
    width: 200px; 
    height: 400px; 
    position: relative; 
    user-select: none;
}

#tshirt-div{
    /* width: 452px;
    height: 548px; */
    position: relative;
    background-color: #fff;
}

#canvas{
    position: absolute;
    width: 200px;
    height: 400px; 
    left: 0px; 
    top: 0px; 
    user-select: none; 
    cursor: default;
}
.colorbox{
  width: 50px;
  height: 50px;
  float: left;
  margin: 5px;
  cursor: pointer;
}
.flavourbox{
  
  /*margin: 5px;
  cursor: pointer;*/
  padding-bottom: 15px;
}
<?php
if ($product->shape == 'round') {
  
?>
 .jcrop-holder div {
  -webkit-border-radius: 50% !important;
  -moz-border-radius: 50% !important;
  border-radius: 50% !important;
  margin: -1px;

} 
.photocake img{
  width:100%;
  border-radius: 50%;
  position: absolute;
  padding: 20px;
}
 <?php } else{

?> 
.photocake img{
  width:100%;
  
  position: absolute;
  padding: 20px;
}
<?php } 
?>
.cakeshape{
  background:url('{{ URL::to('/') }}/uploads/product/{{ @$customize_img->images }}');
  width:400px;
  height:400px;
  background-size: cover;
  position: relative;
}
.photocake{
  width:300px;
  height:300px;
}

</style>
<!-- without photo cake css start -->
<style>
.baseimg {
    height: 100%;
    width: 100%;
    -o-object-fit: cover;
       object-fit: cover;
}


.canvasarea{
	position:absolute;
	top: 0;
	z-index:0;
}
#tshirt-canvas{
  z-index:0 !important;
}
</style>
<!-- without photo cake css end -->
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Customize Your Cake</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="#">Customize Your Cake</a> </li>
            </ul>
           
        </div>
    </div>
</div>

<div class="container" style="margin-top:10px;">
<?php
 if($product->is_photocake){ ?>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <form method="post" action="{{url('uploadCustomizedImage')}}" enctype='multipart/form-data'>
    @csrf
    <input type="hidden" id="product_id" name="product_id" value="{{$product->id}}" />
    <input type="hidden" name="product_shape" value="{{$product->shape}}" />
      <div class="modal-body">
       <div class="col-lg-12">                            
						<input type="file" id="banner" name="banner" accept="image/*" required />
						<input type="hidden" id="banner_x1" name="banner_x1" />
						<input type="hidden" id="banner_y1" name="banner_y1" />
						<input type="hidden" id="banner_x2" name="banner_x2" />
						<input type="hidden" id="banner_y2" name="banner_y2" />
						<input type="hidden" id="banner_w" name="banner_w" />
						<input type="hidden" id="banner_h" name="banner_h" />
				</div>
					<div id="banner_prev_container" class="col-lg-12" style="margin-top:10px;">
						<img src="#" alt="" id="image_prev_banner">
					</div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button> &nbsp;&nbsp;
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
      </form>
    </div>

  </div>
</div>
  <div class="row">  
        <div class="col-md-6">
              <div class="cakeshape">
                  <div class="photocake">
                    <?php 
                    if(isset($customize_gallery->image) && $customize_gallery->image!=''){ ?>
                        <img src="{{ URL::to('/') }}/uploads/customize-gallery/{{ @$customize_gallery->image }}" />
                    <?php } else {
                      if(isset($user_customize_cake->image) && $user_customize_cake->image!=''){ ?>
                        <img src="{{ URL::to('/') }}/uploads/{{ @$user_customize_cake->user_id }}/{{ @$user_customize_cake->image }}" />
                     <?php }
                    } ?>

                    <canvas id="tshirt-canvas" width="400" height="400"></canvas>
                  </div>                  
              </div>
        <?php if(isset($user_customize_cake->image) && $user_customize_cake->image!=''){?>
        <button class="btn btn-info btn-xs" id="delete-multiple-image" form="customized_image_delete"> Remove your photo </button> 
          <form action="{{url('deleteCustomizedImage')}}" id="customized_image_delete" style="display: inline-block;" onSubmit="return confirm('Are you sure you want to delete your uploaded photo?');" method="post">
            @csrf
            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>" />
          </form>
        <?php }else{  ?>
          <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal">Upload your photo</button>
        <?php } ?>
        </div>
          <div class="col-md-6">
              <div class="col-md-12">
                  <p>To remove loaded text on the Cake select it and press the <kbd>DEL</kbd> key.</p>
                  <textarea id="title" class="form-control">Enter your message</textarea>
              </div>  
               <div class="col-md-12">
                  <label>Select from Orangebakery Gallery:</label>
              </div> 
              <div class="col-md-12 row">   
            @foreach($customize_galleries as $customize_gallery )                     
                <div class="col-md-3 flavourbox" data-url="<?php echo "http://".$_SERVER['HTTP_HOST'].'/customize?product_id='.$_REQUEST['product_id'].'&gallery='.$customize_gallery['id']; ?>" >
                  <img src="{{ URL::to('/') }}/uploads/customize-gallery/{{ @$customize_gallery->image }}" style="width: 100%; cursor:pointer">
                </div>
                @endforeach
              <div class="clearfix"></div>
              <div class="col-md-12">  
              <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].'/customize?product_id='.$_REQUEST['product_id']; ?>" class="btn btn-primary btn-sm">Remove Gallery</a>
              </div>
            </div>                      
              <div class="col-md-12">
                  <label>Font Color:</label>
              </div>  
              <div class="col-md-12">  
                     
              @foreach($customize_color as $customizecolor )                
                  <div class="colorbox" data-color="{{ @$customizecolor->color }}" style="background:{{ @$customizecolor->color }};"></div>
                  
                  @endforeach
                  <div class="clearfix"></div>
              </div>
                <div class="col-md-12" >
                    <button id="canvas2png" class="btn btn-primary">Continue</button>
                    <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].'/customize?product_id='.$_REQUEST['product_id']; ?>" class="btn btn-danger">Reset</a>
                    <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].'/products/'.$product->slug; ?>" class="btn btn-danger">Cancel</a>
                </div>
              <div class="col-md-12" style="margin-top: 10px;">
                  <p class="save"></p>
                </div>
            </div>
         </div> 
    </div>

   <hr />
<?php } else {?>

<div class="row">
				 <div class="col-md-6" style="position:relative">

          <?php if(isset($_REQUEST['flavour']) && $_REQUEST['flavour'] !=''){ ?>

             <img src="{{ URL::to('/') }}/uploads/flavour/{{ @$flavour_img->image }}" class="baseimg">
          
          <?php }else{  ?>

           <img src="{{ URL::to('/') }}/uploads/product/{{ @$customize_img->images }}" class="baseimg">

          <?php } ?>
				  


					<div class="canvasarea">
						<div id="tshirt-div">
						 <div id="drawingArea" class="drawing-area">					
							<div class="canvas-container">
								<canvas id="tshirt-canvas" width="400" height="400"></canvas>
							</div>
						</div>
					   </div>  
					</div>

				</div>
        <div class="col-md-6">
            <div class="col-md-12">
               <p>To remove loaded text on the Cake select it and press the <kbd>DEL</kbd> key.</p>
                <label>Select Falvour:</label>
                
            </div>  

            <div class="col-md-12 row">   
            @foreach($customize_flavours as $flavour )                     
                <div class="col-md-3 flavourbox" data-url="<?php echo "http://".$_SERVER['HTTP_HOST'].'/customize?product_id='.$_REQUEST['product_id'].'&flavour='.$flavour['id']; ?>" >
                  <img src="{{ URL::to('/') }}/uploads/flavour/{{ @$flavour->image }}" style="width: 100%;">
                  {{ @$flavour->name }}

                </div>
                @endforeach
              <div class="clearfix"></div>
            </div>
            <div class="col-md-12">                
                <textarea id="title" class="form-control">Enter your message</textarea>
            </div>
            <div class="col-md-12">
                <label>Font Color:</label>
            </div>  
            <div class="col-md-12">                        
                 @foreach($customize_color as $customizecolor )                
                  <div class="colorbox" data-color="{{ @$customizecolor->color }}" style="background:{{ @$customizecolor->color }};"></div>
                  
                  @endforeach
                <div class="clearfix"></div>
            </div>
              <div class="col-md-12">
              <button id="canvas2png" class="btn btn-primary">Continue</button>
                    <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].'/customize?product_id='.$_REQUEST['product_id']; ?>" class="btn btn-danger">Reset</a>
                    <a href="<?php echo "http://".$_SERVER['HTTP_HOST'].'/products/'.$product->slug; ?>" class="btn btn-danger">Cancel</a>
              </div>
              <div class="col-md-12" style="margin-top: 10px;">
                <p class="save"></p>
              </div>
          </div>
    </div>

<?php } ?>
<hr />
</div>
<link href="{{ asset('custimoze/jcrop/css/jquery.Jcrop.css') }}" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{{ asset('custimoze/jcrop/js/jquery.Jcrop.js') }}"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.1.0/fabric.all.min.js'></script>
<script>
	$(document).ready(function(){
			 $('#banner').on('change', function () {
				var file_name = $(this).val();
				if (file_name.length > 0) {
					$('#updated_banner').hide();
					addJcropBanner(this);
				}
			   });
				var addJcropBanner = function (input) {
					if ($('#image_prev_banner').data('Jcrop')) {
						// if need destroy jcrop or its created dom element here
						$('#image_prev_banner').data('Jcrop').destroy();
					}
					// this will add the src in image_prev as uploaded
					if (input.files && input.files[0]) {
						var reader = new FileReader();
						reader.onload = function (e) {
							$("#image_prev_banner").attr('src', e.target.result);
							var box_width = $('#banner_prev_container').width();
							$('#image_prev_banner').Jcrop({
								setSelect: [0, 0, 400],
                <?php if ($product->shape == 'round') { ?>
                aspectRatio: 2 / 2,
                <?php } else { ?>
                  aspectRatio: 4 / 2,
                <?php } ?>
                allowResize: true,
                allowSelect: false,
                bgOpacity: .3, // fade opacity
								onSelect: getBannerCoordinates,
								onChange: getBannerCoordinates,
								keySupport: false,
								boxWidth: box_width
							});
						}
						reader.readAsDataURL(input.files[0]);
					}
				}
				var getBannerCoordinates = function (e) {
					$('#banner_x1').val(e.x);
					$('#banner_y1').val(e.y);
					$('#banner_x2').val(e.x2);
					$('#banner_y2').val(e.y2);
					$('#banner_w').val(e.w);
					$('#banner_h').val(e.h);
				};


		  });
		</script>
    <script>
			let canvas = new fabric.Canvas('tshirt-canvas');
			fabric.Object.prototype.transparentCorners = false;
			fabric.Object.prototype.padding = 5;
			$("#canvas2png").click(function(){
				$(".save").html(canvas.toSVG());
			});
			document.getElementById('title').addEventListener('keyup', function () {
				textObject.text = document.getElementById('title').value;
				canvas.renderAll();
			});
      
      $('.flavourbox').click(function(){
        location.href= $(this).data("url");
			});
			$('.colorbox').click(function(){
				//alert($(this).data("color"));
				canvas.getActiveObject().set({fill: $(this).data("color")})
				canvas.renderAll();
			});

			$('.tshirt-design').click(function(){
				updateTshirtImage(this.value);
			});
			var textOptions = { 
			  fontSize:18, 
			  left:200, 
			  top:200, 
			  radius:10, 
			  borderRadius: '25px', 
			  hasRotatingPoint: true 
			};
			var textObject = new fabric.Text('Enter your message', textOptions);
			canvas.add(textObject).setActiveObject(textObject);
			canvas.renderAll()
			// When the user selects a picture that has been added and press the DEL key
			// The object will be removed !
			document.addEventListener("keydown", function(e) {
				var keyCode = e.keyCode;
				if(keyCode == 46){
					console.log("Removing selected element on Fabric.js on DELETE key !");
					canvas.remove(canvas.getActiveObject());
				}
			}, false);
		</script>
@endsection