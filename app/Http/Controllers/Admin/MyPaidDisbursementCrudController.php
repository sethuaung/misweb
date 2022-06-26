<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\MFS;
use App\Models\AccountChart;
use App\Models\Branch;
use App\Models\BranchU;
use App\Models\Client;
use App\Models\GeneralJournalDetail;
use App\Models\Expense;
use App\Models\DisbursementServiceCharge;
use App\Models\LoanCalculate;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\Loan2;

use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\MyPaidDisbursementRequest as StoreRequest;
use App\Http\Requests\MyPaidDisbursementRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;


/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MyPaidDisbursementCrudController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PaidDisbursement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/my-paid-disbursement');
        $this->crud->setEntityNameStrings('Disbursement', 'Disbursements');

        $this->crud->denyAccess(['update']);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();


//        $loan_id = request()->loan_id;
//        $m = null;
//        $compulsory = null;
//        $tran_type = null;
//
//        if ($loan_id > 0) {
//            $m = optional(Loan::find($loan_id));
//            //$loan_product =
//            //
//            //compulsory_id
//            //saving_amount
//
//            $loan_product = LoanProduct::where('id', $m->loan_production_id)->first();
//
//            $compulsory = CompulsoryProduct::where('id', $loan_product->compulsory_id)->first();
//            $tran_type = TransactionType::where('id', $m->transaction_type_id)->first();
//
//        }
        //dd($m);
        if(companyReportPart() == 'company.mkt'){
            // $this->crud->addClause('join', 'loans', 'paid_disbursements.contract_id', getLoanTable().'.id');
            $this->crud->addClause('where', 'branch_id', session('s_branch_id'));
        }

        $m = null;
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);


        $this->crud->orderBy('id','DESC');

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'reference',
            'label'=> 'Reference No'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'reference', $value);
            }
        );


        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'invoice_no',
            'label'=> 'Invoice No'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'invoice_no', $value);
            }
        );


        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'contract_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Number',
            'placeholder' => 'Pick a Loan Number'
        ],
            url('api/loan-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'contract_id', $value);
            });



        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'client_id',
            'type' => 'select2_ajax',
            'label'=> 'Client',
            'placeholder' => 'Pick a Client'
        ],
            url('api/client-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'client_id', $value);
            });



        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'paid_disbursement_date', '>=', $dates->from);
                $this->crud->addClause('where', 'paid_disbursement_date', '<=', $dates->to . ' 23:59:59');
            });



//        $this->crud->addColumn([
//            'label' => _t('Loan Number'),
//            'type' => 'closure',
//            'function' => function($entry) {
//                $loan = \App\Models\Loan::find($entry->applicant_number_id);
//                return optional($loan)->disbursement_number;
//            }
//        ]);


        $this->crud->addColumn([
            'name' => 'contract_id',
            'label' => _t('Loan Number'),
            'type' => "select",
            'entity' => 'disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\PaidDisbursement", // foreign key model

        ]);

        $this->crud->addColumn([
            'name' => 'reference',
            'label' => _t('reference'),
        ]);

        $this->crud->addColumn([
            'name' => 'paid_disbursement_date',
            'label' => _t('paid_disbursement_date'),
            'type' => 'date'
        ]);


        $this->crud->addColumn([
            // 1-n relationship
            'name' => 'client_number',
            'label' => _t('Client ID'),
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->client_number;
            }
        ]);


        $this->crud->addColumn([
            // 1-n relationship
            'name' => 'client_id',
            'label' => _t('client'),
            'type' => "select",
            'entity' => 'client', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\PaidDisbursement", // foreign key model
        ]);


        $this->crud->addColumn([
            'name' => 'loan_amount',
            'label' => _t('loan_amount'),
            'type' => 'number',
        ]);


        $this->crud->addColumn([
            'name' => 'total_money_disburse',
            'label' => _t('total_money_disburse'),
            'type' => 'number',
        ]);


        $this->crud->addColumn([
            'name' => 'paid_by_tran_id',
            'label' => _t('Paid By'),
            'type' => "select",
            'entity' => 'transaction_type', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\PaidDisbursement", // foreign key model
        ]);
        if(companyReportPart() == "company.mkt"){
            $this->crud->addColumn([
                'label' => "Counter Name", // Table column heading
                'type' => "select",
                'name' => 'user_id', // the column that contains the ID of that connected entity;
                'entity' => 'counter', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => UserU::class, // foreign key model
            ]);
        }

        $this->crud->addField([
            'label' => _t('Loan Number'),
            'type' => "select2_from_ajax",
            'name' => 'contract_id', // the column that contains the ID of that connected entity
            'entity' => 'disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
            'data_source' => url("api/get-loan-disbursement-od"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 '
            ]
        ]);


        $this->crud->addField([
            'label' => _t('Date'),
            'name' => 'paid_disbursement_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);
        $this->crud->addField([
            'label' => _t('First Payment Date'),
            'name' => 'first_payment_date',
            'type' => 'date_picker',
            //'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 first_payment_date'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Loan Number'),
            'type' => "select2_from_ajax",
            'name' => 'contract_id', // the column that contains the ID of that connected entity
            'entity' => 'disbursement', // the method that defines the relationship in your Model
            'attribute' => "disbursement_number", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
            'data_source' => url("api/get-loan-disbursement-od"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'client_id',
            'type' => 'hidden',
        ]);


        $this->crud->addField([
            'label' => _t('reference no'),
            'name' => 'reference',
            'type' => 'text_read',
            'default' => PaidDisbursement::getSeqRef('disbursement_no'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],

        ]);

        /*$this->crud->addField([
            'label' => _t('Contract No'),
            'name' => 'contract_no',
            'default' => PaidDisbursement::getSeqContract('seq_contract'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);*/


        $this->crud->addField([
            'label' => _t('Client Name'),
            'name' => 'client_name',
            'type' => 'text_read',
            //'default' => PaidDisbursement::getSeqRef('paid'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('NRC'),
            'name' => 'client_number',
            'type' => 'text_read',
            //'default' => PaidDisbursement::getSeqRef('paid'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Invoice No'),
            'name' => 'invoice_no',
            //'default' => PaidDisbursement::getSeqRef('paid'),
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);


        /*
        $this->crud->addField([
            'label' => _t('welfare_fund'),
            'name' => 'welfare_fund',
            'type' => 'number2',
            'default' => '0',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);



        $this->crud->addField([
            'label' => _t('loan_process_fee'),
            'name' => 'loan_process_fee',
            'type' => 'number2',
            'default' => '0',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        */


        $this->crud->addField([
            'name' => 'loan-disbursement',
            'type' => 'view',
            'view' => 'partials/loan_disbursement/loan-disbursement-paid'
        ]);
        $this->crud->addField([
            'label' => _t('compulsory_saving'),
            'name' => 'compulsory_saving',
            'type' => 'number2',
            'default' => '0',
            //'default' => $compulsory == null ? '0' : $compulsory->saving_amount,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Loan Request'),
            'name' => 'loan_amount',
            // 'default' => $m == null ? '0' : $m->loan_amount,
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Total Disburse'),
            'name' => 'total_money_disburse',
            'type' => 'number2',
            'default' => '0',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);


        $this->crud->addField([
            'label' => _t('Cash Out'),
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_out_id',
            'data_source' => url("api/account-cash"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select a account", // placeholder for the select
            'default' => optional($br)->cash_account_id,
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);


        $this->crud->addField([
            'label' => _t('Paid By'),
            'name' => 'paid_by_tran_id',
            'type' => 'select_not_null',
            'entity' => 'transaction_type', // the method that defines the relationship in your Model
            'attribute' => 'title', // foreign key attribute that is shown to user
            'model' => "App\\Models\\TransactionType",

            // 'default' => $tran_type == null ? '' : $tran_type->id,

            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        /*
                $this->crud->addField([
                    'label' => _t('loan_id'),
                    'name' => 'loan_id',
                    'type' => 'hidden',
                    //'default' => $m == null ? '0' : $m->id,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-4'
                    ],

                ]);

        */

        $this->crud->addField([
            'label' => _t('Cash Pay'),
            'name' => 'cash_pay',
            'type' => 'number2',
            'default' => '0',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'name' => 'disburse_by',
            'label' => 'Disburse by',
            'type' => 'enum',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);


//        $this->crud->addField([
//            'name' => 'custom-ajax-button',
//            'type' => 'view',
//            'view' => 'partials/loan_disbursement/paid_disbursement'
//        ]);


        // add asterisk for fields that are required in PaidDisbursementRequest
        $field_definition_array = array('disburse_by', 'cash_pay', 'paid_by_tran_id', 'cash_out_id', 'total_money_disburse', 'loan_amount', 'compulsory_saving', 'invoice_no', 'client_number');

        // $this->crud->removeFields($field_definition_array, 'update');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'my-paid-disbursement';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
       if (_can2($this,'update-'.$fname)) {
           $this->crud->allowAccess('update');
       }

        // Allow delete access
        // if (_can2($this,'delete-'.$fname)) {
        //     $this->crud->allowAccess('delete');
        // }


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {

        //dd(($request->all()));
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        $total_service = 0;
        $loan = Loan2::find($this->crud->entry->contract_id);
        $paid_disbursement_id = $this->crud->entry->id;
        $service_charge= $request->service_amount;
        $service_charge_id= $request->service_charge_id;
        $charge_id = $request->charge_id;
        if($service_charge != null){
            foreach ($service_charge as $ke => $va){
                $deposit = new DisbursementServiceCharge();
                $total_service += isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->loan_disbursement_id = $paid_disbursement_id;
                $deposit->service_charge_amount = isset($service_charge[$ke])?$service_charge[$ke]:0;
                $deposit->service_charge_id = isset($service_charge_id[$ke])?$service_charge_id[$ke]:0;

                $deposit->charge_id = isset($charge_id[$ke]) ? $charge_id[$ke] : 0;
                $deposit->save();
            }
        }

        if ($this->crud->entry->contract_id != null) {
            $l = Loan2::find($this->crud->entry->contract_id);

            if($l != null) {
                $l_cal = LoanCalculate::where('disbursement_id',$l->id)->sum('interest_s');
                $l->status_note_date_activated = $this->crud->entry->paid_disbursement_date;
                $l->disbursement_status = "Activated";
                $l->principle_receivable = $l->loan_amount;
                $l->interest_receivable = $l_cal??0;
                $l->save();

            }
        }

        $branch_id = optional($loan)->branch_id;
        PaidDisbursement::savingTransction($this->crud->entry);
        PaidDisbursement::accDisburseTransaction($this->crud->entry,$branch_id);

        $acc = AccountChart::find($this->crud->entry->cash_out_id);

        $deburse = PaidDisbursement::find($this->crud->entry->id);
        $deburse->total_service_charge = $total_service;
        $deburse->acc_code = optional($acc)->code;
        $deburse->save();

        LoanCalculate::where('disbursement_id',$loan->id)->delete();
        $date = $this->crud->entry->paid_disbursement_date;
        $first_date_payment = $this->crud->entry->first_payment_date;
        $loan_product = LoanProduct::find($loan->loan_production_id);
        $interest_method = optional($loan_product)->interest_method;
        $principal_amount = $loan->loan_amount;
        $loan_duration = $loan->loan_term_value;
        $loan_duration_unit = $loan->loan_term;
        $repayment_cycle = $loan->repayment_term;
        $loan_interest = $loan->interest_rate;
        $loan_interest_unit = $loan->interest_rate_period;
        $i = 1;
        $monthly_base = optional($loan_product)->monthly_base??'No';

        $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
            $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
            MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);
        if ($repayment != null) {
            if (is_array($repayment)) {
                foreach ($repayment as $r) {
                    $d_cal = new LoanCalculate();

                    $d_cal->no = $i++;
                    $d_cal->day_num = $r['day_num'];
                    $d_cal->disbursement_id = $loan->id;
                    $d_cal->date_s = $r['date'];
                    $d_cal->principal_s = $r['principal'];
                    $d_cal->interest_s = $r['interest'];
                    $d_cal->penalty_s = 0;
                    $d_cal->service_charge_s = 0;
                    $d_cal->total_s = $r['payment'];
                    $d_cal->balance_s = $r['balance'];
                    $d_cal->branch_id = $loan->branch_id;
                    $d_cal->group_id = $loan->group_loan_id;
                    $d_cal->center_id = $loan->center_leader_id;
                    $d_cal->loan_product_id = $loan->loan_production_id;
                    $d_cal->save();
                }
            }
        }
        return redirect('/admin/print-disbursement?is_pop=1&disbursement_id='.$this->crud->entry->contract_id);

    }




    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $date = $request->paid_disbursement_date;

        $paid_disbursement = PaidDisbursement::find($request->id);
        if ($paid_disbursement != null){
            $paid_disbursement->paid_disbursement_date = $request->paid_disbursement_date;
            $paid_disbursement->save();
        }

        // $loan = Loan::find($request->contract_id);

        // if ($loan != null){
        //     $loan->status_note_date_activated = $request->paid_disbursement_date;
        //     $loan->save();
        // }
        // $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry


        return redirect('/admin/my-paid-disbursement');
    }


    public function loanDisbursementPaid(Request $request)
    {
        $loan_disbursement_id = $request->loan_disbursement_id;
        $disbursement_number = '';
        if ($loan_disbursement_id > 0) {

            $loan_dis = optional(Loan::find($loan_disbursement_id));
            if ($loan_dis != null) {
                $customer = Client::find($loan_dis->client_id);

                $compulsory_amount = 0;
                $loan_amount = 0;

                    $customer_id = optional($customer)->id;

                    $customer_name = optional($customer)->name_other;
                    if ($customer_name == null){
                        $customer_name = optional($customer)->name;
                    }
                    $nrc_number = optional($customer)->nrc_number;

                    $disbursement_number = optional($loan_dis)->disbursement_number;
                    $loan_amount = optional($loan_dis)->loan_amount;
                    $first_installment_date = optional($loan_dis)->first_installment_date;


                $loan_com = LoanCompulsory::where('loan_id', $loan_dis->id)
                    ->where('compulsory_product_type_id', 2)->where('status','Yes')
                    ->first();


                $loan_charge = LoanCharge::where('loan_id', $loan_dis->id)
                    ->where('charge_type', 2)->where('status','Yes')
                    ->get();

                $compulsory_amount = 0;

                if ($loan_com != null) {
                    if($loan_com->charge_option == 2) {
                        $compulsory_amount = $loan_com->saving_amount != null ? ($loan_com->saving_amount * $loan_amount) / 100 : 0;
                    }else{
                        $compulsory_amount = $loan_com->saving_amount;
                    }
                }



                return [
                    'referent_no' => $disbursement_number,
                    'loan_amount' => $loan_amount,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'nrc_number' => $nrc_number,
                    'compulsory_amount' => $compulsory_amount,
                    'loan_charge' => $loan_charge,
                    'first_installment_date' => $first_installment_date,
                ];
            }
        }

        return [
            'referent_no' => 0,
            'customer_id' => 0,
            'customer_name' => '',
            'nrc_number' => '',
            'compulsory_amount' => 0,
            'first_installment_date' => 0,
        ];
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');
        $this->crud->setOperation('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;
        DisbursementServiceCharge::where('loan_disbursement_id', $id)->delete();

        return $this->crud->delete($id);
    }
    public function print_disbursement(Request $request){
        $disbursement_id = $request->disbursement_id;
        //$disbursement = PaidDisbursement::find($disbursement_id);
        $disbursement = PaidDisbursement::where('contract_id',$disbursement_id)->first();

        if(companyReportPart() == 'company.moeyan'){
            return view('partials.loan_disbursement.disbursement-report-moeyan',['row'=>$disbursement]);
        }
        else if(companyReportPart() == 'company.angkor'){
            return view('partials.loan_disbursement.disbursement-report-angkor',['row'=>$disbursement]);
        }else if (companyReportPart() == 'company.bolika'){
            return view('partials.loan_disbursement.disbursement-report-bolika',['row'=>$disbursement]);
        }else{
            return view('partials.loan_disbursement.disbursement-report',['row'=>$disbursement]);
        }
    }

    public function print_contract(Request $request){
        $disbursement_id = $request->disbursement_id;
        $disbursement = PaidDisbursement::find($disbursement_id);

        return view('partials.loan_disbursement.loan-agreement-angkor',['row'=>$disbursement]);
    }

    public function update_client_confirm($id){
        $paid_disbursement = PaidDisbursement::find($id);
        if ($paid_disbursement != null){
            $paid_disbursement->disburse_by = 'client';
            $paid_disbursement->save();
        }

        return redirect()->back();
    }
}
