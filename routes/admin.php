<?php

// Route::get('/home', function () {
//     $users[] = Auth::user();
//     $users[] = Auth::guard()->user();
//     $users[] = Auth::guard('admin')->user();

//     //dd($users);

//     return view('admin.home');
// })->name('home');

Route::get('home','Admin\AdminController@home');
Route::resource('products','Admin\ProductsController');



Route::get('products/create/step2','Admin\ProductsController@step2');
Route::post('products/saveStep2','Admin\ProductsController@saveStep2');
Route::post('products/updatePriceWeight','Admin\ProductsController@updatePriceWeight');
Route::post('products/saveFlavours','Admin\ProductsController@saveFlavours');
Route::post('products/deleteFlavour','Admin\ProductsController@deleteFlavour');
Route::post('products/deletePriceWeight','Admin\ProductsController@deletePriceWeight');
Route::post('products/storeMultipleImages','Admin\ProductsController@storeMultipleImages');
Route::post('products/delete-multiple-image','Admin\ProductsController@deleteMultipleImages');
Route::get('change-password','Admin\AdminController@changePassword');
Route::post('changepassword','Admin\AdminController@updatePassword');
Route::post('products/makeFeatureImage','Admin\ProductsController@makeFeatureImage');
Route::post('products/makeCustomizeImage','Admin\ProductsController@makeCustomizeImage');
Route::post('products/makeCustomizeFlavor','Admin\ProductsController@makeCustomizeFlavor');
Route::resource('cakes','Admin\CakeController');
Route::resource('delivery-boy','Admin\DeliveryBoyController');
Route::resource('deliverycharge','Admin\DeliveryChargeController');
Route::get('cakes/create/step2','Admin\CakeController@step2');
Route::post('cakes/addMorePriceWeight','Admin\CakeController@addMorePriceWeight');
Route::post('cakes/storeMultipleImages','Admin\CakeController@storeMultipleImages');
Route::post('cakes/delete-multiple-image','Admin\CakeController@deleteMultipleImages');
Route::post('cakes/makeFeatureImage','Admin\CakeController@makeFeatureImage');
Route::post('cakes/updatePriceWeight','Admin\CakeController@updatePriceWeight');
Route::post('cakes/deletePriceWeight','Admin\CakeController@deletePriceWeight');
Route::resource('shops','Admin\ShopsController');
Route::resource('occasion','Admin\OccasionController');
Route::resource('flavour','Admin\FlavourController');
Route::resource('orders','Admin\OrdersController');
Route::get('orderDetails','Admin\OrdersController@orderDetails');
Route::post('assignDelivery','Admin\OrdersController@assignDelivery');
Route::post('changeOrderStatus','Admin\OrdersController@changeOrderStatus');
Route::resource('category','Admin\CategoryController');
Route::get('category/create/step2','Admin\CategoryController@step2');
Route::post('category/delete-category-sliders','Admin\CategoryController@deleteSliders');
Route::post('category/multipleimage','Admin\CategoryController@multipleimage');
Route::resource('subcategory','Admin\SubcategoryController');
Route::get('subcategory/create/step2','Admin\SubcategoryController@step2');
Route::resource('cake-category','Admin\CakeCategoryController');
Route::resource('cake-subcategory','Admin\CakeSubcategoryController');
Route::resource('coupons','Admin\CouponsController');
Route::resource('additional-products','Admin\AdditionalProductsController');
Route::resource('app-section','Admin\AppSectionController');
Route::get('customers','Admin\AdminController@customers');
Route::get('email','Admin\SendsmsController@email');
Route::get('sms','Admin\SendsmsController@sms');
Route::get('push-notification','Admin\SendsmsController@pushNotofication');
Route::post('sendEmails','Admin\SendsmsController@sendEmails');
Route::post('sendSms','Admin\SendsmsController@sendSms');
Route::post('sendNotification','Admin\SendsmsController@sendNotification');
Route::get('contacts','Admin\AdminController@contacts');
Route::post('contacts/destroy','Admin\AdminController@destroyContacts');
Route::get('newsletter','Admin\AdminController@newsletter');
Route::resource('sliders','Admin\SliderController');
Route::resource('login-signup-banner','Admin\LoginSignupBannerController');
Route::post('/sliders/delete-sliders', 'Admin\SliderController@destroy');
Route::post('/sliders/delete-login-signup-sliders', 'Admin\LoginSignupBannerController@destroy');
Route::resource('customize-cakes','Admin\CustomizeCakesController');
Route::post('/customize-shapes/delete-customize-shapes', 'Admin\CustomizeShapeController@destroy');
Route::post('/customize-flavours/delete-customize-flavours', 'Admin\CustomizeFlavourController@destroy');
Route::post('app-section/deleteProductSection','Admin\AppSectionController@deleteSectionProducts');
Route::resource('blog-category','Admin\BlogCategoryController');
Route::resource('blogs','Admin\BlogsController');
Route::resource('manage-obcoins','Admin\ManageObcoinsController');
Route::get('customer-obcoins','Admin\AdminController@customerObcoins');
Route::resource('testimonials','Admin\TestimonialController');
Route::post('testimonials/delete-testimonials','Admin\TestimonialController@deleteTestimonials');
Route::resource('customize-shapes','Admin\CustomizeShapeController');

Route::resource('customize-galleries','Admin\CustomizeGalleryController');
Route::post('customize-galleries/storeMultipleImages','Admin\CustomizeGalleryController@storeMultipleImages');
Route::post('customize-galleries/delete-multiple-image','Admin\CustomizeGalleryController@deleteMultipleImages');
Route::resource('customize-flavours','Admin\CustomizeFlavourController');
Route::resource('customize-colours','Admin\CustomizeColourController');

Route::get('excel', 'Admin\TestimonialController@excel')->name('excel');


