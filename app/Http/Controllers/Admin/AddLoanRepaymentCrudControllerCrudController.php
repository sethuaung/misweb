<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ACC;
use App\Helpers\MFS;
use App\Models\AccountChart;
use App\Models\ApproveLoanPaymentPending;
use App\Models\BranchU;
use App\Models\Client;
use App\Models\ClientR;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanCompulsory;
use App\Models\LoanOutstanding;
use App\Models\LoanPayment;
use App\Models\LoanPaymentTem;
use App\Models\LoanProduct;
use App\Models\PaymentCharge;
use App\Models\PaymentHistory;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\AddLoanRepaymentCrudControllerRequest as StoreRequest;
use App\Http\Requests\AddLoanRepaymentCrudControllerRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Support\Facades\DB;

/**
 * Class AddLoanRepaymentCrudControllerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AddLoanRepaymentCrudControllerCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanPayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/addloanrepayment');
        $this->crud->setEntityNameStrings('Loan Repayment', 'Loan Repayments');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */


        $payment_pending_id = request()->payment_pending_id??0;

        $type = request()->type??'';
        $payment_tem = LoanPaymentTem::find($payment_pending_id);
        $loan = Loan::find(optional($payment_tem)->disbursement_id);
        $client = Client::find(optional($payment_tem)->client_id);




        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();

            $m = null;
            $branch_id = session('s_branch_id');
            $br = BranchU::find($branch_id);



            $this->crud->addField([
                'name'=>'payment_pending_id',
                'type'=>'hidden',
                'default'=>$payment_pending_id,
            ]);

            if ($type=='payment_tem'){
                $this->crud->addField([
                    'name'=>'loan_number',
                    'label'=>'Loan Number',
                    'default'=>optional($loan)->disbursement_number,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'class' => 'form-control',
                        'readonly' => 'readonly',
                    ],
                    'fake'=>true
                ]);

                $this->crud->addField([
                    'name'=>'disbursement_id',
                    'type'=>'hidden',
                    'default'=>optional($loan)->id,

                ]);
            }
            else{
                $this->crud->addField([
                    'name'=>'disbursement_id',
                    'label'=>'Loan Number',
                    'type'=>'select2_from_ajax',
                    'entity' => 'loan_disbursement', // the method that defines the relationship in your Model
                    'attribute' => 'disbursement_number', // foreign key attribute that is shown to user
                    'model' => Loan::class, // foreign key model
                    'data_source' => url("api/get-loan-number"), // url to controller search function (with /{id} should return model)
                    'placeholder' => _t("Select a loan Disbursement"), // placeholder for the select
                    'minimum_input_length' => 0, // minimum characters to type before querying results
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3 disbursement_id'
                    ]

                ]);
            }



            $this->crud->addField([
                'name'=>'payment_number',
                'label'=>'Payment Number',
                'default' => LoanPayment::getSeqRef('repayment_no'),
                'type'=>'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 payment_number'
                ]
            ]);
            $this->crud->addField([
                'name'=>'client_number',
                'label'=>'Client ID',
                'default'=>isset($client)?$client->client_number:'',
                'type'=>'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'client_name',
                'label'=>'Client Name',
                'default'=>isset($client)?$client->name:'',
                'type'=>'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'client_id',
                'label'=>'Client Name',
                'default'=>isset($client)?$client->client_id:0,
                'type'=>'hidden',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'receipt_no',
                'label'=>'Receipt No',
                'type'=>'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'over_days',
                'label'=>'Over Days',
                'type'=>'text',
                'default'=>isset($payment_tem)?$payment_tem->over_days:0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'penalty_amount',
                'label'=>'Penalty Amount',
                'default'=>isset($payment_tem)?$payment_tem->penalty_amount:0,
                'type'=>'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'principle',
                'label'=>'Principle',
                'default'=>isset($payment_tem)?$payment_tem->principle:0,
                'type'=>'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'interest',
                'label'=>'Interest',
                'type'=>'text_read',
                'default'=>isset($payment_tem)?$payment_tem->interest:0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'compulsory_saving',
                'label'=>'Compulsory Saving',
                'default'=>isset($payment_tem)?$payment_tem->compulsory_saving:0,
                'type'=>'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'other_payment',
                'default'=>isset($payment_tem)?$payment_tem->other_payment:0,
                'label'=>'Other Payment',
                'type'=>'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'total_payment',
                'label'=>'Total Payment',
                'default'=>isset($payment_tem)?$payment_tem->total_payment:0,
                'type'=>'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'payment',
                'label'=>'Payment',
                'default'=>isset($payment_tem)?$payment_tem->payment:0,
                'type'=>'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'payment_date',
                'label'=>'Payment Date',
                'type' => 'date_picker',
                'default' => date('Y-m-d'),
                // optional:
                'date_picker_options' => [
                    'todayBtn' => true,
                    'format' => 'yyyy-mm-dd',
                    'language' => 'en'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'owed_balance',
                'label'=>'Owed Balance',
                'default'=>isset($payment_tem)?$payment_tem->old_owed:0,
                'type'=>'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'principle_balance',
                'label'=>'Principle Balance',
                'default'=>isset($payment_tem)?$payment_tem->principle_balance:0,
                'type'=>'text_read',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]

            ]);
            $this->crud->addField([
                'name'=>'payment_method',
                'label'=>'Payment Method',
                'default'=>isset($payment_tem)?$payment_tem->payment_method:'cash',
                'type' => 'enum',
               'wrapperAttributes' => [
                   'class' => 'form-group col-md-3'
               ]


            ]);
            $this->crud->addField([
              'label' => 'Cash In', // Table column heading
              'type' => "select2_from_ajax_coa",
              'name' => 'cash_acc_id', // the column that contains the ID of that connected entity
              'data_source' => url("api/account-cash"), // url to controller search function (with /{id} should return model)
              'placeholder' => "Select a cash account", // placeholder for the select
              'minimum_input_length' => 0,
              'default' => optional($br)->cash_account_id,
              'wrapperAttributes' => [
                  'class' => 'form-group col-md-3'
              ]

            ]);

            //dd($loan);
            $this->crud->addField([
                'name' => 'loan_payment_ajax',
                'type' => 'view',
                'view' => 'partials/loan_disbursement/loan_payment_ajax',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-9'
                ]
            ]);
//            $this->crud->addField([
//                'name' => 'disbursement_detail_id',
//                'type' => 'hidden',
//            ]);


            $this->crud->addField([
                'name' => 'disbursement_detail_id',
                'type' => 'hidden',
                'default'=>optional($payment_tem)->disbursement_detail_id,
            ]);
        // add asterisk for fields that are required in AddLoanRepaymentCrudControllerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function index(){
        return redirect('admin/addloanrepayment/create');
    }

    public function store(StoreRequest $request)
    {
    //    dd($request->all());
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
//        return $redirect_location;
        $_param = str_replace('"','',$this->crud->entry->disbursement_detail_id);
        $penalty = $this->crud->entry->penalty_amount;

        $arr = explode('x',$_param);
        MFS::updateChargeCompulsorySchedule($this->crud->entry->disbursement_id,$arr,$penalty);

        $payment_pending_id = $this->crud->entry->payment_pending_id;
        $penalty_pending = LoanPaymentTem::find($payment_pending_id);
        if ($penalty_pending != null){
            $penalty_pending->status = 'completed';
            $penalty_pending->save();
        }



        $total_service = 0;
        $payment_id = $this->crud->entry->id;

        $charge_amount = $request->service_charge;
        $charge_id = $request->charge_id;

        if ($charge_id != null){
            foreach ($charge_id as $key => $va){
                $payment_charge = new  PaymentCharge();
                $total_service += isset($charge_amount[$key])?$charge_amount[$key]:0;
                $payment_charge->payment_id = $payment_id;
                $payment_charge->charge_id = isset($charge_id[$key])?$charge_id[$key]:0;
                $payment_charge->charge_amount = isset($charge_amount[$key])?$charge_amount[$key]:0;
                $payment_charge->save();
            }
        }
        //dd($this->crud->entry);

        LoanPayment::savingTransction($this->crud->entry);
        $loan_id = $this->crud->entry->disbursement_id;
        $principle = $this->crud->entry->principle;
        $interest = $this->crud->entry->interest;
        $saving = $this->crud->entry->compulsory_saving;
        $penalty = $this->crud->entry->penalty_amount;
        //$payment = $this->crud->entry->total_payment;
        $row = $this->crud->entry;
        $arr_charge = [];


        $acc = AccountChart::find($this->crud->entry->cash_acc_id);

        $depo = LoanPayment::find($this->crud->entry->id);
        $depo->total_service_charge = $total_service;
        $depo->acc_code = optional($acc)->code;

//        $interest = $this->crud->entry->interest;
//        $_principle = $this->crud->entry->principle;
//        $penalty_amount = $this->crud->entry->penalty_amount;
//        $_payment = $this->crud->entry->payment;
//        $service = $total_service;
//        $saving = $this->crud->entry->compulsory_saving;
        $loan = Loan2::find($this->crud->entry->disbursement_id);

//        $principle_repayment = $loan->principle_repayment;
//        $interest_repayment = $loan->interest_repayment;
//        $principle_receivable = $loan->principle_receivable;
//        $interest_receivable = $loan->interest_receivable;

        $loan_product = LoanProduct::find(optional($loan)->loan_production_id);
        $repayment_order = optional($loan_product)->repayment_order;

        if(companyReportPart() == 'company.moeyan' && optional($loan)->interest_method == 'moeyan-flexible-rate' || $loan_product->name == "Individual loans(Flexible Monthly)" || $loan_product->name == "Individual loans(Flexible Daily)"){
            MFS::addPaymentNoSchedule($this->crud->entry,$total_service);
        }else {

            //==============================================
            //==============================================
            //========================Accounting======================
            $acc = GeneralJournal::where('tran_id', $row->id)->where('tran_type', 'payment')->first();
            if ($acc == null) {
                $acc = new GeneralJournal();
            }

            //$acc->currency_id = $row->currency_id;
            $acc->reference_no = $row->payment_number;
            $acc->tran_reference = $row->payment_number;
            $acc->note = $row->note;
            $acc->date_general = $row->payment_date;
            $acc->tran_id = $row->id;
            $acc->tran_type = 'payment';
            $acc->branch_id = optional($loan)->branch_id;
            $acc->save();

            ///////Cash acc
            $c_acc = new GeneralJournalDetail();
            $c_acc->journal_id = $acc->id;
            $c_acc->currency_id = $currency_id ?? 0;
            $c_acc->exchange_rate = 1;
            $c_acc->acc_chart_id = $row->cash_acc_id;
            $c_acc->dr = $row->payment;
            $c_acc->cr = 0;
            $c_acc->j_detail_date = $row->payment_date;
            $c_acc->description = 'Payment';
            $c_acc->class_id = 0;
            $c_acc->job_id = 0;
            $c_acc->tran_id = $row->id;
            $c_acc->tran_type = 'payment';

            $c_acc->name = $row->client_id;
            $c_acc->branch_id = optional($loan)->branch_id;
            $c_acc->save();

            //==============================================
            //==============================================
            $payment = $request->payment;

            foreach ($arr as $s_id) {
                $l_s = LoanCalculate::find($s_id);
                if ($l_s != null) {

                    $balance_schedule = $l_s->balance_schedule;
                    if ($payment >= $balance_schedule) {
                        $pay_his = new PaymentHistory();

                        $pay_his->payment_date = $this->crud->entry->payment_date;
                        $pay_his->loan_id = $loan_id;
                        $pay_his->schedule_id = $l_s->id;
                        $pay_his->payment_id = $this->crud->entry->id;
                        $pay_his->principal_p = $l_s->principal_s - $l_s->principle_pd;
                        $pay_his->interest_p = $l_s->interest_s - $l_s->interest_pd;
                        $pay_his->penalty_p = $l_s->penalty_schedule - $l_s->penalty_pd;
                        $pay_his->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                        $pay_his->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                        $pay_his->save();

                        ////


                        ////////////////////////////////Principle Accounting//////////////////////////

                        $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                        $c_acc = new GeneralJournalDetail();
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $func_source;
                        $c_acc->dr = 0;
                        $c_acc->cr = $l_s->principal_s - $l_s->principle_pd;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Principle';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        if ($c_acc->cr > 0) {
                            $c_acc->save();
                        }
                        ////////////////////////////////Principle Accounting//////////////////////////

                        ////////////////////////////////Interest Accounting//////////////////////////
                        $c_acc = new GeneralJournalDetail();
                        $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $interest_income;
                        $c_acc->dr = 0;
                        $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Interest Income';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        if ($c_acc->cr > 0) {
                            $c_acc->save();
                        }
                        ////////////////////////////////Interest Accounting//////////////////////////

                        ////////////////////////////////Compulsory Accounting//////////////////////////
                        $c_acc = new GeneralJournalDetail();
                        $compulsory = LoanCompulsory::where('loan_id', $loan_id)->first();
                        if ($compulsory != null) {
                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $currency_id ?? 0;
                            $c_acc->exchange_rate = 1;
                            $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                            $c_acc->dr = 0;
                            $c_acc->cr = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                            $c_acc->j_detail_date = $row->payment_date;
                            $c_acc->description = 'Saving';
                            $c_acc->class_id = 0;
                            $c_acc->job_id = 0;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'payment';

                            $c_acc->name = $row->client_id;
                            $c_acc->branch_id = optional($loan)->branch_id;
                            if ($c_acc->cr > 0) {
                                $c_acc->save();
                            }
                        }
                        ////////////////////////////////Compulsory Accounting//////////////////////////


                        ////////////////////////////////Service Accounting//////////////////////////
                        MFS::serviceChargeAcc($acc->id, $row->payment_date, $loan, $row->id, $row->client_id, $total_service);
                        ////////////////////////////////Service Accounting//////////////////////////


                        ////////////////////////////////Penalty Accounting//////////////////////////

                        $c_acc = new GeneralJournalDetail();
                        $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                        $c_acc->journal_id = $acc->id;
                        $c_acc->currency_id = $currency_id ?? 0;
                        $c_acc->exchange_rate = 1;
                        $c_acc->acc_chart_id = $penalty_income;
                        $c_acc->dr = 0;
                        $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;
                        $c_acc->j_detail_date = $row->payment_date;
                        $c_acc->description = 'Penalty Payable';
                        $c_acc->class_id = 0;
                        $c_acc->job_id = 0;
                        $c_acc->tran_id = $row->id;
                        $c_acc->tran_type = 'payment';
                        $c_acc->name = $row->client_id;
                        $c_acc->branch_id = optional($loan)->branch_id;
                        if ($c_acc->cr > 0) {
                            $c_acc->save();
                        }
                        ////////////////////////////////Penalty Accounting//////////////////////////


                        $l_s->principal_p = $l_s->principal_s;
                        $l_s->interest_p = $l_s->interest_s;
                        $l_s->penalty_p = 0;
                        $l_s->total_p = $l_s->total_s;
                        $l_s->balance_p = 0;
                        $l_s->owed_balance_p = 0;
                        $l_s->service_charge_p = $l_s->charge_schedule;
                        $l_s->compulsory_p = $l_s->compulsory_schedule;
                        $l_s->penalty_p = $l_s->penalty_schedule;
                        $l_s->principle_pd = $l_s->principal_s;
                        $l_s->interest_pd = $l_s->interest_s;
                        $l_s->total_pd = $l_s->total_s;
                        //$l_s->payment_pd = $balance_schedule;
                        $l_s->service_pd = $l_s->charge_schedule;
                        $l_s->compulsory_pd = $l_s->compulsory_schedule;
                        $l_s->payment_status = 'paid';

                        $l_s->save();


                        /////////////////////////////////update loans oustanding /////////////////////

                        $payment = $payment - $balance_schedule;

                        //=================================================


                    } else {

                        ///============================================
                        ///============================================
                        ///============================================
                        ///============================================
                        foreach ($repayment_order as $key => $value) {

                            if ($key == 'Interest') {

                                ////////////////////////////////Interest Accounting//////////////////////////
                                $c_acc = new GeneralJournalDetail();
                                $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                                $c_acc->journal_id = $acc->id;
                                $c_acc->currency_id = $currency_id ?? 0;
                                $c_acc->exchange_rate = 1;
                                $c_acc->acc_chart_id = $interest_income;
                                $c_acc->dr = 0;

                                $c_acc->j_detail_date = $row->payment_date;
                                $c_acc->description = 'Interest Income';
                                $c_acc->class_id = 0;
                                $c_acc->job_id = 0;
                                $c_acc->tran_id = $row->id;
                                $c_acc->tran_type = 'payment';
                                $c_acc->name = $row->client_id;
                                $c_acc->branch_id = optional($loan)->branch_id;

                                ////////////////////////////////Interest Accounting//////////////////////////

                                if ($payment >= $l_s->interest_s - $l_s->interest_pd) {
                                    $l_s->interest_p = ($l_s->interest_s - $l_s->interest_pd);
                                    $payment = $payment - ($l_s->interest_s - $l_s->interest_pd);
                                    $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;


                                } else {
                                    $l_s->interest_p = $payment;
                                    $c_acc->cr = $payment;

                                    $payment = 0;
                                }

                                if ($c_acc->cr > 0) {
                                    $c_acc->save();
                                }
                            }

                            if ($key == "Penalty") {
                                //=======================Acc Penalty============================
                                $c_acc = new GeneralJournalDetail();
                                $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                                $c_acc->journal_id = $acc->id;
                                $c_acc->currency_id = $currency_id ?? 0;
                                $c_acc->exchange_rate = 1;
                                $c_acc->acc_chart_id = $penalty_income;
                                $c_acc->dr = 0;
                                $c_acc->j_detail_date = $row->payment_date;
                                $c_acc->description = 'Penalty Payable';
                                $c_acc->class_id = 0;
                                $c_acc->job_id = 0;
                                $c_acc->tran_id = $row->id;
                                $c_acc->tran_type = 'payment';
                                $c_acc->name = $row->client_id;
                                $c_acc->branch_id = optional($loan)->branch_id;


                                if ($payment >= $l_s->penalty_schedule - $l_s->penalty_pd) {
                                    $l_s->penalty_p = ($l_s->penalty_schedule - $l_s->penalty_pd);
                                    $payment = $payment - ($l_s->penalty_schedule - $l_s->penalty_pd);
                                    $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;

                                } else {
                                    $l_s->penalty_p = $payment;
                                    $c_acc->cr = $payment;
                                    $payment = 0;
                                }
                                if ($c_acc->cr > 0) {
                                    $c_acc->save();
                                }
                            }

                            if ($key == "Service-Fee") {
                                if ($payment >= $l_s->charge_schedule - $l_s->service_pd) {
                                    $l_s->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                                    ////////////////////////////////Service Accounting//////////////////////////
                                    if ($l_s->service_charge_p > 0) {
                                        MFS::serviceChargeAcc($acc->id, $row->payment_date, $loan, $row->id, $row->client_id, $l_s->service_charge_p);
                                    }
                                    ////////////////////////////////Service Accounting//////////////////////////
                                    $payment = $payment - ($l_s->charge_schedule - $l_s->service_pd);
                                } else {
                                    $l_s->service_charge_p = $payment;
                                    ////////////////////////////////Service Accounting//////////////////////////
                                    if ($payment > 0) {
                                        MFS::serviceChargeAcc($acc->id, $row->payment_date, $loan, $row->id, $row->client_id, $payment);
                                    }
                                    ////////////////////////////////Service Accounting//////////////////////////
                                    $payment = 0;
                                }

                            }

                            if ($key == "Saving") {
                                ////////////////////////////////Compulsory Accounting//////////////////////////
                                $c_acc = new GeneralJournalDetail();
                                $compulsory = LoanCompulsory::where('loan_id', $loan_id)->first();
                                if ($compulsory != null) {
                                    $c_acc->journal_id = $acc->id;
                                    $c_acc->currency_id = $currency_id ?? 0;
                                    $c_acc->exchange_rate = 1;
                                    $c_acc->acc_chart_id = ACC::accDefaultSavingDepositCumpulsory($compulsory->compulsory_id);
                                    $c_acc->dr = 0;

                                    $c_acc->j_detail_date = $row->payment_date;
                                    $c_acc->description = 'Saving';
                                    $c_acc->class_id = 0;
                                    $c_acc->job_id = 0;
                                    $c_acc->tran_id = $row->id;
                                    $c_acc->tran_type = 'payment';

                                    $c_acc->name = $row->client_id;
                                    $c_acc->branch_id = optional($loan)->branch_id;
                                }
                                ////////////////////////////////Compulsory Accounting//////////////////////////

                                if ($payment >= $l_s->compulsory_schedule - $l_s->compulsory_pd) {
                                    $l_s->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                    $payment = $payment - ($l_s->compulsory_schedule - $l_s->compulsory_pd);
                                    $c_acc->cr = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                } else {
                                    $l_s->compulsory_p = $payment;
                                    $c_acc->cr = $payment;
                                    $payment = 0;
                                }
                                if ($c_acc->cr > 0) {
                                    $c_acc->save();
                                }
                            }

                            if ($key == "Principle") {
                                ////////////////////////////////Principle Accounting//////////////////////////
                                $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                                $c_acc = new GeneralJournalDetail();
                                $c_acc->journal_id = $acc->id;
                                $c_acc->currency_id = $currency_id ?? 0;
                                $c_acc->exchange_rate = 1;
                                $c_acc->acc_chart_id = $func_source;
                                $c_acc->dr = 0;

                                $c_acc->j_detail_date = $row->payment_date;
                                $c_acc->description = 'Principle';
                                $c_acc->class_id = 0;
                                $c_acc->job_id = 0;
                                $c_acc->tran_id = $row->id;
                                $c_acc->tran_type = 'payment';
                                $c_acc->name = $row->client_id;
                                $c_acc->branch_id = optional($loan)->branch_id;

                                ////////////////////////////////Principle Accounting//////////////////////////
                                if ($payment >= $l_s->principal_s - $l_s->principle_pd) {
                                    $l_s->principal_p = $l_s->principal_s - $l_s->principle_pd;
                                    $payment = $payment - ($l_s->principal_s - $l_s->principle_pd);
                                    $c_acc->cr = $l_s->principal_s - $l_s->principle_pd;
                                    $loan->save();
                                } else {
                                    $l_s->principal_p = $payment;
                                    $c_acc->cr = $payment;
                                    $payment = 0;
                                    $loan->save();
                                }

                                if ($c_acc->cr > 0) {
                                    $c_acc->save();
                                }
                            }
                        }
                        $l_s->save();


                        $l_s->principle_pd += $l_s->principal_p;
                        $l_s->interest_pd += $l_s->interest_p;
                        $l_s->total_pd += $l_s->total_p;
                        //$l_s->payment_pd += $balance_schedule;
                        $l_s->service_pd += $l_s->service_charge_p;
                        $l_s->compulsory_pd += $l_s->compulsory_p;
                        $l_s->penalty_pd += $l_s->penalty_p;
                        $l_s->save();


                        $balance_schedule = $l_s->total_schedule - $l_s->principle_pd - $l_s->interest_pd - $l_s->penalty_pd - $l_s->service_pd - $l_s->compulsory_pd;
                        $l_s->balance_schedule = $balance_schedule;
                        $l_s->count_payment = ($l_s->count_payment ?? 0) + 1;

                        $l_s->save();


                        ///============================================
                        ///============================================
                        ///============================================

                        $pay_his = new PaymentHistory();

                        $pay_his->payment_date = $this->crud->entry->payment_date;
                        $pay_his->loan_id = $loan_id;
                        $pay_his->schedule_id = $l_s->id;
                        $pay_his->payment_id = $this->crud->entry->id;
                        $pay_his->principal_p = $l_s->principal_p;
                        $pay_his->interest_p = $l_s->interest_p;
                        $pay_his->penalty_p = $l_s->penalty_p;
                        $pay_his->service_charge_p = $l_s->service_charge_p;
                        $pay_his->compulsory_p = $l_s->compulsory_p;
                        $pay_his->save();


                        ///============================================
                        ///============================================
                        ///============================================

                    }
                    /////////////////////////////////update loans oustanding /////////////////////


                }
            }


            MFS::updateOutstanding($loan_id);

            $loan_cal = LoanCalculate::where('disbursement_id',$loan_id)->orderBy('date_s','desc')->first();
            if($loan_cal != null){
                if($loan_cal->total_p >= $loan_cal->total_s){
                    /* $lo_u = Loan::find($obj->disbursement_id);
                         //dd($lo_u);
                         $lo_u->disbursement_status = "Closed";

                         $lo_u->save();*/

                    DB::table(getLoanTable())
                        ->where('id', $loan_id)
                        ->update(['disbursement_status' => 'Closed']);
                }
            }
        }
        //==============================================
        //==============================================
        //==============================================
        //==============================================
        //==============================================


/*
        if($request->is_frame >0) {
            return redirect('api/print-loan-payment?id='.$this->crud->entry->id);
        }else{
            return redirect('admin/addloanrepayment');
        }


        return redirect('admin/addloanrepayment/create');

*/

        return   $redirect_location;

    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    public function getloandid(){
        return LoanOutstanding::get();

    }
    public  function loanDisbursment($id)
    {

//        $m = LoanPayment::where('disbursement_id',$id)->get();

        $loan = Loan::find($id);

        $loan_product = \App\Models\LoanProduct::find($loan->loan_production_id);

        $total_disburse = $loan->loan_amount ?? 0;
        $compulsory_amount = 0;
        $compulsory = LoanCompulsory::where('loan_id', $loan->id)->first();

        $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id', $loan->id)->get();
        $priciple_receivable = optional($loan)->principle_receivable;
        $interst_method = optional($loan)->interest_method;
        $interest_per = optional($loan)->interest_rate;
        $interst = ($interest_per*$priciple_receivable)/100;
        $client = ClientR::find($loan->client_id);
        $flexible = 0;

        if($loan_product->name == "Individual loans(Flexible Monthly)" || $loan_product->name == "Individual loans(Flexible Daily)"){
            $flexible = 1;
            $date = \Carbon\Carbon::today();
            $principal = optional($loan)->principle_receivable;
            $total = optional($loan)->principle_receivable;
        }else{
            $loan_calculate = optional(LoanCalculate::where('disbursement_id', $id)->where('payment_status', 'pending')->orderBy('date_s')->first());
            $last_no = LoanCalculate::selectRaw('select no')->where('id', $loan_calculate->id)->max('no');

            $date = $loan_calculate->date_s;
            $principal = $loan_calculate->principal_s;
            $interest = $loan_calculate->interest_s;
            $penalty = $loan_calculate->penalty_s;
            $total = $loan_calculate->total_s;
            $owed_balance_p = $loan_calculate->owed_balance_p;
            $balance = $loan_calculate->balance_s;
            $id = $loan_calculate->id;
        }
            if ($compulsory != null) {

                if ($compulsory->compulsory_product_type_id == 3) {

                    if ($compulsory->charge_option == 1) {
                        $compulsory_amount = $compulsory->saving_amount;
                    } elseif ($compulsory->charge_option == 2) {
                        $compulsory_amount = ($compulsory->saving_amount * $loan->loan_amount) / 100;
                    }
                }
                if (($compulsory->compulsory_product_type_id == 4) && ($last_no % 2 == 0)) {

                    if ($compulsory->charge_option == 1) {
                        $compulsory_amount = $compulsory->saving_amount;
                    } elseif ($compulsory->charge_option == 2) {
                        $compulsory_amount = ($compulsory->saving_amount * $loan->loan_amount) / 100;
                    }
                }
                if ($compulsory->compulsory_product_type_id == 5 && ($last_no % 3 == 0)) {
                    if ($compulsory->charge_option == 1) {
                        $compulsory_amount = $compulsory->saving_amount;
                    } elseif ($compulsory->charge_option == 2) {
                        $compulsory_amount = ($compulsory->saving_amount * $loan->loan_amount) / 100;
                    }
                }
                if ($compulsory->compulsory_product_type_id == 6 && ($last_no % 6 == 0)) {
                    if ($compulsory->charge_option == 1) {
                        $compulsory_amount = $compulsory->saving_amount;
                    } elseif ($compulsory->charge_option == 2) {
                        $compulsory_amount = ($compulsory->saving_amount * $loan->loan_amount) / 100;
                    }
                }

            }
            if($interst_method == 'moeyan-flexible-rate'){
                return [
                    'client_id' => optional($client)->id,
                    'client' => optional($client)->name,
                    'client_number' => optional($client)->client_number,
                    'date' => $date,
                    'principal_s' => $principal,
                    'flexible' => $flexible,
                    'interest_s' => $interest ?? 0,
                    'penalty_s' => $penalty ?? 0,
                    'total_p' => $total ?? 0,
                    'owed_balance' => $owed_balance_p ?? 0,
                    'princilpale_balance' => $balance ?? 0,
                    'charges' => $charges,
                    'disbursement_detail_id' => $id ?? 0,
                    'compulsory' => $compulsory_amount ?? 0,
                    'total_disburse' => $loan->loan_amount,
                    'interst_method'=>1,
                    'principle' =>$priciple_receivable,
                    'interest' =>$interst
                ];
            }

            return [
                'client_id' => optional($client)->id,
                'client' => optional($client)->name,
                'client_number' => optional($client)->client_number,
                'date' => $date,
                'principal_s' => $principal,
                'flexible' => $flexible,
                'interest_s' => $interest ?? 0,
                'penalty_s' => $penalty ?? 0,
                'total_p' => $total ?? 0,
                'owed_balance' => $owed_balance_p ?? 0,
                'princilpale_balance' => $balance ?? 0,
                'charges' => $charges,
                'disbursement_detail_id' => $id ?? 0,
                'compulsory' => $compulsory_amount ?? 0,
                'total_disburse' => $loan->loan_amount,
                'interst_method'=>0
            ];
        }
    //}
}
