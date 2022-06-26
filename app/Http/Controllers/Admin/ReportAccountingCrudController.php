<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\GeneralJournalDetail;
use App\Models\ReportAccounting;
use App\Models\JournalProfit;
use Maatwebsite\Excel\Facades\Excel;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Exports\ExportGeneralJournal;
use App\Exports\ExportCashBookDetail;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportAccountingRequest as StoreRequest;
use App\Http\Requests\ReportAccountingRequest as UpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class ReportAccountingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportAccountingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportAccounting');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-accounting');
        $this->crud->setEntityNameStrings('report-accounting', 'report_accounting');

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

        $this->crud->setCreateView('custom.create_report_account');


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
        return redirect('admin/report-accounting/create');
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

    public function cash_transaction(Request $request){
        /*$start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;
        $show_zero = $request->show_zero;
        $branch_id=$request->branch_id;
        //dd($start_date);
        $cash= GeneralJournalDetail::selectRaw('j_detail_date,dr,cr,journal_id,acc_chart_id,description,branch_id')
        ->where('section_id', 10)
        ->where(function ($query) use ($acc_chart_id){
            if ($acc_chart_id != null) {
                if (is_array($acc_chart_id)) {
                    if (count($acc_chart_id) > 0) {
                        $query->whereIn('acc_chart_id', $acc_chart_id);
                    }
                }
            }
        })

        ->where(function ($q) use ($branch_id){
            if($branch_id != null || is_array($branch_id)){
                return $q->whereIn('branch_id',$branch_id);
            }
        })
            ->where(function ($q) use ($start_date,$end_date){
                if ($start_date && $end_date){
                    $q->whereBetween('j_detail_date',[$start_date,$end_date]);
                }
            })->get();

        return view('partials.reports.account.cash_transaction',['cash'=>$cash]);*/
//        return view('partials.reports.account.cash_transaction');

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $branch_id = $request->branch_id;
        
        $arr_acc = [];
        $arr_begin = [];
        $arr_leger = [];
        
        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        if($branch_id != null) {
            $beg_leger = ReportAccounting::getBeginCashTransaction($start_date, $end_date, $branch_id, $acc_chart_id);
            $gen_leger = ReportAccounting::getCashTransaction($start_date, $end_date, $branch_id, $acc_chart_id);
            $getAccountBalAll = ReportAccounting::getAccountBalAllB($branch_id,$acc_chart_id,$start_date,$end_date);
            
            // if($beg_leger != null){
            //     foreach ($beg_leger as $b) {
            //         $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
            //         $arr_begin[$b->acc_chart_id] = $b->amt??0;
            //     }
            // }

            if($gen_leger != null){

                foreach ($gen_leger as $k=>$b) {
                    $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                    $arr_leger[$b->acc_chart_id][$k] = $b;
                }
            }



        }else{
            return 'Please select branch';
        }

        return view('partials.reports.account.cash_transaction',['start_date' => $start_date, 'end_date' => $end_date,
            'arr_acc'=>$arr_acc,'arr_begin'=>$arr_begin, 'all_balances' => $getAccountBalAll,'arr_leger'=>$arr_leger, 'acc_chart_id' => $acc_chart_id,'branches' =>$branch_id]);
    }

    public function accountList(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $show_zero = $request->show_zero;
        $branch_id = $request->branch_id;

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        $rows = AccountChart::where(function ($query) use ($acc_chart_id){
            if($acc_chart_id != null){
                if(is_array($acc_chart_id)){
                    if(count($acc_chart_id)>0){
                        $query->whereIn('id',$acc_chart_id);
                    }
                }
            }

        })->get();

        $getAccountBalAll = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date,$end_date,[],false,$branch_id);
        $bals = [];
        $branches= [];
        if($getAccountBalAll != null){
            
            foreach ($getAccountBalAll as $r){
                $bals[$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
//                $bals[$r->acc_chart_id] = ($r->t_dr??0) - ($r->t_cr??0) ;

                $branches[$r->branch_id] = $r->branch_id;
            }

        }
        
        return view('partials.reports.account.account-list',['rows'=>$rows,
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
            'branches' => $branches
            ]);

    }

    public function trialBalance(Request $request){
        $branch_id = $request->branch_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        $rows = AccountChart::where(function ($query) use ($acc_chart_id){
            if($acc_chart_id != null){
                if(is_array($acc_chart_id)){
                    if(count($acc_chart_id)>0){
                        $query->whereIn('id',$acc_chart_id);
                    }
                }
            }
        })->get();

        $getAccountBalAll = ReportAccounting::getAccountBalAllB($branch_id,$acc_chart_id,$start_date,$end_date);

        $bals = [];
        $branches = [];
        //dd($getAccountBalAll);
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                $bals[$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
                $branches[$r->branch_id] = $r->branch_id;
            }

        }
        //dd($rows);
        return view('partials.reports.account.trial-balance',['rows'=>$rows,
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,'branches'=>$branches]);

    }

    public function trialBalanceMoeyan(Request $request){
        $searchBranch = $request->branch_id;
        $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $month = $request->month;
        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        $rows = AccountChart::where(function ($query) use ($acc_chart_id){
            if($acc_chart_id != null){
                if(is_array($acc_chart_id)){
                    if(count($acc_chart_id)>0){
                        $query->whereIn('id',$acc_chart_id);
                    }
                }
            }
        })->get();
        
        if($searchBranch == null){
            $searchBranch = \App\Models\Branch::pluck('id')->toArray();
        }

        $getAccountBalAll = ReportAccounting::getAccountBalAllB($searchBranch,$acc_chart_id,$start_date,$end_date);

        $bals = [];
        $branches = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                if($r->tran_type == "payment"){
                    $bals[$r->acc_chart_id][$r->branch_id] = - ($r->t_cr??0);
                }else{
                    $bals[$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0);
                }
                $branches[$r->branch_id] = $r->branch_id;
            }
        }
        
        return view('partials.reports.account.trial-balance',['rows'=>$rows,
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,'branches'=>$branches]);
    }

    public function profitLoss(Request $request){
        //dd($request);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $branch_id = $request->branch_id;

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        
        $getAccountBalAll = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date,$end_date,[30,40,50,60,70,80],false,$branch_id);
        
        $bals = [];
        $branches = [];
        
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                if($r->tran_type == "payment"){
                    $bals[$r->section_id][$r->acc_chart_id][$r->branch_id] = -($r->t_cr??0);
                }else{ 
                    $bals[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) -($r->t_cr??0);
                }

                $branches[$r->branch_id] = $r->branch_id;
            }
        }

        //dd($bals);
        if(companyReportPart() == 'company.moeyan'){
            return view('partials.reports.account.profit-loss',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'branches' => $branches
            ]);
        }
        elseif(companyReportPart() == 'company.quicken'){
            return view('partials.reports.account.profit-loss-quicken',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'branches' => $branches
            ]);
        }
        else{
            return view('partials.reports.account.profit-loss-mkt',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'branches' => $branches
            ]);
        }

    }

    public function balanceSheet(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $branch_id = $request->branch_id;
        
        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        $time = explode('-',$start_date);
    //dd($time);
        if($time[1] < 10){
            $year = $time[0] - 1;
            $year_two = $time[0] - 2;
        }
        else{
            $year = $time[0];
            $year_two = $time[0]-1;
        }
        //$retain_date = "$year-10-01";
        $start_date_report = "$year-10-01";
        $end_date_retain = "$year-09-31";
        $start_date_retain = "$year_two-10-01";
        $getAccountBalAll = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date_report,$end_date,null,
            [10,12,14,16,18,20,22,24,26,30],false,$branch_id);
        if(companyReportPart() == 'company.quicken'){
            $start_date_earn = "2009-10-01";
            $date=date_create($start_date);
            $end_date_earn = date_sub($date,date_interval_create_from_date_string("1 days"));
            //dd($end_date_earn);
            $getAccountBalRetain = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date_earn,$end_date_earn,[40,50,60,70,80],false,$branch_id);
            //dd($getAccountBalRetain);
            $bals_profit = [];
            $branches = [];
            
            if($getAccountBalRetain != null){
                foreach ($getAccountBalRetain as $r){
                    $bals_profit[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
                    $bals_profit[$r->section_id][$r->acc_chart_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
    
                    $branches[$r->branch_id] = $r->branch_id;
                    
                }
            }
            //dd($bals_profit);
            foreach($bals_profit as $bal_profits){
                //dd($bal_profits);
                $values = array_values($bal_profits);
                foreach($values as $value){
                    $retain = $retain??0;
                    $retain += $value;
                }
                
            }
            //dd($retain);
            $getRetainedEarningBegin = ReportAccounting::getRetainedEarning($start_date_earn,$end_date_earn,true,$branch_id);
            $profit = ReportAccounting::getRetainedEarning($start_date,$end_date,false,$branch_id);
        }
        else{
            $getRetainedEarningBegin = ReportAccounting::getRetainedEarning($start_date,null,true,$branch_id);
            $profit = ReportAccounting::getRetainedEarning($start_date,$end_date,false,$branch_id);
        }
        
        //dd($getRetainedEarningBegin);
        $bals = [];
        if(companyReportPart() == 'company.mkt'){
            $bals_dr = [];
            if($getAccountBalAll != null){
                foreach ($getAccountBalAll as $r){
                    /*if(isset($bals[$r->section_id][$r->acc_chart_id]))
                    $bals[$r->section_id][$r->acc_chart_id] = (($r->t_dr??0) - ($r->t_cr??0));
                    else $bals[$r->section_id][$r->acc_chart_id] = 0;*/
                    $bals_dr[$r->section_id][$r->acc_chart_id][$r->branch_id] = (($r->t_dr??0) - ($r->t_cr??0));
                    $branchs[$r->branch_id] = $r->branch_id;
                }
            }
        }
        $branchs = [];
        if($getAccountBalAll != null){
            foreach ($getAccountBalAll as $r){
                /*if(isset($bals[$r->section_id][$r->acc_chart_id]))
                $bals[$r->section_id][$r->acc_chart_id] = (($r->t_dr??0) - ($r->t_cr??0));
                else $bals[$r->section_id][$r->acc_chart_id] = 0;*/
                $bals[$r->section_id][$r->acc_chart_id][$r->branch_id] = (($r->t_dr??0) - ($r->t_cr??0));
                $branchs[$r->branch_id] = $r->branch_id;
            }
        }
        //dd($branchs);
        if(companyReportPart() == 'company.moeyan' || companyReportPart() == 'company.quicken'){
            $getAccountBalBranch = ReportAccounting::getAccountBalAllByLoanBranch($acc_chart_id,$start_date,$end_date,[30,40,50,60,70,80],false,$branch_id);
            $getAccountBalAll1 = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date,$end_date,[30,40,50,60,70,80],false,$branch_id);
        $bals1 = [];
        $branches1 = [];
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

        $net_income = [];
        // $count = Branch::all();
        $total_expense = [];
        $net_ordinary = [];
        $total_income = [];
        $total_other_income = [];
        $total_other_expense = [];
        $gross_profit = [];
        $total_cogs = [];

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
        }

        $returnEarningBeg = [];
        $arr_profit = [];

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
        if(companyReportPart() == 'company.mkt'){
            if($end_date >= "2019-10-01"){
                $year = explode("-",$start_date);
                if($year[1] > 9){
                    $now = $year[0];
                }
                else{
                    $now = $year[0] - 1;
                }
                
                $start_date = $now."-10-01";

                // $start_date = $request->start_date;
                //dd($start_date);
                $end_date = $request->end_date;
                $month = $request->month;
                $branch_id = $request->branch_id;
        
                $acc_chart_id = [];
                $acc_chart_id = $this->getAccounts($request);
            
                $getAccountBalAll = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date,$end_date,[40,50,60,70,80],false,$branch_id);
                $bals_profit = [];
                $branches = [];
                
                if($getAccountBalAll != null){
                    foreach ($getAccountBalAll as $r){
                        $bals_profit[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
                        $bals_profit[$r->section_id][$r->acc_chart_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
        
                        $branches[$r->branch_id] = $r->branch_id;
                        
                    }
                }
                //dd($bals_profit);
                foreach($bals_profit as $bal_profits){
                    //dd($bal_profits);
                    $values = array_values($bal_profits);
                    foreach($values as $value){
                        $net_income = $net_income??0;
                        $net_income += $value;
                    }
                 
              }
              if($year[1] > 9){
                    $previous = $year[0];
                }
              else{
                    $previous = $year[0] - 1;
               }
             
              $start_date_f = "2019-09-01"; 
              //dd($start_date_f); 
              $end_date_f = $previous."-09-30";
              $month_f = $request->month;
              $branch_id_f = $request->branch_id;
      
              $acc_chart_id_f = [];
              $acc_chart_id_f = $this->getAccounts($request);
             
              $getAccountBalAll_f = ReportAccounting::getAccountBalAll($acc_chart_id_f,$start_date_f,$end_date_f,[40,50,60,70,80],false,$branch_id_f);
                $bals_profit_f = [];
                $branches_f = [];
                
                if($getAccountBalAll_f != null){
                    foreach ($getAccountBalAll_f as $r){
                        $bals_profit_f[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
                        $bals_profit_f[$r->section_id][$r->acc_chart_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
        
                        $branches_f[$r->branch_id] = $r->branch_id;
                        
                    }
                }
                //dd($bals_profit);
                foreach($bals_profit_f as $bal_profits_f){
                    //dd($bal_profits);
                    $values_f = array_values($bal_profits_f);
                    foreach($values_f as $value_f){
                        $net_income_f = $net_income_f??0;
                        $net_income_f += $value_f;
                    }
                   
                }
              
            }
            else{
                $year = explode("-",$start_date);
                $now = $year[0] - 1;
                $start_date =  "2019-09-01";
                // $start_date = $request->start_date;
                //dd($start_date);
                $end_date = $request->end_date;
                $month = $request->month;
                $branch_id = $request->branch_id;
        
                $acc_chart_id = [];
                $acc_chart_id = $this->getAccounts($request);
            
                $getAccountBalAll = ReportAccounting::getAccountBalAll($acc_chart_id,$start_date,$end_date,[40,50,60,70,80],false,$branch_id);
                $bals_profit = [];
                $branches = [];
                
                if($getAccountBalAll != null){
                    foreach ($getAccountBalAll as $r){
                        $bals_profit[$r->section_id][$r->acc_chart_id][$r->branch_id] = ($r->t_dr??0) - ($r->t_cr??0);
                        $bals_profit[$r->section_id][$r->acc_chart_id] = ($r->t_dr??0) - ($r->t_cr??0) ;
        
                        $branches[$r->branch_id] = $r->branch_id;
                        
                    }
                }
                //dd($bals_profit);
                foreach($bals_profit as $bal_profits){
                    
                    $values = array_values($bal_profits);
                    foreach($values as $value){
                        
                        $net_income = $net_income??0;
                        // if($bals_profit[60][445] == $value){
                        //     $net_income -= $value;
                        // }
                        // else{
                            $net_income += $value;
                        // }
                        
                    }
                 
              }
              
              //dd($net_income);
            }
            
              
            }
    
        $net_income = $net_income??0;
        $net_income_f = $net_income_f??0;
        // if($end_date <= "2019-08-31"){
        //     $net_income_f = ;
        // }
        //dd($end_date);
        //dd($net_income,$returnEarningBeg);
        if(companyReportPart() == 'company.moeyan'){
            return view('partials.reports.account.balance-sheet',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'retainedEarningBegin'=> $returnEarningBeg, 'branchs'=>$branches1, 'net_income' => $net_income
            ]);
    
        }
        elseif(companyReportPart() == 'company.quicken'){
            return view('partials.reports.account.balance-sheet-quicken',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'retainedEarningBegin'=> $returnEarningBeg, 'branches1'=>$branches1, 'net_income' => $net_income, 'retain' => $retain
            ]);
    
        }
        elseif(companyReportPart() == 'company.mkt'){
            return view('partials.reports.account.balance-sheet-mkt',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'retainedEarningBegin'=> $returnEarningBeg, 'branchs'=>$branchs,'bals_dr'=>$bals_dr,'net_income' => $net_income,'net_income_f'=>$net_income_f
            ]);
    
        }
        else{
            return view('partials.reports.account.balance-sheet',[
                'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,
                'retainedEarningBegin'=> $returnEarningBeg, 'branchs'=>$branchs
            ]);
    
        }
        
    }

    public function transactionDetailByAccount(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        $getTransDetails = ReportAccounting::getTransactionDetail($acc_chart_id,$start_date,$end_date);


        return view('partials.reports.account.transaction-detail-by-acc',[
            'start_date'=>$start_date,'end_date'=>$end_date,
            'rows'=> $getTransDetails
        ]);

    }
    public function profitLossDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;

        $getTransDetails = ReportAccounting::getTransactionDetail($acc_chart_id,$start_date,$end_date,[40,50,60,70,80]);

        $bals = [];
        if($getTransDetails != null){
            foreach ($getTransDetails as $r){
                $bals[$r->section_id][$r->acc_chart_id][$r->id] = $r;
            }

        }

        return view('partials.reports.account.profit-loss-detail',[
            'start_date'=>$start_date,'end_date'=>$end_date,
            'bals'=> $bals
        ]);

    }
    public function profitLossByJob(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        $getAccountBalAll = ReportAccounting::getAccountBalAllByJob($acc_chart_id,$start_date,$end_date,[40,50,60,70,80]);

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
        return view('partials.reports.account.profit-loss-by-job',[
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,'jobs'=>$jobs]);

    }
    public function profitLossByClass(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;


        $getAccountBalAll = ReportAccounting::getAccountBalAllByClass($acc_chart_id,$start_date,$end_date,[40,50,60,70,80]);

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
        return view('partials.reports.account.profit-loss-by-class',[
            'start_date'=>$start_date,'end_date'=>$end_date,'bals'=>$bals,'jobs'=>$jobs]);

    }
    public function cashStatement(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;
        $branch_id = $request->branch_id;
        //dd("hello world");
        //dd($branch_id);

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        if($branch_id != null) {
            $cash_statement = ReportAccounting::getCashStatement($start_date, $end_date, false, $branch_id, $acc_chart_id);
            $type = [];
            $bal_type = [];
            $branch = [];
            if ($cash_statement != null) {
                foreach ($cash_statement as $r) {
                    $type[$r->tran_type ?? 'N/A'] = $r->tran_type ?? 'N/A';
                    //$branch[$r->branch_id??'N/A'] = $r->branch_id??'N/A'  ;
                    $bal_type[$r->branch_id][$r->acc_chart_id ?? 0][$r->tran_type ?? 'N/A'] = $r->bal ?? 0;
                }
            }
            return view('partials.reports.account.cash-statement',[
                'start_date'=>$start_date,'end_date'=>$end_date,'type' => $type,'bal_type'=>$bal_type,'branches'=>$branch_id,
                'acc_chart_id'=>$acc_chart_id
                ]);
        }else{
            return '';
        }
        //return  ($bal_type);
    }

    public function cashStatementMoeyan(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;
        $branch_id = $request->branch_id;
        //dd("hello world");
        //dd($branch_id);

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        if($branch_id != null) {
            $cash_statement = ReportAccounting::getCashStatement($start_date, $end_date, false, $branch_id, $acc_chart_id);
            $type = [];
            $bal_type = [];
            $branch = [];
            if ($cash_statement != null) {
                foreach ($cash_statement as $r) {
                    $type[$r->tran_type ?? 'N/A'] = $r->tran_type ?? 'N/A';
                    //$branch[$r->branch_id??'N/A'] = $r->branch_id??'N/A'  ;
                    $bal_type[$r->branch_id][$r->acc_chart_id ?? 0][$r->tran_type ?? 'N/A'] = $r->bal ?? 0;
                }
            }
            return view('partials.reports.account.cash-statement-moeyan',[
                'start_date'=>$start_date,'end_date'=>$end_date,'type' => $type,'bal_type'=>$bal_type,'branches'=>$branch_id,
                'acc_chart_id'=>$acc_chart_id
            ]);
        }else{
            return '';
        }
        //return  ($bal_type);
    }

    public function cashStatementDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $branch_id = $request->branch_id;

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);

        if($branch_id != null) {
            $cash_statement = ReportAccounting::getCashStatementDetail($start_date, $end_date, false, $branch_id, $acc_chart_id);
            $bals = [];
            if ($cash_statement != null) {
                foreach ($cash_statement as $r) {
                    $bals[$r->branch_id][$r->section_id][$r->acc_chart_id][$r->id] = $r;
                }

            }
            return view('partials.reports.account.cash-statement-detail', [
                'start_date' => $start_date, 'end_date' => $end_date, 'bals' => $bals,'branches'=>$branch_id,
                'acc_chart_id' => $acc_chart_id
                ]);
        }else{
            return '';
        }

    }

    public function cashStatementDetailMoeyan(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $branch_id = $request->branch_id;

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);

        if($branch_id != null) {
            $cash_statement = ReportAccounting::getCashStatementDetail($start_date, $end_date, false, $branch_id, $acc_chart_id);
            $bals = [];
            if ($cash_statement != null) {
                foreach ($cash_statement as $r) {
                    $bals[$r->branch_id][$r->section_id][$r->acc_chart_id][$r->id] = $r;
                }

            }
            return view('partials.reports.account.cash-statement-detail-moeyan', [
                'start_date' => $start_date, 'end_date' => $end_date, 'bals' => $bals,'branches'=>$branch_id,
                'acc_chart_id' => $acc_chart_id
            ]);
        }else{
            return '';
        }

    }

    public function expense_pop($id){
        $row = Expense::find($id);
        if ($row != null) {
            return view('partials.reports.account.expense-list-pop', ['row' => $row]);
        } else {
            return 'No data';
        }
    }
    public function  general_leger(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $arr_acc = [];
        $arr_begin = [];
        $arr_leger = [];
        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        $branch_id = $request->branch_id;

        $beg_leger = ReportAccounting::getBeginGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);
        if($beg_leger != null){
            foreach ($beg_leger as $b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_begin[$b->acc_chart_id] = $b->amt??0;
            }
        }
        
        if($branch_id == null){
            $branch_id = [1];
        }

        $gen_leger = ReportAccounting::getGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);

        

            if($gen_leger != null){

            foreach ($gen_leger as $k=>$b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_leger[$b->acc_chart_id][$k] = $b;
            }
        }


        // }else{
        //     return 'Please select branch';
        // }

        $view='general-leger';
        if (companyReportPart() == 'company.mkt'){
            $view='general-leger-mkt';
        }

        return view('partials.reports.account.'.$view,['start_date' => $start_date, 'end_date' => $end_date,
            'arr_acc'=>$arr_acc,'arr_begin'=>$arr_begin,'arr_leger'=>$arr_leger, 'acc_chart_id' => $acc_chart_id,'branch_id'=>$branch_id]);


    }

    public function  general_leger_moeyan(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $branch_id = $request->branch_id;

        $arr_acc = [];
        $arr_begin = [];
        $arr_leger = [];

        if($branch_id == null){
            $branch_id = [1];
        }

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);

        $beg_leger = ReportAccounting::getBeginGeneralLegerMoeyan($start_date, $end_date, $branch_id, $acc_chart_id);
        $gen_leger = ReportAccounting::getGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);
        $getAccountBalAll = ReportAccounting::getAccountBalAllB($branch_id,$acc_chart_id,$start_date,$end_date);

        if($gen_leger != null){
            
            foreach ($gen_leger as $k=>$b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_leger[$b->acc_chart_id][$k] = $b;
            }
        }

        return view('partials.reports.account.general-leger-moeyan',['start_date' => $start_date, 'end_date' => $end_date,
            'arr_acc'=>$arr_acc,'arr_begin'=>$beg_leger, 'all_balances' => $getAccountBalAll,'arr_leger'=>$arr_leger, 'acc_chart_id' => $acc_chart_id,'branch_id'=>$branch_id]);

    }

    public function getAccounts($request){
        $search_type = $request->search_type;
        if($search_type == "range"){
            $acc_chart_id = [];
            $from_acc = $request->from_acc;
            $to_acc = $request->to_acc;
            $acc_charts = AccountChart::whereBetween('code', [$from_acc, $to_acc])->get();
            foreach($acc_charts as $acc_chart){
                array_push($acc_chart_id, $acc_chart->id);
            }
            return $acc_chart_id;
        }else{
            return $request->acc_chart_id;
        }
    }

    public function cashBook(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = $request->acc_chart_id;
        $branch_id = $request->branch_id;
        //dd("hello world");
        //dd($branch_id);

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);

        if($branch_id != null) {
//            $cash_statement = ReportAccounting::getCashBook($start_date, $end_date, false, $branch_id, $acc_chart_id);
            $type = [];
            $bal_type = [];
            $branch = [];
           /* if ($cash_statement != null) {
                foreach ($cash_statement as $r) {
                    $type[$r->tran_type ?? 'N/A'] = $r->tran_type ?? 'N/A';
                    //$branch[$r->branch_id??'N/A'] = $r->branch_id??'N/A'  ;
                    $bal_type[$r->branch_id][$r->acc_chart_id ?? 0][$r->tran_type ?? 'N/A'] = $r->bal ?? 0;
                }
            }*/

//            dd($cash_statement);
            return view('partials.reports.account.cash-book',[
                'start_date'=>$start_date,'end_date'=>$end_date,'type' => $type,'bal_type'=>$bal_type,'branches'=>$branch_id,
                'acc_chart_id'=>$acc_chart_id,
//                'cash_book' => $cash_statement
            ]);
        }else{
            return '';
        }
        //return  ($bal_type);
    }


    public function cashBookDetail(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $branch_id = $request->branch_id;

        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);

        if($branch_id != null) {
            /*$cash_statement = ReportAccounting::getCashStatementDetail($start_date, $end_date, false, $branch_id, $acc_chart_id);
            $bals = [];
            if ($cash_statement != null) {
                foreach ($cash_statement as $r) {
                    $bals[$r->branch_id][$r->section_id][$r->acc_chart_id][$r->id] = $r;
                }

            }*/

//            dd($bals);
            return view('partials.reports.account.cash-book-detail', [
                'start_date' => $start_date, 'end_date' => $end_date,'branches'=>$branch_id,
                'acc_chart_id' => $acc_chart_id,
//                'cash_statement'=>$cash_statement
            ]);
        }else{
            return '';
        }

    }

    public function exportconfirm(){
        
    }
    public function export(Request $request){
        
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $arr_acc = [];
        $arr_begin = [];
        $arr_leger = [];
        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        $branch_id = $request->branch_id;
        if($branch_id[0] == null){
            $branch_id = null;
        }
        if($acc_chart_id[0]==null){
            $acc_chart_id = null;
        }
        elseif(strpos($acc_chart_id[0],',')){
            $acc_chart_id = explode(',',$acc_chart_id[0]);
        }
        $beg_leger = ReportAccounting::getBeginGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);
        if($beg_leger != null){
            foreach ($beg_leger as $b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_begin[$b->acc_chart_id] = $b->amt??0;
            }
        }
        $gen_leger = ReportAccounting::getGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);

        

            if($gen_leger != null){

            foreach ($gen_leger as $k=>$b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_leger[$b->acc_chart_id][$k] = $b;
            }
        }
        $general_leger = [$start_date,$end_date,$arr_acc,$arr_begin,$arr_leger,$acc_chart_id,$branch_id];
        
        return Excel::download(new ExportGeneralJournal($general_leger), 'general_journal.xlsx');  

    }
    public function exportmoeyan(Request $request){
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $arr_acc = [];
        $arr_begin = [];
        $arr_leger = [];
        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        $branch_id = $request->branch_id;
        if($branch_id[0] == null){
            $branch_id = [1];
        }
        elseif(strpos($branch_id[0],',')){
            $branch_id = explode(',',$branch_id[0]);
        }
        if($acc_chart_id[0]==null){
            $acc_chart_id = null;
        }
        elseif(strpos($acc_chart_id[0],',')){
            $acc_chart_id = explode(',',$acc_chart_id[0]);
        }
        $beg_leger = ReportAccounting::getBeginGeneralLegerMoeyan($start_date, $end_date, $branch_id, $acc_chart_id);
        $gen_leger = ReportAccounting::getGeneralLeger($start_date, $end_date, $branch_id, $acc_chart_id);
        $getAccountBalAll = ReportAccounting::getAccountBalAllB($branch_id,$acc_chart_id,$start_date,$end_date);
        if($gen_leger != null){
            
            foreach ($gen_leger as $k=>$b) {
                $arr_acc[$b->acc_chart_id] = $b->acc_chart_id;
                $arr_leger[$b->acc_chart_id][$k] = $b;
            }
        }
        $arr_begin = $beg_leger??null;
        $all_balances = $getAccountBalAll??null;

        $general_leger = [$start_date,$end_date,$arr_acc,$arr_begin,$arr_leger,$acc_chart_id,$branch_id,$all_balances];
        //dd($general_leger);
        return Excel::download(new ExportGeneralJournal($general_leger), 'general_journal.xlsx');  

    }
    public function cashexport(Request $request){
        
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $month = $request->month;
        $acc_chart_id = [];
        $acc_chart_id = $this->getAccounts($request);
        $branches = $request->branch_id;

        if($branches == null){
            $branches = [1];
        }

        if($branches != null) {
            $cash_book_detail = [$start_date,$end_date,$acc_chart_id,$branches];
            return Excel::download(new ExportCashBookDetail($cash_book_detail), 'cash_book_detail.xlsx'); 
        }else{
            return '';
        } 

    }

}
