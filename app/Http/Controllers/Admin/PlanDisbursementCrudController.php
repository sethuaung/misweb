<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DisbursementPendingRequest as StoreRequest;
use App\Http\Requests\DisbursementPendingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class LoanPendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PlanDisbursementCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\PlanDisbursement');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/plan-disbursements');
        $this->crud->setEntityNameStrings('Plan Disbursement', 'Plan Disbursements');
        $this->crud->enableExportButtons();
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.plan_disbursements_total');
        
        
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */



        // TODO: remove setFromDb() and manually define Fields and Columns
       // $this->crud->setFromDb();

        $this->crud->denyAccess(['update', 'create', 'delete']);
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }
        if(companyReportPart() != 'company.mkt'){
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'branch_id',
                'type' => 'select2_ajax',
                'label'=> 'Branch',
                'placeholder' => 'Pick a branch'
            ],
            url('/api/branch-option'),
            function($value) { // if the filter is active
                $this->crud->addClause('where', getLoanTable().'.branch_id', $value);
            });
        }
        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'loan_application_date',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'loan_application_date', '>=', $dates->from);
                $this->crud->addClause('where', 'loan_application_date', '<=', $dates->to . ' 23:59:59');
            });

        $this->crud->addColumn([
            'name' => 'disbursement_number',
            'label' => 'Loan Number',
        ]);
        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => 'Client ID',
            'type' => 'closure',
            'function' => function ($entry) {
                 $client_id = optional($entry)->client_id;
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
                $client_id = optional($entry)->client_id;
                return optional(\App\Models\Client::find($client_id))->name_other;
    
            }
        ]);
        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'Nrc Number',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = optional($entry)->client_id;
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
            'name' => 'loan_application_date',
            'label' => 'Apply Date',
            'type' => 'date'

        ]);

        $this->crud->addColumn([
            'name' => 'plan_disbursement_date',
            'label' => 'Plan Disbursement Date',
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
            'label' => _t("Loan Amount"), // Table column heading
            'name' => 'loan_amount',
            'type' => 'number'
        ]);


        $this->crud->addColumn([
            'label' => _t("Principle Repay"), // Table column heading
            'name' => 'principle_repayment',
            'type' => 'number'
        ]);
        $this->crud->addColumn([
            'label' => _t("Principle Outstanding"), // Table column heading
            'name' => 'principle_receivable',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'label' => _t("Interest Repay"), // Table column heading
            'name' => 'interest_repayment',
            'type' => 'number'
        ]);
        $this->crud->addColumn([
            'label' => _t("Interest Outstanding"), // Table column heading
            'name' => 'interest_receivable',
            'type' => 'number'
        ]);

        $this->crud->addColumn([
            'label' => _t("Term Value"), // Table column heading
            'name' => 'loan_term_value'
        ]);
        $this->crud->addColumn([
            'label' => _t("Cycle"), // Table column heading
            'name' => 'cycle',
            'type' => 'closure',
            'function' => function ($entry) {
                //$cycle = \App\Models\Loan::where('client_id',$entry->client_id)->count('id');
                $cycle = \App\Models\LoanCycle::getLoanCycle(optional($entry)->client_id,optional($entry)->loan_production_id,optional($entry)->id);
                return $cycle??1;
            }
        ]);


        $this->crud->addColumn([
            'name' => 'disbursement_status',
            'label' => _t('Status'),
            'type' => 'closure',
            'function' => function($entry) {

                if (optional($entry)->disbursement_status=="Pending"){
                    return '<button class="btn btn-warning btn-xs " >'.optional($entry)->disbursement_status.'</button>';
                }
                elseif (optional($entry)->disbursement_status=="Approved"){
                    return '<button class="btn btn-info btn-xs" >'.optional($entry)->disbursement_status.'</button>';
                }
                elseif (optional($entry)->disbursement_status=="Declined"){
                    return '<button class="btn btn-danger btn-xs" >'.optional($entry)->disbursement_status.'</button>';
                }
                elseif (optional($entry)->disbursement_status=="Completed"){
                    return '<button class="btn btn-success btn-xs" >'.optional($entry)->disbursement_status.'</button>';
                }
                elseif (optional($entry)->disbursement_status=="Activated"){
                    return '<button class="btn btn-primary btn-xs" >'.optional($entry)->disbursement_status.'</button>';
                }
                elseif (optional($entry)->disbursement_status=="Canceled"){
                    return '<button class="btn btn-danger btn-xs" >'.optional($entry)->disbursement_status.'</button>';
                }

            }

        ]);

        $this->crud->removeAllButtons();



//        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom1', 'addButtonCustom1', 'beginning');

        // add asterisk for fields that are required in DisbursementPendingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-pending';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access


        /*
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

        */

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
}
