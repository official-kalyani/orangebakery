<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
  
Route::post('signUp','ApiController@signUp');
Route::post('otpVerify','ApiController@otpVerify');
Route::post('login','ApiController@login');  
Route::post('forgotPassword','ApiController@forgotPassword');
Route::post('chanagePassword','ApiController@chanagePassword');
Route::post('updateProfile','ApiController@updateProfile'); 
Route::post('myCoins','ApiController@myCoins');
Route::post('home','ApiController@home');  
Route::post('category','ApiController@category');  
Route::post('subcategory','ApiController@subcategory');  
Route::post('allProducts','ApiController@allProducts');     
Route::post('location','ApiController@location');    
Route::post('allCakes','ApiController@allCakes');    
Route::post('getTestimonials','ApiController@getTestimonials');    
Route::post('subscribeNewsLetter','ApiController@subscribeNewsLetter');    

Route::post('sections','ApiController@sections');
Route::post('productDetails','ApiController@productDetails');    
Route::post('coupon','ApiController@coupon');
Route::post('address','ApiController@address');
Route::post('search','ApiController@search');
Route::post('placeOrder','ApiController@placeOrder');
Route::post('orderHistory','ApiController@orderHistory');
Route::post('orderdetails','ApiController@orderdetails');
Route::post('payment','ApiController@payment');
Route::post('homev2','ApiController@homev2');
Route::post('categoryv2','ApiController@categoryv2');

