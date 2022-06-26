<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\GroupLoan;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportSummaryRequest as StoreRequest;
use App\Http\Requests\ReportSummaryRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use DatePeriod;
/**
 * Class ReportSummaryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportSummaryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportSummary');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-summary');
        $this->crud->setEntityNameStrings('reportsummary', 'report_summaries');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addField([
            'name' => 'date_date_range', // a unique name for this field
            'start_name' => 'start_date', // the db column that holds the start_date
            'end_name' => 'end_date', // the db column that holds the end_date
            'label' => 'Select Date Range',
            'type' => 'date_range',
            // OPTIONALS
            'start_default' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'), // default value for start_date
            'end_default' => date('Y-m-d'), // default value for end_date
            'date_range_options' => [
                'locale' => ['format' => 'DD/MM/YYYY']
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 date_range'
            ],
            ]);
        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/reports/summary-report/main-script'
        ]);
        
            
        $this->crud->setCreateView('custom.create_summary_report');
        // add asterisk for fields that are required in ReportSummaryRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function index()
    {
        return redirect('admin/summary-report/create');
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

    public function borrower_wise(Request $request)
    {
        //dd($request->all());
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_ids = $request->branch_ids;
        if($branch_ids == null){
            $branch_ids = 1;
        }
        if($request->center_id != "Select Center"){
            $center_id = $request->center_id;
            $center = \App\Models\CenterLeader::find($center_id);
        }else{
            $center_id = null;
            $center = null;
        }
        
        if($request->group_id != "Select Group"){
            $group_id = $request->group_id;
            $group = \App\Models\GroupLoan::find($group_id);
        }else{
            $group_id = null;
            $group = null;
        }
        $schedule_loans = [];
        $week = ["","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
        $active_loans = \App\Models\Loan::where('disbursement_status','Activated')
        ->when($branch_ids,function($query) use ($branch_ids){
            return $query->whereIn('branch_id',$branch_ids);
        })
        ->when($center_id,function($query) use ($center_id){
            return $query->where('center_leader_id',$center_id);
        })
        ->when($group_id,function($query) use ($group_id){
            return $query->where('group_loan_id',$group_id);
        })
        ->get()->pluck('id')->toArray();

        
                $dis_loans = \App\Models\LoanCalculate::whereIn('disbursement_id',$active_loans)
                    ->where('payment_status','pending')
                    ->whereDate('date_s', '>=', $start_date)
                    ->whereDate('date_s', '<=', $end_date)
                    ->get();
                // if($dis_loan != Null){
                //     array_push($schedule_loans,$dis_loan);
                // }
                //dd($dis_loans);
        return view('partials.reports.summary-report.borrow_wise',['branch_ids'=>$branch_ids,'center'=>$center,'group'=>$group,'week'=>$week,'schedule_loans'=>$dis_loans,'start_date'=>$start_date,'end_date'=>$end_date]);
    }
    public function center_wise(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_ids = $request->branch_ids;

        if($request->center_id != "Select Center"){
            $center_id = $request->center_id;
            $center = \App\Models\CenterLeader::find($center_id);
        }else{
            $center_id = null;
            $center = null;
        }
        
        if($request->group_id != "Select Group"){
            $group_id = $request->group_id;
            $group = \App\Models\GroupLoan::find($group_id);
        }else{
            $group_id = null;
            $group = null;
        }

        $week_num = "";
        if(date('d') > 0 && date('d') < 8)
        {
            $week_num = "I";
        }
        elseif(date('d') > 7 && date('d') < 15)
        {
            $week_num = "II";
        }
        elseif(date('d') > 14 && date('d') < 22)
        {
            $week_num = "III";
        }
        else
        {
            $week_num = "IV";
        }
        $week = ["","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
        //dd($loans);


        return view('partials.reports.summary-report.center_wise',['branch_ids'=>$branch_ids,'week_num'=>$week_num,'week'=>$week,'center'=>$center,'group'=>$group,'center_id'=>$center_id,'group_id'=>$group_id,'start_date'=>$start_date,'end_date'=>$end_date]);
        
    }
    public function outstanding(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branch_ids = $request->branch_ids;

        if($request->center_id != "Select Center"){
            $center_id = $request->center_id;
            $center = \App\Models\CenterLeader::find($center_id);
        }else{
            $center_id = null;
            $center = null;
        }
        
        if($request->group_id != "Select Group"){
            $group_id = $request->group_id;
            $group = \App\Models\GroupLoan::find($group_id);
        }else{
            $group_id = null;
            $group = null;
        }

        if($request->loan_product_id !=  null){
            $loan_products = $request->loan_product_id;
        }else{
            $loan_products = null;
        }
        
        $loans = \App\Models\Loan::where('disbursement_status','Activated')
        ->whereDate('status_note_date_activated', '>=', $start_date)
        ->whereDate('status_note_date_activated', '<=', $end_date)

        ->when($branch_ids,function($query) use ($branch_ids){
            return $query->whereIn('branch_id',$branch_ids);
        })
        ->when($center_id,function($query) use ($center_id){
            return $query->where('center_leader_id',$center_id);
        })
        ->when($group_id,function($query) use ($group_id){
            return $query->where('group_loan_id',$group_id);
        })
        ->when($loan_products,function($query) use ($loan_products){
            return $query->whereIn('loan_production_id',$loan_products);
        })
        ->get();

        $months = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $month_arr = [];
        $monthArr = array();
        if($loans){
            foreach($loans as $loan){
                $year = explode('-',$loan->status_note_date_activated);
                array_push($month_arr,(int)$year[1]);
            }
            $monthArr = array_unique($month_arr);
        
            return view('partials.reports.summary-report.outstanding',['branch_ids'=>$branch_ids,'start_date'=>$start_date,'end_date'=>$end_date,'loan_products'=>$loan_products,'center_id'=>$center_id,'group_id'=>$group_id,'months'=>$months,'monthArr'=>$monthArr]);
        }
    }
}