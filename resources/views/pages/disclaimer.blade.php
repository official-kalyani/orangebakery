@extends('layouts.app')
@section('content')
<div class="innerpage-banner">
    <img src="{{ URL::to('/') }}/images/iner-bnr1.jpg" alt="" class="w-100">
    <div class="breadcrum">
        <div class="container">
            <h2>Disclaimer</h2>
            <ul>
                <li>Home  >></li>
                <li><a href="{{url('/disclaimer')}}">Disclaimer</a> </li>
            </ul>
        </div>
    </div>
</div>
@endsection