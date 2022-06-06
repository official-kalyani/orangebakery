@extends('layouts.app')
@section('content')
<script src="{{ asset('custimoze/js/dist/fabric.min.js') }}"></script>
<style>
    .drawing-area{
        position: absolute;
        top: 60px;
        left: 15px;
        z-index: 10;
        width: 400px;
        height: 200px;
        border: solid 1px red;
    }
    #tshirt-canvas{
        border: solid 1px #000;
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
    .logobox{
     padding: 10px;
    }
    .colorbox{
      width: 50px;
      height: 50px;
      float: left;
      margin: 5px;
      cursor: pointer;
    }
</style>
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
<!-- section start -->
<section class="section-b-space my-5">
    <div class="container" >
        <div class="row">
            <div class="col-md-6">
                <div id="tshirt-div">
                    <div id="drawingArea" class="drawing-area">                 
                        <div class="canvas-container">
                            <canvas id="tshirt-canvas" width="500" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row" style="padding-top: 400px;">
                    <div class="col-md-6" style="z-index:99999;">
                        <button id="canvas2png" class="btn btn-primary">Preview Cake</button>
                    </div>
                    <div class="col-md-6">
                        <input type="hidden" id="product_id" value="{{$_REQUEST['product_id']}}">
                         <button id="savecanvas2png" class="btn btn-primary">Save Cake</button>
                    </div>
                </div>
                <br>
                <div class="col-md-12" style="margin-top: 0px;">
                    <p class="save"></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <p>To remove a loaded picture on the T-Shirt select it and press the <kbd>DEL</kbd> key.</p>
                   <textarea id="title" class="form-control">Enter your message</textarea>
                </div>
                <div class="row">
                    @foreach($customCakes as $row)
                    <div class="col-md-3 logobox">                            
                        <label>
                    <img src="{{ URL::to('/') }}/uploads/customize-cakes/{{$row->image}}" alt="" class="img-fluid" />
                    <input class="tshirt-design" type="radio" name="fixedimg" value="{{ URL::to('/') }}/uploads/customize-cakes/{{$row->image}}" />
                        </label>
                    </div>
                    @endforeach
                </div>
                <div class="col-md-12">
                    <label>Font Color:</label>
                </div>  
                <div class="col-md-12" style="z-index:99999;">                        
                    <div class="colorbox" data-color="#ff0000" style="background: #ff0000;"></div>
                    <div class="colorbox" data-color="#742a2a" style="background: #742a2a;"></div>
                    <div class="colorbox" data-color="#d029d3" style="background: #d029d3;"></div>
                    <div class="colorbox" data-color="#4c164d" style="background: #4c164d;"></div>
                    <div class="colorbox" data-color="#762cc7" style="background: #762cc7;"></div>
                    <div class="colorbox" data-color="#1c1156" style="background: #1c1156;"></div>
                    <div class="colorbox" data-color="#0d072e" style="background: #0d072e;"></div>
                    <div class="colorbox" data-color="#1582df" style="background: #1582df;"></div>
                    <div class="colorbox" data-color="#0c5e5a" style="background: #0c5e5a;"></div>
                    <div class="colorbox" data-color="#073926" style="background: #073926;"></div>
                    <div class="colorbox" data-color="#f0de07" style="background: #f0de07;"></div>
                </div>
                <div class="col-md-6">
                    <label for="text-font-size">Font Size:</label>
                    <input type="range" value="18" min="1" max="120" step="1" id="text-font-size">
                </div>
                <div class="col-md-6">
                        <label for="text-font-size">Font Family:</label>
                        <select id="font-family" class="form-control">
                            <option value="arial">Arial</option>
                            <option value="helvetica" selected>Helvetica</option>
                            <option value="myriad pro">Myriad Pro</option>
                            <option value="delicious">Delicious</option>
                            <option value="verdana">Verdana</option>
                            <option value="georgia">Georgia</option>
                            <option value="courier">Courier</option>
                            <option value="comic sans ms">Comic Sans MS</option>
                            <option value="impact">Impact</option>
                            <option value="monaco">Monaco</option>
                            <option value="optima">Optima</option>
                            <option value="hoefler text">Hoefler Text</option>
                            <option value="plaster">Plaster</option>
                            <option value="engagement">Engagement</option>
                        </select>
                    </div>
                <div class="col-md-12" style="margin-top: 10px;">
                          <label >Font Style:</label>
                          <button class="dt_btns manage_font_style" id="dt_text_font_bold" type="button" data-action="bold" style="font-weight:bold" data-rel="font_weight" title="Bold">B</button>
                          <button class="dt_btns manage_font_style" id="dt_text_font_italic" type="button" data-action="italic" style="font-style: italic" data-rel="font_style"  title="Italic">I</button>
                          <button class="dt_btns text_decoration" type="button" data-action="underline" data-rel="font_style" style="text-decoration:underline " title="Underline">U</button>
                          <button class="dt_btns text_decoration" type="button" data-action="linethrough" data-rel="font_style" style="text-decoration:line-through" title="line-through">L</button>
                          <button class="dt_btns text_decoration" type="button" data-action="overline" data-rel="font_style" style="text-decoration:overline " title="overline">O</button>
                 </div>
                 <div class="col-md-12" style="margin-top: 10px;">
                        <label for="tshirt-custompicture">Upload your own design:</label>
                        <input class="form-control" type="file" id="tshirt-custompicture" />
                 </div>
                 
             </div>
        </div>
    </div>
    <div class="clearfix"></div>
</section>
<script>
            let canvas = new fabric.Canvas('tshirt-canvas');
            canvas.setBackgroundImage('<?php echo $_REQUEST['img']; ?>', canvas.renderAll.bind(canvas));
            function updateTshirtImage(imageURL){
                fabric.Image.fromURL(imageURL, function(img) {                   
                    img.scaleToHeight(300);
                    img.scaleToWidth(300); 
                    canvas.centerObject(img);
                    canvas.add(img);
                    canvas.renderAll();
                });
            }


fabric.Object.prototype.transparentCorners = false;
fabric.Object.prototype.padding = 5;
    
$("#canvas2png").click(function(){
    $(".save").html(canvas.toSVG());
});

$("#savecanvas2png").click(function(){
    $(".save").html(canvas.toSVG());
    canvas.isDrawingMode = false;
  //enlarge image
   // zoomIt();
  
    if(!window.localStorage){
        alert("This function is not supported by your browser."); return;
    }
    var dataURL = canvas.toDataURL('png');
    var _token = "{{@csrf_token()}}";
    var product_id = $('#product_id').val();
    //console.log(dataURL);
    $.ajax({
          url: "{{url('saveCustomizedImage')}}",
          method: "POST",
          data: {"image":dataURL,"_token":_token,"product_id":product_id},
          dataType: "json",
          success: function (res) {
            //$('.submit').html('SEND OTP');
            //$('.submit').attr('disabled', false);
            if (res.status == 1) {
              location.href="{{$_REQUEST['return_url']}}";
            }
            else{
              console.log('not saved');
            }
          
          }
          
      });
    //to PNG
    //console.log(canvas.toDataURL('png'));
    //window.open(canvas.toDataURL('png')); 
    //revert back to original size
   // unzoomIt();
});

var textOptions = { 
  fontSize:16, 
  left:100, 
  top:100, 
  radius:10, 
  borderRadius: '25px', 
  hasRotatingPoint: true 
};
var textObject = new fabric.Text('Enter your message', textOptions);
canvas.add(textObject).setActiveObject(textObject);
canvas.renderAll()

document.getElementById('title').addEventListener('keyup', function () {
    textObject.text = document.getElementById('title').value;
    canvas.renderAll();
});

$('.colorbox').click(function(){
    //alert($(this).data("color"));
    canvas.getActiveObject().set({fill: $(this).data("color")})
    canvas.renderAll();
});
document.getElementById('text-font-size').onchange = function() {
    //canvas.getActiveObject().setFontSize(this.value);
    canvas.getActiveObject().set({fontSize: this.value})
    canvas.renderAll();
};
$('#font-family').change(function(){
  var obj = canvas.getActiveObject();
  if(obj){
    obj.set("fontFamily", this.value);
  }
  canvas.renderAll();
});

$('.tshirt-design').click(function(){
    updateTshirtImage(this.value);
});
$('.manage_font_style').click(function(){
    var style_type = $(this).attr('data-rel');
    $( this ).toggleClass( 'highlight' );
    if($(this).hasClass('highlight'))
    {
        var cur_value = $(this).attr('data-action');
    }
    else
    {
        var cur_value = 'normal'; // On deselect hard coded value
    }
    if(cur_value!='')
    {
        //font_both is to support normal forboth font weight and style
        if(style_type=='font_style' || style_type=='font_both')
        {
            var activeObj = canvas.getActiveObject();
            //Check that text is selected
            if(activeObj==undefined)
            {
                alert('Please select a Text');
                return false;
            }
            else
            {
                activeObj.set({
                    fontStyle: cur_value
                });
            }
            canvas.renderAll();
        }
        
        if(style_type=='font_weight' || style_type=='font_both')
        {
            var activeObj = canvas.getActiveObject();
            //Check that text is selected
            if(activeObj==undefined)
            {
                alert('Please select a Text');
                return false;
            }
            activeObj.set({
                fontWeight: cur_value
            });
            canvas.renderAll();
        }
    }
    else
    {
        alert('Please select a Font Style');
        return false;
    }
});

//For Text Decoration
$('.text_decoration').click(function () {
    var status;
    $( this ).toggleClass( 'highlight' );
    if($(this).hasClass('highlight'))
    {
        status = true;
    }
    else
    {
        status = false;
    }
    //var text_decoration = $(this).val();
    var text_decoration = $(this).attr('data-action');
    var tObj = canvas.getActiveObject();
    //Check that text is selected
    if(tObj==undefined)
    {
        alert('Please select a Text');
        return false;
    }

    if(text_decoration=='underline')
    {
        tObj.set({
            underline: status
        });
    }
    else if(text_decoration=='linethrough')
    {
        tObj.set({
            linethrough: status
        });
    }
    else if(text_decoration=='overline')
    {
        tObj.set({
            overline: status
        });
    }
    canvas.renderAll();
});


    // When the user clicks on upload a custom picture
    document.getElementById('tshirt-custompicture').addEventListener("change", function(e){
        var reader = new FileReader();
        
        reader.onload = function (event){
            var imgObj = new Image();
            imgObj.src = event.target.result;

            // When the picture loads, create the image in Fabric.js
            imgObj.onload = function () {
                var img = new fabric.Image(imgObj);

                img.scaleToHeight(300);
                img.scaleToWidth(300); 
                canvas.centerObject(img);
                canvas.add(img);
                canvas.renderAll();
                
                
            };
        };

        // If the user selected a picture, load it
        if(e.target.files[0]){
            reader.readAsDataURL(e.target.files[0]);
        }
    }, false);

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