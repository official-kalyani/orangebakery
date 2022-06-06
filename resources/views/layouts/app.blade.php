<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Orange Bakery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png" sizes="25x25">
        <meta name="description" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"  />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('css/sidebar_cart.css') }}">
        <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/wow.js') }}"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
        <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    </head>
    <body>

        <div class="head-section">
            <header>
                <div class="container">
                    <div class="row">
                        <div class="col-md-2 col-xl-2 col-lg-2 col-sm-12 col-12">
                            <a class="navbar-brand" href="{{url('/')}}"><img src="{{ URL::to('/') }}/images/logo.png" alt="logo" class="w-100"></a>
                        </div>
                        <div class="col-md-10 col-xl-10 col-lg-10 col-sm-12  col-12">
                            <nav class="navbar navbar-expand-md navbar-light float-right left-r mt-3">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar2">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="collapsibleNavbar2">
                                    <ul class="navbar-nav navbar-nav mr-auto top-bar">
                                        
                                        @guest
                                        <li class="nav-item"> <div class="searchbox">
                                            <input class="form-control" type="text" placeholder="Search Products" id="search_product_name" autocomplete="off">
                                            <div id="productLists">
                                            </div>
                                        </div>
                                    </li>
                                        <li class="nav-item">
                                            <a type="button" class="nav-link" data-toggle="modal" data-target="#loginModal"> <img src="{{ URL::to('/') }}/images/login.png"> Login</a>
                                        </li>
                                        <li class="nav-item" data-toggle="modal" data-target="#signupModal">
                                            <a type="button" class="nav-link"> <img src="{{ URL::to('/') }}/images/signup.png"> Signup </a>
                                        </li>
                                        @else
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                                {{@Auth::user()->name}}        
                                            </a>
                                            <div style="background-color: #fb6700;" class="dropdown-menu">
                                                <a href="{{url('/user/profile')}}" class="nav-link"> <img src="{{ URL::to('/') }}/images/login.png"> My Profile</a>
                                                <a class="nav-link" href="{{url('/user/orders')}}"> <img src="{{ URL::to('/') }}/images/login.png"> My Orders</a>
                                                <a class="nav-link" href="{{url('/user/coins')}}"> <img src="{{ URL::to('/') }}/images/login.png"> My coins</a>
                                                <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();" class="nav-link"> <img src="{{ URL::to('/') }}/images/signup.png"> Logout </a>
                                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                </form>
                                            </div>
                                        </li>
                                        @endguest
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{url('/cart')}}"> <img src="{{ URL::to('/') }}/images/cart.png" alt="" > Cart (<span id="cartCounter">{{ count((array) session('cart')) }}</span>) </a>
                                        </li>

                                    </ul>

                                </div>  
                            </nav>
                            <div class="clearfix"></div>
                            <nav class="navbar ml-auto navbar-expand-md navbar-light sticky-top right-r float-right">
                              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                              </button>
                              <div class="collapse navbar-collapse" id="main_nav">

                                <ul class="navbar-nav">
                                   <li class="nav-item">
                                        <a class="nav-link" href="/">HOME</a>
                                    </li>
                                    <li class="nav-item dropdown has-megamenu">
                                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"> CAKES  </a>
                                        <div class="dropdown-menu megamenu" id="style-2" role="menu">
                                            <?php 
                                                $allCakeSubcategories = \App\Models\Category::where('parent_id',1)->get(); 
                                            ?>
                                            <div class="row">
                                                @foreach($allCakeSubcategories as $allCakeSubcategory)
                                                <div class="col-md-3" >
                                                    <div class="col-megamenu" style="border-left:#ccc;">
                                                        <ul class="list-unstyled">
<!--                                                             <li><h5>Headline</h5></li>
 -->                                                            <li><a href="{{url('/')}}/flavour/{{$allCakeSubcategory->slug}}">{{$allCakeSubcategory->name}}</a></li>
                                                        </ul>
                                                    </div>  
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown has-megamenu" style="display:none;">
                                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown"> CAKES BY OCASSION  </a>
                                        <div class="dropdown-menu megamenu" id="style-2" role="menu">
                                        <?php $cakeoccasions = \App\Models\Occasion::all(); ?>
                                            <div class="row">
                                                @foreach($cakeoccasions as $cakeoccasion)
                                                <div class="col-md-3">
                                                    <div class="col-megamenu">
                                                        <ul class="list-unstyled">
                                                            <li><a href="{{url('/')}}/occasion/{{$cakeoccasion->slug}}">{{$cakeoccasion->name}}</a></li>
                                                        </ul>
                                                    </div>  
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>
                                    <!-- <li class="nav-item">
                                        <a class="nav-link" href="{{url('flavour/all')}}">FLAVOURS</a>
                                    </li>   -->
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{url('products/all')}}">PRODUCTS</a>
                                    </li>    
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{url('/contact')}}">CONTACT US</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{url('/blog')}}">BLOG</a>
                                    </li>
                                    
                                </ul>
                              
                            </nav>
                        </div>
                      
                        
                    </div>
                </div>
            </header>
        </div>
        @yield('content')
        <div class="subscribe">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <p>Subscribe Our<br/>
                            <span class="large">Newsletter</span></p>
                    </div>
                    <div class="col-md-7 offset-md-2 mt-2">
                        <form id="subscribeNewsLetterForm">
                        <div class="row">
                            <div class="form-group col-md-8">
                                <input class="form-control mr-sm-2 submail" type="email" placeholder="Enter your mail Id" id="email" name="email" aria-label="Search">
                                <span style="color: white;" id="newslettersubmit_message"></span>
                            </div>
                            <div class="form-group col-md-4">
                                <button class="btn subscribe-btn my-2 newslettersubmit my-sm-0 btn-block" type="submit">Subscribe Now</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="container-fluid">
                <div class="row">
                   
                            <div class="col-md-3 ftr-logo mt-3">
                                <img src="{{ URL::to('/') }}/images/logo.png" alt="logo" class="w-50 mm3">
                            </div>
                      <div class="col-md-6 text-center">
                      <ul class="ftr-link">
                            <li><a href="{{ url('/disclaimer') }}">Disclaimer</a> </li>
                            <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a> </li>
                            <li><a href="{{ url('/faq') }}">Faq</a> </li>
                            <li><a href="{{ url('/terms-condition') }}">Terms & Conditions</a> </li>
                            <li><a href="{{ url('/feedback') }}"> Feedback</a> </li>
                        </ul>
                        <div class="social">
                            <h5>Follow Us:<a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a> <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a> <a target="_blank" href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a></h5>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <h5 class="ftr-logo col-12 col-lg-12 col-sm-12 col-xl-12">Download Now :</h5>
                        <a target="_blank" href="https://play.google.com/store/apps/details?id=com.orangebakeryshop"><img src="{{ URL::to('/') }}/images/gplay.png" alt="" class="img-fluid"></a>    
                    </div>
                      
                    </div>
                    <div class="text-center">
                    <p>Copyright&copy; 2020 <a href="https://orangebakery.in/">Orangebakery</a>  |   All right Reserved   | Crafted With Perfection by <a href="https://www.rixosys.com/" target="_blank">Rixosys</a> </p>

                    </div>
                 
                </div>
            </div>
            @if(Route::current()->getName() != 'cart' && Route::current()->getName() != 'checkout')
                @include('layouts.sidebar_cart')
            @endif             
        </footer>
        
    </body>
<!-- loginModal -->
<div class="modal fade" id="loginModal" role="dialog">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-body">
        <section class="login-page section-b-space my-2">
        <div class="container">
            <div class="row" id="loginModal_content">
                <div class="col-lg-12">
                    <div id="login_sliders" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <?php $banner=0; ?>
                            <?php $login_sliders = App\Models\LoginSignupBanner::all(); ?>
                            @foreach($login_sliders as $login_slider)
                                <li data-target="#login_sliders" data-slide-to="{{$banner++}}" @if($banner == 0) class="active" @endif ></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            <?php $banner1=0; ?>
                            @foreach($login_sliders as $login_slider)
                            <?php $banner1++; ?>
                            <div class="carousel-item @if($banner1 == 1) active @else @endif">
                                <img class="d-block w-100" src="{{ URL::to('/') }}/uploads/sliders/{{ @$login_slider->image }}" alt="First slide">
                            </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#login_sliders" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#login_sliders" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                <div class="theme-card">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#loginWithPasswordTab">Login With Password</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#loginWithOtpTab">Login with OTP</a>
                        </li>
                    </ul>
                    <!-- Tab panes -->
                  <div class="tab-content">
                    <div id="loginWithPasswordTab" class="container tab-pane active"><br>
                    <form id="loginWithPassword" class="theme-form">
                        <div class="form-group">
                            <label for="login_email" class="col-form-label text-md-right">{{ __('E-Mail') }} / {{ __('Phone') }} <span class="error">*</span></label>
                            <input id="login_email" placeholder="Enter Phone or Email Address" type="text" class="form-control" name="login_email">
                        </div>

                        <div class="form-group">
                            <label for="login_password" class="col-form-label text-md-right">{{ __('Password') }} <span class="error">*</span></label>
                            <input id="login_password" placeholder="Enter Password" type="password" class="form-control" name="login_password">
                        </div>
                        <span style="color: red;" id="login_message"></span>
                        <div class="clearfix">
                            <label class="float-left form-check-label"><input type="checkbox"> Remember me</label>
                            <a href="#" id="forgot_password" class="float-right">Forgot Password?</a>
                        </div>
                                            
                        <div class="form-group mb-0">
                            <div class="col-form-label">
                                <button type="submit" id="submit" class="btn submit cart-btn form-control">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </div>
                        <div class="or-seperator"><i>or</i></div>
                        <p class="text-center">Login with your social media account</p>
                        <div class="text-center social-btn">
                           <!--  <a href="{{url('login/facebook')}}" class="btn btn-primary"><i class="fa fa-facebook"></i>&nbsp; Facebook</a> -->
                            <a href="{{url('login/google')}}" class="btn btn-danger"><i class="fa fa-google"></i>&nbsp; Google</a>
                        </div>
                        <p class="text-center text-muted small">Don't have an account? <a href="#" id="createAnAccount">Sign up here!</a></p>
                    </form>
                    </div>
                    <div id="loginWithOtpTab" class="container tab-pane fade"><br>
                        <form id="loginWithOTP" class="theme-form">
                            <div class="form-group">
                                <label for="login_email" class="col-form-label text-md-right">{{ __('E-Mail') }} / {{ __('Phone') }} <span class="error">*</span></label>
                                <input id="login_email_phone" placeholder="Enter Phone or Email Address" type="text" class="form-control" name="login_email_phone">
                            </div>
                            <span style="color: red;" id="loginOtp_message"></span>
                            <div class="form-group mb-0">
                                <div class="col-form-label">
                                    <button type="submit" id="submit" class="btn cart-btn loginWithOTPsubmit form-control">
                                        {{ __('Send OTP') }}
                                    </button>
                                </div>
                            </div>
                            <div class="or-seperator"><i>or</i></div>
                            <p class="text-center">Login with your social media account</p>
                            <div class="text-center social-btn">
                                <!-- <a href="{{url('login/facebook')}}" class="btn btn-secondary"><i class="fa fa-facebook"></i>&nbsp; Facebook</a> -->
                                <a href="{{url('login/google')}}" class="btn btn-danger"><i class="fa fa-google"></i>&nbsp; Google</a>
                            </div>
                            <p class="text-center text-muted small">Don't have an account? <a href="#" id="createAnAccount">Sign up here!</a></p>
                        </form>
                        <form id="validateLoginOtp">
                            <div class="form-group">
                                <label for="otp" class="col-form-label text-md-right">{{ __('Enter OTP') }} <span class="error">*</span></label>
                                <input id="otp" type="number" placeholder="Enter 6 digit otp" class="form-control" min="0" name="otp">
                                <input type="hidden" id="otp_validate_user_id">
                                <input type="hidden" id="sending_id">
                                <span id="otp_message" style="color: green;"></span>
                            </div>
                            <div class="form-group">
                                <a id="resendOtp" href="" style="color: red;">Resend OTP</a>
                                <div style="color: red;" id="countdown_left_loginwithotp"></div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="col-form-label">
                                    <button type="submit" id="submit" class="btn cart-btn validateLoginOtp_btn form-control">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form> 
                    </div>
                  </div>
                </div>
                </div>
            </div>
            <div class="row" id="forgot_password_modal_content">
                <div class="col-lg-12">
                    <div class="theme-card">
                        <form id="forgot_password_form" class="theme-form">
                            <div class="form-group">
                                <label for="forgot_email_phone" class="col-form-label text-md-right">{{ __('E-Mail') }} / {{ __('Phone') }} <span class="error">*</span></label>
                                <input id="forgot_email_phone" placeholder="Enter Phone or Email Address" type="text" class="form-control" name="forgot_email_phone">
                            </div>
                            <span style="color: red;" id="forgotpassword_message"></span>
                            <div class="form-group mb-0">
                                <div class="col-form-label">
                                    <button type="submit" id="submit" class="btn cart-btn forgot_password_form_submit form-control">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form id="validateForgotpasswordOtp">
                            <div class="form-group">
                                <label for="otp" class="col-form-label text-md-right">{{ __('Enter OTP') }} <span class="error">*</span></label>
                                <input id="forgotpassword_otp" type="number" placeholder="Enter 6 digit otp" class="form-control" min="0" name="otp">
                                <input type="hidden" id="forgotpassword_validate_user_id">
                                <input type="hidden" id="forgotpassword_sending_id">
                                <span id="validateForgotPasswordOtp_message" style="color: green;"></span>
                            </div>
                            <div class="form-group">
                                <div style="color: red;" id="countdown_left_forgotpassword_resendOtp"></div>
                                <a id="forgotpassword_resendOtp" href="" style="color: red;">Resend OTP</a>
                            </div>
                            <div class="form-group mb-0">
                                <div class="col-form-label">
                                    <button type="submit" id="submit" class="btn cart-btn validateForgotpasswordOtp_btn form-control">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form> 
                        <form id="changePasswordForm">
                            <input type="hidden" id="forgot_password_user_id">
                            <div class="form-group">
                                <label for="new_password" class="col-form-label text-md-right">{{ __('New Password') }} <span class="error">*</span></label>
                                <input id="new_password" placeholder="Enter New Password" type="password" class="form-control" name="new_password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_new_password" class="col-form-label text-md-right">{{ __('Confirm Password') }} <span class="error">*</span></label>
                                <input id="confirm_new_password" placeholder="Confirm Password" type="password" class="form-control" name="confirm_new_password">
                            </div>
                            <span id="change_password_message"></span>
                            <div class="form-group mb-0">
                                <div class="col-form-label">
                                    <button type="submit" id="submit" class="btn cart-btn changePasswordForm_btn form-control">
                                        {{ __('Change Password') }}
                                    </button>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
    <div class="modal-footer" style="display: block;">
        <div class="col-3">
            <button  type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
     
     </div>
  </div>
</div>
</div>
<!-- loginModal -->
<!-- signupModal -->
  <div class="modal fade" id="signupModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
            <section class="register-page section-b-space my-2">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="signup_sliders" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <?php $banner=0; ?>
                                    <?php $signup_sliders = App\Models\LoginSignupBanner::all(); ?>
                                    @foreach($signup_sliders as $signup_slider)
                                        <li data-target="#signup_sliders" data-slide-to="{{$banner++}}" @if($banner == 0) class="active" @endif ></li>
                                    @endforeach
                                </ol>
                                <div class="carousel-inner">
                                    <?php $banner1=0; ?>
                                    @foreach($signup_sliders as $signup_slider)
                                    <?php $banner1++; ?>
                                    <div class="carousel-item @if($banner1 == 1) active @else @endif">
                                        <img class="d-block w-100" src="{{ URL::to('/') }}/uploads/sliders/{{ @$signup_slider->image }}" alt="First slide">
                                    </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#signup_sliders" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#signup_sliders" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <div class="theme-card">
                                <form id="signUpForm" class="theme-form">
                                    <div class="form-group">
                                        <label for="name" class="col-form-label text-md-right">{{ __('Name') }} <span class="error">*</span></label>
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter Full Name">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone" class="col-form-label text-md-right">{{ __('Phone') }} <span class="error">*</span></label>
                                                <input id="phone" type="text" class="form-control" maxlength="10" name="phone" placeholder="Enter Phone" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email_id" class="col-form-label text-md-right">{{ __('E-Mail Address') }} <span class="error">*</span></label>
                                                <input id="email_id" type="email" class="form-control" name="email_id" placeholder="Enter Email Address">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password" class="col-form-label text-md-right">{{ __('Password') }} <span class="error">*</span></label>
                                                <input id="password" type="password" class="form-control" name="password" placeholder="Enter Password">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cpassword" class="col-form-label text-md-right">{{ __('Confirm Password') }} <span class="error">*</span></label>
                                                <input id="cpassword" placeholder="Enter Password" type="password" class="form-control" name="cpassword">
                                            </div>
                                        </div>
                                    </div>
                                    <span style="color: red;" id="register_message"></span>
                                    <div class="form-group mb-0">
                                        <div class="col-form-label">
                                            <button type="submit" class="btn submit cart-btn">
                                                {{ __('Register') }}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="or-seperator"><i>or</i></div>
                                    <p class="text-center">Signup with your social media account</p>
                                    <div class="text-center social-btn">
                                       <!--  <a href="{{url('login/facebook')}}" class="btn btn-secondary"><i class="fa fa-facebook"></i>&nbsp; Facebook</a> -->
                                        <a href="{{url('login/google')}}" class="btn btn-danger"><i class="fa fa-google"></i>&nbsp; Google</a>
                                    </div>
                                    <p class="text-center text-muted small">Already Have an account? <a href="#" class="loginModal">Login in here!</a></p>
                                </form>

                                <form id="EnterSignupOtp">
                                    <div class="form-group">
                                        <label for="otp" class="col-form-label text-md-right">{{ __('Enter OTP') }}</label>
                                        <input id="signupotp" type="number" placeholder="Enter 6 digit otp" class="form-control" min="0" name="otp">
                                        <input type="hidden" id="signup_otp_validate_user_id">
                                        <span id="signup_otp_message" style="color: green;"></span>
                                    </div>
                                    <div class="form-group">
                                        <a id="signupResendOtp" href="" style="color: red;">Resend OTP</a>
                                        <div style="color:red;" id="countdown_left_signupResendOtp"></div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div class="col-form-label">
                                            <button type="submit" class="btn submit cart-btn form-control">
                                                {{ __('Submit') }}
                                            </button>
                                        </div>
                                    </div>
                                </form> 
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="modal-footer" style="display: block;">
            <div class="col-3">
                <button  type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
         
         </div>
      </div>
    </div>
  </div>
<!-- signupModal --> 
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5e3be59ea89cda5a1884772a/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
@yield('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script>
$(document).ready(function(){


 //    $('body').click(function(){
        
 //        // alert();
 //        // if($('.cd-cart-container::before').hasClass('cart-open')) {
 //        // if($('.cd-cart-container').hasClass('cart-open')) {
 //        //    $('.cd-cart-container').removeClass('cart-open');
          
 //        //     console.log("removed");
 //        // } 
 //        // if($('.cd-cart-container').hasClass('cart-open'))
 //        // {
 //        //     $('.cd-cart-container').removeClass('cart-open');
 //        // }
 //        var cartWrapper = $('.cd-cart-container');
 //        var productId = 0;
    
 //        if( cartWrapper.length > 0 ) {
 //            //store jQuery objects
 //            var cartTrigger = cartWrapper.children('.cd-cart-trigger');
 //            //open/close cart
 //            cartTrigger.on('click', function(event){
 //                event.preventDefault();
 //                toggleCart();
 //            });
 //        }
 //    // $('document').click( function() {
       
 // //     alert('fff');
        
 // //    });
 //        function toggleCart(bool) {
 //            var cartIsOpen = ( typeof bool === 'undefined' ) ? cartWrapper.hasClass('cart-open') : bool;
            
 //            if( cartIsOpen ) {
 //                $('.cd-cart-container').addClass('no-before');
 //                cartWrapper.removeClass('cart-open');
 //            } else {
 //                cartWrapper.addClass('cart-open');
 //                //$('.cart-open .cd-cart .checkout em').css('font-size: 17px !important;');
 //            }
 //        }
 //    });
    $(".obcart").click(function(){
        var id = $(this).data('id');
        var price_id = $(this).data('priceid');
        var title = $(this).data('title');
        var message = '';
        var ele = $(this);
        $.ajax({
                url: '{{ url('add-to-cart') }}' + '/' + id,
                method: "get",
                data: {_token: '{{ csrf_token() }}',"price_id":price_id, "message":message},
                dataType: "json",
                success: function (response) {
                    ele.siblings('.btn-loading').hide();
                    Swal.fire(
                      'Added!',
                      title+ ' added to your cart',
                      'success'
                    )
                    $("#cartCounter").html(response.cartCounter);
                    $("#refresh-sidebar-cart").html(response.data);
                    $(".sidebar_cartCounter").html(response.cartCounter);
                }
            });
    });
    $(".addon_obcart").click(function(){
        var id = $(this).data('id');
        var price_id = $(this).data('priceid');
        var title = $(this).data('title');
        var message = '';
        var ele = $(this);
        $.ajax({
                url: '{{ url('add-to-cart') }}' + '/' + id,
                method: "get",
                data: {_token: '{{ csrf_token() }}',"price_id":price_id, "message":message},
                dataType: "json",
                success: function (response) {
                    Swal.fire({
                        title: 'Added',
                        text: title+ ' added to your cart',
                        icon: 'success',
                        confirmButtonColor: '#fb6700',
                        confirmButtonText: 'Continue'
                      }).then((result) => {
                           window.location.reload(true)
                      });
                }
            });
    });
    $(".obBuyNow").click(function(){
        var id = $(this).data('id');
        var price_id = $(this).data('priceid');
        var title = $(this).data('title');
        var message = '';
        var ele = $(this);
        $.ajax({
                url: '{{ url('add-to-cart') }}' + '/' + id,
                method: "get",
                data: {_token: '{{ csrf_token() }}',"price_id":price_id, "message":message},
                dataType: "json",
                success: function (response) {
                    ele.siblings('.btn-loading').hide();
                    Swal.fire(
                      'Added!',
                      title+ ' Added to Cart',
                      'success'
                    )
                    
                    $("#cartCounter").html(response.cartCounter);
                    $("#refresh-sidebar-cart").html(response.data);
                    $(".sidebar_cartCounter").html(response.cartCounter);
                    setTimeout(function(){ location.href="/checkout"; }, 3000);
                    
                }
            });
    });
});
</script>
<script>
$(document).ready(function() {
    $(document).on('click', '.dropdown-menu', function (e) {
      e.stopPropagation();
    });
    var prevent_formsubmit = 0;
    $('.prevent_formsubmit').click(function(){
        var prevent_formsubmit = 1;
        $('.hide_payment_div').show();
        $('#hide_payment_div').hide();
    });
    if (prevent_formsubmit == 1){}
    else
    { 
        $('#hide_payment_div').show();
        $('.hide_payment_div').hide();
    }
    var cbcoinPercentage = parseInt(<?php echo CBCOIN_PRECENTAGE ?>);
    var cbcoinPaisa = 0.<?php echo CBCOIN_PAISA ?>;
    $.validator.addMethod("mail", function (value, element) {
          return this.optional(element) || /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,10}|[0-9]{1,3})(\]?)$/.test(value);
      }, "Please enter a correct email address");
      $(".toCpas").on('blur', function () {
         var str = $('.toCpas').text();
          $('.toCpas').text(str.charAt(0).toUpperCase() + str.substr(1).toLowerCase())
      });
      
      jQuery.validator.addMethod("alphaNumeric", function (value, element) {
          return this.optional(element) || /^(?=\D*\d)(?=[^a-z]*[a-z])(?=.*[.@,])[0-9a-z.@,]+$/i.test(value);
      }, "Password field must be minimum 8 character length with at least 1 number and 1 alphabet and 1 special characters from ,.@");

      //  jQuery.validator.addMethod("special", function (value, element) {
      //     return this.optional(element) || /^(?=[@,.])+$/i.test(value);
      // }, "Password field contain special character ,@.");
    //   jQuery.validator.addMethod("specialChars", function( value, element ) {
    //     var regex = new RegExp("^[@,.]+$");
    //     var key = value;

    //     if (!regex.test(key)) {
    //        return false;
    //     }
    //     return true;
    // }, "please use @,. characters");
    jQuery.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp("(?!^[0-9]*$)(?!^[a-zA-Z]*$)^([a-zA-Z0-9.,@]{2,})$");
            return this.optional(element) || re.test(value);
        },
        "Password field must be minimum 8 character length with at least 1 number and 1 alphabet and 1 special characters from ,.@"
        );
    
      jQuery.validator.addMethod("lettersonly", function(value, element) 
      {
      return this.optional(element) || /^[a-z," "]+$/i.test(value);
      }, "Letters and spaces only please"); 
      $("#signUpForm").validate({
        rules: {
            name: {
                required: true,
                minlength: 2,
                lettersonly: true
            },
            email_id: {
                required: true,
                mail: true,
            },
            phone:{
              required: true,
              minlength: 10,
              maxlength: 10, 
            },
            password: {
                required: true,
                minlength: 8,
                alphaNumeric: true,
                

            },
            cpassword : {
                required: true,
                equalTo : "#password"
            },
        },
        messages: {
            name: {
                required: "Please enter your full name",
                minlength: "Your full name must be at least 2 characters long"
            },
            email_id: {
                required: "Please enter your email address",
                mail: 'Please enter a valid email address'
            },
            phone: {
                required: "Please enter phone number",
                minlength: "Phone number must be 10 digits",
                maxlength: "Phone number must be 10 digits",
            },
            password: {
                    required: "Please provide a password",
                    minlength: "Password field must be minimum 8 character length with at least 1 number and 1 alphabet",
                    alphaNumeric: "Password field must be minimum 8 character length with at least 1 number and 1 alphabet and 1 special characters from ,.@"
                    // specialChars : "Password field must contain special characters"
                },
            cpassword: "Confirm password should be same as password",
        },
        submitHandler: function (form) {
          $('#register_message').html('');
          $('.submit').html('Sending...');
          $('.submit').attr('disabled', true);
          var name = $('#name').val();
          var email = $('#email_id').val();
          var phone = $('#phone').val();
          var password = $('#password').val();
            $.ajaxSetup({
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
             }); 
          $.ajax({
              url: "{{url('/userSignUp')}}",
              method: "POST",
              data: {"name":name,"email":email,"phone":phone,"password":password},
              dataType: "json",
              success: function (res) {
                  if (res.status == "success") {
                    $('.submit').html('REGISTER');
                    $('.submit').attr('disabled', false);
                    $('#signUpForm').hide();
                    $('#EnterSignupOtp').show();
                    var timeLeft = 30;
                    var elem = document.getElementById('countdown_left_signupResendOtp');
                    var timerId = setInterval(countdown, 1000);

                    function countdown() {
                        if (timeLeft == -1) {
                            clearTimeout(timerId);
                            $('#signupResendOtp').show();
                            $('#countdown_left_signupResendOtp').hide();
                        } else {
                            elem.innerHTML = 'If You have not received otp. Then you can request a new one after ' + timeLeft + ' Seconds';
                            timeLeft--;
                        }
                    }
                    $('#signupResendOtp').hide();
                    $('#countdown_left_signupResendOtp').show();
                    $('#signup_otp_validate_user_id').val(res.user_id);
                  }
                  else{
                    $('#register_message').html(res.message);
                    $('.submit').html('REGISTER');
                    $('.submit').attr('disabled', false);
                  }
              
              }
              
          });
          return false; 
        }
    });
    $("#EnterSignupOtp").validate({
        rules: {
            otp: {
                required: true,
                minlength: 6,
                maxlength: 6,
                lettersonly: false
            },
        },
        messages: {
            otp: {
                required: "Please enter otp you have received",
                minlength: "Otp Must be 6 digit in minimum",
                maxlength: "Otp Must be 6 digit in maximum",
                lettersonly: "Otp must be numeric"
            },
        },
        submitHandler: function (form) {
          $('.submit').html('Sending...');
          $('.submit').attr('disabled', true);
          var signup_otp_validate_user_id = $('#signup_otp_validate_user_id').val();
          var signupotp = $('#signupotp').val();
          var _token = "{{@csrf_token()}}";
          $.ajax({
              url: "{{url('validateOtp')}}",
              method: "POST",
              data: {"user_id":signup_otp_validate_user_id,"otp":signupotp,"_token":_token},
              dataType: "json",
              success: function (res) {
                $('.submit').html('REGISTER');
                $('.submit').attr('disabled', false);
                $('#signup_otp_message').html('');
                if (res.status == 1) {
                  $('#signup_otp_message').html(res.message);
                  location.reload();
                }
                else{
                  $('#signup_otp_message').html(res.message);
                }
              
              }
              
          });
          return false; 
        }
    });
    $("#validateLoginOtp").validate({
        rules: {
            otp: {
                required: true,
                minlength: 6,
                maxlength: 6,
                lettersonly: false
            },
        },
        messages: {
            otp: {
                required: "Please enter otp you have received",
                minlength: "Otp Must be 6 digit in minimum",
                maxlength: "Otp Must be 6 digit in maximum",
                lettersonly: "Otp must be numeric"
            },
        },
        submitHandler: function (form) {
          $('.validateLoginOtp_btn').html('Sending...');
          $('.validateLoginOtp_btn').attr('disabled', true);
          var otp_validate_user_id = $('#otp_validate_user_id').val();
          var otp = $('#otp').val();
          var _token = "{{@csrf_token()}}";
          $.ajax({
              url: "{{url('validateOtp')}}",
              method: "POST",
              data: {"user_id":otp_validate_user_id,"otp":otp,"_token":_token,},
              dataType: "json",
              success: function (res) {
                $('.validateLoginOtp_btn').html('Submit');
                $('.validateLoginOtp_btn').attr('disabled', false);
                $('#otp_message').html('');
                if (res.status == 1) {
                  $('#otp_message').html(res.message);
                  location.reload();
                }
                else{
                  $('#otp_message').html(res.message);
                }
              
              }
              
          });
          return false; 
        }
    });
    $("#validateForgotpasswordOtp").validate({
        rules: {
            forgotpassword_otp: {
                required: true,
                minlength: 6,
                maxlength: 6,
                lettersonly: false
            },
        },
        messages: {
            forgotpassword_otp: {
                required: "Please enter otp you have received",
                minlength: "Otp Must be 6 digit in minimum",
                maxlength: "Otp Must be 6 digit in maximum",
                lettersonly: "Otp must be numeric"
            },
        },
        submitHandler: function (form) {
          $('.validateForgotpasswordOtp_btn').html('Sending...');
          $('.validateForgotpasswordOtp_btn').attr('disabled', true);
          var otp_validate_user_id = $('#forgotpassword_validate_user_id').val();
          var otp = $('#forgotpassword_otp').val();
          //var sending_id = $('#forgotpassword_sending_id').val();
          var _token = "{{@csrf_token()}}";
          $.ajax({
              url: "{{url('validateForgotPasswordOtp')}}",
              method: "POST",
              data: {"user_id":otp_validate_user_id,"otp":otp,"_token":_token},
              dataType: "json",
              success: function (res) {
                $('.validateForgotpasswordOtp_btn').html('Submit');
                $('.validateForgotpasswordOtp_btn').attr('disabled', false);
                $('#validateForgotPasswordOtp_message').html('');
                if (res.status == 1) {
                  $('#validateForgotPasswordOtp_message').html(res.message);
                  $('#validateForgotpasswordOtp').hide();
                  $('#changePasswordForm').show();
                  $('#forgot_password_user_id').show();
                  $('#forgot_password_user_id').val(res.user_id);
                  //location.reload();
                }
                else{
                  $('#validateForgotPasswordOtp_message').html(res.message);
                  $('#changePasswordForm').hide();
                }
              
              }
              
          });
          return false; 
        }
    });
     $("#loginWithOTP").validate({
        rules: {
            login_email_phone: {
                required: true,
            },
        },
        messages: {
            login_email_phone: {
                required: "Please enter phone or email address",
            },
        },
        submitHandler: function (form) {
          $('.loginWithOTPsubmit').html('Sending...');
          $('.loginWithOTPsubmit').attr('disabled', true);
          var email = $('#login_email_phone').val();
          var _token = "{{@csrf_token()}}";
          $.ajax({
              url: "{{url('loginWithOTP')}}",
              method: "POST",
              data: {"email":email,"_token":_token},
              dataType: "json",
              success: function (res) {
                $('.loginWithOTPsubmit').html('SEND OTP');
                $('.loginWithOTPsubmit').attr('disabled', false);
                $('#loginOtp_message').html('');
                if (res.status == 1) {
                    var timeLeft = 30;
                    var elem = document.getElementById('countdown_left_loginwithotp');
                    var timerId = setInterval(countdown, 1000);

                    function countdown() {
                        if (timeLeft == -1) {
                            clearTimeout(timerId);
                            $('#resendOtp').show();
                            $('#countdown_left_loginwithotp').hide();
                        } else {
                            elem.innerHTML = 'If You have not received otp. Then you can request a new one after ' + timeLeft + ' Seconds';
                            timeLeft--;
                        }
                    }
                    $('#resendOtp').hide();
                    $('#countdown_left_loginwithotp').show();

                  $('#loginOtp_message').html(res.message);
                  $('#loginWithOTP').hide();
                  $('#validateLoginOtp').show();
                  $('#otp_validate_user_id').val(res.user_id);
                  $('#sending_id').val(res.sending_id);
                }
                else{
                  $('#loginOtp_message').html(res.message);
                  $('#validateLoginOtp').hide();
                }
              
              }
              
          });
          return false; 
        }
    });
    $("#loginWithPassword").validate({
        rules: {
            login_email: {
                required: true,
            },
            login_password: {
                required: true,
                minlength: 8,
            },
        },
        messages: {
            login_email: {
                required: "Please enter phone or email address",
            },
            login_password: {
                required: "Please provide a password",
                minlength: "Password field must be minimum 8 character length",
            },
        },
        submitHandler: function (form) {
          $('.submit').html('Sending...');
          $('.submit').attr('disabled', true);
          var email = $('#login_email').val();
          var password = $('#login_password').val();
          var _token = "{{@csrf_token()}}";
          $.ajax({
              url: "{{url('loginWithPassword')}}",
              method: "POST",
              data: {"email":email,"password":password,"_token":_token},
              dataType: "json",
              success: function (res) {
                $('.submit').html('LOGIN');
                $('.submit').attr('disabled', false);
                $('#login_message').html('');
                if (res.status == 1) {
                  $('#login_message').html(res.message);
                  location.reload();
                }
                else{
                  $('#login_message').html(res.message);
                }
              
              }
              
          });
          return false; 
        }
    });
    $("#changePasswordForm").validate({
        rules: {
            new_password: {
                required: true,
            },
            confirm_new_password: {
                required: true,
                equalTo : "#new_password"
            },
        },
        messages: {
            new_password: {
                required: "Please enter password",
            },
            confirm_new_password: {
                required: "Please enter Confirm password",
            },
        },
        submitHandler: function (form) {
          $('.changePasswordForm_btn').html('Sending...');
          $('.changePasswordForm_btn').attr('disabled', true);
          var password = $('#new_password').val();
          var user_id = $('#forgot_password_user_id').val();
          var _token = "{{@csrf_token()}}";
          $.ajax({
              url: "{{url('changePassword')}}",
              method: "POST",
              data: {"user_id":user_id,"password":password,"_token":_token},
              dataType: "json",
              success: function (res) {
                $('.changePasswordForm_btn').html('Change Password');
                $('.changePasswordForm_btn').attr('disabled', false);
                $('#change_password_message').html('');
                if (res.status == 1) {
                  $('#change_password_message').html(res.message);
                  location.reload();
                }
                else{
                  $('#change_password_message').html(res.message);
                }
              
              }
              
          });
          return false; 
        }
    });
    $('#signupResendOtp').click(function(e){
      e.preventDefault();
      var otp_validate_user_id = $('#signup_otp_validate_user_id').val();
      var _token = "{{@csrf_token()}}";
      $.ajax({
          url: "{{url('signupResendOtp')}}",
          method: "POST",
          data: {"user_id":otp_validate_user_id,"_token":_token},
          dataType: "json",
          success: function (res) {
            $('#otp_message').html('');
            if (res.status == 1) {
                var timeLeft = 30;
                var elem = document.getElementById('countdown_left_signupResendOtp');
                var timerId = setInterval(countdown, 1000);

                function countdown() {
                    if (timeLeft == -1) {
                        clearTimeout(timerId);
                        $('#signupResendOtp').show();
                        $('#countdown_left_signupResendOtp').hide();
                    } else {
                        elem.innerHTML = 'If You have not received otp. Then you can request a new one after ' + timeLeft + ' Seconds';
                        timeLeft--;
                    }
                }
                $('#signupResendOtp').hide();
                $('#countdown_left_signupResendOtp').show();
              $('#otp_message').html(res.message);
            }
            else{
              $('#otp_message').html(res.message);
            }
          
          }
          
      });
    });
    $('#resendOtp').click(function(e){
      e.preventDefault();
      var otp_validate_user_id = $('#otp_validate_user_id').val();
      var _token = "{{@csrf_token()}}";
      var sending_id = $('#sending_id').val();
      $.ajax({
          url: "{{url('resendOtp')}}",
          method: "POST",
          data: {"user_id":otp_validate_user_id,"_token":_token,"sending_id":sending_id},
          dataType: "json",
          success: function (res) {
            $('#otp_message').html('');
            if (res.status == 1) {
                var timeLeft = 30;
                var elem = document.getElementById('countdown_left_loginwithotp');
                var timerId = setInterval(countdown, 1000);

                function countdown() {
                    if (timeLeft == -1) {
                        clearTimeout(timerId);
                        $('#resendOtp').show();
                        $('#countdown_left_loginwithotp').hide();
                    } else {
                        elem.innerHTML = 'If You have not received otp. Then you can request a new one after ' + timeLeft + ' Seconds';
                        timeLeft--;
                    }
                }
              $('#resendOtp').hide();
              $('#countdown_left_loginwithotp').show();
              $('#otp_message').html(res.message);
            }
            else{
              $('#otp_message').html(res.message);
            }
          
          }
          
      });
    });
    $('#forgotpassword_resendOtp').click(function(e){
      e.preventDefault();
      var otp_validate_user_id = $('#forgotpassword_validate_user_id').val();
      var _token = "{{@csrf_token()}}";
      var sending_id = $('#forgotpassword_sending_id').val();
      $.ajax({
          url: "{{url('resendOtp')}}",
          method: "POST",
          data: {"user_id":otp_validate_user_id,"_token":_token,"sending_id":sending_id},
          dataType: "json",
          success: function (res) {
            $('#validateForgotPasswordOtp_message').html('');
            if (res.status == 1) {
                var timeLeft = 30;
                var elem = document.getElementById('countdown_left_forgotpassword_resendOtp');
                var timerId = setInterval(countdown, 1000);

                function countdown() {
                    if (timeLeft == -1) {
                        clearTimeout(timerId);
                        $('#forgotpassword_resendOtp').show();
                        $('#countdown_left_forgotpassword_resendOtp').hide();
                    } else {
                        elem.innerHTML = 'If You have not received otp. Then you can request a new one after ' + timeLeft + ' Seconds';
                        timeLeft--;
                    }
                }
                $('#forgotpassword_resendOtp').hide();
                $('#countdown_left_forgotpassword_resendOtp').show();
              $('#validateForgotPasswordOtp_message').html(res.message);
            }
            else{
              $('#validateForgotPasswordOtp_message').html(res.message);
            }
          
          }
          
      });
    });
    $('#forgot_password').click(function(e){
        e.preventDefault();
      $('#loginModal_content').hide();
      $('#forgot_password_modal_content').show();
      var email = $('#email_phone').val();
      var _token = "{{@csrf_token()}}";
      $.ajax({
          url: "{{url('resendOtp')}}",
          method: "POST",
          data: {"email":email,"_token":_token},
          dataType: "json",
          success: function (res) {
            $('#otp_message').html('');
            if (res.status == 1) {
              $('#otp_message').html(res.message);
              $('#changePasswordForm').show();
            }
            else{
              $('#otp_message').html(res.message);
            }
          
          }
          
      });
    });
    $("#forgot_password_form").validate({
        rules: {
            forgot_email_phone: {
                required: true,
            },
        },
        messages: {
            forgot_email_phone: {
                required: "Please enter phone or email address",
            },
        },
        submitHandler: function (form) {
          $('#forgotpassword_message').html('');
          $('.forgot_password_form_submit').html('Sending...');
          $('.forgot_password_form_submit').attr('disabled', true);
          var email = $('#forgot_email_phone').val();
          var _token = "{{@csrf_token()}}";
          $.ajax({
              url: "{{url('ForgotPasswordOtp')}}",
              method: "POST",
              data: {"email":email,"_token":_token},
              dataType: "json",
              success: function (res) {
                
                if (res.status == 1) {
                $('.forgot_password_form_submit').html('Submit');
                $('.forgot_password_form_submit').attr('disabled', true);
                  $('#forgotpassword_message').html(res.message);
                  $('#forgot_password_form').hide();
                  $('#validateForgotpasswordOtp').show();
                    var timeLeft = 30;
                    var elem = document.getElementById('countdown_left_forgotpassword_resendOtp');
                    var timerId = setInterval(countdown, 1000);

                    function countdown() {
                        if (timeLeft == -1) {
                            clearTimeout(timerId);
                            $('#forgotpassword_resendOtp').show();
                            $('#countdown_left_forgotpassword_resendOtp').hide();
                        } else {
                            elem.innerHTML = 'If You have not received otp. Then you can request a new one after ' + timeLeft + ' Seconds';
                            timeLeft--;
                        }
                    }
                    $('#forgotpassword_resendOtp').hide();
                    $('#countdown_left_forgotpassword_resendOtp').show();
                  $('#forgotpassword_validate_user_id').val(res.user_id);
                  $('#forgotpassword_sending_id').val(res.sending_id);
                }
                else{
                  $('.forgot_password_form_submit').html('Submit');
                  $('.forgot_password_form_submit').attr('disabled', false);
                  $('#forgotpassword_message').html(res.message);
                  $('#validateForgotpasswordOtp').hide();
                }
              
              }
              
          });
          return false; 
        }
    });
    $('#search_product_name').keyup(function(){ 
        var query = $(this).val();
        if(query != '')
        {
         var _token = "{{@csrf_token()}}";
         $.ajax({
          url:"{{ url('/fetchProducts') }}",
          method:"POST",
          data:{query:query, _token:_token},
          success:function(data){
            $('#productLists').show();
            $('#productLists').html(data);
            $("#productLists").css("position","absolute");
            $("#productLists").css("height","300px");
            $("#productLists").css("overflow","scroll");
            $("#productLists").css("z-index","99999");
            $("#productLists").css("background","#fff");
            $("#productLists").css("min-width","300px");
            $("#search_product_name").css("background","#FFF");
          }
         });
        }
        else{
            $('#productLists').hide();
        }
    });

    $(".clear_all").click(function (e) {
            e.preventDefault();
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
              if (result.value) {
                $.ajax({
                        url: '{{ url('clear-from-cart') }}',
                        method: "DELETE",
                        data: {_token: '{{ csrf_token() }}'},
                        dataType: "json",
                        success: function (response) {

                        //$("span#status").html('<div class="alert alert-success">'+response.msg+'</div>');
                        location.reload();
                    }
                });
                Swal.fire(
                  'Deleted!',
                  'All Products Removed form Cart.',
                  'success'
                )
              }
            })
            
    });
    var shipping_charge =  parseInt($('#shipping_charge').val());
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        var parent_row = ele.parents("tr");
        var quantity = parent_row.find(".quantity").val();
        var price_id = parent_row.find(".price_id").val();
        
        //alert(quantity);
        if (quantity>0) {

            var product_subtotal = parent_row.find("span.product-subtotal");

            var cart_total = $(".cart-total");

            var loading = parent_row.find(".btn-loading");

            //loading.show();

            $.ajax({
                url: '{{ url('update-cart') }}',
                method: "patch",
                data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id"), quantity: quantity,price_id:price_id},
                dataType: "json",
                success: function (response) {

                    loading.hide();

                    //$("span#status").html('<div class="alert alert-success">'+response.msg+'</div>');

                    $("#header-bar").html(response.data);

                    product_subtotal.text(response.subTotal);

                    cart_total.html('&#8377;'+response.total);
                    $("#cart-total").val(response.total);
                    $('.couponapplied_msg').hide();
                    $('.couponapplied_msg_price').hide();
                    $('.remove-coupon').hide();
                    $('#errorRefSuccess').hide();
                    $('#couponcheck').val('');
                    $('#coupon_name').val('');
                    $('#coupon_name_text').html('');
                    $('#discount_amount').val('');
                    $('#final_payable_amount').val(parseInt(response.total)+parseInt(shipping_charge));
                    $('#init_payable_price_static').val(parseInt(response.total));
                    $('#total_price_text').html('&#8377;'+$('#final_payable_amount').val()+'.00');
                    $('#coinswillAdded').val(parseInt(response.total)/cbcoinPercentage);
                    $('.coinswillAdded').html(parseInt(response.total)/cbcoinPercentage);
                }
            });
        }
    });

    $(".cancel-order").click(function (e) {
        e.preventDefault();

        var order_id = $('#order_id').val();
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Cancel Order!'
        }).then((result1) => {
            if (result1.value) {
                $.ajax({
                    url: '{{ url('cancel-order') }}',
                    method: "POST",
                    data: {_token: '{{ csrf_token() }}', order_id:order_id},
                    dataType: "json",
                    success: function (response) {
                        if (response.status == 1) {
                            Swal.fire(
                              'Cancelled!',
                              'Your order has been cancelled.',
                              'success'
                            )
                        }
                        if (response.status == 2) {
                             Swal.fire({
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Your order has been accepted. You can not cancel the order now.',
                            })
                        }
                    }
                });
            }
        })
    });

    $(".remove-from-cart").click(function (e) {
        e.preventDefault();

        var ele = $(this);

        var parent_row = ele.parents("tr");

        var cart_total = $(".cart-total");
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $.ajax({
                url: '{{ url('remove-from-cart') }}',
                method: "DELETE",
                data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id")},
                dataType: "json",
                success: function (response) {

                    parent_row.remove();

                    //$("span#status").html('<div class="alert alert-success">'+response.msg+'</div>');

                    $("#header-bar").html(response.data);

                    cart_total.text(response.total);
                    $("#cart-total").val(response.total);
                    $('#total_price_text').html('&#8377;'+response.total+'.00');
                    //$('#errorRefSuccess').html('');
                    //$('.couponapplied_msg').html('');
                    location.reload();
                }
            });
            Swal.fire(
              'Deleted!',
              'Your product has been deleted.',
              'success'
            )
          }
        })
        // if(confirm("Are you sure")) {
            
        // }
    });
    $('#coinswillAdded').val(Math.round(parseInt($('#init_payable_price_static').val())/cbcoinPercentage));
    $('.coinswillAdded').html(Math.round(parseInt($('#init_payable_price_static').val())/cbcoinPercentage));
    
    //Apply Coupon
    var static_coins = parseInt($('#static_coins').val());
    var init_payable_price_static = parseInt($("#init_payable_price_static").val());
    $('#final_payable_amount').val(init_payable_price_static+shipping_charge);
    $('#total_price_text').html('&#8377;'+(init_payable_price_static+shipping_charge)+'.00');


    //Apply Coupon

    //Delivery Type
    $( "#select_delivery_delivery" ).click(function() {
        
        var selected_delivery_type = $( "#select_delivery_delivery" ).val();
        if (selected_delivery_type == "delivery_selected") {
            $("#delivery_type_delivery").prop("checked", true);
            $('#getstorelocation').hide();
            //$('#show_delivery').show();
            $('#show_pick_up').hide();
            var delivery_type = $("input[name=delivery_type]:checked").val();
        }
    });
    $('.select_delivery_takeaway').click(function() {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Can not Change Delivery to Takeaway!',
        })
        $("#select_delivery_delivery").prop("checked", true);
    });
    $( "#select_delivery_takeaway" ).click(function() {
        var selected_delivery_type = $( "#select_delivery_takeaway" ).val();
        if (selected_delivery_type == "takeaway_selected") {
            $("#delivery_type_takeaway").prop("checked", true);
            $('#show_pick_up').show();
            $('#show_delivery').hide();
            $('#getstorelocation').hide();
            var delivery_type = $("input[name=delivery_type]:checked").val();
            var select = "location";
            var value = 1;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ url('getDynamicStore') }}",
                method:"POST",
                data:{select:select, value:value, _token:_token},
                success:function(result)
                {
                 $('#store').html(result);
                }
            });
        }
    });
    $('#select_delivery_delivery').click(function()
    {
        var type = "delivery";
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url:"{{ url('setCookie') }}",
            method:"POST",
            data:{type:type, _token:_token},
            success:function(result)
            {
             location.reload();
            }
        });
    });
    $( ".delivery_type" ).click(function() {
      if ($(this).is(":checked")) {
        $(this).find(':radio').prop('checked', true);
        var delivery_type = $("input[name=delivery_type]:checked").val();
        if (delivery_type == "delivery") {
            $('#getstorelocation').hide();
            $('#show_delivery').show();
            $('#show_pick_up').hide();
            var type = "delivery";
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ url('setCookie') }}",
                method:"POST",
                data:{type:type, _token:_token},
                success:function(result)
                {
                 location.reload();
                }
            });
        }
      }
    });
    $('.dynamic').click(function(){
        if($(this).val() != '')
        {
           $('.spinimg').show();
           var select = $(this).attr("id");
           var value = $(this).val();
           var _token = $('input[name="_token"]').val();
           if (select == "store") {
            $('#getstorelocation').show();
            $.ajax({
                url:"{{ url('getDynamicStoreAddress') }}",
                method:"POST",
                data:{select:select, value:value, _token:_token},
                success:function(result)
                {
                 $('.spinimg').hide();
                 $('#getstorelocation').html(result);
                }
            });
           }
        }
    });
    //Delivery Type

    $('#createAnAccount').click(function(e){
        e.preventDefault();
        $('#loginModal').modal('hide');
        $('#signupModal').modal('show');
    });

     $('.createAnAccount').click(function(e){
        e.preventDefault();
        $('#loginModal').modal('hide');
        $('#signupModal').modal('show');
    });

     $('.loginModal').click(function(e){
        e.preventDefault();
        $('#signupModal').modal('hide');
        $('#loginModal').modal('show');
    });

     

    $("#subscribeNewsLetterForm").validate({
        rules: {
            email: {
                required: true,
                mail: true,
            },
        },
        messages: {
            email: {
                required: "Please enter your email address",
                mail: 'Please enter a valid email address'
            },
        },
        submitHandler: function (form) {
          $('#newslettersubmit_message').html('');
          $('.newslettersubmit').html('Sending...');
          $('.newslettersubmit').attr('disabled', true);
          var email = $('#email').val();
          var _token = "{{@csrf_token()}}";
          $.ajax({
              url: "{{url('subscribeNewsLetter')}}",
              method: "POST",
              data: {"email":email,"_token":_token},
              dataType: "json",
              success: function (res) {
                $('.newslettersubmit').html('Subscribe Now');
                $('.newslettersubmit').attr('disabled', false);
                if (res.status == 1) {
                  $('.newslettersubmit').html('Subscribed');
                  $('.newslettersubmit').attr('disabled', true);
                }
                else if (res.status == 0){
                  $('#newslettersubmit_message').html(res.message);
                }
              
              }
              
          });
          return false; 
        }
    });

});

$(document).ready(function(){
    $('.customer-logos').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });

    $('.flavour-listings').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 5
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });

    $('.category-logos').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: false,
        dots: false,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 6
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });

    

    $('.recent-products').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: true,
        dots: true,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });

    $('.login-signup').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: true,
        dots: true,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });

    $('.best-seller').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: true,
        dots: true,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });

    $('.cake-subcategory-product-listing').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        arrows: true,
        dots: true,
        pauseOnHover: false,
        responsive: [{
            breakpoint: 768,
            settings: {
                slidesToShow: 4
            }
        }, {
            breakpoint: 520,
            settings: {
                slidesToShow: 1
            }
        }]
    });
});
</script>
</html>