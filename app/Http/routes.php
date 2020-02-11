<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Route::get('/seller', ['uses' => 'HomeController@index']);
//Route::get('/', ['uses' => 'IndexController@index']);

Route::get('/seller/login', ['uses' => 'HomeController@index']);
Route::get('seller/forgot-password', ['uses' => 'HomeController@forgotPassword']);
Route::post('seller/forgot-password', ['uses' => 'HomeController@sellerForgotPassword']);
Route::get('seller/verify-otp-seller', ['uses' => 'HomeController@verifyOtpSeller']);
Route::post('seller/verify-otp-seller', ['uses' => 'HomeController@sellerSendFrogotPass']);
Route::get('/', ['uses' => 'HomeController@home']);
// Authentication Routes...
//Route::get('/login', 'HomeController@login');
Route::get('/logout', 'UserController@logout');
Route::get('/mobile-view-chat', 'UserController@mobileViewChat');
Route::post('/login', 'HomeController@login');
Route::get('/join-as-seller', 'HomeController@join_us');
Route::post('/join-us-store', 'HomeController@join_us_store');
Route::get('/seller-otp', 'HomeController@seller_otp');

Route::post('/get-state-data', 'HomeController@get_state');
Route::post('/get-city-data', 'HomeController@get_city');
Route::get('/seller/logout', 'HomeController@logout');
Route::post('/check-pin-availability',  'HomeController@checkPinAvailability');
Route::get('/admin_001', [ 'uses' => 'LoginController@index']);
Route::post('/login-user', 'UserController@login_user');
Route::post('/register-user', 'UserController@register_user');
Route::post('/register', 'UserController@register_user');
Route::post('/resend-otp-code', 'UserController@resend_otp');
Route::post('/reset-password-verify', 'UserController@sendVerifyLink');
Route::post('/reset-password-verify', 'UserController@sendVerifyLink');
Route::get('/forgot-changepassword/{slug}', 'UserController@forgot_changepassword');
Route::get('/forgot-changepassword', 'UserController@forgot_changepassword');
Route::post('/reset-password', 'UserController@resetPassword');
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::get('/config-clear', function() {
    Artisan::call('config:clear');
    return "config is cleared";
});

/*// Authentication Routes...
Route::get('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout');
Route::post('/login', 'UserController@login_user');
Route::get('/register', 'UserController@register');*/
Route::get('/change-password', 'UserController@change_password');
Route::post('/change-password', 'UserController@update_password');
Route::post('/verify-otp', 'UserController@verify_otp');
Route::post('/verify-login-otp', 'UserController@verify_login_otp');
Route::get('/display-suggestion', 'ProductListingController@display_suggestion');
Route::get('/whats-new/{slug}',  'ProductListingController@whats_new');
Route::get('/search', 'ProductListingController@search_product');
Route::get('/edit-address/{id}', 'DashboardController@edit_address');
Route::post('/update-address/{id}', 'DashboardController@update_address');
Route::get('/update-profile/{id}',  'DashboardController@updateProfile');
Route::post('/update-user-profile',  'DashboardController@updateProfileStore');
Route::get('/refer-earn',  'DashboardController@referEarn');
Route::post('/user/send-email-refer',  'DashboardController@sendEmailRefer');
Route::get('/exchage-item/{id1}/{id2}',  'DashboardController@with_exchage_item');
Route::post('/update-notify-view-status',  'DashboardController@updateNotifyViewStatus');
Route::get('/user/rating/{order_id}/{product_id}',  'DashboardController@rating');
Route::post('/user/update-user-rating-review',  'DashboardController@updateUserRatingReview');
Route::post('/user/user-order-cancle',  'DashboardController@userOrderCancle');


Route::post('/return-now','DashboardController@return_now');
Route::post('/exchange-now','DashboardController@exchange_now');
Route::get('/dashboard-order-invoice/{id}', ['uses' => 'DashboardController@download_invoice']);
Route::get('/add-wallet-amount/{id}', ['uses' => 'DashboardController@addWalletBalance']);
Route::post('/wallet-razorpay-success', ['uses' => 'DashboardController@addWallet']);
Route::post('wallet/payment/status', 'DashboardController@paymentCallback');
Route::get('wallet/payment/status', 'DashboardController@paymentCallback');
Route::get('user-support', 'DashboardController@userSupport');
Route::post('user-support', ['uses' => 'DashboardController@add_support']);
Route::post('send-call-request', ['uses' => 'DashboardController@sendCallRequest']);
//front routes...............
Route::get('/category/{slug1}',  'ProductListingController@index');
Route::get('/category/{slug1}/{slug2}',  'ProductListingController@index');
Route::get('/category/{slug1}/{slug2}/{slug3}',  'ProductListingController@index');
Route::post('/get-product-price',  'ProductListingController@getProductPrice');
Route::post('/get-item-list',  'ProductDetailController@getItemList');
Route::get('/product/{slug1}',  'ProductDetailController@index');
Route::post('/check-delivery',  'ProductDetailController@check_delivery');
Route::post('/product/get-filter-product',  'ProductListingController@filter_product');
Route::post('/product/get-filter-product-type',  'ProductListingController@get_filter_product_type');
Route::post('get-seller-name',  'ProductDetailController@getSellerName');
Route::post('check-item-stock',  'ProductDetailController@checkItemStock');
Route::get('/product-type-list/{type}',  'ProductListingController@productTypeList');
//Cart route
Route::post('/cart/store-cart',  'CartController@addToCart');
Route::post('/cart/get-cart',  'CartController@getAjaxCart');
Route::post('/cart/cart-minus',  'CartController@cartMinus');
Route::post('/cart/cart-plus',  'CartController@cartPlus');
Route::post('/cart/cart-delete',  'CartController@deleteCart');
Route::post('/cart/cart-count',  'CartController@cartCount');
Route::post('/cart/clear-user-cart',  'CartController@clearUserCart');

Route::post('/wishlist-add',  'ProductDetailController@wishlist_add');
Route::get('/my-wishlist',  'DashboardController@wishlist');
Route::post('/update-cart-qty',  'ProductDetailController@update_cart');
Route::post('product/quick-view',  'ProductDetailController@quick_view');
Route::get('/order-view/{id}','DashboardController@my_order_details');


Route::post('/checkout/checkout-total',  'CheckoutController@checkout_total');
Route::get('/checkout-login',  'CheckoutController@login');
Route::post('/checkout-login',  'CheckoutController@authenticate');
Route::get('/checkout-register',  'CheckoutController@register');
Route::post('/checkout-register',  'CheckoutController@register_user');
Route::get('/verify-checkout-otp',  'CheckoutController@verify_checkout_otp');
Route::get('/check-login',  'CheckoutController@check_login');
Route::post('/checkout/checkout-total-minus',  'CheckoutController@checkout_total_minus');
Route::post('/checkout/get-address-by',  'CheckoutController@get_address_by_id');
Route::post('/checkout/edit-address',  'CheckoutController@edit_address');
Route::post('/checkout/get-address',  'CheckoutController@get_address');
Route::post('/checkout/add-address',  'CheckoutController@add_address');
Route::post('/checkout/deliver-type',  'CheckoutController@deliver_type');
Route::post('/checkout/detect',  'CheckoutController@detect');
Route::post('/checkout/deliver-here',  'CheckoutController@deliver_here');
Route::post('/checkout/delete-address',  'CheckoutController@delete_address');
Route::post('/checkout/timeslot',  'CheckoutController@timeslot');
Route::post('payment/status', 'CheckoutController@paymentCallback');
Route::get('payment/status', 'CheckoutController@paymentCallback');
Route::get('success', 'CheckoutController@cod_success');
Route::post('razorpay-success', 'CheckoutController@razorpay_success');
Route::get('razorpay-success', 'CheckoutController@raozarpaySuccessPage');
Route::get('razorpay-faild', 'CheckoutController@raozarpayFaild');

/*//razorpay payment..........................
// Get Route For Show Payment Form
Route::get('paywithrazorpay', 'RazorpayController@payWithRazorpay')->name('paywithrazorpay');
// Post Route For Makw Payment Request
Route::post('razorpay-payment', 'RazorpayController@payment')->name('razorpay-payment');
Route::get('razorpay-success', 'RazorpayController@razorpay_success')->name('razorpay_success');
*/

//pages routes...............
Route::get('/contact-us',  'PageController@contact_us');
Route::post('/contact-us',  'PageController@contact_us_store');
Route::get('/about-us',  'PageController@about_us');
Route::get('/discount-information',  'PageController@discount_information');
Route::get('/customer-service',  'PageController@customer_services');
Route::get('/term-condition',  'PageController@term_condition');
Route::get('/privacy-policy',  'PageController@privacy_policy');
Route::get('/payment-policy',  'PageController@payment_policy');
Route::get('/secure-payment', 'PageController@secure_payment');
Route::get('/shipping-delivery', 'PageController@shipping_delivery');
Route::get('/guarantee', 'PageController@guarantee');
Route::get('/faq', 'PageController@faq');
Route::get('/work-with-us',  'PageController@work_with_us');
Route::get('/return-policy',  'PageController@return_policy');
Route::post('/subscribe',  'PageController@subscribe');
Route::get('/sitemap', 'PageController@sitemap');

Route::get('/gift-card',  'GiftCardController@gift_card');
Route::get('/checkout',  'CheckoutController@index');
Route::post('/checkout',  'CheckoutController@checkout');
Route::post('/applycouponby-ajax',  'CheckoutController@applycouponby_ajax');
Route::get('payment', 'CheckoutController@order');
Route::post('payment/status', 'CheckoutController@paymentCallback');

//dashboard route...
Route::get('/my-account',  'DashboardController@my_account');
Route::post('/get-account-address',  'DashboardController@getAccountAddress');
Route::get('/my-order',  'DashboardController@my_order');
Route::get('/my-wallet',  'DashboardController@my_wallet');

Route::group(['prefix'=>'seller','middleware' => ['auth','seller']], function() {

Route::get('dashboard',  'SellerController@seller_dashboard');
Route::get('/complete-profile',  'SellerController@complete_profile');
Route::post('/get-state',  'SellerController@get_state');
Route::post('/get-city',  'SellerController@get_city');
Route::get('/forget-password',  'HomeController@forget_password');
Route::post('/forget-password',  'HomeController@sendVerifyLink');
Route::get('/forgot-verify',  'HomeController@forgot_verify');
Route::post('/reset-password',  'HomeController@resetPassword');
Route::post('/update-notify-view-status',  'HomeController@update_notify_view_status');


Route::get('/test-order-status',  'TestController@update_order_status');
Route::post('generate-manifest',  'ManifestController@download_manifest');

//Frontend route
Route::get('/index',  'IndexController@index');
Route::get('/order',  'OrderController@index');

Route::get('/display-seller-suggestion',  'CatalogController@display_seller_suggestion');

//seller payment.........
Route::get('/payment',  'PaymentController@index');
Route::get('/payment-details',  'PaymentController@payment_details');
Route::get('/next-payment-details',  'PaymentController@next_payment');
Route::get('/payment-order-details/{id}',  'PaymentController@payment_order_details');
Route::get('/payment-previous-order-details/{id1}', 'PaymentController@payment_previous_order_details');
Route::get('/last-payment',  'PaymentController@last_payment');
Route::get('/previous-payments',  'PaymentController@previous_payments');
Route::get('/outstanding-payments',  'PaymentController@outstanding_payments');
Route::get('/pending-payments',  'PaymentController@pending_payments');
Route::get('/all-order/{slug1}',  'PaymentController@previous_all_orders');
Route::get('/today-payment-order',  'PaymentController@today_payment_order_list');
Route::get('/pending-payment-order/{id}',  'PaymentController@pending_payment_order_list');


/////////////////////////////////////////////////////////////////////////////////////
Route::get('/catalog',  'CatalogController@index');
Route::get('/duplicate-product',  'DuplicateProductController@index');
Route::get('/notice',  'NoticeController@index');
Route::get('/notice/{slug}',  'NoticeController@index');

/* Catalog */
Route::get('/catalog-add',  'CatalogController@create');
Route::post('/catalog-store',  'CatalogController@store');
Route::post('/catalog-details',  'CatalogController@details');
Route::post('/get-product-item',  'InventoryController@getProductItem');
Route::post('/duplicate-catalog-details',  'DuplicateProductController@productView');
Route::get('/catalog-edit/{id}',  'CatalogController@edit');
Route::post('/get-subcat',  'CatalogController@get_subcat');
Route::get('/get-product-list',  'CatalogController@getProductList');
Route::post('/get-supsubcat',  'CatalogController@get_supsubcat');
Route::post('/update-price',  'CatalogController@update_price');
Route::get('/catalog-delete/{id}',  'CatalogController@catalog_delete');
Route::get('/duplicate-catalog-delete/{id}',  'CatalogController@duplicateCatlogDelete');
Route::post('/catalog/delete-catalog-image',  'CatalogController@delete_catalog_image');
Route::post('/catalog-update',  'CatalogController@update');
Route::get('/catalog-import',  'CatalogController@import');
Route::post('/catalog-store-import',  'CatalogController@store_import');
Route::post('/catalog/activate-sponsor',  'CatalogController@activate_sponsor');
Route::post('catalog/get-sponsored',  'CatalogController@get_sponsored');
Route::post('catalog/stock-image',  'CatalogController@stock_image');
Route::get('/catalog-error-view/{id}',  'CatalogController@catalog_error');
    //seller add brand.........
Route::post('/add-brand',  'CatalogController@addBrand');


    /* Scheme On Product */
    Route::get('/scheme-product',  'SchemeProductController@index');
    Route::get('/scheme-product-add',  'SchemeProductController@create');
    Route::post('/scheme-product/get-product',  'SchemeProductController@getProduct');
    Route::post('/scheme-product/get-product-item',  'SchemeProductController@getProductItem');
    Route::post('/scheme-product/scheme-store',  'SchemeProductController@store');
    Route::post('/scheme-product-details',  'SchemeProductController@details');
    Route::get('/scheme-product-delete/{id}',  'SchemeProductController@schemeProductDelete');


    Route::get('/offer-product/catalog-edit/{id}',  'SchemeProductController@edit');
    Route::get('/offer-product/catalog-edit/{id}',  'SchemeProductController@edit');

    Route::post('/offer-product/get-supsubcat',  'SchemeProductController@get_supsubcat');
    Route::post('/offer-product/update-price',  'SchemeProductController@update_price');

    Route::post('/offer-product/catalog/delete-catalog-image',  'SchemeProductController@delete_catalog_image');
    Route::post('/offer-product/catalog-update',  'SchemeProductController@update');
    Route::get('/offer-product/catalog-import',  'SchemeProductController@import');
    Route::post('/offer-product/catalog-store-import',  'SchemeProductController@store_import');
    Route::post('/offer-product/catalog/activate-sponsor',  'SchemeProductController@activate_sponsor');
    Route::post('/offer-product/get-sponsored',  'SchemeProductController@get_sponsored');
    Route::post('/offer-product/stock-image',  'SchemeProductController@stock_image');
    Route::get('/offer-product/catalog-error-view/{id}',  'SchemeProductController@catalog_error');

/* end */
Route::get('/setting', ['uses' => 'SettingController@setting']);
Route::get('/profile', ['uses' => 'SellerController@seller_profile']);
Route::post('/store-profile', ['uses' => 'SellerController@store_profile']);
Route::post('/update-setting', ['uses' => 'SellerController@update_setting']);
Route::post('/complete-user-profile1', ['uses' => 'SellerController@complete_user_profile_1']);
Route::post('/complete-user-profile2', ['uses' => 'SellerController@complete_user_profile_2']);
Route::post('/complete-user-profile3', ['uses' => 'SellerController@complete_user_profile_3']);
Route::get('/change-password', ['uses' => 'SellerController@change_assword']);
Route::post('/change-password', ['uses' => 'SellerController@updatePassword']);
Route::get('/aggreement', ['uses' => 'SellerController@aggreement']);

/*******   Order Route ****/
Route::get('/order-details/{id}', ['uses' => 'OrderController@order_details']);
Route::post('/get-order-address', ['uses' => 'OrderController@get_order_address']);
Route::post('/assign-to-rider', ['uses' => 'OrderController@assign_to_rider']);
Route::post('/order-all-estimate-date', ['uses' => 'OrderController@order_all_estimate_date']);
Route::get('/assign-to-rider', ['uses' => 'OrderController@assign_to_rider_list']);

/*Route::post('/order-ready-to-ship', ['uses' => 'OrderController@order_ready_to_ship']);
Route::post('/order-all-ready-to-ship', ['uses' => 'OrderController@order_all_ready_to_ship']);
Route::get('/ready-to-ship', ['uses' => 'OrderController@ready_to_ship']);*/
Route::get('/download-invoice-shipment/{id}', ['uses' => 'OrderController@download_invoice_shipment']);

Route::get('/cancelled-order', ['uses' => 'OrderController@cancelled_order']);
Route::post('/cancel-order', ['uses' => 'OrderController@cancel_order']);
Route::get('delivered-order', ['uses' => 'OrderController@delivered_order']);
Route::get('return-order', ['uses' => 'OrderController@return_order']);
Route::get('exchange-order', ['uses' => 'OrderController@exchange_order']);

/*-----cancellation risk --*/
Route::get('/cancellation-risk', ['uses' => 'CancellationRiskController@index']);


/*******  Inventory Route*********/
Route::get('/inventory',  'InventoryController@index');
Route::post('/change-category-status',  'InventoryController@change_category_status');
Route::get('/product-by-category/{id}',  'InventoryController@product_list');
Route::post('/change-product-status',  'InventoryController@change_product_status');
Route::get('/out-of-stock',  'InventoryController@out_of_stock');
Route::post('/update-item-qty',  'InventoryController@updateItemQty');
Route::get('/product-list-out-of-stock/{id}',  'InventoryController@product_list_out_of_stock');

});



Route::group(['prefix'=>'admin'], function()
{
    Route::get('/', [ 'uses' => 'LoginController@index']);
    Route::get('/forgot-password', ['uses' => 'LoginController@forgotPassword']);
    Route::post('/forgot-password', ['uses' => 'LoginController@sendVerifyLink']);
    Route::get('/forgot_changepassword/{token}', ['uses' => 'LoginController@forgot_changepassword']);
    Route::post('/reset-password', ['uses' => 'LoginController@resetPassword']);
});

//mobile view route...

Route::get('/help-view',  'HomeController@help');
Route::get('/rate-list',  'HomeController@rate_list');
Route::get('/section-details/{id}',  'HomeController@section_details');
Route::get('/topic-details/{id}',  'HomeController@topic_details');




Route::group(['namespace'=>'Admin','prefix'=>'gadmin'], function()
{

    Route::get('/', [ 'uses' => 'LoginController@index']);
    Route::post('/admin',['uses' => 'LoginController@authenticate']);
    Route::get('password/email', 'Auth\AdminPasswordController@getEmail');
    Route::post('password/email', 'Auth\AdminPasswordController@postEmail');
    Route::get('password/reset{token}','Auth\AdminPasswordController@getReset');
    Route::post('password/reset', 'Auth\AdminPasswordController@postReset');
});

Route::group(['namespace'=>'Admin','prefix'=>'admin','middleware' => ['auth.admin:admin']], function() {
 
    Route::get('/dashboard', ['uses' => 'DashboardController@index']);
    Route::get('/change_password', ['uses' => 'DashboardController@change_password']);
    Route::post('/change_password', array('uses' => 'DashboardController@update_password'));
    Route::get('/logout', ['uses' => 'LoginController@logout']);
    Route::post('/user_reports', array('uses' => 'DashboardController@changeUserReport'));
    //Change admin email....
    Route::get('/change-email', ['uses' => 'DashboardController@change_email']);
    Route::post('/update-email', array('uses' => 'DashboardController@update_email'));
    Route::post('/update-notify-view-status',  'DashboardController@updateNotifyViewStatus');

    Route::get('/city/index', ['uses' => 'CityController@index']);
    Route::post('/city/getCityData',['uses' => 'CityController@getCityData']);
    Route::get('/city/create',['uses' => 'CityController@create']);
    Route::get('/city/import',['uses' => 'CityController@import']);
    Route::post('/city/store_import',['uses' => 'CityController@store_import']);
    Route::post('/city/store',['uses' => 'CityController@store']);
    //Route::get('/city/show/{id}',['uses'=>'CityController@show']);
    Route::get('/city/view/{id}',['uses'=>'CityController@view']);
    Route::post('/city/getPincodeData',['uses'=>'CityController@getPincodeData']);
    Route::post('/city/delete/{id}', ['uses' => 'CityController@deletepincode']);
    Route::post('/city/delete_city/{id}', ['uses' => 'CityController@deleteCity']);
    /*Route::post('/city/get-state', ['uses' => 'CityController@get_state']);*/

    //delivery time...
    Route::get('/delivery-time', ['uses' => 'DeliveryTimeController@index']);
    Route::get('/delivery-charge/{id}', ['uses' => 'DeliveryTimeController@deliveryCharge']);
    Route::post('/getDeliveryTimeData',['uses' => 'DeliveryTimeController@getDeliveryTimeData']);
    Route::post('/update-delivery-time',['uses' => 'DeliveryTimeController@updateDeliveryTime']);
    Route::post('/update-express-time',['uses' => 'DeliveryTimeController@updateExpressTime']);

    //review and rating.....
    Route::get('/product/review-rating', ['uses' => 'ReviewAndRatingController@index']);
    Route::post('/product/getReviewRatingData', ['uses' => 'ReviewAndRatingController@getReviewRatingData']);
    Route::get('/product/change-status-rating-review/{id}', ['uses' => 'ReviewAndRatingController@changeStatusRatingReview']);

    //Added by nadeem ....Warehose
    Route::get('/warehouse/warehouse-list', ['uses' => 'WareHouseController@index']);
    Route::post('/warehouse/getWareHouseData', ['uses' => 'WareHouseController@getWareHouseData']);
    Route::get('/warehouse/add-warehouse', ['uses' => 'WareHouseController@add']);
    Route::post('/warehouse/store-warehouse', ['uses' => 'WareHouseController@store']);
    Route::get('/warehouse/warehouse-edit/{id1}', ['uses' => 'WareHouseController@edit']);
    Route::post('/warehouse/update/{id1}', ['uses' => 'WareHouseController@update']);
    Route::post('/warehouse/delete', ['uses' => 'WareHouseController@delete']);
    Route::get('/warehouse/update-status/{id}', ['uses' => 'WareHouseController@update_status']);
    Route::get('/warehouse/view/{id}', ['uses' => 'WareHouseController@view']);
    Route::post('/warehouse/get-pincode', ['uses' => 'WareHouseController@getPincode']);
    Route::post('/warehouse/subadmin-permission', ['uses' => 'WareHouseController@subadmin_permission']);
    Route::post('/warehouse/get-subadmin', ['uses' => 'WareHouseController@get_subadmin']);
    Route::post('/warehouse/assign-subadmin-warehouse', ['uses' => 'WareHouseController@assign_subadmin_warehouse']);
    
    //Added by nadeem ....Brands
    Route::get('/brand/brand-list', ['uses' => 'BrandController@index']);
    Route::post('/brand/getBrandData', ['uses' => 'BrandController@getBrandData']);
    Route::get('/brand/add-brand', ['uses' => 'BrandController@add']);
    Route::post('/brand/store-brand', ['uses' => 'BrandController@store']);
    Route::get('/brand/brand-edit/{id1}', ['uses' => 'BrandController@edit']);
    Route::post('/brand/update/{id1}', ['uses' => 'BrandController@update']);
    Route::post('/brand/delete', ['uses' => 'BrandController@delete']);
    Route::get('/brand/update-status/{id}', ['uses' => 'BrandController@update_status']);
    Route::get('/brand/view/{id}', ['uses' => 'BrandController@view']);


    Route::get('/gift/gift-list', ['uses' => 'GiftController@index']);
    Route::post('/gift/getGiftData', ['uses' => 'GiftController@getGiftData']);
    Route::get('gift/create-gift', ['uses' => 'GiftController@add']);
    Route::post('gift/storeGift', ['uses' => 'GiftController@store']);

   //Faq...
    Route::get('/faq/faq-list', ['uses' => 'FaqController@index']);
    Route::post('/faq/getFaqData', ['uses' => 'FaqController@getFaqData']);
    Route::get('/faq/add-faq', ['uses' => 'FaqController@add_faq']);
    Route::post('/faq/storeFaq', ['uses' => 'FaqController@store']);
    Route::get('/faq/faq-edit/{id1}', ['uses' => 'FaqController@edit_faq']);
    Route::post('/faq/updateFaq/{id1}', ['uses' => 'FaqController@updateFaq']);
    Route::post('/faq/delete', ['uses' => 'FaqController@delete']);
    Route::get('/faq/update-status/{id}', ['uses' => 'FaqController@update_status']);
    Route::get('/faq/faq-view/{id}', ['uses' => 'FaqController@show']);
    
	 ////////////////// subadmin route ////////////////////////
    Route::get('subadmin/add-subadmin', array('uses' => 'SubadminController@add'));
    Route::post('subadmin/store-subadmin', array('uses' => 'SubadminController@store'));
    Route::get('subadmin/view-all-subadmin', array('uses' => 'SubadminController@index'));
    Route::post('/subadmin/getSubadminData', ['uses' => 'SubadminController@getSubadminData']);
    Route::post('/subadmin/delete', ['uses' => 'SubadminController@delete']);
    Route::get('/subadmin/view/{id}', ['uses' => 'SubadminController@view']);
    Route::get('/subadmin/update-status/{id}', ['uses' => 'SubadminController@update_status']);
    Route::get('/subadmin/edit/{id}', ['uses' => 'SubadminController@edit']);
    Route::post('/subadmin/edit/{id}', ['uses' => 'SubadminController@update']);
    Route::post('/subadmin/change-subadmin-password', ['uses' => 'SubadminController@change_password']);
	
	
	
	////////////////// User route ////////////////////////
    Route::get('user/add-user', array('uses' => 'UserController@add'));
    Route::post('user/store-user', array('uses' => 'UserController@store'));
    Route::get('user/user-list', array('uses' => 'UserController@index'));
    Route::post('/user/getAllUserData', ['uses' => 'UserController@getAllUserData']);
    Route::post('/user/delete', ['uses' => 'UserController@delete']);
    Route::get('/user/update-status/{id}', ['uses' => 'UserController@update_status']);
    Route::get('/user/edit/{id}', ['uses' => 'UserController@edit']);
    Route::post('/user/edit/{id}', ['uses' => 'UserController@update']);
	
   
    ////////////////// Partner route ////////////////////////
    Route::get('partner/add-partner', array('uses' => 'PartnerController@add'));
    Route::post('partner/store-partner', array('uses' => 'PartnerController@store'));
    Route::get('partner/partner-list', array('uses' => 'PartnerController@index'));
    Route::post('/partner/getPartnerData', ['uses' => 'PartnerController@getPartnerData']);
    Route::post('/partner/delete', ['uses' => 'PartnerController@delete']);
    Route::get('/partner/update-status/{id}', ['uses' => 'PartnerController@update_status']);
    Route::get('/partner/edit/{id}', ['uses' => 'PartnerController@edit']);
    Route::post('/partner/edit/{id}', ['uses' => 'PartnerController@update']);
    
	  ////////////////// Customer route ////////////////////////
    Route::get('customer/add-customer', array('uses' => 'CustomerController@add'));
    Route::post('customer/store-customer', array('uses' => 'CustomerController@store'));
    Route::get('customer/customer-list', array('uses' => 'CustomerController@index'));
    Route::post('/customer/getCustomerData', ['uses' => 'CustomerController@getCustomerData']);
    Route::get('customer/active-customer-list', array('uses' => 'CustomerController@active'));
    Route::post('/customer/getActiveCustomerData', ['uses' => 'CustomerController@getActiveCustomerData']);
    Route::get('customer/inactive-customer-list', array('uses' => 'CustomerController@inactive'));
    Route::post('/customer/getInActiveCustomerData', ['uses' => 'CustomerController@getInActiveCustomerData']);
    Route::get('customer/otp-customer-list', array('uses' => 'CustomerController@otp_customer'));
    Route::post('/customer/getOtpCustomerData', ['uses' => 'CustomerController@getOtpCustomerData']); 
	Route::post('/customer/delete', ['uses' => 'CustomerController@delete']);
    Route::get('/customer/update-status/{id}', ['uses' => 'CustomerController@update_status']);
    Route::get('/customer/edit/{id}', ['uses' => 'CustomerController@edit']);
    Route::post('/customer/edit/{id}', ['uses' => 'CustomerController@update']);
    Route::get('/customer/view/{id}', ['uses' => 'CustomerController@view']);
    Route::post('customer/getOrderData', ['uses' => 'CustomerController@getOrderData']);
    Route::post('customer/getCustomerWalletData', ['uses' => 'CustomerController@getCustomerWalletData']);
    Route::post('customer/getCustomerReferralWalletData', ['uses' => 'CustomerController@getCustomerReferralWalletData']);
    Route::post('/customer/get-user-wallet-amount', ['uses' => 'CustomerController@getUserWalletAmount']);
    Route::post('/customer/deduct-user-wallet-amount', ['uses' => 'CustomerController@deductUserWalletAmount']);
    Route::post('/customer/add-user-wallet-amount', ['uses' => 'CustomerController@addUserWalletAmount']);
    Route::get('/customer/notification', ['uses' => 'CustomerController@notification']);
    Route::post('/customer/storeNotification', ['uses' => 'CustomerController@storeNotification']);

    //Raising.............

    Route::get('raising-complain', array('uses' => 'RaisingComplainController@index'));
    Route::post('/getComplainUserData', ['uses' => 'RaisingComplainController@getComplainUserData']);
    Route::post('/register-complaint', ['uses' => 'RaisingComplainController@registerComplaint']);
    Route::post('/add-solution', ['uses' => 'RaisingComplainController@addSolution']);
    Route::get('/raising-list', ['uses' => 'RaisingComplainController@raisingList']);
    Route::post('/getRaisingComplaintData', ['uses' => 'RaisingComplainController@getRaisingComplaintData']);

    //call request ...
    Route::get('/call-request', array('uses' => 'SiteSettingController@callRequest'));
    Route::post('/getCallRequestData', ['uses' => 'SiteSettingController@getCallRequestData']);

      ////////////////// merchant route ////////////////////////
    Route::get('delivery-boy/create-delivery-boy', array('uses' => 'DeliveryBoyController@add'));
    Route::post('delivery-boy/store-delivery-boy', array('uses' => 'DeliveryBoyController@store'));
    Route::get('/delivery-boy/view/{id}', ['uses' => 'DeliveryBoyController@view']);
    Route::post('/delivery-boy/get-warehouse', ['uses' => 'DeliveryBoyController@get_warehouse']);
    Route::get('delivery-boy/delivery-boy-list', array('uses' => 'DeliveryBoyController@index'));
    Route::post('/delivery-boy/getDeliveryBoyData', ['uses' => 'DeliveryBoyController@getDeliveryBoyData']);
    Route::get('delivery-boy/active-delivery-boy-list', array('uses' => 'DeliveryBoyController@active'));
    Route::post('/delivery-boy/getActiveDeliveryBoyData', ['uses' => 'DeliveryBoyController@getActiveDeliveryBoyData']);
    Route::post('/delivery-boy/getInActiveDeliveryBoyData', ['uses' => 'DeliveryBoyController@getinActiveDeliveryBoyData']);
    Route::get('delivery-boy/inactive-delivery-boy-list', array('uses' => 'DeliveryBoyController@inActive'));
    Route::post('/delivery-boy/getInActiveMerchantData', ['uses' => 'DeliveryBoyController@getInActiveMerchantData']);
    Route::get('delivery-boy/otp-delivery-boy-list', array('uses' => 'DeliveryBoyController@otp_merchant'));
    Route::get('/delivery-boy/edit-delivery-boy/{id}', ['uses' => 'DeliveryBoyController@edit']);
    Route::post('/delivery-boy/edit-delivery-boy/{id}', ['uses' => 'DeliveryBoyController@update']);
    Route::get('/delivery-boy/update-status/{id}', ['uses' => 'DeliveryBoyController@update_status']);
    Route::get('/delivery-boy/rider-location/{id}', ['uses' => 'DeliveryBoyController@riderLocation']);
    Route::get('/delivery-boy/income-setting', ['uses' => 'DeliveryBoyController@income_setting']);
    Route::post('/delivery-boy/income-setting', ['uses' => 'DeliveryBoyController@income_setting_store']);
    Route::get('/delivery-boy-cod-payment', ['uses' => 'DeliveryBoyController@cod_payment']);
    Route::get('/delivery-boy-accept-cod/{id}', ['uses' => 'DeliveryBoyController@accept_cod_payment']);


	////////////////// agent route ////////////////////////
    Route::get('agent/create-agent', array('uses' => 'AgentController@add'));
    Route::post('agent/store-agent', array('uses' => 'AgentController@store'));
    Route::get('/agent/view/{id}', ['uses' => 'AgentController@view']);
    Route::get('agent/agent-list', array('uses' => 'AgentController@index'));
    Route::post('/agent/getAgentData', ['uses' => 'AgentController@getAgentData']);
    Route::get('agent/active-agent-list', array('uses' => 'AgentController@active'));
    Route::post('/agent/getActiveAgentData', ['uses' => 'AgentController@getActiveAgentData']);
    Route::get('agent/inactive-agent-list', array('uses' => 'AgentController@inActive'));
    Route::post('/agent/getInActiveAgentData', ['uses' => 'AgentController@getInActiveAgentData']);
    Route::get('agent/otp-agent-list', array('uses' => 'AgentController@otp_merchant'));
    Route::get('/agent/edit-agent/{id}', ['uses' => 'AgentController@edit']);
    Route::post('/agent/edit-agent/{id}', ['uses' => 'AgentController@update']);
    Route::get('/agent/update-status/{id}', ['uses' => 'AgentController@update_status']);
    

       ///coin route///////////////////////////////////////////////
    Route::get('/coin/f-coins', ['uses' => 'CoinController@create']);
    Route::post('/coin/store-fcoin', ['uses' => 'CoinController@store']);
    Route::get('/coin/avl-coins', ['uses' => 'CoinController@avl_coins']);

	
    //Site Setting
    Route::get('/site-setting/general-setting', ['uses' => 'SiteSettingController@create']);
    Route::post('/site-setting/general-setting-store', ['uses' => 'SiteSettingController@store']);
//Seller joinus cms
    Route::get('/site-setting/seller-joinus-cms', ['uses' => 'SiteSettingController@seller_joinus_cms']);
    Route::post('/site-setting/seller-joinus-cms-store', ['uses' => 'SiteSettingController@seller_joinus_cms_store']);

    Route::get('/site-setting/payumoney-setting', ['uses' => 'SiteSettingController@payment_create']);
    Route::post('/site-setting/payumoney-setting', ['uses' => 'SiteSettingController@payumoney_setting_store']);
    Route::get('/site-setting/bank-detail', ['uses' => 'SiteSettingController@bank_detail_create']);
    Route::post('/site-setting/bank-detail', ['uses' => 'SiteSettingController@bank_detail_store']);
    Route::get('/site-setting/contact-us', ['uses' => 'SiteSettingController@contact_us']);
    Route::post('/site-setting/getQueryFormData',   ['uses' => 'SiteSettingController@getQueryFormData']);


    Route::post('/site-setting/contact-store', ['uses' => 'SiteSettingController@store_contact_us']);
    Route::get('/site-setting/user-complaints',['uses'=>'SiteSettingController@user_complaints']);
    Route::post('/site-setting/getUserComplaintData',['uses'=>'SiteSettingController@getUserComplaintData']);
    Route::get('/user-complaints/view/{id}', ['uses' => 'SiteSettingController@view_complaint']);
    Route::post('/site-setting/store-user-complaints',['uses'=>'SiteSettingController@store_user_complaints']);
    Route::post('/update-support-status',['uses'=>'SiteSettingController@update_support_status']);

    
    Route::get('/site-setting/app-update', ['uses' => 'SiteSettingController@app_update']);
    Route::post('site-setting/app-setting-store', ['uses' => 'SiteSettingController@store_app_update']);
    Route::get('site-setting/merchant-commission', ['uses' => 'SiteSettingController@merchant_commission']);
    Route::post('site-setting/merchant-commission', ['uses' => 'SiteSettingController@merchant_commission_store']);
    Route::get('site-setting/cashback',['uses'=>'SiteSettingController@cashback']);
    Route::post('site-setting/update-cashback',['uses'=>'SiteSettingController@update_cashback']);
    Route::get('site-setting/refernearn',['uses'=>'SiteSettingController@refer_earn']);
    Route::post('site-setting/update-referral',['uses'=>'SiteSettingController@update_referral']);
   
    Route::get('/site-setting/bank-detail', ['uses' => 'SiteSettingController@bank_detail_create']);
    Route::post('/site-setting/bank-detail', ['uses' => 'SiteSettingController@bank_detail_store']);
    Route::get('site-setting/add-agreement', ['uses' => 'SiteSettingController@add_agreement']);
    Route::post('site-setting/store-agreement', ['uses' => 'SiteSettingController@store_agreement']);
    Route::get('site-setting/app-video', ['uses' => 'SiteSettingController@app_video']);
    Route::post('site-setting/app-video-store', ['uses' => 'SiteSettingController@store_app_video']);
    Route::get('site-setting/popular-thumbnail', ['uses' => 'SiteSettingController@popular_thumbnail']);
    Route::post('site-setting/popular-thumbnail-store', ['uses' => 'SiteSettingController@popular_thumbnail_store']);
    
    //UserNotification.... UserNotificationController........
    Route::get('/site-setting/user-notification', ['uses' => 'UserNotificationController@index']);
    Route::post('/site-setting/get-user-notification', ['uses' => 'UserNotificationController@getUserNotificationData']);
    Route::get('site-setting/add-user-notification', ['uses' => 'UserNotificationController@add_notification']);
    Route::post('site-setting/storeUserNotification', ['uses' => 'UserNotificationController@store']);
    Route::get('/site-setting/edit-notification/{id}', ['uses' => 'UserNotificationController@edit']);
    Route::post('/site-setting/update-notification/{id}',['uses'=>'UserNotificationController@update']);
    Route::post('/site-setting/user-notification/delete', ['uses' => 'UserNotificationController@delete']);
    Route::post('/site-setting/user-notification/publish', ['uses' => 'UserNotificationController@publish']);

     //User wallet...............
     Route::get('/wallet-users/', ['uses' => 'UserController@index']);
     Route::post('/user/getUserData', ['uses' => 'UserController@getUserData']);
     Route::get('/user/view/{id}', ['uses' => 'UserController@view']);
     Route::get('/user/update-status/{id1}/{id2}', ['uses' => 'UserController@update_status']);
     Route::post('/user/delete', ['uses' => 'UserController@delete']);
     Route::get('/user/add-coin/{id}', ['uses' => 'UserController@add_coin']);
     Route::post('/user/stroe-coin/{id}', ['uses' => 'UserController@coin_store']);
     Route::get('/user/export-excel', ['uses' => 'UserController@getExcelData']);
     //paid unverified.......
      Route::get('/unverified-users', ['uses' => 'UserController@unverified_users']);
      Route::post('/user/getPaidUnverifiedData', ['uses' => 'UserController@getPaidUnverifiedData']);
      Route::get('/user/view-paid-unverified/{id}', ['uses' => 'UserController@view_paid_unverified']);
      // Deposit Amount............
    Route::get('/manage-deposit', ['uses' => 'DepositController@index']);
    Route::post('/deposit/getDepositData', ['uses' => 'DepositController@getDepositData']);
    Route::get('/deposit/update-status/{id1}/{id2}/{id3}', ['uses' => 'DepositController@update_status']);
    //Withdraw Amount.......
    Route::get('/manage-withdraw', ['uses' => 'DepositController@withdraw']);
    Route::post('/withdraw/getWithdrawData', ['uses' => 'DepositController@getWithdrawData']);
    Route::get('/withdraw/withdraw-update-status/{id1}/{id2}/{id3}', ['uses' => 'DepositController@withdraw_update_status']);
    Route::get('/withdraw/withdraw-view/{id}', ['uses' => 'DepositController@withdraw_view']);
    Route::get('/withdraw/export-excel', ['uses' => 'DepositController@getExcelData']);
    //Withdraw Pending.......
    Route::get('/withdraw-pending', ['uses' => 'DepositController@withdraw_pending']);
    Route::post('/withdraw/getPendingData', ['uses' => 'DepositController@getPendingData']);
    Route::get('/withdraw/pending-update-status/{id1}/{id2}/{id3}', ['uses' => 'DepositController@pending_update_status']);
    Route::get('/withdraw/pending-view/{id}', ['uses' => 'DepositController@pending_view']);
    Route::get('/withdraw/pending-export-excel', ['uses' => 'DepositController@getPendingExcelData']);
    //Transfer Amount..
    Route::get('/manage-transfer', ['uses' => 'TransferController@index']);
    Route::post('/transfer/getTransferData', ['uses' => 'TransferController@getTransferData']);
    //admin/btc-address/update-address
    Route::get('/btc-address', ['uses' => 'BtcAddressController@btc_address']);
    Route::post('/update-btc-address', ['uses' => 'BtcAddressController@store']);
   //Admin Report...
    Route::get('/report/report-list', ['uses' => 'ReportController@index']);
    Route::post('/report/getReportData', ['uses' => 'ReportController@getReportData']);
    Route::get('/report/export-excel', ['uses' => 'ReportController@getExcelData']);
    //Slider
    Route::get('/slider/slider-list', ['uses' => 'SliderController@index']);
    Route::post('/slider/getSliderData', ['uses' => 'SliderController@getSliderData']);
    Route::get('/slider/add-slider', ['uses' => 'SliderController@add_slider']);
    Route::post('/slider/storeSlider', ['uses' => 'SliderController@store']);
    Route::get('/slider/slider-edit/{id}', ['uses' => 'SliderController@edit_slider']);
    Route::post('/slider/updateSlider/{id}', ['uses' => 'SliderController@updateSlider']);
    Route::post('/slider/updateSlider/{id}', ['uses' => 'SliderController@updateSlider']);
    Route::post('/slider/delete', ['uses' => 'SliderController@delete']);
    Route::get('/slider/update-status/{id}', ['uses' => 'SliderController@update_status']);
    Route::post('/slider/get-product-list', ['uses' => 'SliderController@getProductList']);

    //Banner
    //Added by nadeem...banner route.
    Route::get('/banner/banner-list', ['uses' => 'BannerController@index']);
    Route::get('/banner/add-banner',['uses' => 'BannerController@addBanner']);
    Route::post('/banner/storeBanner',['uses' => 'BannerController@store']);
    Route::post('/banner/getBannerData',['uses'=>'BannerController@getBannerData']);
    Route::post('/banner/delete', ['uses' => 'BannerController@delete']);
    Route::get('/banner/banner-edit/{id}', ['uses' => 'BannerController@edit_banner']);
    Route::post('/Banner/updateBanner/{id}', ['uses' => 'BannerController@updateBanner']);
  //Route::post('/slider/updateSlider/{id}', ['uses' => 'SliderController@updateSlider']);
    Route::get('/banner/update-status/{id}', ['uses' => 'BannerController@update_status']);



    //Route::
 
	//Dip
    Route::get('/dip/dip-list', ['uses' => 'DipController@index']);
    Route::post('/dip/getDipData', ['uses' => 'DipController@getDipData']);
    Route::get('/dip/add-dip', ['uses' => 'DipController@add_dip']);
    Route::post('/dip/storeDip', ['uses' => 'DipController@store']);
    Route::get('/dip/dip-edit/{id}', ['uses' => 'DipController@edit_dip']);
    Route::post('/dip/updateDip/{id}', ['uses' => 'DipController@updateDip']);
    Route::post('/dip/delete', ['uses' => 'DipController@delete']);
    Route::get('/dip/update-status/{id}', ['uses' => 'DipController@update_status']);

    //Gallery
    Route::get('/gallery/gallery-list', ['uses' => 'GalleryController@index']);
    Route::post('/gallery/getGalleryData', ['uses' => 'GalleryController@getGalleryData']);
    Route::get('/gallery/add-gallery', ['uses' => 'GalleryController@add_gallery']);
    Route::post('/gallery/storeGallery', ['uses' => 'GalleryController@store']);
    Route::get('/gallery/gallery-edit/{id}', ['uses' => 'GalleryController@edit_gallery']);
    Route::post('/gallery/updateGallery/{id}', ['uses' => 'GalleryController@updateGallery']);
    Route::post('/gallery/delete', ['uses' => 'GalleryController@delete']);
    Route::get('/gallery/update-status/{id}', ['uses' => 'GalleryController@update_status']);
    Route::get('/gallery/message', ['uses' => 'GalleryController@message_view']);
    Route::post('/gallery/store-message', ['uses' => 'GalleryController@message']);
	
   //Faq...
	Route::get('/faq/faq-list/{id}', ['uses' => 'FaqController@index']);
    Route::post('/faq/getFaqData', ['uses' => 'FaqController@getFaqData']);
    Route::get('/faq/add-faq/{id}', ['uses' => 'FaqController@add_faq']);
    Route::post('/faq/storeFaq', ['uses' => 'FaqController@store']);
    Route::get('/faq/faq-edit/{id1}/{id2}', ['uses' => 'FaqController@edit_faq']);
    Route::post('/faq/updateFaq/{id1}/{id2}', ['uses' => 'FaqController@updateFaq']);
    Route::post('/faq/delete', ['uses' => 'FaqController@delete']);
    Route::get('/faq/update-status/{id}', ['uses' => 'FaqController@update_status']);
    Route::get('/faq/faq-view/{id}', ['uses' => 'FaqController@show']);
	//Questions...
	Route::get('/question/questions-list/{id1}/{id2}', ['uses' => 'QuestionController@index']);
    Route::post('/question/getQuestionData', ['uses' => 'QuestionController@getQuestionData']);
    Route::get('/question/add-question/{id1}/{id2}', ['uses' => 'QuestionController@add_question']);
    Route::post('/question/storeQuestion', ['uses' => 'QuestionController@store']);
    Route::get('/question/question-edit/{id1}/{id2}/{id3}', ['uses' => 'QuestionController@edit_question']);
    Route::post('/question/updateQuestion/{id1}/{id2}/{id3}', ['uses' => 'QuestionController@updateQuestion']);
    Route::post('/question/delete', ['uses' => 'QuestionController@delete']);
    // Route::get('/faq/update-status/{id}', ['uses' => 'FaqController@update_status']);
	//Must See...
    Route::get('/help/help-list', ['uses' => 'HelpController@index']);
    Route::post('/help/getHelpData', ['uses' => 'HelpController@getHelpData']);
    Route::get('/help/create-help', ['uses' => 'HelpController@add_faq']);
    Route::post('/help/store-help', ['uses' => 'HelpController@store']);
    Route::get('/help/help-edit/{id}', ['uses' => 'HelpController@edit_help']);
    Route::post('/help/updateHelp/{id}', ['uses' => 'HelpController@updateHelp']);
    Route::post('/help/delete', ['uses' => 'HelpController@delete']);
    Route::get('/help/update-status/{id}', ['uses' => 'HelpController@update_status']);
    Route::post('/must-see/delete', ['uses' => 'HelpController@delete_record']);
    //cms page.....	
   Route::get('/cms/cms-list', ['uses' => 'CmsController@index']);
   Route::post('/cms/getCmsData', ['uses' => 'CmsController@getCmsData']);
   Route::get('/cms/edit/{id}', ['uses' => 'CmsController@edit']);
   Route::post('/cms/updateCms/{id}', ['uses' => 'CmsController@update']);
   Route::get('/cms/view/{id}', ['uses' => 'CmsController@view']);
   
    //Our Team...
    Route::get('/team/team-list', ['uses' => 'TeamController@index']);
    Route::post('/team/getTeamData', ['uses' => 'TeamController@getTeamData']);
    Route::get('/team/add-team', ['uses' => 'TeamController@add_team']);
    Route::post('/team/storeTeam', ['uses' => 'TeamController@store']);
    Route::get('/team/team-edit/{id}', ['uses' => 'TeamController@edit_team']);
    Route::post('/team/updateTeam/{id}', ['uses' => 'TeamController@updateTeam']);
    Route::post('/team/delete', ['uses' => 'TeamController@delete']);
    Route::get('/team/update-status/{id}', ['uses' => 'TeamController@update_status']);
    //bank setting
    Route::get('/bank/bank-details/{id}', ['uses' => 'BankController@bank_details']);
    Route::post('/bank/bank-store/{id}', ['uses' => 'BankController@update']);
    
	//Enquiry Route....
    Route::get('/enquiry/enquiry-list', ['uses' => 'EnquiryController@index']);
    Route::post('/enquiry/getEnquiryData', ['uses' => 'EnquiryController@getEnquiryData']);
   
   //category Route....
    Route::get('/category/category-list', ['uses' => 'CategoryController@index']);
    Route::post('/category/getCategoryData', ['uses' => 'CategoryController@getCategoryData']);
    Route::get('/category/create-category', ['uses' => 'CategoryController@create']);
    Route::post('/category/store-category', ['uses' => 'CategoryController@store']);
    Route::get('/category/show/{id}', ['uses' => 'CategoryController@show']);
    Route::get('/category/edit/{id}', ['uses' => 'CategoryController@edit']);
    Route::post('/category/update/{id}', ['uses' => 'CategoryController@update']);
    Route::post('/category/delete/{id}', ['uses' => 'CategoryController@delete']);
    Route::get('/category/update-status/{id}', ['uses' => 'CategoryController@update_status']);
    Route::post('/category/getProductByCategory', ['uses' => 'CategoryController@product_ajax_list']);
    Route::post('/category/position',  'CategoryController@position');
    Route::post('/category/special',  'CategoryController@special');
   //Subcategory Route....
    Route::get('/subcategory/subcategory-list', ['uses' => 'SubCategoryController@index']);
    Route::post('/subcategory/getSubCategoryData', ['uses' => 'SubCategoryController@getSubCategoryData']);
    Route::get('/subcategory/create-subcategory', ['uses' => 'SubCategoryController@create']);
    Route::post('/subcategory/store-subcategory', ['uses' => 'SubCategoryController@store']);
    Route::get('/subcategory/show/{id}', ['uses' => 'SubCategoryController@show']);
    Route::get('/subcategory/edit/{id}', ['uses' => 'SubCategoryController@edit']);
    Route::post('/subcategory/update/{id}', ['uses' => 'SubCategoryController@update']);
    Route::post('/subcategory/delete/{id}', ['uses' => 'SubCategoryController@delete']);
    Route::get('/subcategory/update-status/{id}', ['uses' => 'SubCategoryController@update_status']);
    Route::post('/subcategory/getProductBySubCategory', ['uses' => 'SubCategoryController@product_ajax_list']);
   
   //SuperSubCategory Route....
    Route::get('/supersubcategory/super-subcategory-list', ['uses' => 'SuperSubCategoryController@index']);
    Route::post('/supersubcategory/getSuperSubCategoryData', ['uses' => 'SuperSubCategoryController@getSuperSubCategoryData']);
    Route::get('/supersubcategory/create-super-subcategory', ['uses' => 'SuperSubCategoryController@create']);
    Route::post('/supersubcategory/store-super-subcategory', ['uses' => 'SuperSubCategoryController@store']);
    Route::get('/supersubcategory/show/{id}', ['uses' => 'SuperSubCategoryController@show']);
    Route::get('/supersubcategory/edit/{id}', ['uses' => 'SuperSubCategoryController@edit']);
    Route::post('/supersubcategory/update/{id}', ['uses' => 'SuperSubCategoryController@update']);
    Route::post('/supersubcategory/delete/{id}', ['uses' => 'SuperSubCategoryController@delete']);
    Route::get('/supersubcategory/update-status/{id}', ['uses' => 'SuperSubCategoryController@update_status']);
    Route::post('/subcategory/get-sub-category/{id}', ['uses' => 'SuperSubCategoryController@get_sub_category']);
    

	//Product Route....
    Route::get('/product/product-list',   ['uses' => 'ProductController@index']);
    Route::post('/product/getProductData', ['uses' => 'ProductController@getProductData']);
    Route::get('/product/create-product', ['uses' => 'ProductController@create']);
    Route::post('/product/delete', ['uses' => 'ProductController@delete']);
    Route::get('/product/show/{id}', ['uses' => 'ProductController@property_details']);
    Route::get('/product/edit/{id}', ['uses' => 'ProductController@edit']);
	Route::post('/product/update-product', ['uses' => 'ProductController@update']);
    Route::get('/product/update-inactive-status/{id}', ['uses' => 'ProductController@update_inactive_status']);
    Route::post('/product/update-status/', ['uses' => 'ProductController@update_status']);
    Route::post('/product/store-product', ['uses' => 'ProductController@store']); 
    Route::post('/product/get-sub-category', ['uses' => 'ProductController@get_sub_category']);
    Route::post('/product/get-seller', ['uses' => 'ProductController@get_seller']);
    Route::post('/product/get-super-category', ['uses' => 'ProductController@get_super_category']); 
    Route::get('product/upload-catalog/{id}', ['uses' => 'ProductController@upload_catalog']); 
    Route::post('product/store-catalog-image', ['uses' => 'ProductController@store_catalog_image']); 
    Route::get('product/size-list', ['uses' => 'ProductController@add_size']); 
    Route::post('product/store-size', ['uses' => 'ProductController@store_size']); 
    Route::post('product/delete-size', ['uses' => 'ProductController@delete_size']); 
    Route::get('product/unverified-product-list', ['uses' => 'ProductController@unverified_product_list']); 
    Route::post('product/getUnverifiedProductData', ['uses' => 'ProductController@getUnverifiedProductData']);
    Route::post('catalog/delete-catalog-image', ['uses' => 'ProductController@delete_image']);
    Route::get('/product/is-sunday-bazar-offer/{id}', ['uses' => 'ProductController@isSundayBazarOffer']);
    Route::get('/product/is-grocito-exclusive-offer/{id}', ['uses' => 'ProductController@isGrocitoExclusiveOffer']);

    Route::get('product/color-list', ['uses' => 'ProductController@add_color']);
    Route::post('product/store-color', ['uses' => 'ProductController@store_color']);
    Route::post('product/delete-color', ['uses' => 'ProductController@delete_color']);


    //Front Management Route....
    Route::get('/testimonials/testimonials-list',   ['uses' => 'TestimonialsController@index']);
    Route::post('/testimonials/getTestimonialsData', ['uses' => 'TestimonialsController@getTestimonialsData']);
    Route::get('/testimonial/create-testimonial', ['uses' => 'TestimonialsController@create']);
    Route::post('/testimonial/store-testimonial', ['uses' => 'TestimonialsController@store']);
    Route::get('/testimonials/edit-testimonial/{id}', ['uses' => 'TestimonialsController@delete']);
    Route::post('/testimonials/update/{id}', ['uses' => 'TestimonialsController@update']);
    Route::get('/testimonials/show-testimonial/{id}', ['uses' => 'TestimonialsController@show']);
    
	/*  News and story */
    Route::get('/post/post-list',   ['uses' => 'PostController@index']);
    Route::get('/post/edit-post/{id}', ['uses' => 'PostController@delete']);
    Route::get('/post/show-post/{id}', ['uses' => 'PostController@show']);
    
	/* Notice Board */
	 Route::get('/notice/notice-list',   ['uses' => 'NoticeController@index']);
	 Route::post('/notice/getNoticeData',   ['uses' => 'NoticeController@getNoticeData']);
     Route::get('/notice/create-notice', ['uses' => 'NoticeController@create']);
     Route::post('/notice/store-notice', ['uses' => 'NoticeController@store']);
     Route::get('/notice/update-status/{id}', ['uses' => 'NoticeController@update_status']);
     Route::get('/notice/view/{id}', ['uses' => 'NoticeController@view']);
     Route::get('/notice/edit/{id}', ['uses' => 'NoticeController@edit']);
     Route::post('/notice/update-notice/{id}', ['uses' => 'NoticeController@update']);
	 Route::post('/notice/delete', ['uses' => 'NoticeController@delete']);
	 Route::get('/notice/generate-pdf', ['uses' => 'NoticeController@generate_pdf']);
	
	/* Plan */
	 Route::get('/plan/plan-list', ['uses' => 'PlanController@index']);
	 Route::post('/plan/getPlanData', ['uses' => 'PlanController@getPlanData']);
	 Route::get('/plan/edit/{id}', ['uses' => 'PlanController@edit']);
	 Route::post('/plan/update-plan/{id}', ['uses' => 'PlanController@update']);
    
	/* Order */
	 Route::get('/order/order-list',   ['uses' => 'OrderController@index']);
	 Route::post('/order/getOrderData',   ['uses' => 'OrderController@getOrderData']);
     Route::get('/order/cancelled-order-list',   ['uses' => 'OrderController@cancelled_order']);
	 Route::post('/order/getCancelledOrderData',   ['uses' => 'OrderController@getCancelledOrderData']);
     Route::get('/order/return-exchange-order',   ['uses' => 'OrderController@return_exchange_order']);
	 Route::post('/order/getReturnOrderData',   ['uses' => 'OrderController@getReturnOrderData']);
     Route::get('/order/incompleted-order-list',   ['uses' => 'OrderController@incompleted_order_list']);
	 Route::post('/order/getIncompletedOrderData',   ['uses' => 'OrderController@getIncompletedOrderData']);
     Route::get('/order/completed-order-list',   ['uses' => 'OrderController@completed_order']);
	 Route::post('/order/getCompletedOrderData',   ['uses' => 'OrderController@getCompletedOrderData']);
     Route::post('/order/delete', ['uses' => 'OrderController@delete']);
	 Route::get('/order/view-order/{id}', ['uses' => 'OrderController@view']);
     Route::get('/order/download-order-invoice/{id}', ['uses' => 'OrderController@download_invoice']);
     Route::post('/order/view-reason', ['uses' => 'OrderController@view_return_reason']);
     Route::get('/order/approve-for-return/{id1}/{id2}', ['uses' => 'OrderController@approve_for_return']);
	 Route::get('/order/unapprove-for-return/{id1}/{id2}', ['uses' => 'OrderController@unapprove_for_return']);
     Route::get('/order/approve-for-exchange/{id1}/{id2}', ['uses' => 'OrderController@approve_for_exchange']);
	 Route::get('/order/unapprove-for-exchange/{id1}/{id2}', ['uses' => 'OrderController@unapprove_for_exchange']);
     Route::post('/order/view-product-details', ['uses' => 'OrderController@get_exchange_order_details']);
     Route::get('/order/return-item/{id}', ['uses' => 'OrderController@return_item']);

    //Manage Order
	Route::get('/order-management',   ['uses' => 'ManageOrderController@index']);
	Route::get('/accept-order/{id}',   ['uses' => 'ManageOrderController@accept_order']);
	Route::get('/at-warehouse',   ['uses' => 'ManageOrderController@at_warehouse']);
	Route::post('/get-rider',   ['uses' => 'ManageOrderController@get_rider']);
	Route::post('/assign-rider',   ['uses' => 'ManageOrderController@assign_rider']);
	Route::get('/assign-to-rider',   ['uses' => 'ManageOrderController@assign_to_rider']);
	Route::get('/delivered-order',   ['uses' => 'ManageOrderController@delivered_order']);
    
    //order return
    Route::get('/order/return-pending',   ['uses' => 'OrderController@return_pending_order']);
    Route::get('/order/return-approved',   ['uses' => 'OrderController@return_approved_order']);
    Route::post('/order/getReturnPendingOrderData',   ['uses' => 'OrderController@getReturnPendingOrderData']);
    Route::post('/order/getReturnApprovedOrderData',   ['uses' => 'OrderController@getReturnApprovedOrderData']);

    //order exchange
    Route::get('/order/exchange-pending',   ['uses' => 'OrderController@exchange_pending_order']);
    Route::get('/order/exchange-approved',   ['uses' => 'OrderController@exchange_approved_order']);
    Route::post('/order/getExchangePendingOrderData',   ['uses' => 'OrderController@getExchangePendingOrderData']);
    Route::post('/order/getExchangeApprovedOrderData',   ['uses' => 'OrderController@getExchangeApprovedOrderData']);
    Route::get('/order/approve-for-exchange/{id1}/{id2}', ['uses' => 'OrderController@approve_for_exchange']);
	/*Help(how to use) */
     Route::get('/section/section-list',   ['uses' => 'SectionController@index']);
	 Route::post('/section/getSectionData',   ['uses' => 'SectionController@getSectionData']);
     Route::get('/section/create-section', ['uses' => 'SectionController@create']);
     Route::post('/section/store-section', ['uses' => 'SectionController@store']);
     Route::get('/section/update-status/{id}', ['uses' => 'SectionController@update_status']);
     Route::get('/section/section-view/{id}', ['uses' => 'SectionController@show']);
	 Route::post('/section/delete', ['uses' => 'SectionController@delete']);
	 Route::get('/section/section-edit/{id}', ['uses' => 'SectionController@edit']);
	 Route::post('/section/update/{id}', ['uses' => 'SectionController@update']);
	 
	 /*Report */
     Route::get('/report/delivery-report',   ['uses' => 'ReportController@index']);
	 Route::post('/report/getDeliveryData',   ['uses' => 'ReportController@getDeliveryData']);
	
	/* Payment Report */
     Route::get('/payment/payment-report',   ['uses' => 'PaymentController@index']);
     Route::get('/payment/view-payment/{id}',   ['uses' => 'PaymentController@payment_view']);
     Route::post('/payment/pay-now',   ['uses' => 'PaymentController@pay_now']);
     Route::post('/payment/settle-now',   ['uses' => 'PaymentController@settle_now']);
	 //payment/settle-now
	 Route::post('/payment/getPaymentData',   ['uses' => 'PaymentController@getPaymentData']);
     Route::post('/payment/deposite-payment', ['uses' => 'PaymentController@deposite']);
     Route::post('/payment/withdraw-payment', ['uses' => 'PaymentController@withdraw']);
     Route::get('/payment/transaction_list/{id}', ['uses' => 'PaymentController@transaction_list']);
	 Route::post('/payment/getTransactionData', ['uses' => 'PaymentController@getTransactionData']);
	 Route::get('/orders-between-dates/{id1}/{id2}', ['uses' => 'PaymentController@get_order_list']);
	 Route::post('/payment/getPaymentOrderData', ['uses' => 'PaymentController@get_order_ajax_list']);
	 Route::get('/payment-order-details/{id1}', 'PaymentController@order_details');
	
	//reseller payment
     Route::get('/payment/reseller-payment-list', ['uses' => 'PaymentController@reseller_payment_list']);
	 Route::post('/payment/getResellerPaymentData', ['uses' => 'PaymentController@getResellerPaymentData']);
	 Route::post('payment/update-reseller-payment-status', ['uses' => 'PaymentController@update_reseller_payment_status']);
	 Route::post('payment/get-account-details', ['uses' => 'PaymentController@get_account_details']);
	 Route::get('reseller-payment-history/{id}', ['uses' => 'PaymentController@reseller_payment_history']);
	 Route::post('payment/getResellerPaymentHistory', ['uses' => 'PaymentController@getResellerPaymentHistory']);
	 //Route::post('payment/payment-details', ['uses' => 'PaymentController@payment_details']);
     Route::get('payment/payment-details/{id1}', ['uses' => 'PaymentController@payment_details']);
	 
	 //User Notes..............
	 Route::get('user-note/{id}', ['uses' => 'UserNoteController@index']);
	 Route::post('note/storeNote', ['uses' => 'UserNoteController@user_note_store']);
	 Route::post('user-note/delete', ['uses' => 'UserNoteController@destroy']);
	 
	 //Product Notes..............
	 Route::get('product-note/{id}', ['uses' => 'ProductNoteController@index']);
	 Route::post('product-note/storeNote', ['uses' => 'ProductNoteController@product_note_store']);
	 Route::post('product-note/delete', ['uses' => 'ProductNoteController@destroy']);
	 
	 //Product Notes..............
	 Route::get('order-note/{id}', ['uses' => 'OrderNoteController@index']);
	 Route::post('order-note/storeNote', ['uses' => 'OrderNoteController@order_note_store']);
	 Route::post('order-note/delete', ['uses' => 'OrderNoteController@destroy']);
	 
	 //Seller Manifest..............
	 Route::get('seller/seller-manifest-list', ['uses' => 'ManifestController@index']);
	 Route::post('seller/getManifestData', ['uses' => 'ManifestController@getManifestData']);
	 Route::post('manifest/delete', ['uses' => 'ManifestController@delete']);
	 Route::get('download-manifest/{id}', ['uses' => 'ManifestController@generate_manifest']);
	 
	 //Seller Commission..............
	 Route::get('seller/seller-commission', ['uses' => 'SellerCommissionController@index']);
	 //Route::post('seller/getManifestData', ['uses' => 'ManifestController@getManifestData']);
	 //Route::post('manifest/delete', ['uses' => 'ManifestController@delete']);
	// Route::get('download-manifest/{id}', ['uses' => 'ManifestController@generate_manifest']);
	 Route::get('return-order', ['uses' => 'OrderController@change_return_status']);
	
    //coupon code
     Route::get('coupon/coupon-list', ['uses' => 'CouponController@index']);
     Route::post('coupon/getCouponData', ['uses' => 'CouponController@getCouponCode']);
     Route::get('coupon/create', ['uses' => 'CouponController@create']);
     Route::post('coupon/store', ['uses' => 'CouponController@store']);
     Route::get('coupon/edit/{id}', ['uses' => 'CouponController@edit']);
     Route::post('coupon/update/{id}', ['uses' => 'CouponController@update']);
     Route::post('coupon/delete/{id}', ['uses' => 'CouponController@delete']);


     Route::get('icon-create/{id}', ['uses' => 'CityController@icon_create']);
     Route::post('icon-store/{id}', ['uses' => 'CityController@icon_store']);
     
	 
     ////////////////// Seller route ////////////////////////
    Route::get('seller/create-seller', array('uses' => 'SellerController@add'));
    Route::post('seller/store-seller', array('uses' => 'SellerController@store'));
    Route::get('seller/seller-list', array('uses' => 'SellerController@index'));
    Route::post('/seller/getSellerData', ['uses' => 'SellerController@getSellerData']);
    Route::post('/seller/delete', ['uses' => 'SellerController@delete']);
    Route::get('/seller/update-status/{id}', ['uses' => 'SellerController@update_status']);
    Route::get('/seller/update-verify-status/{id}', ['uses' => 'SellerController@update_verify_status']);
    Route::get('/seller/update-block-status/{id}', ['uses' => 'SellerController@update_block_status']);
    Route::get('/seller/update-block-status-verified/{id}', ['uses' => 'SellerController@update_block_status_verified']);
    Route::get('/seller/edit-seller/{id}', ['uses' => 'SellerController@edit']);
    Route::post('/seller/edit-seller/{id}', ['uses' => 'SellerController@update']);
    Route::get('/seller/view/{id}', ['uses' => 'SellerController@view']);
    Route::post('/seller/get-state', ['uses' => 'SellerController@get_state']);
    Route::post('/seller/get-city', ['uses' => 'SellerController@get_city']);
    Route::post('/seller/get-pincode', ['uses' => 'SellerController@getPincode']);
    Route::post('/seller/send-email', ['uses' => 'SellerController@send_email']);
    Route::post('/seller/check-user', ['uses' => 'SellerController@check_user']);
    Route::get('/seller/unverified-seller-list', ['uses' => 'SellerController@unverified_seller']);
    Route::post('/seller/getUnverifiedSellerData', ['uses' => 'SellerController@getUnverifiedSellerData']);
    Route::get('/seller/verified-seller-list', ['uses' => 'SellerController@verified_seller']);
    Route::post('/seller/getVerifiedSellerData', ['uses' => 'SellerController@getVerifiedSellerData']);
    Route::get('/seller/blocked-seller-list', ['uses' => 'SellerController@blocked_seller']);
    Route::post('/seller/getBlockedSellerData', ['uses' => 'SellerController@getBlockedSellerData']);
    Route::post('/product/getSellerProductData', ['uses' => 'SellerController@getSellerProductData']);
    Route::post('/seller/subadmin-permission', ['uses' => 'SellerController@subadmin_permission']);
  
    //Seller Order Route
    Route::get('/sellerorder/verified-seller-order/{id}', ['uses' => 'SellerOrderController@seller_order']);
    Route::post('/sellerorder/getSellerOrderData', ['uses' => 'SellerOrderController@getSellerOrderData']);
    Route::get('/delivery/standard', ['uses' => 'DeliveryController@standard']);
    Route::post('/delivery/standard/{id}', ['uses' => 'DeliveryController@store']);
    Route::post('/delivery/get-data', ['uses' => 'DeliveryController@get_data']);
	
	//Delivery Boy Payment
	Route::get('/delivery-boy-payment', ['uses' => 'DeliveryBoyPaymentController@index']);
	Route::post('/delivery-boy-payment', ['uses' => 'DeliveryBoyPaymentController@getData']);
	Route::get('/delivery-boy-payment/view/{id}', ['uses' => 'DeliveryBoyPaymentController@payment_view']);
	
	//delivery boy payment details
	Route::get('payment-details', ['uses' => 'DeliveryBoyPaymentController@payment_details']);
	Route::post('rider/make-rider-payment', ['uses' => 'DeliveryBoyPaymentController@make_rider_payment']);
	Route::get('test', ['uses' => 'DashboardController@test']);
	
   
});
Route::post('/api-customer-account', 'ApiController@api_customer_account');
Route::post('/change-password', 'ApiController@change_password');
//Route::post('/update-profile-image', 'ApiController@update_profile_image');
//Route::post('/resend-otp', 'ApiController@resend_otp');
Route::post('/check-otp', 'ApiController@check_otp');
Route::post('/api-login', 'ApiController@api_login');
Route::get('/api-get-category','CatalogApiController@get_category');
Route::post('/api-merchant-account','ApiController@api_merchant_account');
Route::get('/home-api','HomepageApiController@home_api');
Route::post('get-child-category-api','HomepageApiController@get_child_category_api');
Route::post('get-product','HomepageApiController@get_product');
Route::post('get-product-by-home-category','HomepageApiController@get_product_by_home_category');
Route::post('get-product-detail','HomepageApiController@get_product_details');
Route::post('check-delivery-pincode','CatalogApiController@check_pincode');
Route::post('search-product','HomepageApiController@search_product');

/*.............Cart ................*/
/*Route::post('add-to-cart','CatalogApiController@add_to_cart');
Route::post('clear-cart','CatalogApiController@clear_cart');
Route::post('get-cart','CatalogApiController@get_cart');
Route::post('update-cart','CatalogApiController@update_cart');
Route::post('get-cart-count','CatalogApiController@get_cart_count');
Route::post('delete-cart','CatalogApiController@delete_cart');*/

/*.........Wishlist ...........*/
Route::post('add-to-wishlist','WishlistApiController@add_to_wishlist');
Route::post('get-wishlist','WishlistApiController@get_wishlist');
Route::post('delete-wishlist','WishlistApiController@delete_wishlist');

/*...................user address ...........*/
Route::post('add-user-address','ApiController@add_user_address');
Route::post('default-address','ApiController@default_address');
Route::post('get-user-address','ApiController@get_user_address');

/*...............Wallet...................*/
Route::post('get-my-wallet','WalletApiController@get_my_wallet');
Route::post('sent-request','WalletApiController@sent_request');
Route::post('/get-wallet-history', 'WalletApiController@get_wallet_history');
Route::post('/get-merchant-wallet-history', 'WalletApiController@get_merchant_wallet_history');
Route::post('/update-wallet-request', 'WalletApiController@update_wallet_request');
Route::post('/get-merchant-wallet', 'WalletApiController@get_merchant_wallet');
Route::post('/update-merchant-payment', 'ApiController@update_merchant_payment');
Route::post('/update-merchant-commission-payment', 'WalletApiController@update_merchant_commission_payment');

/*    Notification Api */
Route::post('/get-merchant-notifications', 'NotificationApiController@get_merchant_notifications');

/*............. Order ..................................*/
Route::post('place-order','CatalogApiController@place_order');
Route::post('change-order-status','CatalogApiController@change_order_status');
Route::post('get-order','CatalogApiController@get_order');
Route::post('get-order-details','CatalogApiController@get_order_details');

/*............. CMS ..................................*/
Route::get('open-source-api','ApiController@open_source');
Route::get('term-condition-api','ApiController@term_condition');
Route::get('privacy-policy-api','ApiController@privacy_policy');
Route::get('about-us-api','ApiController@about_us');
Route::get('guarantee-api','ApiController@guarantee');
/*...............RiderApiController ......*/
Route::post('r-api-login','RiderApiController@api_login');
Route::post('check-notification-status','RiderApiController@check_notification_status');
Route::post('r-forgot-password','RiderApiController@forgot_password_now');
Route::post('rider-logout','RiderApiController@riderLogout');
Route::post('r-check-otp','RiderApiController@check_otp');
Route::post('r-resend-otp','RiderApiController@resend_otp');
Route::post('get-user','RiderApiController@get_user');
Route::post('get-order-history','RiderApiController@get_order_history');
Route::post('change-status','RiderApiController@change_status');
Route::post('dashboard','RiderApiController@dashboard');
Route::post('get-commission','RiderApiController@get_commission');
Route::post('get-notification','RiderApiController@get_notifications');
Route::post('get-commission-wallet','RiderApiController@get_commission_wallet');
Route::post('get-today-commission','RiderApiController@get_today_commission');
Route::get('test-notification','TestController@new_push_notification');
Route::post('cod-list','RiderApiController@cod_list');
Route::post('today-payment','RiderApiController@today_payment');
Route::post('accept-order','RiderApiController@accept_order');
Route::post('assigned-order','RiderApiController@assigned_order');
Route::post('your-earning','RiderApiController@your_earning');
Route::post('deliver-to-customer','RiderApiController@deliver_to_customer');
Route::post('rider-order-details','RiderApiController@order_details');
Route::post('update-rider-profile-image','RiderApiController@updateProfileImage');
Route::post('get-paid-payment','RiderPaymentApiController@get_paid_payment');
Route::post('get-unpaid-payment','RiderPaymentApiController@get_unpaid_payment');
Route::post('get-paid-payment-details','RiderPaymentApiController@paid_order_list');
Route::post('order-list','RiderPaymentApiController@order_list');


/*--------------------Seller Api Route ----------*/

Route::post('add-seller','SellerApiController@add_seller');
Route::post('state','SellerApiController@state');
Route::post('city','SellerApiController@city');
Route::post('seller-login','SellerApiController@seller_login');
Route::post('get-order-list','SellerApiController@get_order_list');
Route::post('seller-payment','SellerApiController@payment');
Route::post('order-details','SellerApiController@order_details');
Route::post('notice-list','SellerApiController@notice_list');
Route::post('paid-payment-list','SellerApiController@paid_payment_list');
Route::post('paid-payment-details','SellerApiController@paid_payment_details');
Route::post('pending-payment-list','SellerApiController@pending_payment_list');
Route::post('pending-payment-details','SellerApiController@pending_payment_details');
Route::post('today-payment-list','SellerApiController@today_payment_list');
Route::post('today-payment-details','SellerApiController@today_payment_details');
Route::post('payment-order-details','SellerApiController@payment_order_details');
Route::post('add-product','SellerApiController@add_product');
Route::post('update-product','SellerApiController@update_product');
Route::post('product-list','SellerApiController@product_list');
Route::post('brand-list','SellerApiController@brand_list');
Route::post('cat-list','SellerApiController@cat_list');
Route::post('sub-cat-list','SellerApiController@sub_cat_list');
Route::post('color-list','SellerApiController@color_list');
Route::post('inventory-in-stock-list','SellerApiController@inventory_in_stock_list');
Route::post('inventory-in-stock-product-list','SellerApiController@inventory_in_stock_product_list');
Route::post('inventory-out-of-stock-list','SellerApiController@inventory_out_of_stock_list');
Route::post('inventory-out-of-stock-product-list','SellerApiController@inventory_out_of_stock_product_list');
Route::post('update-stock-status','SellerApiController@update_stock_status');
Route::post('dashboard-data','SellerApiController@dashboard_data');
Route::post('delete-product','SellerApiController@delete_product');
Route::post('update-seller-step-1','SellerApiController@update_seller_step_1');
Route::post('update-seller-step-2','SellerApiController@update_seller_step_2');
Route::post('update-seller-step-3','SellerApiController@update_seller_step_3');
Route::post('delovery-pincode','SellerApiController@delovery_pincode');
Route::post('notification-list','SellerApiController@notification_list');
Route::post('update-notification-status','SellerApiController@update_notification_status');
Route::post('get-duplicate-product','SellerApiController@get_duplicate_product');
Route::post('add-duplicate-product','SellerApiController@add_duplicate_product');
Route::post('duplicate-product-list','SellerApiController@duplicate_product_list');
Route::post('delete-duplicate-product','SellerApiController@delete_duplicate_product');
Route::post('order-assign-to-rider','SellerApiController@order_assign_to_rider');
Route::post('order-cancel','SellerApiController@order_cancel');
Route::post('return-order-list','SellerApiController@return_order_list');
Route::post('exchange-order-list','SellerApiController@exchange_order_list');
Route::post('user-get-product-item','SellerApiController@getProductItem');
Route::post('seller-add-brand','SellerApiController@sellerAddBrand');
Route::post('seller-update-item-qty','SellerApiController@sellerUpdateItemQty');
Route::post('seller-forgot-password','SellerApiController@sellerForgotPassword');
Route::post('verify-forgot-password-otp','SellerApiController@verifyForgotPasswordOtp');
Route::post('seller-change-password','SellerApiController@sellerChangePassword');


//User api route....
Route::post('user-reg-step-1','UserApiController@user_reg_step_1');
Route::post('user-reg-step-2','UserApiController@user_reg_step_2');
Route::post('resend-otp','UserApiController@resend_otp');
Route::post('login-api','UserApiController@login_api');
Route::post('user-cat-list','UserApiController@catList');
Route::post('user-home','UserApiController@home');
Route::post('product-details','UserApiController@product_details');
Route::post('product-listing','UserApiController@product_listing');
Route::post('add-user-address','UserApiController@addUserAddress');
Route::post('get-user-address','UserApiController@getUserAddress');
Route::post('update-user-address','UserApiController@updateUserAddress');
Route::post('delete-user-address','UserApiController@deleteUserAddress');
Route::post('user-checkout','UserApiController@userCheckout');
Route::post('get-delivery-amount','UserApiController@getDeliveryAmount');
Route::post('user-place-order','UserApiController@placeOrder');
Route::post('user-cod-success','UserApiController@codSuccess');
Route::post('user-update-profile','UserApiController@updateProfile');
Route::post('user-order-list','UserApiController@getOrderList');
Route::post('user-order-details','UserApiController@getOrderDetails');
Route::post('get-user-wallet','UserApiController@getUserWallet');
Route::post('user-call-request','UserApiController@userCallRequest');
Route::post('get-raising-complaint-list','UserApiController@getRaisingComplaintList');
Route::post('user-search','UserApiController@search');
Route::post('get-seller-product-item','UserApiController@getSellerProductItem');
Route::post('user-pages','UserApiController@pages');
Route::post('user-check-pin-availability','UserApiController@checkPinAvailability');
Route::post('user-return-order','UserApiController@userReturnOrder');
Route::post('user-exchange-order','UserApiController@userExchangeOrder');
Route::post('user-order-tracking','UserApiController@userOrderTracking');
Route::post('user-contact-us','UserApiController@userContactUs');
Route::post('user-razorpay-success','UserApiController@userRazorpaySuccess');
Route::post('user-razorpay-faild','UserApiController@userRazorpayFaild');
Route::post('add-user-wallet-amount','UserApiController@addUserWalletAmount');
Route::post('product-type-listing','UserApiController@product_type_listing');
Route::post('get-user-notification','UserApiController@getUserNotification');
Route::post('update-user-view-notify-status','UserApiController@updateUserViewnotifyStatus');
Route::post('filter-data','UserApiController@filterData');
Route::post('filter-data-product-type','UserApiController@filterDataProductType');
Route::post('submit-review-rating','UserApiController@submitReviewRating');
Route::post('update-profile-image','UserApiController@updateProfileImage');
Route::post('user-order-cancle','UserApiController@userOrderCancle');
Route::post('available-city-list','UserApiController@availableCityList');


//Cart api route....
Route::post('add-to-cart','CartApiController@addToCart');
Route::post('get-cart-data','CartApiController@getCartData');
Route::post('cart-count','CartApiController@cartCount');
Route::post('clear-user-cart','CartApiController@clearUserCart');
Route::post('delete-cart','CartApiController@deleteCart');
Route::post('update-cart-data','CartApiController@updateCartData');