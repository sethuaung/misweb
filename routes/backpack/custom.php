<?php
Route::group([
    'namespace'  =>  'App\Http\Controllers\Admin',
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', backpack_middleware()],
], function () {
    CRUD::resource('permission', 'PermissionCrudController');
    CRUD::resource('role', 'RoleCrudController');
    CRUD::resource('user', 'UserCrudController');
    CRUD::resource('setting', 'SettingCrudController');
});

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes

    CRUD::resource('account-section', 'AccountSectionCrudController');
    CRUD::resource('account-chart', 'AccountChartCrudController');
    CRUD::resource('account-chart-external', 'AccountChartExternalCrudController');

    CRUD::resource('acc-class', 'AccClassCrudController');
    CRUD::resource('job', 'JobCrudController');

    CRUD::resource('currency', 'CurrencyCrudController');

    CRUD::resource('report-product', 'ReportProductCrudController');
    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    CRUD::resource('ministry', 'MinistryCrudController');
    CRUD::resource('general-department', 'GeneralDepartmentCrudController');
    CRUD::resource('department', 'DepartmentCrudController');
    CRUD::resource('office', 'OfficeCrudController');
    CRUD::resource('people', 'PeopleCrudController');
    CRUD::resource('position', 'PositionCrudController');
    CRUD::resource('manufacturer', 'ManufacturerCrudController');
    CRUD::resource('repair', 'MaintenanceCrudController');
    CRUD::resource('purpose', 'PurposeCrudController');
    CRUD::resource('source', 'SourceCrudController');


    CRUD::resource('general-journal','GeneralJournalCrudController')->with(function (){
        Route::get('general-journal-add-detail','GeneralJournalCrudController@add_detail');
        Route::get('delete-journal/{id}','GeneralJournalCrudController@delete_journal');
        Route::get('print-journal/{id}','GeneralJournalCrudController@print_journal');
        Route::get('print_journal/{id}','GeneralJournalCrudController@printJournal');
        Route::get('attach-document/{id}','GeneralJournalCrudController@attachDocument');
    });
    CRUD::resource('client', 'ClientCrudController');
    Route::get('/black_list', 'ClientCrudController@blacklist');
    CRUD::resource('active-member-client', 'ActiveMemberClientCrudController');
    CRUD::resource('dead-member-client', 'DeadMemberClientCrudController');
    CRUD::resource('report/dead-member-report', 'DeadMemberCrudController');
    CRUD::resource('drop_out-member-client', 'DropOutMemberClientCrudController');
    CRUD::resource('rejoin-member-client', 'RejoinMemberClientCrudController');
    CRUD::resource('substitute-member-client', 'SubstituteMemberClientCrudController');
    CRUD::resource('testing-user-client', 'SubstituteMemberClientCrudController');
    CRUD::resource('reject-client', 'RejectClientCrudController');


    CRUD::resource('working-status', 'WorkingStatusCrudController');
    CRUD::resource('guarantor', 'GuarantorCrudController');
    CRUD::resource('inspector', 'InspectorCrudController');
    CRUD::resource('loan-calculator', 'LoanCalculatorCrudController');
    CRUD::resource('saving-calculator', 'SavingCalculatorCrudController');
    CRUD::resource('compulsory-product', 'CompulsoryProductCrudController');
    CRUD::resource('compulsory-product-type', 'CompulsoryProductTypeCrudController');
    CRUD::resource('default-saving-deposit', 'DefaultSavingDepositCrudController');
    CRUD::resource('default-saving-interest', 'DefaultSavingInterestCrudController');
    CRUD::resource('default-saving-interest-payable', 'DefaultSavingInterestPayableCrudController');
    CRUD::resource('default-saving-withdrawal', 'DefaultSavingWithdrawalCrudController');
    CRUD::resource('default-saving-inter-withdrawal', 'DefaultSavingInterestWithdrawalCrudController');
    CRUD::resource('charge', 'ChargeCrudController');
    CRUD::resource('loan-product', 'LoanProductCrudController');
    CRUD::resource('saving-product', 'SavingProductCrudController');
    CRUD::resource('open-saving-account', 'SavingCrudController');
    CRUD::resource('capital', 'CapitalCrudController');
    CRUD::resource('capital-withdraw', 'CapitalWithdrawCrudController');
    CRUD::resource('shareholder', 'ShareholderCrudController');
    CRUD::resource('asset-type', 'AssetTypeCrudController');
    CRUD::resource('asset', 'AssetCrudController');
    CRUD::resource('current-asset-value', 'CurrentAssetValueCrudController');
    CRUD::resource('collateraltype', 'CollateralTypeCrudController');
    CRUD::resource('saving-activated', 'SavingActivatedCrudController');
    Route::get('saving_book','SavingActivatedCrudController@printSaving');
    Route::get('print-record','SavingActivatedCrudController@printRecord');
    Route::get('saving_book_record','SavingActivatedCrudController@transList');
    Route::get('delete_saving','SavingCrudController@deleteSaving');
    Route::get('edit_saving','SavingCrudController@editSaving');


    CRUD::resource('expense', 'ExpenseCrudController');
    CRUD::resource('expensetype', 'ExpenseTypeCrudController');
    Route::get('print-expense','ExpenseCrudController@print_expense');
    Route::get('cashOut_file/{id}','ExpenseCrudController@cashOutFile');
    CRUD::resource('audit', 'AuditCrudController');
    CRUD::resource('branch', 'BranchCrudController');
    CRUD::resource('customergroup', 'CustomerGroupCrudController');
    CRUD::resource('loandisbursement', 'LoanCrudController');
    CRUD::resource('loan-disbursement-branch', 'LoanByBranchCrudController');

    CRUD::resource('due-repayment-list', 'DueRepaymentCrudController');
    CRUD::resource('late-repayment-list', 'LateRepaymentCrudController');
    CRUD::resource('loanobjective', 'LoanObjectiveCrudController');
    CRUD::resource('transactiontype', 'TransactionTypeCrudController');
    CRUD::resource('comming-repayment', 'CommingRepaymentCrudController');

    CRUD::resource('group-report', 'GroupLoarnReportCrudController');
    CRUD::resource('transaction-history', 'TransactionHistoryCrudController');
    CRUD::resource('summary-report', 'ReportSummaryCrudController');
    //CRUD::resource('cash-transaction', 'cashTransactionCrudController');

    CRUD::resource('report-accounting', 'ReportAccountingCrudController');


    CRUD::resource('report-customer', 'ReportCustomerCrudController');
    CRUD::resource('report-supply', 'ReportSupplyCrudController');
    CRUD::resource('report-purchase', 'ReportPurchaseCrudController');
    CRUD::resource('report-sale', 'ReportSaleCrudController');
    CRUD::resource('report-account-external', 'ReportSRDCrudController');

    CRUD::resource('close-purchase', 'ClosePurchaseCrudController');
    CRUD::resource('close-sale', 'CloseSaleCrudController');
    CRUD::resource('close-account', 'CloseAccountCrudController');
    CRUD::resource('close-all', 'CloseAllCrudController');
    CRUD::resource('transfer', 'TransferCrudController');
    Route::get('/transfer_pop', 'TransferCrudController@transferPop');
    Route::get('/attach_file/{id}', 'TransferCrudController@attachFile');
    CRUD::resource('survey', 'SurveyCrudController');
    CRUD::resource('ownership', 'OwnershipCrudController');
    CRUD::resource('ownershipfarmland', 'OwnershipFarmlandCrudController');
    CRUD::resource('holidayschedule', 'HolidayScheduleCrudController');
    CRUD::resource('centerleader', 'CenterLeaderCrudController');
    CRUD::resource('disbursependingapproval', 'LoanPendingApprovalCrudController');

    CRUD::resource('disburseawaiting', 'LoanAwaitingCrudController')->with(function (){
        Route::get('cancel-approved','LoanAwaitingCrudController@cancel_approved');
    });
    CRUD::resource('disbursedeclined', 'LoanDisburseDeclinedCrudController');
    CRUD::resource('disbursecanceled', 'LoanDisburseCenceledCrudController');
    Route::post('update-loan-disbursement-cancel-status', 'LoanDisburseCenceledCrudController@updateLoanStatus');
    CRUD::resource('disbursewithdrawn', 'LoanWithdrawnCrudController');
    CRUD::resource('disbursewrittenoff', 'LoanWrittenOffCrudController');
    CRUD::resource('loan-write-off', 'WrittenOffCrudController');
    CRUD::resource('disburseclosed', 'LoanClosedCrudController');
    CRUD::resource('depositpending', 'DepositPendingCrudController');
    CRUD::resource('disbursementpending', 'LoanPendingCrudController');

    Route::post('update-loan-disbursement-status', 'LoanPendingApprovalCrudController@updateLoanStatus');
    Route::post('disbursement-pending-approval', 'LoanPendingApprovalCrudController@disbursementPendingApproval');
    Route::post('update-loan-approved-disbursement-status', 'LoanAwaitingCrudController@updateLoanApprovedStatus');
    Route::get('/print_schedule', 'LoanCrudController@printSchedule');
    Route::get('/check_box','LoanPendingApprovalCrudController@checkBox');
    Route::get('/history_pop', 'LoanCrudController@paymentHistory');
    Route::get('/his_transfer', 'LoanTransferCrudController@HistroyTransfer');

    CRUD::resource('compulsorysavinglist', 'CompulsorySavingListCrudController');
    CRUD::resource('compulsorysavingactive', 'CompulsorySavingActiveCrudController');
    CRUD::resource('compulsorysavingcompleted', 'CompulsorySavingCompleteCrudController');

    CRUD::resource('prepaidloan', 'PrePaidLoanCrudController');

    CRUD::resource('loanoutstanding', 'LoanOutstandingCrudController')->with(function (){
        Route::get('cancel-activated','LoanOutstandingCrudController@cancel_activated');
        Route::get('cancel-completed','LoanOutstandingCrudController@rollbackCompleted');
    });
    Route::get('first_date','LoanOutstandingCrudController@firstdate')->name('first_date');
    Route::get('change_date','LoanOutstandingCrudController@changedate')->name('change_date');
    Route::get('repayment_date','LoanRepaymentsReportController@repaymentdate')->name('repayment_date');
    Route::get('/payment_pop', 'LoanOutstandingCrudController@paymentPop');
    CRUD::resource('loanpayment', 'LoanPaymentCrudController');
    CRUD::resource('addloanrepayment', 'AddLoanRepaymentCrudControllerCrudController');
    CRUD::resource('paiddisbursement', 'PaidDisbursementCrudController');
    CRUD::resource('grouploan', 'GroupLoanCrudController');
    CRUD::resource('import-journal', 'ImportJournalCrudController');
    CRUD::resource('import-client', 'ImportClientCrudController');
    CRUD::resource('import-loan', 'ImportLoanCrudController');
    CRUD::resource('import-loan-repayment', 'ImportLoanRepaymentCrudController');
    CRUD::resource('import-compulsory-saving', 'ImportCompulsorySavingActiveCrudController');

   // Route::any('search-group-loan', 'GroupPendingApproveCrudController@search_group');
    CRUD::resource('import-fix-saving-accrued-amount', 'ImportFixSavingAccruedAmountCrudController');
    CRUD::resource('import-saving-accrue-interest', 'ImportFirstSavingAccrueInterestCrudController');
    CRUD::resource('import-acc-chart', 'ImportAccountChartCrudController');


    CRUD::resource('cashwithdrawal', 'CashWithdrawalCrudController');
    CRUD::resource('loandisbursementdeposit', 'LoanDepositCrudController');

    CRUD::resource('nrc-prefix', 'NRCPrefixCrudController');
    CRUD::resource('add-address', 'AddAddressCrudController');

    CRUD::resource('my-paid-disbursement', 'MyPaidDisbursementCrudController');
    Route::get('print-disbursement', 'MyPaidDisbursementCrudController@print_disbursement');
    Route::get('print-contract', 'MyPaidDisbursementCrudController@print_contract');

   // Route::get('print-loan-disburse','LoanDepositCrudController@printLoanDeposit');
    Route::get('print-loan-deposit','LoanDepositCrudController@printLoanDeposit');
    // Route::get('loandisburesementdepositu/excel','LoanDisburesementDepositUCrudController@excel')->name('loandisburesementdepositu.excel');
    CRUD::resource('loandisburesementdepositu', 'LoanDisburesementDepositUCrudController');

    Route::get('/report/payment-deposits/excel', ['uses' => 'PaymentDepositsReportCrudController@excel', 'as' => 'payment-deposits.excel']);
    CRUD::resource('/report/payment-deposits', 'PaymentDepositsReportCrudController');
    Route::get('/report/loan-disbursements/excel', ['uses' => 'LoanDisbursementsReportController@excel', 'as' => 'loan-disbursements.excel']);
    CRUD::resource('/report/loan-disbursements', 'LoanDisbursementsReportController');
    Route::get('/report/loan-repayments/excel', ['uses' => 'LoanRepaymentsReportController@excel', 'as' => 'loan-repayments.excel']);
    CRUD::resource('/report/loan-repayments', 'LoanRepaymentsReportController');

    CRUD::resource('/report/client-information', 'ClientInformationReportController');
    Route::get('/client_pop', 'ClientInformationReportController@clientPop');
    CRUD::resource('/report/loan-outstanding', 'LoanOutstandingReportController');
    CRUD::resource('/report/prepaid-loan', 'PrepaidLoanReportController');
   // CRUD::resource('/report/loan-outstanding2', 'LoanOutstandingReport2Controller');
    /*
    |--------------------------------------------------------------------------
    | Compulsory Saving Reports
    |--------------------------------------------------------------------------
    */
    Route::get('/report/saving-deposit/excel', 'SavingDepositReportController@excel')->name('saving-deposit.excel');
    CRUD::resource('/report/compulsory-saving-deposits', 'CompulsorySavingDepositReportController');
    Route::get('/report/saving-withdrawal/excel', 'SavingWithdrawalReportController@excel')->name('saving-withdrawal.excel');
    CRUD::resource('/report/compulsory-saving-withdrawals', 'CompulsorySavingWithdrawalReportController');
    Route::get('/report/saving-accrued-interest/excel', 'SavingAccruedInterestReportController@excel')->name('saving-accrued-interest.excel');
    CRUD::resource('/report/com-saving-accrued-interests', 'CompulsorySavingAccruedInterestReportController');
    Route::get('/report/print-saving', 'SavingWithdrawalReportController@print_saving');
    CRUD::resource('/report/saving-report', 'SavingReportController');
    // Route::get('/report/savsaving-accrued-interesting/report-excel','SavingAccruedInterestReportController@excel')->name('saving-excel');


    /*
     |--------------------------------------------------------------------------
     | Saving Reports
     |--------------------------------------------------------------------------
     */
    CRUD::resource('/report/saving-deposits', 'SavingDepositReportController');
    CRUD::resource('/report/saving-withdrawals', 'SavingWithdrawalReportController');
    CRUD::resource('/report/saving-interests', 'SavingInterestReportController');


    /*
    |--------------------------------------------------------------------------
    | Officer Reports
    |--------------------------------------------------------------------------
    */
    Route::get('/report/officer-disbursement/excel', 'OfficerDisbursementReportController@excel')->name('officer-disbursement.excel');
    CRUD::resource('/report/officer-disbursement', 'OfficerDisbursementReportController');
    Route::get('/report/officer-collection/excel', 'OfficerCollectionReportController@excel')->name('officer-collection.excel');
    CRUD::resource('/report/officer-collection', 'OfficerCollectionReportController');
    Route::get('/report/officer-transaction/excel', 'OfficerTransactionReportController@excel')->name('officer-transaction.excel');
    CRUD::resource('/report/officer-transaction', 'OfficerTransactionReportController');
    Route::get('/report/staff-performance/excel', 'StaffPerformanceReportController@excel')->name('staff-performance.excel');
    CRUD::resource('/report/staff-performance', 'StaffPerformanceReportController');

    if(companyReportPart() == 'company.mkt'){
        CRUD::resource('/report/plan-late-repayments','LateRepaymentCrudController');//PlanLateRepaymentReportController
    }else{
        CRUD::resource('/report/plan-late-repayments','PlanLateRepaymentReportController');
    }
    CRUD::resource('/report/plan-repayments','PlanRepaymentReportController');
    CRUD::resource('/report/plan-due-repayments','PlanDueRepaymentReportController');

    CRUD::resource('clientcenterleader', 'ClientCenterLeaderCrudController');
    CRUD::resource('compulsory-saving-transaction', 'CompulsorySavingTransactionCrudController');
    CRUD::resource('group-loan-approve', 'GroupPendingApproveCrudController');
    CRUD::resource('group-dirseburse', 'GroupDirseburseCrudController');


    CRUD::resource('group-repayment', 'GroupRepaymentCrudController');

    CRUD::resource('group-loan-deposit', 'GroupLoanDepositCrudController');

    Route::post('group-loan-deposit-multiple', 'GroupLoanDepositCrudController@groupLoanDepositMultiple');
    Route::get('group-loan-detail', 'GroupPendingApproveCrudController@group_detail');

    //CRUD::resource('group-loan-approve', 'GroupPendingApproveCrudController');
    CRUD::resource('list-member-pending', 'ListMemberPendingCrudController');
    CRUD::resource('list-member-dirseburse', 'ListMemberDirseburseCrudController');
    CRUD::resource('list-member-repayment', 'ListMemberRepaymentCrudController');
    CRUD::resource('list-due-member-repayment', 'ListDueMemberRepaymentCrudController');
    CRUD::resource('list-member-deposit', 'ListMemberDepositCrudController');
    CRUD::resource('report-loan-client', 'ReportCrudController');
    Route::post('customer_loan_deposit', 'ListMemberDepositCrudController@customer_loan_deposit');


    Route::get('notification-payment', 'NotificationPaymentController@notificationPayment');

    //-----------------------------------
    CRUD::resource('report-payment', 'ReportPaymentCrudController');
    CRUD::resource('report-repayment', 'ReportRepaymentCrudController');
    CRUD::resource('report-loan', 'ReportLoanCrudController');
    CRUD::resource('loan-pending-transfer', 'LoanPendingTransferCrudController');
    CRUD::resource('loan-transfer', 'LoanTransferCrudController');
    CRUD::resource('approve-client-pending', 'ApproveClientPendingCrudController');
    CRUD::resource('authorize-client-pending', 'AuthorizeClientPendingCrudController');


    Route::get('search-group-loan-disburse', 'GroupDirseburseCrudController@search_group');
    Route::get('search-group-loan-deposit', 'GroupLoanDepositCrudController@search_group');
    Route::get('search-group-repayment', 'GroupRepaymentCrudController@search_group');
    CRUD::resource('group-due-repayment', 'GroupDueRepaymentCrudController');
    CRUD::resource('group-repayment-new', 'GroupRepaymentNewCrudController');
    Route::get('search-group-loan', 'GroupPendingApproveCrudController@search_group');
    CRUD::resource('expense','ExpenseCrudController')->with(function (){
        Route::get('expense-add-detail','ExpenseCrudController@add_detail');
        Route::get('delete-expense/{id}','ExpenseCrudController@delete_expense');
    });
    CRUD::resource('journal-profit','JournalProfitCrudController')->with(function (){
        Route::get('profit-add-detail','JournalProfitCrudController@add_detail');
    });
    CRUD::resource('paid-support-fund', 'PaidSupportFundCrudController')->with(function (){
        Route::get('change-fund-type','PaidSupportFundCrudController@change_fund_type');
    });
    Route::get('schedule-remark', 'LoanCalculatorCrudController@saveRemark');
    //-----------------------------------
    CRUD::resource('/report/plan-disbursements', 'PlanDisbursementCrudController');
    Route::get('update-client-confirm/{id}', 'MyPaidDisbursementCrudController@update_client_confirm');
    Route::get('update-center-leader-status/{id}','ClientCenterLeaderCrudController@update_status');
    Route::get('update-expense-pag/{pag}','ExpenseCrudController@update_expense_pag');
    Route::get('update-general-journal-pag/{pag}','GeneralJournalCrudController@update_g_journal_pag');

    CRUD::resource('frd-account-setting','FrdAccountSettingCrudController')->with(function (){
        Route::get('update-frd-profit-loss','FrdAccountSettingCrudController@update_frd_profit_loss');
    });

    CRUD::resource('frd-account','FrdAccountSetting2CrudController')->with(function (){
        Route::get('update-frd-profit-loss2','FrdAccountSetting2CrudController@update_frd_profit_loss');
    });

    Route::get('regenerate-schedule/{loan_id}','LoanCrudController@regenerateSchedule');
    Route::get('regenerate-schedule-all','LoanCrudController@view');
    Route::get('regenerate-loan','LoanCrudController@regenerateScheduleAll')->name('regenerateloan');
    Route::get('regenerate-loan-one','LoanCrudController@regenerateScheduleOne')->name('regenerateloanone');
    Route::get('regenerate-loan-number','LoanCrudController@regenerateScheduleLoan')->name('regenerateloannumber');
    Route::get('delete-loan-number','LoanCrudController@deleteLoan')->name('deleteloannumber');


    CRUD::resource('approve-loan-payment-pending', 'ApproveLoanPaymentPendingCrudController');
    CRUD::resource('authorize-loan-payment-pending', 'AuthorizeLoanPaymentPendingCrudController');
    CRUD::resource('deposit-saving', 'DepositSavingCrudController');
    CRUD::resource('approve-loan-payment', 'ApproveLoanPaymentTemCrudController');
    CRUD::resource('authorize-loan-payment', 'AuthorizeLoanPaymentTemCrudController');


    if (companyReportPart() == 'company.mkt'){
        Route::get('dashboard', function (){
            return view('vendor.backpack.base.dashboard_mkt');
        });
    }

    CRUD::resource('saving-deposit', 'SavingDepositCrudController');

    Route::get('/export-loan-outstanding','LoanOutstandingReportController@export')->name('export-branch');
    if(companyReportPart() == 'company.moeyan'){
        Route::get('/export-excel','ReportAccountingCrudController@exportmoeyan')->name('export-excel');
    }else{
        Route::get('/export-excel','ReportAccountingCrudController@export')->name('export-excel');
    }
    Route::get('/export-excel-confirm','ReportAccountingCrudController@exportconfirm')->name('export-excel-confirm');
    Route::get('/print-excel','GeneralJournalCrudController@export')->name('print-excel');
    Route::get('/excel-confirm','GeneralJournalCrudController@exportconfirm')->name('excel-confirm');
    Route::get('/cash-excel','ReportAccountingCrudController@cashexport')->name('cash-excel');
    Route::get('phpinfo', function (){
        dd(phpinfo());
    });

    CRUD::resource('saving-transaction', 'SavingTransactionController');
    Route::post('remove-saving-transaction', 'SavingTransactionController@remove_tran');
    CRUD::resource('saving-withdrawal', 'SavingWithdrawalCrudController');
    CRUD::resource('product', 'ProductCrudController');
    CRUD::resource('loan-item', 'LoanItemCrudController');
    CRUD::resource('loan-item-pending', 'PendingLoanItemCrudController');
    CRUD::resource('loan-item-approved', 'ApproveLoanItemCrudController');
    CRUD::resource('loan-item-disbursement', 'DisburseLoanItemCrudController');
    CRUD::resource('loan-item-activated', 'ActivatedLoanItemCrudController');
    CRUD::resource('loan-item-complete', 'LoanItemCompleteCrudController');
    CRUD::resource('list-deposit-loan-item', 'ListDepositLoanItemCrudController');
    CRUD::resource('list-disbursement-loan-item', 'ListDisbursementLoanItemCrudController');

    CRUD::resource('inventory', 'InventoryCrudController');
    CRUD::resource('product-category', 'ProductCategoryCrudController');
    CRUD::resource('warehouse', 'WarehouseCrudController');
    CRUD::resource('unit', 'UnitCrudController');
    CRUD::resource('purchase-order', 'PurchaseOrderCrudController');
    CRUD::resource('authorize-purchase-order', 'AuthorizePurchaseOrderCrudController');
    CRUD::resource('approve-purchase-order', 'ApprovePurchaseOrderCrudController');
    CRUD::resource('bill', 'BillCrudController');
    CRUD::resource('purchase-return', 'PurchaseReturnCrudController');
    CRUD::resource('goods-received', 'GoodsReceivedCrudController');
    CRUD::resource('bill-received', 'BillReceivedCrudController');
    CRUD::resource('payment-list', 'PaymentCrudController');
    CRUD::resource('supply', 'SupplyCrudController');
    CRUD::resource('supply', 'SupplyCrudController');
    Route::get('/payment','PaymentController@index');
    Route::get('/get-supply-info','PaymentController@get_supply_info');
    Route::get('/get-supply-transaction','PaymentController@get_supply_transaction');
    //Route::get('/bill-payment','PaymentController@billpayment');
    Route::get('/discount-pop/{id}','PaymentController@disc_pop');
    Route::get('/get-payment/{id}','PaymentController@billpayment');


    Route::get('show-online-users',function (){
        $all_users = \App\User::select('id')->get();
        $online_users='';
        $n=0;
        foreach ($all_users as $a){
            if($a->isOnline()){
                $online_users.=$a->id.', ';
                $n++;
            }

        }

        dd('Current user online id: '.$online_users,'Total online user: '.$n);
    });

}); // this should be the absolute last line of this file
