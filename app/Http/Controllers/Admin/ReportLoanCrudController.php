<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountChart;
use App\Models\CenterLeader;
use App\Models\Client;
use App\Models\ReportAccounting;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ReportAccountingRequest as StoreRequest;
use App\Http\Requests\ReportAccountingRequest as UpdateRequest;
use Illuminate\Http\Request;

/**
 * Class ReportAccountingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ReportLoanCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ReportLoan');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report-loan');
        $this->crud->setEntityNameStrings('report-accounting', 'report_accounting');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addField([
            'label' => "Client",
            'type' => "select2_from_ajax_multiple",
            'name' => 'client_id',
            'entity' => 'client',
            'attribute' => "name",
            'model' => Client::class,
            'data_source' => url("api/get-client"),
            'placeholder' => "Select a Client",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => "Branch",
            'type' => "select2_from_ajax_multiple",
            'name' => 'branch_id',
            'entity' => 'branch',
            'attribute' => "title",
            'model' => Client::class,
            'data_source' => url("api/get-branch"),
            'placeholder' => "Select a branch",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);


        $this->crud->addField([
            'label' => "Center Leader",
            'type' => "select2_from_ajax_multiple",
            'name' => 'center_leader_id',
            'entity' => 'center_leader',
            'attribute' => "title",
            'model' => CenterLeader::class,
            'data_source' => url("api/get-center-leader"),
            'placeholder' => "Select a center",
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => _t('Loan Officer Name'),
            'type' => "select2_from_ajax_multiple",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'entity' => 'loan_officer', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
            'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a officer"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

//            'location_group' => 'General',
        ]);




        $this->crud->addField([
            'name' => 'date_date_range', // a unique name for this field
            'start_name' => 'start_date', // the db column that holds the start_date
            'end_name' => 'end_date', // the db column that holds the end_date
            'label' => 'Select Date Range',
            'type' => 'date_range',
            // OPTIONALS
            'start_default' => '2015-03-28', // default value for start_date
            'end_default' => date('Y-m-d'), // default value for end_date
            'date_range_options' => [ // options sent to daterangepicker.js
                //'timePicker' => true,
                'locale' => ['format' => 'DD/MM/YYYY']
//                'locale' => ['format' => 'DD/MM/YYYY HH:mm']
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-8'
            ],
        ]);






        $this->crud->addField([
            'name' => 'custom-ajax-button',
            'type' => 'view',
            'view' => 'partials/reports/loan/main-script'
        ]);

        $this->crud->setCreateView('custom.create_report_loan');


        // add asterisk for fields that are required in ReportAccountingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        //$this->setPermissions();

    }




    public function index()
    {
        return redirect('admin/report-loan/create');
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

    public function clientList(Request $request){


        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $client_id = $request->client_id;
        $center_leader_id = $request->center_leader_id;
        $branch_id = $request->branch_id;
        $loan_officer_id = $request->loan_officer_id;






        $rows = Client::where(function ($q) use ($client_id){
            if($client_id != null){
                if(is_array($client_id)){
                    if(count($client_id)>0){
                        $q->whereIn('id',$client_id);
                    }
                }else{
                    $q->where('id',$client_id);
                }
            }
        })
        ->where(function ($q) use ($center_leader_id){
            if($center_leader_id != null){
                if(is_array($center_leader_id)){
                    if(count($center_leader_id)>0){
                        $q->whereIn('center_leader_id',$center_leader_id);
                    }
                }else{
                    $q->where('center_leader_id',$center_leader_id);
                }
            }
        })
        ->where(function ($q) use ($center_leader_id){
            if($center_leader_id != null){
                if(is_array($center_leader_id)){
                    if(count($center_leader_id)>0){
                        $q->whereIn('center_leader_id',$center_leader_id);
                    }
                }else{
                    $q->where('center_leader_id',$center_leader_id);
                }
            }
        })

            ->get();

        return view('partials.reports.account.account-list',[
            'rows'=>$rows,
            'start_date'=>$start_date,
            'end_date'=>$end_date
        ]);

    }


}
