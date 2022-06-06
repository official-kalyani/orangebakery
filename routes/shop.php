<?php

// Route::get('/home', function () {
//     $users[] = Auth::user();
//     $users[] = Auth::guard()->user();
//     $users[] = Auth::guard('shop')->user();

//     //dd($users);

//     return view('shop.home');
// })->name('home');

Route::get('home','Store\ProductController@index');
Route::get('dashboard','Store\ProductController@dashboard');

Route::resource('products','Store\ProductController');
Route::get('master-products','Store\ProductController@masterProducts');
Route::get('clone-master-products','Store\ProductController@cloneMasterProducts');
Route::get('my-orders','Store\ProductController@myOrders');
Route::post('assignDelivery','Store\ProductController@assignDelivery');
Route::post('changeOrderStatus','Store\ProductController@changeOrderStatus');
Route::get('order-details','Store\ProductController@orderDetails');

Route::resource('cakes','Store\CakeController');
Route::get('master-cakes','Store\CakeController@masterCakes');
Route::get('clone-master-cakes','Store\CakeController@cloneMasterCakes');
 
Route::get('change-password','Store\ShopController@changePassword');
Route::post('changepassword','Store\ShopController@updatePassword');
