<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ACC;
use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Models\{AccountChart,
    BranchU,
    CompulsorySavingTransaction,
    DeletePaymentHistory,
    GeneralJournal,
    GeneralJournalDetail,
    GeneralJournalDetailTem,
    Loan2,
    LoanCharge,
    LoanCompulsory,
    Loan,
    LoanCalculate,
    LoanDeposit,
    LoanPayment,
    LoanProduct,
    PaymentCharge,
    PaymentHistory,
    ScheduleBackup};
use Backpack\CRUD\app\Http\Controllers\CrudController;
// use App\Src\Backpack\CrudController;
// use App\Src\Backpack\CrudPanel;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanPaymentRequest as StoreRequest;
use App\Http\Requests\LoanPaymentRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
* Class LoanPaymentCrudController
* @package App\Http\Controllers\Admin
* @property-read CrudPanel $crud
*/
class LoanPaymentCrudController extends CrudController
{
    public function setup()
    {
        $disbursement_id = request()->id;
        $param = request()->param;
        //$arr = [];
        $loan_d = null;
        $disburse = null;
        $priciple_balance = 0;
        $payment = 0;
        $over_days = 0;
        $old_owed = 0;
        $other_payment = 0;
        $_param = '';
        if($param != null){
            $_param = str_replace('"','',$param);

            $arr = explode('x',$_param);
            $arr_l = count($arr);
            $loan_d = optional(LoanCalculate::whereIn('id',$arr)
            ->selectRaw('SUM(IFNULL(principal_s,0)) as principal_s, SUM(IFNULL(interest_s,0)) as interest_s, SUM(IFNULL(penalty_s,0)) as penalty_s,
            SUM(IFNULL(total_s,0)) as total_s, SUM(IFNULL(day_num,0)) as day_num,SUM(IFNULL(principle_pd,0)) as principle_pd,
            SUM(IFNULL(interest_pd,0)) as interest_pd,SUM(IFNULL(penalty_pd,0)) as penalty_pd,SUM(IFNULL(service_pd,0)) as service_pd,
            SUM(IFNULL(compulsory_pd,0)) as compulsory_pd,SUM(IFNULL(payment_pd,0)) as payment_pd')
            ->first());
            $last_no = LoanCalculate::selectRaw('select no')->whereIn('id',$arr)->max('no');

            $principle_paid = LoanCalculate::where('disbursement_id',$disbursement_id)
            ->sum('principal_p');
            $disburse = optional(Loan::find($disbursement_id));
            $total_disburse = $disburse->loan_amount??0;
            $compulsory_amount = 0;
            $compulsory = LoanCompulsory::where('loan_id',$disbursement_id)->first();
            $total_line_charge = 0;
            $charges = \App\Models\LoanCharge::where('charge_type', 3)->where('loan_id',$disbursement_id)->get();

            if($charges != null){
                foreach ($charges as $c){
                    $amt_charge = optional($c)->amount;
                    $total_line_charge += ($c->charge_option == 1?$amt_charge:(($total_disburse*$amt_charge)/100));
                }
            }




            $other_payment = $total_line_charge*$arr_l + $compulsory_amount*$arr_l;
            $priciple_balance = $total_disburse - ($principle_paid + $loan_d->principal_s);
            $payment = ($loan_d->principal_s - $loan_d->principle_pd) + ($loan_d->interest_s -$loan_d->interestpd) +
                ($loan_d->penalty_s-$loan_d->penalty_pd);

            $nex_payment = optional(LoanCalculate::where('disbursement_id',$disbursement_id)
            ->where('total_p',0)->orderBy('date_s','ASC')->first());

            $old_owed = optional(LoanCalculate::where('disbursement_id',$disbursement_id)
            ->where('total_p','>',0)->orderBy('date_s','DESC')->first())->owed_balance_p??0;

            if($nex_payment != null){
                $date_s = $nex_payment->date_s;
                $over_days = IDate::dateDiff($date_s,date('Y-m-d'));
            }
        }
        $_penalty = 0;
        $_principle = 0;
        $_interest = 0;
        $_compulsory = 0;
        $_other_payment = 0;

        if($loan_d != null) {
            $_penalty = $loan_d->penalty_s - ($loan_d->penalty_pd ?? 0);
            $_principle = $loan_d->principal_s - ($loan_d->principle_pd ?? 0);
            $_interest = $loan_d->interest_s - ($loan_d->interest_pd ?? 0);

            if(companyReportPart() == "company.quicken"){
                $_penalty = 0;
                foreach($arr as $s_id){
                    $loan_c = LoanCalculate::find($s_id);

                    if($loan_c){
                        if($loan_c->principal_p < $loan_c->principal_s && $loan_c->principal_p > 0){
                            $history = PaymentHistory::where('schedule_id', $loan_c->id)->first();
                            $last_paydate = Carbon::parse(optional($history)->payment_date);
                            $payment = ($loan_c->total_s) - ($loan_c->principal_p + $loan_c->interest_p);
                        }
                        else if($loan_c->principal_p == 0){
                            $last_paydate = Carbon::parse($loan_c->date_s);
                        }

                        $over_days = $last_paydate < Carbon::now() ? $last_paydate->diffInDays(Carbon::now()) : 0;
                    }

                    // if($over_days < 4){
                    //     $_penalty += 0;
                    // }
                    // else if($over_days >= 4 && $over_days <= 7){
                    //     $_penalty += $payment * 3;
                    // }
                    // else if($over_days >= 8 && $over_days <= 15){
                    //     $_penalty += $payment * 5;
                    // }
                    // else if($over_days >= 16 && $over_days <= 20){
                    //     $_penalty += $payment * 10;
                    // }
                    // else if($over_days >= 21 && $over_days <= 30){
                    //     $_penalty += $payment * 15;
                    // }
                    // else if($over_days > 30){
                    //     $_penalty += $payment * 20;
                    // }

                    // $_penalty = round($_penalty / 100);
                    // $_last = substr($_penalty, -2);
                    // if($_last <= 50 && $over_days >= 4){
                    //     $_penalty = (50 - $_last) + $_penalty;
                    // }
                    // else if($_last > 50 && $over_days >= 4){
                    //     $_penalty =  (100 - $_last) + $_penalty;
                    // }
                }
            }

            // 8888888888888888888888888
            // 8888888888888888888888888
            // 8888888888888888888888888
            // 8888888888888888888888888
            $compulsory_amount_total = 0;
            foreach ($arr as $s_id) {
                $l_s = LoanCalculate::find($s_id);
                if($l_s != null) {

                    $last_no = $l_s->no;
                    $compulsory_amount = 0;

                    if($l_s->count_payment>0){
                        $compulsory_amount = $l_s->compulsory_schedule - ($l_s->compulsory_pd??0);

                    }else {
                        $compulsory = LoanCompulsory::where('loan_id', $disbursement_id)->first();
                        if ($compulsory != null) {

                            if ($compulsory->compulsory_product_type_id == 3) {

                                if ($compulsory->charge_option == 1) {
                                    $compulsory_amount = $compulsory->saving_amount;
                                } elseif ($compulsory->charge_option == 2) {
                                    $compulsory_amount = ($compulsory->saving_amount * $total_disburse) / 100;
                                }
                            }
                            if (($compulsory->compulsory_product_type_id == 4) && ($last_no % 2 == 0)) {

                                if ($compulsory->charge_option == 1) {
                                    $compulsory_amount = $compulsory->saving_amount;
                                } elseif ($compulsory->charge_option == 2) {
                                    $compulsory_amount = ($compulsory->saving_amount * $total_disburse) / 100;
                                }
                            }
                            if ($compulsory->compulsory_product_type_id == 5 && ($last_no % 3 == 0)) {
                                if ($compulsory->charge_option == 1) {
                                    $compulsory_amount = $compulsory->saving_amount;
                                } elseif ($compulsory->charge_option == 2) {
                                    $compulsory_amount = ($compulsory->saving_amount * $total_disburse) / 100;
                                }
                            }
                            if ($compulsory->compulsory_product_type_id == 6 && ($last_no % 6 == 0)) {
                                if ($compulsory->charge_option == 1) {
                                    $compulsory_amount = $compulsory->saving_amount;
                                } elseif ($compulsory->charge_option == 2) {
                                    $compulsory_amount = ($compulsory->saving_amount * $total_disburse) / 100;
                                }
                            }

                        }
                    }

                    if($compulsory_amount >0){
                        $compulsory_amount_total += $compulsory_amount;
                    }


                }
            }
            // 8888888888888888888888888
            // 8888888888888888888888888
            // 8888888888888888888888888
            // 8888888888888888888888888
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\LoanPayment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loanpayment');
        $this->crud->setEntityNameStrings('Loan Repayment', 'Loan Repayment');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $m = null;
        //$m = optional(Loan::all());
        $branch_id = session('s_branch_id');
        $br = BranchU::find($branch_id);
        //dd($m);



        $this->crud->addColumns([
            [
                'name' => 'payment_number',
                'label' => _t('payment_number'),
            ],
            [
                'label' => _t('Client name'),
                'type' => "select2",
                'name' => 'client_id', // the column that contains the ID of that connected entity
                'entity' => 'client_name', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\\User", // foreign key model
            ],
            [
                'name' => 'receipt_no',
                'label' => _t('Receipt No')
            ],
            [
                'name' => 'over_days',
                'label' => _t('Over Days')
            ],
            [
                'name' => 'principle',
                'label' => _t('Principle'),

            ],
            [
                'name' => 'interest',
                'label' => _t('Interest'),
                'type' => 'number2',
            ],
            [
                'name' => 'old_owed',
                'label' => _t('Old Owed'),
                'type' => 'number2'
            ],
            [
                'name' => 'other_payment',
                'label' => _t('Other Payment'),
                'type' => 'number2'
            ],
            [
                'name' => 'total_payment',
                'label' => _t('Total Payment'),
                'type' => 'number2'
            ],
            [
                'name' => 'payment',
                'label' => _t('Payment'),
                'type' => 'number2'
            ]
        ]);

        if(companyReportPart() == "company.mkt" || companyReportPart() == "company.quicken" || companyReportPart() == "company.moeyan"){
            $this->crud->addFields([
                [
                    'name' => 'disbursement_id',
                    'default' => $disbursement_id,
                    'value' => $disbursement_id,
                    'type' => 'hidden',
                ],[
                    'name' => 'is_frame',
                    'default' => request()->is_frame,
                    'value' => request()->is_frame,
                    'type' => 'hidden',
                ],[
                    'name' => 'disbursement_detail_id',
                    'default' => $param,
                    'value' => $param,
                    'type' => 'hidden',
                ]
                ,[
                    'name' => 'branch_id',
                    'default' => session('s_branch_id'),
                    'value' => session('s_branch_id'),
                    'type' => 'hidden',
                ],
                [
                    'name' => 'payment_number',
                    'label' => _t('payment_number'),
                    'default' => LoanPayment::getSeqRef('repayment_no'),
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ]
                ],

                [
                    'name' => 'param',
                    'default' => isset($_GET['param'])?count(explode('x',$_GET['param'])):0,
                    'type' => 'hidden'
                ],
                [
                    'name' => 'document',
                    'label' => _t('document'),
                    'type' => 'upload',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ]
                ],
                [
                    'label' => _t('Client ID'),
                    'type' => "text_read",
                    'default' => optional(optional($disburse)->client_name)->client_number,
                    'name' => 'client_number ',

                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                ],
                [
                    'label' => _t('Client name'),
                    'type' => "text_read",
                    'default' => optional(optional($disburse)->client_name)->name,
                    'name' => 'client_idcc',

                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                ],
                [
                    'type' => "hidden",
                    'default' => optional($disburse)->client_id,
                    'name' => 'client_id',
                ],
                [
                    'name' => 'receipt_no',
                    'label' => _t('Receipt No'),
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ]
                ],
                [
                    'name' => 'over_days',
                    'label' => _t('Over Days'),
                    'default' => $over_days,
                    'type' => 'number2',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'class' => 'form-control some-class',
                        'readonly'=>'readonly',
                    ],
                ],
                [
                    'name' => 'penalty_amount',
                    'label' => _t('Penalty Amount'),
                    // 'default' => companyReportPart() == "company.quicken" ?  $_penalty : 0,
                    // 'value' => companyReportPart() == "company.quicken" ?  $_penalty : 0,
                    'default' => 0,
                    'value' => 0,
                    'type' =>  'number' ,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                ],
                [
                    'name' => 'principle',
                    'label' => _t('Principle'),
                    'default' => round($_principle,4),
                    'value' => round($_principle,4),
                    'type' => 'number2',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'id'=> 'principle',
                        'class' => 'form-control some-class',
                    ],
                ],
                [   // select_from_array
                    'name' => 'pre_repayment',
                    'label' => "Pre Repayment",
                    'type' => 'select_from_array',
                    'options' => ['1' => 'Yes', '0' => 'No'],
                    'allows_null' => false,
                    'default' => '0',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'id'=>'pre_repayment',
                    ],
                    // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
                ],
                [   // select_from_array
                    'name' => 'late_repayment',
                    'label' => "Late Repayment",
                    'type' => 'select_from_array',
                    'options' => ['1' => 'Yes', '0' => 'No'],
                    'allows_null' => false,
                    'default' => '0',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'id'=>'late_repayment',
                    ],
                    // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
                ],
                [
                    'name' => 'interest',
                    'label' => _t('Interest'),
                    'default' => round($_interest,4),
                    'value' => round($_interest,4),
                    'type' => 'number2',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'id'=>'interest',
                        'class' => 'form-control some-class',
                    ],
                ],
                [
                    'name' => 'old_owed',
                    'label' => _t('Old Owed'),
                    'default' =>0,// round($old_owed??0,2),
                    'value' => 0,// round($old_owed??0,2),
                    'type' => 'hidden',
                   /* 'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'class' => 'form-control some-class',
                        'readonly'=>'readonly',
                    ],*/
                ]
            ]);
        }
        else{
            $this->crud->addFields([
                [
                    'name' => 'disbursement_id',
                    'default' => $disbursement_id,
                    'value' => $disbursement_id,
                    'type' => 'hidden',
                ],[
                    'name' => 'is_frame',
                    'default' => request()->is_frame,
                    'value' => request()->is_frame,
                    'type' => 'hidden',
                ],[
                    'name' => 'disbursement_detail_id',
                    'default' => $param,
                    'value' => $param,
                    'type' => 'hidden',
                ],
                [
                    'name' => 'payment_number',
                    'label' => _t('payment_number'),
                    'default' => LoanPayment::getSeqRef('repayment_no'),
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ]
                ],

                [
                    'name' => 'param',
                    'default' => isset($_GET['param'])?count(explode('x',$_GET['param'])):0,
                    'type' => 'hidden'
                ],
                [
                    'name' => 'document',
                    'label' => _t('document'),
                    'type' => 'upload',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ]
                ],
                [
                    'label' => _t('Client ID'),
                    'type' => "text_read",
                    'default' => optional(optional($disburse)->client_name)->client_number,
                    'name' => 'client_number ',

                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                ],
                [
                    'label' => _t('Client name'),
                    'type' => "text_read",
                    'default' => optional(optional($disburse)->client_name)->name,
                    'name' => 'client_idcc',

                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                ],
                [
                    'type' => "hidden",
                    'default' => optional($disburse)->client_id,
                    'name' => 'client_id',
                ],
                [
                    'name' => 'receipt_no',
                    'label' => _t('Receipt No'),
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ]
                ],
                [
                    'name' => 'over_days',
                    'label' => _t('Over Days'),
                    'default' => $over_days,
                    'type' => 'number2',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'class' => 'form-control some-class',
                        'readonly'=>'readonly',
                    ],
                ],
                [
                    'name' => 'penalty_amount',
                    'label' => _t('Penalty Amount'),
                    // 'default' => companyReportPart() == "company.quicken" ?  $_penalty : 0,
                    // 'value' => companyReportPart() == "company.quicken" ?  $_penalty : 0,
                    'default' => 0,
                    'value' => 0,
                    'type' =>  'number' ,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                ],
                [
                    'name' => 'principle',
                    'label' => _t('Principle'),
                    'default' => round($_principle,4),
                    'value' => round($_principle,4),
                    'type' => 'number2',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'id'=> 'principle',
                        'class' => 'form-control some-class',
                        'readonly'=>'readonly',

                    ],
                ],
                [   // select_from_array
                    'name' => 'pre_repayment',
                    'type' => 'hidden',
                    'allows_null' => false,
                    'default' => '0',
                    // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
                ],
                [
                    'name' => 'interest',
                    'label' => _t('Interest'),
                    'default' => round($_interest,4),
                    'value' => round($_interest,4),
                    'type' => 'number2',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'id'=>'interest',
                        'class' => 'form-control some-class',
                    ],
                ],
                [
                    'name' => 'old_owed',
                    'label' => _t('Old Owed'),
                    'default' =>0,// round($old_owed??0,2),
                    'value' => 0,// round($old_owed??0,2),
                    'type' => 'hidden',
                   /* 'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'attributes' => [
                        'class' => 'form-control some-class',
                        'readonly'=>'readonly',
                    ],*/
                ]
            ]);
        }
        if(isset($compulsory)) {
            if (companyReportPart() == 'company.mkt'){
                $this->crud->addField([
                    'name' => 'compulsory_saving',
                    'label' => 'Compulsory Saving',
                    'default' => isset($compulsory_amount_total) ? $compulsory_amount_total??0 : 0,
                    'value' => isset($compulsory_amount_total) ? $compulsory_amount_total??0 : 0,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3',
                    ],
                    'attributes' => [
                        'class' => 'form-control some-class',
                    ],
                ]);
            }else{
                $this->crud->addField([
                    'name' => 'compulsory_saving',
                    'label' => 'Compulsory Saving',
                    'default' => isset($compulsory_amount_total) ? $compulsory_amount_total??0 : 0,
                    'value' => isset($compulsory_amount_total) ? $compulsory_amount_total??0 : 0,
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3',
                    ],
                    'attributes' => [
                        'class' => 'form-control some-class',
                        'readonly' => 'readonly',
                    ],
                ]);
            }



        }
        if(isset($charges)) {
            if (optional($charges) != null) {


                $fax = 0;
                foreach ($arr as $s_id) {
                    $l_s = LoanCalculate::find($s_id);
                    if ($l_s != null) {
                        if($l_s->count_payment>0){
                            $fax = 1;
                            $this->crud->addField([
                                'name' => 'service_charge[0]',
                                'label' => 'Old Charge',
                                'default' => $l_s->charge_schedule-$l_s->service_pd,
                                'value' => $l_s->charge_schedule-$l_s->service_pd,
                                'wrapperAttributes' => [
                                    'class' => 'form-group col-md-3',
                                ],
                                'attributes' => [
                                    'class' => 'form-control some-class service-charge',
                                    'readonly' => 'readonly',
                                ],
                                'fake' => true
                            ]);
                        }else {

                        }
                    }
                }

                $n_1 = $arr_l - $fax;

                if($fax >0 && count($arr) == 1){}else {
                    foreach ($charges as $c) {
                        $this->crud->addField([
                            'name' => 'service_charge[' . $c->id . ']',
                            'label' => optional($c)->name,
                            'default' => ($c->charge_option == 1 ? optional($c)->amount * $n_1 : (($total_disburse * optional($c)->amount * $n_1) / 100)),
                            'value' => ($c->charge_option == 1 ? optional($c)->amount * $n_1 : (($total_disburse * optional($c)->amount * $n_1) / 100)),
                            'wrapperAttributes' => [
                                'class' => 'form-group col-md-3',
                            ],
                            'attributes' => [
                                'class' => 'form-control some-class service-charge',
                                'readonly' => 'readonly',
                            ],
                            'fake' => true
                        ]);
                        $this->crud->addField([
                            'name' => 'charge_id[' . $c->id . ']',
                            'type' => 'hidden',
                            'default' => $c->charge_id,
                            'value' => $c->charge_id,
                        ]);
                    }
                }
            }
        }

        $this->crud->addFields([
            [
                'name' => 'other_payment',
                'label' => _t('Other Payment'),
                'default' =>  0,
                'type' => 'number2',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]
            ],

            [
                'name' => 'total_payment',
                'label' => _t('Total Payment'),
                'default' => round($payment??0,2),
                'type' => 'number2',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'attributes' => [
                    'id' => 'total_payment',
                    'class' => 'form-control some-class',
                    'readonly'=>'readonly',
                ],
            ],
            [
                'name' => 'payment',
                'label' => _t('Payment'),
                'default' => round($payment??0,2),
                'type' => 'number2',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'attributes' => [
                    'id' => 'payment',
                    'class' => 'form-control some-class',
                ],
            ],
            [
                'name' => 'payment_date',
                'label' => _t('Payment Date'),
                'type' => 'date_picker',
                'default' => date('Y-m-d'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]
            ],
            [
                'name' => 'owed_balance',
                'label' => _t('Owed Balance'),
                'default' => 0,
                'type' => 'number2',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'attributes' => [
                    'class' => 'form-control some-class',
                    'readonly'=>'readonly',
                ],
            ],
            [
                'name' => 'principle_balance',
                'label' => _t('Principle Balance'),
                'default' => round($priciple_balance??0,2),
                'type' => 'number2',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'attributes' => [
                    'id'=>'principle_balance',
                    'class' => 'form-control some-class',
                    'readonly'=>'readonly',
                ],
            ],
            [
                'name' => 'payment_method',
                'label' => _t('Payment Method'),
                'type' => 'enum',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]
            ],
            [
                'label' => 'Cash In', // Table column heading
                'type' => "select2_from_ajax_coa",
                'name' => 'cash_acc_id', // the column that contains the ID of that connected entity
                'data_source' => url("api/account-cash"), // url to controller search function (with /{id} should return model)
                'placeholder' => "Select a cash account", // placeholder for the select
                'default' => optional($br)->cash_account_id,
                'minimum_input_length' => 0,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ]
            ],
            [
                'name' => 'custom-ajax',
                'type' => 'view',
                'view' => 'partials/loan-payment/loan-payment-script',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
            ]

        ]);
        $this->crud->denyAccess('update');
        // add asterisk for fields that are required in LoanPaymentRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-payment';
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
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }

        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }

    }

   /* public function index()
    {
        $this->crud->hasAccessOrFail('list');
        $this->crud->setOperation('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return redirect('admin/loanpayment');
    }*/

    public function store(StoreRequest $request)
    {
        //dd($request);
        $redirect_location = parent::storeCrud($request);
        $_param_check = str_replace('"','',$this->crud->entry->disbursement_detail_id);
        $arr_check = explode('x',$_param_check);
        $l_s_check = LoanCalculate::find($arr_check['0']);
        $pre_rp = isset($_REQUEST['pre_repayment'])?$_REQUEST['pre_repayment']:0;
        $late_rp = isset($_REQUEST['late_repayment'])?$_REQUEST['late_repayment']:0;
        if ( $l_s_check->payment_status != 'paid'){

        $_param = str_replace('"','',$this->crud->entry->disbursement_detail_id);
        $penalty = $this->crud->entry->penalty_amount;

        $arr = explode('x',$_param);
        MFS::updateChargeCompulsorySchedule($this->crud->entry->disbursement_id,$arr,$penalty);

        $total_service = 0;
        $payment_id = $this->crud->entry->id;

        $charge_amount = $request->service_charge;
        $charge_id = $request->charge_id;
        //dd($this->crud->entry);
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
        //dd("nyi nyi");
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

        $interest = $this->crud->entry->interest;
        $_principle = $this->crud->entry->principle;
        $penalty_amount = $this->crud->entry->penalty_amount;
        $_payment = $this->crud->entry->payment;
        $service = $total_service;
        $saving = $this->crud->entry->compulsory_saving;
        $loan = Loan2::find($this->crud->entry->disbursement_id);

        $principle_repayment = $loan->principle_repayment;
        $interest_repayment = $loan->interest_repayment;
        $principle_receivable = $loan->principle_receivable;
        $interest_receivable = $loan->interest_receivable;

        $loan_product = LoanProduct::find(optional($loan)->loan_production_id);
        $repayment_order = optional($loan_product)->repayment_order;

        //==============================================
        //==============================================
        //========================Accounting======================
        $acc = GeneralJournal::where('tran_id',$row->id)->where('tran_type','payment')->first();
        if($acc == null){
            $acc = new GeneralJournal();
        }

        if(companyReportPart() == "company.moeyan"){
            $branch_id = session('s_branch_id');
        }else{
            $branch_id = optional($loan)->branch_id;
        }

        //$acc->currency_id = $row->currency_id;
        $acc->reference_no = $row->payment_number;
        $acc->tran_reference = $row->payment_number;
        $acc->note = $row->note;
        $acc->date_general = $row->payment_date;
        $acc->tran_id = $row->id;
        $acc->tran_type = 'payment';
        $acc->branch_id = $branch_id;
        $acc->save();

        ///////Cash acc
        if (companyReportPart() == 'company.mkt'){
            $c_acc = new GeneralJournalDetailTem();
        }else{
            $c_acc = new GeneralJournalDetail();
        }

        $c_acc->journal_id = $acc->id;
        $c_acc->currency_id = $currency_id??0;
        $c_acc->exchange_rate = 1;
        $c_acc->acc_chart_id = $row->cash_acc_id;
        $c_acc->dr = $row->payment;
        $c_acc->cr = 0;
        $c_acc->j_detail_date = $row->payment_date;
        $c_acc->description = 'Payment';
        $c_acc->class_id  =  0;
        $c_acc->job_id  =  0;
        $c_acc->tran_id = $row->id;
        $c_acc->tran_type = 'payment';

        $c_acc->name = $row->client_id;
        $c_acc->branch_id = $branch_id;
        $c_acc->save();

        //==============================================
        //==============================================
        $payment = $request->payment;
        $schedule_id = 0;

        foreach ($arr as $s_id){

            $l_s = LoanCalculate::find($s_id);
            
            if($l_s != null) {
                //dd($l_s);
                $schedule_id = $l_s->id;
                //================================================
                //================================================
                //================================================
                if($late_rp == 1){
                    $schedule_back = new ScheduleBackup();
                    $schedule_back->loan_id = $loan_id;
                    $schedule_back->schedule_id = $l_s->id;
                    $schedule_back->payment_id = $this->crud->entry->id;
                    $schedule_back->principal_p = $l_s->principal_p;
                    $schedule_back->interest_p = $l_s->interest_p;
                    $schedule_back->penalty_p = $l_s->penalty_p;
                    $schedule_back->service_charge_p = $l_s->service_charge_p;
                    $schedule_back->balance_p = $l_s->balance_p;
                    $schedule_back->compulsory_p = $l_s->compulsory_p;
                    $schedule_back->charge_schedule = $l_s->charge_schedule;
                    $schedule_back->compulsory_schedule = $l_s->compulsory_schedule;
                    $schedule_back->total_schedule = $l_s->total_schedule;
                    $schedule_back->balance_schedule = $l_s->balance_schedule;
                    $schedule_back->penalty_schedule = $l_s->penalty_schedule;
                    $schedule_back->principle_pd = $l_s->principle_pd;
                    $schedule_back->interest_pd = $l_s->interest_pd;
                    $schedule_back->total_pd = $l_s->total_pd;
                    // $schedule_back->penalty_pd = $l_s->penalty_pd;
                    $schedule_back->service_pd = $l_s->service_pd;
                    $schedule_back->compulsory_pd = $l_s->compulsory_pd;
                    $schedule_back->count_payment = $l_s->count_payment;
                    $schedule_back->save();
                }
                else{
                    $schedule_back = new ScheduleBackup();
                    $schedule_back->loan_id = $loan_id;
                    $schedule_back->schedule_id = $l_s->id;
                    $schedule_back->payment_id = $this->crud->entry->id;
                    $schedule_back->principal_p = $l_s->principal_p;
                    $schedule_back->interest_p = $l_s->interest_p;
                    $schedule_back->penalty_p = $l_s->penalty_p;
                    $schedule_back->service_charge_p = $l_s->service_charge_p;
                    $schedule_back->balance_p = $l_s->balance_p;
                    $schedule_back->compulsory_p = $l_s->compulsory_p;
                    $schedule_back->charge_schedule = $l_s->charge_schedule;
                    $schedule_back->compulsory_schedule = $l_s->compulsory_schedule;
                    $schedule_back->total_schedule = $l_s->total_schedule;
                    $schedule_back->balance_schedule = $l_s->balance_schedule;
                    $schedule_back->penalty_schedule = $l_s->penalty_schedule;
                    $schedule_back->principle_pd = $l_s->principle_pd;
                    $schedule_back->interest_pd = $l_s->interest_pd;
                    $schedule_back->total_pd = $l_s->total_pd;
                    // $schedule_back->penalty_pd = $l_s->penalty_pd;
                    $schedule_back->service_pd = $l_s->service_pd;
                    $schedule_back->compulsory_pd = $l_s->compulsory_pd;
                    $schedule_back->count_payment = $l_s->count_payment;
                    $schedule_back->save();
                }

                if($l_s != Null && $pre_rp == 1){
                    $interest_d = ($this->crud->entry->interest / count($arr));
                    $l_s->old_interest = $l_s->interest_s;
                    $l_s->interest_s = $interest_d;
                    $l_s->total_s = $l_s->principal_s + $interest_d;
                    $l_s->save();
                }
                // elseif($l_s != Null && $pre_rp != 1 && $late_rp != 1){
                //     $interest_d = ($this->crud->entry->interest / count($arr));
                //     $principal_d = ($this->crud->entry->principle / count($arr));
                //     $l_s->old_interest = $l_s->interest_s;
                //     $l_s->interest_s = $interest_d;
                //     $l_s->principal_s = $principal_d;
                //     $l_s->total_s = $principal_d + $interest_d;
                //     $l_s->save();
                // }
                if($l_s != Null && $late_rp == 1){
                    
                    $interest_d = $this->crud->entry->interest;
                    $principal_d = $this->crud->entry->principle;
                    $l_s->old_interest = $l_s->interest_s;
                    $l_s->interest_pd = $interest_d + $l_s->interest_pd;
                    $l_s->principle_pd = $principal_d + $l_s->principle_pd;
                    $l_s->principal_p = $principal_d + $l_s->principle_pd;
                    $l_s->interest_p = $interest_d + $l_s->interest_pd;
                    $l_s->total_p = $principal_d + $interest_d;
                    $l_s->save();
                    //dd($l_s);
                }
                //================================================
                //================================================
                //================================================


                $balance_schedule = $l_s->balance_schedule;
                if($payment >= $balance_schedule || $pre_rp == 1 || $late_rp == 1){
                    if($late_rp == 1){
                        //dd($this->crud->entry);
                        $pay_his =  new PaymentHistory();
                        $pay_his->payment_date = $this->crud->entry->payment_date;
                        $pay_his->loan_id = $loan_id;
                        $pay_his->schedule_id = $l_s->id;
                        $pay_his->payment_id = $this->crud->entry->id;
                        $pay_his->principal_p = $this->crud->entry->principle;
                        $pay_his->interest_p = $this->crud->entry->interest;
                        $pay_his->penalty_p = $penalty_amount;
                        $pay_his->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                        $pay_his->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                        $pay_his->owed_balance = $balance_schedule - $this->crud->entry->principle - $this->crud->entry->interest;
                        $pay_his->save();
                    }
                    else{
                        $pay_his =  new PaymentHistory();
                        $pay_his->payment_date = $this->crud->entry->payment_date;
                        $pay_his->loan_id = $loan_id;
                        $pay_his->schedule_id = $l_s->id;
                        $pay_his->payment_id = $this->crud->entry->id;
                        $pay_his->principal_p = $l_s->principal_s - $l_s->principle_pd;
                        $pay_his->interest_p = $l_s->interest_s - $l_s->interest_pd;
                        $pay_his->penalty_p = $penalty_amount;
                        $pay_his->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                        $pay_his->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                        $pay_his->owed_balance = 0;
                        $pay_his->save();
                    }
                    
                    ////////////////////////////////Principle Accounting//////////////////////////
                    $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);
                    if (companyReportPart() == 'company.mkt'){
                        $c_acc = new GeneralJournalDetailTem();
                    }else{
                        $c_acc = new GeneralJournalDetail();
                    }

                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id ?? 0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = $func_source;
                    $c_acc->dr = 0;
                    if($late_rp == 1){
                        $c_acc->cr = $this->crud->entry->principle;
                    }
                    else{
                        $c_acc->cr = $l_s->principal_s - $l_s->principle_pd;
                    }
                   
                    $c_acc->j_detail_date = $row->payment_date;
                    $c_acc->description = 'Principle';
                    $c_acc->class_id = 0;
                    $c_acc->job_id = 0;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'payment';
                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = companyReportPart() == 'company.moeyan' ? $loan->branch_id : $branch_id;
                    //dd($c_acc->cr);
                    if( $c_acc->cr >0) {
                        $c_acc->save();
                    }
                    ////////////////////////////////Principle Accounting//////////////////////////

                    ////////////////////////////////Interest Accounting//////////////////////////
                    if (companyReportPart() == 'company.mkt'){
                        $c_acc = new GeneralJournalDetailTem();
                    }else{
                        $c_acc = new GeneralJournalDetail();
                    }

                    $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id??0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = $interest_income;
                    $c_acc->dr = 0;
                    if($late_rp == 1){
                        $c_acc->cr = $this->crud->entry->interest;
                    }
                    else{
                        $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;
                    }
                    $c_acc->j_detail_date = $row->payment_date;
                    $c_acc->description = 'Interest Income';
                    $c_acc->class_id  =  0;
                    $c_acc->job_id  =  0;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'payment';
                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = companyReportPart() == 'company.moeyan' ? $loan->branch_id : $branch_id;
                    if($c_acc->cr >0) {
                        $c_acc->save();
                    }
                    ////////////////////////////////Interest Accounting//////////////////////////

                    ////////////////////////////////Compulsory Accounting//////////////////////////
                    if (companyReportPart() == 'company.mkt'){
                        $c_acc = new GeneralJournalDetailTem();
                    }else{
                        $c_acc = new GeneralJournalDetail();
                    }

                    $compulsory = LoanCompulsory::where('loan_id',$loan_id)->first();
                    if($compulsory != null) {
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
                        $c_acc->branch_id = $branch_id;
                        if ($c_acc->cr > 0) {
                            $c_acc->save();
                        }
                    }
                    ////////////////////////////////Compulsory Accounting//////////////////////////

                    ////////////////////////////////Service Accounting//////////////////////////
                    MFS::serviceChargeAcc($acc->id,$row->payment_date,$loan,$row->id,$row->client_id,$total_service);
                    ////////////////////////////////Service Accounting//////////////////////////

                    ////////////////////////////////Penalty Accounting//////////////////////////
                    if (companyReportPart() == 'company.mkt'){
                        $c_acc = new GeneralJournalDetailTem();
                    }else{
                        $c_acc = new GeneralJournalDetail();
                    }

                    $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                    $c_acc->journal_id = $acc->id;
                    $c_acc->currency_id = $currency_id??0;
                    $c_acc->exchange_rate = 1;
                    $c_acc->acc_chart_id = $penalty_income;
                    $c_acc->dr = 0;
                    // $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;
                    $c_acc->cr = $penalty_amount;
                    $c_acc->j_detail_date = $row->payment_date;
                    $c_acc->description = 'Penalty Payable';
                    $c_acc->class_id  =  0;
                    $c_acc->job_id  =  0;
                    $c_acc->tran_id = $row->id;
                    $c_acc->tran_type = 'payment';
                    $c_acc->name = $row->client_id;
                    $c_acc->branch_id = companyReportPart() == 'company.moeyan' ? $loan->branch_id : $branch_id;
                    if ($c_acc->cr > 0) {
                        $c_acc->save();
                    }
                    ////////////////////////////////Penalty Accounting//////////////////////////
                    if($late_rp != 1){
                        $l_s->principal_p = $l_s->principal_s;
                        $l_s->interest_p = $l_s->interest_s;
                        $l_s->penalty_p = 0;
                        $l_s->total_p = $l_s->total_s;
                        $l_s->balance_p = 0;
                        $l_s->owed_balance_p = 0;
                        $l_s->service_charge_p = $l_s->charge_schedule;
                        $l_s->compulsory_p = $l_s->compulsory_schedule;
                        // $l_s->penalty_p = $l_s->penalty_schedule;
                        $l_s->principle_pd = $l_s->principal_s;
                        $l_s->interest_pd = $l_s->interest_s;
                        $l_s->total_pd = $l_s->total_s;
                        //$l_s->payment_pd = $balance_schedule;
                        $l_s->service_pd = $l_s->charge_schedule;
                        $l_s->compulsory_pd = $l_s->compulsory_schedule;
                        $l_s->payment_status = 'paid';
                        $l_s->save();
                    }
                    

                    /////////////////////////////////update loans oustanding /////////////////////

                    $payment = $payment - $balance_schedule;

                    //=================================================


                }else{

                    ///============================================
                    ///============================================
                    ///============================================
                    ///============================================
                    foreach ($repayment_order as $key => $value) {

                        if ($key == 'Interest') {
                            ////////////////////////////////Interest Accounting//////////////////////////
                            if (companyReportPart() == 'company.mkt'){
                                $c_acc = new GeneralJournalDetailTem();
                            }else{
                                $c_acc = new GeneralJournalDetail();
                            }

                            $interest_income = ACC::accIncomeForInterestLoanProduct(optional($loan)->loan_production_id);
                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $currency_id??0;
                            $c_acc->exchange_rate = 1;
                            $c_acc->acc_chart_id = $interest_income;
                            $c_acc->dr = 0;

                            $c_acc->j_detail_date = $row->payment_date;
                            $c_acc->description = 'Interest Income';
                            $c_acc->class_id  =  0;
                            $c_acc->job_id  =  0;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'payment';
                            $c_acc->name = $row->client_id;
                            $c_acc->branch_id = companyReportPart() == 'company.moeyan' ? $loan->branch_id : $branch_id;

                            ////////////////////////////////Interest Accounting//////////////////////////

                            if($payment >= $l_s->interest_s - $l_s->interest_pd) {
                                $l_s->interest_p = ($l_s->interest_s - $l_s->interest_pd);
                                $payment = $payment - ($l_s->interest_s - $l_s->interest_pd);
                                $c_acc->cr = $l_s->interest_s - $l_s->interest_pd;


                            }else{
                                $l_s->interest_p = $payment;
                                $c_acc->cr = $payment;

                                $payment = 0;
                            }

                            if($c_acc->cr>0) {
                                $c_acc->save();
                            }
                        }

                        if ($key == "Penalty") {
                            //=======================Acc Penalty============================
                            if (companyReportPart() == 'company.mkt'){
                                $c_acc = new GeneralJournalDetailTem();
                            }else{
                                $c_acc = new GeneralJournalDetail();
                            }

                            $penalty_income = ACC::accIncomeFromPenaltyLoanProduct(optional($loan)->loan_production_id);
                            $c_acc->journal_id = $acc->id;
                            $c_acc->currency_id = $currency_id??0;
                            $c_acc->exchange_rate = 1;
                            $c_acc->acc_chart_id = $penalty_income;
                            $c_acc->dr = 0;
                            $c_acc->j_detail_date = $row->payment_date;
                            $c_acc->description = 'Penalty Payable';
                            $c_acc->class_id  =  0;
                            $c_acc->job_id  =  0;
                            $c_acc->tran_id = $row->id;
                            $c_acc->tran_type = 'payment';
                            $c_acc->name = $row->client_id;
                            $c_acc->branch_id = companyReportPart() == 'company.moeyan' ? $loan->branch_id : $branch_id;


                            if($payment >= $l_s->penalty_schedule - $l_s->penalty_pd) {
                                // $l_s->penalty_p = ($l_s->penalty_schedule - $l_s->penalty_pd);
                                $l_s->penalty_p = $penalty_amount;
                                // $payment = $payment - ($l_s->penalty_schedule - $l_s->penalty_pd);
                                $payment = $payment - $penalty_amount;
                                // $c_acc->cr = $l_s->penalty_schedule - $l_s->penalty_pd;
                                $c_acc->cr = $penalty_amount;

                            }else{
                                // $l_s->penalty_p = $payment;
                                $l_s->penalty_p = $penalty_amount;
                                // $c_acc->cr = $payment;
                                $c_acc->cr = $penalty_amount;
                                $payment = 0;
                            }
                            if($c_acc->cr>0) {
                                $c_acc->save();
                            }
                        }

                        if ($key == "Service-Fee") {
                            if($payment >= $l_s->charge_schedule - $l_s->service_pd) {
                                $l_s->service_charge_p = $l_s->charge_schedule - $l_s->service_pd;
                                ////////////////////////////////Service Accounting//////////////////////////
                                if($l_s->service_charge_p >0) {
                                    MFS::serviceChargeAcc($acc->id, $row->payment_date, $loan, $row->id, $row->client_id, $l_s->service_charge_p);
                                }
                                ////////////////////////////////Service Accounting//////////////////////////
                                $payment = $payment -($l_s->charge_schedule - $l_s->service_pd);
                            }else{
                                $l_s->service_charge_p = $payment;
                                ////////////////////////////////Service Accounting//////////////////////////
                                if($payment>0) {
                                    MFS::serviceChargeAcc($acc->id, $row->payment_date, $loan, $row->id, $row->client_id, $payment);
                                }
                                ////////////////////////////////Service Accounting//////////////////////////
                                $payment = 0;
                            }

                        }

                        if ($key == "Saving") {
                            ////////////////////////////////Compulsory Accounting//////////////////////////
                            if (companyReportPart() == 'company.mkt'){
                                $c_acc = new GeneralJournalDetailTem();
                            }else{
                                $c_acc = new GeneralJournalDetail();
                            }

                            $compulsory = LoanCompulsory::where('loan_id',$loan_id)->first();
                            if($compulsory != null) {
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
                                $c_acc->branch_id = $branch_id;
                            }
                            ////////////////////////////////Compulsory Accounting//////////////////////////

                            if($payment >= $l_s->compulsory_schedule - $l_s->compulsory_pd) {
                                $l_s->compulsory_p = $l_s->compulsory_schedule - $l_s->compulsory_pd;
                                $payment = $payment - ($l_s->compulsory_schedule - $l_s->compulsory_pd);
                                $c_acc->cr =  $l_s->compulsory_schedule - $l_s->compulsory_pd;
                            }else{
                                $l_s->compulsory_p = $payment;
                                $c_acc->cr = $payment;
                                $payment = 0;
                            }
                            if($c_acc->cr>0) {
                                $c_acc->save();
                            }
                        }

                        if ($key == "Principle") {
                            ////////////////////////////////Principle Accounting//////////////////////////
                            $func_source = ACC::accFundSourceLoanProduct(optional($loan)->loan_production_id);

                            if (companyReportPart() == 'company.mkt'){
                                $c_acc = new GeneralJournalDetailTem();
                            }else{
                                $c_acc = new GeneralJournalDetail();
                            }

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
                            $c_acc->branch_id = companyReportPart() == 'company.moeyan' ? $loan->branch_id : $branch_id;

                            ////////////////////////////////Principle Accounting//////////////////////////
                            if($payment >= $l_s->principal_s - $l_s->principle_pd) {
                                $l_s->principal_p = $l_s->principal_s - $l_s->principle_pd;
                                $payment = $payment - ($l_s->principal_s - $l_s->principle_pd);
                                $c_acc->cr = $l_s->principal_s - $l_s->principle_pd;
                                $loan->save();
                            }else{
                                $l_s->principal_p = $payment;
                                $c_acc->cr = $payment;
                                $payment = 0;
                                $loan->save();
                            }

                            if($c_acc->cr>0) {
                                $c_acc->save();
                            }
                        }
                    }
                    if($late_rp != 1){
                        $l_s->save();
                        $l_s->principle_pd += $l_s->principal_p;
                        $l_s->interest_pd += $l_s->interest_p;
                        $l_s->total_pd += $l_s->total_p;
                        //$l_s->payment_pd += $balance_schedule;
                        $l_s->service_pd += $l_s->service_charge_p;
                        $l_s->compulsory_pd += $l_s->compulsory_p;
                        // $l_s->penalty_pd += $l_s->penalty_p;
                        $l_s->save();
                    }
                    


                    $balance_schedule = $l_s->total_schedule - $l_s->principle_pd - $l_s->interest_pd - $l_s->penalty_pd -$l_s->service_pd - $l_s->compulsory_pd;
                    $l_s->balance_schedule = $balance_schedule;
                    $l_s->count_payment = ($l_s->count_payment??0) +1;

                    $l_s->save();


                    ///============================================
                    ///============================================
                    ///============================================
                    if($late_rp == 1){
                        //dd($l_s->balance_schedule);
                        $pay_his =  new PaymentHistory();
                        $pay_his->payment_date = $this->crud->entry->payment_date;
                        $pay_his->loan_id = $loan_id;
                        $pay_his->schedule_id = $l_s->id;
                        $pay_his->payment_id = $this->crud->entry->id;
                        $pay_his->principal_p = $this->crud->entry->principle;
                        $pay_his->interest_p = $this->crud->entry->interest;
                        $pay_his->penalty_p = $penalty_amount;
                        $pay_his->service_charge_p = $l_s->service_charge_p;
                        $pay_his->compulsory_p = $l_s->compulsory_p;
                        $pay_his->owed_balance = $l_s->balance_schedule - $penalty;
                        $pay_his->save();

                    }
                    else{
                        $pay_his =  new PaymentHistory();
                        $pay_his->payment_date = $this->crud->entry->payment_date;
                        $pay_his->loan_id = $loan_id;
                        $pay_his->schedule_id = $l_s->id;
                        $pay_his->payment_id = $this->crud->entry->id;
                        $pay_his->principal_p = $l_s->principal_p;
                        $pay_his->interest_p = $l_s->interest_p;
                        $pay_his->penalty_p = $penalty_amount;
                        $pay_his->service_charge_p = $l_s->service_charge_p;
                        $pay_his->compulsory_p = $l_s->compulsory_p;
                        $pay_his->owed_balance = $l_s->balance_schedule - $penalty;
                        $pay_his->save();
                    }
                    

                    ///============================================
                    ///============================================
                    ///============================================

                }
                /////////////////////////////////update loans oustanding /////////////////////



            }
        }

        $data = $this->crud->entry;
        MFS::updateOutstanding($loan_id,$late_rp,$data);


        $loan_cal = LoanCalculate::where('disbursement_id',$loan_id)->where('payment_status','pending')->first();
        if($loan_cal == null){

                Loan2::where('id', $loan_id)
                    ->update(['disbursement_status' => 'Closed']);

        }
        //==============================================
        //==============================================
        //==============================================
        //==============================================
        //==============================================
        //==============================================
        //==============================================

        //MFS::getRepaymentAccount($loan_id,$principle,$interest,$saving,$arr_charge,$penalty,$payment,$row);

        if($request->ajax()){
            return ['error'=>0];
        }else {
            if ($request->is_frame > 0) {
                $print = true;
                return redirect('api/print-loan-payment?id=' . $this->crud->entry->id . '&schedule_id=' .$schedule_id. '&print=' .$print);
            } else {
                return redirect('admin/loanpayment');
            }
        }
        }
        else{
            return redirect('admin/loanoutstanding')->withErrors('Your Repayment is already activated');
        }



        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry



    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        $total_service = 0;
        $payment_id = $this->crud->entry->id;
        $charge_amount = $request->service_charge;
        $charge_id = $request->charge_id;

        if ($charge_id != null){
            foreach ($charge_id as $key => $va){
                $payment_charge = new  PaymentCharge();
                $total_service += isset($charge_amount[$key])?$charge_amount[$key]:0;
                $payment_charge->payment_id = $payment_id;
                $payment_charge->service_charge_id = $key;
                $payment_charge->charge_id = isset($charge_id[$key])?$charge_id[$key]:0;
                $payment_charge->charge_amount = isset($charge_amount[$key])?$charge_amount[$key]:0;
                $payment_charge->save();
            }
        }
        LoanPayment::savingTransction($this->crud->entry);
        $acc = AccountChart::find($this->crud->entry->cash_acc_id);

        $depo = LoanPayment::find($this->crud->entry->id);
        $depo->total_service_charge = $total_service;
        $depo->acc_code = optional($acc)->code;
        $depo->save();
        return $redirect_location;
    }
    public function printPayment(Request $request){
        $id = $request->id;
        $loan_payment = LoanPayment::find($id);
        if(companyReportPart() == 'company.moeyan'){
            if($request->print){
                $schedule_id = $request->schedule_id;
                $payment_total = 0;
                $schedule = PaymentHistory::where('schedule_id',$schedule_id)->orderBy('id','desc')->first();
                $payment_principal = PaymentHistory::where('payment_id',$loan_payment->id)->sum('principal_p');
                $payment_interest = PaymentHistory::where('payment_id',$loan_payment->id)->sum('interest_p');
                $payment_total = $payment_principal + $payment_interest;
                return view('partials.loan-payment.loan-payment-print-moeyan',['row'=>$loan_payment,'schedule'=>$schedule,'payment_total'=>$payment_total]);
            }else{
                $schedule_id = $request->schedule_id;
                $schedule = PaymentHistory::where('schedule_id',$schedule_id)->orderBy('id','desc')->first();
                return view('partials.loan-payment.loan-payment-print-moeyan',['row'=>$loan_payment,'schedule'=>$schedule]);
            }
            
        }
        else{
            return view('partials.loan-payment.loan-payment-print',['row'=>$loan_payment]);
        }
    }

    public function loanRepaymentList(){
        $rows = LoanPayment::with('client_name', 'loan_disbursement')->orderBy('id','desc')->paginate(30);
        return  view('partials.loan-payment.loan-payment-list',[
            'rows' => $rows
        ]);
    }

    public function dueRepaymentList(){

        $rows = Loan::with(['loan_schedule' => function($query){
            $query->select('id', 'disbursement_id', 'date_s', 'principal_s')
            ->whereRaw('DATE(date_s) = DATE(NOW())')
            ->first();
        }, 'client_name', 'branch_name', 'center_leader_name', 'officer_name'])
        ->select('id', 'disbursement_number', 'client_id', 'branch_id', 'center_leader_id', 'loan_officer_id')
        ->whereHas('loan_schedule', function($q){
            $q->whereRaw('DATE(date_s) = DATE(NOW())');
        })
        ->where('disbursement_status', 'Activated')
        ->orderBy('id','desc')->paginate(10);

        return  view('partials.loan-payment.due-repayment-list', compact('rows'));
    }

    public function lateRepaymentList(){

        $rows = Loan::with(['loan_schedule' => function($query){
            $query->select('id', 'disbursement_id', 'date_s', 'principal_s')
            ->whereRaw('DATE(date_s) = DATE(NOW())')
            ->first();
        }, 'client_name', 'branch_name', 'center_leader_name', 'officer_name'])
        ->select('id', 'disbursement_number', 'client_id', 'branch_id', 'center_leader_id', 'loan_officer_id')
        ->whereHas('loan_schedule', function($q){
            $q->whereRaw('DATE(date_s) = DATE(NOW())');
        })
        ->where('disbursement_status', 'Activated')
        ->orderBy('id','desc')->paginate(10);

        return  view('partials.loan-payment.due-repayment-list', compact('rows'));
    }

    public function deleteFlexiblePayment(Request $request){
        $id = $request->payment_id;
        $note = $request->note;

        // $l_payment = LoanPayment::find($id);

        // if($l_payment != null) {
        //     $pay_charge = PaymentCharge::where('payment_id',$l_payment->id)->sum('charge_amount');
        //     $disbursement_detail_id = $l_payment->disbursement_detail_id;
        //     $loan  = Loan2::find($l_payment->disbursement_id);
        //     dd($loan);

        //     $del_his = new DeletePaymentHistory();
        //     $del_his->payment_id = $l_payment->id;
        //     $del_his->client_id = $l_payment->client_id;
        //     $del_his->loan_id = $l_payment->disbursement_id;
        //     $del_his->created_by = backpack_auth()->user()->id;
        //     $del_his->payment_number = $l_payment->payment_number;
        //     $del_his->loan_number = optional($loan)->disbursement_number;
        //     $del_his->note = $note;
        //     $del_his->schedule_id = $disbursement_detail_id;
        //     $del_his->compulsory_saving = $l_payment->compulsory_saving;
        //     $del_his->over_days = $l_payment->over_days;
        //     $del_his->penalty_amount = $l_payment->penalty_amount;
        //     $del_his->principle = $l_payment->principle;
        //     $del_his->interest = $l_payment->interest;
        //     $del_his->old_owed = $l_payment->old_owed;
        //     $del_his->other_payment = $l_payment->other_payment;
        //     $del_his->payment = $l_payment->payment;
        //     $del_his->total_service_charge = $pay_charge;
        //     $del_his->payment_date = $l_payment->payment_date;
        //     $del_his->save();
        // }

    }

    public function deletePayment(Request $request){
        $id = $request->payment_id;
        $note = $request->note;
        $l_payment = LoanPayment::find($id);
        $delPayments = array();

        if($l_payment != null) {
            $pay_charge = PaymentCharge::where('payment_id',$l_payment->id)->sum('charge_amount');
            //dd($disbursement_detail_id);
            $disbursement_detail_id = $l_payment->disbursement_detail_id;
            $loan  = Loan2::find($l_payment->disbursement_id);
            $del_his = new DeletePaymentHistory();
            $del_his->payment_id = $l_payment->id;
            $del_his->client_id = $l_payment->client_id;
            $del_his->loan_id = $l_payment->disbursement_id;
            $del_his->created_by = backpack_auth()->user()->id;
            $del_his->payment_number = $l_payment->payment_number;
            $del_his->loan_number = optional($loan)->disbursement_number;
            $del_his->note = $note;
            $del_his->schedule_id = $disbursement_detail_id;
            $del_his->compulsory_saving = $l_payment->compulsory_saving;
            $del_his->over_days = $l_payment->over_days;
            $del_his->penalty_amount = $l_payment->penalty_amount;
            $del_his->principle = $l_payment->principle;
            $del_his->interest = $l_payment->interest;
            $del_his->old_owed = $l_payment->old_owed;
            $del_his->other_payment = $l_payment->other_payment;
            $del_his->payment = $l_payment->payment;
            $del_his->total_service_charge = $pay_charge;
            $del_his->payment_date = $l_payment->payment_date;
            $del_his->save();


            if ($disbursement_detail_id != null) {
                $disbursement_detail_id = str_replace('"', '', $disbursement_detail_id);
                $arr = explode('x', $disbursement_detail_id);
                $loan_d = LoanCalculate::whereIn('id', $arr)->orderBy('date_s', 'ASC')->get();

                if ($loan_d != null) {
                    foreach ($loan_d as $r) {
                        if($l_payment->pre_repayment == 1){
                            $schedule_rollback = LoanCalculate::find($r->id);
                            $schedule_rollback->interest_s = $schedule_rollback->old_interest;
                            $schedule_rollback->total_s = $schedule_rollback->principal_s + $schedule_rollback->old_interest;
                            $schedule_rollback->save();
                        }
                        $backup = ScheduleBackup::where('schedule_id', $r->id)->orderBy('id', 'DESC')->first();

                        if ($backup != null) {
                            $r->principal_p = $backup->principal_p;
                            $r->interest_p = $backup->interest_p;
                            $r->penalty_p = $backup->penalty_p;
                            $r->service_charge_p = $backup->service_charge_p;
                            $r->balance_p = $backup->balance_p;
                            $r->compulsory_p = $backup->compulsory_p;
                            $r->charge_schedule = $backup->charge_schedule;
                            $r->compulsory_schedule = $backup->compulsory_schedule;
                            $r->total_schedule = $backup->total_schedule;
                            $r->balance_schedule = $backup->balance_schedule;
                            $r->penalty_schedule = $backup->penalty_schedule;
                            $r->principle_pd = $backup->principle_pd;
                            $r->interest_pd = $backup->interest_pd;
                            $r->total_pd = $backup->total_pd;
                            $r->penalty_pd = $backup->penalty_pd;
                            $r->service_pd = $backup->service_pd;
                            $r->compulsory_pd = $backup->compulsory_pd;
                            $r->count_payment = $backup->count_payment;
                            $r->payment_status = 'pending';
                            if ($r->save()) {
                                $backup->delete();
                                PaymentHistory::where('schedule_id', $r->id)->where('payment_id', $id)->delete();
                            }
                        }
                        else{
                            $payment_back = ScheduleBackup::where('payment_id', $l_payment->id)->orderBy('id', 'DESC')->first();

                            if(!$payment_back){
                                $r->principal_p = 0;
                                $r->interest_p = 0;
                                $r->penalty_p = 0;
                                $r->service_charge_p = 0;
                                $r->balance_p = 0;
                                $r->compulsory_p = 0;
                                $r->charge_schedule = 0;
                                $r->compulsory_schedule = 0;
                                $r->total_schedule = 0;
                                $r->balance_schedule = 0;
                                $r->penalty_schedule = 0;
                                $r->principle_pd = 0;
                                $r->interest_pd = 0;
                                $r->total_pd = 0;
                                $r->penalty_pd = 0;
                                $r->service_pd = 0;
                                $r->compulsory_pd = 0;
                                $r->count_payment = 0;
                                $r->payment_status = 'pending';
                                if ($r->save()) {
                                    $histories = PaymentHistory::where('schedule_id', $r->id)->get();
                                    foreach($histories as $history){
                                        array_push($delPayments, $history->payment_id);
                                    }
                                    PaymentHistory::where('schedule_id', $r->id)->delete();
                                }

                            }
                        }
                    }
                }
            }

            if(count($delPayments) > 0){
                foreach($delPayments as $id){
                    PaymentCharge::where('payment_id', $id)->delete();
                    $gl = GeneralJournal::where('tran_id', $id)->where('tran_type', 'payment')->first();
                    GeneralJournalDetail::where('journal_id', $gl->id)->delete();
                    GeneralJournal::where('tran_id', $id)->where('tran_type', 'payment')->delete();

                    CompulsorySavingTransaction::where('tran_id', $id)->where('train_type', 'saving')->delete();
                    MFS::updateOutstanding($l_payment->disbursement_id);

                    LoanPayment::where('id', $id)->delete();
                }
            }else{
                PaymentCharge::where('payment_id', $id)->delete();
                $gl = GeneralJournal::where('tran_id', $id)->where('tran_type', 'payment')->first();
                GeneralJournalDetail::where('journal_id', $gl->id)->delete();
                GeneralJournal::where('tran_id', $id)->where('tran_type', 'payment')->delete();

                CompulsorySavingTransaction::where('tran_id', $id)->where('train_type', 'saving')->delete();
                MFS::updateOutstanding($l_payment->disbursement_id);

                LoanPayment::where('id', $id)->delete();
            }

            DB::table(getLoanTable())
                ->where('id', $l_payment->disbursement_id)
                ->update(['disbursement_status' => 'Activated']);

        }

        return ['error'=>1];
    }

    public function deleteDuplicates(Request $request){
        $id = $request->payment_id;
        $note = $request->note;
        $l_payment = LoanPayment::find($id);
        $delPayments = array();

        $histories = PaymentHistory::where('payment_id', $id)->delete();

        PaymentCharge::where('payment_id', $id)->delete();
        $gl = GeneralJournal::where('tran_id', $id)->where('tran_type', 'payment')->first();
        GeneralJournalDetail::where('journal_id', $gl->id)->delete();
        GeneralJournal::where('tran_id', $id)->where('tran_type', 'payment')->delete();

        CompulsorySavingTransaction::where('tran_id', $id)->where('train_type', 'saving')->delete();
        MFS::updateOutstanding($l_payment->disbursement_id);

        LoanPayment::where('id', $id)->delete();
    }

    public function deleteAllPayments(Request $request){
        $payment_ids = explode("x", $request->payment_ids);
        $note = $request->note;
        $loan_id = $request->loan_id;

        PaymentCharge::whereIn('payment_id', $payment_ids)->delete();

        $loan  = Loan2::find($loan_id);
        DeletePaymentHistory::where('loan_id',$loan_id)->delete();

            $loan_d = LoanCalculate::where('disbursement_id', $loan_id)->get();

            if ($loan_d != null) {
                foreach ($loan_d as $r) {
                    $backup = ScheduleBackup::where('schedule_id', $r->id)->orderBy('id', 'DESC')->first();

                    $r->principal_p = 0;
                    $r->interest_p = 0;
                    $r->penalty_p = 0;
                    $r->service_charge_p = 0;
                    $r->balance_p = 0;
                    $r->compulsory_p = 0;
                    $r->charge_schedule = 0;
                    $r->compulsory_schedule = 0;
                    $r->total_schedule = 0;
                    $r->balance_schedule = 0;
                    $r->penalty_schedule = 0;
                    $r->principle_pd = 0;
                    $r->interest_pd = 0;
                    $r->total_pd = 0;
                    $r->penalty_pd = 0;
                    $r->service_pd = 0;
                    $r->compulsory_pd = 0;
                    $r->count_payment = 0;
                    $r->payment_status = 'pending';
                        if ($r->save()) {
                            optional($backup)->delete();
                        }

                }
            }

        PaymentHistory::where('loan_id', $loan_id)->delete();
        GeneralJournalDetail::whereIn('tran_id', $payment_ids)->where('tran_type', 'payment')->delete();
        GeneralJournal::whereIn('tran_id', $payment_ids)->where('tran_type', 'payment')->delete();

        CompulsorySavingTransaction::whereIn('tran_id', $payment_ids)->where('train_type', 'saving')->delete();

        $total_interest_s = LoanCalculate::where('disbursement_id', $loan->id)->sum('interest_s');
        $loan->principle_receivable = $loan->loan_amount;
        $loan->principle_repayment = 0;
        $loan->interest_receivable = $total_interest_s;
        $loan->interest_repayment = 0;
        $loan->disbursement_status = 'Activated';
        $loan->save();

        LoanPayment::where('disbursement_id', $loan_id)->delete();

    }

    public static function paymentList(Request $request){
        $id = $request->payment_id;
        $payment = LoanPayment::find($id);
        return view('partials.loan-payment.last-payment',['payment'=>$payment]);
    }
}
