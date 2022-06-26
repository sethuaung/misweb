<?php


Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    //'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::get('/payment','PaymentController@index');
    Route::get('/get-supply-info','PaymentController@get_supply_info');
    Route::get('/get-supply-transaction','PaymentController@get_supply_transaction');
    //Route::get('/bill-payment','PaymentController@billpayment');
    Route::get('/discount-pop/{id}','PaymentController@disc_pop');
    Route::get('/get-payment/{id}','PaymentController@billpayment');
    Route::get('sale-payment','SalePaymentController@index');
    Route::get('/get-customer-info','SalePaymentController@get_customer_info');
    Route::get('/get-customer-transaction','SalePaymentController@get_customer_transaction');
    //Route::get('/sale-bill-payment','SalePaymentController@billpayment');
    Route::get('/sale-discount-pop/{id}','SalePaymentController@disc_pop');
    Route::get('/get-sale-payment/{id}','SalePaymentController@billpayment');

});