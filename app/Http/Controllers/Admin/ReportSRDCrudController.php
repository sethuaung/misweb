<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\AccountChartExternal;
use App\Models\Branch;
use App\Models\Client;
use App\Models\FrdAccSetting;
use App\Models\GeneralJournalDetail;
use App\Models\ReportAccounting;
use App\Models\ReportSRD;
use App\Models\LoanCalculate;
use App\Models\LoanPayment;
use App\Models\Loan;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportAccountingRequest as StoreRequest;
use App\Http\Requests\ReportAccountingRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class ReportAccountingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportSRDCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportSRD');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-account-external');
        $this->crud->setEntityNameStrings('Report External', 'Report External');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'name' => 'search_type',
            'label' => _t('Search By'),
            'type' => 'select_from_array',
            'options' => [ 'normal' => 'Normal', 'range' => 'Account Range'],
            'fake' => true,
            'attributes' => [
                'id' => 'search_range'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-2'
            ],
        ]);

        $this->crud->addField([
            'label' => "Account",
            'type' => "select2_from_ajax_multiple",
            'name' => 'acc_chart_id',
            'entity' => 'acc_chart',
            'attribute' => "name",
            'model' => AccountChart::class,
            'data_source' => url("api/acc-chart"),
            'placeholder' => "Select a Account",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-5 acc_normal'
            ]
        ]);

        $this->crud->addField([
            'name' => 'acc_chart_range',
            'label' => _t('account_range'),
            'type' => 'account',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-9'
            ],
        ]);

        $this->crud->addField([   // date_picker
            'name' => 'month',
            'type' => 'date_picker',
            'label' => 'Month',
            'default' => date('Y-m-d') ,
            // optional:
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'yyyy-mm'
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-5'
            ],
        ]);

        $this->crud->addField(
            [ // select_from_array
                'name' => 'show_zero',
                'label' => "Show Zero",
                'type' => 'select_from_array',
                'options' => [
                    'No'=>'No',
                    'Yes'=>'Yes'
                ],
                'allows_null' => false,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-2'
                ]
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
            ]
        );
        $this->crud->addField([
            'label' => "Branch",
            'type' => "select2_from_ajax_multiple",
            'name' => 'branch_id',
            'entity' => 'category',
            'attribute' => "title",
            'model' => Branch::class,
            'data_source' => url("api/get-branch"),
            'placeholder' => "Select branch",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name' => 'date_date_range', // a unique name for this field
            'start_name' => 'start_date', // the db column that holds the start_date
            'end_name' => 'end_date', // the db column that holds the end_date
            'label' => 'Select Date Range',
            'type' => 'date_range',
            // OPTIONALS
            'start_default' => Carbon::now()->startOfMonth()->format('Y-m-d'), // default value for start_date
            'end_default' => date('Y-m-d'), // default value for end_date
            'date_range_options' => [ // options sent to daterangepicker.js
                //'timePicker' => true,
                'locale' => ['format' => 'DD/MM/YYYY']
//                'locale' => ['format' => 'DD/MM/YYYY HH:mm']
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
        ]);




        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => companyReportPart() == "company.moeyan" ? 'partials/reports/account/main-script-moeyan' : 'partials/reports/account/main-script',
        ]);

        $this->crud->setCreateView('custom.create_report_account_external');


        // add asterisk for fields that are required in ReportAccountingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'report-accounting';
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


    public function index()
    {
        return redirect('admin/report-account-external/create');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function accountList(Request $request){

//        dd($request->all());
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;
        $show_zero = $request->show_zero;

        $rows = AccountChartExternal::where(function ($query) use ($acc_chart_id){
            if($acc_chart_id != null){
                if(is_array($acc_chart_id)){
                    if(count($acc_chart_id)>0){
                        $query->whereIn('id',$acc_chart_id);
                    }
                }
            }

        })->get();

        $getAccountBalAll = ReportSRD::getAccountBalAll($acc_chart_id);
        $bals = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                $bals[$r->external_acc_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
            }

        }

        return view('partials.reports.account-external.account-list',['rows'=>$rows,
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals]);

    }

    public function trialBalance(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        $rows =  AccountChartExternal::where(function ($query) use ($acc_chart_id){
            if($acc_chart_id != null){
                if(is_array($acc_chart_id)){
                    if(count($acc_chart_id)>0){
                        $query->whereIn('id',$acc_chart_id);
                    }
                }
            }

        })->get();

        $getAccountBalAll = ReportSRD::getAccountBalAll($acc_chart_id,$end_date);
        $bals = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                $bals[$r->external_acc_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
            }

        }

        return view('partials.reports.account-external.trial-balance',['rows'=>$rows,
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals]);

    }


    public function profitLoss(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;
        $branch_id = $request->branch_id??1;


        if(companyReportPart() == 'company.quicken'){
            return view('partials.reports.account-external.profit-loss-quicken',[
                'start_date'=>$start_date,'end_date'=>$end_date,'branch_id'=>$branch_id]);
        }
        else{
            $getAccountBalAll = ReportSRD::getAccountBalAll($acc_chart_id,$start_date,$end_date,[40,50,60,70,80]);
            $bals = [];
            if($getAccountBalAll != null){
                foreach ($getAccountBalAll as $r){
                    $bals[$r->section_id][$r->external_acc_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
                }

            }
            return view('partials.reports.account-external.profit-loss',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals]);
        }

    }


    public function profitLossMKT(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_id = $request->branch_id;

        return view('partials.reports.account-external.profit-loss-mkt',[
            'start_date'=>$start_date,'end_date'=>$end_date,'branch_id'=>$branch_id]);

    }

    public function profitLoss_(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;
        $branch_id = $request->branch_id;


        $getAccountBalAll = ReportSRD::getAccountBalAll($acc_chart_id,$start_date,$end_date,[40,50,60,70,80]);

        //dd($getAccountBalAll);
        $bals = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                $bals[$r->section_id][$r->external_acc_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
            }

        }

        return view('partials.reports.account-external.profit-loss',[
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,'branch_id'=>$branch_id]);

    }

    public function balanceSheet(Request $request){

//        dd($request->all());
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $branch_id = $request->branch_id;
        $acc_chart_id = $request->acc_chart_id;

        $getAccountBalAll = ReportSRD::getAccountBalAll($acc_chart_id,$end_date,null,
            [10,12,14,16,18,20,22,24,26,30]);

        $getRetainedEarningBegin = ReportSRD::getRetainedEarning($start_date,null,true);
        $profit = ReportSRD::getRetainedEarning($start_date,$end_date,false);



        $bals = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                $bals[$r->section_id][$r->external_acc_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
            }
        }
        if(companyReportPart() == 'company.quicken'){
            return view('partials.reports.account-external.balance-sheet-quicken',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'retainedEarningBegin'=> (optional($getRetainedEarningBegin)->bal) ,
                'profit'=> (optional($profit)->bal),'branch_id'=>$branch_id,
            ]);
        }
        else{
            return view('partials.reports.account-external.balance-sheet',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'retainedEarningBegin'=> (optional($getRetainedEarningBegin)->bal) ,
                'profit'=> (optional($profit)->bal)
            ]);
        }
        

    }

    public function ten_largest_loan(Request $request){
        $largest_loans = Loan::where('disbursement_status','Activated')->orderby('loan_amount','desc')->paginate('10');
        //dd($largest_loan);
       return view('partials.reports.account-external.ten-largest-loan',['largest_loans'=>$largest_loans]);
    }

    public function ten_largest_deposit(request $request){
        $largest_loans = Loan::where('disbursement_status','Activated')->orderby('loan_amount','desc')->paginate('10');
        //dd($largest_loan);
       return view('partials.reports.account-external.ten-largest-deposit',['largest_loans'=>$largest_loans]);
    }

    public function saving_report(request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        //dd($largest_loan);
       return view('partials.reports.account-external.saving',['start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function market_conduct(request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_id = $request->branch_id??0;
        $loan_release_total =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                ->where('status_note_date_activated','<=',$end_date)
                                                ->sum('loan_amount');
        $principal_collection_total = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->sum('principle');
        $total_interest= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                            ->where('status_note_date_activated','<=',$end_date)
                            ->sum('interest_s');
        $interest_collection = \App\Models\PaymentHistory::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('interest_p');

        $loan_activated= \App\Models\Loan::where('status_note_date_activated','>=',$start_date)
                        ->where('status_note_date_activated','<=',$end_date)
                        ->whereIn('disbursement_status',['Activated','Closed'])
                        ->count('id');
        $officer_activated= \App\Models\Loan::where('status_note_date_activated','>=',$start_date)
                        ->where('status_note_date_activated','<=',$end_date)
                        ->whereIn('disbursement_status',['Activated','Closed'])
                        ->select('loan_officer_id')
                        ->distinct()->get()
                        ->count();
        $other_income = \App\Models\ReportSRD::getAccountBalAllQuicken('5-71-100-001',$branch_id,$start_date,$end_date);                                                                                            
        //dd($other_income);
       return view('partials.reports.account-external.market-conduct',['end_date'=>$end_date,'start_date'=>$start_date,'branch_id'=>$branch_id,'loan_activated'=>$loan_activated,'officer_activated'=>$officer_activated,'interest_collection'=>$interest_collection,'other_income'=>$other_income]);
    }

    public function deposit_taking(request $request){

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_id = $request->branch_id??2;
        $acc_chart_id = $request->acc_chart_id??0;
        $is_begin = False;  
        $acc_section_id = [10,12,16];

        $loan_release_total =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                ->where('status_note_date_activated','<=',$end_date)
                                                ->sum('loan_amount');
        $principal_collection_total = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->sum('principle');
        $total_interest= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                            ->where('status_note_date_activated','<=',$end_date)
                            ->sum('interest_s');
        $interest_collection = \App\Models\PaymentHistory::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('interest_p');
        $total_outstanding = ($loan_release_total - $principal_collection_total);

        $assets_dr = GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })->where(function ($query) use ($start_date, $end_date, $is_begin) {
            if ($start_date != null && $end_date == null) {
                if ($is_begin) {
                    return $query->whereDate('j_detail_date', '<', $start_date);
                } else {
                    return $query->whereDate('j_detail_date', '<=', $start_date);
                }
            } else if ($start_date != null && $end_date != null) {
                return $query->whereDate('j_detail_date', '>=', $start_date)
                    ->whereDate('j_detail_date', '<=', $end_date);
            }

        })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if (count($branch_id) > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->where(function ($query) use ($acc_section_id) {
                if ($acc_section_id != null) {
                    if (is_array($acc_section_id)) {
                        if (count($acc_section_id) > 0) {
                            $query->whereIn('section_id', $acc_section_id);
                        }
                    } else {
                        if ($acc_section_id > 0) {
                            $query->where('section_id', $acc_section_id);
                        }
                    }
                }
            })
            ->sum('dr');
            
            $assets_cr = GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
                if (is_array($acc_chart_id)) {
                    if (count($acc_chart_id) > 0) {
                        return $query->whereIn('acc_chart_id', $acc_chart_id);
                    }
                }
            })->where(function ($query) use ($start_date, $end_date, $is_begin) {
                if ($start_date != null && $end_date == null) {
                    if ($is_begin) {
                        return $query->whereDate('j_detail_date', '<', $start_date);
                    } else {
                        return $query->whereDate('j_detail_date', '<=', $start_date);
                    }
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('j_detail_date', '>=', $start_date)
                        ->whereDate('j_detail_date', '<=', $end_date);
                }
    
            })
                ->where(function ($query) use ($branch_id) {
                    if ($branch_id != null) {
                        if (is_array($branch_id)) {
                            if (count($branch_id) > 0) {
                                $query->whereIn('branch_id', $branch_id);
                            }
                        } else {
                            if (count($branch_id) > 0) {
                                $query->where('branch_id', $branch_id);
                            }
                        }
                    }
                })
                ->where(function ($query) use ($acc_section_id) {
                    if ($acc_section_id != null) {
                        if (is_array($acc_section_id)) {
                            if (count($acc_section_id) > 0) {
                                $query->whereIn('section_id', $acc_section_id);
                            }
                        } else {
                            if ($acc_section_id > 0) {
                                $query->where('section_id', $acc_section_id);
                            }
                        }
                    }
                })
                ->sum('cr');
        $total_assets =  $assets_dr -  $assets_cr;
        //dd($total_assets);
        $getAccountBalBranch = ReportAccounting::getAccountBalAllByLoanBranch($acc_chart_id,$start_date,$end_date,[40,50,60,70,80],false,$branch_id);
        $getAccountBalAll1 = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date,$end_date,[40,50,60,70,80],false,$branch_id);
        $getRetainedEarningBegin = ReportAccounting::getRetainedEarning($start_date,null,true,$branch_id);
        $profit = ReportAccounting::getRetainedEarning($start_date,$end_date,false,$branch_id);
        
        //dd($assets);
        $bals1 = [];
        $branches1 = [];
        $array_assets =[];
        $returnEarningBeg = [];
        if($getAccountBalAll1 != null){
            foreach ($getAccountBalAll1 as $r){
                $bals1[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
                
                $branches1[$r->branch_id] = $r->branch_id;
            }
        }
  
        if($getAccountBalBranch != null){
            foreach ($getAccountBalBranch as $r){
                $bals1[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;

                $branches1[$r->branch_id] = $r->branch_id;
            }
        }

        if($array_assets != null){
            foreach ($assets as $r){
                $array_assets[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0);
                $branches1[$r->branch_id] = $r->branch_id;
            }
        }

        $net_income = [];
        // $count = Branch::all();
        $total_expense = [];
        $net_ordinary = [];
        $total_income = [];
        $total_other_income = [];
        $total_other_expense = [];
        $gross_profit = [];
        $total_cogs = [];
        $arr_profit = [];

        foreach([40, 50, 60, 70, 80] as $sec){
                foreach($branches1 as $b_id){
                    if(isset($bals1[$sec])){
                        foreach($bals1[$sec] as $key => $amount){
                            if($sec == 40){
                                if(!isset($total_income[$b_id])) 
                        if(!isset($total_income[$b_id])) 
                                if(!isset($total_income[$b_id])) 
                        if(!isset($total_income[$b_id])) 
                                if(!isset($total_income[$b_id])) 
                                {
                                    $total_income[$b_id] = 0;
                                }
                                $total_income[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                            else if($sec == 50) {
                                if(!isset($total_cogs[$b_id])) {$total_cogs[$b_id] = 0;}
                                $total_cogs[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                            else if($sec == 60){
                                if(!isset($total_expense[$b_id])) {$total_expense[$b_id] = 0;}
                                $total_expense[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                            else if($sec == 70){
                                if(!isset($total_other_income[$b_id])) {$total_other_income[$b_id] = 0;}
                                $total_other_income[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                            else if($sec == 80){
                                if(!isset($total_other_expense[$b_id])) {$total_other_expense[$b_id] = 0;}
                                $total_other_expense[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                        }
                        
                    }

                    if(!isset($gross_profit[$b_id])) $gross_profit[$b_id] = 0;
                    $gross_profit[$b_id] = ($total_income[$b_id]??0) + ($total_cogs[$b_id]??0);

                    if(!isset($net_ordinary[$b_id])) {$net_ordinary[$b_id] = 0;}
                    $net_ordinary[$b_id] = ($gross_profit[$b_id]??0) + ($total_expense[$b_id]??0);


                    if(!isset($net_income[$b_id])) {$net_income[$b_id] = 0;}
                    $net_income[$b_id] = ( $net_ordinary[$b_id]??0) + ($total_other_income[$b_id]??0) + ($total_other_expense[$b_id]??0);
                }
            }
            if($getRetainedEarningBegin != null){
                foreach ($getRetainedEarningBegin as $e){
                    $returnEarningBeg[$e->branch_id] = $e->bal??0;
                }
            }
            if($profit != null){
                foreach ($profit as $p){
                    $arr_profit[$p->branch_id] = $p->bal;
                }
            }
            $total_equitity = $returnEarningBeg + $net_income;
            $result1 = $total_equitity[$branch_id[0]] / $total_assets;
            //dd($result1);

            $loans = Loan::where('disbursement_status','Activated')->where('branch_id',$branch_id[0])->get();
            $one_month = [];
            $two_month = [];
            $three_month = [];
            $over_three_month = [];
            foreach($loans as $loan){
                $row = LoanCalculate::select('date_s')
                ->where('disbursement_id', $loan->id)
                    ->where('payment_status','pending')
                ->where('date_p', NULL)
                ->first();

                $over_due = Carbon::parse($row->date_s)->diffInDays();
                if($over_due > 0 && $over_due <= 30){
                    array_push($one_month,$loan->id);
                }
                elseif($over_due > 30 && $over_due <= 60){
                    array_push($two_month,$loan->id);
                }
                elseif($over_due > 60 && $over_due <= 90){
                    array_push($three_month,$loan->id);
                }
                elseif($over_due > 90){
                    array_push($over_three_month,$loan->id);
                }
            }

            $loan_release_total_one =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                    ->whereIn('id',$one_month)
                                                    ->sum('loan_amount');

            $principal_collection_total_one = \App\Models\LoanPayment::whereIn('disbursement_id',$one_month)->sum('principle');
            $total_interest_one= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                ->whereIn('disbursement_id',$one_month)
                                ->sum('interest_s');
            $interest_collection_one = \App\Models\PaymentHistory::whereIn('loan_id',$one_month)
                                                                ->sum('interest_p');
            $total_outstanding_one = ($loan_release_total_one - $principal_collection_total_one);

            $loan_release_total_two =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('id',$two_month)
                                                        ->sum('loan_amount');

            $principal_collection_total_two = \App\Models\LoanPayment::whereIn('disbursement_id',$two_month)->sum('principle');
            $total_interest_two= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                                        ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('disbursement_id',$two_month)
                                                        ->sum('interest_s');
            $interest_collection_two = \App\Models\PaymentHistory::whereIn('loan_id',$two_month)
                                    ->sum('interest_p');
            $total_outstanding_two = ($loan_release_total_two - $principal_collection_total_two) ;

            $loan_release_total_three =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('id',$three_month)
                                                        ->sum('loan_amount');

            $principal_collection_total_three = \App\Models\LoanPayment::whereIn('disbursement_id',$three_month)->sum('principle');
            $total_interest_three= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                                        ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('disbursement_id',$three_month)
                                                        ->sum('interest_s');
            $interest_collection_three = \App\Models\PaymentHistory::whereIn('loan_id',$three_month)
                                    ->sum('interest_p');
            $total_outstanding_three = ($loan_release_total_three - $principal_collection_total_three);

            $loan_release_total_four =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('id',$over_three_month)
                                                        ->sum('loan_amount');

            $principal_collection_total_four = \App\Models\LoanPayment::whereIn('disbursement_id',$over_three_month)->sum('principle');
            $total_interest_four = \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                                        ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('disbursement_id',$over_three_month)
                                                        ->sum('interest_s');
            $interest_collection_four = \App\Models\PaymentHistory::whereIn('loan_id',$over_three_month)
                                    ->sum('interest_p');
            $total_outstanding_four = ($loan_release_total_three - $principal_collection_total_three);
            $average_total_asset = $total_assets * 3;
            $average_total_equity = $total_equitity[$branch_id[0]] * 3;
            $earnings_1 = $net_income[$branch_id[0]] / $average_total_asset;
            $earnings_2 = $net_income[$branch_id[0]] / $average_total_equity;
            $acc_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53',$branch_id,$start_date,$end_date);
    $acc_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54',$branch_id,$start_date,$end_date);
    $acc_3 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55',$branch_id,$start_date,$end_date);
    $acc_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-57',$branch_id,$start_date,$end_date);
    $acc_5 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-58',$branch_id,$start_date,$end_date);
    $acc_6 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-61',$branch_id,$start_date,$end_date);
    $acc_7 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-62',$branch_id,$start_date,$end_date);
    $acc_8 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-63',$branch_id,$start_date,$end_date);
    $acc_9 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-66',$branch_id,$start_date,$end_date);
    $acc_10 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-68',$branch_id,$start_date,$end_date);
    $acc_11 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-92',$branch_id,$start_date,$end_date);
    $pl6_31 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31',$branch_id,$start_date,$end_date);

    $all_amount = [];
    if ($acc_1->isNotEmpty()) {
        foreach($acc_1 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_2->isNotEmpty()) {
        foreach($acc_2 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_3->isNotEmpty()) {
        foreach($acc_3 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_4->isNotEmpty()) {
        foreach($acc_4 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_5->isNotEmpty()) {
        foreach($acc_5 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_6->isNotEmpty()) {
        foreach($acc_6 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_7->isNotEmpty()) {
        foreach($acc_7 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_8->isNotEmpty()) {
        foreach($acc_8 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_9->isNotEmpty()) {
        foreach($acc_9 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_10->isNotEmpty()) {
        foreach($acc_10 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_11->isNotEmpty()) {
        foreach($acc_11 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if($pl6_31->isNotEmpty()){
        $amount = 0;
        foreach($pl6_31 as $bal){
            $amount += $bal->amt;
        }
    }
    $amount_all = array_sum($all_amount);
    //dd($amount_all,$amount);
    $expense = $amount_all + $amount[$branch_id[0]];
    $earning_3 = $expense;
    $earning_4 = $gross_profit[$branch_id[0]];
        //dd($gross_profit);
        return view('partials.reports.account-external.deposit-taking',['result1'=>$result1,'total_outstanding'=>$total_outstanding,
                                                                              'total_outstanding_one'=>$total_outstanding_one,'total_outstanding_two'=>$total_outstanding_two,
                                                                              'total_outstanding_three'=>$total_outstanding_three,'total_outstanding_four'=>$total_outstanding_four,
                                                                              'earnings_1'=>$earnings_1,'earnings_2'=>$earnings_2,'earning_3'=>$earning_3,'earning_4'=>$earning_4]);
    }

    public function prudential_indicators(request $request){

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_id = $request->branch_id??2;
        $acc_chart_id = $request->acc_chart_id??0;
        $is_begin = False;  
        $acc_section_id = [10,12,16];

        $loan_release_total =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                ->where('status_note_date_activated','<=',$end_date)
                                                ->sum('loan_amount');
        $principal_collection_total = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->sum('principle');
        $total_interest= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                            ->where('status_note_date_activated','<=',$end_date)
                            ->sum('interest_s');
        $interest_collection = \App\Models\PaymentHistory::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('interest_p');
        $total_outstanding = ($loan_release_total - $principal_collection_total);
        $total_assets = $total_assets??1;

        // $extra_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('1-11-100-001',$branch_id,$start_date,$end_date);
        // $bs_1_1= \App\Models\ReportSRD::getAccountBalAllQuicken('1-11-100-002',$branch_id,$start_date_report,$end_date);
        // $bs_3_1= \App\Models\ReportSRD::getAccountBalAllQuicken('1-31',$branch_id,$start_date,$end_date);

        // $bs_4= \App\Models\ReportSRD::getAccountBalAllQuicken('2-21-500-001',$branch_id,$start_date,$end_date);
        // $extra_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-21-700-001',$branch_id,$start_date,$end_date);
        
        // $bs_5= \App\Models\ReportSRD::getAccountBalAllQuicken('2-20-100',$branch_id,$start_date,$end_date);
        
        // $bs_6_1= \App\Models\ReportSRD::getAccountBalAllQuicken('6.1',$branch_id,$start_date,$end_date);
        // $bs_6_1_1= \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-500-002',$branch_id,$start_date,$end_date);
        // $bs_6_1_2= \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-800-003',$branch_id,$start_date,$end_date);
        // $bs_6_2= \App\Models\ReportSRD::getAccountBalAllQuicken('6.2',$branch_id,$start_date,$end_date);
        // $bs_6_2_1= \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-500-003',$branch_id,$start_date,$end_date);
        // $bs_6_2_2= \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-800-002',$branch_id,$start_date,$end_date);
        // $bs_6_3= \App\Models\ReportSRD::getAccountBalAllQuicken('6.3',$branch_id,$start_date,$end_date);

        // $bs_6_3_1= \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-100-001',$branch_id,$start_date,$end_date);
        // $extra63_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-200-001',$branch_id,$start_date,$end_date);
        // $extra63_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-200-002',$branch_id,$start_date,$end_date);
        // $extra63_3 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-200-003',$branch_id,$start_date,$end_date);
        // $extra63_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-200-004',$branch_id,$start_date,$end_date);
        // $extra63_5 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-300-001',$branch_id,$start_date,$end_date);
        // $extra63_6 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-400-001',$branch_id,$start_date,$end_date);
        // $extra63_7 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-500-001',$branch_id,$start_date,$end_date);
        // $extra63_8 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-93-500-003',$branch_id,$start_date,$end_date);
        // $extra63_9 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-95-100-001',$branch_id,$start_date,$end_date);
        // $extra63_10 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-95-200-001',$branch_id,$start_date,$end_date);

        // $bs_6_3_2= \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-300-001',$branch_id,$start_date,$end_date);
        // $extra64_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-400-001',$branch_id,$start_date,$end_date);
        // $extra64_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-500-001',$branch_id,$start_date,$end_date);
        // $extra64_3 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-500-002',$branch_id,$start_date,$end_date);
        // $extra64_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-500-003',$branch_id,$start_date,$end_date);
        // $extra64_5 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-500-004',$branch_id,$start_date,$end_date);
        // $extra64_6 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-600-001',$branch_id,$start_date,$end_date);
        // $extra64_7 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-700-001',$branch_id,$start_date,$end_date);
        // $extra64_8 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-800-001',$branch_id,$start_date,$end_date);
        // $extra64_9 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-910-001',$branch_id,$start_date,$end_date);
        // $extra64_10 = \App\Models\ReportSRD::getAccountBalAllQuicken('2-94-920-001',$branch_id,$start_date,$end_date);
        // $bs_8= \App\Models\ReportSRD::getAccountBalAllQuicken('2-89-600',$branch_id,$start_date,$end_date);

        //dd($total_assets);
        $getAccountBalBranch = ReportAccounting::getAccountBalAllByLoanBranch($acc_chart_id,$start_date,$end_date,[40,50,60,70,80],false,$branch_id);
        $getAccountBalAll1 = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date,$end_date,[40,50,60,70,80],false,$branch_id);
        $getRetainedEarningBegin = ReportAccounting::getRetainedEarning($start_date,null,true,$branch_id);
        $profit = ReportAccounting::getRetainedEarning($start_date,$end_date,false,$branch_id);
        
        //dd($assets);
        $bals1 = [];
        $branches1 = [];
        $array_assets =[];
        $returnEarningBeg = [];
        if($getAccountBalAll1 != null){
            foreach ($getAccountBalAll1 as $r){
                $bals1[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
                
                $branches1[$r->branch_id] = $r->branch_id;
            }
        }
  
        if($getAccountBalBranch != null){
            foreach ($getAccountBalBranch as $r){
                $bals1[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;

                $branches1[$r->branch_id] = $r->branch_id;
            }
        }

        if($array_assets != null){
            foreach ($assets as $r){
                $array_assets[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0);
                $branches1[$r->branch_id] = $r->branch_id;
            }
        }

        $net_income = [];
        // $count = Branch::all();
        $total_expense = [];
        $net_ordinary = [];
        $total_income = [];
        $total_other_income = [];
        $total_other_expense = [];
        $gross_profit = [];
        $total_cogs = [];
        $arr_profit = [];

        foreach([40, 50, 60, 70, 80] as $sec){
                foreach($branches1 as $b_id){
                    if(isset($bals1[$sec])){
                        foreach($bals1[$sec] as $key => $amount){
                            if($sec == 40){
                                if(!isset($total_income[$b_id])) 
                        if(!isset($total_income[$b_id])) 
                                if(!isset($total_income[$b_id])) 
                        if(!isset($total_income[$b_id])) 
                                if(!isset($total_income[$b_id])) 
                                {
                                    $total_income[$b_id] = 0;
                                }
                                $total_income[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                            else if($sec == 50) {
                                if(!isset($total_cogs[$b_id])) {$total_cogs[$b_id] = 0;}
                                $total_cogs[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                            else if($sec == 60){
                                if(!isset($total_expense[$b_id])) {$total_expense[$b_id] = 0;}
                                $total_expense[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                            else if($sec == 70){
                                if(!isset($total_other_income[$b_id])) {$total_other_income[$b_id] = 0;}
                                $total_other_income[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                            else if($sec == 80){
                                if(!isset($total_other_expense[$b_id])) {$total_other_expense[$b_id] = 0;}
                                $total_other_expense[$b_id] += isset($amount[$b_id]) ? $amount[$b_id] : 0;
                            }
                        }
                        
                    }

                    if(!isset($gross_profit[$b_id])) $gross_profit[$b_id] = 0;
                    $gross_profit[$b_id] = ($total_income[$b_id]??0) + ($total_cogs[$b_id]??0);

                    if(!isset($net_ordinary[$b_id])) {$net_ordinary[$b_id] = 0;}
                    $net_ordinary[$b_id] = ($gross_profit[$b_id]??0) + ($total_expense[$b_id]??0);


                    if(!isset($net_income[$b_id])) {$net_income[$b_id] = 0;}
                    $net_income[$b_id] = ( $net_ordinary[$b_id]??0) + ($total_other_income[$b_id]??0) + ($total_other_expense[$b_id]??0);
                }
            }
            if($getRetainedEarningBegin != null){
                foreach ($getRetainedEarningBegin as $e){
                    $returnEarningBeg[$e->branch_id] = $e->bal??0;
                }
            }
            if($profit != null){
                foreach ($profit as $p){
                    $arr_profit[$p->branch_id] = $p->bal;
                }
            }
            $total_equitity = $returnEarningBeg + $net_income;
            $result1 = $total_equitity[$branch_id[0]] / $total_assets;
            //dd($result1);

            $loans = Loan::where('disbursement_status','Activated')->where('branch_id',$branch_id[0])->get();
            $one_month = [];
            $two_month = [];
            $three_month = [];
            $over_three_month = [];
            foreach($loans as $loan){
                $row = LoanCalculate::select('date_s')
                ->where('disbursement_id', $loan->id)
                    ->where('payment_status','pending')
                ->where('date_p', NULL)
                ->first();

                $over_due = Carbon::parse($row->date_s)->diffInDays();
                if($over_due > 0 && $over_due <= 30){
                    array_push($one_month,$loan->id);
                }
                elseif($over_due > 30 && $over_due <= 60){
                    array_push($two_month,$loan->id);
                }
                elseif($over_due > 60 && $over_due <= 90){
                    array_push($three_month,$loan->id);
                }
                elseif($over_due > 90){
                    array_push($over_three_month,$loan->id);
                }
            }

            $loan_release_total_one =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                    ->whereIn('id',$one_month)
                                                    ->sum('loan_amount');

            $principal_collection_total_one = \App\Models\LoanPayment::whereIn('disbursement_id',$one_month)->sum('principle');
            $total_interest_one= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                ->whereIn('disbursement_id',$one_month)
                                ->sum('interest_s');
            $interest_collection_one = \App\Models\PaymentHistory::whereIn('loan_id',$one_month)
                                                                ->sum('interest_p');
            $total_outstanding_one = ($loan_release_total_one - $principal_collection_total_one);

            $loan_release_total_two =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('id',$two_month)
                                                        ->sum('loan_amount');

            $principal_collection_total_two = \App\Models\LoanPayment::whereIn('disbursement_id',$two_month)->sum('principle');
            $total_interest_two= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                                        ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('disbursement_id',$two_month)
                                                        ->sum('interest_s');
            $interest_collection_two = \App\Models\PaymentHistory::whereIn('loan_id',$two_month)
                                    ->sum('interest_p');
            $total_outstanding_two = ($loan_release_total_two - $principal_collection_total_two);

            $loan_release_total_three =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('id',$three_month)
                                                        ->sum('loan_amount');

            $principal_collection_total_three = \App\Models\LoanPayment::whereIn('disbursement_id',$three_month)->sum('principle');
            $total_interest_three= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                                        ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('disbursement_id',$three_month)
                                                        ->sum('interest_s');
            $interest_collection_three = \App\Models\PaymentHistory::whereIn('loan_id',$three_month)
                                    ->sum('interest_p');
            $total_outstanding_three = ($loan_release_total_three - $principal_collection_total_three);
            $loan_release_total_four =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('id',$over_three_month)
                                                        ->sum('loan_amount');

            $principal_collection_total_four = \App\Models\LoanPayment::whereIn('disbursement_id',$over_three_month)->sum('principle');
            $total_interest_four = \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                                                        ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                                                        ->whereIn('disbursement_id',$over_three_month)
                                                        ->sum('interest_s');
            $interest_collection_four = \App\Models\PaymentHistory::whereIn('loan_id',$over_three_month)
                                    ->sum('interest_p');
            $total_outstanding_four = ($loan_release_total_three - $principal_collection_total_three);
            //dd($total_outstanding_one);
            $average_total_asset = $total_assets * 3;
            $average_total_equity = $total_equitity[$branch_id[0]] * 3;
            $earnings_1 = $net_income[$branch_id[0]] / $average_total_asset;
            $earnings_2 = $net_income[$branch_id[0]] / $average_total_equity;
            $acc_1 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53',$branch_id,$start_date,$end_date);
    $acc_2 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54',$branch_id,$start_date,$end_date);
    $acc_3 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55',$branch_id,$start_date,$end_date);
    $acc_4 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-57',$branch_id,$start_date,$end_date);
    $acc_5 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-58',$branch_id,$start_date,$end_date);
    $acc_6 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-61',$branch_id,$start_date,$end_date);
    $acc_7 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-62',$branch_id,$start_date,$end_date);
    $acc_8 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-63',$branch_id,$start_date,$end_date);
    $acc_9 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-66',$branch_id,$start_date,$end_date);
    $acc_10 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-68',$branch_id,$start_date,$end_date);
    $acc_11 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-92',$branch_id,$start_date,$end_date);
    $pl6_31 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31',$branch_id,$start_date,$end_date);

    $all_amount = [];
    if ($acc_1->isNotEmpty()) {
        foreach($acc_1 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_2->isNotEmpty()) {
        foreach($acc_2 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_3->isNotEmpty()) {
        foreach($acc_3 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_4->isNotEmpty()) {
        foreach($acc_4 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_5->isNotEmpty()) {
        foreach($acc_5 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_6->isNotEmpty()) {
        foreach($acc_6 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_7->isNotEmpty()) {
        foreach($acc_7 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_8->isNotEmpty()) {
        foreach($acc_8 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_9->isNotEmpty()) {
        foreach($acc_9 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_10->isNotEmpty()) {
        foreach($acc_10 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if ($acc_11->isNotEmpty()) {
        foreach($acc_11 as $bal){
            array_push($all_amount,$bal->amt);
        }
    }
    if($pl6_31->isNotEmpty()){
        $amount = 0;
        foreach($pl6_31 as $bal){
            $amount += $bal->amt;
        }
    }
    $amount_all = array_sum($all_amount);
    //dd($amount_all,$amount);
    $expense = $amount_all + $amount[$branch_id[0]];
    $earning_3 = $expense / $gross_profit[$branch_id[0]];
        //dd($gross_profit);
        return view('partials.reports.account-external.prudential-indicator',['result1'=>$result1,'total_outstanding'=>$total_outstanding,
                                                                              'total_outstanding_one'=>$total_outstanding_one,'total_outstanding_two'=>$total_outstanding_two,
                                                                              'total_outstanding_three'=>$total_outstanding_three,'total_outstanding_four'=>$total_outstanding_four,
                                                                              'earnings_1'=>$earnings_1,'earnings_2'=>$earnings_2,'earning_3'=>$earning_3,'start_date'=>$start_date,'end_date'=>$end_date,'branch_id'=>$branch_id]);
    }


    public function balanceSheetMKT(Request $request){

//        dd($request->all());
        $start_date = $request->start_date;

        $month = $request->month;
        $branch_id = $request->branch_id;

        /*$getAccountBalAll = ReportSRD::getAccountBalAll($acc_chart_id,$end_date,null,
            [10,12,14,16,18,20,22,24,26,30]);

        $getRetainedEarningBegin = ReportSRD::getRetainedEarning($start_date,null,true);
        $profit = ReportSRD::getRetainedEarning($start_date,$end_date,false);



        $bals = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                $bals[$r->section_id][$r->external_acc_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
            }
        }*/

        return view('partials.reports.account-external.balance-sheet-mkt',[
            'start_date'=>$start_date,'branch_id'=>$branch_id
        ]);

    }

    public function staff_information(request $request){
        return view('partials.reports.account-external.staff-information');
    }

    public function donor(request $request){
        return view('partials.reports.account-external.donor-lender-information');
    }

    public function frd_report(request $request){
        $branch_id = $request->branch_id??0;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        return view('partials.reports.account-external.frd-report',['branch_id'=>$branch_id,'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function monthly_progress(request $request){
        $branch_id = $request->branch_id??2;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $capital = \App\Models\ReportSRD::getAccountBalAllQuicken('4-01-100',$branch_id,$start_date,$end_date);
        foreach($capital as $bal){
            $capital = $bal->amt;
        }
        $clients = Client::where('register_date','>=',$start_date)->where('register_date','<=',$end_date)->count();
        $loans = Loan::where('status_note_date_activated','>=',$start_date)->where('status_note_date_activated','<=',$end_date)->count();
        $deposits = \App\Models\DepositLoanItem::where('loan_deposit_date','>=',$start_date)->where('loan_deposit_date','<=',$end_date)->count();
        $disbursement_amount = Loan::where('status_note_date_activated','>=',$start_date)->where('status_note_date_activated','<=',$end_date)->sum('loan_amount');
        $loan_collected = LoanPayment::where('payment_date','>=',$start_date)->where('payment_date','<=',$end_date)->sum('total_payment');
        $loan_release_total =  \App\Models\Loan::whereIn('disbursement_status',['Activated','Closed','Written-Off'])    
                                                ->where('status_note_date_activated','<=',$end_date)
                                                ->sum('loan_amount');
        $principal_collection_total = \App\Models\LoanPayment::where('payment_date','<=',$end_date)->sum('principle');
        $total_interest= \App\Models\LoanCalculate::join(getLoanTable(),getLoanTable().'.id','=',getLoanCalculateTable().'.disbursement_id')
                            ->whereIn(getLoanTable().'.disbursement_status',['Activated','Closed','Written-Off'])
                            ->where('status_note_date_activated','<=',$end_date)
                            ->sum('interest_s');
        $interest_collection = \App\Models\PaymentHistory::where('payment_date','<=',$end_date)->where('payment_date','>=',$start_date)->sum('interest_p');
        $total_outstanding = ($loan_release_total - $principal_collection_total);
        $compulsory =\App\Models\CompulsorySavingActive::join(getLoanTable(),getLoanTable().'.id','=',getLoanCompulsoryTable().'.loan_id')
                                                                ->where(getLoanTable().'.status_note_date_activated','>=',$start_date)
                                                                ->where(getLoanTable().'.status_note_date_activated','<=',$end_date)
                                                                ->where(getLoanCompulsoryTable().'.compulsory_status','=','Active')
                                                                ->sum(getLoanCompulsoryTable().'.principles');
        $withdraw =\App\Models\CompulsorySavingTransaction::where('tran_date','>=',$start_date)
                                                                ->where('tran_date','<=',$end_date)
                                                                ->where('train_type','=','withdraw')
                                                                ->sum('amount');
        $remain =  $compulsory - $withdraw;
        $interest_income = \App\Models\ReportSRD::getAccountBalAllQuicken('2-89-700',$branch_id,$start_date,$end_date);
        $other_income = \App\Models\ReportSRD::getAccountBalAllQuicken('5-71-100',$branch_id,$start_date,$end_date);
        $total_income = $total_income??0;
        foreach($interest_income as $bal){
            $total_income += $bal->amt;
            $interest_income = $bal->amt; 
        }
        foreach($other_income as $bal){
            $total_income += $bal->amt; 
            $other_income = $bal->amt;
        }
        //dd($interest_income);
        if($interest_income){
            $interest_income = 0;
        }
        if($other_income){
            $other_income = 0;
        }
        if($capital){
            $capital = 0;
        }
       
        $other_income = $other_income??0;
        $compulsory_interest = \App\Models\CompulsorySavingTransaction::where('tran_date','>=',$start_date)
                                ->where('tran_date','<=',$end_date)
                                ->where('train_type','=','accrue-interest')
                                ->sum('amount');
        $pl_6_31 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-31',$branch_id,$start_date,$end_date);
        $pl_6_53 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-53',$branch_id,$start_date,$end_date);
        $pl_6_54 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-54',$branch_id,$start_date,$end_date);
        $pl_6_55 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-55',$branch_id,$start_date,$end_date);
        $pl_6_57 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-57',$branch_id,$start_date,$end_date);  
        $pl_6_58 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-58',$branch_id,$start_date,$end_date); 
        $pl_6_61 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-61',$branch_id,$start_date,$end_date);
        $pl_6_62 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-62',$branch_id,$start_date,$end_date); 
        $pl_6_63 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-63',$branch_id,$start_date,$end_date);  
        $pl_6_66 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-66',$branch_id,$start_date,$end_date);
        $pl_6_68 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-68',$branch_id,$start_date,$end_date);  
        $pl_6_92 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-92',$branch_id,$start_date,$end_date);
        $pl_6_42 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-42',$branch_id,$start_date,$end_date);
        $pl_6_71 = \App\Models\ReportSRD::getAccountBalAllQuicken('6-71',$branch_id,$start_date,$end_date);
        $other_expense =  $other_expense??0;
        foreach($pl_6_31 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_53 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_54 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_55 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_57 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_58 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_61 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_62 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_63 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_66 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_68 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_92 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_42 as $bal){
            $other_expense += $bal->amt;
        }
        foreach($pl_6_71 as $bal){
            $other_expense += $bal->amt;
        }

        $profit = $total_income - ($compulsory_interest + $other_expense);
        return view('partials.reports.account-external.monthly-progress',['capital'=>$capital,'clients'=>$clients,'loans'=>$loans,'deposits'=>$deposits,
                                                                          'disbursement_amount'=>$disbursement_amount,'loan_collected'=>$loan_collected,
                                                                          'loan_release_total'=>$loan_release_total,'compulsory'=>$compulsory,'withdraw'=>$withdraw,'remain'=>$remain,
                                                                          'interest_income'=>$interest_income,'other_income'=>$other_income,'total_income'=>$total_income,
                                                                          'compulsory_interest'=>$compulsory_interest,'other_expense'=>$other_expense,'profit'=>$profit,
                                                                          'start_date'=>$start_date,'end_date'=>$end_date]);
    }

    public function female_client(request $request){
        $start_date =$request->start_date;
        $end_date =$request->end_date;
        $loans = Loan::where('status_note_date_activated','>=',$start_date)->where('status_note_date_activated','<=',$end_date)->get();
        //dd($loans);
        $male_loans = 0;
        $female_loans = 0;
        
        foreach($loans as $loan){
            $gender = Client::find($loan->client_id);
            $outstanding = $loan->principle_receivable + $loan->interest_receivable;
            $male_amount = $male_amount??0;
            $female_amount = $female_amount??0;
            $total_outstanding_male = $total_outstanding_male??0;
            $total_outstanding_female = $total_outstanding_female??0;
            if($gender->gender == 'Male'){
               
               
                $male_amount += $loan->loan_amount;
                $total_outstanding_male += $outstanding;
                ++$male_loans;
            }
            elseif($gender->gender == 'Female'){
                
                
                $female_amount += $loan->loan_amount;
                $total_outstanding_female += $outstanding;
                ++$female_loans;
            }
        }
        
        return view('partials.reports.account-external.female-clients',['male_amount'=>$male_amount,'total_outstanding_male'=>$total_outstanding_male,'male_loans'=>$male_loans,
                                                                        'female_amount'=>$female_amount,'total_outstanding_female'=>$total_outstanding_female,'female_loans'=>$female_loans]);
    }

    public function transactionDetailByAccount(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        $getTransDetails = ReportSRD::getTransactionDetail($acc_chart_id,$start_date,$end_date);


        return view('partials.reports.account-external.transaction-detail-by-acc',[
            'start_date'=>$start_date,'end_date'=>$end_date,
            'rows'=> $getTransDetails
        ]);

    }
    public function profitLossDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        $getTransDetails = ReportSRD::getTransactionDetail($acc_chart_id,$start_date,$end_date,[40,50,60,70,80]);

        $bals = [];
        if($getTransDetails != null){
            foreach ($getTransDetails as $r){
                $bals[$r->section_id][$r->acc_chart_id][$r->id] = $r;
            }

        }

        return view('partials.reports.account-external.profit-loss-detail',[
            'start_date'=>$start_date,'end_date'=>$end_date,
            'bals'=> $bals
        ]);

    }
    public function profitLossByJob(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        $getAccountBalAll = ReportSRD::getAccountBalAllByJob($acc_chart_id,$start_date,$end_date,[40,50,60,70,80]);

        //dd($getAccountBalAll);
        $bals = [];
        $jobs = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                $bals[$r->section_id][$r->acc_chart_id][$r->job_id>0?$r->job_id:'NA'] = ($r->t_dr??0) - ($r->t_cr??0) ;
                $jobs[$r->job_id>0?$r->job_id:'NA'] = $r->job_id;
            }

        }

        //dd($jobs,$bals);
        return view('partials.reports.account-external.profit-loss-by-job',[
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,'jobs'=>$jobs]);

    }
    public function profitLossByClass(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        $getAccountBalAll = ReportSRD::getAccountBalAllByClass($acc_chart_id,$start_date,$end_date,[40,50,60,70,80]);

        //dd($getAccountBalAll);
        $bals = [];
        $jobs = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                $bals[$r->section_id][$r->acc_chart_id][$r->class_id>0?$r->class_id:'NA'] = ($r->t_dr??0) - ($r->t_cr??0) ;
                $jobs[$r->class_id>0?$r->class_id:'NA'] = $r->class_id;
            }

        }

        //dd($jobs,$bals);
        return view('partials.reports.account-external.profit-loss-by-class',[
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,'jobs'=>$jobs]);

    }
    public function cashStatement(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        $cash_statement = ReportSRD::getCashStatement($start_date,$end_date,false);

        $type = [];
        $bal_type = [];

        if($cash_statement != null){
            foreach ($cash_statement as $r){
                $type[$r->tran_type??'N/A'] = $r->tran_type??'N/A'  ;
                $bal_type[$r->external_acc_id??0][$r->tran_type??'N/A'] = $r->bal??0  ;
            }
        }

        //dd($bal_type);

        return view('partials.reports.account-external.cash-statement',[
            'start_date'=>$start_date,'end_date'=>$end_date,'type' => $type,'bal_type'=>$bal_type]);

    }

    public function cashStatementDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        $cash_statement = ReportSRD::getCashStatementDetail($start_date,$end_date,false);

        $bals = [];
        if($cash_statement != null){
            foreach ($cash_statement as $r){
                $bals[$r->section_id][$r->acc_chart_id][$r->id] = $r;
            }

        }
        return view('partials.reports.account-external.cash-statement-detail',[
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals]);

    }




    public function portfolioOutreach(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        return view('partials.reports.account-external.portfolio-outreach',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }
    public function tenLargestLoan(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.ten-largest-loan',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }
    public function tenLargestDeposit(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        return view('partials.reports.account-external.ten-largest-deposit',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }
    public function marketConduct(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        return view('partials.reports.account-external.market-conduct',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }



    /*
     *
     * Route::get('/external-monthly-progress', 'Admin\ReportSRDCrudController@monthlyProgress');
Route::get('/external-saving', 'Admin\ReportSRDCrudController@tenLargestLoan');
Route::get('/external-donor-lender-information', 'Admin\ReportSRDCrudController@saving');
Route::get('/external-staff-information', 'Admin\ReportSRDCrudController@staffInformation');
Route::get('/external-loan-type', 'Admin\ReportSRDCrudController@loanType');
Route::get('/external-section-wise', 'Admin\ReportSRDCrudController@sectionWise');
Route::get('/external-section-wise-outstanding', 'Admin\ReportSRDCrudController@sectionWiseOutstanding');
     */
    public function prudentialIndicator(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.prudential-indicator',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }

    public function monthlyProgress(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.monthly-progress',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }
/*
 * Route::get('/external-monthly-progress', 'Admin\ReportSRDCrudController@monthlyProgress');
Route::get('/external-saving', 'Admin\ReportSRDCrudController@saving');
Route::get('/external-donor-lender-information', 'Admin\ReportSRDCrudController@donorLenderInformation');
Route::get('/external-staff-information', 'Admin\ReportSRDCrudController@staffInformation');
Route::get('/external-loan-type', 'Admin\ReportSRDCrudController@loanType');
Route::get('/external-section-wise', 'Admin\ReportSRDCrudController@sectionWise');
Route::get('/external-section-wise-outstanding', 'Admin\ReportSRDCrudController@sectionWiseOutstanding');
 */

    public function saving(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.saving',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }

    public function donorLenderInformation(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.donor-lender-information',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }

    public function staffInformation(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.staff-information',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }

    public function loanType(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.loan-type',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }

    public function sectionWise(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.section-wise',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }

    public function sectionWiseOutstanding(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        return view('partials.reports.account-external.section-wise-outstanding',[
            'start_date'=>$start_date,'end_date'=>$end_date]);

    }





}
