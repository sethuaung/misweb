<?php

namespace App\Http\Controllers\admin;

use App\Exports\ExportLoanOutstanding;
use App\Models\LoanCalculate;
use App\Models\LoanOustandingTem;
use App\Models\LoanOutstanding;
use App\Models\LoanPayment;
use App\Models\LoanProduct;
use App\Models\PaidDisbursement;
use Illuminate\Http\Request;
use Backpack\CRUD\CrudPanel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class LoanOutstandingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PrepaidLoanReportController extends CrudController
{
    public function setup()
    {
        // $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */

        $this->crud->setModel('App\Models\PrePaid');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/prepaid-loan');
        $this->crud->setEntityNameStrings('Prepaid Loan', 'Prepaid Loan');
        //$this->crud->setListView('partials.reports.loan.loan-report');

//        $this->crud->denyAccess(['update']);
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        $this->crud->addClause('whereHas', 'pre_repayment', function($query) {
            $query->where('pre_repayment' ,1);
        });
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'disbursement_number',
            'label'=> 'Loan Number'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.disbursement_number', 'LIKE', "%$value%");
            }
        );
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_id',
            'label'=> 'Client'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', 'clients', getLoanTable().'.client_id', 'clients.id');
                $this->crud->addClause('where', 'clients.name', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'clients.client_number', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
                $this->crud->addClause('select', getLoanTable().'.*');
        
            }
        );
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_production_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan Product',
            'placeholder' => 'Pick a Loan Product'
        ],
        url('api/loan-product-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.loan_production_id', $value);
            });
    
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'loan_officer_id',
                'type' => 'select2_ajax',
                'label'=> _t("CO Name"),
                'placeholder' => 'Pick a Loan officer'
            ],
            url('/api/loan-officer-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.loan_officer_id', $value);
            });
    
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'group_loan_id',
                'type' => 'select2_ajax',
                'label'=> 'Group Loan',
                'placeholder' => 'Pick a group loan'
            ],
            url("/api/get-group-loan-option"),
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.group_loan_id', $value);
            });
        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'guarantor_id',
            'label'=> 'Guarantor'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('join', 'guarantors', getLoanTable().'.guarantor_id', 'guarantors.id');
                $this->crud->addClause('where', 'guarantors.full_name_en', 'LIKE', "%$value%");
                $this->crud->addClause('orWhere', 'guarantors.full_name_mm', 'LIKE', "%$value%");
        
                $this->crud->addClause('select', getLoanTable().'.*');
        
            }
        );
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'center_leader_id',
            'type' => 'select2_ajax',
            'label'=> 'Center',
            'placeholder' => 'Pick a center'
        ],
        url('/api/center-option'),
        function($value) { // if the filter is active
            $this->crud->addClause('where', getLoanTable().'.center_leader_id', $value);
        });
        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $loan_ids = \App\Models\LoanPayment::where('pre_repayment',1)
                    ->where('payment_date','>=', $dates->from)
                    ->where('payment_date','<=', $dates->to . ' 23:59:59')->get()->pluck('disbursement_id')->toArray();
                $this->crud->addClause('whereIn', 'id', $loan_ids);
        });
        $this->crud->addColumn([
            'label' => _t("Loan Number"), // Table column heading
            'name' => 'disbursement_number',
        ]);
        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->client_number;
            }
        ]);
        
        
        $this->crud->addColumn([
            'label' => _t("Name (Eng)"), // Table column heading
            'type' => "select",
            'name' => 'client_id', // the column that contains the ID of that connected entity;
            'entity' => 'client_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);
        
        $this->crud->addColumn([
            'label' => _t("Other (MM)"), // Table column heading
            'type' => "closure",
            'name' => 'name_other', // the column that contains the ID of that connected entity;
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->name_other;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'Nrc Number',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->nrc_number;
            }
        ]);
        $this->crud->addColumn([
            'label' => _t("Group Loan"), // Table column heading
            'type' => "select",
            'name' => 'group_loan_id', // the column that contains the ID of that connected entity;
            'entity' => 'group_loans', // the method that defines the relationship in your Model
            'attribute' => "group_code", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);
        
        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'Nrc Number',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(\App\Models\Client::find($client_id))->nrc_number;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'service_charge',
            'label' => 'Service Charge',
            'type' => 'closure',
            'function' => function ($entry) {
                $total_line_charge = 0;
    
                $charges = \App\Models\LoanCharge::where('status','Yes')->where('loan_id',$entry->id)->get();
                if($charges != null){
                    foreach ($charges as $c){
                        $amt_charge = $c->amount;
                        $total_line_charge += ($c->charge_option == 1?$amt_charge:(($entry->loan_amount*$amt_charge)/100));
                    }
                }
                return $total_line_charge;
            }
        ]);
        $this->crud->addColumn([
            'name' => 'compulsory',
            'label' => 'Compulsory Saving',
            'type' => 'closure',
            'function' => function ($entry) {
                $total_compulsory = 0;
                $compulsory = \App\Models\LoanCompulsory::where('loan_id',$entry->id)->where('status','Yes')->first();
                if($compulsory != null){
                    $amt_compulsory = $compulsory->saving_amount;
                    $total_compulsory += ($compulsory->charge_option == 1?$amt_compulsory:(($entry->loan_amount*$amt_compulsory)/100));
                }
                return $total_compulsory;
            }
        ]);
        
        $this->crud->addColumn([
            'name' => 'first_installment_date',
            'label' => 'First Installment Date',
            'type' => 'date'
        ]);
        
        $this->crud->addColumn([
            'name' => 'loan_application_date',
            'label' => 'Application Date',
            'type' => 'date'
        ]);
        
        $this->crud->addColumn([
            'name' => 'status_note_date_activated',
            'label' => 'Disbursement Date',
            'type' => 'date'
        
        ]);
        $this->crud->addColumn([
            'label' => _t("Branch"), // Table column heading
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);
        
        
        
        $this->crud->addColumn([
            'label' => _t("Center"), // Table column heading
            'type' => "select",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'entity' => 'center_name',  // the method that defines the relationship in your Model
            'attribute' => "title",   // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);
        $this->crud->addColumn([
            'label' => _t("Co Name"), // Table column heading
            'type' => "select",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
        ]);
        $this->crud->addColumn([
            'label' => _t('Loan Product'),
            'type' => "select",
            'name' => 'loan_production_id', // the column that contains the ID of that connected entity
            'entity' => 'loan_product', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\LoanProduct",
        ]);
        
        $this->crud->addColumn([
            'label' => _t("Interest"), // Table column heading
            'name' => 'interest_rate'
        ]);
        $this->crud->addColumn([
            'label' => _t('Repayments Terms'),
            'name' => 'repayment_term',
            'type' => 'enum',
        ]);
        $this->crud->addColumn([
            'label' => _t('Terms Period'),
            'name' => 'loan_term_value',
            'type' => 'number'
        ]);
        $this->crud->addColumn([
            'label' => _t("Loan Amount"), // Table column heading
            'name' => 'loan_amount',
            'type' => 'number'
        ]);
        
        $this->crud->addColumn([
            'name' => 'total_s',
            'label' => _t("Installment Amount"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
        
                $row = \App\Models\LoanCalculate::select('total_s')
                ->where('disbursement_id', $entry->id)
                    ->where('payment_status','pending')
                ->where('date_p', NULL)
                ->first();
        
                return ($row) ? numb_format($row->total_s??0,0) : '';
            }
        ]);
        $this->crud->addColumn([
            'label' => _t("Principle Repay"),
            'name' => 'principle_repayment',
            'type' => 'number',
        ]);
        
        $this->crud->addColumn([
            'label' => _t("Interest Repay"),
            'name' => 'interest_repayment',
            'type' => 'number',
        ]);
        
        $this->crud->addColumn([
            'label' => _t("Principal Outstanding"), // Table column heading
            'name' => 'principal_outstanding',
            'type' => 'closure',
            'function' => function ($entry) {
                $principal_p = optional($entry)->principle_repayment;
                $principal_out = optional($entry)->loan_amount - $principal_p;
                return numb_format($principal_out, 0);
            }
        ]);
        
        $this->crud->addColumn([
            'label' => _t("Interest Outstanding"), // Table column heading
            'name' => 'interest_outstanding',
            'type' => 'closure',
            'function' => function ($entry) {
                $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $entry->id)->sum('interest_s');
                $interest_p = optional($entry)->interest_repayment;
                $interest_out = $total_interest - $interest_p;
                if($interest_out < 0){
                    return 0;
                }else{
                    return numb_format($interest_out, 0);
                }
            }
        ]);
        $this->crud->addColumn([
            'name' => 'total_outstanding',
            'label' => _t("Total Outstanding"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                $total_outstanding = 0;
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to){
                    $dates = json_decode($from_to);
                    $principal_p = \App\Models\Loan::where([['id' , $entry->id], ['status_note_date_activated', '<=',$dates->to]])
                    ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])->first();
                    $total_interest = \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                            ->where(getLoanTable().'.status_note_date_activated','<=',$dates->to)
                                            ->where('disbursement_id' , $entry->id)
                                            ->sum('interest_s');
                    $interest_p = \App\Models\LoanPayment::where([['disbursement_id' , $entry->id], ['payment_date', '<=',$dates->to], ['payment_date', '>=',$dates->from]])->sum('interest');
                    $interest_out = $total_interest - $interest_p;
                    if($interest_out < 0){
                        $interest_out = 0;
                    }
                    $principal_out = optional($entry)->loan_amount - optional($principal_p)->principle_repayment;
                    $total_outstanding = $principal_out + $interest_out;
                }else{
                    $principal_p = optional($entry)->principle_repayment;
                    $principal_out = optional($entry)->loan_amount - $principal_p;
                    $total_interest = \App\Models\LoanCalculate::where('disbursement_id' , $entry->id)->sum('interest_s');
                    $interest_p = optional($entry)->interest_repayment;
                    $interest_out = $total_interest - $interest_p;
                    if($interest_out < 0){
                        $interest_out = 0;
                    }
                    $total_outstanding = $principal_out + $interest_out;
                }
                return number_format($total_outstanding, 0);
            }
        ]);
        $this->crud->addColumn([
            'label' => _t("Remark"), // Table column heading
            'name' => 'remark'
         ]);
         $this->crud->addColumn([
            'label' => _t("Cycle"), // Table column heading
            'name' => 'loan_cycle',
            'type' => 'number',
        ]);
        $this->crud->addColumn([
            'name' => 'disbursement_status',
            'label' => _t('Status'),
            'type' => 'closure',
            'function' => function($entry) {
        
                if ($entry->disbursement_status=="Pending"){
                    return '<button class="btn btn-warning btn-xs " >'.$entry->disbursement_status.'</button>';
                }
                elseif ($entry->disbursement_status=="Approved"){
                    return '<button class="btn btn-info btn-xs" >'.$entry->disbursement_status.'</button>';
                }
                elseif ($entry->disbursement_status=="Declined"){
                    return '<button class="btn btn-danger btn-xs" >'.$entry->disbursement_status.'</button>';
                }
                elseif ($entry->disbursement_status=="Completed"){
                    return '<button class="btn btn-success btn-xs" >'.$entry->disbursement_status.'</button>';
                }
                elseif ($entry->disbursement_status=="Closed"){
                    return '<button class="btn btn-success btn-xs" >'.$entry->disbursement_status.'</button>';
                }
                elseif ($entry->disbursement_status=="Activated"){
                    return '<button class="btn btn-primary btn-xs" >'.$entry->disbursement_status.'</button>';
                }
                elseif ($entry->disbursement_status=="Canceled"){
                    return '<button class="btn btn-danger btn-xs" >'.$entry->disbursement_status.'</button>';
                }
                elseif ($entry->disbursement_status=="Written-Off"){
                    return '<button class="btn btn-danger btn-xs" >'.$entry->disbursement_status.'</button>';
                }
        
            }
        
        ]);
        $this->crud->addColumn([
            'name' => 'count_prepaid',
            'label' => _t("Frequency of Prepaid"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to){
                    $dates = json_decode($from_to);
                    //$dis_ids = \App\Models\LoanCalculate::where('disbursement_id','=',optional($entry)->id)->where('date_s', '>=',$dates->from)->where('date_s','<=',$dates->to)->where('payment_status','paid')->get()->pluck('id')->toArray();
                    //$sch_ids = \App\Models\PaymentHistory::where('schedule_id',$dis_ids)->where('payment_date', '>=',$dates->from)->where('payment_date','<=',$dates->to)->get()->pluck('payment_id')->toArray();
                    $pay_ids = \App\Models\LoanPayment::where('payment_date', '>=',$dates->from)->where('payment_date','<=',$dates->to)->where('pre_repayment',1)->where('disbursement_id',optional($entry)->id)->get();
                    $ids = [];
                    foreach($pay_ids as $pay_id){
                        $_param_check = str_replace('"','',optional($pay_id)->disbursement_detail_id);
                        $arr_check = explode('x',$_param_check);
                        foreach($arr_check as $arr){
                            array_push($ids,(int)$arr);
                        }
                    }
                    //$result = \App\Models\LoanCalculate::whereIn('id',$ids)->get();
                    return count($ids);
                }else{
                    $loan_payment = \App\Models\LoanPayment::where('payment_date', '>',now())->where('pre_repayment',1)->where('disbursement_id',optional($entry)->id)->get();
                }
                return count($loan_payment);
            }
        ]);
        $this->crud->addColumn([
            'name' => 'principle',
            'label' => _t("Prepaid Principle"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to){
                    $dates = json_decode($from_to);
                    //$dis_ids = \App\Models\LoanCalculate::where('disbursement_id','=',optional($entry)->id)->where('date_s', '>=',$dates->from)->where('date_s','<=',$dates->to)->where('payment_status','paid')->get()->pluck('id')->toArray();
                    //$sch_ids = \App\Models\PaymentHistory::whereIn('schedule_id',$dis_ids)->get()->pluck('payment_id')->toArray();
                    $pay_ids = \App\Models\LoanPayment::where('payment_date', '>=',$dates->from)->where('payment_date','<=',$dates->to)->where('pre_repayment',1)->where('disbursement_id',optional($entry)->id)->sum('principle');
                    // $ids = [];
                    // foreach($pay_ids as $pay_id){
                    //     $_param_check = str_replace('"','',optional($pay_id)->disbursement_detail_id);
                    //     $arr_check = explode('x',$_param_check);
                    //     foreach($arr_check as $arr){
                    //         array_push($ids,(int)$arr);
                    //     }
                    // }
                    //dd($pay_ids);
                    //$result = \App\Models\LoanCalculate::whereIn('id',$ids)->sum('principal_s');
                    return number_format($pay_ids, 0);
                }else{
                    $principal_p = \App\Models\LoanPayment::where('payment_date', '>',now())->where('pre_repayment',1)->where('disbursement_id',optional($entry)->id)->sum('principle');
                }
                return number_format($principal_p, 0);
            }
        ]);
        $this->crud->addColumn([
            'name' => 'interest',
            'label' => _t("Prepaid Interest"), // Table column heading
            'type' => 'closure',
            'function' => function ($entry) {
                isset($_REQUEST['from_to']) ? $from_to = $_REQUEST['from_to'] : $from_to = null;
                if($from_to){
                    $dates = json_decode($from_to);
                    //$dis_ids = \App\Models\LoanCalculate::where('disbursement_id','=',optional($entry)->id)->where('date_s', '>=',$dates->from)->where('date_s','<=',$dates->to)->where('payment_status','paid')->get()->pluck('id')->toArray();
                    //$sch_ids = \App\Models\PaymentHistory::whereIn('schedule_id',$dis_ids)->get()->pluck('payment_id')->toArray();
                    $pay_ids = \App\Models\LoanPayment::where('payment_date', '>=',$dates->from)->where('payment_date','<=',$dates->to)->where('pre_repayment',1)->where('disbursement_id',optional($entry)->id)->sum('interest');
                    // $ids = [];
                    // foreach($pay_ids as $pay_id){
                    //     $_param_check = str_replace('"','',optional($pay_id)->disbursement_detail_id);
                    //     $arr_check = explode('x',$_param_check);
                    //     foreach($arr_check as $arr){
                    //         array_push($ids,(int)$arr);
                    //     }
                    // }
                    //$result = \App\Models\LoanCalculate::whereIn('id',$ids)->where('date_s', '>=',$dates->from)->where('date_s','<=',$dates->to)->sum('interest_s');
                    return number_format($pay_ids, 0);
                }else{
                    $interest = \App\Models\LoanPayment::where('payment_date', '>=',now())->where('pre_repayment',1)->where('disbursement_id',optional($entry)->id)->sum('interest');
                }
                return number_format($interest, 0);
            }
        ]);
        //$this->crud->enableDetailsRow();
        // $this->crud->allowAccess('disburse_details_row');
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);
        $this->crud->setListView('partials.loan_disbursement.payment-loan');
        $this->crud->disableResponsiveTable();
        $this->crud->enableExportButtons();
        $this->crud->setDefaultPageLength(10);
        $this->crud->removeAllButtons();
        $this->setPermissions();
    }

    public function setPermissions()
    {
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-outstanding';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }
    }
}
