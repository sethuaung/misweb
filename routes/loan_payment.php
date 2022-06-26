<?php

Route::get('/co-collection', 'Admin\ReportPaymentCrudController@coCollection');
Route::get('/disbursement-by-co', 'Admin\ReportPaymentCrudController@disbursementByCo');
Route::get('/due-payment', 'Admin\ReportPaymentCrudController@duePayment');
Route::get('/overdue-payment', 'Admin\ReportPaymentCrudController@overDuePayment');





