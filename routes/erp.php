<?php

//Route::get('/api/saving-product-option', 'Admin\SavingActivatedCrudController@productOptions');
//Route::get('/api/get-item', 'Api\ProductController@index');
//Route::get('/api/get-price-item', 'Api\ItemController@getPrice');
//Route::get('/api/get-item/{id}', 'Api\ProductController@show');
Route::get('/api/get-acc-op', 'Api\ProductController@getAccOp');
Route::get('/api/product', 'Api\ProductController@index');
Route::get('/api/product/{id}', 'Api\ProductController@show');
Route::get('/api/unit', 'Api\UnitController@index');
Route::get('/api/unit/{id}', 'Api\UnitController@show');
Route::post('/api/gen-unit-variant', 'Admin\InventoryCrudController@gen_unit_variant');
Route::get('/api/get-acc-from-category', 'Api\ProductController@getAccFromCategory');
Route::get('/api/jquery-product', 'Api\ProductController@jqueryProduct');
Route::get('/api/jquery-product/{id}', 'Api\ProductController@show');
Route::get('api/get-cost-by-change-unit', 'Admin\PurchaseCrudController@unit_cost_change');
Route::get('/api/authorize-purchase-order-status', 'Admin\AuthorizePurchaseOrderCrudController@authorizePurchase');
Route::get('api/bill-reference', 'Api\BillController@index');
Route::get('api/bill-reference/{id}', 'Api\BillController@show');

