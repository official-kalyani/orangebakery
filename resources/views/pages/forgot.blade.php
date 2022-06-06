@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="images/cakes-listing.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Forgot</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="#">Forgot</a> </li>
            </ul>
        </div>
    </div>
</div>
<section class="pwd-page section-b-space my-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 text-center">
                <h2>Forget Your Password</h2>
                <form class="theme-form">
                    <div class="form-row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="email" placeholder="Enter Your Email" required="">
                        </div><a href="#" class="btn btn-solid" style="margin: 0 auto;">Submit</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection