<?php

Route::get('/supply-list', 'Admin\ReportSupplyCrudController@supplyList');
Route::get('/supply-aging', 'Admin\ReportSupplyCrudController@supplyAging');