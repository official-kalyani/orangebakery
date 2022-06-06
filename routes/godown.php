<?php

// Route::get('/home', function () {
//     $users[] = Auth::user();
//     $users[] = Auth::guard()->user();
//     $users[] = Auth::guard('godown')->user();

//     //dd($users);

//     return view('godown.home');
// })->name('home');

Route::get('home','Godown\GodownController@home');
Route::get('delivery-boy','Godown\GodownController@deliveryBoy');
Route::get('change-password','Godown\GodownController@changePassword');
Route::post('changepassword','Godown\GodownController@updatePassword');
Route::get('manage-orders','Godown\GodownController@orderLists');
Route::post('assignDelivery','Godown\GodownController@assignDelivery');
Route::get('order-details','Godown\GodownController@orderDetails');
Route::post('changeOrderStatus','Godown\GodownController@changeOrderStatus');
Route::post('ChangePaidStatus','Godown\GodownController@ChangePaidStatus');

//Notifications

Route::post('mark-read-notifications','Godown\GodownController@markReadNotification');
Route::post('get_refresh','Godown\GodownController@get_refresh');
Route::post('orderAction','Godown\GodownController@orderAction');