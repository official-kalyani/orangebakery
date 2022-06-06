@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Contact Us</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/contact')}}">Contact Us</a> </li>
            </ul>
        </div>
    </div>
</div>
<section class="contact-page section-b-space">
    <div class="container">
        <div class="row section-b-space my-5">
            <div class="col-lg-7 map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d1605.811957341231!2d25.45976406005396!3d36.3940974010114!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1550912388321" allowfullscreen=""></iframe>
            </div>
            <div class="col-lg-5">
                <div class="contact-right">
                    <ul>
                        <li>
                            <div class="contact-icon"><i class="fas fa-phone-alt"></i>
                                <h6>Contact Us</h6>
                            </div>
                            <div class="media-body">
                                <p>+91 123 - 456 - 7890</p>
                                <p>+86 163 - 451 - 7894</p>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon"><i class="fa fa-map-marker" aria-hidden="true"></i>
                                <h6>Address</h6>
                            </div>
                            <div class="media-body">
                                <p>ABC Complex,Near xyz, New York</p>
                                <p>USA 123456</p>
                            </div>
                        </li>
                        <li>
                            <div class="contact-icon"><i class="fas fa-address-card"></i>
                                <h6>Address</h6>
                            </div>
                            <div class="media-body">
                                <p>Support@Shopcart.com</p>
                                <p>info@shopcart.com</p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <a href="https://api.whatsapp.com/send?phone=8847824505&amp;text=Hi there! I have a question :)" title="click to open whatsapp chat">
                                    <button class="btn btn-success">
                                        <i class="fa fa-whatsapp"></i> Chat Now
                                    </button>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-sm-12">
                <h4>Contact Us</h4>
                @if(Session::has('flash_success'))
                    <div class="alert alert-success">
                          <button type="button" class="close" data-dismiss="alert">×</button>
                      {{ Session::get('flash_success') }}
                    </div>
                @endif
                <form class="theme-form" id="contactform" method="post" action="{{url('saveContact')}}">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter Your firstname">
                        </div>
                        <div class="col-md-6">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter Your lastname">
                        </div>
                        <div class="col-md-6">
                            <label for="phone">Phone number</label>
                            <input type="number" min="0" class="form-control" name="phone" id="phone" placeholder="Enter your phone number">
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Enter your Email">
                        </div>
                        <div class="col-md-12">
                            <label for="message">Write Your Message</label>
                            <textarea id="message" name="message" class="form-control" placeholder="Write Your Message" id="exampleFormControlTextarea1" rows="6"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-solid" type="submit">Send Your Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script>
$("#contactform").validate({
    rules: {
        firstname: {
            required: true,
            minlength: 2,
            lettersonly: true
        },
        lastname: {
            required: true,
            minlength: 2,
            lettersonly: true
        },
        email: {
            required: true,
            mail: true,
        },
        phone:{
          required: true,
          lettersonly: false 
        },
        // message: {
        //     required: true,
        //     minlength: 2,
        // },
    },
    messages: {
        firstname: {
            required: "Please enter your firstname",
            minlength: "Your firstname must be at least 2 characters long"
        },
        lastname: {
            required: "Please enter your lastname",
            minlength: "Your lastname must be at least 2 characters long"
        },
        email: {
            required: "Please enter your email address",
            mail: 'Please enter a valid email address'
        },
        phone: {
            required: "Please enter phone number",
        },
        // message: {
        //     required: "Please enter message"
        // }
    },
    submitHandler: function (form) {
      $('#submit').html('Sending...');
      $('#submit').attr('disabled', true);
      var fullname = $('#fullname').val();
      var email = $('#email').val();
      var phone = $('#phone').val();
      var subject = $('#subject').val();
      var message = $('#message').val();
      $('#paymentProcessing').modal('show');
      $.ajax({
          type: "POST",
          url: "{{url('/contact-save')}}",
          data: {"fullname":fullname,"email":email,"phone":phone,"subject":subject,"message":message},
          dataType: "json",
          success: function (res) {
              if (res.succ == 1) {
                $('#submit').html('Submit');
                $('#submit').attr('disabled', false);
                $('#fullname').val('');
                $('#email').val('');
                $('#phone').val('');
                $('#subject').val('');
                $('#message').val('');
                $('#remaining_word').html('500');
                $('#paymentProcessing').modal('show');
                window.setTimeout(function () {
                    location.reload();
                }, 5000);
              }
          console.log(data);
          }
          
      });
    }
});
</script>
@endsection