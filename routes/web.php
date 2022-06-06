<?php
use Spatie\Sitemap\SitemapGenerator;

Route::get('/', 'ProductsController@index');
Route::get('/category/{slug}', 'ProductsController@categoryWiseCakeListing');
Route::get('/occasion/{slug}', 'ProductsController@occasionWiseCakeListing');
Route::get('/flavour/{slug}', 'ProductsController@subCategoryWiseCakeListing');
Route::get('/products/{slug}', 'ProductsController@productsDetails');
Route::get('/section/{id}', 'ProductsController@section');
Route::get('/blog/{slug}', 'ProductsController@blogDetails');
Route::get('/blog', 'ProductsController@blogListing');
Route::get('/category/{slug}/allproducts', 'ProductsController@categoryAllProducts');

Route::get('/sitemap', function () {
    SitemapGenerator::create('http://orangebakery.in/')->writeToFile('sitemap.xml');
    return "sitemap generated";
});

Route::get('/app-about-us', function () {
    return view('app.app-about-us'); 
});
Route::get('/app-privacy-policy', function () {
    return view('app.app-privacy-policy'); 
});
Route::get('/app-terms-condition', function () {
    return view('app.app-terms-condition'); 
});
Route::get('/testimonials', 'HomeController@testimonials');

Route::get('/contact', 'HomeController@contact');
Route::get('/disclaimer', 'HomeController@disclaimer');
Route::get('/privacy-policy', 'HomeController@privacyPolicy');
Route::get('/faq', 'HomeController@faq');
Route::get('/terms-condition', 'HomeController@termsCondition');
Route::get('/feedback', 'HomeController@feedback');
Route::get('/forgot', 'HomeController@forgot');

Route::post('update-sidebar-cart','ProductsController@updatesidebar');

Route::get('user/coins','UserController@coins');
Route::post('user/profile/fetchCheckoutAddress','UserController@fetchCheckoutAddress');
Route::post('user/profile/createAddress','UserController@createAddress');
Route::post('user/profile/fetchAddress','UserController@fetchAddress');
Route::post('user/profile/updateAddress','UserController@updateAddress');
Route::post('updateProfile','UserController@updateProfile');
Route::post('updateProfilePicture','UserController@updateProfilePicture');
Route::post('rateProduct','ProductsController@rateProduct');
Route::get('/user/orders','UserController@order');
Route::get('/user/orderdetails','UserController@orderdetails');
Route::get('/user/profile','UserController@profile');
Route::post('removeCustomImage','ProductsController@removeCustomImage');
Route::post('deleteCustomizedImage','ProductsController@deleteCustomizedImage');
Route::post('subscribeNewsLetter','HomeController@subscribeNewsLetter');
Route::get('customize','ProductsController@customize');
Route::post('saveCustomizedImage','ProductsController@saveCustomizedImage');
Route::post('uploadCustomizedImage','ProductsController@uploadCustomizedImage');
Route::post('saveContact','HomeController@saveContact');
Route::get('cart', 'ProductsController@cart')->name('cart');
Route::post('redeemCoins', 'razorpay\PaymentController@redeemCoins')->name('redeemCoins');
Route::get('add-to-cart/{id}', 'ProductsController@addToCart');
Route::get('add-to-cart-main/{id}', 'ProductsController@addToCartMain');
Route::patch('update-cart', 'ProductsController@update');
Route::delete('remove-from-cart', 'ProductsController@remove');
Route::delete('clear-from-cart', 'ProductsController@clearAll');
Route::post('checkCoupon','HomeController@checkCoupon');
Route::post('getDynamicStore','HomeController@getDynamicStore');
Route::post('getDynamicStoreAddress','HomeController@getDynamicStoreAddress');
Route::post('setCookie','HomeController@setCookie');
Route::post('fetchProducts','ProductsController@fetchProducts');
//Payment Gateway
///Route::get('checkout','razorpay\PaymentController@redirectTocheckout')->name('checkout');
Route::get('checkout','razorpay\PaymentController@checkout')->name('checkout');
Route::post('checkout','razorpay\PaymentController@checkout');
//Route::post('payment', 'razorpay\PaymentController@payment');
//Route::get('payment', 'razorpay\PaymentController@redirectTocheckout');
Route::post('place-order', 'razorpay\PaymentController@placeOrder');
Route::post('pay-success', 'razorpay\PaymentController@pay_success');
Route::get('thank-you', 'razorpay\PaymentController@thank_you');
//Payment Gateway
Route::post('cancel-order','ProductsController@cancelOrder');
Route::post('validateForgotPasswordOtp','HomeController@validateForgotPasswordOtp');
Route::post('ForgotPasswordOtp','HomeController@ForgotPasswordOtp');
Route::post('changePassword','HomeController@changePassword');
Route::post('loginWithPassword','HomeController@loginWithPassword');
Route::post('loginWithOTP','HomeController@loginWithOTP');
Route::post('ForgotUserOtp','HomeController@ForgotUserOtp');
Route::post('resendOtp','HomeController@resendOtp');
Route::post('signupResendOtp','HomeController@signupResendOtp');
Route::post('validateOtp','HomeController@validateOtp');
Route::post('userSignUp','HomeController@userSignUp');
Route::any('emailcheck', 'HomeController@checkEmailExistence')->name('email.check');

Route::get('login/{service}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{service}/callback', 'Auth\LoginController@handleProviderCallback');

Route::group(['prefix' => 'admin'], function () {
  Route::get('/', 'AdminAuth\LoginController@showLoginForm');
  Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'AdminAuth\LoginController@login');
  Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'AdminAuth\RegisterController@register');

  Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'shop'], function () {
  Route::get('/login', 'ShopAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'ShopAuth\LoginController@login');
  Route::post('/logout', 'ShopAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'ShopAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'ShopAuth\RegisterController@register');

  Route::post('/password/email', 'ShopAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'ShopAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'ShopAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'ShopAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'godown'], function () {
  Route::get('/login', 'GodownAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'GodownAuth\LoginController@login');
  Route::post('/logout', 'GodownAuth\LoginController@logout')->name('logout');

  Route::get('/register', 'GodownAuth\RegisterController@showRegistrationForm')->name('register');
  Route::post('/register', 'GodownAuth\RegisterController@register');

  Route::post('/password/email', 'GodownAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'GodownAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'GodownAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'GodownAuth\ResetPasswordController@showResetForm');
});
