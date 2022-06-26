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

Route::get('/', function () {
    return view('welcome');
});
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

Route::get('/api/acc-chart-external', 'Api\AccChartExternalController@index');
Route::get('/api/acc-chart-external/{id}', 'Api\AccChartExternalController@show');


Route::get('/api/supplier', 'Api\SupplierController@index');
Route::get('/api/supplier/{id}', 'Api\SupplierController@show');

// Route::get('/api/nrc-prefix', 'Api\NRCprefixController@index');
Route::get('/api/nrc-prefix/{id}', 'Api\NRCprefixController@index');
// Route::get('/api/nrc-prefix/{id}', 'Api\NRCprefixController@show');


Route::get('api/search-general-journal', 'Admin\GeneralJournalCrudController@search_journal');

Route::get('/api/get-working-status', 'Api\WorkingStatusController@index');
Route::get('/api/get-working-status/{id}', 'Api\WorkingStatusController@show');


Route::get('/api/get-user', 'Api\UserController@index');
Route::get('/api/get-user/{id}', 'Api\UserController@show');

Route::get('/api/compulsory', 'Api\CompulsoryProductController@index');
Route::get('/api/compulsory/{id}', 'Api\CompulsoryProductController@show');


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


Route::get('/api/get-client', 'Api\ClientController@index');
Route::get('/api/get-client/{id}', 'Api\ClientController@show');
Route::get('/api/get-client-g/{id}', 'Api\ClientController@showG');


Route::get('/api/get-branch', 'Api\BranchController@index');
Route::get('/api/get-branch/{id}', 'Api\BranchController@show');
/*
Route::get('/api/get-branch-u', 'Api\BranchUController@index');
Route::get('/api/get-branch-u/{id}', 'Api\BranchUController@show');*/
Route::get('/api/get-branch-code', 'Api\BranchUController@getBranch');

Route::get('/api/get-customer-group', 'Api\CustomerGroupController@index');
Route::get('/api/get-customer-group/{id}', 'Api\CustomerGroupController@show');

Route::get('/api/get-center-leader', 'Api\CenterLeaderController@index');
Route::get('/api/get-center-leader/{id}', 'Api\CenterLeaderController@show');


Route::get('/api/get-user', 'Api\UserController@index');
Route::get('/api/get-user/{id}', 'Api\UserController@show');


Route::get('/api/customer', 'Api\CustomerController@index');
Route::get('/api/customer/{id}', 'Api\CustomerController@show');


Route::get('/api/get-loan-product', 'Api\LoanProductController@index');
Route::get('/api/get-loan-product/{id}', 'Api\LoanProductController@show');





Route::get('/api/get-center-leader-name', 'Api\CenterLeanderNameController@index');
Route::get('/api/get-center-leader-name-id', 'Api\CenterLeanderIdNameController@index');
Route::get('/api/get-center-leader-name-id/{id}', 'Api\CenterLeanderIdNameController@show');
Route::get('/api/get-center-leader-name/{id}', 'Api\CenterLeanderNameController@show');



Route::get('api/get-payment-schedule', 'Admin\LoanCrudController@get_payment_schedule');
Route::get('api/get-charge-service', 'Admin\LoanCrudController@get_charge_service');

Route::get('api/get-first-date-payment', 'Admin\LoanCrudController@getFirstDatePayment');
Route::get('api/get-loan-calculator', 'Admin\LoanCalculatorCrudController@getLoanCalculation');
Route::get('api/print-loan-payment', 'Admin\LoanPaymentCrudController@printPayment');
Route::get('api/find_client', 'Api\ClientController@getClient');
Route::get('api/find_client_loan', 'Api\ClientController@getLoan');

Route::get('/api/search-client', 'Admin\LoanOutstandingCrudController@clientOptions');
Route::get('/api/loan-repayment-list', 'Admin\LoanPaymentCrudController@loanRepaymentList');
Route::get('/api/delete-payment', 'Admin\LoanPaymentCrudController@deletePayment');

// Repayment Lists
// Route::get('due-repayment-list', 'Admin\LoanPaymentCrudController@dueRepaymentList');
// Route::get('late-repayment-list', 'Admin\LoanPaymentCrudController@lateRepaymentList');
Route::get('admin/report/plan-repayments','Admin\ReportController@planRepayments');
// Route::get('admin/report/plan-repayments-data','Admin\ReportController@planRepaymentsData');
Route::get('admin/report/plan-late-repayments','Admin\ReportController@planLateRepayments');
// Route::get('admin/report/payment-deposits','Admin\ReportController@paymentDeposits');
// Route::get('admin/report/plan-repayments','Admin\ReportRepaymentCrudController@planRepayments');
// Route::controller('datatables', 'DatatablesController', [
//     'anyData'  => 'datatables.data',
//     'getIndex' => 'datatables',
// ]);

Route::get('/api/client-option', 'Admin\LoanCrudController@clientOptions');
Route::get('/api/guarantor-option', 'Admin\LoanCrudController@guarantorOptions');
Route::get('/api/loan-product-option', 'Admin\LoanCrudController@loanProductOptions');
Route::get('/api/loan-officer-option', 'Admin\LoanCrudController@loanOfficerOptions');
Route::get('/api/loan-option', 'Admin\LoanDisburesementDepositUCrudController@loanOptions');
Route::get('/api/branch-option', 'Admin\LoanCrudController@branchOptions');
Route::get('/api/center-option', 'Admin\LoanCrudController@centerOptions');




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


Route::get('/api/get-loan-disbursement', 'Api\LoanController@index');
Route::get('/api/get-loan-disbursement/{id}', 'Api\LoanController@show');

Route::get('/api/get-loan-disbursement-d', 'Api\LoanDepositController@index');
Route::get('/api/get-loan-disbursement-d/{id}', 'Api\LoanDepositController@show');



Route::get('/api/loan-disbursement-deposit', 'Admin\LoanDepositCrudController@loanDisbursementDeposit');
Route::get('api/change-branch', function (\Illuminate\Http\Request $request){
    $branch_id = $request->branch_id;
    session(['s_branch_id' => $branch_id]);

});


Route::get('/api/get-loan-disbursement-od', 'Api\LoanUController@index');
Route::get('/api/get-loan-disbursement-od/{id}', 'Api\LoanUController@show');
Route::post('/api/dashboard_search', 'Api\ReportController@dashboard');



Route::get('/api/loan-disbursement-paid', 'Admin\MyPaidDisbursementCrudController@loanDisbursementPaid');




Route::get('api/generate-member',function (){

    //$members = \App\Models\NewMember::where('id','<',3)->simplePaginate(50);
    //dd($members);
    $members = \App\Models\NewMember::simplePaginate(10);
    $n = ($members->currentPage()+1);

    //return 'ok';
//where('id','<=',300)->where('id','>=',200)->
    //\App\Models\NewMember::chunk(100, function($members)
    //{
        foreach ($members as $m){
            //======================= Branch ===================================
            $branch = \App\Models\Branch::where('code',$m->branch_code)->first();
            if($branch == null){
                $branch = new \App\Models\Branch();
            }
            $branch->title = $m->branch_name;
            $branch->code = $m->branch_code;
            $branch->phone = '09-251788658';
            $branch->save();
            //=================================================================

            //======================= User =================================
            $user = \App\User::where('user_code',$m->loan_officer_code)->first();

            if($user == null){
                $user = new \App\User();
            }

            $user->user_code = $m->loan_officer_code;
            $user->name = $m->loan_officer_name;
            $user->email = $m->loan_officer_name .'@cloudnet.com';
            $user->password = $m->loan_officer_name .'@cloudnet.com';
            $user->branch_id = $branch->id;
            $user->save();
            //======================= End User =================================

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

            $client->branch_id = $branch->id;
            $client->client_number = $m->client_id;
            $client->register_date = $m->date_register;
            $client->center_leader_id = $m->center_id;
            $client->you_are_a_group_leader = $m->group_leader;
            $client->you_are_a_center_leader = $m->center_leader;
            $client->remark = 'active';
            $client->name = $m->client_name_en;
            $client->name_other = $m->client_name_mm;
            $client->gender = $m->sex;
            //$client->dob = $m->dob;
            $client->education = $m->education_level;
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
            $client->loan_officer_id = $user->id;
            if($client->save()){
                \App\Helpers\MigrateData::create_loan($client->client_number);




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
            }
            //==================================================================

        }
    //});
    if($members->hasMorePages()) {
        return '<head>
      <meta http-equiv=\'refresh\' content=\'5; URL=' . url('api/generate-member?page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});
Route::get('api/generate-saving',function (){

    $members = \App\Models\NewMember::where('id','<',3)->simplePaginate(50);

    $n = ($members->currentPage()+1);

        foreach ($members as $m){
            $client = \App\Models\ClientR::where('client_number',$m->client_id)->first();
            if(optional($m)->total_saving_principle_opening_amount >0) {
                $disburse = \App\Models\PaidDisbursement::where('client_id', $client->id)->orderBy('id', 'desc')->first();
                if($disburse != null) {
                    $compulsory = \App\Models\LoanCompulsory::where('loan_id', optional($disburse)->contract_id)->first();
                    $transaction = new \App\Models\CompulsorySavingTransaction();
                    $transaction->customer_id = $client->id;
                    $transaction->tran_id = optional($disburse)->id;
                    $transaction->train_type = 'deposit';
                    $transaction->train_type_ref = 'disbursement';
                    $transaction->tran_id_ref = optional($disburse)->contract_id;
                    $transaction->tran_date = date('Y-m-d');
                    $transaction->amount = optional($client)->total_saving_principle_opening_amount;
                    $transaction->loan_id = optional($disburse)->contract_id;
                    $transaction->loan_compulsory_id = $compulsory->id;
                    if ($transaction->save()) {
                        $loan_compulsory = \App\Models\LoanCompulsory::where('loan_id', optional($disburse)->contract_id)->first();
                        if ($loan_compulsory != null) {
                            $loan_compulsory->compulsory_status = 'Active';
                            //$loan_compulsory->principles = $loan_compulsory->principles + $transaction->amount;
                            //$loan_compulsory->available_balance = $loan_compulsory->available_balance + $transaction->amount;
                            $loan_compulsory->save();
                        }
                        if(optional($m)->total_saving_interest_opening_amount >0) {
                            $loan_compulsory_s = \App\Models\LoanCompulsory::where('compulsory_status', 'Active')->get();
                            if ($loan_compulsory_s != null) {
                                foreach ($loan_compulsory_s as $saving) {
                                    /*$saving_accrue_int = CompulsorySavingTransaction::where('loan_compulsory_id', $saving->id)
                                        ->where('train_type','accrue-interest')->orderBy('tran_date', 'DESC')->first();*/

                                    /*total_principle = optional($saving_accrue_int)->total_principle ? $saving_accrue_int->total_principle : 0;
                                    $inetrest_rate = $saving->interest_rate / 100 ;
                                    $saving_interest_amt = $total_principle * $inetrest_rate;*/

                                    $accrue_interrest = New \App\Models\CompulsoryAccrueInterests();
                                    $accrue_interrest->compulsory_id = $saving->compulsory_id;
                                    $accrue_interrest->loan_compulsory_id = $saving->id;
                                    $accrue_interrest->loan_id = $saving->loan_id;
                                    $accrue_interrest->client_id = $saving->client_id;
                                    $accrue_interrest->train_type = 'accrue-interest';
                                    $accrue_interrest->tran_id_ref = $saving->loan_id;
                                    $accrue_interrest->tran_date = date('Y-m-d');
                                    $accrue_interrest->reference_no = '';
                                    $accrue_interrest->amount = optional($m)->total_saving_interest_opening_amount;
                                    //$accrue_interrest->seq = '';
                                    $accrue_interrest->save();
                                }
                            }
                        }
                    }
                }
            }
        }
    //});
    if($members->hasMorePages()) {
        return '<head>
      <meta http-equiv=\'refresh\' content=\'5; URL=' . url('api/generate-member?page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return '<h1>OK</h1>';
    }

});

Route::get('api/generate-coa',function (){
    $coa = \App\Models\Coa::all();
    dd($coa);

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
