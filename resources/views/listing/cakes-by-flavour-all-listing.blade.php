@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>CAKES BY FLAVOUR</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/flavour/all')}}">CAKES BY FLAVOUR</a> </li>
            </ul>
        </div>
    </div>
</div>
<div class="category mt-4">
    <div class="container">
        <div class="row">
           @foreach($subcategories as $subcategory)
                   <div class="col-md-3 col-6 mb-3">
       <div class="c-box text-center">
       <a href="{{url('/')}}/flavour/{{$subcategory->slug}}">
                            <img src="{{ URL::to('/') }}/uploads/category/{{ @$subcategory->image }}" alt="{{$subcategory->name}}" class="w-100">
                    </a>
                    <div  class="m-2 pb-2">
                            <a href="{{url('/')}}/flavour/{{$subcategory->slug}}">  {{$subcategory->name}}
                               </a></div>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
</div>
<div class="download-app">
    <div class="container-fluid">
        <div class="col-md-4">
            <h5>Unlock Exclusive Offers</h5>
            <p>For lightning fast ordering experience download the cake app
            </p>
            <div class="row">
                <div class="col-md-6">
                    <a target="_blank" href="https://play.google.com/store/apps/details?id=com.orangebakeryshop"><img src="{{ URL::to('/') }}/images/gplay.png" alt="" class="img-fluid"></a>   
                </div>
                <!-- <div class="col-md-6">
                    <img src="images/appl.png" alt="" class="w-100">
                </div> -->
            </div>
        </div>
    </div>
</div>
@endsection