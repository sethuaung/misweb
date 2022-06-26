<?php

Route::get('/purchase-order', 'Admin\ReportPurchaseCrudController@purchaseOrder');
Route::get('/purchase-order-detail', 'Admin\ReportPurchaseCrudController@purchaseOrderDetail');
Route::get('/purchase-order-by-item', 'Admin\ReportPurchaseCrudController@purchaseOrderByItem');

Route::get('/bill', 'Admin\ReportPurchaseCrudController@bill');
Route::get('/bill-by-supply-summary', 'Admin\ReportPurchaseCrudController@billBySupplySummary');
Route::get('/bill-detail', 'Admin\ReportPurchaseCrudController@billDetail');
Route::get('/bill-by-item', 'Admin\ReportPurchaseCrudController@billByItem');
Route::get('/bill-by-item-summary', 'Admin\ReportPurchaseCrudController@billByItemSummary');

Route::get('/purchase-list-pop/{id}','Admin\ReportPurchaseCrudController@purchase_list_pop');
Route::get('/bill-list-pop/{id}','Admin\ReportPurchaseCrudController@bill_list_pop');


