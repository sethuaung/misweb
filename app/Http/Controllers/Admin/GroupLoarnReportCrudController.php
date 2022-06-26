<?php

namespace App\Http\Controllers\Admin;
use App\Models\AccountChart;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\GroupLoan;
use App\Models\GroupLoanDetail;
use App\Models\GroupLoarnReport;
use App\Models\Loan;
use App\Models\LoanDeclined;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GroupLoarnReportRequest as StoreRequest;
use App\Http\Requests\GroupLoarnReportRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class GroupLoarnReportCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GroupLoarnReportCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\GroupLoarnReport');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/group-report');
        $this->crud->setEntityNameStrings('grouploarnreport', 'group_loarn_reports');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
//        $this->crud->addField([
//            'name'=>'',
//            'type'=>'text',
//            'label'=>'Branch Name',
//             'wrapperAttributes' => [
//        'class' => 'form-group col-md-6'
//    ]
//        ]);
        $this->crud->addField([
            'label' => "Branch",
            'type' => "select2_from_ajax_multiple",
            'name' => 'branch_id',
            // 'entity' => 'category',
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
            'name'=>'center_id',
            'type'=>'select2_from_ajax_multiple',
            'label'=>'Center ID',
            'attribute' => "title",
            'model' => CenterLeader::class,
            'data_source' => url("api/get-center-leader"),
            'placeholder' => "Select center",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        $this->crud->addField([
            'name'=>'group_id',
            'type'=>'select2_from_ajax_multiple',
            'label'=>'Group ID',
            'attribute' => "group_code",
            'model' => GroupLoan::class,
            'data_source' => url("api/get-group-loan2"),
            'placeholder' => "Select Group",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ]
        ]);

        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/reports/group_loan/main-script'
        ]);
        $this->crud->setCreateView('custom.create_report_group_loan');



        // add asterisk for fields that are required in GroupLoarnReportRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }
    public function index()
    {
        return redirect('admin/group-report/create');
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
    public function group_loan(Request $request){
        $branch=$request->branch_id;
        $center=$request->center_id;
        $group_id=$request->group_id;

        $grouploan = GroupLoan::selectRaw('group_loans.center_id,count(group_loans.id) as num_group')->groupBy('group_loans.center_id')
            ->join('center_leaders','center_leaders.id','=','group_loans.center_id')
            ->where(function ($q) use ($branch){
                if($branch != null || is_array($branch)){
                   return $q->whereIn('center_leaders.branch_id',$branch);
                }
            })
            ->where(function($q) use ($center){
              if($center != null || is_array($center)){
                  return $q->whereIn('center_leaders.id',$center);
              }

             })
            ->where(function ($q) use ($group_id){
                if ($group_id != null || is_array($group_id)){
                    return $q->whereIn('group_loans.id',$group_id);
                }
            })
            ->get();


        return view('partials.reports.group_loan.group_loan', ['grouploan' => $grouploan]);
    }
    public function group_loan_list(Request $request){
        $branch=$request->branch_id;
        $center=$request->center_id;
        $group_id=$request->group_id;
        $grouploanlist = GroupLoanDetail::selectRaw('group_loan_details.group_loan_id,count(group_loan_details.client_id) as num_client')->groupBy('group_loan_details.group_loan_id')
            ->join('group_loans','group_loans.id','=','group_loan_details.group_loan_id')
            ->where(function ($q) use ($branch){
                if($branch != null || is_array($branch)){
                    return $q->whereIn('group_loans.branch_id',$branch);
                }
            })
            ->where(function ($q) use ($center){
                if ($center != null || is_array($center)){
                    return $q->whereIn('group_loans.center_id',$center);
                }
            })
            ->where(function ($q) use ($group_id){
                if ($group_id != null || is_array($group_id)){
                    return $q->whereIn('group_loan_details.group_loan_id',$group_id);
                }
            })
            ->get(); 

        return view('partials.reports.group_loan.group_loan_list',['grouploanlist'=>$grouploanlist]);
    }
    public function group_loan_detail(Request $request){
        //dd($request);
        $branch=$request->branch_id;
        $center=$request->center_id;
        $group_id=$request->group_id;
        $grouploandetail = GroupLoanDetail::selectRaw('group_loan_details.group_loan_id,count(group_loan_details.client_id) as num_client')->groupBy('group_loan_details.group_loan_id')
          ->join('group_loans','group_loans.id','=','group_loan_details.group_loan_id')
            ->where(function ($q) use ($branch) {
                if ($branch != null || is_array($branch)) {
                    return $q->whereIn('group_loans.branch_id', $branch);
                }
            })
            ->where(function ($q) use ($center){
                if ($center != null || is_array($center)){
                    return $q->whereIn('group_loans.center_id',$center);
                }
            })
            ->where(function ($q) use ($group_id){
                if ($group_id != null || is_array($group_id)){
                    return $q->whereIn('group_loan_details.group_loan_id',$group_id);
                }
            })->get();
            //dd($grouploandetail);
        return view('partials.reports.group_loan.group_loan_detail',['grouploandetail'=>$grouploandetail]);
    }
}
