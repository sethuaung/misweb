<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\GeneralJournalDetail;
use App\Models\GeneralJournalDetailTem;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/dellll', function () {


    $rs = \Illuminate\Support\Facades\DB::select("
SELECT l.disbursement_number  FROM loans as l
GROUP BY l.disbursement_number
HAVING COUNT(l.disbursement_number)>1");

foreach ($rs as $r){
    $rrrr  =  \Illuminate\Support\Facades\DB::select("SELECT s.client_number,ll.*  FROM loans as ll
INNER JOIN clients as s ON ll.client_id = s.id
WHERE ll.disbursement_number = '".$r->disbursement_number."'
ORDER BY ll.disbursement_number
LIMIT 1
;");
}
        foreach ($rrrr as $t){
            $id = $t->id;




            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM loans WHERE id = '{$id}' ;");
            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM group_loan_details WHERE loan_id = '{$id}'  ;");
            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM loan_disbursement_calculate WHERE disbursement_id = '{$id}' ;");
            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM paid_disbursements WHERE contract_id = '{$id}' ;");
            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM loan_payments WHERE disbursement_id = '{$id}' ;");
            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM loan_compulsory WHERE loan_id = '{$id}' ;");
            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM compulsory_saving_transaction WHERE loan_id = '{$id}' ;");
            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM compulsory_accrue_interests WHERE loan_id = '{$id}' ;");
            \Illuminate\Support\Facades\DB::unprepared("DELETE FROM compulsory_saving_transaction WHERE loan_id = '{$id}' ;");

        }

        return 'OK';

});



Route::get('/api/savingInterestDefualt', 'Api\AccrueInterestCompulsory@savingInterestDefualt');              ///// Quicekn and Angkor
Route::get('/api/savingInterestAngkorMigrate', 'Api\AccrueInterestCompulsory@savingInterestAngkorMigrate');
Route::get('/api/get-data-saving-product', 'Admin\SavingCrudController@getSavingProduct');
Route::get('/api/get-saving-deposit-schedule', 'Admin\SavingCrudController@getSavingSchedule2');



Route::get('/api/savingInterestMKT', 'Api\AccrueInterestCompulsory@savingInterestMKT');
Route::get('/api/savingInterestMKT09', 'Api\AccrueInterestCompulsory@savingInterestMKT09');  ////// for september
Route::get('/api/savingInterestMKT10', 'Api\AccrueInterestCompulsory@savingInterestMKT10');  ////// for october
Route::get('/api/savingIntMKT', 'Api\AccrueInterestCompulsory@savingIntMKT');  ////// for all

Route::get('/api/savingInterestQuicken', 'Api\AccrueInterestCompulsory@savingInterestQuicken');              ///// Quicekn and Angkor
////// for saving accrued interest moeyan
Route::get('/api/manageSavingIntMoeyan', 'Api\AccrueInterestCompulsory@manageSavingIntMoeyan');  ////// for saving accrued interest moeyan
Route::get('/api/manageSavingIntMKT', 'Api\AccrueInterestCompulsory@manageSavingIntMKT');  ////// for saving accrued interest mkt
Route::get('/api/manageSavingIntMKT2', 'Api\AccrueInterestCompulsory@manageSavingIntMKT2');  ////// for saving accrued interest mkt
Route::get('/api/rollbackSavingInts', 'Api\AccrueInterestCompulsory@rollbackSavingInts');


Route::post('/api/gen-unit-variant', 'Admin\InventoryCrudController@gen_unit_variant');
Route::get('/api/product_unit_price_group', 'Admin\ProductPriceGroupCrudController@product_unit_price_group');
Route::get('/api/gen-unit-price-group', 'Admin\ProductPriceGroupCrudController@gen_unit_price_group');

Route::get('/api/open-item-select-product', 'Admin\OpenItemCrudController@selectProduct');
Route::get('/api/get-open-warehouse-detail', 'Admin\OpenItemCrudController@getWarehouseDetail');

Route::get('/api/product', 'Api\ProductController@index');
Route::get('/api/product/{id}', 'Api\ProductController@show');

Route::get('api/get-frd-acc-code', 'Api\FrdController@index');
Route::get('/api/get-frd-acc-code/{id}', 'Api\FrdController@show');

Route::get('/api/province', 'Api\ProvinceController@index');
Route::get('/api/province/{id}', 'Api\ProvinceController@show');

Route::get('/api/district', 'Api\DistrictController@index');
Route::get('/api/district/{id}', 'Api\DistrictController@show');

Route::get('/api/commune', 'Api\CommuneController@index');
Route::get('/api/commune/{id}', 'Api\CommuneController@show');

Route::get('/api/village', 'Api\VillageController@index');
Route::get('/api/village/{id}', 'Api\VillageController@show');

Route::get('/api/acc-chart', 'Api\AccChartController@index');
Route::get('/api/acc-chart/{id}', 'Api\AccChartController@show');
Route::get('/api/find_chart_acc', 'Api\AccChartController@getChart');
Route::get('/api/type-option', 'Admin\AccountChartCrudController@typeOptions');
Route::get('/api/acc-chart-frd', 'Api\AccChartFrdController@index');
Route::get('/api/acc-chart-frd/{id}', 'Api\AccChartFrdController@show');
Route::get('/api/acc-chart-frd2', 'Api\AccChartFrd2Controller@index');
Route::get('/api/acc-chart-frd2/{id}', 'Api\AccChartFrd2Controller@show');
Route::get('/api/acc-chart-external', 'Api\AccChartExternalController@index');
Route::get('/api/acc-chart-external/{id}', 'Api\AccChartExternalController@show');


Route::get('/api/supplier', 'Api\SupplierController@index');
Route::get('/api/supplier/{id}', 'Api\SupplierController@show');

// Route::get('/api/nrc-prefix', 'Api\NRCprefixController@index');
Route::get('/api/nrc-prefix/{id}', 'Api\NRCprefixController@index');
// Route::get('/api/nrc-prefix/{id}', 'Api\NRCprefixController@show');
Route::get('/api/nrc-check/{state}/{nrc}/{type}', function($state, $nrc, $type){
    if($type == "client"){
        return \App\Models\Client::where('nrc_number', ($state. '/' . $nrc))->first();
    }else if($type == "guarantor"){
        return \App\Models\Guarantor::where('nrc_number', ($state. '/' . $nrc))->first();
    }
});

Route::get('/api/guarantor-check/{state}/{nrc}', function($state, $nrc) {
    return \App\Models\Guarantor::where('nrc_number', ($state. '/'. $nrc))->first();
});

Route::get('admin/savings-report', 'Admin\SavingReportController@savingsReport');
Route::get('admin/interest-cal-report', 'Admin\SavingReportController@interestCalReport');
Route::get('api/search-general-journal', 'Admin\GeneralJournalCrudController@list_detail');
Route::get('api/search-expense', 'Admin\ExpenseCrudController@expense');
Route::get('api/search-profit', 'Admin\JournalProfitCrudController@profit');
Route::get('admin/journal-profit-delete/{id}', 'Admin\JournalProfitCrudController@delete');
Route::get('admin/profit-print/{id}', 'Admin\JournalProfitCrudController@print');
Route::get('admin/cashIn_file/{id}', 'Admin\JournalProfitCrudController@cashInFile');
Route::get('admin/expense-delete/{id}', 'Admin\ExpenseCrudController@delete_expense');

Route::get('admin/saving-download/{id}', 'Admin\SavingTransactionController@saving');

Route::get('/api/get-working-status', 'Api\WorkingStatusController@index');
Route::get('/api/get-working-status/{id}', 'Api\WorkingStatusController@show');


Route::get('/api/get-user', 'Api\UserController@index');
Route::get('/api/get-user/{id}', 'Api\UserController@show');

Route::get('/api/compulsory', 'Api\CompulsoryProductController@index');
Route::get('/api/compulsory/{id}', 'Api\CompulsoryProductController@show');
Route::get('/admin/all_approve', 'AllApproveController@index')->name('approve_all');
Route::get('/admin/all_active', 'AllActiveController@index')->name('active_all');
Route::get('/admin/select_repayment', 'SelectRepaymentController@index')->name('select_repayment');
Route::get('/admin/mobile_approve', 'SelectRepaymentController@mobile_approve')->name('mobile_approve');
Route::get('/admin/client_auth_mobile_approve', 'Admin\AuthorizeClientPendingCrudController@client_auth_mobile_approve')->name('client_auth_mobile_approve');


Route::post('/api/save-location', 'Admin\WarehouseCrudController@saveLocation');
Route::post('/api/del-location', 'Admin\WarehouseCrudController@delLocation');
Route::get('/api/add-location', 'Admin\WarehouseCrudController@addLocation');
Route::get('/api/save-location', 'Admin\WarehouseCrudController@saveLocation');



//===================Purchase=================
Route::get('/api/purchase-select-product', 'Admin\PurchaseCrudController@selectProduct');
Route::get('api/get-supplier-currency_id', 'Admin\PurchaseCrudController@get_supply_currency');


// compulsory product type
Route::get('/api/get-compulsory-product-type', 'Api\CompulsoryProductTypeController@index');
Route::get('/api/get-compulsory-product-type/{id}', 'Api\CompulsoryProductTypeController@show');

Route::get('/api/get-default-saving-deposit', 'Api\DefaultSavingDepositController@index');
Route::get('/api/get-default-saving-deposit/{id}', 'Api\DefaultSavingDepositController@show');

Route::get('/api/get-default-saving-interest', 'Api\DefaultSavingInterestController@index');
Route::get('/api/get-default-saving-interest/{id}', 'Api\DefaultSavingInterestController@show');

Route::get('/api/get-default-saving-interest-payable', 'Api\DefaultSavingInterestPayableController@index');
Route::get('/api/get-default-saving-interest-payable/{id}', 'Api\DefaultSavingInterestPayableController@show');

Route::get('/api/get-default-saving-withdrawal', 'Api\DefaultSavingWithdrawalController@index');
Route::get('/api/get-default-saving-withdrawal/{id}', 'Api\DefaultSavingWithdrawalController@show');

Route::get('/api/get-default-saving-interest-withdrawal', 'Api\DefaultSavingInterestWithdrawalController@index');
Route::get('/api/get-default-saving-interest-withdrawal/{id}', 'Api\DefaultSavingInterestWithdrawalController@show');

/* charge */
Route::get('/api/get-charge', 'Api\ChargeController@index');
Route::get('/api/get-charge/{id}', 'Api\ChargeController@show');


Route::get('/api/add-charge', 'Admin\LoanProductCrudController@addCharge');


Route::get('/api/get-asset-type', 'Api\AssetTypeController@index');
Route::get('/api/get-asset-type/{id}', 'Api\AssetTypeController@show');


//------------------------------------------------


Route::get('/api/get-loan-objective', 'Api\LoanObjectiveController@index');
Route::get('/api/get-loan-objective/{id}', 'Api\LoanObjectiveController@show');


Route::get('/api/get-guarantor', 'Api\GuarantorController@index');
Route::get('/api/get-guarantor/{id}', 'Api\GuarantorController@show');

Route::get('/api/get-inspector', 'Api\InspectorController@index');
Route::get('/api/get-inspector/{id}', 'Api\InspectorController@show');


Route::get('/api/get-client', 'Api\ClientController@index');
Route::get('/api/get--loand-disbursement/{id}', 'Admin\AddLoanRepaymentCrudControllerCrudController@loanDisbursment');
Route::get('/api/get-client/{id}', 'Api\ClientController@show');
Route::get('/api/get-client-g/{id}', 'Api\ClientController@showG');


Route::get('/api/get-branch', 'Api\BranchController@index');
Route::get('/api/get-branch/{id}', 'Api\BranchController@show');

Route::get('/api/get-branch-u', 'Api\BranchUController@index');
Route::get('/api/get-branch-u/{id}', 'Api\BranchUController@show');
Route::get('/api/get-branch-code', 'Api\BranchUController@getBranch');

Route::get('/api/get-customer-group', 'Api\CustomerGroupController@index');
Route::get('/api/get-customer-group/{id}', 'Api\CustomerGroupController@show');

Route::get('/api/get-center-leader', 'Api\CenterLeaderController@index');
Route::get('/api/get-center-leader/{id}', 'Api\CenterLeaderController@show');
Route::get('/api/get-group-loan-code', 'Admin\GroupLoanCrudController@getGroupCode');
Route::get('/api/get-group-id/', 'Api\GroupIdController@index');
Route::get('/api/get-group-id/{id}', 'Api\GroupIdController@show');


Route::get('/api/get-user', 'Api\UserController@index');
Route::get('/api/get-user/{id}', 'Api\UserController@show');


Route::get('/api/shareholder', 'Api\ShareholderController@index');
Route::get('/api/shareholder/{id}', 'Api\ShareholderController@show');

Route::get('/api/customer', 'Api\CustomerController@index');
Route::get('/api/customer/{id}', 'Api\CustomerController@show');


Route::get('/api/get-loan-product', 'Api\LoanProductController@index');
Route::get('/api/get-loan-product/{id}', 'Api\LoanProductController@show');
Route::get('/api/get-loan-product2', 'Admin\LoanCrudController@loanOptions');


Route::get('/api/get-saving-product', 'Api\SavingProductController@index');


Route::get('/api/get-center-leader-name', 'Api\CenterLeanderNameController@index');
Route::get('/api/get-center-leader-name/{id}', 'Api\CenterLeanderNameController@show');

Route::get('api/get-payment-schedule', 'Admin\LoanCrudController@get_payment_schedule');
Route::get('api/get-payment-schedule2', 'Admin\LoanCrudController@get_payment_schedule2');
Route::get('api/get-charge-service', 'Admin\LoanCrudController@get_charge_service');

Route::get('api/get-first-date-payment', 'Admin\LoanCrudController@getFirstDatePayment');
Route::get('api/get-loan-calculator', 'Admin\LoanCalculatorCrudController@getLoanCalculation');
Route::get('api/print-loan-payment', 'Admin\LoanPaymentCrudController@printPayment');
Route::get('api/find_client', 'Api\ClientController@getClient');
Route::get('api/find_client_loan', 'Api\ClientController@getLoan');

Route::get('/api/search-client', 'Admin\LoanOutstandingCrudController@clientOptions');
Route::get('/api/loan-repayment-list', 'Admin\LoanPaymentCrudController@loanRepaymentList');
Route::get('/api/delete-payment', 'Admin\LoanPaymentCrudController@deletePayment');
Route::get('/api/del-flex-payment', 'Admin\LoanPaymentCrudController@deleteFlexiblePayment');
Route::get('/api/delete-all-payments', 'Admin\LoanPaymentCrudController@deleteAllPayments');
Route::get('/api/delete-duplicates', 'Admin\LoanPaymentCrudController@deleteDuplicates');
Route::get('/api/list-last-payment', 'Admin\LoanPaymentCrudController@paymentList');
//Route::get('/api/get-loan-id', 'Admin\AddLoanRepaymentCrudControllerCrudController@getloandid');

// Repayment Lists
// Route::get('due-repayment-list', 'Admin\LoanPaymentCrudController@dueRepaymentList');
// Route::get('late-repayment-list', 'Admin\LoanPaymentCrudController@lateRepaymentList');
//Route::get('admin/report/plan-repayments','Admin\ReportController@planRepayments');
// Route::get('admin/report/plan-repayments-data','Admin\ReportController@planRepaymentsData');
//Route::get('admin/report/plan-late-repayments','Admin\ReportController@planLateRepayments');
// Route::get('admin/report/payment-deposits','Admin\ReportController@paymentDeposits');
// Route::get('admin/report/plan-repayments','Admin\ReportRepaymentCrudController@planRepayments');
// Route::controller('datatables', 'DatatablesController', [
//     'anyData'  => 'datatables.data',
//     'getIndex' => 'datatables',
// ]);

Route::get('/api/client-option', 'Admin\LoanCrudController@clientOptions');
Route::get('/api/client-number', 'Admin\LoanCrudController@clientNumber');
Route::get('/api/guarantor-option', 'Admin\LoanCrudController@guarantorOptions');
Route::get('/api/loan-product-option', 'Admin\LoanCrudController@loanProductOptions');
Route::get('/api/loan-officer-option', 'Admin\LoanCrudController@loanOfficerOptions');
Route::get('/api/get-group-loan-option', 'Admin\LoanCrudController@groupLoanOptions');
Route::get('/api/loan-option', 'Admin\LoanDisburesementDepositUCrudController@loanOptions');
Route::get('/api/branch-option', 'Admin\LoanCrudController@branchOptions');
Route::get('/api/branch-option/branch', 'Admin\LoanCrudController@branchselect');

Route::get('/api/center-option', 'Admin\LoanCrudController@centerOptions');
Route::get('/api/center-option-by-branch', 'Admin\LoanCrudController@centerOptionsbybranch');
Route::get('/api/saving-option', 'Admin\CompulsorySavingListCrudController@saving_Options');
Route::get('/api/branch_Option', 'Admin\CompulsorySavingListCrudController@branch_Options');

Route::get('/api/customer-option', 'Admin\ClientCrudController@customerOptions');

Route::get('/api/client-address', 'Api\VillageController@clientAddress');
Route::get('/api/update-branch', 'Admin\LoanByBranchCrudController@updateBranch');
Route::get('/api/authorize_client_pending_status', 'Admin\AuthorizeClientPendingCrudController@authorize_client');


Route::get('exx', function (\Illuminate\Http\Request $request){

    header('Content-type: application/excel');
    $filename = time().'filename.xls';
    header('Content-Disposition: attachment; filename='.$filename);

    $data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
                <head>
                    <!--[if gte mso 9]>
                    <xml>
                        <x:ExcelWorkbook>
                            <x:ExcelWorksheets>
                                <x:ExcelWorksheet>
                                    <x:Name>Sheet 1</x:Name>
                                    <x:WorksheetOptions>
                                        <x:Print>
                                            <x:ValidPrinterInfo/>
                                        </x:Print>
                                    </x:WorksheetOptions>
                                </x:ExcelWorksheet>
                            </x:ExcelWorksheets>
                        </x:ExcelWorkbook>
                    </xml>
                    <![endif]-->
                </head>

                <body>';

    $data .= $request->excel_date;

    $data .=  '
                </body>
                </html>';

    echo $data;
});


Route::get('/api/get-group-loan', 'Api\GroupLoanController@index');
Route::get('/api/get-group-loan/{id}', 'Api\GroupLoanController@show');
Route::get('/api/get-center-leader-name-id', 'Api\CenterLeanderIdNameController@index');
Route::get('/api/get-center-leader-branch', 'Api\CenterLeanderIdNameController@branch')->name('center-leader-branch');
Route::get('/api/get-center-leader-name-id/{id}', 'Api\CenterLeanderIdNameController@show');


Route::get('/api/get-group-loan2', 'Api\GroupLoan2Controller@index');
Route::get('/api/get-group-loan-center', 'Api\GroupLoan2Controller@center')->name('group-loan-center');
Route::get('/api/get-group-loan2/{id}', 'Api\GroupLoan2Controller@show');


Route::get('/api/get-loan-disbursement', 'Api\LoanController@index');
Route::get('/api/get-loan-disbursement-new', 'Api\LoanActiveController@index');
Route::get('/api/get-loan-disbursement/{id}', 'Api\LoanController@show');
Route::get('/api/get-loan-number', 'Api\LoanNumberController@index');
Route::get('/api/get-loan-number/{id}','Api\LoanNumberController@show');
// ============= payment number
Route::get('/api/get-loan-payment-number', 'Api\LoanActiveController@index');
Route::get('/api/get-loan-payment-number/{id}', 'Api\LoanController@show');

Route::get('/api/get-loan-disbursement-d', 'Api\LoanDepositController@index');
Route::get('/api/get-loan-disbursement-d/{id}', 'Api\LoanDepositController@show');

//Repair Wrong acc code according to branch
Route::get('/admin/repairacccode', 'RepairAccCodeController@repair');

//For Add Address
Route::post('/admin/add-state','InsertAddressController@state')->name('state');
Route::post('/admin/add-township','InsertAddressController@township')->name('township');
Route::post('/admin/add-village','InsertAddressController@village')->name('village');
Route::post('/admin/add-ward','InsertAddressController@ward')->name('ward');


Route::get('/api/loan-disbursement-deposit', 'Admin\LoanDepositCrudController@loanDisbursementDeposit');
Route::get('api/change-branch', function (\Illuminate\Http\Request $request){
    $branch_id = $request->branch_id;
    session(['s_branch_id' => $branch_id]);
});


Route::get('/api/get-loan-disbursement-od', 'Api\LoanUController@index');
Route::get('/api/get-loan-disbursement-od/{id}', 'Api\LoanUController@show');
Route::post('/api/dashboard_search', 'Api\ReportController@dashboard');
Route::get('/admin/dashboard_mkt','Api\ReportController@dashboard_data')->name('dashboard_mkt');
Route::get('/api/download-journal-expense', 'Admin\ImportJournalCrudController@download_excel');
Route::get('/api/download-general-journal-excel/{id}', 'Admin\ImportJournalCrudController@excel_download');
Route::get('/api/download-client', 'Admin\ImportClientCrudController@download_excel');
Route::get('/api/download-client-excel/{id}', 'Admin\ImportClientCrudController@excel_download');
Route::get('/api/download-loan', 'Admin\ImportLoanCrudController@download_excel');
Route::get('/api/download-loan', 'Admin\ImportLoanCrudController@download_excel');
Route::get('/api/download-excel/{id}', 'Admin\ImportLoanCrudController@excel_download');
Route::get('/api/download-loan-payment', 'Admin\ImportLoanRepaymentCrudController@download_excel');
Route::get('/api/download-compulsory-saving', 'Admin\ImportCompulsorySavingActiveCrudController@download_excel');

Route::get('/api/download-loan-payment-excel/{id}', 'Admin\ImportLoanRepaymentCrudController@excel_download');
Route::get('/api/download-cash-withdrawal-excel/{id}', 'Admin\ImportCompulsorySavingActiveCrudController@excel_download');

Route::get('/api/loan-disbursement-paid', 'Admin\MyPaidDisbursementCrudController@loanDisbursementPaid');
Route::get('/admin/view-payment', 'Admin\LoanOutstandingCrudController@view_payment');
Route::post('admin/get-loan-by-group', 'Admin\GroupRepaymentNewCrudController@get_loan_by_group');


Route::get('api/generate-member',function (\Illuminate\Http\Request $request){

    //$members = \App\Models\NewMember::where('id','<',3)->simplePaginate(50);
    //dd($members);
    $branch_code  = $request->branch_code;

//    $members = \App\Models\NewMember::where(function ($w) use ($branch_code){
//        if($branch_code != null){
//            $w->where('branch_code',$branch_code);
//        }
//    })->simplePaginate(100);

    $members = \App\Models\NewMember::simplePaginate(100);
    //$n = ($members->currentPage()+1);
    $n = ($request->page??1) +1;

    //return 'ok';
//where('id','<=',300)->where('id','>=',200)->
    //\App\Models\NewMember::chunk(100, function($members)
    //{
    foreach ($members as $m){
        $center_id = 0;

        //======================= Branch ===================================
        $branch = \App\Models\BranchR::where('code',trim($m->branch_code))->first();

        //dd($center);
        if($branch == null){
            $branch = new \App\Models\BranchR();
            $branch->title = $m->branch_name;
            $branch->code = $m->branch_code;
            $branch->phone = 'N/A';
            $branch->save();
        }

        if($branch != null){
            $center = \App\Models\CenterLeaderR::where('code',trim($m->center_id))->where('branch_id',trim($branch->id))->first();
            if($center == null){
                if($m->center_id){
                    $center = new \App\Models\CenterLeaderR();
                    $center->branch_id = $branch->id;
                    $center->code = $m->center_id;
                    $center->title = $m->center_id;
                    $center->phone = 'N/A';
                    $center->save();
                }

            }

            if($center != null){
                $center_id = $center->id;
            }
        }
        //=================================================================

        //======================= User =================================
        $user = \App\User::where('user_code',$m->loan_officer_code)->first();

        if($user == null){
            $user = new \App\User();
            $user->user_code = $m->loan_officer_code;
            $user->name = $m->loan_officer_name;
            $user->email = $m->loan_officer_name .'@cloudnet.com';
            $user->password = $m->loan_officer_name .'@cloudnet.com';
            $user->branch_id = $branch->id;
            $user->save();
        }


        //======================= End User =================================

        //======================== CO ==================================
        $co = \App\User::where('user_code',$m->loan_officer_code)->first();
        //======================== Client ==================================

        $client = \App\Models\ClientR::where('client_number',$m->client_id)->first();

        if($client == null){
            $client = new \App\Models\ClientR();
        }
        $nrc_type = '';
        if($m->nrc_type == 'old'){
            $nrc_type = 'Old Format';
        }else{
            $nrc_type = 'New Format';
        }
        //dd($m);
        $arrYN = ['Yes','No'];

        $client->loan_officer_access_id = $co->id;
        $client->loan_officer_id = $co->id;
        $client->branch_id = $branch->id;
        $client->client_number = $m->client_id;
        $client->register_date = $m->date_register;
        $client->center_leader_id = $center_id;
        $client->you_are_a_group_leader = ($m->group_leader!=null && $m->group_leader!='')?in_array($m->group_leader,$arrYN)?$m->group_leader:'No':'No' ; //  ?trim($m->group_leader) !=''?ucfirst($m->group_leader):'No':'No';
        $client->you_are_a_center_leader = ($m->center_leader!=null && $m->center_leader!='')?in_array($m->center_leader,$arrYN)?$m->center_leader:'No':'No' ;//
        // ($m->center_leader!=null && $m->center_leader!='')?ucfirst($m->center_leader):'No';
        $client->remark = 'active';
        $client->name = $m->client_name_en;
        $client->name_other = $m->client_name_mm;
        $client->gender = $m->sex;
        $client->gender = $m->dob;
        $client->education = $m->education_level? $m->education_level : '';
        $client->primary_phone_number = $m->primary_phone_number;
        $client->alternate_phone_number = $m->alternative_phone_number;
        $client->nrc_number = $m->nrc_number;
        $client->nrc_type = $nrc_type;
        $client->marital_status = $m->marrital_status;
        $client->status = 'Active';
        $client->father_name = $m->father_name;
        $client->husband_name = $m->spouse_name;
        $client->occupation_of_husband = $m->spouse_occupation;
        $client->no_children_in_family = $m->no_of_childredn;
        $client->no_of_working_people = $m->no_of_work_person;
        $client->no_of_dependent = $m->no_of_dependent;
        $client->no_of_person_in_family = $m->no_of_person_in_family;
        $client->address1 = $m->address_1;
        $client->address2 = $m->address_2;
        $client->province_id = $m->region;
        $client->commune_id = $m->township_code;
        /*if($province != null){
            $client->province_id = $province->code;
        }
        $district = \App\Address::where('description', $m->district)->first();
        if($district != null){
            $client->district_id = $district->code;
        }
        $commune = \App\Address::where('description', $m->township)->first();
        if($commune != null){
            $client->commune_id = $commune->code;
        }
        $g_village = \App\Address::where('description', $m->group_of_village)->first();
        if($g_village != null){
            $client->village_id = $g_village->group_of_village;
        }
        $village = \App\Address::where('description', $m->village)->first();
        if($village != null){
            $client->ward_id = $village->village;
        }*/

        $client->save();
        //if($client->save()){
        // \App\Helpers\MigrateData::create_loan($client->client_number);




        /*$loan = \App\Models\Loan2::where('client_id',$client->id)->ordeBy('id','desc')->first();

        $compulsory = LoanCompulsory::where('loan_id', $row->applicant_number_id)->first();
        //dd($compulsory);
        //dd($row);
        $transaction = new CompulsorySavingTransaction();
        $transaction->customer_id = optional($loan)->client_id;
        $transaction->tran_id = $deposit_id;
        $transaction->train_type = 'deposit';
        $transaction->train_type_ref = 'deposit';
        $transaction->tran_id_ref = $row->applicant_number_id;
        $transaction->tran_date = date('Y-m-d');
        $transaction->amount = $row->compulsory_saving_amount;

        $transaction->loan_id = $row->applicant_number_id;
        $transaction->loan_compulsory_id = $compulsory->id;
        if ($transaction->save()) {
            $loan_compulsory = LoanCompulsory::where('loan_id', $row->applicant_number_id)->first();
            if ($loan_compulsory != null) {
                $loan_compulsory->compulsory_status = 'Active';
                //$loan_compulsory->principles = $loan_compulsory->principles + $transaction->amount;
                //$loan_compulsory->available_balance = $loan_compulsory->available_balance + $transaction->amount;
                $loan_compulsory->save();
            }
        }*/
        //}
        //==================================================================
		///    <!--<meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-member?branch_code='.$branch_code.'&page=' . $n) . '\'>-->

    }
    //});
    if($members->hasMorePages()) {
        return '<head>
      <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-member? &page=' . $n) . '\'>

    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/generate-loans',function (\Illuminate\Http\Request $request){

    $branch_code  = $request->branch_code;


    $loans = \App\Models\LoanImportList::where(function ($w) use ($branch_code){
        if($branch_code != null){
            $w->where('branch_code',$branch_code);
        }
    })->where('client_id','WD-18-07004')->where('loan_type','PL01')->simplePaginate(10);
    // $n = ($loans->currentPage()+1);
    $n = ($request->page??1) +1;

    $rows = $loans;

    $arr = ['Monthly'=>'Month', 'Daily'=>'Day', 'Weekly'=>'Week', 'Two-Weeks'=>'Two-Weeks','Four-Weeks'=>'Four-Weeks', 'Yearly'=>'Year'];
    if($rows != null){
        if(count($rows)>0){
            foreach ($rows as $m){
                $branch = \App\Models\BranchU::where('code', $m->branch_code)->first();

                $center = \App\Models\CenterLeaderR::where('code',$m->center_id)->where('branch_id',optional($branch)->id)->first();
                $user = \App\User::where('user_code', $m->loan_officer_id)->first();
                if ($user) {
                    $co_id = $user->id;
                } else {
                    $co_id = 0;
                }
                $group = null;
                if($m->group_id != null) {
                    $group = \App\Models\GroupLoan2::where('group_code', $m->group_id)->first();

                    if ($group == null) {
                        $group = new  \App\Models\GroupLoan2();
                    }
                    $group->group_code = $m->group_id;
                    $group->center_id = optional($center)->id;
                    $group->save();
                }

                $client = \App\Models\ClientR::where('client_number',trim($m->client_id))->first();

                if($client == null){
                    $client = new \App\Models\ClientR();
                    $client->loan_officer_id = $co_id;
                    $client->branch_id = optional($branch)->id;
                    $client->center_leader_id = optional($center)->id;
                    $client->client_number = $m->client_id;
                    $client->name = $m->customer_name;
                    $client->name_other = $m->customer_name;
                    $client->save();
                }
                if($client !=null) {
                    /*$loan = \App\Models\Loan2::where('client_id',$client->id)->whereDate('first_installment_date',$m->start_new_repayment_date)
                            ->where('loan_amount',$m->loan_amount-0)->where('principle_repayment',$m->priciple_repayment-0)->first();
                    if($loan == null) {

                    }*/
                    $loan = new \App\Models\Loan2();
                    $loan_product = \App\Models\LoanProduct::where('code', $m->loan_type)->first();

                    //$loan->disbursement_number = \App\Models\Loan::getSeqRef('loan-id');
                    $loan->disbursement_number = \App\Models\Loan2::getSeqRef('loan');
                    //dd($m);
                    //$loan->disbursement_name = ;
                    $loan->branch_id = optional($branch)->id;
                    $loan->center_leader_id = (optional($center)->id !=null || optional($center)->id != '') ? optional($center)->id : 0;
                    $loan->disbursement_status = 'Activated';
                    $loan->loan_officer_id = $co_id;
                    $loan->group_loan_id = optional($group)->id;
                    $loan->currency_id = 1;
                    $loan->client_id = optional($client)->id;
                    $loan->loan_application_date = $m->disbursement_date!=0000-00-00?$m->disbursement_date:null;
                    $loan->first_installment_date = $m->start_new_repayment_date!=0000-00-00?$m->start_new_repayment_date:null;
                    $loan->loan_production_id = optional($loan_product)->id;
                    $loan->loan_amount = $m->loan_amount;
                    $loan->principle_receivable = $m->principle_outstanding + $m->priciple_late_payment;
                    $loan->principle_repayment = $m->priciple_repayment;
                    $loan->interest_receivable = $m->interest_outstanding + $m->interest_late_payment;
                    $loan->interest_repayment = $m->interest_repayment;
                    $loan->loan_term_value = $m->term_pending_repayment;
                    $loan->loan_term = $arr[$m->repayment_terms];
                    $loan->repayment_term = $m->repayment_terms;
                    $loan->interest_rate_period = $m->interest_period;
                    $loan->interest_rate = $m->interest_rate * 100;
                    $loan->loan_cycle = $m->loans_cycle ? $m->loans_cycle : 1;
                    $loan->status_note_date_activated = $m->disbursement_date!=0000-00-00?$m->disbursement_date:null;

                    $loan->you_are_a_group_leader = optional($client)->you_are_a_group_leader!=''?optional($client)->you_are_a_group_leader:'No';
                    $loan->you_are_a_center_leader = optional($client)->you_are_a_center_leader!=''?optional($client)->you_are_a_center_leader:'No';
                    //$loan->deposit_paid = ;
                    //dd($m);
                    //dd($loan);
                    $l_product_id = optional($loan_product)->id;
                    //dd($loan);
                    if ($loan->save()) {
                        if($m->group_id != null) {
                            $client_id = $loan->client_id;
                            $group_loan_id = $loan->group_loan_id;
                            $md = null;
                            if ($group_loan_id > 0 && $client_id > 0) {
                                $md = \App\Models\GroupLoanDetailApi::where('client_id', $client_id)->where('group_loan_id', $group_loan_id)->first();
                                if ($md == null) {
                                    $md = new \App\Models\GroupLoanDetailApi();
                                }
                                $md->client_id = $client_id;
                                $md->group_loan_id = $group_loan_id;
                                $md->loan_id = $loan->id;
                                $md->save();
                            }
                        }

                        $date = \App\Helpers\MigrateData::get_pre_date($m->start_new_repayment_date, $loan->repayment_term);
                        \App\Helpers\MigrateData::paid_disbursement($loan,$m);
                        \App\Helpers\MigrateData::loanrepayment($loan, $m);
                        \App\Helpers\MigrateData::late_repayment($loan, $m, $date);
                        $first_date_payment = null;
                        if($m->start_new_repayment_date != null) {
                            $first_date_payment = $m->start_new_repayment_date;
                        }else{
                            $first_date_payment = \App\Helpers\MigrateData::get_next_date($m->transaction_date,$loan->repayment_term);
                        }
                        $interest_method = $loan_product->interest_method; //'declining-balance-principal';
                        $principal_amount = $m->principle_outstanding;
                        $loan_duration = $loan->loan_term_value;
                        $loan_duration_unit = $loan->loan_term;
                        $repayment_cycle = $loan->repayment_term;
                        $loan_interest = $loan->interest_rate;
                        $loan_interest_unit = $loan->interest_rate_period;
                        if($m->principle_outstanding >0 && $m->principle_outstanding !='') {
                            \App\Helpers\MigrateData::gen_schedule($m, $date, $first_date_payment, $interest_method,
                                $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit, $loan->id);
                        }
                    }
                }

            }
        }
    }


    //});
    if($loans->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-loans?branch_code='.$branch_code.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/generate-saving',function (\Illuminate\Http\Request $request){
    $branch_code  = $request->branch_code;
    $charge_option = ['Fixed Amount'=>'1', 'Of Loan Amount'=>'2'];
    $compulsory_type = ['Every Repayments'=>'3','Every Repayment'=>'3', 'Deposit Before Disbursement'=>'1'];
    $members = \App\Models\NewMember::where(function ($w) use ($branch_code){
        if($branch_code != null){
            $w->where('branch_code',$branch_code);
        }
    })->simplePaginate(100);


    //$n = ($members->currentPage()+1);

    $n = ($request->page??1) +1;

    foreach ($members as $m){
        //dd($m);
        if($m->saving_amount > 0) {
            $general_loan = \App\Models\LoanProduct::where('code', 'PL01')->first();
            $compulsory_product = \App\Models\CompulsoryProduct::find($general_loan->compulsory_id);
            $client = \App\Models\ClientR::where('client_number', $m->client_id)->first();
            $loan = \App\Models\Loan2::where('client_id', $client->id)->orderBy('loan_cycle', 'desc')->first();
            //dd($loan);

//            if($loan){
//                $loan_compulsory = \App\Models\LoanCompulsory::where('loan_id',$loan->id)->first();
//            }else{
//                $loan_compulsory = new \App\Models\LoanCompulsory();
//            }
            if ($loan) {

                $loan_compulsory = \App\Models\LoanCompulsory::where('loan_id',$loan->id)->where('client_id',$client->id)->where('compulsory_id',$general_loan->compulsory_id)->first();
                if($loan_compulsory == null) {
                    $loan_compulsory = new \App\Models\LoanCompulsory();
                }
                $save_no  =   \App\Models\LoanCompulsory::getSeqRef('compulsory');
                //dd($save_no);

                $loan_compulsory->loan_id = $loan->id;
                $loan_compulsory->client_id = $client->id;
                $loan_compulsory->compulsory_id = $general_loan->compulsory_id;
                $loan_compulsory->product_name = $compulsory_product->product_name;
                $loan_compulsory->saving_amount = $m->saving_amount;
                $loan_compulsory->charge_option = $charge_option[$m->saving_options];
                $loan_compulsory->interest_rate = $m->monthly_interest * 100;
                $loan_compulsory->compound_interest = 0;
                $loan_compulsory->override_cycle = $m->saving_cycle_override_type;
                $loan_compulsory->compulsory_number = $save_no;
                $loan_compulsory->compulsory_product_type_id = $compulsory_type[$m->deposit_options];
                $loan_compulsory->principles = 0;
                $loan_compulsory->interests = 0;
                $loan_compulsory->available_balance = 0;
                $loan_compulsory->compulsory_status = "Pending";
                //dd($m);
                if ($loan_compulsory->save()) {
                    $disburse = \App\Models\PaidDisbursement::where('client_id', $client->id)->orderBy('id', 'desc')->first();

                    $ref_no = $disburse->reference;
                    $saving_deposit = $m->total_saving_principle_opening_amount;
                    $saving_interest = $m->total_saving_interest_opening_amount;
                    if ($saving_deposit > 0) {
                        $deposit_tran = \App\Models\CompulsorySavingTransaction::where('customer_id',$client->id)->where('tran_id',optional($disburse)->id)
                                        ->where('train_type','deposit')->first();
                        if($deposit_tran == null) {
                            $deposit_tran = new \App\Models\CompulsorySavingTransaction();
                        }
                        $deposit_tran->customer_id = $client->id;
                        $deposit_tran->tran_id = optional($disburse)->id;
                        $deposit_tran->train_type = 'deposit';
                        $deposit_tran->train_type_ref = 'disbursement';
                        $deposit_tran->tran_id_ref = $loan->id;
                        $deposit_tran->tran_date = $m->saving_opening_date;
                        $deposit_tran->amount = $saving_deposit;
                        $deposit_tran->loan_id = $loan->id;
                        $deposit_tran->loan_compulsory_id = $loan_compulsory->id;
                        //dd($deposit_tran);
                        if ($deposit_tran->save()) {
                            // dd($deposit_tran);
                            $loan_com = \App\Models\LoanCompulsory::where('id', $loan_compulsory->id)->first();
                            $loan_com->compulsory_status = "Active";
                            $loan_com->save();
                            //\App\Helpers\MigrateData::accDepositMigration($deposit_tran,$loan->branch_id,$m,$ref_no,$disburse);
                        }
                        if ($saving_interest > 0) {
                            $ref_no = \App\Models\CompulsoryAccrueInterests::getSeqRef('accrue-interest');
                            //dd($ref_no);
                            $accrue_interrest = new \App\Models\CompulsoryAccrueInterests();
                            $accrue_interrest->reference = $ref_no;
                            $accrue_interrest->compulsory_id = $loan_compulsory->compulsory_id;
                            $accrue_interrest->loan_compulsory_id = $loan_compulsory->id;
                            $accrue_interrest->loan_id = $loan_compulsory->loan_id;
                            $accrue_interrest->client_id = $loan_compulsory->client_id;
                            $accrue_interrest->train_type = 'accrue-interest';
                            $accrue_interrest->tran_id_ref = $loan_compulsory->loan_id;
                            $accrue_interrest->tran_date = $m->saving_opening_date;
                            $accrue_interrest->reference_no = '';
                            $accrue_interrest->amount = $saving_interest;
                            // dd($accrue_interrest);
                            $accrue_interrest->save();

                            //\App\Helpers\MigrateData::accAccurInterestCompulsory($accrue_interrest);
                        }

                    }


                }
                //dd("no");
            }
        }


    }

    if($members->hasMorePages()) {
        return '<head>
      <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-saving?branch_code='.$branch_code.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});





Route::get('api/generate-coa',function (){
    $coa = \App\Models\Coa::all();
    //dd($coa);

    foreach ($coa as $c){
        $acc_chart = \App\Models\AccountChart::where('code',$c->code)->where('name',$c->name)->first();
        if($acc_chart != null){
            $acc_chart = new \App\Models\AccountChart();
        }
        $acc_chart->section_id = $c->section_id;

        $acc_chart->code = $c->code;
        $acc_chart->name = $c->name;
        $acc_chart->save();

    }
});


Route::get('/list_notification_all', function () {
    $user_id=auth()->user()->id;
    $notifications = \App\Models\Notification::where('notifiable_id',$user_id)
        ->orderBy('created_at','desc')
        ->paginate(20);
    return view('read-all-notification',['notifications'=>$notifications]);
});
Route::get('/read_notification/{id}', function ($id) {
    $notification = \App\Models\Notification::find($id);



    if ($notification !=null) {
        $details = null;
        if ($notification->data != null) {
            $data = json_decode($notification->data);

            if ($data != null) {
                $details = $data->details;
            }
        }
        $type = $notification->type;
        $notification->delete();

        Route::get('/read_notification/{id}', function ($id) {
            $notification = \App\Models\Notification::find($id);
            if ($notification !=null) {
                $details = null;
                if ($notification->data != null) {
                    $data = json_decode($notification->data);

                    if ($data != null) {
                        $details = $data->details;
                    }
                }
                $type = $notification->type;
                $notification->delete();
                if ($type == "App\Notifications\LatePaymentNotification") {
                    return redirect('admin/loanoutstanding');
                }
            }

            return redirect()->back();

        });
    }

    return redirect()->back();

});

Route::get('api/generate-loans-angkor',function (\Illuminate\Http\Request $request){

    $branch_code  = $request->branch_code;
    $charge_option = ['Fixed Amount'=>'1', 'Of Loan Amount'=>'2'];
    $compulsory_type = ['Every Repayments'=>'3','Every Repayment'=>'3', 'Deposit Before Disbursement'=>'1', 'Deduct'=>'2'];

    $loans = \App\Models\LoanImportList::simplePaginate(1);
    // $n = ($loans->currentPage()+1);
    $n = ($request->page??1) +1;

    $rows = $loans;

    $arr = ['Monthly'=>'Month', 'Daily'=>'Day', 'Weekly'=>'Week', 'Two-Weeks'=>'Two-Weeks','Four-Weeks'=>'Four-Weeks', 'Yearly'=>'Year'];
    if($rows != null){
        if(count($rows)>0){
            foreach ($rows as $m){
                $client = \App\Models\ClientR::where('client_number',$m->client_id)->first();
                //dd($m);
                if($client !=null) {
                    $loan = new \App\Models\Loan2();

                    $branch = \App\Models\BranchU::where('code', $m->branch_code)->first();

                    $center = \App\Models\CenterLeaderR::where('code', $m->center_id)->where('branch_id', optional($branch)->id)->first();
                    $user = \App\User::where('user_code', $m->loan_officer_id)->first();
                    if ($user) {
                        $co_id = $user->id;
                    } else {
                        $co_id = 0;
                    }
                    $group = null;
                    if ($m->group_id != null) {
                        $group = \App\Models\GroupLoan2::where('group_code', $m->group_id)->first();

                        if ($group == null) {
                            $group = new  \App\Models\GroupLoan2();
                        }
                        $group->group_code = $m->group_id;
                        $group->center_id = optional($center)->id;
                        $group->save();
                    }


                    $loan_product = \App\Models\LoanProduct::where('code', $m->loan_type)->first();

                    $loan->disbursement_number = $m->loan_id;


                    $loan->branch_id = optional($branch)->id;
                    $loan->center_leader_id = (optional($center)->id != null || optional($center)->id != '') ? optional($center)->id : 0;

                    $loan->loan_officer_id = $co_id;
                    $loan->group_loan_id = optional($group)->id;
                    $loan->currency_id = 1;
                    $loan->client_id = optional($client)->id;
                    $loan->loan_application_date = $m->disbursement_date != 0000 - 00 - 00 ? $m->disbursement_date : null;
                    $loan->status_note_date_activated = $m->disbursement_date != 0000 - 00 - 00 ? $m->disbursement_date : null;
                    $loan->first_installment_date = $m->start_new_repayment_date != 0000 - 00 - 00 ? $m->start_new_repayment_date : null;
                    $loan->loan_production_id = optional($loan_product)->id;
                    $loan->loan_amount = $m->loan_amount;
                    $loan->principle_receivable = $m->principle_outstanding + $m->priciple_late_payment;
                    $loan->principle_repayment = $m->priciple_repayment;
                    $loan->interest_receivable = $m->interest_outstanding + $m->interest_late_payment;
                    $loan->interest_repayment = $m->interest_repayment;
                    $loan->loan_term_value = $m->term_pending_repayment;
                    $loan->loan_term = $arr[$m->repayment_terms];
                    $loan->repayment_term = $m->repayment_terms;
                    $loan->interest_rate_period = $m->interest_period;
                    $loan->interest_rate = $m->interest_rate * 1;
                    $loan->loan_cycle = $m->loans_cycle ? $m->loans_cycle : 1;
                    if ($m->principle_outstanding > 0) {
                        $loan->disbursement_status = 'Activated';
                    } else{
                        $loan->disbursement_status = "Closed";
                    }

                    $loan->you_are_a_group_leader = optional($client)->you_are_a_group_leader!=''?optional($client)->you_are_a_group_leader:'No';
                    $loan->you_are_a_center_leader = optional($client)->you_are_a_center_leader!=''?optional($client)->you_are_a_center_leader:'No';

                    $l_product_id = $loan_product->id;
                    //dd($loan);
                    if ($loan->save()) {
                        if($m->group_id != null) {
                            $client_id = $loan->client_id;
                            $group_loan_id = $loan->group_loan_id;
                            $md = null;
                            if ($group_loan_id > 0 && $client_id > 0) {
                                $md = \App\Models\GroupLoanDetailApi::where('client_id', $client_id)->where('group_loan_id', $group_loan_id)->first();
                                if ($md == null) {
                                    $md = new \App\Models\GroupLoanDetailApi();
                                }
                                $md->client_id = $client_id;
                                $md->group_loan_id = $group_loan_id;
                                $md->loan_id = $loan->id;
                                $md->save();
                            }
                        }

                        $date = \App\Helpers\MigrateData::get_pre_date($m->start_new_repayment_date, $loan->repayment_term);
                        \App\Helpers\MigrateData::paid_disbursement($loan,$m);
                        \App\Helpers\MigrateData::loanrepayment($loan, $m);

                     //   \App\Helpers\MigrateData::late_repayment($loan, $m, $date);


                        $first_date_payment = $m->start_new_repayment_date;
                        $interest_method = $loan_product->interest_method;
                        $principal_amount = $m->principle_outstanding;
                        $loan_duration = $loan->loan_term_value;
                        $loan_duration_unit = $loan->loan_term;
                        $repayment_cycle = $loan->repayment_term;
                        $loan_interest = $loan->interest_rate;
                        $loan_interest_unit = $loan->interest_rate_period;
                        //dd($m->principle_outstanding-0);
                        //dd($loan);
                        if($m->principle_outstanding-0 > 0) {
                           // dd($loan);
                            \App\Helpers\MigrateData::gen_schedule($m, $date, $first_date_payment, $interest_method,
                                $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit, $loan->id);
                        }


                        //// saving
                        //dd($m->saving_amount);
                        if($m->saving_amount > 0) {
                            $general_loan = \App\Models\LoanProduct::where('code', $m->loan_type)->first();
                            $compulsory_product = \App\Models\CompulsoryProduct::find($general_loan->compulsory_id);
                            $client = \App\Models\ClientR::where('client_number', $m->client_id)->first();

                            $loan_compulsory = new \App\Models\LoanCompulsory();
                            //$save_no  =   \App\Models\LoanCompulsory::getSeqRef('compulsory');
                            $save_no  =   $loan->disbursement_number;


                            $loan_compulsory->loan_id = $loan->id;
                            $loan_compulsory->client_id = $client->id;
                            $loan_compulsory->compulsory_id = $general_loan->compulsory_id;
                            $loan_compulsory->product_name = $compulsory_product->product_name;
                            $loan_compulsory->saving_amount = $m->saving_amount;
                            $loan_compulsory->charge_option = $charge_option[$m->saving_options];
                            $loan_compulsory->interest_rate = $m->monthly_interest * 1;
                            $loan_compulsory->compound_interest = 0;
                            $loan_compulsory->override_cycle = "No";
                            $loan_compulsory->compulsory_number = $save_no;
                            $loan_compulsory->compulsory_product_type_id = $compulsory_type[$m->deposit_options];
                            $loan_compulsory->principles = 0;
                            $loan_compulsory->interests = 0;
                            $loan_compulsory->available_balance = 0;
                            $loan_compulsory->compulsory_status = "Pending";
                            //dd($loan_compulsory);
                            if ($loan_compulsory->save()) {
                                $disburse = \App\Models\PaidDisbursement::where('contract_id', $loan->id)->first();

                                $ref_no = $disburse->reference;
                                $saving_deposit = $m->total_saving_principle_opening_amount;
                                $saving_interest = $m->total_saving_interest_opening_amount;
                                $saving_withdraw = $m->total_withdrawal;
                                if ($saving_deposit > 0) {
                                    $deposit_tran = new \App\Models\CompulsorySavingTransaction();
                                    $deposit_tran->customer_id = $client->id;
                                    $deposit_tran->tran_id = optional($disburse)->id;
                                    $deposit_tran->train_type = 'deposit';
                                    $deposit_tran->train_type_ref = 'disbursement';
                                    $deposit_tran->tran_id_ref = $loan->id;
                                    $deposit_tran->tran_date = $disburse->paid_disbursement_date;
                                    $deposit_tran->amount = $saving_deposit;
                                    $deposit_tran->loan_id = $loan->id;
                                    $deposit_tran->loan_compulsory_id = $loan_compulsory->id;
                                 //   dd($deposit_tran);

                                    if ($deposit_tran->save()) {
                                        $disburse = \App\Models\PaidDisbursement::where('contract_id',optional($loan)->id)->first();
                                        $amount_disburse = ($disburse->loan_amount-0) - ($saving_deposit-0);
                                        $disburse->compulsory_saving = $saving_deposit-0;
                                        $disburse->total_money_disburse = $amount_disburse;
                                        $disburse->disburse_amount = $amount_disburse;
                                        $disburse->cash_pay = $amount_disburse;
                                        $disburse->branch_id = 1;
                                        $disburse->loan_id = 0;
                                        $disburse->contract_no = 0;
                                        $disburse->group_tran_id = 0;
                                        $disburse->first_payment_date = 0;
                                        $disburse->invoice_no = 0;
                                       // dd($disburse);
                                        if($disburse){
                                        //    $disburse->save();
                                        }
                                        //dd($disburse);


                                        $loan_com = \App\Models\LoanCompulsory::where('id', $loan_compulsory->id)->first();
                                        $loan_com->compulsory_status = "Active";
                                        if($m->total_withdrawal > 0){
                                            $loan_com->compulsory_status = "Completed";
                                        }else{
                                            $loan_com->compulsory_status = "Active";
                                        }
                                        $loan_com->save();

                                    //    \App\Helpers\MigrateData::accDepositMigration($deposit_tran,$loan->branch_id,$client,$ref_no,$disburse);
                                    }



                                    if ($saving_interest > 0) {
                                        $ref_no = \App\Models\CompulsoryAccrueInterests::getSeqRef('accrue-interest');
                                        //dd($ref_no);
                                        $accrue_interrest = new \App\Models\CompulsoryAccrueInterests();
                                        $accrue_interrest->reference = $ref_no;
                                        $accrue_interrest->compulsory_id = $loan_compulsory->compulsory_id;
                                        $accrue_interrest->loan_compulsory_id = $loan_compulsory->id;
                                        $accrue_interrest->loan_id = $loan_compulsory->loan_id;
                                        $accrue_interrest->client_id = $loan_compulsory->client_id;
                                        $accrue_interrest->train_type = 'accrue-interest';
                                        $accrue_interrest->tran_id_ref = $loan_compulsory->loan_id;
                                        $accrue_interrest->tran_date = $m->saving_opening_date;
                                        $accrue_interrest->reference_no = '';
                                        $accrue_interrest->amount = $saving_interest;
                                        $accrue_interrest->save();
                                       // dd($accrue_interrest);

                                    //    \App\Helpers\MigrateData::accAccurInterestCompulsory($accrue_interrest);
                                    }

                                    if ($saving_withdraw > 0) {

                                        $withdrawals = New \App\Models\CashWithdrawal();
                                        $ref_wit_no = \App\Models\CashWithdrawal::getSeqRef('withdrawal');

                                        if($m->saving_amount){
                                            $remaining_balance = $m->total_withdrawal;
                                            $principle_withdraw = $m->total_saving_principle_opening_amount;
                                            $interest_withdraw = $remaining_balance - $principle_withdraw;
                                        }
                                        $withdrawals->reference = $ref_wit_no;
                                        $withdrawals->cash_withdrawal = $saving_withdraw;
                                        $withdrawals->withdrawal_date = $m->date_withdrawal;
                                        $withdrawals->remaining_balance = $remaining_balance;
                                        $withdrawals->principle_withdraw = $principle_withdraw;
                                        $withdrawals->interest_withdraw = $interest_withdraw;
                                        $withdrawals->principle_remaining = 0;
                                        $withdrawals->interest_remaining = 0;
                                        $withdrawals->loan_id = $loan->id;
                                        $withdrawals->client_id = $client->id;
                                        $withdrawals->cash_out_id = $branch->cash_account_id;
                                        $withdrawals->save_reference_id = $loan_compulsory->id;
                                        $withdrawals->save();

                                        $loan_com = \App\Models\LoanCompulsory::where('id', $loan_compulsory->id)->first();
                                        $loan_com->compulsory_status = "Completed";

                                        \App\Helpers\MigrateData::savingTransaction($withdrawals);
                                    //    \App\Helpers\MigrateData::accWithdrawTransaction($withdrawals);

                                    }

                                }


                            }

                        }
                        ////saving
                    }
                }

            }
        }
    }


    //});
    if($loans->hasMorePages()) {
        return '<head>
             <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-loans-angkor?page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});


Route::get('api/generate-loans-quicken',function (\Illuminate\Http\Request $request){

    $branch_code  = $request->branch_code;
    $charge_option = ['Fixed Amount'=>'1', 'Of Loan Amount'=>'2'];
    $compulsory_type = ['Every Repayments'=>'3','Every Repayment'=>'3', 'Deposit Before Disbursement'=>'1', 'Deduct'=>'2'];

    $loans = \App\Models\LoanImportList::simplePaginate(1);
    // $n = ($loans->currentPage()+1);
    $n = ($request->page??1) +1;

    $rows = $loans;

    $arr = ['Monthly'=>'Month', 'Daily'=>'Day', 'Weekly'=>'Week', 'Two-Weeks'=>'Two-Weeks', 'Four-Weeks'=>'Four-Weeks', 'Yearly'=>'Year'];
    if($rows != null){
        if(count($rows)>0){
            foreach ($rows as $m){
                $client = \App\Models\ClientR::where('client_number',$m->client_id)->first();
                //dd($m);
                if($client !=null) {
                    $loan = new \App\Models\Loan2();

                    $branch = \App\Models\BranchU::where('code', $m->branch_code)->first();

                    $center = \App\Models\CenterLeaderR::where('code', $m->center_id)->where('branch_id', optional($branch)->id)->first();
                    $user = \App\User::where('user_code', $m->loan_officer_id)->first();
                    if ($user) {
                        $co_id = $user->id;
                    } else {
                        $co_id = 0;
                    }
                    $group = null;
                    if ($m->group_id != null) {
                        $group = \App\Models\GroupLoan2::where('group_code', $m->group_id)->first();

                        if ($group == null) {
                            $group = new  \App\Models\GroupLoan2();
                        }
                        $group->group_code = $m->group_id;
                        $group->center_id = optional($center)->id;
                        $group->save();
                    }


                    $loan_product = \App\Models\LoanProduct::where('code', $m->loan_type)->first();
                    if($m->loan_id){
                        $loan->disbursement_number = $m->loan_id;
                    }

                    $loan->branch_id = optional($branch)->id;
                    $loan->center_leader_id = (optional($center)->id != null || optional($center)->id != '') ? optional($center)->id : 0;

                    $loan->loan_officer_id = $co_id;
                    $loan->group_loan_id = optional($group)->id;
                    $loan->currency_id = 1;
                    $loan->client_id = optional($client)->id;
                    $loan->loan_application_date = $m->loan_application_date != 0000 - 00 - 00 ? $m->loan_application_date : null;
                    $loan->status_note_date_activated = $m->disbursement_date != 0000 - 00 - 00 ? $m->disbursement_date : null;
                    $loan->first_installment_date = $m->start_new_repayment_date != 0000 - 00 - 00 ? $m->start_new_repayment_date : null;
                    $loan->loan_production_id = optional($loan_product)->id;
                    $loan->loan_amount = $m->loan_amount;
                    $loan->principle_receivable = $m->principle_outstanding + $m->priciple_late_payment;
                    $loan->principle_repayment = $m->priciple_repayment;
                    $loan->interest_receivable = $m->interest_outstanding + $m->interest_late_payment;
                    $loan->interest_repayment = $m->interest_repayment;
                    $loan->loan_term_value = $m->term_pending_repayment;
                    $loan->loan_term = $arr[$m->repayment_terms];
                    $loan->repayment_term = $m->repayment_terms;
                    $loan->interest_rate_period = $m->interest_period;
                    $loan->interest_rate = $m->interest_rate * 1;
                    $loan->loan_cycle = $m->loans_cycle ? $m->loans_cycle : 1;
                    if ($m->principle_outstanding > 0) {
                        $loan->disbursement_status = 'Activated';
                    } else{
                        $loan->disbursement_status = "Closed";
                    }

                    $loan->you_are_a_group_leader = optional($client)->you_are_a_group_leader!=''?optional($client)->you_are_a_group_leader:'No';
                    $loan->you_are_a_center_leader = optional($client)->you_are_a_center_leader!=''?optional($client)->you_are_a_center_leader:'No';

                    $l_product_id = $loan_product->id;
                    //dd($loan);
                    if ($loan->save()) {
                        if($m->group_id != null) {
                            $client_id = $loan->client_id;
                            $group_loan_id = $loan->group_loan_id;
                            $md = null;
                            if ($group_loan_id > 0 && $client_id > 0) {
                                $md = \App\Models\GroupLoanDetailApi::where('client_id', $client_id)->where('group_loan_id', $group_loan_id)->first();
                                if ($md == null) {
                                    $md = new \App\Models\GroupLoanDetailApi();
                                }
                                $md->client_id = $client_id;
                                $md->group_loan_id = $group_loan_id;
                                $md->loan_id = $loan->id;
                                $md->save();
                            }
                        }

                        $date = \App\Helpers\MigrateData::get_pre_date($m->start_new_repayment_date, $loan->repayment_term);
                        \App\Helpers\MigrateData::paid_disbursement($loan,$m);
                        \App\Helpers\MigrateData::loanrepayment($loan, $m);

                        //   \App\Helpers\MigrateData::late_repayment($loan, $m, $date);


                        $first_date_payment = $m->start_new_repayment_date;
                        $interest_method = $loan_product->interest_method;
                        $principal_amount = $m->principle_outstanding;
                        $loan_duration = $loan->loan_term_value;
                        $loan_duration_unit = $loan->loan_term;
                        $repayment_cycle = $loan->repayment_term;
                        $loan_interest = $loan->interest_rate;
                        $loan_interest_unit = $loan->interest_rate_period;
                        //dd($m->principle_outstanding-0);
                        //dd($loan);
                        if($m->principle_outstanding-0 > 0) {

                            \App\Helpers\MigrateData::gen_schedule($m, $date, $first_date_payment, $interest_method,
                                $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit, $loan->id);
                        }


                        //// saving
                        //dd($m->saving_amount);
                        if($m->saving_amount > 0) {

                            $general_loan = \App\Models\LoanProduct::where('code', $m->loan_type)->first();
                            $compulsory_product = \App\Models\CompulsoryProduct::find($general_loan->compulsory_id);
                            $client = \App\Models\ClientR::where('client_number', $m->client_id)->first();

                            $loan_compulsory = new \App\Models\LoanCompulsory();
                            //$save_no  =   \App\Models\LoanCompulsory::getSeqRef('compulsory');
                            $save_no  =   $loan->disbursement_number;


                            $loan_compulsory->loan_id = $loan->id;
                            $loan_compulsory->client_id = $client->id;
                            $loan_compulsory->compulsory_id = $general_loan->compulsory_id;
                            $loan_compulsory->product_name = $compulsory_product->product_name;
                            $loan_compulsory->saving_amount = $m->saving_amount;
                            $loan_compulsory->charge_option = $charge_option[$m->saving_options];
                            $loan_compulsory->interest_rate = $m->monthly_interest * 1;
                            $loan_compulsory->compound_interest = 0;
                            $loan_compulsory->override_cycle = "No";
                            $loan_compulsory->compulsory_number = $save_no;
                            $loan_compulsory->compulsory_product_type_id = $compulsory_type[$m->deposit_options];
                            $loan_compulsory->principles = 0;
                            $loan_compulsory->interests = 0;
                            $loan_compulsory->available_balance = 0;
                            $loan_compulsory->compulsory_status = "Pending";

                            if ($loan_compulsory->save()) {
                                $disburse = \App\Models\PaidDisbursement::where('contract_id', $loan->id)->first();

                                $ref_no = $disburse->reference;
                                $saving_deposit = $m->total_saving_principle_opening_amount;
                                $saving_interest = $m->total_saving_interest_opening_amount;
                                $saving_withdraw = $m->total_withdrawal;
                                if ($saving_deposit > 0) {

                                    $deposit_tran = new \App\Models\CompulsorySavingTransaction();
                                    $deposit_tran->customer_id = $client->id;
                                    $deposit_tran->tran_id = optional($disburse)->id;
                                    $deposit_tran->train_type = 'deposit';
                                    $deposit_tran->train_type_ref = 'disbursement';
                                    $deposit_tran->tran_id_ref = $loan->id;
                                    $deposit_tran->tran_date = $disburse->paid_disbursement_date;
                                    $deposit_tran->amount = $saving_deposit;
                                    $deposit_tran->loan_id = $loan->id;
                                    $deposit_tran->loan_compulsory_id = $loan_compulsory->id;
                                    //dd($deposit_tran);

                                    if ($deposit_tran->save()) {

                                        $amount_disburse = ($disburse->loan_amount-0) - ($saving_deposit-0);
                                        $disburse->compulsory_saving = $saving_deposit;
                                        $disburse->total_money_disburse = $amount_disburse;
                                        $disburse->disburse_amount = $amount_disburse;
                                        $disburse->cash_pay = $amount_disburse;
                                       // dd($disburse);
                                        $disburse->save();

                                        $loan_com = \App\Models\LoanCompulsory::where('id', $loan_compulsory->id)->first();
                                        $loan_com->compulsory_status = "Active";
                                        if($m->total_withdrawal > 0){
                                            $loan_com->compulsory_status = "Completed";
                                        }else{
                                            $loan_com->compulsory_status = "Active";
                                        }

                                        $loan_com->save();


                                        //    \App\Helpers\MigrateData::accDepositMigration($deposit_tran,$loan->branch_id,$client,$ref_no,$disburse);
                                    }


                                }


                            }

                        }
                        ////saving
                    }
                }

            }
        }
    }


    //});
    if($loans->hasMorePages()) {
        return '<head>
             <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-loans-quicken?page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});



Route::get('api/generate_charge/',function (\Illuminate\Http\Request $request){

    $charge_option = ['Fixed Amount'=>'1', 'Of Loan Amount'=>'2'];
    $compulsory_type = ['Every Repayments'=>'3','Every Repayment'=>'3', 'Deposit Before Disbursement'=>'1', 'Deduct'=>'2'];

    $loans = \App\Models\Loan2::simplePaginate(10);
    //$loans = \App\Models\Loan2::where('disbursement_status','Pending')->simplePaginate(10);

    $n = ($loans->currentPage()+1);

    if(count($loans)>0){
        foreach ($loans as $m) {
                $charge = \App\Models\LoanImpCharge::where('loan_id',$m->disbursement_number)->get();
                $paid_disburse = \App\Models\PaidDisbursement::where('contract_id',$m->id)->first();
                $acc_dis = \App\Models\GeneralJournal::where('tran_id',$paid_disburse->id)->where('tran_type','loan-disbursement')->first();
                //dd($paid_disburse);
                if($charge){

                    $total_charge = 0;
                    foreach ($charge as $fee){
                        $amount = $fee->amount * $paid_disburse->loan_amount;
                        $total_charge += $amount;
                    }

                    if($paid_disburse){
                        $money_dis = ($paid_disburse->disburse_amount-0) - ($total_charge);
                        $paid_disburse->total_service_charge = $total_charge;
                        $paid_disburse->total_money_disburse = $money_dis;
                        $paid_disburse->disburse_amount = $money_dis;
                        $paid_disburse->cash_pay = $money_dis;
                        //$paid_disburse->save();
                    }

                    /////Cash Account
                    $c_acc = new \App\Models\GeneralJournalDetail();
                    $c_acc->journal_id = $acc_dis->id;
                    $c_acc->acc_chart_id = $paid_disburse->cash_out_id;
                    $c_acc->dr = $total_charge;
                    $c_acc->cr = 0;
                    $c_acc->j_detail_date = $paid_disburse->paid_disbursement_date;
                    $c_acc->description = "Service Payment";
                    $c_acc->tran_id = $paid_disburse->id;
                    $c_acc->tran_type = 'loan-disbursement';
                    $c_acc->name = $m->client_id;
                    $c_acc->branch_id = $m->branch_id;
                    $c_acc->save();


                    foreach ($charge as $fee){
                        $charge_p = \App\Models\Charge::where('code',$fee->charge_id)->first();
                        $loan_charge = new \App\Models\LoanCharge();
                        $loan_charge->loan_id = $m->id;
                        $loan_charge->charge_id = $charge_p->id;
                        $loan_charge->name = $charge_p->name;
                        $loan_charge->amount = $fee->amount;
                        $loan_charge->charge_option = $charge_p->charge_option;
                        $loan_charge->charge_type  = $charge_p->charge_type;
                        $loan_charge->status = "Yes";

                        if($loan_charge->save()){
                            $charge_amount = $fee->amount * $paid_disburse->loan_amount;
                            $pay_charge = new \App\Models\DisbursementServiceCharge();

                            $pay_charge->loan_disbursement_id = $paid_disburse->id;
                            $pay_charge->service_charge_id = $loan_charge->id;
                            $pay_charge->service_charge_amount = $charge_amount;
                            $pay_charge->charge_id = $charge_p->id;
                            $pay_charge->save();


                            /////Service Account
                            $c_acc = new \App\Models\GeneralJournalDetail();
                            $c_acc->journal_id = $acc_dis->id;
                            $c_acc->acc_chart_id = \App\Helpers\ACC::accServiceCharge($charge_p->id);
                            $c_acc->dr = 0;
                            $c_acc->cr = $charge_amount;
                            $c_acc->j_detail_date = $paid_disburse->paid_disbursement_date;
                            $c_acc->description = $charge_p->name; ////'Service Charge';
                            $c_acc->tran_id = $paid_disburse->id;
                            $c_acc->tran_type = 'loan-disbursement';
                            $c_acc->name = $m->client_id;
                            $c_acc->branch_id = $m->branch_id;
                            $c_acc->save();
                        }

                    }

                }
            //dd($c_acc);
        }
    }


    if($loans->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate_charge?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }


});
Route::get('api/generate-diburse-acc',function (){
    $disburse =  \App\Models\PaidDisbursement::simplePaginate(300);
    $n = ($disburse->currentPage()+1);
    if($disburse != null){
        foreach ($disburse as $d){
            $loan = \App\Models\Loan::find($d->contract_id);
            if($loan != null){
                $d->branch_id = $loan->branch_id;
                /*if($d->save()){
                    \App\Models\PaidDisbursement::accDisburseTransaction($d,$loan->branch_id);
                }*/
				\App\Models\PaidDisbursement::accDisburseTransaction($d,$loan->branch_id);
            }
        }
    }
    if($disburse->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-diburse-acc?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }
});


Route::get('api/generate-diburse-acc-date',function (){
    $disburse =  \App\Models\PaidDisbursement::simplePaginate(100);
    $n = ($disburse->currentPage()+1);
    if($disburse != null){
        foreach ($disburse as $d){
            $loan = \App\Models\Loan::find($d->contract_id);
            if($loan != null){
                $d->branch_id = $loan->branch_id;
                if($d->save()){

                    $jl = \App\Models\GeneralJournal::where('tran_id', optional($d)->id)->where('tran_type','loan-disbursement')->first();
                    $jl->date_general = $d->paid_disbursement_date;
                    $jl->save();
                    //dd($jl);
                }
            }
        }
    }
    if($disburse->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-diburse-acc-date?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }
});

Route::get('api/generate-repayment-acc',function (){
    $repay = \App\Models\LoanPayment::simplePaginate(100);
    $n = ($repay->currentPage()+1);
    if($repay != null){
        foreach ($repay as $r){
            \App\Helpers\MigrateData::repaymentAcc($r);
        }
    }
    if($repay->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-repayment-acc?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});


Route::get('api/generate-saving-acc',function (){
    $saving = \App\Models\CompulsorySavingTransaction::where('train_type','deposit')->simplePaginate(100);
    $n = ($saving->currentPage()+1);
    if($saving != null){
        foreach ($saving as $s){
            $client = \App\Models\ClientR::find($s->customer_id);
            if($client != null) {
                //$disburse = \App\Models\PaidDisbursement::where('client_id', optional($client)->id)->orderBy('id', 'desc')->first();
                $disburse = \App\Models\PaidDisbursement::where('client_id', optional($client)->id)->where('id',optional($s)->tran_id)->first();
				//dd($disburse);
                $ref_no = optional($disburse)->reference;
                $loan = \App\Models\Loan::find(optional($disburse)->contract_id);
                App\Helpers\MigrateData::accDepositMigration($s, optional($loan)->branch_id, $client, $ref_no, $disburse);
            }
        }
    }
    if($saving->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-saving-acc?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});


Route::get('api/generate-accrue-acc',function (){
    $accrue = \App\Models\CompulsoryAccrueInterests::simplePaginate(100);
    $n = ($accrue->currentPage()+1);
    if($accrue != null){
        foreach ($accrue as $a){
            App\Helpers\MigrateData::accAccurInterestCompulsory($a);
        }
    }
    if($accrue->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-accrue-acc?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/update-old-doc',function (){
    $accrue = \App\Models\ClientO::select('id')->orderBy('id','asc')->simplePaginate(200);
    $n = ($accrue->currentPage()+1);
    if($accrue != null){
        foreach ($accrue as $a){
            \App\Models\OldDuc::updateOldDuc($a->id);
        }
    }
    if($accrue->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-old-doc?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/generate-withdraw-acc',function (){
    $withdraw = \App\Models\CashWithdrawal::simplePaginate(100);
    $n = ($withdraw->currentPage()+1);
    if($withdraw != null){
        foreach ($withdraw as $wtd){
            \App\Helpers\MigrateData::accWithdrawTransaction($wtd);
        }
    }
    if($withdraw->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-withdraw-acc?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/update_cash_acc',function (){
    $new = \App\Models\NewMember::simplePaginate(500);
    $n = ($new->currentPage()+1);
    if($new != null){
        foreach ($new as $nn){

            if(optional($nn)->client_id != null) {
                \Illuminate\Support\Facades\DB::unprepared("update clients set cash_acc_code= '" . $nn->cash_acc_code . "'
            where  client_number = '" . optional($nn)->client_id . "' ");
            }

        }
    }
    if($new->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update_cash_acc?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});
Route::get('api/generate-group-loan',function (){
    $new = \App\Models\GroupLoanDetail::simplePaginate(500);
    $n = ($new->currentPage()+1);
    if($new != null){
        foreach ($new as $nn){
            $client = \App\Models\ClientR::find($nn->client_id);

            if($client != null){
                $branch_id = $client->branch_id;
                $center_id = $client->center_leader_id;
                if($branch_id >0){
                    $branch = \App\Models\BranchU::find($branch_id);
                    $branch_code = optional($branch)->code;

                    $center_code = null;
                    if($center_id >0){
                        $center = \App\Models\CenterLeader::find($center_id);
                        $center_code = optional($center)->code;
                    }

                    $group = \App\Models\GroupLoan::find($nn->group_loan_id);

                    $g_code_new = ($branch_code != null?$branch_code.'-':'').
                        ($center_code != null?$center_code.'-':'').$group->group_code;

                    $nn->group_loan_id_new = $g_code_new;
                    $nn->save();
                }


            }
        }
    }
    if($new->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-group-loan?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/create-group-loan-n',function (){

    $new = \App\Models\GroupLoanDetail::simplePaginate(500);
    $n = ($new->currentPage()+1);
    if($new != null){
        foreach ($new as $nn){
            $group_loan_id_new = $nn->group_loan_id_new;
            if($group_loan_id_new != null){
                $group_loan = \App\Models\GroupLoan2::where('group_code',$group_loan_id_new)
                    ->first();

                if($group_loan == null){
                    $group_loan = new \App\Models\GroupLoan2();
                    $group_loan->group_code = $group_loan_id_new;

                    //====================
                    $client = \App\Models\ClientR::find($nn->client_id);
                    $center_id = 0;
                    if($client != null){
                        $center_id = $client->center_leader_id;
                    }
                    $group_loan->center_id = $center_id;
                    //====================
                    //====================


                    $group_loan->save();
                }
                $nn->group_loan_id = $group_loan->id;
                if($nn->save()){
                    $loan = \App\Models\Loan2::find($nn->loan_id);
                    if($loan != null){
                        $loan->group_loan_id = $group_loan->id;
                        $loan->save();
                    }
                }
            }

        }
    }
    if($new->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/create-group-loan-n?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});


Route::get('api/update-branch-id-compulsory',function (){

    $new = \App\Models\LoanCompulsory::simplePaginate(500);
    $n = ($new->currentPage()+1);
    if($new != null){
        foreach ($new as $nn){
            $loan = \App\Models\Loan::find($nn->loan_id);
            if($loan != null){
                $nn->branch_id = $loan->branch_id;
                $nn->save();
            }
        }
    }
    if($new->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-branch-id-compulsory?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});


Route::get('api/update-interest-to-loans',function (){

    $new = \App\Models\Loan2::whereIn('loan_production_id',[1,2])->where('interest_rate',30)->simplePaginate(500);

    $n = ($new->currentPage()+1);
    if($new != null){
        foreach ($new as $nn){
            //dd($nn);
            $loan_id = optional($nn)->id;
            $interest = 0;

            ////28%
            /*if($nn->loan_amount==50000){
                $interest = 280;
            }if($nn->loan_amount==100000){
                $interest = 560;
            }if($nn->loan_amount==150000){
                $interest = 840;
            }if($nn->loan_amount==200000){
                $interest = 1120;
            }if($nn->loan_amount==250000){
                $interest = 1400;
            }if($nn->loan_amount==300000){
                $interest = 1680;
            }if($nn->loan_amount==350000){
                $interest = 1960;
            }if($nn->loan_amount==400000){
                $interest = 2240;
            }if($nn->loan_amount==450000){
                $interest = 2520;
            }if($nn->loan_amount==500000){
                $interest = 2800;
            }if($nn->loan_amount==550000){
                $interest = 3080;
            }if($nn->loan_amount==600000){
                $interest = 3360;
            }if($nn->loan_amount==650000){
                $interest = 3640;
            }if($nn->loan_amount==700000){
                $interest = 3920;
            }if($nn->loan_amount==750000){
                $interest = 4200;
            }if($nn->loan_amount==800000){
                $interest = 4480;
            }if($nn->loan_amount==900000){
                $interest = 5040;
            }if($nn->loan_amount==1000000){
                $interest = 5600;
            }*/


            if($nn->loan_amount==50000){
                $interest = 300;
            }if($nn->loan_amount==100000){
                $interest = 600;
            }if($nn->loan_amount==150000){
                $interest = 900;
            }if($nn->loan_amount==200000){
                $interest = 1200;
            }if($nn->loan_amount==250000){
                $interest = 1500;
            }if($nn->loan_amount==300000){
                $interest = 1800;
            }if($nn->loan_amount==350000){
                $interest = 2100;
            }if($nn->loan_amount==400000){
                $interest = 2400;
            }if($nn->loan_amount==450000){
                $interest = 2700;
            }if($nn->loan_amount==500000){
                $interest = 3000;
            }if($nn->loan_amount==550000){
                $interest = 3300;
            }if($nn->loan_amount==600000){
                $interest = 3600;
            }if($nn->loan_amount==650000){
                $interest = 3900;
            }if($nn->loan_amount==700000){
                $interest = 4200;
            }if($nn->loan_amount==750000){
                $interest = 4500;
            }if($nn->loan_amount==800000){
                $interest = 4800;
            }if($nn->loan_amount==900000){
                $interest = 5400;
            }if($nn->loan_amount==1000000){
                $interest = 6000;
            }
            //dd($interest);

            DB::statement("UPDATE loan_disbursement_calculate SET loan_disbursement_calculate.interest_s =$interest ,loan_disbursement_calculate.total_s= loan_disbursement_calculate.principal_s+$interest where loan_disbursement_calculate.payment_status ='pending'AND loan_disbursement_calculate.disbursement_id=$loan_id");

            $interest_receive =  \App\Models\LoanCalculate::where('disbursement_id',$loan_id)->where('payment_status','pending')->sum('interest_s');
            $nn->interest_receivable = $interest_receive;
            $nn->save();
        }
    }

    if($new->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-interest-to-loans?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/update-client-seq',function (){

    $new = \App\Models\ClientR::simplePaginate(500);
    $n = ($new->currentPage()+1);
    if($new != null){
        foreach ($new as $nn){
            $client_num = $nn->client_number;
            //$branch_id = $nn->branch_id;
            $branch_id = $nn->branch_id;
            if($branch_id >0){
                $last_num = 1;
                if($client_num != null) {
                    $arr_num = explode('-', $client_num);
                    $last_num = end($arr_num);
                    if($last_num != null){
                        $last_num = RemoveChar($last_num);

                    }
                    if($last_num != null && $last_num != ''){
                        $last_num = $last_num -0;
                    }

                }
                $branch_seq = \App\Models\ClientBranchSeq::where('branch_id',$branch_id)->where('type','client')->first();
                if($branch_seq == null){
                    $branch_seq  = new \App\Models\ClientBranchSeq();
                    $branch_seq->branch_id = $branch_id;
                    $branch_seq->type = 'client';
                    $branch_seq->seq = 1;
                    $branch_seq->save();
                }else{
                    $las_seq = $branch_seq->seq;
                    if($las_seq < $last_num){
                        if($last_num>0) {
                            $branch_seq->seq = $last_num;
                            $branch_seq->save();
                        }
                    }

                }

            }
        }
    }
    if($new->hasMorePages()) {
        return '<head>
                <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-client-seq?page=' . $n) . '\'>
                </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/generate-loans-moey',function (\Illuminate\Http\Request $request){

    $branch_code  = $request->branch_code;
    $charge_option = ['Fixed Amount'=>'1', 'Of Loan Amount'=>'2'];
    $compulsory_type = ['Every Repayments'=>'3','Every Repayment'=>'3', 'Deposit Before Disbursement'=>'1', 'Deduct'=>'2'];

    $loans = \App\Models\LoanImportList::simplePaginate(1);
    // $n = ($loans->currentPage()+1);
    $n = ($request->page??1) +1;

    $rows = $loans;

    $arr = ['Monthly'=>'Month', 'Daily'=>'Day', 'Weekly'=>'Week', 'Two-Weeks'=>'Two-Weeks','Four-Weeks'=>'Four-Weeks', 'Yearly'=>'Year'];
    if($rows != null){
        if(count($rows)>0){
            foreach ($rows as $m){

                $interest_type = ['MoeYan Rate'=>'moeyan-effective-flate-rate','Flixible Rate'=>'moeyan-flexible-rate', 'Efective Flate'=>'moeyan-effective-rate', '7'=>'moeyan-effective-flate-rate', '9'=>'moeyan-flexible-rate', '12'=>'moeyan-effective-rate'];

                $branch = \App\Models\BranchU::where('code', $m->branch_code)->first();

                $center = \App\Models\CenterLeaderR::where('code', $m->center_id)->where('branch_id', optional($branch)->id)->first();
                $user = \App\User::where('user_code', $m->loan_officer_id)->first();

               // $client = \App\Models\ClientR::where('client_number',$m->client_id)->first();
                $client = \App\Models\ClientR::where('client_number',trim($m->client_id))->first();

                if($client == null){
                    $client = new \App\Models\ClientR();
                    $client->loan_officer_id = optional($user)->id;
                    $client->branch_id = optional($branch)->id;
                    $client->center_leader_id = optional($center)->id;
                    $client->client_number = $m->client_id;
                    $client->name = $m->customer_name;
                    $client->name_other = $m->customer_name;
                    $client->save();
                }
                //dd($client);
                if($client !=null) {
                    $loan = new \App\Models\Loan2();

                    $branch = \App\Models\BranchU::where('code', $m->branch_code)->first();

                    $center = \App\Models\CenterLeaderR::where('code', $m->center_id)->where('branch_id', optional($branch)->id)->first();
                    $user = \App\User::where('user_code', $m->loan_officer_id)->first();
                    if ($user) {
                        $co_id = $user->id;
                    } else {
                        $co_id = 0;
                    }
                    $group = null;
                    if ($m->group_id != null) {
                        $group = \App\Models\GroupLoan2::where('group_code', $m->group_id)->first();

                        if ($group == null) {
                            $group = new  \App\Models\GroupLoan2();
                        }
                        $group->group_code = $m->group_id;
                        $group->center_id = optional($center)->id;
                        $group->save();
                    }


                    $loan_product = \App\Models\LoanProduct::where('code', $m->loan_type)->first();

                    $loan->disbursement_number = $m->loan_id;
                    //dd($m->loan_id);
                    //dd($loan);

                    $loan->branch_id = optional($branch)->id;
                    $loan->center_leader_id = (optional($center)->id != null || optional($center)->id != '') ? optional($center)->id : 0;
                    if($m->term_pending_repayment =="" AND $m->status="activated"){
                        $m->term_pending_repayment = 1;
                    }
                    $loan->loan_officer_id = $co_id;
                    $loan->group_loan_id = optional($group)->id;
                    $loan->currency_id = 1;
                    $loan->client_id = optional($client)->id;
                    $loan->loan_application_date = $m->disbursement_date != 0000 - 00 - 00 ? $m->disbursement_date : null;
                    $loan->first_installment_date = $m->start_new_repayment_date != 0000 - 00 - 00 ? $m->start_new_repayment_date : null;
                    $loan->loan_production_id = optional($loan_product)->id;
                    $loan->loan_amount = $m->loan_amount;
                    $loan->principle_receivable = $m->principle_outstanding + $m->priciple_late_payment;
                    $loan->principle_repayment = $m->priciple_repayment;
                    $loan->interest_receivable = $m->interest_outstanding + $m->interest_late_payment;
                    $loan->interest_repayment = $m->interest_repayment;
                    $loan->loan_term_value = $m->term_pending_repayment;
                    $loan->loan_term = $arr[$m->repayment_terms];
                    $loan->repayment_term ='Monthly';
                    $loan->interest_rate_period ='Monthly';
                    $loan->interest_method = $interest_type[$m->interest_type];
                    $loan->interest_rate = $m->interest_rate * 100;
                    $loan->loan_cycle = $m->loans_cycle ? $m->loans_cycle : 1;
                    if ($m->principle_outstanding > 0) {
                        $loan->disbursement_status = 'Activated';
                    } else{
                        $loan->disbursement_status = "Closed";
                    }

                    $loan->you_are_a_group_leader = optional($client)->you_are_a_group_leader!=''?optional($client)->you_are_a_group_leader:'No';
                    $loan->you_are_a_center_leader = optional($client)->you_are_a_center_leader!=''?optional($client)->you_are_a_center_leader:'No';

                    $l_product_id = $loan_product->id;
                    //dd($loan);
                    if ($loan->save()) {
                        if($m->group_id != null) {
                            $client_id = $loan->client_id;
                            $group_loan_id = $loan->group_loan_id;
                            $md = null;
                            if ($group_loan_id > 0 && $client_id > 0) {
                                $md = \App\Models\GroupLoanDetailApi::where('client_id', $client_id)->where('group_loan_id', $group_loan_id)->first();
                                if ($md == null) {
                                    $md = new \App\Models\GroupLoanDetailApi();
                                }
                                $md->client_id = $client_id;
                                $md->group_loan_id = $group_loan_id;
                                $md->loan_id = $loan->id;
                                $md->save();
                            }
                        }

                        $date = \App\Helpers\MigrateData::get_pre_date($m->start_new_repayment_date, $loan->repayment_term);
                         \App\Helpers\MigrateData::paid_disbursement($loan,$m);
                         \App\Helpers\MigrateData::loanrepayment($loan, $m);

                       // \App\Helpers\MigrateData::late_repayment($loan, $m, $date);


                        $first_date_payment = $m->start_new_repayment_date;
                        $interest_method = $interest_type[$m->interest_type];
                        $principal_amount = $m->principle_outstanding;
                        $loan_duration = $loan->loan_term_value;
                        $loan_duration_unit = $loan->loan_term;
                        $repayment_cycle = $loan->repayment_term;
                        $loan_interest = $loan->interest_rate;
                        $loan_interest_unit = $loan->interest_rate_period;
                        if($m->principle_outstanding-0 > 0) {

                            if($loan->interest_method != "moeyan-flexible-rate"){
                                \App\Helpers\MigrateData::gen_schedule($m, $date, $first_date_payment, $interest_method,
                                    $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit, $loan->id);
                            }


                        }

                    }
                }

            }
        }
    }


    //});
    if($loans->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/generate-loans-moey? &page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});


Route::get('api/update-first-schedule',function (\Illuminate\Http\Request $request){

    $loans = \App\Models\Loan2::simplePaginate(500);
    $n = ($loans->currentPage()+1);

    foreach ($loans as $loan) {
        $dis_id = $loan->id;
        $loan_cal = \App\Models\LoanCalculate::where('disbursement_id', $dis_id)->where('payment_status', 'paid')->orderBy('id', 'ASC')->first();
        if ($loan_cal != null) {
            $loan_cal->principle_pd = $loan_cal->principal_p;
            $loan_cal->interest_pd = $loan_cal->interest_p;
            $loan_cal->total_pd = $loan_cal->total_p;
            $loan_cal->save();
            \App\Helpers\MFS::updateOutstanding($dis_id);
        }
    }

    if($loans->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-first-schedule? &page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/update-group-id-schedule',function (\Illuminate\Http\Request $request){

    $loans = \App\Models\Loan2::simplePaginate(100);
    $n = ($loans->currentPage()+1);

    foreach ($loans as $loan) {
        $dis_id = $loan->id;
        $loan_cals = \App\Models\LoanCalculate::where('disbursement_id', $dis_id)->get();
        if ($loan_cals != null) {
            foreach ($loan_cals as $loan_cal) {
                $loan_cal->branch_id = $loan->branch_id??0;
                $loan_cal->group_id = $loan->group_loan_id??0;
                $loan_cal->center_id = $loan->center_leader_id??0;
                $loan_cal->loan_product_id = $loan->loan_production_id??0;
                $loan_cal->save();
            }
        }
    }

    if($loans->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-group-id-schedule? &page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/update-group-loan-branch',function (\Illuminate\Http\Request $request){

    $group_loan = \App\Models\LoanImportList::simplePaginate(500);
    $n = ($group_loan->currentPage()+1);

    foreach ($group_loan as $g_loan) {
        $group = \App\Models\GroupLoan::where('center_id',$g_loan->center_id)->where('group_code',trim($g_loan->new_group_id))->first();
        $branch = \App\Models\BranchU::where('code',trim($g_loan->branch_code))->first();
        if ($group != null && $branch != null){
            $group->branch_id = $branch->id;
            $group->save();

        }

    }

    if($group_loan->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-group-loan-branch? &page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/update-wrong-group-loan',function (\Illuminate\Http\Request $request){
    $branch_code  = $request->branch_code;
    $loans = \App\Models\LoanImportList::where(function ($w) use ($branch_code){
        if($branch_code != null){
            $w->where('branch_code',$branch_code);
        }
    })->simplePaginate(500);
    $n = ($loans->currentPage()+1);

    foreach ($loans as $loan) {
        $client = \App\Models\ClientR::where('client_number',trim($loan->client_id))->first();
        if($client != null){
            $l = \App\Models\Loan2::where('client_id',$client)->first();

            $group = \App\Models\GroupLoan::where('group_code',trim($loan->new_group_id))->first();
            if($l != null && $group != null){
                $l->group_loan_id = $group->id;
                if($l->save()){
                    $loan_cal = \App\Models\LoanCalculate::where('disbursement_id',$l->id)->get();
                    if($loan_cal != null){
                        foreach ($loan_cal as $l_cal){
                            $l_cal->group_id = $group->id;
                            $loan_cal->save();
                        }
                    }
                }
            }
        }

    }
    if($loans->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-wrong-group-loan?branch_code='.$branch_code.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});


Route::get('api/change-name-client',function (\Illuminate\Http\Request $request){

    if (companyReportPart() == 'company.demo_mfi_mm'){
        $client=\App\Models\Client::simplePaginate(500);
        $n = ($client->currentPage()+1);

//    dd($client);


        foreach ($client as $c) {
            $name_rand=str_random(10);
            $name_rand2=str_random(12);
            $name_rand3=str_random(15);
            $phone_rand=str_random(10);


            $cli = \App\Models\Client::where('id',trim($c->id))->first();
            $cli->name=$name_rand;
            $cli->name_other=$name_rand2;
            $cli->nrc_number=$name_rand3;
            $cli->nrc_type='Old Format';
            $cli->primary_phone_number=$phone_rand;
            $cli->save();

        }

        if($client->hasMorePages()) {
            return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/change-name-client?&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
        }else{
            return '<h1>OK</h1>';
        }

    }


});


Route::get('api/remove-saving-transaction',function (\Illuminate\Http\Request $request){


    $date=$request->date;
    $branch_id=$request->branch_id;

    if ($date != null){
        $remove_date=$date;

        $com_acc_interest=\App\Models\CompulsoryAccrueInterests::where('tran_date',$remove_date)
            ->where('train_type','accrue-interest')
//            ->whereNull('tran_id')
//            ->where('branch_id',$branch_id)
            ->simplePaginate(1500);


        foreach ($com_acc_interest as $cai){

            \App\Models\CompulsoryAccrueInterests::where('id',$cai->id)->delete();

            \App\Models\CompulsorySavingTransaction::where('tran_date',$remove_date)
                ->where('train_type','accrue-interest')
                ->where('tran_id',$cai->id)
                ->delete();


          /*  \App\Models\GeneralJournal::where('date_general',$remove_date)
                ->where('tran_type','accrue-interest')
                ->where('tran_id',$cai->id)
                ->delete();


            \App\Models\GeneralJournalDetail::where('j_detail_date',$remove_date)
                ->where('tran_type','accrue-interest')
                ->where('tran_id',$cai->id)
                ->delete();*/

            /*$loan_compulsory=\App\Models\LoanCompulsoryByBranch::find($cai->loan_compulsory_id);

            if ($loan_compulsory != null){

                $interests = $loan_compulsory->interests - $cai->amount;
                $available_balance = $loan_compulsory->principles - $interests;

                $loan_compulsory->interests = $interests;
                $loan_compulsory->available_balance = $available_balance;


                $loan_compulsory->save();



            }*/


        }


        $n = ($com_acc_interest->currentPage()+1);


        if($com_acc_interest->hasMorePages()) {
            if($branch_id != null){
                return '<head>
        <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/remove-saving-transaction?date='.$remove_date.'&branch_id=' . $branch_id.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
            }else{
                return '<head>
        <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/remove-saving-transaction?date='.$remove_date.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
            }


        }else{
            return '<h1>OK</h1>';
        }
    }else{
        return '<h1>Empty Date</h1>';
    }




});



Route::get('api/remove-saving-interest-transaction',function (\Illuminate\Http\Request $request){


    $date=$request->date;

    if ($date != null){
        $remove_date=$date;

        $com_acc_interest=\App\Models\SavingAccrueInterests::where('date',$remove_date)
            ->simplePaginate(100);


        foreach ($com_acc_interest as $cai){
            $saving =\App\Models\Saving::find($cai->saving_id);

            $interests = $saving->interest_amount - $cai->amount;
            $available_balance = $saving->available_balance - $cai->amount;
            $principle = $saving->principle_amount - $cai->amount;
            $total_withdraw = $saving->total_withdraw - $cai->amount;

//        dd($interests,$available_balance);
            ////  Update loan Compulsory
            $saving->interest_amount = $interests;
            $saving->available_balance = $available_balance;
            $saving->principle_amount = $principle;
            //$saving->total_withdraw = $total_withdraw;


            if ($saving->save()){
                \App\Models\SavingAccrueInterests::where('id',$cai->id)->delete();

                \App\Models\SavingTransaction::where('date',$remove_date)
                    ->where('tran_type','accrue-interest')
                    ->where('tran_id',$cai->id)
                    ->delete();


                \App\Models\GeneralJournal::where('date_general',$remove_date)
                    ->where('tran_type','saving-interest')
                    ->where('tran_id',$cai->id)
                    ->delete();


                \App\Models\GeneralJournalDetail::where('j_detail_date',$remove_date)
                    ->where('tran_type','saving-interest')
                    ->where('tran_id',$cai->id)
                    ->delete();
            }




        }


        $n = ($com_acc_interest->currentPage()+1);


        if($com_acc_interest->hasMorePages()) {
            return '<head>
        <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/remove-saving-interest-transaction?date='.$remove_date.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
        }else{
            return '<h1>OK</h1>';
        }
    }else{
        return '<h1>Empty Date</h1>';
    }




});


Route::get('api/remove-duplicate-com-interest',function (\Illuminate\Http\Request $request){


    $date=$request->date;

    if ($date != null){
        $remove_date=$date;

        $com_acc_interest=\App\Models\CompulsoryAccrueInterests1::leftJoin('compulsory_saving_transaction','compulsory_saving_transaction.accrue_interest_id','compulsory_accrue_interests_26.id')
            ->where('compulsory_accrue_interests_26.tran_date',$remove_date)
            ->whereNull('compulsory_saving_transaction.accrue_interest_id')
            ->select('compulsory_accrue_interests_26.id as id')
            ->simplePaginate(100);
//            ->get();

//        dd($com_acc_interest);


        foreach ($com_acc_interest as $cai){

            \App\Models\CompulsoryAccrueInterests1::where('id',$cai->id)->delete();

            \App\Models\CompulsorySavingTransaction::where('tran_date',$remove_date)
                ->where('train_type','accrue-interest')
                ->where('accrue_interest_id',$cai->id)
                ->where('branch_id',25)
                ->delete();


            \App\Models\GeneralJournal::where('date_general',$remove_date)
                ->where('tran_type','accrue-interest')
                ->where('tran_id',$cai->id)
                ->where('branch_id',25)
                ->delete();


            \App\Models\GeneralJournalDetail::where('j_detail_date',$remove_date)
                ->where('tran_type','accrue-interest')
                ->where('tran_id',$cai->id)
                ->where('branch_id',25)
                ->delete();




        }


        $n = ($com_acc_interest->currentPage()+1);


        if($com_acc_interest->hasMorePages()) {
            return '<head>
        <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/remove-duplicate-com-interest?date='.$remove_date.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
        }else{
            return '<h1>OK</h1>';
        }
    }else{
        return '<h1>Empty Date</h1>';
    }


});

Route::get('api/remove-duplicate-com-august',function (\Illuminate\Http\Request $request){


    $date=$request->date;

    if ($date != null){
        $remove_date=$date;

        $com_acc_interest=\App\Models\CompulsoryAccrueInterests1::where('tran_date','2019-08-31')
            ->select('compulsory_accrue_interests_26.loan_id')
            ->groupBy('loan_id')
            ->having(\Illuminate\Support\Facades\DB::raw('count(loan_id)'), '>',1)
//            ->simplePaginate(100);
            ->get();

//        dd($com_acc_interest);

        $com_acc_interest2=\App\Models\CompulsorySavingTransaction::where('tran_date','2019-08-31')
            ->where('branch_id',26)
            ->select('compulsory_saving_transaction.loan_id')
            ->groupBy('loan_id')
            ->having(\Illuminate\Support\Facades\DB::raw('count(loan_id)'), '>',1)
//            ->simplePaginate(100);
            ->get();


        dd($com_acc_interest2);



        foreach ($com_acc_interest2 as $cai){

           /* $c = \App\Models\CompulsoryAccrueInterests1::where('loan_id', $cai->loan_id)
                ->first();*/

            $c = \App\Models\CompulsorySavingTransaction::where('loan_id', $cai->loan_id)
                ->where('branch_id',20)
                ->where('train_type','accrue-interest')
                ->where('tran_date',$remove_date)
                ->first();

//            dd($c);

             \App\Models\CompulsorySavingTransaction::where('tran_date',$remove_date)
                ->where('train_type','accrue-interest')
                ->where('id',$c->id)
                ->where('branch_id',20)
                ->delete();

            \App\Models\GeneralJournal::where('date_general',$remove_date)
                ->where('tran_type','accrue-interest')
                ->where('tran_id',$c->accrue_interest_id)
                ->where('branch_id',20)
                ->delete();


            \App\Models\GeneralJournalDetail::where('j_detail_date',$remove_date)
                ->where('tran_type','accrue-interest')
                ->where('tran_id',$c->accrue_interest_id)
                ->where('branch_id',20)
                ->delete();

//            \App\Models\CompulsoryAccrueInterests1::where('id',$c->id)->delete();

           /* \App\Models\CompulsorySavingTransaction::where('tran_date',$remove_date)
                ->where('train_type','accrue-interest')
                ->where('accrue_interest_id',$c->id)
                ->where('branch_id',20)
                ->delete();

            \App\Models\GeneralJournal::where('date_general',$remove_date)
                ->where('tran_type','accrue-interest')
                ->where('tran_id',$c->id)
                ->where('branch_id',20)
                ->delete();


            \App\Models\GeneralJournalDetail::where('j_detail_date',$remove_date)
                ->where('tran_type','accrue-interest')
                ->where('tran_id',$c->id)
                ->where('branch_id',20)
                ->delete();*/


        }

        dd('Completed');


        $n = ($com_acc_interest->currentPage()+1);


        if($com_acc_interest->hasMorePages()) {
            return '<head>
        <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/remove-duplicate-com-interest?date='.$remove_date.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
        }else{
            return '<h1>OK</h1>';
        }
    }else{
        return '<h1>Empty Date</h1>';
    }


});

Route::get('api/check-empty-com',function (\Illuminate\Http\Request $request){


    $date=$request->date;

    if ($date != null){
        $remove_date=$date;

        $com_acc_interest=\App\Models\CompulsoryAccrueInterests1::where('tran_date','2019-09-30')
            ->orWhere('tran_date','2019-08-31')
            ->select('compulsory_accrue_interests_27.loan_id')
            ->groupBy('loan_id')
            ->having(\Illuminate\Support\Facades\DB::raw('count(loan_id)'), 1)
//            ->simplePaginate(100);
            ->get();

//           dd($com_acc_interest);

        $id='';

        foreach ($com_acc_interest as $cai){

            $c = \App\Models\CompulsoryAccrueInterests1::where('loan_id', $cai->loan_id)
                ->get();

            if (count($c)>1){

                foreach ($c as $row){
                    \App\Models\CompulsoryAccrueInterests1::where('id',$row->id)->delete();


                    \App\Models\CompulsorySavingTransaction::where('tran_date',$row->tran_date)
                        ->where('train_type','accrue-interest')
                        ->where('accrue_interest_id',$row->id)
                        ->where('branch_id',27)
                        ->delete();

                    \App\Models\GeneralJournal::where('date_general',$row->tran_date)
                        ->where('tran_type','accrue-interest')
                        ->where('tran_id',$row->id)
                        ->where('branch_id',27)
                        ->delete();


                    \App\Models\GeneralJournalDetail::where('j_detail_date',$row->tran_date)
                        ->where('tran_type','accrue-interest')
                        ->where('tran_id',$row->id)
                        ->where('branch_id',27)
                        ->delete();
                }



//                $id .= $cai->loan_id.',';
            }

        }


        dd('Completed');


        $n = ($com_acc_interest->currentPage()+1);


        if($com_acc_interest->hasMorePages()) {
            return '<head>
        <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/check-empty-com?date='.$remove_date.'&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
        }else{
            return '<h1>OK</h1>';
        }
    }else{
        return '<h1>Empty Date</h1>';
    }


});


Route::get('/api/authorize_loan_pending_status','Admin\AuthorizeLoanPaymentTemCrudController@authorize_loan_repayment');

Route::post('api/get-data-cash-book-detail','Admin\ReportAccountingCrudController@getDataCashBookDetail');

Route::get('/api/get-loan-duepayment', 'Api\LoanDueRepaymentController@index');
Route::get('/api/get-loan-duepayment/{id}', 'Api\LoanDueRepaymentController@show');

Route::get('/api/get-saving', 'Api\SavingController@index');
Route::get('/api/get-saving/{id}', 'Api\SavingController@show');

Route::get('/api/get-saving-ajax/{id}','Api\SavingController@saving_ajax');

Route::get('/api/saving-product-option', 'Admin\SavingActivatedCrudController@productOptions');
Route::get('/api/get-item', 'Api\ItemController@index');
Route::get('/api/get-price-item', 'Api\ItemController@getPrice');
Route::get('/api/get-item/{id}', 'Api\ItemController@show');


Route::get('api/ajax-saving-options', 'Admin\SavingDepositReportController@savingOptions');
Route::get('api/close-journal', 'CloseJournalController@index');
Route::get('api/insert-gen-term-to-gen', function (){
    \App\Helpers\S::insGenTemToGen();
});
Route::get('api/insert-gen-term', function (){
    $g_terms = GeneralJournalDetailTem::simplePaginate(500);
    $n = ($g_terms->currentPage()+1);
    if($g_terms != null){
        foreach ($g_terms as $g){
            $m = new GeneralJournalDetail();
            $m->section_id = $g->section_id;
            $m->journal_id = $g->journal_id;
            $m->currency_id = $g->currency_id;
            $m->acc_chart_id = $g->acc_chart_id;
            $m->dr = $g->dr;
            $m->cr = $g->cr;
            $m->j_detail_date = $g->j_detail_date;
            $m->description = $g->description;
            $m->tran_id = $g->tran_id;
            $m->tran_type = $g->tran_type;
            $m->class_id = $g->class_id;
            $m->job_id = $g->job_id;
            $m->name = $g->name;
            $m->created_at = $g->created_at;
            $m->updated_at = $g->updated_at;
            $m->exchange_rate = $g->exchange_rate;
            $m->currency_cal = $g->currency_cal;
            $m->dr_cal = $g->dr_cal;
            $m->cr_cal = $g->cr_cal;
            $m->sub_section_id = $g->sub_section_id;
            $m->created_by = $g->created_by;
            $m->updated_by = $g->updated_by;
            $m->branch_id = $g->branch_id;
            $m->external_acc_id = $g->external_acc_id;
            $m->acc_chart_code = $g->acc_chart_code;
            $m->external_acc_chart_id = $g->external_acc_chart_id;
            $m->external_acc_chart_code = $g->external_acc_chart_code;
            $m->round_id = $g->round_id;
            $m->warehouse_id = $g->warehouse_id;
            $m->cash_flow_code = $g->cash_flow_code;
            $m->product_id = $g->product_id;
            $m->category_id = $g->category_id;
            $m->qty = $g->qty;
            $m->sale_price = $g->sale_price;
            if($m->save()){
                GeneralJournalDetailTem::where('id',$g->id)->delete();
            }
        }
    }
    if($g_terms->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/insert-gen-term?&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return redirect('/api/search-general-journal');
    }
});
Route::get('api/gen-loan-outstanding-tem', 'Admin\LoanOutstandingReportController@genLoanOutanding');


Route::get('api/update-compulsory-transaction-branch', function (){
    $rows = \App\Models\CompulsorySavingTransaction::where('train_type','deposit')
        ->where('branch_id',0)
        ->simplePaginate(500);
    $n = ($rows->currentPage()+1);
    if($rows != null){
        foreach ($rows as $r){

            $loan = \App\Models\LoanBranch::where('id',$r->loan_id)->select('branch_id')->first();
            if ($loan != null){
                $r->branch_id = optional($loan)->branch_id;
                $r->save();
            }

        }
    }
    if($rows->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/update-compulsory-transaction-branch?&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }
});


Route::get('api/remove-dup-compulsory-tran', function (){
    $rows = \App\Models\CompulsorySavingTransaction::join('loans_1','loans_1.id','compulsory_saving_transaction.loan_id')
        ->where('compulsory_saving_transaction.train_type','accrue-interest')
        ->where('compulsory_saving_transaction.branch_id',1)
        ->where('compulsory_saving_transaction.total_principle',0)
        ->select('compulsory_saving_transaction.id as id')
        ->simplePaginate(500);
    $n = ($rows->currentPage()+1);
    if($rows != null){
        foreach ($rows as $r){

            \App\Models\CompulsorySavingTransaction::where('id',$r->id)->delete();
            \App\Models\CompulsoryAccrueInterests::where('id',$r->tran_id)->delete();

        }
    }
    if($rows->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/remove-dup-compulsory-tran?&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }
});


Route::get('api/add-old-schedule', function (){
    $l_s = \App\Models\LoanSchedule2019::where('branch_id',27)->simplePaginate(500);
    $n = ($l_s->currentPage()+1);
    if($l_s != null){
        foreach ($l_s as $l){

            $check_exist = \App\Models\LoanDisbursementCalculateByBranch::where('disbursement_id',$l->disbursement_id)
                ->where('date_s',$l->date_s)
                ->select('id')
                ->first();

            if ($check_exist == null){
                $loan_dis_cal = new \App\Models\LoanDisbursementCalculateByBranch();
                $loan_dis_cal->no = $l->no;
                $loan_dis_cal->day_num = $l->day_num;
                $loan_dis_cal->disbursement_id = $l->disbursement_id;
                $loan_dis_cal->date_s = $l->date_s;
                $loan_dis_cal->principal_s = $l->principal_s;
                $loan_dis_cal->interest_s = $l->interest_s;
                $loan_dis_cal->penalty_s = $l->penalty_s;
                $loan_dis_cal->service_charge_s = $l->service_charge_s;
                $loan_dis_cal->total_s = $l->total_s;
                $loan_dis_cal->balance_s = $l->balance_s;
                $loan_dis_cal->date_p = $l->date_p;
                $loan_dis_cal->principal_p = $l->principal_p;
                $loan_dis_cal->interest_p = $l->interest_p;
                $loan_dis_cal->penalty_p = $l->penalty_p;
                $loan_dis_cal->service_charge_p = $l->service_charge_p;
                $loan_dis_cal->total_p = $l->total_p;
                $loan_dis_cal->balance_p = $l->balance_p;
                $loan_dis_cal->owed_balance_p = $l->owed_balance_p;
                $loan_dis_cal->payment_status = $l->payment_status;
                $loan_dis_cal->user_id = $l->user_id;
                $loan_dis_cal->branch_id = $l->branch_id;
                $loan_dis_cal->center_leader_id = $l->center_leader_id;
                $loan_dis_cal->over_days_p = $l->over_days_p;
                $loan_dis_cal->created_at = $l->created_at;
                $loan_dis_cal->updated_at = $l->updated_at;
                $loan_dis_cal->updated_by = $l->updated_by;
                $loan_dis_cal->principle_pd = $l->principle_pd;
                $loan_dis_cal->interest_pd = $l->interest_pd;
                $loan_dis_cal->total_pd = $l->total_pd;
                $loan_dis_cal->penalty_pd = $l->penalty_pd;
                $loan_dis_cal->payment_pd = $l->payment_pd;
                $loan_dis_cal->service_pd = $l->service_pd;
                $loan_dis_cal->compulsory_pd = $l->compulsory_pd;
                $loan_dis_cal->compulsory_p = $l->compulsory_p;
                $loan_dis_cal->count_payment = $l->count_payment;
                $loan_dis_cal->exact_interest = $l->exact_interest;
                $loan_dis_cal->charge_schedule = $l->charge_schedule;
                $loan_dis_cal->compulsory_schedule = $l->compulsory_schedule;
                $loan_dis_cal->total_schedule = $l->total_schedule;
                $loan_dis_cal->balance_schedule = $l->balance_schedule;
                $loan_dis_cal->penalty_schedule = $l->penalty_schedule;
                $loan_dis_cal->group_id = $l->group_id;
                $loan_dis_cal->center_id = $l->center_id;
                $loan_dis_cal->loan_product_id = $l->loan_product_id;
                $loan_dis_cal->remark = $l->remark;
                $loan_dis_cal->old_interest = $l->old_interest;
                $loan_dis_cal->is_mobile = $l->is_mobile;

                if ($loan_dis_cal->save()){
                    \App\Models\LoanSchedule2019::where('id',$l->id)->delete();
                }
            }

        }
    }
    if($l_s->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/add-old-schedule?&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }
});


Route::get('test',function (){
    return view('test');
});