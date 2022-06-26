<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\DepositPendingRequest as StoreRequest;
use App\Http\Requests\DepositPendingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class DepositPendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class DepositPendingCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\DepositPending');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/depositpending');
        $this->crud->setEntityNameStrings('Pending Deposit', 'Pending Deposit');
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', 'branch_id', session('s_branch_id'));
        }
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
     $this->crud->addFilter([ // simple filter
        'type' => 'text',
        'name' => 'disbursement_number',
        'label'=> 'Loan Number'
    ],
        false,
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'disbursement_number', $value);
        }
    );


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



    $this->crud->addFilter([ // select2_ajax filter
        'name' => 'guarantor_id',
        'type' => 'select2_ajax',
        'label'=> 'Guarantor',
        'placeholder' => 'Pick a Guarantor'
    ],
        url('api/guarantor-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'guarantor_id', $value);
        });


    $this->crud->addFilter([ // select2_ajax filter
        'name' => 'loan_production_id',
        'type' => 'select2_ajax',
        'label'=> 'Loan Product',
        'placeholder' => 'Pick a Loan Product'
    ],
        url('api/loan-product-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'loan_production_id', $value);
        });


        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'loan_officer_id',
            'type' => 'select2_ajax',
            'label'=> 'Loan officer',
            'placeholder' => 'Pick a Loan officer'
        ],
        url('api/loan-officer-option'), // the ajax route
        function($value) { // if the filter is active
            $this->crud->addClause('where', 'loan_officer_id', $value);
        });



        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'from_to',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
            });









        $this->crud->denyAccess(['update', 'create', 'delete']);

        $this->crud->addColumn([
            'name' => 'disbursement_number',
            'label' => 'Loan Number',

        ]);

        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => 'Nrc number',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(Client::find($client_id))->nrc_number;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t("Client "), // Table column heading
            'type' => "select",
            'name' => 'client_id', // the column that contains the ID of that connected entity;
            'entity' => 'client_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);

        $this->crud->addColumn([
            'name' => 'name_other',
            'label' => 'Name other',
            'type' => 'closure',
            'function' => function ($entry) {
                $client_id = $entry->client_id;
                return optional(Client::find($client_id))->name_other;
            }
        ]);

        $this->crud->addColumn([
            'name' => 'status_note_date_approve',
            'label' => 'Approved Date',
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
            'label' => _t("Leader "), // Table column heading
            'type' => "select",
            'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
            'entity' => 'leader_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Loan", // foreign key model
        ]);


        $this->crud->addColumn([
            'label' => _t("Principal Amount "), // Table column heading
            'name' => 'loan_amount',
            'type' => 'number'
        ]);


        $this->crud->addColumn([
            'label' => _t("Interest"), // Table column heading
            'name' => 'interest_rate'
        ]);


        $this->crud->addColumn([
            'label' => _t("Loan term"), // Table column heading
            'name' => 'loan_term'
        ]);


        $this->crud->addColumn([
            'label' => _t("Loan term value"), // Table column heading
            'name' => 'loan_term_value'
        ]);


        // add asterisk for fields that are required in DepositPendingRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');

        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom1', 'addButtonCustom1', 'beginning');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'deposit-pending';
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
