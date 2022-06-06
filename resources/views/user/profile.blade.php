@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 --><script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>My Profile</h2>
            <ul>
                <li><a href="{{url('/')}}">Home </a> >></li>
                <li><a href="{{url('/disclaimer')}}">My Profile</a> </li>
            </ul>
        </div>
    </div>
</div>
<div class="cart-secion my-5">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="container bootstrap snippet">
                        <div class="row">
                            <div class="col-sm-10"><h1>{{@$user->name}}</h1></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3"><!--left col-->
                                  

                              <div class="text-center">
                                @if($user->avatar)
                                  <img src="{{ URL::to('/') }}/uploads/profile/{{ @$user->avatar }}" alt="avatar"/>
                                @else
                                    <img src="http://ssl.gstatic.com/accounts/ui/avatar_2x.png" class="avatar img-circle img-thumbnail"  alt="avatar">
                                @endif 
                                <h6>Upload a profile photo...</h6>
                                <div id="upload-demo"></div>
                                
                                <input type="file" class="text-center center-block file-upload" name="image" id="image">
                                <input type="hidden" readonly name="has_image" id="has_image" value="0" />
                                <button class="btn btn-lg addtocart upload-image" type="submit">Update Avatar</button>
                              </div></hr><br>
                                   
                              
                            </div><!--/col-3-->
                            <div class="col-sm-9">    
                                <div class="tab-content">
                                    <div class="tab-pane active" id="home">
                                         @if(Session::has('flash_success'))
                                              <div class="alert alert-success">
                                                  <button type="button" class="close" data-dismiss="alert">×</button>
                                              {{ Session::get('flash_success') }}
                                              </div>
                                          @endif
                                          @if(Session::has('flash_danger'))
                                              <div class="alert alert-danger">
                                                  <button type="button" class="close" data-dismiss="alert">×</button>
                                              {{ Session::get('flash_danger') }}
                                              </div>
                                          @endif
                                          <!-- Nav tabs -->
                                          <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                              <a class="nav-link active" data-toggle="tab" href="#profile">Profile</a>
                                            </li>
                                            <li class="nav-item">
                                              <a class="nav-link" data-toggle="tab" href="#Createlocation">Create Address</a>
                                            </li>
                                            <li class="nav-item">
                                              <a class="nav-link" data-toggle="tab" href="#Updatelocation">Update Address</a>
                                            </li>
                                          </ul>
                                          <br>
                                        <!-- Tab panes -->
                                        <div class="tab-content">
                                          <div id="profile" class="container tab-pane active"><br>
                                              <form class="form" action="{{url('updateProfile')}}" method="post" id="registrationForm">
                                                  @csrf
                                                    <div class="form-group">
                                                        
                                                        <div class="col-xs-6">
                                                            <label for="name">Name</label>
                                                            <input type="text" class="form-control" name="name" id="name" placeholder="first name" value="{{$user->name}}" title="enter your name" >
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <div class="col-xs-6">
                                                            <label for="email">Email</label>
                                                            <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" value="{{$user->email}}" title="enter your email." readonly style="background-color:white;">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <div class="col-xs-6">
                                                            <label for="phone">Phone</label>
                                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="enter phone" title="enter your phone number" value="{{$user->phone}}" readonly style="background-color:white;">
                                                        </div>
                                                    </div>
                                      
                                                    
                                                    <div class="form-group">
                                                        
                                                        <div class="col-xs-6">
                                                            <label for="password">Password</label>
                                                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password if you want to change" title="enter your password.">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        
                                                        <div class="col-xs-6">
                                                          <label for="confirm_password">Confirm Password</label>
                                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password" title="Confirm Password">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                         <div class="col-xs-12">
                                                              <button class="btn btn-lg addtocart" type="submit">Update Profile</button>
                                                          </div>
                                                    </div>
                                              </form>
                                          </div>
                                          <div id="Createlocation" class="container tab-pane fade"><br>
                                              <form id="createAddress">
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                    <div class="pincode">Name<span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="name_c" id="name_c" placeholder="Enter Name">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                    <div class="pincode">Email <span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="email_c" id="email_c" placeholder="Enter Email">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                    <div class="pincode">Phone No <span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="phoneno" id="phoneno_c" placeholder="Enter Phone No">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                  <div class="field-label">Address Type <span class="required">*</span></div>
                                                  <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                      <input id="address_type_c" type="radio" class="form-check-input" name="address_type_c" value="Home" checked="">Home
                                                    </label>
                                                  </div>
                                                  <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                      <input type="radio" class="form-check-input" id="address_type_c" name="address_type_c" value="Office">Office
                                                    </label>
                                                  </div>
                                                  <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                      <input type="radio" class="form-check-input" id="address_type_c" name="address_type_c" value="Public">Public
                                                    </label>
                                                  </div>
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <div class="field-label">Make My Default Address <span class="required">*</span></div>
                                                    <div class="form-check-inline">
                                                      <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" id="has_default_c" name="has_default_c" value="1" checked="">Yes
                                                      </label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                      <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" id="has_default_c" name="has_default_c" value="0">No
                                                      </label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <div class="short_address_c">Address <span class="required">*</span></div>
                                                    <input type="text" id="short_address_c" name="short_address_c" placeholder="Enter Location" class="form-control">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <div class="additional_address">Nearest Landmark</div>
                                                    <input type="text" name="additional_address_c" id="additional_address_c" placeholder="Enter nearest landmark" class="form-control">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                    <div class="pincode">Pin Code <span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="pincode_c" id="pincode_c" placeholder="Enter Pin Code">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12"><button type="submit" class="cart-btn btn createAddress_btn">Create Address</button></div>
                                              </form>
                                          </div>
                                          <div id="Updatelocation" class="container tab-pane fade"><br>
                                          @if(count($addresses) > 0)  
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                              <label>Select Address to Change</label>
                                              <select  @foreach($addresses as $address) id="address{{@$address->id}}" @endforeach class="form-control">
                                                <?php $counter = 1; ?>
                                                @foreach($addresses as $address)
                                                  <option value="{{@$address->id}}">Address {{$counter++}}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                              <form method="post" action="{{url('user/profile/updateAddress')}}">
                                                @csrf
                                                <input type="hidden" id="address_idd" name="address_idd" value="{{@$singleAddress->id}}">
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                <div class="u_name">Name <span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="u_name" id="u_name" placeholder="Enter Name">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                <div class="u_email">Email <span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="u_email" id="u_email" placeholder="Enter Email">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                    <div class="pincode">Phone No <span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="phone" id="phone" placeholder="Enter Phone No">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                    <div class="pincode">Phone No <span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="phoneno" id="phoneno" placeholder="Enter Phone No" maxlength="10" minlength="10" value="{{@$address->phone}}">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                  <div class="field-label">Address Type <span class="required">*</span></div>
                                                  <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                      <input id="address_type" type="radio" class="form-check-input" name="address_type" @if(@$singleAddress->address_type == "Home") checked @endif value="Home">Home
                                                    </label>
                                                  </div>
                                                  <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                      <input type="radio" class="form-check-input" id="address_type" name="address_type" value="Office"  @if(@$singleAddress->address_type == "Office") checked @endif>Office
                                                    </label>
                                                  </div>
                                                  <div class="form-check-inline">
                                                    <label class="form-check-label">
                                                      <input type="radio" class="form-check-input" id="address_type" name="address_type" value="Public" @if(@$singleAddress->address_type == "Public") checked @endif>Public
                                                    </label>
                                                  </div>
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <div class="field-label">Make My Default Address <span class="required">*</span></div>
                                                    <div class="form-check-inline">
                                                      <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="has_default" value="1" @if(@$singleAddress->has_default == 1) checked @endif>Yes
                                                      </label>
                                                    </div>
                                                    <div class="form-check-inline">
                                                      <label class="form-check-label">
                                                        <input type="radio" class="form-check-input" name="has_default" value="0" @if(@$singleAddress->has_default == 0) checked @endif>No
                                                      </label>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <div class="short_address">Address <span class="required">*</span></div>
                                                    <input type="text" id="short_address" name="short_address" placeholder="Enter Location" class="form-control" value="{{@$address->location}}">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                    <div class="additional_address">Nearest Landmark</div>
                                                    <input type="text" name="additional_address" id="additional_address" placeholder="Enter nearest landmark" class="form-control" value="{{@$address->additional_address}}">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-6 col-xs-12">
                                                    <div class="pincode">Pin Code <span class="required">*</span></div>
                                                    <input class="form-control" type="text" name="pincode" id="pincode" placeholder="Enter Pin Code" value="{{@$address->pincode}}">
                                                </div>
                                                <div class="form-group col-md-12 col-sm-12 col-xs-12"><button type="submit" href="#" class="cart-btn btn submit">Update Address</button></div>
                                              </form>
                                          @else
                                          <h5>It Seems you have not created any Address</h5>
                                          @endif
                                          </div>

                                      
                                    </div><!--/tab-pane-->
                                </div><!--/tab-pane-->
                            </div><!--/tab-content-->

                            </div><!--/col-9-->
                        </div><!--/row-->
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script type="text/javascript">
$.validator.addMethod("mail", function (value, element) {
    return this.optional(element) || /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,10}|[0-9]{1,3})(\]?)$/.test(value);
}, "Please enter a correct email address");
$(".toCpas").on('blur', function () {
   var str = $('.toCpas').text();
    $('.toCpas').text(str.charAt(0).toUpperCase() + str.substr(1).toLowerCase())
});

jQuery.validator.addMethod("alphaNumeric", function (value, element) {
    return this.optional(element) || /^(?=\D*\d)(?=[^a-z]*[a-z])[0-9a-z]+$/i.test(value);
}, "Password field must be minimum 8 character length with at least 1 number and 1 alphabet");
jQuery.validator.addMethod("lettersonly", function(value, element) 
{
return this.optional(element) || /^[a-z," "]+$/i.test(value);
}, "Letters and spaces only please"); 


<?php foreach($addresses as $address){ ?>
$('#address<?php echo $address->id; ?>').on('click', function (ev) {
  var address_id = $('#address<?php echo $address->id; ?>').val();
  $.ajax({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST",
    url: "{{url('user/profile/fetchAddress')}}",
    data: {"address_id":address_id},
    dataType: "json",
    success: function (data) {
      $('#address_type').val(data.address_type);
      if (data.address_type == "Home") {
        $('input:radio[name=address_type]')[0].checked = true;
      }
      else if(data.address_type == "Office") {
        $('input:radio[name=address_type]')[1].checked = true;
      }
      else if(data.address_type == "Public") {
        $('input:radio[name=address_type]')[2].checked = true;
      }
      if (data.has_default == 1) {
        $('input:radio[name=has_default]')[0].checked = true;
      }
      else{
        $('input:radio[name=has_default]')[1].checked = true;
      }
      $('#short_address').val(data.location);
      $('#additional_address').val(data.additional_address);
      $('#pincode').val(data.pincode);
      $('#address_idd').val(data.id);
      $('#phoneno').val(data.phone);
      $('#u_name').val(data.name);
      $('#u_email').val(data.email);
    }
  });
});
<?php } ?>
var resize = $('#upload-demo').croppie({
    enableExif: true,
    enableOrientation: true,    
    viewport: { // Default { width: 100, height: 100, type: 'square' } 
        width: 192,
        height: 192,
        type: 'square' //square
    },
    boundary: {
        width: 210,
        height: 210
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
    var requiredCheck = 1
    $(this).html('Uploading...');
    $(this).attr('disabled', true);
  }
  else{
    $(this).html('Update Avatar');
    $(this).attr('disabled', false);
  }
  if (requiredCheck == 1) {
    resize.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function (img) {
      var has_image = $('#has_image').val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{url('updateProfilePicture')}}",
        type: "POST",
        data: {"image":img,"has_image":has_image},
        success: function (res) {
          alert('Profile Updated');
          location.reload();
          console.log(res);
        }
      });
    });
  }
});

$("#createAddress").validate({
    rules: {
        name_c: {
            required: true,
        },
        email_c: {
            required: true,
            mail: true,
        },
        address_type_c: {
            required: true,
        },
        has_default_c: {
            required: true,
        },
        phoneno_c: {
            required: true,
            minlength : 10,
            maxlength : 10,
        },
        short_address_c: {
            required: true,
        },
        pincode_c: {
            required: true,
            minlength : 6,
        },
    },
    messages: {
        name_c: {
            required: "Please enter your full name",
            minlength: "Your full name must be at least 2 characters long"
        },
        email_c: {
            required: "Please enter your email address",
            mail: 'Please enter a valid email address'
        },
        address_type_c: {
            required: "Please enter address type",
        },
        has_default_c: {
            required: "Please enter default address",
        },
        phoneno_c: {
            required: "Please enter phone number",
            minlength : "Pincode must be 10 digits long",
            maxlength : "Pincode must be 10 digits long"
        },
        short_address_c: {
            required: "Please enter location",
        },
        pincode_c: {
            required: "Please enter pincode",
            minlength : "Pincode must be 6 digits long"
        },
    },
    submitHandler: function (form) {
      $('.createAddress_btn').html('Sending...');
      $('.createAddress_btn').attr('disabled', true);
      var name = $('#name_c').val();
      var email = $('#email_c').val();
      var address_type = $('#address_type_c').val();
      var has_default = $('#has_default_c').val();
      var short_address = $('#short_address_c').val();
      var additional_address = $('#additional_address_c').val();
      var pincode = $('#pincode_c').val();
      var phoneno = $('#phoneno_c').val();
      var _token = "{{@csrf_token()}}";

      $.ajax({
          url: "{{url('user/profile/createAddress')}}",
          method: "POST",
          data: {"address_type":address_type,"_token":_token,"has_default":has_default,"short_address":short_address,"additional_address":additional_address,"pincode":pincode,"phoneno":phoneno,"name":name,"email":email},
          dataType: "json",
          success: function (res) {
            $('.createAddress_btn').html('CREATE ADDRESS');
            $('.createAddress_btn').attr('disabled', false);
            alert('new address added');
            location.reload();
          }
          
      });
      return false; 
    }
});
</script>
@endsection