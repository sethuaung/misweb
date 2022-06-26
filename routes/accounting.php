<?php


Route::get('/account-list', 'Admin\ReportAccountingCrudController@accountList');
Route::get('/cash-transaction', 'Admin\ReportAccountingCrudController@cash_transaction');
Route::get('/trial-balance', 'Admin\ReportAccountingCrudController@trialBalance');
Route::get('/trial-balance-moeyan', 'Admin\ReportAccountingCrudController@trialBalanceMoeyan');
Route::get('/profit-loss', 'Admin\ReportAccountingCrudController@profitLoss');
Route::get('/profit-loss-detail', 'Admin\ReportAccountingCrudController@profitLossDetail');
Route::get('/profit-loss-by-job', 'Admin\ReportAccountingCrudController@profitLossByJob');
Route::get('/profit-loss-by-class', 'Admin\ReportAccountingCrudController@profitLossByClass');
Route::get('/balance-sheet', 'Admin\ReportAccountingCrudController@balanceSheet');
Route::get('/transaction-detail-by-account', 'Admin\ReportAccountingCrudController@transactionDetailByAccount');
Route::get('/cash-statement', 'Admin\ReportAccountingCrudController@cashStatement');
Route::get('/cash-statement-detail', 'Admin\ReportAccountingCrudController@cashStatementDetail');
Route::get('/cash-book', 'Admin\ReportAccountingCrudController@cashBook');
Route::get('/cash-book-detail', 'Admin\ReportAccountingCrudController@cashBookDetail');
Route::get('/cash-statement-moeyan', 'Admin\ReportAccountingCrudController@cashStatementMoeyan');
Route::get('/cash-statement-detail-moeyan', 'Admin\ReportAccountingCrudController@cashStatementDetailMoeyan');




Route::get('/external-account-list', 'Admin\ReportSRDCrudController@accountList');
Route::get('/external-trial-balance', 'Admin\ReportSRDCrudController@trialBalance');
Route::get('/external-profit-loss', 'Admin\ReportSRDCrudController@profitLoss');
Route::get('/external-profit-loss-mkt', 'Admin\ReportSRDCrudController@profitLossMKT');
Route::get('/external-profit-loss-mkt', 'Admin\ReportSRDCrudController@profitLossMKT');

Route::get('/external-profit-loss-detail', 'Admin\ReportSRDCrudController@profitLossDetail');
Route::get('/external-profit-loss-by-job', 'Admin\ReportSRDCrudController@profitLossByJob');
Route::get('/external-profit-loss-by-class', 'Admin\ReportSRDCrudController@profitLossByClass');
Route::get('/external-balance-sheet', 'Admin\ReportSRDCrudController@balanceSheet');
Route::get('/external-balance-sheet-mkt', 'Admin\ReportSRDCrudController@balanceSheetMKT');
Route::get('/external-transaction-detail-by-account', 'Admin\ReportSRDCrudController@transactionDetailByAccount');
Route::get('/external-cash-statement', 'Admin\ReportSRDCrudController@cashStatement');
Route::get('/external-cash-statement-detail', 'Admin\ReportSRDCrudController@cashStatementDetail');
Route::get('/expense-list-pop/{id}', 'Admin\ReportAccountingCrudController@expense_pop');
Route::get('/general-leger', 'Admin\ReportAccountingCrudController@general_leger');
Route::get('/general-leger-moeyan', 'Admin\ReportAccountingCrudController@general_leger_moeyan');
Route::get('/ten-largest-loan', 'Admin\ReportSRDCrudController@ten_largest_loan');
Route::get('/ten-largest-deposit', 'Admin\ReportSRDCrudController@ten_largest_deposit');
Route::get('/market-conduct', 'Admin\ReportSRDCrudController@market_conduct');
Route::get('/prudential-indicators', 'Admin\ReportSRDCrudController@prudential_indicators');
Route::get('/staff-information', 'Admin\ReportSRDCrudController@staff_information');
Route::get('/donor', 'Admin\ReportSRDCrudController@donor');
Route::get('/female-client', 'Admin\ReportSRDCrudController@female_client');
Route::get('/deposit-taking', 'Admin\ReportSRDCrudController@deposit_taking');
Route::get('/monthly-progress', 'Admin\ReportSRDCrudController@monthly_progress');
Route::get('/saving-report', 'Admin\ReportSRDCrudController@saving_report');
Route::get('/frd-report', 'Admin\ReportSRDCrudController@frd_report');

Route::get('/external-portfolio-outreach', 'Admin\ReportSRDCrudController@portfolioOutreach');
// Route::get('/external-ten-largest-loan', 'Admin\ReportSRDCrudController@tenLargestLoan');
// Route::get('/external-ten-largest-deposit', 'Admin\ReportSRDCrudController@tenLargestDeposit');
// Route::get('/external-market-conduct', 'Admin\ReportSRDCrudController@marketConduct');
// Route::get('/external-prudential-indicator', 'Admin\ReportSRDCrudController@prudentialIndicator');

// Route::get('/external-monthly-progress', 'Admin\ReportSRDCrudController@monthlyProgress');
// Route::get('/external-saving', 'Admin\ReportSRDCrudController@saving');
// Route::get('/external-donor-lender-information', 'Admin\ReportSRDCrudController@donorLenderInformation');
// Route::get('/external-staff-information', 'Admin\ReportSRDCrudController@staffInformation');
Route::get('/external-loan-type', 'Admin\ReportSRDCrudController@loanType');
Route::get('/external-section-wise', 'Admin\ReportSRDCrudController@sectionWise');
Route::get('/external-section-wise-outstanding', 'Admin\ReportSRDCrudController@sectionWiseOutstanding');

Route::get('bbb',function (){

return _convertUnit(1,0.9166666667);



    $arr = [
        'name'        => 'status', // the name of the db column
        'label'       => 'Status', // the input label
        'type'        => 'radio',
        'options'     => [ // the key will be stored in the db, the value will be shown as label;
            "Yes" => "Yes",
            "No" => "No"
        ],
        // optional
        //'inline'      => false, // show the radios all on the same line?
    ];


    return json_encode($arr);


});
