<?php
Route::get('/plan-repayments', 'Admin\ReportRepaymentCrudController@planRepayments');
Route::get('/plan-late-repayments', 'Admin\ReportRepaymentCrudController@planLateRepayments');
Route::get('/payment-deposits', 'Admin\ReportRepaymentCrudController@paymentDeposits');
Route::get('/loan-disbursements', 'Admin\ReportRepaymentCrudController@loanDisbursements');
Route::get('/loan-repayments', 'Admin\ReportRepaymentCrudController@loanRepayments');
