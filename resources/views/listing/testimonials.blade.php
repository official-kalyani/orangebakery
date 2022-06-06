@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Testimonials</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/testimonials')}}">Testimonials</a> </li>
            </ul>
        </div>
    </div> 
</div>
<div class="container">
    @foreach($testimonials as $testimonial)
    <hr>
    <div class="row"> 
        <div class="col-xs-12 col-sm-9 col-md-9">
            <h5>{{$testimonial->title}}</h5>
            <p style="color: black; font-size: 19px;"><i>{{$testimonial->description}}</i></p>
        </div> 
        <div class="col-xs-12 col-sm-3 col-md-3">
            <img src="{{ URL::to('/') }}/uploads/testimonials/{{ @$testimonial->image }}" class="img-responsive img-box img-thumbnail"> 
        </div> 
    </div>
    <hr>
    @endforeach
    {{$testimonials->links()}}
</div>
@endsection