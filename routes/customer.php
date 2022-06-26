<?php

Route::get('/customer-list', 'Admin\ReportCustomerCrudController@customerList');
Route::get('/customer-list-by-group', 'Admin\ReportCustomerCrudController@customerByGroup');
Route::get('/customer-list-by-group-price', 'Admin\ReportCustomerCrudController@customerByGroupPrice');
Route::get('/customer-aging', 'Admin\ReportCustomerCrudController@customerAging');
Route::get('/customer-aging-by-group', 'Admin\ReportCustomerCrudController@customerAgingByGroup');
Route::get('/customer-aging-by-group-price', 'Admin\ReportCustomerCrudController@customerAgingByGroupPrice');