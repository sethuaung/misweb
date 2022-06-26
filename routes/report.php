<?php


Route::get('/product-list-barcode', 'Admin\ReportPrintBarcodeProductCrudController@productList');

Route::get('/product-list', 'Admin\ReportProductCrudController@productList');
Route::get('/product-price-list', 'Admin\ReportProductCrudController@productPriceList');
Route::get('/product-tran', 'Admin\ReportProductCrudController@productTran');
Route::get('/product-tran-in-out', 'Admin\ReportProductCrudController@productTranInOut');
Route::get('/product-tran-in-out-by-month', 'Admin\ReportProductCrudController@productTranInOutByMonth');
Route::get('/product-tran-in-out-by-day', 'Admin\ReportProductCrudController@productTranInOutByDay');
Route::get('/product-detail-pop/{id}', 'Admin\ReportProductCrudController@product_pop');
Route::get('/open_list_pop/{id}', 'Admin\ReportProductCrudController@open_list_pop');
Route::get('/using_list_pop/{id}', 'Admin\ReportProductCrudController@using_list_pop');
Route::get('/group-loan-report', 'Admin\GroupLoarnReportCrudController@group_loan');
Route::get('/group-loan-list', 'Admin\GroupLoarnReportCrudController@group_loan_list');
Route::get('/group-loan-detail', 'Admin\GroupLoarnReportCrudController@group_loan_detail');

Route::get('/summary-report-borrower-wise', 'Admin\ReportSummaryCrudController@borrower_wise');
Route::get('/summary-report-center-wise', 'Admin\ReportSummaryCrudController@center_wise');
Route::get('/summary-report-outstanding', 'Admin\ReportSummaryCrudController@outstanding');


