<?php

Route::get('/sale-order', 'Admin\ReportSaleCrudController@saleOrder');
Route::get('/sale-order-detail', 'Admin\ReportSaleCrudController@saleOrderDetail');
Route::get('/sale-order-by-item', 'Admin\ReportSaleCrudController@saleOrderByItem');
Route::get('/sale-list-pop/{id}','Admin\ReportSaleCrudController@sale_list_pop');
Route::get('/delivery-list-pop/{id}','Admin\ReportSaleCrudController@delivery_list_pop');

Route::get('/invoice', 'Admin\ReportSaleCrudController@invoice');
Route::get('/invoice-by-customer-summary', 'Admin\ReportSaleCrudController@invoiceByCustomerSummary');
Route::get('/invoice-detail', 'Admin\ReportSaleCrudController@invoiceDetail');
Route::get('/invoice-by-item', 'Admin\ReportSaleCrudController@invoiceByItem');
Route::get('/invoice-by-item-summary', 'Admin\ReportSaleCrudController@invoiceByItemSummary');

///
///
Route::get('/plan-loan-repayment', 'Admin\ReportSaleCrudController@planLoanRepayment');
Route::get('/loan-repayment', 'Admin\ReportSaleCrudController@loanRepayment');
Route::get('/loan-disbursement', 'Admin\ReportSaleCrudController@loanDisbursement');
Route::get('/loan-activated', 'Admin\ReportSaleCrudController@loanActivated');


